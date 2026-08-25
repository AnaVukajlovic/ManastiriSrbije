<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;

$monasteries = Monastery::with('images')->where('eparchy_id', 2)->orderBy('id')->get();

$allImages = [];

foreach ($monasteries as $m) {
    echo "=== [ID {$m->id}] {$m->name} ({$m->slug}) ===\n";
    echo "Card: {$m->image_url}\n";
    $allImages[$m->image_url][] = "Card of {$m->name}";
    
    foreach ($m->images as $img) {
        echo "  - Gal [ID {$img->id}]: {$img->url} | Order: {$img->sort_order} | Caption: {$img->caption}\n";
        $allImages[$img->url][] = "Gallery of {$m->name} (order {$img->sort_order})";
    }
    echo "\n";
}

echo "\n--- JEDINSTVENE SLIKE (" . count($allImages) . ") ---\n";
foreach ($allImages as $path => $usages) {
    $fullPath = __DIR__ . '/public/' . $path;
    $exists = file_exists($fullPath);
    $size = $exists ? filesize($fullPath) : 0;
    $sizeKb = round($size / 1024, 1);
    echo "$path | Exists: " . ($exists ? "DA ({$sizeKb} KB)" : "NE") . " | Usages: " . implode(', ', $usages) . "\n";
}
