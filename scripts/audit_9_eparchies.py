import sqlite3
import os
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()

target_eparchies = [
    ('eparhija-niska', 'Eparhija niška'),
    ('eparhija-rasko-prizrenska', 'Eparhija raško-prizrenska'),
    ('eparhija-sremska', 'Eparhija sremska'),
    ('eparhija-timocka', 'Eparhija timočka'),
    ('eparhija-valjevska', 'Eparhija valjevska'),
    ('eparhija-vranjska', 'Eparhija vranjska'),
    ('eparhija-sabacka', 'Eparhija šabačka'),
    ('eparhija-sumadijska', 'Eparhija šumadijska'),
    ('eparhija-zicka', 'Eparhija žička')
]

for ep_slug, ep_name in target_eparchies:
    c.execute("SELECT id FROM eparchies WHERE slug=?", (ep_slug,))
    ep_id = c.fetchone()[0]
    c.execute("SELECT m.id, m.slug, m.name, m.image_url FROM monasteries m WHERE m.eparchy_id=? ORDER BY m.name", (ep_id,))
    monasteries = c.fetchall()
    print(f"\n==================== {ep_name} ({ep_slug}) - Ukupno {len(monasteries)} ====================")
    with_img = 0
    without_img = 0
    for m_id, slug, name, card in monasteries:
        c.execute("SELECT id, url, caption, sort_order FROM monastery_images WHERE monastery_id=? ORDER BY sort_order", (m_id,))
        imgs = c.fetchall()
        if imgs:
            with_img += 1
            print(f"  [+] {name} ({slug}) - {len(imgs)} slika (Card: {card})")
            for i_id, url, cap, sort in imgs:
                fpath = os.path.join('public', url.replace('/', os.sep))
                sz = os.path.getsize(fpath)//1024 if os.path.exists(fpath) else 0
                print(f"      {sort}. {url} ({sz}KB) | '{cap}'")
        else:
            without_img += 1
            print(f"  [-] {name} ({slug}) - BEZ SLIKA")
    print(f">> Sa slikama: {with_img}, Bez slika: {without_img}")

conn.close()
