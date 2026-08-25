import urllib.request
import urllib.parse
import json
import re
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'}

def get_images_for_article(title):
    # 1. Get image filenames in page
    api_url = f"https://sr.wikipedia.org/w/api.php?action=parse&page={urllib.parse.quote(title)}&prop=images&format=json"
    req = urllib.request.Request(api_url, headers=headers)
    try:
        data = json.loads(urllib.request.urlopen(req, timeout=10).read().decode('utf-8'))
        images = data.get('parse', {}).get('images', [])
    except Exception as e:
        print(f"Error {title}: {e}")
        return []

    # 2. Get URLs for these image filenames
    if not images:
        return []
    
    file_titles = "|".join([f"File:{im}" for im in images if not im.endswith('.svg') and not im.endswith('.ogg') and not im.endswith('.pdf')])
    if not file_titles:
        return []
    
    info_url = f"https://commons.wikimedia.org/w/api.php?action=query&titles={urllib.parse.quote(file_titles)}&prop=imageinfo&iiprop=url|size|mime&format=json"
    req2 = urllib.request.Request(info_url, headers=headers)
    try:
        info_data = json.loads(urllib.request.urlopen(req2, timeout=10).read().decode('utf-8'))
        pages = info_data.get('query', {}).get('pages', {})
        results = []
        for pid, pdata in pages.items():
            t = pdata.get('title', '')
            infos = pdata.get('imageinfo', [])
            if infos:
                results.append((t, infos[0].get('url'), infos[0].get('width'), infos[0].get('height')))
        return results
    except Exception as e:
        # Try on sr.wikipedia instead of commons
        info_url_sr = f"https://sr.wikipedia.org/w/api.php?action=query&titles={urllib.parse.quote(file_titles)}&prop=imageinfo&iiprop=url|size|mime&format=json"
        req3 = urllib.request.Request(info_url_sr, headers=headers)
        info_data = json.loads(urllib.request.urlopen(req3, timeout=10).read().decode('utf-8'))
        pages = info_data.get('query', {}).get('pages', {})
        results = []
        for pid, pdata in pages.items():
            t = pdata.get('title', '')
            infos = pdata.get('imageinfo', [])
            if infos:
                results.append((t, infos[0].get('url'), infos[0].get('width'), infos[0].get('height')))
        return results

test_titles = [
    'Манастир Бођани',
    'Манастир Ковиљ',
    'Манастир Раковица',
    'Манастир Рајиновац',
    'Манастир Сланци',
    'Манастир Војловица',
    'Манастир Месић',
    'Манастир Баваниште',
    'Манастир Хајдучица',
    'Манастир Средиште',
    'Манастир Ваведење Пресвете Богородице на Сењаку'
]

for t in test_titles:
    print(f"\n=== {t} ===")
    res = get_images_for_article(t)
    for r in res:
        print(f"  • {r[0]} ({r[2]}x{r[3]})")
        print(f"    {r[1]}")
