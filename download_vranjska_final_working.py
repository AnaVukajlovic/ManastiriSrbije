import urllib.request
import urllib.parse
import re
import os
import ssl
import sys
import json
import time

sys.stdout.reconfigure(encoding='utf-8')

ctx = ssl.create_default_context()
ctx.check_hostname = False
ctx.verify_mode = ssl.CERT_NONE

HEADERS = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/120.0.0.0'}

dest_dir = r"d:\projekti\ManastiriSrbije\backend\public\images\monasteries"

monasteries = [
    {
        "id": 167,
        "slug": "bresnica",
        "name": "Manastir Bresnica",
        "urls": [
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%91%D1%80%D0%B5%D1%81%D0%BD%D0%B8%D1%86%D0%B0"
        ]
    },
    {
        "id": 168,
        "slug": "kacapun",
        "name": "Manastir Kacapun",
        "urls": [
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%9A%D0%B0%D1%86%D0%B0%D0%BF%D1%83%D0%BD"
        ]
    },
    {
        "id": 169,
        "slug": "lopardince",
        "name": "Manastir Lopardince",
        "urls": [
            "https://sr.wikipedia.org/wiki/%D0%9B%D0%BE%D0%BF%D0%B0%D1%80%D0%B4%D0%B8%D0%BD%D1%86%D0%B5"
        ]
    },
    {
        "id": 170,
        "slug": "prohor-pcinjski",
        "name": "Manastir Prohor Pčinjski",
        "urls": [
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%9F%D1%80%D0%BE%D1%85%D0%BE%D1%80_%D0%9F%D1%87%D0%B8%D1%9A%D1%81%D0%BA%D0%B8"
        ]
    },
    {
        "id": 171,
        "slug": "zapsko",
        "name": "Manastir Žapsko",
        "urls": [
            "https://sr.wikipedia.org/wiki/%D0%96%D0%B0%D0%BF%D1%81%D0%BA%D0%BE"
        ]
    },
    {
        "id": 240,
        "slug": "dubnica-milesevska",
        "name": "Manastir Dubnica",
        "urls": [
            "https://sr.wikipedia.org/wiki/%D0%94%D1%83%D0%B1%D0%BD%D0%B8%D1%86%D0%B0_(%D0%92%D1%80%D0%B0%D1%9A%D0%B5)"
        ]
    },
    {
        "id": 246,
        "slug": "kozji-dol",
        "name": "Manastir Kozji Dol",
        "urls": [
            "https://sr.wikipedia.org/wiki/%D0%94%D0%BE%D1%9A%D0%B8_%D0%9A%D0%BE%D0%B7%D1%98%D0%B8_%D0%94%D0%BE%D0%BB"
        ]
    },
    {
        "id": 247,
        "slug": "lepcince",
        "name": "Manastir Lepčince",
        "urls": [
            "https://sr.wikipedia.org/wiki/%D0%9B%D0%B5%D0%BF%D1%87%D0%B8%D0%BD%D1%86%D0%B5"
        ]
    },
    {
        "id": 249,
        "slug": "simeon-stolpnik",
        "name": "Manastir Simeon Stolpnik",
        "urls": [
            "https://sr.wikipedia.org/wiki/%D0%A1%D0%BE%D0%B4%D0%B5%D1%80%D1%86%D0%B5"
        ]
    },
    {
        "id": 251,
        "slug": "mrtvica",
        "name": "Manastir Mrtvica",
        "urls": [
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%9C%D1%80%D1%82%D0%B2%D0%B8%D1%86%D0%B0"
        ]
    },
    {
        "id": 252,
        "slug": "palja",
        "name": "Manastir Palja",
        "urls": [
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%9F%D0%B0%D1%99%D0%B0"
        ]
    },
    {
        "id": 253,
        "slug": "sveti-nikola-vranje",
        "name": "Manastir Sveti Nikola",
        "urls": [
            "https://sr.wikipedia.org/wiki/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%A1%D0%B2%D0%B5%D1%82%D0%BE%D0%B3_%D0%BE%D1%86%D0%B0_%D0%9D%D0%B8%D0%BA%D0%BE%D0%BB%D0%B0%D1%98%D0%B0_%D1%83_%D0%92%D1%80%D0%B0%D1%9A%D1%83"
        ]
    },
]

