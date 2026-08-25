import os
import sys
import io
import re
import json
import sqlite3
import time
import urllib.request
import urllib.parse
from bs4 import BeautifulSoup

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
CACHE_DIR = os.path.join(BASE_DIR, 'storage', 'cache_manastiri_rs')
DB_STORAGE_PATH = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE_PATH = os.path.join(BASE_DIR, 'database', 'database.sqlite')

HEADERS = {
    'User-Agent': 'PravoslavniSvetionik/1.0 (https://github.com/manastiri-srbije; kontakt@svetionik.org.rs)'
}

def clean_caption_from_filename(filename, monastery_name):
    # Remove file extension and prefixes
    name = re.sub(r'^(?:Датотека|File):', '', filename)
    name = re.sub(r'\.(?:jpg|jpeg|png|webp)$', '', name, flags=re.I)
    name = re.sub(r'[-_]', ' ', name)
    name = re.sub(r'\s+', ' ', name).strip()
    
    # Meaningful Serbian captions based on keywords
    low = name.lower()
    if 'ktitor' in low or 'milutin' in low or 'nemanja' in low or 'stefan' in low:
        return f"Ktitorska freska i prikaz zadužbinara – {monastery_name}"
    elif 'fresk' in low or 'fresco' in low or 'pantocrator' in low or 'hrist' in low or 'bogorodic' in low or 'sava' in low:
        return f"Srednjovekovni živopis i freske – {monastery_name}"
    elif 'ikonostas' in low or 'ikona' in low or 'icon' in low:
        return f"Ikonostas i svete ikone u hramu – {monastery_name}"
    elif 'enterijer' in low or 'interior' in low or 'unutra' in low:
        return f"Unutrašnjost manastirskog hrama – {monastery_name}"
    elif 'zvonik' in low or 'porta' in low or 'konak' in low or 'panorama' in low or 'aerial' in low:
        return f"Manastirski kompleks i porta – {monastery_name}"
    else:
        return f"Pogled na manastirski hram – {monastery_name}"

def fetch_wiki_images_for_monastery(monastery_name):
    query_titles = [
        monastery_name,
        monastery_name.replace('Manastir ', 'Манастир '),
        monastery_name.replace('Manastir ', 'Црква '),
        'Црква Богородица Љевишка' if 'ljevišk' in monastery_name.lower() or 'ljevisk' in monastery_name.lower() else None
    ]
    
    # 1. Search for best Wikipedia title
    search_query = monastery_name
    best_wiki_title = None
    try:
        surl = f"https://sr.wikipedia.org/w/api.php?action=query&list=search&srsearch={urllib.parse.quote(search_query)}&format=json"
        req = urllib.request.Request(surl, headers=HEADERS)
        sdata = json.loads(urllib.request.urlopen(req, timeout=5).read().decode('utf-8'))
        sres = sdata.get('query', {}).get('search', [])
        if sres:
            best_wiki_title = sres[0]['title']
    except Exception:
        pass

    if not best_wiki_title:
        best_wiki_title = query_titles[1]

    # 2. Get images and thumbnail
    images = []
    try:
        purl = f"https://sr.wikipedia.org/w/api.php?action=query&titles={urllib.parse.quote(best_wiki_title)}&prop=pageimages|images&pithumbsize=1280&imlimit=25&format=json"
        req = urllib.request.Request(purl, headers=HEADERS)
        pdata = json.loads(urllib.request.urlopen(req, timeout=6).read().decode('utf-8'))
        pages = pdata.get('query', {}).get('pages', {})
        
        file_titles = []
        for pid, p in pages.items():
            if 'thumbnail' in p:
                main_thumb = p['thumbnail']['source']
                images.append({
                    'url': main_thumb,
                    'caption': f"Glavni hram – {monastery_name}"
                })
            raw_files = [im['title'] for im in p.get('images', [])]
            for rf in raw_files:
                low = rf.lower()
                if any(ext in low for ext in ['.jpg', '.jpeg', '.png', '.webp']):
                    if not any(x in low for x in ['icon', 'logo', 'flag', 'coat_of_arms', 'symbol', 'stub', 'question', 'portal', 'pd-icon', 'red_pog', 'commons-logo', 'ambox']):
                        file_titles.append(rf)
                        
        # 3. Resolve full URLs for up to 6 image files
        if file_titles:
            file_param = '|'.join(file_titles[:6])
            furl = f"https://sr.wikipedia.org/w/api.php?action=query&titles={urllib.parse.quote(file_param)}&prop=imageinfo&iiprop=url&format=json"
            freq = urllib.request.Request(furl, headers=HEADERS)
            fdata = json.loads(urllib.request.urlopen(freq, timeout=6).read().decode('utf-8'))
            for fpid, fp in fdata.get('query', {}).get('pages', {}).items():
                ii = fp.get('imageinfo', [])
                if ii:
                    img_url = ii[0].get('url', '')
                    caption = clean_caption_from_filename(fp.get('title', ''), monastery_name)
                    if img_url and not any(x['url'] == img_url for x in images):
                        images.append({
                            'url': img_url,
                            'caption': caption
                        })
    except Exception as e:
        pass
        
    return images

# Test function on Bogorodica Ljeviska
print("Testing image fetch for Bogorodica Ljeviška:")
imgs = fetch_wiki_images_for_monastery("Bogorodica Ljeviška")
for i, img in enumerate(imgs, 1):
    print(f"[{i}] {img['caption']}")
    print(f"    URL: {img['url']}")
