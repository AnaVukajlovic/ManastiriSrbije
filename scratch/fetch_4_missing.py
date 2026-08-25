import urllib.request
import json
import re
from PIL import Image
import os
import sys

sys.stdout.reconfigure(encoding='utf-8')

headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'}

def fetch_image_from_url(url, target_name):
    try:
        req = urllib.request.Request(url, headers=headers)
        temp_p = f"scratch/temp_{target_name}"
        with urllib.request.urlopen(req, timeout=15) as resp:
            with open(temp_p, 'wb') as f:
                f.write(resp.read())
        with Image.open(temp_p) as im:
            im = im.convert('RGB')
            if im.width > 1920 or im.height > 1920:
                im.thumbnail((1920, 1920), Image.Resampling.LANCZOS)
            target_path = os.path.join('public/images/monasteries', target_name)
            im.save(target_path, 'JPEG', quality=88, optimize=True)
            print(f"  ✓ Uspešno sačuvano {target_name} ({im.width}x{im.height})")
        if os.path.exists(temp_p):
            os.remove(temp_p)
        return True
    except Exception as e:
        print(f"  ✗ Greška za {target_name}: {e}")
        return False

# Direct verified photo URLs:
# 1. Studenica - Kraljeva crkva (Svetih Joakima i Ane)
fetch_image_from_url("https://upload.wikimedia.org/wikipedia/commons/c/c5/King%27s_Church_in_Studenica_monastery.jpg", "studenica_gal_1.jpg")

# 2. Žiča - Visoki zvonik i ulazna kula sa severne strane
fetch_image_from_url("https://upload.wikimedia.org/wikipedia/commons/3/38/Manastir_%C5%BDi%C4%8Da%2C_sve%C4%8Dani_portal_na_ju%C5%BEnoj_strani.jpg", "zica_gal_3.jpg")

# 3. Vraćevšnica - Crkva Svetog Đorđa sa osmostranom kupolom
fetch_image_from_url("https://upload.wikimedia.org/wikipedia/commons/3/34/Crkva_Svetog_Djordja_u_manastiru_Vracevsnica%2C_Srbija.jpg", "vracevsnica_gal_3.jpg")

# 4. Stara Pavlica - Pogled sa padine iznad pruge
fetch_image_from_url("https://upload.wikimedia.org/wikipedia/commons/8/87/Stara_Pavlica_pogled.jpg", "stara-pavlica_gal_2.jpg")
