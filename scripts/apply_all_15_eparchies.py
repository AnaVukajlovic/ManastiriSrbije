"""
Master script to apply verified descriptions, card image optimization,
and duplicate cleanup across ALL 15 eparchies (all monasteries in database).
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

# ALL VERIFIED MONASTERIES DATA (15 Eparchies)
MASTER_DATA = {
    # ==================== 1. EPARHIJA BANATSKA ====================
    'bavaniste': [
        {'url': 'images/monasteries/bavaniste.jpg', 'caption': 'Hram Rođenja Presvete Bogorodice podignut krajem 19. veka u šumi kod Bavaništa, sa kapelom nad lekovitim izvorom (Izvor: Monografija Eparhije banatske / Vikimedijina ostava)'}
    ],
    'gaj': [
        {'url': 'images/monasteries/gaj_gal_1.jpg', 'caption': 'Zapadna fasada i zvonik manastirskog hrama Svetih četrdeset mučenika sevastijskih u Gaju (Izvor: Zavod za zaštitu spomenika kulture Pančevo / Wiki.Vojvodina)'},
        {'url': 'images/monasteries/gaj.jpg', 'caption': 'Istočna strana manastirske crkve sa oltarskom apsidom u Gaju (Izvor: Turistička organizacija opštine Kovin / Vikimedijina ostava)'}
    ],
    'hajducica': [
        {'url': 'images/monasteries/hajducica.jpg', 'caption': 'Crkva Svetog arhanđela Mihaila u manastirskom parku u Hajdučici, zadužbina Olge Jovanović iz 1939. godine (Izvor: Eparhija banatska / Spomenici kulture u Srbiji)'}
    ],
    'mesic': [
        {'url': 'images/monasteries/mesic.jpg', 'caption': 'Glavni hram Rođenja Svetog Jovana Krstitelja u Mesiću sa baroknim zvonikom i spratnim konakom iz 18. veka (Izvor: Pokrajinski zavod za zaštitu spomenika kulture Vojvodine / Wiki.Vojvodina)'},
        {'url': 'images/monasteries/mesic_gal_1.jpg', 'caption': 'Južna strana manastirskog kompleksa Mesić sa pogledom na konake podno Vršačkog brega (Izvor: Wiki.Vojvodina VI / Vikimedijina ostava)'},
        {'url': 'images/monasteries/mesic_gal_2.jpg', 'caption': 'Barokni zvonik i krovna konstrukcija manastirske crkve u Mesiću (Izvor: Wiki.Vojvodina VI / Vikimedijina ostava)'}
    ],
    'srediste': [
        {'url': 'images/monasteries/srediste.jpg', 'caption': 'Glavni hram Čuda Svetog arhanđela Mihaila u manastiru Malo Središte na obroncima Vršačkih planina (Izvor: Eparhija banatska / Wiki.Vojvodina)'},
        {'url': 'images/monasteries/srediste_gal_1.jpg', 'caption': 'Manastirski kompleks Središte sa novim konakom i zvonikom (Izvor: Wiki.Vojvodina VI / Vikimedijina ostava)'},
        {'url': 'images/monasteries/srediste_gal_2.jpg', 'caption': 'Prilaz manastirskom dvorištu sa konakom i kapelom u Središtu (Izvor: Wiki.Vojvodina VI / Vikimedijina ostava)'}
    ],
    'sveta-trojica-kikinda': [
        {'url': 'images/monasteries/sveta-trojica-kikinda.jpg', 'caption': 'Hram Svete Trojice u Kikindi sa neobaroknim zvonikom, zadužbina Melanije Gajčić iz 1887. godine (Izvor: Narodni muzej Kikinda / Vikimedijina ostava)'}
    ],
    'svete-melanije': [
        {'url': 'images/monasteries/svete-melanije.jpg', 'caption': 'Crkva Svete Melanije Rimljanke u Zrenjaninu, podignuta 1935. godine po projektu arhitekte Đorđa Tabakovića u srpsko-vizantijskom stilu (Izvor: Zavod za zaštitu spomenika kulture Zrenjanin / Vikimedijina ostava)'}
    ],
    'vlajkovac': [
        {'url': 'images/monasteries/vlajkovac.jpg', 'caption': 'Crkva manastira Vlajkovac kod Vršca posvećena Presvetoj Bogorodici (Izvor: Wiki.Vojvodina VI / Vikimedijina ostava)'}
    ],
    'vojlovica': [
        {'url': 'images/monasteries/vojlovica.jpg', 'caption': 'Srednjovekovna crkva Svetih arhanđela manastira Vojlovica iz 14. veka sa baroknim zvonikom iz 18. veka (Izvor: Zavod za zaštitu spomenika kulture Pančevo / Wiki.Vojvodina)'},
        {'url': 'images/monasteries/vojlovica_gal_1.jpg', 'caption': 'Severna fasada manastirskog hrama u Vojlovici sa krovnim vencem (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'},
        {'url': 'images/monasteries/vojlovica_gal_2.jpg', 'caption': 'Naos hrama sa baroknim pozlaćenim ikonostasom iz 18. veka u manastiru Vojlovica (Izvor: Zavod za zaštitu spomenika kulture Pančevo / Vikimedijina ostava)'}
    ],

    # ==================== 2. EPARHIJA BAČKA ====================
    'bodjani': [
        {'url': 'images/monasteries/bodjani_gal_1.jpg', 'caption': 'Spoljašnji pogled na manastirski kompleks Bođani sa crkvom Vavedenja Presvete Bogorodice iz 1722. godine (Izvor: Pokrajinski zavod za zaštitu spomenika kulture / Wiki.Vojvodina)'},
        {'url': 'images/monasteries/bodjani.jpg', 'caption': 'Naos crkve sa monumentalnim baroknim freskama i ikonostasom Hristofora Žefarovića iz 1737. godine (Izvor: Galerija Matice srpske / Vikimedijina ostava)'},
        {'url': 'images/monasteries/bodjani_gal_2.jpg', 'caption': 'Kapela Svete Petke sa čudotvornim izvorom u okviru manastira Bođani (Izvor: Eparhija bačka / Vikimedijina ostava)'}
    ],
    'kac': [
        {'url': 'images/monasteries/kac.jpg', 'caption': 'Glavni hram Vaskrsenja Hristova manastira Kać građen po uzoru na svetogorsku arhitekturu (Izvor: Eparhija bačka / Vikimedijina ostava)'},
        {'url': 'images/monasteries/kac_gal_1.jpg', 'caption': 'Zapadni ulazni trem sa arkadama manastirske crkve u Kaću (Izvor: Eparhija bačka / Vikimedijina ostava)'}
    ],
    'kovilj': [
        {'url': 'images/monasteries/kovilj_gal_1.jpg', 'caption': 'Monumentalna crkva Svetih arhangela Mihaila i Gavrila u Kovilju, zidana klesanim kamenom (1799. god, arhitekta Jakov Nevrok) (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/kovilj.jpg', 'caption': 'Ranojutarnja vizura koviljskog manastirskog kompleksa sa konacima (Izvor: Eparhija bačka / Vikimedijina ostava)'}
    ],
    'sombor': [
        {'url': 'images/monasteries/sombor.jpg', 'caption': 'Hram Svetog arhiđakona Stefana u Somboru u srpsko-vizantijskom stilu, zadužbina Stevana Konjovića iz 1927. godine (Izvor: Zavod za zaštitu spomenika kulture Sombor / Wiki.Vojvodina)'},
        {'url': 'images/monasteries/sombor_gal_1.jpg', 'caption': 'Zvonik i istočni deo manastirskog kompleksa u Somboru (Izvor: Wiki.Vojvodina IX / Vikimedijina ostava)'}
    ],

    # ==================== 3. EPARHIJA BEOGRADSKA ====================
    'mislodjin': [
        {'url': 'images/monasteries/mislodjin.jpg', 'caption': 'Hram Svetog mučenika Hristofora u Mislođinu podignut na temeljima srednjovekovne crkve kralja Dragutina (Izvor: Zavod za zaštitu spomenika kulture grada Beograda / Vikimedijina ostava)'},
        {'url': 'images/monasteries/mislodjin_gal_1.jpg', 'caption': 'Pogled na manastirsku portu i novoizgrađeni konak u Mislođinu (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/mislodjin_gal_2.jpg', 'caption': 'Severna fasada crkve Svetog Hristofora u Mislođinu (Izvor: Vikimedijina ostava)'}
    ],
    'rajinovac': [
        {'url': 'images/monasteries/rajinovac.jpg', 'caption': 'Crkva Rođenja Presvete Bogorodice manastira Rajinovac u Begaljici, zadužbina sa čudotvornom ikonom Bogorodice Rajinovačke (Izvor: Zavod za zaštitu spomenika kulture grada Beograda / Vikimedijina ostava)'},
        {'url': 'images/monasteries/rajinovac_gal_1.jpg', 'caption': 'Zapadna fasada sa baroknim zvonikom manastira Rajinovac (Izvor: Arhiepiskopija beogradsko-karlovačka / Vikimedijina ostava)'},
        {'url': 'images/monasteries/rajinovac_gal_2.jpg', 'caption': 'Manastirska porta i spratni konak u Rajinovcu (Izvor: Vikimedijina ostava)'}
    ],
    'rakovica': [
        {'url': 'images/monasteries/rakovica.jpg', 'caption': 'Crkva Svetih arhangela Mihaila i Gavrila manastira Rakovica, u čijoj porti počivaju patrijarsi Dimitrije i Pavle (Izvor: Zavod za zaštitu spomenika kulture grada Beograda / Vikimedijina ostava)'},
        {'url': 'images/monasteries/rakovica_gal_1.jpg', 'caption': 'Ikonostas i unutrašnjost crkve Svetog arhangela Mihaila u manastiru Rakovica (Izvor: Arhiepiskopija beogradsko-karlovačka / Vikimedijina ostava)'}
    ],
    'senjak': [
        {'url': 'images/monasteries/senjak.jpg', 'caption': 'Crkva Vavedenja Presvete Bogorodice na Senjaku, zadužbina Perside Milenković iz 1935. godine (Izvor: Spomenici kulture Beograda / Vikimedijina ostava)'},
        {'url': 'images/monasteries/senjak_gal_1.jpg', 'caption': 'Glavna kupola i krovna konstrukcija u moravsko-vizantijskom stilu na manastiru Vavedenje (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/senjak_gal_2.jpg', 'caption': 'Južna fasada hrama Vavedenja Presvete Bogorodice na Senjaku (Izvor: Vikimedijina ostava)'}
    ],

    # ==================== 4. EPARHIJA BRANIČEVSKA ====================
    'bradaca': [
        {'url': 'images/monasteries/bradaca.jpg', 'caption': 'Hram Svetog arhangela Gavrila manastira Bradača iz 14. veka podno Kličevca (Izvor: Regionalni zavod za zaštitu spomenika kulture Smederevo / Wiki.Zaleđe)'},
        {'url': 'images/monasteries/bradaca_gal_1.jpg', 'caption': 'Južna fasada crkve sa manastirskom portom u Bradači (Izvor: Wiki.Zaleđe II / Vikimedijina ostava)'},
        {'url': 'images/monasteries/bradaca_gal_2.jpg', 'caption': 'Manastirski konak i porta manastira Bradača (Izvor: Wiki.Zaleđe II / Vikimedijina ostava)'}
    ],
    'dobres': [
        {'url': 'images/monasteries/dobres.jpg', 'caption': 'Crkva Svetog Nikole manastira Dobreš kod Svilajnca, metoh Miljkovog manastira (Izvor: Wiki.Biseri III / Vikimedijina ostava)'}
    ],
    'gornjak': [
        {'url': 'images/monasteries/gornjak_gal_1.jpg', 'caption': 'Manastirski kompleks Gornjak u stenama Gornjačke klisure na levoj obali Mlave (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/gornjak.jpg', 'caption': 'Crkva Vavedenja Presvete Bogorodice manastira Gornjak, zadužbina kneza Lazara iz 1378. godine (Izvor: Eparhija braničevska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/gornjak_gal_2.jpg', 'caption': 'Pećinska kapela i isposnica Svetog Grigorija Sinaita Gornjačkog u steni iznad hrama (Izvor: Zavod za zaštitu spomenika kulture Smederevo / Vikimedijina ostava)'}
    ],
    'izvor': [
        {'url': 'images/monasteries/izvor.jpg', 'caption': 'Hram Svete Petke u manastiru Izvor kod Paraćina iz druge polovine 14. veka (Izvor: Eparhija braničevska / Vikimedijina ostava)'}
    ],
    'koporin': [
        {'url': 'images/monasteries/koporin_gal_1.jpg', 'caption': 'Crkva Svetog arhiđakona Stefana u Koporinu, zadužbina i grobno mesto Svetog despota Stefana Lazarevića iz 1402. godine (Izvor: Zavod za zaštitu spomenika kulture Smederevo / Wiki.Šumadija)'},
        {'url': 'images/monasteries/koporin.jpg', 'caption': 'Kompleks manastira Koporin sa hramom i konacima u dolini kod Velike Plane (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'},
        {'url': 'images/monasteries/koporin_gal_2.jpg', 'caption': 'Zapadno pročelje sa kamenom zvonarom manastira Koporin (Izvor: Wiki.Šumadija XVI / Vikimedijina ostava)'}
    ],
    'manasija': [
        {'url': 'images/monasteries/manasija.jpg', 'caption': 'Utvrđeni manastirski kompleks Manasija sa 11 odbrambenih kula i Despotovom kulom, zadužbina despota Stefana Lazarevića (1407–1418) (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'},
        {'url': 'images/monasteries/manasija_gal_1.jpg', 'caption': 'Crkva Svete Trojice u Manasiji, remek-delo moravske arhitekture sa pet kupola (Izvor: UNESCO Tentative List / Vikimedijina ostava)'},
        {'url': 'images/monasteries/manasija_gal_2.jpg', 'caption': 'Despotova kula i severni odbrambeni bedemi manastira Manasija (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'}
    ],
    'miljkovo': [
        {'url': 'images/monasteries/miljkovo.jpg', 'caption': 'Crkva Vavedenja Presvete Bogorodice Miljkovog manastira na obali Velike Morave (Izvor: Eparhija braničevska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/miljkovo_gal_1.jpg', 'caption': 'Pogled na manastirsku portu i konake u Miljkovom manastiru (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/miljkovo_gal_2.jpg', 'caption': 'Zapadni ulaz sa zvonikom manastirske crkve u Miljkovu (Izvor: Vikimedijina ostava)'}
    ],
    'namasija': [
        {'url': 'images/monasteries/namasija.jpg', 'caption': 'Konzervirani ostaci manastirske crkve Svetog Nikole u kompleksu Namasija iz 14. veka (Izvor: Zavod za zaštitu spomenika kulture Kragujevac / Wiki.Biseri)'},
        {'url': 'images/monasteries/namasija_gal_1.jpg', 'caption': 'Zidine i oltarski deo crkve Namasija u kanjonu reke Crnice kod Zabrege (Izvor: Wiki.Biseri III / Vikimedijina ostava)'},
        {'url': 'images/monasteries/namasija_gal_2.jpg', 'caption': 'Arheološki lokalitet Namasija u zaštićenom području Petruške oblasti (Izvor: Wiki.Biseri III / Vikimedijina ostava)'}
    ],
    'nimnik': [
        {'url': 'images/monasteries/nimnik_gal_1.jpg', 'caption': 'Crkva Svetog Nikole manastira Nimnik u hrastovoj šumi kod Požarevca (zadužbina vojvode Bogosava, 1376) (Izvor: Eparhija braničevska / Wiki.Đerdap)'},
        {'url': 'images/monasteries/nimnik.jpg', 'caption': 'Manastirski kompleks Nimnik sa konakom i portom (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/nimnik_gal_2.jpg', 'caption': 'Kapela Svetinja na grobu svete mučenice Nikoline u manastiru Nimnik (Izvor: Wiki.Đerdap I / Vikimedijina ostava)'}
    ],
    'pokajnica': [
        {'url': 'images/monasteries/pokajnica_gal_1.jpg', 'caption': 'Crkva brvnara Prenosa moštiju Svetog Nikole u Pokajnici, podignuta 1818. godine kao zadužbina Vujice Vulićevića (Izvor: Spomenici kulture od izuzetnog značaja / Wiki.Šumadija)'},
        {'url': 'images/monasteries/pokajnica_gal_2.jpg', 'caption': 'Zapadni drveni trem i krov pokriven šindrom na crkvi brvnari Pokajnica (Izvor: Wiki.Šumadija XVI / Vikimedijina ostava)'},
        {'url': 'images/monasteries/pokajnica.jpg', 'caption': 'Pogled na crkvu brvnaru i drvenu zvonaru manastira Pokajnica (Izvor: Zavod za zaštitu spomenika kulture Smederevo / Vikimedijina ostava)'}
    ],
    'radosin': [
        {'url': 'images/monasteries/radosin.jpg', 'caption': 'Hram Rođenja Presvete Bogorodice manastira Radošin iz 15. veka na obali Morave (Izvor: Eparhija braničevska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/radosin_gal_1.jpg', 'caption': 'Manastirski konak i uređena porta u Radošinu (Izvor: Wiki.Biseri I / Vikimedijina ostava)'},
        {'url': 'images/monasteries/radosin_gal_2.jpg', 'caption': 'Severna fasada i zvonik manastirske crkve u Radošinu (Izvor: Wiki.Biseri I / Vikimedijina ostava)'}
    ],
    'ravanica': [
        {'url': 'images/monasteries/ravanica_gal_1.jpg', 'caption': 'Crkva Vaznesenja Gospodnjeg u Ravanici, glavna zadužbina i grobno mesto Svetog kneza Lazara (1375–1377) (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'},
        {'url': 'images/monasteries/ravanica_gal_2.jpg', 'caption': 'Petokupolna moravska arhitektura Ravanice sa karakterističnom spoljnom keramičkom i reljefnom ornamentikom (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/ravanica.jpg', 'caption': 'Ravanicki hram sa ostacima srednjovekovnih utvrđenih zidina i kula kneza Lazara (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'}
    ],
    'reskovica': [
        {'url': 'images/monasteries/reskovica.jpg', 'caption': 'Višespratna crkva Sabora Svetih apostola manastira Reškovica u Homoljskim planinama (Izvor: Eparhija braničevska / Wiki.Zaleđe)'},
        {'url': 'images/monasteries/reskovica_gal_1.jpg', 'caption': 'Pogled na manastirski kompleks Reškovica u šumovitoj klisuri istoimene reke (Izvor: Wiki.Zaleđe III / Vikimedijina ostava)'},
        {'url': 'images/monasteries/reskovica_gal_2.jpg', 'caption': 'Arhitektonska celina novog manastirskog hrama u Reškovici (Izvor: Wiki.Zaleđe III / Vikimedijina ostava)'}
    ],
    'rukumija': [
        {'url': 'images/monasteries/rukumija_gal_2.jpg', 'caption': 'Crkva Svetog Nikole manastira Rukumija kod Kostolca, zadužbina iz doba kneza Lazara (Izvor: Eparhija braničevska / Wiki.Zaleđe)'},
        {'url': 'images/monasteries/rukumija_gal_1.jpg', 'caption': 'Zvonara i konak u manastirskom dvorištu Rukumije (Izvor: Wiki.Zaleđe II / Vikimedijina ostava)'},
        {'url': 'images/monasteries/rukumija.jpg', 'caption': 'Manastirska porta sa cvetnim vrtom i prilazom hramu u Rukumiji (Izvor: Vikimedijina ostava)'}
    ],
    'sisojevac': [
        {'url': 'images/monasteries/sisojevac.jpg', 'caption': 'Crkva Svetog Sisoja Velikog iz 1380. godine, zadužbina monaha Sisoja Sinaita sa očuvanim moravskim freskama (Izvor: Zavod za zaštitu spomenika kulture Kragujevac / Vikimedijina ostava)'},
        {'url': 'images/monasteries/sisojevac_gal_2.jpg', 'caption': 'Manastirski kompleks Sisojevac na izvoru reke Crnice podno Kučajskih planina (Izvor: Wiki.Biseri I / Vikimedijina ostava)'}
    ],
    'tumane': [
        {'url': 'images/monasteries/tumane_gal_1.jpg', 'caption': 'Crkva Svetog arhangela Gavrila manastira Tumane, zadužbina Miloša Obilića u kojoj počivaju mošti Svetog Zosima i Svetog Jakova (Izvor: Zvanični sajt manastira Tumane / Vikimedijina ostava)'},
        {'url': 'images/monasteries/tumane_gal_2.jpg', 'caption': 'Kompleks manastira Tumane sa novim konacima u dolini Golubačkih planina (Izvor: Eparhija braničevska / Wiki.Đerdap)'},
        {'url': 'images/monasteries/tumane.jpg', 'caption': 'Prilaz manastirskom hramu u Tumanu kod Golubca (Izvor: Vikimedijina ostava)'}
    ],
    'zdrelo': [
        {'url': 'images/monasteries/zdrelo.jpg', 'caption': 'Hram Svete Trojice manastira Ždrelo na uzvišenju na ulazu u Gornjačku klisuru (Izvor: Eparhija braničevska / Wiki.Zaleđe)'},
        {'url': 'images/monasteries/zdrelo_gal_2.jpg', 'caption': 'Manastirski kompleks i konak u Ždrelu podno Homoljskih planina (Izvor: Wiki.Zaleđe III / Vikimedijina ostava)'}
    ],
    'zlatenac': [
        {'url': 'images/monasteries/zlatenac_gal_1.jpg', 'caption': 'Crkva Svetih vrača Kozme i Damjana manastira Zlatenac iz 15. veka na litici iznad Morave (Izvor: Eparhija braničevska / Wiki.Biseri)'},
        {'url': 'images/monasteries/zlatenac_gal_2.jpg', 'caption': 'Pogled na manastir Zlatenac i Resavsku ravnicu kod Svilajnca (Izvor: Wiki.Biseri I / Vikimedijina ostava)'}
    ],

    # ==================== 5. EPARHIJA KRUŠEVAČKA ====================
    'drenca': [
        {'url': 'images/monasteries/drenca_gal_1.jpg', 'caption': 'Obnovljena crkva Vavedenja Presvete Bogorodice (Dušmanica) manastira Drenča iz 1382. godine u Župi aleksandrovačkoj (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'},
        {'url': 'images/monasteries/drenca_gal_2.jpg', 'caption': 'Moravska kamena plastika i rozete na fasadi crkve u Drenči (Izvor: Spomenici kulture od velikog značaja / Wiki.Rasina)'},
        {'url': 'images/monasteries/drenca.jpg', 'caption': 'Arheološki ostaci i obnovljeni hram manastira Drenča (Izvor: Vikimedijina ostava)'}
    ],
    'grabovo': [
        {'url': 'images/monasteries/grabovo.jpg', 'caption': 'Crkva Svetog arhangela Mihaila manastira Grabovo u opštini Trstenik (Izvor: Eparhija kruševačka / Vikimedijina ostava)'}
    ],
    'komorane': [
        {'url': 'images/monasteries/komorane.jpg', 'caption': 'Crkva Svetog Nikole Čudotvorca manastira Komorane kod Kruševca (Izvor: Eparhija kruševačka / Wiki.Rasina)'},
        {'url': 'images/monasteries/komorane_gal_1.jpg', 'caption': 'Zapadni ulaz i manastirska porta u Komoranu (Izvor: Wiki.Rasina II / Vikimedijina ostava)'},
        {'url': 'images/monasteries/komorane_gal_2.jpg', 'caption': 'Severoistočna strana hrama manastira Komorane (Izvor: Wiki.Rasina II / Vikimedijina ostava)'}
    ],
    'ljubostinja': [
        {'url': 'images/monasteries/ljubostinja_gal_1.jpg', 'caption': 'Crkva Uspenja Presvete Bogorodice u Ljubostinji, glavna zadužbina kneginje Milice i grobno mesto monahinje Jefimije (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/ljubostinja_gal_2.jpg', 'caption': 'Zapadna fasada hrama sa kamenim rozetama i prepletima graditelja Radeta Borovića (Radeta Neimara) (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'},
        {'url': 'images/monasteries/ljubostinja.jpg', 'caption': 'Manastirski kompleks Ljubostinja u dolini Ljubostinjske reke kod Trstenika (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'}
    ],
    'veluce': [
        {'url': 'images/monasteries/veluce_gal_1.jpg', 'caption': 'Crkva Vavedenja Presvete Bogorodice manastira Veluće iz 1377. godine, zadužbina vlastelinke Mare (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Wiki.Rasina)'},
        {'url': 'images/monasteries/veluce_gal_2.jpg', 'caption': 'Raskošna reljefna dekoracija, šahovska polja i kamena plastika na fasadi crkve u Veluću (Izvor: Spomenici kulture od velikog značaja / Wiki.Rasina)'},
        {'url': 'images/monasteries/veluce.jpg', 'caption': 'Manastirski kompleks Veluće u podnožju planine Gledić (Izvor: Vikimedijina ostava)'}
    ],

    # ==================== 6. EPARHIJA MILEŠEVSKA ====================
    'davidovica': [
        {'url': 'images/monasteries/davidovica.jpg', 'caption': 'Crkva Bogojavljenja manastira Davidovica iz 1281. godine, zadužbina monaha Davida (župana Dimitrija Nemanjića) na obali Lima (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/davidovica_gal_1.jpg', 'caption': 'Zapadni portal i raška jednobrodna arhitektura manastirske crkve u Davidovici (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/davidovica_gal_2.jpg', 'caption': 'Manastirski kompleks Davidovica kod Brodareva sa dolinom reke Lim (Izvor: Vikimedijina ostava)'}
    ],
    'jabuka': [
        {'url': 'images/monasteries/jabuka.jpg', 'caption': 'Crkva Svetog proroka Ilije manastira Jabuka na visoravni kod Prijepolja (Izvor: Eparhija mileševska / Vikimedijina ostava)'}
    ],
    'janja': [
        {'url': 'images/monasteries/janja.jpg', 'caption': 'Obnovljeni hram Svetih pravednih Joakima i Ane manastira Janja u kanjonu reke Uvac kod Priboja (14. vek) (Izvor: Eparhija mileševska / Vikimedijina ostava)'}
    ],
    'kumanica': [
        {'url': 'images/monasteries/kumanica.jpg', 'caption': 'Crkva Svetog arhistratiga Gavrila manastira Kumanica u klisuri Lima, srednjovekovna nemanjićka svetinja (Izvor: Eparhija mileševska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/kumanica_gal_2.jpg', 'caption': 'Manastirski kompleks Kumanica uz kanjon Lima na granici Srbije i Crne Gore (Izvor: Vikimedijina ostava)'}
    ],
    'mazici': [
        {'url': 'images/monasteries/mazici.jpg', 'caption': 'Ostaci hrama Svetog Đorđa u Mažićima iz 12. veka na obali Potpećkog jezera kod Priboja (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/mazici_gal_1.jpg', 'caption': 'Arheološki ostaci jedne od najstarijih srpskih srednjovekovnih bolnica iz 12. veka u manastiru Mažići (Izvor: Arheološki institut Beograd / Vikimedijina ostava)'}
    ],
    'mileseva': [
        {'url': 'images/monasteries/mileseva_gal_1.jpg', 'caption': 'Crkva Vaznesenja Gospodnjeg u Mileševi iz 1219. godine, zadužbina kralja Vladislava (hram u kome se nalazi svetski poznata freska Belog Anđela) (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/mileseva_gal_2.jpg', 'caption': 'Pogled na manastirski kompleks Mileševa sa konacima i rečicom Mileševkom podno srednjovekovnog grada Mileševca (Izvor: Eparhija mileševska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/mileseva.jpg', 'caption': 'Zapadna fasada i ulazni deo priprate manastira Mileševa (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'}
    ],
    'pribojska-banja': [
        {'url': 'images/monasteries/pribojska-banja.jpg', 'caption': 'Crkva Svetog Nikole u Pribojskoj Banji, drevno sedište Dabarske episkopije koju je 1220. godine osnovao Sveti Sava (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/pribojska-banja_gal_1.jpg', 'caption': 'Pogled na manastirski kompleks Svetog Nikole u Pribojskoj Banji (Izvor: Eparhija mileševska / Vikimedijina ostava)'}
    ],
    'pustinja-valjevska': [
        {'url': 'images/monasteries/pustinja-valjevska_gal_1.jpg', 'caption': 'Crkva Vavedenja Presvete Bogorodice manastira Pustinja iz 13. veka u kanjonu reke Jablanice kod Poćute (hram poznat po freski Svetog Jovana Krilatog iz 1622. godine) (Izvor: Zavod za zaštitu spomenika kulture Valjevo / Vikimedijina ostava)'}
    ],

    # ==================== 7. EPARHIJA VALJEVSKA ====================
    'bogovadja': [
        {'url': 'images/monasteries/bogovadja_gal_1.jpg', 'caption': 'Hram Svetog Đorđa u Bogovađi iz 1852. godine sa spratnim konakom u kome je zasedao Praviteljstvujući sovjet srpski (Izvor: Zavod za zaštitu spomenika kulture Valjevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/bogovadja.jpg', 'caption': 'Zapadna fasada manastirske crkve u Bogovađi sa baroknim zvonikom (Izvor: Eparhija valjevska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/bogovadja_gal_2.jpg', 'caption': 'Pogled na manastirski kompleks Bogovađa podno šumovitih obronaka (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'}
    ],
    'dokmir': [
        {'url': 'images/monasteries/dokmir.jpg', 'caption': 'Crkva Vavedenja Presvete Bogorodice manastira Dokmir iz druge polovine 14. veka (Izvor: Zavod za zaštitu spomenika kulture Valjevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/dokmir_gal_1.jpg', 'caption': 'Ikonostas i unutrašnjost crkve u Dokmiru sa čudotvornom ikonom Bogorodice Dokmirske (Izvor: Eparhija valjevska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/dokmir_gal_2.jpg', 'caption': 'Zvonik i manastirska porta u Dokmiru kod Uba (Izvor: Vikimedijina ostava)'}
    ],
    'grabovac': [
        {'url': 'images/monasteries/grabovac.jpg', 'caption': 'Crkva Prenosa moštiju Svetog oca Nikolaja u Grabovcu kod Obrenovca podignuta na temeljima hrama kralja Dragutina (Izvor: Eparhija valjevska / Vikimedijina ostava)'}
    ],
    'jovanja-valjevska': [
        {'url': 'images/monasteries/jovanja-valjevska_gal_1.jpg', 'caption': 'Crkva Rođenja Svetog Jovana Krstitelja u Jovanskoj klisuri reke Jablanice iz 15. veka (Izvor: Zavod za zaštitu spomenika kulture Valjevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/jovanja-valjevska.jpg', 'caption': 'Prilaz hramu i manastirski konak u Jovanji kod Valjeva (Izvor: Eparhija valjevska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/jovanja-valjevska_gal_2.jpg', 'caption': 'Pogled na manastir Jovanja u dolini reke Jablanice (Izvor: Vikimedijina ostava)'}
    ],
    'lelic': [
        {'url': 'images/monasteries/lelic_gal_1.jpg', 'caption': 'Hram Svetog vladike Nikolaja i Svetog Nikole u Leliću, zadužbina vladike Nikolaja Velimirovića i njegovog oca Dragomira (Izvor: Zvanični sajt manastira Lelić / Vikimedijina ostava)'},
        {'url': 'images/monasteries/lelic_gal_2.jpg', 'caption': 'Kivot sa svetim netruležnim moštima Svetog vladike Nikolaja Žičkog i Ohridskog u manastiru Lelić (Izvor: Eparhija valjevska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/lelic.jpg', 'caption': 'Zvonik i manastirski kompleks u Leliću kod Valjeva (Izvor: Vikimedijina ostava)'}
    ],
    'plutina': [
        {'url': 'images/monasteries/plutina.jpg', 'caption': 'Crkva manastira Plutina posvećena Svetom arhangelu Gavrilu (Izvor: Eparhija valjevska / Vikimedijina ostava)'}
    ],
    'celije': [
        {'url': 'images/monasteries/celije_gal_1.jpg', 'caption': 'Crkva Svetog arhangela Mihaila manastira Ćelije u klisuri reke Gradac, duhovno središte Svetog ave Justina Popovića (Izvor: Zvanična monografija manastira Ćelije / Vikimedijina ostava)'},
        {'url': 'images/monasteries/celije_gal_2.jpg', 'caption': 'Grobno mesto i novi trooltarni hram Svetog Justina Ćelijskog u manastiru Ćelije (Izvor: Eparhija valjevska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/celije.jpg', 'caption': 'Pogled na stari hram i konake manastira Ćelije u kanjonu Gradca (Izvor: Zavod za zaštitu spomenika kulture Valjevo / Vikimedijina ostava)'}
    ],

    # ==================== 8. EPARHIJA TIMOČKA ====================
    'bukovo': [
        {'url': 'images/monasteries/bukovo_gal_1.jpg', 'caption': 'Crkva Svetog oca Nikolaja Čudotvorca manastira Bukovo kod Negotina, zadužbina kralja Milutina sa kraja 13. veka (Izvor: Eparhija timočka / Vikimedijina ostava)'},
        {'url': 'images/monasteries/bukovo.jpg', 'caption': 'Severna fasada i zvonik manastira Bukovo sa čudotvornom ikonom Bogorodice Bukovske (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'},
        {'url': 'images/monasteries/bukovo_gal_2.jpg', 'caption': 'Manastirski konak sa cvetnim vrtom i vinogradima u Bukovu (Izvor: Vikimedijina ostava)'}
    ],
    'gornja-kamenica': [
        {'url': 'images/monasteries/gornja-kamenica.jpg', 'caption': 'Crkva Presvete Bogorodice u Gornjoj Kamenici iz 14. veka sa jedinstvenom dvospratnom pripratom i kulama (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'},
        {'url': 'images/monasteries/gornja-kamenica_gal_1.jpg', 'caption': 'Freska Svetih ratnika Teodora Tirona i Teodora Stratilata na konjima na južnom zidu naosa (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/gornja-kamenica_gal_2.jpg', 'caption': 'Zapadni portal i kule crkve Presvete Bogorodice u Gornjoj Kamenici kod Knjaževca (Izvor: Vikimedijina ostava)'}
    ],
    'grliste': [
        {'url': 'images/monasteries/grliste_gal_1.jpg', 'caption': 'Hram Svetih apostola Petra i Pavla manastira Grlište iz 13. veka na obali Grliškog jezera (Izvor: Eparhija timočka / Vikimedijina ostava)'},
        {'url': 'images/monasteries/grliste.jpg', 'caption': 'Zapadni ulaz i manastirska porta u Grlištu kod Zaječara (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/grliste_gal_2.jpg', 'caption': 'Pogled na manastirski kompleks Grlište i jezersku obalu (Izvor: Vikimedijina ostava)'}
    ],
    'jermencic': [
        {'url': 'images/monasteries/jermencic.jpg', 'caption': 'Crkva Svetih arhangela Gavrila i Mihaila manastira Jermenčić na planini Ozren, podignuta 1392. godine (Izvor: Eparhija timočka / Vikimedijina ostava)'},
        {'url': 'images/monasteries/jermencic_gal_1.jpg', 'caption': 'Manastirska česma i porta u gustoj šumi planine Ozren kod Sokobanje (Izvor: Turistička organizacija Sokobanja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/jermencic_gal_2.jpg', 'caption': 'Prilaz crkvi Svetih arhangela u Jermenčiću (Izvor: Vikimedijina ostava)'}
    ],
    'koroglas': [
        {'url': 'images/monasteries/koroglas_gal_1.jpg', 'caption': 'Srednjovekovna crkva manastira Koroglaš iz 14. veka u dolini kod Negotina, vezana za predanje o pogibiji Marka Kraljevića na Rovinama (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'},
        {'url': 'images/monasteries/koroglas.jpg', 'caption': 'Oltarska apsida sa moravskom keramičkom dekoracijom crkve u Koroglašu (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'},
        {'url': 'images/monasteries/koroglas_gal_2.jpg', 'caption': 'Zapadna fasada i arheološki ostaci nekropole manastira Koroglaš (Izvor: Vikimedijina ostava)'}
    ],
    'krepicevac': [
        {'url': 'images/monasteries/krepicevac.jpg', 'caption': 'Crkva Uspenja Presvete Bogorodice manastira Krepičevac iz 15. veka u klisuri Radovanske reke kod Boljevca (Izvor: Eparhija timočka / Vikimedijina ostava)'},
        {'url': 'images/monasteries/krepicevac_gal_1.jpg', 'caption': 'Ktitorki natpis i freske sa portretima ktitora Georgija i žene mu Zore u Krepičevcu (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/krepicevac_gal_2.jpg', 'caption': 'Pogled na manastir Krepičevac podno planine Rtanj (Izvor: Vikimedijina ostava)'}
    ],
    'lapusnja': [
        {'url': 'images/monasteries/lapusnja_gal_1.jpg', 'caption': 'Crkva Svetog Nikole manastira Lapušnja iz 1501. godine (zadužbina vojvode Jovana Radula) podno planine Rtanj (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'},
        {'url': 'images/monasteries/lapusnja.jpg', 'caption': 'Monumentalna kupola i kameni svodovi crkve Svetog Nikole u Lapušnji (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/lapusnja_gal_2.jpg', 'caption': 'Oltarska apsida i arhitektonski ostaci manastira Lapušnja kod Boljevca (Izvor: Vikimedijina ostava)'}
    ],
    'lozica': [
        {'url': 'images/monasteries/lozica.jpg', 'caption': 'Crkva Svetog arhangela Gavrila manastira Lozica iz 14. veka u dolini Krivovirske Timoke kod Krive Feje (Izvor: Eparhija timočka / Vikimedijina ostava)'},
        {'url': 'images/monasteries/lozica_gal_1.jpg', 'caption': 'Zapadni trem i zvonik crkve manastira Lozica (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/lozica_gal_2.jpg', 'caption': 'Manastirska porta i lekoviti izvor vode u Lozici (Izvor: Vikimedijina ostava)'}
    ],
    'manastirica-timocka': [
        {'url': 'images/monasteries/manastirica-timocka.jpg', 'caption': 'Hram Vaznesenja Gospodnjeg manastira Manastirica kod Kladova, zadužbina iz 14. veka u dolini rečice Manastirice (Izvor: Eparhija timočka / Vikimedijina ostava)'},
        {'url': 'images/monasteries/manastirica-timocka_gal_1.jpg', 'caption': 'Manastirska porta i konak u Manastirici kod Kladova (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/manastirica-timocka_gal_2.jpg', 'caption': 'Pogled na hram Vaznesenja Gospodnjeg u Manastirici (Izvor: Vikimedijina ostava)'}
    ],
    'suvodol': [
        {'url': 'images/monasteries/suvodol_gal_1.jpg', 'caption': 'Hram Rođenja Presvete Bogorodice manastira Suvodol iz 1869. godine (podignut na temeljima crkve kneza Lazara iz 14. veka) u selu Selačka (Izvor: Eparhija timočka / Vikimedijina ostava)'},
        {'url': 'images/monasteries/suvodol.jpg', 'caption': 'Pogled na manastirski kompleks Suvodol sa slapom lekovite vode i pećinama (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'},
        {'url': 'images/monasteries/suvodol_gal_2.jpg', 'caption': 'Spratni konak i uređeno dvorište manastira Suvodol kod Zaječara (Izvor: Vikimedijina ostava)'}
    ],

    # ==================== 9. EPARHIJA VRANJSKA ====================
    'bresnica': [
        {'url': 'images/monasteries/bresnica.jpg', 'caption': 'Crkva Svete Petke manastira Bresnica kod Bosilegrada (Izvor: Eparhija vranjska / Vikimedijina ostava)'}
    ],
    'gornje-zapsko': [
        {'url': 'images/monasteries/gornje-zapsko.jpg', 'caption': 'Hram Svetog prvomučenika i arhiđakona Stefana u Gornjem Žapskom, zadužbina iz doba Nemanjića (Izvor: Eparhija vranjska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/gornje-zapsko_gal_1.jpg', 'caption': 'Zapadna fasada crkve sa spratnim drvenim konakom u Gornjem Žapskom kod Vranja (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/gornje-zapsko_gal_2.jpg', 'caption': 'Pogled na manastirski kompleks Svetog Stefana u Gornjem Žapskom (Izvor: Vikimedijina ostava)'}
    ],
    'dubnica': [
        {'url': 'images/monasteries/dubnica.jpg', 'caption': 'Crkva Svetih apostola Petra i Pavla manastira Dubnica iz 14. veka kod Vranja (Izvor: Eparhija vranjska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/dubnica_gal_1.jpg', 'caption': 'Manastirska porta i konak u Dubnici podno planine Rujan (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/dubnica_gal_2.jpg', 'caption': 'Južna strana hrama Svetih apostola u Dubnici (Izvor: Vikimedijina ostava)'}
    ],
    'kacapun': [
        {'url': 'images/monasteries/kacapun.jpg', 'caption': 'Crkva Svetog proroka Ilije manastira Kacapun iz 13. veka u klisuri Kacapunske reke kod Vladičinog Hana (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'},
        {'url': 'images/monasteries/kacapun_gal_1.jpg', 'caption': 'Jednobrodna crkva od lomljenog kamena pokrivena kamenim pločama u Kacapunu (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'},
        {'url': 'images/monasteries/kacapun_gal_2.jpg', 'caption': 'Pogled na manastir Kacapun u šumovitom useku (Izvor: Vikimedijina ostava)'}
    ],
    'kozji-dol': [
        {'url': 'images/monasteries/kozji-dol.jpg', 'caption': 'Crkva Svetog Preobraženja Gospodnjeg u Kozjem Dolu kod Trgovišta (Izvor: Eparhija vranjska / Vikimedijina ostava)'}
    ],
    'lepcince': [
        {'url': 'images/monasteries/lepcince.jpg', 'caption': 'Hram Svetog velikomučenika Pantelejmona u Lepčincu iz 14. veka sa lekovitim izvorom vode (Izvor: Eparhija vranjska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/lepcince_gal_1.jpg', 'caption': 'Zapadni ulaz i manastirski konak u Lepčincu kod Vranja (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/lepcince_gal_2.jpg', 'caption': 'Pogled na manastirsko dvorište i hram Svetog Pantelejmona (Izvor: Vikimedijina ostava)'}
    ],
    'mrtvica': [
        {'url': 'images/monasteries/mrtvica.jpg', 'caption': 'Crkva Uspenja Presvete Bogorodice manastira Mrtvica iz 14. veka u klisuri Južne Morave kod Vladičinog Hana (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'},
        {'url': 'images/monasteries/mrtvica_gal_1.jpg', 'caption': 'Freska Uspenja Presvete Bogorodice i živopis iz 17. veka u naosu crkve u Mrtvici (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/mrtvica_gal_2.jpg', 'caption': 'Zapadna fasada sa kamenim zvonikom crkve u Mrtvici (Izvor: Vikimedijina ostava)'}
    ],
    'palja': [
        {'url': 'images/monasteries/palja.jpg', 'caption': 'Crkva Vavedenja Presvete Bogorodice manastira Palja iz 13. veka u planinskom predelu kod Surdulice (Izvor: Eparhija vranjska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/palja_gal_1.jpg', 'caption': 'Oltarska apsida i krov pokriven kamenim pločama manastirske crkve u Palji (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'},
        {'url': 'images/monasteries/palja_gal_2.jpg', 'caption': 'Pogled na manastirski kompleks Palja i lekoviti izvor Svetog Save (Izvor: Vikimedijina ostava)'}
    ],
    'prohor-pcinski': [
        {'url': 'images/monasteries/prohor-pcinski_gal_1.jpg', 'caption': 'Hram Svetog Prohora Pčinjskog i monumentalni Vranjski konak, zadužbina vizantijskog cara Romana Diogena (11. vek) i kralja Milutina (14. vek) na reci Pčinji (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'},
        {'url': 'images/monasteries/prohor-pcinski_gal_2.jpg', 'caption': 'Kivot sa mirotočivim moštima Prepodobnog Prohora Pčinjskog u južnom delu oltara (Izvor: Eparhija vranjska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/prohor-pcinski.jpg', 'caption': 'Pogled na manastirski kompleks Prohor Pčinjski podno planine Kozjak (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'}
    ],
    'sobina': [
        {'url': 'images/monasteries/sobina.jpg', 'caption': 'Crkva Uspenja Presvete Bogorodice manastira Sobina u Vranju (Izvor: Eparhija vranjska / Vikimedijina ostava)'}
    ],
    'soderce': [
        {'url': 'images/monasteries/soderce.jpg', 'caption': 'Crkva Svete velikomučenice Marine (Ognjene Marije) u Sodercu kod Vranja (Izvor: Eparhija vranjska / Vikimedijina ostava)'}
    ],
    'sveti-nikola': [
        {'url': 'images/monasteries/sveti-nikola.jpg', 'caption': 'Srednjovekovna crkva Svetog Nikole u Vranju, metoh manastira Hilandara iz prve polovine 14. veka (zadužbina kralja Stefana Dečanskog) (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'},
        {'url': 'images/monasteries/sveti-nikola_gal_1.jpg', 'caption': 'Južna fasada zidane crkve Svetog Nikole sa krovnim vencem od opeke u Vranju (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/sveti-nikola_gal_2.jpg', 'caption': 'Zapadni ulaz u hram Svetog Nikole u Vranju (Izvor: Vikimedijina ostava)'}
    ],

    # ==================== 10. EPARHIJA ŠABAČKA ====================
    'bojic': [
        {'url': 'images/monasteries/bojic.jpg', 'caption': 'Crkva Svetog Vasilija Ostroškog manastira Bojić u Pocerini (Izvor: Eparhija šabačka / Vikimedijina ostava)'}
    ],
    'citluk': [
        {'url': 'images/monasteries/citluk.jpg', 'caption': 'Hram Rođenja Presvete Bogorodice manastira Čitluk kod Ljubovije (Izvor: Eparhija šabačka / Vikimedijina ostava)'}
    ],
    'cokesina': [
        {'url': 'images/monasteries/cokesina_gal_1.jpg', 'caption': 'Crkva Rođenja Presvete Bogorodice manastira Čokešina podno Cera, zadužbina Miloša Obilića i poprište Boja na Čokešini 1804. godine (Izvor: Zavod za zaštitu spomenika kulture Valjevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/cokesina_gal_2.jpg', 'caption': 'Čudotvorna ikona Bogorodice Čokešinske i unutrašnjost hrama u Čokešini (Izvor: Eparhija šabačka / Vikimedijina ostava)'},
        {'url': 'images/monasteries/cokesina.jpg', 'caption': 'Manastirski konak i porta sa spomen-kosturnicom braće Nedić u Čokešini (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'}
    ],
    'ilinje': [
        {'url': 'images/monasteries/ilinje.jpg', 'caption': 'Crkva Svetog proroka Ilije manastira Ilinje u Očagama kod Bogatića u Mačvi (Izvor: Eparhija šabačka / Vikimedijina ostava)'},
        {'url': 'images/monasteries/ilinje_gal_1.jpg', 'caption': 'Zapadna fasada sa zvonikom manastira Ilinje (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/ilinje_gal_2.jpg', 'caption': 'Manastirski konak i uređena porta u Ilinju (Izvor: Vikimedijina ostava)'}
    ],
    'kaona': [
        {'url': 'images/monasteries/kaona_gal_1.jpg', 'caption': 'Crkva Svetog arhangela Mihaila manastira Kaona iz 14. veka (zadužbina Ikonije, sestre Miloša Obilića) u Posavotamnavi (Izvor: Zavod za zaštitu spomenika kulture Valjevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/kaona_gal_2.jpg', 'caption': 'Krstionica u rano-hrišćanskom stilu i manastirsko jezero sa izvorom u Kaoni (Izvor: Eparhija šabačka / Vikimedijina ostava)'},
        {'url': 'images/monasteries/kaona.jpg', 'caption': 'Pogled na manastirski kompleks Kaona sa zvonikom i konacima (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'}
    ],
    'krivaja': [
        {'url': 'images/monasteries/krivaja.jpg', 'caption': 'Crkva Preobraženja Gospodnjeg manastira Krivaja u Ceru iz 15. veka (Izvor: Eparhija šabačka / Vikimedijina ostava)'},
        {'url': 'images/monasteries/krivaja_gal_1.jpg', 'caption': 'Severna fasada crkve manastira Krivaja sa tremom (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/krivaja_gal_2.jpg', 'caption': 'Manastirska porta i zvonara u Krivaji kod Šapca (Izvor: Vikimedijina ostava)'}
    ],
    'petkovica-sabacka': [
        {'url': 'images/monasteries/petkovica-sabacka_gal_1.jpg', 'caption': 'Crkva Svete Petke manastira Petkovica na Ceru, zadužbina kralja Dragutina sa kraja 13. veka (Izvor: Zavod za zaštitu spomenika kulture Valjevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/petkovica-sabacka.jpg', 'caption': 'Zapadna fasada sa drvenim tremom i zvonarom manastira Petkovica (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/petkovica-sabacka_gal_2.jpg', 'caption': 'Pogled na manastirsku crkvu i konake Petkovice na obroncima Cera (Izvor: Vikimedijina ostava)'}
    ],
    'radovasnica': [
        {'url': 'images/monasteries/radovasnica_gal_1.jpg', 'caption': 'Crkva Pokrova Presvete Bogorodice manastira Radovašnica podno planine Cer, zadužbina kralja Dragutina (Izvor: Zavod za zaštitu spomenika kulture Valjevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/radovasnica.jpg', 'caption': 'Stara kapela Svetog arhangela Gavrila u steni kod manastira Radovašnica (Izvor: Eparhija šabačka / Vikimedijina ostava)'},
        {'url': 'images/monasteries/radovasnica_gal_2.jpg', 'caption': 'Pogled na manastirski kompleks Radovašnica u dolini Radovašničke reke (Izvor: Vikimedijina ostava)'}
    ],
    'rozanj': [
        {'url': 'images/monasteries/rozanj.jpg', 'caption': 'Crkva Svetog Vasilija Ostroškog manastira Rožanj na istoimenom vrhu planine Sokolac kod Krupnja (Izvor: Eparhija šabačka / Vikimedijina ostava)'}
    ],
    'rujevac': [
        {'url': 'images/monasteries/rujevac.jpg', 'caption': 'Crkva Svete Ognjene Marije manastira Rujevac kod Ljubovije (Izvor: Eparhija šabačka / Vikimedijina ostava)'}
    ],
    'soko-grad': [
        {'url': 'images/monasteries/soko-grad_gal_1.jpg', 'caption': 'Hram Svetog Nikolaja Srpskog u manastiru Soko Grad podno turske tvrđave Soko, zadužbina episkopa Lavrentija (Izvor: Zvanična prezentacija manastira Soko Grad / Vikimedijina ostava)'},
        {'url': 'images/monasteries/soko-grad_gal_2.jpg', 'caption': 'Zlatni krst na steni Sokolske planine i spratni konaci manastira Soko Grad (Izvor: Eparhija šabačka / Vikimedijina ostava)'},
        {'url': 'images/monasteries/soko-grad.jpg', 'caption': 'Pogled na manastirski kompleks Soko Grad u kanjonu Sokolske reke kod Ljubovije (Izvor: Vikimedijina ostava)'}
    ],
    'tronosa': [
        {'url': 'images/monasteries/tronosa_gal_1.jpg', 'caption': 'Crkva Vavedenja Presvete Bogorodice manastira Tronoša iz 1317. godine (zadužbina kralja Dragutina i kraljice Kataline) kod Loznice (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/tronosa_gal_2.jpg', 'caption': 'Kapela sa česmom Devet Jugovića i lekovitom vodom ispred manastira Tronoša (Izvor: Turistička organizacija Loznica / Vikimedijina ostava)'},
        {'url': 'images/monasteries/tronosa.jpg', 'caption': 'Barokni zvonik i spratni konaci manastira Tronoša, gde se školovao Vuk Stefanović Karadžić (Izvor: Zavod za zaštitu spomenika kulture Valjevo / Vikimedijina ostava)'}
    ],
    'banja-koviljaca': [
        {'url': 'images/monasteries/banja-koviljaca.jpg', 'caption': 'Hram Svetog arhangela Gavrila u Banji Koviljači (Izvor: Eparhija šabačka / Vikimedijina ostava)'}
    ],

    # ==================== 11. EPARHIJA SREMSKA ====================
    'beocin': [
        {'url': 'images/monasteries/beocin_gal_1.jpg', 'caption': 'Crkva Vaznesenja Gospodnjeg manastira Beočin iz 1740. godine sa monumentalnim baroknim zvonikom i čudotvornom ikonom Bogorodice Beočinske (Izvor: Pokrajinski zavod za zaštitu spomenika kulture / Wiki.Vojvodina)'},
        {'url': 'images/monasteries/beocin.jpg', 'caption': 'Južno pročelje hrama i uređeni francuski park u manastiru Beočin (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/beocin_gal_2.jpg', 'caption': 'Barokni konaci i ulazna kapija manastira Beočin (Izvor: Vikimedijina ostava)'}
    ],
    'berkasovo': [
        {'url': 'images/monasteries/berkasovo.jpg', 'caption': 'Crkva Svete Petke manastira Berkasovo sa lekovitim izvorom Vodica kod Šida (Izvor: Eparhija sremska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/berkasovo_gal_1.jpg', 'caption': 'Zapadni prilaz i manastirska porta u Berkasovu (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/berkasovo_gal_2.jpg', 'caption': 'Pogled na manastir Berkasovo na obroncima Fruške Gore (Izvor: Vikimedijina ostava)'}
    ],
    'besenovo': [
        {'url': 'images/monasteries/besenovo.jpg', 'caption': 'Obnovljeni hram Svetih arhangela Mihaila i Gavrila manastira Bešenovo, najstarije fruškogorske zadužbine kralja Dragutina (13. vek) (Izvor: Eparhija sremska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/besenovo_gal_1.jpg', 'caption': 'Zvonik i novi konak manastira Bešenovo (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/besenovo_gal_2.jpg', 'caption': 'Pogled na manastirski kompleks Bešenovo u fruškogorskoj dolini (Izvor: Vikimedijina ostava)'}
    ],
    'divsa': [
        {'url': 'images/monasteries/divsa_gal_1.jpg', 'caption': 'Crkva Svetog Nikole manastira Divša (Đipša) iz 15. veka, zadužbina despota Jovana Brankovića (Izvor: Pokrajinski zavod za zaštitu spomenika kulture / Vikimedijina ostava)'},
        {'url': 'images/monasteries/divsa.jpg', 'caption': 'Zapadna fasada sa baroknim zvonikom manastira Divša (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/divsa_gal_2.jpg', 'caption': 'Manastirska porta i konak u Divši kod Vizića (Izvor: Vikimedijina ostava)'}
    ],
    'grgeteg': [
        {'url': 'images/monasteries/grgeteg_gal_1.jpg', 'caption': 'Crkva Prenosa moštiju Svetog Nikole manastira Grgeteg iz 1471. godine (zadužbina Zmaja Ognjenog Vuka), gde se čuva čudotvorna ikona Bogorodice Trojeručice (Izvor: Pokrajinski zavod za zaštitu spomenika kulture / Wiki.Vojvodina)'},
        {'url': 'images/monasteries/grgeteg_gal_2.jpg', 'caption': 'Ikonostas rad Uroša Predića iz 1902. godine u crkvi manastira Grgeteg (Izvor: Galerija Matice srpske / Vikimedijina ostava)'},
        {'url': 'images/monasteries/grgeteg.jpg', 'caption': 'Spratni konaci i zvonik manastira Grgeteg na južnim padinama Fruške Gore (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'}
    ],
    'jazak': [
        {'url': 'images/monasteries/jazak_gal_1.jpg', 'caption': 'Crkva Svete Trojice manastira Jazak iz 1758. godine, mesto gde počivaju svete mošti poslednjeg srpskog cara Stefana Uroša V Nejakog (Izvor: Pokrajinski zavod za zaštitu spomenika kulture / Wiki.Vojvodina)'},
        {'url': 'images/monasteries/jazak.jpg', 'caption': 'Monumentalni barokni zvonik i južno krilo konaka manastira Jazak (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/jazak_gal_2.jpg', 'caption': 'Kivot sa moštima Svetog cara Uroša u naosu hrama manastira Jazak (Izvor: Eparhija sremska / Vikimedijina ostava)'}
    ],
    'krusedol': [
        {'url': 'images/monasteries/krusedol_gal_1.jpg', 'caption': 'Crkva Blagoveštenja Presvete Bogorodice manastira Krušedol (1509–1514), mauzolej srpskih despota Brankovića, patrijarha Arsenija III Čarnojevića i kralja Milana Obrenovića (Izvor: Pokrajinski zavod za zaštitu spomenika kulture / Wiki.Vojvodina)'},
        {'url': 'images/monasteries/krusedol_gal_2.jpg', 'caption': 'Freska Hristovog rodoslova i zidno slikarstvo iz 16. i 18. veka u priprati Krušedola (Izvor: Galerija Matice srpske / Vikimedijina ostava)'},
        {'url': 'images/monasteries/krusedol.jpg', 'caption': 'Zvonik i crvena fasada ulazne kapije manastira Krušedol (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'}
    ],
    'kuvezdin': [
        {'url': 'images/monasteries/kuvezdin_gal_1.jpg', 'caption': 'Crkva Svetog Save i Svetog Simeona Mirotočivog u Kuveždinu iz 1816. godine (zadužbina despota Stefana Štiljanovića iz 1520) (Izvor: Pokrajinski zavod za zaštitu spomenika kulture / Wiki.Vojvodina)'},
        {'url': 'images/monasteries/kuvezdin.jpg', 'caption': 'Zapadno pročelje sa neoklasičnim zvonikom manastira Kuveždin (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/kuvezdin_gal_2.jpg', 'caption': 'Obnovljeni konaci i porta manastira Kuveždin kod Divoša (Izvor: Vikimedijina ostava)'}
    ],
    'mala-remeta': [
        {'url': 'images/monasteries/mala-remeta_gal_1.jpg', 'caption': 'Crkva Pokrova Presvete Bogorodice manastira Mala Remeta iz 1739. godine, jednobrodna kamena građevina bez zvonika (Izvor: Pokrajinski zavod za zaštitu spomenika kulture / Wiki.Vojvodina)'},
        {'url': 'images/monasteries/mala-remeta.jpg', 'caption': 'Drvena zvonara i manastirski konak u Maloj Remeti (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/mala-remeta_gal_2.jpg', 'caption': 'Ikonostas Janka Halkozovića iz 1757. godine u crkvi Pokrova Bogorodice u Maloj Remeti (Izvor: Galerija Matice srpske / Vikimedijina ostava)'}
    ],
    'novo-hopovo': [
        {'url': 'images/monasteries/novo-hopovo_gal_1.jpg', 'caption': 'Crkva Svetog Nikole manastira Novo Hopovo iz 1576. godine, zadužbina despota Đorđa Brankovića (vladike Maksima) u kojoj počivaju mošti Svetog Teodora Tirona (Izvor: Pokrajinski zavod za zaštitu spomenika kulture / Wiki.Vojvodina)'},
        {'url': 'images/monasteries/novo-hopovo_gal_2.jpg', 'caption': 'Freska Pokolja vitlejemske dece i zidno slikarstvo kritskih majstora iz 1608. godine u priprati Novog Hopova (Izvor: Galerija Matice srpske / Vikimedijina ostava)'},
        {'url': 'images/monasteries/novo-hopovo.jpg', 'caption': 'Pročelje sa baroknim zvonikom i spratnim konacima manastira Novo Hopovo (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'}
    ],
    'petkovica-sremska': [
        {'url': 'images/monasteries/petkovica-sremska_gal_1.jpg', 'caption': 'Crkva Svete Petke manastira Petkovica iz 16. veka, zadužbina udovice despota Stefana Štiljanovića, despotice Jelene (Izvor: Pokrajinski zavod za zaštitu spomenika kulture / Wiki.Vojvodina)'},
        {'url': 'images/monasteries/petkovica-sremska.jpg', 'caption': 'Zapadna fasada sa drvenim zvonikom manastira Petkovica kod Ležimira (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/petkovica-sremska_gal_2.jpg', 'caption': 'Očuvane originalne freske iz 1588. godine u naosu crkve Svete Petke (Izvor: Pokrajinski zavod za zaštitu spomenika kulture / Wiki.Vojvodina)'}
    ],
    'privina-glava': [
        {'url': 'images/monasteries/privina-glava_gal_1.jpg', 'caption': 'Crkva Sabora Svetih arhangela manastira Privina Glava iz 1741. godine (predanje o ktitoru vlastelinu Privi iz 12. veka) (Izvor: Pokrajinski zavod za zaštitu spomenika kulture / Wiki.Vojvodina)'},
        {'url': 'images/monasteries/privina-glava.jpg', 'caption': 'Barokni zvonik i monumentalni konaci manastira Privina Glava kod Šida (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/privina-glava_gal_2.jpg', 'caption': 'Pogled na manastirski kompleks i novu kapelu Pokrova Bogorodice u Privinoj Glavi (Izvor: Vikimedijina ostava)'}
    ],
    'rakovac': [
        {'url': 'images/monasteries/rakovac_gal_1.jpg', 'caption': 'Crkva Svetih vrača Kozme i Damjana manastira Rakovac iz 1533. godine, zadužbina velikog komornika Rake Miloševića (Izvor: Pokrajinski zavod za zaštitu spomenika kulture / Wiki.Vojvodina)'},
        {'url': 'images/monasteries/rakovac.jpg', 'caption': 'Južna fasada i barokni zvonik manastira Rakovac na severnim obroncima Fruške Gore (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/rakovac_gal_2.jpg', 'caption': 'Manastirski konak i arheološki ostaci u porti Rakovca (Izvor: Vikimedijina ostava)'}
    ],
    'savina': [
        {'url': 'images/monasteries/savina.jpg', 'caption': 'Manastir Savina kod Novih Banovaca posvećen Svetom Savi (Izvor: Eparhija sremska / Vikimedijina ostava)'}
    ],
    'staro-hopovo': [
        {'url': 'images/monasteries/staro-hopovo_gal_1.jpg', 'caption': 'Crkva Svetog Pantelejmona manastira Staro Hopovo iz 1752. godine (podignuta na mestu manastira despota Đorđa Brankovića) (Izvor: Pokrajinski zavod za zaštitu spomenika kulture / Wiki.Vojvodina)'},
        {'url': 'images/monasteries/staro-hopovo.jpg', 'caption': 'Jednobrodna kamena crkva sa kupolom manastira Staro Hopovo u fruškogorskoj šumi (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/staro-hopovo_gal_2.jpg', 'caption': 'Oltarska apsida i krovna konstrukcija hrama Svetog Pantelejmona u Starom Hopovu (Izvor: Vikimedijina ostava)'}
    ],
    'sveta-petka': [
        {'url': 'images/monasteries/sveta-petka.jpg', 'caption': 'Crkva Svete Petke u Berkasovu kod Šida, zadužbina sa izvorom lekovite vode (Izvor: Eparhija sremska / Vikimedijina ostava)'}
    ],
    'vavedenje': [
        {'url': 'images/monasteries/vavedenje_gal_1.jpg', 'caption': 'Gornja crkva Vavedenja Presvete Bogorodice u Sremskim Karlovcima iz 1746. godine, zadužbina u kojoj počivaju patrijarsi Georgije Branković i Lukijan Bogdanović (Izvor: Pokrajinski zavod za zaštitu spomenika kulture / Wiki.Vojvodina)'},
        {'url': 'images/monasteries/vavedenje_gal_2.jpg', 'caption': 'Raskošni barokni ikonostas Jakova Orfelina i Stefana Gavrilovića iz 1776. godine u Gornjoj crkvi u Karlovcima (Izvor: Galerija Matice srpske / Vikimedijina ostava)'},
        {'url': 'images/monasteries/vavedenje.jpg', 'caption': 'Zapadna fasada sa visokim zvonikom manastira Vavedenje u Sremskim Karlovcima (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'}
    ],
    'velika-remeta': [
        {'url': 'images/monasteries/velika-remeta_gal_1.jpg', 'caption': 'Crkva Svetog Dimitrija manastira Velika Remeta sa najvišim baroknim zvonikom na Fruškoj Gori (visok 38,6 m, podignut 1735. godine) (Izvor: Pokrajinski zavod za zaštitu spomenika kulture / Wiki.Vojvodina)'},
        {'url': 'images/monasteries/velika-remeta.jpg', 'caption': 'Ulazno krilo konaka i spratne arkade u porti manastira Velika Remeta (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/velika-remeta_gal_2.jpg', 'caption': 'Replika pećine Rođenja Hristovog (Vitlejemska pećina) u porti Velike Remete (Izvor: Vikimedijina ostava)'}
    ],
    'vrdnik': [
        {'url': 'images/monasteries/vrdnik_gal_1.jpg', 'caption': 'Crkva Vaznesenja Gospodnjeg manastira Vrdnik (Mala Ravanica) iz 1811. godine, gde su monasi izbegli iz Ravanice 1697. čuvali mošti kneza Lazara (Izvor: Pokrajinski zavod za zaštitu spomenika kulture / Wiki.Vojvodina)'},
        {'url': 'images/monasteries/vrdnik.jpg', 'caption': 'Barokni zvonik i monumentalni konaci manastira Vrdnik na južnoj strani Fruške Gore (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/vrdnik_gal_2.jpg', 'caption': 'Kivot sa česticom moštiju Svetog kneza Lazara i deo moštiju Svete mučenice Anastasije u hramu u Vrdniku (Izvor: Eparhija sremska / Vikimedijina ostava)'}
    ],

    # ==================== 12. EPARHIJA ŠUMADIJSKA ====================
    'brekovac': [
        {'url': 'images/monasteries/brekovac.jpg', 'caption': 'Crkva Svetog arhangela Mihaila manastira Brekovac kod Osečine (Izvor: Eparhija šumadijska / Vikimedijina ostava)'}
    ],
    'denkovac': [
        {'url': 'images/monasteries/denkovac.jpg', 'caption': 'Crkva Uspenja Presvete Bogorodice manastira Denkovac u dolini reke Dulenke, zadužbina kralja Dragutina (Izvor: Eparhija šumadijska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/denkovac_gal_1.jpg', 'caption': 'Zvonik i manastirska porta u Denkovcu kod Kragujevca (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/denkovac_gal_2.jpg', 'caption': 'Pogled na manastirski kompleks Denkovac (Izvor: Vikimedijina ostava)'}
    ],
    'dikuljaca': [
        {'url': 'images/monasteries/dikuljaca.jpg', 'caption': 'Crkva manastira Dikuljača posvećena Rođenju Presvete Bogorodice (Izvor: Eparhija šumadijska / Vikimedijina ostava)'}
    ],
    'dobra-voda': [
        {'url': 'images/monasteries/dobra-voda.jpg', 'caption': 'Manastir Dobra Voda posvećen Svetom velikomučeniku Georgiju kod Jagodine (Izvor: Eparhija šumadijska / Vikimedijina ostava)'}
    ],
    'dranca': [
        {'url': 'images/monasteries/dranca.jpg', 'caption': 'Crkva Svetog Nikole manastira Dranča kod Kragujevca (Izvor: Eparhija šumadijska / Vikimedijina ostava)'}
    ],
    'grncarica': [
        {'url': 'images/monasteries/grncarica_gal_1.jpg', 'caption': 'Crkva Svetog Nikole manastira Grnčarica iz doba kralja Dragutina (kraj 13. veka) u Prnjavoru kod Batočine (Izvor: Zavod za zaštitu spomenika kulture Kragujevac / Vikimedijina ostava)'},
        {'url': 'images/monasteries/grncarica.jpg', 'caption': 'Zapadna fasada sa tremom i zvonarom manastira Grnčarica (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/grncarica_gal_2.jpg', 'caption': 'Manastirski konak i porta u Grnčarici (Izvor: Vikimedijina ostava)'}
    ],
    'ivkovic': [
        {'url': 'images/monasteries/ivkovic.jpg', 'caption': 'Hram Rođenja Presvete Bogorodice manastira Ivković iz 16. veka kod Jagodine (Izvor: Eparhija šumadijska / Vikimedijina ostava)'}
    ],
    'josanica': [
        {'url': 'images/monasteries/josanica_gal_1.jpg', 'caption': 'Crkva Svetog Nikole manastira Jošanica iz 14. veka u dolini reke Jošanice kod Jagodine (moravska škola sa portretima ktitora) (Izvor: Zavod za zaštitu spomenika kulture Kragujevac / Vikimedijina ostava)'},
        {'url': 'images/monasteries/josanica.jpg', 'caption': 'Južna fasada sa kupolom i krovnim vencem hrama u Jošanici (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/josanica_gal_2.jpg', 'caption': 'Manastirski konak i grob kneza Jovana Petrovića Kovača u Jošanici (Izvor: Vikimedijina ostava)'}
    ],
    'kastaljan': [
        {'url': 'images/monasteries/kastaljan_gal_1.jpg', 'caption': 'Konzervirani ostaci manastira Kastaljan posvećenog Svetom Đorđu iz 14. veka na severoistočnoj padini Kosmaja (letnjikovac despota Stefana Lazarevića) (Izvor: Zavod za zaštitu spomenika kulture grada Beograda / Vikimedijina ostava)'},
        {'url': 'images/monasteries/kastaljan.jpg', 'caption': 'Arheološki ostaci crkve, trpezarije i konaka manastira Kastaljan kod Nemenikuća (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/kastaljan_gal_2.jpg', 'caption': 'Zidine despotovog konaka u kompleksu Kastaljan na Kosmaju (Izvor: Vikimedijina ostava)'}
    ],
    'lipar': [
        {'url': 'images/monasteries/lipar.jpg', 'caption': 'Crkva Svetog Đorđa manastira Lipar kod Donje Sabante gde je učiteljevao i pisao pesnik Đura Jakšić (Izvor: Eparhija šumadijska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/lipar_gal_1.jpg', 'caption': 'Zapadni ulaz sa zvonikom manastira Lipar (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/lipar_gal_2.jpg', 'caption': 'Manastirski konak i porta na Liparskom brdu kod Kragujevca (Izvor: Vikimedijina ostava)'}
    ],
    'petkovica-rudnicka': [
        {'url': 'images/monasteries/petkovica-rudnicka_gal_1.jpg', 'caption': 'Crkva Svete Petke manastira Petkovica na Rudniku iz druge polovine 13. veka (zadužbina kralja Dragutina) u Stragarima (Izvor: Zavod za zaštitu spomenika kulture Kragujevac / Vikimedijina ostava)'},
        {'url': 'images/monasteries/petkovica-rudnicka.jpg', 'caption': 'Južna strana jednobrodne kamene crkve Svete Petke na Rudniku (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/petkovica-rudnicka_gal_2.jpg', 'caption': 'Manastirski konak i porta podno rudničkih šuma (Izvor: Vikimedijina ostava)'}
    ],
    'pinosava': [
        {'url': 'images/monasteries/pinosava.jpg', 'caption': 'Crkva Svetog arhangela Gavrila manastira Pinosava u Kusatku kod Smederevske Palanke, zadužbina vezana za despota Stefana Lazarevića (Izvor: Regionalni zavod za zaštitu spomenika kulture Smederevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/pinosava_gal_1.jpg', 'caption': 'Zapadna fasada sa kamenim zvonikom i drvenim tremom u Pinosavi (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'},
        {'url': 'images/monasteries/pinosava_gal_2.jpg', 'caption': 'Manastirska porta sa stoletnim hrastovima u Kusatku (Izvor: Vikimedijina ostava)'}
    ],
    'preradovac': [
        {'url': 'images/monasteries/preradovac.jpg', 'caption': 'Hram Svetog apostola Tome manastira Preradovac kod Rekovca (Izvor: Eparhija šumadijska / Vikimedijina ostava)'}
    ],
    'raletinac': [
        {'url': 'images/monasteries/raletinac.jpg', 'caption': 'Crkva Svetih apostola Petra i Pavla manastira Raletinac iz 15. veka u dolini Raletinačkog potoka kod Rekovca (Izvor: Eparhija šumadijska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/raletinac_gal_1.jpg', 'caption': 'Zvonik i manastirski konak u Raletincu (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/raletinac_gal_2.jpg', 'caption': 'Pogled na manastir Raletinac u Levačkom kraju (Izvor: Vikimedijina ostava)'}
    ],
    'sarunac': [
        {'url': 'images/monasteries/sarunac.jpg', 'caption': 'Crkva Svetih apostola Petra i Pavla manastira Sarunac u Levču (Izvor: Eparhija šumadijska / Vikimedijina ostava)'}
    ],
    'sibnica': [
        {'url': 'images/monasteries/sibnica.jpg', 'caption': 'Crkva Svete Petke manastira Sibnica iz 15. veka na padinama Kosmaja kod Sopota (Izvor: Zavod za zaštitu spomenika kulture grada Beograda / Vikimedijina ostava)'}
    ],
    'tresije': [
        {'url': 'images/monasteries/tresije_gal_1.jpg', 'caption': 'Crkva Sabora Svetih arhangela manastira Tresije iz 1309. godine (zadužbina kralja Dragutina) na planini Kosmaj (Izvor: Zavod za zaštitu spomenika kulture grada Beograda / Vikimedijina ostava)'},
        {'url': 'images/monasteries/tresije.jpg', 'caption': 'Zapadna kamena fasada i zvonik manastira Tresije (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/tresije_gal_2.jpg', 'caption': 'Pogled na manastirski kompleks Tresije okružen kosmajskim šumama (Izvor: Vikimedijina ostava)'}
    ],
    'voljavca-sumadijska': [
        {'url': 'images/monasteries/voljavca-sumadijska_gal_1.jpg', 'caption': 'Crkva Svetog arhangela Mihaila manastira Voljavča iz 1406. godine (zadužbina vlastelina Mihaila Končinovića) na Rudniku (Izvor: Zavod za zaštitu spomenika kulture Kragujevac / Vikimedijina ostava)'},
        {'url': 'images/monasteries/voljavca-sumadijska.jpg', 'caption': 'Karađorđev konak u Voljavči u kome je 1805. održano prvo zasedanje Praviteljstvujušćeg sovjeta srpskog pod vođstvom Prote Mateje Nenadovića (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/voljavca-sumadijska_gal_2.jpg', 'caption': 'Pogled na manastirski kompleks Voljavča u dolini Voljavačkog potoka kod Stragara (Izvor: Vikimedijina ostava)'}
    ],
    'vracevsnica': [
        {'url': 'images/monasteries/vracevsnica_gal_1.jpg', 'caption': 'Crkva Svetog Đorđa manastira Vraćevšnica iz 1428. godine, zadužbina velikog čelnika Radiča Postupovića podno planine Rudnik (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/vracevsnica_gal_2.jpg', 'caption': 'Zapadna fasada sa spratnim konakom kneginje Ljubice u Vraćevšnici (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/vracevsnica.jpg', 'caption': 'Pogled na manastirski kompleks Vraćevšnica gde je 1818. doneta odluka o proglašenju Kragujevca za prestonicu Srbije (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'}
    ],
    'pavlovac': [
        {'url': 'images/monasteries/pavlovac_gal_1.jpg', 'caption': 'Crkva Svetog Nikole manastira Pavlovac iz 1425. godine (zadužbina despota Stefana Lazarevića) kod Koraćice na Kosmaju (Izvor: Zavod za zaštitu spomenika kulture grada Beograda / Vikimedijina ostava)'},
        {'url': 'images/monasteries/pavlovac.jpg', 'caption': 'Arheološki ostaci despotovog dvorca i trpezarije u Pavlovcu (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/pavlovac_gal_2.jpg', 'caption': 'Pogled na manastir Pavlovac gde je despot Stefan Lazarević potpisao povelju Dubrovčanima (Izvor: Vikimedijina ostava)'}
    ],
    'prekopeca': [
        {'url': 'images/monasteries/prekopeca.jpg', 'caption': 'Crkva Preobraženja Gospodnjeg manastira Prekopeča kod Divostina (Izvor: Eparhija šumadijska / Vikimedijina ostava)'}
    ],

    # ==================== 13. EPARHIJA RAŠKO-PRIZRENSKA ====================
    'banja': [
        {'url': 'images/monasteries/banja.jpg', 'caption': 'Crkva manastira Banja kod Srbice posvećena Svetom Nikoli (Izvor: Eparhija raško-prizrenska / Vikimedijina ostava)'}
    ],
    'bela-crkva': [
        {'url': 'images/monasteries/bela-crkva.jpg', 'caption': 'Crkva Uspenja Presvete Bogorodice (Bela Crkva) u selu Karan kod Užica/Kosova (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'}
    ],
    'binac': [
        {'url': 'images/monasteries/binac.jpg', 'caption': 'Ostaci manastira Svetog arhangela Gavrila (Binač) iz 14. veka u dolini reke Binačke Morave kod Vitine (Izvor: Eparhija raško-prizrenska / Vikimedijina ostava)'}
    ],
    'braca': [
        {'url': 'images/monasteries/braca.jpg', 'caption': 'Crkva Svetih vrača Kozme i Damjana manastira Brača kod Podujeva (Izvor: Eparhija raško-prizrenska / Vikimedijina ostava)'}
    ],
    'budisavci': [
        {'url': 'images/monasteries/budisavci.jpg', 'caption': 'Crkva Preobraženja Gospodnjeg manastira Budisavci iz 14. veka (metoh Pećke patrijaršije, zadužbina kralja Milutina) kod Kline (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'},
        {'url': 'images/monasteries/budisavci_gal_1.jpg', 'caption': 'Južna fasada crkve od tesanog kamena sa kupolom u Budisavcima (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/budisavci_gal_2.jpg', 'caption': 'Čudotvorna ikona Presvete Bogorodice Budisavačke iz 14. veka u naosu hrama (Izvor: Eparhija raško-prizrenska / Vikimedijina ostava)'}
    ],
    'devine-vode': [
        {'url': 'images/monasteries/devine-vode.jpg', 'caption': 'Hram Čudotvorne ikone Bogorodice Trojeručice manastira Devine Vode kod Zvečana (Izvor: Eparhija raško-prizrenska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/devine-vode_gal_1.jpg', 'caption': 'Manastirski konak i lekoviti izvor Devine Vode na severu Kosova (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/devine-vode_gal_2.jpg', 'caption': 'Pogled na manastir Devine Vode podno planine Rogozne (Izvor: Vikimedijina ostava)'}
    ],
    'devic': [
        {'url': 'images/monasteries/devic_gal_1.jpg', 'caption': 'Crkva Vavedenja Presvete Bogorodice manastira Devič u Drenici, zadužbina despota Đurđa Brankovića (1434) gde počivaju mošti Svetog Joanikija Devičkog (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/devic.jpg', 'caption': 'Kivot sa čudotvornim moštima Svetog Joanikija Devičkog u crkvi manastira Devič (Izvor: Eparhija raško-prizrenska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/devic_gal_2.jpg', 'caption': 'Obnovljeni konaci i zidine manastira Devič kod Srbice (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'}
    ],
    'draganac': [
        {'url': 'images/monasteries/draganac_gal_1.jpg', 'caption': 'Crkva Svetih arhangela Gavrila i Mihaila manastira Draganac iz 1381. godine (zadužbina kneza Lazara posvećena kćeri Dragani) u Kosovskom Pomoravlju kod Gnjilana (Izvor: Eparhija raško-prizrenska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/draganac.jpg', 'caption': 'Zapadni ulaz sa zvonikom i čudotvornim izvorom lekovite vode u Dragancu (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/draganac_gal_2.jpg', 'caption': 'Pogled na manastirski kompleks Draganac u šumovitoj dolini Novobrdskog kraja (Izvor: Vikimedijina ostava)'}
    ],
    'duboki-potok': [
        {'url': 'images/monasteries/duboki-potok.jpg', 'caption': 'Crkva Vavedenja Presvete Bogorodice manastira Duboki Potok iz 14. veka u Ibarskom Kolašinu kod Zubinog Potoka (čuva ruku Svetog Nikite i deo moštiju Svetih vrača) (Izvor: Eparhija raško-prizrenska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/duboki-potok_gal_1.jpg', 'caption': 'Zvonik i manastirska porta u Dubokom Potoku (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/duboki-potok_gal_2.jpg', 'caption': 'Pogled na manastir Duboki Potok iznad jezera Gazivode (Izvor: Vikimedijina ostava)'}
    ],
    'djurdjevi-stupovi': [
        {'url': 'images/monasteries/djurdjevi-stupovi_gal_1.jpg', 'caption': 'Crkva Svetog Đorđa manastira Đurđevi Stupovi u Rasu iz 1171. godine, prva carska lavra Stefana Nemanje na vrhu brda iznad Novog Pazara (UNESCO svetska baština) (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'},
        {'url': 'images/monasteries/djurdjevi-stupovi_gal_2.jpg', 'caption': 'Južna kula (stup) i kameni reljefi portala Đurđevih Stupova u Rasu (Izvor: UNESCO World Heritage Centre / Vikimedijina ostava)'},
        {'url': 'images/monasteries/djurdjevi-stupovi.jpg', 'caption': 'Pogled na obnovljeni manastirski kompleks i trpezariju Stefana Nemanje u Đurđevim Stupovima (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'}
    ],
    'gorioc': [
        {'url': 'images/monasteries/gorioc_gal_1.jpg', 'caption': 'Crkva Svetog Nikole manastira Gorioč iz 14. veka (metoh manastira Visoki Dečani, zadužbina kralja Stefana Dečanskog podignuta u znak zahvalnosti za isceljenje očiju) iznad Istoka (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/gorioc.jpg', 'caption': 'Zapadna fasada sa zvonikom i konacima manastira Gorioč (Izvor: Eparhija raško-prizrenska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/gorioc_gal_2.jpg', 'caption': 'Pogled na Metohijsku kotlinu iz porte manastira Gorioč (Izvor: Vikimedijina ostava)'}
    ],
    'gracanica': [
        {'url': 'images/monasteries/gracanica_gal_1.jpg', 'caption': 'Crkva Uspenja Presvete Bogorodice manastira Gračanica iz 1321. godine, remek-delo srpsko-vizantijske arhitekture i zadužbina kralja Milutina na Kosovu polju (UNESCO svetska baština) (Izvor: UNESCO World Heritage Centre / Vikimedijina ostava)'},
        {'url': 'images/monasteries/gracanica_gal_2.jpg', 'caption': 'Freska kraljice Simonide i kralja Milutina sa krunom i anđelima na ulazu u naos Gračanice (Izvor: Galerija fresaka Narodnog muzeja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/gracanica.jpg', 'caption': 'Petokupolna piramidalna silueta hrama Gračanice sa spratnim konacima (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'}
    ],
    'kabas': [
        {'url': 'images/monasteries/kabas.jpg', 'caption': 'Ostaci isposnice i manastira Svetog Petra Koriškog u selu Kabaš kod Prizrena iz 13. veka (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'}
    ],
    'kmetovce': [
        {'url': 'images/monasteries/kmetovce.jpg', 'caption': 'Konzervirani ostaci crkve Svetog Dimitrija manastira Kmetovce iz doba cara Dušana (14. vek) kod Gnjilana (Izvor: Zavod za zaštitu spomenika kulture / Vikimedijina ostava)'}
    ],
    'korisa': [
        {'url': 'images/monasteries/korisa.jpg', 'caption': 'Isposnica Svetog Petra Koriškog u steni iznad sela Koriša kod Prizrena (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'}
    ],
    'koncul': [
        {'url': 'images/monasteries/koncul.jpg', 'caption': 'Crkva Svetog Nikole manastira Končul (Nikoljača) iz 11. veka na obali Ibra kod Raške, gde je boravio Sveti Sava (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'}
    ],
    'musutiste': [
        {'url': 'images/monasteries/musutiste.jpg', 'caption': 'Lokalitet manastira Svete Trojice u Mušutištu kod Suve Reke iz 14. veka (Izvor: Eparhija raško-prizrenska / Vikimedijina ostava)'}
    ],
    'pecka-patrijarsija': [
        {'url': 'images/monasteries/pecka-patrijarsija_gal_1.jpg', 'caption': 'Kompleks četiri spojene crkve Pećke patrijaršije (Svetih apostola, Svetog Dimitrija, Bogorodice Odigitrije i Svetog Nikole) podno Prokletija na reci Bistrici, vekovno sedište i mauzolej srpskih arhiepiskopa i patrijaraha (UNESCO svetska baština) (Izvor: UNESCO World Heritage Centre / Vikimedijina ostava)'},
        {'url': 'images/monasteries/pecka-patrijarsija_gal_2.jpg', 'caption': 'Čudotvorna ikona Krasnica (Bogorodica Pećka) i mermerni presto Svetog Save u hramu Bogorodice Odigitrije (Izvor: Srpska pravoslavna crkva / Vikimedijina ostava)'},
        {'url': 'images/monasteries/pecka-patrijarsija.jpg', 'caption': 'Crvena fasada priprate arhiepiskopa Danila II i stoletni Šam-dud u porti Pećke patrijaršije (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'}
    ],
    'socanica': [
        {'url': 'images/monasteries/socanica.jpg', 'caption': 'Crkva Svetog Jovana Preteče manastira Sočanica kod Leposavića na severu Kosova (Izvor: Eparhija raško-prizrenska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/socanica_gal_1.jpg', 'caption': 'Zapadni ulaz i manastirska porta u Sočanici (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/socanica_gal_2.jpg', 'caption': 'Pogled na manastir Sočanica u dolini Ibra (Izvor: Vikimedijina ostava)'}
    ],
    'sopocani': [
        {'url': 'images/monasteries/sopocani_gal_1.jpg', 'caption': 'Crkva Svete Trojice manastira Sopoćani iz 1260. godine, zadužbina kralja Stefana Uroša I na izvoru reke Raške (UNESCO svetska baština) (Izvor: UNESCO World Heritage Centre / Vikimedijina ostava)'},
        {'url': 'images/monasteries/sopocani_gal_2.jpg', 'caption': 'Svetski poznata freska Uspenja Presvete Bogorodice na zapadnom zidu naosa Sopoćana (vrhunac evropskog slikarstva 13. veka) (Izvor: Galerija fresaka Narodnog muzeja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/sopocani.jpg', 'caption': 'Zapadna fasada sa visokim zvonikom i ostacima srednjovekovne trpezarije u Sopoćanima (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'}
    ],
    'sveti-arhangeli': [
        {'url': 'images/monasteries/sveti-arhangeli_gal_1.jpg', 'caption': 'Monumentalni ostaci carske lavre Svetih arhangela u kanjonu Prizrenske Bistrice, monumentalne grobne zadužbine cara Stefana Dušana (1343–1352) (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'},
        {'url': 'images/monasteries/sveti-arhangeli_gal_2.jpg', 'caption': 'Kivot cara Dušana i podni mozaik u ostacima glavnog hrama Svetih arhangela kod Prizrena (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/sveti-arhangeli.jpg', 'caption': 'Obnovljeni konak i nova kapela Svetog Nikolaja u kompleksu Svetih arhangela podno tvrđave Višegrad (Izvor: Eparhija raško-prizrenska / Vikimedijina ostava)'}
    ],
    'zociste': [
        {'url': 'images/monasteries/zociste_gal_1.jpg', 'caption': 'Obnovljena crkva Svetih vrača Kozme i Damjana manastira Zočište iz 14. veka, gde počivaju čudotvorne mošti Svetih vrača (Izvor: Eparhija raško-prizrenska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/zociste.jpg', 'caption': 'Kivot sa čudotvornim isceliteljskim moštima Svetih besrebrenika Kozme i Damjana u Zočištu kod Orahovca (Izvor: Eparhija raško-prizrenska / Vikimedijina ostava)'},
        {'url': 'images/monasteries/zociste_gal_2.jpg', 'caption': 'Manastirski konaci i porta u Zočištu sa pogledom na Metohiju (Izvor: Vikimedijina ostava)'}
    ],
    'tugovce': [
        {'url': 'images/monasteries/tugovce.jpg', 'caption': 'Manastir Tugovce kod Kosovske Kamenice (Izvor: Eparhija raško-prizrenska / Vikimedijina ostava)'}
    ],
    'ubozac': [
        {'url': 'images/monasteries/ubozac.jpg', 'caption': 'Ostaci crkve Vavedenja Presvete Bogorodice manastira Ubožac iz 14. veka u selu Močare kod Kosovske Kamenice (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'}
    ],
    'visoki-decani': [
        {'url': 'images/monasteries/visoki-decani_gal_1.jpg', 'caption': 'Crkva Hrista Pantokratora manastira Visoki Dečani (1327–1335), zadužbina Svetog kralja Stefana Dečanskog i cara Dušana, sagrađena od dvobojnog mermera podno Prokletija (UNESCO svetska baština) (Izvor: UNESCO World Heritage Centre / Vikimedijina ostava)'},
        {'url': 'images/monasteries/visoki-decani_gal_2.jpg', 'caption': 'Kivot sa netruležnim moštima Svetog kralja Stefana Dečanskog i originalni kameni ikonostas iz 14. veka (Izvor: Zvanični sajt manastira Visoki Dečani / Vikimedijina ostava)'},
        {'url': 'images/monasteries/visoki-decani.jpg', 'caption': 'Pogled na manastirski kompleks Visoki Dečani sa Dečanskom Bistricom i spratnim konacima (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'}
    ],
    'vracevo': [
        {'url': 'images/monasteries/vracevo.jpg', 'caption': 'Crkva Svetih vrača Kozme i Damjana u Vračevu kod Leposavića iz 14. veka (Izvor: Eparhija raško-prizrenska / Vikimedijina ostava)'}
    ],

    # ==================== 14. EPARHIJA ŽIČKA ====================
    'gradac': [
        {'url': 'images/monasteries/gradac_gal_1.jpg', 'caption': 'Crkva Blagoveštenja Presvete Bogorodice manastira Gradac iz 1275. godine, zadužbina i grobno mesto kraljice Jelene Anžujske (spoj raške škole i rane gotike) na reci Brvenici (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'},
        {'url': 'images/monasteries/gradac_gal_2.jpg', 'caption': 'Bogorodičina crkva i kapela Svetog Nikole u manastirskom kompleksu Gradac (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/gradac.jpg', 'caption': 'Pogled na manastir Gradac podno planine Golije (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'}
    ],
    'isposnica-svetog-save': [
        {'url': 'images/monasteries/isposnica-svetog-save_gal_1.jpg', 'caption': 'Gornja isposnica Svetog Save u litici planine Radočelo iznad Studenice, gde je Sveti Sava napisao Studenički tipik i Žitije Svetog Simeona (Izvor: Manastir Studenica / Vikimedijina ostava)'},
        {'url': 'images/monasteries/isposnica-svetog-save.jpg', 'caption': 'Pećinska crkva Svetog Đorđa prilepljena uz vertikalnu stenu na litici Radočela (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/isposnica-svetog-save_gal_2.jpg', 'caption': 'Drveni mostić i prilaz Gornjoj isposnici Svetog Save iznad kanjona Studenice (Izvor: Vikimedijina ostava)'}
    ],
    'jezevica': [
        {'url': 'images/monasteries/jezevica_gal_1.jpg', 'caption': 'Crkva Svetog Nikole manastira Ježevica iz 1337. godine (zadužbina bana Milutina) podno planine Jelice kod Čačka (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/jezevica.jpg', 'caption': 'Zapadni ulaz sa kamenom zvonarom manastira Ježevica (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/jezevica_gal_2.jpg', 'caption': 'Očuvane freske iz 1609. godine i ikonostas u naosu hrama u Ježevici (Izvor: Vikimedijina ostava)'}
    ],
    'jovanje-ovcar-kablar': [
        {'url': 'images/monasteries/jovanje-ovcar-kablar_gal_1.jpg', 'caption': 'Crkva Rođenja Svetog Jovana Krstitelja manastira Jovanje na meandru Zapadne Morave u Ovčarsko-kablarskoj klisuri (Izvor: Eparhija žička / Vikimedijina ostava)'},
        {'url': 'images/monasteries/jovanje-ovcar-kablar.jpg', 'caption': 'Čudotvorna ikona Bogorodice Brzopomoćnice i unutrašnjost crkve u Jovanju (Izvor: Turistička organizacija Čačak / Vikimedijina ostava)'},
        {'url': 'images/monasteries/jovanje-ovcar-kablar_gal_2.jpg', 'caption': 'Pogled sa reke na manastir Jovanje u srcu Srpske Svete Gore (Izvor: Vikimedijina ostava)'}
    ],
    'klisura': [
        {'url': 'images/monasteries/klisura_gal_1.jpg', 'caption': 'Crkva Svetih arhangela Mihaila i Gavrila manastira Klisura iz 13. veka u klisuri reke Moravice kod Arilja (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/klisura.jpg', 'caption': 'Zapadna fasada manastirske crkve u Klisuri zidane od sige i kamena (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'},
        {'url': 'images/monasteries/klisura_gal_2.jpg', 'caption': 'Manastirski konak i porta okruženi krečnjačkim stenama u Klisuri (Izvor: Vikimedijina ostava)'}
    ],
    'kovilje': [
        {'url': 'images/monasteries/kovilje_gal_1.jpg', 'caption': 'Pećinski hram Svetih arhangela Mihaila i Gavrila (12. vek) i crkva Svetog Nikole (1638) manastira Kovilje podno planine Javor u dolini Nošnice (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/kovilje.jpg', 'caption': 'Hram Svetog Nikole i pećinska isposnica usečena u stenu u Kovilju kod Ivanjice (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/kovilje_gal_2.jpg', 'caption': 'Pogled na manastirski kompleks Kovilje sa planinskim konacima (Izvor: Vikimedijina ostava)'}
    ],
    'moravci': [
        {'url': 'images/monasteries/moravci_gal_1.jpg', 'caption': 'Crkva Presvete Bogorodice manastira Moravci iz 13. veka (zadužbina kralja Uroša I), mesto gde je sahranjen arhimandrit Hadži Đera (Izvor: Zavod za zaštitu spomenika kulture Valjevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/moravci.jpg', 'caption': 'Zapadna fasada sa monumentalnim zvonikom crkve u Moravcima kod Ljiga (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/moravci_gal_2.jpg', 'caption': 'Manastirska porta i spomen-obeležje Hadži Đeri u Moravcima (Izvor: Vikimedijina ostava)'}
    ],
    'nikolje-sumadijska': [
        {'url': 'images/monasteries/nikolje-sumadijska.jpg', 'caption': 'Crkva Svetog Nikole manastira Nikolje iz 15. veka na padinama Kablara, gde je Vuk Karadžić pronašao Nikoljsko jevanđelje na pergamentu (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/nikolje-sumadijska_gal_1.jpg', 'caption': 'Zapadni drveni trem i krovna konstrukcija crkve Svetog Nikole u Nikolju (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/nikolje-sumadijska_gal_2.jpg', 'caption': 'Konak kneza Miloša Obrenovića iz 1817. godine u manastiru Nikolje Kablarsko (Izvor: Turistička organizacija Čačak / Vikimedijina ostava)'}
    ],
    'nova-pavlica': [
        {'url': 'images/monasteries/nova-pavlica.jpg', 'caption': 'Crkva Vavedenja Presvete Bogorodice manastira Nova Pavlica iz 1381. godine, zadužbina kosovskih junaka braće Musića i njihove majke Dragane (sestre kneza Lazara) na reci Ibar (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'},
        {'url': 'images/monasteries/nova-pavlica_gal_1.jpg', 'caption': 'Moravska arhitektura i kamena fasada hrama Nove Pavlice (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/nova-pavlica_gal_2.jpg', 'caption': 'Ktitorke freske braće Stefana i Lazara Musića u naosu crkve Nove Pavlice (Izvor: Galerija fresaka Narodnog muzeja / Vikimedijina ostava)'}
    ],
    'preobrazenje-ovcar-kablar': [
        {'url': 'images/monasteries/preobrazenje-ovcar-kablar_gal_1.jpg', 'caption': 'Crkva Preobraženja Gospodnjeg manastira Preobraženje iz 1938. godine (podignuta pod nadzorom vladike Nikolaja Velimirovića na mestu srednjovekovnog hrama) podno Ovčara (Izvor: Eparhija žička / Vikimedijina ostava)'},
        {'url': 'images/monasteries/preobrazenje-ovcar-kablar_gal_2.jpg', 'caption': 'Zapadna fasada sa tremom i zvonikom manastira Preobraženje u Ovčarsko-kablarskoj klisuri (Izvor: Turistička organizacija Čačak / Vikimedijina ostava)'},
        {'url': 'images/monasteries/preobrazenje-ovcar-kablar.jpg', 'caption': 'Pogled na manastirski kompleks Preobraženje na desnoj obali Zapadne Morave (Izvor: Vikimedijina ostava)'}
    ],
    'pridvorica': [
        {'url': 'images/monasteries/pridvorica_gal_1.jpg', 'caption': 'Crkva Preobraženja Gospodnjeg u Pridvorici iz 12. veka, zadužbina sluge Stefana Nemanje građena u raškom stilu kod Ivanjice (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/pridvorica.jpg', 'caption': 'Zapadni mermerni portal i jednobrodni naos hrama u Pridvorici (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/pridvorica_gal_2.jpg', 'caption': 'Pogled na manastir Pridvorica u dolini reke Studenice (Izvor: Vikimedijina ostava)'}
    ],
    'raca': [
        {'url': 'images/monasteries/raca_gal_1.jpg', 'caption': 'Crkva Vaznesenja Gospodnjeg manastira Rača iz 1276. godine, zadužbina kralja Dragutina i čuveno središte Račanske prepisivačke škole podno planine Tare (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'},
        {'url': 'images/monasteries/raca_gal_2.jpg', 'caption': 'Monumentalni barokni ikonostas Dimitrija Posnikovića iz 1854. godine i riznica gde se u Drugom svetskom ratu čuvalo Miroslavljevo jevanđelje (Izvor: Zadužbine Nemanjića / Vikimedijina ostava)'},
        {'url': 'images/monasteries/raca.jpg', 'caption': 'Zvonik i spratni konaci manastira Rača kod Bajine Bašte (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'}
    ],
    'rujan': [
        {'url': 'images/monasteries/rujan_gal_1.jpg', 'caption': 'Crkva Svetog Đorđa manastira Rujan u Vrutcima kod Užica, gde je 1537. monah Teodosije štampao čuveno Rujansko četvorojevanđelje (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/rujan.jpg', 'caption': 'Zapadna fasada obnovljenog hrama od crvene opeke i kamena u Rujnu (Izvor: Eparhija žička / Vikimedijina ostava)'},
        {'url': 'images/monasteries/rujan_gal_2.jpg', 'caption': 'Pogled na manastir Rujan na obali Vrutčkog jezera podno Zlatibora (Izvor: Vikimedijina ostava)'}
    ],
    'sabor': [
        {'url': 'images/monasteries/sabor.jpg', 'caption': 'Hram Sabora srpskih svetitelja u selu Drenova kod Gornjeg Milanovca (Izvor: Eparhija žička / Vikimedijina ostava)'}
    ],
    'savinac': [
        {'url': 'images/monasteries/savinac_gal_2.jpg', 'caption': 'Crkva Svetog Save na Savincu iz 1819. godine, zadužbina kneza Miloša Obrenovića gde počiva Mina Karadžić-Vukomanović (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/savinac.jpg', 'caption': 'Kripta i grob Mine Karadžić u crkvi Svetog Save na reci Dičini (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/savinac_gal_1.jpg', 'caption': 'Pogled na manastirsku portu i stoletne borove na Savincu kod Takova (Izvor: Vikimedijina ostava)'}
    ],
    'sretenje-ovcar-kablar': [
        {'url': 'images/monasteries/sretenje-ovcar-kablar_gal_1.jpg', 'caption': 'Crkva Sretenja Gospodnjeg manastira Sretenje iz 16. veka, smeštena na platou pod samim vrhom planine Ovčar na 600 m nadmorske visine (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/sretenje-ovcar-kablar_gal_2.jpg', 'caption': 'Zidno slikarstvo iz 1844. godine i ikonostas u naosu hrama u Sretenju (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/sretenje-ovcar-kablar.jpg', 'caption': 'Manastirski konaci sa zvonikom i pogledom na Ovčarsko-kablarsku klisuru (Izvor: Turistička organizacija Čačak / Vikimedijina ostava)'}
    ],
    'stara-pavlica': [
        {'url': 'images/monasteries/stara-pavlica_gal_1.jpg', 'caption': 'Srednjovekovna crkva Svetih apostola Petra i Pavla (Stara Pavlica) iz 11. ili 12. veka (prednemanjićka epoha) na stenovitoj litici iznad reke Ibar (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'},
        {'url': 'images/monasteries/stara-pavlica.jpg', 'caption': 'Oltarska apsida i kameni lukovi kupole Stare Pavlice (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/stara-pavlica_gal_2.jpg', 'caption': 'Pogled na Staru Pavlicu i železničku prugu u Ibarskoj dolini kod Brvenika (Izvor: Vikimedijina ostava)'}
    ],
    'stjenik': [
        {'url': 'images/monasteries/stjenik_gal_1.jpg', 'caption': 'Crkva Rođenja Svetog Jovana Krstitelja manastira Stjenik iz 14. veka, zadužbina braće Mrnjavčevića podno stene na planini Jelici (Izvor: Eparhija žička / Vikimedijina ostava)'},
        {'url': 'images/monasteries/stjenik_gal_2.jpg', 'caption': 'Pećina i lekoviti izvor Svetog Jovana Krstitelja u steni iznad manastira Stjenik (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/stjenik.jpg', 'caption': 'Manastirski konak i porta Stjenika kod Čačka (Izvor: Vikimedijina ostava)'}
    ],
    'stubal': [
        {'url': 'images/monasteries/stubal.jpg', 'caption': 'Crkva Svete Petke manastira Stubal na obroncima Gledićkih planina kod Vrnjačke Banje (Izvor: Eparhija žička / Vikimedijina ostava)'},
        {'url': 'images/monasteries/stubal_gal_1.jpg', 'caption': 'Čudotvorni kameni stub i kapela Svetog Save u manastiru Stubal (Izvor: Eparhija žička / Vikimedijina ostava)'},
        {'url': 'images/monasteries/stubal_gal_2.jpg', 'caption': 'Pogled na manastirski kompleks Stubal u dolini Zapadne Morave (Izvor: Vikimedijina ostava)'}
    ],
    'studenica': [
        {'url': 'images/monasteries/studenica_gal_2.jpg', 'caption': 'Bogorodičina crkva manastira Studenica (1183–1196), carska lavra i zadužbina rodonačelnika Stefana Nemanje građena od belog mermera (UNESCO svetska baština) (Izvor: UNESCO World Heritage Centre / Vikimedijina ostava)'},
        {'url': 'images/monasteries/studenica_gal_1.jpg', 'caption': 'Kraljeva crkva (crkva Svetih Joakima i Ane) iz 1314. godine, remek-delo kralja Milutina sa freskom Rođenja Bogorodice slikara Mihaila i Evtihija (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/studenica.jpg', 'caption': 'Kivot sa moštima Svetog Simeona Mirotočivog (Stefana Nemanje) i Svetog kralja Stefana Prvovenčanog u Bogorodičinoj crkvi (Izvor: Manastir Studenica / Vikimedijina ostava)'}
    ],
    'sveta-trojica-ducalovici': [
        {'url': 'images/monasteries/sveta-trojica-ducalovici.jpg', 'caption': 'Hram Svete Trojice u Dučalovićima na padinama planine Ovčar iz 16. veka, najlepši primer raške graditeljske tradicije u Srpskoj Svetoj Gori (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'}
    ],
    'trnava': [
        {'url': 'images/monasteries/trnava_gal_1.jpg', 'caption': 'Crkva Blagoveštenja Presvete Bogorodice manastira Trnava iz 13. veka podno planine Jelice, gde je 1814. izbila Hadži Prodanova buna (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/trnava.jpg', 'caption': 'Zapadni ulaz i manastirski zvonik u Trnavi kod Čačka (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/trnava_gal_2.jpg', 'caption': 'Pogled na manastir Trnava sa manastirskim voćnjacima i konakom (Izvor: Vikimedijina ostava)'}
    ],
    'uspenje-ovcar-kablar': [
        {'url': 'images/monasteries/uspenje-ovcar-kablar_gal_1.jpg', 'caption': 'Hram Uspenja Presvete Bogorodice manastira Uspenje iz 1939. godine na steni iznad reke Zapadne Morave (sagrađen po ugledu na crkvu Svetog Konstantina i Jelene u Ohridu) (Izvor: Eparhija žička / Vikimedijina ostava)'},
        {'url': 'images/monasteries/uspenje-ovcar-kablar_gal_2.jpg', 'caption': 'Pogled na manastir Uspenje i litice Kablara u Ovčarsko-kablarskoj klisuri (Izvor: Turistička organizacija Čačak / Vikimedijina ostava)'},
        {'url': 'images/monasteries/uspenje-ovcar-kablar.jpg', 'caption': 'Manastirska porta i konak u Uspenju Kablarskom (Izvor: Vikimedijina ostava)'}
    ],
    'uvac': [
        {'url': 'images/monasteries/uvac_gal_2.jpg', 'caption': 'Hram Rođenja Presvete Bogorodice manastira Uvac iz 12. ili 13. veka (zadužbina Nemanjića) u kanjonu reke Uvac podno Zlatibora (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/uvac.jpg', 'caption': 'Zapadno pročelje i kamena fasada obnovljene crkve u Uvcu (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'}
    ],
    'vaznesenje-ovcar-kablar': [
        {'url': 'images/monasteries/vaznesenje-ovcar-kablar_gal_1.jpg', 'caption': 'Crkva Vaznesenja Gospodnjeg manastira Vaznesenje iz 16. veka na severnim obroncima Ovčara (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/vaznesenje-ovcar-kablar_gal_2.jpg', 'caption': 'Zapadni drveni trem i zvonik crkve Vaznesenja u Ovčarsko-kablarskoj klisuri (Izvor: Turistička organizacija Čačak / Vikimedijina ostava)'},
        {'url': 'images/monasteries/vaznesenje-ovcar-kablar.jpg', 'caption': 'Pogled na manastirski kompleks Vaznesenje okružen šumom (Izvor: Vikimedijina ostava)'}
    ],
    'vujan': [
        {'url': 'images/monasteries/vujan_gal_1.jpg', 'caption': 'Crkva Svetog arhangela Gavrila manastira Vujan iz 13. veka (obnovio Nikola Lunjevica 1805) na šumovitoj planini Vujan kod Čačka (Izvor: Zavod za zaštitu spomenika kulture Kraljevo / Vikimedijina ostava)'},
        {'url': 'images/monasteries/vujan_gal_2.jpg', 'caption': 'Mesto isceljenja i boravka mladog Gojka Stojčevića (potonjeg patrijarha Pavla) u manastiru Vujan (Izvor: Eparhija žička / Vikimedijina ostava)'},
        {'url': 'images/monasteries/vujan.jpg', 'caption': 'Zvonik i manastirski konak u Vujanu kod Prislonice (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'}
    ],
    'zgodacica': [
        {'url': 'images/monasteries/zgodacica.jpg', 'caption': 'Crkva Svetog Dimitrija manastira Zgodačica kod Kraljeva (Izvor: Eparhija žička / Vikimedijina ostava)'}
    ],
    'zica': [
        {'url': 'images/monasteries/zica_gal_1.jpg', 'caption': 'Crkva Vaznesenja Gospodnjeg (Svetog Spasa) manastira Žiča (1208–1220), crvena zadužbina Svetog Save i kralja Stefana Prvovenčanog, sedište prve autokefalne Srpske arhiepiskopije i krunidbeno mesto srpskih kraljeva (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'},
        {'url': 'images/monasteries/zica.jpg', 'caption': 'Zapadni ulazni toranj sa visokim zvonikom i pripratom kralja Radoslava u Žiči (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'}
    ],

    # ==================== 15. EPARHIJA NIŠKA ====================
    'ajdanovac': [
        {'url': 'images/monasteries/ajdanovac_gal_1.jpg', 'caption': 'Crkva Svetog Đorđa manastira Ajdanovac iz 14. veka na obroncima planine Jastrebac kod Prokuplja (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'},
        {'url': 'images/monasteries/ajdanovac.jpg', 'caption': 'Očuvane freske iz 1492. godine u naosu crkve Svetog Đorđa u Ajdanovcu (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/ajdanovac_gal_2.jpg', 'caption': 'Zapadna fasada sa drvenim tremom i spratnim konakom u Ajdanovcu (Izvor: Vikimedijina ostava)'}
    ],
    'babicko': [
        {'url': 'images/monasteries/babicko_gal_1.jpg', 'caption': 'Crkva Uspenja Presvete Bogorodice manastira Babičko iz 16. veka na Babičkoj Gori kod Leskovca (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'},
        {'url': 'images/monasteries/babicko.jpg', 'caption': 'Manastirska porta i zvonik u Babičkom (Izvor: Vikimedijina ostava)'}
    ],
    'bazovik': [
        {'url': 'images/monasteries/bazovik_gal_1.jpg', 'caption': 'Crkva Svetih vrača Kozme i Damjana manastira Bazovik iz 14. veka kod Pirota (Izvor: Eparhija niška / Vikimedijina ostava)'},
        {'url': 'images/monasteries/bazovik.jpg', 'caption': 'Zapadni ulaz u hram u Bazoviku zidan kamenom i opekom (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'},
        {'url': 'images/monasteries/bazovik_gal_2.jpg', 'caption': 'Manastirska porta i konak u Bazoviku (Izvor: Vikimedijina ostava)'}
    ],
    'crkovnica': [
        {'url': 'images/monasteries/crkovnica.jpg', 'caption': 'Crkva Svetog proroka Ilije manastira Crkovnica kod Aleksinca (Izvor: Eparhija niška / Vikimedijina ostava)'}
    ],
    'divljane': [
        {'url': 'images/monasteries/divljane_gal_1.jpg', 'caption': 'Crkva Svetog Dimitrija manastira Divljane iz 1395. godine (zadužbina braće Mrnjavčevića) podno Suve Planine kod Bele Palanke (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'},
        {'url': 'images/monasteries/divljane_gal_2.jpg', 'caption': 'Manastirski konaci sa zvonikom i hiljadugodišnjim hrastom u porti Divljana (Izvor: Eparhija niška / Vikimedijina ostava)'},
        {'url': 'images/monasteries/divljane.jpg', 'caption': 'Zapadna fasada hrama Svetog Dimitrija u Divljanu (Izvor: Vikimedijina ostava)'}
    ],
    'gabrovac': [
        {'url': 'images/monasteries/gabrovac_gal_1.jpg', 'caption': 'Crkva Svete Trojice manastira Gabrovac iz 13. veka podno planine Seličevice kod Niša (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'},
        {'url': 'images/monasteries/gabrovac.jpg', 'caption': 'Oltarski deo crkve i spomen-česma u porti manastira Gabrovac (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'}
    ],
    'gornji-matejevac': [
        {'url': 'images/monasteries/gornji-matejevac_gal_1.jpg', 'caption': 'Latinska crkva (crkva Svete Trojice) u Gornjem Matejevcu iz 11. veka, izuzetan primer rano-vizantijske trikonhosne arhitekture iznad Niša (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'},
        {'url': 'images/monasteries/gornji-matejevac_gal_2.jpg', 'caption': 'Oltarska apsida sa ukrasnim slepim arkadama od opeke na Latinskoj crkvi (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/gornji-matejevac.jpg', 'caption': 'Pogled na hram Svete Trojice na brdu Metoh kod Gornjeg Matejevca (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'}
    ],
    'gorcince': [
        {'url': 'images/monasteries/gorcince_gal_1.jpg', 'caption': 'Hram Svete Petke manastira Gorčince iz 1868. godine kod Babušnice (Izvor: Eparhija niška / Vikimedijina ostava)'},
        {'url': 'images/monasteries/gorcince.jpg', 'caption': 'Zapadni trem i zvonik crkve Svete Petke u Gorčincu (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/gorcince_gal_2.jpg', 'caption': 'Pogled na manastirski kompleks Gorčince u Lužničkoj kotlini (Izvor: Vikimedijina ostava)'}
    ],
    'iverica': [
        {'url': 'images/monasteries/iverica.jpg', 'caption': 'Crkva Svete Petke u Iverici (Srpska vojna crkva) iz 1898. godine u Sićevačkoj klisuri na reci Nišavi (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'},
        {'url': 'images/monasteries/iverica_gal_1.jpg', 'caption': 'Ikonostas i unutrašnjost crkve Svete Petke Iveričke (Izvor: Eparhija niška / Vikimedijina ostava)'},
        {'url': 'images/monasteries/iverica_gal_2.jpg', 'caption': 'Manastirska porta i zvonik pored magistralnog puta u Sićevačkoj klisuri (Izvor: Vikimedijina ostava)'}
    ],
    'janjusa': [
        {'url': 'images/monasteries/janjusa.jpg', 'caption': 'Hram Svetog Jovana Krstitelja manastira Janjuša kod Leskovca (Izvor: Eparhija niška / Vikimedijina ostava)'}
    ],
    'kaludra': [
        {'url': 'images/monasteries/kaludra.jpg', 'caption': 'Crkva Svetih apostola Petra i Pavla manastira Kaludra kod Prokuplja (Izvor: Eparhija niška / Vikimedijina ostava)'}
    ],
    'kamenica-timocka': [
        {'url': 'images/monasteries/kamenica-timocka_gal_1.jpg', 'caption': 'Crkva Svetog Đorđa manastira Kamenica iz 15. veka kod Niša (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'},
        {'url': 'images/monasteries/kamenica-timocka.jpg', 'caption': 'Zapadni ulaz i manastirski konak u Kamenici (Izvor: Eparhija niška / Vikimedijina ostava)'},
        {'url': 'images/monasteries/kamenica-timocka_gal_2.jpg', 'caption': 'Pogled na manastir Kamenica na padinama Kameničkog visa (Izvor: Vikimedijina ostava)'}
    ],
    'kozarski': [
        {'url': 'images/monasteries/kozarski.jpg', 'caption': 'Crkva manastira Kozare kod Vlasotinca posvećena Svetom Nikoli (Izvor: Eparhija niška / Vikimedijina ostava)'}
    ],
    'krajkovac': [
        {'url': 'images/monasteries/krajkovac.jpg', 'caption': 'Hram Rođenja Presvete Bogorodice manastira Krajkovac podno Malog Jastrepca kod Merošine (Izvor: Eparhija niška / Vikimedijina ostava)'}
    ],
    'krupac': [
        {'url': 'images/monasteries/krupac.jpg', 'caption': 'Crkva Svetog Jovana Krstitelja manastira Krupac iz 17. veka kod Pirota (Izvor: Eparhija niška / Vikimedijina ostava)'},
        {'url': 'images/monasteries/krupac_gal_2.jpg', 'caption': 'Manastirska porta i konaci u Krupcu (Izvor: Vikimedijina ostava)'}
    ],
    'kursumlija': [
        {'url': 'images/monasteries/kursumlija_gal_1.jpg', 'caption': 'Crkva Svetog Nikole u Kuršumliji (1166–1168), prva zadužbina Stefana Nemanje pokrivena olovom (zbog čega je mesto nazvano Bele Crkve / Kuršumlija) (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'},
        {'url': 'images/monasteries/kursumlija_gal_2.jpg', 'caption': 'Južna kula zvonara i vizantijska zidna tehnika od opeke na hramu Svetog Nikole (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/kursumlija.jpg', 'caption': 'Pogled na manastir Svetog Nikole iznad reke Toplice (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'}
    ],
    'labukovo': [
        {'url': 'images/monasteries/labukovo_gal_1.jpg', 'caption': 'Crkva Vaznesenja Gospodnjeg manastira Labukovo kod Svrljiga (Izvor: Eparhija niška / Vikimedijina ostava)'},
        {'url': 'images/monasteries/labukovo.jpg', 'caption': 'Manastirska porta i zvonik u Labukovu (Izvor: Vikimedijina ostava)'}
    ],
    'lipovac': [
        {'url': 'images/monasteries/lipovac_gal_1.jpg', 'caption': 'Crkva Svetog Stefana manastira Lipovac iz 1399. godine (zadužbina despota Stefana Lazarevića) podno planine Ozren kod Aleksinca (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'},
        {'url': 'images/monasteries/lipovac.jpg', 'caption': 'Klesani kameni hram sa kupolom i konacima u Lipovcu (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/lipovac_gal_2.jpg', 'caption': 'Freske iz 15. veka u naosu manastirske crkve u Lipovcu (Izvor: Eparhija niška / Vikimedijina ostava)'}
    ],
    'manastirce': [
        {'url': 'images/monasteries/manastirce_gal_1.jpg', 'caption': 'Crkva Svete Trojice manastira Manastirce kod Vlasotinca (Izvor: Eparhija niška / Vikimedijina ostava)'},
        {'url': 'images/monasteries/manastirce.jpg', 'caption': 'Zapadna fasada sa kamenim zvonikom u Manastircu (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/manastirce_gal_2.jpg', 'caption': 'Pogled na manastirski kompleks Manastirce (Izvor: Vikimedijina ostava)'}
    ],
    'miljkovac': [
        {'url': 'images/monasteries/miljkovac.jpg', 'caption': 'Crkva Svetog Nikole manastira Miljkovac iz 14. veka u klisuri Toponičke reke kod Niša (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'}
    ],
    'mustar': [
        {'url': 'images/monasteries/mustar.jpg', 'caption': 'Manastir Muštar kod Kuršumlije posvećen Svetim apostolima Petru i Pavlu (Izvor: Eparhija niška / Vikimedijina ostava)'}
    ],
    'oraovica-niska': [
        {'url': 'images/monasteries/oraovica-niska.jpg', 'caption': 'Crkva Svetog Nikole manastira Oraovica kod Grdelice iz 16. veka (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'}
    ],
    'planinica': [
        {'url': 'images/monasteries/planinica_gal_1.jpg', 'caption': 'Crkva Svetog Nikole manastira Planinica iz 16. veka na reci Jerma kod Pirota (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'},
        {'url': 'images/monasteries/planinica.jpg', 'caption': 'Zapadni ulaz sa drvenim tremom u Planinici (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/planinica_gal_2.jpg', 'caption': 'Freske iz 1606. godine u oltarskoj apsidi crkve u Planinici (Izvor: Vikimedijina ostava)'}
    ],
    'plocnik': [
        {'url': 'images/monasteries/plocnik.jpg', 'caption': 'Hram Svetih vrača Kozme i Damjana u Pločniku kod Prokuplja (Izvor: Eparhija niška / Vikimedijina ostava)'}
    ],
    'poganovo': [
        {'url': 'images/monasteries/poganovo_gal_1.jpg', 'caption': 'Crkva Svetog Jovana Bogoslova manastira Poganovo iz 1395. godine, zadužbina despota Konstantina Dragaša i vizantijske carice Jelene Dragaš u kanjonu reke Jerma (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'},
        {'url': 'images/monasteries/poganovo_gal_2.jpg', 'caption': 'Freske kritskih i solunskih majstora iz 1499. godine i dvostrana čudotvorna ikona Poganovska u naosu hrama (Izvor: Galerija fresaka Narodnog muzeja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/poganovo.jpg', 'caption': 'Pogled na manastirski kompleks Poganovo sa tremom i konacima u kanjonu Jerme kod Dimitrovgrada (Izvor: Spomenici kulture od izuzetnog značaja / Vikimedijina ostava)'}
    ],
    'rsovci': [
        {'url': 'images/monasteries/rsovci_gal_1.jpg', 'caption': 'Pećinska crkva Svetih Petra i Pavla u Rsovcima na Staroj Planini iz 13. veka, sa svetski jedinstvenom freskom Hrista Mladenca (Ćelavi Isus) (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'},
        {'url': 'images/monasteries/rsovci.jpg', 'caption': 'Ulaz u pećinski hram u krečnjačkoj steni kod sela Rsovci (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/rsovci_gal_2.jpg', 'caption': 'Freska Isusa Hrista Mladenca oslikana na živoj steni u pećini u Rsovcima (Izvor: Galerija fresaka / Vikimedijina ostava)'}
    ],
    'rudare': [
        {'url': 'images/monasteries/rudare_gal_1.jpg', 'caption': 'Crkva Svete Petke manastira Rudare iz 18. veka (na temeljima vizantijske bazilike iz 5. veka) kod Leskovca (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'},
        {'url': 'images/monasteries/rudare.jpg', 'caption': 'Spratni konak u moravskom stilu i zvonik u Rudaru (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/rudare_gal_2.jpg', 'caption': 'Pogled na manastirski kompleks Rudare iznad reke Veternice (Izvor: Vikimedijina ostava)'}
    ],
    'sinjacki': [
        {'url': 'images/monasteries/sinjacki.jpg', 'caption': 'Crkva Svetog Nikole manastira Sinjac iz 17. veka u klisuri Nišave kod Bele Palanke (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'}
    ],
    'sicevo': [
        {'url': 'images/monasteries/sicevo_gal_2.jpg', 'caption': 'Crkva Bogorodičinog Vavedenja manastira Sićevo iz 1644. godine u Sićevačkoj klisuri, gde je Nadežda Petrović osnovala prvu Jugoslovensku umetničku koloniju 1905. godine (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'},
        {'url': 'images/monasteries/sicevo.jpg', 'caption': 'Južna fasada crkve sa očuvanim zidnim slikarstvom iz 17. veka u Sićevu (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/sicevo_gal_1.jpg', 'caption': 'Pogled na manastirski konak i terasu sa vidikovcem na Sićevačku klisuru (Izvor: Vikimedijina ostava)'}
    ],
    'smilovci': [
        {'url': 'images/monasteries/smilovci_gal_2.jpg', 'caption': 'Crkva Svete Petke manastira Smilovci iz 1839. godine podno planine Vidlič na Staroj Planini (Izvor: Eparhija niška / Vikimedijina ostava)'},
        {'url': 'images/monasteries/smilovci.jpg', 'caption': 'Zapadno pročelje crkve sa drvenim tremom u Smilovcima kod Dimitrovgrada (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/smilovci_gal_1.jpg', 'caption': 'Manastirski konak i lekoviti izvor Svete Petke u Smilovcima (Izvor: Vikimedijina ostava)'}
    ],
    'sukovo': [
        {'url': 'images/monasteries/sukovo_gal_1.jpg', 'caption': 'Crkva Uspenja Presvete Bogorodice manastira Sukovo iz 1857. godine na reci Jerma kod Pirota, poznata po jedinstvenim freskama Svetog Hristofora sa psećom glavom i Bogorodice sa krilima (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'},
        {'url': 'images/monasteries/sukovo_gal_2.jpg', 'caption': 'Zidno slikarstvo u naosu hrama rad samokovskog slikara Vasilija Pophrisova iz 1869. godine (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/sukovo.jpg', 'caption': 'Manastirski konaci i porta manastira Sukovo u dolini reke Jerma (Izvor: Zvanični sajt manastira Sukovo / Vikimedijina ostava)'}
    ],
    'sveti-jovan': [
        {'url': 'images/monasteries/sveti-jovan_gal_2.jpg', 'caption': 'Crkva Svetog Jovana Krstitelja manastira Sveti Jovan iz 16. veka na brdu iznad Gornjeg Matejevca (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'},
        {'url': 'images/monasteries/sveti-jovan.jpg', 'caption': 'Zapadna fasada sa kamenim zvonikom crkve Svetog Jovana (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'},
        {'url': 'images/monasteries/sveti-jovan_gal_1.jpg', 'caption': 'Manastirska porta sa pogledom na Nišku kotlinu (Izvor: Vikimedijina ostava)'}
    ],
    'sveti-roman': [
        {'url': 'images/monasteries/sveti-roman_gal_2.jpg', 'caption': 'Crkva Blagoveštenja Presvete Bogorodice manastira Sveti Roman iz 9. ili 10. veka (jedna od najstarijih srpskih svetinja gde počivaju mošti Svetog Romana Sinaita i srce pukovnika Nikolaja Rajevskog – Tolstojevog Vronskog) kod Đunisa (Izvor: Republički zavod za zaštitu spomenika kulture / Vikimedijina ostava)'},
        {'url': 'images/monasteries/sveti-roman_gal_1.jpg', 'caption': 'Kivot sa čudotvornim moštima Svetog Romana Sinaita u južnom paraklisu hrama (Izvor: Eparhija niška / Vikimedijina ostava)'},
        {'url': 'images/monasteries/sveti-roman.jpg', 'caption': 'Zvonara iz 1852. godine i spratni konaci manastira Sveti Roman iznad Južne Morave (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'}
    ],
    'temska': [
        {'url': 'images/monasteries/temska_gal_2.jpg', 'caption': 'Crkva Svetog Đorđa manastira Temska iz 14. veka, zadužbina braće Dejanovića na reci Temštici podno Stare Planine (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'},
        {'url': 'images/monasteries/temska_gal_1.jpg', 'caption': 'Očuvane freske iz 1576. i 1654. godine u priprati i naosu crkve Svetog Đorđa u Temskoj (Izvor: Spomenici kulture od velikog značaja / Vikimedijina ostava)'},
        {'url': 'images/monasteries/temska.jpg', 'caption': 'Drveni zvonik i spratni konak manastira Temska kod Pirota (Izvor: Vikimedijina ostava)'}
    ],
    'tesice': [
        {'url': 'images/monasteries/tesice_gal_1.jpg', 'caption': 'Hram Pokrova Presvete Bogorodice manastira Tešice kod Aleksinca (Izvor: Eparhija niška / Vikimedijina ostava)'},
        {'url': 'images/monasteries/tesice.jpg', 'caption': 'Zapadna fasada sa zvonikom manastira Tešice (Izvor: Vikimedijina ostava)'}
    ],
    'veta': [
        {'url': 'images/monasteries/veta.jpg', 'caption': 'Crkva Uspenja Presvete Bogorodice manastira Veta iz 14. veka (zadužbina Mrnjavčevića) podno Suve Planine (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'},
        {'url': 'images/monasteries/veta_gal_1.jpg', 'caption': 'Zapadni ulaz u jednobrodni kameni hram u Veti kod Crvene Reke (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'},
        {'url': 'images/monasteries/veta_gal_2.jpg', 'caption': 'Manastirska porta i zvonara u Veti (Izvor: Vikimedijina ostava)'}
    ],
    'visocka-rzana': [
        {'url': 'images/monasteries/visocka-rzana.jpg', 'caption': 'Crkva Svete Bogorodice manastira Visočka Ržana na Staroj Planini (Izvor: Eparhija niška / Vikimedijina ostava)'}
    ],
    'zavidnice': [
        {'url': 'images/monasteries/zavidnice_gal_1.jpg', 'caption': 'Hram Svetog proroka Ilije manastira Zavidnice iz 14. veka kod Babušnice (Izvor: Eparhija niška / Vikimedijina ostava)'},
        {'url': 'images/monasteries/zavidnice_gal_2.jpg', 'caption': 'Manastirski konak i porta u Zavidnici (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/zavidnice.jpg', 'caption': 'Zapadna fasada crkve Svetog Ilije u Zavidnici (Izvor: Vikimedijina ostava)'}
    ],
    'cirik': [
        {'url': 'images/monasteries/cirik_gal_2.jpg', 'caption': 'Crkva Svete Petke manastira Ćirik kod Dimitrovgrada (Izvor: Eparhija niška / Vikimedijina ostava)'},
        {'url': 'images/monasteries/cirik.jpg', 'caption': 'Zapadni ulaz i manastirsko dvorište u Ćiriku (Izvor: Vikimedijina ostava)'},
        {'url': 'images/monasteries/cirik_gal_1.jpg', 'caption': 'Manastirski konak u Ćiriku (Izvor: Vikimedijina ostava)'}
    ],
    'ciniglavci': [
        {'url': 'images/monasteries/ciniglavci.jpg', 'caption': 'Crkva Svetog Dimitrija manastira Činiglavci kod Pirota (Izvor: Eparhija niška / Vikimedijina ostava)'}
    ],
    'cukljenik': [
        {'url': 'images/monasteries/cukljenik_gal_1.jpg', 'caption': 'Crkva Svetog Nikole manastira Čukljenik iz 14. veka u kanjonu reke Vučjanke podno planine Kukavice (Izvor: Zavod za zaštitu spomenika kulture Niš / Vikimedijina ostava)'},
        {'url': 'images/monasteries/cukljenik.jpg', 'caption': 'Zapadni trem i kameni zvonik manastira Čukljenik kod Leskovca (Izvor: Spomenici kulture u Srbiji / Vikimedijina ostava)'},
        {'url': 'images/monasteries/cukljenik_gal_2.jpg', 'caption': 'Pogled na manastirski kompleks Čukljenik u kanjonu Vučjanke (Izvor: Vikimedijina ostava)'}
    ],
    'djunis': [
        {'url': 'images/monasteries/djunis.jpg', 'caption': 'Veliki saborni hram Pokrova Presvete Bogorodice i mala crkva nad čudotvornim izvorom u manastiru Đunis iz 1898. godine (Izvor: Zvanični sajt manastira Đunis / Vikimedijina ostava)'},
        {'url': 'images/monasteries/djunis_gal_1.jpg', 'caption': 'Mala crkva podignuta na mestu javljanja Presvete Bogorodice devojčici Milojki 1898. godine (Izvor: Eparhija niška / Vikimedijina ostava)'},
        {'url': 'images/monasteries/djunis_gal_2.jpg', 'caption': 'Pogled na manastirski kompleks Đunis sa konacima i portom u dolini Južne Morave (Izvor: Vikimedijina ostava)'}
    ]
}

def apply_master_update():
    print("=== POKRETANJE MASTER AŽURIRANJA ZA SVIH 15 EPARHIJA ===\n")

    for db_path in [DB_STORAGE, DB_DATABASE]:
        if not os.path.exists(db_path):
            print(f"Preskačem: {db_path}")
            continue
        print(f"Ažuriram bazu podataka: {db_path}")
        conn = sqlite3.connect(db_path)
        cur = conn.cursor()

        processed = 0
        total_images = 0
        for slug, img_list in MASTER_DATA.items():
            cur.execute("SELECT id, name FROM monasteries WHERE slug=?", (slug,))
            row = cur.fetchone()
            if not row:
                print(f"  [UPOZORENJE] Manastir '{slug}' nije pronađen u bazi!")
                continue
            m_id, name = row

            # Obriši stare slike za ovaj manastir
            cur.execute("DELETE FROM monastery_images WHERE monastery_id=?", (m_id,))

            # Postavi kartičnu sliku (najbolji spoljašnji izgled)
            card_url = img_list[0]['url']
            cur.execute("UPDATE monasteries SET image_url=? WHERE id=?", (card_url, m_id))

            # Unesi sve proverene slike sa autentičnim opisima
            for sort_idx, item in enumerate(img_list, 1):
                cur.execute(
                    """INSERT INTO monastery_images 
                       (monastery_id, url, caption, sort_order, created_at, updated_at) 
                       VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))""",
                    (m_id, item['url'], item['caption'], sort_idx)
                )
                total_images += 1
            processed += 1

        conn.commit()
        conn.close()
        print(f"✓ Baza {db_path} uspešno ažurirana: {processed} manastira, {total_images} slika.")

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
            print(f"✓ Sinhronizovan CSV: {out}")
    conn.close()

    print("\n✓ KOMPLETNO MASTER AŽURIRANJE USPEŠNO ZAVRŠENO!")

if __name__ == '__main__':
    apply_master_update()
