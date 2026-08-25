@extends('layouts.site')

@section('title', 'Pravoslavni kalendar — Pravoslavni Svetionik')
@section('nav_pravoslavni', 'active')

@section('content')
<style>
  .cal-page {
    --cal-gold: #c5a24a;
    --cal-gold-bright: #e2c26a;
    --cal-red: #e63946;
    --cal-red-soft: rgba(230, 57, 70, 0.15);
    --cal-card-bg: linear-gradient(180deg, rgba(28, 18, 16, 0.98), rgba(16, 10, 10, 0.98));
    --cal-line: rgba(197, 162, 74, 0.25);
    max-width: 1360px;
    margin: 0 auto;
  }

  .cal-head-card {
    background: var(--cal-card-bg);
    border: 1.5px solid var(--cal-line);
    border-radius: 24px;
    padding: 26px 30px;
    margin-bottom: 24px;
    box-shadow: 0 16px 40px rgba(0,0,0,0.5);
  }

  .cal-month-pills {
    display: flex;
    gap: 6px;
    overflow-x: auto;
    padding-bottom: 6px;
    margin-top: 18px;
    scrollbar-width: thin;
  }
  .cal-m-pill {
    padding: 6px 14px;
    border-radius: 999px;
    font-size: 0.84rem;
    font-weight: 600;
    text-decoration: none;
    color: rgba(255, 255, 255, 0.8);
    background: rgba(255, 255, 255, 0.05);
    border: 1px solid rgba(255, 255, 255, 0.1);
    white-space: nowrap;
    transition: all 0.2s ease;
  }
  .cal-m-pill:hover, .cal-m-pill.active {
    background: rgba(197, 162, 74, 0.25);
    border-color: var(--cal-gold);
    color: #fff;
  }
  .cal-m-pill.active {
    font-weight: 800;
    box-shadow: 0 0 12px rgba(197, 162, 74, 0.3);
  }

  .cal-legend-bar {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 12px;
    padding: 12px 18px;
    background: rgba(18, 11, 10, 0.7);
    border: 1px solid rgba(197, 162, 74, 0.2);
    border-radius: 14px;
    margin-bottom: 22px;
    font-size: 0.84rem;
  }

  .cal-grid-layout {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 24px;
  }

  .cal-main-table {
    background: var(--cal-card-bg);
    border: 1.5px solid var(--cal-line);
    border-radius: 24px;
    padding: 22px;
    box-shadow: 0 16px 40px rgba(0,0,0,0.5);
  }

  .cal-weekdays-row {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
    margin-bottom: 10px;
    text-align: center;
    font-size: 0.86rem;
    font-weight: 700;
    color: var(--cal-gold);
    text-transform: uppercase;
    letter-spacing: 0.5px;
  }
  .cal-weekdays-row div:last-child {
    color: #f87171; /* Nedelja crvena */
  }

  .cal-days-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
  }

  .cal-day-cell {
    position: relative;
    min-height: 105px;
    background: rgba(22, 14, 13, 0.85);
    border: 1px solid rgba(255, 255, 255, 0.08);
    border-radius: 14px;
    padding: 8px 9px 7px;
    display: flex;
    flex-direction: column;
    text-decoration: none;
    transition: all 0.22s ease;
    cursor: pointer;
    overflow: hidden;
  }
  .cal-day-cell:hover {
    transform: translateY(-2px);
    border-color: var(--cal-gold);
    box-shadow: 0 8px 20px rgba(0,0,0,0.4);
    background: rgba(32, 20, 18, 0.95);
  }
  .cal-day-cell--empty {
    background: transparent !important;
    border-color: transparent !important;
    cursor: default !important;
    min-height: 0;
  }

  /* Crveno slovo */
  .cal-day-cell--red {
    background: linear-gradient(180deg, rgba(230, 57, 70, 0.18), rgba(22, 14, 13, 0.9)) !important;
    border-color: rgba(230, 57, 70, 0.45) !important;
  }
  .cal-day-cell--red:hover {
    border-color: #e63946 !important;
    box-shadow: 0 8px 24px rgba(230, 57, 70, 0.3) !important;
  }

  /* Crno podebljano slovo */
  .cal-day-cell--bold {
    background: linear-gradient(180deg, rgba(197, 162, 74, 0.12), rgba(22, 14, 13, 0.9)) !important;
    border-color: rgba(197, 162, 74, 0.35) !important;
  }

  /* Danas */
  .cal-day-cell--today {
    border: 2px solid var(--cal-gold-bright) !important;
    box-shadow: 0 0 16px rgba(197, 162, 74, 0.45) !important;
  }

  /* Izabrani dan */
  .cal-day-cell--selected {
    background: rgba(197, 162, 74, 0.25) !important;
    border-color: var(--cal-gold-bright) !important;
  }

  .cal-day-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 4px;
  }

  .cal-day-num {
    font-size: 1.12rem;
    font-weight: 700;
    color: rgba(255, 255, 255, 0.95);
    line-height: 1;
  }
  .cal-day-num--red {
    color: #ff5c6a !important;
    font-weight: 800;
  }
  .cal-day-num--bold {
    color: #fce7b0 !important;
    font-weight: 800;
  }

  .cal-day-saint {
    font-size: 0.76rem;
    line-height: 1.3;
    color: rgba(255, 255, 255, 0.82);
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
    margin-bottom: auto;
  }
  .cal-day-saint--red {
    color: #ffa3ab !important;
    font-weight: 700;
  }
  .cal-day-saint--bold {
    color: #f7e1b5 !important;
    font-weight: 700;
  }

  .cal-day-fast-badge {
    margin-top: 5px;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 0.72rem;
    font-weight: 700;
    padding: 2px 7px;
    border-radius: 6px;
    width: fit-content;
  }

  /* Bočni panel */
  .cal-side-panel {
    display: flex;
    flex-direction: column;
    gap: 20px;
  }
  .cal-side-card {
    background: var(--cal-card-bg);
    border: 1.5px solid var(--cal-line);
    border-radius: 24px;
    padding: 22px 24px;
    box-shadow: 0 16px 40px rgba(0,0,0,0.5);
  }

  @media (max-width: 1024px) {
    .cal-grid-layout { grid-template-columns: 1fr; }
    .cal-day-cell { min-height: 90px; }
  }
  @media (max-width: 640px) {
    .cal-day-cell { min-height: 80px; padding: 5px 6px; }
    .cal-day-saint { font-size: 0.68rem; -webkit-line-clamp: 2; }
    .cal-day-fast-badge { font-size: 0.65rem; padding: 1px 4px; }
  }
