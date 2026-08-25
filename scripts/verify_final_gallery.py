import urllib.request
import re
import json
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

test_slugs = ['bogorodica-ljeviska', 'studenica', 'tumane', 'manasija', 'zica', 'visoki-decani', 'gracanica', 'banjska', 'devic', 'beocin']

for slug in test_slugs:
    try:
        url = f"http://127.0.0.1:8000/manastiri/{slug}"
        html = urllib.request.urlopen(url).read().decode('utf-8')
        
        m_list = re.search(r'const monGalleryList = (\[.*?\]);', html, re.DOTALL)
        if m_list:
            gallery_json = json.loads(m_list.group(1))
            badge = re.search(r'<div class="monSideBannerPhoto__badge">\s*(.*?)\s*</div>', html)
            badge_text = badge.group(1) if badge else ""
            print(f"=== {slug.upper()} ===")
            print(f"  Badge: {badge_text}")
            print(f"  Broj slika: {len(gallery_json)}")
            for i, item in enumerate(gallery_json, 1):
                print(f"    [{i}] {item.get('caption')}")
                print(f"        URL: {item.get('url')[:70]}...")
        else:
            print(f"=== {slug.upper()} ===: Gallery list JS not found")
    except Exception as e:
        print(f"{slug}: Error {e}")
