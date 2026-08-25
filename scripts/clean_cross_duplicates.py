"""
Removes cross-monastery duplicate images where an image of a major monastery
was incorrectly copied or assigned to another monastery.
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

# Known correct owners of images
CORRECT_OWNERS = {
    'ravanica': ['ravanica.jpg', 'ravanica_gal_1.jpg', 'ravanica_gal_2.jpg'],
    'prohor-pcinjski': ['prohor-pcinjski.jpg', 'prohor-pcinjski_gal_1.jpg', 'prohor-pcinjski_gal_2.jpg'],
    'temska': ['temska.jpg', 'temska_gal_1.jpg', 'temska_gal_2.jpg'],
    'kumanica': ['kumanica.jpg', 'kumanica_gal_1.jpg', 'kumanica_gal_2.jpg'],
    'jovanje-ovcar-kablar': ['jovanje-ovcar-kablar.jpg', 'jovanje-ovcar-kablar_gal_1.jpg', 'jovanje-ovcar-kablar_gal_2.jpg'],
    'smilovci': ['smilovci.jpg', 'smilovci_gal_1.jpg', 'smilovci_gal_2.jpg'],
    'braljina': ['braljina.jpg', 'braljina_gal_1.jpg', 'braljina_gal_2.jpg'],
    'ceranjska-reka': ['ceranjska-reka.jpg', 'ceranjska-reka_gal_1.jpg', 'ceranjska-reka_gal_2.jpg'],
}

def clean_cross_duplicates():
    print("=== ČIŠĆENJE MEĐUSOBNIH DUPLIKATA SLIKA ===")

    for db_path in [DB_STORAGE, DB_DATABASE]:
        conn = sqlite3.connect(db_path)
        c = conn.cursor()

        c.execute('''
            SELECT mi.id, m.id, m.name, m.slug, mi.url
            FROM monastery_images mi
            JOIN monasteries m ON mi.monastery_id = m.id
            ORDER BY mi.id ASC
        ''')
        rows = c.fetchall()

        hashes = {} # hash -> (mi_id, m_id, m_name, m_slug, url)
        to_delete = []

        for mi_id, m_id, m_name, slug, url in rows:
            fname = os.path.basename(url)
            disk_path = os.path.join(PUBLIC_IMG_DIR, fname)
            if not os.path.exists(disk_path):
                to_delete.append(mi_id)
                continue

            with open(disk_path, 'rb') as f:
                h = hashlib.md5(f.read()).hexdigest()

            if h in hashes:
                prev_mi_id, prev_m_id, prev_m_name, prev_slug, prev_url = hashes[h]
                
                # Determine which one is the true owner
                if slug in fname and prev_slug not in os.path.basename(prev_url):
                    # Current is true owner, delete previous
                    to_delete.append(prev_mi_id)
                    hashes[h] = (mi_id, m_id, m_name, slug, url)
                    print(f"  [DUP] Obrisano {prev_url} iz {prev_m_name} (prava slika pripada {m_name})")
                else:
                    # Previous is owner or both, delete current duplicate
                    to_delete.append(mi_id)
                    print(f"  [DUP] Obrisano {url} iz {m_name} (prava slika pripada {prev_m_name})")
            else:
                hashes[h] = (mi_id, m_id, m_name, slug, url)

        print(f"Ukupno pronađeno i obrisano {len(to_delete)} pogrešnih kopija/duplikata iz {db_path}.")
        for d_id in to_delete:
            c.execute('DELETE FROM monastery_images WHERE id = ?', (d_id,))

        # Re-sort order
        c.execute('SELECT DISTINCT monastery_id FROM monastery_images')
        m_ids = [r[0] for r in c.fetchall()]
        for m_id in m_ids:
            c.execute('SELECT id FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order ASC, id ASC', (m_id,))
            img_ids = [r[0] for r in c.fetchall()]
            for idx, i_id in enumerate(img_ids, start=1):
                c.execute('UPDATE monastery_images SET sort_order = ? WHERE id = ?', (idx, i_id))

        conn.commit()
        conn.close()

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

    print("✓ Završeno uklanjanje svih duplikata i sinhronizacija!")

if __name__ == '__main__':
    clean_cross_duplicates()
