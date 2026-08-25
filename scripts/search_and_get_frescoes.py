import urllib.request
import urllib.parse
import json
import os
import io
import sys
from PIL import Image

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
PUBLIC_IMG_DIR = os.path.join(BASE_DIR, 'public', 'images', 'monasteries')

def search_commons_files(query):
    url = f"https://commons.wikimedia.org/w/api.php?action=query&format=json&list=search&srsearch={urllib.parse.quote(query)}&srnamespace=6&srlimit=5"
    req = urllib.request.Request(url, headers={'User-Agent': 'ManastiriSrbijeBot/2.0 (ana@manastirisrbije.rs)'})
    try:
        with urllib.request.urlopen(req) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            results = data.get('query', {}).get('search', [])
            return [r['title'] for r in results]
    except Exception as e:
        print(f"Greška za '{query}': {e}")
        return []

def download_file_by_title(title, target_filename):
    url = f"https://commons.wikimedia.org/w/api.php?action=query&format=json&titles={urllib.parse.quote(title)}&prop=imageinfo&iiprop=url|size|mime"
    req = urllib.request.Request(url, headers={'User-Agent': 'ManastiriSrbijeBot/2.0 (ana@manastirisrbije.rs)'})
    try:
        with urllib.request.urlopen(req) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            pages = data['query']['pages']
            p_info = list(pages.values())[0]
            if 'imageinfo' in p_info and len(p_info['imageinfo']) > 0:
                img_url = p_info['imageinfo'][0]['url']
                out_path = os.path.join(PUBLIC_IMG_DIR, target_filename)
                print(f"Preuzimam ({title}): {img_url} -> {target_filename}")
                img_req = urllib.request.Request(img_url, headers={'User-Agent': 'ManastiriSrbijeBot/2.0 (ana@manastirisrbije.rs)'})
                with urllib.request.urlopen(img_req) as img_resp:
                    content = img_resp.read()
                    with open(out_path, 'wb') as f:
                        f.write(content)
                with Image.open(out_path) as img:
                    print(f"  ✓ Sačuvano ({img.size[0]}x{img.size[1]})")
                return True
    except Exception as e:
        print(f"Greška pri preuzimanju '{title}': {e}")
    return False

# Search queries
QUERIES = {
    'studenica_gal_3.jpg': 'Studenica Raspeće',
    'visoki-decani_gal_3.jpg': 'Visoki Dečani freska',
    'ravanica_gal_3.jpg': 'Knez Lazar Ravanica',
    'sopocani_gal_3.jpg': 'Sopoćani Uspenje',
    'zica_gal_2.jpg': 'Žiča manastir unutrašnjost',
    'gracanica_gal_3.jpg': 'Simonida Gračanica',
    'kovilj_gal_2.jpg': 'Kovilj manastir ikonostas'
}

for target_fn, q in QUERIES.items():
    titles = search_commons_files(q)
    print(f"\nUpit: '{q}' -> Pronađeni naslovi:")
    for t in titles:
        print(f"  - {t}")
    if titles:
        # Preuzmi prvi validan
        download_file_by_title(titles[0], target_fn)
