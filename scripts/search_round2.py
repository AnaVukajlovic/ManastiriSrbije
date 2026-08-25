import urllib.request, urllib.parse, json, time, sys, io
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')
H = {'User-Agent': 'ManastiriSrbijeResearchBot/1.0 python'}

time.sleep(10)  # wait for rate limit to expire

queries = [
    ('tomic', 'Tomić monastery Serbia Branicevska'),
    ('trska-crkva', 'Trška Crkva manastir'),
    ('zaova', 'Zaova manastir Malo Crnice'),
    ('zlatenac', 'Zlatenac manastir'),
    ('djerinac', 'Đerinac manastir'),
    ('sestroljin', 'Sestroljin monastery Serbia'),
    ('bosnjane', 'Bošnjane manastir Kruševačka'),
    ('braljina', 'Braljina manastir Serbia'),
    ('drenova', 'Drenova manastir Kruševac'),
    ('grabovo', 'Grabovo manastir Serbia'),
    ('komorane', 'Komorane manastir'),
    ('lepenac', 'Lepenac manastir Kruševačka'),
    ('lesje', 'Lešje manastir Kruševac'),
    ('makresane', 'Makrešane manastir'),
    ('manastirak', 'Manastirak Trstenik manastir'),
    ('mrzenica', 'Mrzenica manastir'),
    ('naupare', 'Naupara manastir Krusevac'),
    ('petina', 'Petina manastir'),
    ('ples', 'Pleš manastir'),
    ('stevanac', 'Stevanac manastir'),
    ('strmac', 'Strmac manastir'),
    ('svojnovo', 'Svojnovo manastir'),
    ('zilinci', 'Žilinci manastir'),
    ('bistrica', 'Bistrica monastery Nova Varos'),
    ('pribojska-banja', 'Priboj Banja monastery'),
    ('pustinja', 'Pustinja monastery Valjevo Jablanica'),
    ('seljani', 'Seljani monastery Milesevska'),
]

for slug, q in queries:
    time.sleep(2.0)
    api = f'https://commons.wikimedia.org/w/api.php?action=query&list=search&srsearch={urllib.parse.quote(q)}&srnamespace=6&srlimit=5&format=json'
    req = urllib.request.Request(api, headers=H)
    try:
        data = json.loads(urllib.request.urlopen(req, timeout=12).read().decode('utf-8'))
        hits = data.get('query',{}).get('search',[])
        if hits:
            print(f'[{slug}] "{q}":')
            for h in hits[:4]:
                print(f'   {h["title"]}')
        else:
            print(f'[{slug}] nema rezultata')
    except Exception as e:
        print(f'[{slug}] ERR: {e}')
