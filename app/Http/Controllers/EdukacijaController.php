<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AiController as ApiAiController;

class EdukacijaController extends Controller
{
    public function index()
    {
        return view('pages.pravoslavni.modules.edukacija.index');
    }

    public function show($slug)
    {
$map = [
    'istorija-kultura'         => 'istorija-kultura',
    'srpska-crkva'             => 'srpska-crkva',
    'arhitektura-umetnost'     => 'arhitektura-umetnost',
    'ucenje-interakcija'       => 'ucenje-interakcija',
    'porodicno-stablo'         => 'porodicno-stablo',
    'manastiri-kao-zaduzbine'  => 'manastiri-kao-zaduzbine',
    'srbija-pod-osmanlijama'   => 'srbija-pod-osmanlijama',

    // aliasi
    'interakcija'              => 'ucenje-interakcija',
    'istorija'                 => 'istorija-kultura',
    'crkva'                    => 'srpska-crkva',
    'sveti-sava'               => 'srpska-crkva',
    'umetnost'                 => 'arhitektura-umetnost',
    'manastiri'                => 'manastiri-kao-zaduzbine',
    'zaduzbine'                => 'manastiri-kao-zaduzbine',
    'osmanlije'                => 'srbija-pod-osmanlijama',
];

        $viewSlug = $map[$slug] ?? null;

        if (!$viewSlug) {
            abort(404);
        }

        return view("pages.pravoslavni.modules.edukacija.$viewSlug");
    }

    /* ---------------- POSEBNE STRANICE ---------------- */

    public function ucenjeInterakcija()
    {
        return view('pages.pravoslavni.modules.edukacija.ucenje-interakcija');
    }

    public function porodicnoStablo()
    {
        return view('pages.pravoslavni.modules.edukacija.porodicno-stablo');
    }

    /* ---------------- TIMELINE ---------------- */

