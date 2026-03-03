<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Curiosity;

class CuriositiesSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'title' => 'Zašto se u pravoslavlju pale sveće?',
                'category' => 'Običaji',
                'reading_minutes' => 7,
                'excerpt' => 'Sveća nije dekoracija, niti “obaveza”, već mala lična molitva i znak unutrašnje pažnje pred Bogom.',
                'content' => $this->textSvece(),
            ],
            [
                'title' => 'Tajne fresaka: zašto svetitelji gledaju pravo u vas',
                'category' => 'Freske',
                'reading_minutes' => 8,
                'excerpt' => 'Freska nije samo slika; ona je vizuelna teologija. Pogled svetitelja često je poziv na budnost i dijalog.',
                'content' => $this->textFreske(),
            ],
            [
                'title' => 'Vladari koji su postali monasi: od krune do poslušanja',
                'category' => 'Istorija',
                'reading_minutes' => 8,
                'excerpt' => 'U srpskoj tradiciji nije retkost da moćni ljudi na kraju biraju tišinu manastira.',
                'content' => $this->textVladariMonasi(),
            ],
            [
                'title' => 'Manastiri skriveni od sveta: zašto su građeni daleko od puteva',
                'category' => 'Manastiri',
                'reading_minutes' => 7,
                'excerpt' => 'Teško dostupna mesta nisu bila slučajnost — tišina i zaštita često su bile deo plana.',
                'content' => $this->textSkriveni(),
            ],
            [
                'title' => 'Zvona: kada zvone i šta poručuju',
                'category' => 'Bogosluženje',
                'reading_minutes' => 6,
                'excerpt' => 'Zvuk zvona je poziv, podsetnik i “ritam” crkvenog života koji se čuje i kada ne vidimo hram.',
                'content' => $this->textZvona(),
            ],
            [
                'title' => 'Zašto se u crkvi stoji: smisao budnosti i poštovanja',
                'category' => 'Običaji',
                'reading_minutes' => 6,
                'excerpt' => 'Stajanje u hramu nije “teško pravilo”, već znak pažnje i sabranosti.',
                'content' => $this->textStajanje(),
            ],
            [
                'title' => 'Miris tamjana: molitva koja se uzdiže',
                'category' => 'Bogosluženje',
                'reading_minutes' => 6,
                'excerpt' => 'Tamjan ima duboko biblijsko i liturgijsko značenje — i utiče na doživljaj službe.',
                'content' => $this->textTamjan(),
            ],
            [
                'title' => 'Kako izgleda život jednog monaha: dan koji uči tišini',
                'category' => 'Manastirski život',
                'reading_minutes' => 9,
                'excerpt' => 'Manastirski život nije “beg”, nego uređen put molitve, rada i poslušanja.',
                'content' => $this->textMonah(),
            ],
            [
                'title' => 'Poklonjenje ikonama: poštovanje, ne idolopoklonstvo',
                'category' => 'Ikone',
                'reading_minutes' => 7,
                'excerpt' => 'Ikona je prozor ka ličnosti, a poklonjenje je izraz ljubavi i poštovanja.',
                'content' => $this->textIkone(),
            ],
            [
                'title' => 'Post: više od hrane — škola slobode',
                'category' => 'Post',
                'reading_minutes' => 8,
                'excerpt' => 'U pravoslavlju post je unutrašnja disciplina: reči, misli, dela i odnos prema drugima.',
                'content' => $this->textPost(),
            ],
            [
                'title' => 'Zašto se krstimo sa tri prsta: vera u Trojicu',
                'category' => 'Osnovi vere',
                'reading_minutes' => 6,
                'excerpt' => 'Način krštenja nije formalnost — on čuva sadržaj ispovedanja vere.',
                'content' => $this->textTriPrsta(),
            ],
            [
                'title' => 'Zašto pravoslavni hramovi imaju kupole',
                'category' => 'Arhitektura',
                'reading_minutes' => 7,
                'excerpt' => 'Kupola nije samo lep detalj; ona simboliše nebo i menja način na koji doživljavamo prostor.',
                'content' => $this->textKupole(),
            ],
            [
                'title' => 'Mit ili istina: najčešće zablude o crkvenim običajima',
                'category' => 'Običaji',
                'reading_minutes' => 8,
                'excerpt' => 'Mnogi ljudi rade “kako su čuli”, ali bez razumevanja. Ovaj tekst razdvaja naviku od smisla.',
                'content' => $this->textMitIstina(),
            ],
            [
                'title' => 'Manastiri kao centri znanja: knjige, prepisivači i pamćenje naroda',
                'category' => 'Istorija',
                'reading_minutes' => 8,
                'excerpt' => 'Manastiri nisu čuvali samo veru, nego i pismenost, istoriju i kulturu.',
                'content' => $this->textZnanje(),
            ],
            [
                'title' => 'Zašto ljudi osećaju mir u manastiru',
                'category' => 'Duhovni život',
                'reading_minutes' => 7,
                'excerpt' => 'Mir nije magija prostora — to je spoj tišine, ritma, molitve i jednostavnosti.',
                'content' => $this->textMir(),
            ],
        ];

        foreach ($items as $i) {
            Curiosity::updateOrCreate(
                ['slug' => Str::slug($i['title'])],
                [
                    'title' => $i['title'],
                    'slug' => Str::slug($i['title']),
                    'category' => $i['category'],
                    'reading_minutes' => $i['reading_minutes'],
                    'image' => null, // kasnije: images/curiosities/xxx.jpg
                    'excerpt' => $i['excerpt'],
                    'content' => $i['content'],
                    'is_published' => true,
                ]
            );
        }
    }

    private function textSvece(): string
    {
        return "Sveća u pravoslavlju nije ukras, niti “formalnost” koju treba obaviti na brzinu.\n\n"
            ."Kada palimo sveću, mi u stvari dajemo mali vidljivi znak nevidljivoj molitvi.\n\n"
            ."Svetlost sveće podseća da Hristos naziva sebe Svetlošću sveta i da vera nije samo misao, već i život.\n\n"
            ."Vosak se tradicionalno koristi jer je prirodan i čist, a i simbolično govori o prinošenju: ono što je naše, dajemo Bogu.\n\n"
            ."Važno je i raspoloženje: sveća “vredi” onoliko koliko je u nju utkana molitva.\n\n"
            ."Mnogi pitaju gde se pali sveća za zdravlje, a gde za upokojene.\n\n"
            ."Praksa se razlikuje po hramovima, ali smisao je isti: setiti se živih u molitvi, setiti se upokojenih sa nadom u Vaskrsenje.\n\n"
            ."Najbolje je uvek pitati u konkretnom hramu ako nisi sigurna, bez ustručavanja.\n\n"
            ."Suština nije “tačna strana”, već sabranost i poštovanje.\n\n"
            ."Ako si u žurbi, bolje je zapaliti jednu sveću sa pažnjom nego pet bez ikakvog unutrašnjeg učešća.\n\n"
            ."Sveća nas uči i jednoj tihoj disciplini: da stanemo, udahnemo i uđemo u mir, makar na minut.\n\n"
            ."U tom smislu, sveća je mali početak molitve, a ne njen kraj.";
    }

    private function textFreske(): string
    {
        return "Freske nisu nastale da “ukrase zid”, nego da prenesu veru u boji i obliku.\n\n"
            ."U ikonografiji ništa nije slučajno: položaj ruku, pogled, odežda, boje, pa čak i način na koji je prostor naslikan.\n\n"
            ."Ljudi često primete da svetitelji na freskama gledaju pravo u posmatrača.\n\n"
            ."To nije efekat “realizma”, već poruka: svetitelj nije daleki lik iz prošlosti, već živa ličnost u zajednici Crkve.\n\n"
            ."Velike oči simbolizuju budnost i duhovni pogled, odnosno čoveka koji gleda srcem.\n\n"
            ."Još jedna zanimljivost je “obrnuta perspektiva”: umesto da se sve sužava ka daljini, ponekad se širi ka posmatraču.\n\n"
            ."To je način da se kaže: ti nisi samo gledalac, ti si pozvan da uđeš u događaj.\n\n"
            ."Zlatna pozadina nije “luksuz”, nego znak Carstva nebeskog, svetlosti koja nije od ovog sveta.\n\n"
            ."Boje takođe imaju značenje: plava često govori o nebu i tajni, crvena o životu i žrtvi, bela o svetlosti i preobraženju.\n\n"
            ."Freska može da bude i “učitelj”: u vreme kad mnogi nisu umeli da čitaju, zidovi hrama su bili katehizam.\n\n"
            ."Zato je važno da na freske ne gledamo kao na muzej.\n\n"
            ."One su pre svega deo molitvenog prostora i govore najviše kada ih posmatramo u tišini.";
    }

    private function textVladariMonasi(): string
    {
        return "U istoriji srpskog naroda postoji snažan motiv: vladar koji na kraju života bira monaštvo.\n\n"
            ."To ne mora da bude “bekstvo”, već često znak zrelosti i svesti da moć nije poslednja reč.\n\n"
            ."Kada čovek ima vlast, novac ili slavu, lako poveruje da je to smisao života.\n\n"
            ."Monaški put, naprotiv, uči čoveka da se odrekne viška i da živi jednostavno.\n\n"
            ."U tradiciji Nemanjića vidimo ovaj motiv jasno: od državničkih odluka do duhovnog zaveta.\n\n"
            ."Prelazak iz palate u keliju nije samo promena adrese; to je promena srca.\n\n"
            ."Monah se uči poslušanju, a to je često teže nego komandovanje.\n\n"
            ."Zanimljivo je da narod ovakve ličnosti pamti ne samo po političkim pobedama nego po zadužbinama i duhovnom tragu.\n\n"
            ."Zadužbina nije samo građevina; ona je svedočanstvo vere i želje da se nešto ostavi za dobro mnogih.\n\n"
            ."Ove priče nas podsećaju da se vrednost čoveka ne meri samo uspehom, nego i sposobnošću da se smiri i promeni.";
    }

    private function textSkriveni(): string
    {
        return "Mnogi manastiri u Srbiji nalaze se u šumama, klisurama ili daleko od glavnih puteva.\n\n"
            ."To je delom posledica istorije: ratovi, seobe i opasnosti često su terali monahe da biraju skrovita mesta.\n\n"
            ."Ali postoji i duhovni razlog: tišina pomaže čoveku da čuje sebe i da se usredsredi na molitvu.\n\n"
            ."U gradu je lako izgubiti ritam, jer sve traži našu pažnju.\n\n"
            ."U prirodi, posebno u planinama, ritam je jednostavniji i sporiji.\n\n"
            ."Skrovitost manastira tako postaje “škola” unutrašnjeg života.\n\n"
            ."Još jedna praktična stvar: voda.\n\n"
            ."Mnogi manastiri su podizani blizu izvora ili reka, jer je to bilo neophodno za život.\n\n"
            ."Zato kada danas dođeš u manastir i osetiš mir, često je to posledica prostora koji je građen baš za sabranost.\n\n"
            ."Skrovito mesto ne znači izolaciju od ljudi, već zaštitu od buke i rasutosti.";
    }

    private function textZvona(): string
    {
        return "Zvona u pravoslavnoj tradiciji nisu samo “signal” da služba počinje.\n\n"
            ."Zvuk zvona je poziv na sabranje, ali i podsetnik da postoji vreme za molitvu.\n\n"
            ."Različiti praznici mogu imati različit način zvonjenja.\n\n"
            ."Ponekad se čuje svečanije i duže, ponekad tiše i kraće.\n\n"
            ."U nekim krajevima zvona prate liturgijski ritam cele godine.\n\n"
            ."Zanimljivo je da ljudi često osete promenu raspoloženja kad čuju zvona.\n\n"
            ."To je zato što zvuk “seče” svakodnevicu i podseća nas na nešto više.\n\n"
            ."Zvona su i zajednički glas: čuju ih i oni koji ne mogu da dođu u hram.\n\n"
            ."Tako zvono postaje tiha propoved bez reči.\n\n"
            ."I kad nisi spremna za molitvu, zvono te bar nakratko seti da zastaneš.";
    }

    private function textStajanje(): string
    {
        return "Mnogi prvi put primete da se u pravoslavnim hramovima uglavnom stoji.\n\n"
            ."Stajanje je znak poštovanja, ali i budnosti.\n\n"
            ."U starini, stajanje je bilo uobičajeno i u drugim javnim prilikama.\n\n"
            ."U crkvi, stajanje simbolično govori: mi smo pred Bogom, a ne u učionici.\n\n"
            ."Naravno, stariji, bolesni i oni koji ne mogu — uvek mogu da sednu.\n\n"
            ."Bog ne traži napor radi napora.\n\n"
            ."Suština je unutrašnje učešće.\n\n"
            ."Ako stojiš, a misli su ti daleko, propuštaš smisao.\n\n"
            ."Ako sediš, a moliš se sabrano, ti si već “unutra” u službi.\n\n"
            ."Zato je najvažnije razumeti da pravilo postoji da pomogne, a ne da slomi čoveka.";
    }

    private function textTamjan(): string
    {
        return "Tamjan je u hrišćanskoj tradiciji simbol molitve koja se uzdiže.\n\n"
            ."Dim tamjana podseća na ono što ne možemo da uhvatimo rukama, ali možemo da osetimo: prisustvo svetinje.\n\n"
            ."U bogosluženju, kadenje označava poštovanje prema oltaru, ikonama i narodu.\n\n"
            ."Zanimljivo je da se kadi i čovek, jer je pozvan da bude “hram Duha Svetoga”.\n\n"
            ."Miris takođe ima ulogu: pomaže sabranosti.\n\n"
            ."Neki ljudi imaju utisak da ih tamjan smiruje.\n\n"
            ."To je spoj simbolike i psihologije: miris veže iskustvo, stvara osećaj pripadnosti i pomaže da uđemo u molitvu.\n\n"
            ."Najlepše je kada tamjan ne doživljavamo kao “miris”, već kao deo jezika Liturgije.";
    }

    private function textMonah(): string
    {
        return "Život monaha često se idealizuje ili pogrešno shvata.\n\n"
            ."U stvarnosti, manastir je mesto rada, discipline i stalnog učenja.\n\n"
            ."Dan obično počinje rano, molitvom.\n\n"
            ."Zatim sledi poslušanje: rad u kuhinji, bašti, ikonopisanju, gostoprimstvu, biblioteci.\n\n"
            ."Molitva i rad se smenjuju, jer čovek nije samo “duh”, nego i telo.\n\n"
            ."Tišina je važna, ali nije cilj sama po sebi.\n\n"
            ."Tišina postoji da bi čovek bolje čuo Boga i sebe.\n\n"
            ."Monah ne beži od sveta iz mržnje prema ljudima.\n\n"
            ."On, naprotiv, često nosi ljude u molitvi.\n\n"
            ."Zato mnogi posetioci osete mir: u manastiru se živi sporije i sabranije.\n\n"
            ."To je lekcija i za nas u svetu: da usporimo, uredimo misli i vratimo se suštini.";
    }

    private function textIkone(): string
    {
        return "Kada pravoslavni hrišćanin celiva ikonu, on ne obožava drvo i boju.\n\n"
            ."Poštovanje se upućuje ličnosti koja je na ikoni prikazana.\n\n"
            ."Ikona je prozor, ne zid.\n\n"
            ."Zato je i način slikanja drugačiji: cilj nije realistična fotografija, nego duhovna poruka.\n\n"
            ."Ikone nas uče da svet nije samo materija.\n\n"
            ."One podsećaju da su svetitelji naši prijatelji i uzori.\n\n"
            ."Celivanje ikone je znak ljubavi i poštovanja, kao kada celivamo ruku majci ili baku — ne zbog “ruke”, nego zbog osobe.\n\n"
            ."Važno je i da se ikone ne tretiraju kao amajlije.\n\n"
            ."One imaju smisla u veri, molitvi i crkvenom životu.";
    }

    private function textPost(): string
    {
        return "Post u pravoslavlju je mnogo širi od jelovnika.\n\n"
            ."Naravno, hrana je vidljivi deo, ali suština je unutrašnja promena.\n\n"
            ."Post nas uči da ne moramo odmah da zadovoljimo svaku želju.\n\n"
            ."To je škola slobode: da mi upravljamo navikama, a ne navike nama.\n\n"
            ."Tokom posta, posebno se naglašavaju praštanje, milostinja i smirenje.\n\n"
            ."Ako čovek posti, a pritom se svađa, osuđuje i prezire druge — promašio je suštinu.\n\n"
            ."Zato se često kaže: bolje je jesti skromnije i biti miran, nego “strogo postiti” i biti grub.\n\n"
            ."Post tako postaje vreme čišćenja: reči, misli, dela.\n\n"
            ."I kada se tako razume, post donosi stvarnu radost, a ne pritisak.";
    }

    private function textTriPrsta(): string
    {
        return "Krštenje sa tri prsta je jedan mali gest, ali nosi veliku poruku.\n\n"
            ."Tri spojena prsta ispovedaju veru u Svetu Trojicu: Oca, Sina i Svetoga Duha.\n\n"
            ."Dva savijena prsta podsećaju na dve prirode Hrista: božansku i čovečansku.\n\n"
            ."Tako se u jednom pokretu čuva osnovna vera.\n\n"
            ."Smer krštenja u pravoslavlju takođe prati predanje.\n\n"
            ."Ali najvažnije je da krštenje ne bude mehanički pokret.\n\n"
            ."Ako se krstimo, a pritom mislimo na deset stvari, gubi se smisao.\n\n"
            ."Bolje je prekrstiti se sporije, sa kratkom molitvom u sebi.";
    }

    private function textKupole(): string
    {
        return "Kupola na pravoslavnom hramu nije samo arhitektonska lepota.\n\n"
            ."Ona simboliše nebo koje se nadvija nad vernim narodom.\n\n"
            ."Kada uđeš u hram i pogledaš gore, prostor te “podiže”.\n\n"
            ."To je fizički način da se čovek podseti na uzvišenost molitve.\n\n"
            ."Kupole često utiču i na akustiku: pojanje dobija punoću.\n\n"
            ."Svetlost u kupoli takođe ima smisla: prozori i raspored svetla stvaraju doživljaj mekoće.\n\n"
            ."Zato pravoslavni hramovi deluju mirno i svečano čak i kad su prazni.\n\n"
            ."Arhitektura je, na neki način, teologija u kamenu.";
    }

    private function textMitIstina(): string
    {
        return "Mnogi crkveni običaji žive u narodu, ali ponekad se vremenom izvitopere.\n\n"
            ."Neko kaže: “mora ovako”, ali ne zna zašto.\n\n"
            ."Prva zabluda je da je forma važnija od smisla.\n\n"
            ."Ako čovek dođe u hram sa skrušenošću, Bog neće odbaciti molitvu zato što nije znao “pravilo marame”.\n\n"
            ."Druga zabluda je da sve deluje kao magija: zapali sveću pa će se sve rešiti.\n\n"
            ."U pravoslavlju ništa nije magijski automatizam.\n\n"
            ."Sve ima smisla samo u odnosu sa Bogom i u životu vere.\n\n"
            ."Treća zabluda je osuđivanje drugih: “ti nisi pravi jer ne radiš kao ja”.\n\n"
            ."Pravoslavna tradicija uči smirenju, a ne takmičenju.\n\n"
            ."Najzdravije je pitati sveštenika ili nekog iskusnog vernika, bez stida.\n\n"
            ."Jer cilj nije da budemo “tačni”, nego da budemo bliži Bogu.";
    }

    private function textZnanje(): string
    {
        return "U prošlosti su manastiri bili mnogo više od mesta molitve.\n\n"
            ."U njima su se prepisivale knjige, čuvali letopisi i prenosilo znanje.\n\n"
            ."Kada je narod prolazio kroz teška vremena, manastiri su često bili utočište kulture.\n\n"
            ."Tamo su nastajali rukopisi, prevodi, hronike.\n\n"
            ."Zadužbine su imale i škole, biblioteke, radionice.\n\n"
            ."Zato se može reći da su manastiri čuvali pamćenje naroda.\n\n"
            ."Danas, kada sve deluje dostupno na internetu, lako je zaboraviti koliko je teško bilo sačuvati jednu knjigu.\n\n"
            ."Ali baš zato je važno poštovati tu ulogu.\n\n"
            ."Manastir nije samo “turističko mesto”, nego i istorijski arhiv živog naroda.";
    }

    private function textMir(): string
    {
        return "Mnogi ljudi kažu da u manastiru osećaju mir koji ne osećaju u gradu.\n\n"
            ."To nije slučajno.\n\n"
            ."Prvo, priroda utiče na čoveka: tišina, drveće, voda, planina.\n\n"
            ."Drugo, ritam manastira je sporiji.\n\n"
            ."Nema stalne buke, žurbe i prekidanja.\n\n"
            ."Treće, molitva “menja vazduh” — ne kao magija, već kao atmosfera sabranosti.\n\n"
            ."Kada dođeš na takvo mesto, i tvoje misli polako usporavaju.\n\n"
            ."Tada čovek prvi put primeti koliko je inače rasut.\n\n"
            ."Manastirski mir je poziv da taj mir poneseš kući.\n\n"
            ."Ne da postaneš monah, nego da u svom životu napraviš prostor za tišinu.\n\n"
            ."Jer mir nije mesto — mir je stanje srca koje se uči.";
    }
}