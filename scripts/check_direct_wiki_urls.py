import urllib.request
import urllib.parse
import json
import sys
import io
import time

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

HEADERS = {
    'User-Agent': 'ManastiriSrbijeBot/1.0 (https://manastirisrbije.org; info@manastirisrbije.org) Python-urllib'
}

monasteries = {
    # BANATSKA
    'bavaniste': [
        'https://upload.wikimedia.org/wikipedia/commons/4/41/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%91%D0%B0%D0%B2%D0%B0%D0%BD%D0%B8%D1%88%D1%82%D0%B5_01.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Manastir_Bavani%C5%A1te_01.jpg/1280px-Manastir_Bavani%C5%A1te_01.jpg'
    ],
    'mesic': [
        'https://upload.wikimedia.org/wikipedia/commons/9/91/Manastir_Mesic_01.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/4/4f/Manastir_Mesic_02.jpg'
    ],
    'hajducica': [
        'https://upload.wikimedia.org/wikipedia/commons/7/75/Manastir_Hajducica_01.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/d/d4/Manastir_Hajducica_02.jpg'
    ],
    'vojlovica': [
        'https://upload.wikimedia.org/wikipedia/commons/8/87/Manastir_Vojlovica_01.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/a/a2/Manastir_Vojlovica_02.jpg'
    ],
    'svete-melanije': [
        'https://upload.wikimedia.org/wikipedia/sr/e/e0/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D1%81%D0%B2%D0%B5%D1%82%D0%B5_%D0%9C%D0%B5%D0%BB%D0%B0%D0%BD%D0%B8%D1%98%D0%B5.jpg'
    ],
    'bodjani': [
        'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/Manastir_Bodjani_%28Vavedenja_Presvete_Bogorodice%29_01.jpg/1280px-Manastir_Bodjani_%28Vavedenja_Presvete_Bogorodice%29_01.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/thumb/2/23/Manastir_Bodjani_02.jpg/1280px-Manastir_Bodjani_02.jpg'
    ],
    'kovilj': [
        'https://upload.wikimedia.org/wikipedia/commons/e/e6/%D0%9A%D0%BE%D0%B2%D0%B8%D1%99%D1%81%D0%BA%D0%B8_%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%A1%D0%B2%D0%B5%D1%82%D0%B8%D1%85_%D0%90%D1%80%D1%85%D0%B0%D0%BD%D0%B3%D0%B5%D0%BB%D0%B0_%D0%9C%D0%B8%D1%85%D0%B0%D0%B8%D0%BB%D0%B0_%D0%B8_%D0%93%D0%B0%D0%B2%D1%80%D0%B8%D0%BB%D0%B0.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/2/29/Manastir_Kovilj_u_rano_jutro.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/8/82/Zvonik_manastira_Kovilj.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/f/f5/Unutrasnjost_manastira_u_Kovilju.jpg'
    ],
    'rajinovac': [
        'https://upload.wikimedia.org/wikipedia/commons/c/c1/Manastir_Rajinovac_1.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/9/9b/Manastir_Rajinovac_2.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/7/75/Wiki_%C5%A0umadija_XIV_Manastir_Rajinovac_184.jpg'
    ],
    'rakovica': [
        'https://upload.wikimedia.org/wikipedia/commons/f/f9/Manastir_Rakovica_-_28_04_2018_02.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/5/53/Manastir_Rakovica%2C_unutra%C5%A1njost_crkve_i_ikonostas.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/f/f0/Manastir_Rakovica_-_28_04_2018_04.jpg',
        'https://upload.wikimedia.org/wikipedia/commons/b/b2/Manastirrakovica1.JPG'
    ]
}

for slug, urls in monasteries.items():
    print(f"\nProvera {slug}:")
    for u in urls:
        try:
            req = urllib.request.Request(u, headers=HEADERS)
            with urllib.request.urlopen(req, timeout=10) as resp:
                print(f"  ✓ 200 OK: {u.split('/')[-1]} ({resp.length} bytes)")
        except Exception as e:
            print(f"  ✗ Greška {u.split('/')[-1]}: {e}")
