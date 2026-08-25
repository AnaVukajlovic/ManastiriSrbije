import urllib.request
import urllib.parse
import json
import ssl

ctx = ssl.create_default_context()
ctx.check_hostname = False
ctx.verify_mode = ssl.CERT_NONE

HEADERS = {'User-Agent': 'PravoslavniSvetionikBot/1.0 (contact@pravoslavnisvetionik.rs)'}

file_title = "Датотека:Manastir Svetog Prohora Pčinjskog (3).jpg"
commons_title = file_title.replace("Датотека:", "File:")

url = f"https://commons.wikimedia.org/w/api.php?action=query&titles={urllib.parse.quote(commons_title)}&prop=imageinfo&iiprop=url|size|extmetadata&format=json"
req = urllib.request.Request(url, headers=HEADERS)
with urllib.request.urlopen(req, context=ctx) as resp:
    data = json.loads(resp.read().decode('utf-8'))
    print(json.dumps(data, ensure_ascii=False, indent=2))
