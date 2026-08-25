import urllib.request
import urllib.parse
import json
import sys
import io
import time
import re

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

HEADERS = {
    'User-Agent': 'ManastiriSrbijeResearchBot/1.0 (https://manastirisrbije.rs; contact@manastirisrbije.rs) python-requests'
}

monasteries = [
    # BANATSKA
    ('bavaniste', 'Манастир Баваниште', 'Category:Bavanište monastery'),
    ('gaj', 'Манастир Гај', 'Category:Gaj'),
    ('hajducica', 'Манастир Хајдучица', 'Category:Hajdučica monastery'),
    ('mesic', 'Манастир Месић', 'Category:Mesić monastery'),
    ('srediste', 'Манастир Средиште', 'Category:Središte monastery'),
    ('sveta-trojica-kikinda', 'Манастир Свете Тројице у Кикинди', 'Category:Holy Trinity monastery in Kikinda'),
    ('svete-melanije', 'Манастир Свете Меланије', 'Category:Saint Melania monastery (Zrenjanin)'),
    ('vlajkovac', 'Влајковац', 'Category:Vlajkovac'),
    ('vojlovica', 'Манастир Војловица', 'Category:Vojlovica monastery'),
    
    # BAČKA
    ('bodjani', 'Манастир Бођани', 'Category:Bođani monastery'),
    ('kac', 'Манастир Каћ', 'Category:Kać'),
    ('kovilj', 'Манастир Ковиљ', 'Category:Kovilj monastery'),
    ('sombor', 'Манастир Сомбор', 'Category:Monasteries in Sombor'),
    ('vodica', 'Манастир Водица код Бача', 'Category:Bač'),
    
    # BEOGRADSKA
    ('mislodjin', 'Манастир Мислођин', 'Category:Mislođin monastery'),
    ('rajinovac', 'Манастир Рајиновац', 'Category:Rajinovac monastery'),
    ('rakovica', 'Манастир Раковица', 'Category:Rakovica monastery'),
    ('senjak', 'Манастир Ваведење Пресвете Богородице на Сењаку', 'Category:Vavedenje monastery (Belgrade)'),
    ('slanci', 'Манастир Сланци', 'Category:Slanci monastery'),
    ('trojerucica', 'Манастир Тројеручица (Београд)', 'Category:Trojeručica monastery (Belgrade)')
]

def find_files_for_monastery(slug, sr_title, cat_name):
    files = []
    
    # 1. Search in sr.wikipedia page parse
    try:
        time.sleep(1.0)
        api_sr = f"https://sr.wikipedia.org/w/api.php?action=parse&page={urllib.parse.quote(sr_title)}&prop=images&format=json"
        req = urllib.request.Request(api_sr, headers=HEADERS)
        with urllib.request.urlopen(req, timeout=10) as r:
            data = json.loads(r.read().decode('utf-8'))
            for im in data.get('parse', {}).get('images', []):
                if not im.endswith('.svg') and not im.endswith('.ogg') and not im.endswith('.pdf'):
                    files.append('File:' + im)
    except Exception as e:
        pass
        
    # 2. Search category in Wikimedia Commons
    try:
        time.sleep(1.0)
        api_cat = f"https://commons.wikimedia.org/w/api.php?action=query&list=categorymembers&cmtitle={urllib.parse.quote(cat_name)}&cmtype=file&cmlimit=20&format=json"
        req = urllib.request.Request(api_cat, headers=HEADERS)
        with urllib.request.urlopen(req, timeout=10) as r:
            data = json.loads(r.read().decode('utf-8'))
            for m in data.get('query', {}).get('categorymembers', []):
                files.append(m.get('title'))
    except Exception as e:
        pass
        
    # 3. Direct search on Commons
    try:
        time.sleep(1.0)
        clean_q = sr_title.replace('Манастир ', '')
        api_search = f"https://commons.wikimedia.org/w/api.php?action=query&list=search&srsearch={urllib.parse.quote(clean_q + ' manastir')}&srnamespace=6&srlimit=10&format=json"
        req = urllib.request.Request(api_search, headers=HEADERS)
        with urllib.request.urlopen(req, timeout=10) as r:
            data = json.loads(r.read().decode('utf-8'))
            for m in data.get('query', {}).get('search', []):
                files.append(m.get('title'))
    except Exception as e:
        pass

    # Filter out logos, maps, coats of arms, flags, portraits of modern people
    bad = ['logo', 'flag', 'zastava', 'map', 'karta', 'grb', 'wappen', 'ambox', 'nuvola', 'commons', 'signature', 'potpis', 'pecat', 'patrijarh', 'svestenik', 'monah', '1912', '1945', '1986', 'marzik', 'location', 'ikona-stub']
    
    unique = []
    seen = set()
    for f in files:
        if not f:
            continue
        fl = f.lower()
        if f not in seen and not any(b in fl for b in bad):
            seen.add(f)
            unique.append(f)
            
    return unique

print("=== PRETRAGA WIKIMEDIA FAJLOVA ZA 20 MANASTIRA ===")
for slug, sr_title, cat in monasteries:
    found = find_files_for_monastery(slug, sr_title, cat)
    print(f"\n[{slug}] {sr_title} -> {len(found)} fajlova:")
    for f in found[:5]:
        print(f"  • {f}")
