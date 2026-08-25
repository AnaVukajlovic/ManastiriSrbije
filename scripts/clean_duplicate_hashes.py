"""
Remove binary-duplicate images within the same monastery across both databases.
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

for db_path in [DB_STORAGE, DB_DATABASE]:
    conn = sqlite3.connect(db_path)
    c = conn.cursor()
    c.execute('SELECT id, monastery_id, url, sort_order FROM monastery_images ORDER BY monastery_id, sort_order ASC')
    rows = c.fetchall()

    monastery_hashes = {} # m_id -> set of hashes
    to_delete = []

    for img_id, m_id, url, sort_order in rows:
        disk_path = os.path.join(PUBLIC_IMG_DIR, os.path.basename(url))
        if os.path.exists(disk_path):
            with open(disk_path, 'rb') as f:
                h = hashlib.md5(f.read()).hexdigest()
            
            if m_id not in monastery_hashes:
                monastery_hashes[m_id] = set()
            
            if h in monastery_hashes[m_id]:
                to_delete.append(img_id)
            else:
                monastery_hashes[m_id].add(h)

    print(f"Deleting {len(to_delete)} internal duplicates from {db_path}...")
    for img_id in to_delete:
        c.execute('DELETE FROM monastery_images WHERE id = ?', (img_id,))
    
    # Re-normalize sort_order per monastery
    c.execute('SELECT DISTINCT monastery_id FROM monastery_images')
    m_ids = [r[0] for r in c.fetchall()]
    for m_id in m_ids:
        c.execute('SELECT id FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order ASC, id ASC', (m_id,))
        img_ids = [r[0] for r in c.fetchall()]
        for idx, i_id in enumerate(img_ids, start=1):
            c.execute('UPDATE monastery_images SET sort_order = ? WHERE id = ?', (idx, i_id))

    conn.commit()
    conn.close()

# Sinhronizuj CSV seedere
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

print("✓ Uspešno uklonjeni svi interni duplikati i sinhronizovane baze!")
