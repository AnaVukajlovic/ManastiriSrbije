<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;

$monasteries = Monastery::where('eparchy_id', 4)->orderBy('id')->get();
$dir = __DIR__ . '/public/images/monasteries';

echo "Eparhija šumadijska - Manastiri i fajlovi na disku:\n";
echo "===================================================\n";

$allFilesOnDisk = [];
foreach ($monasteries as $m) {
    echo "\n[ID {$m->id}] {$m->name} (slug: {$m->slug})\n";
    $slug = $m->slug;
    
    // Tražimo sve fajlove na disku koji odgovaraju slug-u ili varijacijama
    $pattern = $slug;
    if ($slug === 'petkovica-rudnicka') $pattern = 'petkovica-stragari';
    if ($slug === 'celije-lajkovac') $pattern = 'celije-lajkovac';
    
    $files = glob("$dir/{$pattern}*.jpg");
    if (empty($files) && $slug !== $pattern) {
        $files = glob("$dir/{$slug}*.jpg");
    }
    
    // Dodajemo i proveru za specifične nazive
    foreach ($files as $f) {
        $rel = 'images/monasteries/' . basename($f);
        $size = filesize($f);
        echo "  - " . basename($f) . " (" . round($size/1024, 1) . " KB)\n";
        $allFilesOnDisk[$m->id][] = $rel;
    }
}
