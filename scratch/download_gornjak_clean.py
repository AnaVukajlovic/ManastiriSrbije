import urllib.request
import os

headers = {'User-Agent': 'ManastiriSrbijeBot/1.0 (contact@manastirisrbije.rs)'}

downloads = [
    (
        "https://upload.wikimedia.org/wikipedia/commons/9/99/Manastir_Gornjak_%E2%80%93_%C4%8Duvar_klisure.jpg",
        "public/images/monasteries/gornjak_gal_1.jpg"
    ),
    (
        "https://upload.wikimedia.org/wikipedia/commons/d/d1/007_-_Manastir_Gornjak.jpg",
        "public/images/monasteries/gornjak_gal_3.jpg"
    )
]

for url, dest in downloads:
    print(f"Downloading {url} to {dest}...")
    req = urllib.request.Request(url, headers=headers)
    with urllib.request.urlopen(req) as resp, open(dest, 'wb') as f:
        f.write(resp.read())
    print(f"Done: {dest}, size: {os.path.getsize(dest)} bytes")
