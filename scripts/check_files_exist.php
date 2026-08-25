<?php
require dirname(__DIR__) . '/vendor/autoload.php';
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;
use App\Models\MonasteryImage;

$targetIds = [206, 210, 212, 213, 218, 219, 223, 224, 225, 227, 228, 229, 231, 232, 233, 234, 236, 254, 163];

foreach ($targetIds as $id) {
    $m = Monastery::find($id);
    if (!$m) continue;
    echo "======================================================================\n";
    echo "ID {$m->id}: {$m->name} (Slug: {$m->slug})\n";
    echo "  Card URL: {$m->image_url}\n";
    $cardPath = dirname(__DIR__) . '/public/' . ltrim($m->image_url, '/');
    echo "    Card File Exists: " . (file_exists($cardPath) ? 'YES (' . filesize($cardPath) . ' bytes)' : 'NO - MISSING: ' . $cardPath) . "\n";
    
    $imgs = MonasteryImage::where('monastery_id', $m->id)->orderBy('sort_order')->get();
    foreach ($imgs as $idx => $img) {
        $imgPath = dirname(__DIR__) . '/public/' . ltrim($img->url, '/');
        echo "  Img #" . ($idx + 1) . " [ID: {$img->id}]: {$img->url}\n";
        echo "    Exists: " . (file_exists($imgPath) ? 'YES (' . filesize($imgPath) . ' bytes)' : 'NO - MISSING: ' . $imgPath) . "\n";
        echo "    Caption: {$img->caption}\n";
    }
}
