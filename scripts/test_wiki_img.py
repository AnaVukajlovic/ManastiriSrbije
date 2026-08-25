import urllib.request
import urllib.parse
import json
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

def test_wiki_images(title):
    headers = {
        'User-Agent': 'PravoslavniSvetionik/1.0 (https://github.com/manastiri-srbije; kontakt@svetionik.org.rs)'
    }
    url = f"https://sr.wikipedia.org/w/api.php?action=query&titles={urllib.parse.quote(title)}&prop=pageimages|images&pithumbsize=1000&imlimit=20&format=json"
    req = urllib.request.Request(url, headers=headers)
    data = json.loads(urllib.request.urlopen(req, timeout=8).read().decode('utf-8'))
    pages = data.get('query', {}).get('pages', {})
    for pid, p in pages.items():
        print(f"Title: {p.get('title')}")
        if 'thumbnail' in p:
            print(f"  Main Thumbnail: {p['thumbnail']['source']}")
        img_titles = [im['title'] for im in p.get('images', []) if not any(x in im['title'].lower() for x in ['icon', 'logo', 'flag', 'coat_of_arms', 'symbol', 'stub', 'question', 'portal', 'pd-icon', 'red_pog', 'commons-logo', 'ambox'])]
        print(f"  Found {len(img_titles)} image files: {img_titles[:5]}")
        
        # Resolve the image files to URLs
        if img_titles:
            file_param = '|'.join(img_titles[:5])
            furl = f"https://sr.wikipedia.org/w/api.php?action=query&titles={urllib.parse.quote(file_param)}&prop=imageinfo&iiprop=url&format=json"
            freq = urllib.request.Request(furl, headers=headers)
            fdata = json.loads(urllib.request.urlopen(freq, timeout=8).read().decode('utf-8'))
            for fpid, fp in fdata.get('query', {}).get('pages', {}).items():
                ii = fp.get('imageinfo', [])
                if ii:
                    print(f"    -> Resolved Image: {fp.get('title')} => {ii[0].get('url')}")

print("=== BOGORODICA LJEVIŠKA ===")
test_wiki_images("Богородица Љевишка")

print("\n=== STUDENICA ===")
test_wiki_images("Манастир Студеница")

print("\n=== TUMANE ===")
test_wiki_images("Манастир Тумане")
