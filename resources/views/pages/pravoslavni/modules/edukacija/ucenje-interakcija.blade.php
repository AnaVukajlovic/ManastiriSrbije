@extends('layouts.site')

@section('title', 'Učenje i interakcija — Pravoslavni Svetionik')
@section('nav_edukacija', 'active')

@section('content')
<style>
  .ui-page{
    --ui-ink: rgba(255,255,255,.92);
    --ui-muted: rgba(255,255,255,.74);
    --ui-line: rgba(255,255,255,.08);
    --ui-soft: rgba(255,255,255,.03);
    --ui-gold-soft: rgba(197,162,74,.10);
    --ui-gold-line: rgba(197,162,74,.22);
    --ui-shadow: 0 16px 40px rgba(0,0,0,.24);
    --ui-shadow-hover: 0 22px 50px rgba(0,0,0,.30);
  }

  .ui-page .container{
    width:min(1360px, calc(100% - 34px));
    max-width:none;
  }

  .ui-hero{
    position:relative;
    overflow:hidden;
    margin-bottom:26px;
    padding:34px 36px 30px;
    border-radius:28px;
    border:1px solid var(--ui-gold-line);
    background:
      radial-gradient(circle at top right, rgba(197,162,74,.14), transparent 28%),
      radial-gradient(circle at left center, rgba(197,162,74,.06), transparent 24%),
      linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,.015)),
      rgba(20,12,12,.90);
    box-shadow:var(--ui-shadow);
  }

  .ui-kicker{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:8px 14px;
    border-radius:999px;
    border:1px solid rgba(197,162,74,.22);
    background:rgba(197,162,74,.10);
    color:var(--gold);
    font-size:.82rem;
    font-weight:700;
    margin-bottom:16px;
  }

  .ui-hero__title{
    margin:0 0 14px;
    font-size:clamp(2.1rem, 4vw, 3.6rem);
    line-height:1.03;
    letter-spacing:-.03em;
    color:var(--gold);
  }

  .ui-hero__lead{
    margin:0 0 18px;
    max-width:1040px;
    color:var(--ui-ink);
    font-size:1.04rem;
    line-height:1.95;
    text-align:justify;
    text-justify:inter-word;
  }

  .ui-hero__chips{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
    margin-bottom:18px;
  }

  .ui-hero__chips span{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:8px 13px;
    border-radius:999px;
    border:1px solid rgba(255,255,255,.08);
    background:rgba(255,255,255,.04);
    color:var(--ui-ink);
    font-size:.8rem;
    font-weight:600;
  }

  .ui-hero__note{
    max-width:980px;
    padding:16px 18px;
    border-radius:18px;
    border:1px solid rgba(255,255,255,.08);
    background:rgba(255,255,255,.03);
    color:var(--ui-ink);
    line-height:1.85;
    font-size:.96rem;
    text-align:justify;
    text-justify:inter-word;
  }

  .ui-intro-grid{
    display:grid;
    grid-template-columns:repeat(3, minmax(0, 1fr));
    gap:18px;
    margin-bottom:30px;
  }

  .ui-info-card{
    position:relative;
    overflow:hidden;
    padding:22px 22px;
    border-radius:22px;
    border:1px solid var(--ui-line);
    background:
      radial-gradient(circle at top right, rgba(197,162,74,.08), transparent 28%),
      linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,.015)),
      rgba(20,12,12,.88);
    box-shadow:var(--ui-shadow);
  }

  .ui-info-card h3{
    margin:0 0 10px;
    color:var(--gold);
    font-size:1.08rem;
    line-height:1.2;
    font-weight:800;
  }

  .ui-info-card p{
    margin:0;
    color:var(--ui-ink);
    line-height:1.82;
    font-size:.97rem;
    text-align:justify;
    text-justify:inter-word;
  }

  .ui-sectionhead{
    margin-bottom:16px;
  }

  .ui-sectionhead h2{
    margin:0 0 8px;
    color:var(--gold);
    font-size:2rem;
    line-height:1.08;
    letter-spacing:-.02em;
  }

  .ui-sectionhead p{
    margin:0;
    color:var(--ui-ink);
    font-size:.98rem;
    line-height:1.8;
  }

  .ui-modules-grid{
    display:grid;
    grid-template-columns:repeat(4, minmax(0, 1fr));
    gap:18px;
  }

  .ui-module-card{
    position:relative;
    overflow:hidden;
    min-height:272px;
    display:flex;
    flex-direction:column;
    padding:22px 20px 20px;
    border-radius:22px;
    border:1px solid var(--ui-line);
    background:
      radial-gradient(circle at top right, rgba(197,162,74,.10), transparent 26%),
      linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,.015)),
      rgba(20,12,12,.90);
    box-shadow:var(--ui-shadow);
    transition:transform .22s ease, box-shadow .22s ease, border-color .22s ease;
  }

  .ui-module-card:hover{
    transform:translateY(-4px);
    box-shadow:var(--ui-shadow-hover);
    border-color:rgba(197,162,74,.18);
  }

  .ui-module-card__icon{
    width:46px;
    height:46px;
    display:grid;
    place-items:center;
    border-radius:15px;
    background:rgba(197,162,74,.10);
    border:1px solid rgba(197,162,74,.18);
    color:var(--gold);
    font-size:1.08rem;
    margin-bottom:16px;
  }

  .ui-module-card h3{
    margin:0 0 10px;
    color:var(--gold);
    font-size:1.14rem;
    line-height:1.25;
    font-weight:800;
  }

  .ui-module-card p{
    margin:0 0 14px;
    color:var(--ui-ink);
    line-height:1.8;
    font-size:.95rem;
    text-align:justify;
    text-justify:inter-word;
  }

  .ui-tags{
    display:flex;
    flex-wrap:wrap;
    gap:8px;
    margin-top:auto;
    margin-bottom:16px;
  }

  .ui-tags span{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:6px 10px;
    border-radius:999px;
    border:1px solid rgba(255,255,255,.08);
    background:rgba(255,255,255,.04);
    color:var(--ui-ink);
    font-size:.76rem;
    font-weight:600;
  }

  .ui-btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width:max-content;
    padding:10px 16px;
    border-radius:999px;
    border:1px solid rgba(197,162,74,.24);
    background:rgba(197,162,74,.10);
    color:var(--gold);
    text-decoration:none;
    font-weight:700;
    transition:all .2s ease;
  }

  .ui-btn:hover{
    background:rgba(197,162,74,.14);
    border-color:rgba(197,162,74,.30);
  }

  .ui-module-card--wide{
    grid-column:span 2;
    min-height:240px;
  }

  @media (max-width: 1200px){
    .ui-modules-grid{
      grid-template-columns:repeat(2, minmax(0, 1fr));
    }

    .ui-module-card--wide{
      grid-column:span 2;
    }
  }

  @media (max-width: 900px){
    .ui-intro-grid{
      grid-template-columns:1fr;
    }
  }

  @media (max-width: 760px){
    .ui-page .container{
      width:min(100%, calc(100% - 20px));
    }

    .ui-hero,
    .ui-info-card,
    .ui-module-card{
      padding:20px;
      border-radius:20px;
    }

    .ui-modules-grid{
      grid-template-columns:1fr;
      gap:16px;
    }

    .ui-module-card--wide{
      grid-column:auto;
    }

    .ui-hero__title{
      font-size:clamp(1.9rem, 8vw, 2.8rem);
    }

    .ui-sectionhead h2{
      font-size:1.6rem;
    }

    .ui-hero__lead,
    .ui-hero__note,
    .ui-info-card p,
    .ui-module-card p{
      font-size:.96rem;
    }

    .ui-btn{
      width:100%;
    }
  }
