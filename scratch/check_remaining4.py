import urllib.request
import urllib.parse
import json
import sys

sys.stdout.reconfigure(encoding='utf-8')
headers = {'User-Agent': 'ManastiriSrbijeBot/1.0'}

pages = [
    ('lozica', 'Црква Светог Архангела Лозица'),
    ('grabovac', 'Манастир Грабовац'),
    ('ribnica', 'Манастир Рибница у Паштрићу'),
    ('jovanja', 'Манастир Јовања')
]

for slug, title in pages:
    params = {
        'action': 'query',
        'titles': title,
        'prop': 'images',
        'format': 'json'
    }
    url = f"https://sr.wikipedia.org/w/api.php?{urllib.parse.urlencode(params)}"
    req = urllib.request.Request(url, headers=headers)
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            for pid, pdata in data.get('query', {}).get('pages', {}).items():
                print(f"\n=== {title} ({slug}) ===")
                for im in pdata.get('images', []):
                    im_title = im.get('title', '')
                    if not any(x in im_title.lower() for x in ['icon', 'stub', 'flag', 'portal', 'geograph', 'map']):
                        print("  -", im_title)
    except Exception as e:
        print(f"Error {title}: {e}")
