import sys
import os
import sqlite3

sys.stdout.reconfigure(encoding='utf-8')

conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

cursor.execute("""
    SELECT m.eparchy_id, e.name as ep_name, m.id, m.name, m.slug, mi.id, mi.url, mi.caption, mi.sort_order
    FROM monasteries m
    JOIN eparchies e ON m.eparchy_id = e.id
    LEFT JOIN monastery_images mi ON mi.monastery_id = m.id
    WHERE m.eparchy_id IN (12, 13, 14)
    ORDER BY m.eparchy_id, m.id, mi.sort_order
""")
rows = cursor.fetchall()

current_m = None
for ep_id, ep_name, m_id, name, slug, img_id, url, caption, sort_order in rows:
    if current_m != m_id:
        current_m = m_id
        print(f"\n[{ep_name} (ID {ep_id})] #{m_id} {name} (slug: {slug})")
    
    if img_id is None:
        print("    NO IMAGES IN DB!")
        continue
        
    clean_url = (url or '').lstrip('/')
    pub_path = os.path.join('public', clean_url)
    exists = os.path.exists(pub_path)
    file_size = os.path.getsize(pub_path) if exists else 0
    status = f"EXISTS ({file_size} B)" if exists else "MISSING FILE"
    print(f"    - img#{img_id} sort:{sort_order} | url: '{url}' | {status} | caption: '{caption}'")
