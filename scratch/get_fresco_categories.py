import urllib.request
import json
import urllib.parse

def get_category_files(cat_name):
    url = "https://commons.wikimedia.org/w/api.php?" + urllib.parse.urlencode({
        "action": "query",
        "format": "json",
        "generator": "categorymembers",
        "gcmtitle": f"Category:{cat_name}",
        "gcmtype": "file",
        "gcmlimit": "50",
        "prop": "imageinfo",
        "iiprop": "url|size|extmetadata",
        "iiurlwidth": "1200"
    })
    headers = {"User-Agent": "ManastiriSrbijeBot/1.0 (academic research; contact@manastirisrbije.rs)"}
    req = urllib.request.Request(url, headers=headers)
    try:
        with urllib.request.urlopen(req) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            pages = data.get("query", {}).get("pages", {})
            results = []
            for pid, pdata in pages.items():
                title = pdata.get("title", "")
                iinfo = pdata.get("imageinfo", [{}])[0]
                results.append({
                    "title": title,
                    "url": iinfo.get("url"),
                    "thumburl": iinfo.get("thumburl"),
                    "width": iinfo.get("width"),
                    "height": iinfo.get("height"),
                    "description": iinfo.get("extmetadata", {}).get("ImageDescription", {}).get("value", "")
                })
            return results
    except Exception as e:
        print(f"Error for category '{cat_name}': {e}")
        return []

categories = [
    "Frescos of Stefan Nemanja",
    "Frescos of Saint Sava",
    "Frescos of Stefan the First-Crowned",
    "Frescos of Stefan Vladislav",
    "Frescos of Stefan Radoslav",
    "Frescos of Stefan Uroš I",
    "Frescos of Stefan Dragutin",
    "Frescos of Stefan Milutin",
    "Frescos of Stefan Dečanski",
    "Frescos of Stefan Dušan",
    "Frescos of Stefan Uroš V",
    "Frescos of Simonida",
    "Frescos of Helen of Anjou",
    "Frescos of Lazar of Serbia",
    "Frescos of Princess Milica",
    "Frescos of Stefan Lazarević",
    "Nemanjić family tree frescos",
    "Frescos in Visoki Dečani",
    "Frescos in King's Church (Studenica)",
    "Frescos in Mileševa monastery",
    "Frescos in Sopoćani monastery",
    "Frescos in Gračanica monastery",
    "Frescos in Patriarchate of Peć"
]

cat_results = {}
for cat in categories:
    files = get_category_files(cat)
    cat_results[cat] = files
    print(f"Category '{cat}': {len(files)} files found")
    for f in files[:8]:
        print(f"   - {f['title']} ({f['width']}x{f['height']})")

with open('scratch/wikimedia_categories_files.json', 'w', encoding='utf-8') as f:
    json.dump(cat_results, f, ensure_ascii=False, indent=2)

print("\nSaved category files to scratch/wikimedia_categories_files.json")
