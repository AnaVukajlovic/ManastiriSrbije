<?php

$dir = __DIR__ . '/public/images/monasteries';
$files = scandir($dir);

$keywords = [
    'banjska', 'budisavci', 'ceranjska', 'crna-reka', 'devine-vode', 'devic', 'draganac',
    'pecka', 'prizren', 'sokolica', 'sopocani', 'socanica', 'tamnica', 'ulije', 'velika-hoca',
    'visoki-decani', 'decani', 'zociste', 'djakovica', 'djurdjevi', 'duboki-potok', 'gorioc',
    'gracanica', 'koncul', 'vracevo', 'tusimlja', 'bogorodica-ljeviska', 'ljeviska', 'arhangeli'
];

$matched = [];
foreach ($files as $f) {
    if ($f === '.' || $f === '..') continue;
    foreach ($keywords as $kw) {
        if (stripos($f, $kw) !== false) {
            $size = filesize("$dir/$f");
            $matched[] = sprintf("%-40s | %8.1f KB", $f, $size / 1024);
            break;
        }
    }
}

sort($matched);
echo "Pronađeno " . count($matched) . " fajlova slika za Eparhiju raško-prizrensku:\n";
foreach ($matched as $m) {
    echo $m . "\n";
}
