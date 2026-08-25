import urllib.request
import urllib.error
import sqlite3
import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

BASE_URL = "http://127.0.0.1:8000"

def fetch_url(url):
    try:
        req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
        with urllib.request.urlopen(req, timeout=5) as response:
            return response.status, response.read().decode('utf-8')
    except urllib.error.HTTPError as e:
        return e.code, ""
    except Exception as e:
        return 500, str(e)

def check_image_exists(img_url):
    full_url = img_url if img_url.startswith("http") else f"{BASE_URL}/{img_url.lstrip('/')}"
    try:
        req = urllib.request.Request(full_url, headers={'User-Agent': 'Mozilla/5.0'})
        with urllib.request.urlopen(req, timeout=5) as response:
            return response.status == 200, response.headers.get('Content-Length', 'unknown')
    except Exception:
        return False, 0

print("====================================================================")
print("POČINJE DETALJNA PROVERA APLIKACIJE NA http://127.0.0.1:8000")
print("====================================================================\n")

# 1. Provera glavne stranice /manastiri
status, html = fetch_url(f"{BASE_URL}/manastiri")
print(f"1. Glavna stranica /manastiri: HTTP status {status}")
if status != 200:
    print("   ❌ Greška pri učitavanju glavne stranice!")
else:
    print("   ✓ Glavna stranica se uspešno učitava.")

# 2. Provera manastira iz eparhija Žička (1), Raško-prizrenska (2), Šumadijska (4), Šabačka (15)
conn = sqlite3.connect('database/database.sqlite')
cursor = conn.cursor()

eparchies_to_check = [
    (1, 'Žička eparhija'),
    (2, 'Raško-prizrenska eparhija'),
    (4, 'Šumadijska eparhija'),
    (15, 'Šabačka eparhija')
]

total_monasteries = 0
total_images_checked = 0
errors = []

for eid, ename in eparchies_to_check:
    print(f"\n====================================================================")
    print(f"EPARHIJA: {ename} (ID {eid})")
    print(f"====================================================================")
    
    cursor.execute("SELECT id, name, slug, image_url FROM monasteries WHERE eparchy_id = ? ORDER BY id", (eid,))
    monasteries = cursor.fetchall()
    
    for mid, mname, slug, card_img in monasteries:
        total_monasteries += 1
        page_url = f"{BASE_URL}/manastiri/{slug}" if slug else f"{BASE_URL}/manastiri/{mid}"
        p_status, p_html = fetch_url(page_url)
        
        # Check card image
        card_ok, card_size = check_image_exists(card_img) if card_img else (False, 0)
        total_images_checked += 1
        
        # Fetch gallery images from DB
        cursor.execute("SELECT url, caption, sort_order FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order", (mid,))
        gal_images = cursor.fetchall()
        
        gal_urls = [g[0] for g in gal_images]
        has_duplicates = len(gal_urls) != len(set(gal_urls))
        
        # Check each gallery image HTTP status
        broken_gal = []
        for g_url, g_cap, s_ord in gal_images:
            total_images_checked += 1
            g_ok, g_sz = check_image_exists(g_url)
            if not g_ok:
                broken_gal.append(g_url)
        
        # Check if page loaded and images are clean
        if p_status == 200 and card_ok and not broken_gal and not has_duplicates:
            print(f"  ✓ [{mid}] {mname} ({len(gal_images)} slika u galeriji) - Sve slike i stranica rade 200 OK")
        else:
            err_desc = []
            if p_status != 200: err_desc.append(f"HTTP {p_status}")
            if not card_ok: err_desc.append(f"Slomljena kartična slika: {card_img}")
            if broken_gal: err_desc.append(f"Slomljene galerijske slike: {broken_gal}")
            if has_duplicates: err_desc.append(f"Duplikati u galeriji!")
            print(f"  ❌ [{mid}] {mname} - GREŠKA: {', '.join(err_desc)}")
            errors.append((mid, mname, err_desc))

print("\n====================================================================")
print("REZIME TESTIRANJA:")
print(f"Ukupno provereno manastira: {total_monasteries}")
print(f"Ukupno provereno slika preko HTTP-a: {total_images_checked}")
if errors:
    print(f"Pronađeno grešaka: {len(errors)}")
    for mid, name, errs in errors:
        print(f"  - [{mid}] {name}: {errs}")
else:
    print("SVE SLIKE, KARTICE I GALERIJE RADE SAVRŠENO 200 OK BEZ IJEDNE GREŠKE!")
print("====================================================================")
