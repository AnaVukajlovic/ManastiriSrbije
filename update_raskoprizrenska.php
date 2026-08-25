<?php

/**
 * SISTEMSKO ČIŠĆENJE I SINHRONIZACIJA - EPARHIJA RAŠKO-PRIZRENSKA (ID 2)
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
echo "POKRETANJE REVIZIJE I ČIŠĆENJA ZA EPARHIJU RAŠKO-PRIZRENSKU (ID 2)\n";
echo "====================================================================\n\n";

$src = '<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>';

// 1. Fizičko brisanje nedozvoljenih i neautentičnih slika sa diska (ukoliko već nisu obrisane)
$violatingFiles = [
    __DIR__ . '/public/images/monasteries/visoki-decani_gal_2.jpg', // Vojnici sa puškama
    __DIR__ . '/public/images/monasteries/zociste_gal_1.jpg',       // Dve žene ispred ruševina
    __DIR__ . '/public/images/monasteries/djakovica_gal_1.jpg',     // Katolička crkva u Đakovici
    __DIR__ . '/public/images/monasteries/djakovica_gal_2.jpg',     // Moderna ciglana zgrada u Đakovici
    __DIR__ . '/public/images/monasteries/duboki-potok_gal_1.jpg',  // Mutni portret nepoznatog čoveka
    __DIR__ . '/public/images/monasteries/djurdjevi - stupovi.jpg', // Korumpiran fajl sa razmacima
];

echo "1. Brisanje neautentičnih fajlova sa diska:\n";
foreach ($violatingFiles as $file) {
    if (file_exists($file)) {
        unlink($file);
        echo "  [OBRISANO SA DISKA] " . basename($file) . "\n";
    } else {
        echo "  [VEĆ OBRISANO / NE POSTOJI] " . basename($file) . "\n";
    }
}
echo "\n";

// 2. Definicija verifikovanih podataka, tačnih kartičnih slika i čistih galerija (100% usklađeno sa diskom)
$rasko_data = [
    // 112: Manastir Banjska
    112 => [
        'name' => 'Manastir Banjska',
        'card_image' => 'images/monasteries/banjska.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/banjska.jpg',
                'caption' => 'Crkva Svetog arhiđakona Stefana u manastiru Banjska sa prepoznatljivom trobojnom kamenom fasadom, slavna zadužbina kralja Milutina' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 113: Manastir Budisavci
    113 => [
        'name' => 'Manastir Budisavci',
        'card_image' => 'images/monasteries/budisavci.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/budisavci.jpg',
                'caption' => 'Crkva Preobraženja Gospodnjeg sa vitkom kupolom i kamenom fasadom u manastiru Budisavci kod Kline' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 114: Manastir Ceranjska Reka
    114 => [
        'name' => 'Manastir Ceranjska Reka',
        'card_image' => 'images/monasteries/ceranjska-reka.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/ceranjska-reka.jpg',
                'caption' => 'Crkva Svete Petke sa kupolom i belom fasadom u manastiru Ceranjska Reka kod Leposavića podno Kopaonika' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 115: Manastir Crna Reka
    115 => [
        'name' => 'Manastir Crna Reka',
        'card_image' => 'images/monasteries/crna-reka.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/crna-reka.jpg',
                'caption' => 'Pećinski manastir Crna Reka u strmoj litici klisure sa pećinskom crkvom Svetih arhangela i drvenim mostom' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 116: Manastir Devine Vode
    116 => [
        'name' => 'Manastir Devine Vode',
        'card_image' => 'images/monasteries/devine-vode.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/devine-vode.jpg',
                'caption' => 'Crkva Bogorodice Trojeručice u raškom stilu sa česmom lekovite vode u manastiru Devine Vode kod Zvečana' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 117: Manastir Devič
    117 => [
        'name' => 'Manastir Devič',
        'card_image' => 'images/monasteries/devic.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/devic.jpg',
                'caption' => 'Manastirska crkva Vavedenja Presvete Bogorodice sa konacima manastira Devič u šumama Drenice, zadužbina despota Đurđa Brankovića' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 118: Manastir Draganac
    118 => [
        'name' => 'Manastir Draganac',
        'card_image' => 'images/monasteries/draganac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/draganac.jpg',
                'caption' => 'Crkva Svetog arhangela Gavrila u manastiru Draganac okružena gustim šumama Kosovskog Pomoravlja kod Gnjilana' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 119: Manastir Pećka Patrijaršija
    119 => [
        'name' => 'Manastir Pećka Patrijaršija',
        'card_image' => 'images/monasteries/pecka-patrijarsija.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/pecka-patrijarsija.jpg',
                'caption' => 'Kompleks hramova Pećke patrijaršije sa crvenim fasadama podno Prokletija na ulazu u Rugovsku klisuru, vekovno sedište srpskih patrijaraha' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 120: Manastir Prizren (Sveti Arhangeli)
    120 => [
        'name' => 'Manastir Prizren (Sveti Arhangeli)',
        'card_image' => 'images/monasteries/prizren.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/prizren.jpg',
                'caption' => 'Ostaci carske lavre Svetih arhangela cara Dušana sa obnovljenim konakom u kanjonu Prizrenske Bistrice' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 121: Manastir Sokolica
    121 => [
        'name' => 'Manastir Sokolica',
        'card_image' => 'images/monasteries/sokolica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sokolica.jpg',
                'caption' => 'Crkva Pokrova Presvete Bogorodice na litici brda Sokolica kod Zvečana sa pogledom na dolinu Ibra' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 122: Manastir Sopoćani
    122 => [
        'name' => 'Manastir Sopoćani',
        'card_image' => 'images/monasteries/sopocani.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sopocani.jpg',
                'caption' => 'Crkva Svete Trojice u manastiru Sopoćani, zadužbina kralja Stefana Uroša I u Starom Rasu pod zaštitom UNESCO-a' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 123: Manastir Sočanica
    123 => [
        'name' => 'Manastir Sočanica',
        'card_image' => 'images/monasteries/socanica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/socanica.jpg',
                'caption' => 'Crkva Svetog velikomučenika Dimitrija sa drvenim zvonikom u manastiru Sočanica kod Leposavića' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 124: Manastir Tamnica
    124 => [
        'name' => 'Manastir Tamnica',
        'card_image' => 'images/monasteries/tamnica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/tamnica.jpg',
                'caption' => 'Crkva Svete Petke u manastiru Tamnica kod Kosovske Kamenice sa arheološkim ostacima srednjovekovnih zidina' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 125: Manastir Ulije
    125 => [
        'name' => 'Manastir Ulije',
        'card_image' => 'images/monasteries/ulije.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/ulije.jpg',
                'caption' => 'Kamena crkva Svete Petke sa oltarskom apsidom na uzvišenju u selu Ulije kod Leposavića' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 126: Manastir Velika Hoča (Sveti Jovan)
    126 => [
        'name' => 'Manastir Velika Hoča (Sveti Jovan)',
        'card_image' => 'images/monasteries/velika-hoca.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/velika-hoca.jpg',
                'caption' => 'Crkva Svetog Jovana Krstitelja sa kamenom fasadom i preslicom na brdu iznad Velike Hoče kod Orahovca' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 127: Manastir Visoki Dečani
    127 => [
        'name' => 'Manastir Visoki Dečani',
        'card_image' => 'images/monasteries/visoki-decani.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/visoki-decani.jpg',
                'caption' => 'Monumentalna mermerna crkva Hrista Pantokratora manastira Visoki Dečani, zadužbina Svetog Stefana Dečanskog pod zaštitom UNESCO-a' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 128: Manastir Zočište
    128 => [
        'name' => 'Manastir Zočište',
        'card_image' => 'images/monasteries/zociste.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/zociste.jpg',
                'caption' => 'Obnovljena kamena crkva Svetih besrebrnika Kozme i Damjana u manastiru Zočište kod Orahovca' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 129: Manastir Đakovica
    129 => [
        'name' => 'Manastir Đakovica',
        'card_image' => 'images/monasteries/djakovica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/djakovica.jpg',
                'caption' => 'Obnovljeni hram Uspenja Presvete Bogorodice i konak manastira u Đakovici' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 130: Manastir Đurđevi Stupovi
    130 => [
        'name' => 'Manastir Đurđevi Stupovi',
        'card_image' => 'images/monasteries/djurdjevi-stupovi.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/djurdjevi-stupovi.jpg',
                'caption' => 'Crkva Svetog Đorđa sa monumentalnim kulama (stupovima) u manastiru Đurđevi Stupovi u Starom Rasu, prva zadužbina Stefana Nemanje' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 241: Manastir Duboki Potok
    241 => [
        'name' => 'Manastir Duboki Potok',
        'card_image' => 'images/monasteries/duboki-potok.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/duboki-potok.jpg',
                'caption' => 'Crkva Vavedenja Presvete Bogorodice sa kamenim zvonikom u manastiru Duboki Potok u Ibarskom Kolašinu kod Zubinog Potoka' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 242: Manastir Gorioč
    242 => [
        'name' => 'Manastir Gorioč',
        'card_image' => 'images/monasteries/gorioc.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/gorioc.jpg',
                'caption' => 'Crkva Svetog Nikole u manastiru Gorioč kod Istoka, metoh manastira Visoki Dečani na padinama Mokre Gore' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 243: Manastir Gračanica
    243 => [
        'name' => 'Manastir Gračanica',
        'card_image' => 'images/monasteries/gracanica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/gracanica.jpg',
                'caption' => 'Crkva Uspenja Presvete Bogorodice manastira Gračanica, remek-delo srpsko-vizantijske arhitekture kralja Milutina pod zaštitom UNESCO-a' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 245: Manastir Končul
    245 => [
        'name' => 'Manastir Končul',
        'card_image' => 'images/monasteries/koncul.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/koncul.jpg',
                'caption' => 'Crkva Svetog Nikole (Nikoljača) u manastiru Končul na levoj obali Ibra kod Raške, zadužbina Stefana Nemanje' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 256: Manastir Vračevo
    256 => [
        'name' => 'Manastir Vračevo',
        'card_image' => 'images/monasteries/vracevo.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/vracevo.jpg',
                'caption' => 'Kompleks manastira Vračevo sa ulaznom kapijom, konakom i hramom Svetih vrača Kozme i Damjana kod Leposavića' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 258: Manastir Tušimlja
    258 => [
        'name' => 'Manastir Tušimlja',
        'card_image' => 'images/monasteries/tusimlja.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/tusimlja.jpg',
                'caption' => 'Crkva Uspenja Presvete Bogorodice u manastiru Tušimlja kod Raške na obroncima planine Golije' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 260: Bogorodica Ljeviška
    260 => [
        'name' => 'Bogorodica Ljeviška',
        'card_image' => 'images/monasteries/bogorodica-ljeviska.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/bogorodica-ljeviska.jpg',
                'caption' => 'Katedralna crkva Bogorodice Ljeviške u Prizrenu, sedište prizrenskih episkopa i slavna zadužbina kralja Milutina pod zaštitom UNESCO-a' . $src,
                'sort_order' => 1
            ],
        ]
    ],
];

echo "2. Ažuriranje primarne baze podataka (database/database.sqlite):\n";
DB::beginTransaction();

try {
    foreach ($rasko_data as $monasteryId => $data) {
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

        echo "  [AŽURIRAN] [{$monasteryId}] {$monastery->name} | Kartica: {$monastery->image_url} | Galerija: " . count($data['images']) . " slika\n";
    }

    DB::commit();
    echo "\nPrimarna baza je uspešno ažurirana!\n\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Greška pri radu sa primarnom bazom: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Sinhronizacija storage baze podataka
$storageDbPath = __DIR__ . '/storage/database.sqlite';
if (file_exists($storageDbPath)) {
    echo "3. Ažuriranje storage baze podataka ($storageDbPath):\n";
    $pdo = new PDO("sqlite:$storageDbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    foreach ($rasko_data as $monasteryId => $data) {
        $stmt = $pdo->prepare("UPDATE monasteries SET name = :name, image_url = :img WHERE id = :id");
        $stmt->execute([
            'name' => $data['name'] ?? $monastery->name,
            'img' => $data['card_image'],
            'id' => $monasteryId
        ]);

        $stmtDel = $pdo->prepare("DELETE FROM monastery_images WHERE monastery_id = :id");
        $stmtDel->execute(['id' => $monasteryId]);

        $stmtIns = $pdo->prepare("INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (:monastery_id, :url, :caption, :sort_order, datetime('now'), datetime('now'))");
        foreach ($data['images'] as $imgData) {
            $stmtIns->execute([
                'monastery_id' => $monasteryId,
                'url' => $imgData['url'],
                'caption' => $imgData['caption'],
                'sort_order' => $imgData['sort_order'],
            ]);
        }
        echo "  [STORAGE AŽURIRAN] [{$monasteryId}]\n";
    }

    $pdo->commit();
    echo "Storage baza je uspešno ažurirana!\n\n";
}

echo "====================================================================\n";
echo "REVIZIJA I SINHRONIZACIJA EPARHIJE RAŠKO-PRIZRENSKE ZAVRŠENE USPEŠNO!\n";
echo "====================================================================\n";