"""
Generates the rigorous descriptive table for all 185 monasteries in 9 eparchies:
1. Valjevska (7)
2. Timočka (10)
3. Vranjska (12)
4. Šabačka (13)
5. Sremska (19)
6. Šumadijska (21)
7. Raško-prizrenska (26)
8. Žička (35)
9. Niška (42)
"""
import sqlite3
import os
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()

def get_monasteries_for_eparchy(slug):
    c.execute("SELECT id, name FROM eparchies WHERE slug=?", (slug,))
    ep_id, ep_name = c.fetchone()
    c.execute("SELECT id, slug, name, image_url FROM monasteries WHERE eparchy_id=? ORDER BY name", (ep_id,))
    res = []
    for m_id, m_slug, m_name, card in c.fetchall():
        c.execute("SELECT id, url, caption, sort_order FROM monastery_images WHERE monastery_id=? ORDER BY sort_order", (m_id,))
        imgs = c.fetchall()
        res.append((m_id, m_slug, m_name, card, imgs))
    return ep_name, res

conn.close()
