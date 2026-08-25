<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Ktitor;
use App\Models\KtitorImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

echo "=== Ažuriranje galerija ktitora (Konačna čista verzija) ===\n";

// Definisanje jedinstvenih slika, tačnih opisa i izvora za svakog ktitora (slug)
$ktitorsData = [
    // 1. Stefan Nemanja (Sveti Simeon Mirotočivi)
    'stefan-nemanja' => [
        [
            'path' => 'images/ktitors/stefan-nemanja.jpg',
            'caption' => 'Sveti Simeon Mirotočivi (Stefan Nemanja) u monaškoj rasi sa oreolom i svitkom u ruci, freska iz Kraljeve crkve u Studenici (1314. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 1
        ],
        [
            'path' => 'images/ktitors/stefan-nemanja-2.jpg',
            'caption' => 'Sabor Stefana Nemanje protiv bogumila – freska iz crkve Svetog Ahilija u Arilju (1296. god.), prikaz velikog župana na prestolu okruženog sveštenstvom i velikašima.<br><small style="color: #eab308;"><em>* Izvor: dinastijanemanjica.weebly.com / commons.wikimedia.org</em></small>',
            'sort' => 2
        ],
        [
            'path' => 'images/ktitors/stefan-nemanja-3.jpg',
            'caption' => 'Sveti Simeon Mirotočivi u carskom i vladarskom ornatu sa krstom u ruci, freska u priprati manastira Gračanica (1321. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 3
        ],
        [
            'path' => 'images/ktitors/nemanja.jpg',
            'caption' => 'Spomenik Stefanu Nemanji na Savskom trgu u Beogradu, rad vajara Aleksandra Rukavišnjikova – prikaz velikog župana sa mačem i Hilandarskom poveljom na postamentu u obliku vizantijskog šlema.<br><small style="color: #eab308;"><em>* Izvor: dinastijanemanjica.weebly.com / commons.wikimedia.org</em></small>',
            'sort' => 4
        ],
        [
            'path' => 'images/ktitors/_2757791.jpg',
            'caption' => 'Istorijska karta srpske države pod velikim županom Stefanom Nemanjom (1168–1196. god.) sa teritorijalnim proširenjima i ucrtanim manastirima i crkvama.<br><small style="color: #eab308;"><em>* Izvor: dinastijanemanjica.weebly.com</em></small>',
            'sort' => 5
        ]
    ],

    // 2. Stefan Prvovenčani (Stefan Nemanjić)
    'stefan-prvovencani' => [
        [
            'path' => 'images/ktitors/stefan-prvovencani.jpg',
            'caption' => 'Kralj Stefan Prvovenčani u svečanoj vizantijskoj vladarskoj odeždi ukrašenoj krugovima sa dvoglavim orlovima i biserima, sa krunom i skiptrom u ruci, monumentalna freska iz crkve Bogorodice Ljeviške u Prizrenu (1307–1309. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 1
        ],
        [
            'path' => 'images/ktitors/stefan-prvovencani-2.jpg',
            'caption' => 'Najstariji sačuvani autentični portret kralja Stefana Prvovenčanog (Svetog Simona) sa krunom, freska iz manastira Mileševa (oko 1225. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 2
        ],
        [
            'path' => 'images/ktitors/stefan-prvovencani-3.jpg',
            'caption' => 'Hramovna ikona Svetog Stefana Prvovenčanog u vladarskom plaštu sa carskom jabukom i krunom, smeštena u duborezni tron sa srpskim grbom sa ocilima.<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 3
        ],
        [
            'path' => 'images/ktitors/1147777.jpg',
            'caption' => '„Krunisanje Stefana Prvovenčanog u Žiči 1217. godine”, umetnička slika – Sveti Sava polaže kraljevsku krunu na glavu Stefana Prvovenčanog pred srpskim velikašima.<br><small style="color: #eab308;"><em>* Izvor: dinastijanemanjica.weebly.com / commons.wikimedia.org</em></small>',
            'sort' => 4
        ],
        [
            'path' => 'images/ktitors/3.jpg',
            'caption' => 'Pravoslavna ikona Svetog kralja Stefana Prvovenčanog (Prepodobnog Simona monaha) sa modelom manastira Žiča, prvog krunidbenog hrama Nemanjića i sedišta srpske crkve.<br><small style="color: #eab308;"><em>* Izvor: dinastijanemanjica.weebly.com</em></small>',
            'sort' => 5
        ],
        [
            'path' => 'images/ktitors/_9209746.jpg',
            'caption' => 'Istorijska karta srpske države u doba Stefana Nemanje i kralja Stefana Prvovenčanog sa teritorijalnim granicama Kraljevine Srbije.<br><small style="color: #eab308;"><em>* Izvor: dinastijanemanjica.weebly.com</em></small>',
            'sort' => 6
        ]
    ],

    // 3. Stefan Radoslav Nemanjić
    'stefan-radoslav' => [
        [
            'path' => 'images/ktitors/stefan-radoslav.jpg',
            'caption' => 'Kralj Stefan Radoslav u svečanom vladarskom ornatu sa krunom i pendilijama, freska na Lozi Nemanjića u manastiru Gračanica (1321. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 1
        ],
        [
            'path' => 'images/ktitors/stefan-radoslav-2.jpg',
            'caption' => 'Zlatni verenički prsten kralja Stefana Radoslava i kraljice Ane Anđeline Komnine sa grčkim natpisom, izuzetno remek-delo srednjovekovnog zlatarstva (oko 1219–1220. god.).<br><small style="color: #eab308;"><em>* Izvor: Narodni muzej u Beogradu / commons.wikimedia.org</em></small>',
            'sort' => 2
        ],
        [
            'path' => 'images/ktitors/_7774155.jpg',
            'caption' => 'Čankasti novac (trāheja) kralja Stefana Radoslava kovan u kovnici u tvrđavi Ras – prvi kovani novac u srednjovekovnoj Srbiji rađen po vizantijskom uzoru.<br><small style="color: #eab308;"><em>* Izvor: Narodni muzej u Beogradu / dinastijanemanjica.weebly.com</em></small>',
            'sort' => 3
        ]
    ],

    // 4. Stefan Vladislav Nemanjić
    'stefan-vladislav' => [
        [
            'path' => 'images/ktitors/stefan-vladislav.jpg',
            'caption' => 'Ktitorski portret kralja Stefana Vladislava sa modelom manastira Mileševa koji prinosi Hristu uz posredovanje Bogorodice, freska iz manastira Mileševa (oko 1234. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 1
        ],
        [
            'path' => 'images/ktitors/stefan-vladislav-2.jpg',
            'caption' => 'Kralj Stefan Vladislav u vladarskom ornatu sa krunom i skiptrom, freska na Lozi Nemanjića u manastiru Gračanica.<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 2
        ],
        [
            'path' => 'images/ktitors/_6712917.jpg',
            'caption' => 'Freska „Beli Anđeo na Hristovom grobu” (Mironosice na grobu Hristovom) iz manastira Mileševa (oko 1235. god.), glavne zadužbine kralja Vladislava i mesta prenosa moštiju Svetog Save.<br><small style="color: #eab308;"><em>* Izvor: dinastijanemanjica.weebly.com / commons.wikimedia.org</em></small>',
            'sort' => 3
        ]
    ],

    // 5. Stefan Uroš I Nemanjić (Uroš Veliki)
    'stefan-uros-i' => [
        [
            'path' => 'images/ktitors/stefan-uros-I.jpg',
            'caption' => 'Ktitorski portret kralja Stefana Uroša I Velikog sa modelom manastira Sopoćani u rukama, monumentalna freska iz crkve Svete Trojice u manastiru Sopoćani (oko 1265. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 1
        ],
        [
            'path' => 'images/ktitors/stefan-uros-i-3.jpg',
            'caption' => 'Kralj Stefan Uroš I sa sinom Dragutinom i prinčevima, freska iz priprate manastira Sopoćani.<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 2
        ],
        [
            'path' => 'images/ktitors/5.jpg',
            'caption' => 'Kralj Stefan Uroš I Veliki, istorijska litografija Anastasa Jovanovića (1852. god.) koja prikazuje kralja sa krunom i skiptrom.<br><small style="color: #eab308;"><em>* Izvor: dinastijanemanjica.weebly.com</em></small>',
            'sort' => 3
        ],
        [
            'path' => 'images/ktitors/_6734174.jpg',
            'caption' => 'Istorijska karta Kraljevine Srbije u doba kralja Uroša I (1243–1276. god.) sa razvojem rudarstva (dolazak Sasa u Brskovo, Rudnik, Trepču i Novo Brdo).<br><small style="color: #eab308;"><em>* Izvor: dinastijanemanjica.weebly.com</em></small>',
            'sort' => 4
        ]
    ],

    // 6. Stefan Dragutin Nemanjić (Sremski kralj / Prepodobni Teoktist)
    'kralj-dragutin' => [
        [
            'path' => 'images/ktitors/kralj-dragutin.jpg',
            'caption' => 'Ktitorski portret kralja Stefana Dragutina u raskošnom vladarskom ornatu sa krunom i modelom crkve Svetog Ahilija u rukama, freska iz crkve Svetog Ahilija u Arilju (1296. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 1
        ],
        [
            'path' => 'images/ktitors/dragutin-ruzica.jpg',
            'caption' => 'Sveti kralj Stefan Dragutin sa krunom i modelom hrama, hramovna freska sa natpisom „Ст. Драгутинъ Краљ Сербск.” iz crkve Ružica na Kalemegdanu u Beogradu.<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 2
        ],
        [
            'path' => 'images/ktitors/dragutin-milutin-sopocani.jpg',
            'caption' => 'Mladi prinčevi Stefan Dragutin i Stefan Milutin u svečanim odorama, monumentalna freska iz manastira Sopoćani (oko 1265. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 3
        ],
        [
            'path' => 'images/ktitors/kralj-dragutin-3.jpg',
            'caption' => 'Kralj Stefan Dragutin u vladarskoj odeždi sa krunom i skiptrom, freska na Lozi Nemanjića u manastiru Visoki Dečani (oko 1346–1348. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 4
        ],
        [
            'path' => 'images/ktitors/4.jpg',
            'caption' => 'Istorijski portret kralja Stefana Dragutina sa krunom ukrašenom biserima i pendilijama.<br><small style="color: #eab308;"><em>* Izvor: dinastijanemanjica.weebly.com</em></small>',
            'sort' => 5
        ],
        [
            'path' => 'images/ktitors/_9259675.jpg',
            'caption' => 'Srebrni dinar kralja Stefana Dragutina (Sremskog kralja) sa natpisom „STEFANVS REX”, kovan u rudniku i kovnici Brskovo.<br><small style="color: #eab308;"><em>* Izvor: Narodni muzej u Beogradu / dinastijanemanjica.weebly.com</em></small>',
            'sort' => 6
        ]
    ],

    // 7. Stefan Uroš II Milutin (Sveti kralj Milutin)
    'kralj-milutin' => [
        [
            'path' => 'images/ktitors/kralj-milutin-2.jpg',
            'caption' => 'Ktitorski model Bogorodičine crkve u Gračanici u rukama kralja Milutina, monumentalna freska iz manastira Gračanica (1321. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 1
        ],
        [
            'path' => 'images/ktitors/kralj-milutin.jpg',
            'caption' => 'Sveti kralj Milutin u starosti u carskom vizantijskom sakosu sa krunom i krstom, freska na Lozi Nemanjića u manastiru Gračanica.<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 2
        ],
        [
            'path' => 'images/ktitors/Sv_kralj-milutin.jpg',
            'caption' => 'Pravoslavna ikona Svetog kralja Milutina sa modelom gračaničke petokupolne crkve u rukama i svetiteljskim oreolom.<br><small style="color: #eab308;"><em>* Izvor: spc.rs / dinastijanemanjica.weebly.com</em></small>',
            'sort' => 3
        ],
        [
            'path' => 'images/ktitors/2.jpg',
            'caption' => 'Istorijski portret kralja Stefana Uroša II Milutina u punoj vladarskoj zrelosti sa visokom krunom, sedom bradom i vizantijskim lorosom.<br><small style="color: #eab308;"><em>* Izvor: dinastijanemanjica.weebly.com</em></small>',
            'sort' => 4
        ],
        [
            'path' => 'images/ktitors/_4574659_orig.jpg',
            'caption' => 'Istorijska karta Kraljevine Srbije pod kraljem Milutinom (1282–1321. god.) sa teritorijalnim proširenjima na jug (Skoplje, Polog, Ovče Polje, Debar) i brojnim zadužbinama.<br><small style="color: #eab308;"><em>* Izvor: dinastijanemanjica.weebly.com</em></small>',
            'sort' => 5
        ],
        [
            'path' => 'images/ktitors/images (2).jpg',
            'caption' => 'Srebrni dinar kralja Milutina sa prikazom kralja koji prima zastavu od Svetog Stefana prvomučenika – čuveni novac koji je opevao i Dante Aligijeri u „Božanstvenoj komediji”.<br><small style="color: #eab308;"><em>* Izvor: Narodni muzej u Beogradu / commons.wikimedia.org</em></small>',
            'sort' => 6
        ],
        [
            'path' => 'images/ktitors/_2637331.jpg',
            'caption' => 'Umetnički kolaž kralja Milutina: prikaz velikog vladara, graditelja preko 40 manastira i crkava, sa kraljicom Simonidom i vitezovima.<br><small style="color: #eab308;"><em>* Izvor: dinastijanemanjica.weebly.com</em></small>',
            'sort' => 7
        ]
    ],

    // 8. Stefan Uroš III Dečanski (Sveti kralj Dečanski)
    'stefan-decanski' => [
        [
            'path' => 'images/ktitors/stefan-decanski-2.jpg',
            'caption' => 'Monumentalni ktitorski portret kralja Stefana Uroša III Dečanskog u carskoj odori koji prinosi model hrama Hrista Pantokratora, freska iz manastira Visoki Dečani (oko 1346–1348. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 1
        ],
        [
            'path' => 'images/ktitors/stefan-decanski-3.jpg',
            'caption' => 'Kralj Stefan Dečanski i njegov sin mladi kralj Stefan Dušan sa zajedničkim modelom dečanskog hrama, freska iz manastira Visoki Dečani.<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 2
        ],
        [
            'path' => 'images/ktitors/images.jpg',
            'caption' => 'Pravoslavna ikona Svetog velikomučenika i kralja Stefana Dečanskog sa krunom, krstom i zadužbinom manastirom Visoki Dečani.<br><small style="color: #eab308;"><em>* Izvor: spc.rs / dinastijanemanjica.weebly.com</em></small>',
            'sort' => 3
        ],
        [
            'path' => 'images/ktitors/_3731897_orig.jpg',
            'caption' => '„Bitka kod Velbužda 1330. godine”, istorijska litografija Anastasa Jovanovića – velika pobeda kralja Stefana Dečanskog i mladog kralja Dušana nad bugarskim carstvom Mihaila Šišmana.<br><small style="color: #eab308;"><em>* Izvor: Narodni muzej u Beogradu / dinastijanemanjica.weebly.com</em></small>',
            'sort' => 4
        ],
        [
            'path' => 'images/ktitors/_1407683774.jpg',
            'caption' => 'Zlatni prsten kraljice Teodore (supruge kralja Stefana Dečanskog i majke cara Dušana) sa natpisom „Kto ga nosi pomozi mu Bog” i dvoglavim orlom, pronađen u manastiru Banjska.<br><small style="color: #eab308;"><em>* Izvor: Narodni muzej u Beogradu / dinastijanemanjica.weebly.com</em></small>',
            'sort' => 5
        ]
    ],

    // 9. Stefan Uroš IV Dušan Silni (Srpski car Dušan)
    'car-dusan' => [
        [
            'path' => 'images/ktitors/car-dusan.jpg',
            'caption' => 'Car Stefan Dušan Silni u carskom vizantijskom divitezionu i lorosu sa carskom krunom i skiptrom, freska iz manastira Lesnovo (1349. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 1
        ],
        [
            'path' => 'images/ktitors/car-dusan-3.png',
            'caption' => '„Krunisanje cara Dušana u Skoplju 1346. godine”, monumentalno remek-delo srpskog slikara Paje Jovanovića – proglašenje Srpskog carstva i krunisanje cara Srba i Grka.<br><small style="color: #eab308;"><em>* Izvor: Narodni muzej u Beogradu / commons.wikimedia.org</em></small>',
            'sort' => 2
        ],
        [
            'path' => 'images/ktitors/carica-jelena-2.jpg',
            'caption' => 'Carski portret: car Dušan, carica Jelena i mladi kralj/carević Uroš V, freska iz manastira Visoki Dečani (oko 1346–1348. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 3
        ],
        [
            'path' => 'images/ktitors/_467782.jpg',
            'caption' => 'Srednjovekovni viteški mačevi i oružje srpske vojske iz XIV veka iz doba Srpskog carstva cara Stefana Dušana.<br><small style="color: #eab308;"><em>* Izvor: Vojni muzej Beograd / dinastijanemanjica.weebly.com</em></small>',
            'sort' => 4
        ]
    ],

    // 10. Stefan Uroš V Nejaki (Sveti car Uroš)
    'uros-nejaki' => [
        [
            'path' => 'images/ktitors/uros-nejaki.jpg',
            'caption' => 'Car Stefan Uroš V Nejaki u carskom sakosu sa krunom i skiptrom, freska iz manastira Psača (oko 1365–1371. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 1
        ],
        [
            'path' => 'images/ktitors/uros-nejaki-2.jpg',
            'caption' => 'Zajednički portret cara Uroša V i kralja Vukašina Mrnjavčevića, freska iz manastira Psača.<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 2
        ],
        [
            'path' => 'images/ktitors/546.jpg',
            'caption' => '„Krunisanje cara Uroša V”, istorijska umetnička kompozicija – krunisanje poslednjeg srpskog cara iz loze Nemanjića 1355. godine.<br><small style="color: #eab308;"><em>* Izvor: dinastijanemanjica.weebly.com</em></small>',
            'sort' => 3
        ]
    ],

    // 11. Sveti Sava (Rastko Nemanjić)
    'sveti-sava' => [
        [
            'path' => 'images/ktitors/sveti-sava.jpg',
            'caption' => 'Najstariji i najverniji autentični portret Svetog Save u polistavrionu (arhijerejskom krstastom omoforu) sa Jevanđeljem, freska iz manastira Mileševa (oko 1225. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 1
        ],
        [
            'path' => 'images/ktitors/sveti-sava-3.jpg',
            'caption' => 'Sveti Sava Srpski kao prvi arhiepiskop autokefalne Srpske pravoslavne crkve, freska iz Kraljeve crkve u manastiru Studenica (1314. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 2
        ],
        [
            'path' => 'images/ktitors/Sveti_Sava_blagosilja_Srpčad,_Uroš_Predić,_1921.jpg',
            'caption' => '„Sveti Sava blagosilja Srpčad”, čuveno antologijsko ulje na platnu akademika Uroša Predića (1921. god.) – prvi srpski prosvetitelj na arhijerejskom tronu blagosilja decu i narod pored Zakonopravila.<br><small style="color: #eab308;"><em>* Izvor: Narodni muzej u Beogradu / commons.wikimedia.org</em></small>',
            'sort' => 3
        ],
        [
            'path' => 'images/ktitors/16-sveti-sava-blagosilja-srpc48dad.jpg',
            'caption' => 'Zidna hramovna freska sa natpisom „Св. Сава благосиља Српчад” – arhijerej Sava polaže ruku na glavu dečaka i daruje blagoslov srpskoj deci i porodicama u tradicionalnoj nošnji.<br><small style="color: #eab308;"><em>* Izvor: spc.rs</em></small>',
            'sort' => 4
        ],
        [
            'path' => 'images/ktitors/65b4596c6ee7630d38003b55.jpg',
            'caption' => 'Pravoslavni freskopis „Sveti Sava poučava decu i narod pismenosti, mudrosti i hrišćanskoj veri” ispred manastirskog sabornog hrama.<br><small style="color: #eab308;"><em>* Izvor: spc.rs / dinastijanemanjica.weebly.com</em></small>',
            'sort' => 5
        ]
    ],

    // 12. Kraljica Ana Dandolo
    'ana-dandolo' => [
        [
            'path' => 'images/ktitors/ana-dandolo.jpg',
            'caption' => 'Kraljica Ana Dandolo (unuka mletačkog dužda Enrika Dandola i supruga kralja Stefana Prvovenčanog), istorijska umetnička rekonstrukcija kraljice u kraljevskoj odori.<br><small style="color: #eab308;"><em>* Izvor: dinastijanemanjica.weebly.com / commons.wikimedia.org</em></small>',
            'sort' => 1
        ],
        [
            'path' => 'images/ktitors/ana-dandolo-2.jpg',
            'caption' => 'Čuvena istorijska freska „Smrt kraljice Ane Dandolo” iz priprate manastira Sopoćani (oko 1265. god.) – prikaz kraljice na odru okružene sinom kraljem Urošem I, unucima Dragutinom i Milutinom i dvorom.<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 2
        ]
    ],

    // 13. Ana Nemanjić (Sveta Anastasija)
    'ana-zena-stefana-nemanje' => [
        [
            'path' => 'images/ktitors/ana-zena-stefana-nemanje.jpg',
            'caption' => 'Mozaik Prepodobne mati Anastasije (Ane Nemanjić), supruge Stefana Nemanje i majke Svetog Save, Stefana Prvovenčanog i Vukana.<br><small style="color: #eab308;"><em>* Izvor: spc.rs / commons.wikimedia.org</em></small>',
            'sort' => 1
        ],
        [
            'path' => 'images/ktitors/ana-zena-stefana-nemanje-2.jpg',
            'caption' => 'Prepodobna mati Anastasija se moli pred Bogorodicom na prestolu, monumentalna freska iz priprate Bogorodičine crkve u manastiru Studenica (1568. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 2
        ]
    ],

    // 14. Carica Jelena (Sveta Jelisaveta)
    'carica-jelena' => [
        [
            'path' => 'images/ktitors/carica-Jelena.jpg',
            'caption' => 'Carica Jelena u carskom ornatu sa krunom pored cara Dušana, freska iz manastira Lesnovo (1349. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 1
        ],
        [
            'path' => 'images/ktitors/carica-jelena-2.jpg',
            'caption' => 'Carica Jelena sa suprugom carom Dušanom i sinom carem Urošem V, freska iz manastira Visoki Dečani (oko 1346–1348. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 2
        ]
    ],

    // 15. Kraljica Jelena Anžujska (Sveta Jelena)
    'jelena-anzujska' => [
        [
            'path' => 'images/ktitors/jelena-anzujska.jpg',
            'caption' => 'Autentični ktitorski portret kraljice Jelene Anžujske u vladarskoj odeždi sa krunom, freska iz njene glavne zadužbine – manastira Gradac (oko 1275. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 1
        ],
        [
            'path' => 'images/ktitors/_2531645.jpg',
            'caption' => 'Sveta kraljica Jelena Anžujska kao monahinja Jelena u plavoj monaškoj odori sa velom i rukama uzdignutim u molitvi, freska iz kapele u manastiru Đurđevi Stupovi u Rasu (kraj XIII veka).<br><small style="color: #eab308;"><em>* Izvor: dinastijanemanjica.weebly.com / spc.rs</em></small>',
            'sort' => 2
        ]
    ],

    // 16. Kneginja Milica Hrebeljanović (Sveta Evgenija)
    'kneginja-milica' => [
        [
            'path' => 'images/ktitors/kneginja-milica.jpg',
            'caption' => 'Ktitorski portret kneginje Milice u vladarskoj odeždi sa krunom i žezlom u ruci, freska iz njene glavne zadužbine – manastira Ljubostinja (oko 1402–1405. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 1
        ],
        [
            'path' => 'images/ktitors/kneginja-milica-2.jpg',
            'caption' => 'Knez Lazar i kneginja Milica, zajednička ktitorska freska iz manastira Ljubostinja pod blagoslovom Gospoda Isusa Hrista.<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 2
        ],
        [
            'path' => 'images/ktitors/kneginja-milica-mandrovic.jpg',
            'caption' => 'Istorijski portret kneginje Milice u svečanoj srpskoj vladarskoj odori i sa velom (rad K. Mandrovića, 1885. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 3
        ],
        [
            'path' => 'images/ktitors/pohvala-knezu-lazaru.jpg',
            'caption' => 'Čuvena „Pohvala knezu Lazaru” vezena zlatnim i srebrnim nitima na crvenom atlasu od strane monahinje Jefimije (1402. god.), sačuvana u manastiru Ljubostinja zadužbini kneginje Milice.<br><small style="color: #eab308;"><em>* Izvor: Muzej Srpske pravoslavne crkve / commons.wikimedia.org</em></small>',
            'sort' => 4
        ]
    ],

    // 17. Sveti Knez Lazar Hrebeljanović
    'knez-lazar' => [
        [
            'path' => 'images/ktitors/knez-lazar.jpg',
            'caption' => 'Ktitorski portret svetog kneza Lazara sa vladarskom krunom ukrašenom biserima i modelom crkve Vaznesenja Gospodnjeg u ruci, autentična freska iz manastira Ravanica (oko 1385–1387. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 1
        ],
        [
            'path' => 'images/ktitors/kneginja-milica-2.jpg',
            'caption' => 'Sveti knez Lazar i kneginja Milica u svečanim vladarskim odorama, ktitorska freska iz manastira Ljubostinja.<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 2
        ],
        [
            'path' => 'images/ktitors/knezeva-vecera-1871.jpg',
            'caption' => 'Istorijska slika „Kneževa večera uoči Kosovske bitke 1389. godine” sa knezom Lazarom i srpskim vitezovima (rad slikara Adama Stefanovića, 1871. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 3
        ],
        [
            'path' => 'images/ktitors/boj-na-kosovu-1870.jpg',
            'caption' => 'Monumentalna kompozicija „Boj na Kosovu i junačka pogibija svetog kneza Lazara za Krst časni i slobodu zlatnu” (rad slikara Adama Stefanovića, 1870. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 4
        ],
        [
            'path' => 'images/ktitors/knez-lazar-pecat.jpg',
            'caption' => 'Autentični pečatnjak svetog kneza Lazara sa njegovim ličnim viteškim grbom (šlem sa bivoljim rogovima) i starosrpskim ćiriličnim natpisom.<br><small style="color: #eab308;"><em>* Izvor: Narodni muzej u Beogradu / commons.wikimedia.org</em></small>',
            'sort' => 5
        ]
    ],

    // 18. Kraljica Simonida Paleolog
    'simonida' => [
        [
            'path' => 'images/ktitors/simonida.jpg',
            'caption' => 'Kraljica Simonida Paleolog, autentična ktitorska freska iz manastira Gračanica (oko 1321. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 1
        ],
        [
            'path' => 'images/ktitors/_6734174.jpg',
            'caption' => 'Kraljica Simonida u raskošnoj vizantijskoj carskoj haljini sa krunom i bisernim minđušama, detalj freske iz Gračanice.<br><small style="color: #eab308;"><em>* Izvor: dinastijanemanjica.weebly.com</em></small>',
            'sort' => 2
        ]
    ],

    // 19. Sveti Despot Stefan Lazarević
    'stefan-lazarevic' => [
        [
            'path' => 'images/ktitors/stefan-lazarevic-3.jpg',
            'caption' => 'Monumentalni ktitorski portret despota Stefana Lazarevića u vladarskom sakosu sa dvoglavim orlovima, modelom crkve Svete Trojice i poveljom, freska iz manastira Manasija (Resava) (1407–1418. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 1
        ],
        [
            'path' => 'images/ktitors/stefan-lazarevic-kalenic.jpg',
            'caption' => 'Prepodobni despot Stefan Lazarević (Stefan Visoki), čuveni portret i remek-delo moravskog slikarstva iz manastira Kalenić (oko 1413–1420. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 2
        ],
        [
            'path' => 'images/ktitors/stefan-i-vuk-rudenica.jpg',
            'caption' => 'Despot Stefan Lazarević sa krunom, oreolom i modelom hrama pored brata Vuka Lazarevića, ktitorska freska iz manastira Rudenica (1402–1405. god.).<br><small style="color: #eab308;"><em>* Izvor: commons.wikimedia.org</em></small>',
            'sort' => 3
        ],
        [
            'path' => 'images/ktitors/stefan-lazarevic-novac.jpg',
            'caption' => 'Srebrni novac (dinar) despota Stefana Lazarevića sa natpisom „ДЕСПОТ” i prikazom Hrista Pantokratora.<br><small style="color: #eab308;"><em>* Izvor: Narodni muzej u Beogradu / commons.wikimedia.org</em></small>',
            'sort' => 4
        ],
        [
            'path' => 'images/ktitors/stefan-lazarevic-kruna.jpg',
            'caption' => 'Idealna rekonstrukcija zlatne krune despota Stefana Lazarevića, izrađena u filigranskoj tehnici sa biserima i dragim kamenjem.<br><small style="color: #eab308;"><em>* Izvor: Istorijski muzej Srbije / commons.wikimedia.org</em></small>',
            'sort' => 5
        ]
    ],

    // 20. Veliki knez Vukan Nemanjić
    'vukan-nemanjic' => [
        [
            'path' => 'images/ktitors/vukan-nemanjic.jpg',
            'caption' => 'Veliki knez Vukan Nemanjić (kralj Duklje i Dalmacije, najstariji sin Stefana Nemanje), umetnički portret vladara u svečanoj odori.<br><small style="color: #eab308;"><em>* Izvor: dinastijanemanjica.weebly.com / commons.wikimedia.org</em></small>',
            'sort' => 1
        ],
        [
            'path' => 'images/ktitors/vukan-nemanjic-3.jpg',
            'caption' => 'Čuvena celostrana minijatura „Starac Dana” (Hristos Emanuil) iz Vukanovog jevanđelja (oko 1200. god.), jednog od najznačajnijih srpskih srednjovekovnih rukopisa pisanih za velikog kneza Vukana.<br><small style="color: #eab308;"><em>* Izvor: Ruska nacionalna biblioteka u Sankt Peterburgu / commons.wikimedia.org</em></small>',
            'sort' => 2
        ]
    ]
];

