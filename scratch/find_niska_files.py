import urllib.request, urllib.parse, json, sys, os, time

sys.stdout.reconfigure(encoding='utf-8')
USER_AGENT = 'ManastiriSrbijeResearch/1.0 (contact: ana.vukajlovic@gmail.com)'

def search_commons(query, limit=10):
    url = f'https://commons.wikimedia.org/w/api.php?action=query&list=search&srsearch={urllib.parse.quote(query)}&srnamespace=6&srlimit={limit}&format=json'
    req = urllib.request.Request(url, headers={'User-Agent': USER_AGENT})
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            return [x['title'] for x in data.get('query', {}).get('search', []) if not x['title'].endswith('.pdf')]
    except Exception as e:
        print(f"Search error for {query}: {e}", flush=True)
        return []

specific_searches = {
    'poganovo': ['Category:Poganovo Monastery', 'Manastir Poganovo'],
    'sukovo': ['Category:Sukovo Monastery', 'Manastir Sukovo'],
    'sveti-roman': ['Category:Sveti Roman Monastery', 'Manastir Sveti Roman'],
    'djunis': ['Manastir Đunis', 'Category:Djunis Monastery'],
    'temska': ['Category:Temska Monastery', 'Manastir Temska'],
    'lipovac': ['Category:Lipovac Monastery', 'Manastir Lipovac'],
    'kursumlija': ['Category:Monastery of Saint Nicholas, Kuršumlija', 'Manastir Svetog Nikole Kuršumlija'],
    'rsovci': ['Pećinska crkva Rsovci', 'Rsovci crkva', 'Rsovci Isus'],
    'sicevo': ['Category:Sićevo Monastery', 'Manastir Sićevo'],
    'iverica': ['Category:Iverica Monastery', 'Manastir Iverica'],
    'gabrovac': ['Manastir Gabrovac', 'Crkva Svete Trojice Gabrovac'],
    'divljane': ['Category:Divljane Monastery', 'Manastir Divljane'],
    'gornji-matejevac': ['Latinska crkva Gornji Matejevac', 'Category:Latin Church in Gornji Matejevac'],
    'sveti-jovan': ['Манастир Св. Јована Крститеља код Горњег Матејевца', 'Manastir Svetog Jovana Matejevac'],
    'ajdanovac': ['Category:Ajdanovac Monastery', 'Manastir Ajdanovac'],
    'janjusa': ['Manastir Jašunja', 'Jašunjski manastiri'],
    'rudare': ['Category:Rudare Monastery', 'Manastir Rudare'],
    'smilovci': ['Category:Smilovci Monastery', 'Manastir Smilovci'],
    'cukljenik': ['Manastir Čukljenik', 'Manastir Cukljenik'],
    'gorcince': ['Manastir Gorčince'],
    'sinjacki': ['Manastir Sinjac', 'Manastir Sinjački'],
    'veta': ['Manastir Veta'],
    'plocnik': ['Manastir Pločnik']
}

all_found = {}
for slug, queries in specific_searches.items():
    found_titles = []
    for q in queries:
        if q.startswith('Category:'):
            cat_url = f'https://commons.wikimedia.org/w/api.php?action=query&list=categorymembers&cmtitle={urllib.parse.quote(q)}&cmtype=file&cmlimit=15&format=json'
            req = urllib.request.Request(cat_url, headers={'User-Agent': USER_AGENT})
            try:
                with urllib.request.urlopen(req, timeout=10) as resp:
                    cdata = json.loads(resp.read().decode('utf-8'))
                    found_titles.extend([x['title'] for x in cdata.get('query', {}).get('categorymembers', []) if not x['title'].endswith('.pdf') and not x['title'].endswith('.webm')])
            except Exception as e:
                print(f"Cat error {q}: {e}", flush=True)
        else:
            found_titles.extend(search_commons(q, limit=8))
    
    # deduplicate preserving order
    dedup = []
    for t in found_titles:
        if t not in dedup:
            dedup.append(t)
    all_found[slug] = dedup
    print(f'{slug}: {len(dedup)} candidates -> {dedup[:4]}', flush=True)

with open('scratch/niska_candidates.json', 'w', encoding='utf-8') as f:
    json.dump(all_found, f, ensure_ascii=False, indent=2)

print("\nSaved candidates to scratch/niska_candidates.json", flush=True)
