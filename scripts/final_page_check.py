import urllib.request
import re
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

for slug in ['bogorodica-ljeviska', 'studenica', 'manasija', 'decani', 'visoki-decani', 'tumane', 'zica']:
    try:
        url = f"http://127.0.0.1:8000/manastiri/{slug}"
        html = urllib.request.urlopen(url).read().decode('utf-8')
        print(f"\n=== STRANICA: {slug.upper()} ===")
        blocks = re.findall(r'<h3 class="monBookBlock__title">([^<]+)</h3>\s*<p class="monBookBlock__text">([^<]+)</p>', html)
        print(f"  Pronađeno sekcija: {len(blocks)}")
        for title, text in blocks:
            print(f"    - [{title}]: {text[:100]}...")
            
        badge = re.search(r'<div class="monSideBannerPhoto__badge">\s*([^<]+)\s*</div>', html)
        if badge:
            print(f"  Badge: {badge.group(1).strip()}")
    except Exception as e:
        print(f"  {slug}: {e}")
