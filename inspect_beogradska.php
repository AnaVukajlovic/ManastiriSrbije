<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;
use App\Models\MonasteryImage;
use App\Models\Eparchy;

$ep = Eparchy::find(3);
echo "Eparhija: " . ($ep ? $ep->id . ' - ' . $ep->name : 'Nije pronadjena') . "\n\n";

$monasteries = Monastery::where('eparchy_id', 3)->with('images')->get();
foreach ($monasteries as $m) {
    echo "========================================================\n";
    echo "ID: {$m->id} | Naziv: {$m->name}\n";
    echo "Card slika: {$m->image_url}\n";
    $cardPath = public_path($m->image_url);
    echo "Card status: " . (file_exists($cardPath) ? filesize($cardPath) . " B" : "NE POSTOJI") . "\n";
    echo "Galerijske slike (" . $m->images->count() . "):\n";
    foreach ($m->images as $img) {
        $imgPath = public_path($img->url);
        $size = file_exists($imgPath) ? filesize($imgPath) . " B" : "NE POSTOJI";
        echo "  - ID {$img->id} [sort {$img->sort_order}]: {$img->url} ({$size})\n";
        echo "    Caption: " . $img->caption . "\n";
    }
}
