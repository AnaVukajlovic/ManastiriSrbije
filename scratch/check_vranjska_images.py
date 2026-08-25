import urllib.request
from bs4 import BeautifulSoup
import json
import sys

sys.stdout.reconfigure(encoding='utf-8')
headers = {'User-Agent': 'Mozilla/5.0'}

vranjska_slugs = ['bresnica', 'kacapun', 'prohor-pcinjski', 'zapsko', 'lepcince', 'mrtvica']

for slug in vranjska_slugs:
    url = f"https://manastiri.rs/eparhije/vranjska/manastir-{slug}/"
    try:
        req = urllib.request.Request(url, headers=headers)
        with urllib.request.urlopen(req, timeout=10) as resp:
            html = resp.read().decode('utf-8')
            soup = BeautifulSoup(html, 'html.parser')
            imgs = []
            for img in soup.find_all('img'):
                src = img.get('src') or img.get('data-src') or img.get('data-lazy-src')
                if src and 'wp-content/uploads' in src and not any(x in src.lower() for x in ['logo', 'icon', 'banner']):
                    imgs.append(src)
            print(f"[{slug}] {len(imgs)} images on {url}:")
            for im in imgs[:5]:
                print("  ", im)
    except Exception as e:
        print(f"[{slug}] Error: {e}")
