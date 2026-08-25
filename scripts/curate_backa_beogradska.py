import os
import sys
import io
import sqlite3
import urllib.request
import csv

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
IMG_DIR = os.path.join(BASE_DIR, 'public', 'images', 'monasteries')
DB_STORAGE = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE = os.path.join(BASE_DIR, 'database', 'database.sqlite')

HEADERS = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36'
}

def download_img(url, dest_path):
    if not url:
        return False
    try:
        req = urllib.request.Request(url, headers=HEADERS)
        with urllib.request.urlopen(req, timeout=15) as resp:
            if resp.status == 200:
                data = resp.read()
                if len(data) > 5000:
                    with open(dest_path, 'wb') as f:
                        f.write(data)
                    return True
    except Exception as e:
        print(f"Error downloading {url}: {e}")
    return False

CURATION_DATA = {
    # === EPARHIJA BAČKA ===
    'bodjani': [
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/01/manastir.jpg',
            'filename': 'bodjani.jpg',
            'caption': 'Hram Vavedenja Presvete Bogorodice – Manastir Bođani kod Bača'
        },
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/01/freske4.jpg',
            'filename': 'bodjani_gal_1.jpg',
            'caption': 'Čuveni barokni živopis Hristifora Žefarovića iz 1737. godine u hramu Bođana'
        },
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/01/konak.jpg',
            'filename': 'bodjani_gal_2.jpg',
            'caption': 'Manastirski konak, barokni zvonik i uređena porta u Bođanima'
        }
    ],
    'kac': [
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/01/00dobress1rn8.jpg',
            'filename': 'kac.jpg',
            'caption': 'Hram Vaskrsenja Hristovog – Manastir Kać kod Novog Sada'
        },
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/01/00dobress13ka9.jpg',
            'filename': 'kac_gal_1.jpg',
            'caption': 'Pogled na manastirski hram i konak u Kaću'
        },
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/01/00dobress16copybo4.jpg',
            'filename': 'kac_gal_2.jpg',
            'caption': 'Unutrašnjost hrama i ikonostas manastira u Kaću'
        }
    ],
    'kovilj': [
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-kovilj-slika1.jpg',
            'filename': 'kovilj.jpg',
            'caption': 'Monumentalni hram Svetih Arhangela Mihaila i Gavrila – Manastir Kovilj'
        },
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-kovilj-slika2.jpg',
            'filename': 'kovilj_gal_1.jpg',
            'caption': 'Pogled na manastirski kompleks, zvonik i portu u Kovilju'
        },
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-kovilj-slika3.jpg',
            'filename': 'kovilj_gal_2.jpg',
            'caption': 'Raskošni ikonostas rad Aksentija Marodića i unutrašnjost hrama u Kovilju'
        }
    ],
    'sombor': [
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-sombor-1.jpg',
            'filename': 'sombor.jpg',
            'caption': 'Crkva Svetog Prvomučenika Stefana – Manastir Sombor'
        },
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-sombor-2.jpg',
            'filename': 'sombor_gal_1.jpg',
            'caption': 'Srpsko-vizantijska arhitektura i kupola hrama zadužbinara Stevana Konjovića'
        },
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-sombor-3.jpg',
            'filename': 'sombor_gal_2.jpg',
            'caption': 'Zadužbinski kompleks i uređena manastirska porta u Somboru'
        }
    ],
    'vodica': [
        {
            'remote': None,
            'filename': 'vodica.jpg',
            'caption': 'Kapela i svetište Rođenja Presvete Bogorodice na Vodici kod Bača'
        },
        {
            'remote': None,
            'filename': 'vodica_gal_1.jpg',
            'caption': 'Čudotvorni izvor i prirodno okruženje manastira Vodica'
        }
    ],

    # === EPARHIJA BEOGRADSKA ===
    'mislodjin': [
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-mislodjin-1.jpg',
            'filename': 'mislodjin.jpg',
            'caption': 'Hram Svetog Velikomučenika Hristifora – Manastir Mislođin kod Obrenovca'
        },
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-mislodjin-2.jpg',
            'filename': 'mislodjin_gal_1.jpg',
            'caption': 'Pogled na manastirsko zdanje iz vremena kralja Dragutina i novi konak'
        },
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-mislodjin-3.jpg',
            'filename': 'mislodjin_gal_2.jpg',
            'caption': 'Manastirska porta i zvonik u Mislođinu'
        }
    ],
    'rajinovac': [
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-rajinovac-m.jpg',
            'filename': 'rajinovac.jpg',
            'caption': 'Crkva Rođenja Presvete Bogorodice – Manastir Rajinovac u Begaljici'
        },
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-rajinovac-konak.jpg',
            'filename': 'rajinovac_gal_1.jpg',
            'caption': 'Manastirski konak i uređena cvetna porta u Begaljici kod Grocke'
        },
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-rajinovac-ikonostas.jpg',
            'filename': 'rajinovac_gal_2.jpg',
            'caption': 'Ikonostas i unutrašnjost hrama sa čudotvornom ikonom Bogorodice Rajinovačke'
        }
    ],
    'rakovica': [
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-rakovica-manastir.jpg',
            'filename': 'rakovica.jpg',
            'caption': 'Hram Svetog Arhangela Mihaila – Manastir Rakovica u Beogradu'
        },
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-rakovica-dvoriste.jpg',
            'filename': 'rakovica_gal_1.jpg',
            'caption': 'Manastirska porta sa grobnim mestom patrijarha Pavla i Dimitrija'
        },
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-rakovica-cesma.jpg',
            'filename': 'rakovica_gal_2.jpg',
            'caption': 'Česma Svete Petke i manastirski konak u Rakovici'
        }
    ],
    'senjak': [
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-senjak-1.jpg',
            'filename': 'senjak.jpg',
            'caption': 'Hram Vavedenja Presvete Bogorodice – Manastir Vavedenje na Senjaku'
        },
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-senjak-4.jpg',
            'filename': 'senjak_gal_1.jpg',
            'caption': 'Arhitektura hrama zadužbinarke Perside Milenković na Senjaku'
        },
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-senjak-5.jpg',
            'filename': 'senjak_gal_2.jpg',
            'caption': 'Manastirski konak i dvorište pod Topčiderskim brdom u Beogradu'
        }
    ],
    'slanci': [
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-slanci-1.jpg',
            'filename': 'slanci.jpg',
            'caption': 'Hram Svetog Arhiđakona Stefana – Manastir Slanci (metoh Hilandara)'
        },
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-slanci-2.jpg',
            'filename': 'slanci_gal_1.jpg',
            'caption': 'Pogled na manastirski kompleks i hilandarske konake u Slancima'
        },
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-slanci-3.jpg',
            'filename': 'slanci_gal_2.jpg',
            'caption': 'Manastirska porta i zvonik hilandarskog metoha kod Beograda'
        }
    ],
    'trojerucica': [
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-trojerucica-1.jpg',
            'filename': 'trojerucica.jpg',
            'caption': 'Hram Presvete Bogorodice Trojeručice pod Avalom u Ripnju'
        },
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-trojerucica-2.jpg',
            'filename': 'trojerucica_gal_1.jpg',
            'caption': 'Pogled na manastirsko zdanje i zvonik podno Avale'
        },
        {
            'remote': 'https://manastiri.rs/wp-content/uploads/2025/02/manastir-trojerucica-3.jpg',
            'filename': 'trojerucica_gal_2.jpg',
            'caption': 'Manastirska porta i konak manastira Trojeručica'
        }
    ]
}

