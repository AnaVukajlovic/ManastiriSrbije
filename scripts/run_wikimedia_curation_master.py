import os
import sys
import io
import sqlite3
import urllib.request
import urllib.parse
import json
import time
import csv

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
IMG_DIR = os.path.join(BASE_DIR, 'public', 'images', 'monasteries')
DB_STORAGE = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE = os.path.join(BASE_DIR, 'database', 'database.sqlite')

HEADERS = {
    'User-Agent': 'ManastiriSrbijeResearchBot/1.0 (https://manastirisrbije.rs; contact@manastirisrbije.rs) python-requests'
}

def fetch_wiki_file_url(file_title):
    api_url = f"https://commons.wikimedia.org/w/api.php?action=query&titles={urllib.parse.quote(file_title)}&prop=imageinfo&iiprop=url|size|mime&format=json"
    req = urllib.request.Request(api_url, headers=HEADERS)
    try:
        with urllib.request.urlopen(req, timeout=10) as r:
            data = json.loads(r.read().decode('utf-8'))
            pages = data.get('query', {}).get('pages', {})
            for pid, pdata in pages.items():
                infos = pdata.get('imageinfo', [])
                if infos:
                    return infos[0].get('url')
    except Exception as e:
        print(f"  ✗ Error fetching {file_title}: {e}")
    return None

def download_file(url, dest_path):
    if not url:
        return False
    time.sleep(0.8)
    req = urllib.request.Request(url, headers=HEADERS)
    try:
        with urllib.request.urlopen(req, timeout=15) as resp:
            if resp.status == 200:
                data = resp.read()
                if len(data) > 4096:
                    with open(dest_path, 'wb') as f:
                        f.write(data)
                    print(f"    ✓ Preuzeto: {os.path.basename(dest_path)} ({len(data)//1024} KB)")
                    return True
    except Exception as e:
        print(f"    ✗ Download error for {url}: {e}")
    return False

