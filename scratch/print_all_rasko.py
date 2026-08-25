import sqlite3
import sys

sys.stdout.reconfigure(encoding='utf-8')

conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

cursor.execute('SELECT id, name, image_url FROM monasteries WHERE eparchy_id = 2 ORDER BY id')
monasteries = cursor.fetchall()

print(f"Total: {len(monasteries)}")
for m_id, m_name, m_img in monasteries:
    cursor.execute('SELECT url, caption, sort_order FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order', (m_id,))
    imgs = cursor.fetchall()
    print(f"\nID: {m_id} | Name: {m_name} | Card: {m_img}")
    for url, cap, so in imgs:
        print(f"   [{so}] {url}")
        print(f"        {cap}")
