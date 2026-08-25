<?php

/**
 * SISTEMSKO ČIŠĆENJE I SINHRONIZACIJA - EPARHIJA NIŠKA (ID 5)
 * Pravoslavni Svetionik — Master rad
 * Izvor: manastiri.rs / commons.wikimedia.org
 */

use App\Models\Monastery;
use App\Models\MonasteryImage;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "====================================================================\n";
echo "POKRETANJE REVIZIJE I ČIŠĆENJA ZA EPARHIJU NIŠKU (ID 5)\n";
echo "====================================================================\n\n";

// 1. Definicija verifikovanih podataka, tačnih kartičnih slika i usklađenih galerija
$niska_data = [
    // 73: Manastir Ajdanovac
    73 => [
        'name' => 'Manastir Ajdanovac',
        'card_image' => 'images/monasteries/ajdanovac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/ajdanovac.jpg',
                'caption' => 'Manastirska crkva Svetog Georgija u Ajdanovcu, zadužbina s kraja XIV veka podno Jastrepca *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/ajdanovac_gal_1.jpg',
                'caption' => 'Pogled na manastirski kompleks i crkvu Svetog Georgija u prirodnom okruženju *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/ajdanovac_gal_2.jpg',
                'caption' => 'Južna strana crkve sa konacima i uređenom manastirskom portom *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
        ]
    ],

    // 74: Manastir Babičko
    74 => [
        'name' => 'Manastir Babičko',
        'card_image' => 'images/monasteries/babicko_gal_1.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/babicko_gal_1.jpg',
                'caption' => 'Manastirska crkva Uspenja Presvete Bogorodice na šumovitim padinama Babičke gore *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/babicko.jpg',
                'caption' => 'Pogled na crkvu Uspenja Presvete Bogorodice sa manastirskim zvonikom *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
        ]
    ],

    // 75: Manastir Bazovik
    75 => [
        'name' => 'Manastir Bazovik',
        'card_image' => 'images/monasteries/bazovik.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/bazovik.jpg',
                'caption' => 'Manastirska crkva Svetog Jovana Krstitelja u Bazoviku kod Pirota *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/bazovik_gal_1.jpg',
                'caption' => 'Pogled na hram Svetog Jovana Krstitelja i manastirski zvonik *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/bazovik_gal_2.jpg',
                'caption' => 'Južna strana crkve sa karakterističnom krovnom konstrukcijom *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/bazovik_gal_3.jpg',
                'caption' => 'Manastirsko dvorište i pogled na crkvu Svetog Jovana Krstitelja *Izvor: manastiri.rs*',
                'sort_order' => 4
            ],
        ]
    ],

    // 76: Manastir Crkovnica
    76 => [
        'name' => 'Manastir Crkovnica',
        'card_image' => 'images/monasteries/crkovnica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/crkovnica.jpg',
                'caption' => 'Manastirska crkva Svetog proroka Ilije u selu Crkovnica *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
        ]
    ],

    // 77: Manastir Divljane
    77 => [
        'name' => 'Manastir Divljane',
        'card_image' => 'images/monasteries/divljane.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/divljane.jpg',
                'caption' => 'Manastir Svetog Dimitrija u Divljanu kod Bele Palanke *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/divljane_gal_1.jpg',
                'caption' => 'Zvonik i ulazna kapija manastirskog kompleksa Divljane *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/divljane_gal_2.jpg',
                'caption' => 'Pogled na crkvu Svetog Dimitrija i manastirsku portu podno Suve planine *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
        ]
    ],

    // 78: Manastir Gornji Matejevac
    78 => [
        'name' => 'Manastir Gornji Matejevac',
        'card_image' => 'images/monasteries/gornji-matejevac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/gornji-matejevac.jpg',
                'caption' => 'Latinska crkva (Sveta Trojica) u Gornjem Matejevcu, dragulj vizantijskog graditeljstva XI veka *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/gornji-matejevac_gal_1.jpg',
                'caption' => 'Istočna strana crkve sa karakterističnom vizantijskom apsidom i zidanom kupolom *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/gornji-matejevac_gal_2.jpg',
                'caption' => 'Pogled na hram Svete Trojice na brdu Metoh iznad Niša *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
        ]
    ],

    // 79: Manastir Gorčince
    79 => [
        'name' => 'Manastir Gorčince',
        'card_image' => 'images/monasteries/gorcince.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/gorcince.jpg',
                'caption' => 'Manastirska crkva Svetog Nikole u selu Gorčince u Lužničkoj kotlini *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
        ]
    ],

    // 80: Manastir Iverica
    80 => [
        'name' => 'Manastir Iverica',
        'card_image' => 'images/monasteries/iverica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/iverica_gal_2.jpg',
                'caption' => 'Crkva Svete Petke u Iverici – Srpska vojnička svetinja u Sićevačkoj klisuri *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/iverica.jpg',
                'caption' => 'Pogled na hram Svete Petke i manastirski kompleks podno stena Sićevačke klisure *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/iverica_gal_1.jpg',
                'caption' => 'Uređeno manastirsko dvorište sa cvetnim lejama i crkvom Svete Petke *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
        ]
    ],

    // 81: Manastir Janjuša
    81 => [
        'name' => 'Manastir Janjuša',
        'card_image' => 'images/monasteries/janjusa.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/janjusa.jpg',
                'caption' => 'Ženski manastir Vavedenja Presvete Bogorodice (Jašunja / Janjuša) na Babičkoj gori *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/janjusa_gal_1.jpg',
                'caption' => 'Pogled na crkvu Vavedenja Presvete Bogorodice sa drvenim zvonikom *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/janjusa_gal_2.jpg',
                'caption' => 'Manastirski konak i crkva u tihom planinskom okruženju *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
        ]
    ],

    // 82: Manastir Kaludra
    82 => [
        'name' => 'Manastir Kaludra',
        'card_image' => 'images/monasteries/kaludra.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/kaludra.jpg',
                'caption' => 'Manastirska crkva Svetih apostola Petra i Pavla u Kaludri kod Prokuplja *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/kaludra_gal_1.jpg',
                'caption' => 'Pogled na obnovljeni hram Svetih apostola Petra i Pavla *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/kaludra_gal_2.jpg',
                'caption' => 'Južna strana crkve sa novim manastirskim zdanjem *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/kaludra_gal_3.jpg',
                'caption' => 'Pogled na manastirsku crkvu i prirodni ambijent Kaludre *Izvor: manastiri.rs*',
                'sort_order' => 4
            ],
        ]
    ],

    // 83: Manastir Kamenica
    83 => [
        'name' => 'Manastir Kamenica',
        'card_image' => 'images/monasteries/kamenica-timocka.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/kamenica-timocka.jpg',
                'caption' => 'Manastir Svetog Georgija u Kamenici podno Kameničkog visa kod Niša *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/kamenica-timocka_gal_1.jpg',
                'caption' => 'Pogled na manastirski hram Svetog Georgija i zvonik *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/kamenica-timocka_gal_2.jpg',
                'caption' => 'Zapadna fasada crkve sa ulaznim tremom *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/kamenica-timocka_gal_3.jpg',
                'caption' => 'Manastirski kompleks Kamenica u živopisnom planinskom predelu *Izvor: manastiri.rs*',
                'sort_order' => 4
            ],
        ]
    ],

    // 84: Manastir Kozarski
    84 => [
        'name' => 'Manastir Kozarski',
        'card_image' => 'images/monasteries/kozarski.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/kozarski.jpg',
                'caption' => 'Manastir Svetog Nikole u Kozaru (Kozarski manastir u Grdeličkoj klisuri) *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
        ]
    ],

    // 85: Manastir Krajkovac
    85 => [
        'name' => 'Manastir Krajkovac',
        'card_image' => 'images/monasteries/krajkovac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/krajkovac.jpg',
                'caption' => 'Manastirska crkva Pokrova Presvete Bogorodice u Krajkovcu kod Aleksinca *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
        ]
    ],

    // 86: Manastir Krupac
    86 => [
        'name' => 'Manastir Krupac',
        'card_image' => 'images/monasteries/krupac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/krupac.jpg',
                'caption' => 'Manastirska crkva Svetog Jovana Krstitelja u Krupcu kod Pirota *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/krupac_gal_2.jpg',
                'caption' => 'Zapadni portal crkve sa rozetom i freskom Svetog Jovana Krstitelja iznad ulaza *Izvor: commons.wikimedia.org*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/krupac_gal_3.jpg',
                'caption' => 'Spomen-ploča u hramu posvećena palim žrtvama za oslobođenje Pirota od Turaka *Izvor: commons.wikimedia.org*',
                'sort_order' => 3
            ],
        ]
    ],

    // 87: Manastir Kuršumlija
    87 => [
        'name' => 'Manastir Kuršumlija',
        'card_image' => 'images/monasteries/kursumlija.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/kursumlija.jpg',
                'caption' => 'Manastir Svetog Nikole u Kuršumliji – prva zadužbina rodonačelnika dinastije Stefana Nemanje *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/kursumlija_gal_1.jpg',
                'caption' => 'Pogled na južnu fasadu hrama i rekonstruisanu kupolu od opeke *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/kursumlija_gal_2.jpg',
                'caption' => 'Hram Svetog Nikole na uzvišenju iznad Toplice, pogled na kule i oltarsku apsidu *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
        ]
    ],

    // 88: Manastir Labukovo
    88 => [
        'name' => 'Manastir Labukovo',
        'card_image' => 'images/monasteries/labukovo.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/labukovo.jpg',
                'caption' => 'Manastirska crkva Svetog arhangela Mihaila u selu Labukovo *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
        ]
    ],

    // 89: Manastir Lipovac
    89 => [
        'name' => 'Manastir Lipovac',
        'card_image' => 'images/monasteries/lipovac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/lipovac.jpg',
                'caption' => 'Manastir Svetog Stefana u Lipovcu, zadužbina despota Stefana Lazarevića pod Ozrenom *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/lipovac_gal_1.jpg',
                'caption' => 'Pogled na hram Svetog Stefana sa manastirskim zvonikom i cvetnim vrtom *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/lipovac_gal_2.jpg',
                'caption' => 'Manastirski kompleks Lipovac u podnožju šumovite planine Ozren *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/lipovac_gal_3.jpg',
                'caption' => 'Crkveno zdanje i konaci manastira Lipovac *Izvor: manastiri.rs*',
                'sort_order' => 4
            ],
        ]
    ],

    // 90: Manastir Manastirče
    90 => [
        'name' => 'Manastir Manastirče',
        'card_image' => 'images/monasteries/manastirce.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/manastirce.jpg',
                'caption' => 'Manastir Manastirče – crkva Svetih mučenika Kirika i Julite kod Dimitrovgrada *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/manastirce_gal_1.jpg',
                'caption' => 'Pogled na manastirsku crkvu i zvonik na obroncima planina *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/manastirce_gal_2.jpg',
                'caption' => 'Južna fasada hrama Svetih Kirika i Julite u Manastirčetu *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/manastirce_gal_3.jpg',
                'caption' => 'Pogled na manastirsku portu i crkvu Svetih Kirika i Julite *Izvor: manastiri.rs*',
                'sort_order' => 4
            ],
        ]
    ],

    // 91: Manastir Miljkovac
    91 => [
        'name' => 'Manastir Miljkovac',
        'card_image' => 'images/monasteries/miljkovac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/miljkovac.jpg',
                'caption' => 'Manastir Svetog Nikole u selu Miljkovac kod Niša *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
        ]
    ],

    // 92: Manastir Muštar
    92 => [
        'name' => 'Manastir Muštar',
        'card_image' => 'images/monasteries/mustar.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/mustar.jpg',
                'caption' => 'Manastir Svetog Jovana Krstitelja u Muštaru kod Pirota *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/mustar_gal_1.jpg',
                'caption' => 'Manastirski kompleks Muštar u živopisnom planinskom ambijentu *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/mustar_gal_2.jpg',
                'caption' => 'Pogled na manastirsku crkvu Svetog Jovana Krstitelja *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/mustar_gal_3.jpg',
                'caption' => 'Crkva i manastirsko imanje u selu Muštar *Izvor: manastiri.rs*',
                'sort_order' => 4
            ],
        ]
    ],

    // 93: Manastir Oraovica
    93 => [
        'name' => 'Manastir Oraovica',
        'card_image' => 'images/monasteries/oraovica-niska.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/oraovica-niska.jpg',
                'caption' => 'Manastirska crkva Svetog Nikole u Oraovici kod Grdelice *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/oraovica-niska_gal_1.jpg',
                'caption' => 'Pogled na hram Svetog Nikole i uređeno manastirsko dvorište *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/oraovica-niska_gal_2.jpg',
                'caption' => 'Zapadna fasada crkve sa tremom i zvonikom *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/oraovica-niska_gal_3.jpg',
                'caption' => 'Crkva Svetog Nikole u Oraovici u okruženju Grdeličke klisure *Izvor: manastiri.rs*',
                'sort_order' => 4
            ],
        ]
    ],

    // 94: Manastir Planinica
    94 => [
        'name' => 'Manastir Planinica',
        'card_image' => 'images/monasteries/planinica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/planinica.jpg',
                'caption' => 'Manastir Svetog Nikole u selu Planinica kod Pirota *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
        ]
    ],

    // 95: Manastir Pločnik
    95 => [
        'name' => 'Manastir Pločnik',
        'card_image' => 'images/monasteries/plocnik.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/plocnik.jpg',
                'caption' => 'Crkva Svetih vrača Kozme i Damjana u selu Pločnik kod Prokuplja *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
        ]
    ],

    // 96: Manastir Poganovo
    96 => [
        'name' => 'Manastir Poganovo',
        'card_image' => 'images/monasteries/poganovo_gal_1.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/poganovo_gal_1.jpg',
                'caption' => 'Manastir Poganovo – crkva Svetog Jovana Bogoslova u kanjonu reke Jerme *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/poganovo.jpg',
                'caption' => 'Manastirski hram Svetog Jovana Bogoslova, zadužbina plemića Konstantina Dragaša i Jelene *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/poganovo_gal_2.jpg',
                'caption' => 'Zvonik, manastirski trem i crkva u slikovitoj klisuri Jerme *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/poganovo_gal_3.jpg',
                'caption' => 'Pogled na manastirski kompleks Poganovo i okolne strme litice *Izvor: manastiri.rs*',
                'sort_order' => 4
            ],
        ]
    ],

    // 97: Manastir Rsovci
    97 => [
        'name' => 'Manastir Rsovci',
        'card_image' => 'images/monasteries/rsovci.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/rsovci.jpg',
                'caption' => 'Pećinska crkva Svetih Petra i Pavla u Rsovcima na Staroj planini *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/rsovci_gal_1.jpg',
                'caption' => 'Čuvena freska Isusa Hrista Mladenaca („Ćelavi Isus”) u pećinskoj crkvi u Rsovcima *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/rsovci_gal_2.jpg',
                'caption' => 'Ulaz u pećinsku crkvu Svetih Petra i Pavla usečenu u stenu *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
        ]
    ],

    // 98: Manastir Rudare
    98 => [
        'name' => 'Manastir Rudare',
        'card_image' => 'images/monasteries/rudare.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/rudare.jpg',
                'caption' => 'Stari manastirski konak u Rudaru kod Leskovca, biser narodnog neimarskog graditeljstva *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/rudare_gal_1.jpg',
                'caption' => 'Pogled na zadnju stranu starog konaka manastira Rudare *Izvor: commons.wikimedia.org*',
                'sort_order' => 2
            ],
        ]
    ],

    // 99: Manastir Sinjački
    99 => [
        'name' => 'Manastir Sinjački',
        'card_image' => 'images/monasteries/sinjacki.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sinjacki.jpg',
                'caption' => 'Manastir Svetog Nikole u selu Sinjac (Sinjački manastir kod Bele Palanke) *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
        ]
    ],

    // 100: Manastir Smilovci
    100 => [
        'name' => 'Manastir Smilovci',
        'card_image' => 'images/monasteries/smilovci_gal_1.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/smilovci_gal_1.jpg',
                'caption' => 'Manastir Svetih Kirika i Julite kod Smilovaca na padinama planine Vidlič *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/smilovci.jpg',
                'caption' => 'Pogled na crkvu sa autentičnim kamenim krovnim pokrivačem i polukružnom apsidom *Izvor: commons.wikimedia.org*',
                'sort_order' => 2
            ],
        ]
    ],

    // 101: Manastir Sukovo
    101 => [
        'name' => 'Manastir Sukovo',
        'card_image' => 'images/monasteries/sukovo.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sukovo.jpg',
                'caption' => 'Manastirska crkva Uspenja Presvete Bogorodice u Sukovu kod Pirota *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/sukovo_gal_1.jpg',
                'caption' => 'Pogled na hram i manastirski kompleks Sukovo podno brda Carev kamen *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/sukovo_gal_2.jpg',
                'caption' => 'Manastirski zvonik i crkva Uspenja Presvete Bogorodice *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/sukovo_gal_3.jpg',
                'caption' => 'Ulaz u manastirsko dvorište i pogled na crkvu u Sukovu *Izvor: manastiri.rs*',
                'sort_order' => 4
            ],
        ]
    ],

    // 102: Manastir Sveti Jovan
    102 => [
        'name' => 'Manastir Sveti Jovan',
        'card_image' => 'images/monasteries/sveti-jovan.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sveti-jovan.jpg',
                'caption' => 'Manastir Svetog Jovana Krstitelja u Gornjem Matejevcu kod Niša *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/sveti-jovan_gal_1.jpg',
                'caption' => 'Pogled na crkvu Svetog Jovana Krstitelja i manastirski konak *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
        ]
    ],

    // 103: Manastir Sveti Roman
    103 => [
        'name' => 'Manastir Sveti Roman',
        'card_image' => 'images/monasteries/sveti-roman_gal_1.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sveti-roman_gal_1.jpg',
                'caption' => 'Manastir Sveti Roman kod Đunisa – drevno svetilište i grobno mesto prepodobnog Romana Sinaita *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/sveti-roman.jpg',
                'caption' => 'Manastirska crkva Svetog Romana i grob ruskog pukovnika grofa Nikolaja Rajevskog *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/sveti-roman_gal_2.jpg',
                'caption' => 'Pogled na zvonik, manastirski hram i konake manastira Sveti Roman *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
        ]
    ],

    // 104: Manastir Temska
    104 => [
        'name' => 'Manastir Temska',
        'card_image' => 'images/monasteries/temska.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/temska.jpg',
                'caption' => 'Manastir Svetog Georgija u Temskoj kod Pirota podno Stare planine *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/temska_gal_1.jpg',
                'caption' => 'Pogled na hram Svetog Georgija i tradicionalne konake manastira Temska *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/temska_gal_2.jpg',
                'caption' => 'Zapadna fasada crkve sa drvenim tremom i kamenim zidovima *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
        ]
    ],

    // 105: Manastir Tešice
    105 => [
        'name' => 'Manastir Tešice',
        'card_image' => 'images/monasteries/tesice.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/tesice.jpg',
                'caption' => 'Manastirska crkva Uspenja Presvete Bogorodice u selu Tešica kod Aleksinca *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
        ]
    ],

    // 106: Manastir Veta
    106 => [
        'name' => 'Manastir Veta',
        'card_image' => 'images/monasteries/veta_gal_1.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/veta_gal_1.jpg',
                'caption' => 'Manastir Uspenja Presvete Bogorodice u selu Veta na padinama Suve planine *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/veta.jpg',
                'caption' => 'Pogled na manastirsku crkvu i zvonik u Veti *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/veta_gal_2.jpg',
                'caption' => 'Manastirsko dvorište i crkva Uspenja Presvete Bogorodice *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
        ]
    ],

    // 107: Manastir Visočka Ržana
    107 => [
        'name' => 'Manastir Visočka Ržana',
        'card_image' => 'images/monasteries/visocka-rzana.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/visocka-rzana.jpg',
                'caption' => 'Manastir Svete Bogorodice u Visočkoj Ržani na Staroj planini *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
        ]
    ],

    // 108: Manastir Zavidnice
    108 => [
        'name' => 'Manastir Zavidnice',
        'card_image' => 'images/monasteries/zavidnice.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/zavidnice.jpg',
                'caption' => 'Manastir Svetog proroka Ilije u selu Zavidince u Lužnici *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
        ]
    ],

    // 109: Manastir Ćirik
    109 => [
        'name' => 'Manastir Ćirik',
        'card_image' => 'images/monasteries/cirik.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/cirik.jpg',
                'caption' => 'Manastir Svete Petke u Ćiriku kod Dimitrovgrada *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
        ]
    ],

    // 110: Manastir Činiglavci
    110 => [
        'name' => 'Manastir Činiglavci',
        'card_image' => 'images/monasteries/ciniglavci.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/ciniglavci.jpg',
                'caption' => 'Manastir Svete Trojice u Činiglavcima kod Pirota *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
        ]
    ],

    // 111: Manastir Čukljenik
    111 => [
        'name' => 'Manastir Čukljenik',
        'card_image' => 'images/monasteries/cukljenik.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/cukljenik.jpg',
                'caption' => 'Manastir Svetog Nikole u Čukljeniku na padinama planine Kukavice *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/cukljenik_gal_1.jpg',
                'caption' => 'Pogled na manastirsku crkvu Svetog Nikole sa zvonikom *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/cukljenik_gal_2.jpg',
                'caption' => 'Crkva Svetog Nikole u šumskom okruženju planine Kukavice *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/cukljenik_gal_3.jpg',
                'caption' => 'Manastirski kompleks Čukljenik sa konacima i pomoćnim objektima *Izvor: manastiri.rs*',
                'sort_order' => 4
            ],
        ]
    ],

    // 244: Manastir Gabrovac
    244 => [
        'name' => 'Manastir Gabrovac',
        'card_image' => 'images/monasteries/gabrovac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/gabrovac.jpg',
                'caption' => 'Manastirska crkva Svete Trojice u selu Gabrovac kod Niša *Izvor: commons.wikimedia.org*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/gabrovac_gal_1.jpg',
                'caption' => 'Pogled na manastirski kompleks Gabrovac i drveni zvonik *Izvor: commons.wikimedia.org*',
                'sort_order' => 2
            ],
        ]
    ],

    // 250: Manastir Sićevo
    250 => [
        'name' => 'Manastir Sićevo',
        'card_image' => 'images/monasteries/sicevo_gal_1.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sicevo_gal_1.jpg',
                'caption' => 'Manastir Sićevo – crkva Svete Bogorodice u Sićevačkoj klisuri podno brda Kusača *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/sicevo.jpg',
                'caption' => 'Pogled na manastirsku crkvu i konake visoko iznad reke Nišave *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/sicevo_gal_2.jpg',
                'caption' => 'Manastirski kompleks Sićevo okružen krečnjačkim stenama klisure *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
        ]
    ],

    // 255: Manastir Đunis
    255 => [
        'name' => 'Manastir Đunis',
        'card_image' => 'images/monasteries/djunis_gal_1.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/djunis_gal_1.jpg',
                'caption' => 'Manastir Pokrova Presvete Bogorodice u Đunisu – čudotvorno izvorište i mesto hodočašća *Izvor: manastiri.rs*',
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/djunis.jpg',
                'caption' => 'Veliki saborni hram Pokrova Presvete Bogorodice u Đunisu *Izvor: manastiri.rs*',
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/djunis_gal_2.jpg',
                'caption' => 'Mala crkva brvnara podignuta na mestu javljanja Presvete Bogorodice *Izvor: manastiri.rs*',
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/djunis_gal_3.jpg',
                'caption' => 'Pogled na uređeni manastirski park i crkvu Pokrova Presvete Bogorodice *Izvor: manastiri.rs*',
                'sort_order' => 4
            ],
        ]
    ],
];

