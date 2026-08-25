<?php
$pdo = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
$m = $pdo->query('SELECT * FROM monasteries WHERE id = 71')->fetch(PDO::FETCH_ASSOC);
print_r($m);

$valjevskaPustinja = $pdo->query("SELECT * FROM monasteries WHERE name LIKE '%Pustinja%'")->fetchAll(PDO::FETCH_ASSOC);
print_r($valjevskaPustinja);
