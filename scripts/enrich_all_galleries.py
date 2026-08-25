import os
import sys
import io
import sqlite3
import re
import urllib.request
import urllib.parse
import json
import time
import csv

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
IMG_DIR = os.path.join(BASE_DIR, 'public', 'images', 'monasteries')
DB_STORAGE_PATH = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE_PATH = os.path.join(BASE_DIR, 'database', 'database.sqlite')
CACHE_DIR = os.path.join(BASE_DIR, 'storage', 'cache_manastiri_rs')

HEADERS = {
    'User-Agent': 'ManastiriSrbijeBot/2.0 (Cultural heritage educational project; contact: info@manastirisrbije.rs) Mozilla/5.0 Chrome/120.0.0.0',
    'Accept': 'application/json,image/*,*/*;q=0.8',
}

BAD_TOKENS = [
    'flag', 'zastava', 'spomenik', 'tvrdjava', 'fortress', 'groblje', 'cemetery',
    'karta', 'map', 'grb', 'coat_of_arms', 'yugoslavia', 'ambox', 'commons-logo',
    'panorama_grada', 'gradska_galerija', 'narodnog_muzeja', 'suva_planina',
    'pcinja_river', 'grad_uzice', 'nuvola', '.webm', '.svg', '.pdf', '.ogg',
    'arhimandrit', 'patrijarh', 'monah', 'svestenik', 'proslava', 'german', 'marzik',
    '1912', '1945', '1986', 'crno', 'black', 'white', 'signature', 'potpis', 'pecat',
    'stamp', 'icon', 'bullet', 'logo', 'cropped-image'
]

def download_file(url, dest_path):
    if not url:
        return False
    try:
        req = urllib.request.Request(url, headers=HEADERS)
        with urllib.request.urlopen(req, timeout=12) as response:
            if response.status == 200:
                data = response.read()
                if len(data) > 8192:  # At least 8KB
                    with open(dest_path, 'wb') as f:
                        f.write(data)
                    return True
    except Exception as e:
        pass
    return False

def get_wiki_images_for_monastery(name, slug):
    # Try searching Wikipedia for images
    search_queries = [name, f"Манастир {name.replace('Manastir', '').strip()}", f"Crkva {name.replace('Manastir', '').strip()}"]
    
    found_image_urls = []
    seen = set()

    for q in search_queries[:2]:
        try:
            # 1. Search page
            sr_url = f"https://sr.wikipedia.org/w/api.php?action=query&list=search&srsearch={urllib.parse.quote(q)}&format=json&utf8=1"
            req = urllib.request.Request(sr_url, headers=HEADERS)
            data = json.loads(urllib.request.urlopen(req, timeout=8).read().decode('utf-8'))
            results = data.get('query', {}).get('search', [])
            if not results:
                continue

            page_title = results[0]['title']
            
            # 2. Get images for page
            img_url = f"https://sr.wikipedia.org/w/api.php?action=query&titles={urllib.parse.quote(page_title)}&generator=images&gimlimit=30&prop=imageinfo&iiprop=url|size|mime&format=json&utf8=1"
            req2 = urllib.request.Request(img_url, headers=HEADERS)
            img_data = json.loads(urllib.request.urlopen(req2, timeout=8).read().decode('utf-8'))
            
            pages = img_data.get('query', {}).get('pages', {})
            for pid, pdata in pages.items():
                title = pdata.get('title', '').lower()
                infos = pdata.get('imageinfo', [])
                if not infos:
                    continue
                info = infos[0]
                u = info.get('url', '')
                mime = info.get('mime', '')
                w = info.get('width', 0)
                h = info.get('height', 0)

                if mime not in ['image/jpeg', 'image/png', 'image/webp']:
                    continue
                if w < 500 and h < 500:
                    continue
                if any(bt in title or bt in u.lower() for bt in BAD_TOKENS):
                    continue

                # Prefer thumbnail / scaled image for fast loading (1280px)
                if info.get('responsiveUrls'):
                    u = info['responsiveUrls'].get('1.5') or info['responsiveUrls'].get('2') or u
                elif w > 1920:
                    # use thumburl if available
                    u = info.get('thumburl', u)

                if u and u not in seen:
                    seen.add(u)
                    found_image_urls.append(u)
                    if len(found_image_urls) >= 4:
                        break

            if found_image_urls:
                break
        except Exception as e:
            continue

    return found_image_urls

