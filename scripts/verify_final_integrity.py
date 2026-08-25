import sqlite3
import os
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()

eparchies = ['eparhija-banatska','eparhija-backa','eparhija-beogradska','eparhija-branicevska','eparhija-krusevacka','eparhija-milesevska']

print("=== FINAL VERIFICATION ACROSS 6 EPARCHIES ===")

for ep in eparchies:
    c.execute("SELECT id, name FROM eparchies WHERE slug=?", (ep,))
    ep_id, ep_name = c.fetchone()
    print(f"\n--- {ep_name} ({ep}) ---")
    c.execute("SELECT id, slug, name, image_url FROM monasteries WHERE eparchy_id=? ORDER BY name", (ep_id,))
    monasteries = c.fetchall()
    for m_id, slug, name, card_img in monasteries:
        c.execute("SELECT id, url, caption, sort_order FROM monastery_images WHERE monastery_id=? ORDER BY sort_order", (m_id,))
        images = c.fetchall()
        if not images:
            print(f"  [BEZ SLIKA] {name} ({slug})")
            continue
        print(f"  [+] {name} ({slug}) | Card: {card_img} | Broj slika: {len(images)}")
        for img_id, url, caption, sort_order in images:
            fpath = os.path.join('public', url.replace('/', os.sep))
            exists = os.path.exists(fpath)
            sz = os.path.getsize(fpath) if exists else 0
            assert exists, f"Missing file {fpath}"
            assert caption, f"Empty caption for {slug} img {img_id}"
            print(f"      {sort_order}. {url} ({sz//1024}KB) -> \"{caption}\"")

conn.close()
print("\n✓ ALL INTEGRITY CHECKS PASSED!")
