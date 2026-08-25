<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;
use App\Models\Eparchy;
use App\Models\MonasteryImage;

$eparchies = Eparchy::where('name', 'like', '%vranj%')->get();
echo "=== VRANJSKA EPARCHY INFO ===\n";
foreach ($eparchies as $ep) {
    echo "ID: {$ep->id} | Name: {$ep->name} | Slug: {$ep->slug}\n";
}

$vranjskaId = $eparchies->first() ? $eparchies->first()->id : 14;

$mons = Monastery::where('eparchy_id', $vranjskaId)->orWhere('eparchy', 'like', '%vranj%')->get();
echo "\nTotal Monasteries found: " . $mons->count() . "\n";
foreach ($mons as $m) {
    $galCount = MonasteryImage::where('monastery_id', $m->id)->count();
    $cardPath = public_path($m->image_url);
    $cardExists = file_exists($cardPath) ? "DA" : "NE";
    echo "ID: {$m->id} | Slug: {$m->slug} | Name: {$m->name} | Card: '{$m->image_url}' (postoji: {$cardExists}) | Gal: {$galCount} slika\n";
}
