import urllib.request
import urllib.parse
import json
import time
import ssl

ctx = ssl.create_default_context()
ctx.check_hostname = False
ctx.verify_mode = ssl.CERT_NONE

HEADERS = {
    'User-Agent': 'PravoslavniSvetionikBot/1.0 (https://pravoslavnisvetionik.rs; contact@pravoslavnisvetionik.rs) Python-urllib'
}

def query_wiki_page(title):
    url = f"https://sr.wikipedia.org/w/api.php?action=query&titles={urllib.parse.quote(title)}&prop=images|pageimages&piprop=original&format=json"
    req = urllib.request.Request(url, headers=HEADERS)
    try:
        with urllib.request.urlopen(req, context=ctx, timeout=10) as resp:
            return json.loads(resp.read().decode('utf-8'))
    except Exception as e:
        print(f"Error {title}: {e}")
        return None

res = query_wiki_page("Манастир Прохор Пчињски")
print("Prohor Pčinjski:", json.dumps(res, ensure_ascii=False)[:300])

time.sleep(1.5)
res2 = query_wiki_page("Манастир Кацапун")
print("Kacapun:", json.dumps(res2, ensure_ascii=False)[:300])
