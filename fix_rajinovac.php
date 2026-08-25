<?php

/**
 * FIX RAJINOVAC IMAGES & CAPTIONS
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
echo "USKLAĐIVANJE SLIKA I OPISA ZA MANASTIR RAJINOVAC (ID 16)\n";
echo "====================================================================\n\n";

$rajinovac_images = [
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
];

// 1. Primarna baza
DB::beginTransaction();
try {
    $monastery = Monastery::find(16);
    $monastery->image_url = 'images/monasteries/rajinovac.jpg';
    $monastery->save();

    MonasteryImage::where('monastery_id', 16)->delete();

    foreach ($rajinovac_images as $imgData) {
        MonasteryImage::create([
            'monastery_id' => 16,
            'url' => $imgData['url'],
            'caption' => $imgData['caption'],
            'sort_order' => $imgData['sort_order'],
        ]);
        echo "  [AŽURIRANO] {$imgData['url']} -> {$imgData['caption']}\n";
    }

    DB::commit();
    echo "\nPrimarna baza uspešno ažurirana!\n";
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

        $stmtDel = $pdo->prepare("DELETE FROM monastery_images WHERE monastery_id = 16");
        $stmtDel->execute();

        $stmtIns = $pdo->prepare("INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (16, :url, :caption, :sort_order, datetime('now'), datetime('now'))");
        foreach ($rajinovac_images as $imgData) {
            $stmtIns->execute([
                ':url' => $imgData['url'],
                ':caption' => $imgData['caption'],
                ':sort_order' => $imgData['sort_order'],
            ]);
        }

        $pdo->commit();
        echo "Storage baza uspešno ažurirana!\n";
    } catch (\Exception $e) {
        $pdo->rollBack();
        echo "Greška u storage bazi: " . $e->getMessage() . "\n";
    }
}
