import urllib.request
from bs4 import BeautifulSoup
import re

headers = {'User-Agent': 'Mozilla/5.0'}

for path in ['sitemap.xml', 'eparhije/eparhija-timocka/', 'eparhije/eparhija-valjevska/', 'eparhije/']:
    url = f"https://manastiri.rs/{path}"
    try:
        req = urllib.request.Request(url, headers=headers)
        with urllib.request.urlopen(req, timeout=10) as resp:
            content = resp.read().decode('utf-8')
            soup = BeautifulSoup(content, 'html.parser')
            print(f"\n=== URL: {url} ===")
            links = [a['href'] for a in soup.find_all('a', href=True)]
            for l in links[:30]:
                print("  ", l)
    except Exception as e:
        print(f"Error {url}: {e}")
