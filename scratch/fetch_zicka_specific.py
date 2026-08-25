import os
import urllib.request
import urllib.parse
import json
import time
from PIL import Image
import sys

sys.stdout.reconfigure(encoding='utf-8')

headers = {'User-Agent': 'ManastiriSrbijeAcademicResearch/1.0 (Faculty research; student@example.com)'}

def get_wikimedia_image_by_title(title, target_filename):
    url = f"https://commons.wikimedia.org/w/api.php?action=query&format=json&titles=File:{urllib.parse.quote(title)}&prop=imageinfo&iiprop=url|size|mime"
    req = urllib.request.Request(url, headers=headers)
    time.sleep(3)
    try:
        with urllib.request.urlopen(req, timeout=15) as resp:
            data = json.loads(resp.read().decode('utf-8'))
        pages = data.get('query', {}).get('pages', {})
        for pid, pdata in pages.items():
            if 'imageinfo' in pdata:
                file_url = pdata['imageinfo'][0]['url']
                time.sleep(3)
                req_img = urllib.request.Request(file_url, headers=headers)
                with urllib.request.urlopen(req_img, timeout=20) as img_resp:
                    temp_path = 'scratch/temp_wm.jpg'
                    with open(temp_path, 'wb') as f:
                        f.write(img_resp.read())
                with Image.open(temp_path) as im:
                    im = im.convert('RGB')
                    if im.width > 1920 or im.height > 1920:
                        im.thumbnail((1920, 1920), Image.Resampling.LANCZOS)
                    target_path = os.path.join('public/images/monasteries', target_filename)
                    im.save(target_path, 'JPEG', quality=88, optimize=True)
                    print(f"  ✓ Uspešno sačuvano: {target_filename} ({im.width}x{im.height}) iz '{title}'")
                    return True
    except Exception as e:
        print(f"  ✗ Greška za '{title}': {e}")
    return False

# Specific high-value historical Wikimedia files
selected_files = [
    # 1. Studenica - Raspeće Hristovo (najčuvenija srpska freska 1209)
    ("Studenica Crucifixion.jpg", "studenica_gal_4.jpg"),
    # 2. Studenica - Kraljeva crkva (Svetih Joakima i Ane)
    ("King's Church in Studenica monastery.jpg", "studenica_gal_1.jpg"),
    # 3. Žiča - Severni / ulazni portal i kula
    ("Zica 01.jpg", "zica_gal_3.jpg"),
    # 4. Žiča - Freskopis / unutrašnjost hrama
    ("Manastir Žiča, unutrašnjost.jpg", "zica_gal_4.jpg"),
    # 5. Gradac - Zapadni gotizovani portal sa mermerom
    ("Gradac Monastery 2013-09-02 (27).JPG", "gradac_gal_3.jpg"),
    # 6. Vraćevšnica - Hram Svetog Đorđa sa osmostranom kupolom
    ("Manastir Vraćevšnica 01.jpg", "vracevsnica_gal_3.jpg"),
    # 7. Nova Pavlica - Manastirska crkva u dolini Ibra
    ("Manastir Nova Pavlica 04.jpg", "nova-pavlica_gal_3.jpg"),
    # 8. Rača - Ikonostas i unutrašnjost
    ("Manastir Rača 07.jpg", "raca_gal_3.jpg"),
    # 9. Stara Pavlica - Pogled na crkvu i liticu
    ("Stara Pavlica 01.jpg", "stara-pavlica_gal_2.jpg"),
    # 10. Kovilje - Pećinski hram pod stenom
    ("Manastir Kovilje 03.jpg", "kovilje_gal_3.jpg")
]

for title, target in selected_files:
    get_wikimedia_image_by_title(title, target)
