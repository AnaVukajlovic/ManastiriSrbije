@extends('layouts.site')

@section('title','Manastiri kao zadužbine — Edukacija')
@section('nav_edukacija', 'active')

@section('content')
<style>
  .lesson-page{
    --ls-ink: rgba(255,255,255,.92);
    --ls-muted: rgba(255,255,255,.92);
    --ls-soft: rgba(255,255,255,.03);
    --ls-soft-2: rgba(255,255,255,.02);
    --ls-line: rgba(255,255,255,.08);
    --ls-gold-soft: rgba(197,162,74,.10);
    --ls-gold-line: rgba(197,162,74,.20);
    --ls-shadow: 0 18px 45px rgba(0,0,0,.30);
    --ls-shadow-2: 0 12px 30px rgba(0,0,0,.22);
    --ls-radius: 24px;
  }

  .lesson-page .container{
    width:min(1320px, calc(100% - 28px));
    max-width:none;
  }

  .lesson-hero{
    display:grid;
    grid-template-columns:minmax(0, 1fr);
    gap:20px;
    margin-bottom:24px;
  }

  .lesson-hero__main,
  .lesson-hero__side,
  .lesson-toc,
  .lesson-card,
  .lesson-panel{
    position:relative;
    overflow:hidden;
    border-radius:24px;
    border:1px solid var(--ls-line);
    background:
      radial-gradient(circle at top right, rgba(197,162,74,.10), transparent 28%),
      linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,.015)),
      rgba(20,12,12,.88);
    box-shadow:var(--ls-shadow-2);
  }

  .lesson-hero__main{
    padding:34px 36px 30px;
  }

  .lesson-badge{
    display:inline-flex;
    width:max-content;
    align-items:center;
    gap:8px;
    padding:8px 14px;
    border-radius:999px;
    background:rgba(197,162,74,.10);
    border:1px solid rgba(197,162,74,.20);
    color:var(--gold);
    font-size:.82rem;
    font-weight:700;
    margin-bottom:16px;
  }

.lesson-title{
  margin:0 0 14px;
  font-size:clamp(2.2rem, 4vw, 3.3rem);
  line-height:1.08;
  letter-spacing:-.02em;
  color:var(--gold);
}

  .lesson-sub{
    margin:0;
    color:var(--ls-ink);
    font-size:1rem;
    line-height:1.9;
    text-align:justify;
    text-justify:inter-word;
  }

  .lesson-hero__meta{
    margin-top:20px;
    display:flex;
    flex-wrap:wrap;
    gap:10px;
  }

  .lesson-pill{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:8px 13px;
    border-radius:999px;
    border:1px solid rgba(255,255,255,.10);
    background:rgba(255,255,255,.04);
    color:var(--ls-ink);
    font-size:.8rem;
    font-weight:600;
  }

  .lesson-pill--gold{
    border-color:rgba(197,162,74,.20);
    background:rgba(197,162,74,.10);
    color:var(--gold);
  }

  .lesson-hero__side{
    padding:24px 26px;
  }

  .lesson-side__label{
    display:inline-flex;
    width:max-content;
    padding:7px 12px;
    border-radius:999px;
    background:rgba(255,255,255,.04);
    border:1px solid rgba(255,255,255,.08);
    color:var(--ls-ink);
    font-size:.78rem;
    font-weight:700;
    margin-bottom:14px;
  }

  .lesson-side__quote{
    margin:0;
    color:var(--ls-ink);
    line-height:1.9;
    font-size:1rem;
    text-align:justify;
    text-justify:inter-word;
  }

  .lesson-side__foot{
    margin-top:16px;
    color:var(--ls-ink);
    font-size:.88rem;
    line-height:1.72;
    text-align:justify;
    text-justify:inter-word;
  }

  .lesson-grid{
    display:grid;
    grid-template-columns:minmax(0, 1fr);
    gap:22px;
    align-items:start;
  }

  .lesson-toc{
    padding:24px 26px;
  }
