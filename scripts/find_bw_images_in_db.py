import sqlite3
import os
from PIL import Image, ImageStat
import io
import sys

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()
c.execute('SELECT mi.id, m.id, m.name, mi.url, mi.caption FROM monastery_images mi JOIN monasteries m ON mi.monastery_id = m.id')
rows = c.fetchall()
conn.close()

bw_list = []
for img_id, m_id, m_name, url, caption in rows:
    p = os.path.join('public', 'images', 'monasteries', os.path.basename(url))
    if not os.path.exists(p):
        continue
    try:
        with Image.open(p) as img:
            rgb = img.convert('RGB')
            # Check 100 sample pixels or stat
            stat = ImageStat.Stat(rgb)
            r, g, b = stat.mean[:3]
            diff = abs(r - g) + abs(g - b) + abs(r - b)
            if diff < 6: # Grayscale / Black & white
                bw_list.append((img_id, m_id, m_name, url, caption, diff, img.size))
    except Exception as e:
        print(f"Error reading {p}: {e}")

print(f"Total Black & White / Grayscale images in DB: {len(bw_list)}")
for item in bw_list:
    print(f"ID: {item[0]} | Monastery: {item[2]} (ID {item[1]}) | URL: {item[3]} | Size: {item[6]} | Diff: {item[5]:.2f}")
    print(f"  Caption: {item[4]}")