    public function timeline()
    {
        $timelines = [
            'nemanjici' => [
                [
                    'year' => '1113/1114',
                    'title' => 'Rođenje Stefana Nemanje',
                    'text' => 'Rađa se Stefan Nemanja, rodonačelnik najznačajnije srpske srednjovekovne dinastije.',
                    'tag' => 'ličnost',
                    'context' => 'Stefan Nemanja smatra se rodonačelnikom dinastije Nemanjić, koja je obeležila najznačajniji period razvoja srednjovekovne srpske države. Njegova pojava predstavlja početak političkog i duhovnog uspona Srbije. U istoriji je ostao upamćen kao vladar koji je učvrstio državu i povezao vlast sa snažnim duhovnim nasleđem.',
                ],
                [
                    'year' => '1166',
                    'title' => 'Uspon Stefana Nemanje',
                    'text' => 'Nemanja učvršćuje vlast i postavlja temelje snažne srednjovekovne države, uz jačanje političkog i duhovnog jedinstva.',
                    'tag' => 'država',
                    'context' => 'Godine 1166. Stefan Nemanja preuzima vlast kao veliki župan i postepeno učvršćuje položaj Raške među srpskim zemljama. Njegova vladavina označila je početak snažnijeg objedinjavanja političke vlasti, kao i jačanja uloge države u odnosima sa susedima. U ovom periodu nastaju temelji srpske srednjovekovne državnosti u punijem smislu.',
                ],
                [
                    'year' => '1186',
                    'title' => 'Mirovni ugovor sa Dubrovnikom',
                    'text' => 'Srbija jača svoj međunarodni položaj i razvija političke i trgovačke veze sa susednim oblastima.',
                    'tag' => 'diplomatija',
                    'context' => 'Mirovni i trgovački odnosi sa Dubrovnikom imali su veliki značaj za jačanje međunarodnog položaja srpske države. Takvi sporazumi nisu bili važni samo zbog politike, već i zbog razvoja trgovine, privrede i razmene sa jadranskim gradovima. Oni pokazuju da je Srbija u vreme Nemanje sve sigurnije nastupala kao uređena politička celina.',
                ],
                [
                    'year' => '1196',
                    'title' => 'Povlačenje Stefana Nemanje',
                    'text' => 'Nemanja se odriče prestola, zamonašuje i ostavlja snažno duhovno nasleđe svom narodu i porodici.',
                    'tag' => 'duhovnost',
                    'context' => 'Stefan Nemanja se 1196. godine odriče vlasti i povlači se u monaški život, što predstavlja važan trenutak i u političkom i u duhovnom smislu. Nakon odricanja od prestola prima monaški čin i uzima ime Simeon. Ovaj čin ostavio je snažan utisak na srpsku tradiciju, jer pokazuje spoj vladarske odgovornosti i duhovnog opredeljenja.',
                ],
                [
                    'year' => '1198',
                    'title' => 'Osnivanje Hilandara',
                    'text' => 'Stefan Nemanja i Sveti Sava učestvuju u osnivanju Hilandara, jednog od najvažnijih duhovnih centara srpskog naroda.',
                    'tag' => 'zadužbine',
                    'context' => 'Manastir Hilandar, osnovan na Svetoj Gori, postao je jedno od najvažnijih duhovnih i kulturnih središta srpskog naroda. U njegovom osnivanju učestvovali su monah Simeon, nekadašnji Stefan Nemanja, i njegov sin Sveti Sava. Hilandar je vekovima bio mesto čuvanja pismenosti, bogoslužbenog života i srpskog identiteta.',
                ],
                [
                    'year' => '1217',
                    'title' => 'Krunisanje Stefana Prvovenčanog',
                    'text' => 'Stefan Prvovenčani postaje prvi krunisani srpski kralj, čime država dobija dodatno međunarodno priznanje i ugled.',
                    'tag' => 'država',
                    'context' => 'Krunisanje Stefana Prvovenčanog 1217. godine označilo je veliko jačanje međunarodnog položaja Srbije. Time je srpska država dobila kraljevski rang, a vladarska vlast dodatni legitimitet u evropskom političkom prostoru. Ovaj događaj bio je važan korak u učvršćivanju državne samostalnosti i ugleda.',
                ],
                [
                    'year' => '1219',
                    'title' => 'Autokefalnost i učvršćenje države',
                    'text' => 'Uz crkvenu samostalnost koju dobija Sveti Sava, dodatno se učvršćuje i politički identitet srpske države.',
                    'tag' => 'crkva',
                    'context' => 'Godine 1219. Sveti Sava dobija autokefalnost za Srpsku crkvu i postaje njen prvi arhiepiskop. Ovaj događaj nije imao samo crkveni značaj, već i veliki državni značaj, jer je učvrstio identitet Srbije kao samostalne političke i duhovne zajednice. Autokefalnost je omogućila da se crkveni život uređuje iznutra, u skladu sa potrebama srpskog naroda i države.',
                ],
                [
                    'year' => '1221',
                    'title' => 'Žički sabor',
                    'text' => 'Na saboru u Žiči dodatno se uređuje crkveni i državni poredak, a vera dobija središnje mesto u društvu.',
                    'tag' => 'crkva',
                    'context' => 'Žički sabor ima veliki značaj za učvršćivanje crkvenog i državnog poretka u Srbiji. U manastiru Žiči, jednom od glavnih središta tog vremena, dodatno su oblikovani odnosi između države i Crkve. Ovaj događaj pokazuje koliko su politički i duhovni život bili tesno povezani u epohi Nemanjića.',
                ],
                [
                    'year' => '1234',
                    'title' => 'Prenos moštiju Svetog Save u Mileševu',
                    'text' => 'Mošti Svetog Save bivaju prenete u Mileševu, koja postaje jedno od glavnih mesta duhovnog pamćenja.',
                    'tag' => 'duhovnost',
                    'context' => 'Prenos moštiju Svetog Save u manastir Mileševu imao je ogroman duhovni i simbolički značaj. Mileševa je time postala jedno od najvažnijih mesta srpskog pamćenja, molitve i identiteta. Poštovanje Svetog Save kroz njegove mošti vekovima je učvršćivalo narodnu i crkvenu svest.',
                ],
                [
                    'year' => '1243',
                    'title' => 'Početak vladavine Stefana Uroša I',
                    'text' => 'Država se stabilizuje, razvijaju se privreda, rudarstvo i međunarodni odnosi.',
                    'tag' => 'država',
                    'context' => 'Dolaskom Stefana Uroša I na vlast počinje period unutrašnje stabilizacije i privrednog jačanja države. Posebno se razvijaju rudarstvo, trgovina i odnosi sa susednim zemljama. Uroš I je važan jer pokazuje kako srednjovekovna Srbija nije napredovala samo vojno, već i ekonomski i organizaciono.',
                ],
                [
                    'year' => '1276',
                    'title' => 'Dolazak Stefana Dragutina na vlast',
                    'text' => 'Promena na prestolu otvara novo poglavlje dinastičkih odnosa i borbi za vlast.',
                    'tag' => 'dinastija',
                    'context' => 'Promena vlasti 1276. godine i dolazak Stefana Dragutina na presto predstavljaju novo poglavlje u dinastičkim odnosima među Nemanjićima. Ovaj period obeležili su i odnosi među članovima vladajuće porodice, kao i borbe za politički uticaj. Takve promene uticale su na stabilnost i razvoj države.',
                ],
                [
                    'year' => '1282',
                    'title' => 'Početak vladavine kralja Milutina',
                    'text' => 'Milutinova epoha obeležena je velikom graditeljskom delatnošću, diplomatskim uspesima i umetničkim procvatom.',
                    'tag' => 'zadužbine',
                    'context' => 'Vladavina kralja Milutina spada među najznačajnije epohe u istoriji Nemanjića. Obeležena je širenjem države, razvijenom diplomatijom i naročito velikim zadužbinarstvom. Milutin je ostavio snažan trag kroz izgradnju i obnovu brojnih crkava i manastira, pa je njegov period važan i za istoriju umetnosti i duhovnosti.',
                ],
                [
                    'year' => '1321',
                    'title' => 'Smrt kralja Milutina',
                    'text' => 'Završava se jedna od najznačajnijih vladavina u srpskoj srednjovekovnoj istoriji.',
                    'tag' => 'ličnost',
                    'context' => 'Smrt kralja Milutina označila je kraj jedne od najznačajnijih vladavina srednjovekovne Srbije. Njegov politički, kulturni i crkveni uticaj ostao je snažan i nakon njegove smrti. U istoriji je ostao upamćen kao jedan od najvećih ktitora i najznačajnijih srpskih vladara.',
                ],
                [
                    'year' => '1321–1331',
                    'title' => 'Vladavina Stefana Dečanskog',
                    'text' => 'Period obeležen političkim borbama, ali i značajnim zadužbinarstvom, posebno kroz Visoke Dečane.',
                    'tag' => 'zadužbine',
                    'context' => 'Vladavina Stefana Dečanskog odvijala se u vreme političkih napetosti, ali je ostavila veliki trag u zadužbinarstvu. Njegovo ime posebno je vezano za manastir Visoki Dečani, jednu od najvažnijih srpskih svetinja. Ova epoha pokazuje kako su politička borba i duhovno stvaralaštvo često išli zajedno.',
                ],
                [
                    'year' => '1330',
                    'title' => 'Bitka kod Velbužda',
                    'text' => 'Srpska pobeda kod Velbužda snažno učvršćuje položaj države na Balkanu.',
                    'tag' => 'vojna istorija',
                    'context' => 'Bitka kod Velbužda 1330. godine bila je jedna od najvažnijih pobeda srednjovekovne Srbije. Tom pobedom Srbija je znatno učvrstila svoj položaj na Balkanu i pokazala vojnu snagu. Ovaj događaj predstavlja važan uvod u kasniji uspon Srbije u vreme cara Dušana.',
                ],
                [
                    'year' => '1331',
                    'title' => 'Dolazak cara Dušana na vlast',
                    'text' => 'Počinje vrhunac političke moći srednjovekovne Srbije i period velikog teritorijalnog širenja.',
                    'tag' => 'država',
                    'context' => 'Dolaskom Stefana Dušana na vlast Srbija ulazi u period najvećeg političkog i teritorijalnog uspona. Njegova vladavina označava vrhunac moći srednjovekovne države. U ovom periodu Srbija se širi, jača upravu i postaje jedan od najvažnijih činilaca na Balkanu.',
                ],
                [
                    'year' => '1346',
                    'title' => 'Proglašenje carstva',
                    'text' => 'Srpska država uzdiže se na rang carstva, a njen politički i crkveni autoritet dostiže vrhunac.',
                    'tag' => 'država',
                    'context' => 'Godine 1346. Srbija se uzdiže na rang carstva, a Dušan biva proglašen za cara. Ovaj događaj imao je ogroman politički značaj, jer je pokazao snagu i ambiciju srpske države. Istovremeno, uzdizanje Crkve na rang patrijaršije dodatno je učvrstilo duhovni i politički autoritet države.',
                ],
                [
                    'year' => '1349',
                    'title' => 'Dušanov zakonik',
                    'text' => 'Donosi se prvi deo Dušanovog zakonika, jednog od najvažnijih pravnih spomenika srednjeg veka.',
                    'tag' => 'zakon',
                    'context' => 'Dušanov zakonik predstavlja jedan od najvažnijih pravnih spomenika srpske srednjovekovne države. Njime su uređeni brojni odnosi u državi, od vlasti i suda do položaja Crkve i društvenog poretka. Zakonik pokazuje visok stepen uređenosti države i razvijenosti pravne svesti tog vremena.',
                ],
                [
                    'year' => '1354',
                    'title' => 'Dopuna Dušanovog zakonika',
                    'text' => 'Zakonik biva proširen i dodatno uređuje odnose u državi, crkvi i društvu.',
                    'tag' => 'zakon',
                    'context' => 'Dopune Dušanovog zakonika dodatno su razvile i precizirale pravila života u carstvu. Time je pravni sistem postao potpuniji, a uređivanje odnosa u državi i društvu detaljnije. Ovaj događaj pokazuje da je srpska srednjovekovna država imala jaku potrebu za jasno uređenim poretkom.',
                ],
                [
                    'year' => '1355',
                    'title' => 'Smrt cara Dušana',
                    'text' => 'Nakon Dušanove smrti počinje postepeno slabljenje centralne vlasti.',
                    'tag' => 'ličnost',
                    'context' => 'Smrt cara Dušana predstavlja prelomni trenutak u istoriji Srbije. Nakon njegove smrti slabi centralna vlast, a brojni velikaši dobijaju veću samostalnost. Ovaj proces postepeno vodi ka političkom raslojavanju i slabljenju države.',
                ],
                [
                    'year' => '1371',
                    'title' => 'Smrt cara Uroša',
                    'text' => 'Gasi se glavna linija dinastije Nemanjić i završava jedno veliko razdoblje srpske srednjovekovne istorije.',
                    'tag' => 'dinastija',
                    'context' => 'Smrću cara Uroša završava se glavna vladarska linija dinastije Nemanjić. Time se simbolično zatvara najznačajnije razdoblje srpske srednjovekovne istorije. Posle tog trenutka srpske zemlje ulaze u novo i mnogo nestabilnije političko doba.',
                ],
            ],

            'spc' => [
                [
                    'year' => '1219',
                    'title' => 'Dobijanje autokefalnosti',
                    'text' => 'Sveti Sava dobija samostalnost Srpske crkve i postavlja temelje njenog uređenog života.',
                    'tag' => 'crkva',
                    'context' => 'Autokefalnost Srpske crkve iz 1219. godine jedan je od najvažnijih događaja u srpskoj crkvenoj istoriji. Sveti Sava time postaje prvi arhiepiskop samostalne Srpske crkve. Ovaj događaj učvrstio je duhovni identitet naroda i dao snažnu podršku razvoju države.',
                ],
                [
                    'year' => '1219',
                    'title' => 'Osnivanje prvih eparhija',
                    'text' => 'Uspostavlja se eparhijska mreža, što omogućava bolje organizovan duhovni i liturgijski život.',
                    'tag' => 'organizacija',
                    'context' => 'Posle dobijanja autokefalnosti, Sveti Sava organizuje prve eparhije i uređuje crkveni život. Time je omogućeno da Crkva deluje uređenije i prisutnije u različitim oblastima srpskih zemalja. Ova organizacija bila je presudna za učvršćivanje vere, pismenosti i crkvene uprave.',
                ],
                [
                    'year' => '1220',
                    'title' => 'Žiča kao sedište arhiepiskopije',
                    'text' => 'Manastir Žiča postaje jedno od najvažnijih središta crkvenog života i simbol samostalnosti Crkve.',
                    'tag' => 'sedište',
                    'context' => 'Žiča je u prvim decenijama autokefalne Srpske crkve imala izuzetan značaj kao arhiepiskopsko sedište. Ovaj manastir postao je simbol crkvene samostalnosti i duhovnog poretka. Njegova uloga bila je velika i u odnosu između Crkve i države.',
                ],
                [
                    'year' => '1236',
                    'title' => 'Upokojenje Svetog Save',
                    'text' => 'Smrt Svetog Save ostavlja dubok trag u duhovnoj istoriji srpskog naroda.',
                    'tag' => 'ličnost',
                    'context' => 'Upokojenje Svetog Save u Trnovu predstavljalo je veliki gubitak za srpski narod i Crkvu. Njegov rad obeležio je oblikovanje srpske duhovnosti, pismenosti i crkvene organizacije. I nakon smrti, Sveti Sava ostao je najvažnija ličnost srpske crkvene tradicije.',
                ],
                [
                    'year' => '1253',
                    'title' => 'Prenos sedišta u Peć',
                    'text' => 'Crkveno središte se vezuje za Peć, koja će vekovima imati izuzetnu duhovnu važnost.',
                    'tag' => 'sedište',
                    'context' => 'Peć postaje jedno od najvažnijih crkvenih središta u srpskoj istoriji. Vremenom je vezivanje crkvenog života za Peć dalo ovom mestu poseban duhovni i simbolički značaj. Kasnije će upravo Peć biti srce patrijaršijskog života.',
                ],
                [
                    'year' => '1346',
                    'title' => 'Uzdiže se na rang patrijaršije',
                    'text' => 'Srpska arhiepiskopija postaje patrijaršija, što predstavlja vrhunac njenog srednjovekovnog ugleda.',
                    'tag' => 'crkva',
                    'context' => 'Uzdizanje Srpske crkve na rang patrijaršije 1346. godine predstavlja vrhunac njenog ugleda u srednjem veku. Ovaj događaj bio je тесно povezan sa uzdizanjem srpske države na rang carstva. Time je dodatno osnažen i politički i duhovni položaj Srbije.',
                ],
                [
                    'year' => '1389',
                    'title' => 'Kosovski zavet i crkveno pamćenje',
                    'text' => 'Kosovska bitka ostaje snažno upisana u crkvenu i narodnu svest kao simbol žrtve i vernosti.',
                    'tag' => 'predanje',
                    'context' => 'Kosovska bitka ima izuzetan značaj ne samo u istorijskom, već i u crkvenom i duhovnom pamćenju srpskog naroda. Kroz predanje, bogoslužbene tekstove i narodno sećanje, ovaj događaj postaje simbol žrtve, vernosti i zaveta. Zato njegovo mesto u istoriji SPC prevazilazi samo vojni aspekt.',
                ],
                [
                    'year' => '1459',
                    'title' => 'Pad srednjovekovne države',
                    'text' => 'Nakon pada Smedereva, Crkva još snažnije preuzima ulogu čuvara identiteta i pismenosti.',
                    'tag' => 'istorija',
                    'context' => 'Posle pada srednjovekovne srpske države 1459. godine, Srpska crkva postaje jedan od najvažnijih nosilaca identiteta naroda. Manastiri i crkve čuvaju pismenost, pamćenje, predanje i duhovni kontinuitet. U teškim istorijskim okolnostima Crkva ima ulogu koja prevazilazi isključivo bogoslužbeni život.',
                ],
                [
                    'year' => '1557',
                    'title' => 'Obnova Pećke patrijaršije',
                    'text' => 'Obnovom Pećke patrijaršije započinje novi period stabilizacije crkvenog i kulturnog života.',
                    'tag' => 'obnova',
                    'context' => 'Obnova Pećke patrijaršije 1557. godine bila je od ogromnog značaja za duhovni i kulturni život Srba pod osmanskom vlašću. Time je ponovo ojačana crkvena organizacija i omogućeno snažnije delovanje Crkve među narodom. Ovaj događaj označio je novu fazu stabilizacije i duhovne obnove.',
                ],
                [
                    'year' => '1594',
                    'title' => 'Spaljivanje moštiju Svetog Save',
                    'text' => 'Ovaj događaj ostaje duboko urezan u kolektivno pamćenje kao simbol stradanja i vere.',
                    'tag' => 'stradanje',
                    'context' => 'Spaljivanje moštiju Svetog Save na Vračaru jedan je od najpotresnijih događaja u srpskoj istoriji. Iako je cilj bio da se oslabi duhovna snaga naroda, ovaj čin je imao suprotan efekat i dodatno učvrstio mesto Svetog Save u kolektivnom sećanju. Zato ovaj događaj ima snažan simbolički i duhovni značaj.',
                ],
                [
                    'year' => '1690',
                    'title' => 'Velika seoba Srba',
                    'text' => 'Crkva ima važnu ulogu u očuvanju identiteta naroda u novim istorijskim okolnostima.',
                    'tag' => 'narod i crkva',
                    'context' => 'Velika seoba Srba 1690. godine promenila je istorijsku mapu srpskog naroda. U tim okolnostima Crkva je igrala presudnu ulogu u očuvanju vere, jezika, identiteta i istorijskog pamćenja. Ovaj događaj pokazuje koliko su narodna sudbina i crkveni život bili povezani.',
                ],
                [
                    'year' => '1766',
                    'title' => 'Ukidanje Pećke patrijaršije',
                    'text' => 'Patrijaršija biva ukinuta, ali se duhovni kontinuitet čuva kroz narod, monaštvo i svetinje.',
                    'tag' => 'ukidanje',
                    'context' => 'Ukidanje Pećke patrijaršije 1766. godine predstavlja težak udarac za srpski crkveni život. Ipak, uprkos tome, duhovni kontinuitet nije prekinut. On je nastavljen kroz manastire, monaštvo, narodno predanje i uporno čuvanje vere u teškim vremenima.',
                ],
                [
                    'year' => '1831',
                    'title' => 'Autonomija Crkve u Kneževini Srbiji',
                    'text' => 'Otvara se novo poglavlje u obnovi crkvenog života u modernoj srpskoj državi.',
                    'tag' => 'obnova',
                    'context' => 'U 19. veku, sa obnovom srpske državnosti, započinje i nova faza uređenja crkvenog života. Autonomija Crkve u Kneževini Srbiji važna je jer pokazuje povratak crkvene samostalnosti u modernim političkim okolnostima. To je bio važan korak ka daljem učvršćivanju crkvene organizacije.',
                ],
                [
                    'year' => '1879',
                    'title' => 'Autokefalnost u modernom periodu',
                    'text' => 'Srpska crkva ponovo dobija punu samostalnost u novim političkim okolnostima.',
                    'tag' => 'autokefalnost',
                    'context' => 'Dobijanje autokefalnosti u modernom periodu potvrđuje crkvenu samostalnost Srbije u novim istorijskim uslovima. Ovaj događaj pokazuje kontinuitet težnje ka samostalnom crkvenom životu. On ima veliki značaj za odnos između države, naroda i Crkve u savremenom dobu.',
                ],
                [
                    'year' => '1920',
                    'title' => 'Ujedinjenje Srpske pravoslavne crkve',
                    'text' => 'Obnavlja se jedinstvena SPC u savremenom obliku, što predstavlja važan događaj za ceo narod.',
                    'tag' => 'ujedinjenje',
                    'context' => 'Ujedinjenje Srpske pravoslavne crkve 1920. godine predstavlja važan trenutak u njenoj savremenoj istoriji. Time je obnovljeno jedinstvo crkvene organizacije na širem prostoru srpskog naroda. Ovaj događaj ima i duhovni i nacionalni značaj, jer potvrđuje kontinuitet zajedničkog crkvenog života.',
                ],
            ],

            'turci' => [
                [
                    'year' => '1371',
                    'title' => 'Marička bitka',
                    'text' => 'Bitka na Marici označava početak velikih promena u odnosu snaga na Balkanu.',
                    'tag' => 'vojna istorija',
                    'context' => 'Marička bitka odigrala se 1371. godine i predstavlja jedan od ključnih događaja u prodoru Osmanlija na Balkan. U ovoj bici stradali su moćni srpski velikaši, a posledice poraza bile su veoma teške za srpske zemlje. Ovaj događaj se često smatra početkom snažnijeg osmanskog uticaja i slabljenja srpske političke moći na jugu Balkana.',
                ],
                [
                    'year' => '1389',
                    'title' => 'Kosovska bitka',
                    'text' => 'Kosovska bitka postaje jedan od najvažnijih istorijskih i duhovnih simbola srpskog naroda.',
                    'tag' => 'bitka',
                    'context' => 'Kosovska bitka iz 1389. godine jedan je od najvažnijih događaja u srpskoj istoriji. Ona ima i istorijski i snažan simbolički značaj, jer je vekovima oblikovala narodnu svest, predanje i duhovnu kulturu. U pamćenju naroda ostala je kao simbol žrtve, vernosti i opredeljenja za više vrednosti.',
                ],
                [
                    'year' => '1402',
                    'title' => 'Despotovina Srbije',
                    'text' => 'Počinje novo političko razdoblje pod despotima, uz pokušaje očuvanja državnosti.',
                    'tag' => 'država',
                    'context' => 'Početak Despotovine Srbije označava novo razdoblje u istoriji srpske državnosti. Iako u teškim okolnostima i pod stalnim pritiscima, srpski despoti pokušavali su da sačuvaju političku samostalnost, kulturu i državni poredak. To je bio poslednji veliki period srednjovekovne srpske državne organizacije pre konačnog pada.',
                ],
                [
                    'year' => '1453',
                    'title' => 'Pad Carigrada',
                    'text' => 'Ovaj događaj menja širu istorijsku sliku regiona i dodatno utiče na položaj pravoslavnih zemalja.',
                    'tag' => 'širi kontekst',
                    'context' => 'Pad Carigrada 1453. godine imao je ogroman značaj za čitav pravoslavni svet i Balkan. Ovim događajem Osmansko carstvo dodatno učvršćuje svoju moć, a položaj pravoslavnih naroda postaje još teži. Iako se ne odnosi samo na Srbiju, ovaj događaj snažno utiče i na dalji tok srpske istorije.',
                ],
                [
                    'year' => '1459',
                    'title' => 'Pad Smedereva',
                    'text' => 'Nestaje srpska srednjovekovna država i počinje dug period osmanske vlasti.',
                    'tag' => 'pad države',
                    'context' => 'Padom Smedereva 1459. godine prestaje postojanje srednjovekovne srpske države. Ovaj događaj označava početak dugog perioda osmanske vlasti nad velikim delom srpskih zemalja. Ipak, i u takvim uslovima opstaju duhovni, kulturni i identitetski obrasci koji će kasnije biti važni za obnovu države.',
                ],
                [
                    'year' => '1521',
                    'title' => 'Pad Beograda',
                    'text' => 'Pad Beograda predstavlja važan događaj u širenju osmanske vlasti na Balkanu.',
                    'tag' => 'vojna istorija',
                    'context' => 'Pad Beograda 1521. godine predstavlja jedan od važnih koraka u daljem širenju Osmanskog carstva u ovom delu Evrope. Beograd je imao veliki strateški značaj, pa je njegov pad uticao i na šire političke odnose u regionu. Ovaj događaj pokazuje koliko je položaj srpskih zemalja bio težak u vreme osmanskog napredovanja.',
                ],
                [
                    'year' => '1557',
                    'title' => 'Obnova Pećke patrijaršije',
                    'text' => 'U teškim uslovima osmanske vlasti, crkvena obnova daje novu snagu duhovnom i kulturnom opstanku.',
                    'tag' => 'crkva',
                    'context' => 'Obnova Pećke patrijaršije nije važna samo za crkvenu istoriju, već i za opstanak naroda pod osmanskom vlašću. U tom periodu Crkva dobija novu snagu da okuplja narod, čuva identitet, pismenost i tradiciju. Zato ovaj događaj ima i verski i širi istorijski značaj.',
                ],
                [
                    'year' => '1594',
                    'title' => 'Banatski ustanak i spaljivanje moštiju',
                    'text' => 'Ove godine ostaju upamćene po otporu, stradanju i snažnom simboličkom značenju.',
                    'tag' => 'ustanci',
                    'context' => 'Banatski ustanak i spaljivanje moštiju Svetog Save predstavljaju događaje duboko povezane sa idejom otpora i stradanja. U narodnom i crkvenom pamćenju oni su ostali kao simbol pokušaja da se slomi duh naroda, ali i kao potvrda njegove istrajnosti. Zbog toga imaju i istorijski i duhovni značaj.',
                ],
                [
                    'year' => '1690',
                    'title' => 'Velika seoba Srba',
                    'text' => 'Narod se masovno pomera na sever, a istorijska i duhovna mapa srpskog prostora menja se zauvek.',
                    'tag' => 'seobe',
                    'context' => 'Velika seoba Srba 1690. godine jedan je od presudnih događaja za kasniju istoriju srpskog naroda. Seoba je promenila raspored stanovništva, istorijsku mapu i društveni razvoj srpskih zajednica. Ovaj događaj je važan i zbog toga što je oblikovao budući položaj Srba u različitim državnim i kulturnim sredinama.',
                ],
                [
                    'year' => '1718',
                    'title' => 'Požarevački mir',
                    'text' => 'Dolazi do promena granica i odnosa snaga, što utiče i na život naroda u srpskim zemljama.',
                    'tag' => 'diplomatija',
                    'context' => 'Požarevački mir predstavlja važan diplomatski događaj koji menja odnose snaga između velikih carstava na Balkanu. Takvi sporazumi imali su neposredan uticaj na život stanovništva, položaj srpskih zemalja i političke prilike u regionu. Zato je ovaj događaj važan kao deo šire istorijske slike.',
                ],
                [
                    'year' => '1739',
                    'title' => 'Beogradski mir',
                    'text' => 'Nova promena političkih prilika donosi i nove pritiske na stanovništvo.',
                    'tag' => 'diplomatija',
                    'context' => 'Beogradski mir menja političke odnose u regionu i utiče na položaj naroda u srpskim zemljama. Promene granica i vlasti uvek su sa sobom donosile i nove nesigurnosti, pritiske i prilagođavanja. Zato ovaj događaj ima značaj i u širem evropskom i u lokalnom istorijskom kontekstu.',
                ],
                [
                    'year' => '1804',
                    'title' => 'Prvi srpski ustanak',
                    'text' => 'Počinje veliki pokret za oslobođenje, obnovu državnosti i nacionalno buđenje.',
                    'tag' => 'ustanak',
                    'context' => 'Prvi srpski ustanak iz 1804. godine predstavlja početak moderne borbe za oslobođenje od osmanske vlasti. Njime započinje proces obnove srpske državnosti i snažnog nacionalnog buđenja. Ovaj događaj ima ogroman značaj jer označava prelazak iz dugog perioda potčinjenosti ka obnovi političke samostalnosti.',
                ],
                [
                    'year' => '1815',
                    'title' => 'Drugi srpski ustanak',
                    'text' => 'Nastavlja se borba za političku samostalnost i stvaraju se temelji moderne Srbije.',
                    'tag' => 'ustanak',
                    'context' => 'Drugi srpski ustanak nadovezuje se na prethodnu borbu i predstavlja važan korak ka stvaranju moderne Srbije. Njegov značaj ogleda se u tome što je otvorio prostor za stabilnije političko uređivanje i jačanje autonomije. Ovaj događaj je ključan za razumevanje nastanka savremene srpske države.',
                ],
                [
                    'year' => '1830',
                    'title' => 'Hatišerif i autonomija',
                    'text' => 'Srbija dobija širu autonomiju, što predstavlja važan korak ka punoj nezavisnosti.',
                    'tag' => 'autonomija',
                    'context' => 'Hatišerif iz 1830. godine imao je veliki značaj za razvoj srpske autonomije u okviru Osmanskog carstva. Njime su potvrđena važna prava Srbije i otvoren put ka daljem jačanju državnosti. Zato se ovaj događaj smatra jednim od ključnih koraka ka punoj nezavisnosti.',
                ],
                [
                    'year' => '1878',
                    'title' => 'Međunarodno priznanje Srbije',
                    'text' => 'Na Berlinskom kongresu Srbija dobija međunarodno priznanje nezavisnosti.',
                    'tag' => 'nezavisnost',
                    'context' => 'Međunarodno priznanje Srbije 1878. godine predstavlja završetak dugog istorijskog procesa oslobađanja i obnove državnosti. Na Berlinskom kongresu Srbija je potvrđena kao nezavisna država. Ovaj događaj ima izuzetan značaj jer označava ulazak Srbije u novi politički položaj među evropskim državama.',
                ],
            ],
        ];

        return view('pages.pravoslavni.modules.edukacija.timeline', compact('timelines'));
    }

