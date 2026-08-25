import os
import sys
import io
import sqlite3

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
IMG_DIR = os.path.join(BASE_DIR, 'public', 'images', 'monasteries')
CACHE_DIR = os.path.join(BASE_DIR, 'storage', 'cache_manastiri_rs')

banat_slugs = ['bavaniste', 'gaj', 'hajducica', 'mesic', 'srediste', 'sveta-trojica-kikinda', 'svete-melanije', 'vlajkovac', 'vojlovica']

for slug in banat_slugs:
    print(f"\n================================")
    print(f"  MANASTIR: {slug.upper()}")
    print(f"================================")
    
    # 1. Check local files
    files = [f for f in os.listdir(IMG_DIR) if f.startswith(slug)]
    print(f"Lokalni fajlovi ({len(files)}):")
    for f in files:
        fp = os.path.join(IMG_DIR, f)
        print(f"  - {f} ({os.path.getsize(fp) // 1024} KB)")

    # 2. Check manastiri.rs cached html
    cf = f"https___manastiri_rs_eparhije_banatska_manastir-{slug}_.html"
    cfp = os.path.join(CACHE_DIR, cf)
    if os.path.exists(cfp):
        import re
        html = open(cfp, 'r', encoding='utf-8').read()
        raw_imgs = set(re.findall(r'https?://manastiri\.rs/wp-content/uploads/[^\s"\'<>]+\.(?:jpg|jpeg|png|webp)', html, re.IGNORECASE))
        print(f"Slike na manastiri.rs ({len(raw_imgs)}):")
        for im in raw_imgs:
            if not any(bad in im.lower() for bad in ['favicon', 'logo', 'avatar', 'icon']):
                print(f"    • {im}")
    else:
        print(f"Nema keš fajla {cf}")
