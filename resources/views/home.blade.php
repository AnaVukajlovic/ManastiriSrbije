@extends('layouts.site')

@section('title', 'Početna — Pravoslavni Svetionik')
@section('nav_home', 'active')

@section('content')
@php
  $day = $today ?? ($calendarToday ?? null);

  if (is_array($day)) {
    $day = array_merge([
      'date'    => '—',
      'feast'   => '—',
      'fasting' => '—',
      'saint'   => '—',
      'saints'  => null,
      'red'     => null,
      'note'    => null,
    ], $day);

    if (($day['saint'] ?? '—') === '—' && !empty($day['saints'])) {
      $day['saint'] = $day['saints'];
    }
  } else {
    $day = [
      'date'    => '—',
      'feast'   => '—',
      'fasting' => '—',
      'saint'   => '—',
      'red'     => null,
      'note'    => null,
    ];
  }

  $fallbackImages = [
    asset('images/sample/djurdjevi.jpg'),
    asset('images/sample/gracanica.jpg'),
    asset('images/sample/mileseva.jpg'),
    asset('images/sample/sopocani.jpg'),
    asset('images/sample/studenica.jpg'),
    asset('images/sample/zica.jpg'),
  ];

  $cards = [];
  $usedSlugs = [];

  if (!empty($featured) && count($featured) > 0) {
    foreach ($featured as $m) {
      if (empty($m->slug) || in_array($m->slug, $usedSlugs, true)) {
        continue;
      }

      $img = !empty($m->image_url) ? $m->image_url : null;

      if (!$img && !empty($m->slug)) {
        $img = asset('images/monasteries/' . $m->slug . '.jpg');
      }

      if (!$img) {
        $img = $fallbackImages[count($cards) % count($fallbackImages)];
      }

      $regionLabel = (!empty($m->region) && $m->region !== 'Nepoznato') ? $m->region : null;
      $cityLabel   = (!empty($m->city) && $m->city !== 'Nepoznato') ? $m->city : null;

      $meta = trim(($regionLabel ?? '') . ($regionLabel && $cityLabel ? ' • ' : '') . ($cityLabel ?? ''));
      if ($meta === '') {
        $meta = 'Srbija';
      }

      $cards[] = [
        'name' => $m->name ?? 'Manastir',
        'slug' => $m->slug,
        'img'  => $img,
        'meta' => $meta,
      ];

      $usedSlugs[] = $m->slug;

      if (count($cards) >= 4) {
        break;
      }
    }
  }

  if (count($cards) < 4 && !empty($allMonasteries) && count($allMonasteries) > 0) {
    foreach ($allMonasteries as $m) {
      if (empty($m->slug) || in_array($m->slug, $usedSlugs, true)) {
        continue;
      }

      $img = !empty($m->image_url) ? $m->image_url : null;

      if (!$img && !empty($m->slug)) {
        $img = asset('images/monasteries/' . $m->slug . '.jpg');
      }

      if (!$img) {
        $img = $fallbackImages[count($cards) % count($fallbackImages)];
      }

      $regionLabel = (!empty($m->region) && $m->region !== 'Nepoznato') ? $m->region : null;
      $cityLabel   = (!empty($m->city) && $m->city !== 'Nepoznato') ? $m->city : null;

      $meta = trim(($regionLabel ?? '') . ($regionLabel && $cityLabel ? ' • ' : '') . ($cityLabel ?? ''));
      if ($meta === '') {
        $meta = 'Srbija';
      }

      $cards[] = [
        'name' => $m->name ?? 'Manastir',
        'slug' => $m->slug,
        'img'  => $img,
        'meta' => $meta,
      ];

      $usedSlugs[] = $m->slug;

      if (count($cards) >= 4) {
        break;
      }
    }
  }
@endphp

