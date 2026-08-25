<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;

$ids = [206, 210, 212, 213, 218, 219, 223, 224, 225, 227, 228, 229, 231, 232, 233, 234, 236, 254];

foreach ($ids as $id) {
    $m = Monastery::with('images')->find($id);
    if (!$m) {
        echo "ID {$id} NOT FOUND\n";
        continue;
    }
    echo "=== ID: {$m->id} | {$m->name} ({$m->slug}) ===\n";
    echo "Card image: {$m->image_url}\n";
    echo "Card ImageSrc: {$m->image_src}\n";
    echo "Gallery images count: " . $m->images->count() . "\n";
    foreach ($m->images as $img) {
        $fullPath = public_path(ltrim($img->url, '/'));
        $exists = file_exists($fullPath) ? "EXISTS" : "MISSING";
        echo "  - [Order: {$img->sort_order}] {$img->url} ({$exists})\n";
        echo "    Caption: {$img->caption}\n";
    }
    echo "\n";
}
