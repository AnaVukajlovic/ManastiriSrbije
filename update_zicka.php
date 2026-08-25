<?php

/**
 * SISTEMSKO ČIŠĆENJE I SINHRONIZACIJA - EPARHIJA ŽIČKA (ID 1)
 * Pravoslavni Svetionik — Master rad
 * Izvor: commons.wikimedia.org / manastiri.rs
 */

use App\Models\Monastery;
use App\Models\MonasteryImage;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "====================================================================\n";
echo "POKRETANJE REVIZIJE I ČIŠĆENJA ZA EPARHIJU ŽIČKU (ID 1)\n";
echo "====================================================================\n\n";

// 1. Definicija verifikovanih podataka, tačnih kartičnih slika i usklađenih galerija
$zicka_data = [
    // 206: Manastir Blagoveštenje (Ovčar)
    206 => [
        'name' => 'Manastir Blagoveštenje',
        'card_image' => 'images/monasteries/blagovestenje-ovcar.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/blagovestenje-ovcar.jpg',
                'caption' => 'Crkva Blagoveštenja pod šindrom sa drvenim tremom i drveni trospratni zvonik na kamenom postolju u porti<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/blagovestenje-ovcar_gal_1.jpg',
                'caption' => 'Pogled na kupolu crkve pokrivenu drvenom šindrom i krovove konaka kroz bujno zelenilo i šumu<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/blagovestenje-ovcar_gal_2.jpg',
                'caption' => 'Veliki beli manastirski konak sa terasom, kamenim podzidom i parkingom u podnožju šumovitog brda<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/blagovestenje-ovcar_gal_3.jpg',
                'caption' => 'Mermerna spomen-ploča na zidu konaka o obnovi zgrade 1977. godine sa blagoslovom episkopa žičkog dr Vasilija<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 207: Manastir Dubrava
    207 => [
        'name' => 'Manastir Dubrava',
        'card_image' => 'images/monasteries/dubrava.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/dubrava.jpg',
                'caption' => 'Crkva Svetog Vasilija Ostroškog na travnatom uzvišenju sa pogledom na šumovite planinske vence Zlatibora pod plavim nebom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/dubrava_gal_1.jpg',
                'caption' => 'Hram Svetog Vasilija Ostroškog sa kamenom coklom, popločanom stazom i metalnim krstovima na krovu<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/dubrava_gal_2.jpg',
                'caption' => 'Kameno-zidana kula-zvonik i konak sa drvenom nadstrešnicom u cvetnom dvorištu manastira Dubrava<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/dubrava_gal_3.jpg',
                'caption' => 'Šumska staza sa kamenim postoljem, cvećem i krstom, koja vodi kroz drveće ka kapiji<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 208: Manastir Godovik
    208 => [
        'name' => 'Manastir Godovik',
        'card_image' => 'images/monasteries/godovik.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/godovik.jpg',
                'caption' => 'Srednjovekovna crkva Svetog Đorđa od lomljenog kamena sa kupolom pod konusnim krovom na šumovitoj padini u Godoviku<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/godovik_gal_1.jpg',
                'caption' => 'Pogled iz daljine na staru crkvu Svetog Đorđa u zelenilu iza velikog razgranatog drveta<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/godovik_gal_2.jpg',
                'caption' => 'Nova crkva Svetog Ilije u Godoviku iz 19. veka sa visokim baroknim zvonikom i pukotinama na fasadi<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/godovik_gal_3.jpg',
                'caption' => 'Velika spomen-ploča pod nadstrešnicom na fasadi crkve posvećena izginulim ratnicima 1912–1918. godine iz opštine požeške<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 209: Manastir Gradac
    209 => [
        'name' => 'Manastir Gradac',
        'card_image' => 'images/monasteries/gradac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/gradac.jpg',
                'caption' => 'Pogled na Bogorodičinu crkvu manastira Gradac kroz kameni zasvođeni ulazni portal i stepenište<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/gradac_gal_1.jpg',
                'caption' => 'Pogled odozgo na oltarsku apsidu, krovove i kupolu Bogorodičine crkve u zelenoj porti sa arheološkim ostacima<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/gradac_gal_2.jpg',
                'caption' => 'Zapadna fasada crkve sa kupolom i konacima u pozadini viđena iza kamenog ogradnog zida i kapije<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/gradac_gal_3.jpg',
                'caption' => 'Glavni zapadni gotički portal i bifora na kamenoj fasadi Bogorodičine crkve sa drvenim zvonikom i konakom desno<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 210: Manastir Ilinje
    210 => [
        'name' => 'Manastir Ilinje',
        'card_image' => 'images/monasteries/ilinje-ovcar.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/ilinje-ovcar.jpg',
                'caption' => 'Crkva Svetog proroka Ilije na uzvišenju među gustim krošnjama drveća pod plavim nebom sa belim oblacima<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/ilinje-ovcar_gal_1.jpg',
                'caption' => 'Visoki drveni rešetkasti zvonik na kamenom postolju okružen šumom pri zalasku sunca<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/ilinje-ovcar_gal_2.jpg',
                'caption' => 'Bela crkva Svetog proroka Ilije sa kamenom bazom i rozetom na vrhu travnatog brežuljka okružena šumom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/ilinje-ovcar_gal_3.jpg',
                'caption' => 'Pogled na crkvu Svetog Ilije kroz rascvetalu prolećnu livadu sa divljim ljubičastim cvećem<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 211: Manastir Isposnica Svetog Save
    211 => [
        'name' => 'Manastir Isposnica Svetog Save',
        'card_image' => 'images/monasteries/isposnica-svetog-save.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/isposnica-svetog-save.jpg',
                'caption' => 'Kameni ulaz u Gornju isposnicu Svetog Save sa drvenim vratima, stepeništem i ikonom Svetog Save iznad nadvratnika, urezan u liticu<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/isposnica-svetog-save_gal_1.jpg',
                'caption' => 'Crkvica Svetog Đorđa sa kamenim krovom uzidana pod masivnu krečnjačku stenu isposnice<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/isposnica-svetog-save_gal_2.jpg',
                'caption' => 'Crno-bela fotografija drvenog zvonika sa zvonom prislonjenog uz stenu i kamenog prolaza u isposnici<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/isposnica-svetog-save_gal_3.jpg',
                'caption' => 'Fascinantan pogled na višespratnu kamenu kulu isposnice ugrađenu direktno u vertikalnu liticu planine Radočelo<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 212: Manastir Ježevica
    212 => [
        'name' => 'Manastir Ježevica',
        'card_image' => 'images/monasteries/jezevica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/jezevica.jpg',
                'caption' => 'Crkva Svetog Nikole u Ježevici od klesanog kamena sa visokim baroknim zvonikom i drvetom u porti<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/jezevica_gal_1.jpg',
                'caption' => 'Kupola crkve od kamena i opeke sa ukrasnim metalnim krstom na vrhu i golubovima na krovu od šindre<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/jezevica_gal_2.jpg',
                'caption' => 'Prizemni manastirski konak svetle fasade sa tremom i crvenim stubovima u zelenilu porte<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/jezevica_gal_3.jpg',
                'caption' => 'Stari kameni nadgrobni spomenik (krajputaš) sa isklesanim reljefnim krstom i ćiriličnim natpisom iz 1915. godine u porti<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 213: Manastir Jovanje (Ovčar-Kablar)
    213 => [
        'name' => 'Manastir Jovanje',
        'card_image' => 'images/monasteries/jovanje-ovcar-kablar.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/jovanje-ovcar-kablar.jpg',
                'caption' => 'Kamena crkva Rođenja Svetog Jovana Krstitelja sa zvonikom, kupolom i konacima u uređenoj manastirskoj porti<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/jovanje-ovcar-kablar_gal_1.jpg',
                'caption' => 'Zvonik i osmostrana kupola crkve sa pozlaćenim krstovima i kamenom rozetom iznad ulaza<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/jovanje-ovcar-kablar_gal_2.jpg',
                'caption' => 'Zapadno pročelje kamene crkve sa reljefom Svetog Jovana iznad vrata i natkrivenim tremovima u porti<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/jovanje-ovcar-kablar_gal_3.jpg',
                'caption' => 'Živopis i freske unutrašnjosti hrama sa prikazom svetih žena (Sveta Nedelja, Haritina, Petka) i biblijskih scena<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 214: Manastir Klisura
    214 => [
        'name' => 'Manastir Klisura',
        'card_image' => 'images/monasteries/klisura.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/klisura.jpg',
                'caption' => 'Zapadna fasada crkve Svetih arhangela od sige sa drvenom pripratom i kupolom na vrhu u manastiru Klisura<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/klisura_gal_1.jpg',
                'caption' => 'Veliki manastirski konaci sa crvenim krovovima, drvenim mostićem i posetiocima na zelenoj padini<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/klisura_gal_2.jpg',
                'caption' => 'Jedinstvena freska slepog guslara Filipa Višnjića sa dvoje dece u narodnoj nošnji u priprati manastira Klisura<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/klisura_gal_3.jpg',
                'caption' => 'Pogled na crkvu Svetih arhangela, drveni zvonik i visoki kameni potporni zid u podnožju šume<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 215: Manastir Kovilje
    215 => [
        'name' => 'Manastir Kovilje',
        'card_image' => 'images/monasteries/kovilje.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/kovilje.jpg',
                'caption' => 'Zvonara pod šindrom sa drvenom konstrukcijom i bela crkva Svetih arhangela pod krošnjama drveća<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/kovilje_gal_1.jpg',
                'caption' => 'Pogled odozdo na kamenu belu fasadu crkve Svetih arhangela i drvenu klupu ispred hrama<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/kovilje_gal_2.jpg',
                'caption' => 'Ikona Gospoda Isusa Hrista sa upaljenim kandilom u pećinskoj crkvi Svetog Nikole u Kovilju<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/kovilje_gal_3.jpg',
                'caption' => 'Unutrašnjost hrama sa drvenim rezbarenim ikonostasom, bogatim horosom (polijelejem) i freskama na zidovima<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 216: Manastir Moravci
    216 => [
        'name' => 'Manastir Moravci',
        'card_image' => 'images/monasteries/moravci.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/moravci.jpg',
                'caption' => 'Zapadna fasada crkve Presvete Bogorodice od tesanog kamena sa visokim baroknim zvonikom i metalnom ogradom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/moravci_gal_1.jpg',
                'caption' => 'Mermerna nadgrobna ploča arhimandrita Gerasima Georgijevića – Hadži Đere, posečenog u Seči knezova 1804. godine<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/moravci_gal_2.jpg',
                'caption' => 'Južna strana crkve sa polukružnom apsidom, ukrasnim slepim arkadama i vitkim baroknim zvonikom na padini<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/moravci_gal_3.jpg',
                'caption' => 'Pogled na gornji deo kamene fasade i barokni zvonik crkve sa pozlaćenim krstom pod vedrim plavim nebom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 217: Manastir Nikolje (Ovčar-Kablar)
    217 => [
        'name' => 'Manastir Nikolje',
        'card_image' => 'images/monasteries/nikolje-ovcar-kablar.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/nikolje-ovcar-kablar.jpg',
                'caption' => 'Pogled na manastirski kompleks Nikolje sa visokim drvenim zvonikom, kamenim zidom i crkvom Svetog Nikole<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/nikolje-ovcar-kablar_gal_1.jpg',
                'caption' => 'Crkva Svetog Nikole sa drvenim bunarom u prvom planu i monahinjom ispred ulaza u hram<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/nikolje-ovcar-kablar_gal_2.jpg',
                'caption' => 'Unutrašnjost crkve Nikolje sa drvenim ikonostasom, bogatim lusterom (polijelejem) i tepihom na podu<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/nikolje-ovcar-kablar_gal_3.jpg',
                'caption' => 'Drevne freske u manastiru Nikolje sa prikazom Svetog Nikole, Stefana Dečanskog i svetih pustinjaka<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 218: Manastir Nova Pavlica
    218 => [
        'name' => 'Manastir Nova Pavlica',
        'card_image' => 'images/monasteries/nova-pavlica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/nova-pavlica.jpg',
                'caption' => 'Bela crkva Vavedenja Presvete Bogorodice sa osmostranom kupolom i masivnim četvorospratnim zvonikom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/nova-pavlica_gal_1.jpg',
                'caption' => 'Pogled na crkvu Nove Pavlice sa istočne strane preko rascvetale livade sa poljskim cvećem<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/nova-pavlica_gal_2.jpg',
                'caption' => 'Unutrašnjost crkve sa kamenim stubovima, belim oltarskim zavesama, klesanim amvonom i freskama u naosu<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/nova-pavlica_gal_3.jpg',
                'caption' => 'Visoka bela zvonara manastira Nova Pavlica sa lučnim otvorima i krstom na vrhu, slikana iza mladog drveta<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 219: Manastir Preobraženje (Ovčar-Kablar)
    219 => [
        'name' => 'Manastir Preobraženje',
        'card_image' => 'images/monasteries/preobrazenje-ovcar-kablar.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/preobrazenje-ovcar-kablar.jpg',
                'caption' => 'Bela crkva Preobraženja Gospodnjeg sa tremom na arkadama i kamenom kupolom pod stenama Ovčara<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/preobrazenje-ovcar-kablar_gal_1.jpg',
                'caption' => 'Pogled izbliza na kamenu osmostranu kupolu crkve sa krstom na vrhu pod stenovitim masivom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/preobrazenje-ovcar-kablar_gal_2.jpg',
                'caption' => 'Velika braon turistička i informativna tabla dobrodošlice za manastir Preobraženje pored puta u šumi<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/preobrazenje-ovcar-kablar_gal_3.jpg',
                'caption' => 'Unutrašnjost crkve sa drvenim pevničkim stolom, celivajućim ikonama i zidovima ukrašenim ikonama i slikama<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 220: Manastir Pridvorica
    220 => [
        'name' => 'Manastir Pridvorica',
        'card_image' => 'images/monasteries/pridvorica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/pridvorica.jpg',
                'caption' => 'Pogled odozgo na srednjovekovnu kamenu crkvu Preobraženja Gospodnjeg sa drvenom ogradom i zvonikom u zelenilu<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/pridvorica_gal_1.jpg',
                'caption' => 'Zapadna i južna kamena fasada hrama sa polukružnim portalom i mozaikom Preobraženja iznad ulaza<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/pridvorica_gal_2.jpg',
                'caption' => 'Beli ikonostas u crkvi sa pozlaćenim carskim dverima i prestonim ikonama Presvete Bogorodice i Gospoda Isusa Hrista<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/pridvorica_gal_3.jpg',
                'caption' => 'Oltarska apsida i osmostrana kupola crkve od lomljenog kamena sa belim nadgrobnim spomenikom u travi<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 221: Manastir Rača
    221 => [
        'name' => 'Manastir Rača',
        'card_image' => 'images/monasteries/raca.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/raca.jpg',
                'caption' => 'Zapadna fasada crkve Vaznesenja Hristovog od lomljenog kamena sa freskom iznad portala, belim mermernim spomenikom i kulom-zvonikom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/raca_gal_1.jpg',
                'caption' => 'Drvena skulptura arhimandrita Hadži Melentija Stevanovića sa podignutom sabljom u desnoj i krstom u levoj ruci u porti manastira<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/raca_gal_2.jpg',
                'caption' => 'Visoki monumentalni ikonostas u crkvi sa pozlaćenim carskim dverima, prestonim ikonama i freskama na zidovima<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/raca_gal_3.jpg',
                'caption' => 'Svodna freska Silaska Svetog Duha na apostole sa Presvetom Bogorodicom u sredini i pogledom na kupolu ispod<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 222: Manastir Rujan
    222 => [
        'name' => 'Manastir Rujan',
        'card_image' => 'images/monasteries/rujan.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/rujan.jpg',
                'caption' => 'Pogled na crkvu Svetog Đorđa od crvenkastog kamena, visoku kulu-zvonik i konake manastira Rujan na brdu<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/rujan_gal_1.jpg',
                'caption' => 'Visoka kamena kula-zvonik manastira Rujan sa bronzanom statuom monaha Teodosija za štamparskom presom u podnožju<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/rujan_gal_2.jpg',
                'caption' => 'Oltarska trikonhalna apsida crkve Svetog Đorđa od crvenkastog kamena sa mermernim grobom episkopa Hrizostoma<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/rujan_gal_3.jpg',
                'caption' => 'Zapadno pročelje crkve od crvenkastog kamena sa drvenim tremom, srpskom trobojkom i mozaikom Svetog Đorđa iznad ulaza<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 223: Manastir Sabor srpskih svetitelja (Bukovica)
    223 => [
        'name' => 'Manastir Sabor srpskih svetitelja',
        'card_image' => 'images/monasteries/sabor.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sabor.jpg',
                'caption' => 'Crkva Sabora srpskih svetitelja od crvene opeke i kamena sa drvenom zvonarom na travnjaku u Bukovici<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/sabor_gal_1.jpg',
                'caption' => 'Ulazna manastirska kapija sa krovom od crepa, metalnim krstom i prilaznim putem ka imanju<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/sabor_gal_2.jpg',
                'caption' => 'Crna mermerna tabla sa isklesanim krstom i ćiriličnim natpisom "MANASTIR SABOR SRPSKIH SVETITELJA"<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/sabor_gal_3.jpg',
                'caption' => 'Prizemna manastirska zgrada sa belom fasadom, krovom od crepa i drvenim klupama ispred<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 224: Manastir Savinac
    224 => [
        'name' => 'Manastir Savinac',
        'card_image' => 'images/monasteries/savinac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/savinac.jpg',
                'caption' => 'Južna kamena fasada crkve Svetog Save od klesanog kamena sa osmostranom kupolom na zelenom travnjaku u Savincu<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/savinac_gal_1.jpg',
                'caption' => 'Noćna fotografija crkve Svetog Save u Savincu sa osvetljenom kamenom fasadom, kapijom i drvenim zvonikom u pozadini<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/savinac_gal_2.jpg',
                'caption' => 'Klesana kamena osmostrana kupola crkve Svetog Save sa ukrasnim metalnim krstovima<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/savinac_gal_3.jpg',
                'caption' => 'Pogled na crkvu Svetog Save, drveni zvonik i kameni mauzolej (grobnicu) na prostranoj travnatoj padini u Savincu<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 225: Manastir Sretenje (Ovčar-Kablar)
    225 => [
        'name' => 'Manastir Sretenje',
        'card_image' => 'images/monasteries/sretenje.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sretenje.jpg',
                'caption' => 'Zvonik i bela fasada crkve Sretenja Gospodnjeg sa popločanom stazom i četinarom u porti<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/sretenje_gal_1.jpg',
                'caption' => 'Panoramski pogled odozgo na manastirski kompleks Sretenje sa crkvom i konacima u zagrljaju šumovitih planina<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/sretenje_gal_2.jpg',
                'caption' => 'Kamena spomen-ploča sa natpisom o obnovi česme koju je podigao jeromonah Jona Veinović iz Dalmacije 1934. godine<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/sretenje_gal_3.jpg',
                'caption' => 'Monahinja u hodu manastirskom portom pored monaških grobova sa krstovima i crkve sa zvonikom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 226: Manastir Stara Pavlica
    226 => [
        'name' => 'Manastir Stara Pavlica',
        'card_image' => 'images/monasteries/stara-pavlica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/stara-pavlica.jpg',
                'caption' => 'Ostaci ranosrednjovekovne crkve Svetih apostola Petra i Pavla od kamena i opeke sa očuvanom kupolom na uzvišenju<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/stara-pavlica_gal_1.jpg',
                'caption' => 'Pogled odozdo na staru kamenu crkvu sa kupolom kroz procvetalu livadu sa ljubičastim poljskim cvećem<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/stara-pavlica_gal_2.jpg',
                'caption' => 'Stara Pavlica na vrhu travnatog brda sa drvetom i kamenim zidom pod vedrim nebom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/stara-pavlica_gal_3.jpg',
                'caption' => 'Drveno-kameno stepenište koje vodi uz travnatu padinu ka ostacima crkve Stara Pavlica<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 227: Manastir Stubal
    227 => [
        'name' => 'Manastir Stubal',
        'card_image' => 'images/monasteries/stubal.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/stubal.jpg',
                'caption' => 'Popločano dvorište manastira Stubal oivičeno dekorativnim tujama koje vodi ka beloj crkvi Svete Petke sa zvonikom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/stubal_gal_1.jpg',
                'caption' => 'Pogled duž popločane staze sa tujama i drvenim stolom ka beloj crkvi sa zvonikom pod šumovitim brdom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/stubal_gal_2.jpg',
                'caption' => 'Replika biblijske Golgote sa velikim drvenim krstom na veštačkom kamenom uzvišenju i tablom sa natpisom "GOLGOTA"<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/stubal_gal_3.jpg',
                'caption' => 'Replika Pećine Rođenja Hristovog (Vitlejemska pećina) sa kamenim stubovima, ikonom Bogorodice i natpisom "PEĆINA ROĐENJA"<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 228: Manastir Studenica
    228 => [
        'name' => 'Manastir Studenica',
        'card_image' => 'images/monasteries/studenica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/studenica.jpg',
                'caption' => 'Bogorodičina crkva manastira Studenica građena od belog mermera sa crvenom kupolom i Radoslavljevom pripratom od kamena<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/studenica_gal_1.jpg',
                'caption' => 'Kraljeva crkva (posvećena Svetim Joakimu i Ani) u manastiru Studenica sa crvenom kupolom i pripratom u zelenilu<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/studenica_gal_2.jpg',
                'caption' => 'Jugozapadni pogled na Bogorodičinu crkvu i Radoslavljevu pripratu sa Kraljevom crkvom u pozadini manastirske porte<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/studenica_gal_3.jpg',
                'caption' => 'Crkva Svetog Nikole (Nikoljača) od lomljenog kamena pod krovom od ćeramide u kompleksu manastira Studenica<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 229: Manastir Sveta Trojica (Dučalovići)
    229 => [
        'name' => 'Manastir Sveta Trojica',
        'card_image' => 'images/monasteries/sveta-trojica-ovcar.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sveta-trojica-ovcar.jpg',
                'caption' => 'Južna i istočna fasada crkve Svete Trojice od tesanog kamena sa osmostranom kupolom u podnožju Ovčara<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/sveta-trojica-ovcar_gal_1.jpg',
                'caption' => 'Crno-bela fotografija manastirskog hrama sa istočne strane i susednih konaka pod planinskim padinama<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/sveta-trojica-ovcar_gal_2.jpg',
                'caption' => 'Crno-bela fotografija crkve Svete Trojice sa južne strane sa kamenom česmom i stolom u porti<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/sveta-trojica-ovcar_gal_3.jpg',
                'caption' => 'Crno-bela fotografija celog manastirskog kompleksa sa crkvom i konacima posmatrana odozgo kroz šumu<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 230: Manastir Trnava
    230 => [
        'name' => 'Manastir Trnava',
        'card_image' => 'images/monasteries/trnava.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/trnava.jpg',
                'caption' => 'Južna fasada crkve Blagoveštenja (Svetog Nikole) u Trnavi od lomljenog kamena sa kupolom i palionicom sveća u porti<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/trnava_gal_1.jpg',
                'caption' => 'Pogled na oltarsku apsidu i južni zid kamene crkve sa kupolom na zelenoj livadi u manastiru Trnava<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/trnava_gal_2.jpg',
                'caption' => 'Jugozapadni ugao kamene crkve sa metalnim ormarom za paljenje sveća i spomen-pločama na zidu<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/trnava_gal_3.jpg',
                'caption' => 'Oslikani kameni krajputaš kaplaru Spasoju Živkoviću u plavoj boji i beli mermerni spomenik učesnicima Hadži-Prodanove bune (1814-1989) u porti<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 231: Manastir Uspenje (Ovčar-Kablar)
    231 => [
        'name' => 'Manastir Uspenje',
        'card_image' => 'images/monasteries/uspenje-kablar.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/uspenje-kablar.jpg',
                'caption' => 'Manastir Uspenje na samom šumovitom vrhu litice Kablara pod oblačnim nebom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/uspenje-kablar_gal_1.jpg',
                'caption' => 'Pogled odozdo sa prilaznog puta na žutu fasadu crkve Uspenja Presvete Bogorodice i konake<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/uspenje-kablar_gal_2.jpg',
                'caption' => 'Detalj fasade crkve sa reljefnim krstom u niši, slikan kroz rascvetale grane i travu sa maslačcima<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/uspenje-kablar_gal_3.jpg',
                'caption' => 'Crno-bela fotografija manastirskog dvorišta sa natkrivenim drvenim tremom ispred ulaza u crkvu i monahinjom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 232: Manastir Uvac
    232 => [
        'name' => 'Manastir Uvac',
        'card_image' => 'images/monasteries/uvac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/uvac.jpg',
                'caption' => 'Crkva Rođenja Presvete Bogorodice od krupnog kamena sa drvenim krovom od šindre i koničnom kupolom u kanjonu Uvca<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/uvac_gal_1.jpg',
                'caption' => 'Pogled na kamenu ulaznu kapiju sa krovom od šindre i crkvu manastira Uvac na padini pod plavim nebom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/uvac_gal_2.jpg',
                'caption' => 'Pogled odozgo na manastirski kompleks Uvac, kamenu crkvu sa pomoćnim zgradama okruženu jesenjom šumom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/uvac_gal_3.jpg',
                'caption' => 'Pogled kroz kameni lučni prolaz na drveni zvonik sa krstom i kameni zid porte pred jesenjom šumom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 233: Manastir Vavedenje (Ovčar-Kablar)
    233 => [
        'name' => 'Manastir Vavedenje',
        'card_image' => 'images/monasteries/vavedenje.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/vavedenje.jpg',
                'caption' => 'Panoramski pogled na ceo manastirski kompleks sa crkvom Vavedenja Presvete Bogorodice, konakom sa drvenim kapcima i cvetno uređenom portom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/vavedenje-ovcar.jpg',
                'caption' => 'Beli zvonik i fasada crkve Vavedenja Presvete Bogorodice sa rascvetalim cvećem u prvom planu pod vedrim plavim nebom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/vavedenje-ovcar_gal_1.jpg',
                'caption' => 'Gornji sprat i osmostrana kupola belog zvonika sa lučnim i kružnim otvorima, krstom i granama četinara<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/vavedenje-ovcar_gal_2.jpg',
                'caption' => 'Crno-bela fotografija asfaltnog puta kroz Ovčarsko-kablarsku klisuru sa manastirom Vavedenje u daljini<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 234: Manastir Vaznesenje (Ovčar-Kablar)
    234 => [
        'name' => 'Manastir Vaznesenje',
        'card_image' => 'images/monasteries/vaznesenje-ovcar.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/vaznesenje-ovcar.jpg',
                'caption' => 'Kameni nadgrobni krst sa natpisom u prvom planu, lale i crkva Vaznesenja Gospodnjeg u pozadini<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/vaznesenje-ovcar_gal_1.jpg',
                'caption' => 'Bela kupola crkve Vaznesenja Gospodnjeg sa metalnim krstom na vrhu i kamenim krovnim vencem<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/vaznesenje-ovcar_gal_2.jpg',
                'caption' => 'Monaško groblje sa kamenim krstovima i cvećem, crkva Vaznesenja i novi zvonik sa konakom u porti<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/vaznesenje-ovcar_gal_3.jpg',
                'caption' => 'Unutrašnjost crkve — bela potkupolna konstrukcija sa prozorima na tamburu i lancem polijeleja<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 235: Manastir Voljavča (kod Bresnice)
    235 => [
        'name' => 'Manastir Voljavča',
        'card_image' => 'images/monasteries/voljavca-bresnica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/voljavca-bresnica.jpg',
                'caption' => 'Panoramski pogled na crkvu Svete Petke od crvene fasadne opeke i narandžaste konake manastira Voljavča na brdu<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/voljavca-bresnica_gal_1.jpg',
                'caption' => 'Crkva Svete Petke od crvene fasadne opeke sa kupolom, zvonikom i kamenom ulaznom kapijom u dvorištu<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/voljavca-bresnica_gal_2.jpg',
                'caption' => 'Oltarska apsida i osmostrana kupola crkve od crvene opeke sa metalnim krstom pod plavim nebom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/voljavca-bresnica_gal_3.jpg',
                'caption' => 'Zapadna fasada crkve od crvene opeke sa kamenim portalom, rozetom, drvenim vratima i zvonikom na vrhu<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 236: Manastir Vraćevšnica
    236 => [
        'name' => 'Manastir Vraćevšnica',
        'card_image' => 'images/monasteries/vracevsnica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/vracevsnica.jpg',
                'caption' => 'Beli manastirski konak sa visokom baroknom kulom-zvonikom, crvenim krovovima i kamenim ogradnim zidom u šumi<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/vracevsnica_gal_1.jpg',
                'caption' => 'Panoramski pogled iz daljine na manastirski kompleks Vraćevšnica ušuškan među obroncima planine Rudnik u jesenjim bojama<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/vracevsnica_gal_2.jpg',
                'caption' => 'Popločana staza oivičena visokim stablima smrča koja vodi ka ulaznoj kapiji i zvoniku manastira<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/vracevsnica_gal_3.jpg',
                'caption' => 'Crkva Svetog Đorđa od klesanih kamenih blokova sa reljefnim slepim arkadama, kupolom sa pozlaćenim krstom i cvetnom baštom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 237: Manastir Vujan
    237 => [
        'name' => 'Manastir Vujan',
        'card_image' => 'images/monasteries/vujan.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/vujan.jpg',
                'caption' => 'Pogled na manastirski kompleks Vujan sa crkvom Svetog arhangela Gavrila, visokim belim zvonikom i novim konacima u podnožju šumovite planine<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/vujan_gal_1.jpg',
                'caption' => 'Crkva Svetog arhangela Gavrila sa visokim belim zvonikom na kome se nalazi sat i drvenim tremom pod crepom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/vujan_gal_3.jpg',
                'caption' => 'Spomen-česma od klesanog kamena sa krstom i natpisom iz 1922. godine posvećena izginulim ratnicima 1912–1919. u manastirskoj porti<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
        ]
    ],

    // 238: Manastir Zgodačica
    238 => [
        'name' => 'Manastir Zgodačica',
        'card_image' => 'images/monasteries/zgodacica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/zgodacica.jpg',
                'caption' => 'Crkva i manastirski konak od crvene fasadne opeke sa dva zvonika pod snegom<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>',
                'sort_order' => 1
            ],
        ]
    ],

    // 239: Manastir Žiča
    239 => [
        'name' => 'Manastir Žiča',
        'card_image' => 'images/monasteries/zica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/zica.jpg',
                'caption' => 'Crvena crkva Svetog Spasa (Vaznesenja Hristovog) manastira Žiča sa kupolom i pripratom, obasjana suncem<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/zica_gal_1.jpg',
                'caption' => 'Krstionica (fijala) sa plavom kupolom, kamenim stubovima sa reljefnim prepletima i krstom na vrhu u zelenoj porti<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/zica_gal_2.jpg',
                'caption' => 'Mala crkva Svetih Teodora Tirona i Teodora Stratilata sa visokom kulom-zvonikom i čempresima u travnatoj porti<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/zica_gal_3.jpg',
                'caption' => 'Rezbarena drvena ulazna vrata uokvirena raskošnim kamenim portalom sa moravskim prepletima i reljefima<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 254: Manastir Stjenik
    254 => [
        'name' => 'Manastir Stjenik',
        'card_image' => 'images/monasteries/stjenik.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/stjenik.jpg',
                'caption' => 'Pogled na kompleks manastira Stjenik sa crkvom Rođenja Svetog Jovana Krstitelja, drvenom zvonarom na kamenom konaku i ogradom u gustoj šumi Jelice<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/stjenik_gal_1.jpg',
                'caption' => 'Karakterističan kameni prozor (bifora) od žutog peščara na svetloj fasadi crkve<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/stjenik_gal_3.jpg',
                'caption' => 'Crkva Rođenja Svetog Jovana Krstitelja sa prostranim drvenim tremom pod strmim krovom i pčelinjakom sa košnicama u porti<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
        ]
    ],
];

// 2. Sinhronizacija baze na obe putanje (database/database.sqlite i storage/database.sqlite)
$dbPaths = [
    database_path('database.sqlite'),
    storage_path('database.sqlite')
];

foreach ($dbPaths as $dbPath) {
    if (!file_exists($dbPath)) {
        echo "Baza ne postoji na putanji: {$dbPath}\n";
        continue;
    }

    echo "\n----------------------------------------------------\n";
    echo "AŽURIRANJE BAZE: {$dbPath}\n";
    echo "----------------------------------------------------\n";

    try {
        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->beginTransaction();

        foreach ($zicka_data as $monasteryId => $data) {
            $stmt = $pdo->prepare("UPDATE monasteries SET name = :name, image_url = :image_url, image = :img WHERE id = :id");
            $stmt->execute([
                ':name' => $data['name'],
                ':image_url' => $data['card_image'],
                ':img' => $data['card_image'],
                ':id' => $monasteryId
            ]);

            $stmtDel = $pdo->prepare("DELETE FROM monastery_images WHERE monastery_id = :id");
            $stmtDel->execute([':id' => $monasteryId]);

            $stmtIns = $pdo->prepare("INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (:m_id, :url, :caption, :sort_order, datetime('now'), datetime('now'))");
            foreach ($data['images'] as $imgData) {
                $stmtIns->execute([
                    ':m_id' => $monasteryId,
                    ':url' => $imgData['url'],
                    ':caption' => $imgData['caption'],
                    ':sort_order' => $imgData['sort_order'],
                ]);
            }

            echo "[+] [ID {$monasteryId}] {$data['name']}: Kartica -> {$data['card_image']} | Galerija -> " . count($data['images']) . " slika.\n";
        }

        $pdo->commit();
        echo "Baza na {$dbPath} je uspešno ažurirana!\n";
    } catch (\Exception $e) {
        $pdo->rollBack();
        echo "GREŠKA pri radu sa bazom ({$dbPath}): " . $e->getMessage() . "\n";
    }
}

echo "====================================================================\n";
echo "REVIZIJA I SINHRONIZACIJA ZA EPARHIJU ŽIČKU ZAVRŠENE USPEŠNO!\n";
echo "====================================================================\n";