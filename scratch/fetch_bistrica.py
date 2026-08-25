import urllib.request
import re

url = 'https://milesevskaeparhija.rs/manastir-svete-trojice-u-bistrici/'
req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
try:
    with urllib.request.urlopen(req) as resp:
        html = resp.read().decode('utf-8', errors='ignore')
        imgs = re.findall(r'<img[^>]+src=["\']([^"\']+)["\']', html)
        for img in imgs:
            print("Found:", img)
except Exception as e:
    print("Error:", e)
