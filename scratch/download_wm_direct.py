import os
import hashlib
import urllib.request
import urllib.parse
from PIL import Image
import sys
import time

sys.stdout.reconfigure(encoding='utf-8')

headers = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
}

def download_from_commons(filename, target_name):
    # Wikimedia Commons direct URL structure
    fn_clean = filename.replace(' ', '_')
    md5 = hashlib.md5(fn_clean.encode('utf-8')).hexdigest()
    a = md5[0]
    ab = md5[0:2]
    url = f"https://upload.wikimedia.org/wikipedia/commons/{a}/{ab}/{urllib.parse.quote(fn_clean)}"
    print(f"Fetching: {url}")
    
    req = urllib.request.Request(url, headers=headers)
    time.sleep(2)
    try:
        with urllib.request.urlopen(req, timeout=20) as resp:
            temp_path = 'scratch/temp_wm_dl.jpg'
            with open(temp_path, 'wb') as f:
                f.write(resp.read())
        
        with Image.open(temp_path) as im:
            im = im.convert('RGB')
            if im.width > 1920 or im.height > 1920:
                im.thumbnail((1920, 1920), Image.Resampling.LANCZOS)
            target_path = os.path.join('public/images/monasteries', target_name)
            im.save(target_path, 'JPEG', quality=88, optimize=True)
            print(f"  ✓ Uspešno preuzeto i optimizovano: {target_name} ({im.width}x{im.height})")
            return True
    except Exception as e:
        print(f"  ✗ Greška pri preuzimanju {filename}: {e}")
        return False

# High quality verified files on Wikimedia Commons:
downloads = [
    # Studenica
    ("Studenica Crucifixion.jpg", "studenica_gal_4.jpg"),
    ("King's Church in Studenica monastery.jpg", "studenica_gal_1.jpg"),
    ("Bogorodičina crkva, Studenica 05.jpg", "studenica_gal_5.jpg"),
    
    # Žiča
    ("Zica monastery.jpg", "zica_gal_3.jpg"),
    ("Zica 01.jpg", "zica_gal_4.jpg"),
    ("Manastir Zica 04.jpg", "zica_gal_5.jpg"),
    
    # Vraćevšnica
    ("Manastir Vraćevšnica 01.jpg", "vracevsnica_gal_3.jpg"),
    ("Manastir Vraćevšnica 03.jpg", "vracevsnica_gal_4.jpg"),
    
    # Stara Pavlica
    ("Stara Pavlica 01.jpg", "stara-pavlica_gal_2.jpg"),
    ("Stara Pavlica 02.jpg", "stara-pavlica_gal_4.jpg"),
]

for wm_fn, target_fn in downloads:
    download_from_commons(wm_fn, target_fn)
