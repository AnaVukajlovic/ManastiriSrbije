<?php
$searchTerms = [
    'ilinje', 'jezevic', 'jovanj', 'pavlic', 'preobrazen', 'preobražen',
    'sabor', 'savinac', 'sreten', 'stjenik', 'blagovest', 'studenic',
    'stubal', 'uvac', 'ducalovic', 'trojic', 'vaveden', 'uspenj', 'vaznesen', 'vracevsn', 'dubnica'
];

$dirPath = dirname(__DIR__) . '/public/images/monasteries';
$files = scandir($dirPath);

$results = [];
foreach ($files as $f) {
    if ($f === '.' || $f === '..') continue;
    foreach ($searchTerms as $term) {
        if (stripos($f, $term) !== false) {
            $results[$term][] = [
                'filename' => $f,
                'path' => $dirPath . '/' . $f,
                'size' => filesize($dirPath . '/' . $f)
            ];
        }
    }
}

$out = "";
foreach ($results as $term => $list) {
    $out .= "=== TERM: {$term} ===\n";
    foreach ($list as $item) {
        $out .= "  - {$item['filename']} ({$item['size']} bytes)\n";
    }
}

file_put_contents(dirname(__DIR__) . '/scratch_inspect/matched_files_on_disk.txt', $out);
echo $out;
