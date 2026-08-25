import sys
import sqlite3

sys.stdout.reconfigure(encoding='utf-8')

conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

cursor.execute("""
    SELECT m.eparchy_id, e.name as ep_name, m.slug, m.name, mi.id, mi.url, mi.caption
    FROM monastery_images mi
    JOIN monasteries m ON mi.monastery_id = m.id
    JOIN eparchies e ON m.eparchy_id = e.id
    WHERE m.eparchy_id IN (12, 13, 14)
    ORDER BY m.eparchy_id, m.id, mi.sort_order
""")
for ep_id, ep_name, slug, name, img_id, url, caption in cursor.fetchall():
    print(f"[{ep_name}] {slug} | img#{img_id} | caption: '{caption}'")
