"""
Script to inspect and verify details of the images for the 6 eparchies.
"""
import sqlite3
import os
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()

targets = ['reskovica', 'bodjani', 'sveta-trojica-kikinda', 'vlajkovac', 'gaj', 'sisojevac', 'sombor', 'zdrelo', 'kumanica', 'mazici']

for t in targets:
    c.execute("SELECT id, name, image_url FROM monasteries WHERE slug=?", (t,))
    row = c.fetchone()
    if not row: continue
    m_id, name, card = row
    print(f"\n[{t}] {name} (Card: {card})")
    c.execute("SELECT id, url, caption, sort_order FROM monastery_images WHERE monastery_id=? ORDER BY sort_order", (m_id,))
    for img_id, url, caption, sort_order in c.fetchall():
        fpath = os.path.join('public', url.replace('/', os.sep))
        sz = os.path.getsize(fpath) if os.path.exists(fpath) else 0
        print(f"  sort={sort_order}, id={img_id}, url={url}, size={sz//1024}KB, cap='{caption}'")

conn.close()
