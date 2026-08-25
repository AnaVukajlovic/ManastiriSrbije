<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;

$monasteries = Monastery::with('images')->where('eparchy_id', 2)->orderBy('id')->get();

echo "Ukupno manastira u Eparhiji raško-prizrenskoj (eparchy_id 2): " . $monasteries->count() . "\n\n";

foreach ($monasteries as $m) {
    echo "ID: {$m->id} | {$m->name} | slug: {$m->slug} | card: {$m->image_url} | gallery count: " . $m->images->count() . "\n";
}
