<?php

/**
 * SISTEMSKO ČIŠĆENJE I SINHRONIZACIJA - EPARHIJA BEOGRADSKA (ID 3)
 * Pravoslavni Svetionik — Master rad
 * Izvori: commons.wikimedia.org / manastiri.rs
 */

use App\Models\Monastery;
use App\Models\MonasteryImage;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "====================================================================\n";
echo "POKRETANJE REVIZIJE I SINHRONIZACIJE ZA EPARHIJU BEOGRADSKU (ID 3)\n";
echo "====================================================================\n\n";

// Definicija proverenih podataka, usklađenih slika i preciznih opisa u jednom redu
$eparchy_data = [
    // 15: Manastir Mislođin
    15 => [
        'name' => 'Manastir Mislođin',
        'card_image' => 'images/monasteries/mislodjin.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/mislodjin.jpg',
                'caption' => 'Ulazni portal manastirske crkve Svetog Hristofora u Mislođinu sa rezbarenim drvenim vratima i karakterističnim lučnim svodom *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/mislodjin_gal_1.jpg',
                'caption' => 'Zaštićeni arheološki ostaci i temelj srednjovekovne crkve kralja Dragutina vidljivi pod staklom unutar hrama *Izvor: commons.wikimedia.org*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/mislodjin_gal_3.jpg',
                'caption' => 'Konzervirani arheološki ostaci i temelji srednjovekovnog manastira u kripti ispod hrama Svetog Hristofora u Mislođinu *Izvor: commons.wikimedia.org*',
                'sort_order' => 3
            ],
        ]
    ],

    // 16: Manastir Rajinovac
    16 => [
        'name' => 'Manastir Rajinovac',
        'card_image' => 'images/monasteries/rajinovac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/rajinovac.jpg',
                'caption' => 'Crkva Rođenja Presvete Bogorodice sa kamenim zvonikom u manastiru Rajinovac uokvirena rascvetalim ružama u porti *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/rajinovac_gal_1.jpg',
                'caption' => 'Freskopis na zidovima i oko lučnog prozora hrama u Rajinovcu sa likovima svetitelja i svetih arhijereja *Izvor: commons.wikimedia.org*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/rajinovac_gal_2.jpg',
                'caption' => 'Unutrašnjost manastirske crkve u Rajinovcu sa sunčevim zracima, zlatnim polijelejem i oslikanim zidovima *Izvor: commons.wikimedia.org*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/rajinovac_gal_3.jpg',
                'caption' => 'Pogled na crkvu manastira Rajinovac sa zvonikom i konacima u živopisnom zelenilu begaljičkih brda *Izvor: commons.wikimedia.org*',
                'sort_order' => 4
            ],
        ]
    ],

    // 17: Manastir Rakovica
    17 => [
        'name' => 'Manastir Rakovica',
        'card_image' => 'images/monasteries/rakovica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/rakovica.jpg',
                'caption' => 'Ulazni prilaz, zvonik-kapija, konaci i prostrana zelena porta manastira Rakovica u Beogradu *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/rakovica_gal_1.jpg',
                'caption' => 'Raskošna unutrašnjost manastirske crkve u Rakovici sa rezbarenim ikonostasom i freskama na zlatnoj pozadini *Izvor: commons.wikimedia.org*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/rakovica_gal_2.jpg',
                'caption' => 'Mermerni grob prvog patrijarha obnovljene Srpske patrijaršije Dimitrija u porti manastira Rakovica *Izvor: commons.wikimedia.org*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/rakovica_gal_3.jpg',
                'caption' => 'Reljefni zabat spomen-česme sa krunom i kraljevskim grbom dinastije Obrenović u porti manastira Rakovica *Izvor: commons.wikimedia.org*',
                'sort_order' => 4
            ],
        ]
    ],

    // 18: Manastir Senjak
    18 => [
        'name' => 'Manastir Senjak',
        'card_image' => 'images/monasteries/senjak.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/senjak.jpg',
                'caption' => 'Monumentalna bela fasada sa pet kupola hrama Vavedenja Presvete Bogorodice na Senjaku, zadužbine Perse Milenković *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/senjak_gal_1.jpg',
                'caption' => 'Unutrašnjost hrama na Senjaku sa monumentalnim mermernim stubom, vizantijskim kapitelom i oslikanim svodovima *Izvor: commons.wikimedia.org*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/senjak_gal_2.jpg',
                'caption' => 'Mermerni ikonostas sa pozlaćenim carskim dverima i freskama u manastiru Vavedenje na Senjaku *Izvor: commons.wikimedia.org*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/senjak_gal_3.jpg',
                'caption' => 'Zidna freska Svetog Save Srpskog u unutrašnjosti hrama manastira Vavedenje *Izvor: commons.wikimedia.org*',
                'sort_order' => 4
            ],
        ]
    ],

    // 19: Manastir Slanci
    19 => [
        'name' => 'Manastir Slanci',
        'card_image' => 'images/monasteries/slanci.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/slanci.jpg',
                'caption' => 'Manastirska crkva Svetog arhiđakona Stefana u Slancima, metoh manastira Hilandara, sa zvonikom i uređenom portom *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
        ]
    ],

    // 20: Manastir Trojeručica
    20 => [
        'name' => 'Manastir Trojeručica',
        'card_image' => 'images/monasteries/trojerucica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/trojerucica.jpg',
                'caption' => 'Crkva brvnara posvećena Bogorodici Trojeručici u manastiru Trojeručica u Ripnju pod Avalom *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/trojerucica_gal_1.jpg',
                'caption' => 'Čudotvorna ikona Bogorodice Trojeručice, po kojoj je manastir dobio ime, smeštena u unutrašnjosti manastirskog hrama u Ripnju *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
        ]
    ],
];

echo "1. Ažuriranje primarne baze podataka:\n";
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

// 2. Sinhronizacija u storage/database.sqlite
$storageDbPath = storage_path('database.sqlite');
if (file_exists($storageDbPath)) {
    echo "2. Ažuriranje storage baze podataka ({$storageDbPath}):\n";
    try {
        $pdo = new PDO('sqlite:' . $storageDbPath);
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
echo "REVIZIJA I SINHRONIZACIJA ZA EPARHIJU BEOGRADSKU ZAVRŠENE USPEŠNO!\n";
echo "====================================================================\n";
