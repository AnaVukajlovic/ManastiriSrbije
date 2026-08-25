<?php

/**
 * SISTEMSKO ČIŠĆENJE I SINHRONIZACIJA - EPARHIJA ŠABAČKA (ID 15)
 * Pravoslavni Svetionik — Master rad
 * Izvor: manastiri.rs
 */

use App\Models\Monastery;
use App\Models\MonasteryImage;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "====================================================================\n";
echo "POKRETANJE REVIZIJE I ČIŠĆENJA ZA EPARHIJU ŠABAČKU (ID 15)\n";
echo "====================================================================\n\n";

$src = '<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>';

// 1. Definicija verifikovanih podataka i čistih galerija (svaka slika proverena na disku)
$sabacka_data = [
    // 172: Manastir Bogoštica
    172 => [
        'name' => 'Manastir Bogoštica',
        'card_image' => 'images/monasteries/bogostica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/bogostica.jpg',
                'caption' => 'Crkva Svete Trojice sa konakom u manastiru Bogoštica kod Krupnja u Rađevini' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 173: Manastir Dobrić
    173 => [
        'name' => 'Manastir Dobrić',
        'card_image' => 'images/monasteries/dobric.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/dobric.jpg',
                'caption' => 'Crkva Svetih apostola Petra i Pavla sa tremom na stubovima u manastiru Dobrić kod Šapca u Pocerini' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 174: Manastir Dragojevac
    174 => [
        'name' => 'Manastir Dragojevac',
        'card_image' => 'images/monasteries/dragojevac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/dragojevac.jpg',
                'caption' => 'Crkva Vaznesenja Gospodnjeg i konaci manastira Dragojevac u Posavotamnavi' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 175: Manastir Kaona
    175 => [
        'name' => 'Manastir Kaona',
        'card_image' => 'images/monasteries/kaona.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/kaona.jpg',
                'caption' => 'Arkadni trem sa lučnim otvorima od crvene opeke i cvetnim lejama u manastirskom konaku Kaone' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/kaona_gal_1.jpg',
                'caption' => 'Unutrašnjost pećinske kapele sa freskom Rođenja Hristovog u kamenim stenama u Kaoni' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/kaona_gal_2.jpg',
                'caption' => 'Manastirsko jezero i uređeni park sa izvorom lekovite vode podno šume u Kaoni' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/kaona_gal_3.jpg',
                'caption' => 'Polukružni lučni ulaz od opeke i kamena u pećinsku krstionicu u manastiru Kaona' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 176: Manastir Ljubovija
    176 => [
        'name' => 'Manastir Ljubovija',
        'slug' => 'ljubovija',
        'city' => 'Podnemić, Ljubovija',
        'region' => 'Podrinje',
        'ktitor' => 'Karađorđe Ristanović (sa porodicom, u spomen roditeljima Kosti i Božani; osveštao episkop Lavrentije)',
        'godina_izgradnje' => '2005.',
        'lat' => 44.267299,
        'lng' => 19.4259715,
        'latitude' => 44.267299,
        'longitude' => 19.4259715,
        'description' => "OPŠTI PODACI:\nManastir Svete Trojice u Bjelim Vodama nalazi se u duhovnom okrilju Eparhije šabačke, u zaseoku Bjele Vode (selo Podnemić) kod Ljubovije, na živopisnim obroncima planine Nemić iznad reke Drine na području Azbukovice i Podrinja. Predstavlja noviju pravoslavnu svetinju podignutu 2005. godine, čiji je ktitor i zadužbinar privrednik Karađorđe Ristanović sa porodicom, koja svedoči o živoj veri, ljubavi prema precima i postojanosti duhovnog identiteta srpskog naroda.\n\nISTORIJA:\nIzgradnja manastira Svete Trojice u Bjelim Vodama započeta je 2003. godine na porodičnom imanju Ristanovića. Ktitor Karađorđe Ristanović, preduzetnik poreklom iz ovog kraja, podigao je svetinju u znak zahvalnosti Gospodu i u večni spomen na svoje pobožne roditelje Kostu i Božanu Ristanović. Glavni hram je završen i svečano osveštan u oktobru 2005. godine blagoslovom i činodejstvovanjem blaženopočivšeg episkopa šabačko-valjevskog Lavrentija. Vremenom je kompleks obogaćen novim konacima, visokim zvonikom, kapelama i etno-muzejskim selom „Ognjište“, te je manastir postao prepoznatljiv hodočasnički centar ovog dela Srbije.\n\nARHITEKTURA I UMETNOST:\nManastirska crkva Svete Trojice sagrađena je u srpsko-vizantijskom stilu, zidane kamene konstrukcije sa elegantnom kupolom na osmostranom tamburu i skladnim lučnim otvorima. Unutrašnjost hrama ukrašena je raskošnim duboreznim ikonostasom u pozlati i savremenim freskopisom koji prikazuje praznike i likove svetitelja. U neposrednoj blizini hrama uzdiže se monumentalni zvonik, dok prostrani manastirski konaci sa drvenim tremovima i vidikovcem savršeno dočaravaju tradicionalno graditeljstvo Azbukovice. Kompleks je poznat i po izvorima izuzetno čiste planinske vode po kojima je zaseok Bjele Vode i dobio ime.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nDanas je Manastir Ljubovija (Bjele Vode) aktivno duhovno i molitveno središte, sabirno mesto vernog naroda i mnogobrojnih hodočasnika koji dolaze na manastirsku slavu Silaska Svetog Duha na apostole (Trojice) i svakodnevna bogosluženja, nalazeći u njemu mir, blagodat i duhovno ohrabrenje.",
        'excerpt' => 'Manastir Svete Trojice u zaseoku Bjele Vode kod Ljubovije, duhovni biser Azbukovice i Podrinja, podignut kao zadužbina porodice Ristanović.',
        'description_short' => 'Manastir Svete Trojice u zaseoku Bjele Vode kod Ljubovije, duhovni biser Azbukovice i Podrinja, podignut kao zadužbina porodice Ristanović.',
        'history' => 'Izgradnja manastirskog kompleksa u Bjelim Vodama započeta je 2003. godine na porodičnom imanju Ristanovića. Ktitor Karađorđe Ristanović, preduzetnik poreklom iz ovog kraja, podigao je svetinju u znak zahvalnosti Gospodu i u večni spomen na svoje pobožne roditelje Kostu i Božanu Ristanović. Glavni hram je završen i svečano osveštan u oktobru 2005. godine blagoslovom i činodejstvovanjem blaženopočivšeg episkopa šabačko-valjevskog Lavrentija. Vremenom je kompleks obogaćen novim konacima, visokim zvonikom, kapelama i etno-muzejskim selom „Ognjište“, te je manastir postao prepoznatljiv hodočasnički centar ovog dela Srbije.',
        'architecture' => 'Manastirska crkva Svete Trojice sagrađena je u srpsko-vizantijskom stilu, zidane kamene konstrukcije sa elegantnom kupolom na osmostranom tamburu i skladnim lučnim otvorima. Unutrašnjost hrama ukrašena je raskošnim duboreznim ikonostasom u pozlati i savremenim freskopisom koji prikazuje praznike i likove svetitelja. U neposrednoj blizini hrama uzdiže se monumentalni zvonik, dok prostrani manastirski konaci sa drvenim tremovima i vidikovcem savršeno dočaravaju tradicionalno graditeljstvo Azbukovice. Kompleks je poznat i po izvorima izuzetno čiste planinske vode po kojima je zaseok Bjele Vode i dobio ime.',
        'card_image' => 'images/monasteries/ljubovija.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/ljubovija.jpg',
                'caption' => 'Crkva Svete Trojice u manastiru Bjele Vode kod Ljubovije sa zvonikom i uređenom portom' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/ljubovija_gal_2.jpg',
                'caption' => 'Zapadno pročelje sa drvenim ulaznim tremom i visokim kamenim zvonikom' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/ljubovija_gal_3.jpg',
                'caption' => 'Unutrašnjost hrama sa pozlaćenim polijelejem i drvoreznim ikonostasom' . $src,
                'sort_order' => 3
            ],
        ]
    ],

    // 177: Manastir Radovašnica
    177 => [
        'name' => 'Manastir Radovašnica',
        'card_image' => 'images/monasteries/radovasnica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/radovasnica.jpg',
                'caption' => 'Crkva Pokrova Presvete Bogorodice pod planinom Cer, zadužbina kralja Dragutina' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/radovasnica_gal_1.jpg',
                'caption' => 'Konaci i uređeno manastirsko dvorište u Radovašnici' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/radovasnica_gal_2.jpg',
                'caption' => 'Zimska kapela i kameni zvonik u porti manastira Radovašnica' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/radovasnica_gal_3.jpg',
                'caption' => 'Detalj fasade i porte manastira Radovašnica podno Cera' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 178: Manastir Rožanj
    178 => [
        'name' => 'Manastir Rožanj',
        'card_image' => 'images/monasteries/rozanj.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/rozanj.jpg',
                'caption' => 'Crkva brvnara Svetog Vasilija Ostroškog sa zlatnim kupolama na Sokolskoj planini. *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/rozanj_gal_1.jpg',
                'caption' => 'Panoramski pogled na manastirski kompleks Rožanj sa crkvom i konakom u šumovitom predelu Sokolske planine. *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
        ]
    ],

    // 179: Manastir Rujevac
    179 => [
        'name' => 'Manastir Rujevac',
        'card_image' => 'images/monasteries/rujevac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/rujevac.jpg',
                'caption' => 'Crkva Svete Ognjene Marije (velikomučenice Marine) u manastiru Rujevac' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 180: Manastir Soko Grad
    180 => [
        'name' => 'Manastir Soko Grad',
        'card_image' => 'images/monasteries/soko-grad.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/soko-grad.jpg',
                'caption' => 'Crkva Svetog Nikolaja Srpskog (Velimirovića) podno stena Soko Grada' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/soko-grad_gal_1.jpg',
                'caption' => 'Stena Soko Grada sa pozlaćenim krstom i konakom u podnožju' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/soko-grad_gal_2.jpg',
                'caption' => 'Akumulaciono jezero i kapija na ulazu u manastirski kompleks Soko Grad' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/soko-grad_gal_3.jpg',
                'caption' => 'Dom Svetog vladike Nikolaja i spomen-muzej u kompleksu Soko Grad' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 181: Manastir Strmovo
    181 => [
        'name' => 'Manastir Strmovo',
        'card_image' => 'images/monasteries/strmovo.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/strmovo.jpg',
                'caption' => 'Crkva brvnara Svetog apostola i jevanđeliste Luke u manastiru Strmovo kod Lajkovca' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 182: Manastir Tronoša
    182 => [
        'name' => 'Manastir Tronoša',
        'card_image' => 'images/monasteries/tronosa.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/tronosa.jpg',
                'caption' => 'Crkva Vavedenja Presvete Bogorodice sa visokim baroknim zvonikom, zadužbina kralja Dragutina i kraljice Kataline' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/tronosa_gal_1.jpg',
                'caption' => 'Srednjovekovna kamena fasada i kupola manastirske crkve u Tronoši' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/tronosa_gal_2.jpg',
                'caption' => 'Kamena kapela Svetog Pantelejmona i spomen-česma Devet Jugovića u Tronoši' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/tronosa_gal_3.jpg',
                'caption' => 'Muzejska postavka posvećena Vuku Karadžiću i Tronoškom rodoslovu' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 183: Manastir Čitluk
    183 => [
        'name' => 'Manastir Čitluk',
        'slug' => 'citluk',
        'city' => 'Čitluk, Ljubovija',
        'region' => 'Podrinje',
        'ktitor' => 'Episkop šabačko-valjevski Lavrentije i verni narod Podrinja (obnova 1966; vaspostavljen 2006)',
        'godina_izgradnje' => '1878. / 1966. (vaspostavljen 2006)',
        'lat' => 44.2000,
        'lng' => 19.3500,
        'latitude' => 44.2000,
        'longitude' => 19.3500,
        'description' => "OPŠTI PODACI:\nManastir Čitluk nalazi se u duhovnom okrilju Eparhije šabačke u blizini mesta Ljubovija na području Podrinja i Azbukovice. Predstavlja poštovanu pravoslavnu svetinju posvećenu Svetoj Trojici, koja svedoči o postojanosti vere, pismenosti i duhovnog identiteta srpskog naroda u dolini reke Bukovice.\n\nISTORIJA:\nPrema narodnom predanju i materijalnim ostacima, u Čitluku je još u srednjem veku postojala svetinja koju su Osmanlije razorile. Prva obnovljena crkva od brvana i kamena pominje se 1878. godine. Današnji hram Svete Trojice sagrađen je trudom meštana 1966. godine kao parohijska crkva. Zbog svog skrovitog položaja i duhovne atmosfere, crkva je 2006. godine blagoslovom episkopa Lavrentija pretvorena u ženski manastir, a pored nje je podignut nov konak za sestrinstvo i prihvat vernika.\n\nARHITEKTURA I UMETNOST:\nCrkva Svete Trojice je jednobrodna građevina skromnih dimenzija sa polukružnom oltarskom apsidom i samostojećim zvonikom sa otvorenim arkadama u porti. Građena je od čvrstog materijala, sa belo okrečenim zidovima i jednostavnim lučnim prozorima. U porti se nalazi prelepo uređen ružičnjak, travnjak i konak sagrađen u moravskom stilu sa tremom i drvenim stubovima.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nDanas je Manastir Čitluk aktivno duhovno i molitveno središte, sabirno mesto vernog naroda i hodočasnika koji dolaze na manastirsku slavu i bogosluženja, nalazeći u njemu mir, liturgijsko sabranje i duhovno ohrabrenje.",
        'excerpt' => 'Manastir Svete Trojice u selu Čitluk kod Ljubovije, ženski manastir Eparhije šabačke u dolini reke Bukovice.',
        'description_short' => 'Manastir Svete Trojice u selu Čitluk kod Ljubovije, ženski manastir Eparhije šabačke u dolini reke Bukovice.',
        'history' => 'Prema narodnom predanju i materijalnim ostacima, u Čitluku je još u srednjem veku postojala svetinja koju su Osmanlije razorile. Prva obnovljena crkva od brvana i kamena pominje se 1878. godine. Današnji hram Svete Trojice sagrađen je trudom meštana 1966. godine kao parohijska crkva. Zbog svog skrovitog položaja i duhovne atmosfere, crkva je 2006. godine blagoslovom episkopa Lavrentija pretvorena u ženski manastir, a pored nje je podignut nov konak za sestrinstvo i prihvat vernika.',
        'architecture' => 'Crkva Svete Trojice je jednobrodna građevina skromnih dimenzija sa polukružnom oltarskom apsidom i samostojećim zvonikom sa otvorenim arkadama u porti. Građena je od čvrstog materijala, sa belo okrečenim zidovima i jednostavnim lučnim prozorima. U porti se nalazi prelepo uređen ružičnjak, travnjak i konak sagrađen u moravskom stilu sa tremom i drvenim stubovima.',
        'card_image' => 'images/monasteries/citluk.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/citluk.jpg',
                'caption' => 'Crkva Svete Trojice sa zvonikom i cvetnim parkom u manastiru Čitluk kod Ljubovije' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/citluk_gal_1.jpg',
                'caption' => 'Zvonik sa arkadnim otvorima i cvetnim lejama u manastirskoj porti Čitluka' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/citluk_gal_2.jpg',
                'caption' => 'Pročelje i kameni portal crkve Svete Trojice u manastiru Čitluk' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/citluk_gal_3.jpg',
                'caption' => 'Manastirski konak u tradicionalnom stilu sa cvetnim rondelom i baštom' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 184: Manastir Čokešina
    184 => [
        'name' => 'Manastir Čokešina',
        'slug' => 'cokesina',
        'city' => 'Čokešina, Loznica',
        'region' => 'Mačva',
        'ktitor' => 'Bogdan Čokeša / Miloš Obilić (14. vek; obnovio knez Miloš Obrenović 1823–1837)',
        'godina_izgradnje' => '14. vek (pre Kosovske bitke) / 1823–1837.',
        'lat' => 44.6366,
        'lng' => 19.3958,
        'latitude' => 44.6366,
        'longitude' => 19.3958,
        'description' => "OPŠTI PODACI:\nManastir Čokešina nalazi se u duhovnom okrilju Eparhije šabačke u blizini mesta Loznica na severnim padinama planine Cer na području Mačve i Pocerine. Predstavlja značajnu i poštovanu pravoslavnu svetinju posvećenu Rođenju Presvete Bogorodice, čiji su ktitori vojvoda Bogdan Čokeša i Miloš Obilić, koja vekovima svedoči o postojanosti vere, pismenosti i duhovnog identiteta srpskog naroda.\n\nISTORIJA:\nIzgradnja manastira vezuje se za period neposredno pre Kosovskog boja 1389. godine. Tokom vekova ropstva hram je više puta rušen i obnavljan. U proleće 1804. godine, uoči zauzeća Šapca, podno manastira odigrao se legendarni Boj na Čokešini u kome su braća Damnjan i Gligorije Nedić herojski položili živote sa svojih 300 ustanika, što je Leopold Ranke nazvao „srpskim Termopilima”. Turci su tada manastir spalili do temelja. Obnovu svetinje započeo je knez Miloš Obrenović 1823. godine, a crkva je dovrшена 1837. godine, uz ugradnju spomen-kosturnice herojima sa Čokešine.\n\nARHITEKTURA I UMETNOST:\nDanašnja manastirska crkva Rođenja Presvete Bogorodice predstavlja jednobrodnu monumentalnu građevinu sa vitkim baroknim zvonikom na zapadnoj fasadi. U unutrašnjosti se nalazi raskošan drveni ikonostas iz 1834. godine, rad ikonopisca Mihaila Konstantinovića iz Bitolja. Najveća svetinja manastira je čudotvorna ikona Presvete Bogorodice Čokešinske u srebrnom okovu, kojoj vekovima pritiču vernici tražeći isceljenje i pomoć.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nDanas je Manastir Čokešina aktivno duhovno i molitveno središte, sabirno mesto vernog naroda i hodočasnika koji dolaze na manastirsku slavu i bogosluženja, nalazeći u njemu mir, liturgijsko sabranje i duhovno ohrabrenje.",
        'excerpt' => 'Manastir Čokešina podno planine Cer, čuvena svetinja i poprište „srpskih Termopila” iz Prvog srpskog ustanka 1804. godine.',
        'description_short' => 'Manastir Čokešina podno planine Cer, čuvena svetinja i poprište „srpskih Termopila” iz Prvog srpskog ustanka 1804. godine.',
        'history' => 'Izgradnja manastira vezuje se za period neposredno pre Kosovskog boja 1389. godine. Tokom vekova ropstva hram je više puta rušen i obnavljan. U proleće 1804. godine, uoči zauzeća Šapca, podno manastira odigrao se legendarni Boj na Čokešini u kome su braća Damnjan i Gligorije Nedić herojski položili živote sa svojih 300 ustanika, što je Leopold Ranke nazvao „srpskim Termopilima”. Turci su tada manastir spalili do temelja. Obnovu svetinje započeo je knez Miloš Obrenović 1823. godine, a crkva je dovrшена 1837. godine, uz ugradnju spomen-kosturnice herojima sa Čokešine.',
        'architecture' => 'Današnja manastirska crkva Rođenja Presvete Bogorodice predstavlja jednobrodnu monumentalnu građevinu sa vitkim baroknim zvonikom na zapadnoj fasadi. U unutrašnjosti se nalazi raskošan drveni ikonostas iz 1834. godine, rad ikonopisca Mihaila Konstantinovića iz Bitolja. Najveća svetinja manastira je čudotvorna ikona Presvete Bogorodice Čokešinske u srebrnom okovu, kojoj vekovima pritiču vernici tražeći isceljenje i pomoć.',
        'card_image' => 'images/monasteries/cokesina.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/cokesina.jpg',
                'caption' => 'Pogled kroz kapijski luk na manastirski konak i crkvu Rođenja Presvete Bogorodice u Čokešini' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/cokesina_gal_1.jpg',
                'caption' => 'Kameni cvetni rondel u obliku krsta u lepo uređenoj manastirskoj porti' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/cokesina_gal_2.jpg',
                'caption' => 'Zapadno pročelje crkve sa lučnim portalom, reljefnim rozetama i baroknim zvonikom' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/cokesina_gal_3.jpg',
                'caption' => 'Raskošni rezbareni i pozlaćeni ikonostas hrama, rad Mihaila Konstantinovića iz 1834. godine' . $src,
                'sort_order' => 4
            ],
        ]
    ],
];

echo "2. Ažuriranje primarne baze podataka (database/database.sqlite):\n";
DB::beginTransaction();

try {
    foreach ($sabacka_data as $monasteryId => $data) {
        $monastery = Monastery::find($monasteryId);
        if (!$monastery) {
            echo "  [UPOZORENJE] Manastir ID {$monasteryId} nije pronađen!\n";
            continue;
        }

        $monastery->image_url = $data['card_image'];
        if (isset($data['name'])) $monastery->name = $data['name'];
        if (isset($data['slug'])) $monastery->slug = $data['slug'];
        if (isset($data['city'])) $monastery->city = $data['city'];
        if (isset($data['region'])) $monastery->region = $data['region'];
        if (isset($data['description'])) $monastery->description = $data['description'];
        if (isset($data['excerpt'])) $monastery->excerpt = $data['excerpt'];
        if (isset($data['description_short'])) $monastery->description_short = $data['description_short'];
        if (isset($data['history'])) $monastery->history = $data['history'];
        if (isset($data['architecture'])) $monastery->architecture = $data['architecture'];
        if (isset($data['ktitor'])) $monastery->ktitor = $data['ktitor'];
        if (isset($data['godina_izgradnje'])) $monastery->godina_izgradnje = $data['godina_izgradnje'];
        if (isset($data['lat'])) $monastery->lat = $data['lat'];
        if (isset($data['lng'])) $monastery->lng = $data['lng'];
        if (isset($data['latitude'])) $monastery->latitude = $data['latitude'];
        if (isset($data['longitude'])) $monastery->longitude = $data['longitude'];
        $monastery->save();

        MonasteryImage::where('monastery_id', $monasteryId)->delete();

        foreach ($data['images'] as $imgData) {
            MonasteryImage::create([
                'monastery_id' => $monasteryId,
                'url' => $imgData['url'],
                'caption' => $imgData['caption'],
                'sort_order' => $imgData['sort_order'],
            ]);
        }

        $count = count($data['images']);
        echo "  [AŽURIRAN] [{$monasteryId}] {$monastery->name} | Kartica: {$data['card_image']} | Galerija: {$count} slika\n";
    }

    DB::commit();
    echo "\nPrimarna baza je uspešno ažurirana!\n\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "GREŠKA pri radu sa primarnom bazom: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Sinhronizacija u storage/database.sqlite
$storageDbPath = storage_path('database.sqlite');
if (file_exists($storageDbPath)) {
    echo "3. Ažuriranje storage baze podataka ({$storageDbPath}):\n";
    try {
        $pdo = new PDO('sqlite:' . $storageDbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->beginTransaction();

        foreach ($sabacka_data as $monasteryId => $data) {
            $fields = ['image_url = :img', 'image = :img'];
            $params = [
                'img' => $data['card_image'],
                'id' => $monasteryId
            ];
            
            $optFields = ['name', 'slug', 'city', 'region', 'description', 'excerpt', 'description_short', 'history', 'architecture', 'ktitor', 'godina_izgradnje', 'lat', 'lng', 'latitude', 'longitude'];
            foreach ($optFields as $f) {
                if (isset($data[$f])) {
                    $fields[] = "$f = :$f";
                    $params[$f] = $data[$f];
                }
            }
            
            $sql = "UPDATE monasteries SET " . implode(', ', $fields) . " WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

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

            echo "  [STORAGE AŽURIRAN] [{$monasteryId}]\n";
        }

        $pdo->commit();
        echo "Storage baza je uspešno ažurirana!\n\n";
    } catch (\Exception $e) {
        $pdo->rollBack();
        echo "GREŠKA pri radu sa storage bazom: " . $e->getMessage() . "\n";
    }
}

echo "====================================================================\n";
echo "REVIZIJA I SINHRONIZACIJA EPARHIJE ŠABAČKE ZAVRŠENE USPEŠNO!\n";
echo "====================================================================\n";