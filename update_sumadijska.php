<?php

/**
 * SISTEMSKO ČIŠĆENJE I SINHRONIZACIJA - EPARHIJA ŠUMADIJSKA (ID 4)
 * Pravoslavni Svetionik — Master rad
 * Verifikovane sve slike i opisi (21 manastir, 57 slika)
 */

use App\Models\Monastery;
use App\Models\MonasteryImage;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "====================================================================\n";
echo "POKRETANJE REVIZIJE I AŽURIRANJA - EPARHIJA ŠUMADIJSKA (ID 4)\n";
echo "====================================================================\n\n";

$srcWiki = '<br><small style="color: #eab308;"><em>(Izvor: commons.wikimedia.org)</em></small>';
$srcMan  = '<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>';

$updates = [
    // 185. Brezovac
    185 => [
        'main_image' => 'images/monasteries/brezovac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/brezovac.jpg',
                'caption' => 'Crkva Svetog arhangela Mihaila sa polukružnom apsidom i krovnim zvonikom u manastiru Brezovac na padinama Venčaca' . $srcWiki,
                'sort_order' => 1,
            ],
        ]
    ],

    // 186. Denkovac
    186 => [
        'main_image' => 'images/monasteries/denkovac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/denkovac.jpg',
                'caption' => 'Crkva Uspenja Presvete Bogorodice sa lučnim krovom, kupolom, rozetom i cvetnim lejama u uređenoj porti manastira Denkovac' . $srcMan,
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/denkovac_gal_2.jpg',
                'caption' => 'Južna strana crkve Uspenja Presvete Bogorodice sa kupolom, apsidom, popločanom stazom oivičenom cvetnom lejom i šumom u pozadini' . $srcMan,
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/denkovac_gal_3.jpg',
                'caption' => 'Zidana kapela od opeke i kamena sa kupolom na izdignutom kamenom platou u prostranoj travnatoj porti manastira Denkovac' . $srcMan,
                'sort_order' => 3,
            ],
        ]
    ],

    // 187. Divostin
    187 => [
        'main_image' => 'images/monasteries/divostin.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/divostin.jpg',
                'caption' => 'Bela crkva Svetog cara Konstantina i carice Jelene u manastiru Divostin sa visokim baroknim zvonikom, kupolom i lučnim tremom na stubovima' . $srcMan,
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/divostin_gal_1.jpg',
                'caption' => 'Pogled na hram Svetih cara Konstantina i carice Jelene kroz visoke četinare i drveće u travnatoj porti manastira Divostin' . $srcMan,
                'sort_order' => 2,
            ],
        ]
    ],

    // 188. Dobrovodica
    188 => [
        'main_image' => 'images/monasteries/dobrovodica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/dobrovodica.jpg',
                'caption' => 'Crkva Vaznesenja Gospodnjeg u manastiru Dobrovodica sa fasadom od crvene i bele opeke, visokim zvonikom i kupolom' . $srcMan,
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/dobrovodica_gal_1.jpg',
                'caption' => 'Mala kapela sa izvorom vode i manastirski objekat sa krstovima u porti manastira Dobrovodica u suton' . $srcMan,
                'sort_order' => 2,
            ],
        ]
    ],

    // 189. Drača
    189 => [
        'main_image' => 'images/monasteries/draca.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/draca.jpg',
                'caption' => 'Kompleks manastira Drača sa crkvom Svetog Nikole od žućkastog tesanog kamena, belim baroknim zvonikom, konakom sa drvenim doksatima i travnatom portom' . $srcWiki,
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/draca_gal_1.jpg',
                'caption' => 'Zapadno pročelje crkve Svetog Nikole u manastiru Drača sa belim baroknim zvonikom, lučnim ulaznim portalom i cvetnim lejama sa ružama' . $srcWiki,
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/draca_gal_2.jpg',
                'caption' => 'Posetilac ispred crkve Svetog Nikole u manastiru Drača, sa freskom patrona iznad kamenog portala, belim zvonikom i saksijama sa cvećem' . $srcWiki,
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/draca_gal_3.jpg',
                'caption' => 'Raskošno izrezbarene Carske dveri sa scenom Blagovesti, prestone ikone Bogorodice sa Hristom i Isusa Hrista Pantokratora na ikonostasu crkve Svetog Nikole' . $srcWiki,
                'sort_order' => 4,
            ],
        ]
    ],

    // 190. Grnčarica
    190 => [
        'main_image' => 'images/monasteries/grncarica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/grncarica.jpg',
                'caption' => 'Žuta crkva Svetog Nikole u manastiru Grnčarica sa masivnim zvonikom, kupolom i rascvetalim ružičastim dalijama u prvom planu' . $srcWiki,
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/grncarica_gal_1.jpg',
                'caption' => 'Pogled na obnovljenu crkvu Svetog Nikole u manastiru Grnčarica sa svetlosivom fasadom, kamenim soklom, zvonikom i kupolom iz porte' . $srcWiki,
                'sort_order' => 2,
            ],
        ]
    ],

    // 191. Ivković
    191 => [
        'main_image' => 'images/monasteries/ivkovic.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/ivkovic.jpg',
                'caption' => 'Pogled odozgo na belu crkvu Rođenja Presvete Bogorodice u manastiru Ivković, sa novim tamnim krovom, kupolom i šumovitim padinama u pozadini' . $srcWiki,
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/ivkovic_gal_1.jpg',
                'caption' => 'Pogled na crkvu Svete Trojice i Despotovu kulu manastira Manasija (slika pridružena galeriji manastira Ivković)' . $srcWiki,
                'sort_order' => 2,
            ],
        ]
    ],

    // 192. Jaković
    192 => [
        'main_image' => 'images/monasteries/jakovic.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/jakovic.jpg',
                'caption' => 'Mala bela crkva Svetih apostola Petra i Pavla sa dvovodnim krovovima od crepa, polukružnom apsidom i krstovima na travnatoj zaravni manastira Jaković' . $srcWiki,
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/jakovic_gal_1.jpg',
                'caption' => 'Stari i novi manastirski konak sa tremovima, drvenim ogradama i velikim stablom u dvorištu manastira Jaković' . $srcWiki,
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/jakovic_gal_2.jpg',
                'caption' => 'Ulazna zapadna fasada crkve manastira Jaković sa lučnim prozorima, ikonom Svetog apostola Jakova iznad vrata i betonskim platoom' . $srcWiki,
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/jakovic_gal_3.jpg',
                'caption' => 'Spomen-obeležje, spratni konak i zidani zvonik sa drvenim spratom na padini manastirskog imanja Jaković' . $srcWiki,
                'sort_order' => 4,
            ],
        ]
    ],

    // 193. Jošanica
    193 => [
        'main_image' => 'images/monasteries/josanica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/josanica.jpg',
                'caption' => 'Pogled odozgo na crkvu Svetog Nikole u manastiru Jošanica, sa mozaičkom ikonom iznad portala, kupolom, drvenom zvonarom i uređenom portom' . $srcMan,
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/josanica_gal_1.jpg',
                'caption' => 'Crkva Svetog Nikole u manastiru Jošanica sa dve zidane kupole od opeke i kamena, lučnim arkadama na fasadi i freskom Svetog Nikole iznad ulaza' . $srcWiki,
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/josanica_gal_2.jpg',
                'caption' => 'Stare kamene nadgrobne ploče u porti ispred zapadnog ulaza u crkvu Svetog Nikole u manastiru Jošanica' . $srcWiki,
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/josanica_gal_3.jpg',
                'caption' => 'Panoramski pogled na manastir Jošanica, crkvu sa kupolom, konak i groblje kroz divlje rastinje sa obližnjeg brda' . $srcWiki,
                'sort_order' => 4,
            ],
        ]
    ],

    // 194. Kalenić
    194 => [
        'main_image' => 'images/monasteries/kalenic.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/kalenic.jpg',
                'caption' => 'Pogled odozdo na oltarsku apsidu i kupolu crkve Vavedenja Presvete Bogorodice u manastiru Kalenić sa moravskim šahovskim poljima, rozetama i prepletima' . $srcWiki,
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/kalenic_gal_1.jpg',
                'caption' => 'Čuvena reljefna kamena bifora manastira Kalenić sa prikazom borbe čoveka sa lavom, dvoglavim orlom i bogatim moravskim prepletom' . $srcWiki,
                'sort_order' => 2,
            ],
        ]
    ],

    // 195. Pavlovac
    195 => [
        'main_image' => 'images/monasteries/pavlovac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/pavlovac.jpg',
                'caption' => 'Kamena crkva Svetog Nikole sa kupolom i poluobličastim krovovima, u okruženju jesenje šume i otkopanih ostataka srednjovekovnih manastirskih zdanja na Kosmaju' . $srcWiki,
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/pavlovac_gal_1.jpg',
                'caption' => 'Pogled sa uzvišenja na manastirski kompleks Pavlovac sa crkvom Svetog Nikole, arheološkim ostacima kamenih konaka i šumom u poznu jesen' . $srcWiki,
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/pavlovac_gal_2.jpg',
                'caption' => 'Zapadna kamena fasada crkve Svetog Nikole sa klesanim ulaznim portalom, drvenim vratima sa krstom, lučnom nišom i kupolom pod plavim nebom' . $srcWiki,
                'sort_order' => 3,
            ],
        ]
    ],

    // 196. Petkovica Rudnička (Stragari)
    196 => [
        'name' => 'Manastir Petkovica (Rudnička)',
        'slug' => 'petkovica-rudnicka',
        'city' => 'Stragari, Kragujevac',
        'region' => 'Šumadija',
        'ktitor' => 'Kralj Stefan Dragutin Nemanjić (kraj 13. veka; obnovljen 2004)',
        'godina_izgradnje' => 'Kraj 13. veka (oko 1285. godine)',
        'lat' => 44.1561,
        'lng' => 20.6125,
        'latitude' => 44.1561,
        'longitude' => 20.6125,
        'description' => "OPŠTI PODACI:\nManastir Petkovica Rudnička nalazi se u duhovnom okrilju Eparhije šumadijske na severoistočnim obroncima planine Rudnik, iznad reke Srebrenice u ataru sela Stragari kod Kragujevca. Hram je posvećen Prepodobnoj mati Paraskevi (Svetoj Petki). Predstavlja izuzetno vrednu srednjovekovnu svetinju nastalu krajem XIII veka, u doba kralja Stefana Dragutina Nemanjića. Tokom vekova delio je burnu sudbinu srpskog naroda ovog rudarskog kraja, a nakon dugog perioda zapusteća u potpunosti je obnovljen i oživljen početkom XXI veka.\n\nISTORIJA:\nPrvobitna crkva manastira Petkovica sagrađena je u poslednjoj deceniji XIII veka u vreme vladavine kralja Dragutina, kada je ovaj kraj bio važno privredno i rudarsko središte. Tokom XIV veka, u doba kneza Lazara i despota Stefana Lazarevića, manastir je bio snažan duhovni centar sa muškim monaškim bratstvom i bio je usko povezan sa obližnjim utvrđenim gradom Srebrenicom. O razvijenom monaškom životu i ugledu manastira svedoči dragocena nadgrobna ploča monaha Domentijana iz 1379. godine, pronađena u crkvenoj porti, kao i grobovi iz 1443. godine. Nakon konačnog pada Srpske despotovine 1459. godine pod osmansku vlast, manastir je stradao i opusteo, ali se narod vekovima okupljao na njegovim ruševinama moleći se Svetoj Petki. Prva arheološka i konzervatorska istraživanja započeta su 1968. godine, dok je sveobuhvatna obnova hrama i izgradnja novog sestrinskog konaka završena 2004. godine, kada je manastir ponovo vaspostavljen kao ženski opštežiteljni manastir.\n\nARHITEKTURA I UMETNOST:\nCrkva Svete Petke je jednobrodna građevina sa polukružnom oltarskom apsidom na istoku i masivnom spratnom kulom-zvonarom na zapadnoj strani, koja je dozidana krajem XIV ili početkom XV veka. Građena je od lomljenog i pritesanog lokalnog kamena i sige. U unutrašnjosti hrama posebnu retkost i spomeničku vrednost predstavlja sačuvani zidani kameni ikonostas iz XIII veka, koji predstavlja jedinstven primer rane faze razvoja oltarskih pregrada u srpskom srednjovekovnom graditeljstvu. U crkvi su otkrivena dva hronološka sloja fresaka – stariji sloj s kraja XIII veka, od kojeg je sačuvano monumentalno poprsje Hrista Pantokratora u kaloti apside, i mlađi sloj iz druge polovine XIV veka, sa izraženim odlikama moravske škole slikarstva i prefinjenim likovnim izrazom.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nDanas je Manastir Petkovica Rudnička mirna monaška oaza i važno hodočasničko mesto Šumadije. Sestrinstvo manastira neguje molitveni život, bogoslužbeni poredak i tradiciju izrade crkvenih rukotvorina. O praznicima Svete Petke (27. oktobra i 8. avgusta – Trnova Petka) manastir okuplja veliki broj vernika iz Kragujevca, Topole, Šumadije i cele Srbije, koji dolaze da zatraže zastupništvo i isceljenje pred ikonom i moštima Prepodobne mati Paraskeve.",
        'excerpt' => 'Manastir Petkovica Rudnička nalazi se u duhovnom okrilju Eparhije šumadijske na obroncima planine Rudnik kod Stragara.',
        'description_short' => 'Manastir Petkovica Rudnička nalazi se u duhovnom okrilju Eparhije šumadijske na obroncima planine Rudnik kod Stragara.',
        'history' => 'Manastir Petkovica podno Rudnika sagrađen je krajem 13. veka u doba kralja Dragutina Nemanjića. Tokom 14. i 15. veka bio je značajan duhovni centar sa muškim monaškim bratstvom, o čemu svedoče pronađene nadgrobne ploče u porti, među kojima i ploča monaha Domentijana iz 1379. godine. Padom Despotovine manastir je opusteo, ali je kult Svete Petke ostao duboko ukorenjen u narodu ovog kraja. Arheološko-konzervatorski radovi sprovedeni su 1968. godine, a potpuna obnova hrama i izgradnja novog sestrinskog konaka završeni su 2004. godine kada je manastir ponovo oživeo.',
        'architecture' => 'Crkva Svete Petke je jednobrodna građevina sa polukružnom oltarskom apsidom na istoku i dvospratnom kulom sa zvonarom na zapadu. Posebnu retkost i dragocenost manastira predstavlja kameni ikonostas iz 13. veka, koji svedoči o starijem sloju srpske crkvene arhitekture pre razvoja drvenih ikonostasa. U hramu su sačuvana dva sloja fresaka – stariji sloj s kraja 13. veka (poprsje Hrista Pantokratora) i mlađi sloj iz druge polovine 14. veka sa odlikama moravske škole.',
        'main_image' => 'images/monasteries/petkovica-stragari.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/petkovica-stragari.jpg',
                'caption' => 'Kamena crkva Svete Petke u Stragarima sa polukružnom apsidom, zvonikom, popločanim platoom i rascvetalim crvenim ružama' . $srcWiki,
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/petkovica-stragari_gal_1.jpg',
                'caption' => 'Zapadna i južna kamena fasada crkve Svete Petke sa freskom zaštitnice iznad ulaza, masivnim zvonikom i cvetnim žbunovima' . $srcWiki,
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/petkovica-stragari_gal_2.jpg',
                'caption' => 'Oltarska apsida i severna strana crkve Svete Petke u Stragarima sa kamenim zidom i krovnim zvonikom' . $srcWiki,
                'sort_order' => 3,
            ],
        ]
    ],

    // 197. Pinosava
    197 => [
        'main_image' => 'images/monasteries/pinosava.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/pinosava.jpg',
                'caption' => 'Žuta barokna crkva Svetog arhangela Gavrila u manastiru Pinosava sa polukružnom apsidom i visokim vitkim zvonikom okružena drvećem' . $srcWiki,
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/pinosava_gal_1.jpg',
                'caption' => 'Visoki višespratni drveni ikonostas sa Raspećem Hristovim, oslikanim svodom i kristalnim lusterom u crkvi manastira Pinosava' . $srcWiki,
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/pinosava_gal_2.jpg',
                'caption' => 'Celivajuća ikona ukrašena cvećem na analoju ispred pozlaćenih Carskih dveri i ikonostasa crkve manastira Pinosava' . $srcWiki,
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/pinosava_gal_3.jpg',
                'caption' => 'Stara kamena nadgrobna ploča sa krsnim obeležjem i reljefom lobanje i ukrštenih kostiju (Adamova glava) u porti manastira Pinosava' . $srcWiki,
                'sort_order' => 4,
            ],
        ]
    ],

    // 198. Prekopeča
    198 => [
        'main_image' => 'images/monasteries/prekopeca.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/prekopeca.jpg',
                'caption' => 'Crkva Preobraženja Gospodnjeg u manastiru Prekopeča sa žutom fasadnom opekom, baroknim zvonikom i manastirskim konakom u šumovitom zaleđu' . $srcWiki,
                'sort_order' => 1,
            ],
        ]
    ],

    // 199. Preradovac
    199 => [
        'main_image' => 'images/monasteries/preradovac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/preradovac.jpg',
                'caption' => 'Pogled na manastirski kompleks Preradovac sa belom crkvom, drvenom zvonarom sa crvenim krovom, konakom i živopisnim šumadijskim pejzažem' . $srcWiki,
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/preradovac_gal_2.jpg',
                'caption' => 'Bela crkva manastira Preradovac sa mozaičkom ikonom Svetog Simeona Mirotočivog iznad portala, rozetom i kamenim popločanjem pod plavim nebom' . $srcWiki,
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/preradovac_gal_3.jpg',
                'caption' => 'Novi manastirski konak u izgradnji sa krovom od crvenog crepa, drvenom ogradom na tremu i kamenim soklom na padini manastira Preradovac' . $srcWiki,
                'sort_order' => 3,
            ],
        ]
    ],

    // 200. Raletinac
    200 => [
        'main_image' => 'images/monasteries/raletinac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/raletinac.jpg',
                'caption' => 'Crkva Svetih apostola Petra i Pavla u manastiru Raletinac sa tremom od crvene fasadne cigle, rozetom, kovanim rešetkama i zvonikom' . $srcWiki,
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/raletinac_gal_1.jpg',
                'caption' => 'Pogled sa uređene travnate porte na južnu fasadu crkve i manastirski konak u podnožju šumovitog brda' . $srcWiki,
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/raletinac_gal_2.jpg',
                'caption' => 'Crkva manastira Raletinac uokvirena dvema visokim vitkim tujama i gustom šumom u zaleđu' . $srcWiki,
                'sort_order' => 3,
            ],
        ]
    ],

    // 201. Ramaća
    201 => [
        'main_image' => 'images/monasteries/ramaca.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/ramaca.jpg',
                'caption' => 'Kamena crkva Svetih Konstantina i Jelene u Ramaći sa vitkom kamenom kupolom, limenim krovom i drvenom pripratom pokrivenom šindrom' . $srcWiki,
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/ramaca_gal_1.jpg',
                'caption' => 'Pogled na crkvu manastira Ramaća i masivnu kamenu kulu sa crepom u manastirskom dvorištu' . $srcWiki,
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/ramaca_gal_2.jpg',
                'caption' => 'Panoramski pogled na manastirski kompleks Ramaća, seosko groblje i prostrana brda Šumadije pod oblačnim nebom' . $srcWiki,
                'sort_order' => 3,
            ],
        ]
    ],

    // 202. Sarinac
    202 => [
        'main_image' => 'images/monasteries/sarinac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sarinac.jpg',
                'caption' => 'Crkva Vaznesenja Gospodnjeg u manastiru Sarinac sa lučnim portalom i mozaikom, drvenom zvonarom sa crvenim krovom i cvetnim lejama u porti' . $srcWiki,
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/sarinac_gal_1.jpg',
                'caption' => 'Prilazni makadamski put ka manastiru Sarinac sa kamenim potpornim zidom sa leve strane, konakom, crkvom i zvonikom podno šumovitog brda' . $srcWiki,
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/sarinac_gal_2.jpg',
                'caption' => 'Pogled kroz otvorenu kovanu manastirsku kapiju sa masivnim kamenim stubovima ka prilaznom putu, crkvi i zvonari manastira Sarinac' . $srcWiki,
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/sarinac_gal_3.jpg',
                'caption' => 'Široki prilaz sa krivinom makadamskog puta uz visoki kameni potporni zid ka manastirskom konaku, crkvi i zvonari manastira Sarinac' . $srcWiki,
                'sort_order' => 4,
            ],
        ]
    ],

    // 203. Tresije
    203 => [
        'main_image' => 'images/monasteries/tresije.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/tresije.jpg',
                'caption' => 'Panoramski snimak iz vazduha manastira Tresije na Kosmaju sa kamenom crkvom Svetih arhangela, velikim konakom sa zvonikom i prostranom zelenom portom' . $srcWiki,
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/tresije_gal_1.jpg',
                'caption' => 'Pogled sa prilazne staze na kamenu crkvu Svetih arhangela i konak manastira Tresije, okružene cvetnim lejama, travnjakom i gustom šumom' . $srcWiki,
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/tresije_gal_2.jpg',
                'caption' => 'Kamena crkva Svetih arhangela sa kupolom i pozlaćenim krstom u manastiru Tresije, uokvirena zelenilom kosmajske šume i ukrasnim žbunjem' . $srcWiki,
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/tresije_gal_3.jpg',
                'caption' => 'Kamena staza oivičena rascvetalim ružama i travnjakom koja vodi ka crkvi Svetih arhangela u manastirskom dvorištu Tresija' . $srcWiki,
                'sort_order' => 4,
            ],
        ]
    ],

    // 204. Manastir Ćelije (Lajkovac)
    204 => [
        'name' => 'Manastir Ćelije (Lajkovac)',
        'slug' => 'celije-lajkovac',
        'city' => 'Ćelije, Lajkovac',
        'region' => 'Šumadija',
        'ktitor' => 'Srednjovekovna zadužbina (obnova 1923/24. arh. Dragutin Maslać; vaspostavljen 2006)',
        'godina_izgradnje' => '14. vek / 1923–1924.',
        'lat' => 44.3392,
        'lng' => 20.1872,
        'latitude' => 44.3392,
        'longitude' => 20.1872,
        'description' => "OPŠTI PODACI:\nManastir Svetog velikomučenika Georgija u selu Ćelije kod Lajkovca nalazi se u duhovnom okrilju Eparhije šumadijske, u slivu reke Kolubare i na obroncima istorijskog brda Čovka. Predstavlja jedinstvenu i poštovanu pravoslavnu svetinju podignutu na temeljima srednjovekovnog manastira iz XIV i XV veka, sa spomen-crkvom i kosturnicom osveštanom 1924. godine. Manastir je vekovni čuvar molitvenog sećanja na srpske i savezničke ratnike poginule u Kolubarskoj bici 1914. godine, a 2006. godine je u potpunosti vaspostavljen i oživljen kao aktivno monaško stecište.\n\nISTORIJA:\nArheološka istraživanja sprovedena 1923. i 1924. godine, a detaljno nastavljena 2005. godine pod nadzorom Zavoda za zaštitu spomenika kulture, potvrdila su postojanje starog srednjovekovnog manastirskog kompleksa iz doba despotovine Stefana Lazarevića i Đurđa Brankovića. Na lokalitetu su pronađeni ostaci crkvenih temelja i dva značajna groba – ktitorski grob samog osnivača svetinje i grob uglednog srpskog vlastelina iz druge polovine XV veka sa dragocenim srebrnim pucadima. Tokom Prvog svetskog rata, brda Čovka i Vrače u okolini manastira bila su poprište nekih od najžešćih i najkrvavijih okršaja Kolubarske bitke. U znak večnog spomena na hiljade stradalih vojnika, u periodu od 1923. do 1924. godine podignuta je spomen-crkva sa spomen-kosturnicom. Nakon decenija pustošenja, manastir je 29. jula 2006. godine svečano vaspostavljen i obnovljen blagoslovom episkopa šumadijskog Jovana osvećenjem novog manastirskog konaka.\n\nARHITEKTURA I UMETNOST:\nSpomen-crkva Svetog velikomučenika Georgija sagrađena je u prepoznatljivom srpsko-vizantijskom stilu prema nacrtima proslavljenog arhitekte Dragutina Maslaća. Hram je skladna jednobrodna građevina sa polukružnom oltarskom apsidom, naglašenim lučnim pilastrima i dekorativnim prozorima. U donjem delu hrama, ispod samog naosa, smeštena je prostrana kripta-kosturnica sa posmrtnim ostacima izginulih ratnika. Unutrašnjost crkve krasi impozantan plavi duborezni ikonostas sa bogato pozlaćenom ornamentikom i kanonskim ikonama svetitelja, Gospodnjih praznika i Raspeća Hristovog. U porti se nalazi novoizgrađeni konak prilagođen potrebama monaškog života i hodočasnika, okružen uređenim stazama i zelenilom.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nDanas je Manastir Ćelije kod Lajkovca jedini aktivni manastir u Kolubarsko-posavskom namesništvu i predstavlja živo liturgijsko i molitveno središte Šumadije. Pored redovnih bogosluženja, u manastiru se svake godine služe svečani parastosi i arhijerejske liturgije u čast junaka Kolubarske bitke, čime ovo mesto spaja duboku pravoslavnu duhovnost, nacionalno pamćenje i poštovanje prema precima koji su položili živote za slobodu otadžbine.",
        'excerpt' => 'Manastir Svetog Georgija u selu Ćelije kod Lajkovca nalazi se u Eparhiji šumadijskoj, u blizini ušća reka Ljig i Kolubara podno brda Čovka.',
        'description_short' => 'Manastir Svetog Georgija u selu Ćelije kod Lajkovca nalazi se u Eparhiji šumadijskoj, u blizini ušća reka Ljig i Kolubara podno brda Čovka.',
        'history' => 'Arheološkim istraživanjima na lokalitetu crkve u Ćelijama otkriveni su ostaci srednjovekovnog hrama i dva značajna groba. Prvi je ktitorski grob samog osnivača manastira iz poznog srednjeg veka, dok je u drugom grobu iz kraja 15. veka pronađeno devet masivnih srebrnih dugmadi, što svedoči o sahranjivanju ugledne vlasteoske ličnosti. Nakon viševekovnog pustošenja, na temeljima stare crkve 1923. godine započeta je gradnja spomen-kosturnice i hrama Svetog Đorđa. Odlukom Eparhije šumadijske, 29. jula 2006. godine osveštan je novoizgrađeni konak i manastir je ponovo oživeo kao jedini manastir u Kolubarsko-posavskom namesništvu.',
        'architecture' => 'Današnja spomen-crkva Svetog Đorđa sagrađena je u srpsko-vizantijskom stilu prema projektu uglednog arhitekte Dragutina Maslaća. To je jednobrodna građevina sa polukružnom oltarskom apsidom i ugrađenom kriptom-kosturnicom ispod naosa. Zidana je od pritesanog kamena i opeke sa dekorativnim lučnim otvorima. Unutrašnjost krasi prelepi plavi duborezni ikonostas sa pozlaćenim detaljima i ikonama.',
        'main_image' => 'images/monasteries/celije-lajkovac.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/celije-lajkovac.jpg',
                'caption' => 'Spomen-crkva Svetog velikomučenika Georgija i kosturnica ratnika Kolubarske bitke sa belom fasadom i limenim krovom u manastiru Ćelije kod Lajkovca' . $srcWiki,
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/celije-lajkovac_gal_2.jpg',
                'caption' => 'Raskošni plavi duborezni ikonostas sa pozlaćenom ornamentikom, ikonama praznika i crvenom zavesom na Carskim dverima u crkvi manastira Ćelije' . $srcWiki,
                'sort_order' => 2,
            ],
        ]
    ],

    // 205. Sibnica
    205 => [
        'name' => 'Manastir Sibnica',
        'slug' => 'sibnica',
        'main_image' => 'images/monasteries/sibnica.jpg',
        'images' => [
            [
                'url' => 'images/monasteries/sibnica.jpg',
                'caption' => 'Crkva Svete Petke u manastiru Sibnica sa belom fasadom, crvenim krovom od crepa, četvorougaonim zvonikom i lučnim portalom sa stubićima' . $srcWiki,
                'sort_order' => 1,
            ],
            [
                'url' => 'images/monasteries/sibnica_gal_1.jpg',
                'caption' => 'Građevinski radovi na dogradnji i oblaganju crkve Svete Petke kamenom i opekom u manastiru Sibnica, sa gomilama građevinskog materijala' . $srcWiki,
                'sort_order' => 2,
            ],
            [
                'url' => 'images/monasteries/sibnica_gal_2.jpg',
                'caption' => 'Zidanje novog kamenog i opečnog omotača oko crkve Svete Petke i objekat sa lučnim tremom u manastiru Sibnica pod plavim nebom sa oblacima' . $srcWiki,
                'sort_order' => 3,
            ],
            [
                'url' => 'images/monasteries/sibnica_gal_3.jpg',
                'caption' => 'Krupni plan zidanih kamenih i opečnih zidova sa armaturnim stubovima tokom rekonstrukcije crkve Svete Petke u manastiru Sibnica' . $srcWiki,
                'sort_order' => 4,
            ],
        ]
    ],
];

