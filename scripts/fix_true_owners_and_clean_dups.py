"""
Ensures true major monastery owners retain their authentic images,
while any duplicate copies assigned to other monasteries are removed.
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

def run_fix():
    print("=== DUBINSKO USKLAĐIVANJE PRAVIH VLASNIKA SLIKA ===")

    for db_path in [DB_STORAGE, DB_DATABASE]:
        conn = sqlite3.connect(db_path)
        c = conn.cursor()

        # Re-ensure Ravanica has ravanica_gal_1.jpg
        c.execute("SELECT id FROM monasteries WHERE slug = 'ravanica'")
        r_id = c.fetchone()[0]
        c.execute("SELECT id FROM monastery_images WHERE monastery_id = ? AND url = 'images/monasteries/ravanica_gal_1.jpg'", (r_id,))
        if not c.fetchone():
            c.execute("""
                INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at)
                VALUES (?, 'images/monasteries/ravanica_gal_1.jpg', 'Crkva Vaznesenja Gospodnjeg manastira Ravanica (1375–1377), glavna zadužbina i mauzolej kneza Lazara (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
            """, (r_id,))
            print("  ✓ Vraćena ravanica_gal_1.jpg u Manastir Ravanica")

        # Re-ensure Prohor Pčinjski has prohor-pcinjski_gal_1.jpg
        c.execute("SELECT id FROM monasteries WHERE slug = 'prohor-pcinjski'")
        p_id = c.fetchone()[0]
        c.execute("SELECT id FROM monastery_images WHERE monastery_id = ? AND url = 'images/monasteries/prohor-pcinjski_gal_1.jpg'", (p_id,))
        if not c.fetchone():
            c.execute("""
                INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at)
                VALUES (?, 'images/monasteries/prohor-pcinjski_gal_1.jpg', 'Hram Svetog Prohora Pčinjskog i monumentalni Vranjski konak na reci Pčinji (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
            """, (p_id,))
            print("  ✓ Vraćena prohor-pcinjski_gal_1.jpg u Manastir Prohor Pčinjski")

        # Re-ensure Temska has temska_gal_1.jpg
        c.execute("SELECT id FROM monasteries WHERE slug = 'temska'")
        t_id = c.fetchone()[0]
        c.execute("SELECT id FROM monastery_images WHERE monastery_id = ? AND url = 'images/monasteries/temska_gal_1.jpg'", (t_id,))
        if not c.fetchone():
            c.execute("""
                INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at)
                VALUES (?, 'images/monasteries/temska_gal_1.jpg', 'Crkva Svetog Đorđa manastira Temska iz 14. veka, zadužbina braće Dejanovića na reci Temštici (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
            """, (t_id,))
            print("  ✓ Vraćena temska_gal_1.jpg u Manastir Temska")

        # Delete wrong duplicate copies in other monasteries (Izvor, Kacapun, Manastirak, Mrtvica, Stevanac, Sočanica)
        # Izvor: delete izvor_gal_1.jpg if duplicate
        c.execute("DELETE FROM monastery_images WHERE url IN ('images/monasteries/izvor_gal_1.jpg', 'images/monasteries/kacapun_gal_2.jpg', 'images/monasteries/manastirak-sumadijska_gal_1.jpg', 'images/monasteries/manastirak-sumadijska_gal_2.jpg', 'images/monasteries/mrtvica_gal_1.jpg', 'images/monasteries/stevanac_gal_1.jpg', 'images/monasteries/stevanac_gal_2.jpg', 'images/monasteries/socanica_gal_1.jpg', 'images/monasteries/cirik_gal_1.jpg', 'images/monasteries/cirik_gal_2.jpg', 'images/monasteries/dubnica-milesevska_gal_2.jpg', 'images/monasteries/mazici_gal_2.jpg', 'images/monasteries/uspenje-ovcar-kablar_gal_1.jpg', 'images/monasteries/uspenje-ovcar-kablar_gal_2.jpg')")

        # Re-index sort order
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

    print("✓ Završeno precizno usklađivanje!")

if __name__ == '__main__':
    run_fix()
