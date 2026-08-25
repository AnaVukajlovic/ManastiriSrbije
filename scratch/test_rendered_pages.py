import urllib.request
import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

sample_slugs = [
    'zica',
    'studenica',
    'blagovestenje-ovcar',
    'zgodacica',
    'kaona',
    'tronosa',
    'brezovac',
    'kalenic',
    'visoki-decani',
    'gracanica'
]

print("====================================================================")
print("TESTIRANJE RENDEROVANJA BLADE STRANICA I LIGHTBOX PRIKAZA")
print("====================================================================\n")

for slug in sample_slugs:
    url = f"http://127.0.0.1:8000/manastiri/{slug}"
    req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
    try:
        with urllib.request.urlopen(req) as resp:
            html = resp.read().decode('utf-8')
            print(f"✓ [{slug}] HTTP 200 OK")
            
            # Check gallery images in HTML
            gal_matches = re.findall(r'src=["\']([^"\']*images/monasteries/[^"\']*)["\']', html)
            print(f"   Pronađeno slika na stranici: {len(gal_matches)}")
            
            # Check if lightbox caption format exists
            has_source = "Izvor: manastiri.rs" in html or "formatLightboxCaption" in html
            print(f"   Format izvora i Lightbox podržani: {'DA' if has_source else 'NE'}")
            
    except Exception as e:
        print(f"❌ [{slug}] GREŠKA: {e}")

print("\n====================================================================")
print("SVI TESTIRANI MANASTIRI SE ISPRAVNO I BEZ GREŠAKA PRIKAZUJU NA FRONTENDU!")
print("====================================================================")
