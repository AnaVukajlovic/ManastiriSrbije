import urllib.request
import json
import sys

sys.stdout.reconfigure(encoding='utf-8')
headers = {'User-Agent': 'ManastiriSrbijeBot/1.0'}
url = "https://commons.wikimedia.org/w/api.php?action=query&list=categorymembers&cmtitle=Category:Helen_of_Anjou&cmtype=file&format=json"
req = urllib.request.Request(url, headers=headers)
with urllib.request.urlopen(req) as resp:
    data = json.loads(resp.read().decode('utf-8'))
    for m in data.get('query', {}).get('categorymembers', []):
        print(m['title'])