<style>
  .home-page{
    --home-stroke: rgba(255,255,255,.08);
    --home-stroke-soft: rgba(255,255,255,.06);
    --home-muted: rgba(255,255,255,.72);
    --home-muted-2: rgba(255,255,255,.58);
    --home-gold: #c5a24a;
    --home-gold-2: #e2c26a;
    --home-ink: rgba(255,255,255,.94);
  }

  .home-page .section{
    padding-top: 34px;
    padding-bottom: 12px;
  }

  .home-page .sectionhead{
    display:flex;
    flex-direction:column;
    gap:6px;
    margin-bottom:18px;
  }

  .home-page .sectionhead h2{
    margin:0;
    font-size:clamp(1.65rem, 2.3vw, 2.35rem);
    line-height:1.08;
    letter-spacing:-.025em;
    font-weight:800;
  }

  .home-page .sectionhead .muted{
    color:var(--home-muted);
    font-size:1rem;
  }

  .section-title{
    color:var(--home-gold);
    font-weight:800;
    letter-spacing:-0.5px;
    text-shadow:0 0 18px rgba(197,162,74,.14);
  }

  .hero{
    position:relative;
    overflow:hidden;
    min-height:640px;
    padding:42px 0 46px;
    border-bottom:1px solid rgba(255,255,255,.04);
  }

  .hero__bg{
    position:absolute;
    inset:0;
    background-size:cover;
    background-position:center 36%;
    background-repeat:no-repeat;
    transform:scale(1.03);
    filter:brightness(.64) contrast(1.02) saturate(1.02);
  }

  .hero__overlay{
    position:absolute;
    inset:0;
    background:
      linear-gradient(90deg, rgba(10,7,8,.78) 0%, rgba(10,7,8,.54) 38%, rgba(10,7,8,.26) 70%, rgba(10,7,8,.18) 100%),
      linear-gradient(180deg, rgba(12,8,9,.18) 0%, rgba(12,8,9,.14) 35%, rgba(12,8,9,.72) 100%),
      radial-gradient(circle at top left, rgba(197,162,74,.15), transparent 26%);
  }

  .hero__inner{
    position:relative;
    z-index:2;
    display:grid;
    grid-template-columns:minmax(0, 1.25fr) minmax(300px, 410px);
    gap:28px;
    align-items:end;
    min-height:560px;
  }

  .hero__content{
    width:min(760px, 100%);
    padding:26px 28px;
    border-radius:28px;
    background:linear-gradient(180deg, rgba(15,11,11,.48), rgba(15,11,11,.26));
    backdrop-filter:blur(8px);
    border:1px solid rgba(255,255,255,.08);
    box-shadow:0 18px 44px rgba(0,0,0,.22);
  }

  .eyebrow{
    margin:0 0 10px;
    font-size:.92rem;
    letter-spacing:.12em;
    text-transform:uppercase;
    color:var(--home-gold-2);
    font-weight:700;
  }

  .hero h1{
    margin:0 0 12px;
    font-size:clamp(2.15rem, 4.2vw, 4rem);
    line-height:1.02;
    letter-spacing:-.035em;
    color:var(--home-gold);
    text-shadow:0 0 18px rgba(197,162,74,.14);
    font-weight:800;
  }

  .hero__lead{
    margin:0 0 18px;
    max-width:60ch;
    font-size:1.06rem;
    line-height:1.8;
    color:rgba(255,255,255,.90);
  }

  .quote{
    margin:22px 0 20px;
    padding:18px 20px;
    border-radius:22px;
    border:1px solid rgba(255,255,255,.08);
    background:rgba(255,255,255,.045);
    box-shadow:inset 0 1px 0 rgba(255,255,255,.03);
  }

  .quote__title{
    margin-bottom:10px;
    font-size:.9rem;
    font-weight:700;
    letter-spacing:.08em;
    text-transform:uppercase;
    color:var(--home-gold-2);
  }

  .quote__text{
    font-size:1.04rem;
    line-height:1.8;
    color:rgba(255,255,255,.96);
  }

  .quote__meta{
    margin-top:10px;
    font-size:.95rem;
    color:var(--home-muted);
  }

  .hero__search{
    display:grid;
    grid-template-columns:minmax(0, 1fr) auto;
    gap:12px;
    margin-top:18px;
  }

  .hero__search input{
    width:100%;
    height:52px;
    border-radius:16px;
    border:1px solid rgba(255,255,255,.10);
    background:rgba(255,255,255,.05);
    color:#fff;
    padding:0 16px;
    font-size:.98rem;
  }

  .hero__search input::placeholder{
    color:rgba(255,255,255,.56);
  }

  .hero__search input:focus{
    outline:none;
    border-color:rgba(197,162,74,.70);
    box-shadow:0 0 0 3px rgba(197,162,74,.10);
    background:rgba(255,255,255,.07);
  }

  .hero__search button{
    height:52px;
    padding:0 20px;
    border:0;
    border-radius:16px;
    background:linear-gradient(135deg, var(--home-gold), var(--home-gold-2));
    color:#19120e;
    font-weight:800;
    cursor:pointer;
    box-shadow:0 10px 24px rgba(197,162,74,.18);
    transition:transform .2s ease, box-shadow .2s ease, filter .2s ease;
  }

  .hero__search button:hover{
    transform:translateY(-1px);
    box-shadow:0 14px 30px rgba(197,162,74,.22);
    filter:brightness(1.03);
  }

  .hero__aside{
    display:flex;
    align-items:end;
  }

  .asidecard{
    width:100%;
    padding:22px;
    border-radius:26px;
    border:1px solid rgba(255,255,255,.08);
    background:
      radial-gradient(circle at top left, rgba(197,162,74,.12), transparent 32%),
      linear-gradient(135deg, rgba(28,18,17,.96), rgba(12,8,9,.96));
    box-shadow:0 18px 46px rgba(0,0,0,.24);
  }

  .asidecard__head{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
    margin-bottom:12px;
  }

  .asidecard__title{
    font-size:1.05rem;
    font-weight:800;
    color:var(--home-gold);
    text-shadow:0 0 12px rgba(197,162,74,.10);
  }

  .asidecard__badge{
    padding:7px 12px;
    border-radius:999px;
    background:rgba(197,162,74,.14);
    border:1px solid rgba(197,162,74,.24);
    color:#f1d48b;
    font-size:.85rem;
    font-weight:700;
  }

  .asidecard__big{
    margin-bottom:16px;
    font-size:1.75rem;
    line-height:1.06;
    font-weight:800;
    color:#fff;
  }

  .asidecard__row{
    display:grid;
    grid-template-columns:78px 1fr;
    gap:12px;
    padding:10px 0;
    border-top:1px solid rgba(255,255,255,.06);
  }

  .asidecard__row span{
    color:var(--home-muted);
    font-size:.95rem;
  }

  .asidecard__row strong{
    color:#fff;
    font-size:.98rem;
    line-height:1.5;
  }

  .asidecard__note{
    margin-top:14px;
    padding-top:14px;
    border-top:1px solid rgba(255,255,255,.06);
    line-height:1.65;
    color:var(--home-muted);
  }

  .cardgrid{
    display:grid;
    grid-template-columns:repeat(4, minmax(0, 1fr));
    gap:20px;
  }

  .mcard{
    position:relative;
    display:block;
    min-height:340px;
    border-radius:28px;
    overflow:hidden;
    text-decoration:none;
    border:1px solid var(--home-stroke);
    background:linear-gradient(180deg, rgba(28,18,17,.96), rgba(12,8,9,.96));
    box-shadow:0 16px 40px rgba(0,0,0,.22);
    transition:transform .25s ease, box-shadow .25s ease, border-color .25s ease;
  }

  .mcard:hover{
    transform:translateY(-5px);
    box-shadow:
      0 24px 56px rgba(0,0,0,.30),
      0 0 24px rgba(197,162,74,.08);
    border-color:rgba(197,162,74,.20);
  }

  .mcard__img{
    position:absolute;
    inset:0;
    background-size:cover;
    background-position:center center;
    background-repeat:no-repeat;
    transform:scale(1.02);
    transition:transform .35s ease, filter .35s ease;
  }

  .mcard:hover .mcard__img{
    transform:scale(1.05);
    filter:brightness(1.03) saturate(1.04);
  }

  .mcard__shade{
    position:absolute;
    inset:0;
    background:
      linear-gradient(180deg, rgba(0,0,0,.05) 0%, rgba(0,0,0,.10) 30%, rgba(0,0,0,.68) 100%);
  }

  .mcard__body{
    position:absolute;
    left:0;
    right:0;
    bottom:0;
    z-index:2;
    padding:18px 18px 16px;
  }

  .mcard__title{
    margin-bottom:8px;
    font-size:1.35rem;
    line-height:1.14;
    font-weight:800;
    color:#fff;
    text-shadow:0 2px 10px rgba(0,0,0,.25);
  }

  .mcard__meta{
    color:rgba(255,255,255,.84);
    font-size:.95rem;
  }

  .quickgrid{
    display:grid;
    grid-template-columns:repeat(3, minmax(0, 1fr));
    gap:18px;
  }

  .quick{
    display:block;
    min-height:190px;
    padding:22px;
    border-radius:24px;
    text-decoration:none;
    border:1px solid var(--home-stroke);
    background:
      radial-gradient(circle at top left, rgba(197,162,74,.08), transparent 26%),
      linear-gradient(135deg, rgba(29,18,17,.96), rgba(12,8,9,.96));
    box-shadow:0 16px 40px rgba(0,0,0,.22);
    transition:transform .25s ease, box-shadow .25s ease, border-color .25s ease;
  }

  .quick:hover{
    transform:translateY(-4px);
    box-shadow:
      0 24px 54px rgba(0,0,0,.30),
      0 0 20px rgba(197,162,74,.07);
    border-color:rgba(197,162,74,.18);
  }

  .quick__icon{
    width:48px;
    height:48px;
    display:grid;
    place-items:center;
    border-radius:14px;
    margin-bottom:16px;
    background:rgba(197,162,74,.12);
    border:1px solid rgba(197,162,74,.16);
    font-size:1.25rem;
  }

  .quick__title{
    margin-bottom:8px;
    font-size:1.12rem;
    font-weight:800;
    color:var(--home-gold);
    line-height:1.15;
    text-shadow:0 0 12px rgba(197,162,74,.10);
  }

  .quick__text{
    color:rgba(255,255,255,.76);
    line-height:1.65;
    text-align:justify;
    text-justify:inter-word;
  }

  .home-empty-note{
    grid-column:1 / -1;
    padding:24px;
    border-radius:22px;
    border:1px solid rgba(255,255,255,.08);
    background:linear-gradient(135deg, rgba(29,18,17,.96), rgba(12,8,9,.96));
    color:rgba(255,255,255,.82);
  }

  @media (max-width: 1180px){
    .hero__inner{
      grid-template-columns:1fr;
      align-items:start;
    }

    .hero__content{
      width:100%;
    }

    .cardgrid{
      grid-template-columns:repeat(2, minmax(0, 1fr));
    }

    .quickgrid{
      grid-template-columns:repeat(2, minmax(0, 1fr));
    }
  }

  @media (max-width: 720px){
    .hero{
      min-height:auto;
      padding:26px 0 28px;
    }

    .hero__inner{
      min-height:auto;
      gap:18px;
    }

    .hero__content,
    .asidecard{
      padding:18px;
      border-radius:20px;
    }

    .hero__search{
      grid-template-columns:1fr;
    }

    .cardgrid,
    .quickgrid{
      grid-template-columns:1fr;
    }

    .mcard{
      min-height:290px;
    }

    .asidecard__row{
      grid-template-columns:1fr;
      gap:6px;
    }
  }
