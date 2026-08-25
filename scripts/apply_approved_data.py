"""
Script to apply the user-approved verified descriptions and card image settings
for all monasteries in the 6 eparchies.
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

# The exact verified table approved by the user
APPROVED_DATA = {
    # ==================== EPARHIJA BANATSKA ====================
    'bavaniste': [
        {
            'url': 'images/monasteries/bavaniste.jpg',
            'caption': 'Hram Rođenja Presvete Bogorodice podignut krajem 19. veka u šumi kod Bavaništa, sa kapelom nad lekovitim izvorom (Izvor: Monografija Eparhije banatske / Vikimedijina ostava)'
        }
    ],
    'gaj': [
        {
            'url': 'images/monasteries/gaj_gal_1.jpg',
            'caption': 'Zapadna fasada i zvonik manastirskog hrama Svetih četrdeset mučenika sevastijskih u Gaju (Izvor: Zavod za zaštitu spomenika kulture Pančevo / Wiki.Vojvodina)'
        },
        {
            'url': 'images/monasteries/gaj.jpg',
            'caption': 'Istočna strana manastirske crkve sa oltarskom apsidom u Gaju (Izvor: Turistička organizacija opštine Kovin / Vikimedijina ostava)'
        }
    ],
    'hajducica': [
        {
            'url': 'images/monasteries/hajducica.jpg',
            'caption': 'Crkva Svetog arhanđela Mihaila u manastirskom parku u Hajdučici, zadužbina Olge Jovanović iz 1939. godine (Izvor: Eparhija banatska / Spomenici kulture u Srbiji)'
        }
    ],
    'mesic': [
        {
            'url': 'images/monasteries/mesic.jpg',
            'caption': 'Glavni hram Rođenja Svetog Jovana Krstitelja u Mesiću sa baroknim zvonikom i spratnim konakom iz 18. veka (Izvor: Pokrajinski zavod za zaštitu spomenika kulture Vojvodine / Wiki.Vojvodina)'
        },
        {
            'url': 'images/monasteries/mesic_gal_1.jpg',
            'caption': 'Južna strana manastirskog kompleksa Mesić sa pogledom na konake podno Vršačkog brega (Izvor: Wiki.Vojvodina VI / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/mesic_gal_2.jpg',
            'caption': 'Barokni zvonik i krovna konstrukcija manastirske crkve u Mesiću (Izvor: Wiki.Vojvodina VI / Vikimedijina ostava)'
        }
    ],
    'srediste': [
        {
            'url': 'images/monasteries/srediste.jpg',
            'caption': 'Glavni hram Čuda Svetog arhanđela Mihaila u manastiru Malo Središte na obroncima Vršačkih planina (Izvor: Eparhija banatska / Wiki.Vojvodina)'
        },
        {
            'url': 'images/monasteries/srediste_gal_1.jpg',
            'caption': 'Manastirski kompleks Središte sa novim konakom i zvonikom (Izvor: Wiki.Vojvodina VI / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/srediste_gal_2.jpg',
            'caption': 'Prilaz manastirskom dvorištu sa konakom i kapelom u Središtu (Izvor: Wiki.Vojvodina VI / Vikimedijina ostava)'
        }
    ],
    'sveta-trojica-kikinda': [
        {
            'url': 'images/monasteries/sveta-trojica-kikinda.jpg',
            'caption': 'Hram Svete Trojice u Kikindi sa neobaroknim zvonikom, zadužbina Melanije Gajčić iz 1887. godine (Izvor: Narodni muzej Kikinda / Vikimedijina ostava)'
        }
    ],
    'svete-melanije': [
        {
            'url': 'images/monasteries/svete-melanije.jpg',
            'caption': 'Crkva Svete Melanije Rimljanke u Zrenjaninu, podignuta 1935. godine po projektu arhitekte Đorđa Tabakovića u srpsko-vizantijskom stilu (Izvor: Zavod za zaštitu spomenika kulture Zrenjanin / Vikimedijina ostava)'
        }
    ],
    'vlajkovac': [
        {
            'url': 'images/monasteries/vlajkovac.jpg',
            'caption': 'Crkva manastira Vlajkovac kod Vršca posvećena Presvetoj Bogorodici (Izvor: Wiki.Vojvodina VI / Vikimedijina ostava)'
        }
    ],
    'vojlovica': [
        {
            'url': 'images/monasteries/vojlovica.jpg',
            'caption': 'Srednjovekovna crkva Svetih arhanđela manastira Vojlovica iz 14. veka sa baroknim zvonikom iz 18. veka (Izvor: Zavod za zaštitu spomenika kulture Pančevo / Wiki.Vojvodina)'
        },
        {
            'url': 'images/monasteries/vojlovica_gal_1.jpg',
            'caption': 'Severna fasada manastirskog hrama u Vojlovici sa krovnim vencem (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/vojlovica_gal_2.jpg',
            'caption': 'Naos hrama sa baroknim pozlaćenim ikonostasom iz 18. veka u manastiru Vojlovica (Izvor: Zavod za zaštitu spomenika kulture Pančevo / Vikimedijina ostava)'
        }
    ],

    # ==================== EPARHIJA BAČKA ====================
    'bodjani': [
        {
            'url': 'images/monasteries/bodjani_gal_1.jpg',
            'caption': 'Spoljašnji pogled na manastirski kompleks Bođani sa crkvom Vavedenja Presvete Bogorodice iz 1722. godine (Izvor: Pokrajinski zavod za zaštitu spomenika kulture / Wiki.Vojvodina)'
        },
        {
            'url': 'images/monasteries/bodjani.jpg',
            'caption': 'Naos crkve sa monumentalnim baroknim freskama i ikonostasom Hristofora Žefarovića iz 1737. godine (Izvor: Galerija Matice srpske / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/bodjani_gal_2.jpg',
            'caption': 'Kapela Svete Petke sa čudotvornim izvorom u okviru manastira Bođani (Izvor: Eparhija bačka / Vikimedijina ostava)'
        }
    ],
    'kac': [
        {
            'url': 'images/monasteries/kac.jpg',
            'caption': 'Glavni hram Vaskrsenja Hristova manastira Kać građen po uzoru na svetogorsku arhitekturu (Izvor: Eparhija bačka / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/kac_gal_1.jpg',
            'caption': 'Zapadni ulazni trem sa arkadama manastirske crkve u Kaću (Izvor: Eparhija bačka / Vikimedijina ostava)'
        }
    ],
    'kovilj': [
        {
            'url': 'images/monasteries/kovilj_gal_1.jpg',
            'caption': 'Monumentalna crkva Svetih arhangela Mihaila i Gavrila u Kovilju, zidana klesanim kamenom (1799. god, arhitekta Jakov Nevrok) (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/kovilj.jpg',
            'caption': 'Ranojutarnja vizura koviljskog manastirskog kompleksa sa konacima (Izvor: Eparhija bačka / Vikimedijina ostava)'
        }
    ],
    'sombor': [
        {
            'url': 'images/monasteries/sombor.jpg',
            'caption': 'Hram Svetog arhiđakona Stefana u Somboru u srpsko-vizantijskom stilu, zadužbina Stevana Konjovića iz 1927. godine (Izvor: Zavod za zaštitu spomenika kulture Sombor / Wiki.Vojvodina)'
        },
        {
            'url': 'images/monasteries/sombor_gal_1.jpg',
            'caption': 'Zvonik i istočni deo manastirskog kompleksa u Somboru (Izvor: Wiki.Vojvodina IX / Vikimedijina ostava)'
        }
    ],

    # ==================== EPARHIJA BEOGRADSKA ====================
    'mislodjin': [
        {
            'url': 'images/monasteries/mislodjin.jpg',
            'caption': 'Hram Svetog mučenika Hristofora u Mislođinu podignut na temeljima srednjovekovne crkve kralja Dragutina (Izvor: Zavod za zaštitu spomenika kulture grada Beograda / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/mislodjin_gal_1.jpg',
            'caption': 'Pogled na manastirsku portu i novoizgrađeni konak u Mislođinu (Izvor: Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/mislodjin_gal_2.jpg',
            'caption': 'Severna fasada crkve Svetog Hristofora u Mislođinu (Izvor: Vikimedijina ostava)'
        }
    ],
    'rajinovac': [
        {
            'url': 'images/monasteries/rajinovac.jpg',
            'caption': 'Crkva Rođenja Presvete Bogorodice manastira Rajinovac u Begaljici, zadužbina sa čudotvornom ikonom Bogorodice Rajinovačke (Izvor: Zavod za zaštitu spomenika kulture grada Beograda / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/rajinovac_gal_1.jpg',
            'caption': 'Zapadna fasada sa baroknim zvonikom manastira Rajinovac (Izvor: Arhiepiskopija beogradsko-karlovačka / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/rajinovac_gal_2.jpg',
            'caption': 'Manastirska porta i spratni konak u Rajinovcu (Izvor: Vikimedijina ostava)'
        }
    ],
    'rakovica': [
        {
            'url': 'images/monasteries/rakovica.jpg',
            'caption': 'Crkva Svetih arhangela Mihaila i Gavrila manastira Rakovica, u čijoj porti počivaju patrijarsi Dimitrije i Pavle (Izvor: Zavod za zaštitu spomenika kulture grada Beograda / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/rakovica_gal_1.jpg',
            'caption': 'Ikonostas i unutrašnjost crkve Svetog arhangela Mihaila u manastiru Rakovica (Izvor: Arhiepiskopija beogradsko-karlovačka / Vikimedijina ostava)'
        }
    ],
    'senjak': [
        {
            'url': 'images/monasteries/senjak.jpg',
            'caption': 'Crkva Vavedenja Presvete Bogorodice na Senjaku, zadužbina Perside Milenković iz 1935. godine (Izvor: Spomenici kulture Beograda / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/senjak_gal_1.jpg',
            'caption': 'Glavna kupola i krovna konstrukcija u moravsko-vizantijskom stilu na manastiru Vavedenje (Izvor: Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/senjak_gal_2.jpg',
            'caption': 'Južna fasada hrama Vavedenja Presvete Bogorodice na Senjaku (Izvor: Vikimedijina ostava)'
        }
    ],

    # ==================== EPARHIJA BRANIČEVSKA ====================
    'bradaca': [
        {
            'url': 'images/monasteries/bradaca.jpg',
            'caption': 'Hram Svetog arhangela Gavrila manastira Bradača iz 14. veka podno Kličevca (Izvor: Regionalni zavod za zaštitu spomenika kulture Smederevo / Wiki.Zaleđe)'
        },
        {
            'url': 'images/monasteries/bradaca_gal_1.jpg',
            'caption': 'Južna fasada crkve sa manastirskom portom u Bradači (Izvor: Wiki.Zaleđe II / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/bradaca_gal_2.jpg',
            'caption': 'Manastirski konak i porta manastira Bradača (Izvor: Wiki.Zaleđe II / Vikimedijina ostava)'
        }
    ],
    'dobres': [
        {
            'url': 'images/monasteries/dobres.jpg',
            'caption': 'Crkva Svetog Nikole manastira Dobreš kod Svilajnca, metoh Miljkovog manastira (Izvor: Wiki.Biseri III / Vikimedijina ostava)'
        }
    ],
    'gornjak': [
        {
            'url': 'images/monasteries/gornjak_gal_1.jpg',
            'caption': 'Manastirski kompleks Gornjak u stenama Gornjačke klisure na levoj obali Mlave (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/gornjak.jpg',
            'caption': 'Crkva Vavedenja Presvete Bogorodice manastira Gornjak, zadužbina kneza Lazara iz 1378. godine (Izvor: Eparhija braničevska / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/gornjak_gal_2.jpg',
            'caption': 'Pećinska kapela i isposnica Svetog Grigorija Sinaita Gornjačkog u steni iznad hrama (Izvor: Zavod za zaštitu spomenika kulture Smederevo / Vikimedijina ostava)'
        }
    ],
    'izvor': [
        {
            'url': 'images/monasteries/izvor.jpg',
            'caption': 'Hram Svete Petke u manastiru Izvor kod Paraćina iz druge polovine 14. veka (Izvor: Eparhija braničevska / Vikimedijina ostava)'
        }
    ],
    'koporin': [
        {
            'url': 'images/monasteries/koporin_gal_1.jpg',
            'caption': 'Crkva Svetog arhiđakona Stefana u Koporinu, zadužbina i grobno mesto Svetog despota Stefana Lazarevića iz 1402. godine (Izvor: Zavod za zaštitu spomenika kulture Smederevo / Wiki.Šumadija)'
        },
        {
            'url': 'images/monasteries/koporin.jpg',
            'caption': 'Kompleks manastira Koporin sa hramom i konacima u dolini kod Velike Plane (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/koporin_gal_2.jpg',
            'caption': 'Zapadno pročelje sa kamenom zvonarom manastira Koporin (Izvor: Wiki.Šumadija XVI / Vikimedijina ostava)'
        }
    ],
    'manasija': [
        {
            'url': 'images/monasteries/manasija.jpg',
            'caption': 'Utvrđeni manastirski kompleks Manasija sa 11 odbrambenih kula i Despotovom kulom, zadužbina despota Stefana Lazarevića (1407–1418) (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/manasija_gal_1.jpg',
            'caption': 'Crkva Svete Trojice u Manasiji, remek-delo moravske arhitekture sa pet kupola (Izvor: UNESCO Tentative List / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/manasija_gal_2.jpg',
            'caption': 'Despotova kula i severni odbrambeni bedemi manastira Manasija (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'
        }
    ],
    'miljkovo': [
        {
            'url': 'images/monasteries/miljkovo.jpg',
            'caption': 'Crkva Vavedenja Presvete Bogorodice Miljkovog manastira na obali Velike Morave (Izvor: Eparhija braničevska / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/miljkovo_gal_1.jpg',
            'caption': 'Pogled na manastirsku portu i konake u Miljkovom manastiru (Izvor: Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/miljkovo_gal_2.jpg',
            'caption': 'Zapadni ulaz sa zvonikom manastirske crkve u Miljkovu (Izvor: Vikimedijina ostava)'
        }
    ],
    'namasija': [
        {
            'url': 'images/monasteries/namasija.jpg',
            'caption': 'Konzervirani ostaci manastirske crkve Svetog Nikole u kompleksu Namasija iz 14. veka (Izvor: Zavod za zaštitu spomenika kulture Kragujevac / Wiki.Biseri)'
        },
        {
            'url': 'images/monasteries/namasija_gal_1.jpg',
            'caption': 'Zidine i oltarski deo crkve Namasija u kanjonu reke Crnice kod Zabrege (Izvor: Wiki.Biseri III / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/namasija_gal_2.jpg',
            'caption': 'Arheološki lokalitet Namasija u zaštićenom području Petruške oblasti (Izvor: Wiki.Biseri III / Vikimedijina ostava)'
        }
    ],
    'nimnik': [
        {
            'url': 'images/monasteries/nimnik_gal_1.jpg',
            'caption': 'Crkva Svetog Nikole manastira Nimnik u hrastovoj šumi kod Požarevca (zadužbina vojvode Bogosava, 1376) (Izvor: Eparhija braničevska / Wiki.Đerdap)'
        },
        {
            'url': 'images/monasteries/nimnik.jpg',
            'caption': 'Manastirski kompleks Nimnik sa konakom i portom (Izvor: Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/nimnik_gal_2.jpg',
            'caption': 'Kapela Svetinja na grobu svete mučenice Nikoline u manastiru Nimnik (Izvor: Wiki.Đerdap I / Vikimedijina ostava)'
        }
    ],
    'pokajnica': [
        {
            'url': 'images/monasteries/pokajnica_gal_1.jpg',
            'caption': 'Crkva brvnara Prenosa moštiju Svetog Nikole u Pokajnici, podignuta 1818. godine kao zadužbina Vujice Vulićevića (Izvor: Spomenici kulture od izuzetnog značaja / Wiki.Šumadija)'
        },
        {
            'url': 'images/monasteries/pokajnica_gal_2.jpg',
            'caption': 'Zapadni drveni trem i krov pokriven šindrom na crkvi brvnari Pokajnica (Izvor: Wiki.Šumadija XVI / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/pokajnica.jpg',
            'caption': 'Pogled na crkvu brvnaru i drvenu zvonaru manastira Pokajnica (Izvor: Zavod za zaštitu spomenika kulture Smederevo / Vikimedijina ostava)'
        }
    ],
    'radosin': [
        {
            'url': 'images/monasteries/radosin.jpg',
            'caption': 'Hram Rođenja Presvete Bogorodice manastira Radošin iz 15. veka na obali Morave (Izvor: Eparhija braničevska / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/radosin_gal_1.jpg',
            'caption': 'Manastirski konak i uređena porta u Radošinu (Izvor: Wiki.Biseri I / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/radosin_gal_2.jpg',
            'caption': 'Severna fasada i zvonik manastirske crkve u Radošinu (Izvor: Wiki.Biseri I / Vikimedijina ostava)'
        }
    ],
    'ravanica': [
        {
            'url': 'images/monasteries/ravanica_gal_1.jpg',
            'caption': 'Crkva Vaznesenja Gospodnjeg u Ravanici, glavna zadužbina i grobno mesto Svetog kneza Lazara (1375–1377) (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/ravanica_gal_2.jpg',
            'caption': 'Petokupolna moravska arhitektura Ravanice sa karakterističnom spoljnom keramičkom i reljefnom ornamentikom (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/ravanica.jpg',
            'caption': 'Ravanicki hram sa ostacima srednjovekovnih utvrđenih zidina i kula kneza Lazara (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'
        }
    ],
    'reskovica': [
        {
            'url': 'images/monasteries/reskovica.jpg',
            'caption': 'Višespratna crkva Sabora Svetih apostola manastira Reškovica u Homoljskim planinama (Izvor: Eparhija braničevska / Wiki.Zaleđe)'
        },
        {
            'url': 'images/monasteries/reskovica_gal_1.jpg',
            'caption': 'Pogled na manastirski kompleks Reškovica u šumovitoj klisuri istoimene reke (Izvor: Wiki.Zaleđe III / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/reskovica_gal_2.jpg',
            'caption': 'Arhitektonska celina novog manastirskog hrama u Reškovici (Izvor: Wiki.Zaleđe III / Vikimedijina ostava)'
        }
    ],
    'rukumija': [
        {
            'url': 'images/monasteries/rukumija_gal_2.jpg',
            'caption': 'Crkva Svetog Nikole manastira Rukumija kod Kostolca, zadužbina iz doba kneza Lazara (Izvor: Eparhija braničevska / Wiki.Zaleđe)'
        },
        {
            'url': 'images/monasteries/rukumija_gal_1.jpg',
            'caption': 'Zvonara i konak u manastirskom dvorištu Rukumije (Izvor: Wiki.Zaleđe II / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/rukumija.jpg',
            'caption': 'Manastirska porta sa cvetnim vrtom i prilazom hramu u Rukumiji (Izvor: Vikimedijina ostava)'
        }
    ],
    'sisojevac': [
        {
            'url': 'images/monasteries/sisojevac.jpg',
            'caption': 'Crkva Svetog Sisoja Velikog iz 1380. godine, zadužbina monaha Sisoja Sinaita sa očuvanim moravskim freskama (Izvor: Zavod za zaštitu spomenika kulture Kragujevac / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/sisojevac_gal_2.jpg',
            'caption': 'Manastirski kompleks Sisojevac na izvoru reke Crnice podno Kučajskih planina (Izvor: Wiki.Biseri I / Vikimedijina ostava)'
        }
    ],
    'tumane': [
        {
            'url': 'images/monasteries/tumane_gal_1.jpg',
            'caption': 'Crkva Svetog arhangela Gavrila manastira Tumane, zadužbina Miloša Obilića u kojoj počivaju mošti Svetog Zosima i Svetog Jakova (Izvor: Zvanični sajt manastira Tumane / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/tumane_gal_2.jpg',
            'caption': 'Kompleks manastira Tumane sa novim konacima u dolini Golubačkih planina (Izvor: Eparhija braničevska / Wiki.Đerdap)'
        },
        {
            'url': 'images/monasteries/tumane.jpg',
            'caption': 'Prilaz manastirskom hramu u Tumanu kod Golubca (Izvor: Vikimedijina ostava)'
        }
    ],
    'zdrelo': [
        {
            'url': 'images/monasteries/zdrelo.jpg',
            'caption': 'Hram Svete Trojice manastira Ždrelo na uzvišenju na ulazu u Gornjačku klisuru (Izvor: Eparhija braničevska / Wiki.Zaleđe)'
        },
        {
            'url': 'images/monasteries/zdrelo_gal_2.jpg',
            'caption': 'Manastirski kompleks i konak u Ždrelu podno Homoljskih planina (Izvor: Wiki.Zaleđe III / Vikimedijina ostava)'
        }
    ],
    'zlatenac': [
        {
            'url': 'images/monasteries/zlatenac_gal_1.jpg',
            'caption': 'Crkva Svetih vrača Kozme i Damjana manastira Zlatenac iz 15. veka na litici iznad Morave (Izvor: Eparhija braničevska / Wiki.Biseri)'
        },
        {
            'url': 'images/monasteries/zlatenac_gal_2.jpg',
            'caption': 'Pogled na manastir Zlatenac i Resavsku ravnicu kod Svilajnca (Izvor: Wiki.Biseri I / Vikimedijina ostava)'
        }
    ],

    # ==================== EPARHIJA KRUŠEVAČKA ====================
    'drenca': [
        {
            'url': 'images/monasteries/drenca_gal_1.jpg',
            'caption': 'Obnovljena crkva Vavedenja Presvete Bogorodice (Dušmanica) manastira Drenča iz 1382. godine u Župi aleksandrovačkoj (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/drenca_gal_2.jpg',
            'caption': 'Moravska kamena plastika i rozete na fasadi crkve u Drenči (Izvor: Spomenici kulture od velikog značaja / Wiki.Rasina)'
        },
        {
            'url': 'images/monasteries/drenca.jpg',
            'caption': 'Arheološki ostaci i obnovljeni hram manastira Drenča (Izvor: Vikimedijina ostava)'
        }
    ],
    'grabovo': [
        {
            'url': 'images/monasteries/grabovo.jpg',
            'caption': 'Crkva Svetog arhangela Mihaila manastira Grabovo u opštini Trstenik (Izvor: Eparhija kruševačka / Vikimedijina ostava)'
        }
    ],
    'komorane': [
        {
            'url': 'images/monasteries/komorane.jpg',
            'caption': 'Crkva Svetog Nikole Čudotvorca manastira Komorane kod Kruševca (Izvor: Eparhija kruševačka / Wiki.Rasina)'
        },
        {
            'url': 'images/monasteries/komorane_gal_1.jpg',
            'caption': 'Zapadni ulaz i manastirska porta u Komoranu (Izvor: Wiki.Rasina II / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/komorane_gal_2.jpg',
            'caption': 'Severoistočna strana hrama manastira Komorane (Izvor: Wiki.Rasina II / Vikimedijina ostava)'
        }
    ],
    'ljubostinja': [
        {
            'url': 'images/monasteries/ljubostinja_gal_1.jpg',
            'caption': 'Crkva Uspenja Presvete Bogorodice u Ljubostinji, glavna zadužbina kneginje Milice i grobno mesto monahinje Jefimije (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/ljubostinja_gal_2.jpg',
            'caption': 'Zapadna fasada hrama sa kamenim rozetama i prepletima graditelja Radeta Borovića (Radeta Neimara) (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/ljubostinja.jpg',
            'caption': 'Manastirski kompleks Ljubostinja u dolini Ljubostinjske reke kod Trstenika (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'
        }
    ],
    'veluce': [
        {
            'url': 'images/monasteries/veluce_gal_1.jpg',
            'caption': 'Crkva Vavedenja Presvete Bogorodice manastira Veluće iz 1377. godine, zadužbina vlastelinke Mare (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Wiki.Rasina)'
        },
        {
            'url': 'images/monasteries/veluce_gal_2.jpg',
            'caption': 'Raskošna reljefna dekoracija, šahovska polja i kamena plastika na fasadi crkve u Veluću (Izvor: Spomenici kulture od velikog značaja / Wiki.Rasina)'
        },
        {
            'url': 'images/monasteries/veluce.jpg',
            'caption': 'Manastirski kompleks Veluće u podnožju planine Gledić (Izvor: Vikimedijina ostava)'
        }
    ],

    # ==================== EPARHIJA MILEŠEVSKA ====================
    'davidovica': [
        {
            'url': 'images/monasteries/davidovica.jpg',
            'caption': 'Crkva Bogojavljenja manastira Davidovica iz 1281. godine, zadužbina monaha Davida (župana Dimitrija Nemanjića) na obali Lima (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/davidovica_gal_1.jpg',
            'caption': 'Zapadni portal i raška jednobrodna arhitektura manastirske crkve u Davidovici (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/davidovica_gal_2.jpg',
            'caption': 'Manastirski kompleks Davidovica kod Brodareva sa dolinom reke Lim (Izvor: Vikimedijina ostava)'
        }
    ],
    'jabuka': [
        {
            'url': 'images/monasteries/jabuka.jpg',
            'caption': 'Crkva Svetog proroka Ilije manastira Jabuka na visoravni kod Prijepolja (Izvor: Eparhija mileševska / Vikimedijina ostava)'
        }
    ],
    'janja': [
        {
            'url': 'images/monasteries/janja.jpg',
            'caption': 'Obnovljeni hram Svetih pravednih Joakima i Ane manastira Janja u kanjonu reke Uvac kod Priboja (14. vek) (Izvor: Eparhija mileševska / Vikimedijina ostava)'
        }
    ],
    'kumanica': [
        {
            'url': 'images/monasteries/kumanica.jpg',
            'caption': 'Crkva Svetog arhistratiga Gavrila manastira Kumanica u klisuri Lima, srednjovekovna nemanjićka svetinja (Izvor: Eparhija mileševska / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/kumanica_gal_2.jpg',
            'caption': 'Manastirski kompleks Kumanica uz kanjon Lima na granici Srbije i Crne Gore (Izvor: Vikimedijina ostava)'
        }
    ],
    'mazici': [
        {
            'url': 'images/monasteries/mazici.jpg',
            'caption': 'Ostaci hrama Svetog Đorđa u Mažićima iz 12. veka na obali Potpećkog jezera kod Priboja (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/mazici_gal_1.jpg',
            'caption': 'Arheološki ostaci jedne od najstarijih srpskih srednjovekovnih bolnica iz 12. veka u manastiru Mažići (Izvor: Arheološki institut Beograd / Vikimedijina ostava)'
        }
    ],
    'mileseva': [
        {
            'url': 'images/monasteries/mileseva_gal_1.jpg',
            'caption': 'Crkva Vaznesenja Gospodnjeg u Mileševi iz 1219. godine, zadužbina kralja Vladislava (hram u kome se nalazi svetski poznata freska Belog Anđela) (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/mileseva_gal_2.jpg',
            'caption': 'Pogled na manastirski kompleks Mileševa sa konacima i rečicom Mileševkom podno srednjovekovnog grada Mileševca (Izvor: Eparhija mileševska / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/mileseva.jpg',
            'caption': 'Zapadna fasada i ulazni deo priprate manastira Mileševa (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'
        }
    ],
    'pribojska-banja': [
        {
            'url': 'images/monasteries/pribojska-banja.jpg',
            'caption': 'Crkva Svetog Nikole u Pribojskoj Banji, drevno sedište Dabarske episkopije koju je 1220. godine osnovao Sveti Sava (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'
        },
        {
            'url': 'images/monasteries/pribojska-banja_gal_1.jpg',
            'caption': 'Pogled na manastirski kompleks Svetog Nikole u Pribojskoj Banji (Izvor: Eparhija mileševska / Vikimedijina ostava)'
        }
    ],
    'pustinja-valjevska': [
        {
            'url': 'images/monasteries/pustinja-valjevska_gal_1.jpg',
            'caption': 'Crkva Vavedenja Presvete Bogorodice manastira Pustinja iz 13. veka u kanjonu reke Jablanice kod Poćute (hram poznat po freski Svetog Jovana Krilatog iz 1622. godine) (Izvor: Zavod za zaštitu spomenika kulture Valjevo / Vikimedijina ostava)'
        }
    ]
}

def apply_approved_data():
    print("=== UNOS ODOBRENIH OPISA I POSTAVLJANJE SLIKA U BAZU ===\n")
    
    for db_path in [DB_STORAGE, DB_DATABASE]:
        if not os.path.exists(db_path):
            print(f"Baza ne postoji, preskačem: {db_path}")
            continue
        print(f"Ažuriram: {db_path}")
        conn = sqlite3.connect(db_path)
        cur = conn.cursor()

        for slug, img_list in APPROVED_DATA.items():
            cur.execute("SELECT id, name FROM monasteries WHERE slug=?", (slug,))
            row = cur.fetchone()
            if not row:
                print(f"  [UPOZORENJE] Nije nađen manastir: {slug}")
                continue
            m_id, name = row

            # Obriši stare slike za ovaj manastir
            cur.execute("DELETE FROM monastery_images WHERE monastery_id=?", (m_id,))

            # Postavi kartičnu sliku na prvu (najreprezentativniju) sliku
            card_url = img_list[0]['url']
            cur.execute("UPDATE monasteries SET image_url=? WHERE id=?", (card_url, m_id))

            # Unesi sve slike sa novim odobrenim opisima
            for sort_idx, item in enumerate(img_list, 1):
                cur.execute(
                    """INSERT INTO monastery_images 
                       (monastery_id, url, caption, sort_order, created_at, updated_at) 
                       VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))""",
                    (m_id, item['url'], item['caption'], sort_idx)
                )

            print(f"  ✓ {slug} ({name}): postavljen card={card_url}, uneto {len(img_list)} slika sa opisima")

        conn.commit()
        conn.close()

    # Sinhronizacija CSV fajlova
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
            print(f"\nSinhronizovan CSV fajl: {out}")
    conn.close()

    print("\n✓ Unos uspešno završen!")

if __name__ == '__main__':
    apply_approved_data()
