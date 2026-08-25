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
    c.execute("SELECT m.id, m.name, m.slug FROM monasteries m JOIN eparchies e ON m.eparchy_id = e.id WHERE e.slug = ? ORDER BY m.id", (ep,))
    rows = c.fetchall()
    for m_id, name, slug in rows:
        c.execute("SELECT url, caption, sort_order FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order", (m_id,))
        imgs = c.fetchall()
        print(f"\n• {name} ({slug}) - {len(imgs)} slika:")
        for im in imgs:
            print(f"    [{im[2]}] {im[0]}")
            print(f"        -> {im[1]}")
