<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;

$vranjskaMonasteries = Monastery::where('eparchy_id', 14)->get();

foreach ($vranjskaMonasteries as $m) {
    echo "====================================================\n";
    echo "ID {$m->id} | {$m->name} (Slug: {$m->slug})\n";
    echo "Ktitor: {$m->ktitor} | Vek: {$m->century} | Posvećen: {$m->dedication} | Lokacija: {$m->location}\n";
    echo "Opis dužina: " . mb_strlen($m->description ?? '') . " karaktera\n";
    echo "Opis ispis (prvih 400 karaktera):\n";
    echo mb_substr($m->description ?? '', 0, 400) . "...\n";
}
