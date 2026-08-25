<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$images = [
    // Mislođin
    'images/monasteries/mislodjin.jpg',
    'images/monasteries/mislodjin_gal_1.jpg',
    'images/monasteries/mislodjin_gal_2.jpg',
    'images/monasteries/mislodjin_gal_3.jpg',
    // Rajinovac
    'images/monasteries/rajinovac.jpg',
    'images/monasteries/rajinovac_gal_1.jpg',
    'images/monasteries/rajinovac_gal_2.jpg',
    'images/monasteries/rajinovac_gal_3.jpg',
    // Rakovica
    'images/monasteries/rakovica.jpg',
    'images/monasteries/rakovica_gal_1.jpg',
    'images/monasteries/rakovica_gal_2.jpg',
    'images/monasteries/rakovica_gal_3.jpg',
    // Senjak
    'images/monasteries/senjak.jpg',
    'images/monasteries/senjak_gal_1.jpg',
    'images/monasteries/senjak_gal_2.jpg',
    'images/monasteries/senjak_gal_3.jpg',
    // Slanci
    'images/monasteries/slanci.jpg',
    'images/monasteries/slanci_gal_1.jpg',
    'images/monasteries/slanci_gal_2.jpg',
    // Trojeručica
    'images/monasteries/trojerucica.jpg',
    'images/monasteries/trojerucica_gal_1.jpg',
    'images/monasteries/trojerucica_gal_2.jpg',
];

foreach ($images as $img) {
    $full = public_path($img);
    if (file_exists($full)) {
        $info = getimagesize($full);
        $dim = $info ? "{$info[0]}x{$info[1]}" : "dim-unknown";
        $size = round(filesize($full) / 1024) . " KB";
        $hash = md5_file($full);
        echo "[POSTOJI] {$img} | {$dim} | {$size} | md5: {$hash}\n";
    } else {
        echo "[NE POSTOJI] {$img}\n";
    }
}
