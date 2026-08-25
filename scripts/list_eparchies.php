<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Eparchy;

$eparchies = Eparchy::all(['id','name','slug'])->toArray();
foreach ($eparchies as $e) {
    echo $e['id'] . "\t" . $e['name'] . "\t" . $e['slug'] . "\n";
}
?>