# KOMPLETNA LISTA ZA 20 MANASTIRA SA ISKLJUČIVO WIKIMEDIA COMMONS SLIKAMA
WIKIMEDIA_COMMONS_CURATION = {
    # =========================================================================
    # EPARHIJA BANATSKA (9 manastira)
    # =========================================================================
    'bavaniste': [
        {
            'file': 'File:Monastère de Bavanište.jpg',
            'filename': 'bavaniste.jpg',
            'caption': 'Hram Rođenja Presvete Bogorodice – Manastir Bavanište u šumi'
        },
        {
            'file': 'File:Поглед ка извору Свете Водиц.jpg',
            'filename': 'bavaniste_gal_1.jpg',
            'caption': 'Kapela i čudotvorni lekoviti izvor Sveta Vodica'
        },
        {
            'file': 'File:Поглед од конака.jpg',
            'filename': 'bavaniste_gal_2.jpg',
            'caption': 'Pogled na manastirsku portu i zvonik iz konaka'
        }
    ],
    'gaj': [
        {
            'file': 'File:Gaj, Orthodox church.jpg',
            'filename': 'gaj.jpg',
            'caption': 'Hram Vaznesenja Gospodnjeg – Manastir Gaj kod Kovina'
        },
        {
            'file': 'File:Wiki.Vojvodina VI Gaj (Kovin) 680.jpg',
            'filename': 'gaj_gal_1.jpg',
            'caption': 'Pogled na manastirsko zdanje i zvonik u selu Gaj'
        }
    ],
    'hajducica': [
        {
            'file': 'File:Hajdučica Orthodox monastery.jpg',
            'filename': 'hajducica.jpg',
            'caption': 'Crkva Svetog Arhangela Mihaila – Manastir Hajdučica'
        },
        {
            'file': 'File:Hajdučica_Orthodox_monastery.jpg',
            'filename': 'hajducica_gal_1.jpg',
            'caption': 'Zadužbina Olge Jovanović Dunđerski u prostranom parku'
        }
    ],
    'mesic': [
        {
            'file': 'File:Wiki.Vojvodina VI Mesić monastery 006.jpg',
            'filename': 'mesic.jpg',
            'caption': 'Hram Rođenja Svetog Jovana Krstitelja – Manastir Mesić'
        },
        {
            'file': 'File:Wiki.Vojvodina VI Mesić monastery 004.jpg',
            'filename': 'mesic_gal_1.jpg',
            'caption': 'Barokni zvonik i monumentalni konak pod Vršačkim bregom'
        },
        {
            'file': 'File:Mesic Monastery.JPG',
            'filename': 'mesic_gal_2.jpg',
            'caption': 'Pogled na manastirski kompleks i portu u Mesiću'
        }
    ],
    'srediste': [
        {
            'file': 'File:Wiki.Vojvodina VI Manastir Središte 408.jpg',
            'filename': 'srediste.jpg',
            'caption': 'Hram Presvete Bogorodice Trojeručice – Manastir Središte'
        },
        {
            'file': 'File:Wiki.Vojvodina VI Manastir Središte 414.jpg',
            'filename': 'srediste_gal_1.jpg',
            'caption': 'Pogled na manastirski hram i zdanje na padinama Vršačkih planina'
        },
        {
            'file': 'File:Wiki.Vojvodina VI Manastir Središte 410.jpg',
            'filename': 'srediste_gal_2.jpg',
            'caption': 'Uređena manastirska porta i konak na Guduričkom vrhu'
        }
    ],
    'sveta-trojica-kikinda': [
        {
            'file': 'File:Манастир Свете Тројице у Кикинди.jpeg',
            'filename': 'sveta-trojica-kikinda.jpg',
            'caption': 'Zadužbinski hram Svete Trojice – Manastir u Kikindi'
        },
        {
            'file': 'File:Manastir Svete Trojice Kikinda 01.JPG',
            'filename': 'sveta-trojica-kikinda_gal_1.jpg',
            'caption': 'Crkva Svete Trojice zadužbinarke Melanije Nikolić Gačić'
        },
        {
            'file': 'File:Звоник Манастира Свете Тројице у Кикинди.jpeg',
            'filename': 'sveta-trojica-kikinda_gal_2.jpg',
            'caption': 'Monumentalni zvonik i manastirski kompleks u Kikindi'
        }
    ],
    'svete-melanije': [
        {
            'file': 'File:Манастир свете Меланије.jpg',
            'filename': 'svete-melanije.jpg',
            'caption': 'Hram Rođenja Presvete Bogorodice – Manastir Svete Melanije u Zrenjaninu'
        },
        {
            'file': 'File:Manastir Svete Melanije.jpg',
            'filename': 'svete-melanije_gal_1.jpg',
            'caption': 'Zadužbina episkopa banatskog Georgija Letića i manastirski konak'
        }
    ],
    'vlajkovac': [
        {
            'file': 'File:Vlajkovac, Orthodox Church.jpg',
            'filename': 'vlajkovac.jpg',
            'caption': 'Glavni hram i manastirsko zdanje u Vlajkovcu kod Vršca'
        },
        {
            'file': 'File:Wiki.Vojvodina VI Vlajkovac 147.jpg',
            'filename': 'vlajkovac_gal_1.jpg',
            'caption': 'Pogled na manastirski kompleks i park u Vlajkovcu'
        }
    ],
    'vojlovica': [
        {
            'file': 'File:Wiki.Vojvodina VIII Vojlovica monastery 251.jpg',
            'filename': 'vojlovica.jpg',
            'caption': 'Crkva Svetih Arhangela Mihaila i Gavrila – Manastir Vojlovica'
        },
        {
            'file': 'File:Manastir Vojlovica, tornjevi.jpg',
            'filename': 'vojlovica_gal_1.jpg',
            'caption': 'Barokni zvonik iz 1752. godine i bedemi despota Stefana Lazarevića'
        },
        {
            'file': 'File:Monastery Vojlovica, interior 2.jpg',
            'filename': 'vojlovica_gal_2.jpg',
            'caption': 'Raskošni pozlaćeni barokni ikonostas u manastirskom hramu Vojlovice'
        }
    ],

    # =========================================================================
    # EPARHIJA BAČKA (5 manastira)
    # =========================================================================
    'bodjani': [
        {
            'file': 'File:Bođani monastery, naos and iconostasis.jpg',
            'filename': 'bodjani.jpg',
            'caption': 'Hram Vavedenja Presvete Bogorodice sa ikonostasom – Manastir Bođani'
        },
        {
            'file': 'File:Wiki.Vojvodina V Bođani Monastery 379.jpg',
            'filename': 'bodjani_gal_1.jpg',
            'caption': 'Čuveni živopis Hristifora Žefarovića iz 1737. godine u hramu Bođana'
        },
        {
            'file': 'File:Bođani monastery, chapel of Saint Petka.jpg',
            'filename': 'bodjani_gal_2.jpg',
            'caption': 'Kapela Svete Petke i manastirski kompleks u Bođanima'
        }
    ],
    'kac': [
        {
            'file': 'File:Manastir Vaskrsenja Hristova - panoramio.jpg',
            'filename': 'kac.jpg',
            'caption': 'Hram Vaskrsenja Hristovog – Manastir Kać kod Novog Sada'
        },
        {
            'file': 'File:Manastir Vaskrsenja Hristova - panoramio (2).jpg',
            'filename': 'kac_gal_1.jpg',
            'caption': 'Pogled na manastirski hram i konak u Kaću'
        },
        {
            'file': 'File:Manastir Vaskrsenja Hristova - panoramio (8).jpg',
            'filename': 'kac_gal_2.jpg',
            'caption': 'Uređena manastirska porta i zvonik u Kaću'
        }
    ],
    'kovilj': [
        {
            'file': 'File:Ковиљски Манастир Светих Архангела Михаила и Гаврила.jpg',
            'filename': 'kovilj.jpg',
            'caption': 'Monumentalni hram Svetih Arhangela Mihaila i Gavrila – Manastir Kovilj'
        },
        {
            'file': 'File:Manastir Kovilj u rano jutro.jpg',
            'filename': 'kovilj_gal_1.jpg',
            'caption': 'Pogled na manastirski kompleks, zvonik i portu u Kovilju'
        },
        {
            'file': 'File:Unutrasnjost manastira u Kovilju.jpg',
            'filename': 'kovilj_gal_2.jpg',
            'caption': 'Unutrašnjost hrama i ikonostas rad Aksentija Marodića u Kovilju'
        }
    ],
    'sombor': [
        {
            'file': 'File:Wiki.Vojvodina IX Manastir Svetog Stefana 428.jpg',
            'filename': 'sombor.jpg',
            'caption': 'Crkva Svetog Prvomučenika Stefana – Manastir Sombor'
        },
        {
            'file': 'File:Wiki.Vojvodina IX Manastir Svetog Stefana 427.jpg',
            'filename': 'sombor_gal_1.jpg',
            'caption': 'Srpsko-vizantijska arhitektura i kupola hrama Stevana Konjovića'
        },
        {
            'file': 'File:Wiki.Vojvodina IX Manastir Svetog Stefana 432.jpg',
            'filename': 'sombor_gal_2.jpg',
            'caption': 'Zadužbinski kompleks i uređena manastirska porta u Somboru'
        }
    ],
    'vodica': [
        {
            'file': None,
            'filename': 'vodica.jpg',
            'caption': 'Kapela i svetište Rođenja Presvete Bogorodice na Vodici kod Bača'
        },
        {
            'file': None,
            'filename': 'vodica_gal_1.jpg',
            'caption': 'Čudotvorni izvor i prirodno okruženje manastira Vodica'
        }
    ],

    # =========================================================================
    # EPARHIJA BEOGRADSKA (6 manastira)
    # =========================================================================
    'mislodjin': [
        {
            'file': 'File:Manastir svetog Hristofora Mislođin10.12.2016. 002.jpg',
            'filename': 'mislodjin.jpg',
            'caption': 'Hram Svetog Velikomučenika Hristifora – Manastir Mislođin kod Obrenovca'
        },
        {
            'file': 'File:Manastir svetog Hristofora Mislođin10.12.2016. 003.jpg',
            'filename': 'mislodjin_gal_1.jpg',
            'caption': 'Pogled na manastirsko zdanje iz vremena kralja Dragutina i konak'
        },
        {
            'file': 'File:Manastir svetog Hristofora Mislođin10.12.2016. 004.jpg',
            'filename': 'mislodjin_gal_2.jpg',
            'caption': 'Manastirska porta i zvonik u Mislođinu'
        }
    ],
    'rajinovac': [
        {
            'file': 'File:Manastir Rajinovac 1.jpg',
            'filename': 'rajinovac.jpg',
            'caption': 'Crkva Rođenja Presvete Bogorodice – Manastir Rajinovac u Begaljici'
        },
        {
            'file': 'File:Manastir Rajinovac 2.jpg',
            'filename': 'rajinovac_gal_1.jpg',
            'caption': 'Pogled na hram i uređenu cvetnu portu u Begaljici kod Grocke'
        },
        {
            'file': 'File:Wiki Šumadija XIV Manastir Rajinovac 184.jpg',
            'filename': 'rajinovac_gal_2.jpg',
            'caption': 'Ikonostas i unutrašnjost hrama sa čudotvornom ikonom Bogorodice Rajinovačke'
        }
    ],
    'rakovica': [
        {
            'file': 'File:Manastirrakovica1.JPG',
            'filename': 'rakovica.jpg',
            'caption': 'Hram Svetog Arhangela Mihaila – Manastir Rakovica u Beogradu'
        },
        {
            'file': 'File:Manastir Rakovica, unutrašnjost crkve i ikonostas.jpg',
            'filename': 'rakovica_gal_1.jpg',
            'caption': 'Unutrašnjost crkve i pozlaćeni ikonostas manastira Rakovica'
        },
        {
            'file': 'File:Manastirrakovica4.JPG',
            'filename': 'rakovica_gal_2.jpg',
            'caption': 'Manastirska porta sa grobnim mestom patrijarha Pavla i Dimitrija'
        }
    ],
    'senjak': [
        {
            'file': 'File:Manastir Vavedenje Senjak 8.jpg',
            'filename': 'senjak.jpg',
            'caption': 'Hram Vavedenja Presvete Bogorodice – Manastir Vavedenje na Senjaku'
        },
        {
            'file': 'File:Manastir Vavedenje Senjak 7.jpg',
            'filename': 'senjak_gal_1.jpg',
            'caption': 'Arhitektura hrama zadužbinarke Perside Milenković na Senjaku'
        },
        {
            'file': 'File:Manastir Vavedenje Senjak 4.jpg',
            'filename': 'senjak_gal_2.jpg',
            'caption': 'Manastirski konak i dvorište pod Topčiderskim brdom u Beogradu'
        }
    ],
    'slanci': [
        {
            'file': None,
            'filename': 'slanci.jpg',
            'caption': 'Hram Svetog Arhiđakona Stefana – Manastir Slanci (metoh Hilandara)'
        },
        {
            'file': None,
            'filename': 'slanci_gal_1.jpg',
            'caption': 'Pogled na manastirski kompleks i hilandarske konake u Slancima'
        },
        {
            'file': None,
            'filename': 'slanci_gal_2.jpg',
            'caption': 'Manastirska porta i zvonik hilandarskog metoha kod Beograda'
        }
    ],
    'trojerucica': [
        {
            'file': None,
            'filename': 'trojerucica.jpg',
            'caption': 'Hram Presvete Bogorodice Trojeručice pod Avalom u Ripnju'
        },
        {
            'file': None,
            'filename': 'trojerucica_gal_1.jpg',
            'caption': 'Pogled na manastirsko zdanje i zvonik podno Avale'
        },
        {
            'file': None,
            'filename': 'trojerucica_gal_2.jpg',
            'caption': 'Manastirska porta i konak manastira Trojeručica'
        }
    ]
}

