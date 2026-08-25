import sqlite3
import os
import io
import sys

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')
conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()
c.execute("SELECT id, name, slug, eparchy_id, image_url FROM monasteries WHERE slug LIKE '%celij%' OR name LIKE '%Ćelij%'")
for r in c.fetchall():
    print(r)
conn.close()

files = [f for f in os.listdir('public/images/monasteries') if 'celij' in f]
print("Files on disk for celije:", files)
