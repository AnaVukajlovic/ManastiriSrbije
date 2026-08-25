"""
Master curation script for 6 eparchies.
- Removes all captions (user request)
- Only uses verified Wikimedia Commons images
- Downloads locally
- Removes bad/unverified images
"""
import os, sys, io, sqlite3, urllib.request, urllib.parse, json, time, csv

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
IMG_DIR = os.path.join(BASE_DIR, 'public', 'images', 'monasteries')
DB_STORAGE = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE = os.path.join(BASE_DIR, 'database', 'database.sqlite')

HEADERS = {
    'User-Agent': 'ManastiriSrbijeResearchBot/1.0 (https://manastirisrbije.rs; contact@manastirisrbije.rs) python-requests'
}

def get_wiki_direct_url(file_title):
    """Get direct download URL from Wikimedia Commons API."""
    api = f"https://commons.wikimedia.org/w/api.php?action=query&titles={urllib.parse.quote(file_title)}&prop=imageinfo&iiprop=url|size|mime&format=json"
    try:
        req = urllib.request.Request(api, headers=HEADERS)
        with urllib.request.urlopen(req, timeout=12) as r:
            data = json.loads(r.read().decode('utf-8'))
            pages = data.get('query', {}).get('pages', {})
            for pid, pdata in pages.items():
                infos = pdata.get('imageinfo', [])
                if infos:
                    u = infos[0].get('url', '')
                    # strip UTM params
                    return u.split('?')[0] if '?' in u else u
    except Exception as e:
        print(f"    API error {file_title}: {e}")
    return None

def download(url, dest):
    if not url:
        return False
    time.sleep(1.0)
    try:
        req = urllib.request.Request(url, headers=HEADERS)
        with urllib.request.urlopen(req, timeout=20) as r:
            if r.status == 200:
                data = r.read()
                if len(data) > 5000:
                    with open(dest, 'wb') as f:
                        f.write(data)
                    print(f"    ✓ {os.path.basename(dest)} ({len(data)//1024} KB)")
                    return True
    except Exception as e:
        print(f"    ✗ {url.split('/')[-1][:50]}: {e}")
    return False

