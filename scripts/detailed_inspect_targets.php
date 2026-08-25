<?php
require dirname(__DIR__) . '/vendor/autoload.php';
$app = require_once dirname(__DIR__) . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;
use App\Models\MonasteryImage;

$targetIds = [
    210 => 'Ilinje',
    212 => 'Ježevica',
    163 => 'Jovanja (Valjevska)',
    213 => 'Jovanje (Ovčar-Kablar)',
    218 => 'Nova Pavlica',
    219 => 'Preobraženje',
    223 => 'Sabor',
    224 => 'Savinac',
    225 => 'Sretenje',
    254 => 'Stjenik',
    206 => 'Blagoveštenje',
    228 => 'Studenica',
    227 => 'Stubal',
    232 => 'Uvac',
    229 => 'Dučalovići (Sveta Trojica)',
    233 => 'Vavedenje',
    231 => 'Uspenje',
    234 => 'Vaznesenje',
    236 => 'Vraćevšnica'
];

$output = [];
foreach ($targetIds as $id => $label) {
    $m = Monastery::find($id);
    if (!$m) {
        $output[$id] = "NOT FOUND IN DB";
        continue;
    }
    $imgs = MonasteryImage::where('monastery_id', $m->id)->orderBy('sort_order')->get();
    $output[$id] = [
        'id' => $m->id,
        'label' => $label,
        'name' => $m->name,
        'slug' => $m->slug,
        'eparchy_id' => $m->eparchy_id,
        'card_image' => $m->image_url,
        'card_exists' => file_exists(dirname(__DIR__) . '/public/' . ltrim($m->image_url, '/')),
        'images' => $imgs->map(function($img) {
            $path = dirname(__DIR__) . '/public/' . ltrim($img->url, '/');
            return [
                'id' => $img->id,
                'sort_order' => $img->sort_order,
                'url' => $img->url,
                'caption' => $img->caption,
                'exists' => file_exists($path),
                'size' => file_exists($path) ? filesize($path) : 0
            ];
        })->toArray()
    ];
}

echo json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
file_put_contents(dirname(__DIR__) . '/scratch_inspect/detailed_targets.json', json_encode($output, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
