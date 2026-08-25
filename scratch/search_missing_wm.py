import urllib.request
import urllib.parse
import json
import sys

sys.stdout.reconfigure(encoding='utf-8')
headers = {'User-Agent': 'ManastiriSrbijeBot/1.0'}

queries = [
    ('lozica', 'Lozica Boljevac'),
    ('lozica', 'Manastir Lozica'),
    ('grabovac', 'Manastir Grabovac'),
    ('grabovac', 'Grabovac Obrenovac manastir'),
    ('ribnica', 'Manastir Ribnica'),
    ('ribnica', 'Crkva manastira Ribnica Mionica'),
    ('jovanja', 'Manastir Jovanja Valjevo'),
    ('jovanja', 'Manastir Jovanja')
]

for slug, q in queries:
    params = {
        'action': 'query',
        'list': 'search',
        'srsearch': q,
        'format': 'json'
    }
    url = f"https://commons.wikimedia.org/w/api.php?{urllib.parse.urlencode(params)}"
    req = urllib.request.Request(url, headers=headers)
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            results = data.get('query', {}).get('search', [])
            print(f"\nQuery '{q}' ({slug}): {len(results)} results")
            for r in results[:5]:
                print(f"  - {r['title']}")
    except Exception as e:
        print(f"Error {q}: {e}")
