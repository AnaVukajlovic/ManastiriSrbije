import urllib.request
import re
from PIL import Image
import os
import sys
import time

sys.stdout.reconfigure(encoding='utf-8')

headers = {'User-Agent': 'ManastiriSrbijeAcademicBot/1.0 (Faculty research; student@example.com)'}

def fetch_thumb(orig_url, target_name):
    # Convert upload url to 1024px thumbnail url
    # Format: https://upload.wikimedia.org/wikipedia/commons/thumb/a/a3/Kraljeva_crkva.JPG/1024px-Kraljeva_crkva.JPG
    m = re.match(r'https://upload\.wikimedia\.org/wikipedia/commons/([0-9a-f])/([0-9a-f]{2})/([^?]+)', orig_url)
    if not m:
        print(f"URL format mismatch: {orig_url}")
        return False
    a, ab, fn = m.groups()
    thumb_url = f"https://upload.wikimedia.org/wikipedia/commons/thumb/{a}/{ab}/{fn}/1024px-{fn}"
    print(f"Fetching thumbnail: {thumb_url}")
    time.sleep(2)
    try:
        req = urllib.request.Request(thumb_url, headers=headers)
        temp_p = f"scratch/temp_{target_name}"
        with urllib.request.urlopen(req, timeout=15) as resp:
            with open(temp_p, 'wb') as f:
                f.write(resp.read())
        with Image.open(temp_p) as im:
            im = im.convert('RGB')
            if im.width > 1920 or im.height > 1920:
                im.thumbnail((1920, 1920), Image.Resampling.LANCZOS)
            target_p = os.path.join('public/images/monasteries', target_name)
            im.save(target_p, 'JPEG', quality=88, optimize=True)
            print(f"  ✓ Uspešno sačuvano {target_name} ({im.width}x{im.height})")
        if os.path.exists(temp_p):
            os.remove(temp_p)
        return True
    except Exception as e:
        print(f"  ✗ Greška: {e}")
        return False

# 1. Studenica - Kraljeva crkva
fetch_thumb("https://upload.wikimedia.org/wikipedia/commons/a/a3/Kraljeva_crkva.JPG", "studenica_gal_1.jpg")

# 2. Vraćevšnica - Crkva Svetog Đorđa
fetch_thumb("https://upload.wikimedia.org/wikipedia/commons/3/34/Crkva_Svetog_Djordja_u_manastiru_Vracevsnica%2C_Srbija.jpg", "vracevsnica_gal_3.jpg")

# 3. Stara Pavlica - Pogled na crkvu i prugu
fetch_thumb("https://upload.wikimedia.org/wikipedia/commons/8/87/Stara_Pavlica_pogled.jpg", "stara-pavlica_gal_2.jpg")
