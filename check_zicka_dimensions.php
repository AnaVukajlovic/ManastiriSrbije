<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$files = glob(public_path('images/monasteries/*.*'));
$images_info = [];

// List of all slugs for Zicka eparchy
$slugs = [
    'blagovestenje-ovcar', 'dubrava', 'godovik', 'gradac', 'ilinje-ovcar',
    'isposnica-svetog-save', 'jezevica', 'jovanje-ovcar-kablar', 'klisura',
    'kovilje', 'moravci', 'nikolje-ovcar-kablar', 'nova-pavlica',
    'preobrazenje-ovcar-kablar', 'pridvorica', 'raca', 'rujan', 'sabor',
    'savinac', 'sretenje', 'stara-pavlica', 'stubal', 'studenica',
    'sveta-trojica-ovcar', 'trnava', 'uspenje-kablar', 'uvac', 'vavedenje-ovcar',
    'vaznesenje-ovcar', 'voljavca-bresnica', 'vracevsnica', 'vujan',
    'zgodacica', 'zica', 'stjenik'
];

foreach ($files as $f) {
    $bn = basename($f);
    foreach ($slugs as $s) {
        if (strpos($bn, $s) === 0) {
            $sz = filesize($f);
            $md5 = md5_file($f);
            $dim = @getimagesize($f);
            $dimStr = $dim ? "{$dim[0]}x{$dim[1]}" : "unknown";
            echo sprintf("%-35s | %10d B | %10s | %s\n", $bn, $sz, $dimStr, $md5);
            break;
        }
    }
}