</style>

<div class="home-page">
  <section class="hero" aria-label="Uvod">
    <div class="hero__bg" aria-hidden="true"
         style="background-image:url('{{ asset('images/hero/hero1.jpg') }}');"></div>
    <div class="hero__overlay" aria-hidden="true"></div>

    <div class="container hero__inner">
      <div class="hero__content">
        <p class="eyebrow">Digitalni vodič kroz svetinje Srbije</p>
        <h1>Pravoslavni Svetionik</h1>

        <p class="hero__lead">
          Istraži manastire Srbije, pronađi svetinje na mapi, upoznaj njihovu istoriju i duhovni značaj, i na jednom mestu otkrij sadržaje koji povezuju veru, kulturu i nasleđe.
        </p>

        <div class="quote" aria-label="Citat dana">
          <div class="quote__title">Citat dana</div>
          <div class="quote__text">
            {{ $quote?->text ?? 'Još nema citata u bazi.' }}
          </div>
          <div class="quote__meta">
            — {{ $quote?->author ?? 'Pravoslavni Svetionik' }}
          </div>
        </div>

        <form class="hero__search" action="{{ route('monasteries.index') }}" method="GET" role="search">
          <label class="sr-only" for="hero-q">Pretraga manastira</label>
          <input id="hero-q" name="q" type="search" placeholder="Pretraga manastira, grada ili regiona..." />
          <button type="submit">Pretraži</button>
        </form>
      </div>

      <aside class="hero__aside" aria-label="Danas">
        <div class="asidecard">
          <div class="asidecard__head">
            <div class="asidecard__title">Danas u kalendaru</div>

            @if(!empty($day['red']))
              <div class="asidecard__badge" aria-label="Crveno slovo">Crveno slovo</div>
            @endif
          </div>

          <div class="asidecard__big">
            {{ $day['date'] ?? '—' }}
          </div>

          <div class="asidecard__row">
            <span>Praznik</span>
            <strong>{{ $day['feast'] ?? '—' }}</strong>
          </div>

          <div class="asidecard__row">
            <span>Post</span>
            <strong>{{ $day['fasting'] ?? '—' }}</strong>
          </div>

          <div class="asidecard__row">
            <span>Svetac</span>
            <strong>{{ $day['saint'] ?? '—' }}</strong>
          </div>

          @if(!empty($day['note']))
            <div class="asidecard__note">
              {{ $day['note'] }}
            </div>
          @endif
        </div>
      </aside>
    </div>
  </section>

  <section class="section" aria-label="Predlog dana">
    <div class="container">
      <div class="sectionhead">
        <h2 class="section-title">Predlog dana</h2>
        <span class="muted">Izaberi jednu svetinju za posetu ili istraživanje</span>
      </div>

      <div class="cardgrid">
        @forelse($cards as $c)
          <a class="mcard" href="{{ route('monasteries.show', $c['slug']) }}" aria-label="Otvori manastir: {{ $c['name'] }}">
            <div class="mcard__img" aria-hidden="true" style="background-image:url('{{ $c['img'] }}');"></div>
            <div class="mcard__shade" aria-hidden="true"></div>

            <div class="mcard__body">
              <div class="mcard__title">{{ $c['name'] }}</div>
              <div class="mcard__meta">{{ $c['meta'] }}</div>
            </div>
          </a>
        @empty
          <div class="home-empty-note">
            Trenutno nema izdvojenih manastira za prikaz.
          </div>
        @endforelse
      </div>
    </div>
  </section>

  <section class="section" aria-label="Brzi pristup">
    <div class="container">
      <div class="sectionhead">
        <h2 class="section-title">Brzi pristup sekcijama</h2>
        <span class="muted">Sve na jednom mestu</span>
      </div>

      <div class="quickgrid">
        <a class="quick" href="{{ route('map.index') }}">
          <div class="quick__icon" aria-hidden="true">🗺️</div>
          <div class="quick__title">Mapa svetinja</div>
          <div class="quick__text">Istraži manastire Srbije po lokaciji i upoznaj svetinje koje svedoče o duhovnom, kulturnom i istorijskom nasleđu našeg naroda.</div>
        </a>

        <a class="quick" href="{{ route('ktitors.index') }}">
          <div class="quick__icon" aria-hidden="true">👑</div>
          <div class="quick__title">Ktitori</div>
          <div class="quick__text">Upoznaj najveće srpske zadužbinare, sa posebnim osvrtom na Nemanjiće, čije su zadužbine obeležile zlatno doba srednjovekovne Srbije.</div>
        </a>

        <a class="quick" href="{{ route('edukacija.index') }}">
          <div class="quick__icon" aria-hidden="true">🎓</div>
          <div class="quick__title">Edukacija</div>
          <div class="quick__text">Kroz pažljivo odabrane sadržaje upoznaj period od početka do kraja vladavine Nemanjića i značaj koji je to imalo za srpsku duhovnost, državu i kulturu.</div>
        </a>
      </div>
    </div>
  </section>
</div>
@endsection