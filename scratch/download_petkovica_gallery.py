import urllib.request
import urllib.parse
import json
import os
import sys

sys.stdout.reconfigure(encoding='utf-8')

def download_commons_image(page_title, target_path):
    api_url = f"https://commons.wikimedia.org/w/api.php?action=query&titles={urllib.parse.quote(page_title)}&prop=imageinfo&iiprop=url&format=json"
    req = urllib.request.Request(api_url, headers={'User-Agent': 'MonasteryApp/1.0 (contact: test@example.com)'})
    with urllib.request.urlopen(req) as resp:
        data = json.loads(resp.read().decode('utf-8'))
        pages = data['query']['pages']
        for pid in pages:
            if 'imageinfo' in pages[pid]:
                img_url = pages[pid]['imageinfo'][0]['url']
                print(f"Downloading {page_title} from {img_url} to {target_path}...")
                img_req = urllib.request.Request(img_url, headers={'User-Agent': 'Mozilla/5.0'})
                with urllib.request.urlopen(img_req) as img_resp, open(target_path, 'wb') as f:
                    f.write(img_resp.read())
                print(f"Saved {target_path} ({os.path.getsize(target_path)} bytes)")
                return True
    print(f"Failed to find imageinfo for {page_title}")
    return False

# 1. Panorama / complex view
download_commons_image("File:Wiki Šumadija X Petkovica Rudnička Monastery 794.jpg", "public/images/monasteries/petkovica-stragari_gal_1.jpg")

# 2. Portal / detail view
download_commons_image("File:Wiki Šumadija X Petkovica Rudnička Monastery 798.jpg", "public/images/monasteries/petkovica-stragari_gal_2.jpg")
