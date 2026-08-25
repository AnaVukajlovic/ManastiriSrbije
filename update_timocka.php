<?php

/**
 * SISTEMSKO ČIŠĆENJE I SINHRONIZACIJA - EPARHIJA TIMOČKA (ID 12)
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
echo "POKRETANJE REVIZIJE I SINHRONIZACIJE ZA EPARHIJU TIMOČKU (ID 12)\n";
echo "====================================================================\n\n";

// 1. Definicija verifikovanih podataka, tačnih kartičnih slika i usklađenih opisa
$eparchy_data = [
    // 150: Manastir Bukovo
    150 => [
        'name' => 'Manastir Bukovo',
        'card_image' => 'images/monasteries/bukovo.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/bukovo.jpg',
                'caption' => 'Glavna crkva Svetog oca Nikolaja Čudotvorca sa kamenim zvonikom i kolonadom stubova u porti manastira Bukovo kod Negotina *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/bukovo_gal_1.jpg',
                'caption' => 'Zidana manastirska česma sa ikonom Presvete Bogorodice i novim konakom u pozadini u manastiru Bukovo *Izvor: commons.wikimedia.org*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/bukovo_gal_2.jpg',
                'caption' => 'Manastirski konak i vinova loza iznad kamenog potpornog zida u manastirskom kompleksu Bukovo *Izvor: commons.wikimedia.org*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/bukovo_gal_3.jpg',
                'caption' => 'Visoki kameni zvonik manastira Bukovo sa mozaikom Svetog Nikole iznad ulaznog portala *Izvor: commons.wikimedia.org*',
                'sort_order' => 4
            ],
        ]
    ],

    // 151: Manastir Grlište
    151 => [
        'name' => 'Manastir Grlište',
        'card_image' => 'images/monasteries/grliste.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/grliste.jpg',
                'caption' => 'Crkva Svetih apostola Petra i Pavla sa natkrivenim drvenim tremom u porti manastira Grlište kod Zaječara *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/grliste_gal_1.jpg',
                'caption' => 'Pogled na manastirsku crkvu u Grlištu sa kamenim ogradnim zidom u prelepom šumovitom okruženju *Izvor: commons.wikimedia.org*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/grliste_gal_3.jpg',
                'caption' => 'Autentični stari manastirski konak građen u tradicionalnom narodnom stilu od kamena i drveta u manastiru Grlište *Izvor: commons.wikimedia.org*',
                'sort_order' => 3
            ],
        ]
    ],

    // 152: Manastir Jermenčić
    152 => [
        'name' => 'Manastir Jermenčić',
        'card_image' => 'images/monasteries/jermencic.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/jermencic.jpg',
                'caption' => 'Manastirska crkva Svetih arhangela Mihaila i Gavrila sa drvenom zvonarom u porti manastira Jermenčić na planini Ozren *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
        ]
    ],

    // 153: Manastir Koroglaš
    153 => [
        'name' => 'Manastir Koroglaš',
        'card_image' => 'images/monasteries/koroglas.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/koroglas.jpg',
                'caption' => 'Srednjovekovna crkva Vaznesenja Gospodnjeg manastira Koroglaš sa bogatom keramičkom dekoracijom i oltarskom apsidom *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/koroglas_gal_1.jpg',
                'caption' => 'Istočna fasada crkve manastira Koroglaš iz 14. veka u polju kod sela Miloševo blizu Negotina *Izvor: commons.wikimedia.org*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/koroglas_gal_2.jpg',
                'caption' => 'Zapadni ulaz u manastirsku crkvu Koroglaš sa vidljivim lukom nekadašnje priprate i prelomljenim svodom *Izvor: commons.wikimedia.org*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/koroglas_gal_3.jpg',
                'caption' => 'Detalj južnog zida crkve manastira Koroglaš sa lučnim prozorom i ukrasnim zelenim keramičkim rozetama *Izvor: commons.wikimedia.org*',
                'sort_order' => 4
            ],
        ]
    ],

    // 154: Manastir Krepičevac
    154 => [
        'name' => 'Manastir Krepičevac',
        'card_image' => 'images/monasteries/krepicevac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/krepicevac.jpg',
                'caption' => 'Jednobrodna crkva Uspenja Presvete Bogorodice manastira Krepičevac iz 15. veka u šumovitoj klisuri Radovanske reke *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/krepicevac_gal_1.jpg',
                'caption' => 'Zapadna fasada crkve manastira Krepičevac sa ulaznim vratima i očuvanom freskom Uspenja Presvete Bogorodice u luneti *Izvor: commons.wikimedia.org*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/krepicevac_gal_2.jpg',
                'caption' => 'Pogled na manastirsku crkvu i otvoreni zvonik na stubovima u šumovitom ambijentu manastira Krepičevac *Izvor: commons.wikimedia.org*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/krepicevac_gal_3.jpg',
                'caption' => 'Zasebni manastirski zvonik sa zvonom na masivnom kamenom postolju u porti manastira Krepičevac *Izvor: commons.wikimedia.org*',
                'sort_order' => 4
            ],
        ]
    ],

    // 155: Manastir Lapušnja
    155 => [
        'name' => 'Manastir Lapušnja',
        'card_image' => 'images/monasteries/lapusnja.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/lapusnja.jpg',
                'caption' => 'Monumentalni ostaci crkve Svetog Nikole manastira Lapušnja iz 1501. godine sa očuvanom kupolom i oltarom *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/lapusnja_gal_1.jpg',
                'caption' => 'Zapadni deo i ulaz u ruševine manastira Lapušnja podno mistične planine Rtanj kod Boljevca *Izvor: commons.wikimedia.org*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/lapusnja_gal_2.jpg',
                'caption' => 'Očuvani fragmenti freskoslikarstva i vizantijskih ornamenata na unutrašnjim lukovima hrama u Lapušnji *Izvor: commons.wikimedia.org*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/lapusnja_gal_3.jpg',
                'caption' => 'Detalj oštećene srednjovekovne freske svetitelja sa zlatnim oreolom na zidu manastira Lapušnja *Izvor: commons.wikimedia.org*',
                'sort_order' => 4
            ],
        ]
    ],

    // 156: Manastir Lozica
    156 => [
        'name' => 'Manastir Lozica',
        'card_image' => 'images/monasteries/lozica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/lozica.jpg',
                'caption' => 'Crkva Svetog arhangela Gavrila sa drvenom zvonarom na visokom kamenom postolju u prostranoj porti manastira Lozica *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/lozica_gal_1.jpg',
                'caption' => 'Južna fasada manastirske crkve sa bočnim portalom, osmostranim kubetom i stepeništem zvonika *Izvor: commons.wikimedia.org*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/lozica_gal_2.jpg',
                'caption' => 'Zapadni ulazni portal manastira Lozica uokviren borovima sa lučnim vratima i freskom arhangela Gavrila u luneti *Izvor: commons.wikimedia.org*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/lozica_gal_3.jpg',
                'caption' => 'Pogled na manastirsku crkvu u Lozici sa rascvetale livade uokvirene visokim borovima podno Rtnja *Izvor: commons.wikimedia.org*',
                'sort_order' => 4
            ],
        ]
    ],

    // 157: Manastir Manastirica
    157 => [
        'name' => 'Manastir Manastirica',
        'card_image' => 'images/monasteries/manastirica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/manastirica.jpg',
                'caption' => 'Crkva manastira Manastirica posvećena Svetom Nikoli sa prepoznatljivom fasadom i tremom kod Kladova *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
        ]
    ],

    // 158: Manastir Suvodol
    158 => [
        'name' => 'Manastir Suvodol',
        'card_image' => 'images/monasteries/suvodol.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/suvodol.jpg',
                'caption' => 'Kompleks manastira Suvodol sa crkvom Rođenja Presvete Bogorodice, zvonikom i konacima u živopisnom klancu *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/suvodol_gal_1.jpg',
                'caption' => 'Zapadna fasada crkve manastira Suvodol sa visokim zvonikom i manastirskom česmom ispred ulaza *Izvor: commons.wikimedia.org*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/suvodol_gal_2.jpg',
                'caption' => 'Pogled odozdo na vitki zvonik crkve Rođenja Presvete Bogorodice u manastiru Suvodol kod Zaječara *Izvor: commons.wikimedia.org*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/suvodol_gal_3.jpg',
                'caption' => 'Stara kamena česma sa klesanim krstovima i izvorskom lekovitom vodom u manastiru Suvodol *Izvor: commons.wikimedia.org*',
                'sort_order' => 4
            ],
        ]
    ],

    // 159: Manastir Vratna
    159 => [
        'name' => 'Manastir Vratna',
        'card_image' => 'images/monasteries/vratna.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/vratna.jpg',
                'caption' => 'Pogled iz vazduha na manastir Vratna sa crkvom Vaznesenja Gospodnjeg i konacima u kanjonu reke Vratne *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/vratna_gal_1.jpg',
                'caption' => 'Crkva Vaznesenja Gospodnjeg manastira Vratna iz 14. veka sa zvonikom na ulaznom delu *Izvor: manastiri.rs / Snimak: N. Glišić*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/vratna_gal_2.jpg',
                'caption' => 'Manastirski kompleks Vratna sa crkvom, konacima i tremom u skrovitoj dolini reke Vratne *Izvor: manastiri.rs / Snimak: N. Glišić*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/vratna_gal_3.jpg',
                'caption' => 'Oltarska apsida crkve manastira Vratna sa manastirskom česmom i moćnim krečnjačkim liticama u pozadini *Izvor: manastiri.rs / Snimak: N. Glišić*',
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

// 3. Sinhronizacija u storage/database.sqlite
$storageDbPath = storage_path('database.sqlite');
if (file_exists($storageDbPath)) {
    echo "3. Ažuriranje storage baze podataka ({$storageDbPath}):\n";
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
echo "REVIZIJA I SINHRONIZACIJA ZA EPARHIJA TIMOČKA ZAVRŠENE USPEŠNO!\n";
echo "====================================================================\n";
