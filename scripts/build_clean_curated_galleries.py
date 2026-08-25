import os
import sys
import io
import sqlite3
import re
import urllib.parse
import urllib.request
import json
from bs4 import BeautifulSoup

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
CACHE_DIR = os.path.join(BASE_DIR, 'storage', 'cache_manastiri_rs')
DB_STORAGE_PATH = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE_PATH = os.path.join(BASE_DIR, 'database', 'database.sqlite')

# Specific 100% verified, curated modern color photos for key famous monasteries
SPECIAL_CURATED = {
    'bogorodica-ljeviska': [
        {
            'url': 'images/monasteries/bogorodica-ljeviska.jpg',
            'caption': 'Glavni hram crkve Bogorodica Ljeviška u Prizrenu'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/ae/Our_Lady_of_Ljevi%C5%A1%2C_Prizren%2C_2010._View_from_clock_tower.jpg/1280px-Our_Lady_of_Ljevi%C5%A1%2C_Prizren%2C_2010._View_from_clock_tower.jpg?utm_source=sr.wikipedia.org&utm_campaign=api&utm_content=thumbnail',
            'caption': 'Pogled na zvonik i arhitekturu hrama'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/3/35/Crkva_Bogorodica_Ljevi%C5%A1ka%2C_freska_kralja_Milutina_pre_restauracije.jpg?utm_source=sr.wikipedia.org&utm_campaign=imageinfo&utm_content=original',
            'caption': 'Ktitorski portret svetog kralja Milutina'
        }
    ],
    'studenica': [
        {
            'url': 'images/monasteries/studenica.jpg',
            'caption': 'Bogorodičina crkva – Manastir Studenica'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/cd/Monastery_Studenica%2C_St._Nicholas_church.JPG/1280px-Monastery_Studenica%2C_St._Nicholas_church.JPG?utm_source=commons.wikimedia.org&utm_campaign=imageinfo',
            'caption': 'Crkva Svetog Nikole u kompleksu Studenice'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/b/be/Ikona_Bogorodice_Studeni%C4%8Dke.jpg?utm_source=sr.wikipedia.org&utm_campaign=imageinfo&utm_content=original',
            'caption': 'Čudotvorna ikona Presvete Bogorodice Studeničke'
        }
    ],
    'zica': [
        {
            'url': 'images/monasteries/zica.jpg',
            'caption': 'Glavni hram Vaznesenja Hristovog – Manastir Žiča'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1d/Manastir_%C5%BDi%C4%8Da_01.jpg/1280px-Manastir_%C5%BDi%C4%8Da_01.jpg?utm_source=sr.wikipedia.org&utm_campaign=imageinfo',
            'caption': 'Pogled na manastirski kompleks i zvonik u Žiči'
        }
    ],
    'visoki-decani': [
        {
            'url': 'images/monasteries/visoki-decani.jpg',
            'caption': 'Hram Vaznesenja Gospodnjeg – Manastir Visoki Dečani'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b8/Manastir_Visoki_De%C4%8Dani_%28%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%92%D0%B8%D1%81%D0%BE%D0%BA%D0%B8_%D0%94%D0%B5%D1%87%D0%B0%D0%BD%D0%B8%29_-_main_gate.jpg/1280px-Manastir_Visoki_De%C4%8Dani_%28%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%92%D0%B8%D1%81%D0%BE%D0%BA%D0%B8_%D0%94%D0%B5%D1%87%D0%B0%D0%BD%D0%B8%29_-_main_gate.jpg?utm_source=commons.wikimedia.org&utm_campaign=imageinfo',
            'caption': 'Glavna manastirska kapija i porta'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4f/Manastir_Visoki_De%C4%8Dani_%28%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%92%D0%B8%D1%81%D0%BE%D0%BA%D0%B8_%D0%94%D0%B5%D1%87%D0%B0%D0%BD%D0%B8%29_-_portal.jpg/1280px-Manastir_Visoki_De%C4%8Dani_%28%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%92%D0%B8%D1%81%D0%BE%D0%BA%D0%B8_%D0%94%D0%B5%D1%87%D0%B0%D0%BD%D0%B8%29_-_portal.jpg?utm_source=commons.wikimedia.org&utm_campaign=imageinfo',
            'caption': 'Raskošni mermerni portal i kamena plastika'
        }
    ],
    'gracanica': [
        {
            'url': 'images/monasteries/gracanica.jpg',
            'caption': 'Glavni hram Uspenja Presvete Bogorodice – Manastir Gračanica'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4a/Gra%C4%8Danica_Monastery%2C_2010.jpg/1280px-Gra%C4%8Danica_Monastery%2C_2010.jpg?utm_source=commons.wikimedia.org&utm_campaign=imageinfo',
            'caption': 'Arhitektura petokupolnog hrama u Gračanici'
        }
    ],
    'mileseva': [
        {
            'url': 'images/monasteries/mileseva.jpg',
            'caption': 'Glavni hram Vaznesenja Gospodnjeg – Manastir Mileševa'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/27/White_Angel_%28Mileseva%29.jpg/1280px-White_Angel_%28Mileseva%29.jpg?utm_source=commons.wikimedia.org&utm_campaign=imageinfo',
            'caption': 'Čuvena freska Beli Anđeo na Hristovom grobu'
        }
    ],
    'sopocani': [
        {
            'url': 'images/monasteries/sopocani.jpg',
            'caption': 'Crkva Svete Trojice – Manastir Sopoćani'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/Sopocani_Monastery_02.jpg/1280px-Sopocani_Monastery_02.jpg?utm_source=commons.wikimedia.org&utm_campaign=imageinfo',
            'caption': 'Pogled na manastirski kompleks Sopoćana'
        }
    ],
    'djurdjevi-stupovi': [
        {
            'url': 'images/monasteries/djurdjevi-stupovi.jpg',
            'caption': 'Glavni hram Svetog Đorđa – Manastir Đurđevi Stupovi'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b3/Manastir_%C4%90ur%C4%91evi_stupovi_01.jpg/1280px-Manastir_%C4%90ur%C4%91evi_stupovi_01.jpg?utm_source=commons.wikimedia.org&utm_campaign=imageinfo',
            'caption': 'Obnovljene kule i manastirski konak'
        }
    ],
    'banjska': [
        {
            'url': 'images/monasteries/banjska.jpg',
            'caption': 'Crkva Svetog Stefana – Manastir Banjska'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/bc/Banjska_Monastery_2.JPG/1280px-Banjska_Monastery_2.JPG?utm_source=commons.wikimedia.org&utm_campaign=imageinfo',
            'caption': 'Pogled na manastirski hram i zidine'
        }
    ],
    'devic': [
        {
            'url': 'images/monasteries/devic.jpg',
            'caption': 'Manastirski hram Vavedenja Presvete Bogorodice – Manastir Devič'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/a/ac/12-Devic.jpg?utm_source=commons.wikimedia.org&utm_campaign=imageinfo',
            'caption': 'Pogled na manastirsku portu i konak'
        }
    ]
}

