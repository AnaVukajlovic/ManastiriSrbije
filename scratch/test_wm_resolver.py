import urllib.request
import urllib.parse
import json
import sys

sys.stdout.reconfigure(encoding='utf-8')

headers = {'User-Agent': 'ManastiriSrbijeBot/1.0 (contact@manastirisrbije.rs)'}

def get_wikimedia_file_url(filename):
    clean_name = filename.replace('Датотека:', 'File:').replace('Фајл:', 'File:')
    if not clean_name.startswith('File:'):
        clean_name = 'File:' + clean_name
    params = {
        'action': 'query',
        'titles': clean_name,
        'prop': 'imageinfo',
        'iiprop': 'url|size|mime',
        'format': 'json'
    }
    url = f"https://commons.wikimedia.org/w/api.php?{urllib.parse.urlencode(params)}"
    req = urllib.request.Request(url, headers=headers)
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            pages = data.get('query', {}).get('pages', {})
            for pid, pdata in pages.items():
                if 'imageinfo' in pdata:
                    return pdata['imageinfo'][0]['url'], pdata['imageinfo'][0].get('width', 0), pdata['imageinfo'][0].get('height', 0)
    except Exception as e:
        print(f"Error {filename}: {e}")
    return None, 0, 0

# Test a few files
test_files = [
    'Wiki.Biseri III Grlište Monastery 955.jpg',
    'Wiki.Biseri III Grlište Monastery 958.jpg',
    'Manastir Krepičevac kod Jablanice, Boljevac.jpg',
    'Wiki.Biseri V Manastir Krepičevac 61.jpg',
    'Manastir Lapušnja.jpg',
    'Wiki.Zaleđe IV Vratna Monastery 345.jpg',
    'Suvodol 7839.JPG',
    'Manastir Lelić, Valjevo, 012.jpg',
    'Bogovađa, Manastir Bogovađa, 06.jpg',
    'Manastir Dokmir 005.jpg',
    'Manastir Plužac 005.jpg'
]

for tf in test_files:
    u, w, h = get_wikimedia_file_url(tf)
    print(f"{tf} -> {w}x{h} | {u}")
