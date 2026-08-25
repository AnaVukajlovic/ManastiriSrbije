<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;
use App\Models\MonasteryImage;

$monasteries = Monastery::with('images')->where('eparchy_id', 10)->orderBy('id')->get();

echo "Ukupno manastira u Eparhiji raško-prizrenskoj (eparchy_id 10): " . $monasteries->count() . "\n\n";

foreach ($monasteries as $m) {
    echo "========================================\n";
    echo "ID: {$m->id}\n";
    echo "Naziv: {$m->name}\n";
    echo "Slug: {$m->slug}\n";
    echo "Lokacija: {$m->location}\n";
    echo "Image URL (Card): {$m->image_url}\n";
    echo "Opis (prvih 100 karaktera): " . mb_substr(strip_tags($m->description), 0, 100) . "...\n";
    $hasSource = str_contains($m->description, '<small>*(Izvor: manastiri.rs)*</small>');
    echo "Ima manastiri.rs izvor na kraju: " . ($hasSource ? 'DA' : 'NE') . "\n";
    echo "Galerija slika (" . $m->images->count() . "):\n";
    foreach ($m->images as $img) {
        echo "  - [ID {$img->id}] URL: {$img->url} | Redosled: {$img->sort_order} | Caption: {$img->caption}\n";
    }
}
