import urllib.request
import urllib.parse
import json
import sys

sys.stdout.reconfigure(encoding='utf-8')

headers = {'User-Agent': 'ManastiriSrbijeBot/1.0 (contact@manastirisrbije.rs)'}

monasteries = [
    ('grliste', 'Манастир Грлиште'),
    ('krepicevac', 'Манастир Крепичевац'),
    ('lapusnja', 'Манастир Лапушња'),
    ('lozica', 'Манастир Лозица'),
    ('vratna', 'Манастир Вратна'),
    ('suvodol', 'Манастир Суводол'),
    ('lelic', 'Манастир Лелић'),
    ('bogovadja', 'Манастир Боговађа'),
    ('dokmir', 'Манастир Докмир'),
    ('grabovac', 'Манастир Грабовац (Обреновац)'),
    ('ribnica', 'Манастир Рибница'),
    ('pluzac', 'Манастир Плужац'),
    ('jovanja', 'Манастир Јовања (код Ваљева)')
]

def get_wiki_images(title):
    params = {
        'action': 'query',
        'titles': title,
        'prop': 'images',
        'format': 'json'
    }
    url = f"https://sr.wikipedia.org/w/api.php?{urllib.parse.urlencode(params)}"
    req = urllib.request.Request(url, headers=headers)
    try:
        with urllib.request.urlopen(req, timeout=10) as resp:
            data = json.loads(resp.read().decode('utf-8'))
            pages = data.get('query', {}).get('pages', {})
            images = []
            for pid, pdata in pages.items():
                for im in pdata.get('images', []):
                    im_title = im.get('title', '')
                    if not any(x in im_title.lower() for x in ['icon', 'stub', 'flag', 'portal', 'geograph', 'map']):
                        images.append(im_title)
            return images
    except Exception as e:
        print(f"Error {title}: {e}")
        return []

def get_image_url(image_title):
    params = {
        'action': 'query',
        'titles': image_title,
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
                imageinfo = pdata.get('imageinfo', [{}])[0]
                return imageinfo.get('url', '')
    except Exception as e:
        return ''

for slug, title in monasteries:
    imgs = get_wiki_images(title)
    print(f"\n=== {title} ({slug}) - {len(imgs)} images ===")
    for im in imgs:
        url = get_image_url(im)
        print(f"  - {im} -> {url}")
