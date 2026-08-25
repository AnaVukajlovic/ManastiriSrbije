import os
import sqlite3
from PIL import Image
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

print(f"Skeniram {len(rows)} slika...", flush=True)

bw_list = []

for mi_id, m_id, m_name, slug, url, caption in rows:
    p = os.path.join('public', 'images', 'monasteries', os.path.basename(url))
    if not os.path.exists(p):
        print(f"[NEPOSTOJEĆI FAJL] {p}", flush=True)
        continue
    try:
        with Image.open(p) as img:
            thumb = img.resize((15, 15)).convert('RGB')
            pixels = list(thumb.getdata())
            diffs = [abs(r-g) + abs(g-b) + abs(r-b) for r, g, b in pixels]
            avg_diff = sum(diffs) / len(diffs)
            
            # Check saturation / color difference
            # If avg_diff < 7.0, it's black and white or grayscale/sepia
            if avg_diff < 7.0:
                bw_list.append((mi_id, m_id, m_name, url, caption, avg_diff, img.size))
    except Exception as e:
        print(f"[GREŠKA] {p}: {e}", flush=True)

print(f"\nPronađeno crno-belih / sumnjivih slika: {len(bw_list)}\n", flush=True)
for item in bw_list:
    print(f"ID={item[0]} | {item[2]} (ID {item[1]}) | URL={item[3]} | Size={item[6]} | Saturation={item[5]:.2f}")
    print(f"   Caption: {item[4]}\n", flush=True)
