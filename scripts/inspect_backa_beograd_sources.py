import os
import sys
import io
import re
import urllib.request
import json

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
CACHE_DIR = os.path.join(BASE_DIR, 'storage', 'cache_manastiri_rs')

monasteries = [
    # Backa
    ('bodjani', 'backa', 'Manastir Bođani'),
    ('kac', 'backa', 'Manastir Kać'),
    ('kovilj', 'backa', 'Manastir Kovilj'),
    ('sombor', 'backa', 'Manastir Sombor'),
    ('vodica', 'backa', 'Manastir Vodica'),
    # Beogradska
    ('mislodjin', 'beogradska', 'Manastir Mislođin'),
    ('rajinovac', 'beogradska', 'Manastir Rajinovac'),
    ('rakovica', 'beogradska', 'Manastir Rakovica'),
    ('senjak', 'beogradska', 'Manastir Vavedenje Senjak'),
    ('slanci', 'beogradska', 'Manastir Slanci'),
    ('trojerucica', 'beogradska', 'Manastir Trojeručica')
]

for slug, ep, name in monasteries:
    print(f"\n========================================================")
    print(f"  {name.upper()} ({slug}) - {ep.upper()}")
    print(f"========================================================")
    
    # 1. manastiri.rs cache
    cf = f"https___manastiri_rs_eparhije_{ep}_manastir-{slug}_.html"
    cfp = os.path.join(CACHE_DIR, cf)
    if os.path.exists(cfp):
        html = open(cfp, 'r', encoding='utf-8').read()
        raw_imgs = set(re.findall(r'https?://manastiri\.rs/wp-content/uploads/[^\s"\'<>]+\.(?:jpg|jpeg|png|webp)', html, re.IGNORECASE))
        clean_imgs = []
        for im in raw_imgs:
            c = re.sub(r'-\d+x\d+(\.\w+)$', r'\1', im)
            if not any(bad in c.lower() for bad in ['favicon', 'logo', 'avatar', 'icon', 'cropped-image']):
                if c not in clean_imgs:
                    clean_imgs.append(c)
        print(f"Slike na manastiri.rs ({len(clean_imgs)}):")
        for ci in clean_imgs[:8]:
            print(f"  • {ci}")
    else:
        print(f"Nema keš fajla na manastiri.rs za {slug}")
