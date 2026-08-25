import urllib.request
import os
import time

headers = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36'
}

urls = [
    ('https://upload.wikimedia.org/wikipedia/commons/9/9a/Manastir_Svete_Trojice_Kikinda_02.JPG', 'public/images/monasteries/sveta-trojica-kikinda_gal_1.jpg'),
    ('https://upload.wikimedia.org/wikipedia/commons/e/e8/%D0%97%D0%B2%D0%BE%D0%BD%D0%B8%D0%BA_%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80%D0%B0_%D0%A1%D0%B2%D0%B5%D1%82%D0%B5_%D0%A2%D1%80%D0%BE%D1%98%D0%B8%D1%86%D0%B5_%D1%83_%D0%9A%D0%B8%D0%BA%D0%B8%D0%BD%D0%B4%D0%B8.jpeg', 'public/images/monasteries/sveta-trojica-kikinda_gal_2.jpg')
]

for u, dest in urls:
    time.sleep(2)
    try:
        req = urllib.request.Request(u, headers=headers)
        with urllib.request.urlopen(req, timeout=15) as resp:
            if resp.status == 200:
                data = resp.read()
                with open(dest, 'wb') as f:
                    f.write(data)
                print(f"Downloaded {dest} ({len(data)} bytes)")
    except Exception as e:
        print(f"Error {u}: {e}")
