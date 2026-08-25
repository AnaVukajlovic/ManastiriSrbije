import sqlite3
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()
c.execute("SELECT m.id, m.name, m.slug, m.city, m.ktitor, m.godina_izgradnje FROM monasteries m JOIN eparchies e ON m.eparchy_id = e.id WHERE e.slug = 'eparhija-banatska' ORDER BY m.id")
rows = c.fetchall()
print(f"Total monasteries in Eparhija banatska: {len(rows)}")
for r in rows:
    print(f"ID: {r[0]} | Naziv: {r[1]} | Slug: {r[2]} | Mesto: {r[3]} | Ktitor: {r[4]} | Godina: {r[5]}")

print("\nTrenutne slike i opisi:")
for r in rows:
    m_id, name, slug = r[0], r[1], r[2]
    c.execute("SELECT url, caption, sort_order FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order", (m_id,))
    imgs = c.fetchall()
    print(f"\n--- {name} ({slug}) ---")
    for im in imgs:
        print(f"  [{im[2]}] URL: {im[0]}")
        print(f"      Opis: {im[1]}")
