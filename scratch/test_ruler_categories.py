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
                })
            return results
    except Exception as e:
        return []

categories = [
    "Stefan Nemanja",
    "Stefan the First-Crowned",
    "Saint Sava",
    "Stefan Radoslav",
    "Stefan Vladislav",
    "Stefan Uroš I",
    "Stefan Dragutin",
    "Stefan Milutin",
    "Stefan Dečanski",
    "Stefan Dušan",
    "Stefan Uroš V",
    "Simonida",
    "Helen of Anjou",
    "Lazar of Serbia",
    "Princess Milica of Serbia",
    "Stefan Lazarević",
    "Vukan Nemanjić"
]

all_cat_files = {}
for cat in categories:
    files = get_category_files(cat)
    all_cat_files[cat] = files
    print(f"\nCategory '{cat}': {len(files)} files found")
    for f in files:
        print(f"   - {f['title']} ({f['width']}x{f['height']}) -> {f['url']}")

with open('scratch/wikimedia_rulers_category_files.json', 'w', encoding='utf-8') as f:
    json.dump(all_cat_files, f, ensure_ascii=False, indent=2)
