import os
import sys
import io
import sqlite3
import re
import urllib.parse
from bs4 import BeautifulSoup

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB_STORAGE_PATH = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE_PATH = os.path.join(BASE_DIR, 'database', 'database.sqlite')
CACHE_DIR = os.path.join(BASE_DIR, 'storage', 'cache_manastiri_rs')

def normalize_text(t):
    if not t:
        return ""
    t = t.lower()
    t = t.replace('đ', 'dj').replace('ž', 'z').replace('č', 'c').replace('ć', 'c').replace('š', 's')
    t = re.sub(r'[^a-z0-9]+', ' ', t)
    return t.strip()

def is_verified_monastery_image(m_name, m_slug, url):
    u_dec = urllib.parse.unquote(url).lower()
    u_norm = normalize_text(u_dec)
    m_norm = normalize_text(m_name.replace('Manastir', '').strip())
    slug_norm = normalize_text(m_slug)

    # 1. Obvious non-monastery / unrelated keywords
    bad_tokens = [
        'flag', 'zastava', 'spomenik', 'tvrdjava', 'fortress', 'groblje', 'cemetery',
        'karta', 'map', 'grb', 'coat_of_arms', 'yugoslavia', 'ambox', 'commons-logo',
        'panorama_grada', 'gradska_galerija', 'narodnog_muzeja', 'suva_planina',
        'pcinja_river_valley', 'grad_uzice', 'nuvola', '.webm', '.svg'
    ]
    for bt in bad_tokens:
        if bt in u_norm:
            return False

    # 2. Local verified photo
    if url.startswith('images/monasteries/'):
        return True

    # 3. Manastiri.rs uploaded photo from cache for this monastery
    if 'manastiri.rs/wp-content/uploads' in u_dec:
        return True

    # 4. Wikipedia photo - must strictly match monastery name or slug
    core_parts = [p for p in m_norm.split() if len(p) > 2 and p not in ['sveti', 'svete', 'sveta', 'crkva', 'resava']]
    slug_parts = [p for p in slug_norm.split() if len(p) > 2 and p not in ['sveti', 'svete', 'sveta', 'crkva']]

    if any(part in u_norm for part in core_parts + slug_parts):
        return True

    return False

def generate_accurate_caption(m_name, url, index):
    u_dec = urllib.parse.unquote(url)
    filename = os.path.basename(u_dec)
    filename = re.sub(r'^\d+px-', '', filename)
    filename = re.sub(r'\.(?:jpg|jpeg|png|webp).*$', '', filename, flags=re.I)
    filename = re.sub(r'[-_]', ' ', filename).strip()
    
    low = filename.lower()
    m_clean = m_name if m_name.startswith('Manastir') else f"Manastir {m_name}"
    
    if any(k in low for k in ['ktitor', 'milutin', 'nemanja', 'stefan', 'lazar', 'vladar', 'kralj', 'car ']):
        return f"Ktitorski prikaz i zadužbinari – {m_clean}"
    elif any(k in low for k in ['fresk', 'fresco', 'pantocrator', 'hrist', 'bogorodic', 'sava', 'simeon', 'svetit', 'zivopis', 'raspece', 'varvara']):
        return f"Srednjovekovni živopis i freske – {m_clean}"
    elif any(k in low for k in ['ikonostas', 'ikona', 'icon', 'oltar']):
        return f"Ikonostas i oltarski prostor – {m_clean}"
    elif any(k in low for k in ['enterijer', 'interior', 'unutra']):
        return f"Unutrašnjost manastirskog hrama – {m_clean}"
    elif any(k in low for k in ['zvonik', 'porta', 'konak', 'kompleks']):
        return f"Manastirski kompleks i konak – {m_clean}"
    elif index == 1:
        return f"Glavni hram – {m_clean}"
    elif index == 2:
        return f"Pogled na manastirski kompleks – {m_clean}"
    else:
        return f"Arhitektura hrama i manastirska porta – {m_clean}"

def clean_database_images(db_path):
    if not os.path.exists(db_path):
        return
    print(f"\n--- PROVERA I OGRANIČAVANJE SLIKA ZA BAZU: {db_path} ---")
    conn = sqlite3.connect(db_path)
    cur = conn.cursor()

    cur.execute("SELECT id, name, slug, image_url FROM monasteries")
    monasteries = cur.fetchall()

    total_images = 0
    single_img_count = 0
    two_three_count = 0

    for m_id, name, slug, main_img_url in monasteries:
        cur.execute("SELECT id, url, caption FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order, id", (m_id,))
        raw_images = cur.fetchall()

        valid_images = []
        seen_urls = set()

        # Always check local image
        local_rel = f"images/monasteries/{slug}.jpg"
        local_full = os.path.join(BASE_DIR, 'public', local_rel)
        if os.path.exists(local_full):
            seen_urls.add(local_rel)
            valid_images.append({
                'url': local_rel,
                'caption': f"Glavni hram – {name}"
            })

        for img_id, url, cap in raw_images:
            if url not in seen_urls and is_verified_monastery_image(name, slug, url):
                seen_urls.add(url)
                valid_images.append({
                    'url': url,
                    'caption': cap
                })

        # Limit to max 3 verified photos (or 2, or 1 if only 1 exists)
        final_images = valid_images[:3]

        # Repopulate monastery_images cleanly
        cur.execute("DELETE FROM monastery_images WHERE monastery_id = ?", (m_id,))
        for idx, im in enumerate(final_images, 1):
            cap = generate_accurate_caption(name, im['url'], idx)
            cur.execute(
                "INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))",
                (m_id, im['url'], cap, idx)
            )

        # Set primary image_url on monastery
        if final_images:
            cur.execute("UPDATE monasteries SET image_url = ? WHERE id = ?", (final_images[0]['url'], m_id))

        total_images += len(final_images)
        if len(final_images) <= 1:
            single_img_count += 1
        else:
            two_three_count += 1

    conn.commit()
    conn.close()
    print(f"✓ Završeno! Ukupno slika u bazi: {total_images}")
    print(f"  Manastiri sa 1 slikom: {single_img_count}")
    print(f"  Manastiri sa 2–3 proverene slike: {two_three_count}")

def main():
    clean_database_images(DB_STORAGE_PATH)
    clean_database_images(DB_DATABASE_PATH)
    print("\n✓ KOMPLETNO ZAVRŠENO!")

if __name__ == '__main__':
    main()