def extract_all_photos(url):
    req = urllib.request.Request(url, headers=HEADERS)
    with urllib.request.urlopen(req, context=ctx, timeout=10) as resp:
        html = resp.read().decode('utf-8', errors='ignore')
    
    srcs = re.findall(r'<img\s+[^>]*src=["\']([^"\']+)["\'][^>]*>', html)
    res = []
    seen = set()
    for s in srcs:
        if 'commons/thumb' not in s:
            continue
        # clean url
        s_clean = s.replace('&amp;', '&')
        if not s_clean.startswith('http'):
            s_clean = 'https:' + s_clean
            
        m = re.search(r'/wikipedia/commons/thumb/([a-f0-9]/[a-f0-9]{2}/[^/]+)/', s_clean)
        if m:
            path_part = m.group(1)
            file_name = urllib.parse.unquote(path_part.split('/')[-1])
            if any(ign in file_name.lower() for ign in ['flag', 'cross', 'wikidata', 'icon', 'logo', 'portal', 'symbol', 'stub', 'history', 'commons-logo', 'grb', 'padlock', 'magnify', 'oojs']):
                continue
            if file_name not in seen:
                seen.add(file_name)
                res.append({
                    'path_part': path_part,
                    'file_name': file_name,
                    'orig_src': s_clean
                })
    return res

def download_photo(img_info, target_filename):
    target_path = os.path.join(dest_dir, target_filename)
    path_part = img_info['path_part']
    raw_fname = path_part.split('/')[-1]
    
    urls_to_try = [
        f"https://upload.wikimedia.org/wikipedia/commons/thumb/{path_part}/1280px-{raw_fname}",
        f"https://upload.wikimedia.org/wikipedia/commons/thumb/{path_part}/500px-{raw_fname}",
        f"https://upload.wikimedia.org/wikipedia/commons/thumb/{path_part}/250px-{raw_fname}",
        img_info['orig_src'].split('?')[0],
        img_info['orig_src']
    ]
    
    for u in urls_to_try:
        try:
            req = urllib.request.Request(u, headers=HEADERS)
            with urllib.request.urlopen(req, context=ctx, timeout=10) as r:
                data = r.read()
                if len(data) > 1000:
                    with open(target_path, 'wb') as f:
                        f.write(data)
                    print(f"  [SAVED] {target_filename} ({len(data)} bytes) from {img_info['file_name']}")
                    return True
        except Exception:
            pass
    print(f"  [FAILED] {target_filename} ({img_info['file_name']})")
    return False

results = {}

for m in monasteries:
    slug = m['slug']
    print(f"\n==================================================")
    print(f"[{m['id']}] {m['name']} ({slug})")
    print(f"==================================================")
    
    all_imgs = []
    seen = set()
    for u in m['urls']:
        try:
            imgs = extract_all_photos(u)
            for im in imgs:
                if im['file_name'] not in seen:
                    seen.add(im['file_name'])
                    all_imgs.append(im)
        except Exception as e:
            print(f"  [ERR] {u}: {e}")
            
    print(f"Found {len(all_imgs)} monastery photos")
    saved_list = []
    for idx, im in enumerate(all_imgs[:4]):
        time.sleep(0.15)
        if idx == 0:
            fname = f"{slug}.jpg"
        else:
            fname = f"{slug}_gal_{idx}.jpg"
        if download_photo(im, fname):
            saved_list.append(fname)
            
    results[slug] = saved_list

print("\n==================================================")
print("DOWNLOAD SUMMARY:")
print("==================================================")
for slug, fl in results.items():
    print(f"  {slug} ({len(fl)} images): {fl}")
