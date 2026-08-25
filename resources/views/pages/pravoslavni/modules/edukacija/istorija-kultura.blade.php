@extends('layouts.site')

@section('title','Држава Немањића: Битке, крунисања, сабори и прекретнице — Едукација')
@section('nav_edukacija', 'active')

@section('content')
<style>
  .lesson-page{
    max-width: 1260px;
    margin: 0 auto;
    width: 100%;
    padding-bottom: 50px;
  }

  .lesson-card{
    background: linear-gradient(180deg, rgba(28, 18, 16, 0.98), rgba(16, 10, 10, 0.98));
    border: 1.5px solid rgba(197, 162, 74, 0.32);
    border-radius: 28px;
    padding: 40px 46px;
    box-shadow: 0 20px 50px rgba(0,0,0,.55);
    width: 100%;
  }

  .lesson-page h1{
    font-size: clamp(2.1rem, 3.5vw, 2.9rem);
    margin: 0 0 16px 0;
    color: var(--gold, #c5a24a);
    font-weight: 800;
    line-height: 1.15;
    text-shadow: 0 0 20px rgba(197,162,74,.18);
  }

  .lesson-badge{
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 16px;
    border-radius: 999px;
    background: rgba(197,162,74,.12);
    border: 1px solid rgba(197,162,74,.32);
    color: #e2c26a;
    font-size: .84rem;
    font-weight: 700;
    margin-bottom: 16px;
  }

  .lesson-page .lead{
    font-size: 1.15rem;
    line-height: 1.95;
    text-align: justify;
    text-justify: inter-word;
    color: rgba(255,255,255,.96);
    margin-bottom: 28px;
    font-style: italic;
    padding: 20px 24px;
    border-left: 4px solid var(--gold, #c5a24a);
    background: rgba(197,162,74,.08);
    border-radius: 0 16px 16px 0;
  }

  /* BRZI SADRŽAJ (TOC) */
  .edu-toc-box {
    background: rgba(20, 12, 11, 0.85);
    border: 1px solid rgba(197, 162, 74, 0.28);
    border-radius: 20px;
    padding: 22px 26px;
    margin-bottom: 35px;
  }
  .edu-toc-box__title {
    margin: 0 0 14px 0;
    color: #e2c26a;
    font-size: 1.15rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .edu-toc-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 10px;
  }
  .edu-toc-link {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 12px;
    border-radius: 10px;
    color: rgba(255,255,255,.88);
    text-decoration: none;
    font-size: 0.92rem;
    background: rgba(255,255,255,.025);
    border: 1px solid transparent;
    transition: all 0.2s ease;
  }
  .edu-toc-link:hover {
    background: rgba(197,162,74,.12);
    border-color: rgba(197,162,74,.30);
    color: #fce7aa;
    transform: translateX(3px);
  }

  .lesson-section {
    position: relative;
    margin-bottom: 35px;
    padding-bottom: 25px;
  }

  .lesson-page h2{
    margin-top: 32px;
    margin-bottom: 16px;
    font-size: clamp(1.45rem, 2.3vw, 1.85rem);
    color: var(--gold, #c5a24a);
    font-weight: 800;
    display: flex;
    align-items: center;
    gap: 10px;
    line-height: 1.25;
  }

  .lesson-page h3{
    margin-top: 24px;
    margin-bottom: 12px;
    font-size: 1.2rem;
    color: #fce7aa;
    font-weight: 700;
  }

  .lesson-page p{
    text-align: justify;
    text-justify: inter-word;
    line-height: 1.95;
    font-size: 1.05rem;
    margin-bottom: 18px;
    color: rgba(255,255,255,.90);
    width: 100%;
  }

  .event-timeline-card {
    background: rgba(22, 13, 12, 0.90);
    border: 1.5px solid rgba(197, 162, 74, 0.30);
    border-radius: 22px;
    padding: 24px 28px;
    margin: 22px 0;
    box-shadow: 0 10px 28px rgba(0,0,0,0.45);
  }

  .event-year-tag {
    display: inline-block;
    padding: 5px 16px;
    border-radius: 999px;
    background: linear-gradient(135deg, rgba(197,162,74,.30), rgba(226,194,106,.22));
    border: 1px solid rgba(197,162,74,.45);
    color: #fff;
    font-weight: 800;
    font-size: 0.95rem;
    margin-bottom: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
  }

  .battle-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 999px;
    background: rgba(230, 57, 70, 0.15);
    border: 1px solid rgba(230, 57, 70, 0.4);
    color: #ff8590;
    font-size: 0.82rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-left: 8px;
  }

  .coronation-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 999px;
    background: rgba(197, 162, 74, 0.18);
    border: 1px solid rgba(197, 162, 74, 0.45);
    color: #fce7aa;
    font-size: 0.82rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-left: 8px;
  }

  .quote-box {
    margin: 22px 0;
    padding: 18px 22px;
    background: linear-gradient(135deg, rgba(38, 25, 22, 0.8), rgba(20, 13, 11, 0.8));
    border: 1px solid rgba(197, 162, 74, 0.35);
    border-radius: 16px;
    font-style: italic;
    color: #f7eedb;
    font-size: 1.02rem;
    line-height: 1.8;
  }

  .lesson-separator {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 36px 0;
    position: relative;
    width: 100%;
  }
  .lesson-separator::before, .lesson-separator::after {
    content: "";
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(197, 162, 74, 0.4), transparent);
  }
  .lesson-separator__ornament {
    color: var(--gold, #c5a24a);
    font-size: 18px;
    padding: 0 15px;
    opacity: 0.85;
  }

  @media (max-width: 768px){
    .lesson-card{
      padding: 24px 18px;
      border-radius: 20px;
    }

    .lesson-page h1{
      font-size: 1.8rem;
    }

    .lesson-page h2{
      font-size: 1.35rem;
    }

    .lesson-page p,
    .lesson-page .lead{
      font-size: 0.98rem;
      line-height: 1.8;
    }

    .event-timeline-card {
      padding: 18px 16px;
      border-radius: 16px;
    }
  }
</style>

<section class="section">
  <div class="container lesson-page">

    {{-- DUGME ZA POVRATAK --}}
    <div style="margin-bottom: 20px;">
      <a href="{{ route('edukacija.index') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border-radius: 12px; background: rgba(255,255,255,.05); border: 1px solid rgba(255,255,255,.10); color: rgba(255,255,255,.9); text-decoration: none; font-size: 0.9rem; font-weight: 700;">
        ← Nazad na sve obrazovne celine
      </a>
    </div>

    <div class="lesson-card">

      <span class="lesson-badge">👑 Образовна целина 01 • Светоотачко наслеђе</span>
      <h1>Држава Немањића: Битке, крунисања, сабори и историјске прекретнице</h1>

      <p class="lead">
        Период владавине светородне династије Немањића (од 1166. до 1371. године) представља најславније доба српског средњег века.
        Током више од два века, Србија се уздигла од мале жупе у Рашкој до најмоћнијег Царства југоисточне Европе.
        Ова целина обухвата све кључне историјске догађаје: славне битке (од Пантина и Мораве до тријумфа код Велбужда), велике државно-црквене саборе, краљевска и царска крунисања и доношење Душановог законика.
      </p>

      {{-- BRZI INDEKS PREČICA --}}
      <div class="edu-toc-box">
        <div class="edu-toc-box__title">
          <span>📖</span> Брзи преглед историјских догађаја:
        </div>
        <div class="edu-toc-grid">
          <a class="edu-toc-link" href="#pantino-1168"><span>⚔️</span> 1168. Битка код Пантина (Успон Немање)</a>
          <a class="edu-toc-link" href="#morava-1190"><span>⚔️</span> 1190. Битка на Морави (Признање независности)</a>
          <a class="edu-toc-link" href="#sabor-ras-1196"><span>📜</span> 1196. Сабор у Расу (Пренос круне)</a>
          <a class="edu-toc-link" href="#pomirenje-1208"><span>🕊️</span> 1208. Помирење браће над очевим моштима</a>
          <a class="edu-toc-link" href="#krunisanje-1217"><span>👑</span> 1217. Крунисање Стефана Првовенчаног</a>
          <a class="edu-toc-link" href="#autokefalnost-1219"><span>⛪</span> 1219. Аутокефалност СПЦ и Законоправило</a>
          <a class="edu-toc-link" href="#zicki-sabor-1221"><span>🏛️</span> 1221. Велики Жички сабор</a>
          <a class="edu-toc-link" href="#dezevo-1282"><span>📜</span> 1282. Сабор у Дежеву и краљ Милутин</a>
          <a class="edu-toc-link" href="#milutin-pohodi"><span>⚔️</span> 1282–1299. Освајања краља Милутина</a>
          <a class="edu-toc-link" href="#velbuzd-1330"><span>⚔️</span> 1330. Славна Битка код Велбужда</a>
          <a class="edu-toc-link" href="#carsko-krunisanje-1346"><span>👑</span> 1346. Царско крунисање Душана у Скопљу</a>
          <a class="edu-toc-link" href="#stefanijana-1344"><span>⚔️</span> 1344. Битка код Стефанијане</a>
          <a class="edu-toc-link" href="#dusanov-zakonik"><span>⚖️</span> 1349/1354. Душанов законик</a>
          <a class="edu-toc-link" href="#kraj-loze-1371"><span>🕯️</span> 1371. Цар Урош и крај лозе</a>
        </div>
      </div>

      {{-- 1. BITKA KOD PANTINA --}}
      <section class="lesson-section" id="pantino-1168">
        <h2>⚔️ 1. Битка код Пантина (1168. година) <span class="battle-badge">Битка</span></h2>
        <p>
          Стефан Немања је дошао на чело Рашке 1166. године, али су се његова старија браћа — велики жупан Тихомир, Страцимир и Мирослав — уз помоћ Византије супротставила његовој власти.
          Византијски цар Манојло I Комнин послао је војску под заповедништвом војсковође Теодора Падијата и Тихомира да уклоне Немању.
        </p>
        <div class="event-timeline-card">
          <span class="event-year-tag">Прекретница: Јесен 1168. године</span>
          <p style="margin: 0; line-height: 1.85;">
            До одлучујућег сукоба дошло је код села <strong>Пантино на Косову</strong>, у близини Звечана и реке Ситнице.
            Стефан Немања је показао изузетно војничко умеће и до ногу потукао здружену византијско-братску војску. У паничном бекству, велики жупан Тихомир се утопио у реци Ситници.
            Овом победом Немања је учврстио неподељену власт као врховни владар свих српских земаља и отпочео еру светородне лозе Немањића.
          </p>
        </div>
      </section>

      <div class="lesson-separator"><span class="lesson-separator__ornament">❧</span></div>

      {{-- 2. BITKA NA MORAVI --}}
      <section class="lesson-section" id="morava-1190">
        <h2>⚔️ 2. Битка на Морави (1190. година) <span class="battle-badge">Битка</span></h2>
        <p>
          Након проласка крсташа Фридриха I Барбаросе кроз Србију у Трећем крсташком рату и Немањиних освајања византијских градова (Ниша, Сврљига, Равног), византијски цар Исак II Анђел покренуо је у јесен 1190. године велики казнену експедицију против Србије.
        </p>
        <div class="event-timeline-card">
          <span class="event-year-tag">Јесен 1190. године — Река Јужна Морава</span>
          <p style="margin: 0; line-height: 1.85;">
            У жестоком окршају на обалама Јужне Мораве византијска војска је остварила тактичку победу на пољу, али није успела да нанесе одлучујући пораз српским одредима који су се повукли у брда.
            Увидевши снагу српског отпора, цар Исак Анђел је понудио повољан мир: <strong>Византија је признала независност Србије</strong> и већи део освојених земаља.
            Мир је запечаћен браком Немањиног средњег сина Стефана (будућег Првовенчаног) и цареве синовице Евдокије, чиме је Србија подигнута у највиши дипломатски ранг европских држава.
          </p>
        </div>
      </section>

      <div class="lesson-separator"><span class="lesson-separator__ornament">❧</span></div>

      {{-- 3. SABOR U RASU --}}
      <section class="lesson-section" id="sabor-ras-1196">
        <h2>📜 3. Велики државни сабор у Расу (1196. година) <span class="coronation-badge">Сабор</span></h2>
        <p>
          После три деценије мудре и победоносне владавине, велики жупан Стефан Немања је сазвао велики државно-црквени сабор код Петрове цркве у Расу на празник Благовести, 25. марта 1196. године.
        </p>
        <div class="event-timeline-card">
          <span class="event-year-tag">25. март 1196. године</span>
          <p style="margin: 0; line-height: 1.85;">
            Немања се пред властелом, свештенством и народом добровољно одрекао престола у корист свог средњег сина Стефана Немањића, док је најстаријем сину Вукану поверио на управу Зету и Травунију.
            Истога дана, Немања и његова супруга Ана примају монашки завет. Немања узима име <strong>Симеон</strong>, а Ана име <strong>Анастасија</strong>.
            Монах Симеон 1197. године одлази на Свету Гору сину Сави, где заједнички подижу манастир Хиландар, вечни духовни светионик српског народа.
          </p>
        </div>
      </section>

      <div class="lesson-separator"><span class="lesson-separator__ornament">❧</span></div>

      {{-- 4. POMIRENJE BRACE --}}
      <section class="lesson-section" id="pomirenje-1208">
        <h2>🕊️ 4. Измирење завађене браће у Студеници (1208. година)</h2>
        <p>
          Након упокојења Светог Симеона у Хиландару (1199), у Србији је избио сукоб између браће Вукана и Стефана око врховне власти.
          Схвативши погубност неслоге, Свети Сава почетком 1208. године доноси очеве свете и мироточиве мошти у манастир Студеницу.
        </p>
        <div class="quote-box">
          „И положише свето тело његово у кивот у Пресветој Богородици Студеничкој. И над очевим телом, загрлише се Стефан и Вукан, плачући од радости и љубави, и опростише један другоме сва сагрешења своја.”
          <div style="text-align: right; color: #e2c26a; font-weight: 700; margin-top: 6px;">— Доментијан, Житије Светог Симеона</div>
        </div>
        <p>
          Овим светим чином Свети Сава је учврстио принцип братске љубави и јединства државе, а мошти Светог Симеона Мироточивог постале су небески заштитник Србије.
        </p>
      </section>

      <div class="lesson-separator"><span class="lesson-separator__ornament">❧</span></div>

      {{-- 5. KRUNISANJE PRVOVENCANOG --}}
      <section class="lesson-section" id="krunisanje-1217">
        <h2>👑 5. Крунисање Стефана Првовенчаног за краља (1217. година) <span class="coronation-badge">Крунисање</span></h2>
        <p>
          Стефан Немањић је мудром спољном политиком и дипломатијом остварио вековну тежњу српских владара за уздизањем државе у ранг краљевине.
          Године 1217. из Рима, од папе Хонорија III, у Србију стиже краљевска круна.
        </p>
        <div class="event-timeline-card">
          <span class="event-year-tag">1217. година</span>
          <p style="margin: 0; line-height: 1.85;">
            Стефан је свечано крунисан краљевском круном и постао <strong>први српски међународно признати краљ</strong>, због чега је у историји понео славни назив <strong>Стефан Првовенчани</strong>.
            Србија је тиме изједначена са осталим сувереним хришћанским краљевинама Европе.
          </p>
        </div>
      </section>

      <div class="lesson-separator"><span class="lesson-separator__ornament">❧</span></div>

      {{-- 6. AUTOKEFALNOST I ZAKONOPRAVILO --}}
      <section class="lesson-section" id="autokefalnost-1219">
        <h2>⛪ 6. Аутокефалност Српске цркве и Законоправило (1219. година)</h2>
        <p>
          Како би краљевина добила пуну духовну самосталност, Свети Сава 1219. године одлази у Никеју код васељенског патријарха Манојла Сарантена и цара Теодора I Ласкариса.
        </p>
        <div class="event-timeline-card">
          <span class="event-year-tag">1219. година — Никеја</span>
          <p style="margin: 0 0 10px; line-height: 1.85;">
            Патријарх и цар додељују <strong>аутокефалност (потпуну самосталност) Српској православној цркви</strong>, а Свети Сава бива рукоположен за првог српског архиепископа.
          </p>
          <p style="margin: 0; line-height: 1.85;">
            Исте године Сава саставља <strong>Законоправило (Номоканон / Крмчију)</strong> — велики правни зборник грађанског и црквеног права, који је постао темељ српске средњовековне државе, права и просвећености.
          </p>
        </div>
      </section>

      <div class="lesson-separator"><span class="lesson-separator__ornament">❧</span></div>

      {{-- 7. ZICKI SABOR --}}
      <section class="lesson-section" id="zicki-sabor-1221">
        <h2>🏛️ 7. Велики Жички државно-црквени сабор (1221. година) <span class="coronation-badge">Сабор</span></h2>
        <p>
          На Спасовдан 1221. године у новоизграђеном манастиру Жичи — првом седишту српске архиепископије — Свети Сава сазива свесрпски сабор на ком су присуствовали краљ, властела, епископи и монаштво.
        </p>
        <div class="event-timeline-card">
          <span class="event-year-tag">Спасовдан 1221. године — Манастир Жича</span>
          <p style="margin: 0; line-height: 1.85;">
            Свети Сава је лично крунисао краља Стефана по православном црквеном обреду и миропомазао га српском архиепископском руком.
            На овом сабору Свети Сава је изговорио своју знамениту <em>„Беседу о правој вери”</em>, у којој је изложио чисто учење Православне вере и заувек утемељио саборност и заветност српског народа.
          </p>
        </div>
      </section>

      <div class="lesson-separator"><span class="lesson-separator__ornament">❧</span></div>

      {{-- 8. DEZEVSKI SABOR I MILUTIN --}}
      <section class="lesson-section" id="dezevo-1282">
        <h2>📜 8. Сабор у Дежеву (1282. година) и краљ Милутин <span class="coronation-badge">Сабор</span></h2>
        <p>
          После владавине синова Стефана Првовенчаног (Радослава, Владислава и Уроша I Великог), Србијом је владао краљ Стефан Драгутин.
          Након што је почетком 1282. године несрећно пао са коња и сломио ногу код тврђаве Јелеч, Драгутин сазива државни сабор у Дежеву код Новог Пазара.
        </p>
        <div class="event-timeline-card">
          <span class="event-year-tag">Почетак 1282. године — Дежево</span>
          <p style="margin: 0; line-height: 1.85;">
            Краљ Драгутин добровољно предаје престо млађем брату <strong>Стефану Урошу II Милутину</strong>, док за себе задржава северне области (Мачву, Београд, Усору и Соли).
            Ступањем краља Милутина на престо почиње ера незаустављивог војног успона, економског јачања и великог градитељског завета у којем је Милутин подигао преко 40 светиња (Грачаница, Богородица Љевишка, Краљева црква у Студеници, Бањска).
          </p>
        </div>
      </section>

      <div class="lesson-separator"><span class="lesson-separator__ornament">❧</span></div>

      {{-- 9. MILUTINOVA OSVAJANJA --}}
      <section class="lesson-section" id="milutin-pohodi">
        <h2>⚔️ 9. Освајачки походи краља Милутина (1282–1299. година) <span class="battle-badge">Војни походи</span></h2>
        <p>
          Одмах по преузимању круне 1282. године, краљ Милутин је прешао у снажну офанзиву против Византије.
          Српска војска је муњевито заузела Скопље (које постаје нова престоница), Овче поље, Полог, Дебар и пробила се све до реке Струме и Егејског мора.
        </p>
        <div class="event-timeline-card">
          <span class="event-year-tag">1282–1299. година: Потискивање Византије и одбрана од Татара</span>
          <p style="margin: 0; line-height: 1.85;">
            Милутин је сломио татарске пљачкашке походе кана Ногаја и победио византијске војске.
            Византијски цар Андроник II Палеолог био је приморан да 1299. године затражи мир, призна сва српска освајања и за краља Милутина уда своју петогодишњу ћерку, принцезу Симониду.
            Србија је постала најмоћнија држава Вардарске долине и целог централног Балкана.
          </p>
        </div>
      </section>

      <div class="lesson-separator"><span class="lesson-separator__ornament">❧</span></div>

      {{-- 10. BITKA KOD VELBUZDA --}}
      <section class="lesson-section" id="velbuzd-1330">
        <h2>⚔️ 10. Славна Битка код Велбужда (28. јул 1330. године) <span class="battle-badge">Велика победа</span></h2>
        <p>
          Видевши снажан успон Србије, византијски цар Андроник III Палеолог и бугарски цар Михаил III Шишман склопили су војни савез са циљем да једновременим нападом са југа и истока униште српску краљевину и поделе њене територије.
        </p>
        <div class="event-timeline-card">
          <span class="event-year-tag">Субота, 28. јул 1330. године — Велбужд (данашњи Ћустендил)</span>
          <p style="margin: 0 0 10px; line-height: 1.85;">
            Српску војску предводио је мудри краљ <strong>Стефан Урош III Дечански</strong>, док је кључни ударни одред елитне српске коњице и стрелаца водио његов двадесетједногодишњи син, „млади краљ“ <strong>Стефан Душан</strong>.
          </p>
          <p style="margin: 0 0 10px; line-height: 1.85;">
            Иако је постојало примирје док су бугарски одреди били расути у потрази за храном, српска војска је у зору извела изненадни јуриш.
            Млади Душан је на челу оклопника силовито пробио бугарско средиште. Бугарска војска је претрпела потпуни слом, а бугарски цар Михаил Шишман је смртно рањен, заробљен и преминуо.
          </p>
          <p style="margin: 0; line-height: 1.85;">
            <strong>Историјски значај:</strong> Битка код Велбужда спада у највеће победе српског оружја у историји. Византијски цар је у паници повукао своју војску без борбе, Бугарска је прихватила српске услове мира, а Србија се устоличила као апсолутни господар Балкана, чиме су створени услови за проглашење Српског царства.
            У знак захвалности Богу за ову величанствену победу, краљ Стефан Дечански је подигао незаборавни манастир Високи Дечани.
          </p>
        </div>
      </section>

      <div class="lesson-separator"><span class="lesson-separator__ornament">❧</span></div>

      {{-- 11. CARSKO KRUNISANJE DUSANA --}}
      <section class="lesson-section" id="carsko-krunisanje-1346">
        <h2>👑 11. Царско крунисање Стефана Душана у Скопљу (1346. година) <span class="coronation-badge">Царско крунисање</span></h2>
        <p>
          Након ступања на престо 1331. године, краљ Душан је у низу тријумфалних војних похода освојио Албанију, Епир, Тесалију, целу Македонију и стигао све до Коринтског залива и Свете Горе.
          Србија је постала најпространија и најмоћнија држава у овом делу света.
        </p>
        <div class="event-timeline-card">
          <span class="event-year-tag">16. април 1346. године (Васкрс) — Скопље</span>
          <p style="margin: 0 0 10px; line-height: 1.85;">
            На великом свенародном сабору у престоном граду Скопљу, српска архиепископија је уздигнута на ранг <strong>Патријаршије</strong>, а први српски патријарх постао је <strong>Свети Јаникије II</strong>.
          </p>
          <p style="margin: 0; line-height: 1.85;">
            Истога дана, патријарх Јаникије, уз саслужење бугарског (трновског) патријарха Симеона, охридског архиепископа Николе и светогорског прота, свечано крунише Душана златном царском круном за <strong>„Цара Срба и Грка”</strong> (<em>Стефан у Христу Богу верни Цар Србљем и Грком</em>), а његовог сина Уроша за краља.
          </p>
        </div>
      </section>

      <div class="lesson-separator"><span class="lesson-separator__ornament">❧</span></div>

      {{-- 12. BITKA KOD STEFANIJANE --}}
      <section class="lesson-section" id="stefanijana-1344">
        <h2>⚔️ 12. Битка код Стефанијане (мај 1344. године) <span class="battle-badge">Битка</span></h2>
        <p>
          Током византијског грађанског рата у ком је Душан подржавао Јована Кантакузина, у Европу су по први пут ступили турски најамнички одреди емира Умура од Ајдина.
          Када су Турци покушали да се преко Тракије и Солуна врате у Малу Азију, цар Душан је послао српски коњички одред од 3.000 тешко оклопљених витезова под вођством војводе Прељуба.
        </p>
        <div class="event-timeline-card">
          <span class="event-year-tag">Мај 1344. године — Стефанијана (близу залива Стримон / Рендина)</span>
          <p style="margin: 0; line-height: 1.85;">
            Српски оклопници су притисли турску лаку коњицу у брдовити кланац. Међутим, Турци су се лукаво повукли на стрме стеновите врхове, напустили своје коње и из заседе засули стрелама српске коњанике који нису могли да маневришу узбрдо у тешком оклопу.
            Турци су задобили српске коње и побегли ка брдовима. Иако без стратешких последица по снагу српске државе, ова битка представља <strong>први историјски оружани сукоб Срба и Турака</strong>.
          </p>
        </div>
      </section>

      <div class="lesson-separator"><span class="lesson-separator__ornament">❧</span></div>

      {{-- 13. DUSANOV ZAKONIK --}}
      <section class="lesson-section" id="dusanov-zakonik">
        <h2>⚖️ 13. Душанов законик (1349. и 1354. година) <span class="coronation-badge">Правни темељ</span></h2>
        <p>
          Како би огромно Царство имало јединствен, праведан и чврст правни поредак, цар Душан је донео <strong>„Закон благовернаго цара Стефана”</strong>.
        </p>
        <div class="event-timeline-card">
          <span class="event-year-tag">Скопље (1349) и Сер (1354) — 201 члан</span>
          <p style="margin: 0 0 10px; line-height: 1.85;">
            Први део Законика проглашен је на Спасовданском сабору у Скопљу 21. маја 1349. године (чланови 1–135), а допуњен је на сабору у Серу 1354. године (чланови 136–201).
          </p>
          <p style="margin: 0; line-height: 1.85;">
            Душанов законик спада у највише правне домете средњовековне европске цивилизације. Посебно је чувено начело независности судства записано у члану 172:
          </p>
          <div class="quote-box" style="margin-top: 10px;">
            „Све судије да суде по закону, право, како пише у Законику, а не да суде по страху од цара.”
          </div>
        </div>
      </section>

      <div class="lesson-separator"><span class="lesson-separator__ornament">❧</span></div>

      {{-- 14. KRAJ LOZE I VECNO ZAVESTANJE --}}
      <section class="lesson-section" id="kraj-loze-1371">
        <h2>🕯️ 14. Цар Урош Нејаки и крај светородне лозе (1371. година)</h2>
        <p>
          Након изненадне смрти цара Душана у децембру 1355. године, престо преузима његов син, цар Стефан Урош V (у народу назван <em>Урош Нејаки</em>).
          Побожан, кротак и благе нарави, цар Урош није имао очеву чврстину да обузда моћну српску властелу која је цепала царство на засебне феудалне области.
        </p>
        <div class="event-timeline-card">
          <span class="event-year-tag">4. децембар 1371. године</span>
          <p style="margin: 0; line-height: 1.85;">
            Након трагичне Маричке битке у септембру 1371. године, 4. децембра 1371. преминуо је цар Урош V. Његовом смрћу угасила се мушка владарска лоза Немањића.
            Српска црква га је уврстила у ред светитеља као <strong>Светог цара Уроша</strong>.
          </p>
          <p style="margin: 10px 0 0; line-height: 1.85;">
            Иако је лоза завршена, Немањићко духовно, културно и државотворно завештање остало је вечни темељ српског опстанка, који су у наредним вековима наставили кнез Лазар, деспот Стефан Лазаревић и Ђурађ Бранковић.
          </p>
        </div>
      </section>

      {{-- DOKUMENTARNI VIDEO SADRŽAJI --}}
      @php
          $moduleVideos = \App\Support\EducationalMedia::forEduModule('istorija-kultura');
      @endphp
      @if(!empty($moduleVideos) && count($moduleVideos) > 0)
          @include('partials.video-section', [
              'videos' => $moduleVideos,
              'sectionTitle' => '🎬 Историјске емисије и видео документарци (HistoryCast)'
          ])
      @endif

      {{-- IZVORI I STRUČNA LITERATURA --}}
      @include('partials.edu-sources', [
          'title' => 'Извори и стручна литература за тему „Држава Немањића“',
          'sources' => [
              [
                  'author' => 'Станоје Станојевић',
                  'work' => 'Историја српскога народа',
                  'details' => 'Београд (поглавља о успону династије Немањића, војним походима, биткама и државно-правној организацији).'
              ],
              [
                  'author' => 'Владимир Ћоровић',
                  'work' => 'Историја Срба',
                  'details' => 'Томови о средњовековној српској држави, владавини Стефана Немање, краља Милутина и царству Стефана Душана.'
              ],
              [
                  'author' => 'Стефан Првовенчани и Свети Сава',
                  'work' => 'Житије Светог Симеона',
                  'details' => 'Аутентични средњовековни списи о животу, саборима и завештању великог жупана Стефана Немање.'
              ],
              [
                  'author' => 'Архиепископ Данило II',
                  'work' => 'Животи краљева и архиепископа српских (Данилов зборник)',
                  'details' => 'Основни историјски извор за период владавине краљева Драгутина, Милутина, Стефана Дечанског и Душана.'
              ],
              [
                  'author' => 'Српска академија наука и уметности (САНУ)',
                  'work' => 'Душанов законик (Иловички, Призренски и Раковички рукопис)',
                  'details' => 'Стручна издања и правно-историјски коментари о Законику цара Стефана Душана из 1349. и 1354. године.'
              ],
              [
                  'author' => 'Историјски истраживачки портал',
                  'work' => 'Династија Немањића — историјски летопис, сабори и битке',
                  'details' => 'Хронологија владарске лозе, крунисања, сабора и прекретница српског средњег века.'
              ]
          ],
          'note' => 'Сви историјски подаци, године битака, датуми крунисања и хронологија сабора усклађени су са званичном српском средњовековном историографијом и рукописним изворима.'
      ])

    </div>
  </div>
</section>
@endsection