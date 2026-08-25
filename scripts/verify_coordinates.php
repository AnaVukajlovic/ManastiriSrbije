<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Monastery;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;

$issues = [];
foreach (Monastery::all() as $m) {
    $id = $m->id;
    $name = $m->name;
    $lat = $m->latitude;
    $lon = $m->longitude;
    if (empty($lat) || empty($lon)) {
        $issues[] = "[MISSING] ID {$id} – {$name} (slug: {$m->slug}) has no coordinates";
        continue;
    }
    // Reverse‑geocode via Nominatim with User-Agent per policy
    $url = "https://nominatim.openstreetmap.org/reverse?format=json&lat=".urlencode($lat)."&lon=".urlencode($lon)."&zoom=14&addressdetails=1";
    $response = Http::withHeaders([
        'User-Agent' => 'ManastiriSrbijeApp/1.0 (+admin@example.com)'
    ])->timeout(10)->get($url);
    if (!$response->ok()) {
        $issues[] = "[ERROR] ID {$id} – {$name}: Nominatim request failed (status {$response->status()})";
        continue;
    }
    $data = $response->json();
    if (!isset($data['display_name'])) {
        $issues[] = "[ERROR] ID {$id} – {$name}: unexpected Nominatim response";
        continue;
    }
    $display = $data['display_name'];
    if (!Str::contains(Str::lower($display), Str::lower($name))) {
        $issues[] = "[MISMATCH] ID {$id} – {$name}: coordinates point to '{$display}' which does not contain the monastery name";
    }
    usleep(200000); // 0.2 s pause per policy
}

if (empty($issues)) {
    echo "All monastery coordinates appear correct according to Nominatim.\n";
} else {
    echo "Coordinate verification report:\n";
    foreach ($issues as $line) {
        echo $line."\n";
    }
}
?>
