import urllib.request
import urllib.parse
import json
import sys

sys.stdout.reconfigure(encoding='utf-8')

headers = {'User-Agent': 'ManastiriSrbijeBot/1.0 (contact@manastirisrbije.rs)'}

def search_wikimedia(query):
    params = {
        'action': 'query',
        'generator': 'search',
        'gsrsearch': f"{query} filetype:bitmap",
        'gsrlimit': 10,
        'prop': 'imageinfo',
        'iiprop': 'url|size|mime|extmetadata',
        'iiurlwidth': 1200,
        'format': 'json'
    }
    url = f"https://commons.wikimedia.org/w/api.php?{urllib.parse.urlencode(params)}"
    req = urllib.request.Request(url, headers=headers)
    try:
        with urllib.request.urlopen(req, timeout=15) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            pages = data.get('query', {}).get('pages', {})
            results = []
            for pid, pdata in pages.items():
                title = pdata.get('title', '')
                imageinfo = pdata.get('imageinfo', [{}])[0]
                img_url = imageinfo.get('url', '')
                thumb_url = imageinfo.get('thumburl', img_url)
                width = imageinfo.get('width', 0)
                height = imageinfo.get('height', 0)
                results.append({
                    'title': title,
                    'url': img_url,
                    'thumb_url': thumb_url,
                    'width': width,
                    'height': height
                })
            return results
    except Exception as e:
        print(f"Error searching for '{query}': {e}")
        return []

monasteries = [
    ('grliste', 'Manastir Grlište'),
    ('krepicevac', 'Manastir Krepičevac'),
    ('lapusnja', 'Manastir Lapušnja'),
    ('lozica', 'Manastir Lozica'),
    ('vratna', 'Manastir Vratna'),
    ('suvodol', 'Manastir Suvodol'),
    ('lelic', 'Manastir Lelić'),
    ('bogovadja', 'Manastir Bogovađa'),
    ('dokmir', 'Manastir Dokmir'),
    ('grabovac', 'Manastir Grabovac'),
    ('ribnica', 'Manastir Ribnica'),
    ('pluzac', 'Manastir Plužac'),
    ('jovanja', 'Manastir Jovanja')
]

for slug, name in monasteries:
    res = search_wikimedia(name)
    print(f"\n=== {name} ({slug}) - {len(res)} results ===")
    for r in res[:5]:
        print(f"  - {r['title']} ({r['width']}x{r['height']})")
        print(f"    URL: {r['url']}")
