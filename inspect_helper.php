<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$arg = $argv[1] ?? '82';

if (is_numeric($arg)) {
    $m = App\Models\Monastery::with('eparchy', 'images')->find($arg);
    if ($m) {
        echo "ID: {$m->id}\n";
        echo "Name: {$m->name}\n";
        echo "Slug: {$m->slug}\n";
        echo "Eparchy ID: {$m->eparchy_id}\n";
        echo "City: {$m->city}\n";
        echo "Region: {$m->region}\n";
        echo "Dedication: {$m->dedication}\n";
        echo "Image URL: {$m->image_url}\n";
        echo "Images count: " . $m->images->count() . "\n";
        foreach ($m->images as $img) {
            echo "  - [ID {$img->id}, Order {$img->sort_order}] {$img->url} => {$img->caption}\n";
        }
    } else {
        echo "Monastery $arg not found\n";
    }
} else {
    $ms = App\Models\Monastery::where('name', 'like', "%$arg%")
        ->orWhere('slug', 'like', "%$arg%")
        ->get();
    foreach ($ms as $m) {
        echo "ID: {$m->id}, Name: {$m->name}, Eparchy: {$m->eparchy_id}, City: {$m->city}, Image: {$m->image_url}\n";
    }
}
