<?php

/**
 * SISTEMSKO ČIŠĆENJE I SINHRONIZACIJA - EPARHIJA VRANJSKA (ID 14)
 * Pravoslavni Svetionik — Master rad
 * Izvori: manastiri.rs / commons.wikimedia.org / spc.rs
 */

use App\Models\Monastery;
use App\Models\MonasteryImage;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "====================================================================\n";
echo "POKRETANJE REVIZIJE I ČIŠĆENJA ZA EPARHIJU VRANJSKU (ID 14)\n";
echo "====================================================================\n\n";

$src = '<br><small style="color: #eab308;"><em>(Izvor: manastiri.rs)</em></small>';

// 1. Definicija verifikovanih podataka, tačnih kartičnih slika, galerija i strukturiranih opisa
$eparchy_data = [
    // 167: Manastir Bresnica
    167 => [
        'name' => 'Manastir Bresnica',
        'ktitor' => 'Lokalno pravoslavno stanovništvo (obnovljen krajem 19. veka)',
        'godina_izgradnje' => '1895',
        'card_image' => 'images/monasteries/bresnica.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Bresnica smešten je u živopisnom planinskom predelu u blizini Bosilegrada. Posvećen je Svetoj Petki (Trnovoj Petki) i predstavlja vekovno mesto molitvenog sabiranja pravoslavnih vernika ovog dela jugoistočne Srbije. Podignut je krajem 19. veka (1895. godine) na temeljima starijeg srednjovekovnog hrama.\n\nARHITEKTURA I UNUTRAŠNJOST:\nManastirska crkva je jednobrodna građevina zidane strukture od lomljenog i tesanog kamena, pokrivena skladnim dvovodnim krovom. Unutrašnjost krase ikone i ikonostas sa kraja 19. i početka 20. veka, dela lokalnih zografa koji su negovali autentični stil južnosrpskih krajeva.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir pripada Eparhiji vranjskoj. O prazniku Svete Petke manastir okuplja veliki broj vernika iz Bosilegrada i čitavog Krajišta, predstavljajući duhovni oslonac i čuvara pravoslavnog identiteta.",
        'images' => [
            [
                'url' => 'images/monasteries/bresnica.jpg',
                'caption' => 'Crkva Svete Petke u manastiru Bresnica kod Bosilegrada' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 168: Manastir Kacapun
    168 => [
        'name' => 'Manastir Kacapun',
        'ktitor' => 'Nemanjići (13. vek)',
        'godina_izgradnje' => '1300',
        'card_image' => 'images/monasteries/kacapun.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Kacapun nalazi se u uskoj, gustim šumama obrasloj klisuri Kacapunske reke, nedaleko od Vladičinog Hana. Hram je posvećen Svetom proroku Iliji i prema predanju i arhitektonskim odlikama potiče iz doba Nemanjića (13. vek). Smešten je na starom karavanskom putu koji je nekada spajao Leskovac i Vranje.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je malih dimenzija, jednobrodna, sa poluobličastim svodom i polukružnom oltarskom apsidom, zidana od krupnih kamenih blokova i lomljenog kamena. Posebnu vrednost predstavljaju ostaci starog živopisa u unutrašnjosti hrama, nastali rukom veštih majstora iz poznog srednjeg veka.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nNakon više decenija zapustelosti, manastir je doživeo sveobuhvatnu obnovu u sklopu Eparhije vranjske. Podignut je novi manastirski konak i uređen pristup, te je svetinja ponovo postala žarište monaškog života i hodočašća na jugu Srbije.",
        'images' => [
            [
                'url' => 'images/monasteries/kacapun.jpg',
                'caption' => 'Drevna kamena crkva Svetog proroka Ilije iz 13. veka u manastiru Kacapun u klisuri Kacapunske reke' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/kacapun_gal_1.jpg',
                'caption' => 'Oltarska apsida i južna kamena fasada manastirske crkve u Kacapunu' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/kacapun_gal_2.jpg',
                'caption' => 'Pogled na manastirski hram i stoletnu šumu u kanjonu reke' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/kacapun_gal_3.jpg',
                'caption' => 'Kameni zvonik i uređena manastirska porta u Kacapunu' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 169: Manastir Lopardince
    169 => [
        'name' => 'Manastir Lopardince',
        'ktitor' => 'Srpska vlastela / Nepoznati ktitor (obnovljen u 16. veku)',
        'godina_izgradnje' => '1500',
        'card_image' => 'images/monasteries/lopardince.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Lopardince nalazi se na šumovitim padinama planine Rujan, u blizini Bujanovca. Posvećen je Svetom velikomučeniku Đorđu. Narodno predanje vezuje njegov nastanak za srednji vek, dok pisani izvori i tragovi obnove svedoče o živom monaškom centru tokom 16. i 17. veka.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva Svetog Đorđa je jednobrodna kamena građevina sa prostranom pripratom i skladnom kupolom. U unutrašnjosti se nalaze vredni fragmenti fresaka i očuvan ikonostas iz 19. veka, sa ikonama visoke umetničke i zanatske izrade.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Lopardince je kroz vekove bio utočište i mesto molitve srpskog naroda bujanovačkog kraja. U novije vreme hram i manastirski kompleks su obnovljeni, služeći redovna bogosluženja u okviru Eparhije vranjske.",
        'images' => [
            [
                'url' => 'images/monasteries/lopardince.jpg',
                'caption' => 'Crkva Svetog velikomučenika Đorđa sa tremom i kupolom u manastiru Lopardince kod Bujanovca' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 170: Manastir Prohor Pčinjski
    170 => [
        'name' => 'Manastir Prohor Pčinjski',
        'ktitor' => 'Vizantijski car Roman IV Diogen (obnovio Stefan Uroš II Milutin)',
        'godina_izgradnje' => '1070',
        'card_image' => 'images/monasteries/prohor-pcinjski.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Svetog Prohora Pčinjskog je jedan od najstarijih i najznačajnijih srpskih pravoslavnih manastira. Smešten je na šumovitim obroncima planine Kozjak, na samoj obali reke Pčinje. Osnovao ga je u 11. veku (oko 1070. godine) vizantijski car Roman IV Diogen, u znak zahvalnosti pustinjaku i svetitelju Prohoru Pčinjskom koji mu je prorekao carski presto. Početkom 14. veka srpski kralj Stefan Uroš II Milutin temeljno je obnovio i proširio manastir, uvrstivši ga među svoje najvažnije zadužbine.\n\nARHITEKTURA I UNUTRAŠNJOST:\nKompleks se sastoji od monumentalne crkve, prostranih spratnih konaka (među kojima se ističu Vranjski konak i Konak kralja Petra), zvonika i pomoćnih zgrada. Crkva čuva više slojeva živopisa — od monumentalnih fresaka iz Milutinovog doba (14. vek), preko živopisa iz 16. veka, do fresaka iz 19. veka. U desnom oltarskom delu nalazi se grobnica i kivot sa netruležnim, mirotočivim moštima Svetog Prohora Pčinjskog.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir ima izuzetan duhovni, kulturni i istorijski značaj za čitav srpski narod i pravoslavni svet. Već skoro hiljadu godina predstavlja neprekinuto stecište monaškog podviga, hodočašća i isceljenja pred svetim moštima Prepodobnog Prohora Pčinjskog Čudotvorca.",
        'images' => [
            [
                'url' => 'images/monasteries/prohor-pcinjski.jpg',
                'caption' => 'Monumentalni manastirski kompleks Svetog Prohora Pčinjskog pod planinom Kozjak uz reku Pčinju (11. vek)' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/prohor-pcinjski_gal_1.jpg',
                'caption' => 'Raskošni ikonostas i unutrašnjost crkve u kojoj počivaju mirotočive mošti Svetog Prohora Pčinjskog' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/prohor-pcinjski_gal_2.jpg',
                'caption' => 'Glavna manastirska crkva sa spratnim konacima i arkadama u porti manastira Prohor Pčinjski' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/prohor-pcinjski_gal_3.jpg',
                'caption' => 'Pogled na Vranjski konak i konak kralja Petra u manastirskom kompleksu Prohor Pčinjski' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 171: Manastir Žapsko
    171 => [
        'name' => 'Manastir Žapsko',
        'ktitor' => 'Sveti Knez Lazar Hrebeljanović (predanje)',
        'godina_izgradnje' => '1380',
        'card_image' => 'images/monasteries/zapsko.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Svetog arhiđakona Stefana u selu Gornje Žapsko, na obroncima Ristovačke gline nedaleko od Vranja, prema narodnom predanju podigao je Sveti Knez Lazar Hrebeljanović u 14. veku (oko 1380. godine). Manastir je u doba turskog ropstva više puta rušen i obnavljan, a posebnu ulogu imao je tokom 19. i 20. veka kao duhovno i prosvetno sedište.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna trikonhalna građevina sa osmostranom kupolom iznad naosa. Zidana je od kamena i opeke, omalterisana i okrečena u belo. U unutrašnjosti se nalazi bogato rezbareni ikonostas iz 19. veka koji su radili debarski majstori i vranjski slikari.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir je danas aktivan ženski manastir Eparhije vranjske. Poznat je po sestrinstvu koje se bavi ikonopisom, izradom bogoslužbenih odeždi i pčelarstvom. Predstavlja pravu duhovnu oazu mira i molitve u okolini Vranja.",
        'images' => [
            [
                'url' => 'images/monasteries/zapsko.jpg',
                'caption' => 'Bela crkva Svetog prvomučenika i arhiđakona Stefana sa kupolom u manastiru Gornje Žapsko kod Vranja' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/zapsko_gal_1.jpg',
                'caption' => 'Južna fasada hrama Svetog Stefana sa novim konakom i tremom u manastiru Žapsko' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/zapsko_gal_2.jpg',
                'caption' => 'Pogled na manastirsku portu sa zvonikom i cvetnim alejama u Žapskom' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/zapsko_gal_3.jpg',
                'caption' => 'Manastirski kompleks Gornje Žapsko okružen šumom i planinskim vencima' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 240: Manastir Dubnica
    240 => [
        'name' => 'Manastir Dubnica',
        'ktitor' => 'Stefan Uroš II Milutin (obnovio mitropolit novobrdski Josif 1576. godine)',
        'godina_izgradnje' => '1350',
        'card_image' => 'images/monasteries/dubnica-milesevska.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Dubnica nalazi se u istoimenom selu u podnožju planine Pljačkovice, svega nekoliko kilometara severno od Vranja. Posvećen je Svetim apostolima Petru i Pavlu. Prema istorijskim izvorima i natpisu u hramu, manastir je podignut u doba Nemanjića (kralja Milutina), a obnovljen 1576. godine za vreme mitropolita novobrdskog Josifa.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna građevina sa pripratom, naosom i oltarskom apsidom, presvedena poluobličastim svodom i nadvišena vitkom kupolom. Zidana je od klesanog kamena i opeke. Zidno slikarstvo iz 16. veka nosi stilske odlike postvizantijskog slikarstva i dela je vrsnih zografa tog doba.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Dubnica je kroz vekove bio rasadnik pismenosti i duhovni svetionik vranjskog kraja. Početkom 21. veka kompleks je kompletno revitalizovan, izgrađeni su novi konaci i zvonik, te je obnovljeno monaško opštežiće pod okriljem Eparhije vranjske.",
        'images' => [
            [
                'url' => 'images/monasteries/dubnica-milesevska.jpg',
                'caption' => 'Crkva Svetih apostola Petra i Pavla sa kupolom i konakom u manastiru Dubnica kod Vranja' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/dubnica-milesevska_gal_1.jpg',
                'caption' => 'Pogled na manastirski kompleks Dubnica, kamenu česmu i zvonik u porti' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/dubnica-milesevska_gal_2.jpg',
                'caption' => 'Južna fasada crkve Svetih apostola Petra i Pavla sa dekorativnim lukovima u Dubnici' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/dubnica-milesevska_gal_3.jpg',
                'caption' => 'Unutrašnjost hrama i ikonostas u manastiru Dubnica' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 246: Manastir Kozji Dol
    246 => [
        'name' => 'Manastir Kozji Dol',
        'ktitor' => 'Nepoznati vlastelin / Nemanjići (obnovljen u 19. veku)',
        'godina_izgradnje' => '1400',
        'card_image' => 'images/monasteries/kozji-dol.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Kozji Dol nalazi se u divljem i živopisnom kanjonu reke Pčinje, u selu Donji Kozji Dol u opštini Trgovište. Posvećen je Preobraženju Gospodnjem. Prema narodnom predanju, manastir je podignut u 14. veku u doba srpske srednjovekovne države, a stradao je pod turskom vlašću i obnovljen u 19. veku.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna građevina zidane strukture od lokalnog kamena sa polukružnom apsidom i dvovodnim krovom. U hramu se čuva vredan drveni ikonostas sa ikonama iz 19. veka, rad samokovskih i debarskih majstora.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nSmešten u skrovitom i mirnom planinskom ambijentu, manastir Kozji Dol je vekovima pružao duhovnu utehu gorštacima pčinjskog kraja. O prazniku Preobraženja Gospodnjeg ovde se održava tradicionalni crkveno-narodni sabor.",
        'images' => [
            [
                'url' => 'images/monasteries/kozji-dol.jpg',
                'caption' => 'Crkva Preobraženja Gospodnjeg u manastiru Kozji Dol kod Trgovišta' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 247: Manastir Lepčince
    247 => [
        'name' => 'Manastir Lepčince',
        'ktitor' => 'Srpska srednjovekovna vlastela (14. vek) / obnovljen u 19. veku',
        'godina_izgradnje' => '1350',
        'card_image' => 'images/monasteries/lepcince.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Svetog velikomučenika Pantelejmona nalazi se u ataru sela Lepčince, na padinama planine Kozjak, dvadesetak kilometara južno od Vranja. Prema predanju, svetinja potiče iz 14. veka iz doba kralja Stefana Dečanskog i cara Dušana. Nakon razaranja u tursko doba, hram je obnovljen sredinom 19. veka (1852. godine).\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna kamena građevina sa polukružnom apsidom i drvenim tremom na zapadnoj strani. U unutrašnjosti se nalazi izuzetno očuvan ikonostas iz 19. veka i mnogobrojne stare ikone, kao i čudotvorni izvor lekovite vode posvećen Svetom Pantelejmonu.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Lepčince je danas živi ženski manastir Eparhije vranjske. Poznat je po monaškom tihovanju, molitvi za bolesne i izuzetnom gostoprimstvu sestrinstva, privlačeći brojne vernike sa juga Srbije i iz Severne Makedonije.",
        'images' => [
            [
                'url' => 'images/monasteries/lepcince.jpg',
                'caption' => 'Drevna crkva Svetog velikomučenika Pantelejmona iz 14. veka sa zvonikom u manastiru Lepčince kod Vranja' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/lepcince_gal_1.jpg',
                'caption' => 'Južna kamena fasada manastirske crkve sa ulaznim vratima i drvenim tremom u Lepčincu' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/lepcince_gal_2.jpg',
                'caption' => 'Manastirski konak i uređeno dvorište u tišini šumovitih obronaka u Lepčincu' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/lepcince_gal_3.jpg',
                'caption' => 'Pogled na manastirski kompleks Svetog Pantelejmona u Lepčincu i okolnu prirodu' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 249: Manastir Simeon Stolpnik
    249 => [
        'name' => 'Manastir Simeon Stolpnik',
        'ktitor' => 'Stefan Nemanja (Sveti Simeon Mirotočivi) - predanje',
        'godina_izgradnje' => '1300',
        'card_image' => 'images/monasteries/simeon-stolpnik.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir posvećen Svetom Simeonu Stolpniku nalazi se u neposrednoj blizini Vranja (u selu Soderce / Sobina). Prema lokalnom predanju, osnivanje manastira se vezuje za period Nemanjića i poštovanje kulta Svetog Simeona Mirotočivog i ranohrišćanskog podvižnika Simeona Stolpnika.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva manastira je skladna jednobrodna građevina sa pripratom i polukružnom oltarskom apsidom, građena od lomljenog kamena. Unutrašnjost krase ikone i ikonostas izrađeni u duhu pravoslavnog crkvenog slikarstva južne Srbije.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Svetog Simeona Stolpnika pripada Eparhiji vranjskoj. Predstavlja omiljeno molitveno mesto meštana Vranja i okoline, koji se ovde okupljaju u miru i molitvi.",
        'images' => [
            [
                'url' => 'images/monasteries/simeon-stolpnik.jpg',
                'caption' => 'Crkva Svetog Simeona Stolpnika u manastirskom kompleksu kod Vranja' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/simeon-stolpnik_gal_1.jpg',
                'caption' => 'Pogled na hram i uređenu manastirsku portu u Soderce / Sobini' . $src,
                'sort_order' => 2
            ],
        ]
    ],

    // 251: Manastir Mrtvica
    251 => [
        'name' => 'Manastir Mrtvica',
        'ktitor' => 'Srpska srednjovekovna vlastela (14. vek) / obnovljen u 17. veku',
        'godina_izgradnje' => '1400',
        'card_image' => 'images/monasteries/mrtvica.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Uspenja Presvete Bogorodice u selu Mrtvica smešten je na spektakularnoj kamenoj litici na izlazu iz Grdeličke klisure, visoko iznad Južne Morave, nedaleko od Vladičinog Hana. Potiče iz srednjeg veka (kraj 14. ili početak 15. veka), a prema predanju podignut je na temeljima ranohrišćanskog hrama iz 6. veka.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna kamena građevina sa polukružnom apsidom i krovom pokrivenim kamenim pločama. Posebnu vrednost ima unutrašnji živopis iz 17. veka (1643. godine), koji je oslikao zograf Jovan. Na freskama se prepoznaju scene iz Velikih praznika i Bogorodičinog ciklusa, izuzetne likovne i kolorističke snage.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Mrtvica ima status spomenika kulture od velikog značaja. Zahvaljujući svom nestvarnom položaju na litici i vekovnom molitvenom kontinuitetu, predstavlja jedan od najlepših i najupečatljivijih manastira Eparhije vranjske i čitavog juga Srbije.",
        'images' => [
            [
                'url' => 'images/monasteries/mrtvica.jpg',
                'caption' => 'Srednjovekovna crkva Uspenja Presvete Bogorodice na litici iznad Južne Morave u manastiru Mrtvica' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/mrtvica_gal_1.jpg',
                'caption' => 'Oltarska apsida crkve Uspenja Bogorodice sa kamenim krovom i freskama u Mrtvici' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/mrtvica_gal_2.jpg',
                'caption' => 'Kameni zvonik i manastirski konak na strmoj steni u klisuri reke u Mrtvici' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/mrtvica_gal_3.jpg',
                'caption' => 'Pogled na manastir Mrtvicu i netaknutu prirodu Grdeličke klisure' . $src,
                'sort_order' => 4
            ],
        ]
    ],

    // 252: Manastir Palja
    252 => [
        'name' => 'Manastir Palja',
        'ktitor' => 'Stefan Nemanja (Sveti Simeon Mirotočivi) / Sveti Sava - predanje',
        'godina_izgradnje' => '1250',
        'card_image' => 'images/monasteries/palja.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Vavedenja Presvete Bogorodice nalazi se u zabačenom selu Palja, u planinskom masivu u blizini Vlasinskog jezera i Surdulice. Prema drevnom predanju, manastir potiče iz 13. veka iz doba Svetog Save i Stefana Nemanje. Predanje beleži da je upravo u manastiru Palja Sveti Sava zanoćio i služio liturgiju na svom poslednjem putu u Svetu Zemlju i Bugarsku.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je izgrađena od grubog lomljenog kamena sa polukružnom oltarskom apsidom i malim prozorima puškarnicama, svedočeći o burnoj istoriji i odbrambenom karakteru zdanja. U unutrašnjosti se ispod kasnijih slojeva kreča nalaze tragovi starog vizantijskog freskopisa.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nManastir Palja je vekovima bio skrovište i duhovno uporište naroda ovog planinskog kraja. Obnovljen je krajem 20. veka pod okriljem Eparhije vranjske, a o prazniku Vavedenja Presvete Bogorodice ovde se sabira verni narod iz Surdulice, Vranja i Vlasine.",
        'images' => [
            [
                'url' => 'images/monasteries/palja.jpg',
                'caption' => 'Drevna crkva Vavedenja Presvete Bogorodice iz 13. veka u manastiru Palja kod Surdulice' . $src,
                'sort_order' => 1
            ],
        ]
    ],

    // 253: Manastir Sveti Nikola
    253 => [
        'name' => 'Manastir Sveti Nikola',
        'ktitor' => 'Stefan Uroš II Milutin / Knez Baldovin (obnovio Stefan Uroš IV Dušan Silni)',
        'godina_izgradnje' => '1332',
        'card_image' => 'images/monasteries/sveti-nikola-vranje.jpg',
        'description' => "ISTORIJAT I NASTANAK:\nManastir Svetog oca Nikolaja (poznat kao manastir Sveti Nikola) nalazi se u samom gradu Vranju. Prvi put se pominje u poveljama kralja Stefana Dušana iz perioda 1343–1345. godine, kada ga je car Dušan priložio kao metoh manastiru Hilandaru na Svetoj Gori. Ktitor prvobitnog hrama u prvoj polovini 14. veka bio je vlastelin knez Baldovin iz vlasteoske porodice Bagaš.\n\nARHITEKTURA I UNUTRAŠNJOST:\nCrkva je jednobrodna kamena građevina sa polukružnom apsidom i prostranom pripratom. Prilikom arheoloških i restauratorskih istraživanja u hramu su otkrivene nadgrobne ploče srednjovekovnih ktitora (knez Baldovin, vlastelinka Ana). U crkvi se nalazi raskošan ikonostas iz 1905. godine, rad poznatog slikara Teofila iz Debra.\n\nDUHOVNI ŽIVOT I ZNAČAJ:\nKao hilandarski metoh i spomenik kulture od velikog značaja, manastir Svetog Nikole ima prvorazredno mesto u crkvenoj i kulturnoj istoriji Vranja. Od 1995. godine, podizanjem novog konaka i dolaskom monaha, manastiru je vraćen pun monaški život u okviru Eparhije vranjske.",
        'images' => [
            [
                'url' => 'images/monasteries/sveti-nikola-vranje.jpg',
                'caption' => 'Srednjovekovna crkva Svetog Nikole iz 14. veka — metoh Hilandara i zadužbina Nemanjića u Vranju' . $src,
                'sort_order' => 1
            ],
            [
                'url' => 'images/monasteries/sveti-nikola-vranje_gal_1.jpg',
                'caption' => 'Zapadno pročelje sa ulaznim portalom i kamenim svodom crkve Svetog Nikole u Vranju' . $src,
                'sort_order' => 2
            ],
            [
                'url' => 'images/monasteries/sveti-nikola-vranje_gal_2.jpg',
                'caption' => 'Novi manastirski konak sa tremom i cvetnom portom u manastiru Svetog Nikole' . $src,
                'sort_order' => 3
            ],
            [
                'url' => 'images/monasteries/sveti-nikola-vranje_gal_3.jpg',
                'caption' => 'Unutrašnjost crkve sa ikonostasom i arhijerejskim tronom u manastiru Svetog Nikole' . $src,
                'sort_order' => 4
            ],
        ]
    ],
];

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
        echo "  [AŽURIRAN] [{$monasteryId}] {$monastery->name} | Ktitor: {$monastery->ktitor} | Kartica: {$data['card_image']} | Galerija: {$count} slika\n";
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

        foreach ($eparchy_data as $monasteryId => $data) {
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
echo "REVIZIJA I SINHRONIZACIJA ZA EPARHIJU VRANJSKU ZAVRŠENE USPEŠNO!\n";
echo "====================================================================\n";
