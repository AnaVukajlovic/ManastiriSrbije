<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;
use App\Models\MonasteryImage;

$keywords = ['misolodjin', 'slanci', 'trojeruci'];

foreach ($keywords as $kw) {
    $m = Monastery::where('name', 'LIKE', "%{$kw}%")->first();
    if (!$m) {
        echo "NE NADJENO: {$kw}\n";
        continue;
    }
    echo "\n=== {$m->name} (ID {$m->id}, eparchy_id={$m->eparchy_id}) ===\n";
    echo "Card image: {$m->image_url}\n";
    $imgs = MonasteryImage::where('monastery_id', $m->id)->orderBy('sort_order')->get();
    echo "Broj slika u galeriji: " . $imgs->count() . "\n";
    foreach ($imgs as $img) {
        $path = public_path($img->url);
        $size = file_exists($path) ? round(filesize($path)/1024) . ' KB' : 'NE POSTOJI';
        $hash = file_exists($path) ? md5_file($path) : 'N/A';
        echo "  [ID {$img->id}] {$img->url} | sort={$img->sort_order} | {$size} | hash={$hash}\n";
        echo "      caption: " . substr($img->caption ?? '', 0, 150) . "\n";
    }
}
