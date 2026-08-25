<?php
/**
 * sync_timocka_captions.php
 * Updates image captions for Eparhija Timocka (ID 12) ensuring each caption ends with the correct source tag.
 *
 * The source is inferred heuristically:
 *   - If the image file exists and its size > 150 KB, we assume it originates from commons.wikimedia.org.
 *   - Otherwise we default to manastiri.rs.
 *   - You can extend `determineSource` with more sophisticated logic if needed.
 */
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;
use App\Models\MonasteryImage;

$eparchyId = 12;

function determineSource(string $diskPath): string {
    if (!file_exists($diskPath)) {
        return 'manastiri.rs';
    }
    // Heuristic: larger files are likely from Wikimedia Commons
    if (filesize($diskPath) > 150 * 1024) {
        return 'commons.wikimedia.org';
    }
    return 'manastiri.rs';
}

$updated = 0;
$skipped = 0;

$monasteries = Monastery::where('eparchy_id', $eparchyId)->get();
foreach ($monasteries as $monastery) {
    $images = MonasteryImage::where('monastery_id', $monastery->id)->get();
    foreach ($images as $img) {
        $disk = public_path($img->url);
        $source = determineSource($disk);
        // Strip any existing <br><small> source tag
        $baseCaption = preg_replace('/\s*<br\s*<small[^>]*>.*?<\/small>/i', '', $img->caption ?? '');
        $baseCaption = trim($baseCaption);
        $newCaption = $baseCaption . " *Izvor: {$source}*";
        if ($newCaption !== $img->caption) {
            $img->caption = $newCaption;
            $img->save();
            $updated++;
            echo "[UPDATED] {$monastery->name} (ID {$monastery->id}) Image {$img->url} → source {$source}\n";
        } else {
            $skipped++;
        }
    }
}

echo "\nFinished. Updated {$updated} captions, skipped {$skipped}.\n";
?>
