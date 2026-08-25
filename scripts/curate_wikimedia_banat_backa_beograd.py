import os
import sys
import io
import sqlite3
import urllib.request
import time
import csv

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
IMG_DIR = os.path.join(BASE_DIR, 'public', 'images', 'monasteries')
DB_STORAGE = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE = os.path.join(BASE_DIR, 'database', 'database.sqlite')

HEADERS = {
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36'
}

def download_img(url, dest_path):
    if not url:
        return False
    time.sleep(1.2)  # Polite delay to prevent Wikimedia 429
    try:
        req = urllib.request.Request(url, headers=HEADERS)
        with urllib.request.urlopen(req, timeout=15) as resp:
            if resp.status == 200:
                data = resp.read()
                if len(data) > 4096:
                    with open(dest_path, 'wb') as f:
                        f.write(data)
                    print(f"  ✓ Preuzeto: {os.path.basename(dest_path)} ({len(data)//1024} KB)")
                    return True
    except Exception as e:
        print(f"  ✗ Greška pri preuzimanju {url}: {e}")
    return False

# 100% WIKIMEDIA COMMONS & WIKIPEDIA (FREE LICENSE, NO LOGOS, NO WATERMARKS)
WIKI_CURATION = {
    # =========================================================================
    # 1. EPARHIJA BANATSKA (9 manastira)
    # =========================================================================
    'bavaniste': [
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d4/Manastir_Bavani%C5%A1te_01.jpg/1280px-Manastir_Bavani%C5%A1te_01.jpg',
            'filename': 'bavaniste.jpg',
            'caption': 'Hram Rođenja Presvete Bogorodice – Manastir Bavanište u hrastovoj šumi'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1b/Manastir_Bavani%C5%A1te_02.jpg/1280px-Manastir_Bavani%C5%A1te_02.jpg',
            'filename': 'bavaniste_gal_1.jpg',
            'caption': 'Kapela i sveti čudotvorni izvor „Vodica” u manastirskom kompleksu'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e4/Manastir_Bavani%C5%A1te_03.jpg/1280px-Manastir_Bavani%C5%A1te_03.jpg',
            'filename': 'bavaniste_gal_2.jpg',
            'caption': 'Manastirski konak i uređena porta u šumskom okruženju'
        }
    ],
    'gaj': [
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Manastir_Gaj_01.jpg/1280px-Manastir_Gaj_01.jpg',
            'filename': 'gaj.jpg',
            'caption': 'Hram Vaznesenja Gospodnjeg – Manastir Gaj kod Kovina'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Manastir_Gaj_02.jpg/1280px-Manastir_Gaj_02.jpg',
            'filename': 'gaj_gal_1.jpg',
            'caption': 'Pogled na manastirski zvonik i ulaznu kapiju u selu Gaj'
        }
    ],
    'hajducica': [
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4e/Manastir_Hajdu%C4%8Dica_01.jpg/1280px-Manastir_Hajdu%C4%8Dica_01.jpg',
            'filename': 'hajducica.jpg',
            'caption': 'Crkva Svetog Arhangela Mihaila – Manastir Hajdučica'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7b/Manastir_Hajdu%C4%8Dica_02.jpg/1280px-Manastir_Hajdu%C4%8Dica_02.jpg',
            'filename': 'hajducica_gal_1.jpg',
            'caption': 'Zadužbinska crkva Olge Jovanović Dunđerski u prostranom parku'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/23/Manastir_Hajdu%C4%8Dica_03.jpg/1280px-Manastir_Hajdu%C4%8Dica_03.jpg',
            'filename': 'hajducica_gal_2.jpg',
            'caption': 'Zvonik i manastirski konak u Hajdučici'
        }
    ],
    'mesic': [
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b8/Manastir_Mesi%C4%87_01.jpg/1280px-Manastir_Mesi%C4%87_01.jpg',
            'filename': 'mesic.jpg',
            'caption': 'Hram Rođenja Svetog Jovana Krstitelja – Manastir Mesić kod Vršca'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0c/Manastir_Mesi%C4%87_02.jpg/1280px-Manastir_Mesi%C4%87_02.jpg',
            'filename': 'mesic_gal_1.jpg',
            'caption': 'Barokni zvonik i monumentalni manastirski konak pod Vršačkim bregom'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Manastir_Mesi%C4%87_03.jpg/1280px-Manastir_Mesi%C4%87_03.jpg',
            'filename': 'mesic_gal_2.jpg',
            'caption': 'Manastirska porta i pogled na zdanje iz 13. veka'
        }
    ],
    'srediste': [
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/36/Manastir_Sredi%C5%A1te_01.jpg/1280px-Manastir_Sredi%C5%A1te_01.jpg',
            'filename': 'srediste.jpg',
            'caption': 'Hram Presvete Bogorodice Trojeručice – Manastir Središte'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a9/Manastir_Sredi%C5%A1te_02.jpg/1280px-Manastir_Sredi%C5%A1te_02.jpg',
            'filename': 'srediste_gal_1.jpg',
            'caption': 'Pogled na manastirsko zdanje na padinama Vršačkih planina'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/14/Manastir_Sredi%C5%A1te_03.jpg/1280px-Manastir_Sredi%C5%A1te_03.jpg',
            'filename': 'srediste_gal_2.jpg',
            'caption': 'Manastirski konak i porta na Guduričkom vrhu'
        }
    ],
    'sveta-trojica-kikinda': [
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/eb/Manastir_Svete_Trojice_Kikinda_01.JPG/1280px-Manastir_Svete_Trojice_Kikinda_01.JPG',
            'filename': 'sveta-trojica-kikinda.jpg',
            'caption': 'Zadužbinski hram Svete Trojice – Manastir u Kikindi'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9a/Manastir_Svete_Trojice_Kikinda_02.JPG/1280px-Manastir_Svete_Trojice_Kikinda_02.JPG',
            'filename': 'sveta-trojica-kikinda_gal_1.jpg',
            'caption': 'Arhitektura hrama zadužbinarke Melanije Nikolić (rođ. Gačić)'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e8/%D0%97%D0%B2%D0%BE%D0%BD%D0%B8%D0%BA_%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80%D0%B0_%D0%A1%D0%B2%D0%B5%D1%82%D0%B5_%D0%A2%D1%80%D0%BE%D1%98%D0%B8%D1%86%D0%B5_%D1%83_%D0%9A%D0%B8%D0%BA%D0%B8%D0%BD%D0%B4%D0%B8.jpeg/1280px-%D0%97%D0%B2%D0%BE%D0%BD%D0%B8%D0%BA_%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80%D0%B0_%D0%A1%D0%B2%D0%B5%D1%82%D0%B5_%D0%A2%D1%80%D0%BE%D1%98%D0%B8%D1%86%D0%B5_%D1%83_%D0%9A%D0%B8%D0%BA%D0%B8%D0%BD%D0%B4%D0%B8.jpeg',
            'filename': 'sveta-trojica-kikinda_gal_2.jpg',
            'caption': 'Zvonik i manastirski kompleks Svete Trojice u Kikindi'
        }
    ],
    'svete-melanije': [
        {
            'url': 'https://upload.wikimedia.org/wikipedia/sr/e/e0/%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D1%81%D0%B2%D0%B5%D1%82%D0%B5_%D0%9C%D0%B5%D0%BB%D0%B0%D0%BD%D0%B8%D1%98%D0%B5.jpg',
            'filename': 'svete-melanije.jpg',
            'caption': 'Hram Rođenja Presvete Bogorodice – Manastir Svete Melanije u Zrenjaninu'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c5/Manastir_Svete_Melanije_02.jpg/1280px-Manastir_Svete_Melanije_02.jpg',
            'filename': 'svete-melanije_gal_1.jpg',
            'caption': 'Zadužbina episkopa Georgija Letića i manastirski konak'
        }
    ],
    'vlajkovac': [
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/90/Dvorac_u_Vlajkovcu_01.jpg/1280px-Dvorac_u_Vlajkovcu_01.jpg',
            'filename': 'vlajkovac.jpg',
            'caption': 'Glavni hram i zdanje manastirskog poseda u Vlajkovcu kod Vršca'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/73/Dvorac_u_Vlajkovcu_02.jpg/1280px-Dvorac_u_Vlajkovcu_02.jpg',
            'filename': 'vlajkovac_gal_1.jpg',
            'caption': 'Pogled na manastirski kompleks i park u Vlajkovcu'
        }
    ],
    'vojlovica': [
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/77/Manastir_Vojlovica_01.jpg/1280px-Manastir_Vojlovica_01.jpg',
            'filename': 'vojlovica.jpg',
            'caption': 'Crkva Svetih Arhangela Mihaila i Gavrila – Manastir Vojlovica kod Pančeva'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4f/Manastir_Vojlovica_02.jpg/1280px-Manastir_Vojlovica_02.jpg',
            'filename': 'vojlovica_gal_1.jpg',
            'caption': 'Barokni zvonik iz 1752. godine i srednjovekovni bedemi despota Stefana'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/a/a2/Manastir_Vojlovica_03.jpg/1280px-Manastir_Vojlovica_03.jpg',
            'filename': 'vojlovica_gal_2.jpg',
            'caption': 'Raskošni pozlaćeni barokni ikonostas u manastirskom hramu Vojlovice'
        }
    ],

    # =========================================================================
    # 2. EPARHIJA BAČKA (5 manastira)
    # =========================================================================
    'bodjani': [
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b5/Manastir_Bodjani_%28Vavedenja_Presvete_Bogorodice%29_01.jpg/1280px-Manastir_Bodjani_%28Vavedenja_Presvete_Bogorodice%29_01.jpg',
            'filename': 'bodjani.jpg',
            'caption': 'Hram Vavedenja Presvete Bogorodice – Manastir Bođani kod Bača'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/23/Manastir_Bodjani_02.jpg/1280px-Manastir_Bodjani_02.jpg',
            'filename': 'bodjani_gal_1.jpg',
            'caption': 'Čuveni barokni živopis Hristifora Žefarovića iz 1737. godine u hramu Bođana'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/94/Manastir_Bodjani_03.jpg/1280px-Manastir_Bodjani_03.jpg',
            'filename': 'bodjani_gal_2.jpg',
            'caption': 'Manastirski konak, barokni zvonik i uređena porta u Bođanima'
        }
    ],
    'kac': [
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/3a/Manastir_Ka%C4%87_01.jpg/1280px-Manastir_Ka%C4%87_01.jpg',
            'filename': 'kac.jpg',
            'caption': 'Hram Vaskrsenja Hristovog – Manastir Kać kod Novog Sada'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/87/Manastir_Ka%C4%87_02.jpg/1280px-Manastir_Ka%C4%87_02.jpg',
            'filename': 'kac_gal_1.jpg',
            'caption': 'Pogled na manastirski hram i konak u Kaću'
        }
    ],
    'kovilj': [
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e6/%D0%9A%D0%BE%D0%B2%D0%B8%D1%99%D1%81%D0%BA%D0%B8_%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%A1%D0%B2%D0%B5%D1%82%D0%B8%D1%85_%D0%90%D1%80%D1%85%D0%B0%D0%BD%D0%B3%D0%B5%D0%BB%D0%B0_%D0%9C%D0%B8%D1%85%D0%B0%D0%B8%D0%BB%D0%B0_%D0%B8_%D0%93%D0%B0%D0%B2%D1%80%D0%B8%D0%BB%D0%B0.jpg/1280px-%D0%9A%D0%BE%D0%B2%D0%B8%D1%99%D1%81%D0%BA%D0%B8_%D0%9C%D0%B0%D0%BD%D0%B0%D1%81%D1%82%D0%B8%D1%80_%D0%A1%D0%B2%D0%B5%D1%82%D0%B8%D1%85_%D0%90%D1%80%D1%85%D0%B0%D0%BD%D0%B3%D0%B5%D0%BB%D0%B0_%D0%9C%D0%B8%D1%85%D0%B0%D0%B8%D0%BB%D0%B0_%D0%B8_%D0%93%D0%B0%D0%B2%D1%80%D0%B8%D0%BB%D0%B0.jpg',
            'filename': 'kovilj.jpg',
            'caption': 'Monumentalni hram Svetih Arhangela Mihaila i Gavrila – Manastir Kovilj'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/29/Manastir_Kovilj_u_rano_jutro.jpg/1280px-Manastir_Kovilj_u_rano_jutro.jpg',
            'filename': 'kovilj_gal_1.jpg',
            'caption': 'Pogled na manastirski kompleks, zvonik i portu u Kovilju u jutarnjem suncu'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f5/Unutrasnjost_manastira_u_Kovilju.jpg/1280px-Unutrasnjost_manastira_u_Kovilju.jpg',
            'filename': 'kovilj_gal_2.jpg',
            'caption': 'Unutrašnjost hrama i ikonostas rad Aksentija Marodića u Kovilju'
        }
    ],
    'sombor': [
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/42/Manastir_Sombor_01.jpg/1280px-Manastir_Sombor_01.jpg',
            'filename': 'sombor.jpg',
            'caption': 'Crkva Svetog Prvomučenika Stefana – Manastir Sombor'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/07/Manastir_Sombor_02.jpg/1280px-Manastir_Sombor_02.jpg',
            'filename': 'sombor_gal_1.jpg',
            'caption': 'Srpsko-vizantijska arhitektura i kupola hrama zadužbinara Stevana Konjovića'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/b/b3/Manastir_Sombor_03.jpg/1280px-Manastir_Sombor_03.jpg',
            'filename': 'sombor_gal_2.jpg',
            'caption': 'Zadužbinski kompleks i uređena manastirska porta u Somboru'
        }
    ],
    'vodica': [
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/71/Vodica_Bac_01.jpg/1280px-Vodica_Bac_01.jpg',
            'filename': 'vodica.jpg',
            'caption': 'Kapela i svetište Rođenja Presvete Bogorodice na Vodici kod Bača'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8c/Vodica_Bac_02.jpg/1280px-Vodica_Bac_02.jpg',
            'filename': 'vodica_gal_1.jpg',
            'caption': 'Čudotvorni izvor i prirodno okruženje manastira Vodica'
        }
    ],

    # =========================================================================
    # 3. EPARHIJA BEOGRADSKA (6 manastira)
    # =========================================================================
    'mislodjin': [
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e1/Manastir_Mislo%C4%91in_01.jpg/1280px-Manastir_Mislo%C4%91in_01.jpg',
            'filename': 'mislodjin.jpg',
            'caption': 'Hram Svetog Velikomučenika Hristifora – Manastir Mislođin kod Obrenovca'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/2/22/Manastir_Mislo%C4%91in_02.jpg/1280px-Manastir_Mislo%C4%91in_02.jpg',
            'filename': 'mislodjin_gal_1.jpg',
            'caption': 'Pogled na manastirsko zdanje iz vremena kralja Dragutina i novi konak'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/81/Manastir_Mislo%C4%91in_03.jpg/1280px-Manastir_Mislo%C4%91in_03.jpg',
            'filename': 'mislodjin_gal_2.jpg',
            'caption': 'Manastirska porta i zvonik u Mislođinu'
        }
    ],
    'rajinovac': [
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/c/c1/Manastir_Rajinovac_1.jpg/1280px-Manastir_Rajinovac_1.jpg',
            'filename': 'rajinovac.jpg',
            'caption': 'Crkva Rođenja Presvete Bogorodice – Manastir Rajinovac u Begaljici'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/9b/Manastir_Rajinovac_2.jpg/1280px-Manastir_Rajinovac_2.jpg',
            'filename': 'rajinovac_gal_1.jpg',
            'caption': 'Pogled na hram i uređenu cvetnu portu u Begaljici kod Grocke'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/75/Wiki_%C5%A0umadija_XIV_Manastir_Rajinovac_184.jpg/1280px-Wiki_%C5%A0umadija_XIV_Manastir_Rajinovac_184.jpg',
            'filename': 'rajinovac_gal_2.jpg',
            'caption': 'Ikonostas i unutrašnjost hrama sa čudotvornom ikonom Bogorodice Rajinovačke'
        }
    ],
    'rakovica': [
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f9/Manastir_Rakovica_-_28_04_2018_02.jpg/1280px-Manastir_Rakovica_-_28_04_2018_02.jpg',
            'filename': 'rakovica.jpg',
            'caption': 'Hram Svetog Arhangela Mihaila – Manastir Rakovica u Beogradu'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/53/Manastir_Rakovica%2C_unutra%C5%A1njost_crkve_i_ikonostas.jpg/1280px-Manastir_Rakovica%2C_unutra%C5%A1njost_crkve_i_ikonostas.jpg',
            'filename': 'rakovica_gal_1.jpg',
            'caption': 'Unutrašnjost crkve i pozlaćeni ikonostas manastira Rakovica'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/f/f0/Manastir_Rakovica_-_28_04_2018_04.jpg/1280px-Manastir_Rakovica_-_28_04_2018_04.jpg',
            'filename': 'rakovica_gal_2.jpg',
            'caption': 'Manastirska porta sa grobnim mestom patrijarha Pavla i Dimitrija'
        }
    ],
    'senjak': [
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/18/Manastir_Vavedenje_Senjak_01.jpg/1280px-Manastir_Vavedenje_Senjak_01.jpg',
            'filename': 'senjak.jpg',
            'caption': 'Hram Vavedenja Presvete Bogorodice – Manastir Vavedenje na Senjaku'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/57/Manastir_Vavedenje_Senjak_02.jpg/1280px-Manastir_Vavedenje_Senjak_02.jpg',
            'filename': 'senjak_gal_1.jpg',
            'caption': 'Arhitektura hrama zadužbinarke Perside Milenković na Senjaku'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/4/4b/Manastir_Vavedenje_Senjak_03.jpg/1280px-Manastir_Vavedenje_Senjak_03.jpg',
            'filename': 'senjak_gal_2.jpg',
            'caption': 'Manastirski konak i dvorište pod Topčiderskim brdom u Beogradu'
        }
    ],
    'slanci': [
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/9/91/Manastir_Slanci_01.jpg/1280px-Manastir_Slanci_01.jpg',
            'filename': 'slanci.jpg',
            'caption': 'Hram Svetog Arhiđakona Stefana – Manastir Slanci (metoh Hilandara)'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/6/62/Manastir_Slanci_02.jpg/1280px-Manastir_Slanci_02.jpg',
            'filename': 'slanci_gal_1.jpg',
            'caption': 'Pogled na manastirski kompleks i hilandarske konake u Slancima'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/05/Manastir_Slanci_03.jpg/1280px-Manastir_Slanci_03.jpg',
            'filename': 'slanci_gal_2.jpg',
            'caption': 'Manastirska porta i zvonik hilandarskog metoha kod Beograda'
        }
    ],
    'trojerucica': [
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/Manastir_Trojeru%C4%8Dica_Ripanj_01.jpg/1280px-Manastir_Trojeru%C4%8Dica_Ripanj_01.jpg',
            'filename': 'trojerucica.jpg',
            'caption': 'Hram Presvete Bogorodice Trojeručice pod Avalom u Ripnju'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/52/Manastir_Trojeru%C4%8Dica_Ripanj_02.jpg/1280px-Manastir_Trojeru%C4%8Dica_Ripanj_02.jpg',
            'filename': 'trojerucica_gal_1.jpg',
            'caption': 'Pogled na manastirsko zdanje i zvonik podno Avale'
        },
        {
            'url': 'https://upload.wikimedia.org/wikipedia/commons/thumb/1/1a/Manastir_Trojeru%C4%8Dica_Ripanj_03.jpg/1280px-Manastir_Trojeru%C4%8Dica_Ripanj_03.jpg',
            'filename': 'trojerucica_gal_2.jpg',
            'caption': 'Manastirska porta i konak manastira Trojeručica'
        }
    ]
}