def run_download_and_sync():
    print("=== KURIRANJE I PREUZIMANJE WIKIMEDIA COMMONS SLIKA (BEZ LOGOA) ===")
    
    # 1. Download all files directly from Wikimedia Commons API
    for slug, items in WIKIMEDIA_COMMONS_CURATION.items():
        print(f"\nObrada: {slug}...")
        for item in items:
            dest_fn = item['filename']
            dest_full = os.path.join(IMG_DIR, dest_fn)
            file_t = item['file']
            if file_t:
                url = fetch_wiki_file_url(file_t)
                if url:
                    download_file(url, dest_full)

    # 2. Update both SQLite databases
    for db_path in [DB_STORAGE, DB_DATABASE]:
        if not os.path.exists(db_path):
            continue
        print(f"\nAžuriranje baze podataka: {db_path}")
        conn = sqlite3.connect(db_path)
        cur = conn.cursor()

        for slug, items in WIKIMEDIA_COMMONS_CURATION.items():
            cur.execute("SELECT id, name FROM monasteries WHERE slug = ?", (slug,))
            row = cur.fetchone()
            if not row:
                continue
            m_id, name = row[0], row[1]

            cur.execute("DELETE FROM monastery_images WHERE monastery_id = ?", (m_id,))

            valid_items = []
            for s_idx, item in enumerate(items, 1):
                dest_fn = item['filename']
                dest_full = os.path.join(IMG_DIR, dest_fn)
                dest_rel = f"images/monasteries/{dest_fn}"

                if os.path.exists(dest_full) and os.path.getsize(dest_full) > 1024:
                    valid_items.append((dest_rel, item['caption'], s_idx))
                    cur.execute(
                        "INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))",
                        (m_id, dest_rel, item['caption'], s_idx)
                    )

            if valid_items:
                cur.execute("UPDATE monasteries SET image_url = ? WHERE id = ?", (valid_items[0][0], m_id))

        conn.commit()
        conn.close()

    # 3. Synchronize CSV files
    conn = sqlite3.connect(DB_STORAGE)
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
    print("\n✓ KOMPLETNO PREUZETE I SINHRONIZOVANE ČISTE WIKIMEDIA COMMONS SLIKE!")

if __name__ == '__main__':
    run_download_and_sync()
