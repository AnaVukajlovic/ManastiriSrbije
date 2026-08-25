<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Ktitor;

$ktitors = Ktitor::with(['images', 'manastiri'])->get();
echo "Testing " . $ktitors->count() . " ktitors render:\n";

foreach ($ktitors as $k) {
    try {
        $html = view('pages.ktitors.show', ['ktitor' => $k])->render();
        $imagesCount = $k->images()->count();
        echo "  [OK] {$k->slug} ({$imagesCount} slika) - " . strlen($html) . " bytes\n";
    } catch (\Throwable $e) {
        echo "  [FAIL] {$k->slug}: " . $e->getMessage() . "\n";
    }
}
echo "Done testing!\n";
