import sqlite3
import os
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()

c.execute('''
    SELECT m.id, m.name, m.slug, e.name, m.image_url,
           (SELECT COUNT(*) FROM monastery_images WHERE monastery_id = m.id) as img_count
    FROM monasteries m 
    JOIN eparchies e ON m.eparchy_id = e.id 
    ORDER BY e.id, m.id
''')

all_m = c.fetchall()
print(f"Total monasteries in DB: {len(all_m)}")
eparchies = {}
for m in all_m:
    ep = m[3]
    if ep not in eparchies:
        eparchies[ep] = []
    eparchies[ep].append(m)

for ep, ms in eparchies.items():
    print(f"\n--- {ep} ({len(ms)} manastira) ---")
    for m in ms:
        print(f"  ID:{m[0]:3d} | SLUG:{m[2]:25s} | IMGS:{m[5]} | CARD:{m[4]} | NAME:{m[1]}")

conn.close()
