import sqlite3
import os
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()
c.execute('SELECT id, slug, name, image_url FROM monasteries')
monasteries = c.fetchall()

no_local = []
small_local = []
no_gallery = []
has_gallery = []

for m_id, slug, name, img_url in monasteries:
    local_path = f'public/images/monasteries/{slug}.jpg'
    exists = os.path.exists(local_path)
    size = os.path.getsize(local_path) if exists else 0
    c.execute('SELECT url, caption FROM monastery_images WHERE monastery_id = ?', (m_id,))
    gallery = c.fetchall()
    
    if not exists:
        no_local.append((m_id, slug, name, img_url))
    elif size < 5000:
        small_local.append((m_id, slug, name, size))
        
    if len(gallery) == 0:
        no_gallery.append((m_id, slug, name, img_url, exists))
    else:
        has_gallery.append((m_id, slug, name, len(gallery)))

print(f"Ukupno manastira: {len(monasteries)}")
print(f"Nema lokalnu sliku public/images/monasteries/{{slug}}.jpg: {len(no_local)}")
print(f"Ima premalu lokalnu sliku (<5KB): {len(small_local)}")
print(f"Nema galeriju (monastery_images = 0): {len(no_gallery)}")
print(f"Ima galeriju (monastery_images > 0): {len(has_gallery)}")

print("\n--- Manastiri bez lokalne slike: ---")
for m in no_local:
    print(f"  [{m[0]}] {m[1]} - {m[2]} (img_url: {m[3]})")

print("\n--- Bogorodica Ljeviška status: ---")
c.execute("SELECT id, slug, name, image_url FROM monasteries WHERE slug LIKE '%ljevisk%' OR name LIKE '%Ljevišk%'")
for r in c.fetchall():
    print(r)
    loc = f"public/images/monasteries/{r[1]}.jpg"
    print("  Local file exists:", os.path.exists(loc))
    c.execute("SELECT * FROM monastery_images WHERE monastery_id = ?", (r[0],))
    print("  Monastery images:", c.fetchall())

conn.close()
