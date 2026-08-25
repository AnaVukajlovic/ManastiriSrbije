import urllib.request
import urllib.parse
import sys

sys.stdout.reconfigure(encoding='utf-8')

headers = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36'
}

def download_special_filepath(filename, width=1280):
    clean = filename.replace('Датотека:', '').replace('File:', '').strip()
    encoded = urllib.parse.quote(clean)
    url = f"https://commons.wikimedia.org/wiki/Special:FilePath/{encoded}?width={width}"
    req = urllib.request.Request(url, headers=headers)
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = resp.read()
            print(f"[OK] {filename} -> {len(data)} bytes from {resp.geturl()[:80]}")
            return data
    except Exception as e:
        print(f"[ERROR] {filename}: {e}")
        return None

test_files = [
    'Wiki.Biseri V Manastir Krepičevac 61.jpg',
    'Wiki.Biseri V Manastir Krepičevac 67.jpg',
    'Manastir Lapušnja 1.jpg',
    'Wiki.Biseri V Manastir Lozica 144.jpg',
    'Suvodol 7839.JPG',
    'Manastir Ribnica 004.jpg',
    'Manastir Plužac 005.jpg'
]

for tf in test_files:
    download_special_filepath(tf)
