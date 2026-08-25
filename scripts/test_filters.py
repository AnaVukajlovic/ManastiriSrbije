import urllib.request
import re
import sys
import io

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

for s in ['popular', 'name', 'new']:
    url = f"http://127.0.0.1:8000/manastiri?sort={s}"
    html = urllib.request.urlopen(url).read().decode('utf-8')
    titles = re.findall(r'<div class="monCard__title">([^<]+)</div>', html)
    print(f"\n=== SORT: {s.upper()} (Prikaz prvih 6 manastira) ===")
    for t in titles[:6]:
        print(f"  • {t}")

print("\n=== PROVERA FILTRIRANJA PO EPARHIJI (zicka) ===")
url_ep = "http://127.0.0.1:8000/manastiri?eparchy=zicka"
html_ep = urllib.request.urlopen(url_ep).read().decode('utf-8')
titles_ep = re.findall(r'<div class="monCard__title">([^<]+)</div>', html_ep)
for t in titles_ep[:5]:
    print(f"  • {t}")

print("\n=== PROVERA FILTRIRANJA PO REGIONU (Fruska gora) ===")
url_reg = "http://127.0.0.1:8000/manastiri?region=Fru%C5%A1ka+gora"
html_reg = urllib.request.urlopen(url_reg).read().decode('utf-8')
titles_reg = re.findall(r'<div class="monCard__title">([^<]+)</div>', html_reg)
for t in titles_reg[:5]:
    print(f"  • {t}")