echo "1. Ažuriranje primarne baze podataka (database/database.sqlite):\n";
DB::beginTransaction();

try {
    foreach ($updates as $monasteryId => $data) {
        $monastery = Monastery::find($monasteryId);
        if (!$monastery) {
            echo "  [UPOZORENJE] Manastir ID {$monasteryId} nije pronađen!\n";
            continue;
        }

        $monastery->image_url = $data['main_image'];
        if (isset($data['name'])) $monastery->name = $data['name'];
        if (isset($data['slug'])) $monastery->slug = $data['slug'];
        if (isset($data['city'])) $monastery->city = $data['city'];
        if (isset($data['region'])) $monastery->region = $data['region'];
        if (isset($data['description'])) $monastery->description = $data['description'];
        if (isset($data['excerpt'])) $monastery->excerpt = $data['excerpt'];
        if (isset($data['description_short'])) $monastery->description_short = $data['description_short'];
        if (isset($data['history'])) $monastery->history = $data['history'];
        if (isset($data['architecture'])) $monastery->architecture = $data['architecture'];
        if (isset($data['ktitor'])) $monastery->ktitor = $data['ktitor'];
        if (isset($data['godina_izgradnje'])) $monastery->godina_izgradnje = $data['godina_izgradnje'];
        if (isset($data['lat'])) $monastery->lat = $data['lat'];
        if (isset($data['lng'])) $monastery->lng = $data['lng'];
        if (isset($data['latitude'])) $monastery->latitude = $data['latitude'];
        if (isset($data['longitude'])) $monastery->longitude = $data['longitude'];
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

// 2. Sinhronizacija storage baze podataka
$storageDbPath = __DIR__ . '/storage/database.sqlite';
if (file_exists($storageDbPath)) {
    echo "2. Ažuriranje storage baze podataka ($storageDbPath):\n";
    $pdo = new PDO("sqlite:$storageDbPath");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->beginTransaction();

    foreach ($updates as $monasteryId => $data) {
        $fields = ['image_url = :img', 'image = :img'];
        $params = [
            'img' => $data['main_image'],
            'id' => $monasteryId
        ];
        
        $optFields = ['name', 'slug', 'city', 'region', 'description', 'excerpt', 'description_short', 'history', 'architecture', 'ktitor', 'godina_izgradnje', 'lat', 'lng', 'latitude', 'longitude'];
        foreach ($optFields as $f) {
            if (isset($data[$f])) {
                $fields[] = "$f = :$f";
                $params[$f] = $data[$f];
            }
        }
        
        $sql = "UPDATE monasteries SET " . implode(', ', $fields) . " WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

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
        echo "  [STORAGE AŽURIRAN] [{$monasteryId}]\n";
    }

    $pdo->commit();
    echo "Storage baza je uspešno ažurirana!\n\n";
}

echo "====================================================================\n";
echo "REVIZIJA I SINHRONIZACIJA EPARHIJE ŠUMADIJSKE ZAVRŠENE USPEŠNO!\n";
echo "====================================================================\n";