.lesson-toc__title{
  margin:0 0 8px;
  color:var(--gold);
  font-size:1.35rem;
  font-weight:800;
}

  .lesson-toc__sub{
    margin:0 0 16px;
    color:var(--ls-ink);
    font-size:.95rem;
    line-height:1.75;
    text-align:justify;
    text-justify:inter-word;
  }

  .lesson-toc__list{
    margin:0;
    padding:0;
    list-style:none;
    display:grid;
    grid-template-columns:repeat(2, minmax(0, 1fr));
    gap:12px 20px;
  }

  .lesson-toc__item{
    display:flex;
    align-items:center;
    gap:10px;
    min-height:52px;
    padding:12px 14px;
    border-radius:16px;
    background:rgba(255,255,255,.03);
    border:1px solid rgba(255,255,255,.06);
    color:var(--ls-ink);
    font-size:.95rem;
    line-height:1.5;
  }

  .lesson-toc__num{
    width:28px;
    height:28px;
    flex-shrink:0;
    display:grid;
    place-items:center;
    border-radius:999px;
    background:rgba(197,162,74,.10);
    border:1px solid rgba(197,162,74,.20);
    color:var(--gold);
    font-size:.78rem;
    font-weight:800;
  }

  .lesson-toc__note{
    margin-top:16px;
    padding:14px 16px;
    border-radius:16px;
    background:rgba(255,255,255,.03);
    border:1px solid rgba(255,255,255,.06);
    color:var(--ls-ink);
    line-height:1.72;
    font-size:.92rem;
    text-align:justify;
    text-justify:inter-word;
  }

  .lesson-main{
    display:grid;
    gap:22px;
    width:100%;
  }

  .lesson-card{
    padding:32px 34px 30px;
  }

  .lesson-card__eyebrow{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:7px 12px;
    border-radius:999px;
    border:1px solid rgba(197,162,74,.20);
    background:rgba(197,162,74,.10);
    color:var(--gold);
    font-size:.76rem;
    font-weight:700;
    margin-bottom:14px;
  }

.lesson-card h2{
  margin:0 0 16px;
  color:var(--gold);
  font-size:1.75rem;
  line-height:1.2;
  font-weight:800;
}
.lesson-card h3{
  margin:28px 0 10px;
  color:var(--gold);
  font-size:1.18rem;
  line-height:1.3;
  font-weight:800;
}
  .lesson-card p{
    margin:0 0 16px;
    color:var(--ls-ink);
    line-height:1.95;
    font-size:1.02rem;
    text-align:justify;
    text-justify:inter-word;
    max-width:none;
  }

  .lesson-card p:last-child{
    margin-bottom:0;
  }

  .lesson-highlight{
    margin:22px 0 10px;
    padding:18px 18px;
    border-radius:18px;
    background:linear-gradient(180deg, rgba(197,162,74,.09), rgba(255,255,255,.02));
    border:1px solid rgba(197,162,74,.16);
    color:var(--ls-ink);
    line-height:1.86;
    font-size:1rem;
    text-align:justify;
    text-justify:inter-word;
  }

  .lesson-panel{
    padding:22px 22px;
  }

.lesson-panel__title{
  margin:0 0 10px;
  color:var(--gold);
  font-size:1.08rem;
  font-weight:800;
}

  .lesson-panel__text{
    margin:0;
    color:var(--ls-ink);
    line-height:1.8;
    font-size:.97rem;
    text-align:justify;
    text-justify:inter-word;
  }

  .lesson-mini-grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
  }

  .lesson-chip-list{
    display:flex;
    flex-wrap:wrap;
    gap:8px;
    margin-top:14px;
  }

  .lesson-chip{
    padding:7px 12px;
    border-radius:999px;
    border:1px solid rgba(197,162,74,.20);
    background:rgba(197,162,74,.10);
    color:var(--gold);
    font-size:.8rem;
    font-weight:700;
  }

  @media (max-width: 1200px){
    .lesson-toc__list,
    .lesson-mini-grid{
      grid-template-columns:1fr;
    }
  }

  @media (max-width: 760px){
    .lesson-page .container{
      width:min(100%, calc(100% - 20px));
    }

    .lesson-hero__main,
    .lesson-hero__side,
    .lesson-toc,
    .lesson-card,
    .lesson-panel{
      padding:20px;
      border-radius:18px;
    }

    .lesson-title{
      font-size:clamp(1.95rem, 8vw, 2.7rem);
    }

    .lesson-sub,
    .lesson-card p,
    .lesson-panel__text,
    .lesson-highlight{
      font-size:.97rem;
    }

    .lesson-card h2{
      font-size:1.42rem;
    }

    .lesson-card h3{
      font-size:1.06rem;
    }
  }
</style>

