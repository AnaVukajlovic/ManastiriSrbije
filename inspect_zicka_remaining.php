<?php
$db = new PDO('sqlite:database/database.sqlite');
$stmt = $db->query('SELECT * FROM monasteries WHERE id = 235');
print_r($stmt->fetch(PDO::FETCH_ASSOC));

$stmt2 = $db->query("SELECT id, name, slug, eparchy_id, image_url FROM monasteries WHERE name LIKE '%Voljav%'");
print_r($stmt2->fetchAll(PDO::FETCH_ASSOC));
