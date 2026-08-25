import urllib.request, urllib.parse, json, sys, os

sys.stdout.reconfigure(encoding='utf-8')
USER_AGENT = 'ManastiriSrbijeResearch/1.0 (contact: ana.vukajlovic@gmail.com)'

def search_commons(query, limit=12):
    url = f'https://commons.wikimedia.org/w/api.php?action=query&list=search&srsearch={urllib.parse.quote(query)}&srnamespace=6&srlimit={limit}&format=json'
    req = urllib.request.Request(url, headers={'User-Agent': USER_AGENT})
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            return [x['title'] for x in data.get('query', {}).get('search', []) if not x['title'].endswith('.pdf') and not x['title'].endswith('.webm')]
    except Exception as e:
        print(f"Search error for {query}: {e}", flush=True)
        return []

def get_category_files(cat, limit=20):
    url = f'https://commons.wikimedia.org/w/api.php?action=query&list=categorymembers&cmtitle={urllib.parse.quote(cat)}&cmtype=file&cmlimit={limit}&format=json'
    req = urllib.request.Request(url, headers={'User-Agent': USER_AGENT})
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            return [x['title'] for x in data.get('query', {}).get('categorymembers', []) if not x['title'].endswith('.pdf') and not x['title'].endswith('.webm')]
    except Exception as e:
        return []

branicevska_queries = {
    'manasija': ['Category:Manasija monastery', 'Manastir Manasija', 'Manasija Resava'],
    'ravanica': ['Category:Ravanica monastery', 'Manastir Ravanica'],
    'tumane': ['Category:Tumane Monastery', 'Manastir Tumane', 'Manastir Tuman'],
    'gornjak': ['Category:Gornjak Monastery', 'Manastir Gornjak'],
    'koporin': ['Category:Koporin Monastery', 'Manastir Koporin'],
    'pokajnica': ['Category:Pokajnica Monastery', 'Manastir Pokajnica'],
    'nimnik': ['Category:Nimnik Monastery', 'Manastir Nimnik'],
    'rukumija': ['Category:Rukumija Monastery', 'Manastir Rukumija'],
    'zaova': ['Category:Zaova Monastery', 'Manastir Zaova'],
    'sisojevac': ['Category:Sisojevac Monastery', 'Manastir Sisojevac'],
    'trska-crkva': ['Category:Trška Church', 'Trška crkva', 'Manastir Trška crkva'],
    'miljkovo': ['Category:Miljkovo Monastery', 'Manastir Miljkovo'],
    'izvor': ['Category:Izvor Monastery', 'Manastir Izvor Paraćin'],
    'sestroljin': ['Manastir Sestroljin'],
    'bradaca': ['Manastir Bradača'],
    'dobres': ['Manastir Dobreš'],
    'zlatenac': ['Manastir Zlatenac'],
    'tomic': ['Manastir Tomić'],
    'radosin': ['Manastir Radošin'],
    'reskovica': ['Manastir Reškovica'],
    'namasija': ['Manastir Namasija'],
    'djerinac': ['Manastir Đerinac'],
    'zdrelo': ['Manastir Ždrelo']
}

all_found = {}
for slug, queries in branicevska_queries.items():
    found = []
    for q in queries:
        if q.startswith('Category:'):
            found.extend(get_category_files(q))
        else:
            found.extend(search_commons(q))
    
    # deduplicate preserving order
    dedup = []
    for t in found:
        if t not in dedup:
            dedup.append(t)
    all_found[slug] = dedup
    print(f'{slug}: {len(dedup)} candidates -> {dedup[:3]}', flush=True)

with open('scratch/branicevska_candidates.json', 'w', encoding='utf-8') as f:
    json.dump(all_found, f, ensure_ascii=False, indent=2)

print("\nSaved candidates to scratch/branicevska_candidates.json", flush=True)
