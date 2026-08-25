<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$dioceseIds = [3, 6, 7]; // Beogradska, Banatska, Bačka

$monasteries = App\Models\Monastery::whereIn('eparchy_id', $dioceseIds)
    ->with('eparchy')
    ->with('images')
    ->orderBy('eparchy_id')
    ->orderBy('name')
    ->get();

echo "=== ANALYZING GALLERY IMAGES FOR BANATSKA, BAČKA, AND BEOGRADSKA DIOCESES ===" . PHP_EOL;
echo "Total monasteries: " . $monasteries->count() . PHP_EOL . PHP_EOL;

foreach ($monasteries as $monastery) {
    echo "=== {$monastery->name} (slug: {$monastery->slug}) ===" . PHP_EOL;
    echo "Eparchy: " . ($monastery->eparchy ? $monastery->eparchy->name : 'N/A') . PHP_EOL;
    echo "Gallery images: " . $monastery->images->count() . PHP_EOL;
    
    if ($monastery->images->count() == 0) {
        echo "  WARNING: No gallery images found!" . PHP_EOL;
    } else {
        foreach ($monastery->images as $image) {
            echo "  - {$image->url}" . PHP_EOL;
            echo "    Caption: " . ($image->caption ?? 'N/A') . PHP_EOL;
        }
    }
    echo PHP_EOL;
}

echo "=== ANALYSIS COMPLETE ===" . PHP_EOL;
