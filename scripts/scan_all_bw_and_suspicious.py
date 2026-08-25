import os
import sqlite3
from PIL import Image, ImageStat
import io
import sys

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()
c.execute('''
    SELECT mi.id, m.id, m.name, m.slug, mi.url, mi.caption
    FROM monastery_images mi
    JOIN monasteries m ON mi.monastery_id = m.id
    ORDER BY m.id ASC, mi.sort_order ASC
''')
rows = c.fetchall()
conn.close()

print(f"Total rows in DB: {len(rows)}")

bw_or_suspicious = []

for mi_id, m_id, m_name, slug, url, caption in rows:
    p = os.path.join('public', 'images', 'monasteries', os.path.basename(url))
    if not os.path.exists(p):
        print(f"[MISSING] {p}")
        continue
    try:
        with Image.open(p) as img:
            rgb = img.convert('RGB')
            stat = ImageStat.Stat(rgb)
            r, g, b = stat.mean[:3]
            diff = abs(r - g) + abs(g - b) + abs(r - b)
            
            # Check saturation / color difference
            # If diff < 8, it's very close to black and white or sepia/grayscale
            if diff < 8.0:
                bw_or_suspicious.append((mi_id, m_id, m_name, url, caption, diff, img.size))
    except Exception as e:
        print(f"[ERROR] {p}: {e}")

print(f"\nTotal Black & White / Grayscale / Suspicious images: {len(bw_or_suspicious)}")
for item in bw_or_suspicious:
    print(f"ID={item[0]} | Monastery={item[2]} (ID {item[1]}) | URL={item[3]} | Size={item[6]} | ColorDiff={item[5]:.2f}")
    print(f"   Caption: {item[4]}")
