import urllib.request
import urllib.parse
import json
import time
import sys

sys.stdout.reconfigure(encoding='utf-8')

USER_AGENT = 'ManastiriSrbijeResearch/1.0 (contact@pravoslavnisvetionik.rs)'

categories = [
    ("ljubostinja", "Category:Ljubostinja monastery"),
    ("naupare", "Category:Naupara monastery"),
    ("naupare2", "Category:Naupare monastery"),
    ("veluce", "Category:Veluće monastery"),
    ("drenca", "Category:Drenča monastery"),
    ("lepenac", "Category:Lepenac monastery"),
    ("stevanac", "Category:Stevanac monastery"),
    ("stevanac2", "Category:Manastir Stevanac"),
    ("braljina", "Category:Braljina monastery"),
    ("braljina2", "Category:Manastir Braljina"),
    ("bosnjane", "Category:Bošnjane monastery"),
    ("lesje", "Category:Lešje monastery"),
    ("komorane", "Category:Komorane monastery"),
    ("mrzenica", "Category:Mrzenica monastery"),
    ("petina", "Category:Petina monastery"),
    ("ples", "Category:Pleš monastery"),
    ("strmac", "Category:Strmac monastery"),
    ("zilinci", "Category:Žilinci monastery"),
    ("manastirak", "Category:Manastirak"),
    ("grabovo", "Category:Grabovo monastery"),
    ("drenova", "Category:Drenova monastery"),
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

print("=== SEARCHING COMMONS CATEGORIES ===")
cat_results = {}
for slug, cat in categories:
    files = get_category_files(cat)
    time.sleep(0.3)
    if files:
        print(f"[{slug}] {cat} -> {len(files)} files")
        urls = []
        for f in files[:5]:
            u = get_file_url(f)
            time.sleep(0.2)
            if u:
                print(f"   {f} -> {u}")
                urls.append({'title': f, 'url': u})
        cat_results[slug] = urls

with open(r"d:\projekti\ManastiriSrbije\backend\commons_categories_krusevacka.json", "w", encoding="utf-8") as fp:
    json.dump(cat_results, fp, ensure_ascii=False, indent=2)
