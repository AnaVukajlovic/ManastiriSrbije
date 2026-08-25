<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Eparchy;
use App\Models\Monastery;
use App\Models\MonasteryImage;

echo "=== PREGLED FORMATA IZVORA PO EPARHIJAMA ===\n\n";

$eparchies = Eparchy::orderBy('id')->get();
foreach ($eparchies as $ep) {
    $firstMonastery = Monastery::where('eparchy_id', $ep->id)->first();
    if (!$firstMonastery) continue;
    $firstImg = MonasteryImage::where('monastery_id', $firstMonastery->id)->first();
    echo "[Eparhija {$ep->id}: {$ep->name}]\n";
    echo "  Primer manastira: {$firstMonastery->name} (ID {$firstMonastery->id})\n";
    echo "  Primer caption: " . ($firstImg ? $firstImg->caption : 'NEMA') . "\n\n";
}