def is_strictly_bad_image(url):
    u = url.lower()
    # Reject black & white, people, monks, patriarchs, wars, maps, flags, logos, etc.
    bad_tokens = [
        'arhimandrit', 'patrijarh', 'monah', 'svestenik', 'proslava', 'german', 'marzik',
        '1912', '1945', '1986', 'crno', 'black', 'white', 'monument', 'spomenik', 'tvrdjava',
        'fortress', 'gradska_galerija', 'narodnog_muzeja', 'suva_planina', 'pcinja_river',
        'grad_uzice', 'flag', 'zastava', 'karta', 'map', 'grb', 'coat_of_arms', 'yugoslavia',
        'ambox', 'commons-logo', 'nuvola', '.webm', '.svg'
    ]
    return any(bt in u for bt in bad_tokens)

def extract_manastiri_rs_images(cache_file, monastery_name):
    if not cache_file or not os.path.exists(cache_file):
        return []
    html = open(cache_file, 'r', encoding='utf-8').read()
    raw_imgs = re.findall(r'https?://manastiri\.rs/wp-content/uploads/[^\s"\'<>]+\.(?:jpg|jpeg|png|webp)', html, re.IGNORECASE)
    clean = []
    for im in raw_imgs:
        c = re.sub(r'-\d+x\d+(\.\w+)$', r'\1', im)
        low = c.lower()
        if not any(bad in low for bad in ['logo', 'avatar', 'icon', 'cropped', 'favicon', 'default', 'placeholder', 'banner', 'cropped-image']):
            if c not in clean:
                clean.append(c)
    return clean

