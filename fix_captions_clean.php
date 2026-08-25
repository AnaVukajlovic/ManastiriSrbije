<?php
/**
 * fix_captions_clean.php
 * Agresivno čisti sve HTML tagove i zaostale artefakte iz caption-a,
 * pa dodaje ispravan format *Izvor: ...*
 */
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\MonasteryImage;

$ids = [10430, 10431, 10433, 10449, 16099];

foreach ($ids as $id) {
    $img = MonasteryImage::find($id);
    if (!$img) { echo "NE POSTOJI: $id\n"; continue; }

    $caption = $img->caption ?? '';

    // Ukloni sve HTML tagove
    $base = strip_tags($caption);
    // Ukloni zaostale * znakove koji su deo starih tagova (ali ne i *Izvor: ...*  koji dolazi posle)
    $base = preg_replace('/\*Izvor:[^*]+\*/', '', $base);
    // Ukloni višestruke razmake
    $base = preg_replace('/\s+/', ' ', $base);
    $base = trim($base, " \t\n\r*");

    // Odredi izvor prema veličini fajla
    $path = public_path($img->url);
    $source = (file_exists($path) && filesize($path) > 150*1024) ? 'commons.wikimedia.org' : 'manastiri.rs';

    $newCaption = $base . ' *Izvor: ' . $source . '*';

    $img->caption = $newCaption;
    $img->save();
    echo "[UPDATED] ID $id ({$img->url})\n";
    echo "   caption: $newCaption\n\n";
}

echo "Gotovo.\n";
