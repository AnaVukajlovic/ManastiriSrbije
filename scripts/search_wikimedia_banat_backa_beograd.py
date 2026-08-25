import urllib.request
import urllib.parse
import json
import os
import sys
import io
import re

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

HEADERS = {
    'User-Agent': 'ManastiriSrbijeHeritage/3.0 (Educational Serbian monasteries portal; https://manastirisrbije.rs; contact: dev@manastirisrbije.rs)'
}

monasteries = [
    # BANATSKA
    ('bavaniste', 'Манастир Баваниште', 'Monastery of Bavanište'),
    ('gaj', 'Манастир Гај', 'Gaj monastery'),
    ('hajducica', 'Манастир Хајдучица', 'Hajdučica monastery'),
    ('mesic', 'Манастир Месић', 'Mesić monastery'),
    ('srediste', 'Манастир Средиште', 'Središte monastery'),
    ('sveta-trojica-kikinda', 'Манастир Свете Тројице у Кикинди', 'Monastery of Holy Trinity in Kikinda'),
    ('svete-melanije', 'Манастир Свете Меланије', 'Monastery of Saint Melania (Zrenjanin)'),
    ('vlajkovac', 'Влајковац', 'Vlajkovac'),
    ('vojlovica', 'Манастир Војловица', 'Vojlovica monastery'),
    
    # BAČKA
    ('bodjani', 'Манастир Бођани', 'Bođani monastery'),
    ('kac', 'Манастир Каћ', 'Kać monastery'),
    ('kovilj', 'Манастир Ковиљ', 'Kovilj monastery'),
    ('sombor', 'Манастир Сомбор', 'Sombor monastery'),
    ('vodica', 'Манастир Водица', 'Vodica monastery'),
    
    # BEOGRADSKA
    ('mislodjin', 'Манастир Мислођин', 'Mislođin monastery'),
    ('rajinovac', 'Манастир Рајиновац', 'Rajinovac monastery'),
    ('rakovica', 'Манастир Раковица', 'Rakovica monastery'),
    ('senjak', 'Манастир Ваведење Пресвете Богородице на Сењаку', 'Vavedenje Monastery (Belgrade)'),
    ('slanci', 'Манастир Сланци', 'Slanci monastery'),
    ('trojerucica', 'Манастир Тројеручица (Београд)', 'Trojeručica monastery (Belgrade)')
]

def search_wikimedia(query):
    # Search commons.wikimedia.org
    url = f"https://commons.wikimedia.org/w/api.php?action=query&generator=search&gsrsearch={urllib.parse.quote(query)}&gsrnamespace=6&gsrlimit=15&prop=imageinfo&iiprop=url|size|mime|extmetadata&format=json"
    try:
        req = urllib.request.Request(url, headers=HEADERS)
        with urllib.request.urlopen(req, timeout=10) as r:
            data = json.loads(r.read().decode('utf-8'))
            pages = data.get('query', {}).get('pages', {})
            imgs = []
            for pid, pdata in pages.items():
                title = pdata.get('title', '')
                infos = pdata.get('imageinfo', [])
                if not infos:
                    continue
                info = infos[0]
                mime = info.get('mime', '')
                w = info.get('width', 0)
                h = info.get('height', 0)
                u = info.get('url', '')
                desc = info.get('extmetadata', {}).get('ImageDescription', {}).get('value', '')
                desc = re.sub(r'<[^>]+>', '', desc).strip()
                if mime in ['image/jpeg', 'image/png', 'image/webp'] and w >= 600 and h >= 500:
                    imgs.append({
                        'title': title,
                        'url': u,
                        'width': w,
                        'height': h,
                        'desc': desc
                    })
            return imgs
    except Exception as e:
        print(f"Error searching commons for {query}: {e}")
        return []

def search_sr_wiki(query):
    # Search sr.wikipedia.org
    url = f"https://sr.wikipedia.org/w/api.php?action=query&titles={urllib.parse.quote(query)}&generator=images&gimlimit=20&prop=imageinfo&iiprop=url|size|mime|extmetadata&format=json"
    try:
        req = urllib.request.Request(url, headers=HEADERS)
        with urllib.request.urlopen(req, timeout=10) as r:
            data = json.loads(r.read().decode('utf-8'))
            pages = data.get('query', {}).get('pages', {})
            imgs = []
            for pid, pdata in pages.items():
                title = pdata.get('title', '')
                infos = pdata.get('imageinfo', [])
                if not infos:
                    continue
                info = infos[0]
                mime = info.get('mime', '')
                w = info.get('width', 0)
                h = info.get('height', 0)
                u = info.get('url', '')
                if mime in ['image/jpeg', 'image/png', 'image/webp'] and w >= 600 and h >= 500:
                    imgs.append({
                        'title': title,
                        'url': u,
                        'width': w,
                        'height': h
                    })
            return imgs
    except Exception as e:
        print(f"Error searching sr.wiki for {query}: {e}")
        return []

for slug, sr_q, en_q in monasteries:
    print(f"\n========================================================")
    print(f"  {sr_q.upper()} ({slug})")
    print(f"========================================================")
    
    sr_res = search_sr_wiki(sr_q)
    commons_res = search_wikimedia(f"{sr_q} OR {en_q}")
    
    all_res = sr_res + commons_res
    seen = set()
    unique = []
    
    bad = ['logo', 'flag', 'zastava', 'map', 'karta', 'grb', 'wappen', 'icon', 'ambox', 'nuvola', 'commons', 'signature', 'potpis', 'pecat', 'patrijarh', 'arhimandrit', 'svestenik', 'monah', '1912', '1945', '1986', 'marzik']
    
    for item in all_res:
        t = item['title'].lower()
        u = item['url']
        if u not in seen and not any(b in t or b in u.lower() for b in bad):
            seen.add(u)
            unique.append(item)
            
    print(f"Pronađeno {len(unique)} Wikimedia slika:")
    for im in unique[:6]:
        print(f"  • {im['title']}")
        print(f"    URL: {im['url']}")
