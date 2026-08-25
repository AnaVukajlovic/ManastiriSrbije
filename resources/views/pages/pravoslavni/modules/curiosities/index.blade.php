@extends('layouts.site')

@section('title', 'Zanimljivosti — Pravoslavni Svetionik')
@section('nav_curiosities', 'active')

@section('content')
<style>
  /* Uklanja prazan prostor iznad stranice */
  .ps-container {
    padding-top: 20px !important; 
  }

  /* Sređivanje sekcija (tvoj HTML) */
  .ps-asec {
    margin-bottom: 30px;
    max-width: 900px; /* Ograničava širinu da tekst ne bude razvučen preko celog ekrana */
    margin-left: auto;
    margin-right: auto;
  }

  /* Naslov da bude estetski, ali čitljiv */
  .ps-asec h2 {
    color: var(--gold, #c5a24a);
    font-size: clamp(1.5rem, 2.5vw, 2rem);
    line-height: 1.2;
    margin-bottom: 16px;
    font-weight: 800;
  }

  /* Razmak između pasusa kao kod manastira */
  .ps-ap {
    margin-bottom: 20px; /* Ovo pravi "vazduh" između pasusa */
    line-height: 1.8;
    color: rgba(255, 255, 255, 0.85);
    text-align: justify;
  }
  .curios-index {
    --ci-ink: rgba(255, 255, 255, .92);
    --ci-muted: rgba(255, 255, 255, .74);
    --ci-line: rgba(255, 255, 255, .08);
    --ci-gold-line: rgba(197, 162, 74, .22);
    --ci-shadow: 0 16px 38px rgba(0, 0, 0, .24);
  }
/* Naslov članka */
  .post-title {
    font-size: clamp(2rem, 5vw, 3rem); /* Smanjuje se na mobilnom, max 3rem na desktopu */
    line-height: 1.1;
    letter-spacing: -0.01em;
    margin-bottom: 16px;
    color: #fff;
    max-width: 900px; /* Ograniči širinu da ne ide od ivice do ivice */
  }

  /* Kratak opis (subtitle) */
  .post-subtitle {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.7);
    margin-bottom: 24px;
    max-width: 800px;
    line-height: 1.6;
  }

  /* Container za bolje disanje */
  .article-container {
    padding-top: 40px;
    padding-bottom: 40px;
  }
  .curios-index .container {
    width: min(1320px, calc(100% - 30px));
    max-width: none;
  }

  /* Glava sekcije */
  .ps-head { margin-bottom: 22px; display: flex; justify-content: space-between; align-items: flex-start; gap: 18px; flex-wrap: wrap; }
  .ps-title { margin: 0; color: var(--gold); font-size: clamp(2.2rem, 4vw, 3.6rem); line-height: 1.02; letter-spacing: -.02em; }
  .ps-sub { margin: 12px 0 0; color: var(--ci-ink); text-align: justify; max-width: 900px; line-height: 1.9; font-size: 1.02rem; }
  .ps-meta .muted { display: inline-flex; padding: 12px 18px; border-radius: 999px; border: 1px solid var(--ci-line); background: rgba(255, 255, 255, .03); white-space: nowrap; }

  /* Filteri i Kategorije */
  .filters { margin-bottom: 24px; padding: 16px; border-radius: 24px; border: 1px solid var(--ci-line); background: rgba(20, 12, 12, .90); box-shadow: var(--ci-shadow); }
  .filters__row { display: flex; flex-direction: column; gap: 16px; }
  .filters__top { display: flex; gap: 14px; align-items: center; }
  .filters__field { flex-grow: 1; }
  .filters__field input { width: 100%; height: 48px; border-radius: 14px; color: var(--ci-ink); font-size: 14px; border: 1px solid var(--ci-line); background: rgba(255, 255, 255, .04); padding: 0 14px; }
  .filters__actions { display: flex; gap: 10px; }
  /* 1. Smanjujemo razmak između kategorija */
  .cat-pills { 
    display: flex; 
    flex-wrap: wrap; 
    gap: 10px; /* Smanjeno sa 10px na 6px */
    margin-top: 10px;
  }

  /* 2. Smanjujemo padding i font da budu uže */
  .cat-pill { 
    appearance: none; 
    cursor: pointer; 
    display: inline-flex; 
    align-items: center; 
    min-height: 36px; /* Smanjeno sa 42px na 36px */
    padding: 0 12px;   /* Smanjeno sa 16px na 12px */
    border-radius: 999px; 
    background: rgba(255, 255, 255, .04); 
    border: 1px solid rgba(255, 255, 255, .08); 
    color: var(--ci-ink); 
    font-size: 0.9rem; /* Malo manji font da stane više teksta */
    font-weight: 500; 
    transition: all .2s ease; 
    text-decoration: none; 
  }

  /* 3. Hover efekat ostaje, ali su sada elegantnije */
  .cat-pill:hover, .cat-pill.is-active { 
    border-color: rgba(197, 162, 74, .32); 
    color: var(--gold); 
    background: rgba(197, 162, 74, .1); 
  }
  /* Grid Kartica */
  .cards-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; }
  .card { display: flex; flex-direction: column; min-height: 480px; overflow: hidden; border-radius: 22px; border: 1px solid var(--ci-line); background: rgba(20, 12, 12, .90); transition: transform .22s ease; }
  .card:hover { transform: translateY(-4px); border-color: rgba(197, 162, 74, .24); }
  .card__media { position: relative; height: 220px; overflow: hidden; }
  .card__img { position: absolute; inset: 0; background-size: cover; background-position: center; transition: transform .35s ease; }
  .card:hover .card__img { transform: scale(1.05); }
  
  .card__body { padding: 18px; display: flex; flex-direction: column; flex-grow: 1; }
  .card__title { margin: 0 0 10px; font-size: 1.15rem; line-height: 1.3; min-height: 3.2em; }
  .card__title a { color: var(--ci-ink); text-decoration: none; }
  .card__text { margin: 0 0 14px; color: var(--ci-ink); line-height: 1.6; font-size: .95rem; text-align: justify; flex-grow: 1; hyphens: auto; }
  .card__meta { display: flex; align-items: center; justify-content: space-between; margin-top: auto; }
  .link { color: var(--gold); font-weight: 700; text-decoration: none; }

  /* Responsiveness */
  @media (max-width: 1100px) { .cards-grid { grid-template-columns: repeat(2, 1fr); } }
  @media (max-width: 760px) { 
    .cards-grid { grid-template-columns: 1fr; }
    .filters__top { flex-direction: column; align-items: stretch; }
    .filters__actions { width: 100%; }
    .filters__actions .btn { flex: 1; }
  }
