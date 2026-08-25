import urllib.request, urllib.parse, json, sys, os
from PIL import Image

sys.stdout.reconfigure(encoding='utf-8')
USER_AGENT = 'ManastiriSrbijeResearch/1.0 (contact: ana.vukajlovic@gmail.com)'

def get_image_direct_url(file_title):
    url = f'https://commons.wikimedia.org/w/api.php?action=query&titles={urllib.parse.quote(file_title)}&prop=imageinfo&iiprop=url&format=json'
    req = urllib.request.Request(url, headers={'User-Agent': USER_AGENT})
    try:
        with urllib.request.urlopen(req, timeout=12) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            pages = data.get('query', {}).get('pages', {})
            for pid, pdata in pages.items():
                if 'imageinfo' in pdata and len(pdata['imageinfo']) > 0:
                    return pdata['imageinfo'][0]['url']
    except Exception as e:
        print(f"Error fetching URL for {file_title}: {e}")
    return None

def download_and_optimize(file_title, target_rel_path, max_width=1600):
    direct_url = get_image_direct_url(file_title)
    if not direct_url:
        print(f"FAILED direct URL for: {file_title}")
        return False
    
    target_abs = os.path.join('public', target_rel_path)
    os.makedirs(os.path.dirname(target_abs), exist_ok=True)
    temp_target = target_abs + '.tmp'
    
    req = urllib.request.Request(direct_url, headers={'User-Agent': USER_AGENT})
    try:
        with urllib.request.urlopen(req, timeout=30) as resp:
            with open(temp_target, 'wb') as f:
                f.write(resp.read())
        
        # Optimize with PIL to standard JPEG
        with Image.open(temp_target) as img:
            img = img.convert('RGB')
            if img.width > max_width:
                height = int(img.height * (max_width / img.width))
                img = img.resize((max_width, height), Image.Resampling.LANCZOS)
            img.save(target_abs, 'JPEG', quality=88, optimize=True)
        
        if os.path.exists(temp_target):
            os.remove(temp_target)
        
        size_kb = os.path.getsize(target_abs) // 1024
        print(f"SUCCESS: {target_rel_path} ({size_kb} KB) <- {file_title}")
        return True
    except Exception as e:
        print(f"FAILED downloading {file_title} to {target_rel_path}: {e}")
        if os.path.exists(temp_target):
            os.remove(temp_target)
        return False

