"""
Final curation script for 6 eparchies.
Uses VERIFIED Wikimedia Commons file names from search results.
NO captions. Only images confirmed to belong to the specific monastery.
"""
import os, sys, io, sqlite3, urllib.request, urllib.parse, json, time

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
IMG_DIR = os.path.join(BASE_DIR, 'public', 'images', 'monasteries')
DB_STORAGE = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE = os.path.join(BASE_DIR, 'database', 'database.sqlite')

HEADERS = {
    'User-Agent': 'ManastiriSrbijeResearchBot/1.0 (https://manastirisrbije.rs) python-urllib'
}

os.makedirs(IMG_DIR, exist_ok=True)

def api_get_url(file_title):
    api = (
        'https://commons.wikimedia.org/w/api.php'
        f'?action=query&titles={urllib.parse.quote(file_title)}'
        '&prop=imageinfo&iiprop=url|size|mime&format=json'
    )
    for attempt in range(3):
        time.sleep(1.5 if attempt == 0 else 4.0)
        try:
            req = urllib.request.Request(api, headers=HEADERS)
            with urllib.request.urlopen(req, timeout=15) as r:
                data = json.loads(r.read().decode('utf-8'))
                for pid, pd in data.get('query', {}).get('pages', {}).items():
                    if int(pid) > 0:
                        infos = pd.get('imageinfo', [])
                        if infos:
                            mime = infos[0].get('mime', '')
                            if 'image' in mime:
                                return infos[0]['url'].split('?')[0]
        except Exception as e:
            if '429' in str(e):
                print(f"    429 rate limit, čekam 10s...")
                time.sleep(10)
            else:
                print(f"    API err: {e}")
    return None

def download_file(url, dest):
    if not url or os.path.exists(dest) and os.path.getsize(dest) > 5000:
        if os.path.exists(dest) and os.path.getsize(dest) > 5000:
            print(f"    ↷ već preuzeto: {os.path.basename(dest)}")
            return True
        return False
    time.sleep(1.5)
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
                else:
                    print(f"    ✗ Fajl premali: {os.path.basename(dest)}")
    except Exception as e:
        print(f"    ✗ Download err: {e}")
    return False

