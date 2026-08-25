<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Ktitor;

$ktitors = Ktitor::with('images')->get();
echo "=== PREGLED GALERIJE PO KTITORIMA ===\n\n";

foreach ($ktitors as $k) {
    echo "👑 {$k->name} ({$k->slug}) - {$k->images->count()} slika:\n";
    foreach ($k->images->sortBy('sort') as $idx => $img) {
        $cleanCap = strip_tags(explode('<br>', $img->caption)[0]);
        $exists = file_exists(public_path($img->path)) ? '✓ POSTOJI' : '✗ FALI FAJL';
        echo "   " . ($idx + 1) . ". [{$exists}] {$img->path}\n";
        echo "      -> {$cleanCap}\n";
    }
    echo "\n";
}
