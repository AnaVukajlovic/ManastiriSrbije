import sqlite3
import os
import sys
from PIL import Image

sys.stdout.reconfigure(encoding='utf-8')

conn = sqlite3.connect('storage/database.sqlite')
cursor = conn.cursor()

eparchies = [
    (1, 'Žička eparhija'),
    (2, 'Raško-prizrenska eparhija'),
    (4, 'Šumadijska eparhija'),
    (15, 'Šabačka eparhija')
]

print("====================================================================")
print("KOMPLETNA PROVERA KARTICA, GALERIJA I OPISA ZA AŽURIRANE EPARHIJE")
print("====================================================================\n")

total_monasteries = 0
total_images = 0
errors = []

for eid, ename in eparchies:
    print(f"\n📌 {ename.upper()} (ID {eid}):")
    cursor.execute("SELECT id, name, slug, image_url FROM monasteries WHERE eparchy_id = ? ORDER BY id", (eid,))
    monasteries = cursor.fetchall()
    
    for mid, mname, slug, card_img in monasteries:
        total_monasteries += 1
        
        # 1. Provera card_image
        card_path = os.path.join('public', card_img) if card_img else ""
        card_valid = False
        card_dims = (0, 0)
        if card_path and os.path.exists(card_path):
            try:
                with Image.open(card_path) as im:
                    card_dims = im.size
                    card_valid = im.size[0] > 100 and im.size[1] > 100
            except Exception:
                card_valid = False
        
        # 2. Provera galerije
        cursor.execute("SELECT url, caption, sort_order FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order", (mid,))
        gal_images = cursor.fetchall()
        
        gal_urls = [g[0] for g in gal_images]
        has_duplicates = len(gal_urls) != len(set(gal_urls))
        
        broken_gal = []
        for g_url, g_cap, s_ord in gal_images:
            total_images += 1
            g_path = os.path.join('public', g_url)
            if not os.path.exists(g_path) or os.path.getsize(g_path) == 0:
                broken_gal.append(g_url)
            if not g_cap or len(g_cap.strip()) < 5:
                errors.append(f"[{mid}] {mname} ima prazan opis za {g_url}")
                
        if card_valid and not broken_gal and not has_duplicates:
            print(f"  ✓ [{mid}] {mname} ({len(gal_images)} slika u galeriji, kartica: {card_dims[0]}x{card_dims[1]})")
        else:
            err_msg = []
            if not card_valid:
                err_msg.append(f"Neispravna kartica: {card_img}")
            if broken_gal:
                err_msg.append(f"Nedostaju galerijske slike: {broken_gal}")
            if has_duplicates:
                err_msg.append("DUPLIKATI u galeriji!")
            print(f"  ❌ [{mid}] {mname} - GREŠKA: {', '.join(err_msg)}")
            errors.append(f"[{mid}] {mname}: {', '.join(err_msg)}")

print("\n====================================================================")
print("REZIME:")
print(f"Provereno manastira: {total_monasteries}")
print(f"Provereno slika u galerijama i karticama: {total_images}")
if errors:
    print(f"Pronađeno grešaka: {len(errors)}")
    for e in errors:
        print(f"  • {e}")
else:
    print("SVE SLIKE KARTICA I GALERIJA SU 100% ISPRAVNE, BEZ DUPLIKATA I SA POPUNJENIM OPISIMA!")
print("====================================================================")
