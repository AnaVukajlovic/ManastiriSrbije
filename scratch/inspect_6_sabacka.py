import os
import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

for f in ['bogostica', 'dobric', 'dragojevac', 'rozanj', 'rujevac', 'strmovo']:
    fname = f'storage/cache_manastiri_rs/https___manastiri_rs_eparhije_sabacka_manastir-{f}_.html'
    if os.path.exists(fname):
        with open(fname, 'r', encoding='utf-8', errors='ignore') as fp:
            html = fp.read()
        imgs = re.findall(r'https?://[^\s"\'<>]+\.(?:jpg|jpeg|png|webp)', html)
        wp_imgs = [i for i in imgs if 'wp-content/uploads' in i]
        print(f"{f}: {len(wp_imgs)} wp-content imgs")
        for i in set(wp_imgs):
            print(f"   {i}")
