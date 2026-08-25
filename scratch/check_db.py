import sqlite3
import json

conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

cursor.execute("SELECT name FROM sqlite_master WHERE type='table';")
tables = [row[0] for row in cursor.fetchall()]
print('Tables:', tables)

# Check monastery_images or similar tables
for t in ['monasteries', 'monastery_images', 'images']:
    if t in tables:
        cursor.execute(f"PRAGMA table_info({t});")
        print(f"\nColumns in {t}:", [c[1] for c in cursor.fetchall()])

# Check the specific monasteries
monastery_slugs = [
    'grliste', 'krepicevac', 'lapusnja', 'lozica', 'vratna', 'suvodol',
    'lelic', 'bogovadja', 'dokmir', 'grabovac', 'ribnica', 'pluzac', 'jovanja'
]

print("\n--- Monastery Check ---")
for slug in monastery_slugs:
    cursor.execute("SELECT id, name, slug, eparchy, main_image, image_url FROM monasteries WHERE slug LIKE ? OR name LIKE ?", (f"%{slug}%", f"%{slug}%"))
    rows = cursor.fetchall()
    print(f"Slug query '{slug}':", rows)
