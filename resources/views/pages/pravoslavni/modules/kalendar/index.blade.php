@extends('layouts.site')

@section('title', 'Pravoslavni kalendar — Pravoslavni Svetionik')
@section('nav_pravoslavni', 'active')

@section('content')
<section class="section">
  <div class="container cal">

    {{-- Breadcrumb --}}
    <div class="ps-bc">
      <a class="ps-bc__link" href="{{ route('pravoslavni.index') }}">Pravoslavni sadržaj</a>
      <span class="ps-bc__sep">/</span>
      <span class="ps-bc__current">Kalendar</span>
    </div>

    {{-- Header --}}
    <div class="cal-head">
      <div>
        <div class="cal-badge">
          <span class="cal-dot"></span>
          Pravoslavni kalendar
        </div>
        <h1 class="cal-title">{{ $monthStart->translatedFormat('F Y') }}</h1>
        <p class="cal-sub muted">
          Klikni na dan da vidiš svetitelja/praznik, post i napomenu.
        </p>
      </div>

      <div class="cal-nav">
        <a class="btn2 btn2--ghost" href="{{ route('pravoslavni.kalendar.index', ['date' => $prev->toDateString()]) }}">← {{ $prev->translatedFormat('M') }}</a>
        <a class="btn btn--gold" href="{{ route('pravoslavni.kalendar.index', ['date' => now()->toDateString()]) }}">Danas</a>
        <a class="btn2 btn2--ghost" href="{{ route('pravoslavni.kalendar.index', ['date' => $next->toDateString()]) }}">{{ $next->translatedFormat('M') }} →</a>
      </div>
    </div>

    <div class="cal-grid">

      {{-- LEFT: calendar --}}
      <div class="cal-card">
        <div class="cal-weekdays">
          <div>Pon</div><div>Uto</div><div>Sre</div><div>Čet</div><div>Pet</div><div>Sub</div><div>Ned</div>
        </div>

        <div class="cal-days">
          {{-- leading empty --}}
          @for($i=0; $i < $leadingEmpty; $i++)
            <div class="cal-cell cal-cell--empty"></div>
          @endfor

          {{-- days --}}
          @for($d=1; $d <= $daysInMonth; $d++)
            @php
              $row = $byDay->get($d);
              $isToday = $selected->copy()->setDay($d)->isSameDay(now());
              $isSelected = $selected->day === $d;

              $fast = $row?->fasting_type;
              $isRed = (bool)($row?->is_red_letter);

              $fastClass = 'cal-cell--normal';
              if ($fast) {
                $t = mb_strtolower($fast);
                if (str_contains($t,'nema')) $fastClass = 'cal-cell--normal';
                elseif (str_contains($t,'stro')) $fastClass = 'cal-cell--strict';
                elseif (str_contains($t,'ulj')) $fastClass = 'cal-cell--oil';
                else $fastClass = 'cal-cell--fast';
              }
            @endphp

            <a
              class="cal-cell {{ $fastClass }} {{ $isRed ? 'cal-cell--red' : '' }} {{ $isToday ? 'cal-cell--today' : '' }} {{ $isSelected ? 'cal-cell--selected' : '' }}"
              href="{{ route('pravoslavni.kalendar.show', ['date' => $selected->copy()->setDay($d)->toDateString()]) }}"
              aria-label="Dan {{ $d }}"
            >
              <div class="cal-num">{{ $d }}</div>

              <div class="cal-mini">
                @if($isRed)
                  <span class="cal-chip cal-chip--red">crveno</span>
                @endif

                @if($row?->saint_name || $row?->feast_name)
                  <div class="cal-name">
                    {{ $row->saint_name ?: $row->feast_name }}
                  </div>
                @else
                  <div class="cal-name cal-name--muted">—</div>
                @endif
              </div>
            </a>
          @endfor
        </div>

        <div class="cal-legend">
          <div class="cal-legend__item"><span class="cal-legend__sw cal-legend__sw--red"></span> Crveno slovo</div>
          <div class="cal-legend__item"><span class="cal-legend__sw cal-legend__sw--normal"></span> Nema posta / uobičajeno</div>
          <div class="cal-legend__item"><span class="cal-legend__sw cal-legend__sw--oil"></span> Post na ulju</div>
          <div class="cal-legend__item"><span class="cal-legend__sw cal-legend__sw--fast"></span> Post</div>
          <div class="cal-legend__item"><span class="cal-legend__sw cal-legend__sw--strict"></span> Strogi post</div>
        </div>
      </div>

      {{-- RIGHT: sidebar --}}
      <aside class="cal-side">

        <div class="cal-box">
          <div class="cal-box__head">
            <div class="cal-box__title">Izabrani dan</div>
            <div class="cal-box__date">{{ $selected->translatedFormat('d.m.Y') }}</div>
          </div>

          @if($dayRow)
            <div class="cal-kv">
              <div class="cal-kv__row">
                <div class="cal-kv__k">Svetac/Praznik</div>
                <div class="cal-kv__v">{{ $dayRow->saint_name ?: ($dayRow->feast_name ?: '—') }}</div>
              </div>
              <div class="cal-kv__row">
                <div class="cal-kv__k">Post</div>
                <div class="cal-kv__v">{{ $dayRow->fasting_type ?: 'Nema posta' }}</div>
              </div>
              @if(!empty($dayRow->note))
                <div class="cal-kv__row">
                  <div class="cal-kv__k">Napomena</div>
                  <div class="cal-kv__v">{{ $dayRow->note }}</div>
                </div>
              @endif
              @if($dayRow->is_red_letter)
                <div class="cal-call cal-call--gold">Crveno slovo</div>
              @endif
            </div>
          @else
            <div class="muted" style="line-height:1.6">
              Za ovaj datum još nema unosa u bazi. (To je ok — samo nastavi da puniš CSV.)
            </div>
          @endif

          <div class="cal-actions">
            <a class="btn2 btn2--ghost" href="{{ route('pravoslavni.kalendar.show', ['date' => $selected->toDateString()]) }}">Otvori detalj dana →</a>
          </div>
        </div>

        <div class="cal-box">
          <div class="cal-box__head">
            <div class="cal-box__title">Predstojećih 7 dana</div>
            <div class="muted">od {{ $selected->translatedFormat('d.m') }}</div>
          </div>

          <div class="cal-up">
            @forelse($upcoming as $u)
              <a class="cal-up__row" href="{{ route('pravoslavni.kalendar.show', ['date' => $u->date->toDateString()]) }}">
                <div class="cal-up__d">{{ $u->date->translatedFormat('d.m') }}</div>
                <div class="cal-up__t">
                  <div class="cal-up__name">{{ $u->saint_name ?: ($u->feast_name ?: '—') }}</div>
                  <div class="cal-up__meta muted">{{ $u->fasting_type ?: 'Nema posta' }} @if($u->is_red_letter) • Crveno slovo @endif</div>
                </div>
                <div class="cal-up__arr">→</div>
              </a>
            @empty
              <div class="muted">Nema podataka za naredne dane.</div>
            @endforelse
          </div>
        </div>

        <div class="cal-box cal-box--soft">
          <div class="cal-box__title">Dodatak na stranici (wow)</div>
          <div class="muted" style="line-height:1.6">
            Ovde možemo ubaciti “Pouku dana” (iz baze), ili mini objašnjenje: kako se čita kalendar i vrste posta.
          </div>
        </div>

      </aside>
    </div>

  </div>
</section>
@endsection