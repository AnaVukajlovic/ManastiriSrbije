<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$dioceseIds = [6]; // Banatska eparhija

$monasteries = App\Models\Monastery::whereIn('eparchy_id', $dioceseIds)
    ->with('images')
    ->orderBy('name')
    ->get();

echo "=== AŽURIRANJE PRECIZNIH OPISA ZA BANATSKU EPARHIJU ===" . PHP_EOL;
echo "Total monasteries: " . $monasteries->count() . PHP_EOL . PHP_EOL;

// Precizni opisi na osnovu stvarnih slika
$preciseCaptions = [
    'bavaniste' => 'Crkva Rođenja Presvete Bogorodice sa zvonikom i konakom.',
    'hajducica' => 'Crkva Svetih Arhangela Mihaila i Gavrila u baroknom stilu.',
    'mesic' => 'Crkva Svetog Jovana Krstitelja sa baroknim zvonikom i kupolom.',
    'srediste' => 'Crkva Prenudrosti Božije na padini Guduričkog vrha.',
    'sveta-trojica-kikinda' => 'Crkva Svete Trojice sa baroknom arhitekturom.',
    'svete-melanije' => 'Crkva Svete Melanije Rimljanke sa kupolom u vizantijskom stilu.',
    'vojlovica' => 'Crkva Svetog Arhangela Gavrila u raškom stilu sa zvonikom.',
];

foreach ($monasteries as $monastery) {
    echo "=== {$monastery->name} (slug: {$monastery->slug}) ===" . PHP_EOL;
    
    foreach ($monastery->images as $image) {
        $oldCaption = $image->caption;
        
        // Extract source
        $source = '';
        if (strpos($oldCaption, '*Izvor:') !== false) {
            preg_match('/\*Izvor: (.*)\*/', $oldCaption, $matches);
            $source = $matches[1] ?? '';
        }
        
        // Get precise description
        $preciseDesc = $preciseCaptions[$monastery->slug] ?? 'Eksterijer manastira.';
        
        // Format: description + source
        $newCaption = $preciseDesc . "\n*Izvor: " . $source . "*";
        
        $image->caption = $newCaption;
        $image->save();
        
        echo "  - Image ID {$image->id}: Updated caption" . PHP_EOL;
        echo "    New: " . $newCaption . PHP_EOL;
    }
    echo PHP_EOL;
}

echo "=== COMPLETED ===" . PHP_EOL;
