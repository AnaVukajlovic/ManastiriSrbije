import os
import re
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

fp = 'storage/cache_manastiri_rs/https___manastiri_rs_eparhije_zicka_manastir-studenica_.html'
if os.path.exists(fp):
    html = open(fp, 'r', encoding='utf-8').read()
    # Search all image URLs or patterns
    imgs = re.findall(r'(https?://[^\s"\'<>]+\.(?:jpg|jpeg|png|webp))', html)
    print(f"Total image URLs found in Studenica HTML: {len(imgs)}")
    for im in imgs:
        print("  -", im)
