<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$targetSlugs = [
    'bodjani', 'kovilj', 'mislodjin', 'vlajkovac', 'vojlovica', // Target dioceses
    'kaona', 'komorane', 'koporin', 'oraovica', 'reskovica', 'sokograd', 'vrdnik', 'zica' // Others mentioned
];

$monasteries = App\Models\Monastery::whereIn('slug', $targetSlugs)->get();

foreach ($monasteries as $monastery) {
    echo "=== {$monastery->name} (slug: {$monastery->slug}) ===" . PHP_EOL;
    echo "Eparchy: " . ($monastery->eparchy ? $monastery->eparchy->name : 'N/A') . PHP_EOL;
    echo "Card Image: " . ($monastery->image_url ?? 'N/A') . PHP_EOL;
    echo "Coordinates: " . ($monastery->latitude ?? 'N/A') . ', ' . ($monastery->longitude ?? 'N/A') . PHP_EOL;
    echo "Gallery Images: " . $monastery->images->count() . PHP_EOL;
    
    foreach ($monastery->images as $img) {
        echo "  - {$img->url}" . PHP_EOL;
        echo "    Caption: " . ($img->caption ?? 'N/A') . PHP_EOL;
    }
    echo PHP_EOL;
}
