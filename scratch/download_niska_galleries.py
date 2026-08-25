import urllib.request, urllib.parse, json, os, sys, time

sys.stdout.reconfigure(encoding='utf-8')
USER_AGENT = "ManastiriSrbijeAcademicResearch/1.0 (https://manastiri-srbije.rs; contact: ana.vukajlovic@gmail.com)"

downloads = [
    # 96. Poganovo
    ("File:Manastir Poganovo.jpg", "public/images/monasteries/poganovo.jpg"),
    ("File:13 - Manastir Svetog Jovana Bogoslova, kanjon Jerme.jpg", "public/images/monasteries/poganovo_gal_1.jpg"),
    ("File:Manastir Poganovo stari konak.JPG", "public/images/monasteries/poganovo_gal_2.jpg"),
    ("File:Poganovo-monastery-yard.jpg", "public/images/monasteries/poganovo_gal_3.jpg"),

    # 101. Sukovo
    ("File:10 - Manastir Sukovo.jpg", "public/images/monasteries/sukovo.jpg"),
    ("File:Manastir Sukovo.jpg", "public/images/monasteries/sukovo_gal_1.jpg"),
    ("File:Manastir Sukovo, Pirot 10.JPG", "public/images/monasteries/sukovo_gal_2.jpg"),
    ("File:Sukovo-monastery-church.jpg", "public/images/monasteries/sukovo_gal_3.jpg"),

    # 103. Sveti Roman
    ("File:Manastir Sveti Roman 2.jpg", "public/images/monasteries/sveti-roman.jpg"),
    ("File:Manastir Sveti Roman 23-5-2021.jpg", "public/images/monasteries/sveti-roman_gal_1.jpg"),
    ("File:Manastir Sveti Roman kapija.jpg", "public/images/monasteries/sveti-roman_gal_2.jpg"),

    # 255. Đunis
    ("File:Manastir Presvete Bogorodice- Djunis.jpg", "public/images/monasteries/djunis.jpg"),
    ("File:Manastir Pokrova Presvete Bogorodice, Djunis.JPG", "public/images/monasteries/djunis_gal_1.jpg"),
    ("File:Manastir Đunis 04.jpg", "public/images/monasteries/djunis_gal_2.jpg"),
    ("File:Manastir Đunis 11.jpg", "public/images/monasteries/djunis_gal_3.jpg"),

    # 104. Temska
    ("File:Manastir Sv Djordje-Temska 20.07.2012 12-06-34.jpg", "public/images/monasteries/temska.jpg"),
    ("File:Manastir Sv Djordje-Temska 20.07.2012 12-20-02.jpg", "public/images/monasteries/temska_gal_1.jpg"),
    ("File:Manastir Sv Djordje-Temska 20.07.2012 12-23-08.jpg", "public/images/monasteries/temska_gal_2.jpg"),

    # 89. Lipovac
    ("File:Lipovac nova veca.jpg", "public/images/monasteries/lipovac.jpg"),
    ("File:Konak manastira Svetog Stefana.jpg", "public/images/monasteries/lipovac_gal_1.jpg"),
    ("File:Cesma ispred manastira Svetog Stefana.jpg", "public/images/monasteries/lipovac_gal_2.jpg"),
    ("File:Wiki.Biseri V Manastir Lipovac 745.jpg", "public/images/monasteries/lipovac_gal_3.jpg"),

    # 87. Kuršumlija (Sveti Nikola)
    ("File:Manastir Svetog Nikole- Kuršumlija.jpg", "public/images/monasteries/kursumlija.jpg"),
    ("File:Manastir Svetog Nikole, Kuršumlija 01.jpg", "public/images/monasteries/kursumlija_gal_1.jpg"),
    ("File:Manastir Svetog Nikole, Kuršumlija 47.jpg", "public/images/monasteries/kursumlija_gal_2.jpg"),

    # 97. Rsovci
    ("File:Pećinska crkva sv. Petra i Pavla selo Rsovci, Pirot.jpg", "public/images/monasteries/rsovci.jpg"),

    # 250. Sićevo
    ("File:The Monastery of the Theotokos in the Sićevo 3 0443.jpg", "public/images/monasteries/sicevo.jpg"),
    ("File:Manastir Sićevo.1.JPG", "public/images/monasteries/sicevo_gal_1.jpg"),
    ("File:Wiki.Južnije II Manastir Sićevo 116.jpg", "public/images/monasteries/sicevo_gal_2.jpg"),

    # 80. Iverica
    ("File:Wiki.Južnije II Manastir Svete Petke Iverica 217.jpg", "public/images/monasteries/iverica.jpg"),
    ("File:Wiki.Južnije II Manastir Svete Petke Iverica 209.jpg", "public/images/monasteries/iverica_gal_1.jpg"),
    ("File:Wiki.Južnije II Manastir Svete Petke Iverica 199.jpg", "public/images/monasteries/iverica_gal_2.jpg"),

    # 77. Divljane
    ("File:Wiki.Južnije II Manastir Divljane 330.jpg", "public/images/monasteries/divljane.jpg"),
    ("File:Wiki.Južnije II Manastir Divljane 304.jpg", "public/images/monasteries/divljane_gal_1.jpg"),
    ("File:Wiki.Južnije II Manastir Divljane 296.jpg", "public/images/monasteries/divljane_gal_2.jpg"),

    # 78. Gornji Matejevac (Latinska crkva)
    ("File:Latinska Crkva, Gornji Matejevac.jpg", "public/images/monasteries/gornji-matejevac.jpg"),
    ("File:Wiki.Niš foto Latinska crkva 110.jpg", "public/images/monasteries/gornji-matejevac_gal_1.jpg"),
    ("File:Wiki.Niš foto Latinska crkva 109.jpg", "public/images/monasteries/gornji-matejevac_gal_2.jpg"),

    # 102. Sveti Jovan (Matejevac)
    ("File:Манастир Св. Јована Крститеља код Доњег Матејевца3.jpg", "public/images/monasteries/sveti-jovan.jpg"),
    ("File:Манастир Св. Јована Крститеља код Доњег Матејевца6.jpg", "public/images/monasteries/sveti-jovan_gal_1.jpg"),

    # 73. Ajdanovac
    ("File:Ajdanovac.jpg", "public/images/monasteries/ajdanovac.jpg"),
    ("File:Wiki.Rasina II Ajdanovac Monastery 802.jpg", "public/images/monasteries/ajdanovac_gal_1.jpg"),
    ("File:Wiki.Rasina II Ajdanovac Monastery 803.jpg", "public/images/monasteries/ajdanovac_gal_2.jpg"),

    # 81. Janjuša (Jašunja)
    ("File:Wiki.Južnije III Manastir Vavedenja Presvete Bogorodice (Jašunja) 286.jpg", "public/images/monasteries/janjusa.jpg"),
    ("File:Wiki.Južnije III Manastir Vavedenja Presvete Bogorodice (Jašunja) 280.jpg", "public/images/monasteries/janjusa_gal_1.jpg"),
    ("File:Wiki.Južnije III Manastir Vavedenja Presvete Bogorodice (Jašunja) 285.jpg", "public/images/monasteries/janjusa_gal_2.jpg"),

    # 98. Rudare
    ("File:Hram Prepodobne mučenice Paraskeve, Konak, Rudare, Leskovac, a01.JPG", "public/images/monasteries/rudare.jpg"),
    ("File:Hram Prepodobne mučenice Paraskeve, Konak, Rudare, Leskovac, a02.JPG", "public/images/monasteries/rudare_gal_1.jpg"),
    ("File:Hram Prepodobne mučenice Paraskeve, Konak, Rudare, Leskovac, b01.JPG", "public/images/monasteries/rudare_gal_2.jpg"),

    # 100. Smilovci
    ("File:Manastir Smilovci, Dimitrovgrad.jpg", "public/images/monasteries/smilovci.jpg"),
    ("File:Wiki.Južnije V Smilovci Monastery 171.jpg", "public/images/monasteries/smilovci_gal_1.jpg"),
    ("File:Wiki.Južnije V Smilovci Monastery 172.jpg", "public/images/monasteries/smilovci_gal_2.jpg"),

    # 106. Veta
    ("File:Vetanski manastir-1.jpg", "public/images/monasteries/veta.jpg"),
    ("File:Vetanski manastir 1.jpg", "public/images/monasteries/veta_gal_1.jpg"),
    ("File:Vetanski manastir 3.jpg", "public/images/monasteries/veta_gal_2.jpg"),

    # 99. Sinjački
    ("File:Manastir Svetog Nikolaja Mirilikijskog Sinjac.jpg", "public/images/monasteries/sinjacki.jpg"),
]

