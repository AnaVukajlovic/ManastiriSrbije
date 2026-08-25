<?php
$dir = __DIR__ . '/public/images/monasteries';
$files = scandir($dir);

echo "Total files in public/images/monasteries: " . count($files) . "\n\n";

$slugs = [
    'blagovestenje', 'dubrava', 'godovik', 'gradac', 'ilinje', 'isposnica',
    'jezevica', 'jovanje', 'klisura', 'kovilje', 'moravci', 'nikolje',
    'nova-pavlica', 'pavlica', 'preobrazenje', 'pridvorica', 'raca',
    'rujan', 'sabor', 'savinac', 'sretenje', 'stara-pavlica', 'stubal',
    'studenica', 'sveta-trojica', 'ducalovici', 'trnava', 'uspenje',
    'uvac', 'vavedenje', 'vaznesenje', 'voljavca', 'vracevsnica',
    'vujan', 'zgodacica', 'zica', 'stjenik'
];

$matchedFiles = [];

foreach ($files as $f) {
    if ($f === '.' || $f === '..') continue;
    foreach ($slugs as $slug) {
        if (str_contains($f, $slug)) {
            $matchedFiles[$slug][] = $f;
            break;
        }
    }
}

foreach ($matchedFiles as $slug => $fileList) {
    echo "[$slug]:\n";
    foreach ($fileList as $f) {
        echo "  - $f (" . filesize("$dir/$f") . " bytes)\n";
    }
}
