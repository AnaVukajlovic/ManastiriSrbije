import urllib.request
import os
import json

headers = {"User-Agent": "ManastiriSrbijeBot/1.0 (academic research; contact@manastirisrbije.rs)"}

downloads = [
    # Stefan Nemanja
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/9/98/Nemanja%27s_Council_fresco_Arilje.jpg",
        "dest": "public/images/ktitors/stefan-nemanja-2.jpg",
        "slug": "stefan-nemanja"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/2/22/Stefan_Nemanja%2C_narthex%2C_GRACANICA_1_090A8435.jpg",
        "dest": "public/images/ktitors/stefan-nemanja-3.jpg",
        "slug": "stefan-nemanja"
    },
    # Stefan Prvovencani
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/d/df/King_Stefan_Prvoven%C4%8Dani_PRIZREN_1_668A9024-3_-_Bogorodica_Ljevi%C5%A1ka.jpg",
        "dest": "public/images/ktitors/stefan-prvovencani-2.jpg",
        "slug": "stefan-prvovencani"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/8/8f/King_Stefan_the_First-crowned%2C_Ljubostinja_monastery.jpg",
        "dest": "public/images/ktitors/stefan-prvovencani-3.jpg",
        "slug": "stefan-prvovencani"
    },
    # Stefan Radoslav (zamena glavne + druga slika)
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/8/80/KING_RADOSLAV_OF_SERBIA_Fresco.jpg",
        "dest": "public/images/ktitors/stefan-radoslav.jpg",
        "slug": "stefan-radoslav"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/2/25/King_of_Serbia_Stefan_Radoslav_Nemanjic.png",
        "dest": "public/images/ktitors/stefan-radoslav-2.jpg",
        "slug": "stefan-radoslav"
    },
    # Stefan Vladislav (zamena glavne + druga slika)
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/e/e5/Fresco_of_Stefan_Vladislav%2C_Mile%C5%A1eva%2C_edited.jpg",
        "dest": "public/images/ktitors/stefan-vladislav.jpg",
        "slug": "stefan-vladislav"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/0/08/Stefan_vladislav_gracanica_monastery.png",
        "dest": "public/images/ktitors/stefan-vladislav-2.jpg",
        "slug": "stefan-vladislav"
    },
    # Stefan Uros I
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/0/08/Stefan_Uro%C5%A1_I%2C_Sopo%C4%87ani.jpg",
        "dest": "public/images/ktitors/stefan-uros-i-2.jpg",
        "slug": "stefan-uros-i"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/d/d2/King_Stefan_Uro%C5%A1_I_with_his_son_Stefan_Dragutin.jpg",
        "dest": "public/images/ktitors/stefan-uros-i-3.jpg",
        "slug": "stefan-uros-i"
    },
    # Kralj Dragutin
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/6/62/Stefan_Dragutin%2C_Arilje.jpg",
        "dest": "public/images/ktitors/kralj-dragutin-2.jpg",
        "slug": "kralj-dragutin"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/b/b4/Loza_Nemanjica_Decani_d_2.jpg",
        "dest": "public/images/ktitors/kralj-dragutin-3.jpg",
        "slug": "kralj-dragutin"
    },
    # Kralj Milutin
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/c/c9/King_Milutin%2C_detail_with_the_church%2C_GRACANICA_1_080A9353.jpg",
        "dest": "public/images/ktitors/kralj-milutin-2.jpg",
        "slug": "kralj-milutin"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/7/7a/King_Milutin%2C_narthex%2C_GRACANICA_4_080A9359.jpg",
        "dest": "public/images/ktitors/kralj-milutin-3.jpg",
        "slug": "kralj-milutin"
    },
    # Stefan Decanski
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/6/6c/Stefan_Uro%C5%A1_III_De%C4%8Danski_of_Serbia_Visoki_De%C4%8Dani_ktitorska_kompozicija.JPG",
        "dest": "public/images/ktitors/stefan-decanski-2.jpg",
        "slug": "stefan-decanski"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/3/38/Stefan_i_Dusan_ktitorski_portret_Decani1.jpg",
        "dest": "public/images/ktitors/stefan-decanski-3.jpg",
        "slug": "stefan-decanski"
    },
    # Car Dusan
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/8/83/Stefan_Uro%C5%A1_IV_Du%C5%A1an_of_Serbia_Lesnovo_fresco_II.JPG",
        "dest": "public/images/ktitors/car-dusan-2.jpg",
        "slug": "car-dusan"
    },
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/5/54/Stephan_Dusan_Coronation_Paja_Jovanovic.png",
        "dest": "public/images/ktitors/car-dusan-3.jpg",
        "slug": "car-dusan"
    },
    # Uros Nejaki (zamena glavne + druga slika)
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
    # Sveti Sava
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
    # Ana Dandolo
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/7/74/Death_of_Queen_Ana_Dondolo%2C_Sopocani_monastery%2C_1272-74.jpg",
        "dest": "public/images/ktitors/ana-dandolo-2.jpg",
        "slug": "ana-dandolo"
    },
    # Ana Nemanjić (Sveta Anastasija)
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/0/00/Manastir_Studenica%2C_Sveta_Anastasija_Srpska_pred_Presvetom_Bogorodicom_iz_1568._godine.jpg",
        "dest": "public/images/ktitors/ana-zena-stefana-nemanje-2.jpg",
        "slug": "ana-zena-stefana-nemanje"
    },
    # Carica Jelena
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/4/44/Car_Du%C5%A1an_i_carica_Jelena%2C_Manastir_Lesnovo%2C_XIV_vek.jpg",
        "dest": "public/images/ktitors/carica-jelena-2.jpg",
        "slug": "carica-jelena"
    },
    # Simonida
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
    # Vukan Nemanjic
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
    # Jelena Anžujska
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/9/92/Serbian_Queen_Helen_of_Anjou_Nemanjic_Sopocani_Monastery.jpg",
        "dest": "public/images/ktitors/jelena-anzujska-2.jpg",
        "slug": "jelena-anzujska"
    },
    # Kneginja Milica
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/6/67/Lazar_i_Milica_Ljubostinja1.jpg",
        "dest": "public/images/ktitors/kneginja-milica-2.jpg",
        "slug": "kneginja-milica"
    },
    # Knez Lazar
    {
        "url": "https://upload.wikimedia.org/wikipedia/commons/9/90/Prince_Lazar_%28Ravanica_Monastery%29.jpg",
        "dest": "public/images/ktitors/knez-lazar-2.jpg",
        "slug": "knez-lazar"
    },
    # Stefan Lazarevic
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

os.makedirs('public/images/ktitors', exist_ok=True)

for item in downloads:
    dest_path = item["dest"]
    print(f"Downloading {item['dest']} from {item['url']}...")
    try:
        req = urllib.request.Request(item["url"], headers=headers)
        with urllib.request.urlopen(req, timeout=30) as resp:
            data = resp.read()
            with open(dest_path, "wb") as f:
                f.write(data)
            print(f"  OK ({len(data)} bytes)")
    except Exception as e:
        print(f"  FAILED: {e}")

print("\nDownload complete.")
