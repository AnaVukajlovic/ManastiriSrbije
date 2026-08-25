import subprocess
import os
from PIL import Image
import sys
import time
import urllib.request
import json
import urllib.parse

sys.stdout.reconfigure(encoding='utf-8')

headers = {'User-Agent': 'ManastiriSrbijeAcademicBot/1.0 (Faculty research; student@example.com)'}

def fetch_wm_via_api(query, target_name):
    print(f"Searching for '{query}'...")
    url = f"https://commons.wikimedia.org/w/api.php?action=query&generator=search&gsrsearch={urllib.parse.quote(query)}&gsrlimit=6&prop=imageinfo&iiprop=url|size|mime&format=json"
    time.sleep(2)
    try:
        req = urllib.request.Request(url, headers=headers)
        with urllib.request.urlopen(req, timeout=15) as resp:
            data = json.loads(resp.read().decode('utf-8'))
        pages = data.get('query', {}).get('pages', {})
        for pid, pdata in pages.items():
            title = pdata.get('title', '').replace('File:', '')
            if any(title.lower().endswith(ext) for ext in ['.jpg', '.jpeg', '.png']):
                time.sleep(1.5)
                # Use Special:FilePath with curl
                temp_p = f"scratch/temp_{target_name}"
                cmd = [
                    'curl.exe', '-s', '-L',
                    '-A', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                    '-o', temp_p,
                    f"https://commons.wikimedia.org/wiki/Special:FilePath/{urllib.parse.quote(title)}?width=1280"
                ]
                subprocess.run(cmd, timeout=20)
                if os.path.exists(temp_p) and os.path.getsize(temp_p) > 5000:
                    with Image.open(temp_p) as im:
                        im = im.convert('RGB')
                        if im.width > 1920 or im.height > 1920:
                            im.thumbnail((1920, 1920), Image.Resampling.LANCZOS)
                        target_p = os.path.join('public/images/monasteries', target_name)
                        im.save(target_p, 'JPEG', quality=88, optimize=True)
                        print(f"  ✓ Sačuvano {target_name} ({im.width}x{im.height}) iz '{title}'")
                    if os.path.exists(temp_p):
                        os.remove(temp_p)
                    return True
    except Exception as e:
        print(f"  ✗ Greška za '{query}': {e}")
    return False

# Download gallery images for remaining 6 monasteries:
# 1. Bogoštica
fetch_wm_via_api("Bogoštica manastir", "bogostica_gal_1.jpg")
fetch_wm_via_api("Bogoštica", "bogostica_gal_2.jpg")

# 2. Dobrić
fetch_wm_via_api("Manastir Dobrić", "dobric_gal_1.jpg")
fetch_wm_via_api("Dobrić Šabac crkva", "dobric_gal_2.jpg")

# 3. Dragojevac
fetch_wm_via_api("Manastir Dragojevac", "dragojevac_gal_1.jpg")
fetch_wm_via_api("Dragojevac crkva", "dragojevac_gal_2.jpg")

# 4. Rožanj
fetch_wm_via_api("Manastir Rožanj", "rozanj_gal_1.jpg")
fetch_wm_via_api("Rožanj Sokolska planina", "rozanj_gal_2.jpg")

# 5. Rujevac
fetch_wm_via_api("Manastir Rujevac", "rujevac_gal_1.jpg")
fetch_wm_via_api("Rujevac Ognjena Marija", "rujevac_gal_2.jpg")

# 6. Strmovo
fetch_wm_via_api("Manastir Strmovo", "strmovo_gal_1.jpg")
fetch_wm_via_api("Strmovo crkva brvnara", "strmovo_gal_2.jpg")
