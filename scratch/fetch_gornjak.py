import urllib.request
import json
import os

headers = {'User-Agent': 'ManastiriSrbijeBot/1.0 (contact@manastirisrbije.rs)'}

def search_files(query):
    url = f'https://commons.wikimedia.org/w/api.php?action=query&list=search&srsearch={urllib.parse.quote(query)}&srnamespace=6&format=json&srlimit=20'
    req = urllib.request.Request(url, headers=headers)
    with urllib.request.urlopen(req) as resp:
        data = json.loads(resp.read().decode('utf-8'))
        return [item['title'] for item in data['query']['search']]

def get_image_url(title):
    url = f'https://commons.wikimedia.org/w/api.php?action=query&titles={urllib.parse.quote(title)}&prop=imageinfo&iiprop=url&format=json'
    req = urllib.request.Request(url, headers=headers)
    with urllib.request.urlopen(req) as resp:
        data = json.loads(resp.read().decode('utf-8'))
        pages = data['query']['pages']
        for p in pages.values():
            if 'imageinfo' in p:
                return p['imageinfo'][0]['url']
    return None

files = search_files("Manastir Gornjak")
print("Found files:", len(files))
for f in files:
    img_url = get_image_url(f)
    print(f.encode('ascii', 'replace').decode('ascii'), "->", img_url)
