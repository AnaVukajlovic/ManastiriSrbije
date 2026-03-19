@extends('layouts.site')

@section('title','Edukacija — Pravoslavni Svetionik')
@section('nav_edukacija','active')

@section('content')
<style>
  .edu-page{
    --edu-ink: rgba(255,255,255,.92);
    --edu-muted: rgba(255,255,255,.72);
    --edu-line: rgba(255,255,255,.08);
    --edu-soft: rgba(255,255,255,.03);
    --edu-gold-soft: rgba(197,162,74,.10);
    --edu-gold-line: rgba(197,162,74,.22);
    --edu-shadow: 0 18px 40px rgba(0,0,0,.22);
  }

  .edu-page .container{
    width: min(1280px, calc(100% - 40px));
    max-width: none;
  }

  .edu-hero{
    position: relative;
    padding: 18px 0 30px;
    margin-bottom: 18px;
  }

  .edu-hero::after{
    content:"";
    display:block;
    width:100%;
    height:1px;
    margin-top:26px;
    background:linear-gradient(
      90deg,
      transparent,
      rgba(197,162,74,.24),
      rgba(255,255,255,.10),
      rgba(197,162,74,.24),
      transparent
    );
  }

  .edu-kicker{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:8px 14px;
    border-radius:999px;
    border:1px solid rgba(197,162,74,.22);
    background:rgba(197,162,74,.08);
    color:var(--gold);
    font-size:.82rem;
    font-weight:700;
    margin-bottom:14px;
  }

  .edu-title{
    margin:0 0 12px;
    color:var(--gold);
    font-size:clamp(2.2rem, 4vw, 4rem);
    line-height:1.02;
    letter-spacing:-.03em;
  }

  .edu-sub{
    max-width:980px;
    margin:0;
    color:var(--edu-ink);
    font-size:1.04rem;
    line-height:1.95;
    text-align:justify;
    text-justify:inter-word;
  }

  .edu-list{
    display:flex;
    flex-direction:column;
    gap:0;
  }

  .edu-item{
    position:relative;
    display:grid;
    grid-template-columns: 160px minmax(0, 1fr);
    gap:28px;
    padding:34px 0;
    border-bottom:1px solid var(--edu-line);
  }

  .edu-item::before{
    content:"";
    position:absolute;
    top:12px;
    right:0;
    width:280px;
    height:160px;
    background:radial-gradient(circle, rgba(197,162,74,.10), transparent 70%);
    pointer-events:none;
    opacity:.85;
  }

  .edu-num{
    display:flex;
    flex-direction:column;
    align-items:flex-start;
    justify-content:flex-start;
    gap:8px;
    padding-top:2px;
  }

  .edu-num__index{
    font-size:2.4rem;
    line-height:1;
    font-weight:800;
    letter-spacing:-.04em;
    color:rgba(197,162,74,.92);
  }

  .edu-num__line{
    width:72px;
    height:2px;
    border-radius:999px;
    background:linear-gradient(90deg, rgba(197,162,74,.7), transparent);
  }

  .edu-body{
    display:grid;
    grid-template-columns:minmax(0, 1fr) 220px;
    gap:28px;
    align-items:start;
  }

  .edu-main h2{
    margin:0 0 12px;
    color:var(--gold);
    font-size:1.95rem;
    line-height:1.08;
    letter-spacing:-.02em;
  }

  .edu-lead{
    margin:0 0 12px;
    color:var(--edu-ink);
    font-size:1.06rem;
    line-height:1.85;
    text-align:justify;
    text-justify:inter-word;
  }

  .edu-text{
    margin:0 0 16px;
    color:var(--edu-muted);
    font-size:1rem;
    line-height:1.9;
    text-align:justify;
    text-justify:inter-word;
  }

  .edu-tags{
    display:flex;
    flex-wrap:wrap;
    gap:8px;
  }

  .edu-tag{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:7px 11px;
    border-radius:999px;
    border:1px solid rgba(255,255,255,.08);
    background:rgba(255,255,255,.03);
    color:var(--edu-ink);
    font-size:.78rem;
    font-weight:600;
  }

  .edu-side{
    display:flex;
    flex-direction:column;
    align-items:flex-end;
    gap:14px;
    padding-top:4px;
  }

  .edu-icon{
    width:56px;
    height:56px;
    display:grid;
    place-items:center;
    border-radius:18px;
    border:1px solid rgba(197,162,74,.18);
    background:rgba(197,162,74,.08);
    color:var(--gold);
    font-size:1.2rem;
    box-shadow:var(--edu-shadow);
  }

  .edu-btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    gap:8px;
    min-width:170px;
    padding:12px 18px;
    border-radius:999px;
    border:1px solid rgba(197,162,74,.24);
    background:rgba(197,162,74,.10);
    color:var(--gold);
    text-decoration:none;
    font-weight:700;
    transition:all .2s ease;
  }

  .edu-btn:hover{
    transform:translateY(-1px);
    background:rgba(197,162,74,.14);
    border-color:rgba(197,162,74,.32);
    box-shadow:0 10px 24px rgba(0,0,0,.18);
  }

  .edu-item--featured{
    padding-top:10px;
  }

  .edu-item--featured .edu-main h2{
    font-size:2.35rem;
  }

  .edu-item--featured .edu-num__index{
    font-size:3rem;
  }

  .edu-item--featured .edu-lead{
    font-size:1.1rem;
  }

  .edu-foot{
    padding:34px 0 8px;
  }

  .edu-foot__box{
    padding:22px 24px;
    border:1px solid var(--edu-line);
    border-radius:22px;
    background:
      radial-gradient(circle at top right, rgba(197,162,74,.10), transparent 28%),
      linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,.015)),
      rgba(20,12,12,.86);
  }

  .edu-foot__title{
    margin:0 0 10px;
    color:var(--gold);
    font-size:1.22rem;
    line-height:1.2;
  }

  .edu-foot__text{
    margin:0;
    color:var(--edu-ink);
    line-height:1.85;
    text-align:justify;
    text-justify:inter-word;
  }

  @media (max-width: 980px){
    .edu-item{
      grid-template-columns:1fr;
      gap:18px;
      padding:28px 0;
    }

    .edu-body{
      grid-template-columns:1fr;
      gap:18px;
    }

    .edu-side{
      align-items:flex-start;
      padding-top:0;
    }

    .edu-num{
      flex-direction:row;
      align-items:center;
    }

    .edu-num__line{
      width:54px;
    }
  }

  @media (max-width: 760px){
    .edu-page .container{
      width:min(100%, calc(100% - 20px));
    }

    .edu-title{
      font-size:clamp(1.95rem, 8vw, 2.9rem);
    }

    .edu-main h2{
      font-size:1.5rem;
    }

    .edu-item--featured .edu-main h2{
      font-size:1.7rem;
    }

    .edu-lead,
    .edu-text,
    .edu-sub,
    .edu-foot__text{
      font-size:.97rem;
    }

    .edu-btn{
      min-width:auto;
      width:100%;
    }
  }
