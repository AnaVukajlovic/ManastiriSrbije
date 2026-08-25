import sqlite3
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()

for ep in ['eparhija-backa', 'eparhija-beogradska']:
    print(f"\n========================================================")
    print(f"  EPARHIJA: {ep.upper()}")
    print(f"========================================================")
    c.execute("SELECT m.id, m.name, m.slug, m.city, m.ktitor, m.godina_izgradnje FROM monasteries m JOIN eparchies e ON m.eparchy_id = e.id WHERE e.slug = ? ORDER BY m.id", (ep,))
    rows = c.fetchall()
    print(f"Ukupno manastira: {len(rows)}")
    for r in rows:
        print(f"ID: {r[0]:3d} | {r[1]:<35} | slug: {r[2]:<25} | mesto: {r[3] or 'N/A'}")
