<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;

$ms = Monastery::where('eparchy_id', 1)->orderBy('id')->get();
foreach ($ms as $m) {
    echo "ID: {$m->id} | Name: {$m->name} | Slug: {$m->slug} | Place: {$m->place} | Image: {$m->image_url}\n";
}
