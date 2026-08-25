<?php

/**
 * SISTEMSKO ČIŠĆENJE I SINHRONIZACIJA - EPARHIJA MILEŠEVSKA (ID 10)
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
echo "POKRETANJE REVIZIJE I ČIŠĆENJA ZA EPARHIJA MILEŠEVSKA (ID 10)\n";
echo "====================================================================\n\n";

$src = '<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>';
$srcWiki = '<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>';

// 1. Definicija verifikovanih podataka, tačnih kartičnih slika i galerija
$eparchy_data = [
    // 63: Manastir Bistrica
    63 => [
        'name' => 'Manastir Bistrica',
        'card_image' => 'images/monasteries/bistrica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/bistrica.jpg',
                'caption' => 'Crkva Svete Trojice u manastiru Bistrica sa karakterističnim visokim krovom od drvene šindre, zvonikom i pomoćnim zgradama u planinskom okruženju' . $srcWiki,
                'sort_order' => 1
            ],
        ]
    ],

    // 64: Manastir Davidovica
    64 => [
        'name' => 'Manastir Davidovica',
        'card_image' => 'images/monasteries/davidovica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/davidovica.jpg',
                'caption' => 'Crkva Bogojavljenja (1281. god, zadužbina župana Dmitra) sa tri kupole i kamenim fasadama' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/davidovica_gal_1.jpg',
                'caption' => 'Pejzažni pogled na manastirski kompleks uz rečicu sa drvenim mostom i konakom' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/davidovica_gal_2.jpg',
                'caption' => 'Zapadna fasada sa ulaznim portalom i cvetnim vrtom u manastirskoj porti' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/davidovica_gal_3.jpg',
                'caption' => 'Očuvani fragmenti srednjovekovnih fresaka sa floralnim i geometrijskim ornamentima' . $src,
                'sort_order' => 4
            ],
            [
                'url' => 'images/monasteries/davidovica_gal_4.jpg',
                'caption' => 'Originalni mermerni podni mozaik (rozeta / omfalos) u naosu hrama' . $src,
                'sort_order' => 5
            ],
        ]
    ],

    // 65: Manastir Jabuka
    65 => [
        'name' => 'Manastir Jabuka',
        'card_image' => 'images/monasteries/jabuka.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/jabuka.jpg',
                'caption' => 'Crkva Svetog proroka Ilije pod večernjim osvetljenjem na visoravni Jabuka' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/jabuka_gal_1.jpg',
                'caption' => 'Pogled na manastirski hram kroz visoke četinare planinske visoravni' . $src,
                'sort_order' => 2
            ],
        ]
    ],

    // 66: Manastir Janja
    66 => [
        'name' => 'Manastir Janja',
        'card_image' => 'images/monasteries/janja.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/janja.jpg',
                'caption' => 'Crkva Pravednih roditelja Joakima i Ane u selu Rutoši u živopisnom kanjonu reke Uvac' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 67: Manastir Kumanica
    67 => [
        'name' => 'Manastir Kumanica',
        'card_image' => 'images/monasteries/kumanica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/kumanica.jpg',
                'caption' => 'Crkva Svetog arhangela Gavrila sa drvenim zvonikom usečena podno litica u dolini Lima' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/kumanica_gal_1.jpg',
                'caption' => 'Panoramski pogled na manastirski kompleks sa konacima i monumentalnim krstom na steni' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/kumanica_gal_2.jpg',
                'caption' => 'Kamena crkva Svetog arhangela Gavrila sa kupolom i reljefnim krstom na fasadi' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/kumanica_gal_3.jpg',
                'caption' => 'Kameni ogradni zid manastira podno litica kanjona Lima uz prugu Beograd-Bar' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 68: Manastir Mažići
    68 => [
        'name' => 'Manastir Mažići',
        'card_image' => 'images/monasteries/mazici.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/mazici.jpg',
                'caption' => 'Crkva Svetog Georgija u Mažićima (Orahovica) iz 12. veka građena od tesanog kamena' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/mazici_gal_1.jpg',
                'caption' => 'Kamena staza duž manastirskog zida sa pogledom na planinski pejzaž' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/mazici_gal_2.jpg',
                'caption' => 'Srednjovekovna grobnica i arheološki ostaci nekropole manastira Mažići' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/mazici_gal_3.jpg',
                'caption' => 'Pogled kroz lučni kameni portal na ulaz u naos manastirske crkve' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 69: Manastir Mileševa
    69 => [
        'name' => 'Manastir Mileševa',
        'card_image' => 'images/monasteries/mileseva.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/mileseva.jpg',
                'caption' => 'Crkva Vaznesenja Gospodnjeg (1234. god) sa dve kupole, polukružnim apsidama i cvetnim parterom' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/mileseva_gal_1.jpg',
                'caption' => 'Čuvena freska Beli Anđeo (Mironosice na grobu Hristovom) iz 13. veka' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/mileseva_gal_2.jpg',
                'caption' => 'Panorama manastirskog kompleksa Mileševa uz istoimenu reku sa konacima i zvonikom' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/mileseva_gal_3.jpg',
                'caption' => 'Monumentalna manastirska kula-zvonik sa ulaznom kapijom, mozaikom i novim konakom' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 70: Manastir Pribojska Banja
    70 => [
        'name' => 'Manastir Pribojska Banja',
        'card_image' => 'images/monasteries/pribojska-banja.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/pribojska-banja.jpg',
                'caption' => 'Monumentalni hram Svetog Nikole Dabarskog sa dve kupole, konakom i ogradnim zidom' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/pribojska-banja_gal_1.jpg',
                'caption' => 'Južna fasada hrama sa kupolom, apsidom i uređenom zelenom portom' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/pribojska-banja_gal_2.jpg',
                'caption' => 'Zapadna otvorena priprata sa arkadama, lučnim otvorima i arheološkim temeljima' . $src,
                'sort_order' => 3
            ],
        ]
    ],

    // 71: Manastir Pustinja
    71 => [
        'name' => 'Manastir Pustinja',
        'card_image' => 'images/monasteries/pustinja-valjevska.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/pustinja-valjevska.jpg',
                'caption' => 'Crkva Vavedenja Presvete Bogorodice (13. vek) sa kamenim naosom, kubetom i belim zvonikom' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/pustinja_gal_1.jpg',
                'caption' => 'Uređena manastirska porta sa konacima, cvetnim lejama i vekovnim borom' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/pustinja_gal_2.jpg',
                'caption' => 'Zapadna fasada sa belom kulom-zvonikom i lučnim ulaznim portalom' . $src,
                'sort_order' => 3
            ],
        ]
    ],

    // 72: Manastir Seljani
    72 => [
        'name' => 'Manastir Seljani',
        'card_image' => 'images/monasteries/seljani.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/seljani.jpg',
                'caption' => 'Panoramski pogled iz vazduha na manastirski kompleks Seljani sa crkvom, zvonikom i konakom' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 257: Manastir Vodena Poljana
    257 => [
        'name' => 'Manastir Vodena Poljana',
        'card_image' => 'images/monasteries/vodena-poljana.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/vodena-poljana.jpg',
                'caption' => 'Drvena manastirska crkva Svetih vrača Kozme i Damjana sa konakom u četinarskoj šumi na Zlataru' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/vodena-poljana_gal_1.jpg',
                'caption' => 'Crkva brvnara sa visokim drvenim zvonikom i popločanom stazom u porti' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/vodena-poljana_gal_2.jpg',
                'caption' => 'Izvorište i kamena spomen-česma na proplanku Vodena Poljana na Zlataru' . $src,
                'sort_order' => 3
            ],
        ]
    ],
];

$dbFiles = array_unique([
    base_path('database/database.sqlite'),
    storage_path('database.sqlite'),
]);

foreach ($dbFiles as $dbPath) {
    if (!file_exists($dbPath)) {
        echo "Baza na putanji {$dbPath} ne postoji, preskačem.\n";
        continue;
    }

    echo "Ažuriranje SQLite baze podataka ({$dbPath}):\n";
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

            $count = count($data['images']);
            echo "  [AŽURIRAN] [{$monasteryId}] {$data['name']} | Kartica: {$data['card_image']} | Galerija: {$count} slika\n";
        }

        $pdo->commit();
        echo "Baza {$dbPath} je uspešno ažurirana!\n\n";
    } catch (\Exception $e) {
        $pdo->rollBack();
        echo "GREŠKA pri radu sa bazom {$dbPath}: " . $e->getMessage() . "\n";
    }
}

echo "====================================================================\n";
echo "REVIZIJA I SINHRONIZACIJA ZA EPARHIJA MILEŠEVSKA ZAVRŠENE USPEŠNO!\n";
echo "====================================================================\n";

