import urllib.request
import urllib.parse
from bs4 import BeautifulSoup
import re
import json

headers = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/115.0.0.0 Safari/537.36'
}

def search_manastiri_rs(query):
    url = f"https://manastiri.rs/?s={urllib.parse.quote(query)}"
    req = urllib.request.Request(url, headers=headers)
    try:
        with urllib.request.urlopen(req, timeout=10) as response:
            html = response.read().decode('utf-8')
            soup = BeautifulSoup(html, 'html.parser')
            # find articles or links
            links = []
            for a in soup.find_all('a', href=True):
                href = a['href']
                if 'manastiri.rs/manastir-' in href or 'manastiri.rs/' in href:
                    if href not in links and href != 'https://manastiri.rs/' and not 'category' in href and not 'page' in href:
                        links.append((href, a.get_text(strip=True)))
            return links[:5]
    except Exception as e:
        return f"Error: {e}"

monasteries = ['grliste', 'krepicevac', 'lapusnja', 'lozica', 'vratna', 'suvodol', 'lelic', 'bogovadja', 'dokmir', 'grabovac', 'ribnica', 'pluzac', 'jovanja']

for m in monasteries:
    res = search_manastiri_rs(m)
    print(f"Query '{m}': {res}")