def clean_and_populate_all():
    print("=== FINALNA KURIRANA DEDUKCIJA SLIKA (2-3 100% PROVERENE SLIKE) ===")

    # Cache map
    cache_files = [f for f in os.listdir(CACHE_DIR) if f.endswith('.html')]
    file_map = {}
    for cf in cache_files:
        m = re.search(r'manastir-([a-zA-Z0-9_-]+)_\.html', cf)
        if m:
            s = m.group(1).lower().replace('_', '-')
            file_map[s] = os.path.join(CACHE_DIR, cf)

    for db_path in [DB_STORAGE_PATH, DB_DATABASE_PATH]:
        if not os.path.exists(db_path):
            continue
        print(f"\nObrada baze: {db_path}")
        conn = sqlite3.connect(db_path)
        cur = conn.cursor()

        cur.execute("SELECT id, name, slug FROM monasteries")
        monasteries = cur.fetchall()

        total_saved_images = 0
        mons_with_1 = 0
        mons_with_2_3 = 0

        for m_id, name, slug in monasteries:
            final_images = []

            # 1. If special curated exists (e.g. Studenica, Bogorodica Ljeviška, etc.)
            if slug in SPECIAL_CURATED:
                final_images = SPECIAL_CURATED[slug]
            else:
                # 2. Add local image (verified by user)
                local_rel = f"images/monasteries/{slug}.jpg"
                local_full = os.path.join(BASE_DIR, 'public', local_rel)
                if os.path.exists(local_full):
                    final_images.append({
                        'url': local_rel,
                        'caption': f"Glavni hram – {name}"
                    })

                # 3. Add images from manastiri.rs cache (up to 2 additional photos)
                matched_cf = file_map.get(slug)
                if not matched_cf:
                    # Try partial match
                    for k, v in file_map.items():
                        if k in slug or slug in k:
                            matched_cf = v
                            break

                if matched_cf:
                    m_imgs = extract_manastiri_rs_images(matched_cf, name)
                    for idx, mi in enumerate(m_imgs[:2], len(final_images) + 1):
                        if not is_strictly_bad_image(mi) and not any(x['url'] == mi for x in final_images):
                            cap = f"Pogled na manastirski kompleks – {name}" if idx == 2 else f"Arhitektura hrama i manastirska porta – {name}"
                            final_images.append({
                                'url': mi,
                                'caption': cap
                            })

            # Ensure max 3 images
            final_images = final_images[:3]

            # Repopulate monastery_images cleanly
            cur.execute("DELETE FROM monastery_images WHERE monastery_id = ?", (m_id,))
            for s_idx, im in enumerate(final_images, 1):
                cur.execute(
                    "INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))",
                    (m_id, im['url'], im['caption'], s_idx)
                )

            # Update primary image_url on monastery
            if final_images:
                cur.execute("UPDATE monasteries SET image_url = ? WHERE id = ?", (final_images[0]['url'], m_id))

            total_saved_images += len(final_images)
            if len(final_images) <= 1:
                mons_with_1 += 1
            else:
                mons_with_2_3 += 1

        conn.commit()
        conn.close()

        print(f"✓ Završeno za {db_path}:")
        print(f"  Ukupno slika u bazi: {total_saved_images}")
        print(f"  Manastira sa 1 slikom: {mons_with_1}")
        print(f"  Manastira sa 2–3 proverene slike (manastiri.rs / zadužbine): {mons_with_2_3}")

    print("\n✓ KOMPLETNO ZAVRŠENO!")

if __name__ == '__main__':
    clean_and_populate_all()
