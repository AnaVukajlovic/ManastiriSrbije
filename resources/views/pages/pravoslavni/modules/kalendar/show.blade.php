@extends('layouts.site')

@section('title', 'Kalendar — ' . $selected->translatedFormat('d.m.Y') . ' — Pravoslavni Svetionik')
@section('nav_pravoslavni', 'active')

@section('content')
<section class="section">
  <div class="container cal">

    <div class="ps-bc">
      <a class="ps-bc__link" href="{{ route('pravoslavni.index') }}">Pravoslavni sadržaj</a>
      <span class="ps-bc__sep">/</span>
      <a class="ps-bc__link" href="{{ route('pravoslavni.kalendar.index', ['date' => $selected->toDateString()]) }}">Kalendar</a>
      <span class="ps-bc__sep">/</span>
      <span class="ps-bc__current">{{ $selected->translatedFormat('d.m.Y') }}</span>
    </div>

    <div class="cal-showhead">
      <div>
        <div class="cal-badge">
          <span class="cal-dot"></span>
          Detalj dana
        </div>
        <h1 class="cal-title">{{ $selected->translatedFormat('l, d. F Y') }}</h1>
        <p class="cal-sub muted">Svetitelj/praznik, post i napomene za izabrani dan.</p>
      </div>

      <div class="cal-nav">
        <a class="btn2 btn2--ghost" href="{{ route('pravoslavni.kalendar.show', ['date' => $prev->toDateString()]) }}">← Prethodni</a>
        <a class="btn2 btn2--ghost" href="{{ route('pravoslavni.kalendar.index', ['date' => $selected->toDateString()]) }}">Nazad na mesec</a>
        <a class="btn2 btn2--ghost" href="{{ route('pravoslavni.kalendar.show', ['date' => $next->toDateString()]) }}">Sledeći →</a>
      </div>
    </div>

    <div class="cal-showgrid">

      <article class="cal-card cal-card--pad">
        @if($row)
          <div class="cal-big">
            <div class="cal-big__top">
              <div class="cal-big__k">
                <div class="cal-big__label">Svetac/Praznik</div>
                <div class="cal-big__value">{{ $row->saint_name ?: ($row->feast_name ?: '—') }}</div>
              </div>

              <div class="cal-big__badges">
                @if($row->is_red_letter)
                  <span class="cal-chip cal-chip--red">Crveno slovo</span>
                @endif
                @if(!empty($row->fasting_type))
                  <span class="cal-chip cal-chip--gold">{{ $row->fasting_type }}</span>
                @else
                  <span class="cal-chip">Nema posta</span>
                @endif
              </div>
            </div>

            @if(!empty($row->note))
              <div class="cal-call cal-call--note">
                <div class="cal-call__ico">🕯️</div>
                <div class="cal-call__txt">{{ $row->note }}</div>
              </div>
            @endif
          </div>
        @else
          <div class="cal-empty">
            <h2>Nema unosa u bazi</h2>
            <p class="muted">Za ovaj datum nema zapisa u tabeli <code>calendar_days</code>.</p>
            <p class="muted">Možeš kasnije dopuniti CSV pa importovati opet.</p>
          </div>
        @endif

        <div class="cal-divider"></div>

        <h2 class="cal-h2">Ove nedelje (7 dana)</h2>

        <div class="cal-up">
          @forelse($week as $u)
            <a class="cal-up__row" href="{{ route('pravoslavni.kalendar.show', ['date' => $u->date->toDateString()]) }}">
              <div class="cal-up__d">{{ $u->date->translatedFormat('d.m') }}</div>
              <div class="cal-up__t">
                <div class="cal-up__name">{{ $u->saint_name ?: ($u->feast_name ?: '—') }}</div>
                <div class="cal-up__meta muted">{{ $u->fasting_type ?: 'Nema posta' }} @if($u->is_red_letter) • Crveno slovo @endif</div>
              </div>
              <div class="cal-up__arr">→</div>
            </a>
          @empty
            <div class="muted">Nema podataka.</div>
          @endforelse
        </div>
      </article>

      <aside class="cal-side">
        <div class="cal-box">
          <div class="cal-box__title">Legenda posta</div>
          <div class="cal-legend cal-legend--stack">
            <div class="cal-legend__item"><span class="cal-legend__sw cal-legend__sw--red"></span> Crveno slovo</div>
            <div class="cal-legend__item"><span class="cal-legend__sw cal-legend__sw--normal"></span> Nema posta</div>
            <div class="cal-legend__item"><span class="cal-legend__sw cal-legend__sw--oil"></span> Post na ulju</div>
            <div class="cal-legend__item"><span class="cal-legend__sw cal-legend__sw--fast"></span> Post</div>
            <div class="cal-legend__item"><span class="cal-legend__sw cal-legend__sw--strict"></span> Strogi post</div>
          </div>
        </div>

        <div class="cal-box cal-box--soft">
          <div class="cal-box__title">Ideja “pored kalendara”</div>
          <div class="muted" style="line-height:1.6">
            1) Pouka dana (iz baze citata) <br>
            2) Kratko objašnjenje posta i pripreme za Pričešće <br>
            3) Dugme “Podeli dan” (kopira tekst) – kasnije lako dodamo JS.
          </div>
        </div>
      </aside>

    </div>

  </div>
</section>
@endsection