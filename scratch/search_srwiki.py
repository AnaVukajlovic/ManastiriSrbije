import urllib.request
import urllib.parse
import json
import sys

sys.stdout.reconfigure(encoding='utf-8')
headers = {'User-Agent': 'ManastiriSrbijeBot/1.0'}

queries = ['Лозица', 'Грабовац', 'Рибница', 'Јовања']

for q in queries:
    params = {
        'action': 'query',
        'list': 'search',
        'srsearch': f"Манастир {q}",
        'format': 'json'
    }
    url = f"https://sr.wikipedia.org/w/api.php?{urllib.parse.urlencode(params)}"
    req = urllib.request.Request(url, headers=headers)
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            results = data.get('query', {}).get('search', [])
            print(f"\nQuery 'Манастир {q}': {len(results)} results")
            for r in results[:5]:
                print(f"  - {r['title']}")
    except Exception as e:
        print(f"Error {q}: {e}")
