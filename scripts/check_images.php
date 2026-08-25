<?php
$files = glob(dirname(__DIR__) . '/public/images/monasteries/*');
echo "Count of files in public/images/monasteries/: " . count($files) . PHP_EOL;
foreach (array_slice($files, 0, 10) as $f) {
    echo basename($f) . PHP_EOL;
}