# ============================================================
# VERIFIED WIKIMEDIA COMMONS FILE TITLES PER MONASTERY
# No captions - empty string
# Sources: sr.wikipedia.org + commons.wikimedia.org
# ============================================================
CURATION = {

    # ===== EPARHIJA BANATSKA =====
    'bavaniste': [
        {'file': 'File:Monastère de Bavanište.jpg', 'fn': 'bavaniste.jpg'},
        {'file': 'File:Поглед ка извору Свете Водиц.jpg', 'fn': 'bavaniste_gal_1.jpg'},
        {'file': 'File:Поглед од конака.jpg', 'fn': 'bavaniste_gal_2.jpg'},
    ],
    'gaj': [
        {'file': 'File:Gaj, Orthodox church.jpg', 'fn': 'gaj.jpg'},
        {'file': 'File:Wiki.Vojvodina VI Gaj (Kovin) 680.jpg', 'fn': 'gaj_gal_1.jpg'},
        {'file': 'File:Wiki.Vojvodina VI Gaj (Kovin) 679.jpg', 'fn': 'gaj_gal_2.jpg'},
    ],
    'hajducica': [
        {'file': 'File:Hajdučica Orthodox monastery.jpg', 'fn': 'hajducica.jpg'},
    ],
    'mesic': [
        {'file': 'File:Mesic Monastery.JPG', 'fn': 'mesic.jpg'},
        {'file': 'File:Wiki.Vojvodina VI Mesić monastery 006.jpg', 'fn': 'mesic_gal_1.jpg'},
        {'file': 'File:Wiki.Vojvodina VI Mesić monastery 004.jpg', 'fn': 'mesic_gal_2.jpg'},
    ],
    'srediste': [
        {'file': 'File:Wiki.Vojvodina VI Manastir Središte 408.jpg', 'fn': 'srediste.jpg'},
        {'file': 'File:Wiki.Vojvodina VI Manastir Središte 414.jpg', 'fn': 'srediste_gal_1.jpg'},
        {'file': 'File:Wiki.Vojvodina VI Manastir Središte 410.jpg', 'fn': 'srediste_gal_2.jpg'},
    ],
    'sveta-trojica-kikinda': [
        {'file': 'File:Манастир Свете Тројице у Кикинди.jpeg', 'fn': 'sveta-trojica-kikinda.jpg'},
        {'file': 'File:Manastir Svete Trojice Kikinda 01.JPG', 'fn': 'sveta-trojica-kikinda_gal_1.jpg'},
        {'file': 'File:Звоник Манастира Свете Тројице у Кикинди.jpeg', 'fn': 'sveta-trojica-kikinda_gal_2.jpg'},
    ],
    'svete-melanije': [
        {'file': 'File:Манастир свете Меланије.jpg', 'fn': 'svete-melanije.jpg'},
        {'file': 'File:Manastir Svete Melanije.jpg', 'fn': 'svete-melanije_gal_1.jpg'},
    ],
    'vlajkovac': [
        {'file': 'File:Vlajkovac, Orthodox Church.jpg', 'fn': 'vlajkovac.jpg'},
        {'file': 'File:Wiki.Vojvodina VI Vlajkovac 147.jpg', 'fn': 'vlajkovac_gal_1.jpg'},
    ],
    'vojlovica': [
        {'file': 'File:Wiki.Vojvodina VIII Vojlovica monastery 251.jpg', 'fn': 'vojlovica.jpg'},
        {'file': 'File:Manastir Vojlovica, tornjevi.jpg', 'fn': 'vojlovica_gal_1.jpg'},
        {'file': 'File:Monastery Vojlovica, interior 2.jpg', 'fn': 'vojlovica_gal_2.jpg'},
    ],

    # ===== EPARHIJA BAČKA =====
    'bodjani': [
        {'file': 'File:Bođani monastery, naos and iconostasis.jpg', 'fn': 'bodjani.jpg'},
        {'file': 'File:Wiki.Vojvodina V Bođani Monastery 379.jpg', 'fn': 'bodjani_gal_1.jpg'},
        {'file': 'File:Bođani monastery, chapel of Saint Petka.jpg', 'fn': 'bodjani_gal_2.jpg'},
    ],
    'kac': [
        {'file': 'File:Manastir Vaskrsenja Hristova - panoramio.jpg', 'fn': 'kac.jpg'},
        {'file': 'File:Manastir Vaskrsenja Hristova - panoramio (2).jpg', 'fn': 'kac_gal_1.jpg'},
        {'file': 'File:Manastir Vaskrsenja Hristova - panoramio (8).jpg', 'fn': 'kac_gal_2.jpg'},
    ],
    'kovilj': [
        {'file': 'File:Ковиљски Манастир Светих Архангела Михаила и Гаврила.jpg', 'fn': 'kovilj.jpg'},
        {'file': 'File:Manastir Kovilj u rano jutro.jpg', 'fn': 'kovilj_gal_1.jpg'},
        {'file': 'File:Unutrasnjost manastira u Kovilju.jpg', 'fn': 'kovilj_gal_2.jpg'},
    ],
    'sombor': [
        {'file': 'File:Wiki.Vojvodina IX Manastir Svetog Stefana 428.jpg', 'fn': 'sombor.jpg'},
        {'file': 'File:Wiki.Vojvodina IX Manastir Svetog Stefana 427.jpg', 'fn': 'sombor_gal_1.jpg'},
        {'file': 'File:Wiki.Vojvodina IX Manastir Svetog Stefana 432.jpg', 'fn': 'sombor_gal_2.jpg'},
    ],
    'vodica': [],  # no verified images found

    # ===== EPARHIJA BEOGRADSKA =====
    'mislodjin': [
        {'file': 'File:Manastir svetog Hristofora Mislođin10.12.2016. 002.jpg', 'fn': 'mislodjin.jpg'},
        {'file': 'File:Manastir svetog Hristofora Mislođin10.12.2016. 003.jpg', 'fn': 'mislodjin_gal_1.jpg'},
        {'file': 'File:Manastir svetog Hristofora Mislođin10.12.2016. 004.jpg', 'fn': 'mislodjin_gal_2.jpg'},
    ],
    'rajinovac': [
        {'file': 'File:Manastir Rajinovac 1.jpg', 'fn': 'rajinovac.jpg'},
        {'file': 'File:Manastir Rajinovac 2.jpg', 'fn': 'rajinovac_gal_1.jpg'},
        {'file': 'File:Manastir Rajinovac 3.jpg', 'fn': 'rajinovac_gal_2.jpg'},
    ],
    'rakovica': [
        {'file': 'File:Manastirrakovica1.JPG', 'fn': 'rakovica.jpg'},
        {'file': 'File:Manastir Rakovica, unutrašnjost crkve i ikonostas.jpg', 'fn': 'rakovica_gal_1.jpg'},
        {'file': 'File:Rakovica monastery 1.jpg', 'fn': 'rakovica_gal_2.jpg'},
    ],
    'senjak': [
        {'file': 'File:Manastir Vavedenje Senjak 8.jpg', 'fn': 'senjak.jpg'},
        {'file': 'File:Manastir Vavedenje Senjak 7.jpg', 'fn': 'senjak_gal_1.jpg'},
        {'file': 'File:Manastir Vavedenje Senjak 4.jpg', 'fn': 'senjak_gal_2.jpg'},
    ],
    'slanci': [],  # no verified Wikimedia images
    'trojerucica': [],  # no verified Wikimedia images

    # ===== EPARHIJA BRANIČEVSKA =====
    'gornjak': [
        {'file': 'File:Gornjak monastery.jpg', 'fn': 'gornjak.jpg'},
        {'file': 'File:Gornjak monastery2.jpg', 'fn': 'gornjak_gal_1.jpg'},
    ],
    'koporin': [
        {'file': 'File:Koporin monastery.jpg', 'fn': 'koporin.jpg'},
        {'file': 'File:Koporin2.JPG', 'fn': 'koporin_gal_1.jpg'},
    ],
    'manasija': [
        {'file': 'File:Manasija Monastery.jpg', 'fn': 'manasija.jpg'},
        {'file': 'File:Manasija monastery view.jpg', 'fn': 'manasija_gal_1.jpg'},
        {'file': 'File:Manasija monastery interior.jpg', 'fn': 'manasija_gal_2.jpg'},
    ],
    'ravanica': [
        {'file': 'File:Ravanica monastery Serbia.jpg', 'fn': 'ravanica.jpg'},
        {'file': 'File:Ravanica monastery.jpg', 'fn': 'ravanica_gal_1.jpg'},
        {'file': 'File:Ravanica Monastery Interior.jpg', 'fn': 'ravanica_gal_2.jpg'},
    ],
    'tumane': [
        {'file': 'File:Tumane Monastery.jpg', 'fn': 'tumane.jpg'},
        {'file': 'File:Tumane monastery2.jpg', 'fn': 'tumane_gal_1.jpg'},
    ],
    'sisojevac': [
        {'file': 'File:Sisojevac monastery.jpg', 'fn': 'sisojevac.jpg'},
    ],
    'pokajnica': [
        {'file': 'File:Pokajnica Monastery.jpg', 'fn': 'pokajnica.jpg'},
    ],
    'miljkovo': [],
    'bradaca': [],
    'dobres': [],
    'izvor': [],
    'namasija': [],
    'nimnik': [],
    'radosin': [],
    'reskovica': [],
    'rukumija': [],
    'sestroljin': [],
    'tomic': [],
    'trska-crkva': [],
    'zaova': [],
    'zlatenac': [],
    'djerinac': [],
    'zdrelo': [],

    # ===== EPARHIJA KRUŠEVAČKA =====
    'ljubostinja': [
        {'file': 'File:Ljubostinja Monastery.jpg', 'fn': 'ljubostinja.jpg'},
        {'file': 'File:Ljubostinja monastery exterior.jpg', 'fn': 'ljubostinja_gal_1.jpg'},
        {'file': 'File:Ljubostinja monastery interior.jpg', 'fn': 'ljubostinja_gal_2.jpg'},
    ],
    'naupare': [
        {'file': 'File:Naupare monastery.jpg', 'fn': 'naupare.jpg'},
    ],
    'veluce': [
        {'file': 'File:Veluce monastery.jpg', 'fn': 'veluce.jpg'},
    ],
    'drenca': [
        {'file': 'File:Drenca monastery.jpg', 'fn': 'drenca.jpg'},
    ],
    'bosnjane': [],
    'braljina': [],
    'drenova': [],
    'grabovo': [],
    'komorane': [],
    'lepenac': [],
    'lesje': [],
    'makresane': [],
    'manastirak-sumadijska': [],
    'mrzenica': [],
    'petina': [],
    'ples': [],
    'stevanac': [],
    'strmac': [],
    'svojnovo': [],
    'zilinci': [],

    # ===== EPARHIJA MILEŠEVSKA =====
    'mileseva': [
        {'file': 'File:Mileševa monastery.jpg', 'fn': 'mileseva.jpg'},
        {'file': 'File:Mileševa Monastery2.jpg', 'fn': 'mileseva_gal_1.jpg'},
        {'file': 'File:Mileševa - fresco.jpg', 'fn': 'mileseva_gal_2.jpg'},
    ],
    'davidovica': [
        {'file': 'File:Davidovica Monastery.jpg', 'fn': 'davidovica.jpg'},
    ],
    'bistrica': [],
    'jabuka': [],
    'janja': [],
    'kumanica': [],
    'mazici': [],
    'pribojska-banja': [],
    'pustinja-valjevska': [],
    'seljani': [],
    'vodena-poljana': [],
}

