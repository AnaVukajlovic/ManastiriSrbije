import urllib.request
import urllib.parse
import json
import os
import io
import sys
from PIL import Image

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
PUBLIC_IMG_DIR = os.path.join(BASE_DIR, 'public', 'images', 'monasteries')

def download_direct_commons_file(file_title, target_filename):
    url = f"https://commons.wikimedia.org/w/api.php?action=query&format=json&titles=File:{urllib.parse.quote(file_title)}&prop=imageinfo&iiprop=url|size|mime"
    req = urllib.request.Request(url, headers={'User-Agent': 'ManastiriSrbijeBot/2.0 (ana@manastirisrbije.rs)'})
    try:
        with urllib.request.urlopen(req) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            pages = data['query']['pages']
            p_info = list(pages.values())[0]
            if 'imageinfo' in p_info and len(p_info['imageinfo']) > 0:
                img_url = p_info['imageinfo'][0]['url']
                out_path = os.path.join(PUBLIC_IMG_DIR, target_filename)
                print(f"Preuzimam: {img_url} -> {target_filename}")
                img_req = urllib.request.Request(img_url, headers={'User-Agent': 'ManastiriSrbijeBot/2.0 (ana@manastirisrbije.rs)'})
                with urllib.request.urlopen(img_req) as img_resp:
                    content = img_resp.read()
                    with open(out_path, 'wb') as f:
                        f.write(content)
                with Image.open(out_path) as img:
                    print(f"  ✓ Uspešno sačuvano: {target_filename} ({img.size[0]}x{img.size[1]})")
                return True
            else:
                print(f"Fajl nije pronađen: {file_title}")
                return False
    except Exception as e:
        print(f"Greška za '{file_title}': {e}")
        return False

# Exact Wikimedia Commons master files for Serbian monastery frescos
SPECIFIC_COMMONS_FILES = [
    # 1. Studenica - Raspeće Hristovo (Studeničko Raspeće 1209)
    ('Studenica_Crucifixion_fresco.jpg', 'studenica_gal_3.jpg'),
    # 2. Visoki Dečani - Hrist Pantokrator / Freska
    ('Dečani,_freska_Hrista_Pantokratora.jpg', 'visoki-decani_gal_3.jpg'),
    # 3. Ravanica - Knez Lazar ktitor
    ('Knez_Lazar_Ravanica.jpg', 'ravanica_gal_3.jpg'),
    # 4. Sopoćani - Uspenje Bogorodice
    ('Dormition_of_the_Theotokos_fresco_Sopocani.jpg', 'sopocani_gal_3.jpg'),
    # 5. Žiča - Krunisanje / Freska
    ('Zica_fresco_detail.jpg', 'zica_gal_2.jpg'),
    # 6. Gračanica - Simonida
    ('Fresco_of_Queen_Simonida,_Gračanica_monastery,_c._1318-1321.jpg', 'gracanica_gal_3.jpg'),
    # 7. Hilandar / Pećka patrijaršija
    ('Patrijaršija_Peć_Presto_Svetog_Save.jpg', 'pecka-patrijarsija_gal_3.jpg'),
]

for title, fname in SPECIFIC_COMMONS_FILES:
    download_direct_commons_file(title, fname)
