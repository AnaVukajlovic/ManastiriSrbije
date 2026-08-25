<?php

/**
 * SISTEMSKO ČIŠĆENJE I SINHRONIZACIJA - EPARHIJA SREMSKA (ID 11)
 * Pravoslavni Svetionik — Master rad
 * Izvor: manastiri.rs
 */

use App\Models\Monastery;
use App\Models\MonasteryImage;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "====================================================================\n";
echo "POKRETANJE REVIZIJE I ČIŠĆENJA ZA EPARHIJA SREMSKA (ID 11)\n";
echo "====================================================================\n\n";

$src = '<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>';

// 1. Definicija verifikovanih podataka, tačnih kartičnih slika i galerija
$eparchy_data = [
    // 131: Manastir Beočin
    131 => [
        'name' => 'Manastir Beočin',
        'card_image' => 'images/monasteries/beocin.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/beocin.jpg',
                'caption' => 'Crkva Vaznesenja Hristovog u manastiru Beočin sa baroknim zvonikom i cvetnom portom' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/beocin_gal_1.jpg',
                'caption' => 'Monumentalni barokni zvonik i prilaz manastirskom kompleksu' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/beocin_gal_2.jpg',
                'caption' => 'Manastirski konak i porta sa kapelom Svetog Varnave Nastića' . $src,
                'sort_order' => 3
            ],
        ]
    ],

    // 132: Manastir Berkasovo
    132 => [
        'name' => 'Manastir Berkasovo',
        'card_image' => 'images/monasteries/berkasovo.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/berkasovo.jpg',
                'caption' => 'Crkva Svete Petke u manastiru Berkasovo sa prepoznatljivim plavim baroknim krovom i uređenom portom' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/berkasovo_gal_1.jpg',
                'caption' => 'Pogled na manastirski kompleks Berkasovo okružen fruškogorskim šumama i vinogradima' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/berkasovo_gal_2.jpg',
                'caption' => 'Zidana česma i čudotvorni lekoviti izvor Svete Petke u sklopu manastirskog kompleksa' . $src,
                'sort_order' => 3
            ],
        ]
    ],

    // 133: Manastir Bešenovo
    133 => [
        'name' => 'Manastir Bešenovo',
        'card_image' => 'images/monasteries/besenovo.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/besenovo.jpg',
                'caption' => 'Obnovljena crkva Svetih arhangela Gavrila i Mihaila u manastiru Bešenovo od fasadne opeke' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/besenovo_gal_1.jpg',
                'caption' => 'Monumentalni zvonik sa ulaznom kapijom i manastirskim konacima' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/besenovo_gal_2.jpg',
                'caption' => 'Panorama manastirskog kompleksa Bešenovo, zadužbine kralja Dragutina Nemanjića' . $src,
                'sort_order' => 3
            ],
        ]
    ],

    // 134: Manastir Divša (Đipša)
    134 => [
        'name' => 'Manastir Divša (Đipša)',
        'card_image' => 'images/monasteries/divsa.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/divsa.jpg',
                'caption' => 'Crkva Svetog Nikole u manastiru Divša sa baroknim zvonikom i travnatom portom' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/divsa_gal_1.jpg',
                'caption' => 'Drveni letnjikovac i cvetni vrt u mirnom dvorištu manastira Đipša' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/divsa_gal_2.jpg',
                'caption' => 'Pogled sa manastirskog brežuljka na hram Svetog Nikole i fruškogorsku prirodu' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/divsa_gal_3.jpg',
                'caption' => 'Spomen-obeležje i kameni krst u krugu manastira' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 135: Manastir Fenek
    135 => [
        'name' => 'Manastir Fenek',
        'card_image' => 'images/monasteries/fenek.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/fenek.jpg',
                'caption' => 'Crkva Svete mučenice Paraskeve (Svete Petke) u manastiru Fenek sa konacima i portom' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/fenek_gal_1.jpg',
                'caption' => 'Lučni portal i ulazno dvorište sa cvetnim lejama manastira Fenek' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/fenek_gal_2.jpg',
                'caption' => 'Zidana kapela nad čudotvornim izvorom Svete Petke u manastirskoj porti' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/fenek_gal_3.jpg',
                'caption' => 'Manastirski konak i spratna zdanja u donjosremskoj ravnici' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 136: Manastir Grgeteg
    136 => [
        'name' => 'Manastir Grgeteg',
        'card_image' => 'images/monasteries/grgeteg.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/grgeteg.jpg',
                'caption' => 'Crkva Svetog Nikole u manastiru Grgeteg sa visokim baroknim zvonikom i čuvenim konacima' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/grgeteg_gal_1.jpg',
                'caption' => 'Panorama manastirskog kompleksa Grgeteg sa prilaznog puta kroz fruškogorske voćnjake' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/grgeteg_gal_2.jpg',
                'caption' => 'Popločana porta i unutrašnje dvorište sa arkadama manastirskog konaka' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/grgeteg_gal_3.jpg',
                'caption' => 'Mozaik Pokrova Presvete Bogorodice iznad ulaznog portala crkve Svetog Nikole' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 137: Manastir Jazak
    137 => [
        'name' => 'Manastir Jazak',
        'card_image' => 'images/monasteries/jazak.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/jazak.jpg',
                'caption' => 'Crkva Svete Trojice u manastiru Jazak sa skladnim baroknim zvonikom, ukrašenom kupolom i kamenim zidovima' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/jazak_gal_1.jpg',
                'caption' => 'Prilaz crkvi Svete Trojice kroz uređenu cvetnu portu sa pogledom na konake' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/jazak_gal_2.jpg',
                'caption' => 'Prostrani manastirski konak sa belim arkadama i tremom' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/jazak_gal_3.jpg',
                'caption' => 'Lučni kameni portal i priprata crkve u kojoj počivaju mošti Svetog cara Uroša Nejakog' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 138: Manastir Krušedol
    138 => [
        'name' => 'Manastir Krušedol',
        'card_image' => 'images/monasteries/krusedol.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/krusedol.jpg',
                'caption' => 'Panorama manastira Krušedol sa prepoznatljivim crvenim konacima i crkvom Blagoveštenja Presvete Bogorodice' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/krusedol_gal_1.jpg',
                'caption' => 'Otvoreni barokni trem crkve Blagoveštenja sa freskama i zvonikom' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/krusedol_gal_2.jpg',
                'caption' => 'Monumentalna zidna kompozicija Strašnog suda iz 16. veka na zapadnoj fasadi priprate' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/krusedol_gal_3.jpg',
                'caption' => 'Unutrašnjost hrama sa freskama svetih ratnika, vladara Brankovića i duboreznim pevnicama' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 139: Manastir Kuveždin
    139 => [
        'name' => 'Manastir Kuveždin',
        'card_image' => 'images/monasteries/kuvezdin.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/kuvezdin.jpg',
                'caption' => 'Obnovljena crkva Svetog Save i Svetog Simeona u manastiru Kuveždin sa baroknim zvonikom i konacima' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/kuvezdin_gal_1.jpg',
                'caption' => 'Raskošno oslikana kupola sa jevanđelistima i freskama u naosu hrama' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/kuvezdin_gal_2.jpg',
                'caption' => 'Čudotvorna ikona Svetog Jovana Krstitelja u pozlaćenom duboreznom ramu' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/kuvezdin_gal_3.jpg',
                'caption' => 'Čudotvorna vodica Svetog Save u dolini sa spomen-pločom i krstom iz 1537. godine' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 140: Manastir Mala Remeta
    140 => [
        'name' => 'Manastir Mala Remeta',
        'card_image' => 'images/monasteries/mala_remeta.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/mala_remeta.jpg',
                'caption' => 'Jednobrodna kamena crkva Bogorodičinog Pokrova u Maloj Remeti sa osmostranom kupolom i drvenim zvonikom' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/mala_remeta_gal_1.jpg',
                'caption' => 'Pogled kroz ulazni portal na pozlaćeni barokni ikonostas Janka Halkozovića iz 1759. godine' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/mala_remeta_gal_2.jpg',
                'caption' => 'Prestona ikona Gospoda Isusa Hrista na prestolu sa bogatim rokajnim duborezom' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/mala_remeta_gal_3.jpg',
                'caption' => 'Duborezni vladičanski tron i freske svetitelja u unutrašnjosti hrama' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 141: Manastir Novo Hopovo
    141 => [
        'name' => 'Manastir Novo Hopovo',
        'card_image' => 'images/monasteries/novo_hopovo.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/novo_hopovo.jpg',
                'caption' => 'Monumentalna crkva Svetog Nikole u Novom Hopovu sa šesnaestostranom kupolom, arkadama i baroknim zvonikom' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/novo_hopovo_gal_1.jpg',
                'caption' => 'Zapadna fasada sa slepim arkadama i raskošnim belim baroknim zvonikom' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/novo_hopovo_gal_2.jpg',
                'caption' => 'Južno pročelje crkve Svetog Nikole sa ukrasnim vencima i borovima u porti' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/novo_hopovo_gal_3.jpg',
                'caption' => 'Pogled na manastirski kompleks Novo Hopovo sa konacima i prilaznom stazom' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 142: Manastir Obed
    142 => [
        'name' => 'Manastir Obed',
        'card_image' => 'images/monasteries/obed.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/obed.jpg',
                'caption' => 'Crkva Svete majke Angeline – manastir Obed u Kupinovu uz obalu Obedske bare' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/obed_gal_1.jpg',
                'caption' => 'Ulazna kapija i apsida crkve Svetog Luke despota Đurđa Brankovića u Kupinovu' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/obed_gal_2.jpg',
                'caption' => 'Raskošni barokni ikonostas Jakova Orfelina iz 1780. godine u crkvi Svetog Luke' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/obed_gal_3.jpg',
                'caption' => 'Antičke i srednjovekovne kamene spolije uzidane u temelj crkve despota Brankovića' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 143: Manastir Privina Glava
    143 => [
        'name' => 'Manastir Privina Glava',
        'card_image' => 'images/monasteries/privina_glava.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/privina_glava.jpg',
                'caption' => 'Crkva Svetih arhangela Gavrila i Mihaila u manastiru Privina Glava sa lučnom kapijom i konacima' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/privina_gal_1.jpg',
                'caption' => 'Zidni mozaik Svetog arhangela Gavrila u luneti iznad ulaznih vrata hrama' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/privina_gal_2.jpg',
                'caption' => 'Freska Nebeske liturgije na svodu priprate u crkvi Svetih arhangela' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/privina_gal_3.jpg',
                'caption' => 'Nova crkva Svetog velikomučenika Georgija u manastirskom vrtu' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 144: Manastir Rakovac
    144 => [
        'name' => 'Manastir Rakovac',
        'card_image' => 'images/monasteries/rakovac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/rakovac.jpg',
                'caption' => 'Crkva Svetih vrača Kozme i Damjana u manastiru Rakovac sa kamenom osmostranom kupolom i zvonikom' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/rakovac_gal_1.jpg',
                'caption' => 'Kovana ulazna kapija i manastirska porta sa cvetnim vrtom i stazama' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/rakovac_gal_2.jpg',
                'caption' => 'Unutrašnje dvorište sa obnovljenim manastirskim konakom i spratnom lođom' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/rakovac_gal_3.jpg',
                'caption' => 'Oltarska apsida crkve od tesanog kamena sa ukrasnim profilisanim prozorima' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 145: Manastir Velika Remeta
    145 => [
        'name' => 'Manastir Velika Remeta',
        'card_image' => 'images/monasteries/velika_remeta.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/velika_remeta.jpg',
                'caption' => 'Pogled na monumentalni manastirski kompleks Velika Remeta sa konacima i najvišim baroknim zvonikom na Fruškoj gori' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/velika_remeta_gal_1.jpg',
                'caption' => 'Crkva Svetog velikomučenika Dimitrija od opeke i kamena u unutrašnjem dvorištu manastira' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/velika_remeta_gal_2.jpg',
                'caption' => 'Raskošno oslikani ikonostas i Carske dveri u crkvi Svetog Dimitrija sa kristalnim polijelejem' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/velika_remeta_gal_3.jpg',
                'caption' => 'Monumentalna kompozicija Vavedenja Presvete Bogorodice na plavoj pozadini u unutrašnjosti hrama' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 146: Manastir Vodice
    146 => [
        'name' => 'Manastir Vodice',
        'card_image' => 'images/monasteries/vodice.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/vodice.jpg',
                'caption' => 'Crkva Svete Petke – manastir Vodice kod Novih Karlovaca (Sasa) sa popločanim prilazom i uređenom portom' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/vodice_gal_1.jpg',
                'caption' => 'Pogled na hram Svete Petke sa baroknim zvonikom i ulaznim vratima' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/vodice_gal_2.jpg',
                'caption' => 'Spomen-bista blaženopočivšeg patrijarha Pavla i zidana kapela nad izvorom lekovite vode u manastirskoj porti' . $src,
                'sort_order' => 3
            ],
        ]
    ],

    // 147: Manastir Vranjaš
    147 => [
        'name' => 'Manastir Vranjaš',
        'card_image' => 'images/monasteries/vranjas.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/vranjas.jpg',
                'caption' => 'Manastir Vranjaš – prelepa crkva brvnara posvećena Svetom Vasiliju Ostroškom, sagrađena od smrekovih balvana iznad izvora' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/vranjas_gal_1.jpg',
                'caption' => 'Pogled sa bočne strane na crkvu brvnaru i drveni zvonik u manastirskoj porti' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/vranjas_gal_2.jpg',
                'caption' => 'Unutrašnjost crkve sa duborezbarskim vladičanskim tronom, ikonostasom i freskama' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/vranjas_gal_3.jpg',
                'caption' => 'Drevni natkriveni izvor lekovite vode – česma Vranjaš u podnožju crkve' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 148: Manastir Vrdnik (Mala Ravanica)
    148 => [
        'name' => 'Manastir Vrdnik (Mala Ravanica)',
        'card_image' => 'images/monasteries/vrdnik.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/vrdnik.jpg',
                'caption' => 'Pogled na manastirski kompleks Vrdnik (Mala Ravanica) sa crkvom Vaznesenja Gospodnjeg, baroknim zvonikom i konacima' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/vrdnik_gal_1.jpg',
                'caption' => 'Monumentalna ulazna kapija i zapadno pročelje crkve Vaznesenja Gospodnjeg' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/vrdnik_gal_2.jpg',
                'caption' => 'Kivot u kome su vekovima čuvane svete mošti Svetog kneza Lazara Kosovskog' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/vrdnik_gal_3.jpg',
                'caption' => 'Zidno slikarstvo u naosu sa prikazima svetih Nemanjića i stradanja kneza Lazara' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 149: Manastir Šišatovac
    149 => [
        'name' => 'Manastir Šišatovac',
        'card_image' => 'images/monasteries/sisatovac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sisatovac.jpg',
                'caption' => 'Monumentalna crkva Rođenja Presvete Bogorodice sa visokim baroknim zvonikom i velikom kupolom u Šišatovcu' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/sisatovac_gal_1.jpg',
                'caption' => 'Oltarska apsida zidane crkve od klesanog kamena i opeke sa baroknim prozorom' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/sisatovac_gal_2.jpg',
                'caption' => 'Čudotvorni izvor i kapelica Svete Petke u podnožju manastirskog uzvišenja sa pogledom na hram' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/sisatovac_gal_3.jpg',
                'caption' => 'Rascvetala manastirska porta i obnovljeni konak sa arkadnim otvorima' . $src,
                'sort_order' => 4
            ],
        ]
    ],
];

$dbFiles = array_unique([
    base_path('database/database.sqlite'),
    storage_path('database.sqlite'),
]);

foreach ($dbFiles as $dbPath) {
    if (!file_exists($dbPath)) {
        echo "Baza na putanji {$dbPath} ne postoji, preskačem.\n";
        continue;
    }

    echo "Ažuriranje SQLite baze podataka ({$dbPath}):\n";
    try {
        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->beginTransaction();

        foreach ($eparchy_data as $monasteryId => $data) {
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

            $count = count($data['images']);
            echo "  [AŽURIRAN] [{$monasteryId}] {$data['name']} | Kartica: {$data['card_image']} | Galerija: {$count} slika\n";
        }

        $pdo->commit();
        echo "Baza {$dbPath} je uspešno ažurirana!\n\n";
    } catch (\Exception $e) {
        $pdo->rollBack();
        echo "GREŠKA pri radu sa bazom {$dbPath}: " . $e->getMessage() . "\n";
    }
}

echo "====================================================================\n";
echo "REVIZIJA I SINHRONIZACIJA ZA EPARHIJA SREMSKA ZAVRŠENE USPEŠNO!\n";
echo "====================================================================\n";
