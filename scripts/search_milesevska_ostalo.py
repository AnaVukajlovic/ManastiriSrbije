import urllib.request, urllib.parse, json, time, sys, io
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')
H = {'User-Agent': 'ManastiriSrbijeResearchBot/1.0 python'}

# Wait for rate limit to expire first
time.sleep(8)

queries = [
    ('naupare', 'Naupare manastir'),
    ('mileseva', 'Mileševa manastir'),
    ('davidovica', 'Davidovica manastir'),
    ('janja', 'Janja manastir'),
    ('kumanica', 'Kumanica manastir'),
    ('bistrica', 'Bistrica Zlatibor manastir'),
    ('jabuka', 'Jabuka manastir'),
    ('bradaca', 'Bradaca'),
    ('nimnik', 'Nimnik'),
    ('rukumija', 'Rukumija'),
    ('zdrelo', 'Gornjacka klisura'),
    ('sisojevac2', 'Sisojevac monastery Rasina'),
    ('vlajkovac2', 'Vlajkovac monastery Vojvodina'),
    ('sveta-melanije2', 'Svete Melanije manastir'),
    ('kikinda2', 'Svete Trojice Kikinda'),
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
