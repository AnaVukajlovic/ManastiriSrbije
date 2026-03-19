@extends('layouts.site')

@section('title', 'Vaskrs — Pravoslavni Svetionik')
@section('nav_pravoslavni', 'active')

@section('content')
<style>
  .vaskrs-page{
    --vk-bg: rgba(255,255,255,.03);
    --vk-bg-soft: rgba(255,255,255,.02);
    --vk-border: rgba(197,162,74,.16);
    --vk-border-soft: rgba(255,255,255,.08);
    --vk-text-soft: rgba(255,255,255,.72);
    --vk-text-mute: rgba(255,255,255,.60);
    --vk-gold: #c5a24a;
    --vk-gold-2: #e2c26a;
    --vk-gold-soft: rgba(197,162,74,.12);
    --vk-gold-border: rgba(197,162,74,.28);
    --vk-shadow: 0 18px 40px rgba(0,0,0,.28);
    --vk-shadow-hover: 0 24px 56px rgba(0,0,0,.34);
    --vk-radius: 22px;
  }

  .vaskrs-page .container{
    max-width: 1320px;
  }

  .vaskrs-hero{
    display:flex;
    align-items:flex-end;
    justify-content:space-between;
    gap:20px;
    margin-bottom:26px;
  }

  .vaskrs-hero__title{
    margin:0 0 10px !important;
    font-size:clamp(1.7rem, 2.4vw, 2.2rem) !important;
    line-height:1.08 !important;
    letter-spacing:-.02em !important;
    font-weight:800 !important;
    color:var(--vk-gold) !important;
    text-shadow:0 0 14px rgba(197,162,74,.14) !important;
  }

  .vaskrs-hero__sub{
    margin:0;
    max-width:760px;
    color:var(--vk-text-soft);
    line-height:1.75;
    font-size:.98rem;
  }

  .vaskrs-hero__meta{
    flex-shrink:0;
  }

  .vaskrs-pill{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:8px 14px;
    border-radius:999px;
    border:1px solid var(--vk-gold-border);
    background:var(--vk-gold-soft);
    color:#f3dfaa;
    font-size:.82rem;
    font-weight:700;
    white-space:nowrap;
  }

  .vaskrs-grid{
    display:grid;
    grid-template-columns:minmax(0, 1.15fr) minmax(0, .95fr);
    gap:24px;
    align-items:stretch;
  }

  .vaskrs-card,
  .vaskrs-wrap{
    border-radius:var(--vk-radius);
    border:1px solid var(--vk-border);
    background:
      radial-gradient(circle at top left, rgba(197,162,74,.07), transparent 28%),
      linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,.015)),
      rgba(20,12,12,.88);
    box-shadow:var(--vk-shadow);
    transition:transform .25s ease, box-shadow .25s ease, border-color .25s ease;
  }

  .vaskrs-card:hover,
  .vaskrs-wrap:hover{
    transform:translateY(-4px);
    box-shadow:var(--vk-shadow-hover);
    border-color:rgba(197,162,74,.22);
  }

  .vaskrs-card{
    padding:24px;
  }

  .vaskrs-card--guide{
    grid-column:1 / -1;
  }

  .vaskrs-card__head{
    display:flex;
    align-items:flex-start;
    justify-content:space-between;
    gap:14px;
    margin-bottom:14px;
  }

  .vaskrs-card__head h3{
    margin:0;
    font-size:1.15rem;
    line-height:1.2;
    font-weight:800;
    color:var(--vk-gold);
    text-shadow:0 0 12px rgba(197,162,74,.10);
  }

  .vaskrs-chip{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:7px 12px;
    border-radius:999px;
    font-size:.76rem;
    font-weight:700;
    color:#f7e8be;
    background:rgba(255,255,255,.04);
    border:1px solid var(--vk-border-soft);
    white-space:nowrap;
  }

  .vaskrs-form{
    display:flex;
    align-items:end;
    gap:14px;
    flex-wrap:wrap;
    margin-top:18px;
    padding-top:18px;
    border-top:1px solid rgba(255,255,255,.07);
  }

  .vaskrs-field{
    display:flex;
    flex-direction:column;
    gap:8px;
    min-width:190px;
  }

  .vaskrs-input{
    height:46px;
    padding:0 14px;
    border-radius:12px;
    border:1px solid rgba(255,255,255,.10);
    background:rgba(255,255,255,.04);
    color:#fff;
    outline:none;
    transition:.2s ease;
  }

  .vaskrs-input:focus{
    border-color:rgba(197,162,74,.55);
    box-shadow:0 0 0 4px rgba(197,162,74,.12);
    background:rgba(255,255,255,.05);
  }

  .vaskrs-btn{
    min-height:46px;
    padding:0 18px;
    border:none;
    border-radius:12px;
    font-weight:800;
    cursor:pointer;
    color:#1b120d;
    background:linear-gradient(135deg, #d9b35d, #f0d487);
    box-shadow:0 8px 20px rgba(197,162,74,.22);
    transition:transform .15s ease, box-shadow .15s ease, filter .15s ease;
  }

  .vaskrs-btn:hover{
    transform:translateY(-1px);
    box-shadow:0 12px 24px rgba(197,162,74,.28);
    filter:brightness(1.03);
  }

  .vaskrs-result{
    margin-top:18px;
    padding:16px 18px;
    border-radius:16px;
    border:1px solid var(--vk-border-soft);
    background:var(--vk-bg);
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:16px;
  }

  .vaskrs-badge{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:10px 16px;
    border-radius:999px;
    border:1px solid var(--vk-gold-border);
    background:var(--vk-gold-soft);
    color:#fff4d6;
    font-size:1rem;
    font-weight:800;
    text-align:center;
  }

  .vaskrs-mini{
    margin-top:14px;
    line-height:1.65;
    font-size:.9rem;
    color:var(--vk-text-soft);
  }

  .vaskrs-wrap{
    position:relative;
    overflow:hidden;
    min-height:100%;
    min-height:420px;
  }

  .vaskrs-img{
    position:relative;
    width:100%;
    height:100%;
  }

  .vaskrs-img img{
    display:block;
    width:100%;
    height:100%;
    min-height:420px;
    max-height:420px;
    object-fit:cover;
    object-position:center center;
    transition:transform .35s ease, filter .35s ease;
  }

  .vaskrs-wrap:hover .vaskrs-img img{
    transform:scale(1.04);
    filter:brightness(1.03) saturate(1.04);
  }

  .vaskrs-text{
    position:absolute;
    left:0;
    right:0;
    bottom:0;
    padding:24px 20px 20px;
    background:linear-gradient(to top, rgba(10,7,7,.92), rgba(10,7,7,.14));
    color:#fff;
  }

  .vaskrs-text__kicker{
    display:inline-block;
    margin-bottom:8px;
    font-size:.82rem;
    color:#f0d78f;
    font-weight:700;
  }

  .vaskrs-text__title{
    margin:0 0 8px;
    font-size:1.22rem;
    line-height:1.2;
    max-width:90%;
    color:#fff;
  }

  .vaskrs-text__desc{
    margin:0;
    color:rgba(255,255,255,.88);
    line-height:1.65;
    font-size:.94rem;
    max-width:90%;
  }

  .vaskrs-actions{
    margin-top:18px;
  }

  .vaskrs-btn-ghost{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-height:44px;
    padding:0 16px;
    border-radius:12px;
    text-decoration:none;
    font-weight:700;
    color:#fff;
    border:1px solid var(--vk-border-soft);
    background:rgba(255,255,255,.03);
    transition:.2s ease;
  }

  .vaskrs-btn-ghost:hover{
    transform:translateY(-1px);
    border-color:rgba(197,162,74,.30);
    background:rgba(197,162,74,.06);
    color:#f7e8be;
  }

  .vaskrs-quote{
    margin-top:20px;
    padding:18px;
    border-radius:16px;
    border:1px solid var(--vk-border-soft);
    background:rgba(255,255,255,.025);
  }

  .vaskrs-quote__title{
    line-height:1.6;
    margin-bottom:6px;
    color:#fff;
  }

  .vaskrs-quote__title strong{
    color:var(--vk-gold-2);
  }

  .vaskrs-quote__sub{
    line-height:1.6;
    font-size:.95rem;
    color:var(--vk-text-soft);
  }

  @media (max-width: 980px){
    .vaskrs-hero{
      flex-direction:column;
      align-items:flex-start;
    }

    .vaskrs-grid{
      grid-template-columns:1fr;
    }

    .vaskrs-card--guide{
      grid-column:auto;
    }

    .vaskrs-wrap{
      min-height:360px;
    }

    .vaskrs-img img{
      min-height:360px;
      max-height:360px;
    }

    .vaskrs-text__title,
    .vaskrs-text__desc{
      max-width:100%;
    }
  }

  @media (max-width: 640px){
    .vaskrs-card{
      padding:18px;
    }

    .vaskrs-card__head{
      flex-direction:column;
      align-items:flex-start;
    }

    .vaskrs-form{
      align-items:stretch;
    }

    .vaskrs-field{
      width:100%;
      min-width:unset;
    }

    .vaskrs-btn{
      width:100%;
    }

    .vaskrs-result{
      flex-direction:column;
      align-items:flex-start;
    }

    .vaskrs-badge{
      width:100%;
      justify-content:center;
    }

    .vaskrs-wrap{
      min-height:300px;
    }

    .vaskrs-img img{
      min-height:300px;
      max-height:300px;
    }

    .vaskrs-text{
      padding:18px 16px 16px;
    }

    .vaskrs-text__title{
      font-size:1.08rem;
    }

    .vaskrs-text__desc{
      font-size:.9rem;
      line-height:1.5;
    }

    .vaskrs-hero__title{
      font-size:clamp(1.45rem, 7vw, 1.8rem) !important;
    }
  }
</style>

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
        <span class="vaskrs-pill">Pravoslavni sadržaj</span>
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

      {{-- Slika --}}
      <div class="vaskrs-wrap">
        <div class="vaskrs-img">
          <img src="{{ asset('images/vaskrs/vaskrs.jpg') }}" alt="Vaskrs">
          <div class="vaskrs-text">
            <span class="vaskrs-text__kicker">Najveći hrišćanski praznik</span>
            <h3 class="vaskrs-text__title">Praznik Hristovog vaskrsenja</h3>
            <p class="vaskrs-text__desc">
              Vaskrs je središte hrišćanske vere i praznik pobede života nad smrću, svetlosti nad tamom i nade nad očajanjem.
            </p>
          </div>
        </div>
      </div>

      {{-- Vodič --}}
      <div class="vaskrs-card vaskrs-card--guide">
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

    const month = Math.floor((d + e + 114) / 31);
    const day = ((d + e + 114) % 31) + 1;

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

  window.calcVaskrs = calcVaskrs;

  const yearInput = document.getElementById('ve-year');
  if (yearInput) {
    yearInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') {
        e.preventDefault();
        calcVaskrs();
      }
    });
  }

  calcVaskrs();
})();
</script>
@endsection