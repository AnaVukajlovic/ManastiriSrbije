import sqlite3
import os
import io
import sys

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()

# Query all monasteries that have monastery_images records with empty caption OR no monastery_images records
c.execute('''
    SELECT m.id, m.name, m.slug, e.name, mi.url, mi.caption
    FROM monasteries m
    JOIN eparchies e ON m.eparchy_id = e.id
    LEFT JOIN monastery_images mi ON m.id = mi.monastery_id
    WHERE mi.caption IS NULL OR mi.caption = '' OR mi.caption NOT LIKE '%(Izvor:%'
    ORDER BY e.id, m.id
''')
unverified = c.fetchall()

print(f"Unverified or missing caption rows: {len(unverified)}")
grouped = {}
for r in unverified:
    m_id, name, slug, ep, url, cap = r
    if m_id not in grouped:
        grouped[m_id] = {'name': name, 'slug': slug, 'ep': ep, 'urls': []}
    if url:
        grouped[m_id]['urls'].append(url)

for m_id, data in grouped.items():
    print(f"ID={m_id:3d} | EPARCHY={data['ep']:25s} | SLUG={data['slug']:25s} | NAME={data['name']}")
    for u in data['urls']:
        print(f"    {u}")

conn.close()
