<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;

$monasteries = Monastery::where('eparchy_id', 1)->whereBetween('id', [206, 225])->orderBy('id')->get();

$all_files = glob(public_path('images/monasteries/*.*'));
$file_map = [];
foreach ($all_files as $f) {
    $file_map[basename($f)] = filesize($f);
}

foreach ($monasteries as $m) {
    echo "ID: {$m->id} | {$m->name}\n";
    echo "  Card: {$m->image_url}\n";
    $slug = str_replace('images/monasteries/', '', $m->image_url);
    $slug = preg_replace('/(\.jpg|\.png|\.webp)$/', '', $slug);
    $slugBase = preg_replace('/_gal_\d+$/', '', $slug);
    
    echo "  Disk matching '$slugBase*':\n";
    foreach ($file_map as $fn => $sz) {
        if (strpos($fn, $slugBase) === 0) {
            echo "    - $fn ($sz B)\n";
        }
    }
    echo "\n";
}
