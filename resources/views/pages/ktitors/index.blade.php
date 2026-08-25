@extends('layouts.site')

@section('title', 'Ktitori i Zadužbinari — Manastiri i crkve u Srbiji')
@section('nav_ktitors', 'active')

@section('content')
<section class="section kt-page">
  <div class="container kt-page-container">

    {{-- NASLOV I PODNASLOV --}}
    <div class="sectionhead kt-head">
      <div class="kt-head__text">
        <h2>Ktitori i Zadužbinari</h2>
        <p class="kt-subtitle">
          Српски владари, владарке, архијереји и племићи који су својим животом, дубоком вером и материјалним жртвовањем подигли највеће светиње српског народа. 
          Њихове задужбине — манастири, храмови, преписивачке радионице и болнице — вековима су били и остали темељи српског духовног, културног и државотворног идентитета.
        </p>
      </div>
    </div>

    {{-- EDUKATIVNI UVOD O POJMU KTITORA --}}
    <div style="margin-bottom: 28px; padding: 22px 26px; border-radius: 20px; background: linear-gradient(135deg, rgba(28, 18, 17, 0.95), rgba(16, 10, 10, 0.95)); border: 1.5px solid rgba(197, 162, 74, 0.3); box-shadow: 0 12px 30px rgba(0,0,0,0.35);">
      <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
        <span style="font-size: 1.3rem;">📜</span>
        <h3 style="margin: 0; font-size: 1.15rem; color: var(--gold, #c5a24a); font-weight: 700;">
          Шта значи бити ктитор у православном предању?
        </h3>
      </div>
      <p style="margin: 0; font-size: 0.94rem; line-height: 1.8; color: rgba(255, 255, 255, 0.88); text-align: justify; text-justify: inter-word;">
        Реч <strong>ктитор</strong> потиче од грчке речи <em>κτίτωр</em> што значи <strong>градитељ, утемељивач, оснивач</strong>. 
        У средњовековној Србији подизање задужбине — дела саграђеног <em>„за спасење душе”</em> — сматрало се најсветијом хришћанском дужношћу владара. 
        Светородна династија Немањића, на челу са Стефаном Немањом и Светим Савом, утемељила је јединствен ктиторски завет: сваки владар је имао обавезу да подигне своју велику задужбину, дарује јој поседе (метохе) и ктиторску повељу, осигуравајући да се у њој врши непрестана молитва за српски народ кроз сва поколења.
      </p>
    </div>

    {{-- FILTERI I PRETRAGA KTITORA --}}
    <div class="kt-filter-container">
      {{-- TABS FILTERI --}}
      <div class="kt-filter-tabs">
        <a href="{{ route('ktitors.index', array_merge(request()->except('category', 'page'), ['category' => 'all'])) }}" class="kt-ftab {{ ($category ?? 'all') === 'all' ? 'active' : '' }}">
          Сви ктитори
        </a>
        <a href="{{ route('ktitors.index', array_merge(request()->except('category', 'page'), ['category' => 'nemanjici'])) }}" class="kt-ftab {{ ($category ?? '') === 'nemanjici' ? 'active' : '' }}">
          👑 Немањићи
        </a>
        <a href="{{ route('ktitors.index', array_merge(request()->except('category', 'page'), ['category' => 'vladarke'])) }}" class="kt-ftab {{ ($category ?? '') === 'vladarke' ? 'active' : '' }}">
          👑 Владарке и ктиторке
        </a>
        <a href="{{ route('ktitors.index', array_merge(request()->except('category', 'page'), ['category' => 'lazarevici'])) }}" class="kt-ftab {{ ($category ?? '') === 'lazarevici' ? 'active' : '' }}">
          ⚔️ Лазаревићи и Хребељановићи
        </a>
      </div>

      {{-- PRETRAGA --}}
      <form class="kt-toolbar" method="GET" action="{{ route('ktitors.index') }}">
        @if(!empty($category) && $category !== 'all')
          <input type="hidden" name="category" value="{{ $category }}" />
        @endif

        <div class="kt-search">
          <input
            name="q"
            type="search"
            value="{{ $q ?? '' }}"
            placeholder="Претражи ктиторе по имену, титули или биографији..."
            aria-label="Pretraga ktitora"
          />
        </div>

        <div class="kt-actions">
          <button class="btn" type="submit">Претрага</button>
          <a class="btn btn--ghost" href="{{ route('ktitors.index', ['category' => $category ?? 'all']) }}">Ресет</a>
        </div>
      </form>
    </div>

    @if(($q ?? '') !== '')
      <div class="kt-hint" style="margin-bottom: 20px;">
        <span class="muted">Резултати за појам:</span>
        <b>{{ e($q) }}</b>
        <span class="muted">({{ $ktitors->total() }} пронађено)</span>
      </div>
    @endif

    {{-- JEDINSTVENI RASTER KARTICA KTITORA --}}
    @if($ktitors->count() === 0)
      <div class="card kt-empty-state">
        <b>Није пронађен ниједан ктитор</b>
        <div class="muted" style="margin-top:6px;">
          Покушај другачији назив, део имена или изабери другу категорију изнад.
        </div>
      </div>
    @else
      <div class="kt-grid-wrap">
        <div class="kt-grid">
          @foreach($ktitors as $k)
            @php
              $imgUrl = $k->image_src;

              $years = ($k->born_year || $k->died_year)
                ? (($k->born_year ?? '—') . ' – ' . ($k->died_year ?? '—'))
                : null;

              $bio = trim((string)($k->bio ?? ''));
              $shortDesc = 'Биографија ускоро…';

              if ($bio !== '' && preg_match('/Kratak opis:\s*(.+?)(?:\n[A-ZČĆŠĐŽa-zčćšđž ]+:\s*|\z)/us', $bio, $m)) {
                $shortDesc = trim($m[1]);
              } elseif ($bio !== '') {
                $shortDesc = \Illuminate\Support\Str::limit(strip_tags($bio), 160);
              }

              // Odredi dinastijsku oznaku
              $dynastyLabel = $k->dynasty ?: 'Српски ктитор';
              $isWoman = in_array($k->slug, ['simonida', 'kneginja-milica', 'jelena-anzujska', 'carica-jelena', 'ana-dandolo', 'ana-zena-stefana-nemanje']);
              $badgeText = $isWoman ? '👑 Владарка / Ктиторка' : ($k->dynasty ?: '👑 Владар');
            @endphp

            <article class="kt-card">
              {{-- SLIKA SA ZOOM PREGLEDOM --}}
              <div 
                class="kt-card__media"
                onclick="openKtZoomModal('{{ $imgUrl }}', '{{ addslashes($k->name) }}', '{{ addslashes($k->title ?? 'Српски ктитор') }}', '{{ addslashes($years ?? '') }}')"
                title="Кликните за зумирање портрета (lupa)"
              >
                <img
                  src="{{ $imgUrl }}"
                  alt="{{ e($k->name) }}"
                  loading="lazy"
                  onerror="this.onerror=null;this.src='{{ asset('images/sample/studenica.jpg') }}';"
                />
                <div class="kt-card__overlay"></div>
                <div class="kt-card__cat-badge">{{ $badgeText }}</div>
                <div class="kt-card__zoom-pill">🔍 Увећај</div>
              </div>

              {{-- TELO KARTICE --}}
              <div class="kt-card__body">
                <h3 class="kt-card__name">
                  <a href="{{ route('ktitors.show', $k->slug) }}">{{ $k->name }}</a>
                </h3>

                <div class="kt-card__subtitle">
                  @if(!empty($k->title))
                    <span class="kt-card__title-tag">{{ $k->title }}</span>
                  @endif
                  @if($years)
                    <span class="kt-card__years">{{ $years }}</span>
                  @endif
                </div>

                @if($k->is_saint && !empty($k->feast_day))
                  <div class="kt-card__feast-badge">
                    <span>🕊️</span> <b>Празник СПЦ:</b> {{ explode(' (', $k->feast_day)[0] }}
                  </div>
                @endif

                <div class="kt-card__bio">
                  {{ \Illuminate\Support\Str::limit($shortDesc, 140) }}
                </div>
              </div>

              {{-- DUGMAD U PODNOŽJU --}}
              <div class="kt-card__footer">
                <a class="btn btn--ghost kt-card__btn-det" href="{{ route('ktitors.show', $k->slug) }}">
                  Биографија →
                </a>
              </div>
            </article>
          @endforeach
        </div>
      </div>

      {{-- PAGINACIJA --}}
      @if ($ktitors->hasPages())
        <div class="kt-pager">
          <div class="kt-pager__summary">
            Приказано {{ $ktitors->firstItem() }}–{{ $ktitors->lastItem() }} од {{ $ktitors->total() }} ктитора
          </div>

          <div class="kt-pager__controls">
            {{-- Prethodna --}}
            @if ($ktitors->onFirstPage())
              <span class="kt-page-btn kt-page-btn--arrow is-disabled" aria-disabled="true">‹</span>
            @else
              <a class="kt-page-btn kt-page-btn--arrow" href="{{ $ktitors->previousPageUrl() }}" rel="prev">‹</a>
            @endif

            {{-- Brojevi strana --}}
            @for ($page = 1; $page <= $ktitors->lastPage(); $page++)
              @if ($page == $ktitors->currentPage())
                <span class="kt-page-btn is-active" aria-current="page">{{ $page }}</span>
              @else
                <a class="kt-page-btn" href="{{ $ktitors->url($page) }}">{{ $page }}</a>
              @endif
            @endfor

            {{-- Sledeća --}}
            @if ($ktitors->hasMorePages())
              <a class="kt-page-btn kt-page-btn--arrow" href="{{ $ktitors->nextPageUrl() }}" rel="next">›</a>
            @else
              <span class="kt-page-btn kt-page-btn--arrow is-disabled" aria-disabled="true">›</span>
            @endif
          </div>
        </div>
      @endif
    @endif

  </div>
