import urllib.request, urllib.parse, json, sys, os
sys.stdout.reconfigure(encoding='utf-8')

USER_AGENT = 'ManastiriSrbijeResearch/1.0 (contact: ana.vukajlovic@gmail.com)'

def search_commons(query, limit=10):
    url = f'https://commons.wikimedia.org/w/api.php?action=query&list=search&srsearch={urllib.parse.quote(query)}&srnamespace=6&srlimit={limit}&format=json'
    req = urllib.request.Request(url, headers={'User-Agent': USER_AGENT})
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            return [x['title'] for x in data.get('query', {}).get('search', [])]
    except Exception as e:
        return [f'Error: {e}']

def get_category_files(cat, limit=20):
    url = f'https://commons.wikimedia.org/w/api.php?action=query&list=categorymembers&cmtitle={urllib.parse.quote(cat)}&cmtype=file&cmlimit={limit}&format=json'
    req = urllib.request.Request(url, headers={'User-Agent': USER_AGENT})
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            return [x['title'] for x in data.get('query', {}).get('categorymembers', [])]
    except Exception as e:
        return [f'Error: {e}']

categories = {
    'poganovo': 'Category:Poganovo Monastery',
    'sukovo': 'Category:Sukovo Monastery',
    'sveti-roman': 'Category:Sveti Roman Monastery',
    'djunis': 'Category:Djunis Monastery',
    'temska': 'Category:Temska Monastery',
    'lipovac': 'Category:Lipovac Monastery',
    'kursumlija': 'Category:Monastery of Saint Nicholas, Kuršumlija',
    'rsovci': 'Category:Church of St. Peter and Paul in Rsovci',
    'sicevo': 'Category:Sićevo Monastery',
    'iverica': 'Category:Iverica Monastery',
    'gabrovac': 'Category:Gabrovac Monastery',
    'divljane': 'Category:Divljane Monastery',
    'gornji-matejevac': 'Category:Latin Church in Gornji Matejevac',
    'ajdanovac': 'Category:Ajdanovac Monastery',
    'rudare': 'Category:Rudare Monastery',
    'cukljenik': 'Category:Čukljenik Monastery',
    'smilovci': 'Category:Smilovci Monastery',
    'gorcince': 'Category:Gorčince Monastery',
    'janjusa': 'Category:Jašunja Monasteries',
    'plocnik': 'Category:Pločnik'
}

results = {}
for slug, cat in categories.items():
    files = get_category_files(cat)
    if not files or files[0].startswith('Error'):
        # fallback search
        files = search_commons(f'Manastir {slug}')
    results[slug] = files
    print(f'{slug} ({len(files)} files): {files[:3]}', flush=True)

with open('scratch/niska_commons_files.json', 'w', encoding='utf-8') as f:
    json.dump(results, f, ensure_ascii=False, indent=2)

print('\nDone searching! Results saved to scratch/niska_commons_files.json', flush=True)