def execute_curation():
    print("=== KURIRANJE SLIKA SA WIKIMEDIA COMMONS ZA BANATSKU, BAČKU I BEOGRADSKU EPARHIJU ===")
    
    for db_path in [DB_STORAGE, DB_DATABASE]:
        if not os.path.exists(db_path):
            continue
        print(f"\nAžuriranje baze: {db_path}")
        conn = sqlite3.connect(db_path)
        cur = conn.cursor()

        for slug, items in WIKI_CURATION.items():
            cur.execute("SELECT id, name FROM monasteries WHERE slug = ?", (slug,))
            row = cur.fetchone()
            if not row:
                print(f"Manastir {slug} nije pronađen!")
                continue
            m_id, name = row[0], row[1]
            print(f"\nObrada: {name} ({slug})...")

            cur.execute("DELETE FROM monastery_images WHERE monastery_id = ?", (m_id,))

            valid_items = []
            for s_idx, item in enumerate(items, 1):
                dest_fn = item['filename']
                dest_full = os.path.join(IMG_DIR, dest_fn)
                dest_rel = f"images/monasteries/{dest_fn}"

                # Download from Wikimedia
                ok = download_img(item['url'], dest_full)
                if not ok and os.path.exists(dest_full) and os.path.getsize(dest_full) > 1024:
                    ok = True

                if ok:
                    valid_items.append((dest_rel, item['caption'], s_idx))
                    cur.execute(
                        "INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))",
                        (m_id, dest_rel, item['caption'], s_idx)
                    )

            if valid_items:
                cur.execute("UPDATE monasteries SET image_url = ? WHERE id = ?", (valid_items[0][0], m_id))

        conn.commit()
        conn.close()

    # CSV sync
    conn = sqlite3.connect(DB_STORAGE)
    c = conn.cursor()
    c.execute('SELECT * FROM monasteries')
    cols = [d[0] for d in c.description]
    rows = c.fetchall()

    for out_path in ['storage/app/import/monasteries.csv', 'database/seeders/data/monasteries.csv']:
        if os.path.exists(os.path.dirname(out_path)):
            with open(out_path, 'w', encoding='utf-8-sig', newline='') as f:
                writer = csv.writer(f, delimiter=';')
                writer.writerow(cols)
                for r in rows:
                    clean_r = [str(x).replace(';', ',') if x is not None else '' for x in r]
                    writer.writerow(clean_r)
    conn.close()
    print("\n✓ WIKIMEDIA KURIRANJE USPEŠNO ZAVRŠENO I SINHRONIZOVANO!")

if __name__ == '__main__':
    execute_curation()
