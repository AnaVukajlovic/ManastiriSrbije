import sqlite3
import os
import io
import sys

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()

c.execute('''
    SELECT m.id, m.name, m.slug, e.name, mi.url, mi.caption, mi.sort_order
    FROM monasteries m
    JOIN eparchies e ON m.eparchy_id = e.id
    LEFT JOIN monastery_images mi ON m.id = mi.monastery_id
    WHERE mi.caption IS NULL OR mi.caption = '' OR mi.url IS NULL
    ORDER BY e.id, m.id
''')

rows = c.fetchall()
print(f"Total rows with empty/null captions: {len(rows)}")

seen = {}
for r in rows:
    m_id, name, slug, ep, url, cap, so = r
    if m_id not in seen:
        seen[m_id] = {'name': name, 'slug': slug, 'ep': ep, 'urls': []}
    if url:
        seen[m_id]['urls'].append(url)

for m_id, data in seen.items():
    print(f"ID={m_id:3d} | EPARCHY={data['ep']:30s} | SLUG={data['slug']:30s} | NAME={data['name']}")
    for u in data['urls']:
        print(f"    URL: {u}")

conn.close()
