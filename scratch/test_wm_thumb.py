import urllib.request
import urllib.parse
import hashlib
import sys

sys.stdout.reconfigure(encoding='utf-8')

# Wikimedia requires a distinct User-Agent with contact/app info
headers = {
    'User-Agent': 'ManastiriSrbijeApp/1.0 (https://github.com/AnaVukajlovic/ManastiriSrbije; anavukajlovic@example.com)'
}

def wikimedia_thumb_url(filename, width=1280):
    fn = filename.replace('Датотека:', '').replace('File:', '').strip().replace(' ', '_')
    h = hashlib.md5(fn.encode('utf-8')).hexdigest()
    encoded_fn = urllib.parse.quote(fn)
    # Thumbnail URL pattern:
    # https://upload.wikimedia.org/wikipedia/commons/thumb/h[0]/h[0:2]/filename/widthpx-filename
    return f"https://upload.wikimedia.org/wikipedia/commons/thumb/{h[0]}/{h[0:2]}/{encoded_fn}/{width}px-{encoded_fn}"

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
    u = wikimedia_thumb_url(tf)
    print(f"{tf}\n  -> {u}")
    try:
        req = urllib.request.Request(u, headers=headers)
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = resp.read()
            print(f"  [STATUS {resp.status}] size: {len(data)} bytes")
    except Exception as e:
        print(f"  [ERROR] {e}")
