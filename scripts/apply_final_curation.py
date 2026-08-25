"""
Script to apply complete image curation, fix card images, remove duplicates, and add authentic descriptions.
"""
import sqlite3
import os
import sys
import io
import csv

sys.stdout = io.TextIOWrapper(sys.stdout.buffer, encoding='utf-8')

BASE_DIR = os.path.dirname(os.path.dirname(os.path.abspath(__file__)))
DB_STORAGE = os.path.join(BASE_DIR, 'storage', 'database.sqlite')
DB_DATABASE = os.path.join(BASE_DIR, 'database', 'database.sqlite')

# Exact curated data per monastery:
# slug -> list of { 'url': ..., 'caption': ... }
# The first element (index 0) will be sort_order 1 AND monasteries.image_url (the main card cover image)!
CURATED_DATA = {
    # ==================== EPARHIJA BANATSKA ====================
    'bavaniste': [
        {
            'url': 'images/monasteries/bavaniste.jpg',
            'caption': 'Manastirska crkva Rođenja Presvete Bogorodice i lekoviti izvor Vodica u bavaništanskoj šumi'
        }
    ],
    'gaj': [
        {
            'url': 'images/monasteries/gaj_gal_1.jpg',
            'caption': 'Crkva manastira Gaj posvećena Svetim četrdesetim mučenicima sevastijskim'
        },
        {
            'url': 'images/monasteries/gaj.jpg',
            'caption': 'Pogled na oltarsku apsidu i zvonik manastirske crkve u Gaju'
        }
    ],
    'hajducica': [
        {
            'url': 'images/monasteries/hajducica.jpg',
            'caption': 'Hram Svetog arhanđela Mihaila u manastirskom parku u Hajdučici'
        }
    ],
    'mesic': [
        {
            'url': 'images/monasteries/mesic.jpg',
            'caption': 'Barokni hram Rođenja Svetog Jovana Preteče i monumentalni konak manastira Mesić'
        },
        {
            'url': 'images/monasteries/mesic_gal_1.jpg',
            'caption': 'Pogled na manastirski kompleks Mesić podno Vršačkog brega'
        },
        {
            'url': 'images/monasteries/mesic_gal_2.jpg',
            'caption': 'Zvonik i arhitektura manastirske crkve u Mesiću'
        }
    ],
    'srediste': [
        {
            'url': 'images/monasteries/srediste.jpg',
            'caption': 'Glavni hram Čuda Svetog arhanđela Mihaila u manastiru Središte'
        },
        {
            'url': 'images/monasteries/srediste_gal_1.jpg',
            'caption': 'Pogled na manastirski kompleks Središte na padinama Vršačkih planina'
        },
        {
            'url': 'images/monasteries/srediste_gal_2.jpg',
            'caption': 'Ulazni deo sa zvonikom i konakom manastira Središte'
        }
    ],
    'sveta-trojica-kikinda': [
        {
            'url': 'images/monasteries/sveta-trojica-kikinda.jpg',
            'caption': 'Crkva Svete Trojice u Kikindi sa prepoznatljivim zvonikom u neobaroknom stilu'
        }
    ],
    'svete-melanije': [
        {
            'url': 'images/monasteries/svete-melanije.jpg',
            'caption': 'Manastir Svete Melanije Rimljanke u Zrenjaninu, podignut u srpsko-vizantijskom stilu'
        }
    ],
    'vlajkovac': [
        {
            'url': 'images/monasteries/vlajkovac.jpg',
            'caption': 'Crkva manastira Vlajkovac kod Vršca posvećena Presvetoj Bogorodici'
        }
    ],
    'vojlovica': [
        {
            'url': 'images/monasteries/vojlovica.jpg',
            'caption': 'Srednjovekovna crkva Svetih arhanđela manastira Vojlovica sa baroknim zvonikom'
        },
        {
            'url': 'images/monasteries/vojlovica_gal_1.jpg',
            'caption': 'Severna fasada i krovna konstrukcija manastirske crkve u Vojlovici'
        },
        {
            'url': 'images/monasteries/vojlovica_gal_2.jpg',
            'caption': 'Unutrašnjost hrama i barokni ikonostas iz 18. veka u manastiru Vojlovica'
        }
    ],

    # ==================== EPARHIJA BAČKA ====================
    'bodjani': [
        {
            'url': 'images/monasteries/bodjani_gal_1.jpg',
            'caption': 'Pogled na manastirski kompleks Bođani sa crkvom Vavedenja Presvete Bogorodice'
        },
        {
            'url': 'images/monasteries/bodjani.jpg',
            'caption': 'Naos i barokni ikonostas Hristofora Žefarovića iz 1737. godine u crkvi manastira Bođani'
        },
        {
            'url': 'images/monasteries/bodjani_gal_2.jpg',
            'caption': 'Kapela Svete Petke sa lekovitim izvorom u okviru manastira Bođani'
        }
    ],
    'kac': [
        {
            'url': 'images/monasteries/kac.jpg',
            'caption': 'Glavni hram Vaskrsenja Hristova manastira Kać građen u vizantijsko-svetogorskom stilu'
        },
        {
            'url': 'images/monasteries/kac_gal_1.jpg',
            'caption': 'Zapadna fasada i trem manastirske crkve u Kaću'
        }
    ],
    'kovilj': [
        {
            'url': 'images/monasteries/kovilj_gal_1.jpg',
            'caption': 'Monumentalna crkva Svetih arhangela Mihaila i Gavrila u manastiru Kovilj'
        },
        {
            'url': 'images/monasteries/kovilj.jpg',
            'caption': 'Ranojutarnji pogled na konake i manastirski kompleks Kovilja'
        }
    ],
    'sombor': [
        {
            'url': 'images/monasteries/sombor.jpg',
            'caption': 'Crkva Svetog arhiđakona Stefana u Somboru podignuta u srpsko-vizantijskom stilu'
        },
        {
            'url': 'images/monasteries/sombor_gal_1.jpg',
            'caption': 'Pogled na zvonik i manastirski konak u Somboru'
        }
    ],

    # ==================== EPARHIJA BEOGRADSKA ====================
    'mislodjin': [
        {
            'url': 'images/monasteries/mislodjin.jpg',
            'caption': 'Crkva Svetog Hristofora u Mislođinu podignuta na temeljima hrama iz 13. veka'
        },
        {
            'url': 'images/monasteries/mislodjin_gal_1.jpg',
            'caption': 'Pogled na manastirski kompleks i konak u Mislođinu'
        },
        {
            'url': 'images/monasteries/mislodjin_gal_2.jpg',
            'caption': 'Severna strana hrama Svetog Hristofora u Mislođinu'
        }
    ],
    'rajinovac': [
        {
            'url': 'images/monasteries/rajinovac.jpg',
            'caption': 'Crkva Rođenja Presvete Bogorodice manastira Rajinovac u Begaljici'
        },
        {
            'url': 'images/monasteries/rajinovac_gal_1.jpg',
            'caption': 'Zapadni ulaz i barokni zvonik manastira Rajinovac'
        },
        {
            'url': 'images/monasteries/rajinovac_gal_2.jpg',
            'caption': 'Pogled na manastirsku portu i konak u Rajinovcu'
        }
    ],
    'rakovica': [
        {
            'url': 'images/monasteries/rakovica.jpg',
            'caption': 'Crkva Svetih arhangela manastira Rakovica, mesto počinka patrijarha Pavla'
        },
        {
            'url': 'images/monasteries/rakovica_gal_1.jpg',
            'caption': 'Ikonostas i unutrašnjost crkve Svetog arhangela Mihaila u Rakovici'
        }
    ],
    'senjak': [
        {
            'url': 'images/monasteries/senjak.jpg',
            'caption': 'Manastir Vavedenje na Senjaku u Beogradu, zadužbina Perside Milenković'
        },
        {
            'url': 'images/monasteries/senjak_gal_1.jpg',
            'caption': 'Pogled na kupolu i arhitekturu hrama Vavedenja Presvete Bogorodice'
        },
        {
            'url': 'images/monasteries/senjak_gal_2.jpg',
            'caption': 'Južna fasada manastirske crkve na Senjaku'
        }
    ],

    # ==================== EPARHIJA BRANIČEVSKA ====================
    'bradaca': [
        {
            'url': 'images/monasteries/bradaca.jpg',
            'caption': 'Hram Svetog arhangela Gavrila manastira Bradača podno Kličevca'
        },
        {
            'url': 'images/monasteries/bradaca_gal_1.jpg',
            'caption': 'Južna strana crkve i manastirska porta u Bradači'
        },
        {
            'url': 'images/monasteries/bradaca_gal_2.jpg',
            'caption': 'Pogled na manastirski kompleks Bradača'
        }
    ],
    'dobres': [
        {
            'url': 'images/monasteries/dobres.jpg',
            'caption': 'Crkva Svetog Nikole manastira Dobreš kod Svilajnca'
        }
    ],
    'gornjak': [
        {
            'url': 'images/monasteries/gornjak_gal_1.jpg',
            'caption': 'Pogled na manastir Gornjak i litice planine Ježevac na reci Mlavi'
        },
        {
            'url': 'images/monasteries/gornjak.jpg',
            'caption': 'Crkva Vavedenja Presvete Bogorodice manastira Gornjak u steni Gornjačke klisure'
        },
        {
            'url': 'images/monasteries/gornjak_gal_2.jpg',
            'caption': 'Pećinska isposnica Svetog Grigorija Sinaita iznad crkve u Gornjaku'
        }
    ],
    'izvor': [
        {
            'url': 'images/monasteries/izvor.jpg',
            'caption': 'Hram Svete Petke u manastiru Izvor kod Paraćina'
        }
    ],
    'koporin': [
        {
            'url': 'images/monasteries/koporin_gal_1.jpg',
            'caption': 'Crkva Svetog arhiđakona Stefana u Koporinu, zadužbina i grob despota Stefana Lazarevića'
        },
        {
            'url': 'images/monasteries/koporin.jpg',
            'caption': 'Pogled na hram i konake manastira Koporin u šumovitoj dolini kod Velike Plane'
        },
        {
            'url': 'images/monasteries/koporin_gal_2.jpg',
            'caption': 'Zapadna fasada i zvonara manastirskog kompleksa Koporin'
        }
    ],
    'manasija': [
        {
            'url': 'images/monasteries/manasija.jpg',
            'caption': 'Utvrđeni manastirski kompleks Manasija sa crkvom Svete Trojice i 11 odbrambenih kula'
        },
        {
            'url': 'images/monasteries/manasija_gal_1.jpg',
            'caption': 'Crkva Svete Trojice u Manasiji, vrhunac moravske arhitekture 15. veka'
        },
        {
            'url': 'images/monasteries/manasija_gal_2.jpg',
            'caption': 'Pogled na monumentalne zidine i Despotovu kulu manastira Manasija'
        }
    ],
    'miljkovo': [
        {
            'url': 'images/monasteries/miljkovo.jpg',
            'caption': 'Crkva Vavedenja Presvete Bogorodice Miljkovog manastira na obali Velike Morave'
        },
        {
            'url': 'images/monasteries/miljkovo_gal_1.jpg',
            'caption': 'Pogled na manastirsku portu i konake u Miljkovom manastiru'
        },
        {
            'url': 'images/monasteries/miljkovo_gal_2.jpg',
            'caption': 'Zapadni ulaz i zvonik Miljkovog manastira kod Gložana'
        }
    ],
    'namasija': [
        {
            'url': 'images/monasteries/namasija.jpg',
            'caption': 'Ostaci srednjovekovne crkve Svetog Nikole u manastirskom kompleksu Namasija'
        },
        {
            'url': 'images/monasteries/namasija_gal_1.jpg',
            'caption': 'Arhitektonski ostaci manastira Namasija u kanjonu Crnice kod Zabrege'
        },
        {
            'url': 'images/monasteries/namasija_gal_2.jpg',
            'caption': 'Pogled na arheološki lokalitet manastira Namasija'
        }
    ],
    'nimnik': [
        {
            'url': 'images/monasteries/nimnik_gal_1.jpg',
            'caption': 'Crkva Svetog Nikole manastira Nimnik u hrastovoj šumi kod Požarevca'
        },
        {
            'url': 'images/monasteries/nimnik.jpg',
            'caption': 'Pogled na manastirski kompleks Nimnik sa konakom'
        },
        {
            'url': 'images/monasteries/nimnik_gal_2.jpg',
            'caption': 'Kapela Svetinje u Nimniku na grobu svete mučenice Nikoline'
        }
    ],
    'pokajnica': [
        {
            'url': 'images/monasteries/pokajnica_gal_1.jpg',
            'caption': 'Crkva brvnara manastira Pokajnica iz 1818. godine, zadužbina Vujice Vulićevića'
        },
        {
            'url': 'images/monasteries/pokajnica_gal_2.jpg',
            'caption': 'Zapadni trem sa drvenim stubovima crkve brvnare Pokajnica'
        },
        {
            'url': 'images/monasteries/pokajnica.jpg',
            'caption': 'Pogled na crkvu brvnaru i zvonaru manastira Pokajnica kod Velike Plane'
        }
    ],
    'radosin': [
        {
            'url': 'images/monasteries/radosin.jpg',
            'caption': 'Hram Rođenja Presvete Bogorodice manastira Radošin kod Svilajnca'
        },
        {
            'url': 'images/monasteries/radosin_gal_1.jpg',
            'caption': 'Pogled na manastirski konak i portu u Radošinu'
        },
        {
            'url': 'images/monasteries/radosin_gal_2.jpg',
            'caption': 'Severna strana crkve i zvonik manastira Radošin'
        }
    ],
    'ravanica': [
        {
            'url': 'images/monasteries/ravanica_gal_1.jpg',
            'caption': 'Crkva Vaznesenja Gospodnjeg u Ravanici, zadužbina i grobno mesto Svetog kneza Lazara'
        },
        {
            'url': 'images/monasteries/ravanica_gal_2.jpg',
            'caption': 'Petokupolna arhitektura moravskog stila sa bogatom spoljnom keramičkom ornamentikom'
        },
        {
            'url': 'images/monasteries/ravanica.jpg',
            'caption': 'Pogled na ravanicki hram i ostatke srednjovekovnih odbrambenih kula kneza Lazara'
        }
    ],
    'reskovica': [
        {
            'url': 'images/monasteries/reskovica.jpg',
            'caption': 'Višespratna crkva Sabora Svetih apostola manastira Reškovica u Homolju'
        },
        {
            'url': 'images/monasteries/reskovica_gal_1.jpg',
            'caption': 'Pogled na manastirski kompleks Reškovica u klisuri reke Reškovice'
        },
        {
            'url': 'images/monasteries/reskovica_gal_2.jpg',
            'caption': 'Arhitektura novog manastirskog zdanja podignutog na temeljima lazarevske svetinje'
        }
    ],
    'rukumija': [
        {
            'url': 'images/monasteries/rukumija_gal_2.jpg',
            'caption': 'Crkva Svetog Nikole manastira Rukumija kod Kostolca'
        },
        {
            'url': 'images/monasteries/rukumija_gal_1.jpg',
            'caption': 'Pogled na manastirski kompleks i zvonaru u Rukumiji'
        },
        {
            'url': 'images/monasteries/rukumija.jpg',
            'caption': 'Manastirska porta sa konakom i cvetnim vrtom u Rukumiji'
        }
    ],
    'sisojevac': [
        {
            'url': 'images/monasteries/sisojevac.jpg',
            'caption': 'Crkva Svetog Sisoja Velikog iz 14. veka sa očuvanim freskama i grobom prepodobnog Sisoja Sinaita'
        },
        {
            'url': 'images/monasteries/sisojevac_gal_2.jpg',
            'caption': 'Pogled na manastirski kompleks Sisojevac na izvoru reke Crnice podno Kučajskih planina'
        }
    ],
    'tumane': [
        {
            'url': 'images/monasteries/tumane_gal_1.jpg',
            'caption': 'Crkva Svetog arhangela Gavrila manastira Tumane, zadužbina kosovskog junaka Miloša Obilića'
        },
        {
            'url': 'images/monasteries/tumane_gal_2.jpg',
            'caption': 'Pogled na manastirski kompleks Tumane sa novim konacima u dolini Golubačkih planina'
        },
        {
            'url': 'images/monasteries/tumane.jpg',
            'caption': 'Prilaz manastirskoj crkvi i porta u manastiru Tumane kod Golubca'
        }
    ],
    'zdrelo': [
        {
            'url': 'images/monasteries/zdrelo.jpg',
            'caption': 'Hram Svete Trojice manastira Ždrelo na uzvišenju iznad Gornjačke klisure'
        },
        {
            'url': 'images/monasteries/zdrelo_gal_2.jpg',
            'caption': 'Pogled na manastirski kompleks i konak u Ždrelu podno Homolja'
        }
    ],
    'zlatenac': [
        {
            'url': 'images/monasteries/zlatenac_gal_1.jpg',
            'caption': 'Crkva Svetih vrača Kozme i Damjana manastira Zlatenac iznad Velike Morave'
        },
        {
            'url': 'images/monasteries/zlatenac_gal_2.jpg',
            'caption': 'Pogled na manastir Zlatenac i moravsku ravnicu kod Svilajnca'
        }
    ],

    # ==================== EPARHIJA KRUŠEVAČKA ====================
    'drenca': [
        {
            'url': 'images/monasteries/drenca_gal_1.jpg',
            'caption': 'Obnovljeni hram Vavedenja Presvete Bogorodice manastira Drenča kod Aleksandrovca'
        },
        {
            'url': 'images/monasteries/drenca_gal_2.jpg',
            'caption': 'Moravska arhitektura i kamena plastika crkve Dušmanice u Drenči'
        },
        {
            'url': 'images/monasteries/drenca.jpg',
            'caption': 'Pogled na manastirski kompleks Drenča u Župi aleksandrovačkoj'
        }
    ],
    'grabovo': [
        {
            'url': 'images/monasteries/grabovo.jpg',
            'caption': 'Crkva Svetog arhangela Mihaila manastira Grabovo u opštini Trstenik'
        }
    ],
    'komorane': [
        {
            'url': 'images/monasteries/komorane.jpg',
            'caption': 'Crkva Svetog Nikole manastira Komorane kod Kruševca'
        },
        {
            'url': 'images/monasteries/komorane_gal_1.jpg',
            'caption': 'Pogled na manastirski konak i portu u Komoranu'
        },
        {
            'url': 'images/monasteries/komorane_gal_2.jpg',
            'caption': 'Severoistočna strana hrama u manastiru Komorane'
        }
    ],
    'ljubostinja': [
        {
            'url': 'images/monasteries/ljubostinja_gal_1.jpg',
            'caption': 'Crkva Uspenja Presvete Bogorodice manastira Ljubostinja, zadužbina kneginje Milice'
        },
        {
            'url': 'images/monasteries/ljubostinja_gal_2.jpg',
            'caption': 'Zapadna fasada sa raskošnim kamenim rozetama i moravskim prepletima Radeta Borovića'
        },
        {
            'url': 'images/monasteries/ljubostinja.jpg',
            'caption': 'Pogled na manastirski kompleks Ljubostinja u dolini Ljubostinjske reke kod Trstenika'
        }
    ],
    'veluce': [
        {
            'url': 'images/monasteries/veluce_gal_1.jpg',
            'caption': 'Crkva Vavedenja Presvete Bogorodice manastira Veluće iz 1377. godine'
        },
        {
            'url': 'images/monasteries/veluce_gal_2.jpg',
            'caption': 'Bogatstvo reljefne kamene dekoracije i šahovska polja na fasadi hrama u Veluću'
        },
        {
            'url': 'images/monasteries/veluce.jpg',
            'caption': 'Pogled na manastirski kompleks Veluće u podnožju planine Gledić'
        }
    ],

    # ==================== EPARHIJA MILEŠEVSKA ====================
    'davidovica': [
        {
            'url': 'images/monasteries/davidovica.jpg',
            'caption': 'Crkva Bogojavljenja manastira Davidovica iz 1281. godine na obali Lima kod Brodareva'
        },
        {
            'url': 'images/monasteries/davidovica_gal_1.jpg',
            'caption': 'Zapadni ulaz i raška arhitektura zadužbine monaha Davida (Dimitrija Nemanjića)'
        },
        {
            'url': 'images/monasteries/davidovica_gal_2.jpg',
            'caption': 'Pogled na manastirski kompleks Davidovica i reku Lim'
        }
    ],
    'jabuka': [
        {
            'url': 'images/monasteries/jabuka.jpg',
            'caption': 'Crkva Svetog proroka Ilije manastira Jabuka na visoravni kod Prijepolja'
        }
    ],
    'janja': [
        {
            'url': 'images/monasteries/janja.jpg',
            'caption': 'Obnovljeni hram Svetih pravednih Joakima i Ane manastira Janja u kanjonu reke Uvac kod Priboja'
        }
    ],
    'kumanica': [
        {
            'url': 'images/monasteries/kumanica.jpg',
            'caption': 'Crkva Svetog arhangela Gavrila manastira Kumanica u klisuri reke Lim'
        },
        {
            'url': 'images/monasteries/kumanica_gal_2.jpg',
            'caption': 'Pogled na manastirski kompleks Kumanica uz kanjon Lima na granici Srbije i Crne Gore'
        }
    ],
    'mazici': [
        {
            'url': 'images/monasteries/mazici.jpg',
            'caption': 'Crkva Svetog Đorđa u Mažićima iz 12. veka na obali Potpećkog jezera kod Priboja'
        },
        {
            'url': 'images/monasteries/mazici_gal_1.jpg',
            'caption': 'Arheološki ostaci jedne od najstarijih srpskih srednjovekovnih bolnica u manastiru Mažići'
        }
    ],
    'mileseva': [
        {
            'url': 'images/monasteries/mileseva_gal_1.jpg',
            'caption': 'Monumentalna crkva Vaznesenja Gospodnjeg manastira Mileševa iz 1219. godine'
        },
        {
            'url': 'images/monasteries/mileseva_gal_2.jpg',
            'caption': 'Pogled na manastirski kompleks Mileševa sa konacima u dolini reke Mileševke'
        },
        {
            'url': 'images/monasteries/mileseva.jpg',
            'caption': 'Zapadna fasada i zvonik zadužbine srpskog kralja Stefana Vladislava u Mileševi'
        }
    ],
    'pribojska-banja': [
        {
            'url': 'images/monasteries/pribojska-banja.jpg',
            'caption': 'Crkva Svetog Nikole Dabarskog u Pribojskoj Banji, drevno sedište Dabarske eparhije Svetog Save'
        },
        {
            'url': 'images/monasteries/pribojska-banja_gal_1.jpg',
            'caption': 'Pogled na manastirski kompleks Svetog Nikole u Pribojskoj Banji'
        }
    ],
    'pustinja-valjevska': [
        {
            'url': 'images/monasteries/pustinja-valjevska_gal_1.jpg',
            'caption': 'Crkva Vavedenja Presvete Bogorodice manastira Pustinja u klisuri reke Jablanice kod Poćute'
        }
    ]
}

