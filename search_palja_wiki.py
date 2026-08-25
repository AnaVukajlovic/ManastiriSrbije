import urllib.request
import urllib.parse
import json
import sys

sys.stdout.reconfigure(encoding='utf-8')
headers = {'User-Agent': 'ManastiriSrbijeEducationBot/1.0'}

# Search for Palja images on Wikimedia
query = "Манастир Паља"
api_url = f"https://commons.wikimedia.org/w/api.php?action=query&list=search&srsearch={urllib.parse.quote(query)}&srnamespace=6&format=json"
req = urllib.request.Request(api_url, headers=headers)
try:
    with urllib.request.urlopen(req) as resp:
        data = json.loads(resp.read().decode('utf-8'))
        print("Wikimedia search for Palja:")
        for r in data.get('query', {}).get('search', []):
            print(" -", r['title'])
except Exception as e:
    print(e)
