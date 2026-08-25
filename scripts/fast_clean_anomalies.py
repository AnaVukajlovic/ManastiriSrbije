"""
Fast and thorough anomaly cleaner:
1. Removes any non-monastery photos (memorial busts, soldiers, non-monastery objects).
2. Removes any cross-duplicates.
3. Verifies every image.
"""
import sqlite3
import os
import hashlib
import io
import sys
import csv

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB_STORAGE = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE = os.path.join(BASE_DIR, 'database', 'database.sqlite')
PUBLIC_IMG_DIR = os.path.join(BASE_DIR, 'public', 'images', 'monasteries')

print("=== SKENIRANJE I ČIŠĆENJE ANOMALIJA ===", flush=True)

# Problematic filenames / anomalies
ANOMALY_PATTERNS = [
    'karanovi', 'vojnik', 'bista', 'kapetan', 'spomenik_palim', 'partizan', 'ratnik'
]

for db_path in [DB_STORAGE, DB_DATABASE]:
    conn = sqlite3.connect(db_path)
    c = conn.cursor()
    c.execute('''
        SELECT mi.id, m.id, m.name, m.slug, mi.url, mi.caption
        FROM monastery_images mi
        JOIN monasteries m ON mi.monastery_id = m.id
        ORDER BY mi.id ASC
    ''')
    rows = c.fetchall()

    to_delete = []
    seen_hashes = {}

    for mi_id, m_id, m_name, slug, url, caption in rows:
        fname = os.path.basename(url)
        disk_path = os.path.join(PUBLIC_IMG_DIR, fname)

        if not os.path.exists(disk_path):
            to_delete.append(mi_id)
            continue

        # Check anomalies in caption and filename
        check_str = (url + " " + (caption or "")).lower()
        if any(pat in check_str for pat in ANOMALY_PATTERNS):
            print(f"  [ANOMALIJA] Uklanjam {url} ({caption}) iz {m_name}", flush=True)
            to_delete.append(mi_id)
            continue

        # Check hash duplicate
        with open(disk_path, 'rb') as f:
            h = hashlib.md5(f.read()).hexdigest()

        if h in seen_hashes:
            prev_info = seen_hashes[h]
            print(f"  [DUPLIKAT] Uklanjam {url} ({m_name}), već postoji u {prev_info}", flush=True)
            to_delete.append(mi_id)
        else:
            seen_hashes[h] = f"{m_name} ({url})"

    for d_id in to_delete:
        c.execute('DELETE FROM monastery_images WHERE id = ?', (d_id,))

    # Fix primary image_url in monasteries table if deleted
    c.execute('SELECT id, image_url FROM monasteries')
    for m_row in c.fetchall():
        mid, cur_img = m_row
        c.execute('SELECT url FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order ASC LIMIT 1', (mid,))
        first_img = c.fetchone()
        if first_img:
            c.execute('UPDATE monasteries SET image_url = ? WHERE id = ?', (first_img[0], mid))

    # Re-sort order
    c.execute('SELECT DISTINCT monastery_id FROM monastery_images')
    m_ids = [r[0] for r in c.fetchall()]
    for mid in m_ids:
        c.execute('SELECT id FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order ASC, id ASC', (mid,))
        img_ids = [r[0] for r in c.fetchall()]
        for idx, i_id in enumerate(img_ids, start=1):
            c.execute('UPDATE monastery_images SET sort_order = ? WHERE id = ?', (idx, i_id))

    conn.commit()
    conn.close()
    print(f"✓ Završeno za {db_path} (obrisano {len(to_delete)} stavki).", flush=True)

# Sinhronizuj CSV
conn = sqlite3.connect(DB_STORAGE)
c = conn.cursor()
c.execute('SELECT * FROM monasteries')
cols = [d[0] for d in c.description]
rows = c.fetchall()
for out in ['storage/app/import/monasteries.csv', 'database/seeders/data/monasteries.csv']:
    out_path = os.path.join(BASE_DIR, out.replace('/', os.sep))
    with open(out_path, 'w', encoding='utf-8-sig', newline='') as f:
        w = csv.writer(f, delimiter=';')
        w.writerow(cols)
        for r in rows:
            w.writerow([str(x).replace(';', ',') if x is not None else '' for r_item in r for x in [r_item]])
conn.close()

print("=== SVE ANOMALIJE SU USPEŠNO OČIŠĆENE! ===", flush=True)
