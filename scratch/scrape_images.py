import urllib.request
from bs4 import BeautifulSoup
import re
import json

headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36'}

urls = {
    'grliste': 'https://manastiri.rs/eparhije/timocka/manastir-grliste/',
    'krepicevac': 'https://manastiri.rs/eparhije/timocka/manastir-krepicevac/',
    'lapusnja': 'https://manastiri.rs/eparhije/timocka/manastir-lapusnja/',
    'lozica': 'https://manastiri.rs/eparhije/timocka/manastir-lozica/',
    'vratna': 'https://manastiri.rs/eparhije/timocka/manastir-vratna/',
    'suvodol': 'https://manastiri.rs/eparhije/timocka/manastir-suvodol/',
    'lelic': 'https://manastiri.rs/eparhije/valjevska/manastir-lelic/',
    'bogovadja': 'https://manastiri.rs/eparhije/valjevska/manastir-bogovadja/',
    'dokmir': 'https://manastiri.rs/eparhije/valjevska/manastir-dokmir/',
    'grabovac': 'https://manastiri.rs/eparhije/valjevska/manastir-grabovac/',
    'ribnica': 'https://manastiri.rs/eparhije/valjevska/manastir-ribnica/',
    'pluzac': 'https://manastiri.rs/eparhije/valjevska/manastir-pluzac/',
    'jovanja': 'https://manastiri.rs/eparhije/valjevska/manastir-jovanja/'
}

results = {}

for slug, url in urls.items():
    try:
        req = urllib.request.Request(url, headers=headers)
        with urllib.request.urlopen(req, timeout=15) as resp:
            html = resp.read().decode('utf-8')
            soup = BeautifulSoup(html, 'html.parser')
            
            # Find all image tags and a hrefs pointing to images
            imgs = []
            for img in soup.find_all('img'):
                src = img.get('src') or img.get('data-src') or img.get('data-lazy-src')
                if src and ('wp-content/uploads' in src or 'manastiri.rs' in src):
                    if not any(x in src.lower() for x in ['logo', 'icon', 'banner-header', 'avatar']):
                        # get highest resolution if srcset exists or clean up thumb dimensions like -300x200
                        imgs.append(src)
            
            for a in soup.find_all('a', href=True):
                href = a['href']
                if any(href.lower().endswith(ext) for ext in ['.jpg', '.jpeg', '.png', '.webp']):
                    if 'wp-content/uploads' in href:
                        imgs.append(href)
            
            # remove duplicates preserving order
            seen = set()
            unique_imgs = []
            for im in imgs:
                # clean up query params
                im_clean = im.split('?')[0]
                if im_clean not in seen:
                    seen.add(im_clean)
                    unique_imgs.append(im_clean)
            
            results[slug] = {
                'url': url,
                'title': soup.title.string if soup.title else '',
                'images': unique_imgs
            }
            print(f"[{slug}] Found {len(unique_imgs)} images on {url}")
            for im in unique_imgs:
                print(f"    - {im}")
    except Exception as e:
        print(f"[{slug}] Error: {e}")
        results[slug] = {'error': str(e)}

with open('scratch/manastiri_rs_images.json', 'w', encoding='utf-8') as f:
    json.dump(results, f, ensure_ascii=False, indent=2)
