import os
import urllib.request
import urllib.parse
import json

USER_AGENT = 'ManastiriSrbijeResearchBot/1.0 (contact: student@pravoslavnisvetionik.rs)'

def search_files(term):
    url = f"https://commons.wikimedia.org/w/api.php?action=query&list=search&srsearch={urllib.parse.quote(term)}&srnamespace=6&srlimit=10&format=json"
    req = urllib.request.Request(url, headers={'User-Agent': USER_AGENT})
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            return [x['title'] for x in data.get('query', {}).get('search', [])]
    except Exception as e:
        return []

terms = ["Manastir Stevanac", "Manastir Braljina", "Manastir Drenova", "Manastir Žilinci", "Manastir Manastirak", "Manastir Makrešane"]
for t in terms:
    f = search_files(t)
    print(f"[{t}] -> {len(f)} files: {f}")
