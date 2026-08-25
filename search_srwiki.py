import urllib.request
import urllib.parse
import json
import time
import os
import sys

sys.stdout.reconfigure(encoding='utf-8')

USER_AGENT = 'ManastiriSrbijeResearch/1.0 (https://github.com; contact@pravoslavnisvetionik.rs)'

targets = [
    ("braljina", ["Манастир Браљина", "Браљина"]),
    ("drenca", ["Манастир Дренча", "Дренча"]),
    ("drenova", ["Манастир Дренова", "Дренова (Брус)"]),
    ("grabovo", ["Манастир Грабово", "Грабово (Трстеник)"]),
    ("komorane", ["Манастир Коморане", "Коморане (Крушевац)"]),
    ("lepenac", ["Манастир Лепенац", "Лепенац (Брус)"]),
    ("makresane", ["Манастир Макрешане", "Макрешане"]),
    ("manastirak", ["Манастир Манастирак", "Манастирак"]),
    ("mrzenica", ["Манастир Мрзеница", "Мрзеница"]),
    ("petina", ["Манастир Петина", "Петина"]),
    ("ples", ["Манастир Плеш", "Плеш (Александровац)"]),
    ("stevanac", ["Манастир Стеванац", "Стеванац"]),
    ("strmac", ["Манастир Стрмац", "Стрмац (Брус)"]),
    ("zilinci", ["Манастир Жилинци", "Жилинци"]),
]

def get_wiki_images(title):
    url = f"https://sr.wikipedia.org/w/api.php?action=query&titles={urllib.parse.quote(title)}&prop=images|pageimages&piprop=original&format=json"
    req = urllib.request.Request(url, headers={'User-Agent': USER_AGENT})
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            pages = data.get('query', {}).get('pages', {})
            images = []
            for pid, page in pages.items():
                if 'original' in page:
                    images.append(page['original']['source'])
                if 'images' in page:
                    for im in page['images']:
                        im_title = im.get('title', '')
                        if not any(x in im_title.lower() for x in ['icon', 'logo', 'flag', 'map', 'stub']):
                            images.append(im_title)
            return images
    except Exception as e:
        print(f"Error for {title}: {e}")
        return []

def get_image_url(file_title):
    url = f"https://commons.wikimedia.org/w/api.php?action=query&titles={urllib.parse.quote(file_title)}&prop=imageinfo&iiprop=url&format=json"
    req = urllib.request.Request(url, headers={'User-Agent': USER_AGENT})
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            pages = data.get('query', {}).get('pages', {})
            for pid, page in pages.items():
                if 'imageinfo' in page:
                    return page['imageinfo'][0]['url']
    except Exception as e:
        pass
    return None

print("=== SEARCHING WIKIPEDIA PAGES ===")
for slug, titles in targets:
    found_urls = []
    for t in titles:
        imgs = get_wiki_images(t)
        time.sleep(0.5)
        for im in imgs:
            if im.startswith('http'):
                found_urls.append(im)
            elif im.startswith('Датотека:') or im.startswith('File:'):
                u = get_image_url(im)
                time.sleep(0.3)
                if u:
                    found_urls.append(u)
    print(f"[{slug}] Found {len(found_urls)} images:")
    for u in found_urls:
        print(f"   {u}")
