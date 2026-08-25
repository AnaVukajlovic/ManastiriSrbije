"""
Master Comprehensive Visual and Database Synchronization:
1. Deletes the invalid historical photo of soldiers in Visoki Decani (visoki-decani_gal_2.jpg).
2. Sets exact, visually verified captions for Visoki Decani:
   - visoki-decani.jpg -> Monumentalna crkva Hrista Pantokratora od dvobojnog mermera
   - visoki-decani_gal_1.jpg -> Čuvena freska Hrista sa mačem iz 14. veka
   - visoki-decani_gal_3.jpg -> Freske Preobraženja Gospodnjeg i Svetog Save Osvećenog na stubu
3. Connects authentic images for Manastir Celije (ID 204, celije-valjevska.jpg, celije-valjevska_gal_2.jpg).
4. Synchronizes both databases and both CSV seeders.
"""
import sqlite3
import os
import io
import sys
import csv

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB_STORAGE = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE = os.path.join(BASE_DIR, 'database', 'database.sqlite')

for db_path in [DB_STORAGE, DB_DATABASE]:
    conn = sqlite3.connect(db_path)
    c = conn.cursor()

    # 1. VISOKI DEČANI KOREKCIJA
    c.execute("SELECT id FROM monasteries WHERE slug LIKE '%decan%'")
    dec_id = c.fetchone()[0]
    c.execute("DELETE FROM monastery_images WHERE monastery_id = ?", (dec_id,))
    c.execute("""
        INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at)
        VALUES 
        (?, 'images/monasteries/visoki-decani.jpg', 'Crkva Hrista Pantokratora manastira Visoki Dečani (1327–1335), carska lavra Stefana Dečanskog i cara Dušana od belog i ružičastog mermera (UNESCO) <small>*(Izvor: UNESCO World Heritage Centre / Vikimedijina ostava)*</small>', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
        (?, 'images/monasteries/visoki-decani_gal_1.jpg', 'Čuvena jedinstvena freska Hrista sa mačem iz 14. veka u naosu manastira Visoki Dečani <small>*(Izvor: Galerija fresaka Narodnog muzeja / Vikimedijina ostava)*</small>', 2, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
        (?, 'images/monasteries/visoki-decani_gal_3.jpg', 'Freska Preobraženja Gospodnjeg i Svetog Save Osvećenog na oltarskom stubu u Visokim Dečanima <small>*(Izvor: Manastir Visoki Dečani / Vikimedijina ostava)*</small>', 3, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
    """, (dec_id, dec_id, dec_id))

    # 2. ĆELIJE KOREKCIJA (ID 204)
    c.execute("SELECT id FROM monasteries WHERE slug = 'celije-valjevska'")
    cel_row = c.fetchone()
    if cel_row:
        cel_id = cel_row[0]
        c.execute("DELETE FROM monastery_images WHERE monastery_id = ?", (cel_id,))
        c.execute("""
            INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at)
            VALUES 
            (?, 'images/monasteries/celije-valjevska.jpg', 'Crkva Svetog arhangela Mihaila manastira Ćelije (krajem 13. veka, kralj Dragutin) sa grobom Svetog ave Justina Popovića <small>*(Izvor: Eparhija valjevska / Vikimedijina ostava)*</small>', 1, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP),
            (?, 'images/monasteries/celije-valjevska_gal_2.jpg', 'Veliki manastirski konak i uređena cvetna porta manastira Ćelije u klisuri reke Gradac <small>*(Izvor: Vikimedijina ostava)*</small>', 2, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
        """, (cel_id, cel_id))

    # 3. Ažuriraj glavnu sliku u tabeli monasteries
    c.execute('SELECT id FROM monasteries')
    m_ids = [r[0] for r in c.fetchall()]
    for mid in m_ids:
        c.execute('SELECT url FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order ASC, id ASC LIMIT 1', (mid,))
        first_img = c.fetchone()
        if first_img:
            c.execute('UPDATE monasteries SET image_url = ? WHERE id = ?', (first_img[0], mid))

    # 4. Re-sort order
    for mid in m_ids:
        c.execute('SELECT id FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order ASC, id ASC', (mid,))
        imgs = [r[0] for r in c.fetchall()]
        for idx, i_id in enumerate(imgs, start=1):
            c.execute('UPDATE monastery_images SET sort_order = ? WHERE id = ?', (idx, i_id))

    conn.commit()
    conn.close()
    print(f"✓ Ažurirana baza: {db_path}")

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
    print(f"✓ Sinhronizovan CSV: {out}")
conn.close()

print("=== SVE IZMENE SU USPEŠNO PRIMENJENE! ===")
