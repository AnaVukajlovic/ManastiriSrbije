import urllib.request
import urllib.parse
import json
import time
from PIL import Image
import os
import sys

sys.stdout.reconfigure(encoding='utf-8')

headers = {'User-Agent': 'ManastiriSrbijeAcademic/1.0 (Faculty research; student@example.com)'}

def fetch_wm_via_api(query, target_name):
    print(f"Searching for '{query}'...")
    url = f"https://commons.wikimedia.org/w/api.php?action=query&generator=search&gsrsearch={urllib.parse.quote(query)}&gsrlimit=5&prop=imageinfo&iiprop=url|size|mime&format=json"
    time.sleep(3.5)
    try:
        req = urllib.request.Request(url, headers=headers)
        with urllib.request.urlopen(req, timeout=15) as resp:
            data = json.loads(resp.read().decode('utf-8'))
        pages = data.get('query', {}).get('pages', {})
        for pid, pdata in pages.items():
            ii = pdata.get('imageinfo', [{}])[0]
            img_url = ii.get('url')
            if img_url and any(img_url.lower().endswith(ext) for ext in ['.jpg', '.jpeg', '.png']):
                time.sleep(3.5)
                req_img = urllib.request.Request(img_url, headers=headers)
                temp_p = f"scratch/temp_{target_name}"
                with urllib.request.urlopen(req_img, timeout=20) as img_resp:
                    with open(temp_p, 'wb') as f:
                        f.write(img_resp.read())
                with Image.open(temp_p) as im:
                    im = im.convert('RGB')
                    if im.width > 1920 or im.height > 1920:
                        im.thumbnail((1920, 1920), Image.Resampling.LANCZOS)
                    target_p = os.path.join('public/images/monasteries', target_name)
                    im.save(target_p, 'JPEG', quality=88, optimize=True)
                    print(f"  ✓ Uspešno sačuvano {target_name} ({im.width}x{im.height}) iz {img_url}")
                if os.path.exists(temp_p):
                    os.remove(temp_p)
                return True
    except Exception as e:
        print(f"  ✗ Greška za '{query}': {e}")
    return False

# Download 3 missing images:
fetch_wm_via_api("Studenica monastery church", "studenica_gal_1.jpg")
fetch_wm_via_api("Vraćevšnica monastery", "vracevsnica_gal_3.jpg")
fetch_wm_via_api("Stara Pavlica", "stara-pavlica_gal_2.jpg")
