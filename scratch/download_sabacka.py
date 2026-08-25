import os
import re
import urllib.request
from bs4 import BeautifulSoup
from PIL import Image
import sys
import subprocess
import time

sys.stdout.reconfigure(encoding='utf-8')

cache_dir = 'storage/cache_manastiri_rs'
headers = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
}

sabacka_monasteries = [
    (172, 'manastir-bogostica', 'bogostica', 'Bogoštica'),
    (173, 'manastir-dobric', 'dobric', 'Dobrić'),
    (174, 'manastir-dragojevac', 'dragojevac', 'Dragojevac'),
    (175, 'manastir-kaona', 'kaona', 'Kaona'),
    (176, 'manastir-ljubovija', 'ljubovija', 'Ljubovija'),
    (177, 'manastir-radovasnica', 'radovasnica', 'Radovašnica'),
    (178, 'manastir-rozanj', 'rozanj', 'Rožanj'),
    (179, 'manastir-rujevac', 'rujevac', 'Rujevac'),
    (180, 'manastir-soko-grad', 'soko-grad', 'Soko Grad'),
    (181, 'manastir-strmovo', 'strmovo', 'Strmovo'),
    (182, 'manastir-tronosa', 'tronosa', 'Tronoša'),
    (183, 'manastir-citluk', 'citluk', 'Čitluk'),
    (184, 'manastir-cokesina', 'cokesina', 'Čokešina')
]

def download_img(url, dest_path):
    temp_p = dest_path + '.temp'
    cmd = [
        'curl.exe', '-s', '-L',
        '-A', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
        '-o', temp_p,
        url
    ]
    try:
        subprocess.run(cmd, timeout=20)
        if os.path.exists(temp_p) and os.path.getsize(temp_p) > 3000:
            with Image.open(temp_p) as im:
                im = im.convert('RGB')
                if im.width > 1920 or im.height > 1920:
                    im.thumbnail((1920, 1920), Image.Resampling.LANCZOS)
                im.save(dest_path, 'JPEG', quality=88, optimize=True)
                print(f"    ✓ Sačuvano: {os.path.basename(dest_path)} ({im.width}x{im.height})")
            if os.path.exists(temp_p):
                os.remove(temp_p)
            return True
    except Exception as e:
        print(f"    ✗ Greška za {url}: {e}")
    if os.path.exists(temp_p):
        os.remove(temp_p)
    return False

results = {}

for m_id, cache_slug, prefix, name in sabacka_monasteries:
    fname = f"https___manastiri_rs_eparhije_sabacka_{cache_slug}_.html"
    fpath = os.path.join(cache_dir, fname)
    if not os.path.exists(fpath):
        print(f"Nema keša za {fname}")
        continue
    
    with open(fpath, 'r', encoding='utf-8', errors='ignore') as f:
        html = f.read()
    
    # Extract all image links
    raw_urls = re.findall(r'https?://[^\s"\'<>]+\.(?:jpg|jpeg|png|webp)', html)
    clean_urls = []
    for u in raw_urls:
        if 'wp-content/uploads' in u:
            # remove dimensions
            base = re.sub(r'-\d+x\d+(\.[a-zA-Z]+)$', r'\1', u)
            if base not in clean_urls and not any(k in base.lower() for k in ['favicon', 'logo', 'cropped', 'banner', 'avatar']):
                clean_urls.append(base)
    
    print(f"\n[ID {m_id}: {name} ({prefix})] Pronađeno {len(clean_urls)} slika:")
    results[prefix] = []
    
    # Download up to 4 images (0=main, 1..3=gal_1..gal_3)
    for idx, u in enumerate(clean_urls[:4]):
        if idx == 0:
            target_fn = f"{prefix}.jpg"
        else:
            target_fn = f"{prefix}_gal_{idx}.jpg"
        target_path = os.path.join('public/images/monasteries', target_fn)
        if download_img(u, target_path):
            results[prefix].append(target_fn)

print("\n--- REZULTAT PREUZIMANJA ZA ŠABAČKU EPARHIJU ---")
for k, v in results.items():
    print(f"{k}: {len(v)} slika ({', '.join(v)})")