// 1. Očisti postojeće zapise u ktitor_images i unesi nove
DB::table('ktitor_images')->truncate();

$totalInserted = 0;
foreach ($ktitorsData as $slug => $images) {
    $ktitor = Ktitor::where('slug', $slug)->first();
    if (!$ktitor) {
        echo "UPOZORENJE: Ktitor sa slugom '{$slug}' nije pronađen!\n";
        continue;
    }

    echo "Ktitor ID {$ktitor->id} ({$ktitor->name}) -> " . count($images) . " slika\n";
    foreach ($images as $img) {
        KtitorImage::create([
            'ktitor_id' => $ktitor->id,
            'path' => $img['path'],
            'caption' => $img['caption'],
            'sort' => $img['sort']
        ]);
        $totalInserted++;
    }
}

echo "Ukupno uneto slika: {$totalInserted}\n";

// 2. Kopiranje aktivne baze u drugu bazu da obe budu 100% identične
$activeDb = config('database.connections.sqlite.database');
$otherDb = ($activeDb === database_path('database.sqlite')) ? storage_path('database.sqlite') : database_path('database.sqlite');

if (File::exists($activeDb)) {
    File::copy($activeDb, $otherDb);
    echo "Uspešno sinhronizovano: {$activeDb} -> {$otherDb}\n";
}

echo "Završeno uspešno!\n";
