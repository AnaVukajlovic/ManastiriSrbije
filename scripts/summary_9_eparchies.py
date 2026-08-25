import sqlite3
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
    with_img = []
    without_img = []
    for m_id, slug, name, card in monasteries:
        c.execute("SELECT id, url, caption, sort_order FROM monastery_images WHERE monastery_id=? ORDER BY sort_order", (m_id,))
        imgs = c.fetchall()
        if imgs:
            with_img.append((slug, name, len(imgs)))
        else:
            without_img.append((slug, name))
    print(f"\n{ep_name} ({ep_slug}) -> Ukupno: {len(monasteries)} | Sa slikama: {len(with_img)} | Bez slika: {len(without_img)}")
    if without_img:
        print(f"  Bez slika ({len(without_img)}): {', '.join([s for s,n in without_img])}")

conn.close()
