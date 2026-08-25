import sqlite3
import os
import hashlib
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()

def get_hash(filepath):
    if not os.path.exists(filepath): return "NOT_FOUND"
    with open(filepath, 'rb') as f:
        return hashlib.md5(f.read()).hexdigest()

for ep in ['eparhija-banatska','eparhija-backa','eparhija-beogradska']:
    c.execute("SELECT id, name FROM eparchies WHERE slug=?", (ep,))
    ep_row = c.fetchone()
    if not ep_row: continue
    print(f"\n==================== {ep_row[1]} ({ep}) ====================")
    c.execute("SELECT id, slug, name, image_url FROM monasteries WHERE eparchy_id=? ORDER BY name", (ep_row[0],))
    monasteries = c.fetchall()
    for m in monasteries:
        m_id, slug, name, card_img = m
        c.execute("SELECT id, url, caption, sort_order FROM monastery_images WHERE monastery_id=? ORDER BY sort_order", (m_id,))
        images = c.fetchall()
        print(f"\n[{slug}] {name}")
        print(f"  Card Image: {card_img}")
        for img_id, url, caption, sort_order in images:
            fpath = os.path.join('public', url.replace('/', os.sep))
            h = get_hash(fpath)
            sz = os.path.getsize(fpath) if os.path.exists(fpath) else 0
            print(f"    - img_id={img_id}, sort={sort_order}, url={url}, size={sz//1024}KB, md5={h[:8]}, caption='{caption}'")

conn.close()
