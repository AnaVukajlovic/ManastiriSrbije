import os
import re
import urllib.request
from bs4 import BeautifulSoup
from PIL import Image
import sys

sys.stdout.reconfigure(encoding='utf-8')

cache_dir = 'storage/cache_manastiri_rs'
headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'}

def download_img(url, dest_path):
    try:
        req = urllib.request.Request(url, headers=headers)
        temp_p = dest_path + '.temp'
        with urllib.request.urlopen(req, timeout=15) as resp:
            with open(temp_p, 'wb') as f:
                f.write(resp.read())
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
        print(f"    ✗ Neuspešno: {url} -> {e}")
        return False

# List of major monasteries and their target file prefixes
target_monasteries = [
    ("manastir-studenica", "studenica", 228),
    ("manastir-zica", "zica", 239),
    ("manastir-vracevsnica", "vracevsnica", 236),
    ("manastir-gradac", "gradac", 209),
    ("manastir-raca", "raca", 221),
    ("manastir-stara-pavlica", "stara-pavlica", 226),
    ("manastir-nova-pavlica", "nova-pavlica", 218),
    ("manastir-kovilje", "kovilje", 215),
    ("manastir-jezevica", "jezevica", 212),
    ("manastir-moravci", "moravci", 216),
    ("manastir-vujan", "vujan", 237),
    ("manastir-uvac", "uvac", 232)
]

downloaded_summary = {}

for html_slug, prefix, m_id in target_monasteries:
    fname = f"https___manastiri_rs_eparhije_zicka_{html_slug}_.html"
    fpath = os.path.join(cache_dir, fname)
    if not os.path.exists(fpath):
        print(f"Nema fajla: {fname}")
        continue
    
    with open(fpath, 'r', encoding='utf-8', errors='ignore') as f:
        html = f.read()
    
    # Regex to find all uploaded image urls
    urls = re.findall(r'https?://[^\s"\'<>]+\.(?:jpg|jpeg|png|webp)', html)
    clean_urls = []
    for u in urls:
        if 'wp-content/uploads' in u:
            # Strip thumbnail size suffix like -300x200
            orig = re.sub(r'-\d+x\d+(\.[a-zA-Z]+)$', r'\1', u)
            if orig not in clean_urls and not any(k in orig.lower() for k in ['logo', 'banner', 'avatar', 'icon', 'cropped']):
                clean_urls.append(orig)
    
    print(f"\n[ID {m_id}: {prefix}] Pronađeno {len(clean_urls)} originalnih slika na manastiri.rs")
    downloaded_summary[prefix] = []
    
    # Download up to 4 images
    for i, u in enumerate(clean_urls[:4]):
        if i == 0:
            target_fn = f"{prefix}.jpg"
        else:
            target_fn = f"{prefix}_gal_{i}.jpg"
        
        target_path = os.path.join('public/images/monasteries', target_fn)
        if download_img(u, target_path):
            downloaded_summary[prefix].append(target_fn)

print("\n\nDOWNLOAD SUMMARY:")
for k, v in downloaded_summary.items():
    print(f"  {k}: {len(v)} slika ({', '.join(v)})")
