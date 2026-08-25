import os
import sys
import io
import sqlite3
import re
import urllib.request
import urllib.parse
import time

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
IMG_DIR = os.path.join(BASE_DIR, 'public', 'images', 'monasteries')
DB_STORAGE_PATH = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE_PATH = os.path.join(BASE_DIR, 'database', 'database.sqlite')

HEADERS = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
    'Accept': 'image/avif,image/webp,image/apng,image/svg+xml,image/*,*/*;q=0.8',
    'Accept-Language': 'sr,en-US;q=0.9,en;q=0.8',
}

def download_image(url, dest_path):
    if not url:
        return False
    try:
        req = urllib.request.Request(url, headers=HEADERS)
        with urllib.request.urlopen(req, timeout=12) as response:
            if response.status == 200:
                data = response.read()
                # Must be at least 3KB to be a real image
                if len(data) > 3072:
                    with open(dest_path, 'wb') as f:
                        f.write(data)
                    return True
    except Exception as e:
        # Retry once with clean decoded URL
        try:
            clean_url = urllib.parse.unquote(url)
            req2 = urllib.request.Request(clean_url, headers=HEADERS)
            with urllib.request.urlopen(req2, timeout=12) as response:
                if response.status == 200:
                    data = response.read()
                    if len(data) > 3072:
                        with open(dest_path, 'wb') as f:
                            f.write(data)
                        return True
        except Exception:
            pass
    return False

def localize_all():
    print("=== POKRETANJE LOKALIZACIJE I VERIFIKACIJE SVIH SLIKA (100% LOKALNO) ===")
    os.makedirs(IMG_DIR, exist_ok=True)

    conn = sqlite3.connect(DB_STORAGE_PATH)
    cur = conn.cursor()

    cur.execute("SELECT id, name, slug, image_url FROM monasteries ORDER BY id")
    monasteries = cur.fetchall()

    print(f"Pronađeno {len(monasteries)} manastira u bazi.")

    success_count = 0
    gallery_downloaded = 0

    for idx, (m_id, name, slug, main_img) in enumerate(monasteries, 1):
        if idx % 50 == 0 or idx == len(monasteries):
            print(f"Procesuirano: {idx}/{len(monasteries)} manastira...")

        # 1. Provera primarne slike
        local_main_filename = f"{slug}.jpg"
        local_main_path = os.path.join(IMG_DIR, local_main_filename)
        local_main_rel = f"images/monasteries/{local_main_filename}"

        if not os.path.exists(local_main_path) or os.path.getsize(local_main_path) < 1024:
            # Pokušaj preuzimanja sa main_img ako je remote URL
            if main_img and (main_img.startswith('http://') or main_img.startswith('https://')):
                download_image(main_img, local_main_path)

        # 2. Provera i preuzimanje galerijskih slika
        cur.execute("SELECT id, url, caption, sort_order FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order, id", (m_id,))
        raw_gallery = cur.fetchall()

        curated_gallery = []

        # Dodaj primarnu sliku kao prvu u galeriji ako postoji lokalno
        if os.path.exists(local_main_path) and os.path.getsize(local_main_path) > 1024:
            curated_gallery.append({
                'url': local_main_rel,
                'caption': f"Glavni hram – {name}"
            })

        for g_idx, (g_id, g_url, g_cap, g_so) in enumerate(raw_gallery, 1):
            if not g_url:
                continue

            # Ako je već lokalna primarna slika, preskoči duplikat
            if g_url == local_main_rel or g_url == f"images/monasteries/{slug}.jpg":
                continue

            # Ako je lokalni fajl
            if g_url.startswith('images/monasteries/'):
                check_path = os.path.join(BASE_DIR, 'public', g_url)
                if os.path.exists(check_path) and os.path.getsize(check_path) > 1024:
                    if not any(x['url'] == g_url for x in curated_gallery):
                        curated_gallery.append({
                            'url': g_url,
                            'caption': g_cap or f"Pogled na manastirski kompleks – {name}"
                        })
                continue

            # Ako je remote URL (https://...), preuzmi ga lokalno!
            if g_url.startswith('http://') or g_url.startswith('https://'):
                local_gal_filename = f"{slug}_gal_{len(curated_gallery)}.jpg"
                local_gal_path = os.path.join(IMG_DIR, local_gal_filename)
                local_gal_rel = f"images/monasteries/{local_gal_filename}"

                # Preuzmi ako već ne postoji
                if not os.path.exists(local_gal_path) or os.path.getsize(local_gal_path) < 1024:
                    ok = download_image(g_url, local_gal_path)
                    if ok:
                        gallery_downloaded += 1
                        curated_gallery.append({
                            'url': local_gal_rel,
                            'caption': g_cap or f"Pogled na manastirski kompleks – {name}"
                        })
                else:
                    curated_gallery.append({
                        'url': local_gal_rel,
                        'caption': g_cap or f"Pogled na manastirski kompleks – {name}"
                    })

        # Ograniči na najviše 3 slike
        curated_gallery = curated_gallery[:3]

        # Ažuriraj bazu
        cur.execute("DELETE FROM monastery_images WHERE monastery_id = ?", (m_id,))
        for s_idx, item in enumerate(curated_gallery, 1):
            cur.execute(
                "INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))",
                (m_id, item['url'], item['caption'], s_idx)
            )

        if curated_gallery:
            cur.execute("UPDATE monasteries SET image_url = ? WHERE id = ?", (curated_gallery[0]['url'], m_id))

        success_count += 1

    conn.commit()
    conn.close()

    print(f"\n✓ Lokalizacija završena!")
    print(f"  Preuzeto i sačuvano dodatnih galerijskih slika: {gallery_downloaded}")
    print(f"  Ukupno obrađeno manastira: {success_count}")

    # Sinhronizuj drugu bazu
    sync_secondary_db()

def sync_secondary_db():
    if not os.path.exists(DB_DATABASE_PATH):
        return
    print("\nSinhronizacija sekundarne baze...")
    conn_src = sqlite3.connect(DB_STORAGE_PATH)
    conn_dst = sqlite3.connect(DB_DATABASE_PATH)
    
    cur_src = conn_src.cursor()
    cur_dst = conn_dst.cursor()

    cur_dst.execute("DELETE FROM monastery_images")
    cur_src.execute("SELECT id, monastery_id, url, caption, sort_order, created_at, updated_at FROM monastery_images")
    rows = cur_src.fetchall()
    cur_dst.executemany("INSERT INTO monastery_images VALUES (?, ?, ?, ?, ?, ?, ?)", rows)

    cur_src.execute("SELECT id, image_url FROM monasteries")
    m_rows = cur_src.fetchall()
    for m_id, img_url in m_rows:
        cur_dst.execute("UPDATE monasteries SET image_url = ? WHERE id = ?", (img_url, m_id))

    conn_dst.commit()
    conn_dst.close()
    conn_src.close()
    print("✓ Sekundarna baza uspešno sinhronizovana!")

if __name__ == '__main__':
    localize_all()
