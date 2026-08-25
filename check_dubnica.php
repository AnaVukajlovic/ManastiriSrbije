<?php
$pdo = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
$stmt2 = $pdo->query("SELECT id, name, slug, eparchy_id, city, region, description FROM monasteries WHERE name LIKE '%Dubnica%'");
while($r = $stmt2->fetch(PDO::FETCH_ASSOC)) {
    echo "ID: {$r['id']} | Name: {$r['name']} | Slug: {$r['slug']} | Eparchy: {$r['eparchy_id']} | City: {$r['city']} | Region: {$r['region']}\n";
    echo "Desc start: " . substr($r['description'], 0, 150) . "...\n\n";
}
