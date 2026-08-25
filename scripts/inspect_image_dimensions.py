import sqlite3
import os
import sys
import io
from PIL import Image

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()

eparchies = ['eparhija-banatska','eparhija-backa','eparhija-beogradska','eparhija-branicevska','eparhija-krusevacka','eparhija-milesevska']

for ep in eparchies:
    c.execute("SELECT id, name FROM eparchies WHERE slug=?", (ep,))
    ep_row = c.fetchone()
    if not ep_row: continue
    print(f"\n==================== {ep_row[1]} ====================")
    c.execute("SELECT id, slug, name, image_url FROM monasteries WHERE eparchy_id=? ORDER BY name", (ep_row[0],))
    for m_id, slug, name, card_img in c.fetchall():
        c.execute("SELECT id, url, caption, sort_order FROM monastery_images WHERE monastery_id=? ORDER BY sort_order", (m_id,))
        images = c.fetchall()
        if not images:
            print(f"[{slug}] {name}: NEMA SLIKA")
            continue
        print(f"\n[{slug}] {name} (Card: {card_img})")
        for img_id, url, caption, sort_order in images:
            fpath = os.path.join('public', url.replace('/', os.sep))
            if os.path.exists(fpath):
                try:
                    im = Image.open(fpath)
                    w, h = im.size
                    fmt = im.format
                    print(f"  {sort_order}. id={img_id} | {url} | {w}x{h} ({fmt}) | {os.path.getsize(fpath)//1024}KB")
                except Exception as e:
                    print(f"  {sort_order}. id={img_id} | {url} | ERROR: {e}")
            else:
                print(f"  {sort_order}. id={img_id} | {url} | FILE NOT FOUND")

conn.close()
