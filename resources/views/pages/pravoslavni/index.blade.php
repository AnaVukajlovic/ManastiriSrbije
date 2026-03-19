@extends('layouts.site')

@section('title', 'Pravoslavni sadržaj — Pravoslavni Svetionik')
@section('nav_pravoslavni', 'active')

@section('content')
<section class="section ps-page" id="top">
  <div class="container">

    @php
      $titleToday = $day?->saint_name ?: ($day?->feast_name ?: '—');
      $isFastDay = !empty($day?->fasting_type);
      $isRedLetter = !empty($day?->is_red_letter);
      $duhovnaMisao = $pouka ?? 'Smirenje je odeća božanstva.';
    @endphp

    <style>
      .ps-page{
        --ps-gold:#c5a24a;
        --ps-gold-2:#e2c26a;
        --ps-ink:rgba(255,255,255,.94);
        --ps-muted:rgba(255,255,255,.72);
        --ps-muted-2:rgba(255,255,255,.58);
        --ps-line:rgba(255,255,255,.08);
        --ps-line-soft:rgba(255,255,255,.06);
        --ps-soft:rgba(255,255,255,.03);
        --ps-soft-2:rgba(255,255,255,.02);
        --ps-shadow:0 18px 44px rgba(0,0,0,.26);
        --ps-shadow-hover:0 24px 60px rgba(0,0,0,.32);
      }

      .ps-page .container{
        width:min(1560px, calc(100% - 34px));
        max-width:none;
      }

      /* HERO */
      .ps-hero{
        display:grid;
        grid-template-columns:minmax(0,1.2fr) auto;
        gap:24px;
        align-items:end;
        margin-bottom:28px;
      }

      .ps-kicker{
        display:flex;
        align-items:center;
        gap:8px;
        margin-bottom:10px;
      }

      .ps-kicker__dot{
        width:12px;
        height:12px;
        border-radius:999px;
        background:var(--ps-gold);
        box-shadow:0 0 0 4px rgba(197,162,74,.10);
      }

      .ps-kicker .muted{
        color:var(--ps-muted);
      }
