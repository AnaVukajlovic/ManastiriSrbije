import urllib.request
import urllib.parse
import json
import re
from PIL import Image
import os
import sys

sys.stdout.reconfigure(encoding='utf-8')

headers = {'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'}

def fetch_sr_wiki_thumb(page_title, file_keyword, target_filename):
    url = f"https://sr.wikipedia.org/w/api.php?action=query&titles={urllib.parse.quote(page_title)}&generator=images&gimlimit=30&prop=imageinfo&iiprop=url|size&format=json"
    req = urllib.request.Request(url, headers=headers)
    try:
        with urllib.request.urlopen(req, timeout=15) as resp:
            data = json.loads(resp.read().decode('utf-8'))
        pages = data.get('query', {}).get('pages', {})
        for pid, pdata in pages.items():
            title = pdata.get('title', '')
            if file_keyword.lower() in title.lower():
                img_url = pdata['imageinfo'][0]['url']
                print(f"Found image: {title} -> {img_url}")
                # Download
                req_img = urllib.request.Request(img_url, headers=headers)
                temp_p = f"scratch/temp_{target_filename}"
                with urllib.request.urlopen(req_img, timeout=20) as img_resp:
                    with open(temp_p, 'wb') as f:
                        f.write(img_resp.read())
                with Image.open(temp_p) as im:
                    im = im.convert('RGB')
                    if im.width > 1920 or im.height > 1920:
                        im.thumbnail((1920, 1920), Image.Resampling.LANCZOS)
                    target_p = os.path.join('public/images/monasteries', target_filename)
                    im.save(target_p, 'JPEG', quality=88, optimize=True)
                    print(f"  ✓ Saved {target_filename} ({im.width}x{im.height})")
                if os.path.exists(temp_p):
                    os.remove(temp_p)
                return True
    except Exception as e:
        print(f"Error for {page_title}: {e}")
    return False

# 1. Studenica: King's church
fetch_sr_wiki_thumb("Манастир_Студеница", "Kraljeva", "studenica_gal_1.jpg") or fetch_sr_wiki_thumb("Манастир_Студеница", "Studenica", "studenica_gal_1.jpg")

# 2. Vraćevšnica: Church or courtyard
fetch_sr_wiki_thumb("Манастир_Враћевшница", "Djordja", "vracevsnica_gal_3.jpg") or fetch_sr_wiki_thumb("Манастир_Враћевшница", "Vraćevšnica", "vracevsnica_gal_3.jpg")

# 3. Stara Pavlica: View or bifora
fetch_sr_wiki_thumb("Манастир_Стара_Павлица", "Pavlica", "stara-pavlica_gal_2.jpg")
