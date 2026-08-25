"""
Audit every image in monastery_images table and check its existence on disk,
empty captions, generic captions, and source attribution.
"""
import sqlite3
import os
import io
import sys

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB_STORAGE = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
PUBLIC_DIR = os.path.join(BASE_DIR, 'public')

conn = sqlite3.connect(DB_STORAGE)
c = conn.cursor()

c.execute('''
    SELECT m.id, m.name, m.slug, e.name, mi.url, mi.caption, mi.sort_order, m.image_url
    FROM monasteries m
    JOIN eparchies e ON m.eparchy_id = e.id
    LEFT JOIN monastery_images mi ON m.id = mi.monastery_id
    ORDER BY e.id, m.id, mi.sort_order
''')

rows = c.fetchall()

missing_files = []
no_source_captions = []
empty_captions = []
monasteries_with_images = set()
total_images = 0

for r in rows:
    m_id, m_name, m_slug, ep_name, img_url, caption, sort_order, card_url = r
    if img_url:
        total_images += 1
        monasteries_with_images.add(m_id)
        
        # Check disk existence
        disk_path = os.path.join(PUBLIC_DIR, img_url.replace('/', os.sep))
        if not os.path.exists(disk_path):
            missing_files.append((m_name, img_url, disk_path))
            
        # Check caption quality
        if not caption or caption.strip() == '':
            empty_captions.append((m_name, img_url))
        elif '(Izvor:' not in caption:
            no_source_captions.append((m_name, img_url, caption))

print(f"=== REZULTATI AUDITA ===")
print(f"Ukupno manastira u bazi: 259")
print(f"Manastira sa galerijom: {len(monasteries_with_images)}")
print(f"Ukupno slika u bazi: {total_images}")
print(f"Nedostajući fajlovi na disku: {len(missing_files)}")
print(f"Slike bez opisa: {len(empty_captions)}")
print(f"Slike bez izvora (Izvor: ...): {len(no_source_captions)}")

if missing_files:
    print("\n[UPOZORENJE] Nedostajući fajlovi:")
    for item in missing_files[:10]:
        print(" ", item)

if empty_captions:
    print("\n[UPOZORENJE] Prazni opisi:")
    for item in empty_captions[:10]:
        print(" ", item)

if no_source_captions:
    print(f"\n[UPOZORENJE] Slike bez navedenog izvora ({len(no_source_captions)}):")
    for item in no_source_captions[:15]:
        print(f"  {item[0]} ({item[1]}): {item[2]}")

conn.close()
