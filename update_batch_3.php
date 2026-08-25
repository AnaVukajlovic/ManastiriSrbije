<?php

/**
 * SISTEMSKO ČIŠĆENJE I SINHRONIZACIJA:
 * - EPARHIJA SREMSKA (ID 11)
 * - EPARHIJA BRANIČEVSKA (ID 8)
 * Pravoslavni Svetionik — Master rad
 */

use App\Models\Monastery;
use App\Models\MonasteryImage;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "====================================================================\n";
echo "POKRETANJE REVIZIJE ZA EPARHIJE: SREMSKA (11) I BRANIČEVSKA (8)\n";
echo "====================================================================\n\n";

$src = '<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>';

$batch3_data = [
    // ====================================================================
    // EPARHIJA SREMSKA (ID 11) - FRUŠKA GORA
    // ====================================================================

    // 131: Manastir Beočin
    131 => [
        'name' => 'Manastir Beočin',
        'ktitor' => 'Monasi manastira Rača (obnova 16. vek / vladika Visarion)',
        'godina_izgradnje' => '1566',
        'card_image' => 'images/monasteries/beocin.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Beočin posvećen je Vaznesenju Hristovom (Spasovdan) i nalazi se na severnim obroncima Fruške gore, u blizini mesta Beočin. Prvi pisani pomen manastira potiče iz turskih deftera iz 1566. godine. Krajem 17. veka, tokom Velike seobe Srba, u opusteli Beočin su se sklonili izbegli monasi iz manastira Rače na Drini, koji su obnovili svetinju i doneli vredne bogoslužbene knjige i relikvije.\n\nARHITEKTURA I UNUTRAŠNJOST:\nDanašnja manastirska crkva podignuta je između 1732. i 1740. godine u stilu tradicionalnog srpsko-vizantijskog graditeljstva sa elementima baroka i visokim zvonikom. Raskošni pozlaćeni barokni ikonostas izradio je 1757–1768. godine čuveni slikar Janko Halkozović, dok je manastirski park sa francuskim vrtom jedan od najlepših u Vojvodini. U crkvi počivaju mošti Svetog vladike Varnave Nastića.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Beočin je ženski manastir Eparhije sremske i spomenik kulture od izuzetnog značaja. Predstavlja jedno od najznačajnijih duhovnih središta Fruške gore — Srpske Svete Gore.",
        'images' => [
            ['url' => 'images/monasteries/beocin.jpg', 'caption' => 'Crkva Vaznesenja Hristovog u manastiru Beočin sa baroknim zvonikom' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/beocin_gal_1.jpg', 'caption' => 'Raskošni barokni konaci i francuski uređeni vrt u manastiru Beočin' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/beocin_gal_2.jpg', 'caption' => 'Južna fasada hrama sa kupolom i cvetnom alejom u porti' . $src, 'sort_order' => 3],
        ]
    ],

    // 132: Manastir Berkasovo
    132 => [
        'name' => 'Manastir Berkasovo',
        'ktitor' => 'Sveti Despot Jovan Branković (obnovljen 2008. godine)',
        'godina_izgradnje' => '1500',
        'card_image' => 'images/monasteries/berkasovo.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Berkasovo (posvećen Svetoj mučenici Paraskevi — Svetoj Petki) nalazi se u blizini Šida, na zapadnim obroncima Fruške gore. Prema istorijskim podacima, podigli su ga krajem 15. veka srpski despoti Stefan i Jovan Branković pored lekovitog izvora. Nakon stradanja pod turskom vlašću, svetinja je obnovljena u 19. i početkom 21. veka.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Svete Petke je skladna jednobrodna građevina sa pripratom i zvonikom. U sklopu manastirskog kompleksa nalazi se i izvor Svete Petke, čija se voda smatra lekovitom za vid i telesne nemoći.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Berkasovo je ženski manastir Eparhije sremske, omiljeno mesto hodočašća vernika iz Srema, Slavonije i Semberije.",
        'images' => [
            ['url' => 'images/monasteries/berkasovo.jpg', 'caption' => 'Crkva Svete Petke u manastiru Berkasovo kod Šida' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/berkasovo_gal_1.jpg', 'caption' => 'Kapela nad čudotvornim izvorom lekovite vode Svete Petke' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/berkasovo_gal_2.jpg', 'caption' => 'Uređeni manastirski konaci u miru fruškogorskih brežuljaka' . $src, 'sort_order' => 3],
        ]
    ],

    // 133: Manastir Bešenovo
    133 => [
        'name' => 'Manastir Bešenovo',
        'ktitor' => 'Stefan Dragutin Nemanjić (kralj Srema)',
        'godina_izgradnje' => '1280',
        'card_image' => 'images/monasteries/besenovo.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Bešenovo posvećen je Svetim arhangelima Mihailu i Gavrilu i nalazi se podno Fruške gore, u blizini sela Bešenovački Prnjavor. Prema predanju, osnovao ga je krajem 13. veka srpski kralj Stefan Dragutin Nemanjić. Tokom Drugog svetskog rata manastir je doživeo tragičnu sudbinu — 1944. godine ga je nemačka avijacija bombardovala i potpuno sravnila sa zemljom. Sveobuhvatna obnova manastira iz temelja započeta je 2013. godine.\n\nARHITEKTURA I UNUTRAŠNJOST:\nNovi hram je podignut u srpsko-vizantijskom stilu od tesanog kamena i opeke sa vitkom kupolom i zvonikom, verno prateći srednjovekovne uzore. Unutrašnjost krase ikonostas u vizantijskom duhu i novoizgrađeni konaci.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nObnovljeni manastir Bešenovo je muški manastir Eparhije sremske i simbol vaskrsenja uništenih fruškogorskih svetinja.",
        'images' => [
            ['url' => 'images/monasteries/besenovo.jpg', 'caption' => 'Vaskrsla crkva Svetih arhangela u manastiru Bešenovo na Fruškoj gori' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/besenovo_gal_1.jpg', 'caption' => 'Zapadno pročelje sa ulaznim portalom i novim konakom u izgradnji' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/besenovo_gal_2.jpg', 'caption' => 'Pogled na manastirski hram i fruškogorske šume u Bešenovu' . $src, 'sort_order' => 3],
        ]
    ],

    // 134: Manastir Divša (Đipša)
    134 => [
        'name' => 'Manastir Divša (Đipša)',
        'ktitor' => 'Sveti Despot Jovan Branković (kraj 15. veka)',
        'godina_izgradnje' => '1490',
        'card_image' => 'images/monasteries/divsa.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Divša (poznat i kao Đipša) posvećen je Svetom ocu Nikolaju i nalazi se u skrovitoj dolini na zapadnom delu Fruške gore, između sela Vizić i Divoš. Osnovao ga je krajem 15. veka Sveti Despot Jovan Branković. Kroz istoriju je često bio metoh manastira Kuveždina. Manastir je teško oštećen u Drugom svetskom ratu, a potpuno je obnovljen krajem 20. veka.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Svetog Nikole je jednobrodna građevina malih dimenzija sa polukružnom apsidom i baroknim zvonikom. Unutrašnjost krase ikone i ikonostas koji odišu skromnošću i molitvenim mirom.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Divša je aktivan ženski manastir Eparhije sremske. Zbog svoje izdvojenosti i tišine predstavlja pravu oazu isposničkog i molitvenog života na Fruškoj gori.",
        'images' => [
            ['url' => 'images/monasteries/divsa.jpg', 'caption' => 'Crkva Svetog Nikole sa baroknim zvonikom u manastiru Divša (Đipša)' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/divsa_gal_1.jpg', 'caption' => 'Pogled na manastirski hram i konak u skrovitoj šumskoj uvali' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/divsa_gal_2.jpg', 'caption' => 'Oltarska apsida i južni zid crkve u manastiru Divša' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/divsa_gal_3.jpg', 'caption' => 'Uređena manastirska porta sa cvećem i bunarom' . $src, 'sort_order' => 4],
        ]
    ],

    // 135: Manastir Fenek
    135 => [
        'name' => 'Manastir Fenek',
        'ktitor' => 'Sveti Stefan i Sveta Mati Angelina Branković (15. vek)',
        'godina_izgradnje' => '1470',
        'card_image' => 'images/monasteries/fenek.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Fenek posvećen je Svetoj mučenici Paraskevi (Svetoj Petki) i nalazi se u donjem Sremu kod Jakova, u beogradskoj opštini Surčin. Prema predanju, podigli su ga u drugoj polovini 15. veka slepi despot Stefan Branković i njegova supruga Sveta Mati Angelina. Iako geografski u ravnici a ne na Fruškoj gori, istorijski i duhovno se ubraja među fruškogorske manastire. U Feneku su krajem 18. veka boravili vožd Karađorđe i iguman manastira Studenica koji su sklonili mošti Svetog Stefana Prvovenčanog.\n\nARHITEKTURA I UNUTRAŠNJOST:\nDanašnja manastirska crkva sagrađena je 1793–1797. godine u baroknom stilu. Veličanstveni barokni ikonostas izradio je novosadski drvorezbar Aksentije Marković, a ikone je oslikao čuveni Petar Radosavljević. U hramu se nalazi čudotvorni izvor lekovite vode i čestica moštiju Svete Petke.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Fenek je muški manastir Eparhije sremske i spomenik kulture od velikog značaja. Zbog blizine Beograda i moštiju Svete Petke predstavlja jedno od najposećenijih svetilišta u Sremu.",
        'images' => [
            ['url' => 'images/monasteries/fenek.jpg', 'caption' => 'Crkva Svete Petke sa monumentalnim baroknim zvonikom u manastiru Fenek' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/fenek_gal_1.jpg', 'caption' => 'Manastirski konaci sa tremom i prostranom portom u Jakovu' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/fenek_gal_2.jpg', 'caption' => 'Kapela sa čudotvornim izvorom lekovite vode Svete Petke u Feneku' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/fenek_gal_3.jpg', 'caption' => 'Unutrašnjost hrama sa baroknim ikonostasom Petra Radosavljevića' . $src, 'sort_order' => 4],
        ]
    ],

    // 136: Manastir Grgeteg
    136 => [
        'name' => 'Manastir Grgeteg',
        'ktitor' => 'Despot Vuk Grgurević (Zmaj Ognjeni Vuk, 1471. god)',
        'godina_izgradnje' => '1471',
        'card_image' => 'images/monasteries/grgeteg.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Grgeteg posvećen je Prenosu moštiju Svetog oca Nikolaja i nalazi se na južnim padinama Fruške gore, u blizini sela Grgeteg. Prema predanju, osnovao ga je 1471. godine srpski despot Vuk Grgurević (u narodnim pesmama poznat kao Zmaj Ognjeni Vuk) kako bi u njega smestio svog slepog oca Grgura Brankovića. Manastir je u 18. i 19. veku bio sedište znamenitih arhimandrita, među kojima se posebno ističe istoričar Ilarion Ruvarac koji je u Grgetegu i sahranjen.\n\nARHITEKTURA I UNUTRAŠNJOST:\nDanašnja crkva je monumentalno barokno zdanje sa višespratnim zvonikom i četvorostranim konacima koji u potpunosti zatvaraju portu. Ikonostas u hramu izradio je 1901. godine čuveni srpski slikar Uroš Predić, što predstavlja jedno od najvrednijih ostvarenja srpske moderne sakralne umetnosti. U crkvi se čuva i čudotvorna ikona Presvete Bogorodice Trojeručice, dar Svete Gore.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Grgeteg je ženski manastir Eparhije sremske i spomenik kulture od izuzetnog značaja. Poznat je po brojnom i podvižničkom sestrinstvu i vrhunski očuvanom kulturno-istorijskom nasleđu.",
        'images' => [
            ['url' => 'images/monasteries/grgeteg.jpg', 'caption' => 'Crkva Svetog Nikole u manastiru Grgeteg okružena spratnim konacima' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/grgeteg_gal_1.jpg', 'caption' => 'Monumentalni barokni zvonik i zapadno krilo konaka u Grgetegu' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/grgeteg_gal_2.jpg', 'caption' => 'Čuveni ikonostas akademskog slikara Uroša Predića iz 1901. godine' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/grgeteg_gal_3.jpg', 'caption' => 'Grob znamenitog arhimandrita i istoričara Ilariona Ruvarca u porti' . $src, 'sort_order' => 4],
        ]
    ],

    // 137: Manastir Jazak
    137 => [
        'name' => 'Manastir Jazak',
        'ktitor' => 'Sveti Despot Jovan Branković (15. vek) / meštani i verni narod (1736)',
        'godina_izgradnje' => '1736',
        'card_image' => 'images/monasteries/jazak.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Jazak posvećen je Svetoj Trojici i nalazi se u živopisnoj uvali na južnim padinama Fruške gore, kod sela Jazak. Nastao je u 18. veku u blizini Starog Jaska koji je podigao despot Jovan Branković. U manastir Jazak su 1705. godine iz Nerodimlja na Kosovu prenete svete mošti poslednjeg vladara iz loze Nemanjića — Svetog cara Stefana Uroša V Nejakog, gde i danas počivaju.\n\nARHITEKTURA I UNUTRAŠNJOST:\nManastirski hram je sagrađen 1736–1758. godine kao jedinstvena sinteza srpsko-vizantijskog stila i baroka sa vitkim zvonikom. Raskošni barokni ikonostas izradio je 1769. godine Dimitrije Bačević. U hramu se nalazi bogato ukrašeni kivot sa svetim moštima cara Uroša.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Jazak je ženski manastir Eparhije sremske i spomenik kulture od izuzetnog značaja. Zbog moštiju Svetog cara Uroša predstavlja jedno od najvećih narodnih i vladarskih svetilišta na Fruškoj gori.",
        'images' => [
            ['url' => 'images/monasteries/jazak.jpg', 'caption' => 'Crkva Svete Trojice sa baroknim zvonikom i konacima u manastiru Jazak' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/jazak_gal_1.jpg', 'caption' => 'Kivot sa svetim moštima Svetog cara Stefana Uroša V Nejakog u hramu' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/jazak_gal_2.jpg', 'caption' => 'Barokni ikonostas Dimitrija Bačevića iz 1769. godine u Jasku' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/jazak_gal_3.jpg', 'caption' => 'Južna fasada hrama sa kupolom i konacima okruženim šumom' . $src, 'sort_order' => 4],
        ]
    ],

    // 138: Manastir Krušedol
    138 => [
        'name' => 'Manastir Krušedol',
        'ktitor' => 'Sveti Vladika Maksim (Despot Đorđe Branković) i Sveta Mati Angelina',
        'godina_izgradnje' => '1509',
        'card_image' => 'images/monasteries/krusedol.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Krušedol posvećen je Blagovestima Presvete Bogorodice i predstavlja duhovnu i mauzolejsku prestonicu Fruške gore. Podigli su ga između 1509. i 1516. godine vladika Maksim (bivši despot Đorđe Branković) i njegova majka Sveta Mati Angelina, uz materijalnu pomoć vlaškog vojvode Jovana Njagoja. Manastir je bio sedište Sremske eparhije i mesto gde su sahranjeni svi članovi svete dinastije Branković, kao i dva srpska patrijarha (Arsenije III Čarnojević i Arsenije IV Jovanović Šakabenta), kralj Milan Obrenović i kneginja Ljubica.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je građena u stilu moravske škole, kojoj je u 18. veku pridodat barokni zvonik. Unutrašnjost čuva izuzetno vredne zidne slike iz 16. veka (u priprati) i monumentalni barokni živopis Jova Vasilijeviča iz 1750. godine. U crkvi se nalaze kivoti sa svetim moštima svetih Brankovića.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Krušedol je muški manastir Eparhije sremske i spomenik kulture od izuzetnog značaja. Kao večni panteon srpskih vladara, patrijaraha i svetitelja, predstavlja jedan od najsvetijih stubova srpskog naroda.",
        'images' => [
            ['url' => 'images/monasteries/krusedol.jpg', 'caption' => 'Prepoznatljiva crvena fasada hrama Blagovesti u manastiru Krušedol' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/krusedol_gal_1.jpg', 'caption' => 'Četvorostrani spratni konaci sa baroknim zvonikom i ulaznom kapijom' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/krusedol_gal_2.jpg', 'caption' => 'Zidni živopis i ikonostas u unutrašnjosti hrama manastira Krušedol' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/krusedol_gal_3.jpg', 'caption' => 'Kivot sa svetim moštima Svete majke Angeline i svetih Brankovića' . $src, 'sort_order' => 4],
        ]
    ],

    // 139: Manastir Kuveždin
    139 => [
        'name' => 'Manastir Kuveždin',
        'ktitor' => 'Sveti Stefan Štiljanović (1520. god)',
        'godina_izgradnje' => '1520',
        'card_image' => 'images/monasteries/kuvezdin.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Kuveždin posvećen je Svetom Preobraženju Gospodnjem i Svetom Savi i Simeonu, a nalazi se u jugozapadnom delu Fruške gore kod sela Divoš. Osnovao ga je 1520. godine poslednji srpski despot u Ugarskoj, Sveti Stefan Štiljanović. Manastir je imao izuzetnu ulogu u širenju kulta Svetog Save u Vojvodini. Tokom Drugog svetskog rata ustaše su manastir minirale i spalile, a njegova temeljna obnova započeta je 2004. godine.\n\nARHITEKTURA I UNUTRAŠNJOST:\nMonumentalna klasicistička crkva sa velikom kupolom i trostranim konacima obnovljena je u potpunosti. U hramu je nekada postojao čuveni ikonostas Pavla Simića, čiji su delovi sačuvani u Galeriji Matice srpske.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Kuveždin je muški manastir Eparhije sremske i spomenik kulture od izuzetnog značaja. Kao jedna od najlepših obnovljenih svetinja, ponovo sija punim liturgijskim sjajem.",
        'images' => [
            ['url' => 'images/monasteries/kuvezdin.jpg', 'caption' => 'Obnovljeni monumentalni hram Preobraženja Gospodnjeg u manastiru Kuveždin' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/kuvezdin_gal_1.jpg', 'caption' => 'Zapadna fasada crkve sa klasicističkim stubovima i zvonikom' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/kuvezdin_gal_2.jpg', 'caption' => 'Pogled na manastirski kompleks i konake pre i tokom obnove' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/kuvezdin_gal_3.jpg', 'caption' => 'Kapela na uzvišenju iznad manastira Kuveždin' . $src, 'sort_order' => 4],
        ]
    ],

    // 140: Manastir Mala Remeta
    140 => [
        'name' => 'Manastir Mala Remeta',
        'ktitor' => 'Stefan Dragutin Nemanjić (obnovili monasi manastira Rača 1739)',
        'godina_izgradnje' => '1300',
        'card_image' => 'images/monasteries/mala-remeta.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Mala Remeta (posvećen Pokrovu Presvete Bogorodice) nalazi se u središnjem delu Fruške gore, u blizini sela Mala Remeta. Prema predanju, osnovao ga je srpski kralj Stefan Dragutin Nemanjić kao metoh manastira Rače. Nakon Velike seobe, račanski monasi su ovde podigli novu crkvu između 1739. i 1759. godine.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Pokrova Bogorodice je jednobrodna građevina srpsko-vizantijskog stila sa kupolom, bez baroknog zvonika, čime je sačuvala izuzetan srednjovekovni vizuelni identitet. Raskošni ikonostas oslikao je 1759. godine zograf Janko Halkozović.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Mala Remeta je ženski manastir Eparhije sremske i spomenik kulture od izuzetnog značaja. Poznat je po tihovanju i autentičnom ambijentu.",
        'images' => [
            ['url' => 'images/monasteries/mala-remeta.jpg', 'caption' => 'Crkva Pokrova Presvete Bogorodice sa kupolom u manastiru Mala Remeta' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/mala-remeta_gal_1.jpg', 'caption' => 'Južna kamena fasada hrama i uređena porta u Maloj Remeti' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/mala-remeta_gal_2.jpg', 'caption' => 'Istorijski izgled manastira Mala Remeta na starim crtežima iz 1885. godine' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/mala-remeta_gal_3.jpg', 'caption' => 'Pogled na manastirski kompleks i šumske obronke Fruške gore' . $src, 'sort_order' => 4],
        ]
    ],

    // 141: Manastir Novo Hopovo
    141 => [
        'name' => 'Manastir Novo Hopovo',
        'ktitor' => 'Sveti Despot Đorđe Branković (vladika Maksim, kraj 15. veka)',
        'godina_izgradnje' => '1490',
        'card_image' => 'images/monasteries/novo-hopovo.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Novo Hopovo posvećen je Svetom Nikoli i nalazi se na južnim obroncima Fruške gore kod Iriga. Podigao ga je krajem 15. veka despot Đorđe Branković (vladika Maksim). U 16. veku manastir je bio vodeći prosvetni centar u Sremu, u kome je boravio i Dositej Obradović koji se ovde i zamonašio. U manastiru se od 16. veka čuvaju netruležne mošti Svetog velikomučenika Teodora Tirona.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Svetog Nikole sagrađena je 1576. godine kao monumentalna jednobrodna građevina sa dvanaestostranom kupolom, spoj moravske škole i evropskih renesansnih uticaja. Zidno slikarstvo iz 1608. i 1654. godine radili su zografi sa Svete Gore i spada među najlepša ostvarenja 17. veka u Srbiji.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Novo Hopovo je muški manastir Eparhije sremske i spomenik kulture od izuzetnog značaja. Zbog moštiju Svetog Teodora Tirona i lepote fresaka, jedno je od najposećenijih hodočasničkih mesta u zemlji.",
        'images' => [
            ['url' => 'images/monasteries/novo-hopovo.jpg', 'caption' => 'Monumentalna crkva Svetog Nikole sa dvanaestostranom kupolom u Novom Hopovu' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/novo-hopovo_gal_1.jpg', 'caption' => 'Panorama manastirskog kompleksa Novo Hopovo sa baroknim zvonikom i konacima' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/novo-hopovo_gal_2.jpg', 'caption' => 'Zapadno pročelje hrama sa arkadama i kamenom plastike u porti' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/novo-hopovo_gal_3.jpg', 'caption' => 'Unutrašnjost crkve sa freskama svetogorskih zografa i kivotom Teodora Tirona' . $src, 'sort_order' => 4],
        ]
    ],

    // 142: Manastir Obed (Kupinovo)
    142 => [
        'name' => 'Manastir Obed',
        'ktitor' => 'Sveta Mati Angelina Branković (1496. god)',
        'godina_izgradnje' => '1496',
        'card_image' => 'images/monasteries/obed.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Obed (poznat i kao crkva Majke Angeline) nalazi se u Kupinovu uz samu Obedsku baru, na mestu nekadašnjeg utvrđenja Kupinik — prestonice srpskih despota Brankovića. Podigla ga je 1496. godine Sveta Mati Angelina Branković od drveta i kamena, u spomen na svog supruga slepog despota Stefana.\n\nARHITEKTURA I UNUTRAŠNJOST:\nDanašnja crkva posvećena Blagovestima Presvete Bogorodice je obnovljena kamena građevina sa baroknim zvonikom i elementima tradicionalnog graditeljstva donjeg Srema. U hramu se nalaze ikone posvećene svetim Brankovićima.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Obed pripada Eparhiji sremskoj i predstavlja živo svedočanstvo o poslednjoj prestonici srpskih despota u Sremu.",
        'images' => [
            ['url' => 'images/monasteries/obed.jpg', 'caption' => 'Crkva Majke Angeline u manastiru Obed u Kupinovu uz Obedsku baru' . $src, 'sort_order' => 1],
        ]
    ],

    // 143: Manastir Privina Glava
    143 => [
        'name' => 'Manastir Privina Glava',
        'ktitor' => 'Vlastelin Priba (12. vek) / obnovili despoti Brankovići',
        'godina_izgradnje' => '1200',
        'card_image' => 'images/monasteries/privina-glava.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Privina Glava posvećen je Svetim arhangelima Mihailu i Gavrilu i nalazi se na krajnjem zapadu Fruške gore, kod Šida. Prema predanju, najstariji je fruškogorski manastir koji je u 12. veku osnovao vlastelin Priba (Priva). Krajem 15. veka obnovili su ga despoti Jovan i Đorđe Branković. U manastiru se čuvalo čuveno Privinoglavsko jevanđelje iz 14. veka.\n\nARHITEKTURA I UNUTRAŠNJOST:\nDanašnja monumentalna crkva sagrađena je 1741–1760. godine u baroknom stilu po ugledu na crkvu manastira Novo Hopovo. Raskošni ikonostas oslikao je 1786. godine znameniti slikar Andreja Štis.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Privina Glava je muški manastir Eparhije sremske i spomenik kulture od izuzetnog značaja. Poznat je po izuzetnom monaškom redu i prelepom manastirskom parku.",
        'images' => [
            ['url' => 'images/monasteries/privina-glava.jpg', 'caption' => 'Crkva Svetih arhangela sa baroknim zvonikom u manastiru Privina Glava' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/privina-glava_gal_1.jpg', 'caption' => 'Monumentalni manastirski konaci sa zvonikom i lučnim prolazima' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/privina-glava_gal_2.jpg', 'caption' => 'Južna fasada hrama sa kupolom u cvetnoj manastirskoj porti' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/privina-glava_gal_3.jpg', 'caption' => 'Pogled na manastirski kompleks Privina Glava sa okolnih brežuljaka' . $src, 'sort_order' => 4],
        ]
    ],

    // 144: Manastir Rakovac
    144 => [
        'name' => 'Manastir Rakovac',
        'ktitor' => 'Veliki komornik Raka Milošević (1498. god)',
        'godina_izgradnje' => '1498',
        'card_image' => 'images/monasteries/rakovac.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Rakovac posvećen je Svetim vračima Kozmi i Damjanu i nalazi se na severnim padinama Fruške gore kod Beočina. Prema predanju, osnovao ga je 1498. godine veliki komornik despota Jovana Brankovića, Raka Milošević, na mestu gde je u lovu video jelena i doživeo čudesno viđenje. U Rakovcu je 1700. godine prepisan čuveni Dušanov zakonik (Rakovački prepis).\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Svetih vrača je jednobrodna građevina sa kupolom i visokim baroknim zvonikom podignutim 1735. godine. U hramu se nalaze vredni ostaci zidnog slikarstva iz 16. veka i baroknog živopisa iz 18. veka. Manastir je stradao u Drugom svetskom ratu, a danas je uspešno obnovljen.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Rakovac je muški manastir Eparhije sremske i spomenik kulture od izuzetnog značaja, svedok vekovne pismenosti i prepisivačke tradicije Fruške gore.",
        'images' => [
            ['url' => 'images/monasteries/rakovac.jpg', 'caption' => 'Crkva Svetih vrača Kozme i Damjana sa zvonikom u manastiru Rakovac' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/rakovac_gal_1.jpg', 'caption' => 'Panorama manastira Rakovac sa spratnim konacima i šumom Fruške gore' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/rakovac_gal_2.jpg', 'caption' => 'Zapadna fasada crkve sa baroknim portalom i zvonikom' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/rakovac_gal_3.jpg', 'caption' => 'Južna fasada hrama i ostaci zidina manastira Rakovac' . $src, 'sort_order' => 4],
        ]
    ],

    // 145: Manastir Velika Remeta
    145 => [
        'name' => 'Manastir Velika Remeta',
        'ktitor' => 'Stefan Dragutin Nemanjić (kraj 13. veka)',
        'godina_izgradnje' => '1300',
        'card_image' => 'images/monasteries/velika-remeta.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Velika Remeta posvećen je Svetom Dimitriju i nalazi se u istočnom delu Fruške gore, u blizini sela Velika Remeta. Prema predanju, osnovao ga je srpski kralj Stefan Dragutin Nemanjić krajem 13. veka. U pisanim izvorima pominje se 1562. godine. Manastir je u 18. veku bio sedište prepisivačke delatnosti i ikonopisa.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Svetog Dimitrija je jednobrodna kamena građevina sa kupolom. Uz nju je 1735. godine dozidan najviši barokni zvonik na Fruškoj gori (visok 38,6 metara) sa kapelom Svetog Jovana Krstitelja na spratu. U manastiru se nalazi i verna replika Vitlejemske pećine i brda Sion sa krstom, koje je podigao arhimandrit Stefan.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Velika Remeta je muški manastir Eparhije sremske i spomenik kulture od izuzetnog značaja. Poznat je po izuzetnom duhovnom radu, velikom broju hodočasnika i prelepo uređenom manastirskom imanju.",
        'images' => [
            ['url' => 'images/monasteries/velika-remeta.jpg', 'caption' => 'Crkva Svetog Dimitrija sa najvišim baroknim zvonikom na Fruškoj gori (38,6 m)' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/velika-remeta_gal_1.jpg', 'caption' => 'Panorama manastirskog kompleksa Velika Remeta okruženog četvorostranim konacima' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/velika-remeta_gal_2.jpg', 'caption' => 'Pogled na manastirski trg, cvetne leje i repliku Vitlejemske pećine' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/velika-remeta_gal_3.jpg', 'caption' => 'Južna fasada hrama i zvonik u porti manastira Velika Remeta' . $src, 'sort_order' => 4],
        ]
    ],

    // 146: Manastir Vodice
    146 => [
        'name' => 'Manastir Vodice',
        'ktitor' => 'Pravoslavni vernici Srema (18. vek)',
        'godina_izgradnje' => '1750',
        'card_image' => 'images/monasteries/vodice.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Vodice nalazi se na obroncima Fruške gore i nastao je oko drevnog svetilišta i lekovitog izvora vode, na kome su se vekovima sabirali vernici sremskih sela.\n\nARHITEKTURA I UNUTRAŠNJOST:\nManastirska kapela je skromna građevina u stilu vojvođanskog baroka sa natkrivenim izvorom i ikonostasom koji krasi lik Majke Božije i svetih iscelitelja.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Vodice pripada Eparhiji sremskoj i služi kao mesto molitve za zdravlje i isceljenje mnogobrojnih vernika.",
        'images' => [
            ['url' => 'images/monasteries/vodice.jpg', 'caption' => 'Kapela manastira Vodice na Fruškoj gori' . $src, 'sort_order' => 1],
        ]
    ],

    // 147: Manastir Vranjaš
    147 => [
        'name' => 'Manastir Vranjaš',
        'ktitor' => 'Episkop sremski Vasilije (Vadić) i verni narod',
        'godina_izgradnje' => '2011',
        'card_image' => 'images/monasteries/vranjas.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Svetog Vasilija Ostroškog u Vranjašu kod Manđelosa najmlađi je manastir na južnim padinama Fruške gore. Podignut je u periodu od 2011. do 2014. godine blagoslovom Njegovog Preosveštenstva Episkopa sremskog gospodina Vasilija, pored izvora Vranjaš koji slovi za jedan od najjačih i najčistijih na Fruškoj gori.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je sagrađena od drveta i kamena u stilu brvnare sa elementima ruske i srpske sakralne arhitekture. Unutrašnjost krase duborezani ikonostas i ikone Svetog Vasilija Ostroškog i Svetog Serafima Sarovskog.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Vranjaš je ženski manastir Eparhije sremske. Veliki broj vernika dolazi ovde na praznik Svetog Vasilija Ostroškog (12. maja) na molitvu i vodoosvećenje.",
        'images' => [
            ['url' => 'images/monasteries/vranjas.jpg', 'caption' => 'Drvena crkva Svetog Vasilija Ostroškog u manastiru Vranjaš kod Manđelosa' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/vranjas_gal_1.jpg', 'caption' => 'Pogled na manastirski hram kroz borove i cvetno dvorište' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/vranjas_gal_2.jpg', 'caption' => 'Izvorište Vranjaš sa česmom u podnožju manastira' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/vranjas_gal_3.jpg', 'caption' => 'Unutrašnjost drvenog hrama sa rezbarenim ikonostasom' . $src, 'sort_order' => 4],
        ]
    ],

    // 148: Manastir Vrdnik (Mala Ravanica)
    148 => [
        'name' => 'Manastir Vrdnik (Mala Ravanica)',
        'ktitor' => 'Monasi manastira Ravanica (obnova 1697) / knez Lazar Hrebeljanović',
        'godina_izgradnje' => '1566',
        'card_image' => 'images/monasteries/vrdnik.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Vrdnik (poznat i kao Mala Ravanica ili Sremska Ravanica) posvećen je Vaznesenju Gospodnjem i nalazi se u banjskom mestu Vrdnik na južnoj strani Fruške gore. Pominje se u 16. veku, a njegov procvat počinje 1697. godine kada su izbegli monasi iz moravske Ravanice u njega doneli netruležne mošti Svetog kneza Lazara Kosovskog. Mošti kneza Lazara počivale su u Vrdniku skoro tri veka (do 1989. godine kada su vraćene u moravsku Ravanicu).\n\nARHITEKTURA I UNUTRAŠNJOST:\nDanašnja crkva sagrađena je 1801–1811. godine kao monumentalna jednobrodna građevina sa kupolom i baroknim zvonikom. Raskošni klasicistički ikonostas izradio je novosadski drvorezbar Marko Vujatović, a ikone je oslikao Stefan Gavrilović. U crkvi se i danas čuva deo moštiju kneza Lazara i mošti Svete mučenice Anastasije.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Vrdnik je ženski manastir Eparhije sremske i spomenik kulture od izuzetnog značaja. Predstavlja jedno od najvažnijih istorijskih i zavetnih svetilišta srpskog naroda na Fruškoj gori.",
        'images' => [
            ['url' => 'images/monasteries/vrdnik.jpg', 'caption' => 'Monumentalna crkva Vaznesenja Gospodnjeg u manastiru Vrdnik (Mala Ravanica)' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/vrdnik_gal_1.jpg', 'caption' => 'Pogled na manastirski kompleks Vrdnik sa konacima i baroknim zvonikom' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/vrdnik_gal_2.jpg', 'caption' => 'Zapadna fasada hrama sa kamenim portalom u banji Vrdnik' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/vrdnik_gal_3.jpg', 'caption' => 'Ikonostas Stefana Gavrilovića i kivot sa česticom moštiju kneza Lazara' . $src, 'sort_order' => 4],
        ]
    ],

    // 149: Manastir Šišatovac
    149 => [
        'name' => 'Manastir Šišatovac',
        'ktitor' => 'Izbegli monasi manastira Žiča pod vođstvom igumana Teofana (1520. god)',
        'godina_izgradnje' => '1520',
        'card_image' => 'images/monasteries/sisatovac.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Šišatovac posvećen je Rođenju Presvete Bogorodice i nalazi se u dolini Remetskog potoka na Fruškoj gori. Osnovali su ga 1520. godine izbegli monasi manastira Žiče predvođeni igumanom Teofanom. U 16. veku u manastiru su položene mošti Svetog Stefana Štiljanovića. Šišatovac je bio čuven po bogatoj biblioteci i prepisivačkoj školi — u njemu je nastao čuveni Šišatovački apostol (1324), a u 19. veku ovde su boravili Vuk Stefanović Karadžić i guslar Filip Višnjić, kao i arhimandrit Lukijan Mušicki.\n\nARHITEKTURA I UNUTRAŠNJOST:\nDanašnja crkva sagrađena je 1778. godine kao monumentalno barokno zdanje sa visokim zvonikom, rad majstora Jovana Komanovića. Ikonostas je oslikao znameniti slikar Grigorije Davidović Opšić. Manastir je teško postradao u Drugom svetskom ratu, a njegova temeljna obnova uspešno je privedena kraju.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Šišatovac je muški manastir Eparhije sremske i spomenik kulture od izuzetnog značaja. Predstavlja jedan od najsvetijih simbola prosvete, epske poezije i vere u Sremu.",
        'images' => [
            ['url' => 'images/monasteries/sisatovac.jpg', 'caption' => 'Crkva Rođenja Presvete Bogorodice sa monumentalnim zvonikom u manastiru Šišatovac' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/sisatovac_gal_1.jpg', 'caption' => 'Pogled na manastirski kompleks Šišatovac u dolini potoka na Fruškoj gori' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/sisatovac_gal_2.jpg', 'caption' => 'Zapadna fasada sa baroknim portalom i prostranom portom u Šišatovcu' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/sisatovac_gal_3.jpg', 'caption' => 'Unutrašnjost naosa crkve sa kupolom i obnovljenim ikonostasom' . $src, 'sort_order' => 4],
        ]
    ],

    // ====================================================================
    // EPARHIJA BRANIČEVSKA (ID 8)
    // ====================================================================

    // 21: Manastir Bradača
    21 => [
        'name' => 'Manastir Bradača',
        'ktitor' => 'Vuk Branković (14. vek)',
        'godina_izgradnje' => '1380',
        'card_image' => 'images/monasteries/bradaca.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Bradača posvećen je Svetim arhangelima Mihailu i Gavrilu i nalazi se u ataru sela Kula kod Malog Crnića, podno brda Belog bagrema u Braničevu. Prema predanju, zadužbina je velikaša Vuka Brankovića s kraja 14. veka. Manastir je više puta rušen i obnavljan, a krajem 20. veka vraćen mu je manastirski status.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna građevina raškog tipa sa polukružnom apsidom i zvonikom. Unutrašnjost krase ikone i ikonostas u duhu pravoslavnog crkvenog slikarstva.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Bradača pripada Eparhiji braničevskoj i predstavlja mirno duhovno stecište vernika Stiga i Mlave.",
        'images' => [
            ['url' => 'images/monasteries/bradaca.jpg', 'caption' => 'Crkva Svetih Arhangela sa tremom i zvonikom u manastiru Bradača' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/bradaca_gal_1.jpg', 'caption' => 'Uređena manastirska porta sa konakom u ataru sela Kula' . $src, 'sort_order' => 2],
        ]
    ],

    // 22: Manastir Dobreš
    22 => [
        'name' => 'Manastir Dobreš',
        'ktitor' => 'Sveti Knez Lazar Hrebeljanović / Stefan Lazarević (14. vek)',
        'godina_izgradnje' => '1380',
        'card_image' => 'images/monasteries/dobres.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Dobreš posvećen je Svetom ocu Nikolaju i nalazi se u gustim bukovim šumama na obroncima Homoljskih planina, nedaleko od manastira Gornjak. Nastao je u 14. veku u doba kneza Lazara, a vekovima je služio kao skrovita isposnica i mesto tihovanja monaha sinaita.\n\nARHITEKTURA I UNUTRAŠNJOST:\nManastirski hram je manja jednobrodna kamena građevina sa polukružnom apsidom. Unutrašnjost odiše ranohrišćanskom jednostavnošću i molitvenom tišinom.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Dobreš je metoh manastira Gornjak u Eparhiji braničevskoj, omiljeno mesto za osamljenu molitvu u srcu Homolja.",
        'images' => [
            ['url' => 'images/monasteries/dobres.jpg', 'caption' => 'Kamena crkva Svetog Nikole u manastiru Dobreš u Homoljskim šumama' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/dobres_gal_1.jpg', 'caption' => 'Pogled na manastirsku crkvu i prirodno okruženje Dobreša' . $src, 'sort_order' => 2],
        ]
    ],

    // 23: Manastir Gornjak
    23 => [
        'name' => 'Manastir Gornjak',
        'ktitor' => 'Sveti Knez Lazar Hrebeljanović (1378. god) i prepodobni Grigorije Sinait',
        'godina_izgradnje' => '1378',
        'card_image' => 'images/monasteries/gornjak.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Gornjak posvećen je Vavedenju Presvete Bogorodice i nalazi se u Gornjačkoj klisuri reke Mlave, između Petrovca na Mlavi i Žagubice. Podigao ga je 1378. godine Sveti knez Lazar Hrebeljanović, poverivši upravu svom duhovniku, čuvenom isihasti Prepodobnom Grigoriju Gornjačkom (Sinaitu). Kroz vekove je bio čuvar srpske pismenosti i duhovnosti u Homolju.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Vavedenja je građena u moravskom stilu, prislonjena uz samu vertikalnu stenu klisure. Iznad manastira, visoko u pećini u litici, nalazi se pećinska kapela Svetog Nikole u kojoj se podvizavao Prepodobni Grigorije Sinait i gde počivaju njegove čudotvorne mošti. U crkvi se čuvaju vredne freske iz 18. veka.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Gornjak je muški manastir Eparhije braničevske i spomenik kulture od velikog značaja. Zbog moštiju Svetog Grigorija Sinaita i fascinantnog položaja u klisuri Mlave, jedno je od najznačajnijih svetilišta u Srbiji.",
        'images' => [
            ['url' => 'images/monasteries/gornjak.jpg', 'caption' => 'Manastir Gornjak usečen u monumentalne litice Gornjačke klisure reke Mlave' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/gornjak_gal_1.jpg', 'caption' => 'Pećinska isposnica Svetog Grigorija Sinaita u steni iznad manastira' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/gornjak_gal_2.jpg', 'caption' => 'Crkva Vavedenja Bogorodice sa konakom i klesanom kapijom' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/gornjak_gal_3.jpg', 'caption' => 'Pogled na manastirski kompleks i reku Mlavu sa litica' . $src, 'sort_order' => 4],
        ]
    ],

    // 24: Manastir Izvor
    24 => [
        'name' => 'Manastir Izvor',
        'ktitor' => 'Nepoznati srpski velikaš (14. vek) / obnovljen u 19. veku',
        'godina_izgradnje' => '1380',
        'card_image' => 'images/monasteries/izvor.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Svete Petke u Izvoru kod Paraćina smešten je u klisuri reke Grze. Potiče iz druge polovine 14. veka iz doba kneza Lazara. Kroz istoriju je bio poznat po humanitarnom radu, a u novije vreme sestrinstvo manastira posvećeno brine o Domu za decu i omladinu ometenu u razvoju.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Svete Petke je jednobrodna trikonhalna građevina sa kupolom. Unutrašnjost krase rezbareni ikonostas i freske koje zrače toplinom.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Izvor je ženski manastir Eparhije braničevske, prepoznatljiv po hrišćanskom milosrđu i požrtvovanom radu monahinja.",
        'images' => [
            ['url' => 'images/monasteries/izvor.jpg', 'caption' => 'Crkva Svete Petke u manastiru Izvor kod Paraćina' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/izvor_gal_1.jpg', 'caption' => 'Pogled na manastirski kompleks u klisuri reke Grze' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/izvor_gal_2.jpg', 'caption' => 'Manastirska porta sa konacima i domom' . $src, 'sort_order' => 3],
        ]
    ],

    // 25: Manastir Koporin
    25 => [
        'name' => 'Manastir Koporin',
        'ktitor' => 'Sveti Despot Stefan Lazarević (1402. god)',
        'godina_izgradnje' => '1402',
        'card_image' => 'images/monasteries/koporin.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Koporin posvećen je Svetom arhiđakonu Stefanu i nalazi se u skrovitoj šumovitoj dolini kod Velike Plane. Podigao ga je 1402. godine Sveti Despot Stefan Lazarević, neposredno nakon povratka iz Angorske bitke kada je od vizantijskog cara dobio titulu despota. U manastirskoj crkvi su 1977. godine prilikom arheoloških iskopavanja otkrivene svete mošti despota Stefana Lazarevića.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna građevina moravske škole sa poluobličastim svodom i pripratom, zidana od kamena i opeke. Zidno slikarstvo iz 1402. godine spada u najlepša ostvarenja moravskog doba, sa čuvenim ktitorskim portretom despota Stefana. U kivotu u crkvi počivaju svete mošti Svetog despota Stefana.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Koporin je ženski manastir Eparhije braničevske i spomenik kulture od velikog značaja. Zbog moštiju despota Stefana i lekovitog izvora Svetog Stefana u porti, manastir svakodnevno pohode brojni vernici.",
        'images' => [
            ['url' => 'images/monasteries/koporin.jpg', 'caption' => 'Crkva Svetog arhiđakona Stefana u manastiru Koporin kod Velike Plane' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/koporin_gal_1.jpg', 'caption' => 'Kivot sa svetim moštima Svetog Despota Stefana Lazarevića u hramu' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/koporin_gal_2.jpg', 'caption' => 'Čuvena ktitorska freska despota Stefana iz 1402. godine' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/koporin_gal_3.jpg', 'caption' => 'Kapela nad čudotvornim izvorom lekovite vode u manastirskoj porti' . $src, 'sort_order' => 4],
        ]
    ],

    // 26: Manastir Manasija (Resava)
    26 => [
        'name' => 'Manastir Manasija (Resava)',
        'ktitor' => 'Sveti Despot Stefan Lazarević (1407–1418)',
        'godina_izgradnje' => '1407',
        'card_image' => 'images/monasteries/manasija.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Manasija (poznat i kao Resava) posvećen je Svetoj Trojici i nalazi se u dolini reke Resave kod Despotovca. Podigao ga je između 1407. i 1418. godine Sveti Despot Stefan Lazarević kao svoju glavnu zadužbinu i mauzolej. Manasija je bila sedište čuvene **Resavske prepisivačke škole**, najvećeg i najuglednijeg književnog i prevodilačkog centra na Balkanu tog doba, čija su pravopisna pravila uticala na srpsku, bugarsku i rusku pismenost.\n\nARHITEKTURA I UNUTRAŠNJOST:\nManastir predstavlja vrhunac moravskog graditeljstva i vojne arhitekture — opasan je masivnim kamenim bedemima sa 11 monumentalnih kula, među kojima dominira Despotova kula. Crkva Svete Trojice je petokupolna monumentalna građevina obložena klesanim mermerom. Resavski živopis iz 15. veka spada u remek-dela evropskog slikarstva, a posebno se ističu figure Svetih ratnika u naosu i ktitorski portret despota Stefana.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Manasija je ženski manastir Eparhije braničevske i spomenik kulture od izuzetnog značaja na preliminarnoj listi UNESCO svetske baštine. Predstavlja najsjajniji simbol zlatnog doba srpske Despotovine.",
        'images' => [
            ['url' => 'images/monasteries/manasija.jpg', 'caption' => 'Monumentalne kule i zidine manastira Manasija (Resava) kod Despotovca' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/manasija_gal_1.jpg', 'caption' => 'Petokupolni hram Svete Trojice unutar utvrđenih manastirskih bedema' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/manasija_gal_2.jpg', 'caption' => 'Svetski čuvene freske Svetih ratnika u naosu crkve manastira Manasija' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/manasija_gal_3.jpg', 'caption' => 'Despotova kula — glavna odbrambena kula manastirskog utvrđenja' . $src, 'sort_order' => 4],
        ]
    ],

    // 27: Manastir Miljkovo
    27 => [
        'name' => 'Manastir Miljkovo',
        'ktitor' => 'Knez Lazar Hrebeljanović (1374) / obnovio trgovac Miljko Tomić (1787)',
        'godina_izgradnje' => '1374',
        'card_image' => 'images/monasteries/miljkovo.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Miljkovo (nekada Bukovica) posvećen je Vavedenju Presvete Bogorodice i nalazi se na obali Velike Morave kod sela Gložane u opštini Svilajnac. Osnovao ga je 1374. godine knez Lazar Hrebeljanović. Manastir je 1787. godine obnovio trgovac Miljko Tomić, po kome nosi današnje ime. U Miljkovom manastiru je 1926. godine boravio čuveni ruski podvižnik i svetitelj Sveti Jovan Šangajski.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna građevina sa kupolom i pripratom, zidana od kamena i opeke. U manastiru se čuva čudotvorna ikona Bogorodice Ahtirske i vredan rezbareni ikonostas.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Miljkovo je ženski manastir Eparhije braničevske, čuven po vezama sa ruskim zagraničnim monaštvom i Svetim Jovanom Šangajskim.",
        'images' => [
            ['url' => 'images/monasteries/miljkovo.jpg', 'caption' => 'Crkva Vavedenja Presvete Bogorodice u manastiru Miljkovo na Velikoj Moravi' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/miljkovo_gal_1.jpg', 'caption' => 'Zvonik i manastirska porta sa cvetnim vrtom u Miljkovu' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/miljkovo_gal_2.jpg', 'caption' => 'Pogled na manastirski konak i mirnu prirodu uz reku Moravu' . $src, 'sort_order' => 3],
        ]
    ],

    // 28: Manastir Namasija
    28 => [
        'name' => 'Manastir Namasija',
        'ktitor' => 'Srpska srednjovekovna vlastela / monasi sinaiti (14. vek)',
        'godina_izgradnje' => '1380',
        'card_image' => 'images/monasteries/namasija.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Namasija posvećen je Svetom Nikoli i nalazi se u klisuri reke Crnice kod sela Zabrega nedaleko od Paraćina, u sklopu kompleksa srednjovekovne Petruške oblasti („Male Svete Gore“). Potiče iz druge polovine 14. veka iz doba kneza Lazara i monaha isihasta.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je trikonhalna građevina moravske škole zidana od lomljenog kamena. Arheološki ostaci i obnovljeni hram svedoče o bogatoj monaškoj tradiciji ovog kanjona.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Namasija pripada Eparhiji braničevskoj i predstavlja biser srednjovekovnog monaštva Petruške oblasti.",
        'images' => [
            ['url' => 'images/monasteries/namasija.jpg', 'caption' => 'Crkva Svetog Nikole u manastiru Namasija kod Zabrege u kanjonu Crnice' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/namasija_gal_1.jpg', 'caption' => 'Ostaci i obnovljeni delovi drevnog svetilišta u kanjonu Crnice' . $src, 'sort_order' => 2],
        ]
    ],

    // 29: Manastir Nimnik
    29 => [
        'name' => 'Manastir Nimnik',
        'ktitor' => 'Vojvoda Bogosav / knez Lazar Hrebeljanović (1389. god)',
        'godina_izgradnje' => '1389',
        'card_image' => 'images/monasteries/nimnik.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Nimnik posvećen je Prenosu moštiju Svetog oca Nikolaja i nalazi se u hrastovoj šumi kod sela Kurjače, u opštini Veliko Gradište. Podigao ga je 1389. godine vojvoda Bogosav u doba kneza Lazara. Ime manastira vezuje se za vlašku devojčicu Nikolinu (Svetu mučenicu Nikolinu Nimničku), koju su turski vojnici ubili jer nije htela da oda gde se krije manastir, govoreći samo „nimik“ (ne znam ništa).\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna kamena građevina sa pripratom i kupolom. U zidinama hrama ugrađeni su rimski reljefi i spolije iz obližnjeg Viminacijuma. U porti se nalazi kapela „Svetinja“ nad grobom Svete mučenice Nikoline sa čudotvornim izvorom lekovite vode.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Nimnik je ženski manastir Eparhije braničevske. Zbog groba mučenice Nikoline i lekovite vode, jedno je od najposećenijih svetilišta u Braničevu.",
        'images' => [
            ['url' => 'images/monasteries/nimnik.jpg', 'caption' => 'Crkva Prenosa moštiju Svetog Nikole u manastiru Nimnik sa rozetom' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/nimnik_gal_1.jpg', 'caption' => 'Pogled na manastirski kompleks Nimnik sa konakom i zelenom portom' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/nimnik_gal_2.jpg', 'caption' => 'Antički rimski reljef (spolija) uzidan u kameni zid hrama u Nimniku' . $src, 'sort_order' => 3],
        ]
    ],

    // 30: Manastir Pokajnica
    30 => [
        'name' => 'Manastir Pokajnica',
        'ktitor' => 'Knez Vujica Vulićević (1818. god)',
        'godina_izgradnje' => '1818',
        'card_image' => 'images/monasteries/pokajnica.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Pokajnica posvećen je Prenosu moštiju Svetog oca Nikolaja i nalazi se u Starom Selu kod Velike Plane. Podigao ga je 1818. godine knez Vujica Vulićević u znak pokajanja što je učestvovao u ubistvu svog kuma, vožda Karađorđa Petrovića u Radovanjskom lugu 1817. godine. Zbog tog čina narod je svetinju nazvao Pokajnica.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jedna od najlepših i najvećih crkava brvnara u Srbiji, građena od hrastovih talpi sa strmim krovom od šindre i tremom. Unutrašnjost krasi originalni drveni ikonostas koji je 1818. godine oslikao čuveni zograf Konstantin.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Pokajnica je muški manastir Eparhije braničevske i spomenik kulture od izuzetnog značaja. Predstavlja jedinstveni istorijski i pokajnički spomenik srpskog naroda.",
        'images' => [
            ['url' => 'images/monasteries/pokajnica.jpg', 'caption' => 'Crkva brvnara Prenosa moštiju Svetog Nikole iz 1818. godine u Pokajnici' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/pokajnica_gal_1.jpg', 'caption' => 'Pogled na crkvu i zvonik kroz drvenu kapiju manastirskog kompleksa' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/pokajnica_gal_2.jpg', 'caption' => 'Drvena rezbarena tabla sa natpisom „Manastir Pokajnica“' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/pokajnica_gal_3.jpg', 'caption' => 'Unutrašnjost crkve brvnare sa ikonostasom Konstantina zografa' . $src, 'sort_order' => 4],
        ]
    ],

    // 31: Manastir Radošin
    31 => [
        'name' => 'Manastir Radošin',
        'ktitor' => 'Sveti Despot Stefan Lazarević (1427. god)',
        'godina_izgradnje' => '1427',
        'card_image' => 'images/monasteries/radosin.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Radošin posvećen je Pokrovu Presvete Bogorodice i nalazi se u selu Radošin kod Svilajnca, na levoj obali Velike Morave. Podigao ga je 1427. godine Sveti Despot Stefan Lazarević u doba srpske Despotovine. Kroz vekove je više puta rušen od strane Turaka, a obnovljen je u 20. veku.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je skladna jednobrodna građevina sa polukružnom apsidom i zvonikom. Unutrašnjost krase rezbareni ikonostas i ikone novijeg datuma.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Radošin je ženski manastir Eparhije braničevske, koji okuplja vernike Pomoravlja i Resave.",
        'images' => [
            ['url' => 'images/monasteries/radosin.jpg', 'caption' => 'Crkva Pokrova Presvete Bogorodice sa konakom u manastiru Radošin' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/radosin_gal_1.jpg', 'caption' => 'Unutrašnjost crkve sa rezbarenim ikonostasom i celivajućom ikonom' . $src, 'sort_order' => 2],
        ]
    ],

    // 32: Manastir Ravanica
    32 => [
        'name' => 'Manastir Ravanica',
        'ktitor' => 'Sveti Knez Lazar Hrebeljanović (1375–1381)',
        'godina_izgradnje' => '1375',
        'card_image' => 'images/monasteries/ravanica.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Ravanica posvećen je Vaznesenju Gospodnjem i nalazi se u podnožju Kučajskih planina kod Ćuprije. Glavna je zadužbina i grobna crkva Svetog kneza Lazara Hrebeljanovića, sagrađena između 1375. i 1381. godine. Nakon pogibije kneza Lazara u Boju na Kosovu 1389. godine, njegove svete i netruležne mošti prenete su u Ravanicu 1392. godine. Manastir je opasan masivnim utvrđenjem sa 7 kula.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Vaznesenja Gospodnjeg je rodonačelnik **moravske škole arhitekture** — petokupolna trikonhalna građevina sa polihromnom fasadom od opeke i kamena, bogato ukrašena kamenim rozetama i reljefima. Ravanički živopis spada u vrhunac srpskog srednjovekovnog slikarstva, sa čuvenom ktitorskom kompozicijom kneza Lazara i kneginje Milice i prikazima Hristovih čuda. U crkvi počivaju svete mošti Svetog kneza Lazara Kosovskog.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Ravanica je ženski manastir Eparhije braničevske i spomenik kulture od izuzetnog značaja. Predstavlja jedno od najsvetijih i najvažnijih zavetnih mesta čitavog srpskog naroda.",
        'images' => [
            ['url' => 'images/monasteries/ravanica.jpg', 'caption' => 'Petokupolni hram Vaznesenja Gospodnjeg u manastiru Ravanica kod Ćuprije' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/ravanica_gal_1.jpg', 'caption' => 'Srednjovekovni kameni reljef sa motivom krilatih životinja na fasadi' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/ravanica_gal_2.jpg', 'caption' => 'Zapadna fasada crkve sa bogato ukrašenim biforama i rozetom' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/ravanica_gal_3.jpg', 'caption' => 'Pogled na manastirski kompleks sa masivnim kamenim kulama i bedemima' . $src, 'sort_order' => 4],
        ]
    ],

    // 33: Manastir Reškovica
    33 => [
        'name' => 'Manastir Reškovica',
        'ktitor' => 'Sveti Knez Lazar Hrebeljanović (1380) / obnovio otac Tadej',
        'godina_izgradnje' => '1380',
        'card_image' => 'images/monasteries/reskovica.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Reškovica posvećen je Svetom apostolu Tomi i nalazi se u netaknutoj prirodi Homoljskih planina, kod sela Ždrelo. Potiče iz 14. veka iz doba kneza Lazara. Manastir je u 20. veku počeo da obnavlja čuveni starac Tadej Vitovnički, a gradnju je nastavio arhimandrit Danilo.\n\nARHITEKTURA I UNUTRAŠNJOST:\nKompleks se sastoji od više crkava i kapela građenih u kamenu u nekoliko nivoa, uklopljenih u planinski pejzaž Homolja.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Reškovica je muški manastir Eparhije braničevske, mesto molitvenog tihovanja i duhovnog mira podno Homoljskih vrhova.",
        'images' => [
            ['url' => 'images/monasteries/reskovica.jpg', 'caption' => 'Crkva Svetog apostola Tome u manastiru Reškovica podno Homoljskih planina' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/reskovica_gal_1.jpg', 'caption' => 'Pogled na novopodignuti višespratni hram u netaknutoj prirodi Homolja' . $src, 'sort_order' => 2],
        ]
    ],

    // 34: Manastir Rukumija
    34 => [
        'name' => 'Manastir Rukumija',
        'ktitor' => 'Sveti Knez Lazar Hrebeljanović (1380. god)',
        'godina_izgradnje' => '1380',
        'card_image' => 'images/monasteries/rukumija.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Rukumija posvećen je Svetom ocu Nikolaju i nalazi se u ataru sela Bradarac kod Požarevca. Prema narodnoj pesmi „Bog nikom dužan ne ostaje“, manastir je podigao knez Lazar na mestu gde je pala ruka nevino stradale sestre Jelice. Kroz vekove je bio duhovni oslonac naroda Stiga i Braničeva.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna građevina sa otvorenim drvenim tremom, zvonikom i konacima. Unutrašnjost krasi prelepi rezbareni ikonostas i freske. U manastiru se nalazi i grob sestre Jelice, kao i izvor lekovite vode.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Rukumija je ženski manastir Eparhije braničevske, nadaleko čuven po strogom monaškom tipiku, bogosluženjima i molitvenom radu.",
        'images' => [
            ['url' => 'images/monasteries/rukumija.jpg', 'caption' => 'Crkva Svetog Nikole sa otvorenim drvenim tremom u manastiru Rukumija' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/rukumija_gal_1.jpg', 'caption' => 'Glavna ulazna kapija sa zvonikom i natpisom manastira Rukumija' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/rukumija_gal_2.jpg', 'caption' => 'Drveni rezbareni baldahin sa raspećem u cvetnoj porti' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/rukumija_gal_3.jpg', 'caption' => 'Unutrašnjost hrama sa rezbarenim ikonostasom i freskopisom' . $src, 'sort_order' => 4],
        ]
    ],

    // 35: Manastir Sestroljin
    35 => [
        'name' => 'Manastir Sestroljin',
        'ktitor' => 'Sveti Knez Lazar Hrebeljanović (1380. god)',
        'godina_izgradnje' => '1380',
        'card_image' => 'images/monasteries/sestroljin.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Sestroljin posvećen je Vaznesenju Gospodnjem i nalazi se u selu Poljana kod Požarevca. Prema predanju, osnovao ga je knez Lazar na mestu gde su pale oči nevino nastradale sestre Jelice. U narodu je vekovima poznat po čudotvornom i lekovitom izvoru vode koji pomaže kod očnih bolesti.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna kamena građevina sa polukružnom apsidom i pripratom. U sklopu porte nalazi se natkrivena kapela nad čudotvornim izvorom lekovite vode.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Sestroljin pripada Eparhiji braničevskoj i privlači brojne hodočasnike na praznik Spasovdana i tokom cele godine.",
        'images' => [
            ['url' => 'images/monasteries/sestroljin.jpg', 'caption' => 'Crkva Vaznesenja Gospodnjeg u manastiru Sestroljin kod Požarevca' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/sestroljin_gal_1.jpg', 'caption' => 'Unutrašnjost manastirskog hrama sa ikonostasom' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/sestroljin_gal_2.jpg', 'caption' => 'Kapela nad čudotvornim izvorom lekovite vode u cvetnom okruženju' . $src, 'sort_order' => 3],
        ]
    ],

    // 36: Manastir Sisojevac
    36 => [
        'name' => 'Manastir Sisojevac',
        'ktitor' => 'Prepodobni Sisoje Sinait i knez Lazar Hrebeljanović (1389. god)',
        'godina_izgradnje' => '1389',
        'card_image' => 'images/monasteries/sisojevac.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Sisojevac posvećen je Preobraženju Gospodnjem i nalazi se na samom izvoru reke Crnice, u selu Sisevac kod Paraćina. Podigao ga je krajem 14. veka (oko 1389. godine) isihasta Prepodobni Sisoje Sinait, uz pomoć kneza Lazara i carice Milice. U crkvi se nalazi grobnica sa svetim moštima Prepodobnog Sisoja Sinaita.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Preobraženja je jednobrodna trikonhalna građevina moravske škole sa kupolom. Unutrašnjost čuva izuzetno vredan živopis iz prve polovine 15. veka, sa čuvenom ktitorskom freskom Prepodobnog Sisoja koji u ruci drži model manastirskog hrama.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Sisojevac je ženski manastir Eparhije braničevske i spomenik kulture od velikog značaja. Zbog izvora Crnice i svetih moštiju Prepodobnog Sisoja, predstavlja duhovni biser Kučajskih planina.",
        'images' => [
            ['url' => 'images/monasteries/sisojevac.jpg', 'caption' => 'Crkva Preobraženja Gospodnjeg u manastiru Sisojevac na izvoru reke Crnice' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/sisojevac_gal_1.jpg', 'caption' => 'Pogled na moravski hram sa kupolom i odvojeni zvonik' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/sisojevac_gal_2.jpg', 'caption' => 'Južna fasada crkve sa prepoznatljivim plitkim reljefima i freskama' . $src, 'sort_order' => 3],
        ]
    ],

    // 37: Manastir Tomić
    37 => [
        'name' => 'Manastir Tomić',
        'ktitor' => 'Vlastelin Zetan Tomić (15. vek)',
        'godina_izgradnje' => '1400',
        'card_image' => 'images/monasteries/tomic.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Tomić posvećen je Svetom apostolu Tomi i nalazi se na šumovitim padinama iznad Velike Morave, u blizini sela Vojska kod Svilajnca. Podignut je početkom 15. veka u doba despota Stefana Lazarevića, a zadužbina je vlastelina Tomića.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je manja jednobrodna građevina građena od kamena sa polukružnom apsidom. Unutrašnjost krase ikone i ikonostas u tradicionalnom stilu.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Tomić je ženski manastir Eparhije braničevske, koji neguje mir i tihovanje u dolini Morave.",
        'images' => [
            ['url' => 'images/monasteries/tomic.jpg', 'caption' => 'Crkva Svetog apostola Tome u manastiru Tomić kod Vojske na Velikoj Moravi' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/tomic_gal_1.jpg', 'caption' => 'Pogled na manastirski kompleks i noviji konak u šumovitom predelu' . $src, 'sort_order' => 2],
        ]
    ],

    // 38: Manastir Trška Crkva
    38 => [
        'name' => 'Manastir Trška Crkva',
        'ktitor' => 'Nemanjići / raški zadužbinari (kraj 13. veka)',
        'godina_izgradnje' => '1274',
        'card_image' => 'images/monasteries/trska-crkva.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Trška Crkva posvećen je Rođenju Presvete Bogorodice i nalazi se u selu Milatovac kod Žagubice, u Homoljskoj kotlini. Najstariji je sačuvani srednjovekovni spomenik u Homolju, a prema arhitektonskim odlikama potiče s kraja 13. veka (oko 1274. godine) iz doba kralja Dragutina i kralja Milutina.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva pripada raškoj školi arhitekture sa primesama primorske romanike. Zidana je od klesanog peščara i sige. Posebnu vrednost predstavljaju jedinstveni reljefi na zapadnom portalu — ranosrednjovekovni prikazi dvoglavog orla, krilatih grifona i lavova, koji spadaju u najstarije heraldičke motive u srpskoj umetnosti.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nTrška crkva je spomenik kulture od velikog značaja Eparhije braničevske i najstariji svetionik pravoslavlja u Homolju.",
        'images' => [
            ['url' => 'images/monasteries/trska-crkva.jpg', 'caption' => 'Trška Crkva (Rođenje Presvete Bogorodice) iz 13. veka kod Žagubice' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/trska-crkva_gal_1.jpg', 'caption' => 'Pogled na arhaičnu kamenu građevinu raškog stila sa pripratom' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/trska-crkva_gal_2.jpg', 'caption' => 'Čuveni reljef dvoglavog orla i krilatih grifona na zapadnom portalu' . $src, 'sort_order' => 3],
        ]
    ],

    // 39: Manastir Tumane
    39 => [
        'name' => 'Manastir Tumane',
        'ktitor' => 'Miloš Obilić (predanje, 14. vek) i prepodobni Zosim Sinait',
        'godina_izgradnje' => '1389',
        'card_image' => 'images/monasteries/tumane.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Tumane posvećen je Svetom arhangelu Gavrilu i nalazi se u živopisnoj dolini Tumanske reke kod Golupca, u podnožju Golubačkih planina. Prema predanju, osnovao ga je srpski junak Miloš Obilić neposredno pred Boj na Kosovu. Prilikom lova u šumi, Miloš je nehotice ranio isposnika Prepodobnog Zosima Sinaita. Noseći ga ka svom dvoru da mu vida rane, svetitelj mu je rekao „Tu mani“ (tu me ostavi i pusti da umrem), te je na tom mestu Miloš Obilić počeo gradnju zadužbine.\n\nARHITEKTURA I UNUTRAŠNJOST:\nDanašnja crkva sagrađena je 1924. godine u srpsko-vizantijskom stilu sa kupolom. U manastiru se nalaze dve velike svetinje — kivot sa netruležnim moštima Prepodobnog Zosima Sinaita Čudotvorca i mošti Prepodobnog Jakova Novog (učenika vladike Nikolaja Velimirovića), kao i čudotvorna ruska ikona Presvete Bogorodice Kurske. U šumi iznad manastira nalazi se i pećinska isposnica Svetog Zosima.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Tumane je muški manastir Eparhije braničevske i najposećenije svetilište u celoj Srbiji, poznato kao „Đerdapski Ostrog“. Hiljade hodočasnika svakodnevno dolazi u Tumane sa molitvama za isceljenje pred moštima Svetog Zosima i Svetog Jakova.",
        'images' => [
            ['url' => 'images/monasteries/tumane.jpg', 'caption' => 'Manastir Tumane kod Golupca — svetilište Svetog Zosima i Svetog Jakova' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/tumane_gal_1.jpg', 'caption' => 'Crkva Svetog arhangela Gavrila u srpsko-vizantijskom stilu sa kupolom' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/tumane_gal_2.jpg', 'caption' => 'Pogled na hram i uređeni manastirski trg u dolini Tumanske reke' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/tumane_gal_3.jpg', 'caption' => 'Veliki manastirski konak i gostoprimnica za mnogobrojne hodočasnike' . $src, 'sort_order' => 4],
        ]
    ],

    // 40: Manastir Zaova
    40 => [
        'name' => 'Manastir Zaova',
        'ktitor' => 'Sveti Knez Lazar Hrebeljanović (1380. god)',
        'godina_izgradnje' => '1380',
        'card_image' => 'images/monasteries/zaova.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Zaova posvećen je Vaznesenju Gospodnjem i nalazi se u gustoj Zaovačkoj šumi kod sela Veliko Selo u opštini Malo Crniće. Prema narodnoj pesmi i predanju, podigao ga je knez Lazar na mestu gde je izdahnula nevina devojka Jelica koju su braća rastrgla na konjima zbog lažne klevete zavidne snahe („Za-ova“ — sestra muža).\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna kamena građevina sa polukružnom apsidom i zvonikom. Unutrašnjost hrama krasi vredan ikonostas i freskopis iz 19. veka (rad zografa Živka Pavlovića), a u blizini se nalazi grob sestre Jelice i lekoviti izvor.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Zaova pripada Eparhiji braničevskoj i predstavlja omiljeno molitveno i izletničko svetilište Stiga i Braničeva.",
        'images' => [
            ['url' => 'images/monasteries/zaova.jpg', 'caption' => 'Crkva Vaznesenja Gospodnjeg u manastiru Zaova u Zaovačkoj šumi' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/zaova_gal_1.jpg', 'caption' => 'Kamena oltarska apsida sa ukrasnim slepim arkadama i ružama' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/zaova_gal_2.jpg', 'caption' => 'Stari oltarski freskopis i unutrašnjost crkve manastira Zaova' . $src, 'sort_order' => 3],
        ]
    ],

    // 41: Manastir Zlatenac
    41 => [
        'name' => 'Manastir Zlatenac',
        'ktitor' => 'Sveti Despot Stefan Lazarević (1427. god)',
        'godina_izgradnje' => '1427',
        'card_image' => 'images/monasteries/zlatenac.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Zlatenac posvećen je Svetim vračima Kozmi i Damjanu i nalazi se na desnoj obali Velike Morave kod sela Gložane u opštini Svilajnac. Podigao ga je 1427. godine despot Stefan Lazarević. Tokom turske vladavine manastir je spaljen, a obnovljen je u 20. veku.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna građevina sa kupolom i belom fasadom. Unutrašnjost krase rezbareni ikonostas i prelepo uređena cvetna porta sa stepeništem kroz šumu.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Zlatenac je ženski manastir Eparhije braničevske, poznat po izuzetnoj duhovnoj toplini sestrinstva i monaškom miru uz Moravu.",
        'images' => [
            ['url' => 'images/monasteries/zlatenac.jpg', 'caption' => 'Crkva Svetih Vrača Kozme i Damjana u manastiru Zlatenac pod hrastovima' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/zlatenac_gal_1.jpg', 'caption' => 'Stepenište kroz šumski ambijent koje vodi ka manastiru Zlatenac' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/zlatenac_gal_2.jpg', 'caption' => 'Lučna manastirska kapija sa krstom i mozaikom Svete Petke' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/zlatenac_gal_3.jpg', 'caption' => 'Crkva manastira Zlatenac sa belom fasadom, kupolom i portom' . $src, 'sort_order' => 4],
        ]
    ],

    // 42: Manastir Đerinac
    42 => [
        'name' => 'Manastir Đerinac',
        'ktitor' => 'Srpska srednjovekovna vlastela (14. vek) / obnovljen u 20. veku',
        'godina_izgradnje' => '1400',
        'card_image' => 'images/monasteries/djerinac.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Đerinac posvećen je Svetom caru Konstantinu i carici Jeleni i nalazi se u ataru sela Bistrica kod Petrovca na Mlavi. Potiče iz srednjeg veka, a u novije vreme je potpuno obnovljen pod okriljem Eparhije braničevske.\n\nARHITEKTURA I UNUTRAŠNJOST:\nManastirska crkva je skladna jednobrodna kamena građevina sa polukružnom apsidom i zvonikom. Unutrašnjost krasi drveni ikonostas.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Đerinac pripada Eparhiji braničevskoj i sabira verni narod mlavskog kraja o prazniku Svetog cara Konstantina i Jelene.",
        'images' => [
            ['url' => 'images/monasteries/djerinac.jpg', 'caption' => 'Crkva Svetih Konstantina i Jelene u manastiru Đerinac kod Petrovca na Mlavi' . $src, 'sort_order' => 1],
        ]
    ],

    // 43: Manastir Ždrelo
    43 => [
        'name' => 'Manastir Ždrelo',
        'ktitor' => 'Stefan Uroš II Milutin (kraj 13. veka)',
        'godina_izgradnje' => '1300',
        'card_image' => 'images/monasteries/zdrelo.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Ždrelo posvećen je Svetoj Trojici i nalazi se na ulazu u Gornjačku klisuru u selu Ždrelo kod Petrovca na Mlavi. Podigao ga je krajem 13. veka srpski kralj Stefan Uroš II Milutin. Manastir je kroz vekove bio duhovna straža na ulazu u Homolje.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna kamena građevina sa zvonikom i konakom izgrađenim u tradicionalnom planinskom stilu. Unutrašnjost krase ikone i ikonostas u duhu pravoslavnog predanja.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Ždrelo pripada Eparhiji braničevskoj i predstavlja prelepu duhovnu stanicu na putu ka Homoljskim planinama.",
        'images' => [
            ['url' => 'images/monasteries/zdrelo.jpg', 'caption' => 'Crkva Svete Trojice u manastiru Ždrelo u podnožju Homoljskih planina' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/zdrelo_gal_1.jpg', 'caption' => 'Pogled na hram sa zvonikom i lepo uređenim planinskim konakom' . $src, 'sort_order' => 2],
        ]
    ],
];

echo "2. Ažuriranje primarne baze podataka (database/database.sqlite):\n";
DB::beginTransaction();

try {
    foreach ($batch3_data as $monasteryId => $data) {
        $monastery = Monastery::find($monasteryId);
        if (!$monastery) {
            echo "  [UPOZORENJE] Manastir ID {$monasteryId} nije pronađen!\n";
            continue;
        }

        if (isset($data['name'])) {
            $monastery->name = $data['name'];
        }
        if (isset($data['ktitor'])) {
            $monastery->ktitor = $data['ktitor'];
        }
        if (isset($data['godina_izgradnje'])) {
            $monastery->godina_izgradnje = $data['godina_izgradnje'];
        }
        if (isset($data['description'])) {
            $monastery->description = $data['description'];
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

        $count = count($data['images']);
        echo "  [AŽURIRAN] [{$monasteryId}] {$monastery->name} | Ktitor: {$monastery->ktitor} | Galerija: {$count} slika\n";
    }

    DB::commit();
    echo "\nPrimarna baza je uspešno ažurirana!\n\n";
} catch (\Exception $e) {
    DB::rollBack();
    echo "GREŠKA pri radu sa primarnom bazom: " . $e->getMessage() . "\n";
    exit(1);
}

// 3. Sinhronizacija u storage/database.sqlite
$storageDbPath = storage_path('database.sqlite');
if (file_exists($storageDbPath)) {
    echo "3. Ažuriranje storage baze podataka ({$storageDbPath}):\n";
    try {
        $pdo = new PDO('sqlite:' . $storageDbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $pdo->beginTransaction();

        foreach ($batch3_data as $monasteryId => $data) {
            $stmt = $pdo->prepare("UPDATE monasteries SET name = :name, ktitor = :ktitor, godina_izgradnje = :god, description = :desc, image_url = :image_url, image = :img WHERE id = :id");
            $stmt->execute([
                ':name' => $data['name'],
                ':ktitor' => $data['ktitor'],
                ':god' => $data['godina_izgradnje'],
                ':desc' => $data['description'],
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

            echo "  [STORAGE AŽURIRAN] [{$monasteryId}] {$data['name']}\n";
        }

        $pdo->commit();
        echo "Storage baza je uspešno ažurirana!\n\n";
    } catch (\Exception $e) {
        $pdo->rollBack();
        echo "GREŠKA pri radu sa storage bazom: " . $e->getMessage() . "\n";
    }
}

echo "====================================================================\n";
echo "BATCH 3 (SREMSKA, BRANIČEVSKA) ZAVRŠEN USPEŠNO!\n";
echo "====================================================================\n";
