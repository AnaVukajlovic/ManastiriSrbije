import sqlite3
import sys

sys.stdout.reconfigure(encoding='utf-8')

conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

cursor.execute('SELECT id, name FROM eparchies')
eparchies = cursor.fetchall()
for ep_id, ep_name in eparchies:
    if 'rašk' in ep_name.lower() or 'prizren' in ep_name.lower() or 'rask' in ep_name.lower():
        print(f"=== Eparhija {ep_id}: {ep_name} ===")
        cursor.execute('SELECT id, name, image_url FROM monasteries WHERE eparchy_id = ? ORDER BY id', (ep_id,))
        monasteries = cursor.fetchall()
        for m_id, m_name, m_img in monasteries:
            print(f"\n[{m_id}] {m_name} | Card: {m_img}")
            cursor.execute('SELECT id, url, caption, sort_order FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order', (m_id,))
            images = cursor.fetchall()
            for img_id, img_url, caption, sort_order in images:
                print(f"    - ({sort_order}) {img_url} => {caption}")
