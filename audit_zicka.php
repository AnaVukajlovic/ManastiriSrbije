<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;
use App\Models\MonasteryImage;

$monasteries = Monastery::where('eparchy_id', 1)->orderBy('id')->get();

$all_files = glob(public_path('images/monasteries/*.*'));
$file_map = [];
foreach ($all_files as $f) {
    $bn = basename($f);
    $file_map[$bn] = filesize($f);
}

echo "MONASTERIES IN EPARHIJA ŽIČKA (ID 1):\n\n";
foreach ($monasteries as $m) {
    echo "ID: {$m->id} | {$m->name}\n";
    echo "  Card: {$m->image_url} (" . (file_exists(public_path($m->image_url)) ? filesize(public_path($m->image_url)) . ' B' : 'MISSING') . ")\n";
    
    // Find all files matching this monastery slug/prefix on disk
    $slug = str_replace('images/monasteries/', '', $m->image_url);
    $slug = preg_replace('/(\.jpg|\.png|\.webp)$/', '', $slug);
    $slugBase = preg_replace('/_gal_\d+$/', '', $slug);
    
    echo "  Disk matching '$slugBase*':\n";
    foreach ($file_map as $fn => $sz) {
        if (strpos($fn, $slugBase) === 0) {
            echo "    - $fn ($sz B)\n";
        }
    }
    
    echo "  DB Gallery Images:\n";
    foreach ($m->images as $img) {
        echo "    [sort {$img->sort_order}] {$img->url} - {$img->caption}\n";
    }
    echo "\n";
}
