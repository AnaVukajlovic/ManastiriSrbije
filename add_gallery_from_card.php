<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$dioceseIds = [6]; // Banatska eparhija

$monasteries = App\Models\Monastery::whereIn('eparchy_id', $dioceseIds)
    ->with('images')
    ->orderBy('name')
    ->get();

echo "=== DODAVANJE GALERIJSKIH SLIKA IZ CARD IMAGE ZA BANATSKU EPARHIJU ===" . PHP_EOL;
echo "Total monasteries: " . $monasteries->count() . PHP_EOL . PHP_EOL;

$publicPath = __DIR__ . '/public/images/monasteries/';

foreach ($monasteries as $monastery) {
    echo "=== {$monastery->name} (slug: {$monastery->slug}) ===" . PHP_EOL;
    
    // Get current gallery count
    $galleryCount = $monastery->images->count();
    echo "  Current gallery: {$galleryCount} images" . PHP_EOL;
    
    // If gallery has less than 3 images, add copies of card image
    if ($galleryCount < 3 && $monastery->image_url) {
        $cardImage = basename($monastery->image_url);
        $cardPath = $publicPath . $cardImage;
        
        if (file_exists($cardPath)) {
            // Add copies until we have 3 images total
            $needed = 3 - $galleryCount;
            echo "  Adding {$needed} gallery images from card image..." . PHP_EOL;
            
            for ($i = 1; $i <= $needed; $i++) {
                $newFilename = $monastery->slug . '_gal_' . ($galleryCount + $i) . '.jpg';
                $newPath = $publicPath . $newFilename;
                
                // Copy card image
                copy($cardPath, $newPath);
                
                // Add to database
                $image = new App\Models\MonasteryImage();
                $image->monastery_id = $monastery->id;
                $image->url = 'images/monasteries/' . $newFilename;
                $image->caption = 'Eksterijer manastira.' . "\n*Izvor: " . ($monastery->source ?? 'manastiri.rs') . "*";
                $image->sort_order = $galleryCount + $i;
                $image->save();
                
                echo "    - Added: {$newFilename}" . PHP_EOL;
            }
        }
    } else {
        echo "  Gallery already has {$galleryCount} images (OK)" . PHP_EOL;
    }
    echo PHP_EOL;
}

echo "=== COMPLETED ===" . PHP_EOL;
