import urllib.request
import urllib.parse
import json
import ssl

ctx = ssl.create_default_context()
ctx.check_hostname = False
ctx.verify_mode = ssl.CERT_NONE

url = "https://sr.wikipedia.org/w/api.php?action=query&titles=" + urllib.parse.quote("Манастир Прохор Пчињски") + "&prop=images&format=json"
req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)'})
try:
    with urllib.request.urlopen(req, context=ctx, timeout=10) as resp:
        content = resp.read().decode('utf-8')
        print("Response:")
        print(content)
except Exception as e:
    print(f"Error: {e}")
