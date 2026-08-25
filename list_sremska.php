<?php

$pdo = new PDO('sqlite:database/database.sqlite');
$stmt = $pdo->query("SELECT id, name, slug FROM monasteries WHERE eparchy_id = 11 ORDER BY id ASC");
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo "Total monasteries in Eparhija sremska: " . count($rows) . "\n\n";
foreach ($rows as $i => $r) {
    echo ($i + 1) . ". ID {$r['id']}: {$r['name']} (slug: {$r['slug']})\n";
}