    /* ---------------- KVIZ: ISTORIJA ---------------- */

    public function quizHistory()
    {
        $questions = $this->historyQuestions();

        return view('pages.pravoslavni.modules.edukacija.quiz-history', [
            'questions' => $questions,
            'result' => null,
            'answers' => [],
        ]);
    }

    public function quizHistorySubmit(Request $request)
    {
        $questions = $this->historyQuestions();

        $answers = (array) $request->input('answers', []);
        $score = 0;
        $max = count($questions);

        foreach ($questions as $q) {
            $picked = $answers[$q['id']] ?? null;
            if ($picked !== null && (string) $picked === (string) $q['correct']) {
                $score++;
            }
        }

        $result = [
            'score' => $score,
            'max' => $max,
            'percent' => $max ? round(($score / $max) * 100) : 0,
        ];

        return view('pages.pravoslavni.modules.edukacija.quiz-history', [
            'questions' => $questions,
            'result' => $result,
            'answers' => $answers,
        ]);
    }

    private function historyQuestions(): array
    {
        return [
            [
                'id' => 'h1',
                'q' => 'Koji događaj se vezuje za 1219. godinu u istoriji SPC?',
                'options' => [
                    'Ujedinjenje SPC u modernom obliku',
                    'Dobijanje autokefalnosti od strane Svetog Save',
                    'Ukidanje Pećke patrijaršije',
                    'Pad Smedereva',
                ],
                'correct' => 1,
                'explain' => 'Godine 1219. Sveti Sava je izborio autokefalnost Srpske crkve i postao njen prvi arhiepiskop.',
            ],
            [
                'id' => 'h2',
                'q' => 'Koja dinastija je obeležila zlatno doba srednjovekovne Srbije?',
                'options' => ['Obrenovići', 'Nemanjići', 'Karađorđevići', 'Brankovići'],
                'correct' => 1,
                'explain' => 'Dinastija Nemanjića obeležila je politički, kulturni i duhovni vrhunac srednjovekovne Srbije.',
            ],
            [
                'id' => 'h3',
                'q' => 'Koji vladar je poznat po Dušanovom zakoniku i tituli cara?',
                'options' => ['Stefan Dečanski', 'Car Dušan', 'Stefan Prvovenčani', 'Uroš Nejaki'],
                'correct' => 1,
                'explain' => 'Car Dušan je najpoznatiji po proglašenju carstva i donošenju Dušanovog zakonika.',
            ],
            [
                'id' => 'h4',
                'q' => 'Pad Smedereva (1459) označava:',
                'options' => [
                    'Kraj osmanske vlasti',
                    'Početak autokefalnosti',
                    'Nestanak srednjovekovne države',
                    'Uvođenje Zakonika',
                ],
                'correct' => 2,
                'explain' => 'Padom Smedereva 1459. prestaje da postoji srednjovekovna srpska država.',
            ],
            [
                'id' => 'h5',
                'q' => 'Šta je bila posebna uloga manastira tokom osmanske vlasti?',
                'options' => [
                    'Centri fabrike oružja',
                    'Isključivo vojni logori',
                    'Čuvanje pismenosti i identiteta',
                    'Pomorske luke',
                ],
                'correct' => 2,
                'explain' => 'Manastiri su bili čuvari vere, knjiga, pismenosti i narodnog identiteta.',
            ],
            [
                'id' => 'h6',
                'q' => 'Ko se smatra rodonačelnikom dinastije Nemanjića?',
                'options' => ['Stefan Nemanja', 'Kralj Milutin', 'Stefan Lazarević', 'Vuk Branković'],
                'correct' => 0,
                'explain' => 'Stefan Nemanja je osnivač dinastije Nemanjića i jedna od najvažnijih ličnosti srpske istorije.',
            ],
            [
                'id' => 'h7',
                'q' => 'Koji manastir je bio prvo sedište samostalne Srpske arhiepiskopije?',
                'options' => ['Studenica', 'Žiča', 'Manasija', 'Ravanica'],
                'correct' => 1,
                'explain' => 'Žiča je bila prvo sedište autokefalne Srpske arhiepiskopije.',
            ],
            [
                'id' => 'h8',
                'q' => 'Koji svetitelj je bio prvi arhiepiskop samostalne Srpske crkve?',
                'options' => ['Sveti Simeon', 'Sveti Sava', 'Sveti Vasilije Ostroški', 'Sveti Petar Cetinjski'],
                'correct' => 1,
                'explain' => 'Sveti Sava bio je prvi arhiepiskop autokefalne Srpske crkve.',
            ],
            [
                'id' => 'h9',
                'q' => 'Šta je bila Pećka patrijaršija?',
                'options' => ['Srpska vojna oblast', 'Trgovački centar', 'Sedište srpskih patrijaraha', 'Dvor Nemanjića'],
                'correct' => 2,
                'explain' => 'Pećka patrijaršija je bila centralno duhovno i crkveno sedište srpskih patrijaraha.',
            ],
            [
                'id' => 'h10',
                'q' => 'Koji vladar se vezuje za manastir Visoki Dečani?',
                'options' => ['Stefan Dečanski', 'Knez Lazar', 'Stefan Nemanja', 'Car Uroš'],
                'correct' => 0,
                'explain' => 'Visoki Dečani su zadužbina Stefana Dečanskog i jedna od najvažnijih srpskih svetinja.',
            ],
            [
                'id' => 'h11',
                'q' => 'Koji manastir je poznat po fresci „Beli anđeo“?',
                'options' => ['Mileševa', 'Sopoćani', 'Gračanica', 'Studenica'],
                'correct' => 0,
                'explain' => '„Beli anđeo“ iz Mileševe jedna je od najpoznatijih fresaka srpske umetnosti.',
            ],
            [
                'id' => 'h12',
                'q' => 'Kako se zvao Stefan Nemanja nakon zamonašenja?',
                'options' => ['Simeon', 'Sava', 'Jovan', 'Pavle'],
                'correct' => 0,
                'explain' => 'Po zamonašenju Stefan Nemanja je dobio ime Simeon i poznat je kao Sveti Simeon Mirotočivi.',
            ],
            [
                'id' => 'h13',
                'q' => 'Koja je velika zadužbina Stefana Nemanje?',
                'options' => ['Studenica', 'Krušedol', 'Ljubostinja', 'Kaona'],
                'correct' => 0,
                'explain' => 'Studenica je jedna od najznačajnijih zadužbina Stefana Nemanje.',
            ],
            [
                'id' => 'h14',
                'q' => 'Koji događaj iz 1389. ima ogroman istorijski i duhovni značaj?',
                'options' => ['Bitka na Marici', 'Kosovska bitka', 'Pad Carigrada', 'Obnova Pećke patrijaršije'],
                'correct' => 1,
                'explain' => 'Kosovska bitka ostavila je dubok trag u srpskoj istorijskoj i duhovnoj svesti.',
            ],
            [
                'id' => 'h15',
                'q' => 'Koji vladar je povezan sa manastirom Ravanicom?',
                'options' => ['Knez Lazar', 'Despot Stefan', 'Vuk Branković', 'Stefan Prvovenčani'],
                'correct' => 0,
                'explain' => 'Ravanica je zadužbina kneza Lazara.',
            ],
            [
                'id' => 'h16',
                'q' => 'Kako se zove poznati pravni spomenik cara Dušana?',
                'options' => ['Nomokanon', 'Dušanov zakonik', 'Svetosavski ustav', 'Pećki tipik'],
                'correct' => 1,
                'explain' => 'Dušanov zakonik jedan je od najvažnijih pravnih spomenika srednjovekovne Srbije.',
            ],
            [
                'id' => 'h17',
                'q' => 'Ko je bio otac Svetog Save?',
                'options' => ['Stefan Uroš', 'Stefan Nemanja', 'Stefan Lazarević', 'Kralj Milutin'],
                'correct' => 1,
                'explain' => 'Otac Svetog Save bio je Stefan Nemanja, kasnije Sveti Simeon Mirotočivi.',
            ],
            [
                'id' => 'h18',
                'q' => 'Koji manastir je bio značajan kulturni centar u vreme despota Stefana Lazarevića?',
                'options' => ['Manasija', 'Žiča', 'Tuman', 'Petkovica'],
                'correct' => 0,
                'explain' => 'Manasija je bila važan duhovni i kulturni centar, poznata i po Resavskoj školi.',
            ],
            [
                'id' => 'h19',
                'q' => 'Šta najbolje opisuje značaj srpskih zadužbina?',
                'options' => [
                    'Bile su samo privatne palate vladara',
                    'Služile su isključivo za vojsku',
                    'Predstavljale su spoj vere, umetnosti, kulture i državne ideje',
                    'Bile su trgovački objekti',
                ],
                'correct' => 2,
                'explain' => 'Zadužbine su bile izraz vere, državnosti, kulture i umetničkog stvaralaštva.',
            ],
            [
                'id' => 'h20',
                'q' => 'Koji je glavni istorijski značaj Svetog Save?',
                'options' => [
                    'Bio je samo vojskovođa',
                    'Osnovao je prvu srpsku školu mačevanja',
                    'Utemeljio je samostalnost Srpske crkve i oblikovao prosvetu i duhovnost',
                    'Bio je poslednji srpski despot',
                ],
                'correct' => 2,
                'explain' => 'Sveti Sava je ključna ličnost srpske duhovne, prosvetne i crkvene istorije.',
            ],
        ];
    }

