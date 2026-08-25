<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;
use App\Models\MonasteryImage;

$ids = [15, 19, 20]; // Mislođin, Slanci, Trojeručica

foreach ($ids as $id) {
    $m = Monastery::find($id);
    echo "\n=== {$m->name} (ID {$m->id}, ep={$m->eparchy_id}) ===\n";
    echo "Card image: {$m->image_url}\n";
    $imgs = MonasteryImage::where('monastery_id', $m->id)->orderBy('sort_order')->get();
    echo "Broj slika u galeriji: " . $imgs->count() . "\n";
    foreach ($imgs as $img) {
        $path = public_path($img->url);
        $size = file_exists($path) ? round(filesize($path)/1024) . ' KB' : 'NE POSTOJI';
        $hash = file_exists($path) ? md5_file($path) : 'N/A';
        echo "  [ID {$img->id}] {$img->url}\n";
        echo "     sort={$img->sort_order} | {$size} | hash={$hash}\n";
        echo "     caption: " . ($img->caption ?? '(prazan)') . "\n";
    }
}
