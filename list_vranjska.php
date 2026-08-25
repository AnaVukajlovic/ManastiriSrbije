<?php
$pdo = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
$stmt = $pdo->query('SELECT id, name, slug, image_url FROM monasteries WHERE eparchy_id = 14 ORDER BY id');
while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID {$row['id']} | {$row['name']} | slug: {$row['slug']}\n";
    $imgStmt = $pdo->prepare('SELECT id, url, caption, sort_order FROM monastery_images WHERE monastery_id = ? ORDER BY sort_order, id');
    $imgStmt->execute([$row['id']]);
    $imgs = $imgStmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($imgs as $img) {
        echo "   - [order {$img['sort_order']}] {$img['url']} -> {$img['caption']}\n";
    }
}
