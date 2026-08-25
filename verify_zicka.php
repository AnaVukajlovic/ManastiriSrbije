<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;

echo "=========================================================\n";
echo "VERIFIKACIJA PODATAKA EPARHIJE ŽIČKE (ID 1)\n";
echo "=========================================================\n\n";

$monasteries = Monastery::where('eparchy_id', 1)->with('images')->orderBy('id')->get();
echo "Ukupno manastira u Žičkoj eparhiji: " . $monasteries->count() . "\n\n";

$errors = 0;
$missingFiles = 0;

foreach ($monasteries as $idx => $m) {
    echo ($idx + 1) . ". [ID {$m->id}] {$m->name}\n";
    echo "   Kartična slika: {$m->image_url}\n";

    $cardPath = __DIR__ . '/public/' . ltrim($m->image_url, '/');
    if (!file_exists($cardPath)) {
        echo "   [GREŠKA] Kartična slika NE POSTOJI na disku: {$cardPath}\n";
        $missingFiles++;
        $errors++;
    }

    echo "   Galerija (" . $m->images->count() . " slika):\n";
    foreach ($m->images as $img) {
        $imgPath = __DIR__ . '/public/' . ltrim($img->url, '/');
        $exists = file_exists($imgPath) ? "OK" : "NE POSTOJI!";
        if (!file_exists($imgPath)) {
            echo "     [GREŠKA] Fajl ne postoji: {$imgPath}\n";
            $missingFiles++;
            $errors++;
        }
        echo "     - [Redosled {$img->sort_order}] {$img->url} | Fajl: {$exists}\n";
        echo "       Opis: {$img->caption}\n";
    }
    echo "\n";
}

echo "=========================================================\n";
echo "REZULTAT VERIFIKACIJE:\n";
echo "Ukupno grešaka: {$errors}\n";
echo "Nedostajući fajlovi: {$missingFiles}\n";
echo "=========================================================\n";
