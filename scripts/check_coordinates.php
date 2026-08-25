<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Monastery;

$missing = [];
foreach (Monastery::all() as $m) {
    if (empty($m->latitude) || empty($m->longitude)) {
        $missing[] = [$m->id, $m->name, $m->slug];
    }
}

if (empty($missing)) {
    echo "All monasteries have latitude and longitude set.\n";
} else {
    echo "Monasteries missing coordinates:\n";
    foreach ($missing as $row) {
        echo "ID {$row[0]} - {$row[1]} (slug: {$row[2]})\n";
    }
}
?>
