import os
import sys
import io
import re
import sqlite3
import urllib.request
import urllib.parse
import json

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

CACHE_DIR = os.path.join(os.path.dirname(__file__), '..', 'storage', 'cache_manastiri_rs')

def get_images_from_html(html, slug):
    imgs = set(re.findall(r'https?://manastiri\.rs/wp-content/uploads/[^\s"\'<>]+\.(?:jpg|jpeg|png|webp)', html, re.IGNORECASE))
    valid = []
    for img in imgs:
        # Strip size suffixes like -150x150.jpg or -300x200.jpg to get the full-res photo
        clean_img = re.sub(r'-\d+x\d+(\.\w+)$', r'\1', img)
        low = clean_img.lower()
        if not any(x in low for x in ['logo', 'avatar', 'icon', 'cropped', 'favicon', 'default', 'placeholder']):
            if clean_img not in valid:
                valid.append(clean_img)
    return valid

def get_wikipedia_images(title):
    try:
        url = f"https://sr.wikipedia.org/w/api.php?action=query&generator=images&titles={urllib.parse.quote(title)}&gimlimit=10&prop=imageinfo&iiprop=url|extmetadata&format=json"
        req = urllib.request.Request(url, headers={'User-Agent': 'ManastiriSrbije/2.0'})
        data = json.loads(urllib.request.urlopen(req, timeout=4).read().decode('utf-8'))
        pages = data.get('query', {}).get('pages', {})
        imgs = []
        for pid, p in pages.items():
            ii = p.get('imageinfo', [])
            if ii:
                img_url = ii[0].get('url', '')
                low = img_url.lower()
                if any(ext in low for ext in ['.jpg', '.jpeg', '.png', '.webp']) and not any(x in low for x in ['icon', 'logo', 'flag', 'coat_of_arms', 'symbol']):
                    imgs.append(img_url)
        return imgs
    except Exception:
        pass
    return []

def normalize_slug(s):
    if not s:
        return ''
    s = s.lower().strip()
    s = s.replace('đ', 'dj').replace('ž', 'z').replace('č', 'c').replace('ć', 'c').replace('š', 's')
    s = re.sub(r'[^a-z0-9]+', '-', s)
    return s.strip('-')

# Test for bogorodica-ljeviska and sample monasteries
for name, slug in [('Bogorodica Ljeviška', 'bogorodica-ljeviska'), ('Manastir Studenica', 'studenica'), ('Manastir Tumane', 'tumane'), ('Manastir Žiča', 'zica')]:
    print(f"\n=== {name} ({slug}) ===")
    files = [f for f in os.listdir(CACHE_DIR) if slug in f or normalize_slug(name.replace('Manastir', '')) in f]
    found = []
    if files:
        with open(os.path.join(CACHE_DIR, files[0]), 'r', encoding='utf-8') as f:
            found = get_images_from_html(f.read(), slug)
    print(f"From manastiri.rs: {len(found)} images")
    for u in found[:3]:
        print(f"  {u}")

    wiki_imgs = get_wikipedia_images(name) or get_wikipedia_images(f"Богородица Љевишка") or get_wikipedia_images(f"Манастир {name.replace('Manastir ', '')}") or get_wikipedia_images(f"Црква {name.replace('Manastir ', '')}")
    print(f"From Wikipedia/Wikimedia: {len(wiki_imgs)} images")
    for u in wiki_imgs[:3]:
        print(f"  {u}")