def get_manastiri_rs_images(slug):
    cache_files = [f for f in os.listdir(CACHE_DIR) if f.endswith('.html')]
    matched_file = None
    for cf in cache_files:
        if f"manastir-{slug}_" in cf or f"manastir-{slug.replace('-', '')}_" in cf:
            matched_file = os.path.join(CACHE_DIR, cf)
            break
    if not matched_file:
        for cf in cache_files:
            if slug in cf:
                matched_file = os.path.join(CACHE_DIR, cf)
                break
    
    if not matched_file:
        return []

    html = open(matched_file, 'r', encoding='utf-8').read()
    raw_imgs = re.findall(r'https?://manastiri\.rs/wp-content/uploads/[^\s"\'<>]+\.(?:jpg|jpeg|png|webp)', html, re.IGNORECASE)
    clean = []
    for im in raw_imgs:
        c = re.sub(r'-\d+x\d+(\.\w+)$', r'\1', im)
        low = c.lower()
        if not any(bt in low for bt in BAD_TOKENS):
            if c not in clean:
                clean.append(c)
    return clean

def generate_caption(name, index):
    m_clean = name if name.startswith('Manastir') else f"Manastir {name}"
    if index == 1:
        return f"Glavni hram – {m_clean}"
    elif index == 2:
        return f"Pogled na manastirski kompleks i konak – {m_clean}"
    elif index == 3:
        return f"Srednjovekovni živopis i arhitektura – {m_clean}"
    else:
        return f"Manastirska porta i hram – {m_clean}"

def enrich_all_galleries():
    print("=== POKRETANJE OBOGAĆIVANJA I LOKALIZACIJE GALERIJA ZA SVE MANASTIRE ===")
    os.makedirs(IMG_DIR, exist_ok=True)

    conn = sqlite3.connect(DB_STORAGE_PATH)
    cur = conn.cursor()

    cur.execute("SELECT id, name, slug, image_url FROM monasteries ORDER BY id")
    monasteries = cur.fetchall()

    print(f"Ukupno manastira u bazi: {len(monasteries)}")

    total_enriched = 0
    now_multi_count = 0

    for idx, (m_id, name, slug, main_img) in enumerate(monasteries, 1):
        # Proveri postojeće slike u bazi
        cur.execute("SELECT url, caption FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order, id", (m_id,))
        existing_imgs = cur.fetchall()

        curated = []
        seen_files = set()

        # 1. Osnovna lokalna slika
        local_main_rel = f"images/monasteries/{slug}.jpg"
        local_main_full = os.path.join(IMG_DIR, f"{slug}.jpg")
        if os.path.exists(local_main_full) and os.path.getsize(local_main_full) > 1024:
            curated.append({
                'url': local_main_rel,
                'caption': generate_caption(name, 1)
            })
            seen_files.add(local_main_rel)

        # 2. Sačuvaj već postojeće dobre lokalne galerijske slike
        for g_url, g_cap in existing_imgs:
            if g_url not in seen_files and g_url.startswith('images/monasteries/'):
                check_fp = os.path.join(BASE_DIR, 'public', g_url)
                if os.path.exists(check_fp) and os.path.getsize(check_fp) > 1024:
                    seen_files.add(g_url)
                    curated.append({
                        'url': g_url,
                        'caption': g_cap or generate_caption(name, len(curated) + 1)
                    })

        # 3. Ako ima manje od 2-3 slike, potraži na manastiri.rs
        if len(curated) < 3:
            m_rs_imgs = get_manastiri_rs_images(slug)
            for m_img in m_rs_imgs:
                if len(curated) >= 3:
                    break
                target_rel = f"images/monasteries/{slug}_gal_{len(curated)}.jpg"
                target_full = os.path.join(IMG_DIR, f"{slug}_gal_{len(curated)}.jpg")
                if target_rel not in seen_files:
                    if not os.path.exists(target_full) or os.path.getsize(target_full) < 1024:
                        ok = download_file(m_img, target_full)
                    else:
                        ok = True
                    if ok:
                        seen_files.add(target_rel)
                        curated.append({
                            'url': target_rel,
                            'caption': generate_caption(name, len(curated) + 1)
                        })

        # 4. Ako i dalje ima manje od 2-3 slike, potraži na Wikipediji
        if len(curated) < 2:
            wiki_imgs = get_wiki_images_for_monastery(name, slug)
            for w_img in wiki_imgs:
                if len(curated) >= 3:
                    break
                target_rel = f"images/monasteries/{slug}_gal_{len(curated)}.jpg"
                target_full = os.path.join(IMG_DIR, f"{slug}_gal_{len(curated)}.jpg")
                if target_rel not in seen_files:
                    ok = download_file(w_img, target_full)
                    if ok:
                        seen_files.add(target_rel)
                        curated.append({
                            'url': target_rel,
                            'caption': generate_caption(name, len(curated) + 1)
                        })

        # Ograniči na 3 slike
        curated = curated[:3]

        # Ažuriraj bazu
        cur.execute("DELETE FROM monastery_images WHERE monastery_id = ?", (m_id,))
        for s_idx, item in enumerate(curated, 1):
            cur.execute(
                "INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))",
                (m_id, item['url'], item['caption'], s_idx)
            )

        if curated:
            cur.execute("UPDATE monasteries SET image_url = ? WHERE id = ?", (curated[0]['url'], m_id))

        if len(curated) > 1:
            now_multi_count += 1

        if idx % 25 == 0 or idx == len(monasteries):
            print(f"Obrađeno {idx}/{len(monasteries)} manastira... (Trenutno sa galerijom: {now_multi_count})")
            conn.commit()

    conn.commit()
    conn.close()

    print(f"\n✓ Završeno! Manastira sa 2-3 slike: {now_multi_count} / {len(monasteries)}")

    # Sinhronizacija druge baze i CSV fajlova
    sync_all()

