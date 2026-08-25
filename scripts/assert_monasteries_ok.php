<?php

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$targetIds = [210, 198, 212, 213, 163, 218, 219, 223, 224, 225, 254, 206, 228, 227, 232, 229, 233, 231, 234, 236];
$public = public_path();
$err = 0;

foreach ($targetIds as $id) {
    $m = DB::table('monasteries')->find($id);
    if (!$m) {
        echo "Missing monastery $id\n";
        $err++;
        continue;
    }
    if (!file_exists($public . '/' . $m->image_url)) {
        echo "[FAIL] Mon $id ({$m->name}) card image missing: {$m->image_url}\n";
        $err++;
    }
    $imgs = DB::table('monastery_images')->where('monastery_id', $id)->get();
    if ($imgs->isEmpty()) {
        echo "[FAIL] Mon $id ({$m->name}) has no gallery images\n";
        $err++;
    }
    foreach ($imgs as $img) {
        if (!file_exists($public . '/' . $img->url)) {
            echo "[FAIL] Mon $id ({$m->name}) gallery img missing: {$img->url}\n";
            $err++;
        }
        if (empty($img->caption) || mb_strlen($img->caption) < 10) {
            echo "[FAIL] Mon $id ({$m->name}) invalid caption: {$img->caption}\n";
            $err++;
        }
    }
}

if ($err === 0) {
    echo "SUCCESS: All target monasteries have valid, verified card images and gallery images with full descriptions!\n";
} else {
    echo "TOTAL ERRORS: $err\n";
}
