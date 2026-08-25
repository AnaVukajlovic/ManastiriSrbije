"""
Deep verification and description generator for the 9 eparchies:
- Valjevska (7)
- Timočka (10)
- Vranjska (12)
- Šabačka (13)
- Sremska (19)
- Šumadijska (21)
- Raško-prizrenska (26)
- Žička (35)
- Niška (42)
"""
import sqlite3
import os
import sys
import io
import hashlib

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()

def get_hash(fpath):
    if not os.path.exists(fpath): return "NONE"
    with open(fpath, 'rb') as f:
        return hashlib.md5(f.read()).hexdigest()

target_eparchies = [
    ('eparhija-valjevska', 'Eparhija valjevska'),
    ('eparhija-timocka', 'Eparhija timočka'),
    ('eparhija-vranjska', 'Eparhija vranjska'),
    ('eparhija-sabacka', 'Eparhija šabačka'),
    ('eparhija-sremska', 'Eparhija sremska'),
    ('eparhija-sumadijska', 'Eparhija šumadijska'),
    ('eparhija-rasko-prizrenska', 'Eparhija raško-prizrenska'),
    ('eparhija-zicka', 'Eparhija žička'),
    ('eparhija-niska', 'Eparhija niška')
]

for ep_slug, ep_name in target_eparchies:
    c.execute("SELECT id FROM eparchies WHERE slug=?", (ep_slug,))
    ep_id = c.fetchone()[0]
    c.execute("SELECT id, slug, name, image_url FROM monasteries WHERE eparchy_id=? ORDER BY name", (ep_id,))
    monasteries = c.fetchall()
    print(f"\n==================== {ep_name} ({len(monasteries)} manastira) ====================")
    for m_id, slug, name, card in monasteries:
        c.execute("SELECT id, url, caption, sort_order FROM monastery_images WHERE monastery_id=? ORDER BY sort_order", (m_id,))
        imgs = c.fetchall()
        hashes = []
        dups = []
        for i_id, url, cap, sort in imgs:
            fpath = os.path.join('public', url.replace('/', os.sep))
            h = get_hash(fpath)
            if h in hashes and h != "NONE":
                dups.append((sort, url))
            hashes.append(h)
        dup_str = f" [DUPLIKATI: {dups}]" if dups else ""
        print(f"[{slug}] {name} (slika: {len(imgs)}){dup_str}")
        for i_id, url, cap, sort in imgs:
            fpath = os.path.join('public', url.replace('/', os.sep))
            sz = os.path.getsize(fpath)//1024 if os.path.exists(fpath) else 0
            print(f"   {sort}. {url} ({sz}KB)")

conn.close()
