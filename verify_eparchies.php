<?php

/**
 * DETALJNA PROVERA PRIKAZA MANASTIRA, KARTICA, GALERIJA I OPISA
 * Za Eparhije: Žička (1), Raško-prizrenska (2), Šumadijska (4), Šabačka (15)
 */

use App\Models\Monastery;
use App\Models\MonasteryImage;
use App\Models\Eparchy;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$eparchyIds = range(1, 15);

echo "====================================================================\n";
echo "POKRETANJE DUBINSKE KONTROLE KARTICA, GALERIJA I OPISA\n";
echo "====================================================================\n\n";

$totalMonasteries = 0;
$totalGalleryImages = 0;
$issues = [];

foreach ($eparchyIds as $epId) {
    $eparchy = Eparchy::find($epId);
    $epName = $eparchy ? $eparchy->name : "Eparhija ID {$epId}";
    
    echo "--------------------------------------------------------------------\n";
    echo "EPARHIJA: {$epName} (ID: {$epId})\n";
    echo "--------------------------------------------------------------------\n";

    $monasteries = Monastery::where('eparchy_id', $epId)->with('images')->get();

    foreach ($monasteries as $m) {
        $totalMonasteries++;
        $mIssues = [];

        // 1. Provera naslovne / card slike
        $cardImgPath = public_path($m->image_url ?? '');
        $cardImgRel = $m->image_url;
        
        if (empty($cardImgRel)) {
            $mIssues[] = "NEDOSTAJE CARD SLIKA (image_url je prazan)";
        } elseif (!file_exists($cardImgPath)) {
            $mIssues[] = "CARD SLIKA NE POSTOJI NA DISKU: {$cardImgRel}";
        } else {
            $size = filesize($cardImgPath);
            if ($size < 100) {
                $mIssues[] = "CARD SLIKA JE KORUMPIRANA/PRAZNA ({$size} bajtova): {$cardImgRel}";
            }
        }

        // 2. Provera opisa
        $descLen = mb_strlen(trim($m->description ?? ''));
        if ($descLen === 0) {
            $mIssues[] = "OPIS JE PRAZAN";
        }

        // 3. Provera galerijskih slika
        $images = $m->images;
        $imgCount = $images->count();
        $totalGalleryImages += $imgCount;

        $seenUrls = [];
        $seenHashes = [];

        foreach ($images as $img) {
            $url = $img->url;
            $diskPath = public_path($url);

            if (empty($url)) {
                $mIssues[] = "Galerijska slika [ID {$img->id}] ima prazan URL";
                continue;
            }

            if (!file_exists($diskPath)) {
                $mIssues[] = "Galerijska slika NE POSTOJI na disku: {$url}";
                continue;
            }

            // Provera duplikata po URL-u
            if (isset($seenUrls[$url])) {
                $mIssues[] = "DUPLIKAT URL u galeriji: {$url}";
            }
            $seenUrls[$url] = true;

            // Provera duplikata po hešu sadržaja fajla
            $hash = md5_file($diskPath);
            if (isset($seenHashes[$hash])) {
                $mIssues[] = "DUPLIKAT FIZIČKOG FAJLA (isti sadržaj): {$url} == {$seenHashes[$hash]}";
            }
            $seenHashes[$hash] = $url;

            // Provera opisa / caption-a
            $caption = $img->caption ?? '';
            if (empty($caption)) {
                $mIssues[] = "Galerijska slika {$url} nema caption / opis";
            } else {
                // Provera izvora u caption-u
                if (!preg_match('/\(Izvor:\s*[^)]+\)/i', $caption)) {
                    $mIssues[] = "Caption za {$url} nema definisan validan (Izvor: ...)";
                }
            }
        }

        if (empty($mIssues)) {
            echo "  [✓ ISPRAVNO] [ID {$m->id}] {$m->name} | Card: {$cardImgRel} | Galerija: {$imgCount} slika | Opis: {$descLen} karaktera\n";
        } else {
            echo "  [⚠ PROBLEM] [ID {$m->id}] {$m->name}:\n";
            foreach ($mIssues as $iss) {
                echo "      - {$iss}\n";
                $issues[] = "[{$m->id} - {$m->name}] {$iss}";
            }
        }
    }
    echo "\n";
}

echo "====================================================================\n";
echo "REZIME REVIZIJE:\n";
echo "Ukupno provereno manastira: {$totalMonasteries}\n";
echo "Ukupno provereno galerijskih slika: {$totalGalleryImages}\n";
echo "Ukupno pronađenih grešaka/problema: " . count($issues) . "\n";
if (empty($issues)) {
    echo ">>> SVE JE 100% ČISTO, BEZ DUPLIKATA, BEZ NEDOSTUPNIH SLIKA I SA ISPRAVNIM OPISIMA! <<<\n";
} else {
    echo ">>> PRONAĐENI PROBLEMI KOJE TREBA ISPRAVITI: <<<\n";
    foreach ($issues as $i) {
        echo "  * {$i}\n";
    }
}
echo "====================================================================\n";
