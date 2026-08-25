<?php

/**
 * PRECIZNO USKLAĐIVANJE SLIKA I OPISA ZA EPARHIJU VRANJSKU (ID 14)
 * Pravoslavni Svetionik — Master rad
 */

use App\Models\Monastery;
use App\Models\MonasteryImage;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "====================================================================\n";
echo "POKRETANJE PRECIZNE REVIZIJE SLIKA I OPISA ZA EPARHIJU VRANJSKU\n";
echo "====================================================================\n\n";

$src = '<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>';

$vranjska_updates = [
    // 167: Manastir Bresnica
    167 => [
        'name' => 'Manastir Bresnica',
        'card_image' => 'images/monasteries/bresnica.jpg',
        'images' => [
            ['url' => 'images/monasteries/bresnica.jpg', 'caption' => 'Crkva Svete Petke u manastiru Bresnica sa prepoznatljivim trolučnim tremom oslikanim freskama' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/bresnica_gal_1.jpg', 'caption' => 'Pogled na manastirski kompleks i zvonik u mirnom planinskom ambijentu kod Bosilegrada' . $src, 'sort_order' => 2],
        ]
    ],

    // 240: Manastir Dubnica
    240 => [
        'name' => 'Manastir Dubnica',
        'card_image' => 'images/monasteries/dubnica-milesevska.jpg',
        'images' => [
            ['url' => 'images/monasteries/dubnica-milesevska.jpg', 'caption' => 'Crkva Svetih apostola Petra i Pavla sa kupolom od tesanog kamena u manastiru Dubnica' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/dubnica-milesevska_gal_1.jpg', 'caption' => 'Spratni konaci manastira Dubnica u tradicionalnom moravskom stilu sa drvenim tremovima' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/dubnica-milesevska_gal_2.jpg', 'caption' => 'Pogled na kameni manastirski hram i lepo uređenu portu kroz ulaznu kapiju' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/dubnica-milesevska_gal_3.jpg', 'caption' => 'Zapadni portal crkve sa detaljima kamene plastike i rezbarenim drvenim vratima' . $src, 'sort_order' => 4],
        ]
    ],

    // 168: Manastir Kacapun
    168 => [
        'name' => 'Manastir Kacapun',
        'card_image' => 'images/monasteries/kacapun.jpg',
        'images' => [
            ['url' => 'images/monasteries/kacapun.jpg', 'caption' => 'Crkva Svetog proroka Ilije u manastiru Kacapun, kamena građevina iz 13. veka u šumskoj uvali' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/kacapun_gal_1.jpg', 'caption' => 'Zapadna fasada sa drvenim tremom i masivnim klesanim kamenim zidovima hrama' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/kacapun_gal_2.jpg', 'caption' => 'Manastirska porta okružena stoletnim gustim šumama i liticama Kacapunske klisure' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/kacapun_gal_3.jpg', 'caption' => 'Drveni manastirski zvonik i staza koja vodi ka svetinji Svetog Ilije' . $src, 'sort_order' => 4],
        ]
    ],

    // 246: Manastir Kozji Dol
    246 => [
        'name' => 'Manastir Kozji Dol',
        'card_image' => 'images/monasteries/kozji-dol.jpg',
        'images' => [
            ['url' => 'images/monasteries/kozji-dol.jpg', 'caption' => 'Crkva Svetog Preobraženja Gospodnjeg u manastiru Kozji Dol u živopisnom kanjonu kod Trgovišta' . $src, 'sort_order' => 1],
        ]
    ],

    // 247: Manastir Lepčince
    247 => [
        'name' => 'Manastir Lepčince',
        'card_image' => 'images/monasteries/lepcince.jpg',
        'images' => [
            ['url' => 'images/monasteries/lepcince.jpg', 'caption' => 'Crkva Svetog velikomučenika Pantelejmona u manastiru Lepčince iz 14. veka' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/lepcince_gal_1.jpg', 'caption' => 'Prostrani manastirski konaci sa cvetnom portom, tremovima i zvonikom' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/lepcince_gal_2.jpg', 'caption' => 'Pogled na manastirski hram podno šumovitih obronaka planine Kozjak' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/lepcince_gal_3.jpg', 'caption' => 'Čudotvorni izvor lekovite vode i manastirski posed u Lepčincu' . $src, 'sort_order' => 4],
        ]
    ],

    // 169: Manastir Lopardince
    169 => [
        'name' => 'Manastir Lopardince',
        'card_image' => 'images/monasteries/lopardince.jpg',
        'images' => [
            ['url' => 'images/monasteries/lopardince.jpg', 'caption' => 'Manastir Svetog arhangela Gavrila u Lopardincu kod Bujanovca tokom liturgijskog sabranja' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/lopardince_gal_1.jpg', 'caption' => 'Unutrašnjost crkve sa oltarom, ikonostasom i liturgijskim svećnjacima' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/lopardince_gal_2.jpg', 'caption' => 'Panoramski pogled na hram Svetog arhangela Gavrila sa zvonikom i zelenom portom' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/lopardince_gal_3.jpg', 'caption' => 'Uređeni manastirski konaci i saborno mesto vernika o prazniku Svetog arhangela Gavrila' . $src, 'sort_order' => 4],
        ]
    ],

    // 251: Manastir Mrtvica
    251 => [
        'name' => 'Manastir Mrtvica',
        'card_image' => 'images/monasteries/mrtvica.jpg',
        'images' => [
            ['url' => 'images/monasteries/mrtvica.jpg', 'caption' => 'Crkva Uspenja Presvete Bogorodice u manastiru Mrtvica na strmoj litici iznad Južne Morave' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/mrtvica_gal_1.jpg', 'caption' => 'Pogled na manastirski hram sa južne strane kroz bujno zelenilo Grdeličke klisure' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/mrtvica_gal_2.jpg', 'caption' => 'Zapadna fasada sa pripratom i kamenim zvonikom u porti manastira Mrtvica' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/mrtvica_gal_3.jpg', 'caption' => 'Unutrašnjost crkve sa freskama iz 16. veka i autentičnim ikonostasom' . $src, 'sort_order' => 4],
        ]
    ],

    // 252: Manastir Palja
    252 => [
        'name' => 'Manastir Palja',
        'card_image' => 'images/monasteries/palja.jpg',
        'images' => [
            ['url' => 'images/monasteries/palja.jpg', 'caption' => 'Drevna crkva Vavedenja Presvete Bogorodice u manastiru Palja iz 13. veka' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/palja_gal_1.jpg', 'caption' => 'Zapadno pročelje hrama sa kamenom fasadom, pokrivačem od kamenih ploča i netaknutom prirodom' . $src, 'sort_order' => 2],
        ]
    ],

    // 170: Manastir Prohor Pčinjski
    170 => [
        'name' => 'Manastir Prohor Pčinjski',
        'card_image' => 'images/monasteries/prohor-pcinjski.jpg',
        'images' => [
            ['url' => 'images/monasteries/prohor-pcinjski.jpg', 'caption' => 'Monumentalni kompleks manastira Svetog Prohora Pčinjskog na šumovitim obalama reke Pčinje' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/prohor-pcinjski_gal_1.jpg', 'caption' => 'Glavni manastirski hram posvećen Svetom Prohoru sa prepoznatljivim kupolama' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/prohor-pcinjski_gal_2.jpg', 'caption' => 'Prostrani spratni konaci kralja Petra I Karađorđevića i Vranjski konak u porti' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/prohor-pcinjski_gal_3.jpg', 'caption' => 'Kivot sa svetim mirotočivim moštima Prepodobnog Prohora Pčinjskog u unutrašnjosti hrama' . $src, 'sort_order' => 4],
        ]
    ],

    // 249: Manastir Simeon Stolpnik
    249 => [
        'name' => 'Manastir Simeon Stolpnik',
        'card_image' => 'images/monasteries/simeon-stolpnik.jpg',
        'images' => [
            ['url' => 'images/monasteries/simeon-stolpnik.jpg', 'caption' => 'Crkva Svetog Simeona Stolpnika u selu Kupinince kod Vranja u mirnom seoskom ambijentu' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/simeon-stolpnik_gal_1.jpg', 'caption' => 'Zapadna fasada sa otvorenim drvenim tremom, zvonikom i uređenom manastirskom portom' . $src, 'sort_order' => 2],
        ]
    ],

    // 253: Manastir Sveti Nikola (Vranje)
    253 => [
        'name' => 'Manastir Sveti Nikola',
        'card_image' => 'images/monasteries/sveti-nikola-vranje.jpg',
        'images' => [
            ['url' => 'images/monasteries/sveti-nikola-vranje.jpg', 'caption' => 'Crkva Svetog Nikole u Vranju, zadužbina kralja Stefana Uroša II Milutina iz 13. veka' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/sveti-nikola-vranje_gal_1.jpg', 'caption' => 'Istočna fasada crkve sa polukružnom apsidom i vizantijskim slogom zidanja od opeke i kamena' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/sveti-nikola-vranje_gal_2.jpg', 'caption' => 'Ulazni portal sa kamenim zvonikom i natpisom o obnovama manastirskog kompleksa' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/sveti-nikola-vranje_gal_3.jpg', 'caption' => 'Čuveni ikonostas u naosu crkve koji je oslikao znameniti zograf Dičo Krstev u 19. veku' . $src, 'sort_order' => 4],
        ]
    ],

    // 171: Manastir Žapsko
    171 => [
        'name' => 'Manastir Žapsko',
        'card_image' => 'images/monasteries/zapsko.jpg',
        'images' => [
            ['url' => 'images/monasteries/zapsko.jpg', 'caption' => 'Crkva Svetog arhiđakona Stefana u manastiru Žapsko kod Vranja okružena zelenilom' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/zapsko_gal_1.jpg', 'caption' => 'Zapadno pročelje hrama sa kamenim zvonikom i belom fasadom' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/zapsko_gal_2.jpg', 'caption' => 'Uređeni manastirski konaci sa cvetnim lejama i fontanom u porti' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/zapsko_gal_3.jpg', 'caption' => 'Unutrašnjost crkve sa rezbarenim ikonostasom i slavskim ikonama svetitelja' . $src, 'sort_order' => 4],
        ]
    ],
];

echo "2. Ažuriranje primarne baze podataka (database/database.sqlite):\n";
DB::beginTransaction();

try {
    foreach ($vranjska_updates as $monasteryId => $data) {
        $monastery = Monastery::find($monasteryId);
        if (!$monastery) {
            echo "  [UPOZORENJE] Manastir ID {$monasteryId} nije pronađen!\n";
            continue;
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
        echo "  [AŽURIRAN] [{$monasteryId}] {$monastery->name} | Galerija: {$count} slika\n";
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

        foreach ($vranjska_updates as $monasteryId => $data) {
            $stmt = $pdo->prepare("UPDATE monasteries SET image_url = :image_url, image = :img WHERE id = :id");
            $stmt->execute([
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

            echo "  [STORAGE AŽURIRAN] [{$monasteryId}] {$data['name']}\n";
        }

        $pdo->commit();
        echo "Storage baza je uspešno ažurirana!\n\n";
    } catch (\Exception $e) {
        $pdo->rollBack();
        echo "GREŠKA pri radu sa storage bazom: " . $e->getMessage() . "\n";
    }
}

echo "====================================================================\n";
echo "EPARHIJA VRANJSKA - REVIZIJA ZAVRŠENA USPEŠNO!\n";
echo "====================================================================\n";
