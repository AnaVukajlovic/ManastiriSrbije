import urllib.request
import re
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

url = "http://127.0.0.1:8000/manastiri?eparchy=eparhija-zicka"
html = urllib.request.urlopen(url).read().decode('utf-8')
titles = re.findall(r'<div class="monCard__title">([^<]+)</div>', html)
print('Eparhija žička monasteries count on page:', len(titles))
for t in titles[:6]:
    print('  •', t)
