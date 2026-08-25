<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$dioceseIds = [3, 6, 7]; // Beogradska, Banatska, Bačka

$monasteries = App\Models\Monastery::whereIn('eparchy_id', $dioceseIds)
    ->with('eparchy')
    ->orderBy('eparchy_id')
    ->orderBy('name')
    ->get();

echo "=== MONASTERIES IN BANATSKA, BAČKA, AND BEOGRADSKA DIOCESES ===" . PHP_EOL;
echo "Total: " . $monasteries->count() . PHP_EOL . PHP_EOL;

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
