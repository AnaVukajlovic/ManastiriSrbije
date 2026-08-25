<?php

/**
 * SISTEMSKO ČIŠĆENJE I SINHRONIZACIJA - EPARHIJA KRUŠEVAČKA (ID 9)
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
echo "POKRETANJE REVIZIJE I ČIŠĆENJA ZA EPARHIJU KRUŠEVAČKU (ID 9)\n";
echo "====================================================================\n\n";

$src = '<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>';
$srcWiki = '<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>';

// 1. Definicija verifikovanih podataka, tačnih kartičnih slika i bogatih galerija bez duplikata
$eparchy_data = [
    // 44: Manastir Bošnjane
    44 => [
        'name' => 'Manastir Bošnjane',
        'card_image' => 'images/monasteries/bosnjane.jpg',
        'gallery' => [
            ['url' => 'images/monasteries/bosnjane.jpg', 'caption' => 'Veličanstveni crveni hram Pokrova Presvete Bogorodice sa kupolom, zvonikom i mozaicima u manastiru Bošnjane kod Varvarina' . $src],
            ['url' => 'images/monasteries/bosnjane_gal_1.jpg', 'caption' => 'Bela crkva Svetog apostola i jevanđeliste Luke sa zvonikom i tremom sa arkadama u porti manastira Bošnjane' . $src],
            ['url' => 'images/monasteries/bosnjane_gal_2.jpg', 'caption' => 'Kameno stepenište koje vodi do česme i izvora lekovite vode u manastirskom voćnjaku u Bošnjanu' . $src],
            ['url' => 'images/monasteries/bosnjane_gal_3.jpg', 'caption' => 'Drveni letnjikovac i samostojeći drveno-kameni zvonik u zelenilu manastirske porte u Bošnjanu' . $src],
        ]
    ],

    // 45: Manastir Braljina
    45 => [
        'name' => 'Manastir Braljina',
        'card_image' => 'images/monasteries/braljina.jpg',
        'gallery' => [
            ['url' => 'images/monasteries/braljina.jpg', 'caption' => 'Crkva Svetog Nikole u manastiru Braljina kod Ćićevca i Južne Morave' . $src],
        ]
    ],

    // 46: Manastir Drenova
    46 => [
        'name' => 'Manastir Drenova',
        'card_image' => 'images/monasteries/drenova.jpg',
        'gallery' => [
            ['url' => 'images/monasteries/drenova.jpg', 'caption' => 'Pogled sa severoistočne strane na crkvu Uspenja Presvete Bogorodice sa kupolom, zvonikom i travnatom portom u manastiru Drenova kod Brusa' . $srcWiki],
            ['url' => 'images/monasteries/drenova_gal_2.jpg', 'caption' => 'Polukružna oltarska apsida i skladna kupola crkve Uspenja Presvete Bogorodice u manastiru Drenova pod vedrim nebom' . $srcWiki],
        ]
    ],

    // 47: Manastir Drenča
    47 => [
        'name' => 'Manastir Drenča',
        'card_image' => 'images/monasteries/drenca.jpg',
        'gallery' => [
            ['url' => 'images/monasteries/drenca.jpg', 'caption' => 'Pogled iz vazduha na obnovljenu crkvu Vavedenja Presvete Bogorodice (moravska škola), zvonik i konak u manastiru Drenča kod Aleksandrovca' . $src],
            ['url' => 'images/monasteries/drenca_gal_1.jpg', 'caption' => 'Južna i zapadna kamena fasada obnovljene crkve Vavedenja Presvete Bogorodice sa osmougaonom kupolom i lučnim portalom u manastiru Drenča' . $src],
            ['url' => 'images/monasteries/drenca_gal_2.jpg', 'caption' => 'Oltarska apsida crkve Vavedenja Presvete Bogorodice sa biforama i vizantijskim stilom zidanja u manastiru Drenča' . $src],
        ]
    ],

    // 48: Manastir Grabovo
    48 => [
        'name' => 'Manastir Grabovo',
        'card_image' => 'images/monasteries/grabovo.jpg',
        'gallery' => [
            ['url' => 'images/monasteries/grabovo.jpg', 'caption' => 'Bela crkva Svetih arhangela Mihaila i Gavrila sa kupolom, crvenim krovnim vencem, lučnim portalom i živom ogradom u manastiru Grabovo' . $src],
            ['url' => 'images/monasteries/grabovo_gal_1.jpg', 'caption' => 'Visoki beli zvonik sa piramidalnom kapom pored zidanog kamenog hrama pod skelama u toku obnove u manastiru Grabovo' . $srcWiki],
            ['url' => 'images/monasteries/grabovo_gal_2.jpg', 'caption' => 'Kameno stepenište i potporni zid u prvom planu ispred oltarske apside pod skelama i belog zvonika manastira Grabovo' . $srcWiki],
        ]
    ],

    // 49: Manastir Komorane
    49 => [
        'name' => 'Manastir Komorane',
        'card_image' => 'images/monasteries/komorane.jpg',
        'gallery' => [
            ['url' => 'images/monasteries/komorane.jpg', 'caption' => 'Crkva Svetog Nikole uokvirena krošnjama drveća u manastirskoj porti u Komoranu kod Kruševca' . $src],
            ['url' => 'images/monasteries/komorane_gal_1.jpg', 'caption' => 'Hram Svetog Nikole sa karakterističnim soklom od dekorativne opeke, belom fasadom i kupolom u manastiru Komorane' . $src],
            ['url' => 'images/monasteries/komorane_gal_2.jpg', 'caption' => 'Zapadni portal crkve Svetog Nikole uokviren dekorativnim čempresima i cvećem u manastiru Komorane' . $src],
        ]
    ],

    // 50: Manastir Lešje
    50 => [
        'name' => 'Manastir Lešje',
        'card_image' => 'images/monasteries/lesje.jpg',
        'gallery' => [
            ['url' => 'images/monasteries/lesje.jpg', 'caption' => 'Crkva Pokrova Presvete Bogorodice sa kupolama i ruskom arhitekturom u manastirskom kompleksu Lešje pod planinom Baba' . $src],
            ['url' => 'images/monasteries/lesje_gal_1.jpg', 'caption' => 'Drvena crkvica Svetog Jovana Bogoslova sa zvonikom i bogato rezbarenim portalom u manastiru Lešje' . $src],
            ['url' => 'images/monasteries/lesje_gal_2.jpg', 'caption' => 'Pogled na crkvu Pokrova Presvete Bogorodice, crkvu brvnaru i uređeno dvorište pod snegom u manastiru Lešje' . $src],
            ['url' => 'images/monasteries/lesje_gal_3.jpg', 'caption' => 'Manastirski lekoviti izvor i česma Živonosni Istočnik sa pozlaćenom kupolom u manastiru Lešje' . $src],
        ]
    ],

    // 51: Manastir Ljubostinja
    51 => [
        'name' => 'Manastir Ljubostinja',
        'card_image' => 'images/monasteries/ljubostinja.jpg',
        'gallery' => [
            ['url' => 'images/monasteries/ljubostinja.jpg', 'caption' => 'Drevna crkva Uspenja Presvete Bogorodice u manastiru Ljubostinja — biser moravske arhitekture i zadužbina kneginje Milice' . $src],
            ['url' => 'images/monasteries/ljubostinja_gal_1.jpg', 'caption' => 'Glavni zapadni portal sa freskom Bogorodice sa Hristom i čuvena moravska kamena rozeta na crkvi u Ljubostinji' . $src],
            ['url' => 'images/monasteries/ljubostinja_gal_2.jpg', 'caption' => 'Uređeni manastirski konaci sa cvetnim vrtom i travnjakom u manastiru Ljubostinja' . $src],
            ['url' => 'images/monasteries/ljubostinja_gal_3.jpg', 'caption' => 'Raskošni pozlaćeni ikonostas sa carskim dverima i freskama na stubovima u crkvi manastira Ljubostinja' . $src],
        ]
    ],

    // 52: Manastir Makrešane
    52 => [
        'name' => 'Manastir Makrešane',
        'card_image' => 'images/monasteries/makresane.jpg',
        'gallery' => [
            ['url' => 'images/monasteries/makresane.jpg', 'caption' => 'Maketa budućeg izgleda hrama manastira Makrešane kod Kruševca' . $src],
        ]
    ],

    // 53: Manastir Manastirak
    53 => [
        'name' => 'Manastir Manastirak',
        'card_image' => 'images/monasteries/manastirak-sumadijska.jpg',
        'gallery' => [
            ['url' => 'images/monasteries/manastirak-sumadijska.jpg', 'caption' => 'Kamena crkva Svetog Nikole sa kupolom, konakom i vitkim zvonikom u manastiru Manastirak kod Rekovca' . $src],
        ]
    ],

    // 54: Manastir Mrzenica
    54 => [
        'name' => 'Manastir Mrzenica',
        'card_image' => 'images/monasteries/mrzenica_gal_2.jpg',
        'gallery' => [
            ['url' => 'images/monasteries/mrzenica_gal_2.jpg', 'caption' => 'Crkva Svetog proroka Ilije sa belom fasadom, centralnom kupolom, zvonikom i uređenom portom u manastiru Mrzenica kod Ćićevca' . $srcWiki],
        ]
    ],

    // 55: Manastir Naupare
    55 => [
        'name' => 'Manastir Naupare',
        'card_image' => 'images/monasteries/naupare.jpg',
        'gallery' => [
            ['url' => 'images/monasteries/naupare.jpg', 'caption' => 'Srednjovekovna crkva Rođenja Presvete Bogorodice u manastiru Naupare — remek-delo moravske kamene plastike i rozeta (14. vek)' . $src],
            ['url' => 'images/monasteries/naupare_gal_1.jpg', 'caption' => 'Remek-delo moravske kamene plastike — jedinstvena čipkasta rozeta sa prepletima na fasadi crkve u Nauparu' . $src],
            ['url' => 'images/monasteries/naupare_gal_2.jpg', 'caption' => 'Zapadno pročelje crkve manastira Naupare sa spratnom pripratom i kamenim rozetama u okruženju borove šume' . $src],
            ['url' => 'images/monasteries/naupare_gal_3.jpg', 'caption' => 'Manastirski konak u tradicionalnom stilu na padini u krugu manastira Naupare' . $src],
        ]
    ],

    // 56: Manastir Petina
    56 => [
        'name' => 'Manastir Petina',
        'card_image' => 'images/monasteries/petina.jpg',
        'gallery' => [
            ['url' => 'images/monasteries/petina.jpg', 'caption' => 'Prostrana travnata porta sa novom crkvom Svete Petke i modernim zvonikom u manastiru Petina kod Kruševca' . $src],
            ['url' => 'images/monasteries/petina_gal_2.jpg', 'caption' => 'Crkva Svete Petke sa žutom fasadom, kupolom, zvonikom i cvetnim alejama u porti manastira Petina kod Kruševca' . $src],
        ]
    ],

    // 57: Manastir Pleš
    57 => [
        'name' => 'Manastir Pleš',
        'card_image' => 'images/monasteries/ples.jpg',
        'gallery' => [
            ['url' => 'images/monasteries/ples.jpg', 'caption' => 'Karakteristična crvena crkva Svetih vrača Kozme i Damjana sa kupolom, popločanom stazom i uređenom portom u manastiru Pleš' . $srcWiki],
            ['url' => 'images/monasteries/ples_gal_1.jpg', 'caption' => 'Mozaik Svetih vrača Kozme i Damjana u luneti iznad ulaznog portala i reljefna kamena rozeta na crvenoj fasadi manastira Pleš' . $srcWiki],
            ['url' => 'images/monasteries/ples_gal_2.jpg', 'caption' => 'Unutrašnjost hrama sa raskošnim rezbarenim drvenim ikonostasom, freskama na svodovima i bogatim zlatnim polijelejem u manastiru Pleš' . $srcWiki],
            ['url' => 'images/monasteries/ples_gal_3.jpg', 'caption' => 'Spomen-ploča od tamnocrvenog mermera o obnovi manastirske crkve 1939. godine od strane majstora Rajka V. Kovačevića i meštana Pleša' . $srcWiki],
        ]
    ],

    // 58: Manastir Stevanac
    58 => [
        'name' => 'Manastir Stevanac',
        'card_image' => 'images/monasteries/stevanac.jpg',
        'gallery' => [
            ['url' => 'images/monasteries/stevanac.jpg', 'caption' => 'Srednjovekovna kamena crkva Svetog Stefana u gustoj šumi Stalaćke klisure u manastiru Stevanac' . $src],
        ]
    ],

    // 59: Manastir Strmac
    59 => [
        'name' => 'Manastir Strmac',
        'card_image' => 'images/monasteries/strmac.jpg',
        'gallery' => [
            ['url' => 'images/monasteries/strmac.jpg', 'caption' => 'Drveni mostić sa kapijom, crkva Svetog Joakima i Ane i drveni zvonik u manastiru Strmac kod Brusa' . $src],
            ['url' => 'images/monasteries/strmac_gal_1.jpg', 'caption' => 'Crkva Svetog Joakima i Ane sa prostranim drvenim tremom, ogradom i krovom od ćeramida u manastiru Strmac kod Brusa' . $src],
        ]
    ],

    // 60: Manastir Svojnovo
    60 => [
        'name' => 'Manastir Svojnovo',
        'card_image' => 'images/monasteries/svojnovo.jpg',
        'gallery' => [
            ['url' => 'images/monasteries/svojnovo.jpg', 'caption' => 'Zapadna kamena fasada crkve Svetog Nikole sa lučnim portalom i ikonom patrona u manastiru Svojnovo na padinama Juhora' . $src],
            ['url' => 'images/monasteries/svojnovo_gal_1.jpg', 'caption' => 'Oltarska apsida kamene crkve Svetog Nikole sa krovom od kamenih ploča i zvonikom u manastiru Svojnovo na Juhoru' . $src],
            ['url' => 'images/monasteries/svojnovo_gal_2.jpg', 'caption' => 'Freska Tajne večere u manastirskoj trpezariji sa kristalnim polijelejem u manastiru Svojnovo' . $src],
            ['url' => 'images/monasteries/svojnovo_gal_3.jpg', 'caption' => 'Manastirski lekoviti izvor i kamena česma obrasla mahovinom posvećena Svetom Jovanu Šangajskom u Svojnovu' . $src],
        ]
    ],

    // 61: Manastir Veluće
    61 => [
        'name' => 'Manastir Veluće',
        'card_image' => 'images/monasteries/veluce.jpg',
        'gallery' => [
            ['url' => 'images/monasteries/veluce.jpg', 'caption' => 'Srednjovekovna crkva Vavedenja Presvete Bogorodice sa kupolom, kamenim reljefima, čempresom i zvonikom u manastiru Veluće kod Trstenika (14. vek)' . $src],
            ['url' => 'images/monasteries/veluce_gal_1.jpg', 'caption' => 'Južna i istočna fasada crkve Vavedenja Presvete Bogorodice sa karakterističnim moravskim šahovskim poljima i čempresima u manastiru Veluće' . $src],
            ['url' => 'images/monasteries/veluce_gal_2.jpg', 'caption' => 'Manastirski konaci sa doksatima i cvetni vrt u porti manastira Veluće' . $src],
            ['url' => 'images/monasteries/veluce_gal_3.jpg', 'caption' => 'Raskošno klesana kamena monofora sa moravskim prepletima i floralnim motivima na fasadi crkve u manastiru Veluće' . $src],
        ]
    ],

    // 62: Manastir Žilinci
    62 => [
        'name' => 'Manastir Žilinci',
        'card_image' => 'images/monasteries/zilinci.jpg',
        'gallery' => [
            ['url' => 'images/monasteries/zilinci.jpg', 'caption' => 'Zapadno kameno pročelje crkve Svetog Nikole sa lučnim portalom i ikonom patrona u manastiru Žilinci kod Brusa' . $src],
        ]
    ],

    // 248: Manastir Lepenac
    248 => [
        'name' => 'Manastir Lepenac',
        'card_image' => 'images/monasteries/lepenac.jpg',
        'gallery' => [
            ['url' => 'images/monasteries/lepenac.jpg', 'caption' => 'Pogled iz vazduha na monumentalnu crkvu Svetog Stefana iz 14. veka u manastiru Lepenac u živopisnoj Rasini kod Brusa' . $src],
            ['url' => 'images/monasteries/lepenac_gal_1.jpg', 'caption' => 'Južna i istočna strana monumentalne kamene crkve Svetog Stefana sa visokom kupolom u manastiru Lepenac kod Brusa' . $src],
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

    config(['database.connections.sqlite.database' => $dbPath]);
    DB::purge('sqlite');
    DB::reconnect('sqlite');

    foreach ($eparchy_data as $id => $data) {
        $monastery = Monastery::find($id);
        if (!$monastery) {
            echo "[-] Manastir sa ID {$id} ({$data['name']}) nije pronađen u bazi.\n";
            continue;
        }

        // 1. Ažuriraj naslovnu sliku (karticu)
        $monastery->image_url = $data['card_image'];
        $monastery->save();

        // 2. Obriši stare slike iz galerije
        MonasteryImage::where('monastery_id', $id)->delete();

        // 3. Ubaci nove verifikovane slike sa sort_order
        $order = 1;
        foreach ($data['gallery'] as $img) {
            MonasteryImage::create([
                'monastery_id' => $id,
                'url' => $img['url'],
                'caption' => $img['caption'],
                'sort_order' => $order++,
            ]);
        }

        echo "[+] [ID {$id}] {$data['name']}: Kartica -> {$data['card_image']} | Galerija -> " . count($data['gallery']) . " slika.\n";
    }
}

echo "\n====================================================================\n";
echo "SINHRONIZACIJA USPEŠNO ZAVRŠENA ZA EPARHIJU KRUŠEVAČKU!\n";
echo "====================================================================\n";