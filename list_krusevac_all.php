<?php
$db = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
$stmt = $db->prepare("SELECT id, name, slug FROM monasteries WHERE eparchy_id = 9 ORDER BY id");
$stmt->execute();
$monasteries = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($monasteries as $idx => $m) {
    $num = $idx + 1;
    echo "{$num}. ID {$m['id']}: {$m['name']} (slug: {$m['slug']})\n";
    $imgStmt = $db->prepare("SELECT url, caption, sort_order FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order");
    $imgStmt->execute([$m['id']]);
    $imgs = $imgStmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($imgs as $img) {
        echo "   - {$img['url']}\n";
    }
}
