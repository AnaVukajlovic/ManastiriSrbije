<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Eparchy;
use App\Models\Monastery;
use App\Models\MonasteryImage;

$eparchies = Eparchy::all();
echo "=== EPARHIJE ===\n";
foreach ($eparchies as $ep) {
    echo "ID: {$ep->id} | Name: {$ep->name} | Slug: {$ep->slug}\n";
}

$zicka = Eparchy::where('slug', 'like', '%zick%')
    ->orWhere('name', 'like', '%žičk%')
    ->orWhere('name', 'like', '%zick%')
    ->first();

if (!$zicka) {
    echo "\nŽička eparhija nije pronađena po imenu, proveravam sve eparhije.\n";
    exit(1);
}

echo "\nPronađena Eparhija: ID {$zicka->id} | {$zicka->name} ({$zicka->slug})\n\n";

$monasteries = Monastery::where('eparchy_id', $zicka->id)->orderBy('id')->get();
echo "Ukupno manastira u Žičkoj eparhiji: " . $monasteries->count() . "\n\n";

foreach ($monasteries as $m) {
    echo "ID: {$m->id} | Name: {$m->name} | Slug: {$m->slug} | Card: {$m->image_url}\n";
    echo "Has Source Tag in Description: " . (str_contains($m->description, '<small>*(Izvor: manastiri.rs)*</small>') ? 'YES' : 'NO') . "\n";
    $images = MonasteryImage::where('monastery_id', $m->id)->orderBy('sort_order')->get();
    echo "Images count: " . $images->count() . "\n";
    foreach ($images as $img) {
        echo "  - [ID: {$img->id} | Order: {$img->sort_order}] {$img->url}\n    Caption: {$img->caption}\n";
    }
    echo "--------------------------------------------------------\n";
}
