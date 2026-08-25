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

# Specifically for major Žička monasteries:
# Studenica (228), Žiča (239), Gradac (209), Rača (221), Vraćevšnica (236), Stara Pavlica (226), Nova Pavlica (218), Kovilje (215)
major_monasteries = [
    ('studenica', 'studenica'),
    ('zica', 'zica'),
    ('gradac', 'gradac'),
    ('raca', 'raca'),
    ('vracevsnica', 'vracevsnica'),
    ('stara-pavlica', 'stara-pavlica'),
    ('nova-pavlica', 'nova-pavlica'),
    ('kovilje', 'kovilje')
]

for cache_match, prefix in major_monasteries:
    matching_files = [f for f in os.listdir(cache_dir) if cache_match in f and 'zicka' in f]
    if not matching_files:
        matching_files = [f for f in os.listdir(cache_dir) if cache_match in f]
    
    if not matching_files:
        print(f"Nema keša za {cache_match}")
        continue
    
    cache_path = os.path.join(cache_dir, matching_files[0])
    with open(cache_path, 'r', encoding='utf-8', errors='ignore') as f:
        html = f.read()
    
    soup = BeautifulSoup(html, 'html.parser')
    img_urls = []
    
    # 1. Main featured image
    # 2. Gallery images inside article or swiper/gallery
    for a in soup.find_all('a'):
        href = a.get('href') or ''
        if href.endswith(('.jpg', '.jpeg', '.png', '.webp')) and 'wp-content/uploads' in href:
            if href not in img_urls:
                img_urls.append(href)
                
    for img in soup.find_all('img'):
        src = img.get('src') or img.get('data-src') or ''
        clean_src = re.sub(r'-\d+x\d+(\.[a-zA-Z]+)$', r'\1', src)
        if clean_src and 'wp-content/uploads' in clean_src and clean_src not in img_urls:
            if not any(skip in clean_src.lower() for skip in ['logo', 'icon', 'banner', 'avatar', 'placeholder']):
                img_urls.append(clean_src)

    print(f"\n[Manastir {prefix}] Pronađeno {len(img_urls)} slika na manastiri.rs:")
    
    # Save main card image + up to 4 gallery images
    for i, u in enumerate(img_urls[:5]):
        if i == 0:
            target_fn = f"{prefix}.jpg"
        else:
            target_fn = f"{prefix}_gal_{i}.jpg"
        
        target_path = os.path.join('public/images/monasteries', target_fn)
        download_img(u, target_path)

