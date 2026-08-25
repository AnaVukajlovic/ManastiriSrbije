@extends('layouts.site')

@section('title','Srpska crkva i Sveti Sava')

@section('content')
<style>
  .lesson-page{
    max-width: 1240px;
    margin: 0 auto;
    width: 100%;
  }

  .lesson-card{
    background: linear-gradient(180deg, rgba(28, 18, 16, 0.96), rgba(16, 10, 10, 0.96));
    border: 1.5px solid rgba(197, 162, 74, 0.28);
    border-radius: 24px;
    padding: 38px 42px;
    box-shadow: 0 16px 40px rgba(0,0,0,.45);
    width: 100%;
  }

  .lesson-page h1{
    font-size: clamp(2rem, 3.2vw, 2.7rem);
    margin-bottom: 20px;
    color: var(--gold, #c5a24a);
    font-weight: 800;
  }

  .lesson-page .lead{
    font-size: 1.12rem;
    line-height: 1.95;
    text-align: justify;
    text-justify: inter-word;
    color: rgba(255,255,255,.96);
    margin-bottom: 24px;
    font-style: italic;
  }

  .lesson-page h2{
    margin-top: 36px;
    margin-bottom: 14px;
    font-size: 1.5rem;
    color: var(--gold, #c5a24a);
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

  @media (max-width: 768px){
    .lesson-card{
      padding: 22px 18px;
      border-radius: 16px;
    }

    .lesson-page h1{
      font-size: 1.75rem;
    }

    .lesson-page h2{
      font-size: 1.3rem;
    }

    .lesson-page p,
    .lesson-page .lead{
      font-size: 0.98rem;
      line-height: 1.8;
    }
  }
</style>

<section class="section">
  <div class="container lesson-page">
    <div class="lesson-card">

      <h1>Srpska crkva i Sveti Sava</h1>

      <p class="lead">
        Razvoj Srpske pravoslavne crkve predstavlja jedan od najvažnijih procesa u
        oblikovanju duhovnog, kulturnog i nacionalnog identiteta srpskog naroda.
        U tom procesu presudnu ulogu imao je Sveti Sava, koji nije bio samo prvi
        srpski arhiepiskop, već i prosvetitelj, organizator crkvenog života i jedna
        od najznačajnijih ličnosti srpske istorije.
      </p>

      <h2>Početak samostalnog crkvenog života</h2>

      <p>
        Pre sticanja samostalnosti, crkveni život na srpskim prostorima bio je pod
        uticajem različitih crkvenih centara. U vremenu kada se srpska država
        učvršćivala pod dinastijom Nemanjića, javila se i potreba da narod dobije
        svoju samostalnu crkvenu organizaciju. To je bilo važno ne samo sa verskog,
        već i sa političkog i kulturnog aspekta, jer je samostalna crkva doprinosila
        stabilnosti države i jačanju zajedničkog identiteta.
      </p>

      <p>
        Ključnu ulogu u tome imao je Rastko Nemanjić, kasnije monah Sava. Kao sin
        Stefana Nemanje, on je mogao da vodi život vladara, ali je izabrao duhovni
        put. Upravo taj izbor učinio ga je jednom od najvažnijih ličnosti srpske
        istorije i pravoslavne tradicije.
      </p>

      <h2>Sveti Sava i autokefalnost</h2>

      <p>
        Najveći istorijski uspeh Svetog Save bilo je dobijanje autokefalnosti
        Srpske crkve 1219. godine. Time je Srpska crkva postala samostalna, a
        Sveti Sava njen prvi arhiepiskop. Ovaj događaj imao je ogroman značaj,
        jer je srpski narod dobio svoju uređenu crkvenu organizaciju, nezavisnu
        u unutrašnjem upravljanju.
      </p>

      <p>
        Autokefalnost nije bila važna samo za crkvu, već i za čitavo društvo.
        Ona je doprinela učvršćivanju države, jačanju duhovnog jedinstva i
        povezivanju različitih oblasti u jednu celinu. Tako su crkva i država
        u tom periodu delovale u snažnoj međusobnoj povezanosti.
      </p>

      <h2>Organizacija crkve i episkopije</h2>

      <p>
        Nakon dobijanja samostalnosti, Sveti Sava je radio na uređenju crkvenog
        života. Osnovao je episkopije, odredio njihovu organizaciju i postavio
        temelje stabilne crkvene uprave. Na taj način Srpska crkva nije ostala
        samo simbol samostalnosti, već je postala uređena institucija koja je
        mogla da odgovori na potrebe naroda.
      </p>

      <p>
        Episkopije su imale veliku ulogu u širenju vere, pismenosti i obrazovanja.
        Kroz njih su se crkveni život i duhovna tradicija prenosili u različite
        krajeve srpskih zemalja, čime je dodatno jačana povezanost naroda i
        zajednička svest o pripadnosti istoj veri i kulturi.
      </p>

      <h2>Sveti Sava kao prosvetitelj i zakonodavac</h2>

      <p>
        Značaj Svetog Save ne ogleda se samo u crkvenoj organizaciji. On je bio i
        veliki prosvetitelj, jer je razumeo da vera i znanje moraju biti povezani.
        Zato su manastiri u njegovo vreme postajali mesta molitve, ali i centri
        obrazovanja, prepisivanja knjiga i očuvanja pisane kulture.
      </p>

      <p>
        Poseban doprinos dao je pisanjem Zakonopravila, važnog pravnog i crkvenog
        zbornika koji je uređivao mnoga pitanja života u tadašnjem društvu. Time
        je postavio temelje pravnog i moralnog poretka, povezujući duhovne vrednosti
        sa svakodnevnim životom zajednice.
      </p>

      <h2>Manastiri kao centri duhovnosti i kulture</h2>

      <p>
        U vremenu Svetog Save i njegovih naslednika manastiri su postali središta
        duhovnog i kulturnog života. U njima su se čuvale knjige, razvijala pismenost,
        negovalo bogosluženje i oblikovala umetnost. Manastiri nisu bili samo mesta
        molitve, već i čuvari identiteta, istorije i obrazovanja.
      </p>

      <p>
        Kroz rad crkve i manastira očuvan je kontinuitet srpske kulture. To je bilo
        naročito važno i u kasnijim vekovima, kada je upravo crkva postala jedan od
        glavnih oslonaca narodnog opstanka i pamćenja.
      </p>

      <h2>Trajni značaj Svetog Save</h2>

      <p>
        Sveti Sava je ostao upamćen kao jedna od centralnih ličnosti srpske istorije,
        jer je u sebi spojio duhovnost, mudrost, obrazovanje i organizatorsku snagu.
        Njegovo delo prevazilazi granice jednog vremena: ono je trajno uticalo na
        razvoj crkve, škole, kulture i nacionalne svesti.
      </p>

      <p>
        Zbog toga se Sveti Sava ne posmatra samo kao istorijska ličnost, već i kao
        simbol prosvete, mira, vere i duhovnog jedinstva. Njegovo nasleđe i danas
        zauzima posebno mesto u životu srpskog naroda i Srpske pravoslavne crkve.
      </p>

      {{-- DOKUMENTARNI VIDEO SADRŽAJI --}}
      @php
          $moduleVideos = \App\Support\EducationalMedia::forEduModule('srpska-crkva');
      @endphp
      @if(!empty($moduleVideos) && count($moduleVideos) > 0)
          @include('partials.video-section', [
              'videos' => $moduleVideos,
              'sectionTitle' => '🎬 Video sadržaji: Sveti Sava i autokefalna crkva (HistoryCast)'
          ])
      @endif

      {{-- IZVORI I STRUČNA LITERATURA --}}
      @include('partials.edu-sources', [
          'title' => 'Извори и стручна литература за тему „Српска црква кроз векове“',
          'sources' => [
              [
                  'author' => 'Епископ Никодим Милаш',
                  'work' => 'Православно црквено право / Историја Цркве',
                  'details' => 'Канонска и правна анализа добијања аутокефалности Жичке архиепископије 1219. године.'
              ],
              [
                  'author' => 'Свети Сава Српски',
                  'work' => 'Законоправило (Номоканон) — Иловички препис из 1262. године',
                  'details' => 'Први српски правни кодекс који спаја грађанско законодавство и црквене каноне.'
              ],
              [
                  'author' => 'Димитрије Богдановић',
                  'work' => 'Историја старе српске књижевности и теологије',
                  'details' => 'СКЗ, Београд (анализа списа Светог Саве, Доментијана и Теодосија).'
              ],
              [
                  'author' => 'Српска Православна Црква (СПЦ)',
                  'work' => 'Споменица поводом 800 година аутокефалности СПЦ (1219–2019)',
                  'details' => 'Званична монографија о историјском путу и устројству српске јерархије.'
              ],
              [
                  'author' => 'Информативна служба СПЦ',
                  'work' => 'Званични портал Српске Православне Цркве (spc.rs)',
                  'details' => 'Историјски документи о епархијама, патријарсима и црквеном предању.'
              ]
          ],
          'note' => 'Подаци о црквеним саборима, рукоположењима и канонском статусу засновани су на документима Патријаршије СПЦ и канонским зборницима.'
      ])
  </div>
</section>
@endsection