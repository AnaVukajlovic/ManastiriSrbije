import urllib.request
import urllib.parse
import json
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

HEADERS = {
    'User-Agent': 'ManastiriSrbijeResearchBot/1.0 (https://manastirisrbije.rs; contact@manastirisrbije.rs) python-requests'
}

def get_wikimedia_url(file_title):
    # file_title like "File:Manastir Rakovica, unutrašnjost crkve i ikonostas.jpg"
    api_url = f"https://commons.wikimedia.org/w/api.php?action=query&titles={urllib.parse.quote(file_title)}&prop=imageinfo&iiprop=url|size|mime&format=json"
    req = urllib.request.Request(api_url, headers=HEADERS)
    try:
        with urllib.request.urlopen(req, timeout=10) as r:
            data = json.loads(r.read().decode('utf-8'))
            pages = data.get('query', {}).get('pages', {})
            for pid, pdata in pages.items():
                infos = pdata.get('imageinfo', [])
                if infos:
                    return infos[0].get('url')
    except Exception as e:
        print(f"Error {file_title}: {e}")
    return None

test_files = [
    'File:Manastir Rakovica, unutrašnjost crkve i ikonostas.jpg',
    'File:Manastir Rakovica - 28 04 2018 02.jpg',
    'File:Manastirrakovica1.JPG',
    'File:Ковиљски Манастир Светих Архангела Михаила и Гаврила.jpg',
    'File:Manastir Kovilj u rano jutro.jpg',
    'File:Manastir Rajinovac 1.jpg',
    'File:Manastir Rajinovac 2.jpg'
]

for tf in test_files:
    u = get_wikimedia_url(tf)
    print(f"• {tf} -> {u}")
