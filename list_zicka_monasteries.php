<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Eparchy;
use App\Models\Monastery;

$zicka = Eparchy::where('slug', 'like', '%zick%')
    ->orWhere('name', 'like', '%žičk%')
    ->orWhere('name', 'like', '%zick%')
    ->first();

echo "Eparchy ID: {$zicka->id} | Name: {$zicka->name}\n\n";

$monasteries = Monastery::where('eparchy_id', $zicka->id)->orderBy('id')->get();
echo "Total monasteries: " . $monasteries->count() . "\n\n";

foreach ($monasteries as $idx => $m) {
    echo ($idx + 1) . ". ID: {$m->id} | Slug: {$m->slug} | Name: {$m->name} | Card: {$m->image_url}\n";
}