</section>

{{-- MODAL LIGHTBOX ZA ZUMIRANJE PORTRETA KTITORA --}}
<div id="ktZoomModal" class="kt-zoom-modal" role="dialog" aria-modal="true" aria-hidden="true">
  <div class="kt-zoom-modal__backdrop" onclick="closeKtZoomModal()"></div>
  <div class="kt-zoom-modal__dialog">
    <div class="kt-zoom-modal__head">
      <div>
        <h3 id="ktZoomTitle" class="kt-zoom-modal__title">Портрет ктитора</h3>
        <div id="ktZoomSubtitle" class="kt-zoom-modal__subtitle">Титула и године</div>
      </div>
      <button type="button" class="kt-zoom-modal__close" onclick="closeKtZoomModal()" aria-label="Затвори">&times;</button>
    </div>
    <div class="kt-zoom-modal__stage">
      <img id="ktZoomImg" src="" alt="Портрет ктитора" draggable="false" />
    </div>
    <div class="kt-zoom-modal__foot">
      <span style="font-size:0.82rem; color:rgba(255,255,255,0.75);">🔍 Портрет и фрескопис из српског средњовековног манастира</span>
      <button type="button" class="btn btn--ghost" style="padding:4px 14px; font-size:0.8rem;" onclick="closeKtZoomModal()">Затвори</button>
    </div>
  </div>
