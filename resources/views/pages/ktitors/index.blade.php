@extends('layouts.site')

@section('title','Ktitori — Pravoslavni Svetionik')
@section('nav_ktitors','active')

@section('content')
<section class="section">
  <div class="container">

    <div class="sectionhead">
      <h2>Ktitori</h2>
      <span class="muted">Vladari i dobrotvori srpskih svetinja</span>
    </div>

    {{-- Toolbar --}}
    <form class="kt-toolbar" method="GET" action="{{ route('ktitors.index') }}">
      <div class="kt-search">
        <input
          name="q"
          type="search"
          value="{{ $q ?? '' }}"
          placeholder="Pretraži ktitore (ime/biografija)..."
          aria-label="Pretraga ktitora"
        />
      </div>

      <div class="kt-actions">
        <button class="btn" type="submit">Pretraga</button>
        <a class="btn btn--ghost" href="{{ route('ktitors.index') }}">Reset</a>
      </div>
    </form>

    <div class="kt-hint muted">
      Pretraga: <b>{{ ($q ?? '') !== '' ? e($q) : '—' }}</b>
    </div>

    {{-- Grid --}}
    @if($ktitors->count() === 0)
      <div class="card" style="padding:18px; margin-top:14px;">
        <b>Nema rezultata</b>
        <div class="muted" style="margin-top:6px;">Pokušaj drugačiju pretragu.</div>
      </div>
    @else
      <div class="kt-grid" style="margin-top:14px;">
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
          @endphp

          <article class="kt-card">
            <a class="kt-card__media" href="{{ route('ktitors.show', $k->slug) }}" aria-label="Detalji: {{ e($k->name) }}">
              <img
                src="{{ $imgUrl }}"
                alt="{{ e($k->name) }}"
                loading="lazy"
                onerror="this.onerror=null;this.src='{{ asset('images/placeholders/ktitor.png') }}';"
              />
              <div class="kt-card__shade"></div>
            </a>

            <div class="kt-card__body">
              <div class="kt-card__name">{{ $k->name }}</div>

              @if($years)
                <div class="kt-card__years">{{ $years }}</div>
              @endif

              <div class="kt-card__bio">
                {{ \Illuminate\Support\Str::limit($bio !== '' ? $bio : 'Biografija uskoro…', 160) }}
              </div>
            </div>

            <div class="kt-card__footer">
              <a class="btn btn--ghost" href="{{ route('ktitors.show', $k->slug) }}">Detalji</a>

              {{-- ✅ Pitaj AI: šaljemo podatke kroz data-atribute --}}
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

      <div class="kt-pager">
        {{ $ktitors->links('vendor.pagination.ktitors') }}
      </div>
    @endif

  </div>
</section>

{{-- AI Modal --}}
<div class="kt-modal" id="aiModal" aria-hidden="true">
  <div class="kt-modal__backdrop" data-close></div>

  <div class="kt-modal__panel" role="dialog" aria-modal="true" aria-labelledby="aiTitle">
    <div class="kt-modal__head">
      <div>
        <div class="muted" style="font-size:12px;">Pitaj AI</div>
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

      <div id="aiStatus" class="muted" style="margin-top:10px; display:none;"></div>

      <div id="aiAnswerWrap" style="margin-top:12px; display:none;">
        <div class="muted" style="margin-bottom:6px;">Odgovor:</div>
        <div id="aiAnswer" class="kt-answer"></div>
      </div>
    </div>
  </div>
</div>

{{-- ✅ JS za modal + poziv /api/ai/chat --}}
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
  let currentName = '';

  function openModal() {
    modal.setAttribute('aria-hidden', 'false');
    modal.classList.add('is-open');
    status.style.display = 'none';
    wrap.style.display = 'none';
    answerEl.textContent = '';
    setTimeout(() => qEl && qEl.focus(), 50);
  }

  function closeModal() {
    modal.setAttribute('aria-hidden', 'true');
    modal.classList.remove('is-open');
  }

  function setStatus(t) {
    status.style.display = 'block';
    status.textContent = t;
  }

  // Close handlers
  modal.querySelectorAll('[data-close]').forEach(el => {
    el.addEventListener('click', closeModal);
  });
  document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeModal();
  });

  // Open from buttons
  document.querySelectorAll('[data-ai]').forEach((b) => {
    b.addEventListener('click', () => {
      const name = (b.dataset.name || '').trim() || 'Ktitor';
      const born = (b.dataset.born || '').trim();
      const died = (b.dataset.died || '').trim();
      const bio  = (b.dataset.bio || '').trim();

      currentName = name;
      titleEl.textContent = name;

      const years =
        (born || died) ? `${born || '—'} – ${died || '—'}` : '—';

      // Kontekst (jedini izvor istine)
      contextText =
        `KTITOR (podatak iz baze; jedini izvor istine):\n` +
        `Ime: ${name}\n` +
        `Godine: ${years}\n\n` +
        `Biografija:\n${bio || 'Nema opisa u bazi.'}\n`;

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

{{-- Minimal fallback stil ako ti modal nema .is-open logiku u CSS-u --}}
<style>
.kt-modal{ display:none; }
.kt-modal.is-open{ display:block; }
</style>

@endsection