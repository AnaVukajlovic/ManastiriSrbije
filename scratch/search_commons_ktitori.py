import urllib.request
import json
import urllib.parse
import os

def search_wikimedia(query, limit=10):
    url = "https://commons.wikimedia.org/w/api.php?" + urllib.parse.urlencode({
        "action": "query",
        "format": "json",
        "generator": "search",
        "gsrsearch": query,
        "gsrlimit": limit,
        "gsrnamespace": "6", # File
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
        print(f"Error searching for '{query}': {e}")
        return []

queries = [
    ("stefan-nemanja", "Stefan Nemanja fresco Studenica OR Hilandar OR Sopocani"),
    ("stefan-prvovencani", "Stefan Prvovencani fresco Mileseva OR Studenica OR Zica"),
    ("sveti-sava", "Saint Sava fresco Mileseva OR Studenica OR Sopocani OR Pec"),
    ("stefan-radoslav", "Stefan Radoslav fresco Studenica OR Zica"),
    ("stefan-vladislav", "Stefan Vladislav fresco Mileseva"),
    ("stefan-uros-i", "Stefan Uros I fresco Sopocani OR Arilje OR Gradac"),
    ("kralj-dragutin", "Stefan Dragutin fresco Arilje OR Djurdjevi Stupovi OR Sopocani"),
    ("kralj-milutin", "King Milutin fresco Gracanica OR Studenica OR Nagoricane OR Banjska"),
    ("stefan-decanski", "Stefan Decanski fresco Visoki Decani"),
    ("car-dusan", "Emperor Dusan fresco Decani OR Lesnovo OR Matejic"),
    ("uros-nejaki", "Stefan Uros V fresco Psaca OR Decani"),
    ("simonida", "Queen Simonida fresco Gracanica OR Studenica"),
    ("carica-jelena", "Empress Jelena fresco Lesnovo OR Decani"),
    ("ana-dandolo", "Ana Dandolo fresco Sopocani"),
    ("ana-zena-stefana-nemanje", "Saint Anastasia fresco Studenica"),
    ("vukan-nemanjic", "Vukan Nemanjic fresco Moraca OR Vukan Gospel"),
    ("knez-lazar", "Prince Lazar fresco Ravanica OR Ljubostinja"),
    ("kneginja-milica", "Princess Milica fresco Ljubostinja OR Ravanica"),
    ("stefan-lazarevic", "Stefan Lazarevic fresco Manasija OR Kalenic OR Rudenica"),
    ("jelena-anzujska", "Helen of Anjou fresco Sopocani OR Gradac")
]

all_found = {}
for slug, q in queries:
    print(f"\n==================== Search: {slug} ('{q}') ====================")
    res = search_wikimedia(q, limit=6)
    all_found[slug] = res
    for r in res:
        print(f" - {r['title']} ({r['width']}x{r['height']}) -> {r['url']}")

with open('scratch/wikimedia_ktitori_candidates.json', 'w', encoding='utf-8') as f:
    json.dump(all_found, f, ensure_ascii=False, indent=2)

print("\nFinished writing scratch/wikimedia_ktitori_candidates.json")
