import urllib.request
import urllib.parse
import json
import time
import sys
import ssl

sys.stdout.reconfigure(encoding='utf-8')

ctx = ssl.create_default_context()
ctx.check_hostname = False
ctx.verify_mode = ssl.CERT_NONE

HEADERS = {'User-Agent': 'PravoslavniSvetionikBot/1.0 (contact@pravoslavnisvetionik.rs)'}

monasteries = [
    {
        "id": 167,
        "slug": "bresnica",
        "name": "Manastir Bresnica",
        "wiki_titles": ["Манастир Бресница", "Бресница (Босилеград)"]
    },
    {
        "id": 168,
        "slug": "kacapun",
        "name": "Manastir Kacapun",
        "wiki_titles": ["Манастир Кацапун", "Кацапун"]
    },
    {
        "id": 169,
        "slug": "lopardince",
        "name": "Manastir Lopardince",
        "wiki_titles": ["Манастир Лопардинце", "Лопардинце"]
    },
    {
        "id": 170,
        "slug": "prohor-pcinjski",
        "name": "Manastir Prohor Pčinjski",
        "wiki_titles": ["Манастир Прохор Пчињски", "Прохор Пчињски"]
    },
    {
        "id": 171,
        "slug": "zapsko",
        "name": "Manastir Žapsko",
        "wiki_titles": ["Манастир Жапско", "Жапско"]
    },
    {
        "id": 240,
        "slug": "dubnica-milesevska",
        "name": "Manastir Dubnica",
        "wiki_titles": ["Манастир Дубница (Врање)", "Дубница (Врање)"]
    },
    {
        "id": 246,
        "slug": "kozji-dol",
        "name": "Manastir Kozji Dol",
        "wiki_titles": ["Манастир Козји Дол", "Козји Дол (Трговиште)"]
    },
    {
        "id": 247,
        "slug": "lepcince",
        "name": "Manastir Lepčince",
        "wiki_titles": ["Манастир Лепчинце", "Лепчинце"]
    },
    {
        "id": 249,
        "slug": "simeon-stolpnik",
        "name": "Manastir Simeon Stolpnik",
        "wiki_titles": ["Манастир Светог Симеона Столпника у Собини", "Собина (Врање)"]
    },
    {
        "id": 251,
        "slug": "mrtvica",
        "name": "Manastir Mrtvica",
        "wiki_titles": ["Манастир Мртвица", "Мртвица (Владичин Хан)"]
    },
    {
        "id": 252,
        "slug": "palja",
        "name": "Manastir Palja",
        "wiki_titles": ["Манастир Паља", "Паља"]
    },
    {
        "id": 253,
        "slug": "sveti-nikola-vranje",
        "name": "Manastir Sveti Nikola",
        "wiki_titles": ["Манастир Светог Николе у Врању", "Црква Светог Николе у Врању"]
    },
]

def fetch_json(url):
    req = urllib.request.Request(url, headers=HEADERS)
    try:
        with urllib.request.urlopen(req, context=ctx, timeout=10) as r:
            return json.loads(r.read().decode('utf-8'))
    except Exception as e:
        return None

def get_file_info(file_title):
    commons_title = file_title.replace("Датотека:", "File:")
    url = f"https://commons.wikimedia.org/w/api.php?action=query&titles={urllib.parse.quote(commons_title)}&prop=imageinfo&iiprop=url|size|extmetadata&format=json"
    data = fetch_json(url)
    if data and 'query' in data:
        pages = data['query'].get('pages', {})
        for pid, pdata in pages.items():
            if 'imageinfo' in pdata:
                info = pdata['imageinfo'][0]
                meta = info.get('extmetadata', {})
                desc = meta.get('ImageDescription', {}).get('value', '') or meta.get('ObjectName', {}).get('value', '')
                return {
                    'url': info.get('url', ''),
                    'width': info.get('width', 0),
                    'height': info.get('height', 0),
                    'desc': desc
                }
    # Fallback on sr.wikipedia.org
    url_sr = f"https://sr.wikipedia.org/w/api.php?action=query&titles={urllib.parse.quote(file_title)}&prop=imageinfo&iiprop=url|size|extmetadata&format=json"
    data = fetch_json(url_sr)
    if data and 'query' in data:
        pages = data['query'].get('pages', {})
        for pid, pdata in pages.items():
            if 'imageinfo' in pdata:
                info = pdata['imageinfo'][0]
                return {
                    'url': info.get('url', ''),
                    'width': info.get('width', 0),
                    'height': info.get('height', 0),
                    'desc': ''
                }
    return None

results = {}

for m in monasteries:
    slug = m['slug']
    print(f"\n==================================================")
    print(f"[{m['id']}] {m['name']} ({slug})")
    print(f"==================================================")
    found_files = []
    seen = set()
    
    for wt in m['wiki_titles']:
        time.sleep(0.5)
        url = f"https://sr.wikipedia.org/w/api.php?action=query&titles={urllib.parse.quote(wt)}&prop=images&format=json"
        data = fetch_json(url)
        if not data:
            continue
        pages = data.get('query', {}).get('pages', {})
        for pid, pdata in pages.items():
            if 'images' in pdata:
                for img in pdata['images']:
                    ititle = img['title']
                    if any(ign in ititle.lower() for ign in ['icon', 'logo', 'flag', 'map', 'stub', 'location', 'portal', 'cross', 'edit', 'history', 'commons']):
                        continue
                    if ititle not in seen:
                        seen.add(ititle)
                        time.sleep(0.3)
                        info = get_file_info(ititle)
                        if info and info['url'] and any(info['url'].lower().endswith(ext) for ext in ['.jpg', '.jpeg', '.png']):
                            found_files.append({
                                'file_title': ititle,
                                'url': info['url'],
                                'width': info['width'],
                                'height': info['height'],
                                'desc': info['desc']
                            })
                            print(f"  + [{ititle}] ({info['width']}x{info['height']}) -> {info['url']}")

    results[slug] = found_files

with open(r'd:\projekti\ManastiriSrbije\backend\vranjska_wiki_found.json', 'w', encoding='utf-8') as f:
    json.dump(results, f, ensure_ascii=False, indent=2)

print("\nFinished! Results saved to vranjska_wiki_found.json")
