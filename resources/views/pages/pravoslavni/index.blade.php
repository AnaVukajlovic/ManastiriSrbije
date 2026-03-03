@extends('layouts.site')

@section('title', 'Pravoslavni sadržaj — Pravoslavni Svetionik')
@section('nav_pravoslavni', 'active')

@section('content')
<section class="section ps-page" id="top">
  <div class="container">

    {{-- HEADER --}}
    <div class="ps-hero">
      <div class="ps-hero__left">
        <div class="ps-kicker">
          <span class="ps-kicker__dot"></span>
          <span class="muted">Duhovni vodič kroz pravoslavlje</span>
        </div>

        <h1 class="ps-title">Pravoslavni sadržaj</h1>
        <p class="ps-sub">
          Crkveni kalendar, pouke, post i duhovni sadržaji — na jednom mestu.
        </p>
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

    {{-- TOP KARTICE --}}
    <div class="ps-topgrid">

      {{-- DANAS U KALENDARU --}}
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
            @php
              $titleToday = $day?->saint_name ?: ($day?->feast_name ?: '—');
            @endphp
            {{ $titleToday }}
          </div>

          <div class="ps-row">
            @if(!empty($day?->fasting_type))
              <span class="ps-badge">{{ $day->fasting_type }}</span>
            @else
              <span class="ps-badge ps-badge--muted">Nema posta</span>
            @endif

            @if(!empty($day?->is_red_letter))
              <span class="ps-badge ps-badge--gold">Crveno slovo</span>
            @endif
          </div>

          @if(!empty($day?->note))
            <div class="ps-note">{{ $day->note }}</div>
          @endif

          <div class="ps-actions">
            <a class="ps-link" href="{{ route('pravoslavni.show', 'kalendar') }}">Otvori kalendar →</a>
          </div>
        </div>
      </div>

      {{-- POUKA DANA --}}
<div class="ps-card">

  <div class="ps-card__head">
    <div class="ps-card__title">
      <span class="ps-ico">📜</span>
      <span>Pouka dana</span>
    </div>
    <span style="
      padding:6px 10px;
      border-radius:999px;
      border:1px solid rgba(255,255,255,.14);
      background:rgba(0,0,0,.25);
      font-size:12px;
    ">
      Nadahnuto
    </span>
  </div>

  <div class="ps-card__body">

    <div style="
      position:relative;
      border-radius:18px;
      border:1px solid rgba(255,255,255,.12);
      background:rgba(0,0,0,.22);
      padding:18px 18px 18px 22px;
      overflow:hidden;
      margin-bottom:12px;
    ">

      {{-- zlatna vertikalna linija --}}
      <div style="
        position:absolute;
        left:0;
        top:0;
        bottom:0;
        width:4px;
        background:linear-gradient(to bottom,
          rgba(197,162,74,.9),
          rgba(197,162,74,.3)
        );
      "></div>

      <div style="
        font-size:16px;
        line-height:1.8;
        font-style:italic;
        color:rgba(255,255,255,.92);
        animation: fadeQuote .6s ease;
      ">
        „{{ $pouka ?? 'Smirenje je odeća božanstva.' }}“
      </div>

    </div>

    <div style="font-size:12.5px; opacity:.75;">
      Uskoro: arhiva pouka svetih otaca i citata po temama.
    </div>

  </div>
</div>

<style>
@keyframes fadeQuote {
  from { opacity:0; transform:translateY(6px); }
  to { opacity:1; transform:translateY(0); }
}
</style>

      {{-- PREDSTOJEĆI PRAZNICI --}}
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

    {{-- DUHOVNI MODULI --}}
    <div class="ps-sectionhead">
      <div>
        <h2>Duhovni moduli</h2>
        <span class="muted">Izaberi oblast koju želiš da istražiš.</span>
      </div>

      <div class="ps-sectionmeta">
        <span class="ps-sectionpill">5 modula</span>
      </div>
    </div>

    <div class="ps-modgrid">

      <a class="ps-mod" href="{{ route('pravoslavni.show', 'osnovni-koncepti') }}">
        <div class="ps-mod__top">
          <span class="ps-mod__ico">📘</span>
          <span class="ps-mod__name">Osnovni koncepti vere</span>
        </div>
        <div class="ps-mod__desc">
          Liturgija, molitva, post, pričešće i osnove pravoslavlja.
        </div>
        <div class="ps-mod__cta">Otvori</div>
      </a>

      <a class="ps-mod" href="{{ route('pravoslavni.show', 'kalendar') }}">
        <div class="ps-mod__top">
          <span class="ps-mod__ico">🗓️</span>
          <span class="ps-mod__name">Pravoslavni kalendar</span>
        </div>
        <div class="ps-mod__desc">
          Svetitelji, praznici, tipovi posta i crvena slova.
        </div>
        <div class="ps-mod__cta">Otvori</div>
      </a>

      <a class="ps-mod" href="{{ route('pravoslavni.show', 'zanimljivosti') }}">
        <div class="ps-mod__top">
          <span class="ps-mod__ico">✨</span>
          <span class="ps-mod__name">Zanimljivosti</span>
        </div>
        <div class="ps-mod__desc">
          Kratke zanimljive činjenice iz pravoslavnog sveta.
        </div>
        <div class="ps-mod__cta">Otvori</div>
      </a>

      <a class="ps-mod" href="{{ route('pravoslavni.show', 'datum-vaskrsa') }}">
        <div class="ps-mod__top">
          <span class="ps-mod__ico">✝️</span>
          <span class="ps-mod__name">Datum Vaskrsa</span>
        </div>
        <div class="ps-mod__desc">
          Izračunavanje datuma Vaskrsa po godinama.
        </div>
        <div class="ps-mod__cta">Otvori</div>
      </a>

      <a class="ps-mod" href="{{ route('pravoslavni.show', 'posni-recepti') }}">
        <div class="ps-mod__top">
          <span class="ps-mod__ico">🥗</span>
          <span class="ps-mod__name">Posni recepti</span>
        </div>
        <div class="ps-mod__desc">
          Recepti prema postu — na vodi, ulju i riblji dani.
        </div>
        <div class="ps-mod__cta">Otvori</div>
      </a>

    </div>

  </div>
</section>
@endsection