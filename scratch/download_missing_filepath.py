import subprocess
import os
from PIL import Image
import sys
import time

sys.stdout.reconfigure(encoding='utf-8')

downloads = [
    ("Kraljeva_crkva.JPG", "studenica_gal_1.jpg"),
    ("Crkva_Svetog_Djordja_u_manastiru_Vracevsnica%2C_Srbija.jpg", "vracevsnica_gal_3.jpg"),
    ("Stara_Pavlica_pogled.jpg", "stara-pavlica_gal_2.jpg")
]

for wm_name, target_name in downloads:
    url = f"https://commons.wikimedia.org/wiki/Special:FilePath/{wm_name}?width=1280"
    temp_path = f"scratch/{target_name}"
    cmd = [
        "curl.exe", "-s", "-L",
        "-A", "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36",
        "-o", temp_path,
        url
    ]
    time.sleep(2)
    res = subprocess.run(cmd, capture_output=True)
    if os.path.exists(temp_path) and os.path.getsize(temp_path) > 5000:
        with Image.open(temp_path) as im:
            im = im.convert('RGB')
            if im.width > 1920 or im.height > 1920:
                im.thumbnail((1920, 1920), Image.Resampling.LANCZOS)
            target_p = os.path.join('public/images/monasteries', target_name)
            im.save(target_p, 'JPEG', quality=88, optimize=True)
            print(f"  ✓ USPEŠNO: {target_name} ({im.width}x{im.height}, {os.path.getsize(target_p)} bajtova)")
        if os.path.exists(temp_path):
            os.remove(temp_path)
    else:
        print(f"  ✗ Neuspešno za {target_name}")
