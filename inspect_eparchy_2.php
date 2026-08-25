<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;

$monasteries = Monastery::with('images')->where('eparchy_id', 2)->orderBy('id')->get();

echo "Ukupno manastira u Eparhiji raško-prizrenskoj (eparchy_id 2): " . $monasteries->count() . "\n\n";

foreach ($monasteries as $m) {
    echo "========================================\n";
    echo "ID: {$m->id} | Naziv: {$m->name} | Slug: {$m->slug}\n";
    echo "Card slika: {$m->image_url}\n";
    $hasSource = str_contains($m->description, '<small>*(Izvor: manastiri.rs)*</small>');
    echo "Izvor na kraju opisa: " . ($hasSource ? 'DA' : 'NE') . "\n";
    echo "Galerija (" . $m->images->count() . " slika):\n";
    foreach ($m->images as $img) {
        echo "  - [ID {$img->id}] URL: {$img->url} | Order: {$img->sort_order} | Caption: {$img->caption}\n";
    }
}
