<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Monastery;
use App\Models\MonasteryImage;

$monasteries = Monastery::whereIn('eparchy_id', [3, 6, 7])->with('images')->get();
$data = [];

foreach ($monasteries as $m) {
    $data[] = [
        'id' => $m->id,
        'name' => $m->name,
        'eparchy_id' => $m->eparchy_id,
        'card_image' => $m->card_image,
        'description' => $m->description, // Or whatever the description field is called
        'lat' => $m->lat,
        'lng' => $m->lng,
        'images' => $m->images->map(function ($img) {
            return [
                'id' => $img->id,
                'path' => $img->path,
                'description' => $img->description,
                'source' => $img->source, // Assuming these fields exist
            ];
        })->toArray()
    ];
}

file_put_contents('scratch_inspect/bbb_data.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Done.";
