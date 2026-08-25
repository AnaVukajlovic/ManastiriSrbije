import os
import re
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

cache_dir = 'storage/cache_manastiri_rs'
files = [f for f in os.listdir(cache_dir) if f.endswith('.html')]
print(f'Total cached html files from manastiri.rs: {len(files)}')

samples = ['manastir-studenica_.html', 'manastir-manasija_.html', 'manastir-zica_.html', 'manastir-tumane_.html', 'manastir-raca_.html', 'manastir-gracanica_.html', 'manastir-decani_.html', 'manastir-beocin_.html', 'manastir-tresije_.html']

for sample in samples:
    fp = os.path.join(cache_dir, sample)
    if os.path.exists(fp):
        html = open(fp, 'r', encoding='utf-8').read()
        raw_imgs = re.findall(r'https?://manastiri\.rs/wp-content/uploads/[^\s"\'<>]+\.(?:jpg|jpeg|png|webp)', html, re.IGNORECASE)
        clean_imgs = []
        for im in raw_imgs:
            clean = re.sub(r'-\d+x\d+(\.\w+)$', r'\1', im)
            low = clean.lower()
            if not any(bad in low for bad in ['logo', 'avatar', 'icon', 'cropped', 'favicon', 'default', 'placeholder', 'banner', 'cropped']):
                if clean not in clean_imgs:
                    clean_imgs.append(clean)
        print(f"\n{sample}: {len(clean_imgs)} slika:")
        for ci in clean_imgs[:4]:
            print(f"  {ci}")
