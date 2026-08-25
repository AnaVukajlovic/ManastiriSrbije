<?php

require_once __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use Illuminate\Support\Facades\DB;

$monasteries = [
    // 1. Ilinje Ovčar-Kablar (ID 210)
    [
        'id' => 210,
        'image_url' => 'images/monasteries/ilinje-ovcar.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/ilinje-ovcar.jpg',
                'caption' => 'Crkva Svetog proroka Ilije na travnatom uzvišenju sa cvetnom portom podno Kablara <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/ilinje-ovcar_gal_1.jpg',
                'caption' => 'Crkva Svetog Ilije u rano proleće okružena šumom i padinama Ovčarsko-kablarske klisure <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
        ],
    ],
    // 1b. Ilinje Šabačka (ID 198) - just in case ensure clean URL and gallery
    [
        'id' => 198,
        'image_url' => 'images/monasteries/ilinje-sabacka.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/ilinje-sabacka.jpg',
                'caption' => 'Manastirski kompleks Ilinje u Očagama kod Bogatića sa crkvom Svetog proroka Ilije <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/ilinje-sabacka_gal_1.jpg',
                'caption' => 'Crkva Svetog proroka Ilije sa zvonikom i cvetnom portom u manastiru Ilinje <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
        ],
    ],
    // 2. Ježevica (ID 212)
    [
        'id' => 212,
        'image_url' => 'images/monasteries/jezevica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/jezevica.jpg',
                'caption' => 'Crkva Svetog Nikole u manastiru Ježevica sa kamenom baroknom zvonarom u porti <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/jezevica_gal_1.jpg',
                'caption' => 'Freska Svetog cara Konstantina i carice Jelene u naosu crkve manastira Ježevica <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/jezevica_gal_2.jpg',
                'caption' => 'Rukopisno Jevanđelje manastira Ježevica sa raskošnim drvorezom Rođenja Hristovog <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
        ],
    ],
    // 3. Jovanje Ovčar-Kablar (ID 213)
    [
        'id' => 213,
        'image_url' => 'images/monasteries/jovanje-ovcar-kablar.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/jovanje-ovcar-kablar.jpg',
                'caption' => 'Crkva Rođenja Svetog Jovana Krstitelja i manastirski konak u Ovčarsko-kablarskoj klisuri <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/jovanje-ovcar-kablar_gal_1.jpg',
                'caption' => 'Kameni vidikovac sa časnim krstom na steni iznad manastira Jovanje <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/jovanje-ovcar-kablar_gal_2.jpg',
                'caption' => 'Kamena ulazna kapija sa zvonarom i lučnim prolazom na ulazu u manastirsku portu <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
        ],
    ],
    // 3b. Jovanja Valjevska (ID 163)
    [
        'id' => 163,
        'image_url' => 'images/monasteries/jovanja.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/jovanja.jpg',
                'caption' => 'Crkva Rođenja Svetog Jovana Krstitelja u manastiru Jovanja kod Valjeva <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/jovanja_gal_1.jpg',
                'caption' => 'Manastirska crkva sa drvenom nadstrešnicom i zvonarom među drvećem <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/jovanja_gal_2.jpg',
                'caption' => 'Oltarska apsida i krovna konstrukcija crkve manastira Jovanja Valjevska <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
        ],
    ],
    // 4. Nova Pavlica (ID 218)
    [
        'id' => 218,
        'image_url' => 'images/monasteries/nova-pavlica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/nova-pavlica.jpg',
                'caption' => 'Crkva Vavedenja Presvete Bogorodice, zadužbina braće Musića kod Raške <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/nova-pavlica_gal_1.jpg',
                'caption' => 'Ktitorska freska vlastelina Stefana i Lazara Musića u naosu crkve Nove Pavlice <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/nova-pavlica_gal_2.jpg',
                'caption' => 'Panoramski pogled na manastir Nova Pavlica i dolinu reke Ibar podno Kopaonika <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
        ],
    ],
    // 5. Preobraženje (ID 219)
    [
        'id' => 219,
        'image_url' => 'images/monasteries/preobrazenje-ovcar-kablar.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/preobrazenje-ovcar-kablar.jpg',
                'caption' => 'Crkva Preobraženja Gospodnjeg sa osmostranom kupolom pod liticama Ovčara <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/preobrazenje-ovcar-kablar_gal_1.jpg',
                'caption' => 'Zasebna zidana zvonara manastira Preobraženje okružena gustom šumom <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/preobrazenje-ovcar-kablar_gal_2.jpg',
                'caption' => 'Unutrašnjost hrama Preobraženja Gospodnjeg sa nalonjem i ikonama <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
        ],
    ],
    // 6. Sabor / Bela Crkva Karanska (ID 223)
    [
        'id' => 223,
        'image_url' => 'images/monasteries/sabor.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sabor.jpg',
                'caption' => 'Crkva od opeke i kamena sa drvenom zvonarom među borovima u manastiru Sabor <small>*(Izvor: manastirisrbije.com)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/sabor_gal_1.jpg',
                'caption' => 'Ulazna drvena kapija sa prilaznom stazom i manastirskim krovovima u pozadini <small>*(Izvor: manastirisrbije.com)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/sabor_gal_2.jpg',
                'caption' => 'Mermerna tabla sa natpisom „Manastir Sabor Srpskih Svetitelja” na kapiji manastira <small>*(Izvor: manastirisrbije.com)*</small>',
                'sort_order' => 3,
            ],
        ],
    ],
    // 7. Savinac (ID 224)
    [
        'id' => 224,
        'image_url' => 'images/monasteries/savinac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/savinac.jpg',
                'caption' => 'Crkva Svetog Save na Savincu, zadužbina kneza Miloša Obrenovića od klesanog kamena <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/savinac_gal_1.jpg',
                'caption' => 'Kompleks manastira sa crkvom, drvenom zvonarom i mauzolejom porodice Vukomanović <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/savinac_gal_2.jpg',
                'caption' => 'Ikonostas i unutrašnjost crkve Svetog Save obasjani svetlošću kroz prozore kupole <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
        ],
    ],
    // 8. Sretenje (ID 225)
    [
        'id' => 225,
        'image_url' => 'images/monasteries/sretenje-ovcar-kablar.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sretenje-ovcar-kablar.jpg',
                'caption' => 'Manastirski kompleks Sretenje sa crkvom i konacima visoko na padinama Ovčara <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/sretenje-ovcar-kablar_gal_1.jpg',
                'caption' => 'Beli zvonik crkve Sretenja Gospodnjeg sa pozlaćenim krstom podno stena Ovčara <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/sretenje-ovcar-kablar_gal_2.jpg',
                'caption' => 'Kovani metalni krst na kamenom postolju kraj starih nadgrobnih spomenika u porti <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
        ],
    ],
    // 9. Stjenik (ID 254)
    [
        'id' => 254,
        'image_url' => 'images/monasteries/stjenik.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/stjenik.jpg',
                'caption' => 'Manastirski kompleks Stjenik sa crkvom, drvenom zvonarom i konakom u šumovitoj uvali planine Jelice <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/stjenik_gal_1.jpg',
                'caption' => 'Crkva Rođenja Svetog Jovana Krstitelja u Stjeniku pokrivena tradicionalnim kamenim pločama <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
        ],
    ],
    // 10. Blagoveštenje (ID 206)
    [
        'id' => 206,
        'image_url' => 'images/monasteries/blagovestenje.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/blagovestenje.jpg',
                'caption' => 'Crkva Blagoveštenja Presvete Bogorodice sa drvenom zvonarom u Ovčarsko-kablarskoj klisuri <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/blagovestenje_gal_1.jpg',
                'caption' => 'Kamena fasada crkve sa krovom od drvene šindre i osmostranom kupolom <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/blagovestenje_gal_2.jpg',
                'caption' => 'Drvena konstrukcija zvonika sa crkvenim zvonima u manastiru Blagoveštenje <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
        ],
    ],
    // 11. Studenica (ID 228)
    [
        'id' => 228,
        'image_url' => 'images/monasteries/studenica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/studenica.jpg',
                'caption' => 'Bogorodičina crkva u manastiru Studenica, zadužbina Stefana Nemanje građena od belog mermera <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/studenica_gal_1.jpg',
                'caption' => 'Crkva Svetog Nikole (Nikoljača) i manastirski konak sa lučnim arkadama <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/studenica_gal_2.jpg',
                'caption' => 'Čuvena freska Studeničke Bogorodice sa Hristom Mladencom na prestolu <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/studenica_gal_3.jpg',
                'caption' => 'Monumentalna freska Raspeća Hristovog (Studeničko raspeće) iz 1209. godine <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 4,
            ],
        ],
    ],
    // 12. Stubal (ID 227)
    [
        'id' => 227,
        'image_url' => 'images/monasteries/stubal.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/stubal.jpg',
                'caption' => 'Crkva Svete Petke u manastiru Stubal sa visokim zvonikom i lepo uređenom cvetnom portom <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/stubal_gal_1.jpg',
                'caption' => 'Kivot sa vezenim pokrovom i ikonom Prepodobne mati Paraskeve (Svete Petke) <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/stubal_gal_2.jpg',
                'caption' => 'Sveti kamen u kapeli manastira Stubal sa ikonama i kandilima <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
        ],
    ],
    // 13. Uvac (ID 232)
    [
        'id' => 232,
        'image_url' => 'images/monasteries/uvac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/uvac.jpg',
                'caption' => 'Crkva Rođenja Presvete Bogorodice u manastiru Uvac građena od kamena sa šindrom <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/uvac_gal_1.jpg',
                'caption' => 'Pogled iz vazduha na manastirski kompleks Uvac smešten u dolini reke Uvac <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/uvac_gal_2.jpg',
                'caption' => 'Oltarska apsida crkve i kameni manastirski bedem sa vinovom lozom <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
        ],
    ],
    // 14. Dučalovići / Sveta Trojica (ID 229)
    [
        'id' => 229,
        'image_url' => 'images/monasteries/sveta-trojica-ducalovici.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sveta-trojica-ducalovici.jpg',
                'caption' => 'Crkva Svete Trojice u Dučalovićima na jugozapadnim padinama planine Ovčar <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/sveta-trojica-ducalovici_gal_1.jpg',
                'caption' => 'Tradicionalni manastirski konak okružen cvetnim lejama i gustom šumom <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/sveta-trojica-ducalovici_gal_2.jpg',
                'caption' => 'Kameni profilisani portal crkve sa freskom Presvete Bogorodice u luneti <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
        ],
    ],
    // 15. Vavedenje (ID 233)
    [
        'id' => 233,
        'image_url' => 'images/monasteries/vavedenje.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/vavedenje.jpg',
                'caption' => 'Crkva Vavedenja Presvete Bogorodice sa zvonikom i konakom na ulazu u Ovčarsko-kablarsku klisuru <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/vavedenje_gal_1.jpg',
                'caption' => 'Raskošno rezbareni i pozlaćeni ikonostas sa prestonim ikonama u crkvi Vavedenja <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/vavedenje_gal_2.jpg',
                'caption' => 'Unutrašnjost hrama Vavedenja sa centralnim polijelejem i ikonama <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
        ],
    ],
    // 16. Uspenje (ID 231)
    [
        'id' => 231,
        'image_url' => 'images/monasteries/uspenje-ovcar-kablar.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/uspenje-ovcar-kablar.jpg',
                'caption' => 'Crkva Uspenja Presvete Bogorodice sa tremom i mermernim platoom podno Kablara <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/uspenje-ovcar-kablar_gal_1.jpg',
                'caption' => 'Ostaci srednjovekovne kule motrilje Gradina sa krstom na steni iznad manastira <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/uspenje-ovcar-kablar_gal_2.jpg',
                'caption' => 'Kamena ulazna kapija manastira sa klesanim rozetama i metalnim krstom <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
        ],
    ],
    // 17. Vaznesenje (ID 234)
    [
        'id' => 234,
        'image_url' => 'images/monasteries/vaznesenje-ovcar-kablar.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/vaznesenje-ovcar-kablar.jpg',
                'caption' => 'Crkva Vaznesenja Gospodnjeg sa zvonikom i monaškim grobljem na severnim padinama Ovčara <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/vaznesenje-ovcar-kablar_gal_2.jpg',
                'caption' => 'Manastirski zvonik od tesanog kamena i opeke sa konakom i ulaznom kapijom <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
        ],
    ],
    // 18. Vraćevšnica (ID 236)
    [
        'id' => 236,
        'image_url' => 'images/monasteries/vracevsnica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/vracevsnica.jpg',
                'caption' => 'Manastirski kompleks Vraćevšnica sa visokom belom zvonarom, konacima i obzidom podno Rudnika <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/vracevsnica_gal_1.jpg',
                'caption' => 'Ktitorska freska u crkvi Svetog Đorđa: Veliki čelnik Radič Postupović sa Svetim Đorđem prinosi model hrama <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/vracevsnica_gal_2.jpg',
                'caption' => 'Crkva Svetog Đorđa od tesanog kamena sa slepim arkadama i vitkom kupolom <small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
        ],
    ],
];

