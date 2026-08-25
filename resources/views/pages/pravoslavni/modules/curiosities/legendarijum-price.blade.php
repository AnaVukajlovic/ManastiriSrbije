@extends('layouts.site')

@section('title', 'Legendarijum: Priče i predanja o Nemanjićima i Svetom Savi — Zanimljivosti')
@section('nav_curiosities', 'active')

@section('content')
<style>
  .legend-page {
    --lg-ink: rgba(255,255,255,.94);
    --lg-muted: rgba(255,255,255,.82);
    --lg-line: rgba(197,162,74,.24);
    --lg-gold: #c5a24a;
    --lg-gold-bright: #e2c26a;
    --lg-bg-card: linear-gradient(180deg, rgba(28, 18, 16, 0.96), rgba(16, 10, 10, 0.96));
    --lg-shadow: 0 20px 50px rgba(0,0,0,.45);
    max-width: 1280px;
    margin: 0 auto;
    padding-top: 15px;
    padding-bottom: 60px;
  }

  .legend-page .container {
    width: min(1240px, calc(100% - 32px));
    max-width: none;
  }

  /* HERO ZAGLAVLJE */
  .legend-hero {
    position: relative;
    padding: 36px 40px 32px;
    border-radius: 28px;
    border: 1.5px solid var(--lg-line);
    background: 
      radial-gradient(circle at top right, rgba(197,162,74,.15), transparent 45%),
      linear-gradient(180deg, rgba(32, 21, 18, 0.98), rgba(18, 11, 10, 0.98));
    box-shadow: var(--lg-shadow);
    margin-bottom: 35px;
    overflow: hidden;
  }

  .legend-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border-radius: 999px;
    background: rgba(197,162,74,.12);
    border: 1px solid rgba(197,162,74,.30);
    color: var(--lg-gold-bright);
    font-size: .86rem;
    font-weight: 700;
    margin-bottom: 16px;
    letter-spacing: 0.5px;
  }

  .legend-title {
    margin: 0 0 14px;
    font-size: clamp(1.6rem, 2.5vw, 2.2rem);
    line-height: 1.2;
    letter-spacing: -.01em;
    color: var(--lg-gold);
    text-shadow: 0 0 14px rgba(197,162,74,.15);
  }

  .legend-sub {
    margin: 0;
    color: var(--lg-ink);
    font-size: 1.15rem;
    line-height: 1.9;
    text-align: justify;
    text-justify: inter-word;
  }

  .legend-meta-strip {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 22px;
    padding-top: 18px;
    border-top: 1px solid rgba(255,255,255,.08);
  }

  .legend-chip {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    border-radius: 999px;
    background: rgba(255,255,255,.04);
    border: 1px solid rgba(255,255,255,.10);
    color: rgba(255,255,255,.9);
    font-size: .84rem;
    font-weight: 600;
  }

  /* BRZI SADRŽAJ (TOC) */
  .legend-toc {
    background: rgba(22, 14, 13, 0.85);
    border: 1px solid rgba(197, 162, 74, 0.22);
    border-radius: 20px;
    padding: 22px 26px;
    margin-bottom: 35px;
  }
  .legend-toc__title {
    margin: 0 0 14px 0;
    color: var(--lg-gold-bright);
    font-size: 1.15rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .legend-toc__grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
    gap: 10px;
  }
  .legend-toc__link {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 10px;
    color: rgba(255,255,255,.88);
    text-decoration: none;
    font-size: 0.94rem;
    background: rgba(255,255,255,.02);
    border: 1px solid transparent;
    transition: all 0.2s ease;
  }
  .legend-toc__link:hover {
    background: rgba(197,162,74,.10);
    border-color: rgba(197,162,74,.30);
    color: var(--lg-gold-bright);
    transform: translateX(3px);
  }

  /* KARTICE PRIČA */
  .story-card {
    position: relative;
    background: var(--lg-bg-card);
    border: 1.5px solid var(--lg-line);
    border-radius: 26px;
    padding: 36px 40px;
    margin-bottom: 35px;
    box-shadow: var(--lg-shadow);
    transition: border-color 0.3s ease;
  }
  .story-card:hover {
    border-color: rgba(197, 162, 74, 0.55);
  }

  .story-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 20px;
    margin-bottom: 22px;
    padding-bottom: 16px;
    border-bottom: 1px solid rgba(197,162,74,.20);
  }

  .story-tag {
    display: inline-block;
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--lg-gold-bright);
    background: rgba(197,162,74,.12);
    border: 1px solid rgba(197,162,74,.3);
    padding: 4px 12px;
    border-radius: 999px;
    margin-bottom: 8px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }

  .story-title {
    margin: 0;
    color: var(--lg-gold);
    font-size: clamp(1.6rem, 2.8vw, 2.2rem);
    line-height: 1.2;
    font-weight: 800;
  }

  .story-icon-badge {
    width: 58px;
    height: 58px;
    border-radius: 18px;
    background: rgba(197,162,74,.10);
    border: 1px solid rgba(197,162,74,.25);
    display: grid;
    place-items: center;
    font-size: 1.7rem;
    flex-shrink: 0;
    box-shadow: 0 8px 20px rgba(0,0,0,.3);
  }

  .story-intro-lead {
    font-size: 1.12rem;
    line-height: 1.9;
    color: #fff;
    font-style: italic;
    margin-bottom: 22px;
    padding: 16px 20px;
    border-left: 3.5px solid var(--lg-gold);
    background: rgba(197,162,74,.06);
    border-radius: 0 14px 14px 0;
    text-align: justify;
    text-justify: inter-word;
  }

  .story-body p {
    font-size: 1.04rem;
    line-height: 1.95;
    color: var(--lg-ink);
    margin-bottom: 18px;
    text-align: justify;
    text-justify: inter-word;
  }

  .story-quote-box {
    margin: 24px 0;
    padding: 20px 24px;
    background: linear-gradient(135deg, rgba(38, 26, 22, 0.8), rgba(20, 13, 11, 0.8));
    border: 1px solid rgba(197, 162, 74, 0.35);
    border-radius: 18px;
    position: relative;
  }
  .story-quote-box__text {
    font-size: 1.05rem;
    line-height: 1.8;
    color: #f7eedb;
    font-weight: 500;
    margin-bottom: 8px;
  }
  .story-quote-box__author {
    font-size: 0.86rem;
    color: var(--lg-gold-bright);
    font-weight: 700;
    text-align: right;
  }

  .story-separator {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 28px 0;
    position: relative;
  }
  .story-separator::before, .story-separator::after {
    content: "";
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(197, 162, 74, 0.4), transparent);
  }
  .story-separator__ornament {
    color: var(--lg-gold);
    font-size: 1.2rem;
    padding: 0 16px;
    opacity: 0.85;
  }

  .story-footer-nav {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    margin-top: 24px;
    padding-top: 16px;
    border-top: 1px solid rgba(255,255,255,.06);
  }

  .story-link-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 8px 16px;
    border-radius: 999px;
    background: rgba(197,162,74,.10);
    border: 1px solid rgba(197,162,74,.25);
    color: var(--lg-gold-bright);
    text-decoration: none;
    font-size: 0.88rem;
    font-weight: 700;
    transition: all 0.2s ease;
  }
  .story-link-btn:hover {
    background: rgba(197,162,74,.20);
    border-color: var(--lg-gold);
    transform: translateY(-1px);
  }

  /* IZVORI I LITERATURA */
  .sources-card {
    background: linear-gradient(180deg, rgba(24, 15, 14, 0.96), rgba(14, 9, 8, 0.96));
    border: 1.5px solid rgba(197, 162, 74, 0.35);
    border-radius: 24px;
    padding: 30px 36px;
    margin-top: 40px;
    box-shadow: var(--lg-shadow);
  }
  .sources-title {
    margin: 0 0 14px 0;
    color: var(--lg-gold);
    font-size: 1.3rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 10px;
  }
  .sources-list {
    margin: 0;
    padding-left: 24px;
    color: rgba(255,255,255,.84);
    font-size: 0.98rem;
    line-height: 1.9;
  }

  @media (max-width: 768px) {
    .legend-hero {
      padding: 24px 20px;
      border-radius: 20px;
    }
    .story-card {
      padding: 24px 18px;
      border-radius: 20px;
    }
    .story-head {
      flex-direction: column;
      gap: 12px;
    }
    .story-icon-badge {
      width: 46px;
      height: 46px;
      font-size: 1.3rem;
    }
    .story-intro-lead {
      font-size: 1rem;
      padding: 12px 14px;
    }
    .story-body p {
      font-size: 0.96rem;
    }
  }
