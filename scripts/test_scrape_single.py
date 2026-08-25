import urllib.request
import re
from bs4 import BeautifulSoup
import io
import sys

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

# Test fetching Manastir Studenica or Manastirica from manastiri.rs
test_url = 'https://manastiri.rs/eparhije/zicka/manastir-studenica/'
try:
    req = urllib.request.Request(test_url, headers={'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'})
    with urllib.request.urlopen(req, timeout=10) as resp:
        html = resp.read().decode('utf-8')
        soup = BeautifulSoup(html, 'html.parser')
        
        title = soup.find('h1')
        print(f"Title: {title.text if title else 'No H1'}")
        
        # Get content paragraphs
        paragraphs = [p.text.strip() for p in soup.find_all('p') if len(p.text.strip()) > 30]
        print(f"\nFound {len(paragraphs)} paragraphs. First 4:")
        for idx, p in enumerate(paragraphs[:4], 1):
            print(f"[{idx}] {p}\n")
except Exception as e:
    print(f"Error fetching {test_url}: {e}")
