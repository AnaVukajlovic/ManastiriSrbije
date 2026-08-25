<?php
$pdo = new PDO('sqlite:database/database.sqlite');
$monasteries = $pdo->query("SELECT id, name, slug, image_url FROM monasteries WHERE eparchy_id = 12 ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);

foreach ($monasteries as $m) {
    echo "ID {$m['id']}: {$m['name']} (slug: {$m['slug']})\n";
    $images = $pdo->query("SELECT id, url, caption, sort_order FROM monastery_images WHERE monastery_id = {$m['id']} ORDER BY sort_order")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($images as $img) {
        echo "   - [{$img['sort_order']}] {$img['url']}\n";
    }
}