</div>

{{-- AI MODAL ZA DIGITALNOG LETOPISCA --}}
<div class="kt-modal" id="aiModal" aria-hidden="true">
  <div class="kt-modal__backdrop" data-close></div>

  <div class="kt-modal__panel" role="dialog" aria-modal="true" aria-labelledby="aiTitle">
    <div class="kt-modal__head">
      <div>
        <div class="kt-modal__eyebrow">✨ Дигитални Летописац</div>
        <div id="aiTitle" class="kt-modal__title">—</div>
      </div>
      <button class="kt-x" type="button" data-close aria-label="Затвори">&times;</button>
    </div>

    <div class="kt-modal__body">
      <form id="aiForm" class="kt-aiform">
        <input
          id="aiQuestion"
          type="text"
          placeholder="Нпр. Ко је био овај ктитор, шта је саградио и по чему је значајан?"
          autocomplete="off"
        />
        <button id="aiBtn" class="btn" type="submit">Пошаљи</button>
      </form>

      <div id="aiStatus" class="kt-ai-status" style="display:none;"></div>

      <div id="aiAnswerWrap" class="kt-answer-wrap" style="display:none;">
        <div class="kt-answer-label">Одговор Летописца:</div>
        <div id="aiAnswer" class="kt-answer"></div>
      </div>
    </div>
  </div>
