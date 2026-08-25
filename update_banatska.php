<?php

/**
 * SISTEMSKO ČIŠĆENJE I SINHRONIZACIJA - EPARHIJA BANATSKA (ID 6)
 * Pravoslavni Svetionik — Master rad
 * Izvori: commons.wikimedia.org / eparhijabanatska.rs / manastiri.rs
 */

use App\Models\Monastery;
use App\Models\MonasteryImage;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "====================================================================\n";
echo "POKRETANJE REVIZIJE I ČIŠĆENJA ZA EPARHIJU BANATSKU (ID 6)\n";
echo "====================================================================\n\n";

// 1. Definicija verifikovanih podataka, tačnih kartičnih slika i galerija
$eparchy_data = [
    // 1: Manastir Bavanište
    1 => [
        'name' => 'Manastir Bavanište',
        'card_image' => 'images/monasteries/bavaniste.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/bavaniste.jpg',
                'caption' => 'Pogled na manastirsku crkvu Rođenja Presvete Bogorodice i portu sa cvećem i zelenilom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/bavaniste_gal_1.jpg',
                'caption' => 'Manastirska crkva sa oltarskom apsidom, kupolom i fontanom u porti<br><small style="color: #eab308;"><em>(Izvor: eparhijabanatska.rs)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/bavaniste_gal_2.jpg',
                'caption' => 'Zidana ulazna kapija manastira sa mozaikom Rođenja Presvete Bogorodice i krstom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/bavaniste_gal_3.jpg',
                'caption' => 'Drveni manastirski zvonik sa zvonima, ogradom i konakom u pozadini<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 2: Manastir Gaj
    2 => [
        'name' => 'Manastir Gaj',
        'card_image' => 'images/monasteries/gaj.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/gaj.jpg',
                'caption' => 'Pogled na manastirsku crkvu Vaznesenja Gospodnjeg sa baroknim zvonikom i tremom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/gaj_gal_1.jpg',
                'caption' => 'Barokni zvonik manastirske crkve sa satom i dekorativnom kapom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/gaj_gal_2.jpg',
                'caption' => 'Pogled na crkvu kroz drvored i kapiju porte<br><small style="color: #eab308;"><em>(Izvor: eparhijabanatska.rs)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/gaj_gal_3.jpg',
                'caption' => 'Mermerni spomen-krst i natpis uzidani u fasadu crkve<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 3: Manastir Hajdučica
    3 => [
        'name' => 'Manastir Hajdučica',
        'card_image' => 'images/monasteries/hajducica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/hajducica.jpg',
                'caption' => 'Pogled na manastirsku crkvu Svetih Arhanđela kroz kovanu ulaznu kapiju<br><small style="color: #eab308;"><em>(Izvor: eparhijabanatska.rs)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/hajducica_gal_1.jpg',
                'caption' => 'Manastirski kompleks sa zidanom ogradom, ulazom i portom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/hajducica_gal_2.jpg',
                'caption' => 'Manastirski konak sa lučnim prozorima i zvonikom<br><small style="color: #eab308;"><em>(Izvor: eparhijabanatska.rs)</em></small>',
                'sort_order' => 3
            ],
        ]
    ],

    // 4: Manastir Mesić
    4 => [
        'name' => 'Manastir Mesić',
        'card_image' => 'images/monasteries/mesic.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/mesic.jpg',
                'caption' => 'Panorama manastirskog kompleksa Mesić sa kamenom crkvom, baroknim zvonikom i konakom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/mesic_gal_1.jpg',
                'caption' => 'Zapadna fasada manastirske crkve sa pripratom, baroknim zvonikom i kamenim naosom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/mesic_gal_2.jpg',
                'caption' => 'Enterijer naosa sa srednjovekovnim freskama na stubovima i drvorezbarskim ikonostasom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/mesic_gal_3.jpg',
                'caption' => 'Manastirski konak sa kamenim stepeništem, cvećem i popločanim dvorištem<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 5: Manastir Središte
    5 => [
        'name' => 'Manastir Središte',
        'card_image' => 'images/monasteries/srediste.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/srediste.jpg',
                'caption' => 'Pogled odozgo na crkvu manastira Središte od opeke u moravsko-vizantijskom stilu<br><small style="color: #eab308;"><em>(Izvor: eparhijabanatska.rs)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/srediste_gal_1.jpg',
                'caption' => 'Monumentalna ulazna kula sa lučnom kapijom manastirskog kompleksa Središte<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/srediste_gal_2.jpg',
                'caption' => 'Višespratni manastirski konak sa paraklisom i kupolom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/srediste_gal_3.jpg',
                'caption' => 'Kupola manastirskog hrama i zaseban zvonik okruženi šumom Vršačkih planina<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 6: Manastir Sveta Trojica Kikinda
    6 => [
        'name' => 'Manastir Sveta Trojica Kikinda',
        'card_image' => 'images/monasteries/sveta_trojica_kikinda.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sveta_trojica_kikinda.jpg',
                'caption' => 'Zapadna žuto-bela fasada crkve Svete Trojice sa baroknim zvonikom, mozaicima anđela u nišama i ćiriličnim natpisima iznad ulaza<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/sveta_trojica_kikinda_gal_1.jpg',
                'caption' => 'Pogled na žutu crkvu Svete Trojice sa polukružnom apsidom i baroknim zvonikom preko starog pravoslavnog groblja u Kikindi<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/sveta_trojica_kikinda_gal_2.jpg',
                'caption' => 'Gornji deo baroknog tornja-zvonika sa bakarnom lukovičastom kapom i krstom pod popodnevnim suncem<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
        ]
    ],

    // 7: Manastir Svete Melanije
    7 => [
        'name' => 'Manastir Svete Melanije',
        'card_image' => 'images/monasteries/svete_melanije.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/svete_melanije.jpg',
                'caption' => 'Crkva Rođenja Presvete Bogorodice manastira Svete Melanije u Zrenjaninu sa belom fasadom, osmougaonom kupolom, lučnim ulaznim tremom i cvećem u porti<br><small style="color: #eab308;"><em>(Izvor: eparhijabanatska.rs)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/svete_melanije_gal_1.jpg',
                'caption' => 'Unutrašnjost hrama sa rezbarenim tamnim drvenim ikonostasom, pozlaćenim carskim dverima, upaljenim polijelejem i ikonom na stolu<br><small style="color: #eab308;"><em>(Izvor: eparhijabanatska.rs)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/svete_melanije_gal_2.jpg',
                'caption' => 'Prizemni beli manastirski konak pod krovom od novog crepa sa uređenim popločanim stazama i cvetnim dvorištem<br><small style="color: #eab308;"><em>(Izvor: eparhijabanatska.rs)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/svete_melanije_gal_3.jpg',
                'caption' => 'Uramljeni portret prve nastojateljice manastira Svete Melanije, igumanije Petronije, sa drvenim krstom u ruci<br><small style="color: #eab308;"><em>(Izvor: eparhijabanatska.rs)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 8: Manastir Vlajkovac
    8 => [
        'name' => 'Manastir Vlajkovac',
        'card_image' => 'images/monasteries/vlajkovac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/vlajkovac.jpg',
                'caption' => 'Pogled na manastirsku crkvu Svetih apostola Petra i Pavla sa zvonikom i oslikanim nišama<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/vlajkovac_gal_1.jpg',
                'caption' => 'Zapadna fasada crkve sa oslikanim nišama, zvonikom i ogradom porte<br><small style="color: #eab308;"><em>(Izvor: eparhijabanatska.rs)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/vlajkovac_gal_2.jpg',
                'caption' => 'Južna bočna strana naosa i krov manastirske crkve<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/vlajkovac_gal_3.jpg',
                'caption' => 'Fasadna freska Svetog velikomučenika Dimitrija na zapadnom zidu crkve<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 4
            ],
        ]
    ],

    // 9: Manastir Vojlovica
    9 => [
        'name' => 'Manastir Vojlovica',
        'card_image' => 'images/monasteries/vojlovica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/vojlovica.jpg',
                'caption' => 'Zapadna fasada crkve manastira Vojlovica sa baroknim zvonikom, portalom i ikonama<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/vojlovica_gal_1.jpg',
                'caption' => 'Raskošni barokni pozlaćeni ikonostas manastirske crkve sa polijelejem i celivajućom ikonom<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/vojlovica_gal_2.jpg',
                'caption' => 'Monumentalni spratni konak manastira Vojlovica sa parkom u porti<br><small style="color: #eab308;"><em>(Izvor: eparhijabanatska.rs)</em></small>',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/vojlovica_gal_3.jpg',
                'caption' => 'Spomen-stub sa krstom i mozaikom Svetog Arhangela Gavrila na prilazu manastiru<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>',
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
        $monastery->image = $data['card_image'];
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
echo "REVIZIJA I SINHRONIZACIJA ZA EPARHIJU BANATSKU ZAVRŠENE USPEŠNO!\n";
echo "====================================================================\n";