# ==============================================================
# VERIFIED WIKIMEDIA COMMONS FILE TITLES PER MONASTERY
# Empty list = no verified images found
# NO captions
# ==============================================================
CURATION = {

    # ===== EPARHIJA BANATSKA =====
    'bavaniste': [
        'File:Monastère de Bavanište.jpg',
    ],
    'gaj': [
        'File:Wiki.Vojvodina VI Gaj (Kovin) 680.jpg',
        'File:Wiki.Vojvodina VI Gaj (Kovin) 679.jpg',
    ],
    'hajducica': [
        'File:Hajdučica Orthodox monastery.jpg',
    ],
    'mesic': [
        'File:Mesic Monastery.JPG',
        'File:Wiki.Vojvodina VI Mesić monastery 006.jpg',
        'File:Wiki.Vojvodina VI Mesić monastery 004.jpg',
    ],
    'srediste': [
        'File:Wiki.Vojvodina VI Manastir Središte 408.jpg',
        'File:Wiki.Vojvodina VI Manastir Središte 414.jpg',
        'File:Wiki.Vojvodina VI Manastir Središte 410.jpg',
    ],
    'sveta-trojica-kikinda': [
        'File:Manastir Svete Trojice Kikinda 01.JPG',
    ],
    'svete-melanije': [],  # no verified commons image
    'vlajkovac': [
        'File:Wiki.Vojvodina VI Vlajkovac 147.jpg',
    ],
    'vojlovica': [
        'File:Wiki.Vojvodina VIII Vojlovica monastery 251.jpg',
        'File:Manastir Vojlovica, tornjevi.jpg',
        'File:Monastery Vojlovica, interior 2.jpg',
    ],

    # ===== EPARHIJA BAČKA =====
    'bodjani': [
        'File:Bođani monastery, naos and iconostasis.jpg',
        'File:Wiki.Vojvodina V Bođani Monastery 379.jpg',
        'File:Bođani monastery, chapel of Saint Petka.jpg',
    ],
    'kac': [
        'File:Manastir Vaskrsenja Hristova - panoramio.jpg',
        'File:Manastir Vaskrsenja Hristova - panoramio (2).jpg',
    ],
    'kovilj': [
        'File:Manastir Kovilj u rano jutro.jpg',
        'File:Unutrasnjost manastira u Kovilju.jpg',
    ],
    'sombor': [
        'File:Wiki.Vojvodina IX Manastir Svetog Stefana 428.jpg',
        'File:Wiki.Vojvodina IX Manastir Svetog Stefana 427.jpg',
    ],
    'vodica': [],

    # ===== EPARHIJA BEOGRADSKA =====
    'mislodjin': [
        'File:Manastir svetog Hristofora Mislođin10.12.2016. 002.jpg',
        'File:Manastir svetog Hristofora Mislođin10.12.2016. 003.jpg',
        'File:Manastir svetog Hristofora Mislođin10.12.2016. 004.jpg',
    ],
    'rajinovac': [
        'File:Manastir Rajinovac 1.jpg',
        'File:Manastir Rajinovac 2.jpg',
        'File:Manastir Rajinovac 3.jpg',
    ],
    'rakovica': [
        'File:Manastirrakovica1.JPG',
        'File:Manastir Rakovica, unutrašnjost crkve i ikonostas.jpg',
    ],
    'senjak': [
        'File:Manastir Vavedenje Senjak 8.jpg',
        'File:Manastir Vavedenje Senjak 7.jpg',
        'File:Manastir Vavedenje Senjak 4.jpg',
    ],
    'slanci': [],
    'trojerucica': [],

    # ===== EPARHIJA BRANIČEVSKA =====
    'gornjak': [
        'File:Manastir Gornjak.JPG',
        'File:005 - Manastir Gornjak.jpg',
        'File:Wiki.Zaleđe III Gornjak Monastery 242.jpg',
    ],
    'koporin': [
        'File:Манастир Копорин 2.jpg',
        'File:Wiki Šumadija XVI Koporin Monastery 272.jpg',
        'File:Wiki Šumadija XVI Koporin Monastery 265.jpg',
    ],
    'manasija': [
        'File:Manasija monastery II.JPG',
        'File:Despotovac l Manastir Manasija 001.jpg',
        'File:Manasija Monastery 12.jpg',
    ],
    'ravanica': [
        'File:Monastery Ravanica.JPG',
        'File:Ravanica Monastery (by Pudelek).jpg',
        'File:Manastir Ravanica 1.JPG',
    ],
    'tumane': [
        'File:Manastir Tumane 2025.jpg',
        'File:Wiki.Đerdap II Tuman Monastery 298.jpg',
        'File:ManastirTuman.jpg',
    ],
    'sisojevac': [
        'File:Манастир Сисевац споља.jpg',
        'File:Wiki.Biseri I Sisojevac Monastery 994.jpg',
        'File:Wiki.Biseri I Sisojevac Monastery 993.jpg',
    ],
    'pokajnica': [
        'File:Манастир Покајница 2.jpg',
        'File:Pokajnica - unutrašnjost.JPG',
        'File:Manastir Pokajnica,Velika Plana.jpg',
    ],
    'bradaca': [
        'File:Wiki.Zaleđe II Manastir Bradača 1464 11.jpg',
        'File:Wiki.Zaleđe II Manastir Bradača 1464 06.jpg',
        'File:Wiki.Zaleđe II Manastir Bradača 1464 12.jpg',
    ],
    'dobres': [],
    'izvor': [],
    'miljkovo': [],
    'namasija': [],
    'nimnik': [
        'File:Wiki.Đerdap I Nimnik Monastery 203.jpg',
        'File:Nimnik ulaz.jpg',
        'File:Wiki.Đerdap I Nimnik Monastery 206.jpg',
    ],
    'radosin': [],
    'reskovica': [],
    'rukumija': [
        'File:Wiki.Zaleđe II Rukumija Monastery 410.jpg',
        'File:Wiki.Zaleđe II Rukumija Monastery 414.jpg',
        'File:Wiki.Zaleđe II Rukumija Monastery 423.jpg',
    ],
    'sestroljin': [],
    'tomic': [],
    'trska-crkva': [],
    'zaova': [],
    'zlatenac': [],
    'djerinac': [],
    'zdrelo': [],

    # ===== EPARHIJA KRUŠEVAČKA =====
    'ljubostinja': [
        'File:Crkva manastira Ljubostinja.jpg',
        'File:Detalj fasade i kupole crkve manastira Ljubostinja.jpg',
        'File:Манастир Љубостиња 02.jpg',
    ],
    'veluce': [
        'File:Wiki.Rasina I Manastir Veluće 497.jpg',
        'File:Wiki.Rasina I Manastir Veluće 470.jpg',
        'File:Miniature of the Monastery Veluće.JPG',
    ],
    'drenca': [
        'File:Манастир Дренча (Душманица) 02.JPG',
        'File:Манастир Дренча - Александровац Жупски.JPG',
        'File:Wiki.Rasina I Manastir Drenča 609.jpg',
    ],
    'naupare': [
        'File:Manastir Naupare.JPG',
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
        'File:Mileševa monastery (by Pudelek) 2.JPG',
        'File:Mileševa monastery (by Pudelek).JPG',
        'File:Manastir Mileševa - 2004.jpg',
    ],
    'davidovica': [
        'File:Manastir Davidovica, selo Grobnice, Brodarevo.jpg',
        'File:Monastère de Davidovica.jpg',
        'File:CT10 - Manastir Davidovica.jpg',
    ],
    'janja': [
        'File:Manastir Janja.JPG',
    ],
    'kumanica': [
        'File:Manastir-kumanica-0124.jpg',
        'File:Manastir-kumanica-0122.jpg',
        'File:ManastirKumanica.jpg',
    ],
    'jabuka': [
        'File:Manastir Jabuka.jpg',
    ],
    'bistrica': [],
    'mazici': [],
    'pribojska-banja': [],
    'pustinja-valjevska': [],
    'seljani': [],
    'vodena-poljana': [],
}

