import urllib.request
import re
import io
import sys

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

# Try sitemap
sitemap_urls = [
    'https://manastiri.rs/sitemap.xml',
    'https://manastiri.rs/sitemap_index.xml',
    'https://manastiri.rs/post-sitemap.xml',
    'https://manastiri.rs/page-sitemap.xml',
    'https://manastiri.rs/manastiri-sitemap.xml'
]

for sm in sitemap_urls:
    try:
        req = urllib.request.Request(sm, headers={'User-Agent': 'Mozilla/5.0'})
        with urllib.request.urlopen(req, timeout=5) as resp:
            content = resp.read().decode('utf-8')
            print(f"Sitemap found: {sm} ({len(content)} bytes)")
            urls = re.findall(r'<loc>(.*?)</loc>', content)
            print(f"  First 5 URLs: {urls[:5]}")
    except Exception as e:
        print(f"No sitemap at {sm}: {e}")
