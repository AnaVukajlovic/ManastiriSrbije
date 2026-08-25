import urllib.request
import urllib.parse
import json
import ssl

ctx = ssl.create_default_context()
ctx.check_hostname = False
ctx.verify_mode = ssl.CERT_NONE

headers = {'User-Agent': 'MonasteriesExplorer/1.0 (serbian.monasteries.master@example.com)'}

def search_commons(query):
    url = f"https://commons.wikimedia.org/w/api.php?action=query&generator=search&gsrsearch={urllib.parse.quote(query)}&gsrlimit=10&prop=imageinfo&iiprop=url|size|extmetadata&format=json"
    req = urllib.request.Request(url, headers=headers)
    try:
        with urllib.request.urlopen(req, context=ctx, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            pages = data.get('query', {}).get('pages', {})
            results = []
            for pid, pdata in pages.items():
                title = pdata.get('title', '')
                imginfo = pdata.get('imageinfo', [{}])[0]
                imgurl = imginfo.get('url', '')
                extmeta = imginfo.get('extmetadata', {})
                desc = extmeta.get('ObjectName', {}).get('value', '') or extmeta.get('ImageDescription', {}).get('value', '')
                if imgurl and any(imgurl.lower().endswith(ext) for ext in ['.jpg', '.jpeg', '.png']):
                    results.append({
                        'title': title,
                        'url': imgurl,
                        'desc': desc
                    })
            return results
    except Exception as e:
        print(f"Error searching '{query}': {e}")
        return []

queries = {
    'prohor-pcinjski': ['Prohor Pcinjski', 'Manastir Prohor Pčinjski', 'Prohor Pčinjski monastery'],
    'kacapun': ['Manastir Kacapun', 'Kacapun monastery', 'Kacapun crkva'],
    'mrtvica': ['Manastir Mrtvica', 'Mrtvica manastir', 'Mrtvica monastery Vladicin Han'],
    'palja': ['Manastir Palja', 'Palja monastery Surdulica'],
    'lepcince': ['Manastir Lepcince', 'Lepčince manastir', 'Lepcince monastery'],
    'zapsko': ['Manastir Zapsko', 'Žapsko manastir', 'Zapsko monastery'],
    'sveti-nikola-vranje': ['Manastir Svetog Nikole Vranje', 'Crkva Svetog Nikole Vranje', 'Sveti Nikola Vranje manastir'],
    'kozji-dol': ['Manastir Kozji Dol', 'Kozji Dol Trgoviste'],
    'lopardince': ['Manastir Lopardince', 'Lopardince Bujanovac'],
    'bresnica': ['Manastir Bresnica Bosilegrad', 'Crkva Svete Petke Bresnica Bosilegrad'],
    'simeon-stolpnik': ['Manastir Sveti Simeon Stolpnik', 'Simeon Stolpnik Vranje', 'Crkva Svetog Simeona Stolpnika'],
    'dubnica-milesevska': ['Manastir Dubnica Vranje', 'Manastir Dubnica Knez Lazar Vranje', 'Dubnica crkva Vranje']
}

print("=== SEARCHING COMMONS FOR VRANJSKA EPARCHY ===")
found_all = {}
for slug, qlist in queries.items():
    print(f"\n--- Searching for {slug} ---")
    found_all[slug] = []
    seen_urls = set()
    for q in qlist:
        res = search_commons(q)
        for r in res:
            if r['url'] not in seen_urls:
                seen_urls.add(r['url'])
                found_all[slug].append(r)
                print(f"  [{r['title']}] -> {r['url']}")

with open(r'd:\projekti\ManastiriSrbije\backend\wikimedia_vranjska.json', 'w', encoding='utf-8') as f:
    json.dump(found_all, f, indent=2, ensure_ascii=False)
print("\nSaved results to wikimedia_vranjska.json")
