<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;
use App\Models\MonasteryImage;

// Pretrazivanje po imenu
$results = Monastery::where('name', 'LIKE', '%miso%')
    ->orWhere('name', 'LIKE', '%misol%')
    ->orWhere('name', 'LIKE', '%Miso%')
    ->orWhere('name', 'LIKE', '%Trojer%')
    ->orWhere('name', 'LIKE', '%trojer%')
    ->orWhere('name', 'LIKE', '%Trojeruci%')
    ->get();

echo "=== PRETRAGA PO IMENU ===\n";
foreach ($results as $m) {
    echo "[ID {$m->id}] {$m->name} (ep={$m->eparchy_id})\n";
}

// Ako nema rezultata, izlistaj sve manastire sa ep 1,3,14
echo "\n=== SVI MANASTIRI EPARHIJA 1,3,14 ===\n";
Monastery::whereIn('eparchy_id', [1, 3, 14])->orderBy('eparchy_id')->orderBy('name')->get()->each(function($m) {
    echo "[ID {$m->id}] ep={$m->eparchy_id} {$m->name}\n";
});