.ps-page .ps-hero .ps-hero__left .ps-title{
  margin:0 0 10px !important;
  font-size:clamp(1.7rem, 2.3vw, 2.25rem) !important;
  line-height:1.08 !important;
  letter-spacing:-.02em !important;
  font-weight:800 !important;
  color:var(--ps-gold) !important;
  text-shadow:0 0 14px rgba(197,162,74,.14) !important;
}

      .ps-sub{
        margin:0;
        max-width:980px;
        color:var(--ps-muted);
        font-size:1rem;
        line-height:1.8;
      }

      .ps-hero__chips{
        display:flex;
        gap:10px;
        flex-wrap:wrap;
        margin-top:18px;
      }

      .ps-badge{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:8px 12px;
        border-radius:999px;
        border:1px solid rgba(197,162,74,.18);
        background:rgba(197,162,74,.10);
        color:#f0d78f;
        font-size:.88rem;
        font-weight:700;
        line-height:1;
      }

      .ps-badge--muted{
        border-color:rgba(255,255,255,.10);
        background:rgba(255,255,255,.04);
        color:rgba(255,255,255,.78);
      }

      .ps-badge--gold{
        background:linear-gradient(135deg, rgba(197,162,74,.18), rgba(226,194,106,.14));
        color:#f4dc95;
      }

      .ps-badge--soft{
        border-color:rgba(255,255,255,.08);
        background:rgba(255,255,255,.03);
        color:rgba(255,255,255,.78);
      }

      .ps-hero__right{
        display:flex;
        justify-content:flex-end;
      }

      .ps-meta{
        display:flex;
        align-items:center;
        gap:10px;
        flex-wrap:wrap;
        padding:10px 14px;
        border-radius:999px;
        border:1px solid var(--ps-line);
        background:rgba(255,255,255,.025);
        color:#fff;
      }

      .ps-meta strong{
        color:var(--ps-gold-2);
      }

      .ps-meta .muted{
        color:var(--ps-muted);
      }

      .ps-dot{
        color:rgba(255,255,255,.36);
      }

      /* TOP GRID */
      .ps-topgrid{
        display:grid;
        grid-template-columns:repeat(3, minmax(0, 1fr));
        gap:18px;
        margin-bottom:30px;
      }

      .ps-card{
        border-radius:24px;
        border:1px solid var(--ps-line);
        background:
          radial-gradient(circle at top left, rgba(197,162,74,.08), transparent 24%),
          linear-gradient(180deg, rgba(255,255,255,.025), rgba(255,255,255,.012)),
          rgba(18,12,13,.74);
        box-shadow:var(--ps-shadow);
        overflow:hidden;
        transition:transform .25s ease, box-shadow .25s ease, border-color .25s ease;
      }

      .ps-card:hover{
        transform:translateY(-5px);
        box-shadow:var(--ps-shadow-hover);
        border-color:rgba(197,162,74,.18);
      }

      .ps-card__head{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:12px;
        padding:16px 18px 12px;
        border-bottom:1px solid var(--ps-line-soft);
      }

      .ps-card__title{
        display:flex;
        align-items:center;
        gap:10px;
        font-size:1.02rem;
        font-weight:800;
        color:var(--ps-gold);
        text-shadow:0 0 12px rgba(197,162,74,.10);
      }

      .ps-ico{
        width:34px;
        height:34px;
        display:grid;
        place-items:center;
        border-radius:12px;
        background:rgba(197,162,74,.10);
        border:1px solid rgba(197,162,74,.16);
        font-size:1rem;
      }

      .ps-chip{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:7px 11px;
        border-radius:999px;
        border:1px solid rgba(255,255,255,.08);
        background:rgba(255,255,255,.03);
        color:rgba(255,255,255,.82);
        font-size:.82rem;
        font-weight:700;
      }

      .ps-card__body{
        padding:16px 18px 18px;
      }

      .ps-big{
        margin-bottom:14px;
        font-size:clamp(1.28rem, 1.55vw, 1.75rem);
        line-height:1.22;
        font-weight:800;
        color:#fff;
      }

      .ps-row{
        display:flex;
        gap:10px;
        flex-wrap:wrap;
        margin-bottom:14px;
      }

      .ps-note{
        color:var(--ps-muted);
        line-height:1.75;
        font-size:.95rem;
      }

      .ps-miniinfo{
        display:grid;
        grid-template-columns:repeat(2, minmax(0,1fr));
        gap:12px;
        margin-top:16px;
      }

      .ps-miniinfo__item{
        padding:12px;
        border-radius:16px;
        border:1px solid var(--ps-line-soft);
        background:rgba(255,255,255,.02);
      }

      .ps-miniinfo__label{
        display:block;
        margin-bottom:6px;
        font-size:.78rem;
        text-transform:uppercase;
        letter-spacing:.06em;
        color:var(--ps-muted-2);
      }

      .ps-miniinfo__item strong{
        color:#fff;
        font-size:1rem;
      }

      .ps-actions{
        margin-top:18px;
      }

      .ps-link{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:10px 14px;
        border-radius:999px;
        border:1px solid rgba(197,162,74,.18);
        background:rgba(197,162,74,.08);
        color:var(--ps-gold-2);
        text-decoration:none;
        font-weight:700;
        transition:transform .2s ease, background .2s ease, border-color .2s ease;
      }

      .ps-link:hover{
        transform:translateY(-1px);
        background:rgba(197,162,74,.12);
        border-color:rgba(197,162,74,.24);
      }

      .ps-quotebox{
        display:flex;
        gap:12px;
        align-items:flex-start;
        padding:2px 0 8px;
        margin-bottom:10px;
      }

      .ps-quotebar{
        width:3px;
        min-width:3px;
        border-radius:999px;
        background:linear-gradient(180deg, var(--ps-gold), var(--ps-gold-2));
        align-self:stretch;
      }

      .ps-quote{
        color:#fff;
        font-style:italic;
        line-height:1.75;
        font-size:1rem;
      }

      .ps-list{
        margin:0;
        padding:0;
        list-style:none;
        display:flex;
        flex-direction:column;
        gap:12px;
      }

      .ps-list li{
        display:grid;
        grid-template-columns:auto 1fr auto;
        gap:10px;
        align-items:flex-start;
        padding-bottom:12px;
        border-bottom:1px solid var(--ps-line-soft);
      }

      .ps-list li:last-child{
        border-bottom:none;
        padding-bottom:0;
      }

      .ps-list__dot{
        width:10px;
        height:10px;
        margin-top:7px;
        border-radius:999px;
        background:var(--ps-gold);
        box-shadow:0 0 0 4px rgba(197,162,74,.10);
      }

      .ps-list__text{
        color:#fff;
        line-height:1.6;
      }

      .ps-datepill{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:8px 11px;
        border-radius:999px;
        background:rgba(255,255,255,.04);
        border:1px solid rgba(255,255,255,.08);
        color:rgba(255,255,255,.84);
        font-size:.84rem;
        white-space:nowrap;
      }

      /* SECTION HEAD */
      .ps-sectionhead{
        display:flex;
        align-items:flex-end;
        justify-content:space-between;
        gap:18px;
        margin:6px 0 16px;
      }

      .ps-sectionhead h2{
        margin:0 0 6px;
        font-size:clamp(1.35rem, 1.8vw, 1.75rem);
        line-height:1.08;
        font-weight:800;
        color:var(--ps-gold);
        text-shadow:0 0 14px rgba(197,162,74,.12);
      }

      .ps-sectionhead .muted{
        color:var(--ps-muted);
      }

      .ps-sectionpill{
        display:inline-flex;
        align-items:center;
        justify-content:center;
        padding:8px 12px;
        border-radius:999px;
        border:1px solid rgba(255,255,255,.08);
        background:rgba(255,255,255,.03);
        color:rgba(255,255,255,.86);
        font-size:.84rem;
        font-weight:700;
      }

      /* MOD GRID */
      .ps-modgrid{
        display:grid;
        gap:16px;
      }

      .ps-modgrid--4{
        grid-template-columns:repeat(4, minmax(0,1fr));
      }

      .ps-mod{
        display:flex;
        flex-direction:column;
        min-height:220px;
        padding:18px;
        border-radius:24px;
        text-decoration:none;
        border:1px solid var(--ps-line);
        background:
          radial-gradient(circle at top left, rgba(197,162,74,.08), transparent 24%),
          linear-gradient(180deg, rgba(255,255,255,.025), rgba(255,255,255,.012)),
          rgba(18,12,13,.72);
        box-shadow:var(--ps-shadow);
        transition:transform .25s ease, box-shadow .25s ease, border-color .25s ease;
      }

      .ps-mod:hover{
        transform:translateY(-5px);
        box-shadow:var(--ps-shadow-hover);
        border-color:rgba(197,162,74,.18);
      }

      .ps-mod__top{
        display:flex;
        align-items:center;
        gap:12px;
        margin-bottom:14px;
      }

      .ps-mod__ico{
        width:42px;
        height:42px;
        display:grid;
        place-items:center;
        border-radius:14px;
        background:rgba(197,162,74,.10);
        border:1px solid rgba(197,162,74,.16);
        font-size:1.08rem;
      }

      .ps-mod__name{
        font-size:1.02rem;
        font-weight:800;
        color:var(--ps-gold);
        line-height:1.2;
        text-shadow:0 0 12px rgba(197,162,74,.10);
      }

      .ps-mod__desc{
        color:var(--ps-muted);
        line-height:1.72;
        font-size:.94rem;
      }

      .ps-mod__cta{
        margin-top:auto;
        padding-top:18px;
        color:var(--ps-gold-2);
        font-weight:700;
      }

      .ps-mod--featured{
        border-color:rgba(197,162,74,.18);
      }

      @media (max-width: 1220px){
        .ps-topgrid{
          grid-template-columns:1fr;
        }

        .ps-modgrid--4{
          grid-template-columns:repeat(2, minmax(0,1fr));
        }
      }

      @media (max-width: 760px){
        .ps-page .container{
          width:min(100%, calc(100% - 22px));
        }

        .ps-hero{
          grid-template-columns:1fr;
          align-items:start;
        }

        .ps-title{
          font-size:clamp(1.6rem, 7vw, 2.05rem);
        }

        .ps-meta{
          width:100%;
          justify-content:center;
          border-radius:18px;
        }

        .ps-miniinfo{
          grid-template-columns:1fr;
        }

        .ps-sectionhead{
          flex-direction:column;
          align-items:flex-start;
        }

        .ps-modgrid--4{
          grid-template-columns:1fr;
        }

        .ps-list li{
          grid-template-columns:auto 1fr;
        }

        .ps-datepill{
          grid-column:2;
          justify-self:start;
        }
      }
    </style>

    {{-- HERO --}}
    <div class="ps-hero">
      <div class="ps-hero__left">
        <div class="ps-kicker">
          <span class="ps-kicker__dot"></span>
          <span class="muted">Duhovni vodič kroz pravoslavlje</span>
        </div>

        <h1 class="ps-title">Pravoslavni sadržaj</h1>
        <p class="ps-sub">
          Crkveni kalendar, praznici, post, duhovne pouke i osnovni pojmovi pravoslavne vere — na jednom mestu.
        </p>

        <div class="ps-hero__chips">
          @if($isFastDay)
            <span class="ps-badge">{{ $day->fasting_type }}</span>
          @else
            <span class="ps-badge ps-badge--muted">Nije post</span>
          @endif

          @if($isRedLetter)
            <span class="ps-badge ps-badge--gold">Crveno slovo</span>
          @endif

          <span class="ps-badge ps-badge--soft">{{ $today->translatedFormat('l') }}</span>
        </div>
      </div>

      <div class="ps-hero__right">
        <div class="ps-meta">
          <span class="muted">Danas je</span>
          <strong>{{ $today->format('d.m.Y') }}</strong>
          <span class="ps-dot">•</span>
          <span class="muted">{{ $today->translatedFormat('l') }}</span>
        </div>
      </div>
    </div>

    {{-- TOP GRID --}}
    <div class="ps-topgrid">

      <div class="ps-card ps-card--today">
        <div class="ps-card__head">
          <div class="ps-card__title">
            <span class="ps-ico">🕯️</span>
            <span>Danas u kalendaru</span>
          </div>
          <span class="ps-chip">Kalendar</span>
        </div>

        <div class="ps-card__body">
          <div class="ps-big">
            {{ $titleToday }}
          </div>

          <div class="ps-row">
            @if($isFastDay)
              <span class="ps-badge">{{ $day->fasting_type }}</span>
            @else
              <span class="ps-badge ps-badge--muted">Nema posta</span>
            @endif

            @if($isRedLetter)
              <span class="ps-badge ps-badge--gold">Crveno slovo</span>
            @endif
          </div>

          @if(!empty($day?->note))
            <div class="ps-note">{{ $day->note }}</div>
          @else
            <div class="ps-note">
              Pogledaj detalje dana, svetitelje i crkvene napomene u kalendaru.
            </div>
          @endif

          <div class="ps-miniinfo">
            <div class="ps-miniinfo__item">
              <span class="ps-miniinfo__label">Datum</span>
              <strong>{{ $today->format('d.m.Y') }}</strong>
            </div>
            <div class="ps-miniinfo__item">
              <span class="ps-miniinfo__label">Dan</span>
              <strong>{{ $today->translatedFormat('l') }}</strong>
            </div>
          </div>

          <div class="ps-actions">
            <a class="ps-link" href="{{ route('pravoslavni.show', 'kalendar') }}">Otvori kalendar →</a>
          </div>
        </div>
      </div>

      <div class="ps-card ps-card--thought">
        <div class="ps-card__head">
          <div class="ps-card__title">
            <span class="ps-ico">📜</span>
            <span>Duhovna misao dana</span>
          </div>
          <span class="ps-chip">Nadahnuće</span>
        </div>

        <div class="ps-card__body">
          <div class="ps-quotebox">
            <div class="ps-quotebar"></div>
            <div class="ps-quote">
              „{{ $duhovnaMisao }}“
            </div>
          </div>

          <div class="ps-note">
            Kratka misao za sabranost, mir i podsećanje na duhovni smisao dana.
          </div>

          <div class="ps-actions">
            <a class="ps-link" href="{{ route('pravoslavni.show', 'osnovni-koncepti') }}">Istraži sadržaj →</a>
          </div>
        </div>
      </div>

      <div class="ps-card ps-card--upcoming">
        <div class="ps-card__head">
          <div class="ps-card__title">
            <span class="ps-ico">🗓️</span>
            <span>Predstojeći praznici</span>
          </div>
          <span class="ps-chip">Naredni dani</span>
        </div>

        <div class="ps-card__body">
          @if(isset($upcoming) && $upcoming->count())
            <ul class="ps-list">
              @foreach($upcoming as $u)
                <li>
                  <span class="ps-list__dot"></span>
                  <span class="ps-list__text">{{ $u['label'] ?? '—' }}</span>
                  @if(!empty($u['date']))
                    <span class="ps-datepill">{{ $u['date'] }}</span>
                  @endif
                </li>
              @endforeach
            </ul>
          @else
            <div class="muted">Nema podataka za naredne praznike.</div>
          @endif

          <div class="ps-actions">
            <a class="ps-link" href="{{ route('pravoslavni.show', 'kalendar') }}">Detalji u kalendaru →</a>
          </div>
        </div>
      </div>

    </div>

    {{-- MODULI --}}
    <div class="ps-sectionhead">
      <div>
        <h2>Duhovni moduli</h2>
        <span class="muted">Izaberi oblast koju želiš da istražiš i produbiš svoje znanje.</span>
      </div>

      <div class="ps-sectionmeta">
        <span class="ps-sectionpill">4 modula</span>
      </div>
    </div>

    <div class="ps-modgrid ps-modgrid--4">

      <a class="ps-mod ps-mod--featured" href="{{ route('pravoslavni.show', 'osnovni-koncepti') }}">
        <div class="ps-mod__top">
          <span class="ps-mod__ico">📘</span>
          <span class="ps-mod__name">Osnovni koncepti vere</span>
        </div>
        <div class="ps-mod__desc">
          Liturgija, molitva, post, pričešće i osnovni pojmovi pravoslavnog života.
        </div>
        <div class="ps-mod__cta">Otvori</div>
      </a>

      <a class="ps-mod" href="{{ route('pravoslavni.show', 'kalendar') }}">
        <div class="ps-mod__top">
          <span class="ps-mod__ico">🗓️</span>
          <span class="ps-mod__name">Pravoslavni kalendar</span>
        </div>
        <div class="ps-mod__desc">
          Svetitelji, praznici, tipovi posta, crvena slova i dnevni crkveni sadržaj.
        </div>
        <div class="ps-mod__cta">Otvori</div>
      </a>

      <a class="ps-mod" href="{{ route('pravoslavni.show', 'zanimljivosti') }}">
        <div class="ps-mod__top">
          <span class="ps-mod__ico">✨</span>
          <span class="ps-mod__name">Zanimljivosti</span>
        </div>
        <div class="ps-mod__desc">
          Kratke zanimljive činjenice, predanja i detalji iz pravoslavnog sveta.
        </div>
        <div class="ps-mod__cta">Otvori</div>
      </a>

      <a class="ps-mod" href="{{ route('pravoslavni.show', 'datum-vaskrsa') }}">
        <div class="ps-mod__top">
          <span class="ps-mod__ico">✝️</span>
          <span class="ps-mod__name">Datum Vaskrsa</span>
        </div>
        <div class="ps-mod__desc">
          Izračunavanje datuma Vaskrsa po godinama i razumevanje njegovog značaja.
        </div>
        <div class="ps-mod__cta">Otvori</div>
      </a>

    </div>

  </div>
</section>
@endsection