<?php
require dirname(__DIR__) . '/vendor/autoload.php';
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;
use App\Models\MonasteryImage;
use App\Models\Eparchy;

$targets = [
    'Ilinje / Klinje / Ailinje' => Monastery::where('name', 'like', '%ilinj%')->orWhere('slug', 'like', '%ilinj%')->orWhere('slug', 'like', '%alinj%')->orWhere('slug', 'like', '%klinj%')->get(),
    'Ježevica' => Monastery::where('name', 'like', '%jezevic%')->orWhere('name', 'like', '%ježevic%')->get(),
    'Jovanje' => Monastery::where('name', 'like', '%jovanj%')->get(),
    'Nova Pavlica / Pavlica' => Monastery::where('name', 'like', '%pavlic%')->get(),
    'Preobraženje' => Monastery::where('name', 'like', '%preobrazenj%')->orWhere('name', 'like', '%preobraženj%')->get(),
    'Sabor' => Monastery::where('name', 'like', '%sabor%')->get(),
    'Savinac' => Monastery::where('name', 'like', '%savinac%')->get(),
    'Sretenje' => Monastery::where('name', 'like', '%sretenj%')->get(),
    'Stjenik' => Monastery::where('name', 'like', '%stjenik%')->orWhere('name', 'like', '%sjtenik%')->get(),
    'Blagoveštenje' => Monastery::where('name', 'like', '%blagovest%')->orWhere('name', 'like', '%blagovešt%')->get(),
    'Studenica' => Monastery::where('name', 'like', '%studenic%')->get(),
    'Stubal' => Monastery::where('name', 'like', '%stubal%')->get(),
    'Uvac' => Monastery::where('name', 'like', '%uvac%')->get(),
    'Dučalovići / Sveta Trojica' => Monastery::where('name', 'like', '%ducalovic%')->orWhere('name', 'like', '%dučalovic%')->orWhere('slug', 'like', '%ducalovic%')->get(),
    'Vavedenje' => Monastery::where('name', 'like', '%vavedenj%')->get(),
    'Uspenje' => Monastery::where('name', 'like', '%uspenj%')->get(),
    'Vaznesenje' => Monastery::where('name', 'like', '%vaznesenj%')->get(),
    'Vraćevšnica' => Monastery::where('name', 'like', '%vracevsn%')->orWhere('name', 'like', '%vraćevšn%')->get(),
];

$out = "";
foreach ($targets as $key => $list) {
    $out .= "======================================================================\n";
    $out .= "=== TARGET GROUP: {$key} (Found: " . $list->count() . ") ===\n";
    $out .= "======================================================================\n";
    foreach ($list as $m) {
        $epName = $m->eparchy ? $m->eparchy->name : "Eparhija {$m->eparchy_id}";
        $out .= "ID: {$m->id} | Name: '{$m->name}' | Slug: '{$m->slug}' | Eparchy: {$epName} (ID: {$m->eparchy_id})\n";
        $out .= "  Card URL: '{$m->image_url}'\n";
        $imgs = MonasteryImage::where('monastery_id', $m->id)->orderBy('sort_order')->get();
        $out .= "  Images count: " . $imgs->count() . "\n";
        foreach ($imgs as $idx => $img) {
            $num = $idx + 1;
            $out .= "    #{$num} [ID: {$img->id}, Order: {$img->sort_order}]: '{$img->url}'\n";
            $out .= "       Caption: '{$img->caption}'\n";
        }
    }
    $out .= "\n";
}

file_put_contents(dirname(__DIR__) . '/scratch_inspect/target_monasteries_dump.txt', $out);
echo $out;
