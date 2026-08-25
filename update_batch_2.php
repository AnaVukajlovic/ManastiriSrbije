<?php

/**
 * SISTEMSKO ČIŠĆENJE I SINHRONIZACIJA:
 * - EPARHIJA VALJEVSKA (ID 13)
 * - EPARHIJA MILEŠEVSKA (ID 10)
 * - EPARHIJA TIMOČKA (ID 12)
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
echo "POKRETANJE REVIZIJE ZA EPARHIJE: VALJEVSKA (13), MILEŠEVSKA (10), TIMOČKA (12)\n";
echo "====================================================================\n\n";

$src = '<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>';

$batch2_data = [
    // ====================================================================
    // EPARHIJA VALJEVSKA (ID 13)
    // ====================================================================

    // 160: Manastir Bogovađa
    160 => [
        'name' => 'Manastir Bogovađa',
        'ktitor' => 'Sveti Despot Stefan Lazarević (obnovio Grgur Branković / Hadži Ruvim)',
        'godina_izgradnje' => '1410',
        'card_image' => 'images/monasteries/bogovadja.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Bogovađa posvećen je Svetom velikomučeniku Georgiju i nalazi se u selu Bogovađa kod Lajkovca, u gustoj hrastovoj šumi. Srednjovekovni manastir podigao je u 15. veku (oko 1410. godine) Sveti Despot Stefan Lazarević, a potom ga obnovio Grgur Branković. U 18. i početkom 19. veka manastir je bio sedište Praviteljstvujuščeg sovjeta srpskog pod vođstvom prote Mateje Nenadovića i Karađorđa, a u njemu je kao arhimandrit stvarao čuveni duborezac i prosvetitelj Hadži Ruvim (Nešković).\n\nARHITEKTURA I UNUTRAŠNJOST:\nDanašnja crkva sagrađena je između 1852. i 1857. godine kao jednobrodna građevina u stilu klasicizma i baroka sa visokim zvonikom. U manastiru se čuva izuzetno vredna zbirka rukopisa, duboreza i gravira Hadži Ruvima, kao i vredan ikonostas koji su oslikali Milija Marković i Dimitrije Posniković.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Bogovađa je ženski manastir i spomenik kulture od velikog značaja. Predstavlja jedan od najvažnijih istorijskih i duhovnih centara valjevskog i kolubarskog kraja.",
        'images' => [
            ['url' => 'images/monasteries/bogovadja.jpg', 'caption' => 'Crkva Svetog velikomučenika Georgija u manastiru Bogovađa kod Lajkovca' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/bogovadja_gal_1.jpg', 'caption' => 'Monumentalni barokni zvonik i zapadna fasada crkve u Bogovađi' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/bogovadja_gal_2.jpg', 'caption' => 'Čuvena spomen-česma Hadži Ruvima u porti manastira Bogovađa' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/bogovadja_gal_3.jpg', 'caption' => 'Manastirski konak i muzej u mirnom šumskom okruženju' . $src, 'sort_order' => 4],
        ]
    ],

    // 161: Manastir Dokmir
    161 => [
        'name' => 'Manastir Dokmir',
        'ktitor' => 'Sestre monahinje / narod valjevskog kraja (15. vek)',
        'godina_izgradnje' => '1415',
        'card_image' => 'images/monasteries/dokmir.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Dokmir posvećen je Vavedenju Presvete Bogorodice i nalazi se u selu Dokmir kod Uba, u pitomom valjevskom kraju. Manastir potiče iz prve polovine 15. veka iz doba srpske Despotovine. Kroz vekove je bio čuven po prepisivačkoj školi, ikonopisanju i duhovnom radu, a krajem 18. veka u Dokmiru je radila i poznata zografska škola pod vođstvom hadži-Ruvima.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Vavedenja Bogorodice je jednobrodna građevina od lomljenog kamena sa polukružnom apsidom i osmostranom kupolom. Posebnu vrednost ima raskošan barokni ikonostas iz 1734. godine sa ikonama izuzetne likovne lepote koje su oslikali zografi Teodor Dikanović i majstori iz Kostura.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Dokmir je ženski manastir Eparhije valjevske. Sestrinstvo manastira bavi se tkanjem, ikonopisanjem i pčelarstvom, čuvajući vekovnu monašku tradiciju ovog kraja.",
        'images' => [
            ['url' => 'images/monasteries/dokmir.jpg', 'caption' => 'Kamena crkva Vavedenja Presvete Bogorodice sa kupolom u manastiru Dokmir kod Uba' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/dokmir_gal_1.jpg', 'caption' => 'Pogled na manastirsku crkvu i stari konak u cvetnom dvorištu' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/dokmir_gal_2.jpg', 'caption' => 'Zvonik i klesani kameni detalji fasade hrama u Dokmiru' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/dokmir_gal_3.jpg', 'caption' => 'Raskošni barokni ikonostas iz 18. veka u unutrašnjosti hrama' . $src, 'sort_order' => 4],
        ]
    ],

    // 162: Manastir Grabovac
    162 => [
        'name' => 'Manastir Grabovac',
        'ktitor' => 'Stefan Dragutin Nemanjić (obnovljen 1894. godine)',
        'godina_izgradnje' => '1280',
        'card_image' => 'images/monasteries/grabovac.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Grabovac (posvećen Prenosu moštiju Svetog oca Nikolaja) nalazi se u selu Grabovac kod Obrenovca. Osnovao ga je krajem 13. veka srpski kralj Stefan Dragutin Nemanjić. Tokom viševekovnog turskog ropstva manastir je bio rušen i spaljivan, a današnji hram je podignut 1894. godine na temeljima starije srednjovekovne svetinje.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Svetog Nikole je jednobrodna monumentalna građevina sa kupolom, građena u srpsko-vizantijskom stilu. Ikonostas i živopis izradili su vrsni majstori krajem 19. veka, a u manastirskoj porti nalazi se i lekoviti izvor posvećen Svetoj Petki.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Grabovac pripada Eparhiji valjevskoj. Predstavlja važno duhovno i molitveno sabiralište vernika Posavine i Kolubare.",
        'images' => [
            ['url' => 'images/monasteries/grabovac.jpg', 'caption' => 'Crkva Svetog Nikole sa kupolom u manastiru Grabovac kod Obrenovca' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/grabovac_gal_1.jpg', 'caption' => 'Zapadna fasada sa portalom i zvonikom u Grabovcu' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/grabovac_gal_2.jpg', 'caption' => 'Kapela nad lekovitim izvorom Svete Petke u manastirskoj porti' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/grabovac_gal_3.jpg', 'caption' => 'Uređeni manastirski konaci okruženi zelenilom u Grabovcu' . $src, 'sort_order' => 4],
        ]
    ],

    // 163: Manastir Jovanja (Valjevo)
    163 => [
        'name' => 'Manastir Jovanja',
        'ktitor' => 'Nemanjići / Kraljević Marko (predanje)',
        'godina_izgradnje' => '1300',
        'card_image' => 'images/monasteries/jovanja.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Jovanja posvećen je Rođenju Svetog Jovana Krstitelja i nalazi se u klisuri reke Jablanice, nedaleko od Valjeva. Prema narodnom predanju, manastir potiče s kraja 13. ili početka 14. veka i vezuje se za doba Nemanjića. U pisanim izvorima pominje se u 16. veku, kada je pri hramu postojala prepisivačka škola i bogata biblioteka.\n\nARHITEKTURA I UNUTRAŠNJOST:\nManastirska crkva je jednobrodna građevina raškog stila sa kubetom i pripratom, zidana od kamena i sige. Unutrašnjost krase ostaci starog živopisa iz 16. veka i rezbareni ikonostas. Crkva je pokrivena tradicionalnim drvenim krovom koji daje poseban arhaični sklad celom kompleksu.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Jovanja je muški manastir Eparhije valjevske. Smešten u netaknutoj prirodi pored reke, manastir pruža savršen mir za molitvu i duhovno uzdizanje hodočasnika.",
        'images' => [
            ['url' => 'images/monasteries/jovanja.jpg', 'caption' => 'Crkva Rođenja Svetog Jovana Krstitelja sa kupolom i krovom od šindre u Jovanji' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/jovanja_gal_1.jpg', 'caption' => 'Pogled na južnu fasadu crkve i zelenu portu podno planinskih obronaka' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/jovanja_gal_2.jpg', 'caption' => 'Manastirski konak sa belim lučnim arkadama i tremom' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/jovanja_gal_3.jpg', 'caption' => 'Raskošni drvorezbareni ikonostas u naosu sa prestolnim ikonama' . $src, 'sort_order' => 4],
        ]
    ],

    // 164: Manastir Lelić
    164 => [
        'name' => 'Manastir Lelić',
        'ktitor' => 'Sveti Vladika Nikolaj Velimirović i otac Dragomir',
        'godina_izgradnje' => '1929',
        'card_image' => 'images/monasteries/lelic.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Lelić posvećen je Prenosu moštiju Svetog oca Nikolaja i nalazi se u selu Lelić kod Valjeva. Podigli su ga 1929. godine kao svoju zadužbinu Sveti vladika Nikolaj Žički i Ohridski (Velimirović) i njegov otac Dragomir. Prvobitno je bio parohijski hram, a 1996. godine odlukom SPC preuređen je u muški manastir, kada su u njega prenete svete mošti vladike Nikolaja iz Libertivila (SAD).\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je građena u moravskom stilu sa karakterističnim skladnim kupolama i fasadnom dekoracijom. Unutrašnjost hrama živopisali su upečatljivim freskama čuveni ruski zografi prema uputstvima samog vladike Nikolaja. U južnom delu naosa nalazi se kivot sa netruležnim moštima Svetog vladike Nikolaja Žičkog.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Lelić je jedno od najvećih mesta hodočašća u celoj Srpskoj pravoslavnoj crkvi. U manastirskom kompleksu nalazi se i spomen-muzej posvećen životu, bogoslovskom radu i stradanju Svetog vladike Nikolaja Velimirovića.",
        'images' => [
            ['url' => 'images/monasteries/lelic.jpg', 'caption' => 'Glavna kamena lučna kapija, manastirski zid i crkva Svetog Nikole u Leliću' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/lelic_gal_1.jpg', 'caption' => 'Pogled na hram Svetog Nikole u moravskom stilu i uređenu portu' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/lelic_gal_2.jpg', 'caption' => 'Spratni manastirski konak i crkveni zvonik u Leliću' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/lelic_gal_3.jpg', 'caption' => 'Ćivot sa svetim moštima Svetog vladike Nikolaja Žičkog i Ohridskog' . $src, 'sort_order' => 4],
        ]
    ],

    // 165: Manastir Plužac
    165 => [
        'name' => 'Manastir Plužac',
        'ktitor' => 'Pravoslavni vernici Podgorine i Rađevine',
        'godina_izgradnje' => '2008',
        'card_image' => 'images/monasteries/pluzac.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Plužac posvećen je Svetom caru Konstantinu i carici Jeleni i nalazi se u selu Plužac kod Osečine, u prelepom brdsko-planinskom predelu Podgorine. Podignut je u periodu od 2005. do 2008. godine dobrovoljnim prilozima i trudom vernog naroda osečanskog i valjevskog kraja.\n\nARHITEKTURA I UNUTRAŠNJOST:\nManastirska crkva je sagrađena u raško-moravskom stilu sa vitkom kupolom i zvonikom. Zidana je od kamena i opeke, sa klesanim kamenim rozetama i portalima ukrašenim mozaicima. Unutrašnjost je živopisana u vizantijskom duhu, a ikonostas je rezbaren u drvetu.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Plužac je ženski manastir Eparhije valjevske. Predstavlja noviji, ali izuzetno živ duhovni centar Podgorine gde se redovno okuplja verni narod na bogosluženjima i crkvenim saborima.",
        'images' => [
            ['url' => 'images/monasteries/pluzac.jpg', 'caption' => 'Crkva Svetog cara Konstantina i carice Jelene u manastiru Plužac kod Osečine' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/pluzac_gal_1.jpg', 'caption' => 'Novi manastirski konak građen u tradicionalnom stilu sa tremom' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/pluzac_gal_2.jpg', 'caption' => 'Zapadna fasada sa zvonikom, portalom i mozaicima u Plužcu' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/pluzac_gal_3.jpg', 'caption' => 'Detalj bogato klesane kamene rozete sa prepletom na pročelju hrama' . $src, 'sort_order' => 4],
        ]
    ],

    // 166: Manastir Ribnica
    166 => [
        'name' => 'Manastir Ribnica',
        'ktitor' => 'Nemanjići (predanje) / obnovio knez Miloš Obrenović 1823. godine',
        'godina_izgradnje' => '1300',
        'card_image' => 'images/monasteries/ribnica.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Ribnica posvećen je Svetim apostolima Petru i Pavlu i nalazi se u selu Paštrić kod Mionice, uz samu reku Ribnicu i poznatu Ribničku pećinu. Prema narodnom predanju, manastir potiče iz doba Nemanjića (kraj 13. veka). Manastir je spaljen od strane Turaka 1788. godine za vreme Kočine krajine, a obnovio ga je knez Miloš Obrenović 1823. godine. U njemu je 1909. godine podignuta današnja crkva.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Svetih Petra i Pavla izgrađena je u srpsko-vizantijskom stilu sa osnovom u obliku upisanog krsta i prostranom kupolom prema projektu arhitekte Svetozara Ivačkovića. U unutrašnjosti se nalazi ikonostas koji je oslikao znameniti slikar Živko Jugović.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Ribnica je muški manastir Eparhije valjevske i spomenik kulture od velikog značaja. Zbog svoje jedinstvene lokacije u zaštićenom spomeniku prirode uz reku Ribnicu, predstavlja izuzetno privlačno molitveno i prirodno svetilište.",
        'images' => [
            ['url' => 'images/monasteries/ribnica.jpg', 'caption' => 'Zapadna fasada crkve Svetih apostola Petra i Pavla u manastiru Ribnica kod Mionice' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/ribnica_gal_1.jpg', 'caption' => 'Pogled sa puta na slobodnostojeći zvonik i crkvu sa kupolom u Ribnici' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/ribnica_gal_2.jpg', 'caption' => 'Manastirska spomen-česma od klesanog kamena sa krstom' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/ribnica_gal_3.jpg', 'caption' => 'Manastirski konak u senci stena pored ulaza u Ribničku pećinu' . $src, 'sort_order' => 4],
        ]
    ],

    // ====================================================================
    // EPARHIJA MILEŠEVSKA (ID 10)
    // ====================================================================

    // 63: Manastir Bistrica
    63 => [
        'name' => 'Manastir Bistrica',
        'ktitor' => 'Stefan Vladislav Nemanjić / knez Vukan Nemanjić (13. vek)',
        'godina_izgradnje' => '1240',
        'card_image' => 'images/monasteries/bistrica.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Bistrica (posvećen Svetom ocu Nikolaju) nalazi se u selu Bistrica na reci Bistrici, između Nove Varoši i Prijepolja. Prema istorijskim izvorima i poveljama Nemanjića, manastir potiče iz 13. veka iz doba kralja Stefana Vladislava. Pominje se i u Povelji kralja Milutina iz 1314. godine.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna kamena građevina sa polukružnom oltarskom apsidom, presvedena poluobličastim svodom. Unutrašnjost krasi očuvani drveni ikonostas i freske koje su oslikali lokalni majstori.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Bistrica pripada Eparhiji mileševskoj. Smešten u mirnom planinskom kanjonu reke Bistrice, čuva vekovni duhovni mir i sabira verni narod ovog dela Polimlja.",
        'images' => [
            ['url' => 'images/monasteries/bistrica.jpg', 'caption' => 'Crkva Svetog Nikole u manastiru Bistrica kod Nove Varoši i Prijepolja' . $src, 'sort_order' => 1],
        ]
    ],

    // 64: Manastir Davidovica
    64 => [
        'name' => 'Manastir Davidovica',
        'ktitor' => 'Župan Dmitar Nemanjić (monah David, sin kneza Vukana)',
        'godina_izgradnje' => '1281',
        'card_image' => 'images/monasteries/davidovica.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Davidovica posvećen je Bogojavljenju Gospodnjem i nalazi se u selu Grobnice kod Brodareva, uz samu reku Lim. Podigao ga je 1281. godine župan Dmitar Nemanjić, unuk Stefana Nemanje i sin kneza Vukana, koji se kasnije zamonašio uzevši ime David (po kome je manastir dobio ime). Prema predanju i istorijskim izvorima, u Davidovici su sahranjeni slavni Jug Bogdan (knez Vratko Nemanjić) i njegovih devet sinova — Jugovići, koji su izginuli na Kosovu polju 1389. godine.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Bogojavljenja je jednobrodna građevina raške škole sa kupolom i bočnim kapelama, građena od tesanog krečnjaka po ugledu na Studenicu i Žiču, radom primorskih majstora iz Dubrovnika. Unutrašnjost čuva autentični mermerni podni mozaik (omfalos) i fragmente fresaka iz 13. veka.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nNakon vekova provedenih u ruševinama, manastir Davidovica je krajem 20. veka temeljno obnovljen pod okriljem Eparhije mileševske. Predstavlja prvorazredni istorijski i duhovni spomenik srpske srednjovekovne baštine u Polimlju.",
        'images' => [
            ['url' => 'images/monasteries/davidovica.jpg', 'caption' => 'Crkva Bogojavljenja Gospodnjeg (1281. god) sa kupolom u manastiru Davidovica' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/davidovica_gal_1.jpg', 'caption' => 'Ostaci monumentalnog srednjovekovnog hrama i manastirska porta uz reku Lim' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/davidovica_gal_2.jpg', 'caption' => 'Pogled na kamenu fasadu, apsidu i lučni portal crkve u Davidovici' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/davidovica_gal_3.jpg', 'caption' => 'Arheološki ostaci grobnice devet Jugovića i kneza Vratka u porti Davidovice' . $src, 'sort_order' => 4],
        ]
    ],

    // 65: Manastir Jabuka
    65 => [
        'name' => 'Manastir Jabuka',
        'ktitor' => 'Pravoslavni vernici prijepoljskog kraja',
        'godina_izgradnje' => '2011',
        'card_image' => 'images/monasteries/jabuka.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Svetog proroka Ilije nalazi se na planinskoj visoravni Jabuka kod Prijepolja, u blizini granice sa Crnom Gorom. Izgrađen je u periodu od 2008. do 2011. godine trudom Eparhije mileševske i vernog naroda ovog kraja.\n\nARHITEKTURA I UNUTRAŠNJOST:\nManastirska crkva je izgrađena od drveta i kamena u duhu tradicionalnih planinskih hramova. Unutrašnjost krase rezbareni ikonostas i ikone svetih ugodnika Božijih.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Jabuka je duhovna oaza na planinskoj tromeđi, gde se o Ilindanu održava veliki crkveno-narodni sabor.",
        'images' => [
            ['url' => 'images/monasteries/jabuka.jpg', 'caption' => 'Crkva Svetog proroka Ilije pod večernjim osvetljenjem na visoravni Jabuka' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/jabuka_gal_1.jpg', 'caption' => 'Pogled na manastirski hram kroz visoke četinare planinske visoravni' . $src, 'sort_order' => 2],
        ]
    ],

    // 66: Manastir Janja
    66 => [
        'name' => 'Manastir Janja',
        'ktitor' => 'Nemanjići (obnovljen početkom 21. veka)',
        'godina_izgradnje' => '1500',
        'card_image' => 'images/monasteries/janja.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Janja (posvećen Pravednim roditeljima Joakimu i Ani) nalazi se u selu Rutoši kod Nove Varoši, u živopisnom kanjonu reke Uvac. Prema epskoj narodnoj pesmi „Miloš u Latinima“, zadužbina je Nemanjića („crkva Janja u Vlahu Starome“). Manastir je srušen u tursko doba, a temelji su otkriveni 1993. godine, nakon čega je usledila kompletna obnova hrama i konaka.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna kamena građevina sa pripratom i polukružnom apsidom. Unutrašnjost je živopisana i opremljena rezbarenim ikonostasom.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Janja je ženski manastir Eparhije mileševske, poznat po molitvenom miru i monaškom tihovanju u kanjonu Uvca.",
        'images' => [
            ['url' => 'images/monasteries/janja.jpg', 'caption' => 'Crkva Pravednih roditelja Joakima i Ane u selu Rutoši kod Nove Varoši' . $src, 'sort_order' => 1],
        ]
    ],

    // 67: Manastir Kumanica
    67 => [
        'name' => 'Manastir Kumanica',
        'ktitor' => 'Srpska srednjovekovna vlastela (14. vek) / obnovljen 2000. godine',
        'godina_izgradnje' => '1400',
        'card_image' => 'images/monasteries/kumanica.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Kumanica posvećen je Svetom arhangelu Gavrilu i nalazi se u živopisnoj klisuri reke Lim, na samoj granici Srbije i Crne Gore (između Prijepolja i Bijelog Polja). Najstariji pisani pomen manastira potiče iz 1514. godine, a u njemu se čuvalo čuveno Kumaničko četvorojevanđelje iz 16. veka. Manastir je bio u ruševinama sve do 2000. godine, kada je obnovljen i ponovo osveštan.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna građevina od lomljenog kamena usečena u stene kanjona Lima, sa kupolom i drvenim zvonikom. U manastiru se čuvaju čudotvorne mošti Svetog Grigorija Kumaničkog, pred kojima se vekovima dešavaju mnoga isceljenja.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Kumanica je jedno od najčuvenijih svetilišta na Balkanu. O prazniku Sabora Svetog arhangela Gavrila (26. jula) ovde se okupljaju desetine hiljada vernika pravoslavne, ali i drugih veroispovesti, tražeći isceljenje i pomoć.",
        'images' => [
            ['url' => 'images/monasteries/kumanica.jpg', 'caption' => 'Crkva Svetog arhangela Gavrila sa drvenim zvonikom pod liticama kanjona Lima' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/kumanica_gal_1.jpg', 'caption' => 'Panoramski pogled na manastirski kompleks Kumanica sa konacima' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/kumanica_gal_2.jpg', 'caption' => 'Kamena crkva Svetog arhangela Gavrila sa kupolom i reljefnim portalom' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/kumanica_gal_3.jpg', 'caption' => 'Kameni ogradni zid manastira podno litica kanjona Lima' . $src, 'sort_order' => 4],
        ]
    ],

    // 68: Manastir Mažići (Orahovica)
    68 => [
        'name' => 'Manastir Mažići',
        'ktitor' => 'Stefan Nemanja (Sveti Simeon Mirotočivi) / obnovio kralj Stefan Uroš II Milutin',
        'godina_izgradnje' => '1200',
        'card_image' => 'images/monasteries/mazici.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Mažići (u srednjem veku poznat kao Orahovica) posvećen je Svetom velikomučeniku Georgiju i nalazi se u selu Mažići iznad Potpećkog jezera kod Priboja. Osnovao ga je u 12. veku Stefan Nemanja, a temeljno ga je obnovio početkom 14. veka kralj Stefan Uroš II Milutin. Tokom arheoloških iskopavanja ovde je otkrivena srednjovekovna manastirska bolnica sa hirurškim instrumentima iz 14. veka, što svedoči o izuzetnom stepenu medicinske kulture u srednjovekovnoj Srbiji.\n\nARHITEKTURA I UNUTRAŠNJOST:\nManastirska crkva je jednobrodna kamena građevina sa polukružnom apsidom i pripratom, zidana od krupnih kamenih tesanika. Oko hrama se nalaze ostaci nekropole, manastirske trpezarije, konaka i bolnice.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Mažići je spomenik kulture od izuzetnog značaja. Obnovljen je krajem 20. i početkom 21. veka u sklopu Eparhije mileševske, svedočeći o zlatnom dobu nemanjićke duhovnosti i nauke.",
        'images' => [
            ['url' => 'images/monasteries/mazici.jpg', 'caption' => 'Crkva Svetog Georgija u Mažićima (Orahovica) iz 12. veka iznad jezera Potpeć' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/mazici_gal_1.jpg', 'caption' => 'Kamena staza duž manastirskog zida sa pogledom na planinski predeo' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/mazici_gal_2.jpg', 'caption' => 'Srednjovekovna grobnica i arheološki ostaci nekropole manastira Mažići' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/mazici_gal_3.jpg', 'caption' => 'Pogled kroz lučni kameni portal na ulaz u naos manastirske crkve' . $src, 'sort_order' => 4],
        ]
    ],

    // 69: Manastir Mileševa
    69 => [
        'name' => 'Manastir Mileševa',
        'ktitor' => 'Kralj Stefan Vladislav Nemanjić',
        'godina_izgradnje' => '1219',
        'card_image' => 'images/monasteries/mileseva.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Mileševa posvećen je Vaznesenju Gospodnjem i nalazi se u dolini reke Mileševke, nekoliko kilometara od Prijepolja. Podigao ga je srpski kralj Stefan Vladislav (sin Stefana Prvovenčanog) između 1219. i 1235. godine. U Mileševu su 1237. godine iz Trnova prenete mošti Svetog Save Srpskog, gde su počivale sve do 1594. godine, kada ih je Sinan-paša odneo i spalio na Vračaru u Beogradu. U Mileševi se 1377. godine krunisao bosanski ban i srpski kralj Tvrtko I Kotromanić, a u 16. veku pri manastiru je radila čuvena Mileševska štamparija.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Vaznesenja Gospodnjeg pripada raškoj školi arhitekture sa jednobrodnom osnovom, kupolom i bočnim pevnicama. Mileševski živopis iz 13. veka spada u sam vrh evropske i svetske srednjovekovne umetnosti. Među freskama se posebno ističe svetski remek-delo — **Beli Anđeo** (Mironosice na grobu Hristovom), kao i najstariji autentični portret Svetog Save Srpskog i vladara iz dinastije Nemanjića.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Mileševa je sedište Eparhije mileševske i spomenik kulture od izuzetnog značaja. Predstavlja jednu od najvažnijih i najsvetijih nemanjićkih carskih lavri srpskog naroda.",
        'images' => [
            ['url' => 'images/monasteries/mileseva.jpg', 'caption' => 'Crkva Vaznesenja Gospodnjeg (1234. god) sa kupolom u manastiru Mileševa' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/mileseva_gal_1.jpg', 'caption' => 'Čuvena freska Beli Anđeo (Mironosice na grobu Hristovom) iz 13. veka u Mileševi' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/mileseva_gal_2.jpg', 'caption' => 'Panorama manastirskog kompleksa Mileševa uz reku sa spratnim konacima' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/mileseva_gal_3.jpg', 'caption' => 'Monumentalna manastirska kula-zvonik sa ulaznom kapijom i mozaicima' . $src, 'sort_order' => 4],
        ]
    ],

    // 70: Manastir Pribojska Banja (Banja kod Priboja)
    70 => [
        'name' => 'Manastir Pribojska Banja',
        'ktitor' => 'Stefan Nemanja (Sveti Simeon Mirotočivi) / kralj Stefan Uroš III Dečanski',
        'godina_izgradnje' => '1154',
        'card_image' => 'images/monasteries/pribojska-banja.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Svetog Nikole u Banji kod Priboja (Sveti Nikola Dabarski) jedan je od najstarijih srpskih manastira u Polimlju. Potiče iz sredine 12. veka iz prednemanjićkog i ranonemanjićkog doba. Kada je Sveti Sava 1219. godine uspostavio autokefalnost Srpske pravoslavne crkve, u manastiru Svetog Nikole u Banji osnovao je Dabarsku eparhiju. Manastir je temeljno obnovio kralj Stefan Uroš III Dečanski 1329. godine.\n\nARHITEKTURA I UNUTRAŠNJOST:\nKompleks se sastoji od monumentalne crkve Svetog Nikole, kapele Svetog Ilije i kapele Uspenja Bogorodice. Crkva Svetog Nikole je jednobrodna građevina raškog stila sa dve kupole i prostranom pripratom. U manastiru je otkrivena neprocenjivo vredna riznica sakralnih predmeta iz 14–17. veka, koja se danas čuva u manastirskom muzeju.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Svetog Nikole Dabarskog u Pribojskoj Banji ima prvorazredni istorijski značaj kao jedno od prvih sedišta svetosavskih eparhija i centar pismenosti i duhovnosti u Polimlju.",
        'images' => [
            ['url' => 'images/monasteries/pribojska-banja.jpg', 'caption' => 'Monumentalni hram Svetog Nikole Dabarskog sa dve kupole u Pribojskoj Banji' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/pribojska-banja_gal_1.jpg', 'caption' => 'Južna fasada hrama sa kupolom, apsidom i uređenom zelenom portom' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/pribojska-banja_gal_2.jpg', 'caption' => 'Zapadna otvorena priprata sa arkadama i lučnim otvorima' . $src, 'sort_order' => 3],
        ]
    ],

    // 71: Manastir Pustinja (Prijepolje / Mileševska)
    71 => [
        'name' => 'Manastir Pustinja',
        'ktitor' => 'Monasi isposnici (13. vek) / monah Joakim',
        'godina_izgradnje' => '1622',
        'card_image' => 'images/monasteries/pustinja-valjevska.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Pustinja (posvećen Vavedenju Presvete Bogorodice) nalazi se u skrovitom planinskom useku u blizini reke. Potiče iz srednjeg veka, a prema sačuvanom natpisu iznad ulaza hram je obnovljen i živopisan 1622. godine trudom monaha Joakima i zografa Nikole i Jovana.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna građevina sa kupolom i pripratom, zidana od lomljenog kamena. Unutrašnjost krasi izuzetno očuvan živopis iz 1622. godine koji po svojoj likovnoj snazi spada u najlepša ostvarenja postvizantijske umetnosti u Srbiji.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Pustinja je mesto tihovanja i molitve, svedočanstvo o neugasivom plamenu pravoslavnog podvižništva.",
        'images' => [
            ['url' => 'images/monasteries/pustinja-valjevska.jpg', 'caption' => 'Crkva Vavedenja Presvete Bogorodice sa kamenim naosom i kupolom' . $src, 'sort_order' => 1],
        ]
    ],

    // 72: Manastir Seljani
    72 => [
        'name' => 'Manastir Seljani',
        'ktitor' => 'Srpska srednjovekovna vlastela (obnovljen krajem 20. veka)',
        'godina_izgradnje' => '1800',
        'card_image' => 'images/monasteries/seljani.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Seljani (posvećen Vaznesenju Gospodnjem) nalazi se u selu Seljani na planinskim visovima iznad Prijepolja. Podignut je na temeljima starog srednjovekovnog manastirišta koje je vekovima bilo mesto narodnog molitvenog sabiranja.\n\nARHITEKTURA I UNUTRAŠNJOST:\nManastirski hram je skladna jednobrodna kamena građevina sa zvonikom i konakom prilagođenim planinskom ambijentu. Unutrašnjost krase ikone i ikonostas u duhu pravoslavne tradicije polimskog kraja.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Seljani pripada Eparhiji mileševskoj i predstavlja mirno duhovno uporište vernika prijepoljskog kraja.",
        'images' => [
            ['url' => 'images/monasteries/seljani.jpg', 'caption' => 'Panoramski pogled iz vazduha na manastirski kompleks Seljani kod Prijepolja' . $src, 'sort_order' => 1],
        ]
    ],

    // 257: Manastir Vodena Poljana (Zlatar)
    257 => [
        'name' => 'Manastir Vodena Poljana',
        'ktitor' => 'Verni narod i Eparhija mileševska',
        'godina_izgradnje' => '2007',
        'card_image' => 'images/monasteries/vodena-poljana.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Svetih vrača Kozme i Damjana na Vodenoj Poljani nalazi se u srcu četinarskih šuma planine Zlatar, na nadmorskoj visini od preko 1.400 metara, u blizini Nove Varoši. Izgradnja ovog planinskog manastira započeta je 2000. godine, a hram je osveštan 2007. godine.\n\nARHITEKTURA I UNUTRAŠNJOST:\nManastirska crkva je prelepa brvnara građena u stilu starovlaških crkava od masivnih borovih brvana sa visokim drvenim zvonikom i krovom od šindre. Unutrašnjost odiše mirom i mirisom borovine, sa ručno duborezanim drvenim ikonostasom.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Vodena Poljana je najviši manastir Eparhije mileševske. Posvećen je Svetim besrebrenicima i čudotvorcima Kozmi i Damjanu, privlačeći brojne vernike i planinare koji na Zlataru traže telesno i duhovno okrepljenje.",
        'images' => [
            ['url' => 'images/monasteries/vodena-poljana.jpg', 'caption' => 'Drvena manastirska crkva Svetih vrača Kozme i Damjana na Zlataru' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/vodena-poljana_gal_1.jpg', 'caption' => 'Crkva brvnara sa visokim drvenim zvonikom i stazom kroz borovu šumu' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/vodena-poljana_gal_2.jpg', 'caption' => 'Izvorište i kamena spomen-česma na proplanku Vodena Poljana' . $src, 'sort_order' => 3],
        ]
    ],

    // ====================================================================
    // EPARHIJA TIMOČKA (ID 12)
    // ====================================================================

    // 150: Manastir Bukovo
    150 => [
        'name' => 'Manastir Bukovo',
        'ktitor' => 'Stefan Uroš II Milutin (13. vek) / Sveti Nikodim Tismanski',
        'godina_izgradnje' => '1300',
        'card_image' => 'images/monasteries/bukovo.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Bukovo posvećen je Svetom ocu Nikolaju i nalazi se na padinama Bratujevca, svega nekoliko kilometara od Negotina. Prema predanju, zadužbina je srpskog kralja Stefana Uroša II Milutina sa kraja 13. veka, ili Svetog Nikodima Tismanskog iz 14. veka. Manastir je kroz vekove bio duhovno i prosvetno središte Krajine, u kome je radila i prva poljoprivredna škola u Srbiji.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Svetog Nikole građena je u stilu moravske i raške škole od tesanog kamena sa kupolom. Unutrašnjost čuva slojeve živopisa iz 17. i 19. veka (rad zografa Milije Markovića), kao i čudotvornu ikonu Presvete Bogorodice Pokroviteljke Bukovske. U manastiru se nalazi i kapela posvećena Svetom Vasiliju Ostroškom.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Bukovo je muški manastir Eparhije timočke, čuven po negovanju tradicije, vinogradarstvu i proizvodnji vrhunskih vina od autohtone sorte crna tamjanika.",
        'images' => [
            ['url' => 'images/monasteries/bukovo.jpg', 'caption' => 'Manastir Bukovo sa crkvom Svetog Nikole, zvonikom i konacima kod Negotina' . $src, 'sort_order' => 1],
        ]
    ],

    // 151: Manastir Grlište
    151 => [
        'name' => 'Manastir Grlište',
        'ktitor' => 'Srpska srednjovekovna vlastela (13. vek) / obnovljen u 19. veku',
        'godina_izgradnje' => '1300',
        'card_image' => 'images/monasteries/grliste.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Grlište posvećen je Svetim apostolima Petru i Pavlu i nalazi se na obali Grliškog jezera kod Zaječara. Potiče iz srednjeg veka (13–14. vek), a temeljno je obnovljen 1804. godine u vreme Prvog srpskog ustanka trudom sveštenika i naroda zaječarskog kraja.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna kamena građevina sa polukružnom apsidom i zvonikom. Unutrašnjost krase ikone i ikonostas iz 19. veka, rad lokalnih ikonopisaca.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Grlište pripada Eparhiji timočkoj. Smešten uz mirne vode jezera, pruža duhovni mir i sabira verni narod o Petrovdanu.",
        'images' => [
            ['url' => 'images/monasteries/grliste.jpg', 'caption' => 'Crkva Svetih apostola Petra i Pavla u manastiru Grlište na obali jezera' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/grliste_gal_1.jpg', 'caption' => 'Zvonik i manastirska porta okruženi šumom u Grlištu' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/grliste_gal_3.jpg', 'caption' => 'Pogled na manastirski kompleks sa obale Grliškog jezera' . $src, 'sort_order' => 3],
        ]
    ],

    // 152: Manastir Jermenčić
    152 => [
        'name' => 'Manastir Jermenčić',
        'ktitor' => 'Jermenski vojnici u službi sultana (predanje, 1392. god)',
        'godina_izgradnje' => '1392',
        'card_image' => 'images/monasteries/jermencic.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Jermenčić posvećen je Svetim arhangelima Mihailu i Gavrilu i nalazi se na planini Ozren kod Sokobanje, na 850 metara nadmorske visine. Prema predanju, osnovali su ga 1392. godine jermenski vojnici koji su, kao vazali u turskoj vojsci u Boju na Kosovu, prešli na srpsku stranu i potom u znak pokajanja podigli manastir u ozrenskim šumama.\n\nARHITEKTURA I UNUTRAŠNJOST:\nManastirska crkva je skromna jednobrodna građevina zidana od lomljenog kamena sa malom pripratom. U blizini manastira nalaze se četiri izvora lekovite vode posvećena Svetom Jovanu, Svetom Arhangelu, Svetoj Petki i Bogorodici.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Jermenčić pripada Eparhiji timočkoj i predstavlja omiljeno izletište i molitveno mesto posetilaca Sokobanje i Ozrena.",
        'images' => [
            ['url' => 'images/monasteries/jermencic.jpg', 'caption' => 'Kamena crkva Svetih arhangela u manastiru Jermenčić na planini Ozren' . $src, 'sort_order' => 1],
        ]
    ],

    // 153: Manastir Koroglaš
    153 => [
        'name' => 'Manastir Koroglaš',
        'ktitor' => 'Stefan Uroš IV Dušan Silni / kralj Milutin (14. vek)',
        'godina_izgradnje' => '1350',
        'card_image' => 'images/monasteries/koroglas.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Koroglaš (posvećen Vaznesenju Gospodnjem) nalazi se u selu Miloševo kod Negotina. Prema narodnom predanju, manastir je podigao car Stefan Dušan ili kralj Milutin u 14. veku. Predanje takođe beleži da je nakon Bitke na Rovinama (1395. godine) u ovom manastiru sahranjen legendarni srpski junak Kraljević Marko.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna trikonhalna građevina moravskog stila sa kupolom, građena od kamena i opeke sa dekorativnim keramičkim ukrasima na fasadi. Oko crkve se nalazi velika srednjovekovna nekropola sa kamenim nadgrobnim spomenicima.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Koroglaš ima status spomenika kulture od velikog značaja i svedoči o dometima srednjovekovne srpske arhitekture u Negotinskoj Krajini.",
        'images' => [
            ['url' => 'images/monasteries/koroglas.jpg', 'caption' => 'Srednjovekovna crkva Vaznesenja Gospodnjeg u manastiru Koroglaš kod Negotina' . $src, 'sort_order' => 1],
        ]
    ],

    // 154: Manastir Krepičevac
    154 => [
        'name' => 'Manastir Krepičevac',
        'ktitor' => 'Župan Georgije i vlastelinka Zora (početak 16. veka)',
        'godina_izgradnje' => '1500',
        'card_image' => 'images/monasteries/krepicevac.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Krepičevac posvećen je Uspenju Presvete Bogorodice i nalazi se u živopisnoj klisuri Radovanske reke, nekoliko kilometara od sela Jabukovac kod Boljevca. Manastir su početkom 16. veka (oko 1500. godine) podigli župan Georgije i njegova supruga Zora, o čemu svedoči ktitorski natpis i sačuvane freske u hramu.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna trikonhalna građevina sa osmostranom kupolom, zidana od krupnog kamena. Unutrašnjost hrama čuva izuzetno vredne freske iz prve polovine 16. veka sa likovima ktitora župana Georgija i zore, kao i scenama iz Hristovog i Bogorodičinog života.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Krepičevac je spomenik kulture od velikog značaja Eparhije timočke, skriven u tišini netaknute prirode istočne Srbije.",
        'images' => [
            ['url' => 'images/monasteries/krepicevac.jpg', 'caption' => 'Crkva Uspenja Presvete Bogorodice u manastiru Krepičevac kod Boljevca' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/krepicevac_gal_1.jpg', 'caption' => 'Oltarska apsida i južna kamena fasada hrama u klisuri reke' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/krepicevac_gal_2.jpg', 'caption' => 'Manastirski konak i uređena porta okruženi šumom u Krepičevcu' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/krepicevac_gal_3.jpg', 'caption' => 'Srednjovekovne freske i ktitorska kompozicija u unutrašnjosti hrama' . $src, 'sort_order' => 4],
        ]
    ],

    // 155: Manastir Lapušnja
    155 => [
        'name' => 'Manastir Lapušnja',
        'ktitor' => 'Vojvoda Jovan Radul i knez Bogoje (1501. god)',
        'godina_izgradnje' => '1501',
        'card_image' => 'images/monasteries/lapusnja.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Lapušnja posvećen je Svetom Nikoli i nalazi se na padinama planine Rtanj u blizini Boljevca. Hram su 1501. godine podigli vojvoda Jovan Radul i knez Bogoje sa suprugom Marom, o čemu svedoči detaljan ktitorski natpis u crkvi.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Svetog Nikole je trikonhalna građevina moravske stilske grupe sa kupolom oslonjenom na slobodne stupce. Zidana je od lomljenog kamena i sige. Unutrašnjost čuva fragmente izuzetnog živopisa iz 1510. godine visoke umetničke vrednosti.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Lapušnja je spomenik kulture od velikog značaja i predstavlja jedan od najvažnijih srednjovekovnih bisera podno mistične planine Rtanj.",
        'images' => [
            ['url' => 'images/monasteries/lapusnja.jpg', 'caption' => 'Srednjovekovni hram Svetog Nikole u manastiru Lapušnja pod planinom Rtanj' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/lapusnja_gal_1.jpg', 'caption' => 'Kupola i kameni lukovi naosa crkve u Lapušnji' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/lapusnja_gal_2.jpg', 'caption' => 'Ostaci srednjovekovnih fresaka na zidovima hrama u Lapušnji' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/lapusnja_gal_3.jpg', 'caption' => 'Pogled na manastirski kompleks i padine planine Rtanj' . $src, 'sort_order' => 4],
        ]
    ],

    // 156: Manastir Lozica
    156 => [
        'name' => 'Manastir Lozica',
        'ktitor' => 'Nemanjići (14. vek) / obnovljen u 19. veku',
        'godina_izgradnje' => '1300',
        'card_image' => 'images/monasteries/lozica.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Lozica posvećen je Svetom arhangelu Gavrilu i nalazi se u selu Krivi Vir kod Boljevca, blizu izvorišta reke Crni Timok. Prema predanju, potiče iz 14. veka iz doba Nemanjića. Hram je više puta stradao pod Turcima, a obnovljen je u 19. veku (1854. godine).\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna građevina sa pripratom i polukružnom apsidom, zidana od kamena. Unutrašnjost krase ikone i ikonostas iz 19. veka, kao i lekoviti izvor vode u porti hrama.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Lozica pripada Eparhiji timočkoj. Predstavlja mirno svetilište i mesto okupljanja pravoslavnog naroda Timočke krajine.",
        'images' => [
            ['url' => 'images/monasteries/lozica.jpg', 'caption' => 'Crkva Svetog arhangela Gavrila u manastiru Lozica kod Krivog Vira' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/lozica_gal_1.jpg', 'caption' => 'Zapadna fasada sa ulaznim portalom i zvonikom u Lozici' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/lozica_gal_2.jpg', 'caption' => 'Oltarska apsida i krovna konstrukcija manastirske crkve' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/lozica_gal_3.jpg', 'caption' => 'Manastirsko dvorište sa česmom u prirodi Timočke krajine' . $src, 'sort_order' => 4],
        ]
    ],

    // 157: Manastir Manastirica (Kladovo)
    157 => [
        'name' => 'Manastir Manastirica',
        'ktitor' => 'Sveti Nikodim Tismanski / knez Lazar Hrebeljanović',
        'godina_izgradnje' => '1300',
        'card_image' => 'images/monasteries/manastirica.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Manastirica posvećen je Svetom Nikoli i nalazi se u selu Manastirica kod Kladova u Timočkoj Krajini. Prema predanju, osnovao ga je krajem 14. veka Sveti Nikodim Tismanski, osnivač mnogih manastira u Krajini i Vlaškoj, uz podršku kneza Lazara.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna kamena građevina jednostavnih formi, sa pripratom i polukružnom apsidom. Unutrašnjost krasi skroman drveni ikonostas i ikone novijeg datuma.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Manastirica pripada Eparhiji timočkoj i predstavlja svedočanstvo o vekovnom monaškom prisustvu na obalama Dunava u Đerdapskom Podunavlju.",
        'images' => [
            ['url' => 'images/monasteries/manastirica.jpg', 'caption' => 'Crkva Svetog Nikole u manastiru Manastirica kod Kladova' . $src, 'sort_order' => 1],
        ]
    ],

    // 158: Manastir Suvodol
    158 => [
        'name' => 'Manastir Suvodol',
        'ktitor' => 'Sveti Knez Lazar Hrebeljanović / kralj Stefan Uroš II Milutin (predanje)',
        'godina_izgradnje' => '1000',
        'card_image' => 'images/monasteries/suvodol.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Suvodol posvećen je Rođenju Presvete Bogorodice i nalazi se u selu Selačka kod Zaječara, u skrovitoj dolini Suvodolskog potoka. Prema predanju, manastir potiče iz 11. veka iz doba vizantijske vlasti, a obnovio ga je knez Lazar Hrebeljanović u 14. veku. Sadašnja monumentalna crkva podignuta je 1869. godine za vreme vladavine kneza Milana Obrenovića na temeljima drevnog hrama.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Rođenja Presvete Bogorodice je monumentalna jednobrodna građevina u vizantijsko-romaničkom stilu sa velikom kupolom. U hramu se nalazi izuzetno vredan ikonostas koji je 1891. godine oslikao čuveni zograf Milija Marković. U manastiru se čuvaju čudotvorne ikone i lekoviti izvor vode.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Suvodol je ženski manastir Eparhije timočke. Poznat je po monaškom redu, prelepom cvetnom vrtu i velikom crkveno-narodnom saboru o Maloj Gospojini.",
        'images' => [
            ['url' => 'images/monasteries/suvodol.jpg', 'caption' => 'Monumentalna crkva Rođenja Presvete Bogorodice u manastiru Suvodol kod Zaječara' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/suvodol_gal_1.jpg', 'caption' => 'Zapadno pročelje sa ulaznim portalom i zvonikom u Suvodolu' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/suvodol_gal_2.jpg', 'caption' => 'Uređeni manastirski konaci sa cvetnim alejama i portom' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/suvodol_gal_3.jpg', 'caption' => 'Pogled na manastirski kompleks u skrovitoj dolini Suvodolskog potoka' . $src, 'sort_order' => 4],
        ]
    ],

    // 159: Manastir Vratna
    159 => [
        'name' => 'Manastir Vratna',
        'ktitor' => 'Sveti Nikodim Tismanski / kralj Stefan Dragutin Nemanjić (14. vek)',
        'godina_izgradnje' => '1300',
        'card_image' => 'images/monasteries/vratna.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Vratna posvećen je Vaznesenju Gospodnjem i nalazi se u selu Vratna kod Negotina, na samom ulazu u čuveni kanjon Vratnjanskih kapija (prirodnih kamenih prerasti, najvećih u Evropi). Manastir je u 14. veku osnovao Sveti Nikodim Tismanski uz pomoć kralja Milutina ili kralja Dragutina. Manastir je kroz vekove bio spaljivan i obnavljan, deleći sudbinu naroda Negotinske Krajine.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Vaznesenja Gospodnjeg je jednobrodna kamena građevina sa polukružnom apsidom i zvonikom na zapadnoj strani. U hramu se nalazi ikonostas iz 19. veka, a manastirski konak skladno prati divlji krečnjački pejzaž kanjona.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Vratna je ženski manastir Eparhije timočke. Zbog svoje fascinantne lokacije uz tri kamene prerasti reke Vratne, predstavlja jedinstvenu kombinaciju prirodnog čuda i pravoslavne duhovnosti.",
        'images' => [
            ['url' => 'images/monasteries/vratna.jpg', 'caption' => 'Crkva Vaznesenja Gospodnjeg u manastiru Vratna na ulazu u kanjon kamenih kapija' . $src, 'sort_order' => 1],
            ['url' => 'images/monasteries/vratna_gal_1.jpg', 'caption' => 'Pogled na manastirsku crkvu i gigantske kamene stene kanjona Vratne' . $src, 'sort_order' => 2],
            ['url' => 'images/monasteries/vratna_gal_2.jpg', 'caption' => 'Ulazni zvonik i cvetna manastirska porta u Vratni' . $src, 'sort_order' => 3],
            ['url' => 'images/monasteries/vratna_gal_3.jpg', 'caption' => 'Pogled na Vratnjanske kapije — prirodne kamene prerasti iznad manastira' . $src, 'sort_order' => 4],
        ]
    ],
];

echo "2. Ažuriranje primarne baze podataka (database/database.sqlite):\n";
DB::beginTransaction();

try {
    foreach ($batch2_data as $monasteryId => $data) {
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

        foreach ($batch2_data as $monasteryId => $data) {
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
echo "BATCH 2 (VALJEVSKA, MILEŠEVSKA, TIMOČKA) ZAVRŠEN USPEŠNO!\n";
echo "====================================================================\n";
