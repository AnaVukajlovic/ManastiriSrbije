import urllib.request
import urllib.parse
import json
import ssl
import sys
import os

sys.stdout.reconfigure(encoding='utf-8')

ctx = ssl.create_default_context()
ctx.check_hostname = False
ctx.verify_mode = ssl.CERT_NONE

USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36'

monasteries = [
    {
        "id": 167,
        "slug": "bresnica",
        "name": "Manastir Bresnica",
        "wiki_titles": ["Манастир Бресница", "Црква Свете Петке у Бресници", "Бресница (Босилеград)"]
    },
    {
        "id": 168,
        "slug": "kacapun",
        "name": "Manastir Kacapun",
        "wiki_titles": ["Манастир Кацапун", "Кацапун", "Црква Светог пророка Илије у Кацапуну"]
    },
    {
        "id": 169,
        "slug": "lopardince",
        "name": "Manastir Lopardince",
        "wiki_titles": ["Манастир Лопардинце", "Црква Светог Ђорђа у Лопардинцу", "Лопардинце"]
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
        "wiki_titles": ["Манастир Жапско", "Манастир Светог Стефана у Жапском", "Жапско"]
    },
    {
        "id": 240,
        "slug": "dubnica-milesevska",
        "name": "Manastir Dubnica",
        "wiki_titles": ["Манастир Дубница (Врање)", "Манастир Светих апостола Петра и Павла у Дубници", "Дубница (Врање)"]
    },
    {
        "id": 246,
        "slug": "kozji-dol",
        "name": "Manastir Kozji Dol",
        "wiki_titles": ["Манастир Козји Дол", "Манастир Преображења Господњег у Козјем Долу", "Козји Дол (Трговиште)"]
    },
    {
        "id": 247,
        "slug": "lepcince",
        "name": "Manastir Lepčince",
        "wiki_titles": ["Манастир Лепчинце", "Манастир Светог Пантелејмона у Лепчинцу", "Лепчинце"]
    },
    {
        "id": 249,
        "slug": "simeon-stolpnik",
        "name": "Manastir Simeon Stolpnik",
        "wiki_titles": ["Манастир Светог Симеона Столпника у Собини", "Манастир Свети Симеон Столпник", "Собина (Врање)"]
    },
    {
        "id": 251,
        "slug": "mrtvica",
        "name": "Manastir Mrtvica",
        "wiki_titles": ["Манастир Мртвица", "Манастир Успења Пресвете Богородице у Мртвици", "Мртвица (Владичин Хан)"]
    },
    {
        "id": 252,
        "slug": "palja",
        "name": "Manastir Palja",
        "wiki_titles": ["Манастир Паља", "Манастир Ваведења Пресвете Богородице у Паљи", "Паља"]
    },
    {
        "id": 253,
        "slug": "sveti-nikola-vranje",
        "name": "Manastir Sveti Nikola",
        "wiki_titles": ["Манастир Светог Николе у Врању", "Црква Светог Николе у Врању", "Црква Светог Николе (Врање)"]
    },
]

def fetch_json(url):
    req = urllib.request.Request(url, headers={'User-Agent': USER_AGENT})
    try:
        with urllib.request.urlopen(req, context=ctx, timeout=10) as r:
            return json.loads(r.read().decode('utf-8'))
    except Exception as e:
        print(f"Fetch error {url[:60]}: {e}")
        return None

def get_file_info(file_title):
    url = f"https://commons.wikimedia.org/w/api.php?action=query&titles={urllib.parse.quote(file_title)}&prop=imageinfo&iiprop=url|size|extmetadata&format=json"
    data = fetch_json(url)
    if not data:
        # try on sr.wikipedia.org
        url_sr = f"https://sr.wikipedia.org/w/api.php?action=query&titles={urllib.parse.quote(file_title)}&prop=imageinfo&iiprop=url|size|extmetadata&format=json"
        data = fetch_json(url_sr)
    if data:
        pages = data.get('query', {}).get('pages', {})
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
    return None

results = {}

for m in monasteries:
    slug = m['slug']
    print(f"\n==================================================")
    print(f"Processing {m['name']} (ID {m['id']}, slug: {slug})")
    print(f"==================================================")
    found_files = []
    seen = set()
    
    for wt in m['wiki_titles']:
        url = f"https://sr.wikipedia.org/w/api.php?action=query&titles={urllib.parse.quote(wt)}&prop=images|pageimages&piprop=original&format=json"
        data = fetch_json(url)
        if not data:
            continue
        pages = data.get('query', {}).get('pages', {})
        for pid, pdata in pages.items():
            if 'images' in pdata:
                for img in pdata['images']:
                    ititle = img['title']
                    if any(ign in ititle.lower() for ign in ['icon', 'logo', 'flag', 'map', 'stub', 'location', 'portal', 'cross', 'edit', 'history']):
                        continue
                    if ititle not in seen:
                        seen.add(ititle)
                        info = get_file_info(ititle)
                        if info and info['url'] and any(info['url'].lower().endswith(ext) for ext in ['.jpg', '.jpeg', '.png']):
                            found_files.append({
                                'title': ititle,
                                'url': info['url'],
                                'width': info['width'],
                                'height': info['height'],
                                'desc': info['desc']
                            })
                            print(f"  + [{ititle}] -> {info['url']}")

    results[slug] = found_files

with open(r'd:\projekti\ManastiriSrbije\backend\vranjska_wiki_images.json', 'w', encoding='utf-8') as f:
    json.dump(results, f, ensure_ascii=False, indent=2)

print("\nDone! Saved all file data to vranjska_wiki_images.json")
