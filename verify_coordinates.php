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

echo "=== VERIFYING COORDINATES FOR BANATSKA, BAČKA, AND BEOGRADSKA DIOCESES ===" . PHP_EOL;
echo "Total monasteries: " . $monasteries->count() . PHP_EOL . PHP_EOL;

// Correct coordinates from research
$correctCoordinates = [
    'mislodjin' => ['lat' => 44.6416, 'lon' => 20.2377],
    'rajinovac' => ['lat' => 44.6247, 'lon' => 20.7128],
    'rakovica' => ['lat' => 44.7306, 'lon' => 20.4471],
    'senjak' => ['lat' => 44.7927, 'lon' => 20.4389],
    'slanci' => ['lat' => 44.7954, 'lon' => 20.5842],
    'trojerucica' => ['lat' => 44.6164, 'lon' => 20.5863],
    'bavaniste' => ['lat' => 44.8271, 'lon' => 20.8940],
    'gaj' => ['lat' => 44.7703, 'lon' => 21.0889],
    'hajducica' => ['lat' => 45.2539, 'lon' => 20.9661],
    'mesic' => ['lat' => 45.1041, 'lon' => 21.3920],
    'srediste' => ['lat' => 45.1441, 'lon' => 21.3977],
    'sveta-trojica-kikinda' => ['lat' => 45.8188, 'lon' => 20.4675],
    'svete-melanije' => ['lat' => 45.3939, 'lon' => 20.4130],
    'vlajkovac' => ['lat' => 45.0713, 'lon' => 21.1997],
    'vojlovica' => ['lat' => 44.8280, 'lon' => 20.6843],
    'bodjani' => ['lat' => 45.3961, 'lon' => 19.1022],
    'kac' => ['lat' => 45.2941, 'lon' => 19.9633],
    'kovilj' => ['lat' => 45.2139, 'lon' => 20.0355],
    'sombor' => ['lat' => 45.7781, 'lon' => 19.1339],
    'vodica' => ['lat' => 45.7141, 'lon' => 20.0377],
];

foreach ($monasteries as $monastery) {
    echo "=== {$monastery->name} (slug: {$monastery->slug}) ===" . PHP_EOL;
    echo "Eparchy: " . ($monastery->eparchy ? $monastery->eparchy->name : 'N/A') . PHP_EOL;
    echo "Current coordinates: " . ($monastery->latitude ?? 'N/A') . ', ' . ($monastery->longitude ?? 'N/A') . PHP_EOL;
    
    if (isset($correctCoordinates[$monastery->slug])) {
        $correct = $correctCoordinates[$monastery->slug];
        echo "Correct coordinates: {$correct['lat']}, {$correct['lon']}" . PHP_EOL;
        
        // Check if coordinates need updating (allow small margin of error)
        $latDiff = abs(($monastery->latitude ?? 0) - $correct['lat']);
        $lonDiff = abs(($monastery->longitude ?? 0) - $correct['lon']);
        
        if ($latDiff > 0.001 || $lonDiff > 0.001) {
            $monastery->latitude = $correct['lat'];
            $monastery->longitude = $correct['lon'];
            $monastery->save();
            echo "UPDATED: Coordinates corrected" . PHP_EOL;
        } else {
            echo "OK: Coordinates are correct" . PHP_EOL;
        }
    } else {
        echo "WARNING: No correct coordinates found in reference data" . PHP_EOL;
    }
    echo PHP_EOL;
}

echo "=== COMPLETED ===" . PHP_EOL;