// Sinhronizacija u bazu podataka (Laravel Eloquent / primarna baza)
echo "1. Ažuriranje primarne baze podataka (database.sqlite):\n";
DB::beginTransaction();

try {
    foreach ($niska_data as $monasteryId => $data) {
        $monastery = Monastery::find($monasteryId);
        if (!$monastery) {
            echo "  [UPOZORENJE] Manastir sa ID-jem {$monasteryId} nije pronađen!\n";
            continue;
        }

        // Ažuriranje osnovnih podataka manastira
        $monastery->name = $data['name'];
        $monastery->image_url = $data['card_image'];
        $monastery->image = $data['card_image'];
        $monastery->save();

        // Brisanje starih slika iz galerije
        MonasteryImage::where('monastery_id', $monasteryId)->delete();

        // Unos novih, verifikovanih slika
        foreach ($data['images'] as $imgData) {
            MonasteryImage::create([
                'monastery_id' => $monasteryId,
                'url' => $imgData['url'],
                'caption' => $imgData['caption'],
                'sort_order' => $imgData['sort_order'],
            ]);
        }

        $count = count($data['images']);
        echo "  [AŽURIRAN] [{$monasteryId}] {$monastery->name} | Kartica: {$data['card_image']} | Galerija: {$count} slika\n";
    }

    DB::commit();
    echo "\nPrimarna baza je uspešno ažurirana!\n\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "GREŠKA pri radu sa primarnom bazom: " . $e->getMessage() . "\n";
    exit(1);
}

