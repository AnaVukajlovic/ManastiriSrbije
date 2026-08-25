import os
import sqlite3
import io
import sys
from PIL import Image

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()
c.execute('SELECT mi.id, m.name, mi.url, mi.caption FROM monastery_images mi JOIN monasteries m ON mi.monastery_id = m.id')
rows = c.fetchall()
conn.close()

suspicious = []

for img_id, m_name, url, caption in rows:
    p = os.path.join('public', 'images', 'monasteries', os.path.basename(url))
    if not os.path.exists(p):
        suspicious.append((img_id, m_name, url, "FILE NOT FOUND", caption))
        continue
    try:
        with Image.open(p) as img:
            # Check mode
            if img.mode in ('L', '1'):
                suspicious.append((img_id, m_name, url, "GRAYSCALE MODE", caption))
                continue
            
            # Sample center pixel and corners
            w, h = img.size
            pixels = [
                img.getpixel((w//2, h//2)),
                img.getpixel((w//4, h//4)),
                img.getpixel((3*w//4, 3*h//4)),
                img.getpixel((w//2, h//4)),
                img.getpixel((w//4, h//2)),
                img.getpixel((3*w//4, h//2)),
                img.getpixel((w//2, 3*h//4))
            ]
            diffs = []
            for px in pixels:
                if isinstance(px, tuple) and len(px) >= 3:
                    r, g, b = px[:3]
                    diffs.append(abs(r-g) + abs(g-b) + abs(r-b))
            avg_diff = sum(diffs) / len(diffs) if diffs else 0
            if avg_diff < 5:
                suspicious.append((img_id, m_name, url, f"VERY LOW COLOR (diff={avg_diff:.1f})", caption))
    except Exception as e:
        suspicious.append((img_id, m_name, url, f"ERROR: {e}", caption))

print(f"Total suspicious/grayscale images found: {len(suspicious)}", flush=True)
for s in suspicious:
    print(f"ID={s[0]} | {s[1]} | {s[2]} | {s[3]}", flush=True)
    print(f"   Caption: {s[4]}\n", flush=True)
