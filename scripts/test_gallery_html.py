import urllib.request
import re
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

slugs = ['mileseva', 'studenica', 'tumane', 'zica', 'manasija', 'visoki-decani', 'gracanica', 'bogorodica-ljeviska', 'beocin', 'krusedol']

for slug in slugs:
    try:
        html = urllib.request.urlopen(f'http://127.0.0.1:8000/manastiri/{slug}').read().decode('utf-8')
        main_match = re.search(r'id="sideMainBannerImg"[^>]*src="([^"]+)"', html)
        main_img = main_match.group(1) if main_match else 'N/A'
        
        # Find all thumbnail image sources
        thumbs = re.findall(r'<button[^>]*class="monSideThumbItem[^"]*"[^>]*>.*?<img[^>]*src="([^"]+)"', html, re.DOTALL)
        
        badge_match = re.search(r'<div class="monSideBannerPhoto__badge">\s*(.*?)\s*</div>', html)
        badge = badge_match.group(1) if badge_match else ''
        
        print(f"\n=== {slug.upper()} ===")
        print(f"  Glavna slika: {main_img}")
        print(f"  Badge: {badge}")
        print(f"  Broj sličica: {len(thumbs)}")
        for i, t in enumerate(thumbs, 1):
            print(f"    [{i}] {t}")
    except Exception as e:
        print(f"Error {slug}: {e}")
