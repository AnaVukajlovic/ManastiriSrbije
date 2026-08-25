import urllib.request
import urllib.parse
import json
import ssl
import sys

sys.stdout.reconfigure(encoding='utf-8')

USER_AGENT = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36'
ctx = ssl.create_default_context()
ctx.check_hostname = False
ctx.verify_mode = ssl.CERT_NONE

monasteries = [
    {"id": 167, "slug": "bresnica", "name": "Manastir Bresnica", "titles": ["Манастир Бресница", "Бресница (Босилеград)"]},
    {"id": 168, "slug": "kacapun", "name": "Manastir Kacapun", "titles": ["Манастир Кацапун", "Кацапун"]},
    {"id": 169, "slug": "lopardince", "name": "Manastir Lopardince", "titles": ["Манастир Лопардинце", "Лопардинце"]},
    {"id": 170, "slug": "prohor-pcinjski", "name": "Manastir Prohor Pčinjski", "titles": ["Манастир Прохор Пчињски", "Прохор Пчињски"]},
    {"id": 171, "slug": "zapsko", "name": "Manastir Žapsko", "titles": ["Манастир Жапско", "Жапско"]},
    {"id": 240, "slug": "dubnica", "name": "Manastir Dubnica", "titles": ["Манастир Дубница (Врање)", "Дубница (Врање)"]},
    {"id": 246, "slug": "kozji-dol", "name": "Manastir Kozji Dol", "titles": ["Манастир Козји Дол", "Козји Дол (Трговиште)"]},
    {"id": 247, "slug": "lepcince", "name": "Manastir Lepčince", "titles": ["Манастир Лепчинце", "Лепчинце"]},
    {"id": 249, "slug": "simeon-stolpnik", "name": "Manastir Simeon Stolpnik", "titles": ["Манастир Светог Симеона Столпника у Соbinu", "Собина (Врање)", "Манастир Свети Симеон Столпник"]},
    {"id": 251, "slug": "mrtvica", "name": "Manastir Mrtvica", "titles": ["Манастир Мртвица", "Мртвица (Владичин Хан)"]},
    {"id": 252, "slug": "palja", "name": "Manastir Palja", "titles": ["Манастир Паља", "Паља"]},
    {"id": 253, "slug": "sveti-nikola-vranje", "name": "Manastir Sveti Nikola", "titles": ["Манастир Светог Николе у Врању", "Црква Светог Николе у Врању"]},
]

def fetch_json(url):
    req = urllib.request.Request(url, headers={'User-Agent': USER_AGENT})
    try:
        with urllib.request.urlopen(req, context=ctx, timeout=8) as r:
            return json.loads(r.read().decode('utf-8'))
    except Exception as e:
        return None

results = {}

for m in monasteries:
    slug = m['slug']
    print(f"\nSearching for {m['name']} ({slug})...")
    found = []
    
    # 1. Search Wikipedia page images
    for t in m['titles']:
        url = f"https://sr.wikipedia.org/w/api.php?action=query&titles={urllib.parse.quote(t)}&prop=pageimages|images&piprop=original|thumbnail&pithumbsize=1000&format=json"
        data = fetch_json(url)
        if not data:
            continue
        pages = data.get('query', {}).get('pages', {})
        for pid, pdata in pages.items():
            if 'original' in pdata:
                found.append({'source': 'page_original', 'url': pdata['original']['source'], 'title': t})
            if 'thumbnail' in pdata:
                found.append({'source': 'page_thumb', 'url': pdata['thumbnail']['source'], 'title': t})
            if 'images' in pdata:
                for img in pdata['images']:
                    ititle = img['title']
                    if not any(ign in ititle.lower() for ign in ['icon', 'logo', 'flag', 'map', 'stub', 'location', 'portal', 'question']):
                        # get file url from commons
                        furl = f"https://commons.wikimedia.org/w/api.php?action=query&titles={urllib.parse.quote(ititle)}&prop=imageinfo&iiprop=url|size|extmetadata&format=json"
                        fdata = fetch_json(furl)
                        if fdata:
                            fpages = fdata.get('query', {}).get('pages', {})
                            for fpid, fpdata in fpages.items():
                                if 'imageinfo' in fpdata:
                                    info = fpdata['imageinfo'][0]
                                    found.append({
                                        'source': 'file',
                                        'file_title': ititle,
                                        'url': info['url'],
                                        'width': info.get('width', 0),
                                        'height': info.get('height', 0)
                                    })
    # Also search Commons generator search for the monastery name
    cgen_url = f"https://commons.wikimedia.org/w/api.php?action=query&generator=search&gsrsearch={urllib.parse.quote(m['name'])}&gsrlimit=8&prop=imageinfo&iiprop=url|size&format=json"
    cdata = fetch_json(cgen_url)
    if cdata:
        cpages = cdata.get('query', {}).get('pages', {})
        for cpid, cpdata in cpages.items():
            if 'imageinfo' in cpdata:
                cinfo = cpdata['imageinfo'][0]
                found.append({
                    'source': 'commons_search',
                    'file_title': cpdata.get('title', ''),
                    'url': cinfo['url'],
                    'width': cinfo.get('width', 0),
                    'height': cinfo.get('height', 0)
                })

    # Filter unique URLs
    seen_urls = set()
    uniq = []
    for f in found:
        u = f.get('url', '')
        if u and u not in seen_urls and any(u.lower().endswith(ext) for ext in ['.jpg', '.jpeg', '.png']):
            seen_urls.add(u)
            uniq.append(f)
            print(f"  + Found: {u}")
            
    results[slug] = uniq

with open(r'd:\projekti\ManastiriSrbije\backend\fast_vranjska_images.json', 'w', encoding='utf-8') as f:
    json.dump(results, f, ensure_ascii=False, indent=2)

print("\nAll done! Saved to fast_vranjska_images.json")