def process_curation():
    print("=== KURIRANJE SLIKA I OPISA ZA EPARHIJU BAČKU I BEOGRADSKU ===")
    
    for db_path in [DB_STORAGE, DB_DATABASE]:
        if not os.path.exists(db_path):
            continue
        print(f"\nAžuriranje baze: {db_path}")
        conn = sqlite3.connect(db_path)
        cur = conn.cursor()

        for slug, items in CURATION_DATA.items():
            cur.execute("SELECT id, name FROM monasteries WHERE slug = ?", (slug,))
            row = cur.fetchone()
            if not row:
                print(f"Manastir {slug} nije pronađen!")
                continue
            m_id, name = row[0], row[1]
            print(f"Obrada: {name} ({slug})...")

            cur.execute("DELETE FROM monastery_images WHERE monastery_id = ?", (m_id,))

            valid_items = []
            for s_idx, item in enumerate(items, 1):
                dest_fn = item['filename']
                dest_full = os.path.join(IMG_DIR, dest_fn)
                dest_rel = f"images/monasteries/{dest_fn}"

                if item['remote']:
                    download_img(item['remote'], dest_full)

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

    # CSV sync
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
    print("\n✓ EPARHIJA BAČKA I BEOGRADSKA USPEŠNO ZAVRŠENE I SINHRONIZOVANE!")

if __name__ == '__main__':
    process_curation()
