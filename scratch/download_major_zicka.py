import os
import urllib.request
import urllib.parse
import json
import time
from PIL import Image

headers = {'User-Agent': 'ManastiriSrbijeAcademicBot/1.0 (academic research project; contact@example.com)'}

def search_and_download(search_term, target_filename, min_width=600):
    url = f"https://commons.wikimedia.org/w/api.php?action=query&format=json&generator=search&gsrsearch={urllib.parse.quote(search_term)}&gsrlimit=10&prop=imageinfo&iiprop=url|size|mime"
    req = urllib.request.Request(url, headers=headers)
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
    except Exception as e:
        print(f"Error searching {search_term}: {e}")
        return False
    
    pages = data.get('query', {}).get('pages', {})
    for pid, pdata in pages.items():
        ii = pdata.get('imageinfo', [{}])[0]
        file_url = ii.get('url')
        mime = ii.get('mime', '')
        width = ii.get('width', 0)
        if not file_url or 'image' not in mime or width < min_width:
            continue
        
        # Download and test
        temp_path = 'scratch/temp_img.jpg'
        time.sleep(1.5)
        try:
            req_img = urllib.request.Request(file_url, headers=headers)
            with urllib.request.urlopen(req_img, timeout=15) as img_resp:
                with open(temp_path, 'wb') as f:
                    f.write(img_resp.read())
            
            with Image.open(temp_path) as im:
                im = im.convert('RGB')
                if im.width > 1920 or im.height > 1920:
                    im.thumbnail((1920, 1920), Image.Resampling.LANCZOS)
                target_path = os.path.join('public/images/monasteries', target_filename)
                im.save(target_path, 'JPEG', quality=88, optimize=True)
                print(f"  ✓ Saved {target_filename} ({im.width}x{im.height}) from {search_term}")
                return True
        except Exception as e:
            print(f"  ✗ Failed downloading {file_url}: {e}")
            continue
    return False

# Target downloads for major Žička monasteries
targets = [
    # Studenica
    ("Studenica Crucifixion fresco", "studenica_gal_4.jpg"),
    ("Studenica Virgin and Child fresco", "studenica_gal_5.jpg"),
    ("Studenica King church portal", "studenica_gal_6.jpg"),
    
    # Žiča
    ("Zica monastery north portal fresco", "zica_gal_3.jpg"),
    ("Zica monastery church interior rozeta", "zica_gal_4.jpg"),
    ("Zica monastery church of st peter and paul", "zica_gal_5.jpg"),
    
    # Gradac
    ("Gradac monastery Queen Helen of Anjou", "gradac_gal_4.jpg"),
    ("Gradac monastery interior fresco", "gradac_gal_5.jpg"),
    
    # Rača
    ("Raca monastery interior iconostasis", "raca_gal_4.jpg"),
    ("Raca monastery scriptorium Hadzi Melentije", "raca_gal_5.jpg"),
    
    # Vraćevšnica
    ("Vracevsnica monastery Radic Postupovic fresco", "vracevsnica_gal_3.jpg"),
    ("Vracevsnica monastery Georgije church cupola", "vracevsnica_gal_4.jpg"),
    
    # Nova Pavlica
    ("Nova Pavlica Music brothers fresco", "nova-pavlica_gal_4.jpg"),
    
    # Kovilje
    ("Kovilje monastery cave church interior", "kovilje_gal_4.jpg"),
]

for term, fn in targets:
    search_and_download(term, fn)