# Plan downloads for Braničevska eparhija
downloads = [
    # Manasija (26)
    ('File:Despotovac l Manastir Manasija 001.jpg', 'images/monasteries/manasija.jpg'),
    ('File:Manastir-manasija-despotovac-serbia-atipiks.jpg', 'images/monasteries/manasija_gal_1.jpg'),
    ('File:Manasija manastir 5.jpg', 'images/monasteries/manasija_gal_2.jpg'),
    ('File:Manasija manastir 13.jpg', 'images/monasteries/manasija_gal_3.jpg'),

    # Ravanica (32)
    ('File:Monastery Ravanica.JPG', 'images/monasteries/ravanica.jpg'),
    ('File:Ravanica Monastery (by Pudelek).jpg', 'images/monasteries/ravanica_gal_1.jpg'),
    ('File:Manastir Ravanica 1.JPG', 'images/monasteries/ravanica_gal_2.jpg'),
    ('File:Manastir Ravanica sa zidinama.JPG', 'images/monasteries/ravanica_gal_3.jpg'),

    # Tumane (39)
    ('File:Manastir Tumane 2025.jpg', 'images/monasteries/tumane.jpg'),
    ('File:Wiki.Đerdap II Tuman Monastery 352.jpg', 'images/monasteries/tumane_gal_1.jpg'),
    ('File:Wiki.Đerdap II Tuman Monastery 328.jpg', 'images/monasteries/tumane_gal_2.jpg'),
    ('File:Wiki.Đerdap II Tuman Monastery 349.jpg', 'images/monasteries/tumane_gal_3.jpg'),

    # Gornjak (23)
    ('File:005 - Manastir Gornjak.jpg', 'images/monasteries/gornjak.jpg'),
    ('File:007 - Manastir Gornjak.jpg', 'images/monasteries/gornjak_gal_1.jpg'),
    ('File:Gornjak СК 569.JPG', 'images/monasteries/gornjak_gal_2.jpg'),
    ('File:Wiki.Zaleđe III Gornjak Monastery 243.jpg', 'images/monasteries/gornjak_gal_3.jpg'),

    # Koporin (25)
    ('File:Manastir Koporin 1.jpg', 'images/monasteries/koporin.jpg'),
    ('File:Manastir Koporin 2.jpg', 'images/monasteries/koporin_gal_1.jpg'),
    ('File:Manastir Koporin 4.jpg', 'images/monasteries/koporin_gal_2.jpg'),

    # Pokajnica (30)
    ('File:Manastir Pokajnica,Velika Plana.jpg', 'images/monasteries/pokajnica.jpg'),
    ('File:Manastir Pokajnica.JPG', 'images/monasteries/pokajnica_gal_1.jpg'),
    ('File:Pokajnica - detalj.JPG', 'images/monasteries/pokajnica_gal_2.jpg'),
    ('File:Pokajnica - unutrašnjost.JPG', 'images/monasteries/pokajnica_gal_3.jpg'),

    # Nimnik (29)
    ('File:Nimnik ulaz.jpg', 'images/monasteries/nimnik.jpg'),
    ('File:Manastir Nimnik - konak u zagrljaju prirode.jpg', 'images/monasteries/nimnik_gal_1.jpg'),
    ('File:Manastir Nimnik - detalj sa crkve.jpg', 'images/monasteries/nimnik_gal_2.jpg'),

    # Rukumija (34)
    ('File:Manastir rukumija.jpg', 'images/monasteries/rukumija.jpg'),
    ('File:Wiki.Zaleđe II Rukumija Monastery 387.jpg', 'images/monasteries/rukumija_gal_1.jpg'),
    ('File:Wiki.Zaleđe II Rukumija Monastery 388.jpg', 'images/monasteries/rukumija_gal_2.jpg'),

    # Zaova (40)
    ('File:Wiki.Zaleđe II Manastir Zaova 1279 01.jpg', 'images/monasteries/zaova.jpg'),
    ('File:Wiki.Zaleđe II Manastir Zaova 1279 02.jpg', 'images/monasteries/zaova_gal_1.jpg'),
    ('File:Wiki.Zaleđe II Manastir Zaova 1279 03.jpg', 'images/monasteries/zaova_gal_2.jpg'),

    # Sisojevac (36)
    ('File:07 - Manastir Sisojevac.jpg', 'images/monasteries/sisojevac.jpg'),
    ('File:Sisojevac-crkva i zvonik-800.jpg', 'images/monasteries/sisojevac_gal_1.jpg'),
    ('File:BW401 - Manastir Sisojevac.jpg', 'images/monasteries/sisojevac_gal_2.jpg'),

    # Trška Crkva (38)
    ('File:Trška crkva.jpg', 'images/monasteries/trska-crkva.jpg'),
    ('File:Trška crkva2.jpg', 'images/monasteries/trska-crkva_gal_1.jpg'),
    ('File:Trška crkva dvoglavi orao detalj.jpg', 'images/monasteries/trska-crkva_gal_2.jpg'),

    # Miljkovo (27)
    ('File:Миљков манастир 13.jpg', 'images/monasteries/miljkovo.jpg'),
    ('File:Wiki.Biseri III Milkov Monastery 112.jpg', 'images/monasteries/miljkovo_gal_1.jpg'),
    ('File:Wiki.Biseri III Milkov Monastery 129.jpg', 'images/monasteries/miljkovo_gal_2.jpg'),

    # Izvor (24)
    ('File:Izvor Monastery - chapel outside.jpg', 'images/monasteries/izvor.jpg'),
    ('File:Izvor Monastery - ayazmo 1.jpg', 'images/monasteries/izvor_gal_1.jpg'),
    ('File:Izvor Monastery - ayazmo 2.jpg', 'images/monasteries/izvor_gal_2.jpg'),

    # Sestroljin (35)
    ('File:Manastir Sestroljin.jpg', 'images/monasteries/sestroljin.jpg'),
    ('File:Manastir Sestroljin - unutrašnjost.jpg', 'images/monasteries/sestroljin_gal_1.jpg'),
    ('File:Sestroljin – kapela u cvetnom okviru.jpg', 'images/monasteries/sestroljin_gal_2.jpg'),

    # Bradača (21)
    ('File:Wiki.Zaleđe II Manastir Bradača 1464 11.jpg', 'images/monasteries/bradaca.jpg'),
    ('File:Wiki.Zaleđe II Manastir Bradača 1464 12.jpg', 'images/monasteries/bradaca_gal_1.jpg'),

    # Dobreš (22)
    ('File:Wiki.Biseri III Dobreš Monastery 159.jpg', 'images/monasteries/dobres.jpg'),
    ('File:Wiki.Biseri III Dobreš Monastery 153.jpg', 'images/monasteries/dobres_gal_1.jpg'),

    # Zlatenac (41)
    ('File:Manastir Zlatenac.jpg', 'images/monasteries/zlatenac.jpg'),
    ('File:Wiki.Biseri I Zlatenac Monastery 1244 12.jpg', 'images/monasteries/zlatenac_gal_1.jpg'),

    # Tomić (37)
    ('File:Wiki.Biseri I Tomić Monastery 1189 29.jpg', 'images/monasteries/tomic.jpg'),
    ('File:Wiki.Biseri I Tomić Monastery 1189 20.jpg', 'images/monasteries/tomic_gal_1.jpg'),

    # Radošin (31)
    ('File:Wiki.Biseri I Radošin Monastery 1221 02.jpg', 'images/monasteries/radosin.jpg'),
    ('File:Wiki.Biseri I Radošin Monastery 1221 07.jpg', 'images/monasteries/radosin_gal_1.jpg'),

    # Reškovica (33)
    ('File:Wiki.Zaleđe III Reškovica Monastery 372.jpg', 'images/monasteries/reskovica.jpg'),
    ('File:Wili.Zaleđe III Reškovica Monastery 1551 11.jpg', 'images/monasteries/reskovica_gal_1.jpg'),

    # Namasija (28)
    ('File:Wiki.Biseri III Manastir Namasija 339.jpg', 'images/monasteries/namasija.jpg'),
    ('File:Wiki.Biseri III Namasija Monastery 407.jpg', 'images/monasteries/namasija_gal_1.jpg'),

    # Ždrelo (43)
    ('File:Manastir Ždrelo.jpg', 'images/monasteries/zdrelo.jpg'),
    ('File:Wili.Zaleđe III Holy Trinity Monastery (Ždrelo) 1538 13.jpg', 'images/monasteries/zdrelo_gal_1.jpg'),
]

print(f"Total downloads planned: {len(downloads)}")
success_count = 0
for src_title, dst_path in downloads:
    if download_and_optimize(src_title, dst_path):
        success_count += 1

print(f"\nFinished: {success_count}/{len(downloads)} images downloaded successfully.")