def make_filename(slug, file_title, idx):
    ext = os.path.splitext(file_title)[1].lower() or '.jpg'
    ext = ext.replace('.jpeg', '.jpg')
    if idx == 0:
        return f"{slug}{ext}"
    return f"{slug}_gal_{idx}{ext}"

def run():
    print("=== KURIRANJE 6 EPARHIJA - VERIFIKOVANE WIKIMEDIA SLIKE, BEZ OPISA ===\n")

    all_items = {}
    for slug, files in CURATION.items():
        if not files:
            all_items[slug] = []
            continue
        print(f"\n[{slug}] Dohvatam URL-ove...")
        items = []
        for idx, file_title in enumerate(files):
            url = api_get_url(file_title)
            fn = make_filename(slug, file_title, idx)
            items.append({'file': file_title, 'url': url, 'fn': fn})
            if url:
                print(f"  ✓ {file_title[:50]}")
            else:
                print(f"  ✗ Nije nađeno: {file_title[:50]}")
        all_items[slug] = items

    print("\n\n--- PREUZIMANJE SLIKA ---")
    for slug, items in all_items.items():
        for item in items:
            if item['url']:
                dest = os.path.join(IMG_DIR, item['fn'])
                download_file(item['url'], dest)

    print("\n\n--- AŽURIRANJE BAZE ---")
    for db_path in [DB_STORAGE, DB_DATABASE]:
        if not os.path.exists(db_path):
            print(f"  ! Baza ne postoji: {db_path}")
            continue
        print(f"\n  DB: {os.path.basename(os.path.dirname(db_path))}/database.sqlite")
        conn = sqlite3.connect(db_path)
        cur = conn.cursor()

        for slug, items in all_items.items():
            cur.execute("SELECT id FROM monasteries WHERE slug=?", (slug,))
            row = cur.fetchone()
            if not row:
                continue
            m_id = row[0]
            cur.execute("DELETE FROM monastery_images WHERE monastery_id=?", (m_id,))

            sort = 1
            first_url = None
            for item in items:
                dest = os.path.join(IMG_DIR, item['fn'])
                rel = f"images/monasteries/{item['fn']}"
                if os.path.exists(dest) and os.path.getsize(dest) > 5000:
                    cur.execute(
                        "INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (?,?,?,?,datetime('now'),datetime('now'))",
                        (m_id, rel, '', sort)
                    )
                    if first_url is None:
                        first_url = rel
                    sort += 1

            if first_url:
                cur.execute("UPDATE monasteries SET image_url=? WHERE id=?", (first_url, m_id))
                print(f"    {slug}: {sort-1} slika")
            else:
                print(f"    {slug}: 0 slika")

        conn.commit()
        conn.close()

    print("\n✓ SVE ZAVRŠENO!")

if __name__ == '__main__':
    run()
