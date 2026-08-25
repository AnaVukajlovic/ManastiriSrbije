import sys
import sqlite3

sys.stdout.reconfigure(encoding='utf-8')

conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

cursor.execute("""
    SELECT m.eparchy_id, e.name as ep_name, m.id, m.slug, m.name, m.image, m.image_url, COUNT(mi.id) as img_count
    FROM monasteries m
    JOIN eparchies e ON m.eparchy_id = e.id
    LEFT JOIN monastery_images mi ON mi.monastery_id = m.id
    WHERE m.eparchy_id IN (12, 13, 14)
    GROUP BY m.id
    ORDER BY m.eparchy_id, m.id
""")
for row in cursor.fetchall():
    print(row)
