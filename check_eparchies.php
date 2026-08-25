<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Eparchy;
use App\Models\Monastery;

$eparchies = Eparchy::orderBy('id')->get();
foreach ($eparchies as $e) {
    $count = Monastery::where('eparchy_id', $e->id)->count();
    echo "Eparhija [ID {$e->id}] {$e->name} ({$e->slug}) - Broj manastira: {$count}\n";
}

echo "\nTražim manastire Visoki Dečani, Gračanica, Pećka Patrijaršija, Banjska, Sopoćani, Devič itd:\n";
$names = ['decani', 'gracanica', 'pecka', 'banjska', 'sopocani', 'devic', 'djurdjevi-stupovi', 'prizren', 'zociste', 'draganac', 'sokolica', 'crna-reka', 'gorioc', 'budisavci'];
foreach ($names as $slug) {
    $m = Monastery::where('slug', 'like', "%$slug%")->first();
    if ($m) {
        $ep = Eparchy::find($m->eparchy_id);
        echo "Manastir: {$m->name} [ID {$m->id}] (Slug: {$m->slug}) -> Eparhija: {$ep->name} [ID {$m->eparchy_id}]\n";
    } else {
        echo "Manastir sa slugom '{$slug}' NIJE pronađen!\n";
    }
}
