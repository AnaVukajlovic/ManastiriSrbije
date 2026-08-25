<?php
/**
 * scripts/bacak_summary.php
 *
 * Generates a summary of data completeness for monasteries in the Bačka eparchy.
 */

require __DIR__ . '/../vendor/autoload.php';

use App\Models\Eparchy;
use Illuminate\Support\Str;

// Bootstrap Laravel application
$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Find Bačka eparchy by slug (ASCII) or name
$eparchy = Eparchy::where('slug', 'eparhija-backa')
    ->orWhere('name', 'LIKE', "%bačka%")
    ->first();
if (!$eparchy) {
    echo "Bačka eparchy not found in the database.\n";
    exit(1);
}

$monasteries = $eparchy->monasteries()->get();
$total = $monasteries->count();
$missingImage = 0;
$missingCoords = 0;
$missingDesc = 0;

foreach ($monasteries as $m) {
    // Image check: existing URL or file in public/images/monasteries or placeholder
    $hasImage = false;
    if (!empty($m->image_url)) {
        $hasImage = true;
    } else {
        $candidate = public_path('images/monasteries/' . $m->slug . '.jpg');
        if (file_exists($candidate) || file_exists(public_path('images/monasteries/placeholder.jpg')) ) {
            $hasImage = true;
        }
    }
    if (!$hasImage) {
        $missingImage++;
    }
    if (empty($m->latitude) || empty($m->longitude)) {
        $missingCoords++;
    }
    if (empty($m->description) && empty($m->excerpt)) {
        $missingDesc++;
    }
}

echo "Bačka Eparchy Summary:\n";
echo "Total monasteries: $total\n";
echo "Missing image: $missingImage\n";
echo "Missing coordinates: $missingCoords\n";
echo "Missing description/excerpt: $missingDesc\n";
?>
