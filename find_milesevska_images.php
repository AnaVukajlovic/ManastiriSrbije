<?php
$slugs = ['bistrica', 'davidovica', 'jabuka', 'janja', 'kumanica', 'mazici', 'mileseva', 'pribojska-banja', 'seljani', 'vodena-poljana'];
$dir = __DIR__ . '/public/images/monasteries/';

foreach ($slugs as $slug) {
    echo "=== $slug ===\n";
    $files = glob($dir . $slug . '*');
    foreach ($files as $f) {
        echo "  " . basename($f) . " (" . filesize($f) . " bytes)\n";
    }
}