</div>

<style>
/* VARIJABLE I OPŠTI STILOVI */
:root {
  --kt-gold: #c5a24a;
  --kt-gold-2: #e2c26a;
  --kt-ink: rgba(255,255,255,.94);
  --kt-line: rgba(197,162,74,.22);
}

.kt-page { --kt-gold:#c5a24a; --kt-gold-2:#e2c26a; --kt-ink:rgba(255,255,255,.94); --kt-muted:rgba(255,255,255,.74); --kt-line:rgba(197,162,74,.22); }
.kt-page-container { width:min(1580px, calc(100% - 34px)); max-width:none !important; }

.kt-head { margin-bottom:18px; }
.kt-head__text h2 { margin:0 0 8px; font-size:clamp(1.75rem, 2.2vw, 2.25rem); font-weight:800; color:var(--kt-gold); }
.kt-head__text::after { content:""; display:block; width:68px; height:2px; margin-top:10px; border-radius:999px; background:linear-gradient(90deg, var(--kt-gold), rgba(197,162,74,0)); }

/* FILTER TABS */
.kt-filter-container {
  display: flex;
  flex-direction: column;
  gap: 16px;
  margin-bottom: 25px;
  background: rgba(20, 13, 12, 0.6);
  border: 1px solid rgba(197, 162, 74, 0.25);
  border-radius: 24px;
  padding: 18px 22px;
}

.kt-filter-tabs {
  display: flex;
  flex-wrap: wrap;
  gap: 8px;
}

.kt-ftab {
  padding: 8px 18px;
  border-radius: 999px;
  border: 1px solid rgba(197, 162, 74, 0.3);
  background: rgba(255, 255, 255, 0.03);
  color: rgba(255, 255, 255, 0.88);
  font-size: 0.86rem;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.2s ease;
}

.kt-ftab:hover {
  background: rgba(197, 162, 74, 0.15);
  border-color: #c5a24a;
  color: #fff;
}

.kt-ftab.active {
  background: linear-gradient(135deg, #e2c26a, #c5a24a);
  color: #1a100b;
  border-color: #e2c26a;
  font-weight: 700;
  box-shadow: 0 4px 14px rgba(197, 162, 74, 0.3);
}

.kt-toolbar {
  display: flex;
  gap: 12px;
  width: 100%;
}

.kt-search {
  flex: 1;
}

.kt-search input {
  width: 100%;
  height: 46px;
  padding: 0 16px;
  border-radius: 14px;
  border: 1px solid rgba(197, 162, 74, 0.3);
  background: rgba(12, 8, 8, 0.7);
  color: #fff;
  font-size: 0.95rem;
}

.kt-search input:focus {
  outline: none;
  border-color: #e2c26a;
  box-shadow: 0 0 0 3px rgba(197, 162, 74, 0.15);
}

.kt-actions {
  display: flex;
  gap: 8px;
}

.kt-actions .btn {
  height: 46px;
  padding: 0 20px;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  border-radius: 14px;
  font-weight: 700;
}

/* RASTER KARTICA */
.kt-grid { 
  display: grid !important; 
  grid-template-columns: repeat(auto-fill, minmax(285px, 1fr)) !important; 
  gap: 26px !important; 
  width: 100% !important; 
  align-items: stretch !important; 
}

.kt-card {
  position: relative; 
  width: 100% !important; 
  display: flex !important; 
  flex-direction: column !important;
  overflow: hidden; 
  border-radius: 24px; 
  border: 1.5px solid var(--kt-line);
  background: radial-gradient(circle at top left, rgba(197,162,74,.08), transparent 28%), linear-gradient(180deg, rgba(28,18,17,.96), rgba(16,10,10,.98));
  min-height: 500px; 
  box-shadow: 0 12px 32px rgba(0,0,0,.45);
  transition: transform .28s ease, box-shadow .28s ease, border-color .28s ease;
}

.kt-card:hover { 
  transform: translateY(-6px); 
  border-color: rgba(197,162,74,.55); 
  box-shadow: 0 22px 55px rgba(197,162,74,.2); 
}

.kt-card__media { 
  position: relative; 
  display: block; 
  width: 100%;
  aspect-ratio: 16 / 11; 
  overflow: hidden; 
  background: #0c0808; 
  border-bottom: 1px solid rgba(197,162,74,.22);
  cursor: zoom-in;
}

.kt-card__media img { 
  width: 100%; 
  height: 100%; 
  object-fit: cover; 
  object-position: center top; 
  transition: transform .35s ease; 
}

.kt-card:hover .kt-card__media img { 
  transform: scale(1.06); 
}

.kt-card__overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.05) 50%, rgba(0,0,0,0.75) 100%);
  pointer-events: none;
}

