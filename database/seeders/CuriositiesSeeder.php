<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CuriositiesSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('curiosities')->truncate();

        $items = [

            [
                "title" => "Zašto ljudi osećaju mir u manastiru",
                "slug" => Str::slug("Zašto ljudi osećaju mir u manastiru"),
                "category" => "Duhovni život",
                "image" => "images/curiosities/mir-u-manastiru.jpg",
                "reading_minutes" => 3,
                "excerpt" => "Mir koji ljudi osećaju u manastiru nije slučajan već dolazi iz duhovne atmosfere molitve i tišine.",
                "content" => "Manastiri vekovima predstavljaju mesta tišine, molitve i unutrašnjeg sabiranja. Ljudi koji ih posećuju često osećaju neobičan mir čim uđu u manastirski prostor. Taj mir nije slučajan, već nastaje iz ritma molitve koji monasi održavaju svakodnevno. U manastirima se dani provode u tišini, radu i bogosluženju, pa se i sam prostor oblikuje kao mesto sabranosti i smirenja.

U savremenom svetu čovek je često izložen buci, informacijama, žurbi i stalnoj napetosti. Kada dođe u manastir, makar na kratko izlazi iz takvog ritma i ulazi u drugačiji poredak vremena. Tišina, zvuk zvona, miris tamjana i prirodno okruženje pomažu da se misli uspore i da se čovek vrati sebi. Zato mnogi ljudi i bez posebnog objašnjenja osećaju olakšanje i mir već pri samom boravku u manastiru.

Pored spoljašnje tišine, važna je i unutrašnja dimenzija manastira. To su mesta u kojima se vekovima uznosi molitva, gde se neguju vera, poslušanje i duhovna disciplina. Posetioci često osećaju upravo tu atmosferu molitvenog života, čak i kada ne mogu odmah da je opišu rečima. Zbog toga mnogi ljudi upravo u manastirima pronalaze duhovni predah, smirenje i osećaj da su se udaljili od svakodnevnog nemira."
            ],

            [
                "title" => "Manastiri kao centri znanja: knjige, prepisivači i pamćenje naroda",
                "slug" => Str::slug("Manastiri kao centri znanja: knjige, prepisivači i pamćenje naroda"),
                "category" => "Istorija",
                "image" => "images/curiosities/manastiri-knjige.jpg",
                "reading_minutes" => 4,
                "excerpt" => "Manastiri su vekovima bili centri obrazovanja, pisanja i čuvanja istorije.",
                "content" => "U srednjem veku manastiri su imali ogromnu ulogu u očuvanju kulture, pismenosti i istorijskog pamćenja. U njima su monasi prepisivali knjige i stvarali rukopise koji su sačuvani do danas. Taj posao zahtevao je veliko strpljenje, tačnost i posvećenost, jer se jedna knjiga često prepisivala mesecima, a nekada i godinama. Zahvaljujući takvom radu sačuvana su mnoga dela crkvene književnosti, istorije i duhovne misli.

Manastiri nisu bili samo mesta gde se čuvaju knjige, već i mesta gde se znanje prenosi. Mladi ljudi su u njima učili čitanje i pisanje, a monasi su bili među retkima koji su imali pristup rukopisima, obrazovanju i bogatoj duhovnoj tradiciji. Zbog toga su manastiri postajali i obrazovni centri u kojima se oblikovalo kulturno lice jednog naroda.

Njihove biblioteke i danas predstavljaju dragoceno kulturno blago. U njima je sačuvano ne samo znanje, već i trag o vremenu, jeziku i duhovnom životu ljudi koji su te knjige stvarali i čuvali. Zato se s pravom kaže da su manastiri vekovima bili čuvari pamćenja naroda."
            ],

            [
                "title" => "Mit ili istina: najčešće zablude o crkvenim običajima",
                "slug" => Str::slug("Mit ili istina: najčešće zablude o crkvenim običajima"),
                "category" => "Običaji",
                "image" => "images/curiosities/crkvene-svece.jpg",
                "reading_minutes" => 3,
                "excerpt" => "Mnogi crkveni običaji imaju dublje značenje koje ljudi često pogrešno razumeju.",
                "content" => "U crkvenom životu postoji mnogo običaja koji su nastajali tokom vekova, ali ljudi ponekad pogrešno razumeju njihov smisao. Na primer, paljenje sveće nije magijski čin, već simbol molitve, nade i vere. Sveća predstavlja svetlost Hristovu i podseća vernike da čovek ne treba da živi samo spoljašnjim navikama, već i unutrašnjom sabranošću pred Bogom.

Slično važi i za druge običaje u crkvi. Poklonjenje ikonama, osenjivanje krstom, stajanje na bogosluženju ili uzimanje osvećene vode nisu radnje koje imaju smisao same po sebi. Njihova vrednost postoji samo kada su povezane sa verom, molitvom i razumevanjem onoga što čovek čini. Ako se običaji svode samo na spoljašnje ponavljanje, njihov pravi duhovni sadržaj ostaje neprepoznat.

Crkva zato ne naglašava običaj radi običaja, već veru koja mu daje smisao. Običaji imaju vrednost kada čoveka vode dubljem odnosu prema Bogu, bližnjima i sopstvenom životu. Tek tada oni postaju deo živog duhovnog iskustva, a ne samo nasledstvo bez razumevanja."
            ],

            [
                "title" => "Zašto pravoslavni hramovi imaju kupole",
                "slug" => Str::slug("Zašto pravoslavni hramovi imaju kupole"),
                "category" => "Arhitektura",
                "image" => "images/curiosities/pravoslavne-kupole.jpg",
                "reading_minutes" => 3,
                "excerpt" => "Kupole pravoslavnih hramova imaju duboku simboliku i predstavljaju nebo.",
                "content" => "Kupole su jedan od najprepoznatljivijih elemenata pravoslavnih crkava i imaju duboko simboličko značenje. Njihov oblik upućuje na nebo i Božije prisustvo, pa se pogled vernika pri ulasku u hram prirodno usmerava nagore. Na taj način sama arhitektura podseća čoveka da je hram prostor u kome se zemaljsko uzdiže ka nebeskom.

U mnogim kupolama nalazi se freska Hrista Pantokratora, odnosno Hrista kao Vladara sveta. To dodatno naglašava da se u središtu crkvenog prostora nalazi Bog, a ne čovek. Kupola tako ne služi samo estetskom utisku, već nosi poruku vere i pomaže da čitav prostor hrama dobije svoj duhovni smisao.

Pored simbolike, kupole imaju i praktičnu ulogu. One doprinose akustici u crkvi, pa se zvuk pojanja i bogosluženja lepše i ravnomernije širi kroz prostor. Zato su kupole istovremeno i arhitektonski, i liturgijski, i simbolički važan deo pravoslavnog hrama."
            ],

            [
                "title" => "Zašto se krstimo sa tri prsta: vera u Trojicu",
                "slug" => Str::slug("Zašto se krstimo sa tri prsta: vera u Trojicu"),
                "category" => "Osnovi vere",
                "image" => "images/curiosities/tri-prsta.jpg",
                "reading_minutes" => 3,
                "excerpt" => "Tri prsta pri krštenju simbolizuju veru u Svetu Trojicu i dve Hristove prirode.",
                "content" => "Kada se pravoslavni vernici krste, spajaju tri prsta desne ruke. Ta tri prsta simbolizuju veru u Svetu Trojicu: Oca, Sina i Svetoga Duha. Preostala dva prsta predstavljaju dve Hristove prirode, božansku i ljudsku. Sam čin krštenja ima duboku simboliku i nije samo navika, već ispovedanje vere kroz pokret.

Vernik dodiruje čelo, stomak i ramena pokazujući da je ceo čovek, i umom i srcem i telom, pozvan da pripada Bogu. Ovaj gest potiče iz starih vremena hrišćanske tradicije i vremenom je postao jedan od najprepoznatljivijih znakova pravoslavne vere. On nije samo spoljašnji pokret, već kratka molitva i podsećanje na temeljne istine hrišćanskog učenja.

Zato se pravoslavni vernici krste na molitvi, u crkvi i u mnogim važnim trenucima života. Krštenje izražava veru, traženje blagoslova i želju da čovek ostane pod Božijom zaštitom. Upravo zbog toga ovaj jednostavan gest ima tako veliko duhovno značenje."
            ],

            [
                "title" => "Post: više od hrane — škola slobode",
                "slug" => Str::slug("Post: više od hrane — škola slobode"),
                "category" => "Post",
                "image" => "images/curiosities/pravoslavni-post.jpg",
                "reading_minutes" => 3,
                "excerpt" => "Post u pravoslavlju nije samo promena ishrane već duhovna disciplina i vežba slobode.",
                "content" => "Post ima duboko duhovno značenje u pravoslavnoj tradiciji. Njegov cilj nije samo uzdržavanje od određene hrane, već celokupno usmeravanje čoveka ka sabranijem i pažljivijem životu. Post pomaže da se razviju samodisciplina, strpljenje i smirenje, ali i da čovek jasnije vidi sopstvene slabosti i navike.

U vremenu posta vernici se više posvećuju molitvi, uzdržanju, ispovesti i dobrim delima. Crkva naglašava da post bez ljubavi, milosrđa i rada na sebi nema puni smisao. Ako se svede samo na jelovnik, on gubi svoju najvažniju dimenziju. Suština posta jeste da čovek ne bude rob svojih želja, navika i trenutnih potreba.

Zato se post često naziva školom slobode. On uči čoveka da upravlja sobom, a ne da njime upravljaju strasti i navike. Na taj način post postaje put unutrašnjeg sazrevanja, trezvenosti i dubljeg razumevanja duhovnog života."
            ],

            [
                "title" => "Poklonjenje ikonama: poštovanje, ne idolopoklonstvo",
                "slug" => Str::slug("Poklonjenje ikonama: poštovanje, ne idolopoklonstvo"),
                "category" => "Ikone",
                "image" => "images/curiosities/ikona-ruke.jpg",
                "reading_minutes" => 3,
                "excerpt" => "Pravoslavni vernici ne obožavaju drvo i boju, već iskazuju poštovanje onome ko je na ikoni prikazan.",
                "content" => "Ikone u pravoslavlju imaju posebno mesto u duhovnom životu. One nisu samo slike, već vidljivi podsetnik na Hrista, Bogorodicu i svetitelje. Kada vernici celivaju ikonu, oni ne obožavaju drvo, boju ili materijal od kog je ikona napravljena. Poštovanje je usmereno ka osobi koja je na ikoni prikazana.

Ikona se zato često naziva prozorom ka duhovnoj stvarnosti. Ona ne služi samo da ukrasi prostor, već da pomogne verniku da usmeri misli ka Bogu i svetosti. Kroz vekove je Crkva jasno razlikovala poštovanje ikona od idolopoklonstva. Idolopoklonstvo pripisuje božansku vrednost samom predmetu, dok ikona upućuje na onoga koga predstavlja.

Zbog toga ikone zauzimaju važno mesto i u crkvama i u domovima. Njihovo poštovanje podseća na prisutnost vere u svakodnevnom životu i pomaže čoveku da razvije dublji molitveni odnos. Upravo zato poklonjenje ikonama u pravoslavlju predstavlja izraz vere i poštovanja, a ne obožavanje predmeta."
            ],

            [
                "title" => "Kako izgleda život jednog monaha: dan koji uči tišini",
                "slug" => Str::slug("Kako izgleda život jednog monaha: dan koji uči tišini"),
                "category" => "Monaški život",
                "image" => "images/curiosities/monah-manastir.jpg",
                "reading_minutes" => 3,
                "excerpt" => "Monaški dan je jednostavan, disciplinovan i ispunjen molitvom, radom i tišinom.",
                "content" => "Život monaha razlikuje se od uobičajenog ritma sveta. Dan u manastiru počinje rano, često pre izlaska sunca, i od samog početka je usmeren na molitvu. Prvi deo dana posvećen je bogosluženju, čitanju i unutrašnjem sabiranju, a zatim slede svakodnevni poslovi bez kojih manastirski život ne bi mogao da funkcioniše.

Monasi rade u bašti, kuhinji, radionici, biblioteci ili pri dočeku gostiju. Taj rad nije samo praktična obaveza, već deo duhovne discipline. U manastiru se rad i molitva ne suprotstavljaju, već dopunjuju. Kroz takav ritam čovek uči strpljenju, pažnji, jednostavnosti i unutrašnjem miru.

Tokom dana smenjuju se molitva, ćutanje i korisni poslovi. Upravo taj spoj čini da monaški život mnogima deluje tih, ali duboko smislen. On pokazuje da nije neophodno živeti u stalnoj užurbanosti da bi život bio ispunjen. Naprotiv, sabran i jednostavan ritam često čoveku otkriva dublji mir."
            ],

            [
                "title" => "Miris tamjana: molitva koja se uzdiže",
                "slug" => Str::slug("Miris tamjana: molitva koja se uzdiže"),
                "category" => "Bogosluženje",
                "image" => "images/curiosities/tamjan-kadionica.jpg",
                "reading_minutes" => 3,
                "excerpt" => "Tamjan u pravoslavnom bogosluženju simbolizuje molitvu koja se uzdiže ka Bogu.",
                "content" => "Tamjan zauzima posebno mesto u pravoslavnom bogosluženju. Kada se pali, njegov miris ispunjava prostor hrama i stvara atmosferu sabranosti i poštovanja. Dim tamjana simbolizuje molitvu vernika koja se uzdiže ka Bogu, pa ovaj čin ima duboko biblijsko i liturgijsko značenje.

Kadionica kojom se tamjan raznosi koristi se vekovima i sastavni je deo bogoslužbenog poretka. Kroz miris, pokret i dim vernik ne doživljava molitvu samo razumom, već i čitavim bićem. Zato je tamjan više od običnog mirisa — on učestvuje u oblikovanju liturgijskog iskustva.

Mnogi ljudi upravo miris tamjana povezuju sa mirom, hramom i osećajem svetinje. On pomaže čoveku da se dublje unese u molitvu i da oseti da je bogosluženje nešto više od običnog okupljanja. Zato tamjan ostaje jedan od najupečatljivijih simbola pravoslavne molitve."
            ],

            [
                "title" => "Zašto se u crkvi stoji: smisao budnosti i poštovanja",
                "slug" => Str::slug("Zašto se u crkvi stoji: smisao budnosti i poštovanja"),
                "category" => "Običaji",
                "image" => "images/curiosities/stajanje-u-crkvi.jpg",
                "reading_minutes" => 3,
                "excerpt" => "Stajanje u crkvi izražava pažnju, budnost i poštovanje pred Bogom.",
                "content" => "U pravoslavnoj tradiciji vernici tokom bogosluženja često stoje. To nije slučajno, već ima dubok smisao. Stajanje izražava pažnju, budnost i poštovanje pred Bogom. Ono pokazuje da čovek ne prisustvuje bogosluženju ravnodušno, već aktivno i sabrano.

U starim vremenima stajanje je bilo uobičajen molitveni stav. Sedenje se koristilo više kao izuzetak nego kao pravilo. Naravno, Crkva uvek pokazuje razumevanje prema bolesnima, starima i umornima, jer suština nije u spoljašnjem naporu, već u unutrašnjem raspoloženju. Ipak, sam stav tela često pomaže čoveku da bude pažljiviji i da se manje rasipa mislima.

Zato se stajanje u crkvi posmatra kao znak duhovne budnosti. Ono podseća da molitva zahteva prisutnost celog čoveka — i tela i misli i srca. Na taj način i spoljašnji stav dobija dublje unutrašnje značenje."
            ],

            [
                "title" => "Zvona: kada zvone i šta poručuju",
                "slug" => Str::slug("Zvona: kada zvone i šta poručuju"),
                "category" => "Bogosluženje",
                "image" => "images/curiosities/zvona-hrama.jpg",
                "reading_minutes" => 3,
                "excerpt" => "Zvona pozivaju vernike na molitvu i obaveštavaju zajednicu o važnim trenucima.",
                "content" => "Zvona imaju posebno mesto u životu pravoslavne zajednice. Njihov zvuk nije samo obaveštenje, već i poziv na molitvu. Kada zazvone pred bogosluženje, vernici znaju da je vreme da se saberu i dođu u hram. Tako zvona postaju deo zajedničkog duhovnog ritma.

Njihov ton može biti radostan, svečan ili ozbiljan, u zavisnosti od prilike. Zvona se oglašavaju na praznike, u važnim liturgijskim trenucima, ali i u trenucima tuge, opela i sećanja. Zbog toga zvona nisu samo praktičan znak, već i nosilac raspoloženja i poruke koju zajednica prepoznaje.

Kroz vekove su zvona postala važan deo crkvenog identiteta. Njihov zvuk širi se dalje od zidova hrama i povezuje ljude u molitvenom iskustvu. Zato ona imaju i praktičan i duboko simboličan značaj u životu vernika."
            ],

            [
                "title" => "Manastiri skriveni od sveta: zašto su građeni daleko od puteva",
                "slug" => Str::slug("Manastiri skriveni od sveta: zašto su građeni daleko od puteva"),
                "category" => "Manastiri",
                "image" => "images/curiosities/manastir-priroda.jpg",
                "reading_minutes" => 3,
                "excerpt" => "Mnogi manastiri građeni su u tišini prirode kako bi omogućili molitveni i sabrani život.",
                "content" => "Mnogi pravoslavni manastiri nalaze se daleko od velikih puteva i gradova. To nije slučajnost, već svesan izbor. Mirno okruženje pomaže monasima da vode sabran i tih život, udaljen od svakodnevne buke i rastrzanosti. Priroda tako postaje deo duhovnog prostora manastira.

Tišina šume, planine ili reke doprinosi molitvenoj atmosferi. U takvom okruženju lakše je usmeriti misli na molitvu, rad i unutrašnju disciplinu. Manastiri su često građeni upravo tamo gde je bilo moguće živeti jednostavno, skromno i sabrano. Istovremeno, takva mesta su u mnogim istorijskim periodima pružala i određenu zaštitu.

Posetioci i danas upravo zato doživljavaju manastire kao duhovna utočišta. Njihova udaljenost od sveta ne znači bekstvo od ljudi, već stvaranje prostora u kome se čovek može lakše sresti sa sobom, tišinom i Bogom. Zbog toga ta skrivenost ostaje deo njihove posebne lepote."
            ],

            [
                "title" => "Vladari koji su postali monasi: od krune do poslušanja",
                "slug" => Str::slug("Vladari koji su postali monasi: od krune do poslušanja"),
                "category" => "Istorija",
                "image" => "images/curiosities/vladar-monah.jpg",
                "reading_minutes" => 3,
                "excerpt" => "U srpskoj istoriji pojedini vladari su na kraju života birali monaški put.",
                "content" => "U istoriji srpskog naroda poznati su primeri vladara koji su postali monasi. Taj korak pokazuje koliko je u srednjovekovnom društvu duhovni život bio cenjen. Vladarska moć, ugled i bogatstvo nisu smatrani poslednjim ciljem čoveka, već prolaznim stvarima koje ne mogu zameniti mir duše.

Kada bi se povukli iz javnog života, pojedini vladari birali su monaštvo kao put pokajanja, smirenja i duhovne pripreme. Time su pokazivali da i najviši položaj u društvu nije iznad potrebe za ličnim preobražajem. U manastiru više nisu bili gospodari, već poslušnici, što je imalo snažnu poruku i za savremenike i za buduća pokolenja.

Takva promena puta ostala je upečatljiva u narodnom sećanju. Ona pokazuje da su i najmoćniji ljudi prolazni pred Bogom i da je duhovna vrednost veća od zemaljske slave. Upravo zbog toga priče o vladarima koji su postali monasi i danas imaju veliku snagu."
            ],

            [
                "title" => "Tajne fresaka: zašto svetitelji gledaju pravo u vas",
                "slug" => Str::slug("Tajne fresaka: zašto svetitelji gledaju pravo u vas"),
                "category" => "Freske",
                "image" => "images/curiosities/freska-lice.jpg",
                "reading_minutes" => 3,
                "excerpt" => "Pogled svetitelja na freskama ima duhovnu poruku i nije slučajan umetnički detalj.",
                "content" => "Freske u pravoslavnim hramovima oslikane su sa posebnom pažnjom i simbolikom. Vernici često primećuju da izgleda kao da ih svetitelji posmatraju. Taj utisak nije slučajan. U ikonografiji lice i pogled imaju veliku važnost, jer freske nisu zamišljene kao obični portreti, već kao svedočanstvo duhovne stvarnosti.

Svetitelji na freskama ne prikazuju se kao istorijske ličnosti zatvorene u prošlost, već kao živa prisutnost Crkve i vere. Pogled usmeren ka verniku podseća čoveka na lični odnos sa Bogom, na odgovornost i na budnost u duhovnom životu. Freske ne služe samo da ukrase zidove hrama, već i da poučavaju i vode vernika ka dubljem razumevanju vere.

Zato je svaki detalj pažljivo osmišljen. Kroz lice, boje, položaj tela i pogled prenosi se poruka koja prevazilazi običnu umetničku lepotu. Upravo zbog toga svetitelji na freskama deluju tako snažno i upečatljivo."
            ],

            [
                "title" => "Zašto se u pravoslavlju pale sveće?",
                "slug" => Str::slug("Zašto se u pravoslavlju pale sveće?"),
                "category" => "Običaji",
                "image" => "images/curiosities/paljenje-sveca.jpg",
                "reading_minutes" => 3,
                "excerpt" => "Paljenje sveće je izraz molitve, vere i nade, a ne običaj bez značenja.",
                "content" => "Paljenje sveća jedan je od najpoznatijih običaja u pravoslavnoj crkvi, ali njegov smisao je mnogo dublji od spoljašnjeg čina. Sveća predstavlja svetlost Hristovu koja osvetljava život vernika. Kada čovek pali sveću, on time izražava molitvu, nadu i želju da svoj život prinese Bogu sa verom i sabranošću.

Plamen podseća da vera treba da gori i u srcu čoveka. Sveće se pale za zdravlje, za pokoj duša, u trenucima tuge, zahvalnosti ili molitvenog traženja pomoći. Taj čin uvek prati tiha molitva i lična sabranost. Crkva uči da sama sveća nema magijsku moć i da njena vrednost nije u predmetu, već u veri sa kojom se prinosi.

Zato paljenje sveće ostaje lep i dubok znak molitvenog obraćanja Bogu. To je jednostavan, ali snažan izraz vere koji povezuje spoljašnji gest i unutrašnju molitvu."
            ],

        ];

        DB::table('curiosities')->insert($items);
    }
}