<?php

/**
 * SINHRONIZACIJA I USKLAĐIVANJE - MANASTIR LOZICA (ID 156) & MANASTIR KREPIČEVAC (ID 154)
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
echo "USKLAĐIVANJE SLIKA I OPISA: KREPIČEVAC (ID 154) I LOZICA (ID 156)\n";
echo "====================================================================\n\n";

$data_to_sync = [
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
];

// 1. Primarna baza
DB::beginTransaction();
try {
    foreach ($data_to_sync as $id => $item) {
        $monastery = Monastery::find($id);
        if (!$monastery) continue;

        $monastery->image_url = $item['card_image'];
        $monastery->save();

        MonasteryImage::where('monastery_id', $id)->delete();

        foreach ($item['images'] as $imgData) {
            MonasteryImage::create([
                'monastery_id' => $id,
                'url' => $imgData['url'],
                'caption' => $imgData['caption'],
                'sort_order' => $imgData['sort_order'],
            ]);
            echo "  [PRIMARNA BAZA] [{$id}] {$imgData['url']} -> {$imgData['caption']}\n";
        }
    }

    DB::commit();
    echo "\nPrimarna baza uspešno ažurirana!\n\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Greška u primarnoj bazi: " . $e->getMessage() . "\n";
}

// 2. Storage baza
$storageDbPath = storage_path('database.sqlite');
if (file_exists($storageDbPath)) {
    try {
        $pdo = new PDO('sqlite:' . $storageDbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->beginTransaction();

        foreach ($data_to_sync as $id => $item) {
            $stmt = $pdo->prepare("UPDATE monasteries SET image_url = :image_url, image = :img WHERE id = :id");
            $stmt->execute([
                ':image_url' => $item['card_image'],
                ':img' => $item['card_image'],
                ':id' => $id
            ]);

            $stmtDel = $pdo->prepare("DELETE FROM monastery_images WHERE monastery_id = :id");
            $stmtDel->execute([':id' => $id]);

            $stmtIns = $pdo->prepare("INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (:m_id, :url, :caption, :sort_order, datetime('now'), datetime('now'))");
            foreach ($item['images'] as $imgData) {
                $stmtIns->execute([
                    ':m_id' => $id,
                    ':url' => $imgData['url'],
                    ':caption' => $imgData['caption'],
                    ':sort_order' => $imgData['sort_order'],
                ]);
            }
            echo "  [STORAGE BAZA] [{$id}] ažuriran.\n";
        }

        $pdo->commit();
        echo "\nStorage baza uspešno ažurirana!\n\n";
    } catch (\Exception $e) {
        $pdo->rollBack();
        echo "Greška u storage bazi: " . $e->getMessage() . "\n";
    }
}
