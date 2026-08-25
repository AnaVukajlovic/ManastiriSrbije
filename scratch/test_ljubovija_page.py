import urllib.request
import re
import sys

sys.stdout.reconfigure(encoding='utf-8')

url = 'http://127.0.0.1:8000/manastiri/ljubovija'
with urllib.request.urlopen(url) as r:
    html = r.read().decode('utf-8')
    print('Page HTTP code:', r.status)
    print('Contains <смалл>:', '<смалл>' in html)
    
    captions = re.findall(r'<p class="monGalleryModal__caption"[^>]*>(.*?)</p>', html, re.DOTALL)
    print('\nGallery Captions:')
    for i, c in enumerate(captions, 1):
        print(f'{i}. {c.strip()}')
