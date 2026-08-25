import sqlite3
import os
import sys

sys.stdout.reconfigure(encoding='utf-8')

conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

cursor.execute('SELECT id, name, image_url FROM monasteries WHERE eparchy_id = 2 ORDER BY id')
monasteries = cursor.fetchall()

print(f"Total monasteries in Eparhija raško-prizrenska: {len(monasteries)}\n")

for m_id, m_name, m_img in monasteries:
    print(f"[{m_id}] {m_name}")
    print(f"  Card image: {m_img}")
    cursor.execute('SELECT id, url, caption, sort_order FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order', (m_id,))
    images = cursor.fetchall()
    print(f"  Gallery images ({len(images)}):")
    for img_id, img_url, caption, sort_order in images:
        file_path = os.path.join('public', img_url.replace('/', os.sep))
        exists = os.path.exists(file_path)
        size = os.path.getsize(file_path) if exists else 0
        print(f"    - ({sort_order}) {img_url} [exists: {exists}, size: {size} bytes]")
        print(f"        Caption: {caption}")
    print("-" * 60)
