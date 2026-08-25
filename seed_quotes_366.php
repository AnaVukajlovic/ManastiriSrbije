<?php

/**
 * SKRIPTA ZA GENERISANJE I SINHRONIZACIJU 366 CITATA DANA
 * Citati Patrijarha Pavla, Svetog vladike Nikolaja Velimirovića,
 * Prepodobnog Justina Ćelijskog, Svetog Save i ostalih Svetih Otaca.
 */

use App\Models\Quote;
use Illuminate\Support\Facades\DB;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "====================================================================\n";
echo "POKRETANJE GENERISANJA I SINHRONIZACIJE CITATA DANA (366 DANA)\n";
echo "====================================================================\n\n";

$quotesData = [
    // 1 - 30 (Januar)
    1 => [
        'text' => 'Budimo ljudi, makar i po cenu života, ali neljudi nemojmo biti ni po cenu celog sveta.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke i besede'
    ],
    2 => [
        'text' => 'Ko nema mira u sebi, taj ga uzalud traži u celom svetu.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misli o dobru i zlu'
    ],
    3 => [
        'text' => 'Kakve su ti misli, takav ti je život. Ako su ti misli mirne i tihe, i život će ti biti ispunjen mirom.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Kakve su ti misli, takav ti je život'
    ],
    4 => [
        'text' => 'Čuvajmo se neljudi, ali se još više čuvajmo da i mi sami ne postanemo neljudi.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko slovo'
    ],
    5 => [
        'text' => 'Stekni mir u srcu svom, i hiljade oko tebe naći će spasenje.',
        'author' => 'Sveti Serafim Sarovski',
        'source' => 'Duhovne pouke'
    ],
    6 => [
        'text' => 'Gde je ljubav, tamo je i Bog. Bez ljubavi sva druga dela gube svoju pravu vrednost.',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'Besede o ljubavi'
    ],
    7 => [
        'text' => 'Hristos se rodi! Neka mir Božiji i ljubav Njegova ispune svaki dom i svako pravoslavno srce.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Božićna poslanica'
    ],
    8 => [
        'text' => 'Dobra pomisao ima toliku snagu da može preobraziti i najtežu životnu situaciju.',
        'author' => 'Sveti Pajsije Svetogorac',
        'source' => 'Duhovna buđenja'
    ],
    9 => [
        'text' => 'Vera je svetlost koja obasjava tamu ovoga sveta i vodi dušu ka večnom izvoru života.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Vera svetih'
    ],
    10 => [
        'text' => 'Bog nas ne pita šta su drugi nama učinili, nego šta smo mi učinili drugima.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Duhovne pouke'
    ],
    11 => [
        'text' => 'Molitva je disanje duše. Kao što telo ne može živeti bez vazduha, tako ni duša ne može bez molitve.',
        'author' => 'Sveti Jovan Lestvičnik',
        'source' => 'Lestvica božanstvenog ushođenja'
    ],
    12 => [
        'text' => 'Sve što čovek deli sa drugima smanjuje se, osim ljubavi. Što je više dajete, više je imate.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Besede'
    ],
    13 => [
        'text' => 'Nema veće pobede nego pobediti sebe samoga, svoje strasti i svoju sujetu.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misionarska pisma'
    ],
    14 => [
        'text' => 'Trudite se da uvek budete u ljubavi i miru među sobom, jer gde je mir, tu je i Bog.',
        'author' => 'Sveti Sava Srpski',
        'source' => 'Zakonopravilo i pouke'
    ],
    15 => [
        'text' => 'Radosti moja, Hristos vaskrse! Nema mesta tuzi i strahu tamo gde živi vaskrsli Gospod.',
        'author' => 'Sveti Serafim Sarovski',
        'source' => 'Žitije i pouke'
    ],
    16 => [
        'text' => 'Ako je Bog sa nama, čega se imamo bojati? A ako nije sa nama, čemu se imamo nadati?',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko slovo'
    ],
    17 => [
        'text' => 'Mir i radost od Duha Svetoga jesu najveće bogatstvo koje čovek može zadobiti na zemlji.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke i besede'
    ],
    18 => [
        'text' => 'Kada ti je najteže, znaj da te Bog tada najviše čisti i priprema za veće duhovne darove.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Molitve na jezeru'
    ],
    19 => [
        'text' => 'Proći će sve, ali duša, obraz i ono što je dobro urađeno ostaju večno.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke narodu'
    ],
    20 => [
        'text' => 'Ljubi neprijatelje svoje, jer oni ti pomažu da spoznaš koliko je tvoje srce ispunjeno smirenjem.',
        'author' => 'Sveti Siluan Atonski',
        'source' => 'Zapisi o smirenju'
    ],
    21 => [
        'text' => 'Hristos je večna Istina, večni Život i večno Vaskrsenje čovekovo u svim svetovima.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Dogmatika Pravoslavne Crkve'
    ],
    22 => [
        'text' => 'Čista savest je najmekši jastuk za počinak i miran san.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Duhovni razgovori'
    ],
    23 => [
        'text' => 'Nauči da ćutiš, da bi tvoja duša mogla u tišini da čuje tihi i blagi glas Božiji.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Omilije'
    ],
    24 => [
        'text' => 'Ne brinite previše za sutrašnji dan; Gospod se brine o svemu, samo Mu predajte svoje srce u poverenju.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Duhovni saveti'
    ],
    25 => [
        'text' => 'Prava vera se ne pokazuje lepim rečima, već delima ljubavi i čistotom srca.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Besede'
    ],
    26 => [
        'text' => 'Koren svakog dobra jeste strah Božiji i iskrena, nesebična ljubav prema bližnjima.',
        'author' => 'Sveti Sava Srpski',
        'source' => 'Hilandarski tipik'
    ],
    27 => [
        'text' => 'Sveti Sava nas uči da hodimo putem pravde, istine i bratske sloge.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Svetosavska beseda'
    ],
    28 => [
        'text' => 'Kao što sunce sija i dobrima i zlima, tako i hrišćansko srce treba da obasjava ljubavlju sve ljude.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misli o dobru i zlu'
    ],
    29 => [
        'text' => 'Svako vidi ono što želi: pčela traži cvet i med, a muva nečistoću. Budimo duhovne pčele.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],
    30 => [
        'text' => 'Duhovni mir dolazi kada predate svoj život i sve svoje brige u svedržeće ruke Božije.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Mir i radost u Duhu Svetom'
    ],
    31 => [
        'text' => 'Nema malog dobra koje je pred Bogom zaboravljeno, niti male čaše hladne vode pružene u Njegovo ime.',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'Besede o milostinji'
    ],

    // 32 - 59 (Februar)
    32 => [
        'text' => 'Reči treba da budu blage, a dokazi jaki i utemeljeni u istini.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Razgovori sa narodom'
    ],
    33 => [
        'text' => 'Gospod traži od nas samo malo dobre volje i trud, a On sam daje blagodat i snagu.',
        'author' => 'Sveti Pajsije Svetogorac',
        'source' => 'Sa bolom i ljubavlju savremenom čoveku'
    ],
    34 => [
        'text' => 'Zlo se zlom ne može pobediti, kao što se vatra vatrom ne gasi, nego samo vodom dobrote i praštanja.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],
    35 => [
        'text' => 'Zemaljsko je za malena carstvo, a nebesko uvek i doveka.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Kosovska misao'
    ],
    36 => [
        'text' => 'Oprostite svima od srca, jer onaj ko ne prašta, samog sebe zatvara u mračnu tamnicu gorčine.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    37 => [
        'text' => 'Nemojmo osuđivati druge, jer ne znamo kakve unutrašnje borbe i krstove svako u svojoj duši nosi.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Duhovne pouke'
    ],
    38 => [
        'text' => 'Molitva bez smirenja je kao ptica bez krila – ne može se uzdići ka nebeskom prestolu.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Duhovna lira'
    ],
    39 => [
        'text' => 'Kada pomažeš nevoljniku, nemoj se gorditi; to Bog preko tvojih ruku pruža Svoju milost.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Besede'
    ],
    40 => [
        'text' => 'Smirenje je odeća Božanstva; gde je smirenje, tamo se nastanjuje blagodat Duha Svetoga.',
        'author' => 'Sveti Isak Sirin',
        'source' => 'Podvižnička slova'
    ],
    41 => [
        'text' => 'Budite blagi prema drugima, a strogi prema sebi i svojim gresima.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Duhovni saveti'
    ],
    42 => [
        'text' => 'Ko hoće da vlada drugima, mora najpre naučiti da vlada sobom i svojim pomislima.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],
    43 => [
        'text' => 'Vreme je tkanina od koje se tka večnost; tkajmo je delima milosrđa, molitve i trpljenja.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misli o dobru i zlu'
    ],
    44 => [
        'text' => 'Život na zemlji je kratak ispit za večnost. Pazimo kako polažemo taj ispit svakoga dana.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirske besede'
    ],
    45 => [
        'text' => 'Na Sretenje se susreću čovek i Bog; neka i naše srce bude hram u koji rado primamo Hrista.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Praznične besede'
    ],
    46 => [
        'text' => 'Iskrena suza pokajanja vredi pred Bogom više nego sva prolazna blaga ovoga sveta.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Molitve na jezeru'
    ],
    47 => [
        'text' => 'Čovek ne može birati vreme u kojem će živeti, ali od njega zavisi hoće li biti čovek ili neljudi.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Besede'
    ],
    48 => [
        'text' => 'Kada se molimo za druge, mi im šaljemo tihu, nevidljivu božansku silu mira i ljubavi.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Kakve su ti misli, takav ti je život'
    ],
    49 => [
        'text' => 'Bratoljublje i sloga temelj su svakog čestitog doma i hrišćanskog naroda.',
        'author' => 'Sveti Sava Srpski',
        'source' => 'Slovo o ljubavi'
    ],
    50 => [
        'text' => 'Gledajmo uvek u oči jedni drugima kao braća i sestre, sa smirenjem, toplinom i praštanjem.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],
    51 => [
        'text' => 'Ko se u Boga uzda, taj ne posustaje ni kada se sve zemaljske nade ugase.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misionarska pisma'
    ],
    52 => [
        'text' => 'Cilj hrišćanskog života jeste zadobijanje oblagodaćenog mira i Duha Svetoga u čistom srcu.',
        'author' => 'Sveti Serafim Sarovski',
        'source' => 'Razgovor sa Motovilovim'
    ],
    53 => [
        'text' => 'Nema lepše i uzvišenije dužnosti na zemlji nego pružiti ruku utehe onome ko strada.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko slovo'
    ],
    54 => [
        'text' => 'Neka tvoje srce neprestano peva tihu hvalu Gospodu i videćeš kako svaka tuga beži.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Duhovne pouke'
    ],
    55 => [
        'text' => 'Nije siromašan onaj ko ima malo zemaljskih dobara, nego onaj kome nikada nije dosta.',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'Besede'
    ],
    56 => [
        'text' => 'Strpljenje i trpljenje su zlatni ključevi koji otvaraju dveri Carstva Nebeskog.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],
    57 => [
        'text' => 'Ljubav prema Hristu preobražava i najtvrđe ljudsko srce u nepresušni izvor dobrote.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Svetosavlje kao filosofija života'
    ],
    58 => [
        'text' => 'Ko seje seme dobrote na zemlji, požnjeće plodove večnog života i radosti na nebu.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misli o dobru i zlu'
    ],
    59 => [
        'text' => 'Zahvaljuj Bogu na svemu – i na radosti i na krstu, jer sve biva po Njegovom svetom promislu.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko slovo'
    ],

    // 60 - 90 (Mart)
    60 => [
        'text' => 'Post bez molitve i milosrđa jeste samo dijeta; pravi post je uzdržanje jezika od osude i srca od zlobe.',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'Besede o Velikom Postu'
    ],
    61 => [
        'text' => 'Čuvaj mir u duši i ne dozvoli da te sitne spoljašnje neprilike izbace iz ravnoteže.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    62 => [
        'text' => 'Ako sami ne oprostimo onima koji su nam sagrešili, kako možemo tražiti od Boga da nama oprosti?',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke u postu'
    ],
    63 => [
        'text' => 'Gospode Isuse Hriste, Sine Božiji, pomiluj me grešnog – ova molitva osvećuje svaki trenutak našeg života.',
        'author' => 'Sveti Grigorije Palama',
        'source' => 'O Isusovoj molitvi'
    ],
    64 => [
        'text' => 'Nemoj tražiti priznanja od ljudi; traži da tvoja dela budu ugodna Bogu Koji vidi u tajnosti.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Besede'
    ],
    65 => [
        'text' => 'Dela ljubavi ostaju zauvek upisana u knjizi večnog Božijeg pamćenja.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misionarska pisma'
    ],
    66 => [
        'text' => 'Kada osudiš brata svoga, ti gubiš duhovni mir; kada ga pokriješ ljubavlju, Hristos te nagrađuje blagodaću.',
        'author' => 'Sveti Pajsije Svetogorac',
        'source' => 'Duhovno buđenje'
    ],
    67 => [
        'text' => 'Bolje je nepravdu trpeti nego nepravdu činiti. Ko nepravdu trpi, sa Hristom je; ko je čini, sam sebe ranjava.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko pismo'
    ],
    68 => [
        'text' => 'Sve što radiš, radi sa molitvom i blagoslovom, pa će svaki tvoj trud biti plodonosan.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Saveti'
    ],
    69 => [
        'text' => 'Pravoslavlje je život u Hristu Bogočoveku, neprestano svedočenje svetlosti i pobede nad smrću.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Zapisi'
    ],
    70 => [
        'text' => 'Nema tog zemaljskog blaga koje može zameniti mirnu i čistu ljudsku savest.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Duhovne pouke'
    ],
    71 => [
        'text' => 'Molitva pravednika otvara nebesa i privlači blagoslov na ceo narod.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Omilije'
    ],
    72 => [
        'text' => 'U tišini i trpljenju nalazi se istinska duhovna snaga svakog pravoslavnog hrišćanina.',
        'author' => 'Sveti Isak Sirin',
        'source' => 'Podvižnička slova'
    ],
    73 => [
        'text' => 'Kada bi se svi držali pravila da ne čine drugome ono što ne žele sebi, zemlja bi postala predvorje raja.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Besede'
    ],
    74 => [
        'text' => 'Radost u Gospodu jeste naša neuništiva tvrđava u svim iskušenjima ovoga sveta.',
        'author' => 'Sveti Serafim Sarovski',
        'source' => 'Pouke'
    ],
    75 => [
        'text' => 'Nemoj se žaliti na svoj krst; Bog zna meru naših snaga i nikada ne daje iskušenje veće od onoga što možemo poneti.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Kakve su ti misli, takav ti je život'
    ],
    76 => [
        'text' => 'Prava veličina čoveka meri se njegovom spremnošću da služi drugima u smirenju.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko slovo'
    ],
    77 => [
        'text' => 'Hristos je jedini istinski mir nemirnom i uplašenom čovečanstvu.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Poruke narodu'
    ],
    78 => [
        'text' => 'Svetitelji su živa jevanđelja ispisana delima vere, nade i neizmernog milosrđa.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Žitija Svetih'
    ],
    79 => [
        'text' => 'Čuvaj svoj jezik od praznoslovlja i osude, jer reč ima silu da leči, ali i da ranjava.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],
    80 => [
        'text' => 'Kada čovek spozna svoju nemoć i obrati se Bogu, tada u njega ulazi svemoguća božanska blagodat.',
        'author' => 'Sveti Siluan Atonski',
        'source' => 'Zapisi'
    ],
    81 => [
        'text' => 'Blagost i krotost razoružavaju svaku zlobu i osvajaju ljudska srca.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Duhovni razgovori'
    ],
    82 => [
        'text' => 'Ne bojte se onih koji ubijaju telo, nego se bojte greha koji odvaja dušu od njenog Tvorca.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko slovo'
    ],
    83 => [
        'text' => 'Molitva je zlatni most između zemaljske prolaznosti i večnog Carstva Božijeg.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Molitve na jezeru'
    ],
    84 => [
        'text' => 'Milostinja data u tajnosti svedoči o istinskoj ljubavi koja ne traži ljudsku slavu.',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'Besede'
    ],
    85 => [
        'text' => 'Kada se nađeš u nedoumici, stani, pomoli se kratko Bogu i On će ti osvetliti pravi put.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],
    86 => [
        'text' => 'Život bez Boga je kao brod bez kormila na uzburkanom moru.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misli o dobru i zlu'
    ],
    87 => [
        'text' => 'Svaki dan je nova prilika za pokajanje, praštanje i činjenje dobrih dela.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Duhovni zapisi'
    ],
    88 => [
        'text' => 'Učini sve što je do tebe, a ono što ne možeš – prepusti Bogu u veri i nadi.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Razgovori'
    ],
    89 => [
        'text' => 'Mirno srce je najlepši oltar na kome gori plamen neprestane zahvalnosti Gospodu.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    90 => [
        'text' => 'Gospod ne gleda na spoljašnji izgled i bogatstvo, već na čistoću i nameru ljudskog srca.',
        'author' => 'Sveti Vasilije Veliki',
        'source' => 'Pouke mladima'
    ],

    // 91 - 120 (April)
    91 => [
        'text' => 'Obavezni smo i u najtežim iskušenjima da postupamo kao ljudi, bez izgovora za neljudskost.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko pismo'
    ],
    92 => [
        'text' => 'Krst Hristov je merilo ljubavi: dati sebe za spasenje i život drugoga.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Praznične besede'
    ],
    93 => [
        'text' => 'Ko pretrpi do kraja u veri i nadi, taj će ući u radost vaskrslog Gospoda.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Omilije'
    ],
    94 => [
        'text' => 'Smiren čovek ne pada, jer stoji niže od svih u svom srcu, a Bog ga uzvisuje.',
        'author' => 'Sveti Antonije Veliki',
        'source' => 'Dobrotoljublje'
    ],
    95 => [
        'text' => 'Kad se čovek rodi, svi se raduju a samo on plače. Živimo tako da kad umremo, svi plaču a mi se radujemo.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke o životu'
    ],
    96 => [
        'text' => 'Hristos Vaskrse – u te dve reči sadržana je sva naša vera, sva naša nada i sva naša večnost.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Vaskršnja beseda'
    ],
    97 => [
        'text' => 'Vaskrsenje Hristovo je pobeda života nad smrću, ljubavi nad mržnjom i svetlosti nad tamom.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Vaskršnja poslanica'
    ],
    98 => [
        'text' => 'Neka vaskršnja radost obasja vaša srca i otera svaki strah i malodušnost.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misionarska pisma'
    ],
    99 => [
        'text' => 'Kada imaš mir u duši, tada je u tvom srcu neprestani Vaskrs.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    100 => [
        'text' => 'Nemoj se bojati ničega osim greha, jer jedino greh može nauditi tvojoj besmrtnoj duši.',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'Besede'
    ],
    101 => [
        'text' => 'Ljubav ne traži svoje; ona se raduje kada može drugome da pomogne i pruži utehu.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Besede'
    ],
    102 => [
        'text' => 'Kao što se zlato čisti u ognju, tako se hrišćanska vrlina kali kroz strpljivo podnošenje nevolja.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Duhovna lira'
    ],
    103 => [
        'text' => 'Budi zahvalan za svaku čašu vode, za svaki zrak sunca i svaki blagosloveni dan koji ti Bog daruje.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Duhovni saveti'
    ],
    104 => [
        'text' => 'Dobar čovek iz dobre riznice svoga srca iznosi dobro, svedočeći Hrista svakim svojim postupkom.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],
    105 => [
        'text' => 'Molitva za neprijatelje jeste vrhunac jevanđelske ljubavi i siguran put ka duhovnom miru.',
        'author' => 'Sveti Siluan Atonski',
        'source' => 'Zapisi'
    ],
    106 => [
        'text' => 'Svetlost Hristova obasjava sve ljude; otvorimo zavese našeg srca da ta svetlost uđe u nas.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Besede'
    ],
    107 => [
        'text' => 'Neka vam reči budu mirne, postupci pravedni, a srce puno razumevanja za tuđe slabosti.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko slovo'
    ],
    108 => [
        'text' => 'Vera bez dela je mrtva, ali i dela bez ljubavi i smirenja nemaju duhovnu snagu.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misli o dobru i zlu'
    ],
    109 => [
        'text' => 'Kada te obuzme tuga ili briga, priteci pod okrilje Presvete Bogorodice i ona će ti izmoliti mir.',
        'author' => 'Sveti Nektarije Eginski',
        'source' => 'Duhovne pouke'
    ],
    110 => [
        'text' => 'Ako želiš da promeniš svet oko sebe, počni od preobražaja sopstvenog srca i svojih misli.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Kakve su ti misli, takav ti je život'
    ],
    111 => [
        'text' => 'Nema veće radosti nego sresti drugog čoveka sa čistim srcem i pružiti mu iskrenu bratsku ljubav.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Razgovori'
    ],
    112 => [
        'text' => 'Svaki hrišćanin pozvan je da bude so zemlji i svetlost svetu kroz svoja dobra dela.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misionarska pisma'
    ],
    113 => [
        'text' => 'Pokajanje nije samo žaljenje za prošlošću, već hrabra odluka da se od danas živi bolje i čistije.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Duhovni zapisi'
    ],
    114 => [
        'text' => 'Čuvajmo čistotu vere i lepotu naših svetinja, jer one su koren našeg duhovnog identiteta.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko pismo'
    ],
    115 => [
        'text' => 'Kada ti neko nanese uvredu, ti mu uzvrati molitvom i blagoslovom; tako se gasi svaki gnev.',
        'author' => 'Sveti Pajsije Svetogorac',
        'source' => 'Duhovna borba'
    ],
    116 => [
        'text' => 'Mir u duši je najlepši dar koji možemo prineti Gospodu.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    117 => [
        'text' => 'Biti čovek znači nositi u sebi sliku i priliku Božiju i nikada je ne uprljati zlom.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Besede'
    ],
    118 => [
        'text' => 'Istina Božija uvek na kraju pobeđuje svaku laž i privid ovoga sveta.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Omilije'
    ],
    119 => [
        'text' => 'Gospod traži od nas veru koja se pokazuje u trpljenju, nadi i nesebičnoj žrtvi.',
        'author' => 'Sveti Vasilije Veliki',
        'source' => 'Besede'
    ],
    120 => [
        'text' => 'Gde je sloga i međusobno uvažavanje, tamo Gospod šalje Svoj neiscrpni blagoslov.',
        'author' => 'Sveti Sava Srpski',
        'source' => 'Pouke'
    ],

    // 121 - 151 (Maj)
    121 => [
        'text' => 'Ko prašta drugome, taj sam prima oproštaj i ulazi u tihu luku božanskog mira.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],
    122 => [
        'text' => 'Sve što je prolazno nestaje kao dim, a dela načinjena u ime Hristovo sijaju u večnosti.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Molitve na jezeru'
    ],
    123 => [
        'text' => 'Ne dopusti da te tuđe loše raspoloženje zarazi; nosi svetlost i unosi mir gde god da se pojaviš.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    124 => [
        'text' => 'Sveti Vasilije Ostroški nas uči da je vera živa i da čuda bivaju tamo gde postoji iskrena molitva.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Beseda na Ostrogu'
    ],
    125 => [
        'text' => 'Sveti Vasilije Ostroški je nebeski lekar i utešitelj svih onih koji mu sa verom i suzama prilaze.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Besede o svetiteljima'
    ],
    126 => [
        'text' => 'Učini dobro i baci u vodu; ako ljudi zaborave, Bog nikada ne zaboravlja.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Razgovori'
    ],
    127 => [
        'text' => 'Čuvaj duhovni mir po svaku cenu; ne daj da ga bilo kakve zemaljske brige pomute.',
        'author' => 'Sveti Serafim Sarovski',
        'source' => 'Duhovne pouke'
    ],
    128 => [
        'text' => 'Kada pomažeš drugome, čini to sa radošću i osmehom, a ne sa prekorom ili uzdisanjem.',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'O milostinji'
    ],
    129 => [
        'text' => 'Najvažnije je sačuvati čist obraz i veru, a sve ostalo će Gospod urediti po Svojoj milosti.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko slovo'
    ],
    130 => [
        'text' => 'Lepota prirode svedoči o beskrajnoj premudrosti i dobroti Tvorca.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Simvoli i signali'
    ],
    131 => [
        'text' => 'Ako tvoje srce ne osuđuje nikoga, onda je u njemu nastanjen Duh Sveti.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Duhovne pouke'
    ],
    132 => [
        'text' => 'Nema većeg bogatstva od vere u Vaskrslog Hrista i nade u večni život.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Dogmatika'
    ],
    133 => [
        'text' => 'Gospod nas poziva na svetost, a svetost počinje od malih svakodnevnih dela dobrote i poštenja.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Besede'
    ],
    134 => [
        'text' => 'Sveti Ćirilo i Metodije doneli su nam pismenost i svetlost Jevanđelja; čuvajmo to sveto nasleđe.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Katihizis'
    ],
    135 => [
        'text' => 'Molitva u porodici donosi mir, slogu i blagoslov na decu i dom.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke o porodici'
    ],
    136 => [
        'text' => 'Smirenomisleći čovek u svakome vidi vrlinu, a u sebi prepoznaje potrebu za Božijim milosrđem.',
        'author' => 'Sveti Pajsije Svetogorac',
        'source' => 'Duhovno buđenje'
    ],
    137 => [
        'text' => 'Nemojte gubiti nadu u najtežim trenucima; noć je najtamnija pred samo svitanje.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Saveti'
    ],
    138 => [
        'text' => 'Svaki čovek kojeg sretneš nosi u sebi neku muku; budi mu melem, a ne nova rana.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Duhovne pouke'
    ],
    139 => [
        'text' => 'Radost davanja je daleko veća i slađa od radosti primanja darova.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misli o dobru i zlu'
    ],
    140 => [
        'text' => 'Hristos je svetlost koja goni svaku tamu; ko za Njim ide, neće hoditi u mraku.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Besede'
    ],
    141 => [
        'text' => 'Vozljubimo jedni druge da bismo jednodušno ispovedali Oca i Sina i Svetoga Duha.',
        'author' => 'Sveti Sava Srpski',
        'source' => 'Žička beseda o pravoj veri'
    ],
    142 => [
        'text' => 'Gospod traži čisto srce, a ne spoljašnje licemerje i prazne reči.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],
    143 => [
        'text' => 'Tišina i molitva u manastirskoj porti leče i odmaraju umornu ljudsku dušu.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Zapisi iz manastira'
    ],
    144 => [
        'text' => 'Kada se predamo Božijoj volji, u naše srce ulazi neshvatljiv mir koji prevazilazi svaki ljudski razum.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    145 => [
        'text' => 'Život nam je dat kao dar i prilika da naučimo da volimo Boga i bližnje.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Besede'
    ],
    146 => [
        'text' => 'Nema te muke koju blagodat Duha Svetoga ne može preobraziti u izvor duhovne utehe.',
        'author' => 'Sveti Serafim Sarovski',
        'source' => 'Pouke'
    ],
    147 => [
        'text' => 'Duh Sveti silazi na smirene i čiste dušom, ispunjavajući ih nebeskom mudrošću.',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'Besede na Pedesetnicu'
    ],
    148 => [
        'text' => 'Duhovni život zahteva svakodnevnu pažnju i bdenje nad sopstvenim srcem.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Zapisi'
    ],
    149 => [
        'text' => 'Budimo uvek svesni da smo pred licem Božijim i živimo dostojno Njegove ljubavi.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko slovo'
    ],
    150 => [
        'text' => 'Zahvalno srce privlači nove božanske blagoslove i neprestani mir.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misli o dobru i zlu'
    ],
    151 => [
        'text' => 'Kada praštaš, ti skidaš sa svojih leđa teški teret gneva i gorčine.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Duhovni saveti'
    ],

    // 152 - 181 (Jun)
    152 => [
        'text' => 'U svakom čoveku gledaj brata i sasud Božije blagodati, ma ko on bio.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],
    153 => [
        'text' => 'Molitva je najlepši razgovor koji stvorenje može imati sa svojim Tvorcem.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Molitve na jezeru'
    ],
    154 => [
        'text' => 'Kada voliš Hrista, onda ti nijedna zapovest Njegova nije teška.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Besede'
    ],
    155 => [
        'text' => 'Ne dopustimo da nas obeshrabre ljudske nepravde; Bog sve vidi i Njegova je pravda večna.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko pismo'
    ],
    156 => [
        'text' => 'Krotost i smirenje su najjače oružje protiv svake oholosti i gordosti.',
        'author' => 'Sveti Isak Sirin',
        'source' => 'Podvižnička slova'
    ],
    157 => [
        'text' => 'Kada u porodici vlada ljubav i poštovanje, taj dom je mala domaća Crkva.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    158 => [
        'text' => 'Bolje je ćutati nego izgovoriti reč koja će raniti nečiju dušu i uneti razdor.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Duhovni razgovori'
    ],
    159 => [
        'text' => 'Svetlost vere sija najjače u tami nevolja i stradanja.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misionarska pisma'
    ],
    160 => [
        'text' => 'Pokajanjem čistimo ogledalo naše duše da bi se u njemu ogledao lik Hristov.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Duhovni zapisi'
    ],
    161 => [
        'text' => 'Nemoj se hvaliti svojim dobrim delima; neka tvoja desnica ne zna šta čini levica.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Besede'
    ],
    162 => [
        'text' => 'Duhovni mir je skuplji od svih zemaljskih kruna i bogatstava.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Kakve su ti misli, takav ti je život'
    ],
    163 => [
        'text' => 'Bog je Ljubav, i ko prebiva u ljubavi, u Bogu prebiva i Bog u njemu.',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'Tumačenje Jevanđelja'
    ],
    164 => [
        'text' => 'Zlo nema trajnu silu; ono prolazi, a dobro ostaje u večnosti sa Bogom.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],
    165 => [
        'text' => 'Čuvajmo svoje svetinje i istorijsko pamćenje, jer oni su svedoci našeg duhovnog opstanka.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Srpski narod kao Teodul'
    ],
    166 => [
        'text' => 'Smiren čovek živi u miru sa celom prirodom i sa svim ljudima.',
        'author' => 'Sveti Siluan Atonski',
        'source' => 'Pouke o miru'
    ],
    167 => [
        'text' => 'Biti hrišćanin znači opraštati, voleti i nositi krst svoj sa verom i trpljenjem.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Besede'
    ],
    168 => [
        'text' => 'Ko u malom ostane veran i pošten, tome će Gospod poveriti i veće darove.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Besede'
    ],
    169 => [
        'text' => 'Nema situacije iz koje Bog ne može izvesti dobro za onoga ko Ga iskreno voli.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Duhovni saveti'
    ],
    170 => [
        'text' => 'Radost je plod Duha Svetoga; nosimo tu tihu radost gde god da idemo.',
        'author' => 'Sveti Serafim Sarovski',
        'source' => 'Pouke'
    ],
    171 => [
        'text' => 'Naše je samo ono što damo drugome u ime Hristovo.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misli o dobru i zlu'
    ],
    172 => [
        'text' => 'Nemojmo dopustiti da nas mržnja zarazi; mržnja uništava onoga ko je nosi u sebi.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko slovo'
    ],
    173 => [
        'text' => 'Molitva roditelja ima ogromnu snagu i čuva decu na svim njihovim životnim putevima.',
        'author' => 'Sveti Pajsije Svetogorac',
        'source' => 'Porodični život'
    ],
    174 => [
        'text' => 'Kada ti je srce mirno, onda i najteže poslove obavljaš sa lakoćom i blagoslovom.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    175 => [
        'text' => 'Gospod nas nikada ne ostavlja same; On je uvek tu, bliže nama nego što mi mislimo.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Zapisi'
    ],
    176 => [
        'text' => 'Zahvaljuj Bogu za svaki dan, jer svaki dan je Njegov neprocenjivi dar.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],
    177 => [
        'text' => 'Sveti knez Lazar na Vidovdan je izabrao nebesko carstvo, ostavivši nam zavet časti i vere.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Vidovdanska beseda'
    ],
    178 => [
        'text' => 'Vidovdan nas podseća da je zemaljsko za malena carstvo, a nebesko uvek i doveka.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Vidovdanska poslanica'
    ],
    179 => [
        'text' => 'Sveti knez Lazar je svojim mučeništvom zapečatio vernost Hristu i Njegovom večnom Carstvu.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Beseda na Vidovdan'
    ],
    180 => [
        'text' => 'Čuvajmo kosovski zavet vere, čojstva i smirenja kao zenicu svoga oka.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirske reči'
    ],
    181 => [
        'text' => 'Ko živi po savesti i zapovestima Božijim, taj već na zemlji oseća predokus večnog mira.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],

    // 182 - 212 (Jul)
    182 => [
        'text' => 'Sveti apostoli Petar i Pavle učili su nas da veru svedočimo smirenjem i neustrašivom revnošću.',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'Pohvala apostolima'
    ],
    183 => [
        'text' => 'Duhovni život počinje onog trenutka kada prestanemo da krivimo druge za svoje nevolje.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Saveti'
    ],
    184 => [
        'text' => 'Budimo uvek spremni da pružimo ruku pomirenja, jer u praštanju je istinska hrišćanska snaga.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],
    185 => [
        'text' => 'Ko u srcu nosi ljubav prema Bogu, taj ne oseća teskobu ovoga sveta.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Duhovni spisi'
    ],
    186 => [
        'text' => 'Vera je svetionik koji osvetljava put kroz olujne talase ovozemaljskog života.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misionarska pisma'
    ],
    187 => [
        'text' => 'Nemoj tražiti od drugih ono što sam nisi spreman da učiniš.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Razgovori'
    ],
    188 => [
        'text' => 'Kada ti dođe teška misao, nemoj je primati u razgovor; zameni je molitvom i zahvalnošću Bogu.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Kakve su ti misli, takav ti je život'
    ],
    189 => [
        'text' => 'Milostivo srce privlači anđele čuvare i donosi nebeski blagoslov na ceo dom.',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'Besede'
    ],
    190 => [
        'text' => 'Prava sloboda je sloboda od greha, strasti i sebičnosti u Hristu Spasitelju.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Filosofske urvine'
    ],
    191 => [
        'text' => 'Bolje je u smirenju služiti drugima nego u gordosti tražiti prva mesta.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko pismo'
    ],
    192 => [
        'text' => 'Sveti apostoli su ceo svet preobrazili ne mačem, već rečju ljubavi i sopstvenom žrtvom.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Omilije'
    ],
    193 => [
        'text' => 'Sveti apostoli Petar i Pavle podsećaju nas da se istina Božija svedoči čistim životom i mučeničkom vernošću.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Petrovdanska beseda'
    ],
    194 => [
        'text' => 'Kada se u duši nastani mir Božiji, čovek zrači radošću i dobrotom na sve oko sebe.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    195 => [
        'text' => 'Gospod zna svaku našu suzu i nijedan naš iskreni uzdah Mu ne ostaje nepoznat.',
        'author' => 'Sveti Siluan Atonski',
        'source' => 'Zapisi'
    ],
    196 => [
        'text' => 'Čuvajmo mir u svojim porodicama; bez mira u kući nema blagoslova ni u poslu.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke narodu'
    ],
    197 => [
        'text' => 'Lepota manastirskih fresaka je prozor u večnost i svedočanstvo nebeske harmonije.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Katihizis'
    ],
    198 => [
        'text' => 'Svaki dan proveden u dobru i molitvi jeste korak bliže večnom spasenju.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Zapisi'
    ],
    199 => [
        'text' => 'Nemoj se uznemiravati kada te ljudi klevetaju; Bog zna istinu i Njegov sud je jedini pravedan.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Duhovni saveti'
    ],
    200 => [
        'text' => 'U tišini i skromnosti rađaju se najveća dela i najuzvišenije vrline.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    201 => [
        'text' => 'Sveti prorok Ilija nas uči vatrenoj revnosti za Boga i nepokolebljivoj odanosti istini.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Praznične omilije'
    ],
    202 => [
        'text' => 'Sveti prorok Ilija gromoglasno svedoči da je Bog živ i da Njegova reč traje vavek.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Beseda na Ilindan'
    ],
    203 => [
        'text' => 'Iskrena molitva je najmoćnije oružje protiv malodušnosti i beznađa.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Besede'
    ],
    204 => [
        'text' => 'Smirenje otvara sva vrata i omekšava i najtvrđa ljudska srca.',
        'author' => 'Sveti Serafim Sarovski',
        'source' => 'Duhovne pouke'
    ],
    205 => [
        'text' => 'Neka vam misli budu čiste, želje umerene, a srce ispunjeno zahvalnošću.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    206 => [
        'text' => 'Čovek vredi onoliko koliko dobra učini drugima za života.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko slovo'
    ],
    207 => [
        'text' => 'Vera je kao seme gorušičino: u početku malo, ali kad uzraste u srcu, donosi nebrojene plodove.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misionarska pisma'
    ],
    208 => [
        'text' => 'Sveta Petka Trnova i Prepodobna mati Paraskeva su brze pomoćnice u nevoljama i zaštitnice vernih.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Praznične pouke'
    ],
    209 => [
        'text' => 'Ko se moli za ceo svet sa bolom u srcu, taj se upodobljava Hristu Spasitelju.',
        'author' => 'Sveti Siluan Atonski',
        'source' => 'Zapisi'
    ],
    210 => [
        'text' => 'Ne gubite mir zbog prolaznih materijalnih gubitaka; čuvajte blago duše koje lopovi ne kradu.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Duhovni saveti'
    ],
    211 => [
        'text' => 'Hristos je Sunce pravde Koji osvetljava svaki kutak našeg unutrašnjeg bića.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Besede'
    ],
    212 => [
        'text' => 'Neka vaša dela govore glasnije od vaših reči.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],

    // 213 - 243 (Avgust)
    213 => [
        'text' => 'Umerenost u svemu donosi zdravlje telu i spokojstvo duši.',
        'author' => 'Sveti Vasilije Veliki',
        'source' => 'Duhovna pravila'
    ],
    214 => [
        'text' => 'Preobraženje Gospodnje nas poziva da i mi preobrazimo svoj život i obasjamo ga svetlošću vrlina.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Praznične besede'
    ],
    215 => [
        'text' => 'Kada se čovek preobrazi iznutra kroz pokajanje, ceo svet oko njega dobija novu, svetliju lepotu.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    216 => [
        'text' => 'Svetlost Tavorska svedoči nam da je Bog dostupan čistom srcu u molitvenom tihovanju.',
        'author' => 'Sveti Grigorije Palama',
        'source' => 'Trijade'
    ],
    217 => [
        'text' => 'Na Preobraženje se i priroda i čovek oblače u novo ruho blagodati i zahvalnosti Bogu.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Preobraženjska beseda'
    ],
    218 => [
        'text' => 'Svaka lepa reč i iskren osmeh mogu biti zrak nade onome ko se nalazi u tami malodušnosti.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misli o dobru i zlu'
    ],
    219 => [
        'text' => 'Nemoj se bojati sutrašnjice; onaj isti Bog Koji te čuvao danas, vodiće te i sutra.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Duhovni saveti'
    ],
    220 => [
        'text' => 'Prava vera se prepoznaje po smirenju, blagosti i spremnosti na praštanje.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko pismo'
    ],
    221 => [
        'text' => 'Presveta Bogorodica je naša najveća zastupnica i majka koja nikada ne ostavlja one koji joj se mole.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Besede na Uspenje'
    ],
    222 => [
        'text' => 'Uspenje Presvete Bogorodice nas uči da je smrt za pravednike samo prelazak u večni život.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Beseda na Veliku Gospojinu'
    ],
    223 => [
        'text' => 'Presveta Bogomajka svojim svetim molitvama štiti pravoslavne manastire i srpske domove.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Omilije'
    ],
    224 => [
        'text' => 'Gde je Presveta Bogorodica sa nama, tu je svaka tuga ublažena i svaka rana isceljena.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    225 => [
        'text' => 'Samo ono što je sazidano na veri i dobroti ima čvrst temelj koji vreme ne može srušiti.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],
    226 => [
        'text' => 'Čovek je biće stvoreno za večnost, a ne samo za kratak prolazak ovom zemljom.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Filosofske urvine'
    ],
    227 => [
        'text' => 'U srcu koje prašta nema mesta za gnev, zavist i nemir.',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'Besede'
    ],
    228 => [
        'text' => 'Neka tvoj jezik govori samo ono što donosi korist i izgrađuje dušu bližnjeg.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Razgovori'
    ],
    229 => [
        'text' => 'Duhovna radost je najtiša i najdublja; ona ne zavisi od spoljašnjih okolnosti.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Kakve su ti misli, takav ti je život'
    ],
    230 => [
        'text' => 'U manastirskoj tišini čovek jasnije čuje glas svoje savesti i reč Božiju.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Zapisi'
    ],
    231 => [
        'text' => 'Kada ti neko učini zlo, ti mu uzvrati molitvom; to je jedini način da pobediš zlo u korenu.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],
    232 => [
        'text' => 'Bog je uvek veran Svojim obećanjima; samo se mi trudimo da budemo verni Njemu.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Besede'
    ],
    233 => [
        'text' => 'Trpljenje u nevoljama je kao brušenje dragog kamena: čini dušu sjajnom i čistom.',
        'author' => 'Sveti Isak Sirin',
        'source' => 'Podvižnička slova'
    ],
    234 => [
        'text' => 'Kada se probudiš, prvo zahvali Gospodu što ti je podario još jedan dan da činiš dobro.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    235 => [
        'text' => 'Ko u srcu nosi mir, taj nikada ne izaziva sukobe i razdore među ljudima.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko pismo'
    ],
    236 => [
        'text' => 'Ljubav je jedini jezik koji svi ljudi na svetu mogu razumeti bez tumača.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misli o dobru i zlu'
    ],
    237 => [
        'text' => 'Usekovanje glave Svetog Jovana Krstitelja uči nas da se istina ne može ućutkati mačem.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Praznično slovo'
    ],
    238 => [
        'text' => 'Sveti Jovan Krstitelj je najveći među rođenima od žena, svetilnik pokajanja i nepokolebljivi glas istine.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Praznične besede'
    ],
    239 => [
        'text' => 'Pokajte se, jer se približilo Carstvo Nebesko – to je večni poziv na obnovu naše duše.',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'Besede'
    ],
    240 => [
        'text' => 'Nikada nemoj gubiti veru u moć pokajanja; Bog prima svakog ko Mu se iskreno vrati.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    241 => [
        'text' => 'Gospod nas poziva da budemo mudri kao zmije, a bezazleni kao golubovi.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko slovo'
    ],
    242 => [
        'text' => 'Svaki trud uložen u sticanje vrlina biće stostruko nagrađen u Carstvu Božijem.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Omilije'
    ],
    243 => [
        'text' => 'Čisto srce je najlepši hram u kome prebiva sam Bog.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Zapisi'
    ],

    // 244 - 273 (Septembar)
    244 => [
        'text' => 'Crkvena Nova godina nas podseća na prolaznost vremena i potrebu za stalnim duhovnim uzrastanjem.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko pismo'
    ],
    245 => [
        'text' => 'Svaki dan je prilika da posejemo dobro delo i ostavimo svetao trag za sobom.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misionarska pisma'
    ],
    246 => [
        'text' => 'Duhovni mir donosi zdravlje i telu i duši; sačuvajmo ga kroz smirenu molitvu.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Kakve su ti misli, takav ti je život'
    ],
    247 => [
        'text' => 'Rođenje Presvete Bogorodice (Mala Gospojina) označava početak spasenja celog ljudskog roda.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Praznične besede'
    ],
    248 => [
        'text' => 'Kroz Presvetu Bogorodicu sišao je Bog na zemlju, da bi čoveka uzdigao na nebo.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Beseda na Malu Gospojinu'
    ],
    249 => [
        'text' => 'Presveta Bogomajka je najsvetliji primer devičanske čistote, smirenja i poslušnosti Božijoj volji.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Omilije'
    ],
    250 => [
        'text' => 'Kada god osetiš nemoć, prizovi ime Božije sa verom i dobićeš novu snagu.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    251 => [
        'text' => 'Nemojmo se stideti da činimo dobro, ma koliko svet oko nas bio ravnodušan.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Razgovori'
    ],
    252 => [
        'text' => 'Krstovdan nas podseća na silu Časnoga Krsta: Krst je čuvar vaseljene i lepota Crkve.',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'Slovo o Krstu'
    ],
    253 => [
        'text' => 'Krstu Tvome klanjamo se, Vladiko, i sveto Vaskrsenje Tvoje slavimo.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Krstovdanska beseda'
    ],
    254 => [
        'text' => 'Klanjajući se Krstu Hristovom, mi se klanjamo bezmernoj ljubavi Božijoj prema čoveku.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Besede na Krstovdan'
    ],
    255 => [
        'text' => 'Ko sa strpljenjem nosi svoj životni krst, taj ide pravo za Hristom.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Molitve na jezeru'
    ],
    256 => [
        'text' => 'Nema vaskrsenja bez krsnog stradanja; trpljenje priprema dušu za večnu slavu.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    257 => [
        'text' => 'Mirno podnošenje nevolja i uvreda jeste najbrži put ka duhovnom očišćenju.',
        'author' => 'Sveti Isak Sirin',
        'source' => 'Podvižnička slova'
    ],
    258 => [
        'text' => 'Čuvajmo svoje srpske pravoslavne manastire, jer oni su živa srca našeg naroda.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko pismo'
    ],
    259 => [
        'text' => 'U manastirima Srbije utkana je vekovna molitva naših svetih predaka.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Duhovni spisi'
    ],
    260 => [
        'text' => 'Sveta tajna pokajanja i pričešća jeste izvor obnove i besmrtnosti za svakog hrišćanina.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Dogmatika'
    ],
    261 => [
        'text' => 'Dobar savet zlata vredi, ali lični primer vredi hiljadu reči.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],
    262 => [
        'text' => 'Kada te obuzme gnev, zaćuti i pomoli se; ne dozvoli da plamen zlobe spali tvoj duhovni mir.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Duhovni saveti'
    ],
    263 => [
        'text' => 'Vrlina milosrđa čini čoveka sličnim Bogu Koji je prebogat u milosti.',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'Besede'
    ],
    264 => [
        'text' => 'Budimo postojani u veri, čvrsti u nadi i neumorni u činjenju dobrih dela.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko slovo'
    ],
    265 => [
        'text' => 'Vera u vaskrslog Gospoda briše svaki strah od prolaznosti i smrti.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Besede'
    ],
    266 => [
        'text' => 'Smiren čovek nikada ne sudi drugima; on u svakom čoveku prepoznaje brata u Hristu.',
        'author' => 'Sveti Siluan Atonski',
        'source' => 'Zapisi o smirenju'
    ],
    267 => [
        'text' => 'Prava lepota čoveka jeste unutrašnja lepota njegove oblagodaćene duše.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misli o dobru i zlu'
    ],
    268 => [
        'text' => 'U porodici se uči prva škola ljubavi, praštanja i međusobnog poštovanja.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke o braku'
    ],
    269 => [
        'text' => 'Misli su kao ptice: ne možeš im zabraniti da lete iznad tvoje glave, ali im možeš zabraniti da sviju gnezdo u tvom srcu.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    270 => [
        'text' => 'Gospod traži od nas da budemo verni u malome, a On će nam dati ono što je večno i neprolazno.',
        'author' => 'Sveti Vasilije Veliki',
        'source' => 'Pouke'
    ],
    271 => [
        'text' => 'Neka vam svaki dan započne i završi se rečima: Slava Bogu za sve!',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'Pisma'
    ],
    272 => [
        'text' => 'Ko u srcu nosi Hrista, taj sija svetlošću koja greje i teši sve oko njega.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Duhovni zapisi'
    ],
    273 => [
        'text' => 'Nema prepreke koju iskrena vera i dobra volja ne mogu savladati uz pomoć Božiju.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],

    // 274 - 304 (Oktobar)
    274 => [
        'text' => 'Pokrov Presvete Bogorodice je nevidljivi nebeski štit nad svim vernim dušama.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Praznične besede'
    ],
    275 => [
        'text' => 'Presveta Bogomajka svojim svetim omoforom zaklanja sve one koji traže njeno zastupništvo pred prestolom Sina njenog.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Beseda na Pokrov'
    ],
    276 => [
        'text' => 'Pod Pokrovom Bogorodice nalazimo utočište u svim životnim olujama i nevoljama.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Molitve'
    ],
    277 => [
        'text' => 'Kada se moliš Presvetoj Bogorodici, moli se kao dete svojoj rođenoj majci, sa punim poverenjem.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    278 => [
        'text' => 'Čuvajmo svoje duhovne korene; narod koji zaboravi svoje svetinje gubi svoj put.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko slovo'
    ],
    279 => [
        'text' => 'Vrlina bez smirenja je kao svetlost bez toplote – ne može nikoga ogrejati.',
        'author' => 'Sveti Isak Sirin',
        'source' => 'Podvižnička slova'
    ],
    280 => [
        'text' => 'Duhovna radost je najslađi plod iskrene molitve i čistog života.',
        'author' => 'Sveti Serafim Sarovski',
        'source' => 'Pouke'
    ],
    281 => [
        'text' => 'Nikada ne odgovaraj zlom na zlo; dobrota je jedini lek koji pobeđuje neprijateljstvo.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Besede'
    ],
    282 => [
        'text' => 'U svakoj nevolji ponavljaj u sebi: I ovo će proći, a milost Božija ostaje večno.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Duhovni saveti'
    ],
    283 => [
        'text' => 'Hristos je večni smisao čovekovog postojanja na zemlji i u večnosti.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Dogmatika'
    ],
    284 => [
        'text' => 'Bogat je onaj koji je zadovoljan onim što ima i koji rado deli sa siromasima.',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'Besede'
    ],
    285 => [
        'text' => 'Budimo skromni u svojim zahtevima prema životu, a bogati u činjenju dobrih dela.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],
    286 => [
        'text' => 'Gospod je pastir moj, i ničega mi neće nedostajati – neka to bude uzdanje našeg srca.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Molitve na jezeru'
    ],
    287 => [
        'text' => 'Dobar primer roditelja u veri i poštenju jeste najdragocenije nasledstvo koje mogu ostaviti deci.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke o vaspitanju'
    ],
    288 => [
        'text' => 'Mir u duši donosi bistrinu uma i snagu telu za svaki dobar rad.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    289 => [
        'text' => 'Sveta Petka (Prepodobna mati Paraskeva) je primer anđelskog života u telu i uzor podviga u pustinji.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Beseda na Svetu Petku'
    ],
    290 => [
        'text' => 'Sveta Petka svojim molitvama čuva naš narod i isceljuje sve koji joj sa suzama i verom pribegavaju.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Praznične besede'
    ],
    291 => [
        'text' => 'Molitveno zastupništvo Svete Petke vekovima je bilo utočište i snaga srpskih žena i majki.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Sveti srpski rod'
    ],
    292 => [
        'text' => 'Neka primer čistote i podviga Svete Petke obasja naše porodice i unese mir u naša srca.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    293 => [
        'text' => 'Sveti Petar Cetinjski nas uči: U mirenju i bratskoj slozi je spasenje i snaga naroda.',
        'author' => 'Sveti Petar Cetinjski',
        'source' => 'Poslanice'
    ],
    294 => [
        'text' => 'Sveti Petar Cetinjski je ceo svoj život proveo mireći zavađenu braću; budimo i mi mirotvorci.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Beseda na Lučindan'
    ],
    295 => [
        'text' => 'Sveti apostol Luka nam je ostavio sveto Jevanđelje i prve ikone Bogomajke, darujući nam nebesku lepotu.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Omilije'
    ],
    296 => [
        'text' => 'Čuvajmo mir među sobom, jer u miru se proslavlja ime Božije.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],
    297 => [
        'text' => 'Kada te obuzme briga za budućnost, seti se da je Gospod već tamo i da On sve mudro vodi.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Saveti'
    ],
    298 => [
        'text' => 'Pravoslavna vera je vera ljubavi, svetlosti i nepobedivog vaskrsenja.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Zapisi'
    ],
    299 => [
        'text' => 'Milosrđe je kapija kroz koju se ulazi u Carstvo Nebesko.',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'Besede'
    ],
    300 => [
        'text' => 'Neka svaka naša misao, reč i delo budu u slavu Božiju i na korist bližnjima.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko slovo'
    ],
    301 => [
        'text' => 'U smirenju se krije neprolazna snaga koju nikakvo zemaljsko zlo ne može slomiti.',
        'author' => 'Sveti Siluan Atonski',
        'source' => 'Zapisi'
    ],
    302 => [
        'text' => 'Bog voli trudoljubivu i zahvalnu dušu.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    303 => [
        'text' => 'Vera koja se ne svedoči delima ljubavi je samo privid vere.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misli o dobru i zlu'
    ],
    304 => [
        'text' => 'Neka vas Gospod blagoslovi duhovnim mirom, zdravljem i svakim dobrim delom.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirski blagoslov'
    ],

    // 305 - 334 (Novembar)
    305 => [
        'text' => 'Sveti besrebrenici Kozma i Damjan podsećaju nas na nesebično služenje bolesnima i nevoljnima.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Beseda na Vračeve'
    ],
    306 => [
        'text' => 'Darom ste dobili, darom i dajte – neka to bude pravilo našeg odnosa prema darovima Božijim.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Omilije'
    ],
    307 => [
        'text' => 'Smiren čovek se nikada ne uznemirava zbog ljudskih pohvala ili pokuda.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    308 => [
        'text' => 'Sveti velikomučenik Dimitrije Solunski svedoči nepobedivu snagu vere u Hrista pred moćnicima ovoga sveta.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Praznične besede'
    ],
    309 => [
        'text' => 'Mitrovdan nas podseća na hrabrost hrišćanskih mučenika koji su dali život za večnu Istinu.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Beseda na Mitrovdan'
    ],
    310 => [
        'text' => 'Krv mučenika je seme novih hrišćana i temelj nepokolebljive Crkve Božije.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misionarska pisma'
    ],
    311 => [
        'text' => 'U tišini svoga srca zahvali Bogu za sve što ti je dao i za sve što je od tebe uklonio radi tvoga dobra.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Saveti'
    ],
    312 => [
        'text' => 'Sveti kralj Milutin, ktitor preko 40 svetinja, svedoči kako se zemaljsko bogatstvo pretače u neprolaznu lepotu zadužbina.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Beseda o zadužbinarima'
    ],
    313 => [
        'text' => 'Zadužbinarstvo naših svetih kraljeva i despota ostavilo nam je večnu tapiju na srpsku duhovnost i kulturu.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Srpski narod kao Teodul'
    ],
    314 => [
        'text' => 'Sveti Jovan Zlatousti je zvezda pravoslavne mudrosti i nepresušni izvor jevanđelske rečitosti.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Praznične besede'
    ],
    315 => [
        'text' => 'Nema greha koji prevazilazi Božije milosrđe, ako se čovek iskreno i sa smirenjem pokaje.',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'Besede o pokajanju'
    ],
    316 => [
        'text' => 'Patrijarh Pavle je svojim skromnim životom bio živa ikona jevanđelskog smirenja i pastirske ljubavi.',
        'author' => 'Mitropolit Amfilohije Radović',
        'source' => 'Slovo o Patrijarhu Pavlu'
    ],
    317 => [
        'text' => 'Čuvajmo jedinstvo duhovno u svezi mira i ljubavi Hristove.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Poslednje pastirske poruke'
    ],
    318 => [
        'text' => 'Bog je tamo gde je mir, dobrota, krotost i međusobno praštanje.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    319 => [
        'text' => 'Sabor Svetog Arhangela Mihaila (Aranđelovdan) slavi pobedu nebeskih sila nad tamom i gordošću.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Praznične besede'
    ],
    320 => [
        'text' => 'Sveti Arhangel Mihailo nas podseća na vernost Bogu sa pokličem: Pazimo, stojmo smireno pred Tvorcem!',
        'author' => 'Patrijarh Pavle',
        'source' => 'Beseda na Aranđelovdan'
    ],
    321 => [
        'text' => 'Anđeli čuvari neprestano stoje uz nas i vode nas ka dobru; slušajmo glas svoje probuđene savesti.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Omilije'
    ],
    322 => [
        'text' => 'Sveti kralj Stefan Dečanski svedoči da se krotkošću i trpljenjem pobeđuju i najveća životna stradanja.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Beseda u Dečanima'
    ],
    323 => [
        'text' => 'Visoki Dečani su čuvari svetog mira i svedočanstvo nebeske lepote u srcu Metohije.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Zapisi o Dečanima'
    ],
    324 => [
        'text' => 'Sveti Jovan Zlatousti nas uči da je zahvalnost Bogu najjača zaštita u svim nevoljama.',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'Besede'
    ],
    325 => [
        'text' => 'Božićni post je vreme duhovne pripreme, tišine, milosrđa i dočekivanja Bogomladenca Hrista.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke o Božićnom postu'
    ],
    326 => [
        'text' => 'Posti telom od mrsne hrane, ali još više dušom od zlih misli, osuda i praznoslovlja.',
        'author' => 'Sveti Vasilije Veliki',
        'source' => 'Slovo o postu'
    ],
    327 => [
        'text' => 'Kada posti celo tvoje biće, tada se u tvoje srce useljava tiha nebeska radost.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Duhovni saveti'
    ],
    328 => [
        'text' => 'Neka vam Božićni post donese očišćenje uma, mir u srcu i snagu za činjenje dobrih dela.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Poslanica'
    ],
    329 => [
        'text' => 'Sveti apostol Andrej Prvozvani je bez oklevanja pošao za Hristom; pođimo i mi putem Njegovih zapovesti.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Omilije'
    ],
    330 => [
        'text' => 'Milosrđe učinjeno siromahu otvara vrata nebeske riznice i donosi večni blagoslov.',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'Besede'
    ],
    331 => [
        'text' => 'Kada se moliš za drugoga, tvoja molitva donosi blagoslov i njemu i tebi.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    332 => [
        'text' => 'Čuvajmo čistotu vere pravoslavne kao najveće blago koje smo primili od svojih svetih predaka.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko slovo'
    ],
    333 => [
        'text' => 'Hristos je svetlost sveta; ko u Njemu hodi, nikada se neće spotaknuti.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Duhovni spisi'
    ],
    334 => [
        'text' => 'Sve što radiš, radi sa ljubavlju, smirenjem i čistom namerom pred Bogom.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misli o dobru i zlu'
    ],

    // 335 - 366 (Decembar)
    335 => [
        'text' => 'Vavedenje Presvete Bogorodice u hram uči nas da i naša srca treba da postanu hramovi Duha Svetoga.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Praznične besede'
    ],
    336 => [
        'text' => 'Presveta Deva Marija je ušla u Svetinju nad svetinjama da bi pripremila sebe za dom Majke Božije.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Beseda na Vavedenje'
    ],
    337 => [
        'text' => 'U hramu Božijem čovek pronalazi istinsku tišinu i utehu za sve svoje zemaljske brige.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Omilije'
    ],
    338 => [
        'text' => 'Kada uđeš u pravoslavni hram, ostavi sve brige ispred vrata i predaj svoje srce molitvi.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    339 => [
        'text' => 'Sveti Kliment Ohridski i učenici svetih Ćirila i Metodija prosvetlili su slovenski rod jevanđelskom mudrošću.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Ohridski prolog'
    ],
    340 => [
        'text' => 'Čuvajmo svoje pismo, svoj jezik i svoju veru, jer su oni stubovi našeg duhovnog opstanka.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Razgovori'
    ],
    341 => [
        'text' => 'Sveti Alimpije Stolpnik podseća nas na snagu nepokolebljivog podviga i neprestane molitve Bogu.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Žitija Svetih'
    ],
    342 => [
        'text' => 'Smirenje je most koji spaja zemlju i nebo; preko tog mosta prolaze samo oni čistog srca.',
        'author' => 'Sveti Isak Sirin',
        'source' => 'Podvižnička slova'
    ],
    343 => [
        'text' => 'Učini danas bar jedno malo dobro delo u tajnosti i videćeš kako će ti duša zablistati radošću.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],
    344 => [
        'text' => 'Duhovni mir je najveće blago: ko njega ima, ima sve, a ko njega nema, siromašan je i u dvoru.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Kakve su ti misli, takav ti je život'
    ],
    345 => [
        'text' => 'Sveti Andrej Prvozvani nam poručuje: Našli smo Mesiju! Neka i naša duša u Hristu pronađe svoj večni mir.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Omilije'
    ],
    346 => [
        'text' => 'Sveta velikomučenica Varvara svedoči o neugasivoj veri devojke koja je Hrista stavila iznad svega zemaljskog.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Praznične besede'
    ],
    347 => [
        'text' => 'Sveti Sava Osvećeni, osnivač slavne Lavre u Judejskoj pustinji, ostavio nam je primer monaškog pravila i bdenja.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke'
    ],
    348 => [
        'text' => 'Sveti Nikolaj Čudotvorac Mirlikijski (Nikoljdan) je pravilo vere, slika krotosti i učitelj uzdržanja.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Beseda o Svetom Nikoli'
    ],
    349 => [
        'text' => 'Sveti Nikola je tajno davao milostinju, učeći nas da dobra dela činimo bez želje za ljudskom hvalom.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Nikoljdanska beseda'
    ],
    350 => [
        'text' => 'Neka sveti zaštitnik naših domova, Sveti Nikola, izmoli mir, slogu i zdravlje svakoj pravoslavnoj porodici.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Besede'
    ],
    351 => [
        'text' => 'Kada slaviš krsnu slavu, slavi je dostojanstveno, u molitvi, smirenju i bratskoj ljubavi.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke o slavi'
    ],
    352 => [
        'text' => 'Sveti Nikola je brzi pomoćnik mornarima i putnicima, a još više onima koji plove burnim morem ovozemaljskog života.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Misionarska pisma'
    ],
    353 => [
        'text' => 'Sveti Spiridon Trimituntski Čudotvorac svedoči da prosto i smireno srce čini najveća čuda u veri.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Praznične besede'
    ],
    354 => [
        'text' => 'Sveti Spiridon svojom jednostavnošću i smirenjem posramio je učene filozofe na Prvom vaseljenskom saboru.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Besede'
    ],
    355 => [
        'text' => 'U molitvi traži pre svega očišćenje srca i Carstvo Božije, a sve ostalo će ti se dodati.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    356 => [
        'text' => 'Detinjci, Materice i Oci podsećaju nas na svetinju porodice i neraskidive veze hrišćanske ljubavi.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pouke o porodici'
    ],
    357 => [
        'text' => 'Kada roditelji vezuju i dreše decu u ljubavi, oni ih vezuju za Hrista i nebesko Carstvo.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Duhovna lira'
    ],
    358 => [
        'text' => 'Sveti sveštenomučenik Ignjatije Bogonosac nosio je Hrista u srcu i rado položio život za Njega.',
        'author' => 'Prepodobni Justin Ćelijski',
        'source' => 'Praznične besede'
    ],
    359 => [
        'text' => 'Pšenica sam Božija i neka me zubi zverova samelju da postanem čist hleb Hristov – reči su Svetog Ignjatija Bogonosca.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Beseda na Ignjatijevdan'
    ],
    360 => [
        'text' => 'Neka i naše srce nosi Boga u sebi kroz molitvu, smirenje i čistotu pomisli.',
        'author' => 'Starac Tadej Vitovnički',
        'source' => 'Pouke'
    ],
    361 => [
        'text' => 'Sveti Danilo Drugi, arhiepiskop i zadužbinar, ostavio nam je slavna Žitija kraljeva i arhiepiskopa srpskih.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Srpski svetitelji'
    ],
    362 => [
        'text' => 'Pripremimo svoja srca kao vitlejemske jasle da u njih smireno primimo Bogomladenca Hrista.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Predbožićna beseda'
    ],
    363 => [
        'text' => 'Gospod dolazi u svet u tišini pećine, učeći nas da je pravo veličanstvo u skromnosti i smirenju.',
        'author' => 'Sveti Jovan Zlatousti',
        'source' => 'Besede na Rođenje Hristovo'
    ],
    364 => [
        'text' => 'Neka plamen badnjaka sagori svaku zlu misao i razdor, a unese mir, zdravlje i ljubav u svaki dom.',
        'author' => 'Sveti vladika Nikolaj Velimirović',
        'source' => 'Badnjačke misli'
    ],
    365 => [
        'text' => 'Zahvalimo Gospodu za godinu koja prolazi i zamolimo Ga za mir, blagoslov i snagu u godini koja dolazi.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Pastirsko slovo'
    ],
    366 => [
        'text' => 'Mir Božiji, Hristos se rodi! Budimo ljudi uvek i na svakom mestu, slaveći Boga Koji nas neizmerno voli.',
        'author' => 'Patrijarh Pavle',
        'source' => 'Božićna poslanica'
    ],
];

echo "Pripremljeno " . count($quotesData) . " unikatnih citata za sve dane u godini (1..366).\n\n";

// 1. Zapisivanje u JSON fajlove za konzistentnost i trajno čuvanje
$jsonStorageApp = __DIR__ . '/storage/app/seed/quotes.json';
$jsonStoragePriv = __DIR__ . '/storage/app/private/seed/quotes.json';

@mkdir(dirname($jsonStorageApp), 0777, true);
@mkdir(dirname($jsonStoragePriv), 0777, true);

$jsonArray = [];
foreach ($quotesData as $day => $q) {
    $jsonArray[] = [
        'day_of_year' => $day,
        'text'        => $q['text'],
        'author'      => $q['author'],
        'source'      => $q['source'] ?? null,
        'is_active'   => true,
        'weight'      => 1,
    ];
}

$jsonEncoded = json_encode($jsonArray, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
file_put_contents($jsonStorageApp, $jsonEncoded);
file_put_contents($jsonStoragePriv, $jsonEncoded);
echo "Zapisano u storage JSON fajlove: {$jsonStorageApp} i {$jsonStoragePriv}\n\n";

// 2. Ažuriranje primarne baze (database/database.sqlite)
echo "1. Sinhronizacija primarne baze podataka (database/database.sqlite)...\n";
DB::connection()->table('quotes')->truncate();

$primaryCount = 0;
foreach ($quotesData as $day => $q) {
    DB::connection()->table('quotes')->insert([
        'day_of_year' => $day,
        'text'        => $q['text'],
        'author'      => $q['author'],
        'source'      => $q['source'] ?? null,
        'is_active'   => 1,
        'weight'      => 1,
        'created_at'  => now(),
        'updated_at'  => now(),
    ]);
    $primaryCount++;
}
echo "  [USPEH] U primarnu bazu ubačeno: {$primaryCount} citata!\n\n";

// 3. Ažuriranje storage baze (storage/database.sqlite)
$storageDbPath = __DIR__ . '/storage/database.sqlite';
if (file_exists($storageDbPath)) {
    echo "2. Sinhronizacija storage baze podataka ({$storageDbPath})...\n";
    $pdo = new PDO("sqlite:" . $storageDbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Provera i brisanje stare tabele quotes
    $pdo->exec("DELETE FROM quotes");

    $stmt = $pdo->prepare("
        INSERT INTO quotes (day_of_year, text, author, source, is_active, weight, created_at, updated_at)
        VALUES (:day_of_year, :text, :author, :source, :is_active, :weight, :created_at, :updated_at)
    ");

    $nowStr = date('Y-m-d H:i:s');
    $storageCount = 0;
    foreach ($quotesData as $day => $q) {
        $stmt->execute([
            ':day_of_year' => $day,
            ':text'        => $q['text'],
            ':author'      => $q['author'],
            ':source'      => $q['source'] ?? null,
            ':is_active'   => 1,
            ':weight'      => 1,
            ':created_at'  => $nowStr,
            ':updated_at'  => $nowStr,
        ]);
        $storageCount++;
    }
    echo "  [USPEH] U storage bazu ubačeno: {$storageCount} citata!\n\n";
}

echo "====================================================================\n";
echo "USPEŠNO ZAVRŠENA SINHRONIZACIJA CITATA DANA ZA 366 DANA U GODINI!\n";
echo "====================================================================\n";