.kt-card__cat-badge {
  position: absolute;
  bottom: 10px;
  left: 10px;
  background: rgba(16, 10, 9, 0.9);
  backdrop-filter: blur(4px);
  border: 1px solid rgba(197, 162, 74, 0.35);
  color: #e2c26a;
  font-size: 0.74rem;
  font-weight: 700;
  padding: 4px 10px;
  border-radius: 999px;
}

.kt-card__zoom-pill {
  position: absolute;
  top: 10px;
  right: 10px;
  background: rgba(12, 8, 8, 0.88);
  border: 1px solid rgba(197, 162, 74, 0.35);
  color: #e2c26a;
  font-size: 0.72rem;
  font-weight: 700;
  padding: 3px 8px;
  border-radius: 999px;
  backdrop-filter: blur(4px);
  pointer-events: none;
}

/* TELO KARTICE */
.kt-card__body { 
  display: flex; 
  flex-direction: column; 
  padding: 18px 18px 12px; 
  flex: 1 0 auto; 
}

.kt-card__name { 
  font-size: 1.18rem; 
  font-weight: 800; 
  min-height: 44px; 
  display: -webkit-box; 
  -webkit-line-clamp: 2; 
  -webkit-box-orient: vertical; 
  overflow: hidden; 
  margin: 0 0 6px 0; 
  line-height: 1.3; 
}

.kt-card__name a { 
  color: #fff; 
  text-decoration: none; 
  transition: color .2s ease; 
}

.kt-card__name a:hover { 
  color: #e2c26a; 
}

.kt-card__subtitle {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 8px;
  margin-bottom: 10px;
  flex-wrap: wrap;
}

.kt-card__title-tag {
  font-size: 0.8rem;
  font-weight: 600;
  color: rgba(255, 255, 255, 0.7);
}

.kt-card__years { 
  font-size: 0.82rem; 
  font-weight: 700; 
  color: #c5a24a; 
}

.kt-card__feast-badge {
  display: flex;
  align-items: center;
  gap: 6px;
  font-size: 0.77rem;
  color: #f7eedb;
  background: rgba(197, 162, 74, 0.12);
  border: 1px solid rgba(197, 162, 74, 0.3);
  border-radius: 8px;
  padding: 4px 8px;
  margin-bottom: 10px;
  line-height: 1.35;
}

.kt-card__feast-badge b {
  color: #e2c26a;
}

.kt-card__bio { 
  font-size: 0.88rem; 
  line-height: 1.65; 
  color: rgba(255,255,255,.82); 
  text-align: justify; 
  text-justify: inter-word; 
  margin-bottom: 12px; 
}

