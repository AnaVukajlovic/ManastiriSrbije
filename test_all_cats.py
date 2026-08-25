import urllib.request
import urllib.parse
import json
import time
import sys

sys.stdout.reconfigure(encoding='utf-8')

USER_AGENT = 'ManastiriSrbijeResearch/1.0 (contact@pravoslavnisvetionik.rs)'

cats_to_test = [
    ("ljubostinja", ["Category:Ljubostinja", "Category:Ljubostinja monastery", "Category:Манастир Љубостиња"]),
    ("naupare", ["Category:Naupara", "Category:Naupara monastery", "Category:Манастир Наупаре", "Category:Naupare"]),
    ("veluce", ["Category:Veluće", "Category:Veluće monastery", "Category:Манастир Велуће"]),
    ("drenca", ["Category:Drenča", "Category:Drenča monastery", "Category:Манастир Дренча"]),
    ("lepenac", ["Category:Lepenac", "Category:Lepenac monastery", "Category:Манастир Лепенац"]),
    ("stevanac", ["Category:Stevanac", "Category:Манастир Стеванац", "Category:Stevanac monastery"]),
    ("mrzenica", ["Category:Mrzenica", "Category:Манастир Мрзеница"]),
    ("petina", ["Category:Petina", "Category:Манастир Петина"]),
    ("ples", ["Category:Pleš", "Category:Манастир Плеш"]),
    ("strmac", ["Category:Strmac", "Category:Манастир Стрмац"]),
    ("braljina", ["Category:Braljina", "Category:Манастир Браљина"]),
    ("komorane", ["Category:Komorane", "Category:Манастир Коморане"]),
    ("svojnovo", ["Category:Svojnovo", "Category:Манастир Својново"]),
    ("grabovo", ["Category:Grabovo", "Category:Манастир Грабово"]),
    ("drenova", ["Category:Drenova", "Category:Манастир Дренова"]),
    ("zilinci", ["Category:Žilinci", "Category:Манастир Жилинци"]),
    ("manastirak", ["Category:Manastirak", "Category:Манастир Манастирак"]),
    ("makresane", ["Category:Makrešane", "Category:Манастир Макрешане"]),
    ("bosnjane", ["Category:Bošnjane", "Category:Манастир Бошњане"]),
]

def get_category_files(cat):
    url = f"https://commons.wikimedia.org/w/api.php?action=query&list=categorymembers&cmtitle={urllib.parse.quote(cat)}&cmtype=file&cmlimit=20&format=json"
    req = urllib.request.Request(url, headers={'User-Agent': USER_AGENT})
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            members = data.get('query', {}).get('categorymembers', [])
            return [m['title'] for m in members]
    except Exception as e:
        return []

def get_file_url(title):
    url = f"https://commons.wikimedia.org/w/api.php?action=query&titles={urllib.parse.quote(title)}&prop=imageinfo&iiprop=url&format=json"
    req = urllib.request.Request(url, headers={'User-Agent': USER_AGENT})
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            pages = data.get('query', {}).get('pages', {})
            for pid, p in pages.items():
                if 'imageinfo' in p:
                    return p['imageinfo'][0]['url']
    except Exception as e:
        pass
    return None

results = {}
for slug, cats in cats_to_test:
    all_files = []
    for c in cats:
        f = get_category_files(c)
        time.sleep(0.3)
        if f:
            all_files.extend(f)
    all_files = list(set(all_files))
    print(f"[{slug}] -> {len(all_files)} unique files")
    file_urls = []
    for f in all_files[:6]:
        u = get_file_url(f)
        time.sleep(0.2)
        if u and any(u.lower().endswith(ext) for ext in ['.jpg', '.jpeg', '.png']):
            file_urls.append({'title': f, 'url': u})
            print(f"   {f} -> {u}")
    results[slug] = file_urls

with open(r"d:\projekti\ManastiriSrbije\backend\commons_all_krusevacka.json", "w", encoding="utf-8") as fp:
    json.dump(results, fp, ensure_ascii=False, indent=2)
