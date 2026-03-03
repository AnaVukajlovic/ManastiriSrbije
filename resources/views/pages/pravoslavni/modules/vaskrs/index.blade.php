@extends('layouts.site')

@section('title', 'Vaskrs — Pravoslavni Svetionik')
@section('nav_pravoslavni', 'active')

@section('content')
<section class="section vaskrs-page" id="top">
  <div class="container">

    {{-- HERO --}}
    <div class="vaskrs-hero">
      <div class="vaskrs-hero__text">
        <h1 class="vaskrs-hero__title">Vaskrs</h1>
        <p class="vaskrs-hero__sub">
          Računanje datuma Vaskrsa + vodič kroz smisao praznika, običaje i pozdrav.
        </p>
      </div>

      <div class="vaskrs-hero__meta">
        <span class="vaskrs-pill">Pravoslavni Svetionik</span>
      </div>
    </div>

    {{-- GRID --}}
    <div class="vaskrs-grid">

      {{-- Kalkulator --}}
      <div class="vaskrs-card">
        <div class="vaskrs-card__head">
          <h3>Izračunaj datum Vaskrsa</h3>
          <span class="vaskrs-chip">Kalkulator</span>
        </div>

        <p class="muted">
          Unesi godinu i dobićeš datum Vaskrsa po pravoslavnom računanju (Julijanski pashalion → građanski kalendar).
        </p>

        <div class="vaskrs-form">
          <div class="vaskrs-field">
            <label for="ve-year" class="muted">Godina</label>
            <input
              id="ve-year"
              class="vaskrs-input"
              type="number"
              min="1900"
              max="2200"
              value="{{ $year }}"
              inputmode="numeric"
            />
          </div>

          <button class="btn vaskrs-btn" type="button" onclick="calcVaskrs()">
            Izračunaj
          </button>
        </div>

        <div class="vaskrs-result" aria-live="polite">
          <span class="muted">Rezultat</span>
          <strong class="vaskrs-badge" id="ve-date">—</strong>
        </div>

        <div class="vaskrs-mini muted">
          Napomena: za godine 1900–2099 razlika julijanski → gregorijanski je 13 dana (posle 2100 je 14).
        </div>
      </div>

      {{-- Vodič --}}
      <div class="vaskrs-card">
        <div class="vaskrs-card__head">
          <h3>Vodič</h3>
          <span class="vaskrs-chip">Čitanje</span>
        </div>

        <p>
          Na stranici vodiča nalazi se lepo objašnjenje: šta je Vaskrs (Pasha), zašto je “praznik nad praznicima”,
          kako Crkva opisuje događaj Vaskrsenja, kao i simbolika običaja farbanja jaja.
        </p>

        <div class="vaskrs-actions">
          <a class="btn btn--ghost vaskrs-btn-ghost" href="{{ route('vaskrs.show', 'sve-o-vaskrsu') }}">
            Pročitaj sve o Vaskrsu →
          </a>
        </div>

        <div class="vaskrs-quote">
          <div class="vaskrs-quote__title">
            <strong>Hristos vaskrse!</strong> <span class="muted">—</span> <strong>Vaistinu vaskrse!</strong>
          </div>
          <div class="vaskrs-quote__sub muted">
            Praznični pozdrav kao kratko ispovedanje radosti Vaskrsenja.
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<script>
(function () {
  function orthodoxEasterDate(year) {
    const a = year % 4;
    const b = year % 7;
    const c = year % 19;
    const d = (19 * c + 15) % 30;
    const e = (2 * a + 4 * b - d + 34) % 7;

    // Julian calendar month/day
    const month = Math.floor((d + e + 114) / 31); // 3=March, 4=April
    const day = ((d + e + 114) % 31) + 1;

    // Julian->Gregorian shift (1900–2099=13 days, 2100+=14)
    const shift = (year >= 2100) ? 14 : 13;

    const dt = new Date(Date.UTC(year, month - 1, day));
    dt.setUTCDate(dt.getUTCDate() + shift);
    return dt;
  }

  function fmtSR(dt) {
    const dd = String(dt.getUTCDate()).padStart(2, '0');
    const mm = String(dt.getUTCMonth() + 1).padStart(2, '0');
    const yyyy = dt.getUTCFullYear();
    return `${dd}.${mm}.${yyyy}.`;
  }

  function setText(id, text) {
    const el = document.getElementById(id);
    if (el) el.textContent = text;
  }

  function calcVaskrs() {
    const input = document.getElementById('ve-year');
    const y = parseInt((input && input.value) ? input.value : '0', 10);

    if (!y || y < 1900 || y > 2200) {
      setText('ve-date', 'Unesi godinu 1900–2200.');
      return;
    }

    const dt = orthodoxEasterDate(y);
    setText('ve-date', fmtSR(dt));
  }

  // expose for onclick
  window.calcVaskrs = calcVaskrs;

  // Enter key triggers calc
  const yearInput = document.getElementById('ve-year');
  if (yearInput) {
    yearInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        calcVaskrs();
      }
    });
  }

  // auto calculate on load
  calcVaskrs();
})();
</script>
@endsection