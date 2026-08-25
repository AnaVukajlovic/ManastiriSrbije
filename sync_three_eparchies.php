<?php

/**
 * DETALJNA REVIZIJA I SINHRONIZACIJA:
 * 1. Eparhija banatska (ID 6, 9 manastira)
 * 2. Eparhija bačka (ID 7, 5 manastira)
 * 3. Beogradsko-karlovačka arhiepiskopija / Beogradska (ID 3, 6 manastira)
 *
 * Ukupno: 20 manastira
 */

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Monastery;
use App\Models\MonasteryImage;
use Illuminate\Support\Facades\DB;

echo "====================================================================\n";
echo "POKRETANJE REVIZIJE: BANATSKA, BAČKA I BEOGRADSKA EPARHIJA\n";
echo "====================================================================\n\n";

$data = [
    // =========================================================================
    // 1. BANATSKA EPARHIJA (ID 6)
    // =========================================================================

    // 1. BAVANIŠTE (ID 1)
    1 => [
        'lat' => 44.827124,
        'lng' => 20.894011,
        'card_image' => 'images/monasteries/bavaniste.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/bavaniste.jpg',
                'caption' => 'Crkva Rođenja Presvete Bogorodice sa drvenim zvonikom i konakom u manastirskoj porti Bavaništa' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/bavaniste_gal_1.jpg',
                'caption' => 'Uređeni prilaz i staza ka hramu u manastirskom kompleksu Bavanište' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/bavaniste_gal_2.jpg',
                'caption' => 'Južna i istočna fasada hrama sa polukružnom oltarskom apsidom u hrastovoj šumi' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/bavaniste_gal_3.jpg',
                'caption' => 'Ulazna kapija i drveni zvonik u manastiru Bavanište' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 4,
            ],
        ],
    ],

    // 2. GAJ (ID 2)
    2 => [
        'lat' => 44.770261,
        'lng' => 21.088950,
        'card_image' => 'images/monasteries/gaj.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/gaj.jpg',
                'caption' => 'Pogled na manastirski hram Svetih 40 mučenika Sevastijskih sa zvonikom i travnatom portom u Gaju' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/gaj_gal_1.jpg',
                'caption' => 'Zapadna fasada sa ulaznim tremom i zvonikom crkve manastira Gaj' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/gaj_gal_2.jpg',
                'caption' => 'Kameni otvoreni letnji oltar za bogosluženja u prostranoj porti manastira Gaj' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/gaj_gal_3.jpg',
                'caption' => 'Pogled na manastirski kompleks i konake u mirnom banatskom pejzažu' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 4,
            ],
        ],
    ],

    // 3. HAJDUČICA (ID 3)
    3 => [
        'lat' => 45.253855,
        'lng' => 20.966131,
        'card_image' => 'images/monasteries/hajducica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/hajducica.jpg',
                'caption' => 'Ulazna kapija sa zvonikom i pogled na manastirsku crkvu Svetih arhangela u Hajdučici' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/hajducica_gal_1.jpg',
                'caption' => 'Crkva Svetih arhangela Mihaila i Gavrila u baroknom stilu u manastirskom parku' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/hajducica_gal_2.jpg',
                'caption' => 'Zapadni portal i barokni toranj crkve manastira Hajdučica' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/hajducica_gal_3.jpg',
                'caption' => 'Pogled na manastirski kompleks sa drvoredom u parku dvorca Damaskin' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 4,
            ],
        ],
    ],

    // 4. MESIĆ (ID 4)
    4 => [
        'lat' => 45.104080,
        'lng' => 21.392033,
        'card_image' => 'images/monasteries/mesic.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/mesic.jpg',
                'caption' => 'Crkva Svetog Jovana Krstitelja sa baroknim zvonikom i velikim konakom u Mesiću podno Vršačkih planina' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/mesic_gal_1.jpg',
                'caption' => 'Južna fasada manastirske crkve sa cvetnim lejama i zvonikom u porti Mesića' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/mesic_gal_2.jpg',
                'caption' => 'Oslikani naos i raskošni pozlaćeni barokni ikonostas u crkvi manastira Mesić' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/mesic_gal_3.jpg',
                'caption' => 'Manastirski konaci sa arkadnim tremom i dvorištem u Mesiću' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 4,
            ],
        ],
    ],

    // 5. SREDIŠTE (ID 5)
    5 => [
        'lat' => 45.144114,
        'lng' => 21.397702,
        'card_image' => 'images/monasteries/srediste.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/srediste.jpg',
                'caption' => 'Panoramski pogled na manastirski kompleks Središte sa crkvom i kulom zvonikom na padinama Guduričkog vrha' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/srediste_gal_1.jpg',
                'caption' => 'Visoki zvonik od fasadne crvene opeke sa satnim mehanizmom u manastiru Središte' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/srediste_gal_2.jpg',
                'caption' => 'Nova crkva sa kupolama i pozlaćenim krstovima u manastiru Središte' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/srediste_gal_3.jpg',
                'caption' => 'Pogled na manastirski konak i terase u vinogradarskom kraju' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 4,
            ],
        ],
    ],

    // 6. SVETA TROJICA KIKINDA (ID 6)
    6 => [
        'lat' => 45.818803,
        'lng' => 20.467467,
        'card_image' => 'images/monasteries/sveta-trojica-kikinda.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sveta-trojica-kikinda.jpg',
                'caption' => 'Zadužbinska crkva Svete Trojice sa baroknim zvonikom na gradskom groblju u Kikindi' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/sveta-trojica-kikinda_gal_1.jpg',
                'caption' => 'Zapadna fasada sa dekorativnim baroknim portalom i zvonikom' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/sveta-trojica-kikinda_gal_2.jpg',
                'caption' => 'Bočna fasada manastirske crkve sa polukružnom oltarskom apsidom' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/sveta-trojica-kikinda_gal_3.jpg',
                'caption' => 'Detalj visokog baroknog zvonika sa satom i krstom' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 4,
            ],
        ],
    ],

    // 7. SVETE MELANIJE (ID 7)
    7 => [
        'lat' => 45.393949,
        'lng' => 20.413013,
        'card_image' => 'images/monasteries/svete-melanije.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/svete-melanije.jpg',
                'caption' => 'Kompleks manastira Svete Melanije u Zrenjaninu sa crkvom u srpsko-vizantijskom stilu, kupolom i konakom' . "\n" . '<small>*(Izvor: sr.wikipedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/svete-melanije_gal_1.jpg',
                'caption' => 'Zapadna fasada crkve Svete Melanije sa portalom i zvonikom' . "\n" . '<small>*(Izvor: sr.wikipedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/svete-melanije_gal_2.jpg',
                'caption' => 'Uređena manastirska porta sa cvetnim vrtom i stazama u Zrenjaninu' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/svete-melanije_gal_3.jpg',
                'caption' => 'Crkva Svete Melanije sa kupolom i konacima u manastirskom krugu' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 4,
            ],
        ],
    ],

    // 8. VLAJKOVAC (ID 8)
    8 => [
        'lat' => 45.071323,
        'lng' => 21.199672,
        'card_image' => 'images/monasteries/vlajkovac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/vlajkovac.jpg',
                'caption' => 'Zapadna fasada sa zvonikom i ulazom u manastirsku crkvu Svetog Dimitrija u Vlajkovcu' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/vlajkovac_gal_1.jpg',
                'caption' => 'Obnovljena fasada manastirske crkve sa kovanim portalom i mozaicima svetitelja' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/vlajkovac_gal_2.jpg',
                'caption' => 'Ikonostas i unutrašnjost crkve Svetog Dimitrija u Vlajkovcu' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/vlajkovac_gal_3.jpg',
                'caption' => 'Pogled na manastirsku portu i zvonik u Vlajkovcu' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 4,
            ],
        ],
    ],

    // 9. VOJLOVICA (ID 9)
    9 => [
        'lat' => 44.827955,
        'lng' => 20.684339,
        'card_image' => 'images/monasteries/vojlovica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/vojlovica.jpg',
                'caption' => 'Spoj srednjovekovne osmostrane kupole i kasnobaroknog zvonika crkve Svetih arhanđela u Vojlovici' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/vojlovica_gal_1.jpg',
                'caption' => 'Pogled na manastirski kompleks sa zidinama, zvonikom i konacima u Vojlovici' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/vojlovica_gal_2.jpg',
                'caption' => 'Raskošni barokni pozlaćeni ikonostas iz 18. veka u hramu manastira Vojlovica' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/vojlovica_gal_3.jpg',
                'caption' => 'Bronzani spomenik Svetom arhangelu Gavrilu u porti manastira Vojlovica' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 4,
            ],
        ],
    ],

    // =========================================================================
    // 2. BAČKA EPARHIJA (ID 7)
    // =========================================================================

    // 10. BOĐANI (ID 10)
    10 => [
        'lat' => 45.396079,
        'lng' => 19.102198,
        'card_image' => 'images/monasteries/bodjani.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/bodjani.jpg',
                'caption' => 'Panoramski pogled na manastirski kompleks Bođani sa baroknim konacima, parkom i crkvom Vavedenja Presvete Bogorodice' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/bodjani_gal_1.jpg',
                'caption' => 'Pogled kroz krošnje parka na kupolu i barokni zvonik manastirske crkve u Bođanima' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/bodjani_gal_2.jpg',
                'caption' => 'Unutrašnjost naosa sa čuvenim freskama Hristofora Žefarovića i rezbarenim ikonostasom' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/bodjani_gal_3.jpg',
                'caption' => 'Oslikana manastirska kapela Svete Petke sa drvorezbarskim ikonostasom i podnim mozaikom' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 4,
            ],
        ],
    ],

    // 11. KAĆ (ID 11)
    11 => [
        'lat' => 45.294067,
        'lng' => 19.963272,
        'card_image' => 'images/monasteries/kac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/kac.jpg',
                'caption' => 'Pogled na manastirsku crkvu sa zvonikom, konake u vizantijskom stilu i uređenu portu manastira Kać' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/kac_gal_1.jpg',
                'caption' => 'Zasvođena kapija sa mozaikom Vaskrsenja Hristovog i arhanđelima na ulazu u manastirski kompleks' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/kac_gal_2.jpg',
                'caption' => 'Zasvođeni ulazni prolaz sa plavim zvezdanim mozaikom na svodu i pogledom na travnatu portu' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/kac_gal_3.jpg',
                'caption' => 'Južna strana manastirskog hrama sa kupolom i konacima u Kaću' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 4,
            ],
        ],
    ],

    // 12. KOVILJ (ID 12)
    12 => [
        'lat' => 45.213900,
        'lng' => 20.035516,
        'card_image' => 'images/monasteries/kovilj.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/kovilj.jpg',
                'caption' => 'Zapadna fasada monumentalne kamene crkve Svetih arhangela Mihaila i Gavrila sa portalom u manastiru Kovilj' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/kovilj_gal_1.jpg',
                'caption' => 'Panorama manastirskog kompleksa Kovilj sa kupolama hrama i kulom zvonikom iznad bačkog zelenila' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/kovilj_gal_2.jpg',
                'caption' => 'Monumentalni mermerni ikonostas i freskopis unutar crkve manastira Kovilj' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/kovilj_gal_3.jpg',
                'caption' => 'Visoki zvonik i detalji kamene plastike u porti manastira Kovilj' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 4,
            ],
        ],
    ],

    // 13. SOMBOR (ID 13)
    13 => [
        'lat' => 45.778146,
        'lng' => 19.133855,
        'card_image' => 'images/monasteries/sombor.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sombor.jpg',
                'caption' => 'Crkva Svetog arhiđakona Stefana u srpsko-vizantijskom stilu sa kupolama i manastirskim konakom u Somboru' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/sombor_gal_1.jpg',
                'caption' => 'Glavni ulaz sa kamenom rozetom, triforom i mozaikom svetitelja na zapadnoj fasadi' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/sombor_gal_2.jpg',
                'caption' => 'Pogled na manastirski hram sa južne strane sa zvonikom i prilaznom stazom' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/sombor_gal_3.jpg',
                'caption' => 'Mermerna spomen-ploča inženjeru Svetozaru Krotinu, ktitoru manastirske crkve u Somboru' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 4,
            ],
        ],
    ],

    // 14. VODICA (ID 14)
    14 => [
        'lat' => 45.714128,
        'lng' => 20.037708,
        'card_image' => 'images/monasteries/vodica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/vodica.jpg',
                'caption' => 'Crkva i kapela posvećena Svetom proroku Iliji na lekovitom izvoru Vodica u Bačkoj' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/vodica_gal_1.jpg',
                'caption' => 'Pogled na manastirski hram sa zvonikom i uređenu portu oko svetog izvora' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/vodica_gal_2.jpg',
                'caption' => 'Zapadni ulaz u manastirsku kapelu sa natpisom i dekorativnim elementima' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/vodica_gal_3.jpg',
                'caption' => 'Unutrašnjost kapele posvećene Svetom proroku Iliji sa ikonostasom i nalonjem' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 4,
            ],
        ],
    ],

    // =========================================================================
    // 3. BEOGRADSKA EPARHIJA (ID 3)
    // =========================================================================

    // 15. MISLOĐIN (ID 15)
    15 => [
        'lat' => 44.641647,
        'lng' => 20.237692,
        'card_image' => 'images/monasteries/mislodjin.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/mislodjin.jpg',
                'caption' => 'Nova crkva Svetog arhiđakona Stefana i velikomučenika Hristofora sa kupolom i otvorenim zvonikom u manastiru Mislođin' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/mislodjin_gal_1.jpg',
                'caption' => 'Zaštićeni arheološki ostaci i temelj srednjovekovne crkve kralja Dragutina vidljivi pod staklom unutar hrama' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/mislodjin_gal_2.jpg',
                'caption' => 'Ulazni portal sa rezbarenim drvenim vratima i lučnim svodovima manastirske crkve' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/mislodjin_gal_3.jpg',
                'caption' => 'Pogled na manastirski kompleks i portu u Mislođinu na uzvišenju iznad Kolubare' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 4,
            ],
        ],
    ],

    // 16. RAJINOVAC (ID 16)
    16 => [
        'lat' => 44.624652,
        'lng' => 20.712839,
        'card_image' => 'images/monasteries/rajinovac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/rajinovac.jpg',
                'caption' => 'Crkva Rođenja Presvete Bogorodice sa kamenim zvonikom u manastiru Rajinovac uokvirena rascvetalim ružama u porti' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/rajinovac_gal_1.jpg',
                'caption' => 'Južna i istočna fasada hrama sa polukružnom apsidom i manastirskim konakom u Begaljici' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/rajinovac_gal_2.jpg',
                'caption' => 'Unutrašnjost manastirske crkve u Rajinovcu sa drvorezbarskim ikonostasom, zlatnim polijelejem i oslikanom apsidom' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/rajinovac_gal_3.jpg',
                'caption' => 'Freskopis na svodovima hrama manastira Rajinovac sa likovima svetitelja' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 4,
            ],
        ],
    ],

    // 17. RAKOVICA (ID 17)
    17 => [
        'lat' => 44.730621,
        'lng' => 20.447111,
        'card_image' => 'images/monasteries/rakovica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/rakovica.jpg',
                'caption' => 'Ulazni prilaz, zvonik-kapija, konaci i prostrana zelena porta manastira Rakovica u Beogradu' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/rakovica_gal_1.jpg',
                'caption' => 'Raskošna unutrašnjost manastirske crkve u Rakovici sa rezbarenim ikonostasom i freskama na zlatnoj pozadini' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/rakovica_gal_2.jpg',
                'caption' => 'Grob patrijarha srpskog Dimitrija i patrijarha Pavla u mirnoj porti manastira Rakovica' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/rakovica_gal_3.jpg',
                'caption' => 'Bela kamena spomen-česma u porti manastira Rakovica sa cvetnim lejama i konakom u pozadini' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 4,
            ],
        ],
    ],

    // 18. SENJAK (ID 18)
    18 => [
        'lat' => 44.792713,
        'lng' => 20.438918,
        'card_image' => 'images/monasteries/senjak.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/senjak.jpg',
                'caption' => 'Monumentalna bela fasada sa kupolama hrama Vavedenja Presvete Bogorodice na Senjaku, zadužbine Perse Milenković' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/senjak_gal_1.jpg',
                'caption' => 'Klesani kameni portal sa mozaikom Presvete Bogorodice i ktitorskim natpisom iz 1936. godine na ulazu u crkvu' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/senjak_gal_2.jpg',
                'caption' => 'Konak manastira Vavedenje sa zvonikom u mirnom ambijentu beogradskog naselja Senjak' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/senjak_gal_3.jpg',
                'caption' => 'Zidna freska Svetog Save Srpskog u unutrašnjosti hrama manastira Vavedenje' . "\n" . '<small>*(Izvor: commons.wikimedia.org)*</small>',
                'sort_order' => 4,
            ],
        ],
    ],

    // 19. SLANCI (ID 19)
    19 => [
        'lat' => 44.795357,
        'lng' => 20.584164,
        'card_image' => 'images/monasteries/slanci.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/slanci.jpg',
                'caption' => 'Crkva Svetog arhiđakona Stefana u manastiru Slanci, metoh manastira Hilandara, sa zvonikom i uređenom portom' . "\n" . '<small>*(Izvor: manastirisrbije.com)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/slanci_gal_1.jpg',
                'caption' => 'Bočna fasada manastirske crkve u Slancima sa lučnim prozorima i redom zasađenih tuja' . "\n" . '<small>*(Izvor: manastirisrbije.com)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/slanci_gal_2.jpg',
                'caption' => 'Pogled na crkvu Svetog Stefana sa oltarske apside i kupolom u manastiru Slanci' . "\n" . '<small>*(Izvor: manastirisrbije.com)*</small>',
                'sort_order' => 3,
            ],
        ],
    ],

    // 20. TROJERUČICA (ID 20)
    20 => [
        'lat' => 44.616434,
        'lng' => 20.586341,
        'card_image' => 'images/monasteries/trojerucica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/trojerucica.jpg',
                'caption' => 'Crkva brvnara posvećena Bogorodici Trojeručici u manastiru Trojeručica u Ripnju pod Avalom' . "\n" . '<small>*(Izvor: manastiri-crkve.com)*</small>',
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/trojerucica_gal_1.jpg',
                'caption' => 'Drvena crkva brvnara posvećena Bogorodici Trojeručici sa tradicionalnim šindranim krovom' . "\n" . '<small>*(Izvor: manastiri-crkve.com)*</small>',
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/trojerucica_gal_2.jpg',
                'caption' => 'Manastirski konak i detalji drvene krovne konstrukcije crkve brvnare u Ripnju' . "\n" . '<small>*(Izvor: manastiri-crkve.com)*</small>',
                'sort_order' => 3,
            ],
        ],
    ],
];

foreach ($data as $mId => $info) {
    $m = Monastery::find($mId);
    if (!$m) {
        echo "[ERROR] Monastery ID $mId not found!\n";
        continue;
    }

    echo "Updating Monastery [ID $mId] {$m->name} ({$m->slug})...\n";

    // 1. Update coordinates and card image
    $m->lat = $info['lat'];
    $m->lng = $info['lng'];
    $m->latitude = $info['lat'];
    $m->longitude = $info['lng'];
    $m->image_url = $info['card_image'];
    $m->save();

    // 2. Refresh gallery images
    MonasteryImage::where('monastery_id', $mId)->delete();

    foreach ($info['images'] as $imgData) {
        MonasteryImage::create([
            'monastery_id' => $mId,
            'url' => $imgData['url'],
            'caption' => $imgData['caption'],
            'sort_order' => $imgData['sort_order'],
        ]);
    }
}

// Ensure both copies are identical
if (file_exists(__DIR__ . '/storage/database.sqlite')) {
    copy(__DIR__ . '/storage/database.sqlite', __DIR__ . '/database/database.sqlite');
    echo "\nDatabase synchronized to database/database.sqlite successfully!\n";
}

echo "\nAll 20 monasteries updated successfully in database!\n";
