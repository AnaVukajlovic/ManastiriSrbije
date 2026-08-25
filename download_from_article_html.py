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

HEADERS = {
    'User-Agent': 'PravoslavniSvetionik/1.0 (https://pravoslavnisvetionik.rs; anavukajlovic@gmail.com)'
}

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

def get_article_images(url):
    req = urllib.request.Request(url, headers=HEADERS)
    with urllib.request.urlopen(req, context=ctx, timeout=10) as resp:
        html = resp.read().decode('utf-8', errors='ignore')
    
    srcs = re.findall(r'<img\s+[^>]*src=["\']([^"\']+)["\'][^>]*>', html)
    res = []
    seen = set()
    for s in srcs:
        if 'commons/thumb' not in s:
            continue
        s_clean = s.replace('&amp;', '&')
        if not s_clean.startswith('http'):
            s_clean = 'https:' + s_clean
            
        m = re.search(r'/wikipedia/commons/thumb/([a-f0-9]/[a-f0-9]{2}/[^/]+)/', s_clean)
        if m:
            path_part = m.group(1)
            file_name = urllib.parse.unquote(path_part.split('/')[-1])
            if any(ign in file_name.lower() for ign in ['flag', 'cross', 'wikidata', 'icon', 'logo', 'portal', 'symbol', 'stub', 'history', 'commons-logo', 'grb', 'padlock', 'magnify', 'oojs', 'location_map', 'red_pog', 'disambig']):
                continue
            if file_name not in seen:
                seen.add(file_name)
                # create 500px or fallback url
                raw_name = path_part.split('/')[-1]
                thumb_500 = f"https://upload.wikimedia.org/wikipedia/commons/thumb/{path_part}/500px-{raw_name}"
                res.append({
                    'file_name': file_name,
                    'thumb_500': thumb_500,
                    'orig_src': s_clean
                })
    return res

results = {}

for m in monasteries:
    slug = m['slug']
    print(f"\n[{m['id']}] {m['name']} ({slug})")
    all_imgs = []
    seen = set()
    for u in m['urls']:
        try:
            imgs = get_article_images(u)
            for im in imgs:
                if im['file_name'] not in seen:
                    seen.add(im['file_name'])
                    all_imgs.append(im)
        except Exception as e:
            print(f"  [ERR] {u}: {e}")
            
    print(f"Found {len(all_imgs)} valid images")
    saved = []
    for idx, im in enumerate(all_imgs[:4]):
        time.sleep(0.4)
        if idx == 0:
            fname = f"{slug}.jpg"
        else:
            fname = f"{slug}_gal_{idx}.jpg"
            
        tpath = os.path.join(dest_dir, fname)
        downloaded = False
        for utry in [im['thumb_500'], im['orig_src'].split('?')[0], im['orig_src']]:
            try:
                req = urllib.request.Request(utry, headers=HEADERS)
                with urllib.request.urlopen(req, context=ctx, timeout=10) as r:
                    data = r.read()
                    if len(data) > 1000:
                        with open(tpath, 'wb') as f:
                            f.write(data)
                        print(f"  [SAVED] {fname} ({len(data)} bytes) from {im['file_name']}")
                        saved.append(fname)
                        downloaded = True
                        break
            except Exception:
                pass
        if not downloaded:
            print(f"  [FAIL] {fname} ({im['file_name']})")
    results[slug] = saved

print("\n==================================================")
print("FINAL SUMMARY:")
print("==================================================")
for slug, fl in results.items():
    print(f"  {slug}: {len(fl)} images -> {fl}")
