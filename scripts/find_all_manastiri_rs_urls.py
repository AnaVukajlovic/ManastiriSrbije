import urllib.request
import re
import xml.etree.ElementTree as ET
import io
import sys

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

sitemaps = [
    'https://manastiri.rs/page-sitemap1.xml',
    'https://manastiri.rs/page-sitemap2.xml',
    'https://manastiri.rs/page-sitemap3.xml',
    'https://manastiri.rs/post-sitemap1.xml',
    'https://manastiri.rs/post-sitemap2.xml',
]

ns = {'ns': 'http://www.sitemaps.org/schemas/sitemap/0.9'}
all_monastery_urls = set()

for sm in sitemaps:
    try:
        req = urllib.request.Request(sm, headers={'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'})
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = resp.read()
            root = ET.fromstring(data)
            locs = [elem.text for elem in root.findall('.//ns:loc', ns)]
            for l in locs:
                if '/eparhije/' in l or 'manastir' in l:
                    all_monastery_urls.add(l)
            print(f"Loaded {sm}: {len(locs)} total URLs")
    except Exception as e:
        print(f"Error {sm}: {e}")

print(f"\nTotal unique monastery URLs discovered: {len(all_monastery_urls)}")
