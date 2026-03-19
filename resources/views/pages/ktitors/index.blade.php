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

            <button
              class="btn"
              type="button"
              data-ai
              data-name="{{ e($k->name) }}"
              data-born="{{ $k->born_year ?? '' }}"
              data-died="{{ $k->died_year ?? '' }}"
              data-bio="{{ e(\Illuminate\Support\Str::limit(strip_tags($k->bio ?? ''), 2200)) }}"
            >
              Pitaj AI
            </button>
          </div>
        </article>
      @endforeach
    </div>
  </div>

  <div class="kt-pager">
    {{ $ktitors->links('vendor.pagination.ktitors') }}
  </div>
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
.kt-page{
  --kt-gold:#c5a24a;
  --kt-gold-2:#e2c26a;
  --kt-ink:rgba(255,255,255,.94);
  --kt-muted:rgba(255,255,255,.74);
  --kt-line:rgba(197,162,74,.16);
  --kt-line-strong:rgba(197,162,74,.30);
  --kt-soft:rgba(255,255,255,.04);
  --kt-soft-2:rgba(255,255,255,.025);
  --kt-shadow:0 20px 50px rgba(0,0,0,.34);
  --kt-shadow-hover:0 28px 64px rgba(0,0,0,.42);
}

.kt-page-container{
  width:min(1580px, calc(100% - 34px));
  max-width:none !important;
}

.kt-head{
  margin-bottom:18px;
}
.kt-head__text{
  position:relative;
}
.kt-head__text h2{
  margin:0 0 8px;
  font-size:clamp(1.75rem, 2.2vw, 2.25rem);
  line-height:1.08;
  letter-spacing:-.02em;
  font-weight:800;
  color:var(--kt-gold);
  text-shadow:0 0 14px rgba(197,162,74,.14);
}
.kt-head__text::after{
  content:"";
  display:block;
  width:68px;
  height:2px;
  margin-top:10px;
  border-radius:999px;
  background:linear-gradient(90deg, var(--kt-gold), rgba(197,162,74,0));
}

.kt-head__text{
  width:100%;
  max-width:none;
}

.kt-subtitle{
  width:100%;
  max-width:none;
  margin:0;
  color:rgba(255,255,255,.78);
  line-height:1.9;
  font-size:15.5px;
  text-align:justify;
  text-justify:inter-word;
}

.kt-toolbar{
  display:flex;
  align-items:center;
  gap:14px;
  flex-wrap:wrap;
  padding:16px;
  border:1px solid rgba(255,255,255,.06);
  border-radius:20px;
  background:
    radial-gradient(circle at top left, rgba(197,162,74,.08), transparent 28%),
    linear-gradient(180deg, rgba(255,255,255,.025), rgba(255,255,255,.012)),
    rgba(18,12,13,.58);
  box-shadow:0 14px 34px rgba(0,0,0,.18);
  backdrop-filter:blur(6px);
}




.kt-search{
  flex:1 1 520px;
  min-width:280px;
}

.kt-search input{
  width:100%;
  height:52px;
  border-radius:15px;
  border:1px solid rgba(255,255,255,.08);
  background:rgba(9,7,8,.76);
  color:#fff;
  padding:0 16px;
  outline:none;
  transition:border-color .2s ease, box-shadow .2s ease, background .2s ease;
}

.kt-search input::placeholder{
  color:rgba(255,255,255,.40);
}

.kt-search input:focus{
  border-color:rgba(197,162,74,.42);
  box-shadow:0 0 0 4px rgba(197,162,74,.10);
  background:rgba(9,7,8,.92);
}

.kt-actions{
  display:flex;
  align-items:center;
  gap:10px;
  flex-wrap:wrap;
}

.kt-hint{
  margin-top:12px;
  margin-bottom:4px;
  color:var(--kt-ink);
  font-size:15px;
}

.kt-empty-state{
  margin-top:18px;
  padding:22px;
  border-radius:18px;
  border:1px solid rgba(255,255,255,.08);
  background:rgba(255,255,255,.03);
}

.kt-grid-wrap{
  margin-top:22px;
}

.kt-grid{
  display:flex;
  flex-wrap:wrap;
  justify-content:center;
  gap:26px;
  align-items:stretch;
}

.kt-card{
  position:relative;
  width:285px;
  min-height:460px;
  display:flex;
  flex-direction:column;
  overflow:hidden;
  border-radius:24px;
  border:1px solid rgba(197,162,74,.16);
  background:
    radial-gradient(circle at top left, rgba(197,162,74,.06), transparent 24%),
    linear-gradient(180deg, rgba(255,255,255,.045), rgba(255,255,255,.018)),
    rgba(18,12,13,.92);
  box-shadow:
    0 18px 44px rgba(0,0,0,.34),
    0 0 0 rgba(197,162,74,0);
  transition:
    transform .28s ease,
    box-shadow .28s ease,
    border-color .28s ease;
}

