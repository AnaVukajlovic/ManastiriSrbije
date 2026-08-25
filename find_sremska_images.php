<?php

$pdo = new PDO('sqlite:database/database.sqlite');
$stmt = $pdo->query("SELECT id, name, slug, image_url FROM monasteries WHERE eparchy_id = 11 ORDER BY id ASC");
$monasteries = $stmt->fetchAll(PDO::FETCH_ASSOC);

$allFiles = scandir(__DIR__ . '/public/images/monasteries');

echo "Total monasteries in Eparhija sremska (ID 11): " . count($monasteries) . "\n\n";

foreach ($monasteries as $m) {
    echo "========================================================\n";
    echo "ID {$m['id']}: {$m['name']} (slug: {$m['slug']})\n";
    
    // Existing monastery_images
    $imgStmt = $pdo->prepare("SELECT url, caption, sort_order FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order ASC");
    $imgStmt->execute([$m['id']]);
    $existing = $imgStmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Existing in DB (" . count($existing) . "):\n";
    foreach ($existing as $e) {
        echo "  - [Order {$e['sort_order']}] {$e['url']} -> {$e['caption']}\n";
    }

    // Matching files in filesystem
    $slugClean = str_replace('-', '_', $m['slug']);
    $matching = [];
    foreach ($allFiles as $f) {
        if ($f === '.' || $f === '..') continue;
        if (strpos($f, $m['slug']) !== false || strpos($f, $slugClean) !== false) {
            $matching[] = $f;
        }
    }
    echo "Matching files on disk (" . count($matching) . "):\n";
    foreach ($matching as $mf) {
        echo "  * $mf\n";
    }
}
