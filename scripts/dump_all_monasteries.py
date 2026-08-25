import sqlite3
import io
import sys

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()
c.execute('SELECT m.id, m.name, m.slug, e.name FROM monasteries m JOIN eparchies e ON m.eparchy_id = e.id ORDER BY e.id, m.name')
for r in c.fetchall():
    print(f"ID={r[0]:3d} | EPARCHY={r[3]:30s} | SLUG={r[2]:30s} | NAME={r[1]}")
conn.close()
