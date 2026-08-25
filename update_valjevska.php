<?php

/**
 * SISTEMSKO ČIŠĆENJE I SINHRONIZACIJA - EPARHIJA VALJEVSKA (ID 13)
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
echo "POKRETANJE REVIZIJE I ČIŠĆENJA ZA EPARHIJA VALJEVSKA (ID 13)\n";
echo "====================================================================\n\n";

$src = '<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>';

// 1. Definicija verifikovanih podataka, tačnih kartičnih slika i galerija
$eparchy_data = [
    // 160: Manastir Bogovađa
    160 => [
        'name' => 'Manastir Bogovađa',
        'card_image' => 'images/monasteries/bogovadja.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/bogovadja.jpg',
                'caption' => 'Crkva Rođenja Svetog Jovana Krstitelja u manastiru Bogovađa sa baroknim zvonikom i cvetnom portom' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/bogovadja_gal_1.jpg',
                'caption' => 'Autentični istorijski Hadži Ruvimov konak sa drvenim spratnim tremom i kamenim podzidom' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/bogovadja_gal_2.jpg',
                'caption' => 'Popločana manastirska porta sa konacima i zvonikom' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/bogovadja_gal_3.jpg',
                'caption' => 'Monumentalna kamena kapija sa kovanom kapijom na ulazu u manastirski kompleks' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 161: Manastir Dokmir
    161 => [
        'name' => 'Manastir Dokmir',
        'card_image' => 'images/monasteries/dokmir.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/dokmir.jpg',
                'caption' => 'Crkva Vavedenja Presvete Bogorodice u manastiru Dokmir sa zidanom kapelom na uzvišenju i manastirskim imanjem' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/dokmir_gal_1.jpg',
                'caption' => 'Širi pogled na manastirski kompleks sa konakom, ogradnim zidom i livadama u pitomoj Tamnavi' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/dokmir_gal_2.jpg',
                'caption' => 'Novi manastirski konak sa spratnom lođom i zvonikom' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/dokmir_gal_3.jpg',
                'caption' => 'Mermerno spomen-obeležje u manastirskoj porti posvećeno dokmirskim monasima i učiteljima' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 162: Manastir Grabovac
    162 => [
        'name' => 'Manastir Grabovac',
        'card_image' => 'images/monasteries/grabovac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/grabovac.jpg',
                'caption' => 'Crkva Svetog oca Nikolaja u Grabovcu sa prepoznatljivom dvobojnom vizantijskom fasadom, rozetama i kupolama' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/grabovac_gal_1.jpg',
                'caption' => 'Pogled na manastirsku kapelu i crkvu Svetog Nikole sa prilaznog puta' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/grabovac_gal_2.jpg',
                'caption' => 'Novi manastirski konak sa prostranim tremom, staklenim arkadama i cvetnim vrtom' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/grabovac_gal_3.jpg',
                'caption' => 'Slobodnostojeći monumentalni zvonik sa ukrasnim triforama i prepoznatljivom fasadom' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 163: Manastir Jovanja
    163 => [
        'name' => 'Manastir Jovanja',
        'card_image' => 'images/monasteries/jovanja.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/jovanja.jpg',
                'caption' => 'Crkva Rođenja Svetog Jovana Krstitelja sa kupolom, krovom od drvene šindre i drvenim zvonikom u dolini Jablanice' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/jovanja_gal_1.jpg',
                'caption' => 'Pogled na južnu fasadu crkve i prostranu zelenu portu podno šumovitih padina' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/jovanja_gal_2.jpg',
                'caption' => 'Manastirski konak sa belim lučnim arkadama, prozorima i negovanim travnjakom sa cvećem' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/jovanja_gal_3.jpg',
                'caption' => 'Raskošni drvorezbareni ikonostas u naosu sa prestolnim ikonama i ostacima drevnog živopisa na kamenim zidovima' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 164: Manastir Lelić
    164 => [
        'name' => 'Manastir Lelić',
        'card_image' => 'images/monasteries/lelic.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/lelic.jpg',
                'caption' => 'Glavna kamena lučna kapija, manastirski zid i crkva Svetog Nikole u Leliću (zadužbina Svetog vladike Nikolaja)' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/lelic_gal_1.jpg',
                'caption' => 'Pogled na hram Svetog Nikole u moravskom stilu, zidanu spomen-kapelu, zvonik i prostranu portu' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/lelic_gal_2.jpg',
                'caption' => 'Spratni manastirski konak i crkveni zvonik opasani masivnim kamenim zidom' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/lelic_gal_3.jpg',
                'caption' => 'Ćivot sa svetim moštima Svetog vladike Nikolaja Žičkog i Ohridskog pod polijelejem u naosu crkve' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 165: Manastir Plužac
    165 => [
        'name' => 'Manastir Plužac',
        'card_image' => 'images/monasteries/pluzac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/pluzac.jpg',
                'caption' => 'Crkva Svetog cara Konstantina i carice Jelene u manastiru Plužac podno planine Vlašić' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/pluzac_gal_1.jpg',
                'caption' => 'Novi manastirski konak građen u tradicionalnom stilu sa drvenim tremom i kamenim temeljom' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/pluzac_gal_2.jpg',
                'caption' => 'Zapadna fasada sa zvonikom, portalom i mozaicima Presvete Bogorodice i Gospoda Isusa Hrista' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/pluzac_gal_3.jpg',
                'caption' => 'Detalj bogato klesane kamene rozete sa prepletom na pročelju zvonika' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 166: Manastir Ribnica
    166 => [
        'name' => 'Manastir Ribnica',
        'card_image' => 'images/monasteries/ribnica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/ribnica.jpg',
                'caption' => 'Zapadna fasada crkve Svetih apostola Petra i Pavla sa stubovima, rozetom i lunetom iznad portala' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/ribnica_gal_1.jpg',
                'caption' => 'Pogled sa puta na slobodnostojeći zvonik, crkvu sa velikom triforom i manastirsku ogradu sa lučnom kapijom' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/ribnica_gal_2.jpg',
                'caption' => 'Manastirska spomen-česma od klesanog kamena sa krstom i cvetnim lejama u porti' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/ribnica_gal_3.jpg',
                'caption' => 'Manastirski konak u senci šumovitih stena sa ulazom u čuvenu Ribničku pećinu u pozadini' . $src,
                'sort_order' => 4
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
echo "REVIZIJA I SINHRONIZACIJA ZA EPARHIJA VALJEVSKA ZAVRŠENE USPEŠNO!\n";
echo "====================================================================\n";
