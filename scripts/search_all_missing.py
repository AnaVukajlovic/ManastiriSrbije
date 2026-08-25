import urllib.request, urllib.parse, json, time, sys, io
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')
H = {'User-Agent': 'ManastiriSrbijeResearchBot/1.0 python'}

# Search for specific file names from Commons categories/articles
queries = [
    # Banatska
    ('svete-melanije', 'Manastir Svete Melanije Zrenjanin'),
    # Backa
    ('vodica', 'Backo Petrovo Selo monastery Vodica'),
    # Beogradska
    ('slanci', 'Manastir Slanci Beograd'),
    ('trojerucica', 'Manastir Trojerucica Ripanj'),
    # Branicevska missing
    ('dobres', 'Dobreš Monastery Svilajnac'),
    ('izvor', 'Izvor Monastery Paracin Parascheva'),
    ('zdrelo', 'Holy Trinity Monastery Zdrelo'),
    ('miljkovo', 'Miljkovo monastery'),
    ('namasija', 'Namasija monastery'),
    ('radosin', 'Radosin monastery'),
    ('reskovica', 'Reskovica monastery'),
    ('sestroljin', 'Sestroljin monastery'),
    ('tomic', 'Tomic monastery Serbia'),
    ('trska-crkva', 'Trska Crkva monastery'),
    ('zaova', 'Zaova monastery'),
    ('zlatenac', 'Zlatenac monastery'),
    ('djerinac', 'Djerinac monastery'),
    # Krusevacka missing
    ('bosnjane', 'Bosnjane monastery'),
    ('braljina', 'Braljina monastery'),
    ('drenova', 'Drenova monastery Krusevac'),
    ('grabovo', 'Grabovo monastery'),
    ('komorane', 'Komorane monastery'),
    ('lepenac', 'Lepenac monastery'),
    ('lesje', 'Lesje monastery'),
    ('makresane', 'Makresane monastery'),
    ('manastirak', 'Manastirak Sumadija'),
    ('mrzenica', 'Mrzenica monastery'),
    ('naupare', 'Naupare monastery Krusevac'),
    ('petina', 'Petina monastery'),
    ('ples', 'Ples monastery Serbia'),
    ('stevanac', 'Stevanac monastery'),
    ('strmac', 'Strmac monastery'),
    ('svojnovo', 'Svojnovo monastery'),
    ('zilinci', 'Zilinci monastery'),
    # Milesevska missing
    ('bistrica', 'Bistrica monastery Zlatibor'),
    ('mazici', 'Mazici monastery'),
    ('pribojska-banja', 'Pribojska Banja monastery'),
    ('pustinja', 'Pustinja Valjevska monastery'),
    ('seljani', 'Seljani monastery'),
    ('vodena-poljana', 'Vodena Poljana monastery'),
]

results = {}
for slug, q in queries:
    time.sleep(1.5)
    api = f'https://commons.wikimedia.org/w/api.php?action=query&list=search&srsearch={urllib.parse.quote(q)}&srnamespace=6&srlimit=5&format=json'
    req = urllib.request.Request(api, headers=H)
    try:
        data = json.loads(urllib.request.urlopen(req, timeout=12).read().decode('utf-8'))
        hits = data.get('query',{}).get('search',[])
        if hits:
            results[slug] = [h['title'] for h in hits[:4]]
            print(f'[{slug}] "{q}":')
            for h in hits[:4]:
                print(f'   {h["title"]}')
        else:
            results[slug] = []
            print(f'[{slug}] nema rezultata')
    except Exception as e:
        results[slug] = []
        print(f'[{slug}] ERR: {e}')
