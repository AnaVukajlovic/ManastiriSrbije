import urllib.request, urllib.parse, json, time, sys, io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')
H = {'User-Agent': 'ManastiriSrbijeResearchBot/1.0 python'}

files_to_check = [
    # Sveta Trojica Kikinda
    'File:Манастир Свете Тројице у Кикинди.jpeg',
    'File:Звоник Манастира Свете Тројице у Кикинди.jpeg',
    'File:Manastir Svete Trojice Kikinda 01.JPG',
    # Vlajkovac
    'File:Vlajkovac, Orthodox Church.jpg',
    'File:Wiki.Vojvodina VI Vlajkovac 147.jpg',
    'File:Wiki.Vojvodina VI Vlajkovac 148.jpg',
    # Gaj
    'File:Wiki.Vojvodina VI Gaj (Kovin) 680.jpg',
    'File:Wiki.Vojvodina VI Gaj (Kovin) 679.jpg',
    # Reškovica
    'File:Wiki.Zaleđe III Reškovica Monastery 368.jpg',
    'File:Wiki.Zaleđe III Reškovica Monastery 369.jpg',
    'File:Wili.Zaleđe III Reškovica Monastery 1551 24.jpg',
    'File:Wili.Zaleđe III Reškovica Monastery 1551 01.jpg',
    # Sombor
    'File:Wiki.Vojvodina IX Manastir Svetog Stefana 428.jpg',
    'File:Wiki.Vojvodina IX Manastir Svetog Stefana 427.jpg',
    'File:Wiki.Vojvodina IX Manastir Svetog Stefana 432.jpg',
    # Sisojevac
    'File:Wiki.Biseri I Sisojevac Monastery 994.jpg',
    'File:Wiki.Biseri I Sisojevac Monastery 993.jpg',
    'File:Wiki.Biseri I Sisojevac Monastery 995.jpg',
    'File:Манастир Сисевац споља.jpg',
    # Ždrelo
    'File:Wili.Zaleđe III Holy Trinity Monastery (Ždrelo) 1538 13.jpg',
    'File:Wili.Zaleđe III Holy Trinity Monastery (Ždrelo) 1538 01.jpg',
    'File:Wili.Zaleđe III Holy Trinity Monastery (Ždrelo) 1517 15.jpg',
    # Mažići
    'File:Manastir Mažići3.jpg',
    'File:Priboj Mazici IMG 0250.JPG',
    'File:Priboj Mazici IMG 0255.JPG',
    # Kumanica
    'File:Manastir-kumanica-0124.jpg',
    'File:Manastir-kumanica-0122.jpg',
    'File:Manastir Kumanica.jpg',
]

for f in files_to_check:
    time.sleep(1.0)
    api = f'https://commons.wikimedia.org/w/api.php?action=query&titles={urllib.parse.quote(f)}&prop=imageinfo&iiprop=url|size&format=json'
    req = urllib.request.Request(api, headers=H)
    try:
        data = json.loads(urllib.request.urlopen(req, timeout=12).read().decode('utf-8'))
        pages = data.get('query',{}).get('pages',{})
        for pid, pd in pages.items():
            if int(pid) > 0:
                infos = pd.get('imageinfo',[])
                if infos:
                    sz = infos[0].get('size',0)
                    u = infos[0].get('url','')
                    print(f"OK | {f} | {sz//1024}KB | {u[:70]}")
            else:
                print(f"MISSING | {f}")
    except Exception as e:
        print(f"ERR | {f} | {e}")