// 2. Sinhronizacija u storage/database.sqlite (ako postoji)
$storageDbPath = storage_path('database.sqlite');
if (file_exists($storageDbPath)) {
    echo "2. Ažuriranje storage baze podataka ({$storageDbPath}):\n";
    try {
        $pdo = new PDO('sqlite:' . $storageDbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->beginTransaction();

        foreach ($niska_data as $monasteryId => $data) {
            $stmt = $pdo->prepare("UPDATE monasteries SET name = :name, image_url = :image_url, image = :img WHERE id = :id");
            $stmt->execute([
                ':name' => $data['name'],
                ':image_url' => $data['card_image'],
                ':img' => $data['card_image'],
                ':id' => $monasteryId
            ]);

            $stmtDel = $pdo->prepare("DELETE FROM monastery_images WHERE monastery_id = :id");
            $stmtDel->execute([':id' => $monasteryId]);

            $stmtIns = $pdo->prepare("INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (:m_id, :url, :caption, :sort_order, datetime('now'), datetime('now'))");
            foreach ($data['images'] as $imgData) {
                $stmtIns->execute([
                    ':m_id' => $monasteryId,
                    ':url' => $imgData['url'],
                    ':caption' => $imgData['caption'],
                    ':sort_order' => $imgData['sort_order'],
                ]);
            }

            echo "  [STORAGE AŽURIRAN] [{$monasteryId}]\n";
        }

        $pdo->commit();
        echo "Storage baza je uspešno ažurirana!\n\n";
    } catch (\Exception $e) {
        $pdo->rollBack();
        echo "GREŠKA pri radu sa storage bazom: " . $e->getMessage() . "\n";
    }
}

echo "====================================================================\n";
echo "REVIZIJA I SINHRONIZACIJA ZA EPARHIJU NIŠKU ZAVRŠENE USPEŠNO!\n";
echo "====================================================================\n";
