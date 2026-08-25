<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;

$monasteries = Monastery::where('eparchy_id', 5)->with('images')->orderBy('id')->get();
$index = 1;
foreach ($monasteries as $m) {
    $imgCount = $m->images->count();
    $imgs = $m->images->pluck('url')->implode(', ');
    echo "{$index}. ID {$m->id} | {$m->name} ({$m->slug}) | Total images: {$imgCount} | Files: [{$imgs}]\n";
    $index++;
}
