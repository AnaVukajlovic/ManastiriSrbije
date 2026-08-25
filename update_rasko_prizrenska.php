<?php

/**
 * SISTEMSKO ČIŠĆENJE I SINHRONIZACIJA - EPARHIJA RAŠKO-PRIZRENSKA (ID 2)
 * Pravoslavni Svetionik — Master rad
 * Izvori: commons.wikimedia.org / manastiri.rs / eparhija-prizren.com
 */

use App\Models\Monastery;
use App\Models\MonasteryImage;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "====================================================================\n";
echo "POKRETANJE REVIZIJE I SINHRONIZACIJE ZA EPARHIJU RAŠKO-PRIZRENSKU (ID 2)\n";
echo "====================================================================\n\n";

$src_commons = '<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>';
$src_manastiri = '<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>';
$src_eparhija = '<br><small style="color: #eab308;"><em>(Izvor: eparhija-prizren.com)</em></small>';

// Definicija proverenih podataka, usklađenih slika i preciznih opisa za svih 26 manastira
$eparchy_data = [
    // 112: Manastir Banjska
    112 => [
        'name' => 'Manastir Banjska',
        'card_image' => 'images/monasteries/banjska.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/banjska.jpg',
                'caption' => 'Crkva Svetog Stefana sa prepoznatljivom fasadom od trobojnog mermera i oltarskom apsidom' . $src_commons,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/banjska_gal_1.jpg',
                'caption' => 'Panorama manastirskog kompleksa Banjska sa kamenim bedemima, konakom i hramom podno brda' . $src_commons,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/banjska_gal_2.jpg',
                'caption' => 'Južna i zapadna fasada crkve Svetog Stefana sa kupolom od opeke i ostacima zidina priprate' . $src_commons,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/banjska_gal_3.jpg',
                'caption' => 'Polukružna oltarska apsida hrama od klesanih mermernih blokova sa trodelnim prozorom (triforom)' . $src_commons,
                'sort_order' => 4
            ],
        ]
    ],

    // 113: Manastir Budisavci
    113 => [
        'name' => 'Manastir Budisavci',
        'card_image' => 'images/monasteries/budisavci.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/budisavci.jpg',
                'caption' => 'Srednjovekovna crkva Preobraženja Gospodnjeg sa vitkom kupolom od opeke, novim belim konakom i zvonikom u cvetnoj porti' . $src_commons,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/budisavci_gal_2.jpg',
                'caption' => 'Pogled iz vazduha na crkvu Preobraženja Gospodnjeg, beli konak, zvonik i popločanu portu manastira Budisavci' . $src_commons,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/budisavci_gal_3.jpg',
                'caption' => 'Očuvani fragment srednjovekovne freske u unutrašnjosti hrama sa likom svetitelja sa zlatnim oreolom' . $src_commons,
                'sort_order' => 3
            ],
        ]
    ],

    // 114: Manastir Ceranjska Reka
    114 => [
        'name' => 'Manastir Ceranjska Reka',
        'card_image' => 'images/monasteries/ceranjska-reka.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/ceranjska-reka.jpg',
                'caption' => 'Kamena crkva Svete Petke u Ceranjskoj Reci podno Kopaonika, okružena stoletnim stablima sa lučnim ulazom i freskom iznad vrata' . $src_manastiri,
                'sort_order' => 1
            ],
        ]
    ],

    // 115: Manastir Crna Reka
    115 => [
        'name' => 'Manastir Crna Reka',
        'card_image' => 'images/monasteries/crna-reka.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/crna-reka.jpg',
                'caption' => 'Pećinski manastirski kompleks sa drvenim mostićem na ulazu i tremom, uklesan u strmu vertikalnu liticu' . $src_commons,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/crna-reka_gal_1.jpg',
                'caption' => 'Spratne drvene terase (doksati) i kamena fasada pećinskog hrama Svetih arhangela i isposnice Svetog Petra Koriškog' . $src_commons,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/crna-reka_gal_2.jpg',
                'caption' => 'Drveni most-pasarela i pristupne drvene konstrukcije na litici iznad ponora reke Ponorac' . $src_commons,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/crna-reka_gal_3.jpg',
                'caption' => 'Vertikalni kadar drvenih visećih balkona obraslih mahovinom na steni kanjona Crne Reke' . $src_commons,
                'sort_order' => 4
            ],
        ]
    ],

    // 116: Manastir Devine Vode
    116 => [
        'name' => 'Manastir Devine Vode',
        'card_image' => 'images/monasteries/devine-vode.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/devine-vode.jpg',
                'caption' => 'Pogled sa visine na masivnu kamenu ulaznu kapiju sa mozaikom Bogorodice i crkvu Čudotvorne Ikone Bogorodice Trojeručice' . $src_commons,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/devine-vode_gal_1.jpg',
                'caption' => 'Crkva Bogorodice Trojeručice sa prugastom fasadom od kamena i opeke i novim konakom u pozadini' . $src_commons,
                'sort_order' => 2
            ],
        ]
    ],

    // 117: Manastir Devič
    117 => [
        'name' => 'Manastir Devič',
        'card_image' => 'images/monasteries/devic.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/devic.jpg',
                'caption' => 'Obnovljeni hram Vavedenja Presvete Bogorodice sa visokim kamenim zvonikom pod snegom u Drenici' . $src_commons,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/devic_gal_2.jpg',
                'caption' => 'Pejzaž dreničkog kraja i brdovite okoline sa putem koji vodi prema manastiru Devič kod Srbice' . $src_commons,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/devic_gal_3.jpg',
                'caption' => 'Ruševine porušenog manastirskog konaka pod snegom pre temeljne obnove svetinje' . $src_commons,
                'sort_order' => 3
            ],
        ]
    ],

    // 118: Manastir Draganac
    118 => [
        'name' => 'Manastir Draganac',
        'card_image' => 'images/monasteries/draganac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/draganac.jpg',
                'caption' => 'Pogled iz vazduha na crkvu Svetih arhangela Gavrila i Mihaila, konake i uređenu portu manastira Draganac u Kosovskom Pomoravlju' . $src_commons,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/draganac_gal_1.jpg',
                'caption' => 'Mermerni ktitorski natpis na staroslovenskom jeziku i krstoobrazni otvor iznad zapadnog portala crkve' . $src_commons,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/draganac_gal_2.jpg',
                'caption' => 'Raskošni klesani kameni portal zapadnog ulaza sa reljefom dvoglavog orla, anđela i krsta' . $src_commons,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/draganac_gal_3.jpg',
                'caption' => 'Istorijska arhivska fotografija naroda okupljenog ispred crkve Svetih arhangela u manastiru Draganac' . $src_commons,
                'sort_order' => 4
            ],
        ]
    ],

    // 119: Manastir Pećka Patrijaršija
    119 => [
        'name' => 'Manastir Pećka Patrijaršija',
        'card_image' => 'images/monasteries/pecka-patrijarsija.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/pecka-patrijarsija.jpg',
                'caption' => 'Crvena fasada spojenih crkava Pećke Patrijaršije sa kupolama i Danilovom pripratom podno Rugovske klisure (UNESCO)' . $src_commons,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/pecka-patrijarsija_gal_1.jpg',
                'caption' => 'Pogled na kompleks Pećke Patrijaršije iz dvorišta sa starim stablom šam-duda Svetog Save i popločanim stazama' . $src_commons,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/pecka-patrijarsija_gal_2.jpg',
                'caption' => 'Južna i zapadna vizura Danilove priprate i crkava Pećke Patrijaršije po sunčanom danu' . $src_commons,
                'sort_order' => 3
            ],
        ]
    ],

    // 120: Manastir Prizren (Sveti Arhangeli)
    120 => [
        'name' => 'Manastir Prizren (Sveti Arhangeli)',
        'card_image' => 'images/monasteries/prizren.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/prizren.jpg',
                'caption' => 'Kameni most preko Prizrenske Bistrice, lučna kapija i obnovljeni Dušanov konak u klisuri' . $src_commons,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/prizren_gal_1.jpg',
                'caption' => 'Odlomci klesane mermerne ornamentike i reljefne kamene plastike monumentalnog Dušanovog hrama Svetih arhangela' . $src_commons,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/prizren_gal_2.jpg',
                'caption' => 'Izloženi fragmenti kamenih stubova, kapitela i klesanih reljefa sa ruševina carske lavre' . $src_commons,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/prizren_gal_3.jpg',
                'caption' => 'Mermerni kapiteli sa volutama i klesani kameni elementi pronađeni na lokalitetu manastira' . $src_commons,
                'sort_order' => 4
            ],
        ]
    ],

    // 121: Manastir Sokolica
    121 => [
        'name' => 'Manastir Sokolica',
        'card_image' => 'images/monasteries/sokolica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sokolica.jpg',
                'caption' => 'Panoramski pogled sa visine na manastirski kompleks Sokolica sa konacima, kulom i kamenim obodnim zidinama na brdu Sokolica' . $src_commons,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/sokolica_gal_1.jpg',
                'caption' => 'Crkva Pokrova Presvete Bogorodice od klesanog kamena sa polukružnim limenim svodom pod snegom' . $src_commons,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/sokolica_gal_2.jpg',
                'caption' => 'Pogled sa gornje terase na konake, kulu-zvonik, travnato dvorište i uređene staze manastira Sokolica' . $src_commons,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/sokolica_gal_3.jpg',
                'caption' => 'Lučna manastirska ulazna kapija sa drvenim vratima, kameni bedemi i zvonik u zimskom periodu' . $src_commons,
                'sort_order' => 4
            ],
        ]
    ],

    // 122: Manastir Sopoćani
    122 => [
        'name' => 'Manastir Sopoćani',
        'card_image' => 'images/monasteries/sopocani.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sopocani.jpg',
                'caption' => 'Crkva Svete Trojice u manastiru Sopoćani sa visokim romaničkim zvonikom, spoljnom pripratom i ostacima starih bedema (UNESCO)' . $src_commons,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/sopocani_gal_1.jpg',
                'caption' => 'Pogled na južnu i zapadnu fasadu hrama Svete Trojice sa visokim zvonikom i travnatom portom pod oblačnim nebom' . $src_commons,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/sopocani_gal_2.jpg',
                'caption' => 'Masivna polukružna oltarska apsida od sige i klesanog kamena sa biforom i vencem slepih arkada' . $src_commons,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/sopocani_gal_3.jpg',
                'caption' => 'Vertikalni kadar monumentalnog kamenog zvonika i otvorenog lučnog trema priprate manastira Sopoćani' . $src_commons,
                'sort_order' => 4
            ],
        ]
    ],

    // 123: Manastir Sočanica
    123 => [
        'name' => 'Manastir Sočanica',
        'card_image' => 'images/monasteries/socanica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/socanica.jpg',
                'caption' => 'Crkva Usekovanja glave Svetog Jovana Krstitelja u Sočanici podignuta na steni, sa drvenom konstrukcijom zvonika u porti i šumom u pozadini' . $src_manastiri,
                'sort_order' => 1
            ],
        ]
    ],

    // 124: Manastir Tamnica
    124 => [
        'name' => 'Manastir Tamnica',
        'card_image' => 'images/monasteries/tamnica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/tamnica.jpg',
                'caption' => 'Srednjovekovna crkva Svete Petke (manastir Tamnica) kod Ajnovca zidane tehnikom kamena i opeke sa ostacima zidina priprate' . $src_commons,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/tamnica_gal_1.jpg',
                'caption' => 'Oštećena unutrašnja freska svetitelja arhijereja sa mitrom i omoforom na svodu hrama' . $src_commons,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/tamnica_gal_2.jpg',
                'caption' => 'Očuvani crtež i konture fresaka na zidu crkve Svete Petke u Tamnici' . $src_commons,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/tamnica_gal_3.jpg',
                'caption' => 'Detalj freske arhijereja u belo-crvenoj odeždi sa omoforom i natpisom na zidu crkve Svete Petke' . $src_commons,
                'sort_order' => 4
            ],
        ]
    ],

    // 125: Manastir Ulije
    125 => [
        'name' => 'Manastir Ulije',
        'card_image' => 'images/monasteries/ulije.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/ulije.jpg',
                'caption' => 'Mala kamena crkva Svete Petke u Uliju kod Leposavića sa polukružnom apsidom, popločanom stazom i zvonikom na krovu' . $src_manastiri,
                'sort_order' => 1
            ],
        ]
    ],

    // 126: Manastir Velika Hoča (Sveti Jovan)
    126 => [
        'name' => 'Manastir Velika Hoča (Sveti Jovan)',
        'card_image' => 'images/monasteries/velika-hoca.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/velika-hoca.jpg',
                'caption' => 'Zapadna fasada crkve Svetog Jovana Krstitelja u Velikoj Hoči sa visokim zvonikom na preslicu i bujnim borom u porti' . $src_commons,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/velika-hoca_gal_1.jpg',
                'caption' => 'Crkva Svetog Jovana građena od lomljenog kamena sa krovom od kamenih ploča i travnatom portom' . $src_commons,
                'sort_order' => 2
            ],
        ]
    ],

    // 127: Manastir Visoki Dečani
    127 => [
        'name' => 'Manastir Visoki Dečani',
        'card_image' => 'images/monasteries/visoki-decani.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/visoki-decani.jpg',
                'caption' => 'Monumentalna crkva Vaznesenja Hristovog (Dečani) od mermera u dve boje pod zaštitom UNESCO-a' . $src_commons,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/visoki-decani_gal_1.jpg',
                'caption' => 'Zapadni portal i južna fasada crkve Vaznesenja Hristovog sa romaničko-gotičkom mermernom plastikom i triforom' . $src_commons,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/visoki-decani_gal_2.jpg',
                'caption' => 'Hram manastira Visoki Dečani sa posetiocima na kamenom podnožju fasade i konakom u pozadini' . $src_commons,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/visoki-decani_gal_3.jpg',
                'caption' => 'Južna i zapadna mermerna fasada sa kupolom, arkadnim frizovima i travnatom površinom porte' . $src_commons,
                'sort_order' => 4
            ],
        ]
    ],

    // 128: Manastir Zočište
    128 => [
        'name' => 'Manastir Zočište',
        'card_image' => 'images/monasteries/zociste.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/zociste.jpg',
                'caption' => 'Obnovljena kamena crkva Svetih vrača Kozme i Damjana sa krovom od kamenih ploča, zvonikom i maketom hrama u porti' . $src_commons,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/zociste_gal_1.jpg',
                'caption' => 'Zapadna kamena fasada crkve sa mozaikom svetih besrebrenika Kozme i Damjana iznad lučnog portala' . $src_commons,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/zociste_gal_2.jpg',
                'caption' => 'Južna strana crkve sa kamenim krovnim pločama, cvetnim lejama i stablom jabuke u dvorištu' . $src_commons,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/zociste_gal_3.jpg',
                'caption' => 'Čudotvorna ikona Svetih besrebrenika Kozme i Damjana na drvetu, sa prikazom svetitelja koji drže lekarske kutije i krst' . $src_commons,
                'sort_order' => 4
            ],
        ]
    ],

    // 129: Manastir Đakovica
    129 => [
        'name' => 'Manastir Đakovica',
        'card_image' => 'images/monasteries/djakovica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/djakovica.jpg',
                'caption' => 'Obnovljeni hram Uspenja Presvete Bogorodice u Đakovici sa zvonikom i cvetnim vrtom u ograđenoj porti' . $src_eparhija,
                'sort_order' => 1
            ],
        ]
    ],

    // 130: Manastir Đurđevi Stupovi
    130 => [
        'name' => 'Manastir Đurđevi Stupovi',
        'card_image' => 'images/monasteries/djurdjevi-stupovi.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/djurdjevi-stupovi.jpg',
                'caption' => 'Zapadna fasada crkve Svetog Đorđa sa dva monumentalna stuba (kule) i stepeništem u manastiru Đurđevi Stupovi u Rasu' . $src_commons,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/djurdjevi-stupovi_gal_1.jpg',
                'caption' => 'Pogled na hram Svetog Đorđa sa jugozapada, sa obnovljenim kamenim kulama, portalima i popločanim platoom' . $src_commons,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/djurdjevi-stupovi_gal_2.jpg',
                'caption' => 'Istočna i severna strana crkve sa polukružnom oltarskom apsidom, kupolom i masivnim zvonikom podno brda' . $src_commons,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/djurdjevi-stupovi_gal_3.jpg',
                'caption' => 'Severoistočna strana hrama Svetog Đorđa sa kupolom, apsidom i zapadnom kulom na zelenom platou' . $src_commons,
                'sort_order' => 4
            ],
        ]
    ],

    // 241: Manastir Duboki Potok
    241 => [
        'name' => 'Manastir Duboki Potok',
        'card_image' => 'images/monasteries/duboki-potok.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/duboki-potok.jpg',
                'caption' => 'Crkva Vavedenja Presvete Bogorodice od klesanog kamena sa masivnim kamenim zvonikom i popločanim dvorištem u Ibarskom Kolašinu' . $src_commons,
                'sort_order' => 1
            ],
        ]
    ],

    // 242: Manastir Gorioč
    242 => [
        'name' => 'Manastir Gorioč',
        'card_image' => 'images/monasteries/gorioc.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/gorioc.jpg',
                'caption' => 'Crkva Svetog Nikole u manastiru Gorioč kod Istoka sa prugastim zvonikom i crvenim ružama u prvom planu' . $src_commons,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/gorioc_gal_1.jpg',
                'caption' => 'Pogled na crkvu Svetog Nikole, visoki kameni zvonik i uređeno travnato dvorište manastirskog kompleksa Gorioč' . $src_commons,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/gorioc_gal_2.jpg',
                'caption' => 'Južna strana crkve Svetog Nikole sa zvonikom i četinarima u manastirskoj porti' . $src_commons,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/gorioc_gal_3.jpg',
                'caption' => 'Veliki beli manastirski konak sa crvenim krovom, mansardnim prozorima i živom ogradom podno planine' . $src_commons,
                'sort_order' => 4
            ],
        ]
    ],

    // 243: Manastir Gračanica
    243 => [
        'name' => 'Manastir Gračanica',
        'card_image' => 'images/monasteries/gracanica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/gracanica.jpg',
                'caption' => 'Petokupolna crkva Uspenja Presvete Bogorodice u manastiru Gračanica, remek-delo srpsko-vizantijskog stila kralja Milutina (UNESCO)' . $src_commons,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/gracanica_gal_1.jpg',
                'caption' => 'Pogled na južnu fasadu hrama Uspenja Presvete Bogorodice u Gračanici, sa spoljnom pripratom i kupolama od kamena i opeke' . $src_commons,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/gracanica_gal_2.jpg',
                'caption' => 'Slikoviti pogled na crkvu Uspenja Presvete Bogorodice u Gračanici pod oblačnim nebom sa jugozapada' . $src_commons,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/gracanica_gal_3.jpg',
                'caption' => 'Zapadna fasada lavre Gračanica sa staklenim lučnim otvorima spoljne priprate i skladnim kupolama' . $src_commons,
                'sort_order' => 4
            ],
        ]
    ],

    // 245: Manastir Končul
    245 => [
        'name' => 'Manastir Končul',
        'card_image' => 'images/monasteries/koncul.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/koncul.jpg',
                'caption' => 'Crkva Svetog Nikole u Končulu (Nikoljača) na uzvišenju kod Raške sa belom fasadom i starim grobljem' . $src_commons,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/koncul_gal_1.jpg',
                'caption' => 'Zapadna fasada crkve Svetog Nikole u Končulu sa biforom, polukružnim portalom i starim kamenim nadgrobnim spomenicima na travnatom platou' . $src_commons,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/koncul_gal_2.jpg',
                'caption' => 'Donji ugao crkve Svetog Nikole sa polukružnom oltarskom apsidom, arkadnim frizom i rascvetalim granama u prvom planu' . $src_commons,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/koncul_gal_3.jpg',
                'caption' => 'Pogled iz daljine na manastirski kompleks Končul okružen gustom borovom šumom' . $src_commons,
                'sort_order' => 4
            ],
        ]
    ],

    // 256: Manastir Vračevo
    256 => [
        'name' => 'Manastir Vračevo',
        'card_image' => 'images/monasteries/vracevo.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/vracevo.jpg',
                'caption' => 'Ulazna natkrivena kapija, novi parohijski dom i kamena crkva Svetih besrebrenika Kozme i Damjana u Vračevu kod Leposavića' . $src_commons,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/vracevo_gal_1.jpg',
                'caption' => 'Pogled sa okolnih brda na manastirski kompleks Vračevo u bujnom zelenilu sa crkvom i konakom na uzvišenju' . $src_commons,
                'sort_order' => 2
            ],
        ]
    ],

    // 258: Manastir Tušimlja
    258 => [
        'name' => 'Manastir Tušimlja',
        'card_image' => 'images/monasteries/tusimlja.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/tusimlja.jpg',
                'caption' => 'Kamena crkva Uspenja Presvete Bogorodice u manastiru Tušimlja na brdu podno planine, sa polukružnom apsidom i zvonikom na krovu' . $src_commons,
                'sort_order' => 1
            ],
        ]
    ],

    // 260: Bogorodica Ljeviška
    260 => [
        'name' => 'Bogorodica Ljeviška',
        'card_image' => 'images/monasteries/bogorodica-ljeviska.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/bogorodica-ljeviska.jpg',
                'caption' => 'Srednjovekovna katedralna crkva Bogorodica Ljeviška u Prizrenu (UNESCO) sa prepoznatljivim kupolama i zaštitnom ogradom' . $src_commons,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/bogorodica-ljeviska_gal_1.jpg',
                'caption' => 'Pogled iz ptičje perspektive na crkvu Bogorodica Ljeviška sa zvonikom, pripratom i krovovima starog grada Prizrena' . $src_commons,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/bogorodica-ljeviska_gal_2.jpg',
                'caption' => 'Južna fasada hrama Bogorodice Ljeviške sa otvorenim zvonikom pod snegom iza zaštitne gvozdene ograde' . $src_commons,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/bogorodica-ljeviska_gal_3.jpg',
                'caption' => 'Bogorodica Ljeviška i stara prizrenska kuća pod snegom u zimu, sa uličnom ogradom' . $src_commons,
                'sort_order' => 4
            ],
        ]
    ],
];

