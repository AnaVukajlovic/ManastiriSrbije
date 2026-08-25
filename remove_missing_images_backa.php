<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$dioceseIds = [7]; // Bačka eparhija

$monasteries = App\Models\Monastery::whereIn('eparchy_id', $dioceseIds)
    ->with('images')
    ->orderBy('name')
    ->get();

echo "=== UKLANJANJE NEDOSTAJUĆIH GALERIJSKIH SLIKA ZA BAČKU EPARHIJU ===" . PHP_EOL;
echo "Total monasteries: " . $monasteries->count() . PHP_EOL . PHP_EOL;

$publicPath = __DIR__ . '/public/images/monasteries/';

$removedCount = 0;

foreach ($monasteries as $monastery) {
    echo "=== {$monastery->name} (slug: {$monastery->slug}) ===" . PHP_EOL;
    
    foreach ($monastery->images as $image) {
        $imagePath = $publicPath . basename($image->url);
        
        if (!file_exists($imagePath)) {
            echo "  REMOVING: {$image->url} (file does not exist)" . PHP_EOL;
            $image->delete();
            $removedCount++;
        }
    }
    echo PHP_EOL;
}

echo "=== UKLONJENO UKUPNO: {$removedCount} SLIKA ===" . PHP_EOL;
