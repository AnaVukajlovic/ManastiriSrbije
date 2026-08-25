import urllib.request, urllib.parse, json, time, sys, io
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')
H = {'User-Agent': 'ManastiriSrbijeResearchBot/1.0 python'}

queries = [
    ('gornjak', 'Gornjak monastery'),
    ('koporin', 'Koporin monastery'),
    ('manasija', 'Manasija'),
    ('ravanica', 'Ravanica monastery'),
    ('tumane', 'Tumane monastery'),
    ('sisojevac', 'Sisojevac'),
    ('pokajnica', 'Pokajnica'),
    ('ljubostinja', 'Ljubostinja'),
    ('veluce', 'Veluce'),
    ('drenca', 'Drenca monastery'),
    ('naupare', 'Naupare'),
    ('mileseva', 'Mileseva monastery'),
    ('davidovica', 'Davidovica monastery'),
    ('janja', 'Janja monastery'),
    ('kumanica', 'Kumanica monastery'),
    ('bistrica', 'Bistrica monastery'),
    ('bradaca', 'Bradaca'),
    ('nimnik', 'Nimnik'),
    ('rukumija', 'Rukumija'),
    ('zdrelo', 'Zdrelo monastery'),
    ('tumane2', 'Tumane Serbia'),
    ('ravanica2', 'Ravanica Serbia'),
    ('gornjak2', 'Gornjak Serbia'),
]

for slug, q in queries:
    time.sleep(1.0)
    api = f'https://commons.wikimedia.org/w/api.php?action=query&list=search&srsearch={urllib.parse.quote(q)}&srnamespace=6&srlimit=6&format=json'
    req = urllib.request.Request(api, headers=H)
    try:
        data = json.loads(urllib.request.urlopen(req, timeout=12).read().decode('utf-8'))
        hits = data.get('query',{}).get('search',[])
        if hits:
            print(f'[{slug}] "{q}":')
            for h in hits[:5]:
                print(f'   {h["title"]}')
    except Exception as e:
        print(f'[{slug}] ERR: {e}')
