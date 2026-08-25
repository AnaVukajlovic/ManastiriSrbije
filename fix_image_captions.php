<?php
/**
 * fix_image_captions.php
 *
 * Ensures each monastery image caption ends with a proper source tag.
 */
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\MonasteryImage;

function determineSource(string $diskPath): string {
    if (!file_exists($diskPath)) {
        return 'manastiri.rs';
    }
    // Heuristic: large file => commons.wikimedia.org
    if (filesize($diskPath) > 150 * 1024) {
        return 'commons.wikimedia.org';
    }
    return 'manastiri.rs';
}

$updated = 0;
foreach (MonasteryImage::cursor() as $img) {
    $url = $img->url; // e.g., images/monasteries/bresnica.jpg
    $disk = public_path($url);
    $source = determineSource($disk);
    $caption = $img->caption ?? '';
    // Strip any existing source tag
    $caption = preg_replace('/<br\s*<small[^>]*>.*?<\/small>/i', '', $caption);
    $caption = trim($caption);
    $newCaption = $caption . " <br><small style=\"color: #eab308;\"><em>(Izvor: {$source})</em></small>";
    if ($newCaption !== $img->caption) {
        $img->caption = $newCaption;
        $img->save();
        $updated++;
        echo "[UPDATED] ID {$img->id} source {$source}\n";
    }
}

echo "Finished. Updated {$updated} captions.\n";
?>
