import os
import re
import urllib.request
from bs4 import BeautifulSoup
from PIL import Image
import sys

sys.stdout.reconfigure(encoding='utf-8')

cache_dir = 'storage/cache_manastiri_rs'
headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'}

def get_images_from_cache(monastery_slug):
    files = [f for f in os.listdir(cache_dir) if monastery_slug in f]
    if not files:
        print(f"No cache file for {monastery_slug}")
        return []
    
    path = os.path.join(cache_dir, files[0])
    with open(path, 'r', encoding='utf-8', errors='ignore') as f:
        html = f.read()
    
    soup = BeautifulSoup(html, 'html.parser')
    images = []
    # Find all gallery or content images
    for img in soup.find_all('img'):
        src = img.get('src') or img.get('data-src')
        if src and ('wp-content/uploads' in src or 'images' in src):
            if not src.startswith('http'):
                src = 'https://manastiri.rs' + ('/' if not src.startswith('/') else '') + src
            # Clean thumbnail dimensions from wp urls
            clean_src = re.sub(r'-\d+x\d+(\.[a-zA-Z]+)$', r'\1', src)
            if clean_src not in images and not any(skip in clean_src.lower() for skip in ['logo', 'icon', 'banner', 'facebook', 'instagram', 'avatar']):
                images.append(clean_src)
    return images

monasteries_to_check = [
    ('studenica', 'studenica'),
    ('zica', 'zica'),
    ('vracevsnica', 'vracevsnica'),
    ('gradac', 'gradac'),
    ('raca', 'raca'),
    ('stara-pavlica', 'stara-pavlica'),
    ('nova-pavlica', 'nova-pavlica'),
    ('kovilje', 'kovilje'),
    ('blagovestenje', 'blagovestenje-ovcar'),
    ('jezevica', 'jezevica'),
    ('jovanje', 'jovanje-ovcar-kablar'),
    ('nikolje', 'nikolje-ovcar-kablar'),
    ('preobrazenje', 'preobrazenje-ovcar-kablar'),
    ('sretenje', 'sretenje'),
    ('uvac', 'uvac'),
    ('vavedenje', 'vavedenje-ovcar'),
    ('vaznesenje', 'vaznesenje-ovcar'),
    ('vujan', 'vujan'),
    ('moravci', 'moravci')
]

for slug, prefix in monasteries_to_check:
    imgs = get_images_from_cache(slug)
    print(f"\n[{prefix}] Found {len(imgs)} images on manastiri.rs:")
    for idx, img_url in enumerate(imgs[:6]):
        print(f"  {idx+1}: {img_url}")
