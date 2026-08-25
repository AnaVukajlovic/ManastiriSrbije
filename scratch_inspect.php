<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Monastery;
use App\Models\MonasteryImage;

echo "=== SAMPLES FROM EPARHIJA SREMSKA (131) & SUMADIJSKA (185) & NISKA (73) ===" . PHP_EOL;
$sampleImgs = MonasteryImage::whereIn('monastery_id', [131, 185, 73, 206])->take(10)->get();
foreach ($sampleImgs as $img) {
    echo "Monastery ID: {$img->monastery_id} | URL: {$img->url}" . PHP_EOL;
    echo "Caption: {$img->caption}" . PHP_EOL;
    echo "----------------------------------------" . PHP_EOL;
}
