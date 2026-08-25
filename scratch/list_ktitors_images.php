<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$ktitors = App\Models\Ktitor::with('images')->get();
foreach ($ktitors as $k) {
    echo "ID: {$k->id} | Slug: {$k->slug} | Name: {$k->name}\n";
    foreach ($k->images as $img) {
        echo "  - Img ID: {$img->id} | sort: {$img->sort} | path: {$img->path} | caption: {$img->caption}\n";
    }
}
