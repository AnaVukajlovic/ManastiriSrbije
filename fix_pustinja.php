<?php
$pdo = new PDO('sqlite:' . __DIR__ . '/database/database.sqlite');
$pdo->exec("UPDATE monasteries SET eparchy_id = 13, 
    description = REPLACE(description, 'Eparhije mileševske', 'Eparhije valjevske'),
    excerpt = REPLACE(excerpt, 'Eparhije mileševske', 'Eparhije valjevske'),
    description_short = REPLACE(description_short, 'Eparhije mileševske', 'Eparhije valjevske')
    WHERE id = 71");
echo "Updated monastery 71 to Eparhija valjevska (ID 13)\n";
