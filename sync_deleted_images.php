<?php

/**
 * SINHRONIZACIJA OBRISANIH SLIKA SA BAZOM PODATAKA
 * Pravoslavni Svetionik
 */

use App\Models\Monastery;
use App\Models\MonasteryImage;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "====================================================================\n";
echo "SINHRONIZACIJA BAZA SA STANJEM SLIKA NA DISKU\n";
echo "====================================================================\n\n";

$dbPaths = [
    database_path('database.sqlite'),
    storage_path('database.sqlite')
];

$publicDir = public_path();

foreach ($dbPaths as $dbPath) {
    if (!file_exists($dbPath)) {
        echo "Baza ne postoji na putanji: {$dbPath}\n";
        continue;
    }

    echo "\n----------------------------------------------------\n";
    echo "OBRADA BAZE: {$dbPath}\n";
    echo "----------------------------------------------------\n";

    config(['database.connections.sqlite.database' => $dbPath]);
    DB::purge('sqlite');
    DB::reconnect('sqlite');

    $removedGalleryCount = 0;
    $updatedCardsCount = 0;

    $monasteries = Monastery::all();

    foreach ($monasteries as $m) {
        // 1. Provera galerijskih slika
        $galleryImages = MonasteryImage::where('monastery_id', $m->id)->orderBy('sort_order')->get();
        $validImages = [];

        foreach ($galleryImages as $img) {
            $filePath = $publicDir . '/' . ltrim($img->url, '/\\');
            if (file_exists($filePath)) {
                $validImages[] = $img;
            } else {
                echo "[-] [ID {$m->id} - {$m->name}] Uklonjena nepostojeća galerijska slika: '{$img->url}'\n";
                $img->delete();
                $removedGalleryCount++;
            }
        }

        // Re-indeksiranje sort_order-a za preostale validne slike
        $order = 1;
        foreach ($validImages as $img) {
            if ($img->sort_order !== $order) {
                $img->sort_order = $order;
                $img->save();
            }
            $order++;
        }

        // 2. Provera naslovne (card) slike
        if (!empty($m->image_url)) {
            $cardPath = $publicDir . '/' . ltrim($m->image_url, '/\\');
            if (!file_exists($cardPath)) {
                // Ako kartica ne postoji, uzmi prvu postojeću iz galerije ili placeholder
                if (count($validImages) > 0) {
                    $newCard = $validImages[0]->url;
                } else {
                    $newCard = 'images/monasteries/placeholder.jpg';
                }
                echo "[!] [ID {$m->id} - {$m->name}] Kartica '{$m->image_url}' ne postoji -> postavljena nova: '{$newCard}'\n";
                $m->image_url = $newCard;
                $m->save();
                $updatedCardsCount++;
            }
        }
    }

    echo "Završeno za bazu {$dbPath}: Uklonjeno {$removedGalleryCount} slika iz galerija, ažurirano {$updatedCardsCount} kartica.\n";
}

echo "\n====================================================================\n";
echo "SINHRONIZACIJA SLIKA USPEŠNO ZAVRŠENA NA OBEMA BAZAMA!\n";
echo "====================================================================\n";
