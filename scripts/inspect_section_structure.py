import urllib.request
from bs4 import BeautifulSoup
import io
import sys

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

url = 'https://manastiri.rs/eparhije/branicevska/manastir-manasija/'
req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
with urllib.request.urlopen(req) as resp:
    html = resp.read().decode('utf-8', errors='ignore')
    soup = BeautifulSoup(html, 'html.parser')
    
    for h in soup.find_all(['h2', 'h3']):
        print(f"Heading: {h.text.strip()}")
        # Print following siblings until next heading
        curr = h.next_sibling
        content = []
        while curr and getattr(curr, 'name', None) not in ['h2', 'h3']:
            if getattr(curr, 'name', None) == 'p':
                t = curr.text.strip()
                if len(t) > 20:
                    content.append(t)
            curr = curr.next_sibling
        print(f"  Paragraphs count: {len(content)}")
        for p in content[:2]:
            print(f"    - {p[:120]}...")