</style>

<section class="section edu-page">
  <div class="container">

    <div class="edu-hero">
      <span class="edu-kicker">📚 Edukacija • Pravoslavni Svetionik</span>
      <h1 class="edu-title">Obrazovne celine</h1>
      <p class="edu-sub">
        Istraži glavne teme kroz koje se najjasnije razumeju nastanak svetinja, uloga ktitora,
        razvoj crkvenog života i umetničko nasleđe koje je obeležilo srpski srednji vek.
        Svaka celina vodi dubljem razumevanju istorije, duhovnosti i kulturnog kontinuiteta.
      </p>
    </div>

    <div class="edu-list">

      <section class="edu-item edu-item--featured">
        <div class="edu-num">
          <div class="edu-num__index">01</div>
          <div class="edu-num__line"></div>
        </div>

        <div class="edu-body">
          <div class="edu-main">
            <h2>Država Nemanjića</h2>
            <p class="edu-lead">
              Temelj srednjovekovne Srbije i jedno od najvažnijih razdoblja nacionalne istorije.
            </p>
            <p class="edu-text">
              Upoznaj nastanak i razvoj srpske države, ulogu Stefana Nemanje i njegovih naslednika,
              političko jačanje Srbije i vezu između vladarske vlasti, Crkve i manastira.
            </p>
            <div class="edu-tags">
              <span class="edu-tag">dinastija</span>
              <span class="edu-tag">država</span>
              <span class="edu-tag">srednji vek</span>
              <span class="edu-tag">zadužbine</span>
            </div>
          </div>

          <div class="edu-side">
            <div class="edu-icon">👑</div>
            <a class="edu-btn" href="{{ route('edukacija.show','istorija-kultura') }}">Otvori lekciju →</a>
          </div>
        </div>
      </section>

      <section class="edu-item">
        <div class="edu-num">
          <div class="edu-num__index">02</div>
          <div class="edu-num__line"></div>
        </div>

        <div class="edu-body">
          <div class="edu-main">
            <h2>Srpska crkva i Sveti Sava</h2>
            <p class="edu-lead">
              Put ka autokefalnosti i oblikovanju samostalnog duhovnog identiteta.
            </p>
            <p class="edu-text">
              Saznaj kako je nastala samostalna Srpska crkva, kakva je bila uloga Svetog Save
              i zašto su manastiri postali središta vere, pismenosti i kulturnog kontinuiteta.
            </p>
            <div class="edu-tags">
              <span class="edu-tag">Sveti Sava</span>
              <span class="edu-tag">autokefalnost</span>
              <span class="edu-tag">episkopije</span>
              <span class="edu-tag">duhovnost</span>
            </div>
          </div>

          <div class="edu-side">
            <div class="edu-icon">⛪</div>
            <a class="edu-btn" href="{{ route('edukacija.show','srpska-crkva') }}">Otvori lekciju →</a>
          </div>
        </div>
      </section>

      <section class="edu-item">
        <div class="edu-num">
          <div class="edu-num__index">03</div>
          <div class="edu-num__line"></div>
        </div>

        <div class="edu-body">
          <div class="edu-main">
            <h2>Manastiri kao zadužbine</h2>
            <p class="edu-lead">
              Svetinje kao mesta molitve, kulture, pamćenja i vladarskog zaveta.
            </p>
            <p class="edu-text">
              Otkrij šta znači zadužbinarstvo, zbog čega su vladari i vlastela podizali manastire
              i kako su te svetinje postajale duhovni, obrazovni i kulturni centri naroda.
            </p>
            <div class="edu-tags">
              <span class="edu-tag">ktitori</span>
              <span class="edu-tag">monaštvo</span>
              <span class="edu-tag">nasleđe</span>
              <span class="edu-tag">zavet</span>
            </div>
          </div>

          <div class="edu-side">
            <div class="edu-icon">🏛️</div>
            <a class="edu-btn" href="{{ route('edukacija.show','manastiri-kao-zaduzbine') }}">Otvori lekciju →</a>
          </div>
        </div>
      </section>

      <section class="edu-item">
        <div class="edu-num">
          <div class="edu-num__index">04</div>
          <div class="edu-num__line"></div>
        </div>

        <div class="edu-body">
          <div class="edu-main">
            <h2>Arhitektura i umetnost</h2>
            <p class="edu-lead">
              Prepoznavanje epoha kroz stilove gradnje, freske, ikone i simboliku.
            </p>
            <p class="edu-text">
              Nauči kako da razlikuješ umetničke pravce, planove crkava, ikonopis i freskoslikarstvo
              koji su obeležili vrhunce srpske srednjovekovne umetnosti.
            </p>
            <div class="edu-tags">
              <span class="edu-tag">Raška škola</span>
              <span class="edu-tag">Moravska škola</span>
              <span class="edu-tag">freske</span>
              <span class="edu-tag">ikone</span>
            </div>
          </div>

          <div class="edu-side">
            <div class="edu-icon">🎨</div>
            <a class="edu-btn" href="{{ route('edukacija.show','arhitektura-umetnost') }}">Otvori lekciju →</a>
          </div>
        </div>
      </section>

      <section class="edu-item">
        <div class="edu-num">
          <div class="edu-num__index">05</div>
          <div class="edu-num__line"></div>
        </div>

        <div class="edu-body">
          <div class="edu-main">
            <h2>Srbija pod Osmanlijama</h2>
            <p class="edu-lead">
              Istorija iskušenja, rušenja, obnove i duhovnog opstanka kroz vekove.
            </p>
            <p class="edu-text">
              Upoznaj vreme nakon pada srednjovekovne države, ulogu monaha i naroda u čuvanju vere,
              kao i značaj obnavljanja svetinja i trajanja kulturnog identiteta.
            </p>
            <div class="edu-tags">
              <span class="edu-tag">osmansko doba</span>
              <span class="edu-tag">obnova</span>
              <span class="edu-tag">predanje</span>
              <span class="edu-tag">opstanak</span>
            </div>
          </div>

          <div class="edu-side">
            <div class="edu-icon">🕯️</div>
            <a class="edu-btn" href="{{ route('edukacija.show','srbija-pod-osmanlijama') }}">Otvori lekciju →</a>
          </div>
        </div>
      </section>

      <section class="edu-item">
        <div class="edu-num">
          <div class="edu-num__index">06</div>
          <div class="edu-num__line"></div>
        </div>

        <div class="edu-body">
          <div class="edu-main">
            <h2>Učenje i povezivanje sadržaja</h2>
            <p class="edu-lead">
              Pregledno i moderno učenje kroz povezivanje tema, mesta, ličnosti i pojmova.
            </p>
            <p class="edu-text">
              Ova celina sabira sadržaj iz ostalih modula i otvara prostor za interaktivno učenje,
              lakše snalaženje i dublje povezivanje istorijskih i duhovnih tema.
            </p>
            <div class="edu-tags">
              <span class="edu-tag">timeline</span>
              <span class="edu-tag">porodično stablo</span>
              <span class="edu-tag">kviz</span>
              <span class="edu-tag">AI vodič</span>
            </div>
          </div>

          <div class="edu-side">
            <div class="edu-icon">🧠</div>
            <a class="edu-btn" href="{{ route('edukacija.show','ucenje-interakcija') }}">Otvori lekciju →</a>
          </div>
        </div>
      </section>

    </div>

    <div class="edu-foot">
      <div class="edu-foot__box">
        <h3 class="edu-foot__title">Edukativni modul kao povezana celina</h3>
        <p class="edu-foot__text">
          Ovaj deo aplikacije zamišljen je tako da korisnik ne čita izdvojene tekstove, već da postepeno
          ulazi u celovit prikaz srpske duhovne i kulturne istorije — od države i Crkve, preko manastira
          i umetnosti, do interaktivnog povezivanja znanja kroz kviz, vremensku liniju i dodatne module.
        </p>
      </div>
    </div>

  </div>
</section>
@endsection