</style>

<section class="section legend-page">
  <div class="container">

    {{-- DUGME ZA POVRATAK --}}
    <div style="margin-bottom: 20px;">
      <a href="{{ route('curiosities.index') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 12px; background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.10); color: rgba(255,255,255,.9); text-decoration: none; font-size: 0.9rem; font-weight: 700;">
        ← Nazad na Zanimljivosti
      </a>
    </div>

    {{-- HERO ZAGLAVLJE --}}
    <div class="legend-hero">
      <span class="legend-badge">📜 Zanimljivosti i predanja • Legendarijum</span>
      <h1 class="legend-title">Legendarijum i priče o Nemanjićima i Svetom Savi</h1>
      <p class="legend-sub">
        Zaviri u tajanstveni, živi svet srednjovekovnih predanja, dirljivih susreta i istorijskih anegdota.
        Ovde istorija ne govori samo kroz datume i povelje, već kroz topli narativ o ljudima od krvi i mesa —
        njihovim zavetima, iskušenjima, čudotvornim izmirenjima i neprolaznoj veri koja je utemeljila srpski duhovni svetionik.
      </p>

      <div class="legend-meta-strip">
        <span class="legend-chip">👑 Немањићи и владари</span>
        <span class="legend-chip">🕊️ Свети Сава</span>
        <span class="legend-chip">🐺 Народна предања</span>
        <span class="legend-chip">🏛️ Манастири и задужбине</span>
        <span class="legend-chip">📜 Српска житија и летописи</span>
      </div>
    </div>

    {{-- BRZI KORISNIČKI INDEKS (TOC) --}}
    <div class="legend-toc">
      <div class="legend-toc__title">
        <span>📖</span> Изабери причу за читање:
      </div>
      <div class="legend-toc__grid">
        <a class="legend-toc__link" href="#prica-1"><span>🕊️</span> 1. Бекство принца Растка на Свету Гору</a>
        <a class="legend-toc__link" href="#prica-2"><span>🕯️</span> 2. Мироточење и измирење браће у Студеници</a>
        <a class="legend-toc__link" href="#prica-3"><span>🐺</span> 3. Свети Сава и горски вуци (Заштитник вукова)</a>
        <a class="legend-toc__link" href="#prica-4"><span>💧</span> 4. Савине воде и чудотворни извори</a>
        <a class="legend-toc__link" href="#prica-5"><span>👑</span> 5. Краљ Милутин и завет од 40 светиња</a>
        <a class="legend-toc__link" href="#prica-6"><span>✨</span> 6. Враћени вид и градња Високих Дечана</a>
        <a class="legend-toc__link" href="#prica-7"><span>⚔️</span> 7. Душан Силни, царска круна и Законик</a>
        <a class="legend-toc__link" href="#prica-8"><span>🌹</span> 8. Кнез Лазар и вечни завет у Раваници</a>
        <a class="legend-toc__link" href="#prica-9"><span>🕊️</span> 9. Бели Анђео Милешеве и тајна зрачења мира</a>
        <a class="legend-toc__link" href="#prica-10"><span>🍇</span> 10. Чудотворна лоза Светог Симеона на Хиландару</a>
        <a class="legend-toc__link" href="#prica-11"><span>☀️</span> 11. Мудрост Светог Саве: Светлост у домовима</a>
        <a class="legend-toc__link" href="#prica-12"><span>🌸</span> 12. Царица Милица и удовице Косовског боја: Љубостиња</a>
        <a class="legend-toc__link" href="#prica-13"><span>🛡️</span> 13. Деспот Стефан: Витез Змаја, песник и Манасија</a>
        <a class="legend-toc__link" href="#prica-14"><span>⛰️</span> 14. Свети Василије Острошки: Молитва у стени и чуда</a>
      </div>
    </div>

    {{-- PRIČA 1: BEKSTVO PRINCA RASTKA --}}
    <article class="story-card" id="prica-1">
      <div class="story-head">
        <div>
          <span class="story-tag">Predanje o počecima duhovnosti</span>
          <h2 class="story-title">1. Noćno bekstvo princa Rastka na Svetu Goru</h2>
        </div>
        <div class="story-icon-badge">🕊️</div>
      </div>

      <div class="story-intro-lead">
        „Kada srce mladog princa ne žudi za krunom, mačem i zlatnim dvorovima, već za tihim plamenom sveće u atonskoj noći — počinje najlepša priča srpske pismenosti i duhovnog rođenja.”
      </div>

      <div class="story-body">
        <p>
          Zamislite humski dvor velikog župana Stefana Nemanje krajem 12. veka. Najmlađi Nemanjin sin, bistri i omiljeni Rastko, imao je tek oko sedamnaest godina. Pred njim je stajao čitav svet: kneževska vlast nad Humom, raskošno plemićko ruho, konji, lovovi i sigurna vladarska budućnost. Ali, kako nam svedoče stari životopisci Domentijan i Teodosije, mladog Rastka nije privlačila zemaljska slava. Njegov um je tražio nešto dublje i trajnije.
        </p>
        <p>
          Prilika se ukazala kada su na dvor njegovog oca došli monasi sa Svete Gore Atonske tražeći milostinju. Među njima beše i jedan mudri ruski kaluđer. Rastko se tajno sastajao sa njim, opčinjen pričama o pustinjskom monaškom životu gde nema spletki i borbi za vlast, već samo neprekidne molitve i mira. Doneta je sudbonosna odluka: pod izgovorom da odlazi u lov na jelene, mladi Rastko sa vernom pratnjom i ruskim monahom u gluvo doba noći krenu put juga, ka Egejskom moru.
        </p>

        <div class="story-quote-box">
          <div class="story-quote-box__text">
            „Kada Nemanja shvati da mu sina nema u lovu, nasta velika uzbuna. Potera najboljih vitezova na najbržim konjima pojuri za beguncem, sustigavši ga tek u zidinama ruskog manastira Svetog Pantelejmona (Rusika).”
          </div>
          <div class="story-quote-box__author">— Prema svedočenju Teodosija Hilandarca</div>
        </div>

        <p>
          Vojvoda koji je predvodio poteru imao je strogu Nemanjinu zapovest: vratiti princa živog, milom ili silom! Međutim, Rastko posluži vojnike bogatom večerom i vinom, te kada oni umorni zaspaše, on se pope na visoku manastirsku kulu. Tamo položi monaške zavete i primi ime <strong>Sava</strong>. U svitanje, začuđeni vojnici se probudiše i ugledaše sa kule monaha u skromnoj rizi. Sava im sa visine baci svoje raskošne kneževske haljine, zlatni pojas i odsečene vlasi kose, poručivši im: <em>„Nosite ovo mojim roditeljima kao znak da Rastko više ne pripada ovom svetu, već da se posvetio Caru Nebeskom.”</em>
        </p>
        <p>
          To bekstvo nije razbilo Nemanjinu porodicu — naprotiv, ono je postalo temelj na kome će kasnije i sam ostareli Nemanja poći za sinom, postati monah Simeon, te zajedno sagraditi Hilandar kao večnu srpsku lađu na Svetoj Gori.
        </p>
      </div>

      <div class="story-separator"><span class="story-separator__ornament">❧</span></div>
    </article>

    {{-- PRIČA 2: MIROTOČENJE I IZMIRENJE BRAĆE U STUDENICI --}}
    <article class="story-card" id="prica-2">
      <div class="story-head">
        <div>
          <span class="story-tag">Čudo mira i bratske ljubavi</span>
          <h2 class="story-title">2. Mirotočenje i izmirenje braće nad očevim telom</h2>
        </div>
        <div class="story-icon-badge">🕯️</div>
      </div>

      <div class="story-intro-lead">
        „Kada je građanski rat pretio da uništi tek rođenu Srbiju, mrtvi otac i najmlađi brat monah učinili su ono što mačevi nisu mogli — vratili su mir među rođenu braću.”
      </div>

      <div class="story-body">
        <p>
          Nakon smrti Stefana Nemanje (Svetog Simeona) u Hilandaru 1199. godine, u Srbiji je izbio strašan i poguban sukob. Stariji sin Vukan, uz pomoć ugarskog kralja, zbacio je mlađeg brata Stefana sa prestola. Zemlja je bila opustošena, sela popaljena, a narod je gladovao pod teretom bratoubilačkog rata.
        </p>
        <p>
          U tim najtežim trenucima, knez Stefan šalje pismo na Svetu Goru svom bratu Savi: <em>„Brate naš, dođi i donesi nam mošti našeg svetog roditelja, da njegovim prisustvom zacelimo rane naše otadžbine.”</em> Sava se odaziva na vapaj naroda. U zimu 1206/1207. godine, kroz snegom zavejane balkanske klance, Sava na rukama prenosi kivot sa očevim moštima u manastir Studenicu.
        </p>

        <div class="story-quote-box">
          <div class="story-quote-box__text">
            „Kada su otvorili kivot pred okupljenom zavađenom braćom i vlastelom, iz suvih kostiju starca poteklo je blagouhano sveto miro, a hram se ispunio nezemaljskim mirisom ruža i tamjana. Vukan i Stefan padoše na kolena, obliše se suzama i zagrliše pred očevim odrom.”
          </div>
          <div class="story-quote-box__author">— Stanojević, S. (1989), Istorija srpskoga naroda</div>
        </div>

        <p>
          Ovim činom Sava nije samo pomirio rođenu braću, već je spasao srpsku državu od potpunog rasparčavanja. Stefan Nemanja postao je <strong>Sveti Simeon Mirotočivi</strong>, a Studenica večni simbol sabornosti i roditeljskog blagoslova.
        </p>
      </div>

      <div class="story-separator"><span class="story-separator__ornament">❧</span></div>
    </article>

    {{-- PRIČA 3: SVETI SAVA I VUKOVI --}}
    <article class="story-card" id="prica-3">
      <div class="story-head">
        <div>
          <span class="story-tag">Narodna mitologija i hrišćanski smisao</span>
          <h2 class="story-title">3. Legenda o Svetom Savi i gorskim vukovima</h2>
        </div>
        <div class="story-icon-badge">🐺</div>
      </div>

      <div class="story-intro-lead">
        „U narodnom pamćenju, Sveti Sava je hodao zemljom peške, u opancima, sa štapom u ruci — učeći narod redu, a zverima određujući pravednu meru.”
      </div>

      <div class="story-body">
        <p>
          U srpskoj narodnoj tradiciji, Sveti Sava zauzima jedinstveno mesto. Naši preci su u njegovom liku spojili hrišćanskog svetitelja i prastarog zaštitnika prirode — naročito vuka, koji je u staroslovenskoj svesti bio mitska životinja srpskog prostora.
        </p>
        <p>
          Predanje kaže da bi svakog kasnog novembra, pred zimske mrazeve (oko praznika Mratindana), Sveti Sava okupio sve vukove iz šuma na jednom proplanku. Vukovi bi mirno sedeli u krugu oko Svetitelja dok bi im on delio hranu i određivao koje stado i čiju šumu smeju da pohode tokom zime, a koje domaćine nikako ne smeju dirati.
        </p>
        <p>
          Jedna anegdota kazuje kako je jedan hromi, izgladneli vuk zakasnio na sabor. Kada je upitao Savu šta je ostalo za njega, Svetitelj mu odgovori: <em>„Tebi pripada onaj tvrdoglavi i škrti domaćin koji zaboravlja sirotinju i ne pomaže komšiji.”</em> Narodna pouka ove priče bila je jednostavna, ali duboka: nepravda i pohlepa uvek bivaju kažnjene, dok pravičnost i gostoljublje čuvaju kuću od svake nevolje.
        </p>
      </div>

      <div class="story-separator"><span class="story-separator__ornament">❧</span></div>
    </article>

    {{-- PRIČA 4: SAVINE VODE I ČUDOTVORNI IZVORI --}}
    <article class="story-card" id="prica-4">
      <div class="story-head">
        <div>
          <span class="story-tag">Svetosavska geografija</span>
          <h2 class="story-title">4. Savin izvor i živa voda u vrletima</h2>
        </div>
        <div class="story-icon-badge">💧</div>
      </div>

      <div class="story-intro-lead">
        „Kuda god je hodio prvi srpski prosvetitelj, kamen je progovarao, a iz suve stene tekla je bistra, lekovita voda.”
      </div>

      <div class="story-body">
        <p>
          Širom Srbije, Crne Gore, Bosne i Hercegovine i Hercegovine, postoje stotine toponima sa imenom Svetog Save: <em>Savina voda</em>, <em>Savin kuk</em>, <em>Savina stopa</em>, <em>Savina trpeza</em>, <em>Savinac</em>.
        </p>
        <p>
          Najpoznatija legenda vezana je za Savinu isposnicu iznad Studenice. Dok se Sava u tišini i postu molio visoko u liticama Radočela, narod iz doline popeo se tražeći pomoć jer je vladala strašna suša. Sava se prekrstio, udario svojim pastirskim štapom u suvi krečnjački kamen i rekao: <em>„Gospode, otvori ove stene da narod ne skapava od žeđi!”</em> Istog trena iz kamena je šiknuo mlaz kristalno čiste vode koji i dan-danas teče i smatra se lekovitim za oči i duševni mir.
        </p>
        <p>
          Ove priče nisu samo puka bajka — one svedoče o tome koliko je Sveti Sava podučavao narod higijeni, gradnji česama, navodnjavanju i brizi o prirodi u srednjem veku.
        </p>
      </div>

      <div class="story-separator"><span class="story-separator__ornament">❧</span></div>
    </article>

    {{-- PRIČA 5: KRALJ MILUTIN I ZAVET OD 40 SVETINJA --}}
    <article class="story-card" id="prica-5">
      <div class="story-head">
        <div>
          <span class="story-tag">Vrhunac zadužbinarstva</span>
          <h2 class="story-title">5. Kralj Milutin i zavet zvezdanog neba: Za svaku godinu vladavine po jedna crkva</h2>
        </div>
        <div class="story-icon-badge">👑</div>
      </div>

      <div class="story-intro-lead">
        „Kada je kralj Milutin stupio na presto 1282. godine, dao je svečani zavet pred Bogom: koliko god godina budem nosio krunu, toliko ću podići ili iz temelja obnoviti svetih hramova.”
      </div>

      <div class="story-body">
        <p>
          Kralj Stefan Uroš II Milutin vladao je punih 39 i po godina (skoro četiri decenije). I zaista — istorijski izvori i arheologija potvrđuju da je Milutin podigao preko 40 crkava i manastira, čime je postao bez premca najveći ktitor u čitavoj vizantijskoj i slovenskoj epohi toga doba!
        </p>
        <p>
          Milutin nije gradio samo u Srbiji. Njegove zadužbine i bolnice nicale su u Solunu, Carigradu, Sofiji, na Svetoj Gori (čuvena Kraljeva crkva u Hilandaru) pa čak i u Jerusalimu (Manastir Svetih Arhangela u Svetoj Zemlji).
        </p>
        <p>
          Biser njegove graditeljske delatnosti jeste čuvena <strong>Gračanica</strong> na Kosovu polju — remek-delo srpsko-vizantijskog stila gde se sklad crvenih opeka, sivog kamena i pet kupola uzdiže ka nebu poput molitve u kamenu.
        </p>

        <div class="story-quote-box">
          <div class="story-quote-box__text">
            „Milutin je pokazao da se vladarska moć ne meri samo osvojenim gradovima, već lepotom onoga što ostavljaš u nasleđe potomcima.”
          </div>
          <div class="story-quote-box__author">— Vladimir Ćorović (2001), Istorija Srba</div>
        </div>
      </div>

      <div class="story-separator"><span class="story-separator__ornament">❧</span></div>
    </article>

    {{-- PRIČA 6: VRAĆENI VID I VISOKI DEČANI --}}
    <article class="story-card" id="prica-6">
      <div class="story-head">
        <div>
          <span class="story-tag">Iskušenje i čudo vere</span>
          <h2 class="story-title">6. Kralj Stefan Dečanski: Sveti Nikola i čudo u carigradskom Pantokratoru</h2>
        </div>
        <div class="story-icon-badge">✨</div>
      </div>

      <div class="story-intro-lead">
        „Kroz mrak oslepljenja i godine progonstva, princ Stefan je sačuvao krotost. Zato je iz njegovog bola nikao najveličanstveniji hram srednjovekovnog Balkana — Visoki Dečani.”
      </div>

      <div class="story-body">
        <p>
          Tragična sudbina Stefana Dečanskog jedna je od najpotresnijih drama srpske istorije. Usled lažnih optužbi i intriga na dvoru, otac Milutin naredio je da se njegov sin Stefan oslepi usijanim gvožđem na Ovčem Polju, a potom ga progna u Carigrad, u manastir Hrista Pantokratora.
        </p>
        <p>
          Međutim, dželat je bio potkupljen ili se sažalio, te usijano gvožđe nije uništilo očne živce, ali je Stefan godinama nosio povez preko očiju praveći se slep kako bi sačuvao život. U noćnoj tišini carigradskog manastira, Stefanu se u viđenju javio <strong>Sveti Nikola Čudotvorac</strong>, držeći njegove oči na svom dlanu i poručivši mu: <em>„Ne boj se, Stefane, tvoje oči su kod mene na sigurnom. Kada dođe vreme, povratićeš vid i sagradićeš hram koji će sijati kroz vekove.”</em>
        </p>
        <p>
          Nakon očeve smrti, Stefan se vraća u Srbiju, preuzima presto i u znak večne zahvalnosti podiže <strong>Manastir Visoki Dečani</strong> podno Prokletija. Sa zidinama od uglačanog belog i ružičastog mermera i preko hiljadu sačuvanih fresaka, Dečani su i danas pod zaštitom UNESCO-a kao vrhunac svetske kulturne baštine.
        </p>
      </div>

      <div class="story-separator"><span class="story-separator__ornament">❧</span></div>
    </article>

    {{-- PRIČA 7: CAR DUŠAN I ZAKONIK --}}
    <article class="story-card" id="prica-7">
      <div class="story-head">
        <div>
          <span class="story-tag">Vrhunac carske moći</span>
          <h2 class="story-title">7. Dušan Silni: Carska kruna u Skoplju i zakon jednak za sve</h2>
        </div>
        <div class="story-icon-badge">⚔️</div>
      </div>

      <div class="story-intro-lead">
        „Na Vaskrs 1346. godine, Srbija je postala Carstvo, a srpska crkva uzdignuta na rang Patrijaršije. Ali najveća Dušanova pobeda nije bila vojna — bio je to Zakon.”
      </div>

      <div class="story-body">
        <p>
          Za vreme vladavine Stefana Dušana, Srbija se prostirala od Dunava na severu do Korintskog zaliva na jugu. Na svečanom saboru u Skoplju 1346. godine, prvi srpski patrijarh Joanikije krunisao je Dušana za „Cara Srba i Grka”.
        </p>
        <p>
          Tri godine kasnije, 1349. godine u Skoplju, donet je čuveni <strong>Dušanov zakonik</strong> (dopunjen u Seru 1354). Ono što ovaj zakonik čini vanvremenskim jeste odredba u članu 171: <em>„Sudije da sude po pravdi i zakonu, a ne po strahu od carstva mi.”</em>
        </p>
        <p>
          Car je time stavio Zakon iznad sopstvene carske samovolje! U carstvu je vladala takva sigurnost i pravni red da je, prema predanju savremenika, devojka sa zlatnom jabukom u ruci mogla sama prepešačiti od Dunava do mora, a da je niko ne napadne niti joj naudi.
        </p>
      </div>

      <div class="story-separator"><span class="story-separator__ornament">❧</span></div>
    </article>

    {{-- PRIČA 8: KNEZ LAZAR I RAVANICA --}}
    <article class="story-card" id="prica-8">
      <div class="story-head">
        <div>
          <span class="story-tag">Zavet moravske Srbije</span>
          <h2 class="story-title">8. Knez Lazar i večni zavet u Ravanici</h2>
        </div>
        <div class="story-icon-badge">🌹</div>
      </div>

      <div class="story-intro-lead">
        „U osvit kosovskog boja, knez Lazar je u skrovitoj kučajskoj dolini zidao Ravanicu — svetinju koja je trebalo da sačuva dušu naroda u vekovima tame koji su dolazili.”
      </div>

      <div class="story-body">
        <p>
          Nakon raspada Dušanovog carstva i Maričke bitke 1371. godine, teški oblaci nadvili su se nad Balkanom. U tim godinama iskušenja, knez Lazar Hrebeljanović mudro okuplja srpske zemlje u slivu Morave, otvara rudnike srebra u Novom Brdu i obnavlja duhovni život.
        </p>
        <p>
          U tišini ispod Kučajskih planina, Lazar gradi svoju zadužbinu — <strong>Manastir Ravanicu</strong>, posvećenu Vaznesenju Gospodnjem. Sa svojim karakterističnim preplitima, rozetama i toplom moravskom keramikom, Ravanica je postala utočište stotina monaha i prepisivača koji su bežali pred osmanskom najezdom.
        </p>
        <p>
          Nakon Lazareve mučeničke pogibije na Kosovu polju 1389. godine, kneginja Milica prenosi njegove netruležne mošti u Ravanicu. Kroz potonje vekove seoba, razaranja i obnova, Ravanica je ostala večni stub srpskog kosovskog zaveta i nade u vaskrsenje slobode.
        </p>
      </div>

      <div class="story-separator"><span class="story-separator__ornament">❧</span></div>
    </article>

    {{-- PRIČA 9: BELI ANĐEO MILEŠEVE --}}
    <article class="story-card" id="prica-9">
      <div class="story-head">
        <div>
          <span class="story-tag">РЕМЕК-ДЕЛО СВЕТСКЕ УМЕТНОСТИ И ВЕЧНА НАДА</span>
          <h2 class="story-title">9. Бели Анђео Милешеве и тајна зрачења небеског мира</h2>
        </div>
        <div class="story-icon-badge">🕊️</div>
      </div>

      <div class="story-intro-lead">
        „У полутами милешевске припрате, у снежнобелој одежди седи Арханђел Гаврило. Његов поглед не припада само једном веку — он гледа право у очи сваког човека који пред њега стане, са које год стране да му приђе.”
      </div>

      <div class="story-body">
        <p>
          Српски краљ Владислав, син Стефана Првовенчаног и унук Немањин, подигао је око 1234. године <strong>Манастир Милешеву</strong> на реци Милешевци код Пријепоља. Када се његов стриц Свети Сава упокојио у бугарском Трнову 1236. године, краљ Владислав је после дугих преговора пренео Савине свете мошти у Милешеву 1237. године. Овај догађај учинио је Милешеву најважнијим ходочасничким средиштем српског народа кроз векове.
        </p>
        <p>
          За осликавање милешевског храма Вазнесења Господњег ангажовани су врхунски византијски и српски мајстори. На јужном зиду наоса настала је композиција <em>„Мироносице на Христовом гробу”</em>, а њен централни део чини раскошни <strong>Бели Анђео</strong>. Млади, бескрилно лепи арханђел у белом хитону седи на одваљеном камену гроба и десном руком показује празну плаштаницу, доносећи уплаканим женама најрадоснију вест: <em>„Не бојте се; знам да Исуса распетога тражите. Није овде; јер устаде!”</em>
        </p>

        <div class="story-quote-box">
          <div class="story-quote-box__text">
            „Када је 1962. године успостављен први сателитски видео-пренос између Европе и Северне Америке (Telstar), у првом кадру који је полетео преко океана била је слика Белог Анђела из Милешеве као универзални поздрав људске цивилизације. Касније је исти сигнал упућен ка дубоком свемиру као симбол наде и мира.”
          </div>
          <div class="story-quote-box__author">— Из историје телекомуникација и културне баштине УНЕСКО-а</div>
        </div>

        <p>
          Иако су османски освајачи крајем 16. века однели Савине мошти на Врачар и више пута палили и пљачкали Милешеву, преко фреске Белог Анђела је нанета малтерска превлака која ју је сачувала од уништења све до 20. века, када је поново засијала у свом првобитном сјају 13. века.
        </p>
      </div>

      <div class="story-separator"><span class="story-separator__ornament">❧</span></div>
    </article>

    {{-- PRIČA 10: ČUDOTVORNA LOZA SVETOG SIMEONA NA HILANDARU --}}
    <article class="story-card" id="prica-10">
      <div class="story-head">
        <div>
          <span class="story-tag">Атонско чудо и благослов порода</span>
          <h2 class="story-title">10. Чудотворна лоза Светог Симеона на Хиландару</h2>
        </div>
        <div class="story-icon-badge">🍇</div>
      </div>

      <div class="story-intro-lead">
        „Из сувог камена подно хиландарског олтара, где нема ни прегршти земље, већ више од осам векова буја и рађа лоза која доноси радост родитељства људима са свих континената.”
      </div>

      <div class="story-body">
        <p>
          Када се велики жупан Стефан Немања одрекао престола, замонашио као Симеон и са најмлађим сином Савом на Светој Гори из рушевина подигао <strong>Манастир Хиландар</strong> (1198. г.), ударени су духовни темељи српског бића. Свети Симеон се мирно упокојио 13. фебруара 1199. године пред иконом Пресвете Богородице и сахрањен је у саборном храму.
        </p>
        <p>
          Када је осам година касније (1207. г.) Свети Сава морао да пренесе очеве мошти у Србију како би измирио завађену браћу Стефана и Вукана и зауставио грађански рат, хиландарски монаси су неутешно плакали јер њихова обитељ остаје без телесног присуства свог светог ктитора.
        </p>

        <div class="story-quote-box">
          <div class="story-quote-box__text">
            „Свети Симеон се тада у сну јавио хиландарском игуману и поручио: ’Не тугујте, братијо! Као вечни залог моје присутности и очевог благослова, из мог празног гроба израшће лоза. Докле год она буде цветала и рађала, мој благослов ће почивати на Хиландару.’”
          </div>
          <div class="story-quote-box__author">— Из Светогорских житија и Хиландарског летописа</div>
        </div>

        <p>
          И заиста — из самог мермерног пода и пукотине у чврстој стени избила је винова лоза која нити се ђубри, нити прска, а рађа пуних осам стотина година. Осушена зрна грожђа са ове лозе, уз пратеће молитвено правило и пост, према сведочењима хиљада брачних парова широм планете, донела су благослов рађања и исцељење од неплодности.
        </p>
      </div>

      <div class="story-separator"><span class="story-separator__ornament">❧</span></div>
    </article>

    {{-- PRIČA 11: SVETI SAVA I SVETLOST U DOMOVIMA --}}
    <article class="story-card" id="prica-11">
      <div class="story-head">
        <div>
          <span class="story-tag">Народна мудрост и просветитељство</span>
          <h2 class="story-title">11. Мудрост Светог Саве: Како је просветитељ унео светлост у домове</h2>
        </div>
        <div class="story-icon-badge">☀️</div>
      </div>

      <div class="story-intro-lead">
        „Кроз топле народне приповетке и поучне анегдоте, наш народ је вековима памтио како је Свети Сава учио људе раду, занатима, писмености и здравом разуму.”
      </div>

      <div class="story-body">
        <p>
          Српска усмена традиција препуна је предања у којима Свети Сава путује српском земљом прерушен у обичног калуђера или старца, посматра како народ живи и помаже му да савлада свакодневне тешкоће. Једна од најсликовитијих приповедака говори о доласку Светог Саве у једно планинско село.
        </p>
        <p>
          Ходајући сеоским сокацима, Сава угледа необичан призор: мештани по врелом подневу трче по дворишту са великим празним врећама, широм их отварају према сунцу, везују канапом, а онда журно утрчавају у своје ниске брвнаре без прозора. Након неког времена излазе покуњени, па опет понављају исти посао.
        </p>

        <div class="story-quote-box">
          <div class="story-quote-box__text">
            „Приђе Сава једном домаћину и упита га: ’Шта то радите, добри људи?’ Домаћин бришући зној са чела одговори: ’Ево, путoffset-че, саградисмо куће од дебелих брвана, али у њима је тама као у гробу! Цео дан хватамо сунце у вреће и уносимо га у собу, али чим одвежемо врећу — сунце негде побегне и опет остане мрак!’”
          </div>
          <div class="story-quote-box__author">— Вук Стефановић Караџић, Српске народне приповијетке</div>
        </div>

        <p>
          Сава се благо осмехну, затражи секиру од домаћина, приђе јужном зиду брвнаре и у неколико вештих потеза засече брвна и отвори простран, широк прозор. Сунчева светлост у трену обасја целу собу, а укућани занемеше од одушевљења. Тада им Свети Сава поручи: <em>„Не мучите се узалуд хватајући светлост тамо где не може да се задржи. Отворите своје домове за сунце, а своје умове за науку и веру — и ниједан мрак неће моћи да вас савлада.”</em>
        </p>
      </div>

      <div class="story-separator"><span class="story-separator__ornament">❧</span></div>
    </article>

    {{-- PRIČA 12: CARICA MILICA I LJUBOSTINJA --}}
    <article class="story-card" id="prica-12">
      <div class="story-head">
        <div>
          <span class="story-tag">Завет моравске Србије и женска духовност</span>
          <h2 class="story-title">12. Царица Милица и удовице Косовског боја: Задужбина Љубостиња</h2>
        </div>
        <div class="story-icon-badge">🌸</div>
      </div>

      <div class="story-intro-lead">
        „После косовске трагедије 1389. године, кнегиња Милица је у скривеној долини Љубостиње створила тихо уточиште бола, молитве и мудре државничке дипломатије.”
      </div>

      <div class="story-body">
        <p>
          Видовдански бој на Косову пољу 1389. године завио је Моравску Србију у црно. Изгинула је српска властела, погинуо је кнез Лазар, а на челу рањене државе нашла се кнегиња Милица (у народним песмама опевана као Царица Милица), пореклом из лозе Немањића преко Вукановог сина Димитрија (Светог Давида).
        </p>
        <p>
          Милица окупља удовице изгинулих српских витезова и племића. У шумовитом кланцу Љубостињске реке близу Трстеника, она подиже <strong>Манастир Љубостињу</strong> (од старосрпског <em>љубвестан</em> — место љубави). Тамо прима монашки постриг као монахиња <strong>Јевгенија</strong> (касније схимонахиња Јефросина), заједно са чувененом монахињом <strong>Јефимијом</strong> (Јеленом Мрњавчевић).
        </p>

        <div class="story-quote-box">
          <div class="story-quote-box__text">
            „У тишини љубостињских келија, Јефимија златовезом на црвеној свиленој атлас-тканини везе чувену ’Похвалу кнезу Лазару’ — један од најпотреснијих и најузвишенијих песничких текстова европског средњег века, намењен као покров за Лазарев ћивот у Раваници.”
          </div>
          <div class="story-quote-box__author">— Димитрије Богдановић, Историја старе српске књижевности</div>
        </div>

        <p>
          Љубостиња је била више од молитвеног уточишта: из ње су две мудре монахиње, Милица и Јефимија, водиле преговоре са турским султаном Бајазитом, издејствовале пренос моштију Свете Петке из Видина у Србију и сачувале престо за младог деспота Стефана Лазаревића.
        </p>
      </div>

      <div class="story-separator"><span class="story-separator__ornament">❧</span></div>
    </article>

    {{-- PRIČA 13: DESPOT STEFAN LAZAREVIĆ I MANASIJA --}}
    <article class="story-card" id="prica-13">
      <div class="story-head">
        <div>
          <span class="story-tag">Ренесанса пре ренесансе и витешки дух</span>
          <h2 class="story-title">13. Деспот Стефан Лазаревић: Витез Змаја, песник и неосвојива Манасија</h2>
        </div>
        <div class="story-icon-badge">🛡️</div>
      </div>

      <div class="story-intro-lead">
        „Најобразованији владар свог доба, први витез европског Реда Змаја и генијални војсковођа, деспот Стефан је у долини Ресаве саградио неосвојиву тврђаву књиге и уметности — Манасију.”
      </div>

      <div class="story-body">
        <p>
          Син кнеза Лазара и кнегиње Милице, <strong>деспот Стефан Лазаревић</strong> (Стефан Високи), био је једна од најфасцинантнијих појава европског 15. века. Био је величанственог стаса, говорио је грчки, латински и старословенски, преводио са страних језика и писао поезију која стоји у самом врху европске средњовековне лирике.
        </p>
        <p>
          Као војсковођа није доживео ниједан лични пораз. Због свог витешког држања и јунаштва, угарски краљ и немачки цар Жигмунд Луксембуршки поставио га је на чело најелитнијег витешког савеза хришћанског света — <strong>Витешког реда Змаја</strong> (Societas Draconistarum). Деспот је од разореног Београда створио блиставу хришћанску престоницу и кључну трговачку луку југоисточне Европе.
        </p>

        <div class="story-quote-box">
          <div class="story-quote-box__text">
            „У својој главној задужбини, Манастиру Манасији (Ресави), деспот Стефан је основао чувену Ресавску преписивачку школу. Ту су се сакупљали најбољи преписивачи, филолози и преводиоци који су исправили и умножили на хиљаде рукописних књига, створивши нови правописни стандард за све православне Словене.”
          </div>
          <div class="story-quote-box__author">— Константин Филозоф (1431), Житије деспота Стефана Лазаревића</div>
        </div>

        <p>
          Опасана са једанаест моћних кула и масивним одбрамбеним зидовима са машикулама, Манасија је сачувала ремек-дела фрескосликарства на којима су свети ратници приказани у раскошној витешкој опреми тога доба. Сам деспот испевао је безвремено <strong>„Слово љубве”</strong> — химну љубави, праштању и лепоти људске душе.
        </p>
      </div>

      <div class="story-separator"><span class="story-separator__ornament">❧</span></div>
    </article>

    {{-- PRIČA 14: SVETI VASILIJE OSTROŠKI --}}
    <article class="story-card" id="prica-14">
      <div class="story-head">
        <div>
          <span class="story-tag">Чудотворац и стуб вере под Османлијама</span>
          <h2 class="story-title">14. Свети Василије Острошки: Молитва у стени и чувар херцеговачких литица</h2>
        </div>
        <div class="story-icon-badge">⛰️</div>
      </div>

      <div class="story-intro-lead">
        „У најтежим вековима туђинског ропства, када су цркве спаљиване а народ губио наду, у вертикалној пећинској стени Острога засијао је светионик вере коме и данас хрле стотине хиљада људи свих вероисповести.”
      </div>

      <div class="story-body">
        <p>
          Рођен почетком 17. века у херцеговачком селу Мркоњићи у Поповом Пољу као Стојан Јовановић, будући <strong>Свети Василије Острошки Чудотворац</strong> од раног детињства одрастао је у молитви и посту. Замонашио се у манастиру Завала, у Тврдошу постао архимандрит, а потом и митрополит захумски.
        </p>
        <p>
          Када су османске казне побеснеле, а латински унијатски мисионари вршили притисак на осиромашени народ, владика Василије је са штапом и бисагама пешице обилазио херцеговачка и црногорска села, крепећи веру, градећи богомоље и делећи последње залихе брашна и уља са сиротињом. Након разарања Тврдоша, Василије се повлачи у сурове, неприступачне литице планине Острог изнад Бјелопавлићке равнице.
        </p>

        <div class="story-quote-box">
          <div class="story-quote-box__text">
            „У двема пећинама у вертикалној литици, Свети Василије подиже Горњи Острог. Ту се упокојио 1671. године, а из суве пећинске стене на месту његовог престављења израсла је жива винова лоза. Његово свето тело сачувано је нетрулежно кроз векове ратова, пожара и похара.”
          </div>
          <div class="story-quote-box__author">— Острошки летопис и сачувана сведочанства исцељења</div>
        </div>

        <p>
          Више од три стотине педесет година, ћивот Светог Василија у Острогу представља једно од најпосећенијих хришћанских светилишта на свету, где мир, утеху и исцељење налазе људи свих нација и свих вера који му са чистим срцем прилазе.
        </p>
      </div>
    </article>

    {{-- IZVORI --}}
    @include('partials.sources-card', [
        'title' => 'Историјски извори и средњовековна житија',
        'sources' => [
            'Станојевић, Станоје. (1989). <em>„Историја српскога народа”</em>. Ниш: Нова Књига БСМ.',
            'Ћоровић, Владимир. (2001). <em>„Историја Срба”</em>. Чачак: Легенда.',
            'Богдановић, Димитрије. (1980). <em>„Историја старе српске књижевности”</em>. Београд: СКЗ.',
            'Доментијан. (XIII век). <em>„Житије Светог Саве”</em> и <em>„Житије Светог Симеона”</em>.',
            'Теодосије Хиландарац. (крајем XIII века). <em>„Житије Светог Саве”</em>.',
            'Архиепископ Данило II. (XIV век). <em>„Животи краљева и архиепископа српских”</em>.',
            'Константин Филозоф. (XV век). <em>„Житије деспота Стефана Лазаревића”</em>.',
            'Караџић, Вук Стефановић. (1867). <em>„Живот и обичаји народа српскога”</em> (Свети Сава у народним приповеткама).'
        ],
        'note' => 'Сва предања, приче и историјске епизоде усклађене су са српским средњовековним житијима, летописима и званичном историографијом.'
    ])

  </div>
</section>
@endsection