    /* ---------------- KVIZ: PRAVOSLAVLJE ---------------- */

    public function quizOrthodox()
    {
        $questions = $this->orthodoxQuestions();

        return view('pages.pravoslavni.modules.edukacija.quiz-orthodox', [
            'questions' => $questions,
            'result' => null,
            'answers' => [],
        ]);
    }

    public function quizOrthodoxSubmit(Request $request)
    {
        $questions = $this->orthodoxQuestions();

        $answers = (array) $request->input('answers', []);
        $score = 0;
        $max = count($questions);

        foreach ($questions as $q) {
            $picked = $answers[$q['id']] ?? null;
            if ($picked !== null && (string) $picked === (string) $q['correct']) {
                $score++;
            }
        }

        $result = [
            'score' => $score,
            'max' => $max,
            'percent' => $max ? round(($score / $max) * 100) : 0,
        ];

        return view('pages.pravoslavni.modules.edukacija.quiz-orthodox', [
            'questions' => $questions,
            'result' => $result,
            'answers' => $answers,
        ]);
    }

    private function orthodoxQuestions(): array
    {
        return [
            [
                'id' => 'o1',
                'q' => 'Šta znači „Vaskrs“ u hrišćanskom kontekstu?',
                'options' => [
                    'Rođenje Hrista',
                    'Vaskrsenje Hrista',
                    'Krštenje Hrista',
                    'Vaznesenje Presvete Bogorodice',
                ],
                'correct' => 1,
                'explain' => 'Vaskrs je praznik Vaskrsenja Gospoda Isusa Hrista i centralni događaj hrišćanske vere.',
            ],
            [
                'id' => 'o2',
                'q' => 'Šta je liturgija?',
                'options' => [
                    'Samo crkvena pesma',
                    'Zajedničko bogosluženje i Evharistija',
                    'Samo privatna molitva',
                    'Post bez hrane',
                ],
                'correct' => 1,
                'explain' => 'Liturgija je centralno bogosluženje Crkve u kome se vernici sabiraju oko Evharistije.',
            ],
            [
                'id' => 'o3',
                'q' => 'Zašto se farbaju jaja za Vaskrs?',
                'options' => [
                    'Samo zbog ukrasa',
                    'Kao simbol novog života i radosti Vaskrsenja',
                    'Kao obavezni poklon bez značenja',
                    'Zbog starog običaja bez veze sa verom',
                ],
                'correct' => 1,
                'explain' => 'Jaje simbolizuje novi život, pobedu života nad smrću i radost Vaskrsa.',
            ],
            [
                'id' => 'o4',
                'q' => 'Šta znači autokefalnost crkve?',
                'options' => [
                    'Potpuna zabrana bogosluženja',
                    'Samostalnost u upravljanju crkvenim životom',
                    'Obavezno postavljanje ikona u svakoj kući',
                    'Jedan isti praznik svake nedelje',
                ],
                'correct' => 1,
                'explain' => 'Autokefalnost znači da crkva samostalno upravlja svojim unutrašnjim životom.',
            ],
            [
                'id' => 'o5',
                'q' => 'Koji je tradicionalni vaskršnji pozdrav?',
                'options' => [
                    'Mir Božiji',
                    'Hristos vaskrse — Vaistinu vaskrse',
                    'Srećna slava',
                    'Pomoz’ Bog',
                ],
                'correct' => 1,
                'explain' => 'To je svečani hrišćanski pozdrav kojim se ispoveda radost Vaskrsenja.',
            ],
            [
                'id' => 'o6',
                'q' => 'Ko je glava Crkve u pravoslavnom učenju?',
                'options' => ['Patrijarh', 'Episkop', 'Isus Hristos', 'Sveštenik'],
                'correct' => 2,
                'explain' => 'U pravoslavnom učenju glava Crkve je sam Gospod Isus Hristos.',
            ],
            [
                'id' => 'o7',
                'q' => 'Šta je ikona u pravoslavlju?',
                'options' => [
                    'Obična ukrasna slika',
                    'Sveta slika koja upućuje na duhovnu stvarnost',
                    'Samo istorijski crtež',
                    'Vrsta knjige',
                ],
                'correct' => 1,
                'explain' => 'Ikona je sveta slika i prozor ka duhovnoj stvarnosti, a ne običan ukras.',
            ],
            [
                'id' => 'o8',
                'q' => 'Kako se zove najvažnija hrišćanska tajna na Liturgiji?',
                'options' => ['Krštenje', 'Evharistija', 'Jeleosvećenje', 'Venčanje'],
                'correct' => 1,
                'explain' => 'Evharistija je središte liturgijskog života Crkve.',
            ],
            [
                'id' => 'o9',
                'q' => 'Šta je post u pravoslavlju?',
                'options' => [
                    'Samo promena jelovnika',
                    'Duhovno i telesno uzdržanje uz molitvu i pokajanje',
                    'Kazna za vernike',
                    'Običaj bez dubljeg značenja',
                ],
                'correct' => 1,
                'explain' => 'Post nije samo uzdržanje od hrane, već i rad na sebi, molitva i pokajanje.',
            ],
            [
                'id' => 'o10',
                'q' => 'Koja sveta tajna uvodi čoveka u život Crkve?',
                'options' => ['Krštenje', 'Venčanje', 'Ispovest', 'Monašenje'],
                'correct' => 0,
                'explain' => 'Krštenjem čovek ulazi u zajednicu Crkve.',
            ],
            [
                'id' => 'o11',
                'q' => 'Šta označava kandilo pred ikonom?',
                'options' => [
                    'Samo dekoraciju',
                    'Svetlost vere i molitve',
                    'Stari narodni običaj bez smisla',
                    'Vrstu tamjana',
                ],
                'correct' => 1,
                'explain' => 'Kandilo simbolizuje svetlost Hristovu, veru i molitveno bdenje.',
            ],
            [
                'id' => 'o12',
                'q' => 'Koji je najvažniji praznik u hrišćanstvu?',
                'options' => ['Božić', 'Vaskrs', 'Sveti Nikola', 'Vidovdan'],
                'correct' => 1,
                'explain' => 'Vaskrs je centralni i najvažniji praznik hrišćanske vere.',
            ],
            [
                'id' => 'o13',
                'q' => 'Šta je ispovest?',
                'options' => [
                    'Javno čitanje molitava',
                    'Priznanje grehova pred Bogom uz sveštenika kao svedoka Crkve',
                    'Samo razgovor o životu',
                    'Vrsta posta',
                ],
                'correct' => 1,
                'explain' => 'Ispovest je sveta tajna pokajanja i duhovnog očišćenja.',
            ],
            [
                'id' => 'o14',
                'q' => 'Ko se u pravoslavlju posebno poštuje kao Majka Božija?',
                'options' => ['Marija Magdalena', 'Presveta Bogorodica', 'Sveta Petka', 'Sveta Katarina'],
                'correct' => 1,
                'explain' => 'Presveta Bogorodica zauzima posebno mesto u pravoslavnom poštovanju.',
            ],
            [
                'id' => 'o15',
                'q' => 'Šta znači reč „pravoslavlje“?',
                'options' => [
                    'Stari običaji',
                    'Pravo slavljenje Boga i ispravno verovanje',
                    'Samo istočna tradicija',
                    'Monaški život',
                ],
                'correct' => 1,
                'explain' => 'Pravoslavlje znači pravilno verovanje i pravo slavljenje Boga.',
            ],
            [
                'id' => 'o16',
                'q' => 'Šta je slava u srpskoj pravoslavnoj tradiciji?',
                'options' => [
                    'Rođendanska proslava',
                    'Porodični praznik posvećen svetitelju zaštitniku doma',
                    'Državni praznik',
                    'Dan venčanja',
                ],
                'correct' => 1,
                'explain' => 'Slava je poseban srpski pravoslavni običaj posvećen svetitelju zaštitniku porodice.',
            ],
            [
                'id' => 'o17',
                'q' => 'Šta je manastir?',
                'options' => [
                    'Samo istorijska građevina',
                    'Mesto monaškog života, molitve i duhovnog podviga',
                    'Vrsta škole',
                    'Mesto za trgovinu',
                ],
                'correct' => 1,
                'explain' => 'Manastir je mesto molitve, monaškog života i duhovnog sabiranja.',
            ],
            [
                'id' => 'o18',
                'q' => 'Koja knjiga je osnovna sveta knjiga hrišćanstva?',
                'options' => ['Psaltir', 'Biblija', 'Trebnik', 'Oktoih'],
                'correct' => 1,
                'explain' => 'Biblija je osnovna sveta knjiga hrišćanstva.',
            ],
            [
                'id' => 'o19',
                'q' => 'Čemu služi molitva u pravoslavnom životu?',
                'options' => [
                    'Samo kao običaj pred praznik',
                    'Kao razgovor sa Bogom i put duhovnog uzrastanja',
                    'Samo za posebne dane',
                    'Samo za monahe',
                ],
                'correct' => 1,
                'explain' => 'Molitva je lični i zajednički razgovor sa Bogom i osnova duhovnog života.',
            ],
            [
                'id' => 'o20',
                'q' => 'Šta je cilj hrišćanskog života prema pravoslavnom učenju?',
                'options' => [
                    'Samo spoljašnje poštovanje običaja',
                    'Uspeh u poslu',
                    'Zajednica sa Bogom, ljubav, spasenje i duhovno preobraženje',
                    'Život bez ikakvih pravila',
                ],
                'correct' => 2,
                'explain' => 'Cilj hrišćanskog života jeste zajednica sa Bogom, spasenje i duhovno preobraženje čoveka.',
            ],
        ];
    }

    /* ---------------- AI ---------------- */

    public function ai()
    {
        return view('pages.pravoslavni.modules.edukacija.ai');
    }

    public function aiChat(Request $request)
    {
        $api = app(ApiAiController::class);
        return $api->chat($request);
    }
}