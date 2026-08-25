import sqlite3
import sys
import io
import os

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()

target_eparchies = [
    'eparhija-niska',
    'eparhija-rasko-prizrenska',
    'eparhija-sremska',
    'eparhija-timocka',
    'eparhija-valjevska',
    'eparhija-vranjska',
    'eparhija-sabacka',
    'eparhija-sumadijska',
    'eparhija-zicka'
]

for ep in target_eparchies:
    c.execute("SELECT id, name, slug FROM eparchies WHERE slug=?", (ep,))
    ep_row = c.fetchone()
    if not ep_row: continue
    ep_id, name, slug = ep_row
    print(f"\n==================== {name} ({slug}) ====================")
    c.execute("SELECT id, slug, name, image_url FROM monasteries WHERE eparchy_id=? ORDER BY name", (ep_id,))
    monasteries = c.fetchall()
    for m_id, m_slug, m_name, card in monasteries:
        c.execute("SELECT id, url, caption, sort_order FROM monastery_images WHERE monastery_id=? ORDER BY sort_order", (m_id,))
        imgs = c.fetchall()
        print(f"  [{m_slug}] {m_name} (slika: {len(imgs)}) | card: {card}")
        for i_id, url, cap, sort in imgs:
            fpath = os.path.join('public', url.replace('/', os.sep))
            exists = os.path.exists(fpath)
            sz = os.path.getsize(fpath) if exists else 0
            print(f"     {sort}. {url} ({sz//1024}KB) [exists={exists}] | cap='{cap[:40]}...'")

conn.close()