.kt-card__monasteries {
  margin-top: auto;
  padding: 8px 10px;
  border-radius: 10px;
  background: rgba(197, 162, 74, 0.05);
  border: 1px solid rgba(197, 162, 74, 0.18);
  font-size: 0.78rem;
  line-height: 1.4;
  margin-bottom: 6px;
}

.kt-card__mon-label {
  color: #e2c26a;
  font-weight: 700;
}

.kt-card__mon-names {
  color: rgba(255, 255, 255, 0.85);
}

/* PODNOŽJE KARTICE */
.kt-card__footer { 
  margin-top: auto; 
  padding: 12px 14px; 
  display: flex; 
  gap: 8px; 
  justify-content: center; 
  border-top: 1px solid rgba(255,255,255,.06); 
}

.kt-card__btn-det {
  flex: 1.2;
  padding: 9px 10px;
  font-size: 0.84rem;
  font-weight: 700;
  border-radius: 12px;
  text-align: center;
  text-decoration: none;
}

.kt-card__btn-ai {
  flex: 1;
  padding: 9px 8px;
  font-size: 0.82rem;
  font-weight: 700;
  border-radius: 12px;
  background: rgba(197, 162, 74, 0.12);
  border: 1px solid rgba(197, 162, 74, 0.3);
  color: #e2c26a;
  cursor: pointer;
  transition: all 0.2s ease;
}

