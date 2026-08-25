import sqlite3
import os

conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

cursor.execute('SELECT id, name, image_url FROM monasteries WHERE eparchy_id = 2 ORDER BY id')
monasteries = cursor.fetchall()

all_images = []
for m_id, m_name, m_card in monasteries:
    cursor.execute('SELECT url FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order', (m_id,))
    urls = [x[0] for x in cursor.fetchall()]
    all_images.append((m_id, m_name, m_card, urls))

print(f"Total monasteries: {len(all_images)}")
total_img_count = sum(len(x[3]) for x in all_images)
print(f"Total gallery images: {total_img_count}")

with open("scratch/rasko_image_list.txt", "w", encoding="utf-8") as f:
    for m_id, m_name, m_card, urls in all_images:
        f.write(f"[{m_id}] {m_name}\n")
        f.write(f"  Card: {m_card}\n")
        for u in urls:
            f.write(f"  - {u}\n")
        f.write("\n")

print("Saved scratch/rasko_image_list.txt")
