import urllib.request
import json
import os

headers = {'User-Agent': 'ManastiriSrbijeBot/1.0 (contact@manastirisrbije.rs)'}

candidates = [
    ("Manastir-Gornjak-2.jpg", "https://upload.wikimedia.org/wikipedia/commons/8/8f/Manastir-Gornjak-2.jpg"),
    ("005_-_Manastir_Gornjak.jpg", "https://upload.wikimedia.org/wikipedia/commons/4/48/005_-_Manastir_Gornjak.jpg"),
    ("Manastir_Gornjak.JPG", "https://upload.wikimedia.org/wikipedia/commons/8/84/Manastir_Gornjak.JPG"),
    ("Gornjak_biser.jpg", "https://upload.wikimedia.org/wikipedia/commons/0/01/Manastir_Gornjak_%E2%80%93_biser_skriven_u_zelenilu.jpg"),
    ("Zaledje_250.jpg", "https://upload.wikimedia.org/wikipedia/commons/6/68/Wiki.Zale%C4%91e_III_Gornjak_Monastery_250.jpg")
]

os.makedirs("scratch/gornjak_candidates", exist_ok=True)

for name, url in candidates:
    dest = f"scratch/gornjak_candidates/{name}"
    print(f"Downloading {name}...")
    try:
        req = urllib.request.Request(url, headers=headers)
        with urllib.request.urlopen(req) as resp, open(dest, 'wb') as f:
            f.write(resp.read())
        print(f"Saved {dest}, size: {os.path.getsize(dest)}")
    except Exception as e:
        print(f"Error {name}: {e}")
