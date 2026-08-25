<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;
use App\Models\MonasteryImage;

$monasteries = Monastery::where('name', 'LIKE', '%Lozica%')
    ->orWhere('name', 'LIKE', '%Krepičevac%')
    ->orWhere('name', 'LIKE', '%Krepicevac%')
    ->get();

foreach ($monasteries as $m) {
    echo "========================================================\n";
    echo "ID: {$m->id} | Naziv: {$m->name} | Eparhija: {$m->eparchy_id}\n";
    echo "Card slika: {$m->image_url}\n";
    $cardPath = public_path($m->image_url);
    echo "Card status: " . (file_exists($cardPath) ? filesize($cardPath) . " B" : "NE POSTOJI") . "\n";
    echo "Galerijske slike (" . $m->images->count() . "):\n";
    foreach ($m->images as $img) {
        $imgPath = public_path($img->url);
        $size = file_exists($imgPath) ? filesize($imgPath) . " B" : "NE POSTOJI";
        $hash = file_exists($imgPath) ? md5_file($imgPath) : "N/A";
        echo "  - ID {$img->id} [sort {$img->sort_order}]: {$img->url} ({$size}, md5: {$hash})\n";
        echo "    Caption: " . $img->caption . "\n";
    }
}
