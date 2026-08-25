<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;
use App\Models\Eparchy;

$eparchy = Eparchy::where('id', 4)->orWhere('name', 'like', '%Šumadij%')->orWhere('name', 'like', '%Sumadij%')->first();
echo "Eparhija: ID {$eparchy->id} | {$eparchy->name}\n\n";

$monasteries = Monastery::with('images')->where('eparchy_id', $eparchy->id)->orderBy('id')->get();
echo "Ukupno manastira: " . $monasteries->count() . "\n\n";

foreach ($monasteries as $m) {
    echo "=== [ID {$m->id}] {$m->name} ({$m->slug}) ===\n";
    echo "Card: {$m->image_url}\n";
    foreach ($m->images as $img) {
        echo "  - Gal [ID {$img->id}]: {$img->url} | Order: {$img->sort_order} | Caption: {$img->caption}\n";
    }
    echo "\n";
}