.kt-card__btn-ai:hover {
  background: linear-gradient(135deg, #e2c26a, #c5a24a);
  color: #1a100b;
}

/* PAGINACIJA */
.kt-pager { margin-top:34px; display:flex; flex-direction:column; align-items:center; gap:16px; text-align:center; }
.kt-pager__controls { display: flex; gap: 6px; }
.kt-page-btn { min-width:44px; height:42px; display:inline-flex; align-items:center; justify-content:center; border-radius:14px; border:1px solid rgba(197,162,74,.45); background:linear-gradient(180deg, rgba(255,255,255,.05), rgba(255,255,255,.02)); color:var(--kt-ink); font-weight:700; text-decoration: none; transition:all .18s ease; }
.kt-page-btn:hover { border-color:rgba(226,194,106,.75); background:linear-gradient(180deg, rgba(197,162,74,.18), rgba(197,162,74,.08)); color:#fff; }
.kt-page-btn.is-active { border-color:#e2c26a; background:linear-gradient(180deg, #e2c26a, #c5a24a); color:#2a1a08; }
.kt-page-btn.is-disabled { opacity: 0.4; pointer-events: none; }

/* LIGHTBOX MODAL ZA ZOOM SLIKA */
.kt-zoom-modal {
  position: fixed;
  inset: 0;
  z-index: 9999999;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
  opacity: 0;
  pointer-events: none;
  transition: opacity 0.3s ease;
}

.kt-zoom-modal.active {
  opacity: 1;
  pointer-events: auto;
}

.kt-zoom-modal__backdrop {
  position: absolute;
  inset: 0;
  background: rgba(6, 4, 4, 0.95);
  backdrop-filter: blur(12px);
}

.kt-zoom-modal__dialog {
  position: relative;
  z-index: 2;
  width: min(780px, 94vw);
  max-height: 90vh;
  background: linear-gradient(180deg, #201412, #120b0a);
  border: 1.5px solid rgba(197, 162, 74, 0.45);
  border-radius: 24px;
  box-shadow: 0 24px 60px rgba(0, 0, 0, 0.8);
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.kt-zoom-modal__head {
  padding: 16px 20px;
  background: rgba(14, 8, 8, 0.9);
  border-bottom: 1px solid rgba(197, 162, 74, 0.25);
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 16px;
}

.kt-zoom-modal__title {
  margin: 0;
  font-size: 1.25rem;
  color: #e2c26a;
  font-weight: 800;
}

.kt-zoom-modal__subtitle {
  font-size: 0.84rem;
  color: rgba(255, 255, 255, 0.75);
  margin-top: 2px;
}

.kt-zoom-modal__close {
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.15);
  color: #fff;
  font-size: 24px;
  width: 36px;
  height: 36px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  cursor: pointer;
  line-height: 1;
}

.kt-zoom-modal__close:hover {
  background: #c5a24a;
  color: #1a100b;
}

.kt-zoom-modal__stage {
  padding: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: #080404;
  overflow: auto;
  max-height: 60vh;
}

.kt-zoom-modal__stage img {
  max-width: 100%;
  max-height: 56vh;
  object-fit: contain;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0,0,0,0.6);
}

.kt-zoom-modal__foot {
  padding: 14px 20px;
  background: rgba(14, 8, 8, 0.9);
  border-top: 1px solid rgba(197, 162, 74, 0.2);
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 10px;
}

/* AI MODAL */
.kt-modal { position:fixed; inset:0; display:none; z-index:1200; }
.kt-modal.is-open { display:block; }
.kt-modal__backdrop { position:absolute; inset:0; background:rgba(0,0,0,.62); backdrop-filter:blur(4px); }
.kt-modal__panel { position:relative; width:min(760px, calc(100% - 28px)); margin:6vh auto 0; border-radius:24px; border:1px solid rgba(197,162,74,.20); background:rgba(18,12,13,.98); padding:20px; }

@media (max-width: 768px){
  .kt-card { width:100% !important; min-height:unset; }
  .kt-toolbar { flex-direction: column; }
  .kt-actions { width: 100%; }
  .kt-actions .btn { flex: 1; }
}
</style>

<script>
(function () {
  const modal = document.getElementById('aiModal');
  const qEl = document.getElementById('aiQuestion');
  const btn = document.getElementById('aiBtn');
  const status = document.getElementById('aiStatus');
  const wrap = document.getElementById('aiAnswerWrap');
  const answerEl = document.getElementById('aiAnswer');

  // Funkcija za otvaranje modala
  document.querySelectorAll('[data-ai]').forEach((button) => {
    button.addEventListener('click', () => {
      document.getElementById('aiTitle').textContent = button.dataset.name;
      
      // Priprema konteksta za kontroler
      window.aiContext = `Ktitor: ${button.dataset.name}\nBiografija: ${button.dataset.bio}`;
      
      modal.classList.add('is-open');
      qEl.value = '';
      wrap.style.display = 'none'; // Sakrij stari odgovor
    });
  });

  // Zatvaranje modala na data-close ili klik van panela
  document.querySelectorAll('[data-close]').forEach((el) => {
    el.addEventListener('click', () => {
      modal.classList.remove('is-open');
    });
  });

  // Funkcija za slanje na AI
  document.getElementById('aiForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const question = qEl.value.trim();
    if (!question) return;

    btn.disabled = true;
    status.style.display = 'block';
    status.textContent = 'Припремам одговор Летописца...';

    try {
      const res = await fetch('/api/ai/chat', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ 
          question: question,
          context: window.aiContext 
        })
      });

      const data = await res.json();
      
      if (!res.ok) throw new Error(data.error || 'Дошло је до грешке при комуникацији са АИ Летописцем.');

      status.textContent = 'Одговор:';
      answerEl.textContent = data.answer || '—';
      wrap.style.display = 'block';
    } catch (err) {
      status.textContent = 'Грешка: ' + err.message;
      console.error(err);
    } finally {
      btn.disabled = false;
    }
  });
})();

function openKtZoomModal(imgUrl, name, title, years) {
  const modal = document.getElementById('ktZoomModal');
  document.getElementById('ktZoomImg').src = imgUrl;
  document.getElementById('ktZoomTitle').textContent = name;
  document.getElementById('ktZoomSubtitle').textContent = title + (years ? ' (' + years + ')' : '');
  modal.classList.add('active');
  document.body.style.overflow = 'hidden';
}

function closeKtZoomModal() {
  const modal = document.getElementById('ktZoomModal');
  modal.classList.remove('active');
  document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
  if (e.key === 'Escape') {
    closeKtZoomModal();
    const aiM = document.getElementById('aiModal');
    if (aiM) aiM.classList.remove('is-open');
  }
});
</script>
@endsection