def apply_curation():
    print("=== PRIMENA KURIRANIH SLIKA I OPISA ZA 6 EPARHIJA ===\n")
    
    # 1. Update both database files
    for db_path in [DB_STORAGE, DB_DATABASE]:
        if not os.path.exists(db_path):
            print(f"Preskačem nepostojeću bazu: {db_path}")
            continue
        print(f"Ažuriram bazu: {db_path}")
        conn = sqlite3.connect(db_path)
        cur = conn.cursor()

        for slug, img_list in CURATED_DATA.items():
            cur.execute("SELECT id, name FROM monasteries WHERE slug=?", (slug,))
            row = cur.fetchone()
            if not row:
                print(f"  [!] Nije nađen manastir sa slugom: {slug}")
                continue
            m_id, name = row

            # Delete existing images for this monastery
            cur.execute("DELETE FROM monastery_images WHERE monastery_id=?", (m_id,))

            card_url = img_list[0]['url']
            cur.execute("UPDATE monasteries SET image_url=? WHERE id=?", (card_url, m_id))

            for sort_idx, item in enumerate(img_list, 1):
                cur.execute(
                    """INSERT INTO monastery_images 
                       (monastery_id, url, caption, sort_order, created_at, updated_at) 
                       VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))""",
                    (m_id, item['url'], item['caption'], sort_idx)
                )

            print(f"  ✓ {slug} ({name}): postavljen card={card_url} i {len(img_list)} slika sa opisima")

        conn.commit()
        conn.close()

    # 2. Export updated monasteries.csv
    conn = sqlite3.connect(DB_STORAGE)
    c = conn.cursor()
    c.execute('SELECT * FROM monasteries')
    cols = [d[0] for d in c.description]
    rows = c.fetchall()
    for out in ['storage/app/import/monasteries.csv', 'database/seeders/data/monasteries.csv']:
        out_path = os.path.join(BASE_DIR, out.replace('/', os.sep))
        if os.path.exists(os.path.dirname(out_path)):
            with open(out_path, 'w', encoding='utf-8-sig', newline='') as f:
                w = csv.writer(f, delimiter=';')
                w.writerow(cols)
                for r in rows:
                    w.writerow([str(x).replace(';', ',') if x is not None else '' for r_item in r for x in [r_item]])
            print(f"\nSinhronizovan CSV: {out}")
    conn.close()

    print("\n✓ Sve uspešno završeno!")

if __name__ == '__main__':
    apply_curation()