</style>

@php
    use App\Support\OrthodoxCalendarHelper;
    use Carbon\Carbon;
@endphp

<section class="section cal-page">
  <div class="container">

    {{-- BREADCRUMBS --}}
    <div class="ps-bc" style="margin-bottom: 16px;">
      <a class="ps-bc__link" href="{{ route('pravoslavni.index') }}">Pravoslavni sadržaj</a>
      <span class="ps-bc__sep">/</span>
      <span class="ps-bc__current">Crkveni kalendar SPC</span>
    </div>

    {{-- GLAVNO ZAGLAVLJE SA NAVIGACIJOM I BRZIM SKOKOM --}}
    <div class="cal-head-card">
      <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div>
          <div style="display: inline-flex; align-items: center; gap: 8px; padding: 5px 14px; border-radius: 999px; background: rgba(197, 162, 74, 0.12); border: 1px solid rgba(197, 162, 74, 0.3); color: var(--cal-gold-bright); font-size: 0.82rem; font-weight: 700; margin-bottom: 8px;">
            <span>☦️</span> Crkveni kalendar za {{ $selected->year }}. godinu
          </div>
          <h1 style="margin: 0; font-size: clamp(1.9rem, 3.5vw, 2.7rem); color: var(--cal-gold); font-weight: 800; line-height: 1.1;">
            {{ $monthStart->translatedFormat('F Y') }}
          </h1>
          <p style="margin: 6px 0 0; font-size: 0.92rem; color: rgba(255, 255, 255, 0.7);">
            Izvor: <strong>Zvanični crkveni kalendar Srpske Pravoslavne Crkve (SPC)</strong>
          </p>
        </div>

        {{-- PREV / TODAY / NEXT DUGMAD --}}
        <div style="display: flex; align-items: center; gap: 8px;">
          <a class="btn2 btn2--ghost" href="{{ route('pravoslavni.kalendar.index', ['date' => $prev->toDateString()]) }}" title="Prethodni mesec">
            ← {{ $prev->translatedFormat('M') }}
          </a>
          <a class="btn btn--gold" href="{{ route('pravoslavni.kalendar.index', ['date' => now()->toDateString()]) }}" style="background: linear-gradient(135deg, #c5a24a, #e2c26a); color: #160e0a; font-weight: 800;">
            Danas
          </a>
          <a class="btn2 btn2--ghost" href="{{ route('pravoslavni.kalendar.index', ['date' => $next->toDateString()]) }}" title="Sledeći mesec">
            {{ $next->translatedFormat('M') }} →
          </a>
        </div>
      </div>

      {{-- SKOK NA BILO KOJI MESEC U GODINI --}}
      <div class="cal-month-pills">
        @foreach($monthsList as $mItem)
          <a 
            href="{{ route('pravoslavni.kalendar.index', ['date' => $mItem['date']]) }}" 
            class="cal-m-pill {{ $mItem['is_active'] ? 'active' : '' }}"
          >
            {{ $mItem['name'] }}
          </a>
        @endforeach
      </div>
    </div>

    {{-- LEGENDA KALENDARA --}}
    <div class="cal-legend-bar">
      <span style="font-weight: 700; color: var(--cal-gold); margin-right: 6px;">Legenda kalendara:</span>
      <span style="display: inline-flex; align-items: center; gap: 5px; color: #ff6b77; font-weight: 700;">
        🔴 Crveno slovo (Zapovedni praznik / Nedelja)
      </span>
      <span style="display: inline-flex; align-items: center; gap: 5px; color: #fce7b0; font-weight: 700;">
        <strong>+ Crno podebljano (Polijelej)</strong>
      </span>
      <span style="display: inline-flex; align-items: center; gap: 5px; color: #60a5fa;">
        💧 Na vodi
      </span>
      <span style="display: inline-flex; align-items: center; gap: 5px; color: #fbbf24;">
        🫒 Na ulju i vinu
      </span>
      <span style="display: inline-flex; align-items: center; gap: 5px; color: #38bdf8;">
        🐟 Riba
      </span>
      <span style="display: inline-flex; align-items: center; gap: 5px; color: #e2e8f0;">
        🥛 Beli mrs
      </span>
      <span style="display: inline-flex; align-items: center; gap: 5px; color: rgba(255,255,255,0.65);">
        🥩 Nema posta (Mrs)
      </span>
    </div>

    {{-- KALENDAR MREŽA I BOČNI PANEL --}}
    <div class="cal-grid-layout">

      {{-- GLAVNA TABELA MESECA --}}
      <div class="cal-main-table">
        <div class="cal-weekdays-row">
          <div>Pon</div>
          <div>Uto</div>
          <div>Sre</div>
          <div>Čet</div>
          <div>Pet</div>
          <div>Sub</div>
          <div>Ned</div>
        </div>

        <div class="cal-days-grid">
          {{-- Prazne ćelije za početak meseca --}}
          @for($i = 0; $i < $leadingEmpty; $i++)
            <div class="cal-day-cell cal-day-cell--empty"></div>
          @endfor

          {{-- Dani u mesecu --}}
          @for($d = 1; $d <= $daysInMonth; $d++)
            @php
              $row = $byDay->get($d);
              $currentDayDate = $selected->copy()->setDay($d);
              $isToday = $currentDayDate->isSameDay(now());
              $isSelected = $selected->day === $d;

              $isRed = $row ? (bool)$row->is_red_letter : OrthodoxCalendarHelper::isRedLetter($currentDayDate);
              $isBold = $row ? (bool)$row->is_bold_letter : OrthodoxCalendarHelper::isBoldLetter($currentDayDate);

              $fastingInfo = OrthodoxCalendarHelper::formatFasting($row?->fasting_type);
            @endphp

            <a
              class="cal-day-cell {{ $isRed ? 'cal-day-cell--red' : '' }} {{ ($isBold && !$isRed) ? 'cal-day-cell--bold' : '' }} {{ $isToday ? 'cal-day-cell--today' : '' }} {{ $isSelected ? 'cal-day-cell--selected' : '' }}"
              href="{{ route('pravoslavni.kalendar.index', ['date' => $currentDayDate->toDateString()]) }}"
              title="{{ $row?->saint_name ?: ($row?->feast_name ?: 'Dan ' . $d) }} — {{ $fastingInfo['label'] }}"
            >
              {{-- Gornji red ćelije: Broj dana i indikator --}}
              <div class="cal-day-top">
                <span class="cal-day-num {{ $isRed ? 'cal-day-num--red' : '' }} {{ ($isBold && !$isRed) ? 'cal-day-num--bold' : '' }}">
                  {{ $d }}@if($isBold && !$isRed)<span style="font-size: 0.8rem; vertical-align: super;">+</span>@endif
                </span>

                @if($isRed)
                  <span style="font-size: 0.75rem;" title="Crveno slovo / Zapovedni praznik">🔴</span>
                @endif
              </div>

              {{-- Naziv svetitelja / praznika --}}
              <div class="cal-day-saint {{ $isRed ? 'cal-day-saint--red' : '' }} {{ ($isBold && !$isRed) ? 'cal-day-saint--bold' : '' }}">
                @if($row?->feast_name)
                  {{ $row->feast_name }}
                @elseif($row?->saint_name)
                  {{ $row->saint_name }}
                @else
                  —
                @endif
              </div>

              {{-- Bedž pravila posta --}}
              <div class="cal-day-fast-badge" style="background: {{ $fastingInfo['bg'] }}; border: 1px solid {{ $fastingInfo['border'] }}; color: {{ $fastingInfo['color'] }};">
                <span>{{ $fastingInfo['icon'] }}</span>
                <span>{{ $fastingInfo['short'] }}</span>
              </div>
            </a>
          @endfor
        </div>
      </div>

      {{-- BOČNI PANEL: DETALJ IZABRANOG DANA --}}
      <aside class="cal-side-panel">
        
        @php
            $selFasting = OrthodoxCalendarHelper::formatFasting($dayRow?->fasting_type);
            $selIsRed = $dayRow ? (bool)$dayRow->is_red_letter : OrthodoxCalendarHelper::isRedLetter($selected);
            $selIsBold = $dayRow ? (bool)$dayRow->is_bold_letter : OrthodoxCalendarHelper::isBoldLetter($selected);
        @endphp

        <div class="cal-side-card" style="border-color: {{ $selIsRed ? '#e63946' : 'var(--cal-line)' }};">
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 14px; padding-bottom: 12px; border-bottom: 1px solid rgba(197, 162, 74, 0.2);">
            <div>
              <span style="font-size: 0.82rem; font-weight: 700; color: var(--cal-gold); text-transform: uppercase;">
                Izabrani datum:
              </span>
              <h2 style="margin: 2px 0 0; font-size: 1.45rem; color: #fff; font-weight: 800;">
                {{ $selected->translatedFormat('l, d. F Y') }}
              </h2>
            </div>
            @if($selIsRed)
              <span style="padding: 4px 10px; border-radius: 999px; background: rgba(230, 57, 70, 0.2); border: 1px solid #e63946; color: #ff7582; font-size: 0.8rem; font-weight: 800;">
                🔴 Crveno slovo
              </span>
            @elseif($selIsBold)
              <span style="padding: 4px 10px; border-radius: 999px; background: rgba(197, 162, 74, 0.2); border: 1px solid var(--cal-gold); color: var(--cal-gold-bright); font-size: 0.8rem; font-weight: 800;">
                + Crno boldovano
              </span>
            @endif
          </div>

          {{-- STARI KALENDAR --}}
          @if(!empty($dayRow?->old_date))
            <div style="font-size: 0.86rem; color: rgba(255, 255, 255, 0.7); margin-bottom: 14px;">
              📜 Po starom (julijanskom) kalendaru: <strong style="color: #f7e1b5;">{{ $dayRow->old_date }}</strong>
            </div>
          @endif

          {{-- SVETITELJ / PRAZNIK --}}
          <div style="margin-bottom: 16px;">
            <div style="font-size: 0.82rem; font-weight: 700; color: var(--cal-gold); margin-bottom: 4px;">
              СВЕТИТЕЉ / ПРАЗНИК ДАНА:
            </div>
            <div style="font-size: 1.15rem; font-weight: 700; color: {{ $selIsRed ? '#ff8590' : '#fff' }}; line-height: 1.45;">
              {{ $dayRow?->feast_name ?: ($dayRow?->saint_name ?: 'Spomen svetih za današnji dan') }}
            </div>
          </div>

          {{-- PRAVILO POSTA --}}
          <div style="padding: 14px 16px; border-radius: 16px; background: {{ $selFasting['bg'] }}; border: 1px solid {{ $selFasting['border'] }}; margin-bottom: 18px;">
            <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 4px;">
              <span style="font-size: 1.25rem;">{{ $selFasting['icon'] }}</span>
              <strong style="color: {{ $selFasting['color'] }}; font-size: 1rem;">{{ $selFasting['label'] }}</strong>
            </div>
            <p style="margin: 0; font-size: 0.86rem; color: rgba(255, 255, 255, 0.88); line-height: 1.4;">
              {{ $selFasting['desc'] }}
            </p>
          </div>

          @if(!empty($dayRow?->note))
            <div style="padding: 12px 14px; border-radius: 14px; background: rgba(255,255,255,0.04); border: 1px solid rgba(255,255,255,0.1); margin-bottom: 18px;">
              <div style="font-size: 0.8rem; font-weight: 700; color: var(--cal-gold); margin-bottom: 3px;">Napomena:</div>
              <div style="font-size: 0.86rem; color: rgba(255,255,255,0.85); line-height: 1.45;">{{ $dayRow->note }}</div>
            </div>
          @endif

          <a 
            class="btn btn--gold" 
            href="{{ route('pravoslavni.kalendar.show', ['date' => $selected->toDateString()]) }}" 
            style="width: 100%; display: flex; align-items: center; justify-content: center; gap: 6px; padding: 10px 16px; border-radius: 12px; background: linear-gradient(135deg, #c5a24a, #e2c26a); color: #160e0a; font-weight: 800; text-decoration: none;"
          >
            Otvori ceo prikaz dana i pouku →
          </a>
        </div>

        {{-- PREDSTOJEĆIH 7 DANA --}}
        <div class="cal-side-card">
          <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 12px;">
            <h3 style="margin: 0; font-size: 1.05rem; color: var(--cal-gold); font-weight: 700;">
              Predstojećih 7 dana
            </h3>
            <span style="font-size: 0.8rem; color: rgba(255,255,255,0.6);">od {{ $selected->translatedFormat('d.m') }}</span>
          </div>

          <div style="display: flex; flex-direction: column; gap: 8px;">
            @forelse($upcoming as $u)
              @php
                  $uDate = Carbon::parse($u->date);
                  $uFast = OrthodoxCalendarHelper::formatFasting($u->fasting_type);
                  $uIsRed = (bool)$u->is_red_letter;
              @endphp
              <a 
                href="{{ route('pravoslavni.kalendar.index', ['date' => $uDate->toDateString()]) }}" 
                style="display: flex; align-items: center; gap: 12px; padding: 9px 12px; border-radius: 12px; background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.07); text-decoration: none; transition: all 0.2s ease;"
              >
                <div style="font-size: 0.95rem; font-weight: 800; color: {{ $uIsRed ? '#ff6b77' : 'var(--cal-gold)' }}; min-width: 36px;">
                  {{ $uDate->translatedFormat('d.m') }}
                </div>
                <div style="flex: 1; min-width: 0;">
                  <div style="font-size: 0.86rem; font-weight: 700; color: {{ $uIsRed ? '#ff8590' : '#fff' }}; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ $u->feast_name ?: ($u->saint_name ?: '—') }}
                  </div>
                  <div style="font-size: 0.76rem; color: {{ $uFast['color'] }};">
                    {{ $uFast['icon'] }} {{ $uFast['label'] }} @if($uIsRed) • 🔴 Crveno slovo @endif
                  </div>
                </div>
                <div style="color: rgba(255, 255, 255, 0.4); font-size: 0.9rem;">→</div>
              </a>
            @empty
              <div style="font-size: 0.86rem; color: rgba(255,255,255,0.6);">Nema podataka.</div>
            @endforelse
          </div>
        </div>

      </aside>

    </div>

  </div>
</section>
@endsection