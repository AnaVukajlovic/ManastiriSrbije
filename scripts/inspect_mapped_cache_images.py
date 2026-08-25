import os
import re
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

cache_dir = 'storage/cache_manastiri_rs'
files = [f for f in os.listdir(cache_dir) if f.endswith('.html')]

print(f"Total cached files: {len(files)}")

# Map each monastery slug to its cached file
file_map = {}
for f in files:
    # Extract slug from filename: e.g. https___manastiri_rs_eparhije_backa_manastir-bodjani_.html -> bodjani
    m = re.search(r'manastir-([a-zA-Z0-9_-]+)_\.html', f)
    if m:
        slug = m.group(1).lower().replace('_', '-')
        file_map[slug] = f

print(f"Mapped {len(file_map)} monastery slugs from manastiri.rs.")

# Test image extraction for 10 monasteries
test_slugs = ['studenica', 'zica', 'tumane', 'manasija', 'decani', 'gracanica', 'mileseva', 'sopocani', 'krusedol', 'beocin']
for ts in test_slugs:
    fn = file_map.get(ts)
    if fn:
        html = open(os.path.join(cache_dir, fn), 'r', encoding='utf-8').read()
        raw_imgs = re.findall(r'https?://manastiri\.rs/wp-content/uploads/[^\s"\'<>]+\.(?:jpg|jpeg|png|webp)', html, re.IGNORECASE)
        clean = []
        for img in raw_imgs:
            c = re.sub(r'-\d+x\d+(\.\w+)$', r'\1', img)
            low = c.lower()
            if not any(bad in low for bad in ['logo', 'avatar', 'icon', 'cropped', 'favicon', 'default', 'placeholder', 'banner']):
                if c not in clean:
                    clean.append(c)
        print(f"\n{ts.upper()} ({fn}): {len(clean)} slika na manastiri.rs:")
        for c in clean[:4]:
            print(f"  {c}")
    else:
        print(f"\n{ts.upper()}: Nije pronađen u mapi!")
