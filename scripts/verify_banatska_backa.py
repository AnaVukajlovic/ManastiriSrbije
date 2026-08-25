import urllib.request, urllib.parse, json, time, sys, io
sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')
H = {'User-Agent': 'ManastiriSrbijeResearchBot/1.0 python'}

# Verify banatska/backa/beogradska files
files_to_check = [
    ('bavaniste', 'File:Monastère de Bavanište.jpg'),
    ('gaj', 'File:Gaj, Orthodox church.jpg'),
    ('gaj2', 'File:Wiki.Vojvodina VI Gaj (Kovin) 680.jpg'),
    ('hajducica', 'File:Hajdučica Orthodox monastery.jpg'),
    ('mesic', 'File:Mesic Monastery.JPG'),
    ('mesic2', 'File:Wiki.Vojvodina VI Mesić monastery 006.jpg'),
    ('srediste', 'File:Wiki.Vojvodina VI Manastir Središte 408.jpg'),
    ('srediste2', 'File:Wiki.Vojvodina VI Manastir Središte 414.jpg'),
    ('kikinda', 'File:Monastir-Svete-Trojice-Kikinda-exterior2.JPG'),
    ('vojlovica', 'File:Wiki.Vojvodina VIII Vojlovica monastery 251.jpg'),
    ('vojlovica2', 'File:Manastir Vojlovica, tornjevi.jpg'),
    ('bodjani', 'File:Bođani monastery, naos and iconostasis.jpg'),
    ('bodjani2', 'File:Wiki.Vojvodina V Bođani Monastery 379.jpg'),
    ('kovilj', 'File:Kovilj monastery.jpg'),
    ('kovilj2', 'File:Manastir Kovilj u rano jutro.jpg'),
    ('mislodjin', 'File:Manastir svetog Hristofora Mislođin10.12.2016. 002.jpg'),
    ('rakovica', 'File:Manastirrakovica1.JPG'),
    ('rakovica2', 'File:Manastir Rakovica, unutrašnjost crkve i ikonostas.jpg'),
    ('senjak', 'File:Manastir Vavedenje Senjak 8.jpg'),
    ('rajinovac', 'File:Manastir Rajinovac 1.jpg'),
    ('kac', 'File:Manastir Vaskrsenja Hristova - panoramio.jpg'),
    ('sombor', 'File:Wiki.Vojvodina IX Manastir Svetog Stefana 428.jpg'),
]

for slug, f in files_to_check:
    time.sleep(0.6)
    api = f'https://commons.wikimedia.org/w/api.php?action=query&titles={urllib.parse.quote(f)}&prop=imageinfo&iiprop=url|size&format=json'
    req = urllib.request.Request(api, headers=H)
    try:
        data = json.loads(urllib.request.urlopen(req, timeout=12).read().decode('utf-8'))
        pages = data.get('query',{}).get('pages',{})
        found = False
        for pid, pd in pages.items():
            if int(pid) > 0:
                infos = pd.get('imageinfo',[])
                if infos:
                    sz = infos[0].get('size',0)
                    print(f'  OK [{slug}] {f}: {sz//1024}KB')
                    found = True
        if not found:
            print(f'  MISS [{slug}] {f}')
    except Exception as e:
        print(f'  ERR [{slug}] {e}')
