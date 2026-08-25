<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;
use App\Models\Eparchy;

$all = Monastery::orderBy('eparchy_id')->orderBy('id')->get(['id', 'name', 'eparchy_id']);
foreach ($all as $m) {
    if ($m->eparchy_id == 3 || stripos($m->name, 'beograd') !== false || stripos($m->name, 'zemun') !== false || stripos($m->name, 'senjak') !== false) {
        echo "[ID {$m->id}] ep={$m->eparchy_id} {$m->name}\n";
    }
}
