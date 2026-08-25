import hashlib
import urllib.parse
import urllib.request
import sys

sys.stdout.reconfigure(encoding='utf-8')

def wikimedia_direct_url(filename):
    fn = filename.replace('Датотека:', '').replace('File:', '').strip().replace(' ', '_')
    h = hashlib.md5(fn.encode('utf-8')).hexdigest()
    encoded_fn = urllib.parse.quote(fn)
    return f"https://upload.wikimedia.org/wikipedia/commons/{h[0]}/{h[0:2]}/{encoded_fn}"

test_files = [
    'Wiki.Biseri V Manastir Krepičevac 61.jpg',
    'Wiki.Biseri V Manastir Krepičevac 67.jpg',
    'Manastir Lapušnja 1.jpg',
    'Wiki.Biseri V Manastir Lozica 144.jpg',
    'Suvodol 7839.JPG',
    'Manastir Ribnica 004.jpg',
    'Manastir Plužac 005.jpg'
]

headers = {'User-Agent': 'Mozilla/5.0'}
for tf in test_files:
    u = wikimedia_direct_url(tf)
    print(f"{tf}\n  -> {u}")
    try:
        req = urllib.request.Request(u, headers=headers)
        with urllib.request.urlopen(req, timeout=5) as resp:
            print(f"  [STATUS {resp.status}] size: {len(resp.read())} bytes")
    except Exception as e:
        print(f"  [ERROR] {e}")
