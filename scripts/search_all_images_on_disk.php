<?php
require dirname(__DIR__) . '/vendor/autoload.php';

$searchTerms = [
    'ilinje', 'jezevic', 'jovanj', 'pavlic', 'preobrazen', 'preobražen',
    'sabor', 'savinac', 'sreten', 'stjenik', 'blagovest', 'studenic',
    'stubal', 'uvac', 'ducalovic', 'trojic', 'vaveden', 'uspenj', 'vaznesen', 'vracevsn'
];

$scanDirs = [
    'public/images/monasteries' => dirname(__DIR__) . '/public/images/monasteries',
    'public/images' => dirname(__DIR__) . '/public/images',
    'skladiste' => dirname(__DIR__) . '/skladiste',
    'storage' => dirname(__DIR__) . '/storage'
];

foreach ($searchTerms as $term) {
    echo "========================================================\n";
    echo "SEARCH FOR: {$term}\n";
    foreach ($scanDirs as $dirName => $dirPath) {
        if (!is_dir($dirPath)) continue;
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dirPath));
        foreach ($files as $file) {
            if ($file->isDir()) continue;
            $filename = $file->getFilename();
            if (stripos($filename, $term) !== false) {
                echo "  [{$dirName}] " . $file->getPathname() . " (" . $file->getSize() . " bytes)\n";
            }
        }
    }
}