$publicDir = public_path();
$errors = [];

// Pre-check all file paths
foreach ($monasteries as $m) {
    $cardPath = $publicDir . '/' . $m['image_url'];
    if (!file_exists($cardPath)) {
        $errors[] = "Monastery ID {$m['id']} card image missing: {$m['image_url']}";
    }
    foreach ($m['images'] as $img) {
        $imgPath = $publicDir . '/' . $img['url'];
        if (!file_exists($imgPath)) {
            $errors[] = "Monastery ID {$m['id']} gallery image missing: {$img['url']}";
        }
    }
}

if (!empty($errors)) {
    echo "ERRORS FOUND:\n" . implode("\n", $errors) . "\n";
    exit(1);
}

echo "All images verified on disk! Applying DB updates in a transaction...\n";

DB::transaction(function () use ($monasteries) {
    foreach ($monasteries as $m) {
        // Update card image
        DB::table('monasteries')
            ->where('id', $m['id'])
            ->update([
                'image_url' => $m['image_url'],
                'updated_at' => now(),
            ]);

        // Delete existing gallery images
        DB::table('monastery_images')
            ->where('monastery_id', $m['id'])
            ->delete();

        // Insert new gallery images
        foreach ($m['images'] as $img) {
            DB::table('monastery_images')->insert([
                'monastery_id' => $m['id'],
                'url' => $img['url'],
                'caption' => $img['caption'],
                'sort_order' => $img['sort_order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        echo "Updated Monastery ID {$m['id']} successfully.\n";
    }
});

echo "ALL MONASTERIES UPDATED ATOMICALLY AND SUCCESSFULLY!\n";
