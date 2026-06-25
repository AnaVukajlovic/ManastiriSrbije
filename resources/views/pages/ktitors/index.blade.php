@extends('layouts.site')

@section('title','Ktitori — Pravoslavni Svetionik')
@section('nav_ktitors','active')

@section('content')
<section class="section kt-page">
  <div class="container kt-page-container">

    <div class="sectionhead kt-head">
      <div class="kt-head__text">
        <h2>Ktitori</h2>
<p class="kt-subtitle">
  Vladari, vladarke i dobrotvori srpskih svetinja koji su svojim životom, verom i
  zadužbinama ostavili dubok trag u srpskoj duhovnoj i kulturnoj istoriji.
  Posebno mesto među njima zauzimaju pripadnici dinastije Nemanjić, najveći
  zadužbinari u istoriji srednjovekovne Srbije. Njihove zadužbine — manastiri,
  crkve i duhovni centri — postale su stubovi pravoslavne vere, kulture i
  državnosti, a mnoge od tih svetinja i danas svedoče o vremenu u kome su
  nastale i o duhovnom nasleđu koje su ostavili srpskom narodu.
</p>
      </div>
    </div>

{{-- Toolbar --}}
<form class="kt-toolbar" method="GET" action="{{ route('ktitors.index') }}">
  <div class="kt-search">
    <input
      name="q"
      type="search"
      value="{{ $q ?? '' }}"
      placeholder="Pretraži ktitore po imenu ili biografiji..."
      aria-label="Pretraga ktitora"
    />
  </div>

  <div class="kt-actions">
    <button class="btn" type="submit">Pretraga</button>
    <a class="btn btn--ghost" href="{{ route('ktitors.index') }}">Reset</a>
  </div>
</form>

<div class="kt-hint">
  <span class="muted">Pretraga:</span>
  <b>{{ ($q ?? '') !== '' ? e($q) : '—' }}</b>
</div>

{{-- Grid --}}
@if($ktitors->count() === 0)
  <div class="card kt-empty-state">
    <b>Nema rezultata</b>
    <div class="muted" style="margin-top:6px;">
      Pokušaj drugačiji naziv, deo imena ili pojam iz biografije.
    </div>
  </div>
@else
  <div class="kt-grid-wrap">
    <div class="kt-grid">
      @foreach($ktitors as $k)
        @php
          $imgPath = optional($k->mainImage)->path
            ?? optional($k->images->sortBy('sort')->first())->path
            ?? null;

          $imgUrl = $imgPath ? asset($imgPath) : asset('images/placeholders/ktitor.png');

          $years = ($k->born_year || $k->died_year)
            ? (($k->born_year ?? '—') . ' – ' . ($k->died_year ?? '—'))
            : null;

          $bio = trim((string)($k->bio ?? ''));
          $shortDesc = 'Biografija uskoro…';

          if ($bio !== '' && preg_match('/Kratak opis:\s*(.+?)(?:\n[A-ZČĆŠĐŽa-zčćšđž ]+:\s*|\z)/us', $bio, $m)) {
            $shortDesc = trim($m[1]);
          } elseif ($bio !== '') {
            $shortDesc = \Illuminate\Support\Str::limit(strip_tags($bio), 170);
          }
        @endphp

        <article class="kt-card">
          <a
            class="kt-card__media"
            href="{{ route('ktitors.show', $k->slug) }}"
            aria-label="Detalji: {{ e($k->name) }}"
          >
            <img
              src="{{ $imgUrl }}"
              alt="{{ e($k->name) }}"
              loading="lazy"
              onerror="this.onerror=null;this.src='{{ asset('images/placeholders/ktitor.png') }}';"
            />
            <div class="kt-card__overlay"></div>
          </a>

          <div class="kt-card__body">
            <h3 class="kt-card__name">
              <a href="{{ route('ktitors.show', $k->slug) }}">{{ $k->name }}</a>
            </h3>

            @if($years)
              <div class="kt-card__years">{{ $years }}</div>
            @endif

            <div class="kt-card__bio">
              {{ \Illuminate\Support\Str::limit($shortDesc, 160) }}
            </div>
          </div>

          <div class="kt-card__footer">
             <a class="btn btn--ghost" href="{{ route('ktitors.show', $k->slug) }}">Detalji</a>
    
              
              </div> {{-- OVO ZATVARA FOOTER --}}
            </article> {{-- OVO ZATVARA KT-CARD --}}
      @endforeach
    </div>
  </div>

@if ($ktitors->hasPages())
  <div class="kt-pager">
    <div class="kt-pager__summary">
      Prikazano {{ $ktitors->firstItem() }}–{{ $ktitors->lastItem() }} od {{ $ktitors->total() }} ktitora
    </div>

    <div class="kt-pager__list">
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

{{-- AI Modal --}}
<div class="kt-modal" id="aiModal" aria-hidden="true">
  <div class="kt-modal__backdrop" data-close></div>

  <div class="kt-modal__panel" role="dialog" aria-modal="true" aria-labelledby="aiTitle">
    <div class="kt-modal__head">
      <div>
        <div class="kt-modal__eyebrow">Pitaj AI</div>
        <div id="aiTitle" class="kt-modal__title">—</div>
      </div>
      <button class="kt-x" type="button" data-close aria-label="Zatvori">×</button>
    </div>

    <div class="kt-modal__body">
      <form id="aiForm" class="kt-aiform">
        <input
          id="aiQuestion"
          type="text"
          placeholder="Npr. Ko je bio ovaj ktitor i po čemu je značajan?"
          autocomplete="off"
        />
        <button id="aiBtn" class="btn" type="submit">Pošalji</button>
      </form>

      <div id="aiStatus" class="kt-ai-status" style="display:none;"></div>

      <div id="aiAnswerWrap" class="kt-answer-wrap" style="display:none;">
        <div class="kt-answer-label">Odgovor:</div>
        <div id="aiAnswer" class="kt-answer"></div>
      </div>
    </div>
  </div>
