import urllib.request
import urllib.parse
import json
import os
import time
import sys

sys.stdout.reconfigure(encoding='utf-8')

downloads = [
    # Cokesina
    ("File:Manastir Čokešina 001.jpg", "public/images/monasteries/cokesina.jpg"),
    ("File:Manastir Čokešina 004.jpg", "public/images/monasteries/cokesina_gal_1.jpg"),
    ("File:Manastir Čokešina 006.jpg", "public/images/monasteries/cokesina_gal_2.jpg"),
    ("File:Manastir Čokešina 010.jpg", "public/images/monasteries/cokesina_gal_3.jpg"),
    
    # Ljubovija (Bjele Vode)
    ("File:Manastir Bjele vode 001.jpg", "public/images/monasteries/ljubovija.jpg"),
    ("File:Manastir Bjele vode 003.jpg", "public/images/monasteries/ljubovija_gal_1.jpg"),
    ("File:Manastir Bjele vode 006.jpg", "public/images/monasteries/ljubovija_gal_2.jpg"),
    ("File:Manastir Bjele vode 010.jpg", "public/images/monasteries/ljubovija_gal_3.jpg"),
    
    # Citluk
    ("File:Manastir Čitluk 001.jpg", "public/images/monasteries/citluk.jpg"),
    ("File:Manastir Čitluk 010.jpg", "public/images/monasteries/citluk_gal_1.jpg"),
    ("File:Manastir Čitluk 017.jpg", "public/images/monasteries/citluk_gal_2.jpg"),
    ("File:Manastir Čitluk 036.jpg", "public/images/monasteries/citluk_gal_3.jpg"),
]

USER_AGENT = "ManastiriSrbijeAcademicResearch/1.0 (https://manastiri-srbije.rs; contact: ana.vukajlovic@gmail.com)"

def download_file(page_title, target_path):
    api_url = f"https://commons.wikimedia.org/w/api.php?action=query&titles={urllib.parse.quote(page_title)}&prop=imageinfo&iiprop=url&iiurlwidth=1280&format=json"
    req = urllib.request.Request(api_url, headers={'User-Agent': USER_AGENT})
    try:
        with urllib.request.urlopen(req) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            pages = data['query']['pages']
            for pid in pages:
                if 'imageinfo' in pages[pid]:
                    info = pages[pid]['imageinfo'][0]
                    img_url = info.get('thumburl', info.get('url'))
                    print(f"Downloading {page_title} -> {target_path}...")
                    img_req = urllib.request.Request(img_url, headers={'User-Agent': USER_AGENT})
                    with urllib.request.urlopen(img_req) as img_resp, open(target_path, 'wb') as f:
                        f.write(img_resp.read())
                    print(f"✓ Saved {target_path} ({os.path.getsize(target_path)} bytes)")
                    return True
    except Exception as e:
        print(f"❌ Error downloading {page_title}: {e}")
    return False

for title, target in downloads:
    download_file(title, target)
    time.sleep(1.2)

print("\nPreuzimanje slika je završeno!")
