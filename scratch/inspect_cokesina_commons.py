import urllib.request
import urllib.parse
import json
import sys

sys.stdout.reconfigure(encoding='utf-8')

for i in [1, 2, 4, 6, 8, 10, 15, 20, 25, 29]:
    title = f"File:Manastir Čokešina {i:03d}.jpg"
    api_url = f"https://commons.wikimedia.org/w/api.php?action=query&titles={urllib.parse.quote(title)}&prop=imageinfo&iiprop=url|size|comment&format=json"
    req = urllib.request.Request(api_url, headers={'User-Agent': 'MonasteryApp/1.0'})
    with urllib.request.urlopen(req) as resp:
        data = json.loads(resp.read().decode('utf-8'))
        for pid in data['query']['pages']:
            if 'imageinfo' in data['query']['pages'][pid]:
                info = data['query']['pages'][pid]['imageinfo'][0]
                print(f"{title}: {info['width']}x{info['height']}, {info.get('comment', '')}")
