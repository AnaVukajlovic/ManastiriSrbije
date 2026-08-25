<?php

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$targetIds = [210, 198, 212, 213, 163, 218, 219, 223, 224, 225, 254, 206, 228, 227, 232, 229, 233, 231, 234, 236];

$monasteries = DB::table('monasteries')
    ->whereIn('id', $targetIds)
    ->orderBy('name')
    ->get();

$publicDir = public_path();
$report = [];

foreach ($monasteries as $m) {
    $cardExists = file_exists($publicDir . '/' . $m->image_url);
    $images = DB::table('monastery_images')
        ->where('monastery_id', $m->id)
        ->orderBy('sort_order')
        ->get();

    $imgList = [];
    foreach ($images as $img) {
        $imgExists = file_exists($publicDir . '/' . $img->url);
        $imgList[] = [
            'id' => $img->id,
            'url' => $img->url,
            'exists_on_disk' => $imgExists,
            'caption' => $img->caption,
            'sort_order' => $img->sort_order,
        ];
    }

    $report[] = [
        'id' => $m->id,
        'name' => $m->name,
        'eparchy_id' => $m->eparchy_id,
        'card_image' => $m->image_url,
        'card_exists' => $cardExists,
        'gallery_count' => count($images),
        'gallery' => $imgList,
    ];
}

echo json_encode($report, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