</div>

<style>
/* 1. VARIJABLE I OPŠTI STILOVI */
:root {
  --kt-gold: #c5a24a;
  --kt-gold-2: #e2c26a;
  --kt-ink: rgba(255,255,255,.94);
  --kt-line: rgba(197,162,74,.16);
}

.kt-page { --kt-gold:#c5a24a; --kt-gold-2:#e2c26a; --kt-ink:rgba(255,255,255,.94); --kt-muted:rgba(255,255,255,.74); --kt-line:rgba(197,162,74,.16); }
.kt-page-container { width:min(1580px, calc(100% - 34px)); max-width:none !important; }

.kt-head { margin-bottom:18px; }
.kt-head__text h2 { margin:0 0 8px; font-size:clamp(1.75rem, 2.2vw, 2.25rem); font-weight:800; color:var(--kt-gold); }
.kt-head__text::after { content:""; display:block; width:68px; height:2px; margin-top:10px; border-radius:999px; background:linear-gradient(90deg, var(--kt-gold), rgba(197,162,74,0)); }

/* 2. GRID I KARTICE */
.kt-grid { display:flex !important; flex-wrap:wrap !important; justify-content:center !important; gap:26px !important; align-items:stretch !important; }

.kt-card {
    position:relative; width:285px !important; display:flex !important; flex-direction:column !important;
    overflow:hidden; border-radius:24px; border:1px solid var(--kt-line);
    background:radial-gradient(circle at top left, rgba(197,162,74,.06), transparent 24%), linear-gradient(180deg, rgba(255,255,255,.045), rgba(255,255,255,.018)), rgba(18,12,13,.92);
    min-height:480px; transition:transform .28s ease, box-shadow .28s ease;
}

.kt-card__media { position:relative; display:block; height:220px; overflow:hidden; background:rgba(0,0,0,.2); }
.kt-card__media img { width:100%; height:100%; object-fit:cover; transition:transform .3s ease; }
.kt-card:hover { transform:translateY(-7px); border-color:rgba(197,162,74,.34); box-shadow:0 26px 62px rgba(0,0,0,.42); }
.kt-card:hover .kt-card__media img { transform:scale(1.06); }

.kt-card__body { display:flex; flex-direction:column; padding:18px 18px 10px; flex:1 0 auto; }
.kt-card__name { font-size:20px; font-weight:900; color:#fff; height:48px; display:-webkit-box; -webkit-line-clamp:2; -webkit-box-orient:vertical; overflow:hidden; margin-bottom:4px; }
.kt-card__name a { color:#fff; text-decoration:none; }
.kt-card__footer { margin-top:auto; padding:10px 8px; display:flex; gap:4px; justify-content:center; border-top:1px solid rgba(255,255,255,.06); }
.kt-card__footer .btn { flex:1; padding:8px 4px; font-size:11px; }

/* 3. PAGINACIJA */
.kt-pager { margin-top:34px; display:flex; flex-direction:column; align-items:center; gap:16px; text-align:center; }
.kt-page-btn { min-width:46px; height:44px; display:inline-flex; align-items:center; justify-content:center; border-radius:15px; border:1px solid rgba(197,162,74,.45); background:linear-gradient(180deg, rgba(255,255,255,.05), rgba(255,255,255,.02)); color:var(--kt-ink); font-weight:700; transition:all .18s ease; }
.kt-page-btn:hover { border-color:rgba(226,194,106,.75); background:linear-gradient(180deg, rgba(197,162,74,.18), rgba(197,162,74,.08)); color:#fff; }
.kt-page-btn.is-active { border-color:#e2c26a; background:linear-gradient(180deg, #e2c26a, #c5a24a); color:#2a1a08; }

/* 4. MODAL I AI FORMA */
.kt-modal { position:fixed; inset:0; display:none; z-index:1200; }
.kt-modal.is-open { display:block; }
.kt-modal__backdrop { position:absolute; inset:0; background:rgba(0,0,0,.62); backdrop-filter:blur(4px); }
.kt-modal__panel { position:relative; width:min(760px, calc(100% - 28px)); margin:6vh auto 0; border-radius:24px; border:1px solid rgba(197,162,74,.20); background:rgba(18,12,13,.98); padding:20px; }

/* 5. RESPONSIVE */
@media (max-width: 768px){
  .kt-card { width:100% !important; min-height:unset; }
  .kt-pager .kt-page-btn { min-width:40px; height:40px; font-size:16px; }
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

  // Funkcija za slanje na AI
  document.getElementById('aiForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    const question = qEl.value.trim();
    if (!question) return;

    btn.disabled = true;
    status.style.display = 'block';
    status.textContent = 'Generišem odgovor...';

    try {
      // Koristimo apsolutnu putanju ka /api/ai/chat
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
      
      if (!res.ok) throw new Error(data.error || 'Došlo je do greške pri komunikaciji sa AI.');

      status.textContent = 'Gotovo.';
      answerEl.textContent = data.answer || '—';
      wrap.style.display = 'block';
    } catch (err) {
      status.textContent = 'Greška: ' + err.message;
      console.error(err);
    } finally {
      btn.disabled = false;
    }
  });
})();
</script>
@endsection