.kt-card::before{
  content:"";
  position:absolute;
  inset:0 0 auto 0;
  height:1px;
  background:linear-gradient(90deg, transparent, rgba(197,162,74,.52), transparent);
  opacity:.75;
  z-index:3;
  pointer-events:none;
}

.kt-card:hover{
  transform:translateY(-7px);
  border-color:rgba(197,162,74,.34);
  box-shadow:
    0 26px 62px rgba(0,0,0,.42),
    0 0 24px rgba(197,162,74,.10),
    0 0 48px rgba(197,162,74,.05);
}

.kt-card__media{
  position:relative;
  display:block;
  height:220px;
  overflow:hidden;
  background:rgba(0,0,0,.2);
}

.kt-card__media img{
  width:100%;
  height:100%;
  object-fit:cover;
  object-position:center 20%;
  transform:scale(.93);
}

.kt-card:hover .kt-card__media img{
  transform:scale(1.06);
  filter:brightness(1.04) saturate(1.04);
}
.kt-card__name a:hover{
  color:var(--kt-gold-2);
  text-shadow:0 0 12px rgba(197,162,74,.16);
}
.kt-card__overlay{
  position:absolute;
  inset:0;
  background:
    linear-gradient(to top, rgba(10,8,9,.55), rgba(10,8,9,.08) 50%, rgba(10,8,9,0) 100%);
  pointer-events:none;
}

.kt-card__body{
  display:flex;
  flex-direction:column;
  padding:18px 18px 10px;
  flex:1;
}

.kt-card__name{
  font-size:20px;
  line-height:1.16;
  font-weight:900;
  color:#fff;
  letter-spacing:-.01em;
}

.kt-card__name a{
  color:#fff;
  text-decoration:none;
}

.kt-card__name a:hover{
  color:var(--kt-gold-2);
}

.kt-card__years{
  margin-top:8px;
  color:rgba(226,194,106,.95);
  font-size:14px;
  font-weight:800;
  letter-spacing:.02em;
}
.kt-card__bio{
  margin-top:10px;
  color:rgba(255,255,255,.80);
  line-height:1.68;
  font-size:14px;
  text-align:justify;
  text-justify:inter-word;
}

.kt-card__footer{
  display:flex;
  gap:12px;
  justify-content:space-between;
  align-items:center;
  padding:16px 18px 18px;
  border-top:1px solid rgba(255,255,255,.06);
  background:linear-gradient(180deg, rgba(255,255,255,.015), rgba(255,255,255,.005));
}

.kt-card__footer .btn{
  min-width:104px;
  border-radius:12px;
  transition:transform .2s ease, box-shadow .2s ease, border-color .2s ease;
}

.kt-card__footer .btn:hover{
  transform:translateY(-1px);
}

.kt-pager{
  margin-top:30px;
  display:flex;
  justify-content:center;
}

.kt-modal{
  position:fixed;
  inset:0;
  display:none;
  z-index:1200;
}

.kt-modal.is-open{
  display:block;
}

.kt-modal__backdrop{
  position:absolute;
  inset:0;
  background:rgba(0,0,0,.62);
  backdrop-filter: blur(4px);
}

.kt-modal__panel{
  position:relative;
  width:min(760px, calc(100% - 28px));
  margin:6vh auto 0;
  border-radius:24px;
  overflow:hidden;
  border:1px solid rgba(197,162,74,.20);
  background:
    linear-gradient(180deg, rgba(255,255,255,.04), rgba(255,255,255,.018)),
    rgba(18,12,13,.98);
  box-shadow:0 28px 70px rgba(0,0,0,.5);
}

.kt-modal__head{
  display:flex;
  align-items:flex-start;
  justify-content:space-between;
  gap:16px;
  padding:18px 20px 14px;
  border-bottom:1px solid rgba(255,255,255,.08);
}

.kt-modal__eyebrow{
  font-size:12px;
  letter-spacing:.08em;
  text-transform:uppercase;
  color:rgba(226,194,106,.85);
  margin-bottom:4px;
}

.kt-modal__title{
  font-size:26px;
  line-height:1.15;
  font-weight:900;
  color:#fff;
}

.kt-x{
  width:42px;
  height:42px;
  border:none;
  border-radius:12px;
  cursor:pointer;
  background:rgba(255,255,255,.06);
  color:#fff;
  font-size:26px;
  line-height:1;
  transition:background .2s ease, transform .2s ease;
}

.kt-x:hover{
  background:rgba(197,162,74,.18);
  transform:scale(1.04);
}

.kt-modal__body{
  padding:20px;
}

.kt-aiform{
  display:flex;
  gap:12px;
  flex-wrap:wrap;
}

.kt-aiform input{
  flex:1 1 420px;
  min-width:220px;
  height:52px;
  border-radius:14px;
  border:1px solid rgba(255,255,255,.08);
  background:rgba(9,7,8,.7);
  color:#fff;
  padding:0 16px;
  outline:none;
}

.kt-aiform input:focus{
  border-color:rgba(197,162,74,.38);
  box-shadow:0 0 0 4px rgba(197,162,74,.10);
}

