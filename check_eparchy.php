<?php

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;

$eparchyId = 4;
$ms = Monastery::where('eparchy_id', $eparchyId)->with('images')->get();

echo "Total monasteries in Eparhija Šumadijska (ID: {$eparchyId}): " . count($ms) . "\n\n";

foreach ($ms as $m) {
    echo "ID: {$m->id} - {$m->name}\n";
    foreach ($m->images as $img) {
        echo "   [{$img->sort_order}] {$img->url}\n";
    }
}
