<?php
$pdo = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
$valjevska = $pdo->query('SELECT id, name, slug, eparchy_id FROM monasteries WHERE eparchy_id = 13 ORDER BY id')->fetchAll(PDO::FETCH_ASSOC);
print_r($valjevska);
