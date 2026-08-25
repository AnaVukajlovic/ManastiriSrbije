"""
Final batch: download and insert newly found monastery images.
Only verified Wikimedia Commons files. No captions.
"""
import os, sys, io, sqlite3, urllib.request, urllib.parse, json, time

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
IMG_DIR = os.path.join(BASE_DIR, 'public', 'images', 'monasteries')
DB_STORAGE = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE = os.path.join(BASE_DIR, 'database', 'database.sqlite')

HEADERS = {'User-Agent': 'ManastiriSrbijeResearchBot/1.0 python-urllib'}

os.makedirs(IMG_DIR, exist_ok=True)

def api_get_url(file_title):
    api = 'https://commons.wikimedia.org/w/api.php?action=query&titles=' + urllib.parse.quote(file_title) + '&prop=imageinfo&iiprop=url|size|mime&format=json'
    for attempt in range(3):
        time.sleep(2.0 if attempt == 0 else 6.0)
        try:
            req = urllib.request.Request(api, headers=HEADERS)
            with urllib.request.urlopen(req, timeout=15) as r:
                data = json.loads(r.read().decode('utf-8'))
                for pid, pd in data.get('query', {}).get('pages', {}).items():
                    if int(pid) > 0:
                        infos = pd.get('imageinfo', [])
                        if infos and 'image' in infos[0].get('mime', ''):
                            return infos[0]['url'].split('?')[0]
        except Exception as e:
            if '429' in str(e):
                print(f"    429 – čekam 12s...")
                time.sleep(12)
            else:
                print(f"    API err: {e}")
    return None

def download_file(url, dest):
    if os.path.exists(dest) and os.path.getsize(dest) > 50000:
        print(f"    ↷ {os.path.basename(dest)}")
        return True
    if not url:
        return False
    time.sleep(2.0)
    try:
        req = urllib.request.Request(url, headers=HEADERS)
        with urllib.request.urlopen(req, timeout=20) as r:
            if r.status == 200:
                data = r.read()
                if len(data) > 50000:
                    with open(dest, 'wb') as f:
                        f.write(data)
                    print(f"    ✓ {os.path.basename(dest)} ({len(data)//1024}KB)")
                    return True
                else:
                    print(f"    ✗ premalo ({len(data)//1024}KB)")
    except Exception as e:
        print(f"    ✗ {e}")
    return False

# NEW images to add – verified from Wikimedia Commons
NEW_IMAGES = {
    # Banatska
    'svete-melanije': [
        'File:Manastir Svete Melanije.jpg',
    ],
    # Braničevska – newly found
    'dobres': [
        'File:Wiki.Biseri III Dobreš Monastery 144.jpg',
    ],
    'izvor': [
        'File:Sveta Petka u Izvoru1.jpg',
    ],
    'zdrelo': [
        'File:Wili.Zaleđe III Holy Trinity Monastery (Ždrelo) 1538 13.jpg',
        'File:Wili.Zaleđe III Holy Trinity Monastery (Ždrelo) 1538 01.jpg',
        'File:Wili.Zaleđe III Holy Trinity Monastery (Ždrelo) 1538 03.jpg',
    ],
    'miljkovo': [
        'File:Миљков манастир 13.jpg',
        'File:Миљков манастир 04.jpg',
        'File:Миљков манастир 07.jpg',
    ],
    'namasija': [
        'File:Wiki.Biseri III Namasija Monastery 407.jpg',
        'File:Wiki.Biseri III Namasija Monastery 411.jpg',
        'File:Wiki.Biseri III Namasija Monastery 355.jpg',
    ],
    'radosin': [
        'File:Манастир Радошин 02.jpg',
        'File:Wiki.Biseri I Radošin Monastery 1221 07.jpg',
        'File:Wiki.Biseri I Radošin Monastery 1221 09.jpg',
    ],
    'reskovica': [
        'File:Wiki.Zaleđe III Reškovica Monastery 368.jpg',
        'File:Wiki.Zaleđe III Reškovica Monastery 369.jpg',
        'File:Wiki.Zaleđe III Reškovica Monastery 372.jpg',
    ],
    'zaova': [],  # only interior found – not adding
    'zlatenac': [
        'File:Wiki.Biseri I Zlatenac Monastery 1244 10.jpg',
        'File:Wiki.Biseri I Zlatenac Monastery 1244 12.jpg',
        'File:Wiki.Biseri I Zlatenac Monastery 1244 05.jpg',
    ],
    # Kruševačka – newly found
    'grabovo': [
        'File:Manastir Sv. arhangela Grabovo 012.jpg',
    ],
    'komorane': [
        'File:Wiki.Rasina II Komorane Monastery 328.jpg',
        'File:Wiki.Rasina II Komorane Monastery 320.jpg',
        'File:Wiki.Rasina II Komorane Monastery 324.jpg',
    ],
    'naupare': [
        'File:Wiki.Rasina I Naupara Monastery 556.jpg',
        'File:Wiki.Rasina I Naupara Monastery 533.jpg',
    ],
    # Mileševska – newly found
    'mazici': [
        'File:Manastir Mažići3.jpg',
        'File:Priboj Mazici IMG 0250.JPG',
        'File:Priboj Mazici IMG 0251.JPG',
    ],
    'pribojska-banja': [
        'File:Priboj monastery Banja IMG 0352.JPG',
        'File:Priboj monastery Banja IMG 0348.JPG',
    ],
    'pustinja-valjevska': [
        'File:Manastir Pustinja 1.jpg',
        'File:Manastir Pustinja 2.jpg',
    ],
}

