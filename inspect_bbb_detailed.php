<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Monastery;
use App\Models\MonasteryImage;

$monasteries = Monastery::whereIn('eparchy_id', [3, 6, 7])->with('images')->get();
$data = [];

foreach ($monasteries as $m) {
    $data[$m->id] = [
        'id' => $m->id,
        'name' => $m->name,
        'eparchy_id' => $m->eparchy_id,
        'card_image' => $m->image_url,
        'lat' => $m->latitude ?? $m->lat,
        'lng' => $m->longitude ?? $m->lng,
        'description' => $m->description,
        'history' => $m->history,
        'architecture' => $m->architecture,
        'images' => $m->images->map(function ($img) {
            return [
                'id' => $img->id,
                'url' => $img->url,
                'caption' => $img->caption,
            ];
        })->toArray()
    ];
}

file_put_contents('scratch_inspect/bbb_detailed.json', json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "Done.";