</style>

<section class="section curios-index">
  <div class="container">

    <div class="ps-head">
      <div>
        <h1 class="ps-title">Zanimljivosti</h1>
        <p class="ps-sub">
          Priče, simboli i objašnjenja iz pravoslavne tradicije.
        </p>
      </div>

      <div class="ps-meta">
        <span class="muted">Freske • Običaji • Post • Ikone • Manastirski život</span>
      </div>
    </div>

    {{-- FEATURED BANNER ZA LEGENDARIJUM --}}
    @if(empty($q) && empty($category))
      <div style="margin-bottom: 28px; position: relative; border-radius: 24px; border: 1.5px solid rgba(197,162,74,.32); background: radial-gradient(circle at top right, rgba(197,162,74,.15), transparent 50%), linear-gradient(180deg, rgba(32, 21, 18, 0.96), rgba(18, 11, 10, 0.96)); padding: 26px 30px; box-shadow: 0 16px 40px rgba(0,0,0,.35); display: flex; justify-content: space-between; align-items: center; gap: 24px; flex-wrap: wrap;">
        <div style="max-width: 820px;">
          <div style="display: inline-flex; align-items: center; gap: 8px; padding: 5px 14px; border-radius: 999px; background: rgba(197,162,74,.12); border: 1px solid rgba(197,162,74,.30); color: #e2c26a; font-size: .82rem; font-weight: 700; margin-bottom: 10px;">
            📜 Посебна тематска целина
          </div>
          <h2 style="margin: 0 0 8px 0; color: var(--gold, #c5a24a); font-size: clamp(1.2rem, 1.85vw, 1.55rem); line-height: 1.25; font-weight: 800;">
            Легендаријум: Приче и предања о Немањићима и Светом Сави
          </h2>
          <p style="margin: 0; color: rgba(255,255,255,.88); font-size: 1rem; line-height: 1.7; text-align: justify;">
            Завири у тајанствени, живи свет средњовековних предања, дирљивих сусрета и историјских анегдота — од бекства принца Растка на Свету Гору и Савиних вода до Милутиновог завета и Душановог царства.
          </p>
        </div>
        <div>
          <a href="{{ route('curiosities.show', 'legendarijum-price') }}" style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 22px; border-radius: 999px; background: linear-gradient(135deg, rgba(197,162,74,.25), rgba(226,194,106,.20)); border: 1.5px solid rgba(197,162,74,.45); color: #f4dc95; text-decoration: none; font-weight: 800; font-size: 0.96rem; transition: all .2s ease; box-shadow: 0 8px 20px rgba(0,0,0,.25); white-space: nowrap;">
            Отвори Легендаријум →
          </a>
        </div>
      </div>
    @endif

    <form class="filters" method="GET" action="{{ route('curiosities.index') }}">
    <div class="filters__row">
        <!-- Prvi red: Pretraga i Akcije -->
        <div style="display: flex; gap: 14px; width: 100%;">
            <div class="filters__field" style="flex-grow: 1;">
                <input name="q" type="search" value="{{ $q }}" placeholder="Pretraži..." />
            </div>
            <div class="filters__actions">
                <button class="btn" type="submit">Primeni</button>
                <a class="btn btn--ghost" href="{{ route('curiosities.index') }}">Reset</a>
            </div>
        </div>

        <!-- Drugi red: Kategorije (potpuno slobodne) -->
        <div class="cat-pills">
            <button type="submit" name="category" value="" class="cat-pill {{ $category === null ? 'is-active' : '' }}">
                Sve kategorije
            </button>
            @foreach($categories as $c)
                <button type="submit" name="category" value="{{ $c }}" class="cat-pill {{ $category === $c ? 'is-active' : '' }}">
                    {{ $c }}
                </button>
            @endforeach
        </div>
    </div>
