@extends('layouts.site')

@section('title', $q ? "Pretraga: {$q} — Pravoslavni Svetionik" : 'Pretraga — Pravoslavni Svetionik')

@section('content')
<section class="search-page-section">
  <div class="container">

    <style>
      .search-page-section {
        padding: 40px 0 70px;
      }

      .search-hero {
        text-align: center;
        max-width: 860px;
        margin: 0 auto 36px;
      }

      .search-hero__kicker {
        font-family: var(--sans, sans-serif);
        font-size: 13px;
        font-weight: 800;
        letter-spacing: .15em;
        text-transform: uppercase;
        color: var(--gold-soft, #dfc27d);
        margin-bottom: 10px;
      }

      .search-hero__title {
        font-family: var(--serif, 'Playfair Display', serif);
        font-size: clamp(2rem, 3.2vw, 3rem);
        color: var(--gold, #c5a24a);
        line-height: 1.1;
        margin: 0 0 16px;
        text-shadow: 0 0 16px rgba(197, 162, 74, .15);
      }

      .search-hero__desc {
        font-size: 1.05rem;
        color: rgba(255, 255, 255, .76);
        margin-bottom: 24px;
        line-height: 1.6;
      }

      .search-form-card {
        background: linear-gradient(180deg, rgba(30, 24, 20, 0.85), rgba(16, 12, 11, 0.95));
        border: 1px solid rgba(255, 255, 255, 0.10);
        border-radius: 24px;
        padding: 10px;
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.45);
        margin-bottom: 30px;
      }

      .search-form-inner {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 140px;
        gap: 10px;
        align-items: center;
      }

      .search-form-inner input {
        width: 100%;
        height: 54px;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        background: rgba(0, 0, 0, 0.35);
        color: #fff;
        padding: 0 20px;
        font-size: 1.05rem;
        outline: none;
        box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.3);
      }

      .search-form-inner input::placeholder {
        color: rgba(255, 255, 255, 0.45);
      }

      .search-form-inner input:focus {
        border-color: var(--gold, #c5a24a);
        background: rgba(0, 0, 0, 0.55);
      }

      .search-form-inner button {
        height: 54px;
        border: 0;
        border-radius: 16px;
        background: linear-gradient(135deg, #d4af37, #f3d882);
        color: #1a1209;
        font-size: 1rem;
        font-weight: 800;
        cursor: pointer;
        transition: transform .15s ease, box-shadow .15s ease;
        box-shadow: 0 8px 20px rgba(212, 175, 55, 0.25);
      }

      .search-form-inner button:hover {
        transform: translateY(-1px);
        box-shadow: 0 10px 24px rgba(212, 175, 55, 0.35);
      }

      /* Filter Tabs */
      .search-tabs {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
        margin-bottom: 36px;
      }

      .search-tab-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 18px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.04);
        border: 1px solid rgba(255, 255, 255, 0.08);
        color: rgba(255, 255, 255, 0.78);
        font-size: 0.92rem;
        font-weight: 600;
        cursor: pointer;
        text-decoration: none;
        transition: all .2s ease;
      }

      .search-tab-btn:hover {
        background: rgba(255, 255, 255, 0.09);
        color: #fff;
        border-color: rgba(197, 162, 74, 0.3);
      }

      .search-tab-btn.active {
        background: linear-gradient(135deg, rgba(197, 162, 74, 0.22), rgba(197, 162, 74, 0.10));
        border-color: var(--gold, #c5a24a);
        color: #fff;
        box-shadow: 0 4px 14px rgba(197, 162, 74, 0.18);
      }

      .search-tab-badge {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 22px;
        height: 22px;
        padding: 0 6px;
        border-radius: 999px;
        background: rgba(0, 0, 0, 0.35);
        color: var(--gold-soft, #dfc27d);
        font-size: 0.8rem;
        font-weight: 700;
      }

      .search-tab-btn.active .search-tab-badge {
        background: var(--gold, #c5a24a);
        color: #140d06;
      }

      /* Section headings */
      .search-group {
        margin-bottom: 46px;
      }

      .search-group-head {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
      }

      .search-group-title {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 0;
        font-family: var(--serif, 'Playfair Display', serif);
        font-size: 1.45rem;
        color: var(--gold-soft, #dfc27d);
      }

      .search-group-count {
        font-size: 0.88rem;
        color: rgba(255, 255, 255, 0.55);
        font-family: var(--sans, sans-serif);
      }

      /* Cards Grid */
      .search-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(310px, 1fr));
        gap: 20px;
      }

      /* Monastery Card */
      .search-mon-card {
        display: flex;
        flex-direction: column;
        background: linear-gradient(180deg, rgba(28, 22, 19, 0.95), rgba(16, 12, 10, 0.95));
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        overflow: hidden;
        text-decoration: none;
        color: inherit;
        transition: transform .2s ease, border-color .2s ease, box-shadow .2s ease;
      }

      .search-mon-card:hover {
        transform: translateY(-4px);
        border-color: rgba(197, 162, 74, 0.35);
        box-shadow: 0 14px 30px rgba(0, 0, 0, 0.45);
      }

      .search-mon-img {
        width: 100%;
        height: 170px;
        object-fit: cover;
        background-color: #1f1814;
      }

      .search-mon-body {
        padding: 18px;
        display: flex;
        flex-direction: column;
        flex: 1;
      }

      .search-card-meta {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 10px;
      }

      .search-pill {
        display: inline-block;
        font-size: 0.76rem;
        font-weight: 700;
        letter-spacing: .03em;
        text-transform: uppercase;
        padding: 4px 10px;
        border-radius: 999px;
        background: rgba(197, 162, 74, 0.12);
        color: var(--gold-soft, #dfc27d);
        border: 1px solid rgba(197, 162, 74, 0.25);
      }

      .search-pill--loc {
        background: rgba(255, 255, 255, 0.06);
        color: rgba(255, 255, 255, 0.85);
        border-color: rgba(255, 255, 255, 0.12);
      }

      .search-pill--red {
        background: rgba(220, 53, 69, 0.18);
        color: #ff7b89;
        border-color: rgba(220, 53, 69, 0.35);
      }

      .search-card-title {
        font-family: var(--serif, 'Playfair Display', serif);
        font-size: 1.3rem;
        color: #fff;
        margin: 0 0 8px;
        line-height: 1.25;
      }

      .search-card-text {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.68);
        line-height: 1.5;
        margin: 0 0 16px;
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
      }

      .search-card-footer {
        margin-top: auto;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.85rem;
        color: var(--gold-soft, #dfc27d);
        font-weight: 600;
        padding-top: 12px;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
      }

      /* Ktitor Card */
      .search-ktitor-card {
        display: flex;
        gap: 16px;
        background: linear-gradient(180deg, rgba(28, 22, 19, 0.95), rgba(16, 12, 10, 0.95));
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 18px;
        text-decoration: none;
        color: inherit;
        transition: transform .2s ease, border-color .2s ease;
      }

      .search-ktitor-card:hover {
        transform: translateY(-3px);
        border-color: rgba(197, 162, 74, 0.35);
      }

      .search-ktitor-img {
        width: 90px;
        height: 110px;
        border-radius: 14px;
        object-fit: cover;
        flex-shrink: 0;
        background: #251c16;
        border: 1px solid rgba(255, 255, 255, 0.1);
      }

      .search-ktitor-info {
        display: flex;
        flex-direction: column;
        min-width: 0;
      }

      .search-ktitor-years {
        font-size: 0.82rem;
        color: var(--gold-soft, #dfc27d);
        font-weight: 700;
        margin-bottom: 4px;
      }

      /* Topic / Edu Card */
      .search-topic-card {
        display: flex;
        flex-direction: column;
        background: linear-gradient(180deg, rgba(24, 20, 26, 0.95), rgba(14, 11, 16, 0.95));
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 22px;
        text-decoration: none;
        color: inherit;
        transition: transform .2s ease, border-color .2s ease;
      }

      .search-topic-card:hover {
        transform: translateY(-3px);
        border-color: rgba(197, 162, 74, 0.35);
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.35);
      }

      .search-topic-icon {
        font-size: 2rem;
        margin-bottom: 12px;
      }

      /* Calendar Card */
      .search-cal-card {
        display: flex;
        flex-direction: column;
        background: linear-gradient(180deg, rgba(26, 22, 20, 0.95), rgba(15, 12, 11, 0.95));
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 20px;
        padding: 20px;
        text-decoration: none;
        color: inherit;
        transition: transform .2s ease, border-color .2s ease;
      }

      .search-cal-card:hover {
        transform: translateY(-3px);
        border-color: rgba(197, 162, 74, 0.35);
      }

      .search-cal-date {
        font-family: var(--serif, 'Playfair Display', serif);
        font-size: 1.4rem;
        color: var(--gold, #c5a24a);
        margin-bottom: 8px;
      }

      .search-cal-feast {
        font-size: 1.05rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 6px;
      }

      .search-cal-saints {
        font-size: 0.88rem;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 12px;
        line-height: 1.4;
      }

      .search-cal-fast {
        font-size: 0.82rem;
        color: var(--gold-soft, #dfc27d);
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
      }

      /* Empty State */
      .search-empty {
        text-align: center;
        padding: 50px 20px;
        background: rgba(255, 255, 255, 0.02);
        border: 1px solid rgba(255, 255, 255, 0.06);
        border-radius: 28px;
        max-width: 680px;
        margin: 0 auto;
      }

      .search-empty__icon {
        font-size: 3.5rem;
        margin-bottom: 16px;
        display: block;
      }

      .search-empty__title {
        font-family: var(--serif, 'Playfair Display', serif);
        font-size: 1.8rem;
        color: #fff;
        margin: 0 0 12px;
      }

      .search-empty__text {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.65);
        margin-bottom: 24px;
        line-height: 1.6;
      }

      .search-suggestions {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        justify-content: center;
      }

      .search-sug-item {
        display: inline-block;
        padding: 6px 14px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: var(--gold-soft, #dfc27d);
        font-size: 0.88rem;
        text-decoration: none;
        transition: all .2s ease;
      }

      .search-sug-item:hover {
        background: rgba(197, 162, 74, 0.15);
        border-color: var(--gold, #c5a24a);
        color: #fff;
      }

      @media (max-width: 640px) {
        .search-form-inner {
          grid-template-columns: 1fr;
        }
        .search-grid {
          grid-template-columns: 1fr;
        }
        .search-ktitor-card {
          flex-direction: column;
        }
        .search-ktitor-img {
          width: 100%;
          height: 160px;
        }
      }
    </style>

    <!-- Hero Search Box -->
    <div class="search-hero">
      <div class="search-hero__kicker">Pretraga celog sajta</div>
      <h1 class="search-hero__title">Pravoslavni Svetionik — Pretraga</h1>
      <p class="search-hero__desc">
        Pretraži manastire, ktitore, gradove, eparhije, crkveni kalendar, praznike i edukativne sadržaje.
      </p>

      <div class="search-form-card">
        <form class="search-form-inner" action="{{ route('search') }}" method="GET" role="search">
          <input
            type="search"
            name="q"
            value="{{ $q }}"
            placeholder="Unesi pojam (npr. Vaskrs, Studenica, Stefan Nemanja, Valjevo, Žička eparhija, Post...)"
            autofocus
            autocomplete="off"
          >
          <button type="submit">Pretraži</button>
        </form>
      </div>

      @if($q !== '')
        <div style="font-size: 1.05rem; color: rgba(255,255,255,0.85); margin-top: 10px;">
          Pronađeno <strong>{{ $counts['all'] }}</strong> {{ $counts['all'] == 1 ? 'rezultat' : ($counts['all'] >= 2 && $counts['all'] <= 4 ? 'rezultata' : 'rezultata') }} za pojam: <span style="color: var(--gold); font-weight: 700;">„{{ $q }}”</span>
        </div>
      @endif
    </div>

    @if($q === '' || $counts['all'] === 0)
      <!-- Empty State & Suggestions -->
      <div class="search-empty">
        <span class="search-empty__icon">{{ $q === '' ? '🔍' : '🕊️' }}</span>
        <h2 class="search-empty__title">
          {{ $q === '' ? 'Šta želiš da pronađeš?' : 'Nema direktnih rezultata za uneti pojam' }}
        </h2>
        <p class="search-empty__text">
          {{ $q === ''
            ? 'Možeš pretraživati po nazivu manastira, imenu ktitora, gradu ili oblasti, eparhiji, nazivu praznika ili pojmu iz vere i istorije.'
            : 'Pokušaj sa nekim od predloženih pojmova ili proveri tačnost unosa:' }}
        </p>

        <div class="search-suggestions">
          <a class="search-sug-item" href="{{ route('search', ['q' => 'Studenica']) }}">Studenica</a>
          <a class="search-sug-item" href="{{ route('search', ['q' => 'Stefan Nemanja']) }}">Stefan Nemanja</a>
          <a class="search-sug-item" href="{{ route('search', ['q' => 'Vaskrs']) }}">Vaskrs</a>
          <a class="search-sug-item" href="{{ route('search', ['q' => 'Valjevo']) }}">Valjevo</a>
          <a class="search-sug-item" href="{{ route('search', ['q' => 'Kralj Milutin']) }}">Kralj Milutin</a>
          <a class="search-sug-item" href="{{ route('search', ['q' => 'Žička eparhija']) }}">Žička eparhija</a>
          <a class="search-sug-item" href="{{ route('search', ['q' => 'Božić']) }}">Božić</a>
          <a class="search-sug-item" href="{{ route('search', ['q' => 'Porodično stablo']) }}">Porodično stablo Nemanjića</a>
          <a class="search-sug-item" href="{{ route('search', ['q' => 'Post']) }}">Post i recepti</a>
          <a class="search-sug-item" href="{{ route('search', ['q' => 'Freskoslikarstvo']) }}">Freskoslikarstvo</a>
          <a class="search-sug-item" href="{{ route('search', ['q' => 'Sveti Sava']) }}">Sveti Sava</a>
          <a class="search-sug-item" href="{{ route('search', ['q' => 'Gračanica']) }}">Gračanica</a>
        </div>
      </div>
    @else
      <!-- Filter Tabs -->
      <div class="search-tabs" id="searchTabsNav">
        <button type="button" class="search-tab-btn active" data-target="all">
          Sve <span class="search-tab-badge">{{ $counts['all'] }}</span>
        </button>

        @if($counts['monasteries'] > 0)
          <button type="button" class="search-tab-btn" data-target="monasteries">
            Manastiri <span class="search-tab-badge">{{ $counts['monasteries'] }}</span>
          </button>
        @endif

        @if($counts['ktitors'] > 0)
          <button type="button" class="search-tab-btn" data-target="ktitors">
            Ktitori <span class="search-tab-badge">{{ $counts['ktitors'] }}</span>
          </button>
        @endif

        @if($counts['topics'] > 0)
          <button type="button" class="search-tab-btn" data-target="topics">
            Edukacija i teme <span class="search-tab-badge">{{ $counts['topics'] }}</span>
          </button>
        @endif

        @if($counts['calendar'] > 0)
          <button type="button" class="search-tab-btn" data-target="calendar">
            Kalendar i praznici <span class="search-tab-badge">{{ $counts['calendar'] }}</span>
          </button>
        @endif

        @if($counts['curiosities'] > 0)
          <button type="button" class="search-tab-btn" data-target="curiosities">
            Zanimljivosti <span class="search-tab-badge">{{ $counts['curiosities'] }}</span>
          </button>
        @endif
      </div>

      <!-- 1. Edukativne teme i posebne stranice -->
      @if($topics->count() > 0)
        <div class="search-group" data-tab-section="topics">
          <div class="search-group-head">
            <h2 class="search-group-title">
              <span>📖</span> Edukacija, teme i pojmovi
            </h2>
            <span class="search-group-count">{{ $topics->count() }} {{ $topics->count() == 1 ? 'tema' : 'tema' }}</span>
          </div>

          <div class="search-grid">
            @foreach($topics as $topic)
              <a class="search-topic-card" href="{{ $topic['url'] }}">
                <div class="search-topic-icon">{{ $topic['icon'] }}</div>
                <div class="search-card-meta">
                  <span class="search-pill">{{ $topic['category'] }}</span>
                </div>
                <h3 class="search-card-title">{{ $topic['title'] }}</h3>
                <p class="search-card-text">{{ $topic['description'] }}</p>
                <div class="search-card-footer">
                  <span>Pročitaj / Otvori</span>
                  <span>→</span>
                </div>
              </a>
            @endforeach
          </div>
        </div>
      @endif

      <!-- 2. Manastiri -->
      @if($monasteries->count() > 0)
        <div class="search-group" data-tab-section="monasteries">
          <div class="search-group-head">
            <h2 class="search-group-title">
              <span>⛪</span> Manastiri Srbije
            </h2>
            <span class="search-group-count">{{ $monasteries->count() }} {{ $monasteries->count() == 1 ? 'manastir' : 'manastira' }}</span>
          </div>

          <div class="search-grid">
            @foreach($monasteries as $m)
              @php
                $img = $m->image_src;
                $loc = $m->city ?: ($m->region ?: null);
                $eparchyName = $m->eparchy?->name;
              @endphp
              <a class="search-mon-card" href="{{ route('monasteries.show', $m->slug) }}">
                <img
                  class="search-mon-img"
                  src="{{ $img }}"
                  alt="{{ $m->name }}"
                  loading="lazy"
                  onerror="this.onerror=null;this.src='{{ asset('images/monasteries/placeholder.jpg') }}';"
                >
                <div class="search-mon-body">
                  <div class="search-card-meta">
                    @if($loc)
                      <span class="search-pill search-pill--loc">📍 {{ $loc }}</span>
                    @endif
                    @if($eparchyName)
                      <span class="search-pill">🏛️ {{ $eparchyName }}</span>
                    @endif
                    @if($m->godina_izgradnje)
                      <span class="search-pill">{{ $m->godina_izgradnje }}. god.</span>
                    @endif
                  </div>

                  <h3 class="search-card-title">{{ $m->name }}</h3>

                  @if($m->ktitor)
                    <div style="font-size: 0.85rem; color: var(--gold-soft); margin-bottom: 8px;">
                      <strong>Ktitor:</strong> {{ $m->ktitor }}
                    </div>
                  @endif

                  <p class="search-card-text">
                    {{ $m->excerpt ?: ($m->description_short ?: ($m->description ?: ($m->history ?: 'Upoznaj istoriju, arhitekturu i duhovni značaj ovog manastira.'))) }}
                  </p>

                  <div class="search-card-footer">
                    <span>Pogledaj manastir</span>
                    <span>→</span>
                  </div>
                </div>
              </a>
            @endforeach
          </div>
        </div>
      @endif

      <!-- 3. Ktitori -->
      @if($ktitors->count() > 0)
        <div class="search-group" data-tab-section="ktitors">
          <div class="search-group-head">
            <h2 class="search-group-title">
              <span>👑</span> Ktitori i zadužbinari
            </h2>
            <span class="search-group-count">{{ $ktitors->count() }} {{ $ktitors->count() == 1 ? 'ktitor' : 'ktitora' }}</span>
          </div>

          <div class="search-grid">
            @foreach($ktitors as $k)
              @php
                $kImg = $k->image_src;
                $years = ($k->born_year || $k->died_year)
                  ? (($k->born_year ?? '?') . ' – ' . ($k->died_year ?? '?'))
                  : null;
                $manastiriCount = $k->manastiri->count();
              @endphp
              <a class="search-ktitor-card" href="{{ route('ktitors.show', $k->slug) }}">
                <img
                  class="search-ktitor-img"
                  src="{{ $kImg }}"
                  alt="{{ $k->name }}"
                  loading="lazy"
                  onerror="this.onerror=null;this.src='{{ asset('images/sample/studenica.jpg') }}';"
                >
                <div class="search-ktitor-info">
                  <div class="search-card-meta">
                    <span class="search-pill">Ktitor</span>
                    @if($manastiriCount > 0)
                      <span class="search-pill search-pill--loc">{{ $manastiriCount }} {{ $manastiriCount == 1 ? 'manastir' : 'manastira' }}</span>
                    @endif
                  </div>

                  <h3 class="search-card-title" style="font-size: 1.15rem; margin-bottom: 4px;">{{ $k->name }}</h3>

                  @if($years)
                    <div class="search-ktitor-years">{{ $years }}</div>
                  @endif

                  <p class="search-card-text" style="font-size: 0.85rem; margin-bottom: 8px;">
                    {{ $k->bio ?: 'Istorijska ličnost i ktitor srpskih svetinja.' }}
                  </p>

                  <div class="search-card-footer" style="padding-top: 6px;">
                    <span>Otvori profil</span>
                    <span>→</span>
                  </div>
                </div>
              </a>
            @endforeach
          </div>
        </div>
      @endif

      <!-- 4. Kalendar i Praznici -->
      @if($calendarDays->count() > 0)
        <div class="search-group" data-tab-section="calendar">
          <div class="search-group-head">
            <h2 class="search-group-title">
              <span>📅</span> Kalendar i crkveni praznici
            </h2>
            <span class="search-group-count">{{ $calendarDays->count() }} {{ $calendarDays->count() == 1 ? 'praznik/dan' : 'praznika/dana' }}</span>
          </div>

          <div class="search-grid">
            @foreach($calendarDays as $day)
              @php
                $targetUrl = $day->date
                  ? route('pravoslavni.kalendar.show', ['date' => is_string($day->date) ? substr($day->date, 0, 10) : $day->date->format('Y-m-d')])
                  : route('pravoslavni.kalendar.index');
              @endphp
              <a class="search-cal-card" href="{{ $targetUrl }}">
                <div class="search-card-meta">
                  @if($day->is_red_letter)
                    <span class="search-pill search-pill--red">✝️ Crveno slovo</span>
                  @else
                    <span class="search-pill">Kalendar</span>
                  @endif
                </div>

                <div class="search-cal-date">{{ $day->formatted_date }}</div>

                @if($day->feast_name)
                  <div class="search-cal-feast">{{ $day->feast_name }}</div>
                @endif

                @if($day->saint_name)
                  <div class="search-cal-saints">{{ $day->saint_name }}</div>
                @endif

                @if($day->fasting_type)
                  <div class="search-cal-fast">
                    <span>🕊️ Post:</span> <strong>{{ $day->fasting_type }}</strong>
                  </div>
                @endif

                <div class="search-card-footer" style="margin-top: 14px;">
                  <span>Otvori u kalendaru</span>
                  <span>→</span>
                </div>
              </a>
            @endforeach
          </div>
        </div>
      @endif

      <!-- 5. Zanimljivosti -->
      @if($curiosities->count() > 0)
        <div class="search-group" data-tab-section="curiosities">
          <div class="search-group-head">
            <h2 class="search-group-title">
              <span>✨</span> Zanimljivosti i priče
            </h2>
            <span class="search-group-count">{{ $curiosities->count() }} {{ $curiosities->count() == 1 ? 'članak' : 'članka' }}</span>
          </div>

          <div class="search-grid">
            @foreach($curiosities as $cur)
              @php
                $cImg = $cur->image_src;
              @endphp
              <a class="search-mon-card" href="{{ route('curiosities.show', $cur->slug) }}">
                <img
                  class="search-mon-img"
                  src="{{ $cImg }}"
                  alt="{{ $cur->title }}"
                  loading="lazy"
                  onerror="this.onerror=null;this.src='{{ asset('images/curiosities/default.jpg') }}';"
                >
                <div class="search-mon-body">
                  <div class="search-card-meta">
                    @if($cur->category)
                      <span class="search-pill">{{ $cur->category }}</span>
                    @endif
                    <span class="search-pill search-pill--loc">⏱️ {{ $cur->reading_minutes }} min čitanja</span>
                  </div>

                  <h3 class="search-card-title">{{ $cur->title }}</h3>

                  <p class="search-card-text">
                    {{ $cur->excerpt ?: strip_tags($cur->content) }}
                  </p>

                  <div class="search-card-footer">
                    <span>Pročitaj članak</span>
                    <span>→</span>
                  </div>
                </div>
              </a>
            @endforeach
          </div>
        </div>
      @endif

    @endif

  </div>
</section>

<script>
  document.addEventListener('DOMContentLoaded', function () {
    const tabButtons = document.querySelectorAll('#searchTabsNav .search-tab-btn');
    const sections = document.querySelectorAll('[data-tab-section]');

    tabButtons.forEach(btn => {
      btn.addEventListener('click', function () {
        const target = this.getAttribute('data-target');

        tabButtons.forEach(b => b.classList.remove('active'));
        this.classList.add('active');

        if (target === 'all') {
          sections.forEach(sec => sec.style.display = 'block');
        } else {
          sections.forEach(sec => {
            if (sec.getAttribute('data-tab-section') === target) {
              sec.style.display = 'block';
            } else {
              sec.style.display = 'none';
            }
          });
        }
      });
    });
  });
</script>
@endsection
