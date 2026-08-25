import os
import sqlite3
import json
import sys

sys.stdout.reconfigure(encoding='utf-8')

conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

cursor.execute("""
    SELECT e.id as ep_id, e.name as ep_name, m.id as m_id, m.name as m_name, m.slug as m_slug, mi.id as img_id, mi.url, mi.caption, mi.sort_order
    FROM eparchies e
    JOIN monasteries m ON m.eparchy_id = e.id
    LEFT JOIN monastery_images mi ON mi.monastery_id = m.id
    ORDER BY e.id, m.id, mi.sort_order
""")
rows = cursor.fetchall()

eparchies_data = {}
missing_files = []

for ep_id, ep_name, m_id, m_name, m_slug, img_id, url, caption, sort_order in rows:
    if ep_name not in eparchies_data:
        eparchies_data[ep_name] = {}
    if m_name not in eparchies_data[ep_name]:
        eparchies_data[ep_name][m_name] = {
            'id': m_id,
            'slug': m_slug,
            'images': []
        }
    if img_id is not None:
        clean_u = (url or '').lstrip('/')
        disk_p = os.path.join('public', clean_u)
        exists = os.path.exists(disk_p)
        size = os.path.getsize(disk_p) if exists else 0
        if not exists:
            missing_files.append((ep_name, m_name, url))
        eparchies_data[ep_name][m_name]['images'].append({
            'img_id': img_id,
            'url': url,
            'caption': caption,
            'sort_order': sort_order,
            'exists': exists,
            'size': size
        })

print(f"Total Eparchies: {len(eparchies_data)}")
print(f"Missing image files across entire database: {len(missing_files)}")
for ep, m, u in missing_files:
    print(f"  ✗ [{ep}] {m} -> {u}")

# Save full export to JSON for easy viewing
with open('scratch/all_eparchies_gallery_images.json', 'w', encoding='utf-8') as f:
    json.dump(eparchies_data, f, ensure_ascii=False, indent=2)

print("\nSaved complete overview to 'scratch/all_eparchies_gallery_images.json'")