</form>

    @if($items->count() === 0)
      <div class="empty">
        <h3>Nema rezultata</h3>
        <p class="muted">Pokušaj sa drugim pojmom ili obriši filtere.</p>
      </div>
    @else
      <div class="grid cards-grid">
        @foreach($items as $it)
          @php
            $img = $it->image_src;
          @endphp

          <article class="card">
            <a class="card__media" href="{{ route('curiosities.show', $it->slug) }}">
              <div class="card__img" style="background-image:url('{{ $img }}')"></div>
              <div class="card__shade"></div>

              @if(!empty($it->category))
                <span class="chip">{{ $it->category }}</span>
              @endif
            </a>

            <div class="card__body">
              <h3 class="card__title">
                <a href="{{ route('curiosities.show', $it->slug) }}">{{ $it->title }}</a>
              </h3>

              <p class="card__text">
                {{ $it->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($it->content ?? ''), 180) }}
              </p>

              <div class="card__meta">
                <span class="muted">
                  {{ $it->reading_minutes ? ((int)$it->reading_minutes . ' min čitanja') : 'Duhovni vodič' }}
                </span>

                <a class="link" href="{{ route('curiosities.show', $it->slug) }}">
                  Pročitaj više →
                </a>
              </div>
            </div>
          </article>
        @endforeach
      </div>

@if($items->hasPages())
  <div class="pagination-wrap">
    <div class="ps-pagination-wrap">
      <div class="ps-pagination">

        @if($items->onFirstPage())
          <span class="ps-page ps-disabled">← Prethodna</span>
        @else
          <a class="ps-page" href="{{ $items->previousPageUrl() }}">← Prethodna</a>
        @endif

        @foreach($items->getUrlRange(1, $items->lastPage()) as $page => $url)
          @if($page == $items->currentPage())
            <span class="ps-page is-active">{{ $page }}</span>
          @else
            <a class="ps-page" href="{{ $url }}">{{ $page }}</a>
          @endif
        @endforeach

        @if($items->hasMorePages())
          <a class="ps-page" href="{{ $items->nextPageUrl() }}">Sledeća →</a>
        @else
          <span class="ps-page ps-disabled">Sledeća →</span>
        @endif

      </div>
    </div>
  </div>
@endif
    @endif

    {{-- IZVORI I STRUCNA LITERATURA --}}
    @include('partials.sources-card', [
        'title' => 'Izvori i stručna literatura za zanimljivosti i predanja',
        'sources' => [
            'Свети Владика Николај Велимировић: <em>„Охридски пролог”</em>, <em>„Мисли о добру и злу”</em> и <em>„Вера Светих”</em>, Ваљево / Београд',
            'Др Радомир Поповић: <em>„Српска Црква у историји”</em> и <em>„Српски архијереји”</em>, Православни богословски факултет Универзитета у Београду',
            'Епископ др Данило (Крстић), Епископ др Амфилохије (Радовић): <em>„Нема лепше вере од хришћанске — Основи православног васпитања”</em>, Вршац / Београд',
            'Академик Владимир Ћоровић: <em>„Историја српског народа”</em>, Бања Лука – Београд, 2001.',
            'Званични едукативни материјали и зборници Одбора за верску наставу Архиепископије београдско-карловачке СПЦ'
        ],
        'note' => 'Све занимљивости, предања и поуке темеље се на веродостојним изворима Српске Православне Цркве и српске историјске науке.'
    ])

  </div>
</section>
@endsection