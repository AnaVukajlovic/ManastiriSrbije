<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$eparchies = App\Models\Eparchy::with('monasteries')->get();

foreach ($eparchies as $eparchy) {
    echo "=== {$eparchy->name} (ID: {$eparchy->id}) ===" . PHP_EOL;
    echo "Monasteries: " . $eparchy->monasteries->pluck('name')->implode(', ') . PHP_EOL;
    echo "Count: " . $eparchy->monasteries->count() . PHP_EOL;
    echo PHP_EOL;
}
