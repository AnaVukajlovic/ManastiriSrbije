import os
import sys
import io
import sqlite3
import re
import urllib.parse

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB_STORAGE_PATH = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE_PATH = os.path.join(BASE_DIR, 'database', 'database.sqlite')

def normalize_text(t):
    if not t:
        return ""
    t = t.lower()
    t = t.replace('đ', 'dj').replace('ž', 'z').replace('č', 'c').replace('ć', 'c').replace('š', 's')
    t = re.sub(r'[^a-z0-9]+', ' ', t)
    return t.strip()

def check_image_relevance(m_name, m_slug, url, caption):
    u_dec = urllib.parse.unquote(url).lower()
    u_norm = normalize_text(u_dec)
    m_norm = normalize_text(m_name.replace('Manastir', '').strip())
    slug_norm = normalize_text(m_slug)

    # 1. Obvious non-monastery objects
    bad_patterns = [
        'gradska_galerija', 'narodnog_muzeja', 'suva_planina', 'pcinja_river_valley',
        'spomenik', 'tvrdjava', 'fortress', 'groblje', 'cemetery', 'flag', 'zastava',
        'karta', 'mapa', 'map', 'grb', 'coat_of_arms', 'yugoslavia', 'ambox', 'commons-logo',
        'panorama_grada', 'grad_uzice', 'muzej', 'most_', 'vidikovac'
    ]
    for bp in bad_patterns:
        if bp in u_norm:
            return False, f"Neodgovarajući objekat: {bp}"

    # 2. If it's from manastiri.rs or local images
    if 'manastiri.rs/wp-content/uploads' in u_dec:
        return True, "Validna slika sa manastiri.rs"
    if url.startswith('images/monasteries/'):
        return True, "Validna lokalna fotografija"

    # 3. If from Wikipedia/Wikimedia:
    # Check if the filename contains the monastery name / slug core or related church/fresco name
    core_parts = [p for p in m_norm.split() if len(p) > 2 and p not in ['sveti', 'svete', 'sveta', 'crkva']]
    slug_parts = [p for p in slug_norm.split() if len(p) > 2 and p not in ['sveti', 'svete', 'sveta', 'crkva']]

    has_name_match = any(part in u_norm for part in core_parts + slug_parts)
    has_sacral_term = any(t in u_norm for t in ['manastir', 'monastery', 'church', 'crkva', 'fresk', 'fresco', 'ikon', 'icon', 'konak', 'zvonik', 'porta', 'oltar', 'ktitor'])

    if has_name_match or (has_sacral_term and len(core_parts) == 0):
        return True, "Validna fotografija manastira"

    # If it's completely generic without name match
    return False, f"Nema potvrde da pripada manastiru '{m_name}' (URL: {u_dec[-40:]})"

def generate_accurate_caption(m_name, url):
    u_dec = urllib.parse.unquote(url)
    filename = os.path.basename(u_dec)
    filename = re.sub(r'^\d+px-', '', filename)
    filename = re.sub(r'\.(?:jpg|jpeg|png|webp).*$', '', filename, flags=re.I)
    filename = re.sub(r'[-_]', ' ', filename).strip()
    
    low = filename.lower()
    m_clean = m_name if m_name.startswith('Manastir') else f"Manastir {m_name}"
    
    if any(k in low for k in ['ktitor', 'milutin', 'nemanja', 'stefan', 'lazar', 'vladar', 'kralj', 'car ']):
        return f"Ktitorski portret i prikaz zadužbinara – {m_clean}"
    elif any(k in low for k in ['fresk', 'fresco', 'pantocrator', 'hrist', 'bogorodic', 'sava', 'simeon', 'svetit', 'zivopis', 'raspece', 'tajna vecera', 'uspenje']):
        return f"Srednjovekovni živopis i freske – {m_clean}"
    elif any(k in low for k in ['ikonostas', 'ikona', 'icon', 'oltar', 'prestone']):
        return f"Ikonostas i oltarski prostor – {m_clean}"
    elif any(k in low for k in ['enterijer', 'interior', 'unutra', 'naos', 'priprata']):
        return f"Unutrašnjost manastirskog hrama – {m_clean}"
    elif any(k in low for k in ['zvonik', 'porta', 'konak', 'dvoriste', 'kompleks', 'kapija']):
        return f"Manastirski kompleks, konak i porta – {m_clean}"
    elif any(k in low for k in ['panorama', 'aerial', 'pogled', 'dron']):
        return f"Panoramski pogled na {m_clean}"
    else:
        return f"Glavni hram – {m_clean}"

def clean_database(db_path):
    if not os.path.exists(db_path):
        return
    print(f"\n--- PROČIŠĆAVANJE SLIKA U BAZI: {db_path} ---")
    conn = sqlite3.connect(db_path)
    cur = conn.cursor()

    cur.execute("""
        SELECT m.id, m.name, m.slug, i.id, i.url, i.caption 
        FROM monastery_images i 
        JOIN monasteries m ON i.monastery_id = m.id
    """)
    rows = cur.fetchall()

    removed = 0
    updated_caps = 0

    for m_id, m_name, m_slug, img_id, url, cur_caption in rows:
        is_valid, reason = check_image_relevance(m_name, m_slug, url, caption=cur_caption)
        if not is_valid:
            cur.execute("DELETE FROM monastery_images WHERE id = ?", (img_id,))
            removed += 1
            print(f"  [UKLONJENO] {m_name}: {reason} -> {url[:60]}")
        else:
            # Generate accurate caption
            new_cap = generate_accurate_caption(m_name, url)
            cur.execute("UPDATE monastery_images SET caption = ? WHERE id = ?", (new_cap, img_id))
            updated_caps += 1

    # Remove duplicates
    cur.execute("""
        DELETE FROM monastery_images 
        WHERE id NOT IN (
            SELECT MIN(id) 
            FROM monastery_images 
            GROUP BY monastery_id, url
        )
    """)

    # Re-order sort_order for remaining images per monastery
    cur.execute("SELECT DISTINCT monastery_id FROM monastery_images")
    m_ids = [r[0] for r in cur.fetchall()]
    for mid in m_ids:
        cur.execute("SELECT id FROM monastery_images WHERE monastery_id = ? ORDER BY id", (mid,))
        img_ids = [r[0] for r in cur.fetchall()]
        for s_idx, iid in enumerate(img_ids, 1):
            cur.execute("UPDATE monastery_images SET sort_order = ? WHERE id = ?", (s_idx, iid))

    conn.commit()
    conn.close()
    print(f"✓ Uklonjeno {removed} nepotrebnih/netačnih slika. Ažurirano {updated_caps} preciznih opisa.")

clean_database(DB_STORAGE_PATH)
clean_database(DB_DATABASE_PATH)
print("\n✓ KOMPLETNO ZAVRŠENA ČIŠĆENJE SLIKA I AŽURIRANJE OPISA!")
