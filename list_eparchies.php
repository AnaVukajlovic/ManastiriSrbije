<?php
$pdo = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
$eparchies = $pdo->query('SELECT id, name, slug FROM eparchies ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);

foreach ($eparchies as $ep) {
    $count = $pdo->query("SELECT COUNT(*) FROM monasteries WHERE eparchy_id = {$ep['id']}")->fetchColumn();
    echo "ID: {$ep['id']} | {$ep['name']} ({$ep['slug']}) - $count monasteries\n";
}
