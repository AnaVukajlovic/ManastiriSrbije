import urllib.request
from bs4 import BeautifulSoup

headers = {'User-Agent': 'Mozilla/5.0'}

for slug in ['grliste', 'krepicevac', 'lapusnja', 'lozica', 'suvodol', 'ribnica', 'pluzac']:
    url = f"https://manastiri.rs/eparhije/{'timocka' if slug in ['grliste', 'krepicevac', 'lapusnja', 'lozica', 'suvodol'] else 'valjevska'}/manastir-{slug}/"
    try:
        req = urllib.request.Request(url, headers=headers)
        with urllib.request.urlopen(req, timeout=10) as resp:
            html = resp.read().decode('utf-8')
            soup = BeautifulSoup(html, 'html.parser')
            print(f"\n[{slug}] Status 200, length: {len(html)}")
            # print all img tags
            imgs = soup.find_all('img')
            print(f"  All img tags ({len(imgs)}):")
            for img in imgs:
                print("    ", img.attrs)
            # check content
            entry = soup.find('div', class_='entry-content') or soup.find('article') or soup.find('main')
            if entry:
                print("  Entry text preview:", entry.get_text()[:200].replace('\n', ' '))
    except Exception as e:
        print(f"[{slug}] Error: {e}")