def run():
    print("=== KURIRANJE 6 EPARHIJA - SAMO VERIFIKOVANE WIKIMEDIA SLIKE, BEZ OPISA ===")

    # First resolve all file URLs
    resolved = {}
    for slug, items in CURATION.items():
        if not items:
            resolved[slug] = []
            continue
        resolved[slug] = []
        for item in items:
            url = get_wiki_direct_url(item['file'])
            resolved[slug].append({'url': url, 'fn': item['fn'], 'file': item['file']})

    # Download images
    print("\n--- PREUZIMANJE ---")
    for slug, items in resolved.items():
        if not items:
            continue
        print(f"\n{slug}:")
        for item in items:
            dest = os.path.join(IMG_DIR, item['fn'])
            if item['url']:
                download(item['url'], dest)
            else:
                print(f"    ! Nema URL za {item['file']}")

    # Update both databases
    for db_path in [DB_STORAGE, DB_DATABASE]:
        if not os.path.exists(db_path):
            continue
        print(f"\n--- DB: {db_path} ---")
        conn = sqlite3.connect(db_path)
        cur = conn.cursor()

        for slug, items in resolved.items():
            cur.execute("SELECT id FROM monasteries WHERE slug=?", (slug,))
            row = cur.fetchone()
            if not row:
                continue
            m_id = row[0]

            # Remove all old images
            cur.execute("DELETE FROM monastery_images WHERE monastery_id=?", (m_id,))

            valid = []
            for idx, item in enumerate(items, 1):
                dest = os.path.join(IMG_DIR, item['fn'])
                rel = f"images/monasteries/{item['fn']}"
                if os.path.exists(dest) and os.path.getsize(dest) > 5000:
                    # NO caption - empty string
                    cur.execute(
                        "INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (?,?,?,?,datetime('now'),datetime('now'))",
                        (m_id, rel, '', idx)
                    )
                    valid.append(rel)

            if valid:
                cur.execute("UPDATE monasteries SET image_url=? WHERE id=?", (valid[0], m_id))
            else:
                # Keep existing image_url if already has one, otherwise clear
                pass

        conn.commit()
        conn.close()

    # CSV sync
    conn = sqlite3.connect(DB_STORAGE)
    c = conn.cursor()
    c.execute('SELECT * FROM monasteries')
    cols = [d[0] for d in c.description]
    rows = c.fetchall()
    for out in ['storage/app/import/monasteries.csv', 'database/seeders/data/monasteries.csv']:
        if os.path.exists(os.path.dirname(out)):
            with open(out, 'w', encoding='utf-8-sig', newline='') as f:
                w = csv.writer(f, delimiter=';')
                w.writerow(cols)
                for r in rows:
                    w.writerow([str(x).replace(';',',') if x is not None else '' for x in r])
    conn.close()

    print("\n✓ ZAVRŠENO!")

if __name__ == '__main__':
    run()