def make_fn(slug, file_title, idx):
    ext = os.path.splitext(file_title)[1].lower().replace('.jpeg', '.jpg') or '.jpg'
    if idx == 0:
        return f"{slug}{ext}"
    return f"{slug}_gal_{idx}{ext}"

def run():
    print("=== BATCH 2: NOVI MANASTIRI – VERIFIKOVANE WIKIMEDIA SLIKE ===\n")

    all_items = {}
    for slug, files in NEW_IMAGES.items():
        if not files:
            all_items[slug] = []
            continue
        print(f"\n[{slug}]")
        items = []
        for idx, file_title in enumerate(files):
            url = api_get_url(file_title)
            fn = make_fn(slug, file_title, idx)
            items.append({'file': file_title, 'url': url, 'fn': fn})
            status = '✓' if url else '✗'
            print(f"  {status} {file_title[:55]}")
        all_items[slug] = items

    print("\n\n--- PREUZIMANJE ---")
    for slug, items in all_items.items():
        for item in items:
            if item['url']:
                dest = os.path.join(IMG_DIR, item['fn'])
                download_file(item['url'], dest)

    print("\n\n--- AŽURIRANJE BAZE ---")
    for db_path in [DB_STORAGE, DB_DATABASE]:
        if not os.path.exists(db_path):
            continue
        conn = sqlite3.connect(db_path)
        cur = conn.cursor()

        for slug, items in all_items.items():
            cur.execute("SELECT id FROM monasteries WHERE slug=?", (slug,))
            row = cur.fetchone()
            if not row:
                continue
            m_id = row[0]

            # Check existing images
            cur.execute("SELECT COUNT(*) FROM monastery_images WHERE monastery_id=?", (m_id,))
            existing = cur.fetchone()[0]

            sort = existing + 1
            first_url = None
            added = 0
            for item in items:
                dest = os.path.join(IMG_DIR, item['fn'])
                rel = f"images/monasteries/{item['fn']}"
                if os.path.exists(dest) and os.path.getsize(dest) > 50000:
                    cur.execute(
                        "INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (?,?,?,?,datetime('now'),datetime('now'))",
                        (m_id, rel, '', sort)
                    )
                    if sort == 1:
                        first_url = rel
                    sort += 1
                    added += 1

            if first_url:
                cur.execute("UPDATE monasteries SET image_url=? WHERE id=?", (first_url, m_id))

            if added > 0:
                print(f"  {slug}: +{added} slika")

        conn.commit()
        conn.close()

    # Clear all captions one more time to be safe
    for db_path in [DB_STORAGE, DB_DATABASE]:
        if not os.path.exists(db_path):
            continue
        conn = sqlite3.connect(db_path)
        conn.execute("UPDATE monastery_images SET caption='' WHERE caption IS NOT NULL AND caption != ''")
        conn.commit()
        conn.close()

    print("\n✓ ZAVRŠENO!")

if __name__ == '__main__':
    run()
