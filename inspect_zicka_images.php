<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;
use App\Models\MonasteryImage;

$monasteries = Monastery::where('eparchy_id', 1)->orderBy('id')->get();

$allImages = [];

foreach ($monasteries as $m) {
    echo "=== [ID: {$m->id}] {$m->name} ({$m->slug}) ===\n";
    echo "Card image: {$m->image_url}\n";
    $cardPath = __DIR__ . '/public/' . $m->image_url;
    echo "Card file exists: " . (file_exists($cardPath) ? 'YES' : 'NO (' . $cardPath . ')') . "\n";
    
    $images = MonasteryImage::where('monastery_id', $m->id)->orderBy('sort_order')->get();
    echo "Gallery count: " . $images->count() . "\n";
    foreach ($images as $img) {
        $imgPath = __DIR__ . '/public/' . $img->url;
        echo "  - [Order: {$img->sort_order}] {$img->url} | Exists: " . (file_exists($imgPath) ? 'YES' : 'NO') . "\n";
        echo "    Caption: {$img->caption}\n";
    }
    echo "\n";
}
