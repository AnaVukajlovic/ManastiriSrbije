import urllib.request
import re
import xml.etree.ElementTree as ET
import sqlite3
import unicodedata
import io
import sys
from bs4 import BeautifulSoup

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

def normalize_str(s):
    if not s:
        return ""
    s = s.lower().replace('đ', 'dj').replace('dž', 'dz')
    return "".join(c for c in unicodedata.normalize('NFKD', s) if not unicodedata.combining(c))

# 1. Fetch all sitemaps to get monastery URL map
sitemaps = [
    'https://manastiri.rs/page-sitemap1.xml',
    'https://manastiri.rs/page-sitemap2.xml',
    'https://manastiri.rs/page-sitemap3.xml',
    'https://manastiri.rs/post-sitemap1.xml',
    'https://manastiri.rs/post-sitemap2.xml',
]

ns = {'ns': 'http://www.sitemaps.org/schemas/sitemap/0.9'}
monastery_urls = []

for sm in sitemaps:
    try:
        req = urllib.request.Request(sm, headers={'User-Agent': 'Mozilla/5.0'})
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = resp.read()
            root = ET.fromstring(data)
            locs = [elem.text for elem in root.findall('.//ns:loc', ns)]
            for l in locs:
                if '/eparhije/' in l and l.strip('/').split('/')[-1].startswith('manastir-'):
                    monastery_urls.append(l)
    except Exception as e:
        print(f"Error {sm}: {e}")

print(f"Pronađeno manastirskih URL-ova na manastiri.rs: {len(monastery_urls)}")

# 2. Match with our database
conn = sqlite3.connect('storage/database.sqlite')
c = conn.cursor()
c.execute('SELECT id, name, slug, eparchy FROM monasteries ORDER BY id ASC')
monasteries = c.fetchall()
conn.close()

matched = 0
unmatched = []

for m_id, name, slug, eparchy in monasteries:
    norm_slug = normalize_str(slug).replace('manastir-', '')
    norm_name = normalize_str(name).replace('manastir', '').strip()
    
    # Try exact or partial match in URLs
    found_url = None
    for u in monastery_urls:
        last_segment = u.strip('/').split('/')[-1].replace('manastir-', '')
        if norm_slug == last_segment or norm_name == last_segment or norm_slug in last_segment or last_segment in norm_slug:
            found_url = u
            break
            
    if found_url:
        matched += 1
    else:
        unmatched.append((m_id, name, slug, eparchy))

print(f"Uspešno mapirano direktno: {matched} / {len(monasteries)}")
print(f"Za mapiranje ostalo: {len(unmatched)}")
if unmatched:
    print("Prvih 10 nemapiranih:")
    for u in unmatched[:10]:
        print(f"  ID {u[0]}: {u[1]} (slug: {u[2]})")
