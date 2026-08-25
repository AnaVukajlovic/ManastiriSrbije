<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$dioceseIds = [6]; // Banatska eparhija

$monasteries = App\Models\Monastery::whereIn('eparchy_id', $dioceseIds)
    ->with('images')
    ->orderBy('name')
    ->get();

echo "=== PROVERA NEDOSTAJUĆIH SLIKA ZA BANATSKU EPARHIJU ===" . PHP_EOL;
echo "Total monasteries: " . $monasteries->count() . PHP_EOL . PHP_EOL;

$publicPath = __DIR__ . '/public/images/monasteries/';

foreach ($monasteries as $monastery) {
    echo "=== {$monastery->name} (slug: {$monastery->slug}) ===" . PHP_EOL;
    
    // Check card image
    $cardImage = $monastery->image_url;
    if ($cardImage) {
        $cardPath = $publicPath . basename($cardImage);
        if (file_exists($cardPath)) {
            echo "  Card image: EXISTS - " . basename($cardImage) . PHP_EOL;
        } else {
            echo "  Card image: MISSING - " . basename($cardImage) . PHP_EOL;
        }
    }
    
    // Check gallery images
    foreach ($monastery->images as $image) {
        $imagePath = $publicPath . basename($image->url);
        if (file_exists($imagePath)) {
            echo "  Gallery: EXISTS - " . basename($image->url) . PHP_EOL;
        } else {
            echo "  Gallery: MISSING - " . basename($image->url) . PHP_EOL;
        }
    }
    echo PHP_EOL;
}
