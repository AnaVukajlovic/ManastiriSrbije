import os
import sys
import io
import re
import json
import sqlite3
import time
import urllib.request
import urllib.parse
from bs4 import BeautifulSoup

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
CACHE_DIR = os.path.join(BASE_DIR, 'storage', 'cache_manastiri_rs')
DB_STORAGE_PATH = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE_PATH = os.path.join(BASE_DIR, 'database', 'database.sqlite')

HEADERS = {
    'User-Agent': 'PravoslavniSvetionik/1.0 (https://github.com/manastiri-srbije; kontakt@svetionik.org.rs)'
}

def normalize_slug(s):
    if not s:
        return ''
    s = s.lower().strip()
    s = s.replace('đ', 'dj').replace('ž', 'z').replace('č', 'c').replace('ć', 'c').replace('š', 's')
    s = re.sub(r'[^a-z0-9]+', '-', s)
    return s.strip('-')

def clean_caption_from_filename(filename, monastery_name):
    name = re.sub(r'^(?:Датотека|File):', '', filename)
    name = re.sub(r'\.(?:jpg|jpeg|png|webp)$', '', name, flags=re.I)
    name = re.sub(r'[-_]', ' ', name)
    name = re.sub(r'\s+', ' ', name).strip()
    
    low = name.lower()
    if any(k in low for k in ['ktitor', 'milutin', 'nemanja', 'stefan', 'lazar', 'vladar', 'kralj', 'car ']):
        return f"Ktitorski prikaz i zadužbinari – {monastery_name}"
    elif any(k in low for k in ['fresk', 'fresco', 'pantocrator', 'hrist', 'bogorodic', 'sava', 'simeon', 'svetit']):
        return f"Srednjovekovni živopis i freske – {monastery_name}"
    elif any(k in low for k in ['ikonostas', 'ikona', 'icon', 'oltar']):
        return f"Ikonostas i oltarski prostor – {monastery_name}"
    elif any(k in low for k in ['enterijer', 'interior', 'unutra']):
        return f"Unutrašnjost manastirskog hrama – {monastery_name}"
    elif any(k in low for k in ['zvonik', 'porta', 'konak', 'panorama', 'aerial', 'kompleks']):
        return f"Manastirski kompleks i porta – {monastery_name}"
    else:
        return f"Pogled na manastirski hram – {monastery_name}"

def get_images_from_cache(html_content, monastery_name):
    imgs = set(re.findall(r'https?://manastiri\.rs/wp-content/uploads/[^\s"\'<>]+\.(?:jpg|jpeg|png|webp)', html_content, re.IGNORECASE))
    valid = []
    for img in imgs:
        clean_img = re.sub(r'-\d+x\d+(\.\w+)$', r'\1', img)
        low = clean_img.lower()
        if not any(x in low for x in ['logo', 'avatar', 'icon', 'cropped', 'favicon', 'default', 'placeholder']):
            if not any(x['url'] == clean_img for x in valid):
                valid.append({
                    'url': clean_img,
                    'caption': f"Fotografija svetinje – {monastery_name}"
                })
    return valid

def fetch_wiki_images_for_monastery(monastery_name):
    # Search for page
    search_query = monastery_name
    best_wiki_title = None
    try:
        surl = f"https://sr.wikipedia.org/w/api.php?action=query&list=search&srsearch={urllib.parse.quote(search_query)}&format=json"
        req = urllib.request.Request(surl, headers=HEADERS)
        sdata = json.loads(urllib.request.urlopen(req, timeout=4).read().decode('utf-8'))
        sres = sdata.get('query', {}).get('search', [])
        if sres:
            best_wiki_title = sres[0]['title']
    except Exception:
        pass

    if not best_wiki_title:
        best_wiki_title = f"Манастир {monastery_name.replace('Manastir ', '')}"

    images = []
    try:
        purl = f"https://sr.wikipedia.org/w/api.php?action=query&titles={urllib.parse.quote(best_wiki_title)}&prop=pageimages|images&pithumbsize=1280&imlimit=25&format=json"
        req = urllib.request.Request(purl, headers=HEADERS)
        pdata = json.loads(urllib.request.urlopen(req, timeout=5).read().decode('utf-8'))
        pages = pdata.get('query', {}).get('pages', {})
        
        file_titles = []
        for pid, p in pages.items():
            if 'thumbnail' in p:
                main_thumb = p['thumbnail']['source']
                images.append({
                    'url': main_thumb,
                    'caption': f"Glavni hram – {monastery_name}"
                })
            raw_files = [im['title'] for im in p.get('images', [])]
            for rf in raw_files:
                low = rf.lower()
                if any(ext in low for ext in ['.jpg', '.jpeg', '.png', '.webp']):
                    if not any(x in low for x in ['icon', 'logo', 'flag', 'coat_of_arms', 'symbol', 'stub', 'question', 'portal', 'pd-icon', 'red_pog', 'commons-logo', 'ambox', 'edit-ltr']):
                        file_titles.append(rf)
                        
        if file_titles:
            file_param = '|'.join(file_titles[:6])
            furl = f"https://sr.wikipedia.org/w/api.php?action=query&titles={urllib.parse.quote(file_param)}&prop=imageinfo&iiprop=url&format=json"
            freq = urllib.request.Request(furl, headers=HEADERS)
            fdata = json.loads(urllib.request.urlopen(freq, timeout=5).read().decode('utf-8'))
            for fpid, fp in fdata.get('query', {}).get('pages', {}).items():
                ii = fp.get('imageinfo', [])
                if ii:
                    img_url = ii[0].get('url', '')
                    caption = clean_caption_from_filename(fp.get('title', ''), monastery_name)
                    if img_url and not any(x['url'] == img_url for x in images):
                        images.append({
                            'url': img_url,
                            'caption': caption
                        })
    except Exception:
        pass
        
    return images

