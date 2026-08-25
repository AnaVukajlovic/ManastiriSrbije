import sqlite3
import sys
import io
import os

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()

# Check tables
c.execute("SELECT name FROM sqlite_master WHERE type='table'")
print("Tables in storage/database.sqlite:", [r[0] for r in c.fetchall()])

# Check monasteries columns
c.execute("PRAGMA table_info(monasteries)")
print("Monasteries columns:", [r[1] for r in c.fetchall()])

# Check monastery_images columns
c.execute("PRAGMA table_info(monastery_images)")
print("Monastery_images columns:", [r[1] for r in c.fetchall()])

# Check how many images in monastery_images per monastery
c.execute("""
    SELECT m.id, m.slug, m.name, m.image_url, COUNT(mi.id) as img_count
    FROM monasteries m
    LEFT JOIN monastery_images mi ON m.id = mi.monastery_id
    GROUP BY m.id
    ORDER BY img_count ASC, m.name ASC
""")
rows = c.fetchall()
print(f"\nUkupno manastira u bazi: {len(rows)}")

zero_images = [r for r in rows if r[4] == 0]
has_one = [r for r in rows if r[4] == 1]
print(f"Manastiri sa 0 slika u galeriji (monastery_images): {len(zero_images)}")
print(f"Manastiri sa 1 slikom u galeriji: {len(has_one)}")

print("\n--- Manastiri sa 0 slika (prvih 30): ---")
for r in zero_images[:30]:
    print(f"[{r[0]}] {r[1]} - {r[2]} | main image_url: {r[3]}")

# Check local files in public/images/monasteries
local_dir = 'public/images/monasteries'
if os.path.exists(local_dir):
    files = os.listdir(local_dir)
    print(f"\nUkupno lokalnih slika u {local_dir}: {len(files)}")