def sync_all():
    if os.path.exists(DB_DATABASE_PATH):
        conn_src = sqlite3.connect(DB_STORAGE_PATH)
        conn_dst = sqlite3.connect(DB_DATABASE_PATH)
        cur_src = conn_src.cursor()
        cur_dst = conn_dst.cursor()

        cur_dst.execute("DELETE FROM monastery_images")
        cur_src.execute("SELECT id, monastery_id, url, caption, sort_order, created_at, updated_at FROM monastery_images")
        cur_dst.executemany("INSERT INTO monastery_images VALUES (?, ?, ?, ?, ?, ?, ?)", cur_src.fetchall())

        cur_src.execute("SELECT id, image_url FROM monasteries")
        for m_id, img_url in cur_src.fetchall():
            cur_dst.execute("UPDATE monasteries SET image_url = ? WHERE id = ?", (img_url, m_id))

        conn_dst.commit()
        conn_dst.close()
        conn_src.close()
        print("✓ Baza database/database.sqlite sinhronizovana!")

    # CSV
    conn = sqlite3.connect(DB_STORAGE_PATH)
    c = conn.cursor()
    c.execute('SELECT * FROM monasteries')
    cols = [d[0] for d in c.description]
    rows = c.fetchall()

    for out_path in ['storage/app/import/monasteries.csv', 'database/seeders/data/monasteries.csv']:
        if os.path.exists(os.path.dirname(out_path)):
            with open(out_path, 'w', encoding='utf-8-sig', newline='') as f:
                writer = csv.writer(f, delimiter=';')
                writer.writerow(cols)
                for r in rows:
                    clean_r = [str(x).replace(';', ',') if x is not None else '' for x in r]
                    writer.writerow(clean_r)
    conn.close()
    print("✓ CSV fajlovi sinhronizovani!")

if __name__ == '__main__':
    enrich_all_galleries()