// Provera postojanja svih fajlova na disku pre baze
echo "1. Validacija postojanja slika na disku:\n";
$missingCount = 0;
foreach ($eparchy_data as $mId => $mData) {
    $cardPath = __DIR__ . '/public/' . $mData['card_image'];
    if (!file_exists($cardPath)) {
        echo "  [NEDOSTAJE KARTICA] [{$mId}] {$mData['name']}: {$mData['card_image']}\n";
        $missingCount++;
    }
    foreach ($mData['images'] as $img) {
        $imgPath = __DIR__ . '/public/' . $img['url'];
        if (!file_exists($imgPath)) {
            echo "  [NEDOSTAJE GALERIJA] [{$mId}] {$mData['name']}: {$img['url']}\n";
            $missingCount++;
        }
    }
}

if ($missingCount > 0) {
    echo "\nGREŠKA: Nedostaje $missingCount slika na disku! Prekid rada.\n";
    exit(1);
} else {
    echo "  -> Sve slike (100%) postoje na disku!\n\n";
}

// 2. Ažuriranje primarne baze podataka (database/database.sqlite)
echo "2. Ažuriranje primarne baze podataka (database/database.sqlite):\n";
DB::beginTransaction();

try {
    foreach ($eparchy_data as $monasteryId => $data) {
        $monastery = Monastery::find($monasteryId);
        if (!$monastery) {
            echo "  [UPOZORENJE] Manastir ID {$monasteryId} nije pronađen!\n";
            continue;
        }

        if (isset($data['name'])) {
            $monastery->name = $data['name'];
        }

        $monastery->image_url = $data['card_image'];
        $monastery->save();

        MonasteryImage::where('monastery_id', $monasteryId)->delete();

        foreach ($data['images'] as $imgData) {
            MonasteryImage::create([
                'monastery_id' => $monasteryId,
                'url' => $imgData['url'],
                'caption' => $imgData['caption'],
                'sort_order' => $imgData['sort_order'],
            ]);
        }

        echo "  [AŽURIRAN] [{$monasteryId}] {$monastery->name} | Kartica: {$monastery->image_url} | Galerija: " . count($data['images']) . " slika\n";
    }

    DB::commit();
    echo "\nPrimarna baza je uspešno ažurirana!\n\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "Greška pri radu sa primarnom bazom: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Sinhronizacija storage baze podataka
$storageDbPath = __DIR__ . '/storage/database.sqlite';
if (file_exists($storageDbPath)) {
    echo "3. Ažuriranje storage baze podataka ($storageDbPath):\n";
    $pdo = new PDO("sqlite:$storageDbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    foreach ($eparchy_data as $monasteryId => $data) {
        $monastery = Monastery::find($monasteryId);
        $name = $data['name'] ?? ($monastery ? $monastery->name : '');
        
        $stmt = $pdo->prepare("UPDATE monasteries SET name = :name, image_url = :img WHERE id = :id");
        $stmt->execute([
            'name' => $name,
            'img' => $data['card_image'],
            'id' => $monasteryId
        ]);

        $stmtDel = $pdo->prepare("DELETE FROM monastery_images WHERE monastery_id = :id");
        $stmtDel->execute(['id' => $monasteryId]);

        $stmtIns = $pdo->prepare("INSERT INTO monastery_images (monastery_id, url, caption, sort_order, created_at, updated_at) VALUES (:monastery_id, :url, :caption, :sort_order, datetime('now'), datetime('now'))");
        foreach ($data['images'] as $imgData) {
            $stmtIns->execute([
                'monastery_id' => $monasteryId,
                'url' => $imgData['url'],
                'caption' => $imgData['caption'],
                'sort_order' => $imgData['sort_order'],
            ]);
        }
        echo "  [STORAGE AŽURIRAN] [{$monasteryId}] {$name}\n";
    }

    $pdo->commit();
    echo "Storage baza je uspešno ažurirana!\n\n";
}

echo "====================================================================\n";
echo "REVIZIJA I SINHRONIZACIJA EPARHIJE RAŠKO-PRIZRENSKE ZAVRŠENA USPEŠNO!\n";
echo "====================================================================\n";
