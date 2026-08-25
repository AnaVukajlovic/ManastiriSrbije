<?php

/**
 * SISTEMSKO ČIŠĆENJE I SINHRONIZACIJA - EPARHIJA BRANIČEVSKA (ID 8)
 * Pravoslavni Svetionik — Master rad
 * Izvor: manastiri.rs / commons.wikimedia.org
 */

use App\Models\Monastery;
use App\Models\MonasteryImage;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "====================================================================\n";
echo "POKRETANJE REVIZIJE I ČIŠĆENJA ZA EPARHIJU BRANIČEVSKU (ID 8)\n";
echo "====================================================================\n\n";

$src = '<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>';

// 1. Definicija verifikovanih podataka, tačnih kartičnih slika i galerija
$eparchy_data = [
    // 21: Manastir Bradača
    21 => [
        'name' => 'Manastir Bradača',
        'card_image' => 'images/monasteries/bradaca.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/bradaca.jpg',
                'caption' => 'Crkva Svetih arhangela Mihaila i Gavrila u manastiru Bradača kod Požarevca (14. vek)' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/bradaca_gal_1.jpg',
                'caption' => 'Zapadna fasada sa ulaznim vratima i zvonikom u zelenilu manastirske porte' . $src,
                'sort_order' => 2
            ],
        ]
    ],

    // 22: Manastir Dobreš
    22 => [
        'name' => 'Manastir Dobreš',
        'card_image' => 'images/monasteries/dobres.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/dobres.jpg',
                'caption' => 'Crkva Svetog oca Nikolaja u manastiru Dobreš sa ulaznim tremom i uređenom portom. *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/dobres_gal_1.jpg',
                'caption' => 'Oltarska apsida i severna kamena fasada manastirske crkve. *Izvor: commons.wikimedia.org*',
                'sort_order' => 2
            ],
        ]
    ],

    // 23: Manastir Gornjak
    23 => [
        'name' => 'Manastir Gornjak',
        'card_image' => 'images/monasteries/gornjak.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/gornjak.jpg',
                'caption' => 'Ulazni trem sa lučnim arkadama, zvonik i kupola hrama Vavedenja Presvete Bogorodice pod strmim liticama Gornjačke klisure. *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/gornjak_gal_1.jpg',
                'caption' => 'Zapadna kamena fasada hrama sa zvonikom i kupolom u podnožju planinskog masiva Ježevca. *Izvor: commons.wikimedia.org*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/gornjak_gal_2.jpg',
                'caption' => 'Kameno stepenište uklesano u liticu koje vodi do pećinske isposnice i kapele Svetog Nikole. *Izvor: commons.wikimedia.org*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/gornjak_gal_3.jpg',
                'caption' => 'Pogled sa visine na manastirski kompleks, hram i konake u klisuri reke Mlave. *Izvor: commons.wikimedia.org*',
                'sort_order' => 4
            ],
        ]
    ],

    // 24: Manastir Izvor
    24 => [
        'name' => 'Manastir Izvor',
        'card_image' => 'images/monasteries/izvor.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/izvor.jpg',
                'caption' => 'Crkva Svete Petke u manastiru Izvor kod Paraćina — kameni hram iz 14. veka' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/izvor_gal_1.jpg',
                'caption' => 'Čudotvorni izvor lekovite vode (Ajazma) Svete Petke u kamenom zdanju podno šume' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/izvor_gal_2.jpg',
                'caption' => 'Popločana manastirska porta sa drvenom ulaznom kapijom i nadstrešnicom' . $src,
                'sort_order' => 3
            ],
        ]
    ],

    // 25: Manastir Koporin
    25 => [
        'name' => 'Manastir Koporin',
        'card_image' => 'images/monasteries/koporin.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/koporin.jpg',
                'caption' => 'Crkva Svetog Stefana sa zidanim zvonikom i cvetnim vrtom u manastiru Koporin (zadužbina despota Stefana Lazarevića, 1402)' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/koporin_gal_1.jpg',
                'caption' => 'Popločana staza u manastirskoj porti sa četinarima i cvetnim lejama' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/koporin_gal_2.jpg',
                'caption' => 'Kamena česma i natkriveni bunar sa kupolom i ikonama' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/koporin_gal_3.jpg',
                'caption' => 'Manastirski konak sa doksatima i cvetnim balkonima' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 26: Manastir Manasija (Resava)
    26 => [
        'name' => 'Manastir Manasija (Resava)',
        'card_image' => 'images/monasteries/manasija.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/manasija.jpg',
                'caption' => 'Veličanstveni manastir Manasija (Resava) — vrhunac moravske arhitekture i zadužbina despota Stefana Lazarevića (1407–1418)' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/manasija_gal_1.jpg',
                'caption' => 'Masivni srednjovekovni bedemi sa 11 utvrđenih kula i Despotovom kulom' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/manasija_gal_2.jpg',
                'caption' => 'Crkva Svete Trojice sa pet kupola i raskošnom kamenom plastikom' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/manasija_gal_3.jpg',
                'caption' => 'Pogled na manastirski kompleks i zidine iz doline reke Resave' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 27: Manastir Miljkovo
    27 => [
        'name' => 'Manastir Miljkovo',
        'card_image' => 'images/monasteries/miljkovo.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/miljkovo.jpg',
                'caption' => 'Panoramski pogled na crkvu Vavedenja Presvete Bogorodice, zvonik i konak pod šumovitim brdom' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/miljkovo_gal_1.jpg',
                'caption' => 'Mermerni spomenik ruskih podvižnika shiarhimandrita Amvrosija i jeroshimonaha Marka' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/miljkovo_gal_2.jpg',
                'caption' => 'Kamena lučna ulazna kapija sa natpisom „Манастир Ваведење“' . $src,
                'sort_order' => 3
            ],
        ]
    ],

    // 28: Manastir Namasija
    28 => [
        'name' => 'Manastir Namasija',
        'card_image' => 'images/monasteries/namasija.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/namasija.jpg',
                'caption' => 'Crkva Svetog Nikole u manastiru Namasija kod Zabrege u kanjonu Crnice' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/namasija_gal_1.jpg',
                'caption' => 'Ostaci i obnovljeni delovi drevnog svetilišta u kanjonu Crnice (Petruška oblast)' . $src,
                'sort_order' => 2
            ],
        ]
    ],

    // 29: Manastir Nimnik
    29 => [
        'name' => 'Manastir Nimnik',
        'card_image' => 'images/monasteries/nimnik.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/nimnik.jpg',
                'caption' => 'Crkva Prenosa moštiju Svetog Nikole u manastiru Nimnik sa rozetom, freskom patrona i kamenom fasadom' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/nimnik_gal_1.jpg',
                'caption' => 'Pogled preko reke na manastirski kompleks Nimnik sa konakom i zelenim brdima' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/nimnik_gal_2.jpg',
                'caption' => 'Antički rimski reljef (spolija) sa prikazom glave uzidan u kameni zid crkve' . $src,
                'sort_order' => 3
            ],
        ]
    ],

    // 30: Manastir Pokajnica
    30 => [
        'name' => 'Manastir Pokajnica',
        'card_image' => 'images/monasteries/pokajnica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/pokajnica.jpg',
                'caption' => 'Crkva brvnara Prenosa moštiju Svetog Nikole iz 1818. godine i drveni zvonik u prostranoj porti' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/pokajnica_gal_1.jpg',
                'caption' => 'Pogled na crkvu i zvonik kroz drvenu kapiju manastirskog kompleksa' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/pokajnica_gal_2.jpg',
                'caption' => 'Drvena rezbarena tabla sa natpisom „Манастир Покајница“' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/pokajnica_gal_3.jpg',
                'caption' => 'Unutrašnjost crkve brvnare sa ikonostasom Konstantina zografa i rezbarenim prestolima' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 31: Manastir Radošin
    31 => [
        'name' => 'Manastir Radošin',
        'card_image' => 'images/monasteries/radosin.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/radosin.jpg',
                'caption' => 'Crkva Pokrova Presvete Bogorodice sa konakom, uređenim cvetnim parterom i fontanom' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/radosin_gal_1.jpg',
                'caption' => 'Unutrašnjost crkve sa rezbarenim ikonostasom, celivajućom ikonom i živopisom' . $src,
                'sort_order' => 2
            ],
        ]
    ],

    // 32: Manastir Ravanica
    32 => [
        'name' => 'Manastir Ravanica',
        'card_image' => 'images/monasteries/ravanica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/ravanica.jpg',
                'caption' => 'Manastir Ravanica — zadužbina Svetog kneza Lazara sa pet kupola i polihromnom fasadom (1375–1377)' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/ravanica_gal_1.jpg',
                'caption' => 'Čuveni srednjovekovni kameni reljef sa motivom krilatih životinja u ornamentisanom medaljonu na fasadi hrama' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/ravanica_gal_2.jpg',
                'caption' => 'Zapadna fasada crkve sa bogato ukrašenim biforama i rozetom' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/ravanica_gal_3.jpg',
                'caption' => 'Pogled na manastirski kompleks sa masivnim kamenim kulama i zidinama kneza Lazara' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 33: Manastir Reškovica
    33 => [
        'name' => 'Manastir Reškovica',
        'card_image' => 'images/monasteries/reskovica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/reskovica.jpg',
                'caption' => 'Crkva Svetog apostola Tome u manastiru Reškovica podno Homoljskih planina' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/reskovica_gal_1.jpg',
                'caption' => 'Pogled na novopodignuti višespratni hram u netaknutoj prirodi' . $src,
                'sort_order' => 2
            ],
        ]
    ],

    // 34: Manastir Rukumija
    34 => [
        'name' => 'Manastir Rukumija',
        'card_image' => 'images/monasteries/rukumija.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/rukumija.jpg',
                'caption' => 'Crkva Svetog Nikole sa otvorenim drvenim tremom, freskama i kamenim zidovima' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/rukumija_gal_1.jpg',
                'caption' => 'Glavna ulazna kapija sa zvonikom i natpisom „Српски православни манастир Рукумија“' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/rukumija_gal_2.jpg',
                'caption' => 'Drveni rezbareni baldahin sa raspećem u cvetnoj manastirskoj porti' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/rukumija_gal_3.jpg',
                'caption' => 'Unutrašnjost hrama sa rezbarenim ikonostasom, bogatim freskopisom i svećnjacima' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 35: Manastir Sestroljin
    35 => [
        'name' => 'Manastir Sestroljin',
        'card_image' => 'images/monasteries/sestroljin.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sestroljin.jpg',
                'caption' => 'Crkva Vaznesenja Gospodnjeg u manastiru Sestroljin kod Požarevca (14. vek)' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/sestroljin_gal_1.jpg',
                'caption' => 'Unutrašnjost manastirskog hrama sa ikonostasom' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/sestroljin_gal_2.jpg',
                'caption' => 'Kapela nad čudotvornim izvorom lekovite vode u cvetnom okruženju' . $src,
                'sort_order' => 3
            ],
        ]
    ],

    // 36: Manastir Sisojevac
    36 => [
        'name' => 'Manastir Sisojevac',
        'card_image' => 'images/monasteries/sisojevac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sisojevac.jpg',
                'caption' => 'Crkva Preobraženja Gospodnjeg u manastiru Sisojevac na izvoru reke Crnice (14. vek)' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/sisojevac_gal_1.jpg',
                'caption' => 'Pogled na moravski hram sa kupolom i odvojeni zvonik' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/sisojevac_gal_2.jpg',
                'caption' => 'Južna fasada crkve sa prepoznatljivim plitkim reljefima i frizom arkadica' . $src,
                'sort_order' => 3
            ],
        ]
    ],

    // 37: Manastir Tomić
    37 => [
        'name' => 'Manastir Tomić',
        'card_image' => 'images/monasteries/tomic.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/tomic.jpg',
                'caption' => 'Crkva Svetog apostola Tome u manastiru Tomić kod Vojske na Velikoj Moravi' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/tomic_gal_1.jpg',
                'caption' => 'Pogled na manastirski kompleks i noviji konak u šumovitom predelu' . $src,
                'sort_order' => 2
            ],
        ]
    ],

    // 38: Manastir Trška Crkva
    38 => [
        'name' => 'Manastir Trška Crkva',
        'card_image' => 'images/monasteries/trska-crkva.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/trska-crkva.jpg',
                'caption' => 'Manastir Trška Crkva (Crkva Rođenja Presvete Bogorodice) kod Žagubice u Homolju (13. vek)' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/trska-crkva_gal_1.jpg',
                'caption' => 'Pogled na arhaičnu kamenu jednobrodnu građevinu sa pripratom' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/trska-crkva_gal_2.jpg',
                'caption' => 'Čuveni ranosrednjovekovni reljef dvoglavog orla i krilatih grifona na zapadnom portalu' . $src,
                'sort_order' => 3
            ],
        ]
    ],

    // 39: Manastir Tumane
    39 => [
        'name' => 'Manastir Tumane',
        'card_image' => 'images/monasteries/tumane.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/tumane.jpg',
                'caption' => 'Manastir Tumane kod Golupca — čuveno svetilište Svetog Zosima Sinajita i Svetog Jakova Novog' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/tumane_gal_1.jpg',
                'caption' => 'Crkva Svetog arhangela Gavrila u srpsko-vizantijskom stilu sa skladnim kupolama' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/tumane_gal_2.jpg',
                'caption' => 'Pogled na hram i uređeni manastirski trg u dolini Tumanske reke' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/tumane_gal_3.jpg',
                'caption' => 'Veliki manastirski konak i gostoprimnica za mnogobrojne hodočasnike' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 40: Manastir Zaova
    40 => [
        'name' => 'Manastir Zaova',
        'card_image' => 'images/monasteries/zaova.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/zaova.jpg',
                'caption' => 'Pogled na crkvu Vaznesenja Gospodnjeg i visoki zvonik obrastao bršljanom u manastirskoj porti (14. vek)' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/zaova_gal_1.jpg',
                'caption' => 'Kamena oltarska apsida sa ukrasnim slepim arkadama i ružama' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/zaova_gal_2.jpg',
                'caption' => 'Stari oltarski freskopis i unutrašnjost crkve manastira Zaova' . $src,
                'sort_order' => 3
            ],
        ]
    ],

    // 41: Manastir Zlatenac
    41 => [
        'name' => 'Manastir Zlatenac',
        'card_image' => 'images/monasteries/zlatenac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/zlatenac.jpg',
                'caption' => 'Crkva Svetih Vrača Kozme i Damjana u manastiru Zlatenac pod plavim nebom' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/zlatenac_gal_1.jpg',
                'caption' => 'Stepenište kroz šumski ambijent koje vodi ka manastiru Zlatenac' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/zlatenac_gal_2.jpg',
                'caption' => 'Lučna manastirska kapija sa krstom i mozaikom Svete Petke' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/zlatenac_gal_3.jpg',
                'caption' => 'Crkva manastira Zlatenac sa belom fasadom, kupolom i cvetnim saksijama' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 42: Manastir Đerinac
    42 => [
        'name' => 'Manastir Đerinac',
        'card_image' => 'images/monasteries/djerinac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/djerinac.jpg',
                'caption' => 'Crkva Svetih Konstantina i Jelene u manastiru Đerinac kod Velike Plane' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 43: Manastir Ždrelo
    43 => [
        'name' => 'Manastir Ždrelo',
        'card_image' => 'images/monasteries/zdrelo.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/zdrelo.jpg',
                'caption' => 'Crkva Svete Trojice u manastiru Ždrelo u podnožju Homoljskih planina' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/zdrelo_gal_1.jpg',
                'caption' => 'Pogled na hram sa zvonikom i lepo uređenim planinskim konakom' . $src,
                'sort_order' => 2
            ],
        ]
    ],
];

