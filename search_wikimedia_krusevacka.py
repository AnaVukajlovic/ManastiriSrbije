import urllib.request
import urllib.parse
import json
import sys

sys.stdout.reconfigure(encoding='utf-8')

monasteries = [
    ("braljina", "Manastir Braljina"),
    ("drenca", "Manastir Drenča"),
    ("drenova", "Manastir Drenova"),
    ("grabovo", "Manastir Grabovo"),
    ("komorane", "Manastir Komorane"),
    ("lepenac", "Manastir Lepenac"),
    ("makresane", "Manastir Makrešane"),
    ("manastirak", "Manastir Manastirak"),
    ("mrzenica", "Manastir Mrzenica"),
    ("petina", "Manastir Petina"),
    ("ples", "Manastir Pleš"),
    ("stevanac", "Manastir Stevanac"),
    ("strmac", "Manastir Strmac"),
    ("zilinci", "Manastir Žilinci"),
    ("ljubostinja", "Manastir Ljubostinja"),
    ("veluce", "Manastir Veluće"),
    ("naupare", "Manastir Naupare"),
    ("bosnjane", "Manastir Bošnjane"),
    ("lesje", "Manastir Lešje"),
]

def search_commons(query):
    url = f"https://commons.wikimedia.org/w/api.php?action=query&generator=search&gsrsearch={urllib.parse.quote(query)}&gsrlimit=10&prop=imageinfo&iiprop=url|size|extmetadata&format=json"
    req = urllib.request.Request(url, headers={'User-Agent': 'ManastiriSrbije/1.0 (academic research)'})
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            pages = data.get('query', {}).get('pages', {})
            results = []
            for pid, page in pages.items():
                title = page.get('title', '')
                imageinfo = page.get('imageinfo', [{}])[0]
                img_url = imageinfo.get('url', '')
                if img_url and any(img_url.lower().endswith(ext) for ext in ['.jpg', '.jpeg', '.png']):
                    results.append({'title': title, 'url': img_url})
            return results
    except Exception as e:
        print(f"Error searching for {query}: {e}")
        return []

print("=== SEARCHING WIKIMEDIA COMMONS FOR KRUSEVACKA MONASTERIES ===")
found_data = {}
for slug, name in monasteries:
    q = name
    res = search_commons(q)
    if not res:
        # try without "Manastir"
        q2 = name.replace("Manastir ", "") + " manastir"
        res = search_commons(q2)
    print(f"[{slug}] {name} -> {len(res)} results")
    for r in res[:5]:
        print(f"   {r['title']} -> {r['url']}")
    found_data[slug] = res

with open(r"d:\projekti\ManastiriSrbije\backend\wikimedia_krusevacka.json", "w", encoding="utf-8") as f:
    json.dump(found_data, f, ensure_ascii=False, indent=2)
