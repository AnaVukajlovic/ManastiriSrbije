import os
import glob
import sqlite3
import sys

sys.stdout.reconfigure(encoding='utf-8')

conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

cursor.execute("""
    SELECT id, name, slug, image_url
    FROM monasteries
    WHERE eparchy_id = 9 OR id BETWEEN 172 AND 184
    ORDER BY id
""")
rows = cursor.fetchall()
print(f"Pronađeno {len(rows)} manastira Šabačke eparhije u bazi:")

for r in rows:
    m_id, name, slug, img_url = r
    # Find matching files in public/images/monasteries
    files = glob.glob(f"public/images/monasteries/{slug}*.jpg") + glob.glob(f"public/images/monasteries/{slug}*.png")
    files = [os.path.basename(f) for f in files]
    print(f"ID {m_id}: {name} ({slug})")
    print(f"   Trenutni image_url: {img_url}")
    print(f"   Fajlovi na disku: {files}")
