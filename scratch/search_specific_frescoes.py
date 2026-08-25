import urllib.request
import json
import urllib.parse

def search_files(query, limit=10):
    url = "https://commons.wikimedia.org/w/api.php?" + urllib.parse.urlencode({
        "action": "query",
        "format": "json",
        "generator": "search",
        "gsrsearch": f"{query} filetype:bitmap",
        "gsrlimit": limit,
        "gsrnamespace": "6",
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
                if iinfo.get("url"):
                    results.append({
                        "title": title,
                        "url": iinfo.get("url"),
                        "thumburl": iinfo.get("thumburl"),
                        "width": iinfo.get("width"),
                        "height": iinfo.get("height"),
                    })
            return results
    except Exception as e:
        print(f"Error {query}: {e}")
        return []

specific_searches = {
    "stefan-nemanja": [
        "Stefan Nemanja fresco Studenica",
        "Sveti Simeon Mirotocivi fresco",
        "Fresco of Stefan Nemanja"
    ],
    "stefan-prvovencani": [
        "Stefan Prvovencani fresco",
        "Stefan the First-Crowned fresco Mileseva",
        "Stefan Prvovencani Studenica"
    ],
    "sveti-sava": [
        "Saint Sava fresco Mileseva",
        "Sveti Sava Studenica fresco",
        "Saint Sava fresco Pec"
    ],
    "stefan-radoslav": [
        "Stefan Radoslav fresco",
        "King Radoslav Studenica fresco",
        "Stefan Radoslav"
    ],
    "stefan-vladislav": [
        "Stefan Vladislav fresco",
        "King Vladislav Mileseva fresco"
    ],
    "stefan-uros-i": [
        "Stefan Uros I fresco Sopocani",
        "Stefan Uros I Arilje"
    ],
    "kralj-dragutin": [
        "Stefan Dragutin fresco Arilje",
        "Stefan Dragutin Djurdjevi Stupovi"
    ],
    "kralj-milutin": [
        "King Milutin fresco Gracanica",
        "King Milutin fresco Studenica",
        "Milutin Gracanica"
    ],
    "stefan-decanski": [
        "Stefan Decanski fresco",
        "Stefan Decanski Visoki Decani"
    ],
    "car-dusan": [
        "Stefan Dusan fresco",
        "Car Dusan fresco Lesnovo",
        "Stefan Dusan Decani"
    ],
    "uros-nejaki": [
        "Stefan Uros V Psaca",
        "Stefan Uros V fresco"
    ],
    "simonida": [
        "Simonida fresco Gracanica",
        "Simonida Studenica"
    ],
    "carica-jelena": [
        "Empress Jelena fresco Lesnovo",
        "Carica Jelena fresco"
    ],
    "ana-dandolo": [
        "Death of Queen Anna Dandolo Sopocani",
        "Ana Dandolo Sopocani"
    ],
    "ana-zena-stefana-nemanje": [
        "Sveta Anastasija Studenica",
        "Saint Anastasia Nemanjic"
    ],
    "vukan-nemanjic": [
        "Vukan Nemanjic",
        "Vukan Gospel"
    ],
    "jelena-anzujska": [
        "Helen of Anjou Sopocani",
        "Jelena Anzujska fresco"
    ],
    "kneginja-milica": [
        "Kneginja Milica fresco Ljubostinja",
        "Princess Milica Ljubostinja"
    ],
    "knez-lazar": [
        "Knez Lazar fresco Ravanica",
        "Prince Lazar Ravanica"
    ],
    "stefan-lazarevic": [
        "Stefan Lazarevic Manasija fresco",
        "Despot Stefan Lazarevic Kalenic"
    ]
}

findings = {}
for slug, q_list in specific_searches.items():
    print(f"\n==================== {slug} ====================")
    findings[slug] = []
    seen_titles = set()
    for q in q_list:
        res = search_files(q, limit=5)
        for r in res:
            if r['title'] not in seen_titles:
                seen_titles.add(r['title'])
                findings[slug].append(r)
                print(f"  [{slug}] {r['title']} ({r['width']}x{r['height']}) -> {r['url']}")

with open('scratch/specific_frescoes_found.json', 'w', encoding='utf-8') as f:
    json.dump(findings, f, ensure_ascii=False, indent=2)

print("\nSaved to scratch/specific_frescoes_found.json")
