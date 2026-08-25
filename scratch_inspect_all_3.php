<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Monastery;
use App\Models\MonasteryImage;
use App\Models\Eparchy;

$eparchies = [
    3 => 'Beogradska eparhija (Beogradsko-karlovačka)',
    6 => 'Banatska eparhija',
    7 => 'Bačka eparhija'
];

foreach ($eparchies as $epId => $epName) {
    echo "========================================================================\n";
    echo "EPARHIJA: $epName (ID: $epId)\n";
    echo "========================================================================\n";
    
    $monasteries = Monastery::where('eparchy_id', $epId)->orderBy('id')->get();
    
    foreach ($monasteries as $m) {
        echo "------------------------------------------------------------------------\n";
        echo "ID: {$m->id} | Name: {$m->name} | Slug: {$m->slug}\n";
        echo "City: {$m->city} | Region: {$m->region}\n";
        echo "Coordinates (lat, lng): {$m->lat}, {$m->lng} (latitude: {$m->latitude}, longitude: {$m->longitude})\n";
        echo "Card Image: {$m->image_url}\n";
        echo "Card Image exists on disk: " . (file_exists(public_path($m->image_url)) ? 'YES' : (file_exists(public_path('/' . ltrim($m->image_url, '/'))) ? 'YES' : 'NO')) . "\n";
        echo "Short Description: " . mb_substr($m->description_short ?? '', 0, 100) . "...\n";
        echo "Description length: " . mb_strlen($m->description ?? '') . " chars\n";
        echo "Gallery Images (" . $m->images->count() . "):\n";
        foreach ($m->images as $img) {
            $diskExists = file_exists(public_path($img->url)) || file_exists(public_path('/' . ltrim($img->url, '/')));
            echo "  - [ID: {$img->id}] Order: {$img->sort_order} | File: {$img->url} (Disk: " . ($diskExists ? 'YES' : 'NO') . ")\n";
            echo "    Caption: " . str_replace("\n", " \\n ", $img->caption) . "\n";
        }
    }
    echo "\n";
}
