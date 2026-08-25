<?php
require dirname(__DIR__) . '/vendor/autoload.php';
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;
use App\Models\MonasteryImage;
use App\Models\Eparchy;

$searchNames = [
    'ilinj', 'ailinj', 'klinj',
    'jezevic', 'ježevic',
    'jovanj',
    'pavlic',
    'preobrazenj', 'preobraženj',
    'sabor',
    'savinac',
    'sretenj',
    'stjenik', 'sjtenik',
    'blagovest',
    'studenic',
    'stubal',
    'uvac',
    'ducalovic', 'dučalovic',
    'vavedenj',
    'uspenj',
    'vaznesenj',
    'vracevsn', 'vraćevšn'
];

$seenIds = [];

foreach ($searchNames as $name) {
    $monasteries = Monastery::where('name', 'like', "%{$name}%")
        ->orWhere('slug', 'like', "%{$name}%")
        ->with('eparchy')
        ->get();
    foreach ($monasteries as $m) {
        if (in_array($m->id, $seenIds)) continue;
        $seenIds[] = $m->id;
        $epName = $m->eparchy ? $m->eparchy->name : "Eparhija {$m->eparchy_id}";
        echo "========================================================\n";
        echo "ID: {$m->id} | Name: {$m->name} | Slug: {$m->slug} | Eparchy: {$epName} (ID: {$m->eparchy_id})\n";
        echo "Card Image: {$m->image_url}\n";
        $imgs = MonasteryImage::where('monastery_id', $m->id)->orderBy('sort_order')->get();
        echo "Images count: " . $imgs->count() . "\n";
        foreach ($imgs as $idx => $img) {
            $num = $idx + 1;
            echo "   [#{$num} ID: {$img->id}, Order: {$img->sort_order}]\n";
            echo "      URL: {$img->url}\n";
            echo "      Caption: '{$img->caption}'\n";
        }
    }
}
