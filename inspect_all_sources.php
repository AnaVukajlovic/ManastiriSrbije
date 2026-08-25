<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;
use App\Models\MonasteryImage;

echo "=== DETALJNA PROVERA IZVORA ZA SVE MANASTIRE ===\n\n";

$monasteries = Monastery::orderBy('eparchy_id')->orderBy('id')->get();

foreach ($monasteries as $m) {
    $images = MonasteryImage::where('monastery_id', $m->id)->get();
    $sources = [];
    foreach ($images as $img) {
        if (preg_match('/Izvor:\s*([^)<*]+)/i', $img->caption, $matches)) {
            $src = trim($matches[1]);
            $sources[$src] = ($sources[$src] ?? 0) + 1;
        } else {
            $sources['BEZ_IZVORA'] = ($sources['BEZ_IZVORA'] ?? 0) + 1;
        }
    }
    $srcStr = [];
    foreach ($sources as $k => $v) {
        $srcStr[] = "$k ($v)";
    }
    echo "ID {$m->id} [Ep {$m->eparchy_id}] {$m->name} -> " . implode(', ', $srcStr) . "\n";
}
