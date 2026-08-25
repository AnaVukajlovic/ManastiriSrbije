<?php
/**
 * fix_remaining_captions.php
 * Čisti zaostale <br><small> tagove i standardizuje format izvora na *Izvor: ...*
 * za preostale slike Mislođina i Trojeručice.
 */
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\MonasteryImage;

// Sve slike u manastirima ep=3 koje imaju stari format
$ids = [10431, 10433, 10449]; // mislodjin_gal_1, mislodjin_gal_3, trojerucica card

foreach ($ids as $id) {
    $img = MonasteryImage::find($id);
    if (!$img) { echo "NE POSTOJI: $id\n"; continue; }

    $caption = $img->caption ?? '';
    // Ukloni sve <br><small...>...</small> tagove
    $base = preg_replace('/<br\s*\/?>?\s*<small[^>]*>.*?<\/small>/is', '', $caption);
    $base = preg_replace('/\s+/', ' ', $base);
    $base = trim($base);

    // Odredi izvor prema veličini fajla
    $path = public_path($img->url);
    $source = (file_exists($path) && filesize($path) > 150*1024) ? 'commons.wikimedia.org' : 'manastiri.rs';

    $newCaption = $base . ' *Izvor: ' . $source . '*';

    if ($newCaption !== $img->caption) {
        $img->caption = $newCaption;
        $img->save();
        echo "[UPDATED] ID $id: $img->url\n";
        echo "   → $newCaption\n";
    } else {
        echo "[SKIP] ID $id vec ima ispravan format\n";
    }
}

echo "\nGotovo.\n";
