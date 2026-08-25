import urllib.request
import re
import xml.etree.ElementTree as ET
import sqlite3
import io
import sys
from bs4 import BeautifulSoup

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

# Fetch page-sitemap.xml from manastiri.rs
sitemap_url = 'https://manastiri.rs/page-sitemap.xml'
req = urllib.request.Request(sitemap_url, headers={'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'})
with urllib.request.urlopen(req, timeout=10) as resp:
    xml_data = resp.read()

root = ET.fromstring(xml_data)
# Namespace map
ns = {'ns': 'http://www.sitemaps.org/schemas/sitemap/0.9'}

all_urls = [elem.text for elem in root.findall('.//ns:loc', ns)]
monastery_urls = [u for u in all_urls if '/eparhije/' in u]

print(f"Ukupno URL-ova u page-sitemap: {len(all_urls)}")
print(f"Ukupno manastirskih URL-ova (/eparhije/...): {len(monastery_urls)}")
print("\nPrvih 10 manastirskih URL-ova:")
for u in monastery_urls[:10]:
    print(f" - {u}")
