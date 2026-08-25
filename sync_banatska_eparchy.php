<?php

$dbPaths = [
    __DIR__ . '/storage/database.sqlite',
    __DIR__ . '/database/database.sqlite'
];

$monasteries = [
    [
        'id' => 1,
        'name' => 'Баваниште',
        'lat' => 44.827124,
        'lng' => 20.894011,
        'image_url' => '/images/monasteries/bavaniste.jpg',
        'gallery' => [
            ['url' => '/images/monasteries/bavaniste_gal_1.jpg', 'caption' => 'Izvor: Zvanični sajt Eparhije banatske', 'sort_order' => 1],
            ['url' => '/images/monasteries/bavaniste_gal_2.jpg', 'caption' => 'Izvor: commons.wikimedia.org', 'sort_order' => 2],
            ['url' => '/images/monasteries/bavaniste_gal_3.jpg', 'caption' => 'Izvor: commons.wikimedia.org', 'sort_order' => 3],
        ]
    ],
    [
        'id' => 2,
        'name' => 'Гај',
        'lat' => 44.770261,
        'lng' => 21.088950,
        'image_url' => '/images/monasteries/gaj.jpg',
        'gallery' => [
            ['url' => '/images/monasteries/gaj_gal_1.jpg', 'caption' => 'Izvor: commons.wikimedia.org', 'sort_order' => 1],
            ['url' => '/images/monasteries/gaj_gal_2.jpg', 'caption' => 'Izvor: Zvanični sajt Eparhije banatske', 'sort_order' => 2],
            ['url' => '/images/monasteries/gaj_gal_3.jpg', 'caption' => 'Izvor: commons.wikimedia.org', 'sort_order' => 3],
        ]
    ],
    [
        'id' => 3,
        'name' => 'Хајдучица',
        'lat' => 45.253855,
        'lng' => 20.966131,
        'image_url' => '/images/monasteries/hajducica.jpg',
        'gallery' => [
            ['url' => '/images/monasteries/hajducica_gal_1.jpg', 'caption' => 'Izvor: commons.wikimedia.org', 'sort_order' => 1],
            ['url' => '/images/monasteries/hajducica_gal_2.jpg', 'caption' => 'Izvor: Zvanični sajt Eparhije banatske', 'sort_order' => 2],
            ['url' => '/images/monasteries/hajducica_gal_3.jpg', 'caption' => 'Izvor: Zvanični sajt Eparhije banatske', 'sort_order' => 3],
        ]
    ],
    [
        'id' => 4,
        'name' => 'Месић',
        'lat' => 45.104080,
        'lng' => 21.392033,
        'image_url' => '/images/monasteries/mesic.jpg',
        'gallery' => [
            ['url' => '/images/monasteries/mesic_gal_1.jpg', 'caption' => 'Izvor: commons.wikimedia.org', 'sort_order' => 1],
            ['url' => '/images/monasteries/mesic_gal_2.jpg', 'caption' => 'Izvor: commons.wikimedia.org', 'sort_order' => 2],
            ['url' => '/images/monasteries/mesic_gal_3.jpg', 'caption' => 'Izvor: commons.wikimedia.org', 'sort_order' => 3],
        ]
    ],
    [
        'id' => 5,
        'name' => 'Средиште',
        'lat' => 45.144114,
        'lng' => 21.397702,
        'image_url' => '/images/monasteries/srediste.jpg',
        'gallery' => [
            ['url' => '/images/monasteries/srediste_gal_1.jpg', 'caption' => 'Izvor: commons.wikimedia.org', 'sort_order' => 1],
            ['url' => '/images/monasteries/srediste_gal_2.jpg', 'caption' => 'Izvor: commons.wikimedia.org', 'sort_order' => 2],
            ['url' => '/images/monasteries/srediste_gal_3.jpg', 'caption' => 'Izvor: commons.wikimedia.org', 'sort_order' => 3],
        ]
    ],
    [
        'id' => 6,
        'name' => 'Света Тројица Кикинда',
        'lat' => 45.818803,
        'lng' => 20.467467,
        'image_url' => '/images/monasteries/sveta_trojica_kikinda.jpg',
        'gallery' => [
            ['url' => '/images/monasteries/sveta_trojica_kikinda_gal_1.jpg', 'caption' => 'Izvor: commons.wikimedia.org', 'sort_order' => 1],
            ['url' => '/images/monasteries/sveta_trojica_kikinda_gal_2.jpg', 'caption' => 'Izvor: commons.wikimedia.org', 'sort_order' => 2],
            ['url' => '/images/monasteries/sveta_trojica_kikinda_gal_3.jpg', 'caption' => 'Izvor: commons.wikimedia.org', 'sort_order' => 3],
        ]
    ],
    [
        'id' => 7,
        'name' => 'Свете Меланије',
        'lat' => 45.393949,
        'lng' => 20.413013,
        'image_url' => '/images/monasteries/svete_melanije.jpg',
        'gallery' => [
            ['url' => '/images/monasteries/svete_melanije_gal_1.jpg', 'caption' => 'Izvor: Zvanični sajt Eparhije banatske', 'sort_order' => 1],
            ['url' => '/images/monasteries/svete_melanije_gal_2.jpg', 'caption' => 'Izvor: Zvanični sajt Eparhije banatske', 'sort_order' => 2],
            ['url' => '/images/monasteries/svete_melanije_gal_3.jpg', 'caption' => 'Izvor: Zvanični sajt Eparhije banatske', 'sort_order' => 3],
        ]
    ],
    [
        'id' => 8,
        'name' => 'Влајковац',
        'lat' => 45.071323,
        'lng' => 21.199672,
        'image_url' => '/images/monasteries/vlajkovac.jpg',
        'gallery' => [
            ['url' => '/images/monasteries/vlajkovac_gal_1.jpg', 'caption' => 'Izvor: Zvanični sajt Eparhije banatske', 'sort_order' => 1],
            ['url' => '/images/monasteries/vlajkovac_gal_2.jpg', 'caption' => 'Izvor: commons.wikimedia.org', 'sort_order' => 2],
            ['url' => '/images/monasteries/vlajkovac_gal_3.jpg', 'caption' => 'Izvor: commons.wikimedia.org', 'sort_order' => 3],
        ]
    ],
    [
        'id' => 9,
        'name' => 'Војловица',
        'lat' => 44.827955,
        'lng' => 20.684339,
        'image_url' => '/images/monasteries/vojlovica.jpg',
        'gallery' => [
            ['url' => '/images/monasteries/vojlovica_gal_1.jpg', 'caption' => 'Izvor: commons.wikimedia.org', 'sort_order' => 1],
            ['url' => '/images/monasteries/vojlovica_gal_2.jpg', 'caption' => 'Izvor: Zvanični sajt Eparhije banatske', 'sort_order' => 2],
            ['url' => '/images/monasteries/vojlovica_gal_3.jpg', 'caption' => 'Izvor: commons.wikimedia.org', 'sort_order' => 3],
        ]
    ],
];

foreach ($dbPaths as $dbPath) {
    if (!file_exists($dbPath)) {
        echo "Database path not found: $dbPath\n";
        continue;
    }
    
    echo "Updating database at: $dbPath\n";
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    foreach ($monasteries as $m) {
        $stmt = $pdo->prepare("UPDATE monasteries SET image_url = ?, lat = ?, lng = ? WHERE id = ?");
        $stmt->execute([$m['image_url'], $m['lat'], $m['lng'], $m['id']]);
        
        // Remove existing gallery images for this monastery
        $del = $pdo->prepare("DELETE FROM monastery_images WHERE monastery_id = ?");
        $del->execute([$m['id']]);
        
        // Insert new verified gallery images
        $ins = $pdo->prepare("INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))");
        foreach ($m['gallery'] as $img) {
            $ins->execute([$m['id'], $img['url'], $img['caption'], $img['sort_order']]);
        }
        
        echo "  [OK] Updated monastery #{$m['id']} ({$m['name']}) with " . count($m['gallery']) . " gallery images\n";
    }
}

echo "All Banatska Eparhija monasteries synchronized successfully!\n";
