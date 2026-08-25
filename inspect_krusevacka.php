<?php
$db = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
$stmt = $db->prepare("SELECT id, name, slug, image_url, image FROM monasteries WHERE eparchy_id = 9 ORDER BY id");
$stmt->execute();
$monasteries = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($monasteries as $m) {
    echo "ID {$m['id']}: {$m['name']} (slug: {$m['slug']})\n";
    $imgStmt = $db->prepare("SELECT url, caption, sort_order FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order");
    $imgStmt->execute([$m['id']]);
    $imgs = $imgStmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($imgs)) {
        echo "  (No images in monastery_images table; main image_url: {$m['image_url']})\n";
    } else {
        foreach ($imgs as $img) {
            echo "  - [order {$img['sort_order']}] {$img['url']}\n";
            echo "    caption: {$img['caption']}\n";
        }
    }
}
