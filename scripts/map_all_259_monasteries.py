import sqlite3
import os
import io
import sys

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()

c.execute('''
    SELECT m.id, m.name, m.slug, e.name, m.image_url
    FROM monasteries m
    JOIN eparchies e ON m.eparchy_id = e.id
    ORDER BY m.id
''')
monasteries = c.fetchall()

print(f"Total monasteries: {len(monasteries)}")

# Check what files exist in public/images/monasteries for each slug/id
images_dir = os.path.join(BASE_DIR, 'public', 'images', 'monasteries')
all_files = set(os.listdir(images_dir))

for m in monasteries:
    m_id, name, slug, ep, card_url = m
    # find all matching files
    matched = [f for f in all_files if f.startswith(slug) and f.endswith(('.jpg', '.png', '.webp'))]
    print(f"ID={m_id:3d} | EPARCHY={ep:25s} | SLUG={slug:25s} | MATCHED_FILES={len(matched)} | {matched} | NAME={name}")

conn.close()
