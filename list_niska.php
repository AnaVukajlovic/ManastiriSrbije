<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;

$monasteries = Monastery::where('eparchy_id', 5)->with('images')->orderBy('id')->get();
echo "Found " . $monasteries->count() . " monasteries in Eparhija niska:\n";
foreach ($monasteries as $m) {
    echo "ID: {$m->id} | {$m->name} | slug: {$m->slug} | main_img: {$m->image_url}\n";
    foreach ($m->images as $img) {
        echo "  - [order {$img->sort_order}] {$img->url} | caption: {$img->caption}\n";
    }
}
