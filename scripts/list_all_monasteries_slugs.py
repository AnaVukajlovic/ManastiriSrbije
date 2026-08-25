import sqlite3
import io
import sys

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()
c.execute('SELECT m.id, m.name, m.slug, e.name, m.image_url FROM monasteries m JOIN eparchies e ON m.eparchy_id = e.id ORDER BY e.id, m.id')
rows = c.fetchall()

print(f"Total monasteries: {len(rows)}")
for r in rows:
    print(f"ID: {r[0]} | Eparchy: {r[3]} | Name: {r[1]} | Slug: {r[2]} | Image: {r[4]}")

conn.close()
