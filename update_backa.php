<?php

/**
 * SISTEMSKO ČIŠĆENJE I SINHRONIZACIJA - EPARHIJA BAČKA (ID 7)
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
echo "POKRETANJE REVIZIJE I ČIŠĆENJA ZA EPARHIJA BAČKA (ID 7)\n";
echo "====================================================================\n\n";

$src = '<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>';

// 1. Definicija verifikovanih podataka, tačnih kartičnih slika i galerija
$eparchy_data = [
    // 10: Manastir Bođani
    10 => [
        'name' => 'Manastir Bođani',
        'card_image' => 'images/monasteries/bodjani.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/bodjani.jpg',
                'caption' => 'Panoramski pogled na manastirski kompleks Bođani sa baroknim konacima, parkom i crkvom Vavedenja Presvete Bogorodice
<small>*(Izvor: commons.wikimedia.org)*</small>' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/bodjani_gal_1.jpg',
                'caption' => 'Pogled kroz krošnje parka na kupolu i barokni zvonik manastirske crkve u Bođanima
<small>*(Izvor: commons.wikimedia.org)*</small>' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/bodjani_gal_2.jpg',
                'caption' => 'Unutrašnjost naosa sa čuvenim freskama Hristofora Žefarovića i rezbarenim ikonostasom
<small>*(Izvor: commons.wikimedia.org)*</small>' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/bodjani_gal_3.jpg',
                'caption' => 'Oslikana manastirska kapela Svete Petke sa drvorezbarskim ikonostasom i podnim mozaikom
<small>*(Izvor: commons.wikimedia.org)*</small>' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 11: Manastir Kać
    11 => [
        'name' => 'Manastir Kać',
        'card_image' => 'images/monasteries/kac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/kac.jpg',
                'caption' => 'Pogled na manastirsku crkvu sa zvonikom, konake u vizantijskom stilu i uređenu portu manastira Kać
<small>*(Izvor: commons.wikimedia.org)*</small>' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/kac_gal_1.jpg',
                'caption' => 'Zasvođena kapija sa mozaikom Vaskrsenja Hristovog i arhanđelima na ulazu u manastirski kompleks
<small>*(Izvor: commons.wikimedia.org)*</small>' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/kac_gal_2.jpg',
                'caption' => 'Zasvođeni ulazni prolaz sa plavim zvezdanim mozaikom na svodu i pogledom na travnatu portu
<small>*(Izvor: commons.wikimedia.org)*</small>' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/kac_gal_3.jpg',
                'caption' => 'Južna strana manastirskog hrama sa kupolom i konacima u Kaću
<small>*(Izvor: commons.wikimedia.org)*</small>' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 12: Manastir Kovilj
    12 => [
        'name' => 'Manastir Kovilj',
        'card_image' => 'images/monasteries/kovilj.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/kovilj.jpg',
                'caption' => 'Zapadna fasada monumentalne kamene crkve Svetih arhangela Mihaila i Gavrila sa portalom u manastiru Kovilj
<small>*(Izvor: commons.wikimedia.org)*</small>' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/kovilj_gal_1.jpg',
                'caption' => 'Panorama manastirskog kompleksa Kovilj sa kupolama hrama i kulom zvonikom iznad bačkog zelenila
<small>*(Izvor: commons.wikimedia.org)*</small>' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/kovilj_gal_2.jpg',
                'caption' => 'Monumentalni mermerni ikonostas i freskopis unutar crkve manastira Kovilj
<small>*(Izvor: commons.wikimedia.org)*</small>' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/kovilj_gal_3.jpg',
                'caption' => 'Visoki zvonik i detalji kamene plastike u porti manastira Kovilj
<small>*(Izvor: commons.wikimedia.org)*</small>' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 13: Manastir Sombor
    13 => [
        'name' => 'Manastir Sombor',
        'card_image' => 'images/monasteries/sombor.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sombor.jpg',
                'caption' => 'Crkva Svetog arhiđakona Stefana u srpsko-vizantijskom stilu sa kupolama i manastirskim konakom u Somboru
<small>*(Izvor: commons.wikimedia.org)*</small>' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/sombor_gal_1.jpg',
                'caption' => 'Glavni ulaz sa kamenom rozetom, triforom i mozaikom svetitelja na zapadnoj fasadi
<small>*(Izvor: commons.wikimedia.org)*</small>' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/sombor_gal_2.jpg',
                'caption' => 'Pogled na manastirski hram sa južne strane sa zvonikom i prilaznom stazom
<small>*(Izvor: commons.wikimedia.org)*</small>' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/sombor_gal_3.jpg',
                'caption' => 'Mermerna spomen-ploča inženjeru Svetozaru Krotinu, ktitoru manastirske crkve u Somboru
<small>*(Izvor: commons.wikimedia.org)*</small>' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 14: Manastir Vodica
    14 => [
        'name' => 'Manastir Vodica',
        'card_image' => 'images/monasteries/vodica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/vodica.jpg',
                'caption' => 'Širi pogled na pravoslavnu kapelu Vodica sa ogradnim zidom i zelenilom pored puta (1793. god)' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/vodica_gal_1.jpg',
                'caption' => 'Zapadna fasada kapele sa baroknim dekorativnim zabatom i lučnim ulaznim tremom' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/vodica_gal_2.jpg',
                'caption' => 'Unutrašnje dvorište i porta sa stazom pod krošnjama drveća i oltarom sa lekovitim izvorom' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/vodica_gal_3.jpg',
                'caption' => 'Bočna strana kapele sa ovalnim prozorom i tradicionalnim crepnim krovom' . $src,
                'sort_order' => 4
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

// 3. Sinhronizacija u sve SQLite baze
$dbPaths = array_unique([
    database_path('database.sqlite'),
    storage_path('database.sqlite'),
    base_path('database/database.sqlite'),
    base_path('storage/database.sqlite')
]);

foreach ($dbPaths as $dbPath) {
    if (file_exists($dbPath)) {
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
            $pdo->rollBack();
            echo "GREŠKA pri radu sa bazom {$dbPath}: " . $e->getMessage() . "\n";
        }
    }
}

echo "====================================================================\n";
echo "REVIZIJA I SINHRONIZACIJA ZA EPARHIJA BAČKA ZAVRŠENE USPEŠNO!\n";
echo "====================================================================\n";