</style>

<section class="section ui-page">
  <div class="container">

    <div class="ui-hero">
      <div class="ui-hero__content">
        <span class="ui-kicker">Obrazovni modul</span>
        <h1 class="ui-hero__title">Učenje i interakcija</h1>

        <p class="ui-hero__lead">
          Ovaj deo aplikacije namenjen je aktivnom učenju kroz povezivanje istorijskih tema,
          ličnosti, događaja i duhovnih pojmova. Korisnik ovde ne čita samo sadržaj, već ga
          istražuje kroz interaktivne module.
        </p>

        <div class="ui-hero__chips">
          <span>porodično stablo</span>
          <span>timeline istorije</span>
          <span>kviz</span>
          <span>AI vodič</span>
        </div>

        <div class="ui-hero__note">
          Kroz ovu celinu korisnik može da prati razvoj dinastije Nemanjić, razume ključne istorijske periode,
          proveri svoje znanje i dobije dodatna objašnjenja kroz interaktivne module.
        </div>
      </div>
    </div>

    <div class="ui-intro-grid">
      <div class="ui-info-card">
        <h3>Aktivno učenje</h3>
        <p>
          Sadržaj je organizovan tako da korisnik ne samo čita, već povezuje teme i lakše pamti informacije.
        </p>
      </div>

      <div class="ui-info-card">
        <h3>Povezivanje sadržaja</h3>
        <p>
          Istorijske ličnosti, događaji i pojmovi povezuju se kroz module koji olakšavaju razumevanje širih celina.
        </p>
      </div>

      <div class="ui-info-card">
        <h3>Interaktivan pristup</h3>
        <p>
          Ovaj deo objedinjuje sadržaje iz edukacije i pretvara ih u pregledno, moderno i aktivno iskustvo učenja.
        </p>
      </div>
    </div>

    <div class="sectionhead ui-sectionhead">
      <div>
        <h2>Interaktivni moduli</h2>
        <p>Izaberi način učenja koji ti najviše odgovara.</p>
      </div>
    </div>

    <div class="ui-modules-grid">

      <article class="ui-module-card">
        <div class="ui-module-card__icon">👑</div>
        <h3>Porodično stablo Nemanjića</h3>
        <p>
          Vizuelni prikaz dinastije sa vezama između članova porodice i pregledom važnih ličnosti.
        </p>
        <div class="ui-tags">
          <span>dinastija</span>
          <span>porodica</span>
          <span>istorija</span>
        </div>
        <a href="{{ route('edukacija.porodicno-stablo') }}" class="ui-btn">Otvori</a>
      </article>

      <article class="ui-module-card">
        <div class="ui-module-card__icon">🕰️</div>
        <h3>Vremenska linija istorije</h3>
        <p>
          Hronološki pregled Nemanjića, istorije SPC i perioda pod osmanskom vlašću.
        </p>
        <div class="ui-tags">
          <span>timeline</span>
          <span>periodi</span>
          <span>događaji</span>
        </div>
        <a href="{{ route('edukacija.timeline') }}" class="ui-btn">Otvori</a>
      </article>

      <article class="ui-module-card">
        <div class="ui-module-card__icon">📘</div>
        <h3>Kviz iz istorije</h3>
        <p>
          Kratka pitanja za proveru znanja o srednjovekovnoj Srbiji, vladarima i važnim događajima.
        </p>
        <div class="ui-tags">
          <span>istorija</span>
          <span>provera</span>
          <span>učenje</span>
        </div>
        <a href="{{ route('edukacija.quiz-history') }}" class="ui-btn">Otvori</a>
      </article>

      <article class="ui-module-card">
        <div class="ui-module-card__icon">☦️</div>
        <h3>Kviz iz pravoslavlja</h3>
        <p>
          Pitanja iz oblasti vere, crkvenog života, praznika, svetitelja i osnovnih pojmova.
        </p>
        <div class="ui-tags">
          <span>vera</span>
          <span>pravoslavlje</span>
          <span>kviz</span>
        </div>
        <a href="{{ route('edukacija.quiz-orthodox') }}" class="ui-btn">Otvori</a>
      </article>

      <article class="ui-module-card ui-module-card--wide">
        <div class="ui-module-card__icon">🤖</div>
        <h3>AI vodič</h3>
        <p>
          Korisnik može da postavlja pitanja i dobija jednostavnija objašnjenja istorijskih i duhovnih tema
          uz pomoć lokalnog AI modela integrisanog u aplikaciju.
        </p>
        <div class="ui-tags">
          <span>AI</span>
          <span>pitanja i odgovori</span>
          <span>lokalni model</span>
        </div>
        <a href="{{ route('edukacija.ai') }}" class="ui-btn">Otvori</a>
      </article>

    </div>
  </div>
</section>
@endsection