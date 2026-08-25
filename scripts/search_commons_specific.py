import urllib.request
import urllib.parse
import json
import sys
import io
import time

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

HEADERS = {
    'User-Agent': 'ManastiriSrbijeResearchBot/1.0 (https://manastirisrbije.rs; contact@manastirisrbije.rs) python-requests'
}

queries = [
    ('senjak', 'Vavedenje Belgrade'),
    ('senjak', 'Vavedenje Senjak'),
    ('senjak', 'Vavedenje monastery'),
    ('slanci', 'Slanci'),
    ('slanci', 'Manastir Slanci'),
    ('sombor', 'Sombor monastery'),
    ('sombor', 'Manastir Sombor'),
    ('sombor', 'Stefan Sombor'),
    ('trojerucica', 'Trojerucica'),
    ('trojerucica', 'Ripanj'),
    ('kac', 'Manastir Kac'),
    ('kac', 'Vaskrsenja Hristova Kac'),
    ('gaj', 'Gaj Kovin'),
    ('gaj', 'Manastir Gaj')
]

for slug, q in queries:
    time.sleep(1.0)
    api = f"https://commons.wikimedia.org/w/api.php?action=query&list=search&srsearch={urllib.parse.quote(q)}&srnamespace=6&srlimit=10&format=json"
    req = urllib.request.Request(api, headers=HEADERS)
    try:
        with urllib.request.urlopen(req, timeout=10) as r:
            data = json.loads(r.read().decode('utf-8'))
            hits = data.get('query', {}).get('search', [])
            print(f"[{slug}] Query '{q}' -> {len(hits)} hits:")
            for h in hits[:4]:
                print(f"   • {h.get('title')}")
    except Exception as e:
        print(f"Error {q}: {e}")