echo "2. Ažuriranje primarne baze podataka (database/database.sqlite):\n";
DB::beginTransaction();

try {
    foreach ($eparchy_data as $monasteryId => $data) {
        $monastery = Monastery::find($monasteryId);
        if (!$monastery) {
            echo "  [UPOZORENJE] Manastir ID {$monasteryId} nije pronađen!\n";
            continue;
        }

        if (isset($data['name'])) {
            $monastery->name = $data['name'];
        }

        $monastery->image_url = $data['card_image'];
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

// 3. Sinhronizacija u sve SQLite baze (storage/database.sqlite i database/database.sqlite)
$dbPaths = array_unique([
    database_path('database.sqlite'),
    storage_path('database.sqlite')
]);

foreach ($dbPaths as $dbPath) {
    if (!file_exists($dbPath)) continue;
    echo "3. Ažuriranje baze podataka ({$dbPath}):\n";
    try {
        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->beginTransaction();

        foreach ($eparchy_data as $monasteryId => $data) {
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
        }

        $pdo->commit();
        echo "  Baza {$dbPath} je uspešno ažurirana!\n";
    } catch (\Exception $e) {
        if (isset($pdo) && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        echo "GREŠKA pri radu sa bazom {$dbPath}: " . $e->getMessage() . "\n";
    }
}

echo "\n====================================================================\n";
echo "REVIZIJA I SINHRONIZACIJA ZA EPARHIJU BRANIČEVSKU ZAVRŠENE USPEŠNO!\n";
echo "====================================================================\n";