def populate_database_images(db_path, cached_files, file_by_slug):
    if not os.path.exists(db_path):
        return
    print(f"\n--- POPUNJAVANJE SLIKA ZA BAZU: {db_path} ---")
    conn = sqlite3.connect(db_path)
    cursor = conn.cursor()

    cursor.execute("SELECT id, slug, name, image_url FROM monasteries")
    monasteries = cursor.fetchall()
    print(f"Ukupno manastira u bazi: {len(monasteries)}")

    total_images_added = 0

    for i, (m_id, slug, name, cur_img_url) in enumerate(monasteries, 1):
        s_norm = normalize_slug(slug)
        n_norm = normalize_slug(name.replace('Manastir', '').strip())

        # Check existing images in monastery_images
        cursor.execute("SELECT url FROM monastery_images WHERE monastery_id = ?", (m_id,))
        existing_urls = set(r[0] for r in cursor.fetchall())

        collected_images = []

        # 1. Local image if exists
        local_rel = f"images/monasteries/{slug}.jpg"
        local_full = os.path.join(BASE_DIR, 'public', local_rel)
        if os.path.exists(local_full):
            collected_images.append({
                'url': local_rel,
                'caption': f"Glavni hram – {name}"
            })

        # 2. From cached manastiri.rs
        matched_file = None
        if s_norm in file_by_slug:
            matched_file = file_by_slug[s_norm]
        elif n_norm in file_by_slug:
            matched_file = file_by_slug[n_norm]

        if matched_file:
            try:
                with open(os.path.join(CACHE_DIR, matched_file), 'r', encoding='utf-8') as f:
                    cache_imgs = get_images_from_cache(f.read(), name)
                    for ci in cache_imgs:
                        if not any(x['url'] == ci['url'] for x in collected_images):
                            collected_images.append(ci)
            except Exception:
                pass

        # 3. From Wikipedia if we have fewer than 3 images
        if len(collected_images) < 3:
            wiki_imgs = fetch_wiki_images_for_monastery(name)
            for wi in wiki_imgs:
                if not any(x['url'] == wi['url'] for x in collected_images):
                    collected_images.append(wi)

        # 4. Insert new images into monastery_images
        sort_order = len(existing_urls) + 1
        new_added = 0
        for img in collected_images:
            u = img['url']
            cap = img['caption']
            if u not in existing_urls:
                cursor.execute(
                    "INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))",
                    (m_id, u, cap, sort_order)
                )
                existing_urls.add(u)
                sort_order += 1
                new_added += 1

        # 5. Ensure image_url on monastery is clean and set
        if collected_images:
            first_url = collected_images[0]['url']
            if not cur_img_url or 'OPŠTI' in cur_img_url or 'placeholder' in cur_img_url:
                cursor.execute("UPDATE monasteries SET image_url = ? WHERE id = ?", (first_url, m_id))

        total_images_added += new_added
        if i % 30 == 0 or new_added > 0:
            print(f"  [{i}/{len(monasteries)}] {name} ({slug}): dodato {new_added} slika (ukupno u galeriji: {len(existing_urls)})", flush=True)

    conn.commit()
    conn.close()
    print(f"✓ Završeno za {db_path}! Ukupno dodato {total_images_added} novih slika u bazu.")

def main():
    print("=== SINHRONIZACIJA JAVNIH SLIKA I GALERIJA ZA SVE MANASTIRE ===")

    cached_files = [f for f in os.listdir(CACHE_DIR) if f.endswith('.html')]
    file_by_slug = {}
    for cf in cached_files:
        m = re.search(r'manastir[-_]([a-zA-Z0-9_-]+)_\.html', cf)
        if m:
            clean_s = normalize_slug(m.group(1))
            file_by_slug[clean_s] = cf

    for db_p in [DB_STORAGE_PATH, DB_DATABASE_PATH]:
        populate_database_images(db_p, cached_files, file_by_slug)

    print("\n✓ KOMPLETNO ZAVRŠENO!")

if __name__ == '__main__':
    main()
