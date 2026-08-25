import urllib.request
import os
import time

headers = {"User-Agent": "ManastiriSrbijeResearch/1.0 (https://manastirisrbije.rs; contact@manastirisrbije.rs) python-urllib"}

failed_downloads = [
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/5/54/Stephan_Dusan_Coronation_Paja_Jovanovic.png",
        "dest": "public/images/ktitors/car-dusan-3.png",
        "slug": "car-dusan"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/4/40/Stefan_Uro%C5%A1_V_Psaca_fresco.JPG",
        "dest": "public/images/ktitors/uros-nejaki.jpg",
        "slug": "uros-nejaki"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/b/b7/Stefan_Uro%C5%A1_V_and_King_Vuka%C5%A1in.JPG",
        "dest": "public/images/ktitors/uros-nejaki-2.jpg",
        "slug": "uros-nejaki"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/b/bb/Sava_of_Serbia_Mile%C5%A1evo.JPG",
        "dest": "public/images/ktitors/sveti-sava-2.jpg",
        "slug": "sveti-sava"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/0/05/Sveti_Sava_Kraljeva_Crkva.jpg",
        "dest": "public/images/ktitors/sveti-sava-3.jpg",
        "slug": "sveti-sava"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/7/74/Death_of_Queen_Ana_Dondolo%2C_Sopocani_monastery%2C_1272-74.jpg",
        "dest": "public/images/ktitors/ana-dandolo-2.jpg",
        "slug": "ana-dandolo"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/0/00/Manastir_Studenica%2C_Sveta_Anastasija_Srpska_pred_Presvetom_Bogorodicom_iz_1568._godine.jpg",
        "dest": "public/images/ktitors/ana-zena-stefana-nemanje-2.jpg",
        "slug": "ana-zena-stefana-nemanje"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/4/44/Car_Du%C5%A1an_i_carica_Jelena%2C_Manastir_Lesnovo%2C_XIV_vek.jpg",
        "dest": "public/images/ktitors/carica-jelena-2.jpg",
        "slug": "carica-jelena"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/f/fe/Simonida_Kraljeva.jpg",
        "dest": "public/images/ktitors/simonida-2.jpg",
        "slug": "simonida"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/d/df/Simonida_Gracanica_lik.jpg",
        "dest": "public/images/ktitors/simonida-3.jpg",
        "slug": "simonida"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/3/3b/Vukan_Nemanji%C4%87%2C_Studenica.jpg",
        "dest": "public/images/ktitors/vukan-nemanjic-2.jpg",
        "slug": "vukan-nemanjic"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/8/8f/Vukan%27s_Gospel%2C_miniature.jpg",
        "dest": "public/images/ktitors/vukan-nemanjic-3.jpg",
        "slug": "vukan-nemanjic"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/9/92/Serbian_Queen_Helen_of_Anjou_Nemanjic_Sopocani_Monastery.jpg",
        "dest": "public/images/ktitors/jelena-anzujska-2.jpg",
        "slug": "jelena-anzujska"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/6/67/Lazar_i_Milica_Ljubostinja1.jpg",
        "dest": "public/images/ktitors/kneginja-milica-2.jpg",
        "slug": "kneginja-milica"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/3/3e/Stefan_Manasija_%281415-1418%29.jpg",
        "dest": "public/images/ktitors/stefan-lazarevic-2.jpg",
        "slug": "stefan-lazarevic"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/7/7b/Stefan_Lazarevic-freska.JPG",
        "dest": "public/images/ktitors/stefan-lazarevic-3.jpg",
        "slug": "stefan-lazarevic"
    }
]

for item in failed_downloads:
    dest_path = item["dest"]
    print(f"Downloading {item['dest']}...")
    retries = 3
    success = False
    while retries > 0 and not success:
        try:
            time.sleep(2.5) # pause to avoid 429
            req = urllib.request.Request(item["url"], headers=headers)
            with urllib.request.urlopen(req, timeout=30) as resp:
                data = resp.read()
                with open(dest_path, "wb") as f:
                    f.write(data)
                print(f"  OK ({len(data)} bytes) -> {dest_path}")
                success = True
        except Exception as e:
            print(f"  Error: {e}, retrying in 5 seconds...")
            time.sleep(5)
            retries -= 1
    if not success:
        print(f"  FAILED permanently: {dest_path}")

print("\nFinished retry script.")
