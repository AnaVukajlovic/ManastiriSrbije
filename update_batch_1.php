<?php

/**
 * SISTEMSKO ČIŠĆENJE I SINHRONIZACIJA:
 * - EPARHIJA BEOGRADSKA (ID 3)
 * - EPARHIJA BAČKA (ID 7)
 * - EPARHIJA BANATSKA (ID 6)
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
echo "POKRETANJE REVIZIJE ZA EPARHIJE: BEOGRADSKA (3), BAČKA (7), BANATSKA (6)\n";
echo "====================================================================\n\n";

$src = '<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>';

$batch1_data = [
    // ====================================================================
    // EPARHIJA BEOGRADSKA (ID 3)
    // ====================================================================

    // 15: Manastir Mislođin
    15 => [
        'name' => 'Manastir Mislođin',
        'ktitor' => 'Stefan Dragutin Nemanjić (kralj Srema)',
        'godina_izgradnje' => '1280',
        'card_image' => 'images/monasteries/mislodjin.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Mislođin (poznat i kao manastir Svetog Hristofora) nalazi se u selu Mislođin u opštini Obrenovac, na pitomim padinama iznad reke Kolubare. Prema istorijskim podacima i arheološkim nalazima, manastir je u 13. veku (oko 1280. godine) podigao srpski kralj Stefan Dragutin Nemanjić, koji je kao kralj Srema i Mačve upravljao ovim krajevima. Svetinja je više puta rušena u turskim pohodima, a u novije vreme je potpuno obnovljena.\n\nARHITEKTURA I UNUTRAŠNJOST:\nManastirska crkva posvećena Svetom velikomučeniku Hristoforu sagrađena je u tradicionalnom srpsko-vizantijskom stilu od tesanog kamena i opeke sa vitkom kupolom. Unutrašnjost hrama krasi raskošan ikonostas sa ručno slikanim ikonama i bogat zidni živopis u kome dominiraju scene iz jevanđelja i likovi srpskih svetitelja.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Mislođin je danas aktivan ženski manastir Arhiepiskopije beogradsko-karlovačke. Predstavlja važno duhovno utočište pravoslavnih vernika Obrenovca i Beograda, gde se redovno vrše sveta bogosluženja i neguje molitveno tihovanje.",
        'images' => [
            ['url' => 'images/monasteries/mislodjin.jpg', 'caption' => 'Crkva Svetog Hristofora sa kupolom i konacima u manastiru Mislođin kod Obrenovca' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/mislodjin_gal_1.jpg', 'caption' => 'Glavni ulazni portal manastirskog hrama sa mozaikom patrona hrama' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/mislodjin_gal_2.jpg', 'caption' => 'Uređena manastirska porta sa cvetnim lejama i zvonikom u Mislođinu' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/mislodjin_gal_3.jpg', 'caption' => 'Unutrašnjost hrama sa oslikanom kupolom i rezbarenim ikonostasom' . $src, 'sort_order' => 4],
        ]
    ],

    // 16: Manastir Rajinovac
    16 => [
        'name' => 'Manastir Rajinovac',
        'ktitor' => 'Raja (beogradski knez / predanje) / obnovio knez Stevan Andrejević Palalija',
        'godina_izgradnje' => '1528',
        'card_image' => 'images/monasteries/rajinovac.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Rajinovac nalazi se u selu Begaljica nedaleko od Grocke, u plodnom i voćarskom beogradskom Podunavlju. Posvećen je Rođenju Presvete Bogorodice (Maloj Gospojini). Prema narodnom predanju, manastir je u 16. veku osnovao pobožni Raja, sluga beogradskog despota. U pisanim turskim defterima pominje se 1528. godine. Manastir je temeljno obnovio knez gročanski Stevan Andrejević Palalija krajem 18. veka pre Seče knezova.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Rođenja Presvete Bogorodice je jednobrodna građevina sa polukružnom apsidom i zvonikom baroknih formi. U hramu se čuva čudotvorna ikona Presvete Bogorodice Rajinovačke, koja vekovima privlači nebrojene hodočasnike sa molitvama za isceljenje i porod. Ikonostas je delo čuvenih zografa iz 19. veka.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nRajinovac je jedan od najposećenijih manastira u okolini Beograda. Kao duhovno središte i čuvar pravoslavne tradicije i čudotvorne ikone Majke Božije, manastir svakodnevno pruža utehu i liturgijsko sabranje stotinama vernika.",
        'images' => [
            ['url' => 'images/monasteries/rajinovac.jpg', 'caption' => 'Crkva Rođenja Presvete Bogorodice u manastiru Rajinovac u Begaljici kod Grocke' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/rajinovac_gal_1.jpg', 'caption' => 'Zapadna fasada sa baroknim zvonikom i ulazom u manastirski hram' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/rajinovac_gal_2.jpg', 'caption' => 'Čudotvorna ikona Presvete Bogorodice Rajinovačke u hramu' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/rajinovac_gal_3.jpg', 'caption' => 'Prostrani manastirski konak sa cvetnim vrtom i česmom u porti' . $src, 'sort_order' => 4],
        ]
    ],

    // 17: Manastir Rakovica
    17 => [
        'name' => 'Manastir Rakovica',
        'ktitor' => 'Stefan Dragutin Nemanjić / vojvoda Radul I Crni (obnovili knez Miloš Obrenović i knez Mihailo)',
        'godina_izgradnje' => '1380',
        'card_image' => 'images/monasteries/rakovica.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Rakovica posvećen je Svetim arhangelima Mihailu i Gavrilu i nalazi se u beogradskom naselju Rakovica, u dolini Rakovičkog potoka. Nastanak manastira vezuje se za kraj 13. i 14. vek i doba kralja Stefana Dragutina i vlaškog kneza Radula I. Manastir je u 16. veku premešten na današnje, skrovitije mesto. Kroz istoriju je bio centar pismenosti i otpora turskoj vlasti, a u 19. veku ga je bogato darivala dinastija Obrenović.\n\nARHITEKTURA I UNUTRAŠNJOST:\nManastirska crkva je građena po ugledu na moravsku školu, sa trikonhalnom osnovom i kupolom. Unutrašnjost krase izuzetno vredan barokno-klasicistički ikonostas i grobnice znamenitih srpskih ličnosti — u porti počivaju srpski patrijarsi Dimitrije i Pavle, kao i sin kneza Miloša Todor i junak Prvog srpskog ustanka Vasa Čarapić.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Rakovica je ženski manastir i jedna od najvećih svetinja Beograda. Grob patrijarha Pavla mesto je neprestanog poklonjenja hiljada vernika iz celog sveta koji u Rakovici nalaze molitveni mir i blagoslov.",
        'images' => [
            ['url' => 'images/monasteries/rakovica.jpg', 'caption' => 'Ulazni prilaz, zvonik-kapija, konaci i zelena porta manastira Rakovica u Beogradu' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/rakovica_gal_1.jpg', 'caption' => 'Raskošna unutrašnjost crkve u Rakovici sa pozlaćenim ikonostasom i freskama' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/rakovica_gal_2.jpg', 'caption' => 'Grobnice srpskih patrijaraha Dimitrija i Pavla u porti manastira Rakovica' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/rakovica_gal_3.jpg', 'caption' => 'Bela kamena spomen-česma u manastirskoj porti sa cvetnim lejama' . $src, 'sort_order' => 4],
        ]
    ],

    // 18: Manastir Vavedenje na Senjaku
    18 => [
        'name' => 'Manastir Vavedenje na Senjaku',
        'ktitor' => 'Persida Milenković (sa arhitektom Petrom Popovićem)',
        'godina_izgradnje' => '1935',
        'card_image' => 'images/monasteries/senjak.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Vavedenja Presvete Bogorodice nalazi se na Senjaku, u elitnom delu Beograda. Podignut je 1935. godine kao zadužbina velike srpske dobrotvorke Perside Milenković. Prema predanju, ktitorka je usnula san u kome joj je naloženo da na tom uzvišenju podigne manastirski hram. Hram je osveštao patrijarh srpski Varnava 1936. godine.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je remek-delo srpsko-vizantijskog stila arhitekte Petra Popovića. Zidana je sa pet kupola i skladnim fasadnim reljefima. Ikonostas i živopis radili su čuveni ruski akademski slikari emigranti, unoseći prefinjeni duh staroruskog i vizantijskog freskopisa. U konaku manastira nalazi se kapela Svetog Nikole.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Vavedenje je ženski manastir i prepoznatljivo duhovno središte prestonice. Poznat je po bogosluženjima, monaškom pojanju i velikom broju beogradskih vernika koji redovno prisustvuju svetim liturgijama.",
        'images' => [
            ['url' => 'images/monasteries/senjak.jpg', 'caption' => 'Monumentalna bela fasada sa kupolama hrama Vavedenja Presvete Bogorodice na Senjaku' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/senjak_gal_1.jpg', 'caption' => 'Klesani kameni portal sa mozaikom Presvete Bogorodice i ktitorskim natpisom iz 1936. godine' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/senjak_gal_2.jpg', 'caption' => 'Konak manastira Vavedenje sa zvonikom u mirnom ambijentu naselja Senjak' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/senjak_gal_3.jpg', 'caption' => 'Zidna freska Svetog Save Srpskog u unutrašnjosti hrama manastira Vavedenje' . $src, 'sort_order' => 4],
        ]
    ],

    // 19: Manastir Slanci
    19 => [
        'name' => 'Manastir Slanci',
        'ktitor' => 'Stefan Dragutin Nemanjić (obnovio Sveti Despot Stefan Lazarević)',
        'godina_izgradnje' => '1300',
        'card_image' => 'images/monasteries/slanci.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Slanci (posvećen Svetom arhiđakonu Stefanu) nalazi se u pitomoj kotlini kod sela Slanci na teritoriji beogradske opštine Palilula. Prema predanju, osnovao ga je srpski kralj Stefan Dragutin Nemanjić krajem 13. veka, dok ga je početkom 15. veka obnovio Sveti Despot Stefan Lazarević. U doba turske vlasti bio je metoh manastira Hilandara, što je ponovo postao krajem 20. veka.\n\nARHITEKTURA I UNUTRAŠNJOST:\nDanašnji hram je podignut na temeljima starog srednjovekovnog zdanja u srpsko-vizantijskom stilu sa skladnom kupolom i otvorenim tremom. Unutrašnjost krase ikone rađene u svetogorskom stilu i bogat liturgijski inventar koji svedoči o neraskidivoj vezi sa manastirom Hilandarom.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nKao zvanični metoh Svete carske lavre manastira Hilandara, Slanci neguju svetogorski tipik, celonoćna bdenija i strogi duhovni život. Poznat je po izuzetnom gostoprimstvu i bratstvu koje svakodnevno hrani stotine potrebitih u svojoj trpezariji.",
        'images' => [
            ['url' => 'images/monasteries/slanci.jpg', 'caption' => 'Crkva Svetog arhiđakona Stefana u manastiru Slanci — metohu manastira Hilandara' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/slanci_gal_1.jpg', 'caption' => 'Bočna fasada manastirske crkve u Slancima sa lučnim prozorima i redom tuja' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/slanci_gal_2.jpg', 'caption' => 'Pogled na crkvu Svetog Stefana sa oltarske apside i kupolom' . $src, 'sort_order' => 3],
        ]
    ],

    // 20: Manastir Trojeručica
    20 => [
        'name' => 'Manastir Trojeručica',
        'ktitor' => 'Verni narod i arhimandrit Jovan (Radosavljević)',
        'godina_izgradnje' => '1998',
        'card_image' => 'images/monasteries/trojerucica.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Presvete Bogorodice Trojeručice nalazi se u naselju Drobnjaci u Ripnju podno planine Avale, dvadesetak kilometara južno od Beograda. Osnovan je krajem 20. veka (1998. godine) blagoslovom blaženopočivšeg patrijarha Pavla i trudom arhimandrita Jovana Radosavljevića, kao mesto molitvenog sabiranja i očuvanja kulta Čudotvorne ikone Bogorodice Trojeručice Hilandarske.\n\nARHITEKTURA I UNUTRAŠNJOST:\nManastirska crkva je autentična građevina brvnara od punog drveta sa strmim krovom od šindre, koja odiše skromnošću i ranohrišćanskom toplinom. U hramu se nalazi verna kopija čudotvorne ikone Bogorodice Trojeručice i prelepo rezbareni drveni ikonostas.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Trojeručica pod Avalom predstavlja pravu oazu mira i molitvenog tihovanja u neposrednoj blizini Beograda. Vernici iz cele Srbije dolaze ovde da zatraže pomoć i blagoslov pred ikonom Trojeručice.",
        'images' => [
            ['url' => 'images/monasteries/trojerucica.jpg', 'caption' => 'Crkva brvnara posvećena Bogorodici Trojeručici u manastiru Trojeručica u Ripnju pod Avalom' . $src, 'sort_order' => 1],
        ]
    ],

    // ====================================================================
    // EPARHIJA BAČKA (ID 7)
    // ====================================================================

    // 10: Manastir Bođani
    10 => [
        'name' => 'Manastir Bođani',
        'ktitor' => 'Trgovac Bogdan iz Dalmacije (1478) / Mihailo Temišvarlija',
        'godina_izgradnje' => '1478',
        'card_image' => 'images/monasteries/bodjani.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Bođani posvećen je Vavedenju Presvete Bogorodice i nalazi se u Bačkoj ravnici u blizini sela Bođani i grada Bača. Osnovao ga je 1478. godine trgovac Bogdan iz Dalmacije u znak zahvalnosti Bogorodici za isceljenje vida na lekovitom izvoru. Manastir je više puta plavljen izlivanjem Dunava i razaran tokom ratova, a sadašnji izgled dobio je u velikoj obnovi 1722. godine pod pokroviteljstvom Mihaila Temišvarlije.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Vavedenja Presvete Bogorodice predstavlja spoj vizantijske prostorne koncepcije i barokne spoljašnje dekoracije sa prepoznatljivim baroknim zvonikom. Najveće blago manastira je monumentalni živopis Hristofora Žefarovića iz 1737. godine, koji predstavlja prelomnu tačku u srpskom slikarstvu i uvođenje modernog evropskog baroka u pravoslavnu sakralnu umetnost. U crkvi se čuva i čudotvorna ikona Bogorodice Bođanske.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Bođani je spomenik kulture od izuzetnog značaja i jedan od najvažnijih duhovnih i umetničkih centara Eparhije bačke. U manastiru živi muško bratstvo posvećeno molitvi i održavanju ovog neprocenjivog kulturnog blaga.",
        'images' => [
            ['url' => 'images/monasteries/bodjani.jpg', 'caption' => 'Zapadna fasada crkve Vavedenja Presvete Bogorodice u manastiru Bođani sa baroknim zvonikom' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/bodjani_gal_1.jpg', 'caption' => 'Kompleks manastira Bođani sa četvorostranim spratnim konacima i parkom' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/bodjani_gal_2.jpg', 'caption' => 'Čuveni barokni freskopis Hristofora Žefarovića iz 1737. godine u naosu hrama' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/bodjani_gal_3.jpg', 'caption' => 'Oltarska apsida i severno krilo manastirskih konaka u cvetnom dvorištu' . $src, 'sort_order' => 4],
        ]
    ],

    // 11: Manastir Kać
    11 => [
        'name' => 'Manastir Kać',
        'ktitor' => 'Episkop bački Irinej (Bulović) i sestrinstvo',
        'godina_izgradnje' => '2010',
        'card_image' => 'images/monasteries/kac.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Vaskrsenja Hristova u Kaću kod Novog Sada najmlađi je manastir Eparhije bačke. Izgradnja ovog ženskog manastirskog kompleksa započeta je 2007. godine blagoslovom Njegovog Preosveštenstva Episkopa bačkog dr Irineja, a hram je osveštan 2010. godine. Podignut je u prostranom ataru Kaća kao duhovni rasadnik monaštva i molitve.\n\nARHITEKTURA I UNUTRAŠNJOST:\nManastirski hram je izgrađen u raskošnom vizantijskom stilu sa prepoznatljivom centralnom kupolom i fasadom od pečene opeke i kamena. Unutrašnjost krase vrhunski mozaici i freske radionice savremenih grčkih i srpskih ikonopisaca, kao i mermerni pod i ikonostas izuzetne umetničke vrednosti.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Kać je aktivan ženski manastir poznat po strogom tipiku, besprekornom uređenju imanja i gostoprimstvu sestrinstva. Privlači mnogobrojne vernike iz Novog Sada, Bačke i cele Vojvodine.",
        'images' => [
            ['url' => 'images/monasteries/kac.jpg', 'caption' => 'Glavni hram Vaskrsenja Hristovog u manastiru Kać u vizantijskom stilu' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/kac_gal_1.jpg', 'caption' => 'Mozaik Hrista Pantokratora i ulazni kameni portal manastirskog hrama' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/kac_gal_2.jpg', 'caption' => 'Unutrašnjost crkve sa mermernim ikonostasom i vizantijskim živopisom' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/kac_gal_3.jpg', 'caption' => 'Južna strana manastirskog hrama sa kupolom i konacima u Kaću' . $src, 'sort_order' => 4],
        ]
    ],

    // 12: Manastir Kovilj
    12 => [
        'name' => 'Manastir Kovilj',
        'ktitor' => 'Sveti Sava (Rastko Nemanjić - predanje) / obnovljen 1707. godine',
        'godina_izgradnje' => '1220',
        'card_image' => 'images/monasteries/kovilj.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Kovilj posvećen je Svetim arhangelima Mihailu i Gavrilu i nalazi se pored sela Kovilj nedaleko od Novog Sada, u specijalnom rezervatu prirode Koviljsko-petrovaradinski rit. Prema predanju, osnovao ga je u 13. veku Sveti Sava Nemanjić na mestu gde je izmirio ugarskog kralja Andriju II sa svojim bratom kraljem Stefanom Prvovenčanim. Najstariji pisani pomen manastira datira iz 1651. godine. Sadašnji monumentalni hram sagrađen je između 1705. i 1707. godine.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Svetih arhangela predstavlja monumentalnu jednobrodnu građevinu raškog stila sa elementima baroka, građenu od klesanog tesanika. Unutrašnjost krasi jedinstveni mermerni ikonostas, delo vajara Jovana Beslića, sa ikonama znamenitog akademskog slikara Aksentija Marodića. U hramu se čuvaju čudotvorna ikona Bogorodice Koviljske i čestice moštiju svetih ugodnika Božijih.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Kovilj je jedan od najuglednijih i najbrojnijih muških manastira Srpske pravoslavne crkve. Poznat je po vizantijskom pojanju, proizvodnji čuvene koviljske rakije, meda i melema, kao i po svojoj terapijskoj zajednici „Zemlja živih“ za rehabilitaciju mladih.",
        'images' => [
            ['url' => 'images/monasteries/kovilj.jpg', 'caption' => 'Zapadna fasada monumentalne kamene crkve Svetih arhangela u manastiru Kovilj' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/kovilj_gal_1.jpg', 'caption' => 'Panorama manastirskog kompleksa Kovilj sa kupolama hrama i konacima' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/kovilj_gal_2.jpg', 'caption' => 'Monumentalni mermerni ikonostas i freskopis unutar crkve manastira Kovilj' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/kovilj_gal_3.jpg', 'caption' => 'Visoki zvonik i detalji kamene plastike u porti manastira Kovilj' . $src, 'sort_order' => 4],
        ]
    ],

    // 13: Manastir Sombor
    13 => [
        'name' => 'Manastir Sombor',
        'ktitor' => 'Stevan Konjović i dr Svetozar Krotin',
        'godina_izgradnje' => '1928',
        'card_image' => 'images/monasteries/sombor.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Svetog arhiđakona Stefana u Somboru podignut je između 1928. i 1933. godine kao zadužbina Stevana Konjovića i somborskog inženjera dr Svetozara Krotina. Smešten je u mirnom delu grada Sombora i predstavlja jedini pravoslavni manastir na području Gornjeg Podunavlja.\n\nARHITEKTURA I UNUTRAŠNJOST:\nManastirska crkva je izgrađena u prepoznatljivom srpsko-vizantijskom stilu sa elementima moravske arhitekture prema projektu arhitekte Svetozara Krotina. Fasada se odlikuje alternacijom crvene opeke i svetlog maltera sa rozetama i biforama. Ikonostas i živopis unutrašnjosti hrama rad su akademskog slikara Vladimira Predojevića i ruskih zografa.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Svetog arhiđakona Stefana pripada Eparhiji bačkoj. Predstavlja važno liturgijsko i kulturno uporište pravoslavnog naroda u Somboru i čitavoj Zapadnobačkoj oblasti.",
        'images' => [
            ['url' => 'images/monasteries/sombor.jpg', 'caption' => 'Crkva Svetog arhiđakona Stefana u srpsko-vizantijskom stilu u manastiru Sombor' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/sombor_gal_1.jpg', 'caption' => 'Glavni ulaz sa kamenom rozetom, triforom i mozaikom svetitelja' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/sombor_gal_2.jpg', 'caption' => 'Pogled na manastirski hram sa južne strane sa zvonikom i pripratom' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/sombor_gal_3.jpg', 'caption' => 'Mermerna spomen-ploča inženjeru Svetozaru Krotinu u porti manastira' . $src, 'sort_order' => 4],
        ]
    ],

    // 14: Manastir Vodica (Bačka Palanka)
    14 => [
        'name' => 'Manastir Vodica',
        'ktitor' => 'Pravoslavni meštani Bačke Palanke',
        'godina_izgradnje' => '1860',
        'card_image' => 'images/monasteries/vodica.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Vodica (poznat i kao kapela Vodica Svetog proroka Ilije) nalazi se u pitomom prirodnom okruženju u ataru Bačke Palanke. Nastao je oko lekovitog izvora vode na kome su se, prema svedočenjima meštana još iz 18. veka, događala mnoga čudesna isceljenja.\n\nARHITEKTURA I UNUTRAŠNJOST:\nKapela je jednobrodna barokna građevina sa polukružnom apsidom, dekorativnim zabatom i malim zvonikom. Unutrašnjost krase ikone lokalnih vojvođanskih ikonopisaca iz 19. veka i natkriveni izvor svete i lekovite vode.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Vodica je sveto mesto okupljanja vernog naroda Bačke Palanke i okoline, posebno o prazniku Svetog proroka Ilije (Ilindan), kada se ovde vrši vodoosvećenje i služi sveta liturgija.",
        'images' => [
            ['url' => 'images/monasteries/vodica.jpg', 'caption' => 'Pravoslavna kapela Vodica sa ogradnim zidom i parkom u Bačkoj Palanci' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/vodica_gal_1.jpg', 'caption' => 'Zapadna fasada kapele sa baroknim dekorativnim zabatom i lučnim ulazom' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/vodica_gal_2.jpg', 'caption' => 'Unutrašnje dvorište i porta sa stazom pod krošnjama drveća' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/vodica_gal_3.jpg', 'caption' => 'Bočna strana kapele sa ovalnim prozorom i tradicionalnim crepom' . $src, 'sort_order' => 4],
        ]
    ],

    // ====================================================================
    // EPARHIJA BANATSKA (ID 6)
    // ====================================================================

    // 1: Manastir Bavanište
    1 => [
        'name' => 'Manastir Bavanište',
        'ktitor' => 'Srpski doseljenici u Banat (obnovljen krajem 19. veka)',
        'godina_izgradnje' => '1594',
        'card_image' => 'images/monasteries/bavaniste.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Bavanište posvećen je Rođenju Presvete Bogorodice i nalazi se u gustoj šumi u blizini sela Bavanište u opštini Kovin. Osnovan je krajem 16. veka (oko 1594. godine) u vreme Banatskog ustanka pod vođstvom episkopa vršačkog Teodora Nestorovića. Manastir je spaljen od strane Turaka 1716. godine, a obnovljen je krajem 19. veka (1856–1858) kada je narod na čudotvornom izvoru ponovo podigao svetinju.\n\nARHITEKTURA I UNUTRAŠNJOST:\nManastirski hram je skladna jednobrodna građevina sa polukružnom apsidom i zvonikom. U sklopu crkve nalazi se natkrivena česma sa čudotvornom i lekovitom vodom. Unutrašnjost krase ikone i zidni živopis u tradicionalnom pravoslavnom stilu.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Bavanište je ženski manastir Eparhije banatske. Predstavlja jedno od najomiljenijih hodočasničkih mesta južnog Banata, gde mnogobrojni vernici dolaze po isceljenje i duhovni mir.",
        'images' => [
            ['url' => 'images/monasteries/bavaniste.jpg', 'caption' => 'Manastirska crkva Rođenja Presvete Bogorodice u Bavaništu u šumskom ambijentu' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/bavaniste_gal_1.jpg', 'caption' => 'Oltarska apsida, kupola i fontana sa lekovitom vodom u porti manastira' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/bavaniste_gal_2.jpg', 'caption' => 'Zidana ulazna kapija sa mozaikom Rođenja Presvete Bogorodice' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/bavaniste_gal_3.jpg', 'caption' => 'Drveni manastirski zvonik sa zvonima i konakom u pozadini' . $src, 'sort_order' => 4],
        ]
    ],

    // 2: Manastir Gaj
    2 => [
        'name' => 'Manastir Gaj',
        'ktitor' => 'Episkop vršački i narod gajski',
        'godina_izgradnje' => '1735',
        'card_image' => 'images/monasteries/gaj.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Vaznesenja Gospodnjeg (Manastir Gaj) nalazi se u ataru sela Gaj kod Kovina u južnom Banatu. Podignut je u prvoj polovini 18. veka (oko 1735. godine) na mestu stare bogomolje. Kroz vekove je bio duhovni oslonac srpskog graničarskog naroda ovog dela Vojne krajine.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva manastira Gaj građena je u stilu vojvođanskog baroka sa masivnim zapadnim zvonikom, dekorativnim krovom i lučnim prozorima. U unutrašnjosti se nalazi bogat barokni ikonostas sa ikonama izuzetne likovne vrednosti.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Gaj pripada Eparhiji banatskoj. O prazniku Vaznesenja Gospodnjeg (Spasovdan) ovde se tradicionalno okupljaju vernici iz Kovina, Pančeva, Vršca i susednih banatskih mesta.",
        'images' => [
            ['url' => 'images/monasteries/gaj.jpg', 'caption' => 'Crkva Vaznesenja Gospodnjeg sa baroknim zvonikom u manastiru Gaj kod Kovina' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/gaj_gal_1.jpg', 'caption' => 'Barokni zvonik manastirske crkve sa satom i dekorativnom kapom' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/gaj_gal_2.jpg', 'caption' => 'Pogled na crkvu kroz drvored i kapiju manastirske porte' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/gaj_gal_3.jpg', 'caption' => 'Mermerni spomen-krst i natpis uzidani u fasadu crkve u Gaju' . $src, 'sort_order' => 4],
        ]
    ],

    // 3: Manastir Hajdučica
    3 => [
        'name' => 'Manastir Hajdučica',
        'ktitor' => 'Olga S. Jovanović (rođ. Dunđerski)',
        'godina_izgradnje' => '1939',
        'card_image' => 'images/monasteries/hajducica.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Svetih arhangela Mihaila i Gavrila u Hajdučici (opština Plandište) podignut je 1939. godine kao zadužbina Olge Jovanović, ćerke čuvenog veleposednika Lazara Dunđerskog. Ktitorka je manastir podigla sa željom da bude večno počivalište njene porodice i duhovni centar pravoslavnog naroda ovog dela Banata.\n\nARHITEKTURA I UNUTRAŠNJOST:\nManastirski hram je podignut u srpsko-vizantijskom stilu sa elementima ruske sakralne arhitekture. Ikonostas je rezbaren u drvetu, a ikone i živopis delo su poznatog ruskog emigrantskog slikara Vladimira Predojevića.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Hajdučica je ženski manastir Eparhije banatske. U manastiru se neguje molitveno tihovanje, izrada crkvenog veza i ikonopis, privlačeći poklonike iz čitavog regiona.",
        'images' => [
            ['url' => 'images/monasteries/hajducica.jpg', 'caption' => 'Crkva Svetih Arhanđela kroz kovanu ulaznu kapiju manastira Hajdučica' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/hajducica_gal_1.jpg', 'caption' => 'Manastirski kompleks sa zidanom ogradom, ulazom i portom u Hajdučici' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/hajducica_gal_2.jpg', 'caption' => 'Manastirski konak sa lučnim prozorima i zvonikom' . $src, 'sort_order' => 3],
        ]
    ],

    // 4: Manastir Mesić
    4 => [
        'name' => 'Manastir Mesić',
        'ktitor' => 'Sveti Arsenije Sremac / Sveti Despot Jovan Branković',
        'godina_izgradnje' => '1225',
        'card_image' => 'images/monasteries/mesic.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Mesić posvećen je Rođenju Svetog Jovana Krstitelja i nalazi se u podnožju Vršačkih planina, nekoliko kilometara istočno od Vršca. Prema predanju, osnovao ga je 1225. godine učenik Svetog Save i drugi srpski arhiepiskop Sveti Arsenije Sremac. Krajem 15. veka manastir je temeljno obnovio Sveti Despot Jovan Branković. Kroz burnu istoriju Mesić je bio sedište vršačkih episkopa i glavni čuvar srpskog identiteta u Banatu.\n\nARHITEKTURA I UNUTRAŠNJOST:\nManastirska crkva je jednobrodna građevina sa kupolom, kojoj je u 18. veku dozidan visoki barokni zvonik. U hramu se nalaze tri sloja fresaka — najstariji iz 14. veka i sloj iz 1743. godine zografa Andreje Andrejevića. U crkvi se čuva velika svetinja — Čudotvorna ikona Presvete Bogorodice Dostojno Jest (Aksion Estin), doneta sa Svete Gore 1803. godine.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Mesić je ženski manastir i spomenik kulture od izuzetnog značaja. Predstavlja najznačajniji manastir Eparhije banatske i jedno od najsvetijih mesta hodočašća u celoj Vojvodini.",
        'images' => [
            ['url' => 'images/monasteries/mesic.jpg', 'caption' => 'Panorama manastirskog kompleksa Mesić sa kamenom crkvom i baroknim zvonikom' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/mesic_gal_1.jpg', 'caption' => 'Zapadna fasada manastirske crkve sa pripratom i zvonikom' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/mesic_gal_2.jpg', 'caption' => 'Enterijer naosa sa srednjovekovnim freskama na stubovima i ikonostasom' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/mesic_gal_3.jpg', 'caption' => 'Manastirski konak sa kamenim stepeništem i popločanim dvorištem' . $src, 'sort_order' => 4],
        ]
    ],

    // 5: Manastir Središte
    5 => [
        'name' => 'Manastir Središte',
        'ktitor' => 'Sveti Despot Jovan Branković (obnovljen krajem 20. veka)',
        'godina_izgradnje' => '1490',
        'card_image' => 'images/monasteries/srediste.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Malo Središte (posvećen Čudu Svetog arhangela Mihaila u Honi) nalazi se na šumovitim padinama Guduričkog vrha u blizini Vršca. Podigao ga je krajem 15. veka (oko 1490. godine) poslednji srpski despot iz roda Brankovića, Sveti Despot Jovan Branković. Manastir je u 18. veku uništen u turskim napadima, a njegova sveobuhvatna obnova započeta je 1995. godine pod pokroviteljstvom vladike Hrizostoma Stolića.\n\nARHITEKTURA I UNUTRAŠNJOST:\nNovi manastirski kompleks sagrađen je u vizantijsko-moravskom stilu od crvene opeke i kamena. Čine ga crkva Svetog arhangela Mihaila, paraklis, monumentalna ulazna kula-zvonik i konaci koji stepenasto prate planinski reljef.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Središte je muški manastir Eparhije banatske. Poznat je po izuzetnoj duhovnoj atmosferi, monaškom miru i prelepoj prirodi Vršačkih planina.",
        'images' => [
            ['url' => 'images/monasteries/srediste.jpg', 'caption' => 'Crkva manastira Središte od opeke u moravskom stilu na Vršačkom brijegu' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/srediste_gal_1.jpg', 'caption' => 'Monumentalna ulazna kula sa lučnom kapijom manastirskog kompleksa' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/srediste_gal_2.jpg', 'caption' => 'Višespratni manastirski konak sa paraklisom i kupolom' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/srediste_gal_3.jpg', 'caption' => 'Kupola manastirskog hrama i zaseban zvonik okruženi šumom' . $src, 'sort_order' => 4],
        ]
    ],

    // 6: Manastir Sveta Trojica Kikinda
    6 => [
        'name' => 'Manastir Sveta Trojica Kikinda',
        'ktitor' => 'Melanija Nikolić (rođ. Gačić)',
        'godina_izgradnje' => '1885',
        'card_image' => 'images/monasteries/sveta-trojica-kikinda.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Svete Trojice u Kikindi podignut je 1885–1887. godine kao zadužbina plemenite Melanije Nikolić rođene Gačić. Ktitorka je manastir podigla kao grobnu crkvu svoje porodice i zaveštala ga Srpskoj pravoslavnoj crkvi zajedno sa velikim imanjem za izdržavanje siromašnih đaka.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Svete Trojice je skladna građevina u neobaroknom stilu sa kupolom i visokim zvonikom. Ikonostas u hramu izradio je 1887. godine čuveni srpski slikar Đura Pecić, a unutrašnjost krase ikone i mozaici visoke umetničke vrednosti.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Svete Trojice je ženski manastir Eparhije banatske i važno duhovno središte Kikinde i severnog Banata.",
        'images' => [
            ['url' => 'images/monasteries/sveta-trojica-kikinda.jpg', 'caption' => 'Prednja fasada crkve Svete Trojice sa mozaicima i zvonikom u Kikindi' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/sveta-trojica-kikinda_gal_1.jpg', 'caption' => 'Pogled na crkvu — oltarska apsida, zvonik i manastirski mir' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/sveta-trojica-kikinda_gal_2.jpg', 'caption' => 'Krupni plan baroknog zvonika manastirske crkve sa bakarnom kapom' . $src, 'sort_order' => 3],
        ]
    ],

    // 7: Manastir Svete Melanije (Zrenjanin)
    7 => [
        'name' => 'Manastir Svete Melanije',
        'ktitor' => 'Episkop vršački dr Georgije (Letić)',
        'godina_izgradnje' => '1935',
        'card_image' => 'images/monasteries/svete-melanije.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Svete Melanije Rimljanke nalazi se na periferiji Zrenjanina. Podigao ga je 1935. godine episkop banatski dr Georgije Letić na mestu gde je nekada postojala stara crkva vodica. Manastir je posvećen Svetoj prepodobnoj Melaniji Rimljanki, krsnoj slavi vladike Georgija.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva manastira podignuta je u vizantijskom stilu sa jedinstvenom osmougaonom kupolom. Ikonostas je rezbaren u hrastovini, a ikone je oslikao poznati beogradski slikar Vladimir Pecić. U manastirskoj kripti sahranjeni su ktitor vladika Georgije Letić i njegova sestra Olga.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Svete Melanije je prvi ženski manastir osnovan u Banatu u novijoj istoriji. Sestrinstvo manastira neguje molitveni život, bogosluženja i crkveno rukodelje.",
        'images' => [
            ['url' => 'images/monasteries/svete-melanije.jpg', 'caption' => 'Crkva Svete Melanije sa osmougaonom kupolom i pripratom u Zrenjaninu' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/svete-melanije_gal_1.jpg', 'caption' => 'Rezbaren drveni ikonostas sa carskim dverima i ikonama u hramu' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/svete-melanije_gal_2.jpg', 'caption' => 'Renoviran manastirski konak sa popločanim stazama i dvorištem' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/svete-melanije_gal_3.jpg', 'caption' => 'Portret prve igumanije manastira Svete Melanije, mati Petronije' . $src, 'sort_order' => 4],
        ]
    ],

    // 8: Manastir Vlajkovac
    8 => [
        'name' => 'Manastir Vlajkovac',
        'ktitor' => 'Grofovska porodica Bisingen-Nipenburg / pravoslavni vernici',
        'godina_izgradnje' => '1872',
        'card_image' => 'images/monasteries/vlajkovac.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Svetih apostola Petra i Pavla nalazi se u selu Vlajkovac kod Vršca. Podignut je u drugoj polovini 19. veka (1872. godine) kao hram pravoslavnih žitelja Vlajkovca i okoline, a u rang manastira uzdignut je odlukom Eparhije banatske krajem 20. veka.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna građevina sa baroknim zvonikom na zapadnom pročelju. Na fasadi hrama nalaze se oslikane niše sa likovima svetih apostola Petra i Pavla i Svetog Dimitrija. Ikonostas nosi odlike klasicizma i banatskog crkvenog slikarstva 19. veka.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Vlajkovac pripada Eparhiji banatskoj. Predstavlja mesto molitvenog sabiranja meštana i vernika vršačkog kraja.",
        'images' => [
            ['url' => 'images/monasteries/vlajkovac.jpg', 'caption' => 'Crkva Svetih apostola Petra i Pavla sa baroknim zvonikom u Vlajkovcu' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/vlajkovac_gal_1.jpg', 'caption' => 'Zapadna fasada crkve sa oslikanim nišama, zvonikom i ogradom' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/vlajkovac_gal_2.jpg', 'caption' => 'Južna bočna strana naosa i krov manastirske crkve' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/vlajkovac_gal_3.jpg', 'caption' => 'Fasadna freska Svetog velikomučenika Dimitrija na zapadnom zidu' . $src, 'sort_order' => 4],
        ]
    ],

    // 9: Manastir Vojlovica (Pančevo)
    9 => [
        'name' => 'Manastir Vojlovica',
        'ktitor' => 'Sveti Despot Stefan Lazarević (1383) / knez Lazar Hrebeljanović',
        'godina_izgradnje' => '1383',
        'card_image' => 'images/monasteries/vojlovica.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Vojlovica posvećen je Svetim arhangelima Mihailu i Gavrilu i nalazi se u Pančevu. Prema predanju, osnovao ga je 1383. godine despot Stefan Lazarević ili njegov otac Sveti knez Lazar. Manastir je kroz vekove bio jedan od najvažnijih bastiona pravoslavlja i pismenosti u južnom Banatu. Za vreme Drugog svetskog rata u manastiru su od strane nemačkih okupatora bili zatočeni Sveti vladika Nikolaj Velimirović i srpski patrijarh Gavrilo Dožić.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva manastira je jednobrodna građevina kojoj je u 18. veku dograđen barokni zvonik. U hramu se nalazi veličanstven pozlaćeni barokni ikonostas iz 18. veka sa ikonama izuzetne umetničke vrednosti. Manastirski konaci svedoče o bogatoj istoriji i stradanju ove svetinje.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Vojlovica je spomenik kulture od izuzetnog značaja. Iako danas okružen industrijskom zonom Pančeva, manastir čuva neprekinuti molitveni plamen i svedočanstvo o stradanju srpskih arhijereja i postojanosti vere.",
        'images' => [
            ['url' => 'images/monasteries/vojlovica.jpg', 'caption' => 'Zapadna fasada crkve manastira Vojlovica sa baroknim zvonikom u Pančevu' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/vojlovica_gal_1.jpg', 'caption' => 'Raskošni barokni pozlaćeni ikonostas manastirske crkve sa prestolnim ikonama' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/vojlovica_gal_2.jpg', 'caption' => 'Monumentalni spratni konak manastira Vojlovica sa parkom u porti' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/vojlovica_gal_3.jpg', 'caption' => 'Spomen-stub sa krstom i mozaikom Svetog Arhangela Gavrila u porti' . $src, 'sort_order' => 4],
        ]
    ],
];

echo "2. Ažuriranje primarne baze podataka (database/database.sqlite):\n";
DB::beginTransaction();

try {
    foreach ($batch1_data as $monasteryId => $data) {
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

        foreach ($batch1_data as $monasteryId => $data) {
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
echo "BATCH 1 (BEOGRADSKA, BAČKA, BANATSKA) ZAVRŠEN USPEŠNO!\n";
echo "====================================================================\n";
