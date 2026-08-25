<?php
require dirname(__DIR__) . '/vendor/autoload.php';
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;
use App\Models\MonasteryImage;
use App\Models\Eparchy;

$allMonasteries = Monastery::with('eparchy')->orderBy('id')->get();
echo "Total monasteries in DB: " . $allMonasteries->count() . PHP_EOL;

$keywords = [
    'ilinje', 'jezevica', 'jovanje', 'jovanja', 'pavlica', 'preobrazenje', 'sabor', 
    'savinac', 'sretenje', 'stjenik', 'blagovestenje', 'studenica', 'stubal', 
    'uvac', 'ducalovici', 'trojica', 'vavedenje', 'uspenje', 'vaznesenje', 'vracevsnica'
];

foreach ($allMonasteries as $m) {
    $match = false;
    foreach ($keywords as $kw) {
        if (stripos($m->name, $kw) !== false || stripos($m->slug, $kw) !== false) {
            $match = true;
            break;
        }
    }
    if ($match) {
        $epName = $m->eparchy ? $m->eparchy->name : "Eparchy {$m->eparchy_id}";
        echo "ID: {$m->id} | {$m->name} | Slug: {$m->slug} | Eparchy: {$epName} (ID: {$m->eparchy_id})\n";
    }
}