<section class="section lesson-page">
  <div class="container">

    <div class="lesson-hero">
      <div class="lesson-hero__main">
        <div>
          <span class="lesson-badge">🏛️ Edukacija • Manastiri kao zadužbine</span>
          <h1 class="lesson-title">Manastiri kao zadužbine</h1>
          <p class="lesson-sub">
            U srednjovekovnoj Srbiji manastiri nisu bili samo mesta molitve. Oni su nastajali kao izraz vere,
            zahvalnosti, zaveta i istorijskog pamćenja. U njima su se susretali duhovni život, državna ideja,
            umetnost, pismenost i trajna želja da se iza sebe ostavi delo koje će živeti duže od jednog ljudskog veka.
          </p>
        </div>

        <div class="lesson-hero__meta">
          <span class="lesson-pill lesson-pill--gold">ktitori</span>
          <span class="lesson-pill">zadužbine</span>
          <span class="lesson-pill">duhovno nasleđe</span>
          <span class="lesson-pill">srednji vek</span>
        </div>
      </div>

      <div class="lesson-hero__side">
        <span class="lesson-side__label">Istorijska lekcija</span>
        <p class="lesson-side__quote">
          Manastir kao zadužbina bio je i molitva u kamenu, i politička poruka, i mesto gde se narod
          učio veri, pismenosti i sopstvenom pamćenju.
        </p>
        <div class="lesson-side__foot">
          Ova lekcija povezuje istoriju države, ulogu ktitora i nastanak manastira sa širim kulturnim i duhovnim kontekstom.
        </div>
      </div>
    </div>

    <div class="lesson-grid">
      <aside class="lesson-toc">
        <h3 class="lesson-toc__title">Sadržaj lekcije</h3>
        <p class="lesson-toc__sub">
          Pregled glavnih celina kroz koje možeš pratiti temu zadužbinarstva i uloge manastira u srednjovekovnoj Srbiji.
        </p>

        <div class="lesson-toc__list">
          <div class="lesson-toc__item"><span class="lesson-toc__num">1</span><span>Šta znači zadužbina</span></div>
          <div class="lesson-toc__item"><span class="lesson-toc__num">2</span><span>Ko su bili ktitori</span></div>
          <div class="lesson-toc__item"><span class="lesson-toc__num">3</span><span>Zašto su manastiri podizani</span></div>
          <div class="lesson-toc__item"><span class="lesson-toc__num">4</span><span>Manastiri kao duhovni centri</span></div>
          <div class="lesson-toc__item"><span class="lesson-toc__num">5</span><span>Manastiri kao centri pismenosti i umetnosti</span></div>
          <div class="lesson-toc__item"><span class="lesson-toc__num">6</span><span>Društvena i politička uloga zadužbina</span></div>
          <div class="lesson-toc__item"><span class="lesson-toc__num">7</span><span>Trajanje zadužbinske ideje</span></div>
          <div class="lesson-toc__item"><span class="lesson-toc__num">8</span><span>Zaključak</span></div>
        </div>

        <div class="lesson-toc__note">
          Ova lekcija je osmišljena kao pregledna i celovita uvodna stranica u temu zadužbinarstva, sa fokusom na istorijski,
          duhovni i kulturni značaj manastira.
        </div>
      </aside>

      <div class="lesson-main">

        <article class="lesson-card">
          <span class="lesson-card__eyebrow">Uvod</span>
          <h2>Manastir kao trag vere i istorijskog pamćenja</h2>

          <p>
            Kada se govori o srednjovekovnoj Srbiji, gotovo je nemoguće odvojiti istoriju države od istorije
            njenih manastira. Svetinje koje su podizali vladari, članovi njihovih porodica, vlastela i
            crkveni velikodostojnici nisu bile samo građevine namenjene bogosluženju. One su u sebi spajale
            duhovnu namenu, lični zavet ktitora, političku poruku vremena u kome nastaju i kulturno nasleđe
            koje nadilazi generacije. Zato je pojam zadužbine u srpskoj istoriji daleko dublji od običnog
            pojma gradnje crkve ili manastira.
          </p>

          <p>
            Zadužbina je bila delo podignuto sa svešću da ostaje iza svog osnivača. U njenom temelju nalazila
            se vera da čovek svojim delom može da posvedoči zahvalnost Bogu, da ostavi trag pokajanja, da
            sačuva uspomenu na porodicu i da doprinese zajednici. U tom smislu manastiri su postajali mesta
            gde se molitva susretala sa istorijom. U njima su se služile liturgije, prepisivale knjige,
            oslikavali zidovi, sahranjivali vladari i čuvalo se sećanje na najvažnije događaje jednog naroda.
          </p>

          <div class="lesson-highlight">
            U srednjovekovnom poimanju sveta, zadužbina nije bila samo lična građevina nekog vladara. Bila je
            duhovni zavet, izraz odgovornosti pred Bogom i pokušaj da se iza sebe ostavi delo koje će koristiti
            i savremenicima i budućim pokolenjima.
          </div>

          <h3>Šta zapravo znači reč „zadužbina“</h3>
          <p>
            Sama reč zadužbina upućuje na nešto što je „zadato“, ostavljeno kao obaveza, zavet ili trajni
            prilog. U srpskoj tradiciji ona se najčešće vezuje za manastire i crkve koje su podizali ktitori,
            ali njeno značenje nije samo materijalno. Zadužbina podrazumeva i duhovnu nameru: da se nekim delom
            trajno služi Bogu i narodu. Zbog toga su mnoge zadužbine nastajale kao izraz zahvalnosti za pobedu,
            za dobijenu vlast, za iskupljenje greha, za molitvu za porodicu ili za spas duše.
          </p>

          <p>
            Kod srpskih vladara i velikaša zadužbina je često imala i porodični karakter. Ona je bila mesto gde
            se porodica moli, gde se čuva uspomena na pretke i gde se obezbeđuje duhovni kontinuitet loze.
            Mnogi vladari birali su upravo svoje zadužbine za mesto sahrane, čime su dodatno naglašavali vezu
            između lične sudbine, državne ideje i svetinje koju podižu.
          </p>

          <h3>Ko su bili ktitori</h3>
          <p>
            Ktitor je osnivač ili obnovitelj crkve i manastira. U srpskoj srednjovekovnoj istoriji to su
            najčešće bili vladari iz dinastije Nemanjića, članovi njihovih porodica, vlastela, crkveni ljudi,
            pa ponekad i šire zajednice vernika. Ktitorstvo nije bilo samo pitanje bogatstva i moći. Ono je bilo
            vezano za predstavu da čovek, posebno onaj koji nosi odgovornost za druge, treba da ostavi delo koje
            će imati duhovnu vrednost i opšti značaj.
          </p>

          <p>
            Zato se na freskama u mnogim manastirima mogu videti ktitorske kompozicije: osnivač drži model crkve
            ili manastira i prinosi ga Hristu, Bogorodici ili svetitelju zaštitniku. Ta predstava nije samo
            umetnički motiv. Ona pokazuje da ktitor svoju zadužbinu ne doživljava kao lični posed, već kao dar
            i zavet. Time je jasno naglašeno da prava vrednost zadužbine nije u raskoši, nego u njenoj nameni i
            duhovnom smislu.
          </p>

          <h3>Zašto su manastiri podizani</h3>
          <p>
            Razlozi za podizanje manastira bili su brojni i često su se međusobno preplitali. Najpre je tu bio
            religijski razlog: želja da se Bogu podigne hram, da se osnuje mesto molitve i monaškog života i da
            se time učvrsti hrišćanski poredak u državi. U vreme jačanja srpske srednjovekovne države to je bilo
            od naročitog značaja, jer su manastiri predstavljali vidljive znakove duhovnog i političkog uređenja.
          </p>

          <p>
            Drugi važan razlog bio je zadužbinski i porodični. Vladari su podizali manastire kao svoje grobne
            crkve, kao mesta molitve za sebe i svoju porodicu, ali i kao trajna dela po kojima će biti zapamćeni.
            Treći razlog bio je kulturni i prosvetni. Manastir je veoma brzo postajao središte u kome se ne
            okupljaju samo monasi, već i pisari, slikari, graditelji i vernici. Tako je zadužbina prelazila
            granice ličnog zaveta i postajala opšte dobro.
          </p>

          <p>
            Postojao je i politički razlog. U srednjem veku podizanje manastira imalo je snažnu simboličku
            vrednost. Time je vladar pokazivao svoju moć, pobožnost, legitimnost i povezanost sa Crkvom.
            Zadužbina je tako istovremeno govorila o veri njenog osnivača i o mestu koje on zauzima u poretku
            države.
          </p>
        </article>

        <article class="lesson-card">
          <span class="lesson-card__eyebrow">Glavni deo</span>
          <h2>Manastiri kao središta duhovnog, kulturnog i društvenog života</h2>

          <p>
            U svakodnevnom životu srednjovekovne Srbije manastiri su imali mnogo širu ulogu nego što se danas na
            prvi pogled može pretpostaviti. Oni jesu bili mesta bogosluženja, tišine i molitve, ali su istovremeno
            bili i prostori u kojima se čuvalo znanje, negovala umetnost i održavala veza između naroda, Crkve i
            vladarske vlasti. U tom spoju duhovnog i praktičnog ogleda se njihova posebna važnost.
          </p>

          <h3>Manastir kao duhovni centar</h3>
          <p>
            Najpre, manastir je bio prostor monaškog podviga. U njemu se živelo po pravilima crkvenog poretka,
            kroz molitvu, poslušanje, post i bogosluženje. Takav život nije bio zatvoren samo u zidove manastira,
            već je imao širi uticaj na vernike koji su dolazili po savet, utehu i blagoslov. Zbog toga su
            zadužbine često postajale duhovni oslonac čitavih krajeva.
          </p>

          <p>
            U narodu je postojala snažna svest da svetinja nije samo građevina već živo mesto prisustva molitve.
            To je dodatno jačalo ugled manastira i činilo da oni postanu važna mesta sabiranja. Za mnoge ljude
            manastir je bio prostor gde se susreću vera, predanje i osećaj pripadnosti zajednici.
          </p>

          <h3>Manastir kao centar pismenosti</h3>
          <p>
            Jedna od najvažnijih uloga manastira bila je čuvanje pismenosti. U vreme kada su knjige bile retke i
            dragocene, upravo su manastirski skriptorijumi omogućavali prepisivanje rukopisa, očuvanje liturgijskih
            tekstova, zakonskih zbirki, žitija i drugih dela. Monasi su bili među najobrazovanijim ljudima svog
            vremena i zahvaljujući njima sačuvan je veliki deo srednjovekovne književne baštine.
          </p>

          <p>
            Kroz taj rad manastiri su postali mesta gde se znanje ne samo čuva već i umnožava. Svaki prepis rukopisa
            značio je produžavanje života jednom tekstu. Zato su zadužbine, iako podignute iz ličnog zaveta,
            postajale i ustanove od opšte kulturne vrednosti.
          </p>

          <h3>Manastir kao umetnički centar</h3>
          <p>
            Zadužbine su bile i najveći prostori umetničkog stvaralaštva svog doba. U njima su radili graditelji,
            kamenoresci, duboresci, ikonopisci i freskopisci. Srpska srednjovekovna umetnost ne može se razumeti
            bez manastira, jer su upravo u njima nastali njeni najlepši primeri. Zidovi manastira nisu bili samo
            ukras, već vizuelna teologija: kroz freske i ikone vernicima se prenosila biblijska poruka, istorija
            svetitelja i smisao crkvenog života.
          </p>

          <p>
            Arhitektura zadužbina takođe nosi snažnu poruku. Izbor mesta, plan crkve, spoljašnja obrada i unutrašnje
            oslikavanje zajedno čine celinu koja je istovremeno i molitvena i reprezentativna. Zato manastiri nisu
            bili podizani slučajno ili samo iz praktičnih razloga, već kao pažljivo osmišljena dela trajne vrednosti.
          </p>

          <h3>Društvena uloga manastira</h3>
          <p>
            Iako su prvenstveno bili duhovne ustanove, manastiri su imali i važnu društvenu funkciju. Oni su često
            pomagali putnicima, siromašnima i potrebitima, čuvali relikvije i dragocenosti, a ponekad bili i mesta
            gde se donose važne odluke ili sastavljaju povelje. U kriznim vremenima svetinje su postajale skloništa
            za ljude, knjige i sećanje.
          </p>

          <p>
            Upravo zbog te višestruke uloge, manastir kao zadužbina ne može se posmatrati samo kao religijski objekat.
            On je bio deo šireg društvenog poretka. Njegovo postojanje govorilo je o stepenu organizovanosti države,
            o odnosu prema veri, o kulturnim dometima epohe i o svesti da trajne vrednosti moraju imati i svoj
            materijalni izraz.
          </p>

          <div class="lesson-highlight">
            Kada se kaže da su manastiri bili centri duhovnosti, umetnosti i pismenosti, to nije samo opšta formula.
            To znači da su se upravo u njima oblikovali oni sadržaji bez kojih danas ne bismo mogli da razumemo
            ni srpsku srednjovekovnu istoriju, ni njenu kulturu, ni njeno trajanje kroz teške epohe.
          </div>

          <h3>Zadužbine kao politička i simbolička dela</h3>
          <p>
            Svaki veliki vladar nastojao je da iza sebe ostavi zadužbinu koja govori o njegovom vremenu. Na taj način
            manastir je postajao i politički simbol. On je svedočio o stabilnosti vlasti, o vezi sa pravoslavnim
            poretkom i o nastojanju da se država predstavi kao uređena i duhovno utemeljena zajednica. U tom smislu
            zadužbina nije bila samo privatno delo jednog ktitora, nego poruka celom društvu.
          </p>

          <p>
            Posebno je važno to što su zadužbine često nadživljavale političke promene. Vladari su dolazili i odlazili,
            granice su se menjale, ali su manastiri ostajali. Tako su postajali trajni svedoci epoha, čuvari uspomene
            i mesta sa kojih se mogla obnavljati svest o kontinuitetu. Zato je istorija srpskih zadužbina ujedno i
            istorija trajanja države i naroda u mnogo širem smislu.
          </p>
        </article>

        <article class="lesson-card">
          <span class="lesson-card__eyebrow">Zaključak</span>
          <h2>Zašto su zadužbine važne i danas</h2>

          <p>
            Danas, kada se posmatraju srednjovekovni manastiri Srbije, lako je uočiti njihova umetnička lepota,
            arhitektonska posebnost i istorijska vrednost. Međutim, da bi se njihovo značenje zaista razumelo,
            potrebno je videti ih u širem okviru zadužbinske ideje. Manastiri nisu nastali samo zato da budu lepe
            građevine, niti samo kao mesta bogosluženja. Oni su podizani sa svešću da budu trajna dela vere,
            pamćenja i odgovornosti.
          </p>

          <p>
            Zadužbine nas i danas podsećaju da istorija nije sastavljena samo od ratova, promena vlasti i političkih
            događaja. Ona je sačuvana i u delima koja su ljudi ostavili iza sebe. U slučaju srpske srednjovekovne
            tradicije to su upravo manastiri: mesta na kojima su se vekovima susretali molitva, umetnost, knjiga,
            sećanje i nada. Zato je njihova vrednost i savremena, a ne samo istorijska.
          </p>

          <p>
            Kada se u okviru ove aplikacije govori o manastirima, ktitorima i svetinjama, važno je imati na umu da
            iza svakog kamena, freske i zapisa stoji jedna dublja ideja. To je ideja da ono što se gradi iz vere,
            odgovornosti i ljubavi prema Bogu i narodu može da traje mnogo duže od vremena u kome je nastalo.
            Upravo zato su manastiri kao zadužbine jedno od najvažnijih poglavlja srpske istorije i kulture.
          </p>
        </article>

        <div class="lesson-mini-grid">
          <div class="lesson-panel">
            <h3 class="lesson-panel__title">Ključni pojmovi</h3>
            <p class="lesson-panel__text">
              Zadužbina, ktitor, manastir, skriptorijum, freska, ikonopis, duhovno nasleđe, srednji vek,
              monaštvo, kulturno pamćenje.
            </p>
            <div class="lesson-chip-list">
              <span class="lesson-chip">zadužbinarstvo</span>
              <span class="lesson-chip">ktitori</span>
              <span class="lesson-chip">svetinje</span>
            </div>
          </div>

          <div class="lesson-panel">
            <h3 class="lesson-panel__title">Veza sa ostatkom aplikacije</h3>
            <p class="lesson-panel__text">
              Ova lekcija prirodno se povezuje sa stranicama o ktitorima, pojedinačnim manastirima i budućim
              interaktivnim sadržajima kao što su vremenska linija, kviz i porodično stablo Nemanjića.
            </p>
            <div class="lesson-chip-list">
              <span class="lesson-chip">manastiri</span>
              <span class="lesson-chip">ktitori</span>
              <span class="lesson-chip">edukacija</span>
            </div>
          </div>
        </div>

      </div>
    </div>

  </div>
</section>
@endsection