def download_file(page_title, target_path):
    api_url = f"https://commons.wikimedia.org/w/api.php?action=query&titles={urllib.parse.quote(page_title)}&prop=imageinfo&iiprop=url&iiurlwidth=1280&format=json"
    req = urllib.request.Request(api_url, headers={'User-Agent': USER_AGENT})
    try:
        with urllib.request.urlopen(req, timeout=15) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            pages = data['query']['pages']
            for pid in pages:
                if 'imageinfo' in pages[pid]:
                    info = pages[pid]['imageinfo'][0]
                    img_url = info.get('thumburl', info.get('url'))
                    print(f"Downloading {page_title} -> {target_path}...", flush=True)
                    img_req = urllib.request.Request(img_url, headers={'User-Agent': USER_AGENT})
                    with urllib.request.urlopen(img_req, timeout=20) as img_resp, open(target_path, 'wb') as f:
                        f.write(img_resp.read())
                    print(f"✓ Saved {target_path} ({os.path.getsize(target_path)} bytes)", flush=True)
                    return True
    except Exception as e:
        print(f"❌ Error downloading {page_title}: {e}", flush=True)
    return False

for title, target in downloads:
    download_file(title, target)
    time.sleep(0.8)

print("\nPreuzimanje slika za Eparhiju nišku je završeno!", flush=True)
