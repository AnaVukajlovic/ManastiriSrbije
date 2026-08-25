<?php
/**
 * scripts/validate_monasteries.php
 *
 * Command-line script to validate and fix monastery data:
 *   - Ensure image is valid or assign placeholder.
 *   - Remove unrelated images.
 *   - Validate or fetch coordinates.
 *   - Ensure slug exists.
 *   - Enrich missing description/excerpt from Wikipedia.
 */

require __DIR__ . '/../vendor/autoload.php';

use App\Models\Monastery;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

// Initialize Laravel application (for standalone script)
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$logPath = storage_path('logs/monastery_validation.log');
$log = fopen($logPath, 'a');
function logMsg(string $msg) {
    global $log;
    $timestamp = date('c');
    fwrite($log, "[$timestamp] $msg\n");
}


$publicImagesPath = public_path('images/monasteries');

$allMonasteries = Monastery::all();

$processed = 0;
$updated = 0;

foreach ($allMonasteries as $monastery) {
    $processed++;
    $needsSave = false;
    $slug = $monastery->slug ?? Str::slug($monastery->name);
    if (empty($monastery->slug) && !empty($slug)) {
        $monastery->slug = $slug;
        $needsSave = true;
        logMsg("Generated slug for monastery ID {$monastery->id}: {$slug}");
    }

    // ---------- Image validation ----------
    $imageUrl = $monastery->image_url;
    if ($imageUrl) {
        if (str_starts_with($imageUrl, 'http://') || str_starts_with($imageUrl, 'https://')) {
            // remote URL, assume ok
        } else {
            $clean = ltrim($imageUrl, '/');
            $fullPath = public_path($clean);
            if (!file_exists($fullPath)) {
                $monastery->image_url = null;
                $needsSave = true;
                logMsg("Removed broken local image reference for monastery ID {$monastery->id}");
            }
        }
    }

    // Use default slug image if missing
    if (empty($monastery->image_url)) {
        $candidate = "$publicImagesPath/{$slug}.jpg";
        if (file_exists($candidate)) {
            $monastery->image_url = "images/monasteries/{$slug}.jpg";
            $needsSave = true;
            logMsg("Assigned slug image for monastery ID {$monastery->id}");
        } else {
            $monastery->image_url = null; // placeholder will be used
            $needsSave = true;
            logMsg("No image found for monastery ID {$monastery->id}; will use placeholder");
        }
    }

    // ---------- Remove unrelated images ----------
    $files = glob("$publicImagesPath/*.jpg");
    foreach ($files as $file) {
        $basename = basename($file, '.jpg');
        if (!Monastery::where('slug', $basename)->exists()) {
            unlink($file);
            logMsg("Deleted unrelated image file: $file");
        }
    }

    // ---------- Coordinate validation ----------
    if (empty($monastery->latitude) || empty($monastery->longitude)) {
        $fetched = false;
        if (!empty($monastery->coord_url)) {
            $response = @file_get_contents($monastery->coord_url);
            if ($response !== false) {
                $data = json_decode($response, true);
                if (isset($data['lat']) && isset($data['lng'])) {
                    $monastery->latitude = $data['lat'];
                    $monastery->longitude = $data['lng'];
                    $monastery->coord_source = 'coord_url';
                    $needsSave = true;
                    $fetched = true;
                    logMsg("Fetched coordinates from coord_url for monastery ID {$monastery->id}");
                }
            }
        }
        if (!$fetched && !empty($monastery->address)) {
            $search = urlencode($monastery->address . ' ' . $monastery->name);
            $geoUrl = "https://nominatim.openstreetmap.org/search?q={$search}&format=json&limit=1";
            $geoResp = @file_get_contents($geoUrl);
            if ($geoResp) {
                $geoData = json_decode($geoResp, true);
                if (!empty($geoData[0]['lat']) && !empty($geoData[0]['lon'])) {
                    $monastery->latitude = $geoData[0]['lat'];
                    $monastery->longitude = $geoData[0]['lon'];
                    $monastery->coord_source = 'nominatim';
                    $needsSave = true;
                    logMsg("Geocoded coordinates via Nominatim for monastery ID {$monastery->id}");
                }
            }
        }
    }

    // ---------- Text enrichment ----------
    if (empty($monastery->description) || empty($monastery->excerpt)) {
        $title = urlencode($monastery->name);
        $wikiApi = "https://en.wikipedia.org/api/rest_v1/page/summary/{$title}";
        $wikiResp = @file_get_contents($wikiApi);
        if ($wikiResp) {
            $wikiData = json_decode($wikiResp, true);
            if (isset($wikiData['extract'])) {
                $monastery->description = $wikiData['extract'];
                $monastery->excerpt = Str::limit($wikiData['extract'], 200);
                $monastery->source = 'wikipedia';
                $needsSave = true;
                logMsg("Fetched description from Wikipedia for monastery ID {$monastery->id}");
            }
        }
    }

    if ($needsSave) {
        $monastery->save();
        $updated++;
    }
}

logMsg("Monastery validation completed. Processed: $processed, Updated: $updated");
fclose($log);

echo "Validation finished. See log at $logPath\n";
?>
