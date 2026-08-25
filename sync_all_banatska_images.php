<?php

$baseDir = __DIR__;
$dbPaths = [
    $baseDir . '/storage/database.sqlite',
    $baseDir . '/database/database.sqlite'
];

$galleryData = [
    // 1. Bavanište
    1 => [
        ['url' => '/images/monasteries/bavaniste.jpg', 'caption' => 'Pogled na manastirsku crkvu Rođenja Presvete Bogorodice i portu sa cvećem i zelenilom - Izvor: commons.wikimedia.org', 'sort_order' => 1],
        ['url' => '/images/monasteries/bavaniste_gal_1.jpg', 'caption' => 'Manastirska crkva sa oltarskom apsidom, kupolom i fontanom u porti - Izvor: Zvanični sajt Eparhije banatske', 'sort_order' => 2],
        ['url' => '/images/monasteries/bavaniste_gal_2.jpg', 'caption' => 'Zidana ulazna kapija manastira sa mozaikom Rođenja Presvete Bogorodice i krstom - Izvor: commons.wikimedia.org', 'sort_order' => 3],
        ['url' => '/images/monasteries/bavaniste_gal_3.jpg', 'caption' => 'Drveni manastirski zvonik sa zvonima, ogradom i konakom u pozadini - Izvor: commons.wikimedia.org', 'sort_order' => 4],
    ],
    // 2. Gaj
    2 => [
        ['url' => '/images/monasteries/gaj.jpg', 'caption' => 'Pogled na manastirsku crkvu Vaznesenja Gospodnjeg sa baroknim zvonikom i tremom - Izvor: commons.wikimedia.org', 'sort_order' => 1],
        ['url' => '/images/monasteries/gaj_gal_1.jpg', 'caption' => 'Barokni zvonik manastirske crkve sa satom i dekorativnom kapom - Izvor: commons.wikimedia.org', 'sort_order' => 2],
        ['url' => '/images/monasteries/gaj_gal_2.jpg', 'caption' => 'Pogled na crkvu kroz drvored i kapiju porte - Izvor: Zvanični sajt Eparhije banatske', 'sort_order' => 3],
        ['url' => '/images/monasteries/gaj_gal_3.jpg', 'caption' => 'Mermerni spomen-krst i natpis uzidani u fasadu crkve - Izvor: commons.wikimedia.org', 'sort_order' => 4],
    ],
    // 3. Hajdučica
    3 => [
        ['url' => '/images/monasteries/hajducica.jpg', 'caption' => 'Pogled na manastirsku crkvu Svetih Arhanđela kroz kovanu ulaznu kapiju - Izvor: Zvanični sajt Eparhije banatske', 'sort_order' => 1],
        ['url' => '/images/monasteries/hajducica_gal_1.jpg', 'caption' => 'Manastirski kompleks sa zidanom ogradom, ulazom i portom - Izvor: commons.wikimedia.org', 'sort_order' => 2],
        ['url' => '/images/monasteries/hajducica_gal_2.jpg', 'caption' => 'Manastirski konak sa lučnim prozorima i zvonikom - Izvor: Zvanični sajt Eparhije banatske', 'sort_order' => 3],
    ],
    // 4. Mesić
    4 => [
        ['url' => '/images/monasteries/mesic.jpg', 'caption' => 'Panorama manastirskog kompleksa Mesić sa kamenom crkvom, baroknim zvonikom i konakom - Izvor: commons.wikimedia.org', 'sort_order' => 1],
        ['url' => '/images/monasteries/mesic_gal_1.jpg', 'caption' => 'Zapadna fasada manastirske crkve sa pripratom, baroknim zvonikom i kamenim naosom - Izvor: commons.wikimedia.org', 'sort_order' => 2],
        ['url' => '/images/monasteries/mesic_gal_2.jpg', 'caption' => 'Enterijer naosa sa srednjovekovnim freskama na stubovima i drvorezbarskim ikonostasom - Izvor: commons.wikimedia.org', 'sort_order' => 3],
        ['url' => '/images/monasteries/mesic_gal_3.jpg', 'caption' => 'Manastirski konak sa kamenim stepeništem, cvećem i popločanim dvorištem - Izvor: commons.wikimedia.org', 'sort_order' => 4],
    ],
    // 5. Središte
    5 => [
        ['url' => '/images/monasteries/srediste.jpg', 'caption' => 'Pogled odozgo na crkvu manastira Središte od opeke u moravsko-vizantijskom stilu - Izvor: Zvanični sajt Eparhije banatske', 'sort_order' => 1],
        ['url' => '/images/monasteries/srediste_gal_1.jpg', 'caption' => 'Monumentalna ulazna kula sa lučnom kapijom manastirskog kompleksa Središte - Izvor: commons.wikimedia.org', 'sort_order' => 2],
        ['url' => '/images/monasteries/srediste_gal_2.jpg', 'caption' => 'Višespratni manastirski konak sa paraklisom i kupolom - Izvor: commons.wikimedia.org', 'sort_order' => 3],
        ['url' => '/images/monasteries/srediste_gal_3.jpg', 'caption' => 'Kupola manastirskog hrama i zaseban zvonik okruženi šumom Vršačkih planina - Izvor: commons.wikimedia.org', 'sort_order' => 4],
    ],
    // 6. Sveta Trojica Kikinda
    6 => [
        ['url' => '/images/monasteries/sveta_trojica_kikinda.jpg', 'caption' => 'Prednja fasada crkve Svete Trojice sa mozaicima anđela, natpisima i tornjem - Izvor: commons.wikimedia.org', 'sort_order' => 1],
        ['url' => '/images/monasteries/sveta_trojica_kikinda_gal_1.jpg', 'caption' => 'Oltarska apsida crkve i manastirski konak u pozadini - Izvor: commons.wikimedia.org', 'sort_order' => 2],
        ['url' => '/images/monasteries/sveta_trojica_kikinda_gal_2.jpg', 'caption' => 'Barokni zvonik manastirske crkve sa bakarnom kapom i krstom - Izvor: commons.wikimedia.org', 'sort_order' => 3],
        ['url' => '/images/monasteries/sveta_trojica_kikinda_gal_3.jpg', 'caption' => 'Pogled na zapadni portal crkve kroz drveće porte - Izvor: commons.wikimedia.org', 'sort_order' => 4],
    ],
    // 7. Svete Melanije
    7 => [
        ['url' => '/images/monasteries/svete_melanije.jpg', 'caption' => 'Pogled na manastirsku crkvu Svete Melanije sa osmougaonom kupolom, tremom i vrtom - Izvor: Zvanični sajt Eparhije banatske', 'sort_order' => 1],
        ['url' => '/images/monasteries/svete_melanije_gal_1.jpg', 'caption' => 'Rezbaren drveni ikonostas sa carskim dverima, ikonama i polijelejem - Izvor: Zvanični sajt Eparhije banatske', 'sort_order' => 2],
        ['url' => '/images/monasteries/svete_melanije_gal_2.jpg', 'caption' => 'Renoviran manastirski konak sa popločanim stazama i dvorištem - Izvor: Zvanični sajt Eparhije banatske', 'sort_order' => 3],
        ['url' => '/images/monasteries/svete_melanije_gal_3.jpg', 'caption' => 'Portret prve igumanije manastira Svete Melanije, mati Petronije - Izvor: Zvanični sajt Eparhije banatske', 'sort_order' => 4],
    ],
    // 8. Vlajkovac
    8 => [
        ['url' => '/images/monasteries/vlajkovac.jpg', 'caption' => 'Pogled na manastirsku crkvu Svetih apostola Petra i Pavla sa zvonikom i oslikanim nišama - Izvor: commons.wikimedia.org', 'sort_order' => 1],
        ['url' => '/images/monasteries/vlajkovac_gal_1.jpg', 'caption' => 'Zapadna fasada crkve sa oslikanim nišama, zvonikom i ogradom porte - Izvor: Zvanični sajt Eparhije banatske', 'sort_order' => 2],
        ['url' => '/images/monasteries/vlajkovac_gal_2.jpg', 'caption' => 'Južna bočna strana naosa i krov manastirske crkve - Izvor: commons.wikimedia.org', 'sort_order' => 3],
        ['url' => '/images/monasteries/vlajkovac_gal_3.jpg', 'caption' => 'Fasadna freska Svetog velikomučenika Dimitrija na zapadnom zidu crkve - Izvor: commons.wikimedia.org', 'sort_order' => 4],
    ],
    // 9. Vojlovica
    9 => [
        ['url' => '/images/monasteries/vojlovica.jpg', 'caption' => 'Zapadna fasada crkve manastira Vojlovica sa baroknim zvonikom, portalom i ikonama - Izvor: commons.wikimedia.org', 'sort_order' => 1],
        ['url' => '/images/monasteries/vojlovica_gal_1.jpg', 'caption' => 'Raskošni barokni pozlaćeni ikonostas manastirske crkve sa polijelejem i celivajućom ikonom - Izvor: commons.wikimedia.org', 'sort_order' => 2],
        ['url' => '/images/monasteries/vojlovica_gal_2.jpg', 'caption' => 'Monumentalni spratni konak manastira Vojlovica sa parkom u porti - Izvor: Zvanični sajt Eparhije banatske', 'sort_order' => 3],
        ['url' => '/images/monasteries/vojlovica_gal_3.jpg', 'caption' => 'Spomen-stub sa krstom i mozaikom Svetog Arhangela Gavrila na prilazu manastiru - Izvor: commons.wikimedia.org', 'sort_order' => 4],
    ],
];

foreach ($dbPaths as $dbPath) {
    if (!file_exists($dbPath)) {
        continue;
    }
    
    echo "Updating database at: $dbPath\n";
    $pdo = new PDO("sqlite:$dbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    foreach ($galleryData as $monasteryId => $images) {
        $del = $pdo->prepare("DELETE FROM monastery_images WHERE monastery_id = ?");
        $del->execute([$monasteryId]);
        
        $ins = $pdo->prepare("INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (?, ?, ?, ?, datetime('now'), datetime('now'))");
        foreach ($images as $img) {
            $ins->execute([$monasteryId, $img['url'], $img['caption'], $img['sort_order']]);
        }
        echo "  [OK] Updated monastery #{$monasteryId} with " . count($images) . " images (including card image at #1)\n";
    }
}

echo "All databases updated successfully!\n";