.kt-ai-status{
  margin-top:12px;
  color:rgba(255,255,255,.74);
}

.kt-answer-wrap{
  margin-top:16px;
}

.kt-answer-label{
  margin-bottom:8px;
  color:rgba(226,194,106,.92);
  font-weight:700;
}

.kt-answer{
  padding:16px 18px;
  border-radius:16px;
  border:1px solid rgba(255,255,255,.06);
  background:rgba(255,255,255,.03);
  color:rgba(255,255,255,.90);
  line-height:1.8;
  white-space:pre-wrap;
  text-align:justify;
  text-justify:inter-word;
}

@media (max-width: 1200px){
  .kt-grid{
    grid-template-columns:repeat(auto-fit, minmax(270px, 1fr));
  }
}

@media (max-width: 760px){
  .kt-page-container{
    width:min(100%, calc(100% - 22px));
  }

  .kt-toolbar{
    padding:14px;
  }

  .kt-search{
    flex:1 1 100%;
    min-width:100%;
  }

  .kt-actions{
    width:100%;
  }

  .kt-actions .btn,
  .kt-actions .btn--ghost{
    flex:1 1 auto;
    text-align:center;
    justify-content:center;
  }
.kt-grid{
  gap:20px;
}

.kt-card{
  width:100%;
  max-width:380px;
  flex:1 1 100%;
}

  .kt-card{
    min-height:unset;
  }

  .kt-card__media{
    height:230px;
  }

  .kt-card__footer{
    flex-direction:column;
    align-items:stretch;
  }

  .kt-card__footer .btn{
    width:100%;
  }

  .kt-modal__panel{
    width:min(100%, calc(100% - 18px));
    margin:4vh auto 0;
  }

  .kt-modal__title{
    font-size:22px;
  }

  .kt-aiform{
    flex-direction:column;
  }

  .kt-aiform input,
  .kt-aiform .btn{
    width:100%;
  }
}
</style>

<script>
(function () {
  const modal = document.getElementById('aiModal');
  const titleEl = document.getElementById('aiTitle');
  const form = document.getElementById('aiForm');
  const qEl = document.getElementById('aiQuestion');
  const btn = document.getElementById('aiBtn');
  const status = document.getElementById('aiStatus');
  const wrap = document.getElementById('aiAnswerWrap');
  const answerEl = document.getElementById('aiAnswer');

  let contextText = '';

  function openModal() {
    modal.setAttribute('aria-hidden', 'false');
    modal.classList.add('is-open');
    document.body.style.overflow = 'hidden';
    status.style.display = 'none';
    wrap.style.display = 'none';
    answerEl.textContent = '';
    setTimeout(() => qEl && qEl.focus(), 80);
  }

  function closeModal() {
    modal.setAttribute('aria-hidden', 'true');
    modal.classList.remove('is-open');
    document.body.style.overflow = '';
  }

  function setStatus(text) {
    status.style.display = 'block';
    status.textContent = text;
  }

  modal.querySelectorAll('[data-close]').forEach((el) => {
    el.addEventListener('click', closeModal);
  });

  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape' && modal.classList.contains('is-open')) {
      closeModal();
    }
  });

  document.querySelectorAll('[data-ai]').forEach((button) => {
    button.addEventListener('click', () => {
      const name = (button.dataset.name || '').trim() || 'Ktitor';
      const born = (button.dataset.born || '').trim();
      const died = (button.dataset.died || '').trim();
      const bio = (button.dataset.bio || '').trim();

      const years = (born || died) ? `${born || '—'} – ${died || '—'}` : '—';

      titleEl.textContent = name;

      contextText =
        `KTITOR (podatak iz baze; jedini izvor istine):\n` +
        `Ime: ${name}\n` +
        `Godine: ${years}\n\n` +
        `Biografija:\n${bio || 'Nema opisa u bazi.'}\n`;

      qEl.value = '';
      openModal();
    });
  });

  if (!form) return;

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const question = (qEl.value || '').trim();

    if (!question) {
      setStatus('Unesi pitanje.');
      wrap.style.display = 'none';
      return;
    }

    btn.disabled = true;
    setStatus('Šaljem pitanje AI...');

    try {
      const res = await fetch('/api/ai/chat', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          message: question,
          context: contextText
        })
      });

      const data = await res.json().catch(() => ({}));

      if (!res.ok || data.ok === false) {
        setStatus(data.error || 'Greška pri AI upitu.');
        wrap.style.display = 'none';
        btn.disabled = false;
        return;
      }

      const text = (data.answer || data.reply || '').trim();

      setStatus(text ? 'Gotovo.' : 'AI nije vratio odgovor.');
      answerEl.textContent = text || '—';
      wrap.style.display = 'block';
    } catch (err) {
      setStatus('Ne mogu da kontaktiram backend ili Ollamu.');
      wrap.style.display = 'none';
    } finally {
      btn.disabled = false;
    }
  });
})();
</script>
@endsection