@extends('layouts.site')

@section('title', 'Crkveni kalendar — ' . $selected->translatedFormat('d. F Y') . ' — Pravoslavni Svetionik')
@section('nav_pravoslavni', 'active')

@section('content')
<style>
  .cal-show-page {
    --cs-gold: #c5a24a;
    --cs-gold-bright: #e2c26a;
    --cs-red: #e63946;
    --cs-card-bg: linear-gradient(180deg, rgba(28, 18, 16, 0.98), rgba(16, 10, 10, 0.98));
    --cs-line: rgba(197, 162, 74, 0.25);
    max-width: 1320px;
    margin: 0 auto;
  }

  .cal-show-grid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 24px;
  }

  .cal-show-card {
    background: var(--cs-card-bg);
    border: 1.5px solid var(--cs-line);
    border-radius: 26px;
    padding: 30px 34px;
    box-shadow: 0 16px 40px rgba(0,0,0,0.5);
  }

  @media (max-width: 1024px) {
    .cal-show-grid { grid-template-columns: 1fr; }
    .cal-show-card { padding: 22px 20px; }
  }
</style>

@php
    use App\Support\OrthodoxCalendarHelper;
    use Carbon\Carbon;

    $isRed = $row ? (bool)$row->is_red_letter : OrthodoxCalendarHelper::isRedLetter($selected);
    $isBold = $row ? (bool)$row->is_bold_letter : OrthodoxCalendarHelper::isBoldLetter($selected);
    $fastingInfo = OrthodoxCalendarHelper::formatFasting($row?->fasting_type);
@endphp

<section class="section cal-show-page">
  <div class="container">

    {{-- BREADCRUMBS --}}
    <div class="ps-bc" style="margin-bottom: 16px;">
      <a class="ps-bc__link" href="{{ route('pravoslavni.index') }}">Pravoslavni sadržaj</a>
      <span class="ps-bc__sep">/</span>
      <a class="ps-bc__link" href="{{ route('pravoslavni.kalendar.index', ['date' => $selected->toDateString()]) }}">Kalendar</a>
      <span class="ps-bc__sep">/</span>
      <span class="ps-bc__current">{{ $selected->translatedFormat('d.m.Y') }}</span>
    </div>

    {{-- HEADER SA NAVIGACIJOM DANA --}}
    <div class="cal-show-card" style="margin-bottom: 24px; padding: 24px 30px;">
      <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;">
        <div>
          <div style="display: flex; align-items: center; gap: 8px; margin-bottom: 6px;">
            <span style="font-size: 1.4rem;">☦️</span>
            <span style="font-size: 0.86rem; font-weight: 700; color: var(--cs-gold); text-transform: uppercase; letter-spacing: 0.5px;">
              Pravoslavni kalendar SPC
            </span>
          </div>
          <h1 style="margin: 0; font-size: clamp(1.8rem, 3.5vw, 2.5rem); color: var(--cs-gold-bright); font-weight: 800;">
            {{ $selected->translatedFormat('l, d. F Y') }}
          </h1>
          @if(!empty($row?->old_date))
            <p style="margin: 4px 0 0; font-size: 0.95rem; color: #f7e1b5;">
              📜 Po julijanskom (starom) kalendaru: <strong>{{ $row->old_date }}</strong>
            </p>
          @endif
        </div>

        {{-- NAVIGACIJA PREV / NAZAD / NEXT --}}
        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
          <a class="btn2 btn2--ghost" href="{{ route('pravoslavni.kalendar.show', ['date' => $prev->toDateString()]) }}">
            ← Prethodni dan
          </a>
          <a class="btn btn--gold" href="{{ route('pravoslavni.kalendar.index', ['date' => $selected->toDateString()]) }}" style="background: linear-gradient(135deg, #c5a24a, #e2c26a); color: #160e0a; font-weight: 800;">
            📅 Mesečni kalendar
          </a>
          <a class="btn2 btn2--ghost" href="{{ route('pravoslavni.kalendar.show', ['date' => $next->toDateString()]) }}">
            Sledeći dan →
          </a>
        </div>
      </div>
    </div>

    {{-- GLAVNI SADRŽAJ I SIDEBAR --}}
    <div class="cal-show-grid">

      {{-- LEVA GLAVNA KARTICA SA DETALJIMA --}}
      <article class="cal-show-card">
        
        {{-- STATUS PRAZNIKA & BEDŽEVI --}}
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 10px; margin-bottom: 20px; padding-bottom: 14px; border-bottom: 1px solid rgba(197, 162, 74, 0.2);">
          <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
            @if($isRed)
              <span style="padding: 6px 14px; border-radius: 999px; background: rgba(230, 57, 70, 0.25); border: 1px solid #e63946; color: #ff8590; font-size: 0.88rem; font-weight: 800;">
                🔴 Crveno slovo — Zapovedni praznik
              </span>
            @elseif($isBold)
              <span style="padding: 6px 14px; border-radius: 999px; background: rgba(197, 162, 74, 0.2); border: 1px solid var(--cs-gold); color: var(--cs-gold-bright); font-size: 0.88rem; font-weight: 800;">
                + Crno podebljano slovo (Bdenije / Polijelej)
              </span>
            @else
              <span style="padding: 6px 14px; border-radius: 999px; background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.15); color: rgba(255, 255, 255, 0.85); font-size: 0.86rem; font-weight: 600;">
                Spomen svetih
              </span>
            @endif

            <span style="padding: 6px 14px; border-radius: 999px; background: {{ $fastingInfo['bg'] }}; border: 1px solid {{ $fastingInfo['border'] }}; color: {{ $fastingInfo['color'] }}; font-size: 0.88rem; font-weight: 800;">
              {{ $fastingInfo['icon'] }} {{ $fastingInfo['label'] }}
            </span>
          </div>

          <span style="font-size: 0.84rem; color: rgba(255, 255, 255, 0.6);">
            Dan {{ $selected->dayOfYear }} u godini
          </span>
        </div>

        {{-- NAZIV SVETITELJA / PRAZNIKA --}}
        <div style="margin-bottom: 28px;">
          <span style="font-size: 0.84rem; font-weight: 700; color: var(--cs-gold); text-transform: uppercase; letter-spacing: 0.5px;">
            СПОМЕН СВЕТИТЕЉА И ПРАЗНИКА ЗА ДАНАШЊИ ДАН:
          </span>
          <h2 style="margin: 8px 0 0; font-size: clamp(1.4rem, 2.5vw, 1.85rem); color: {{ $isRed ? '#ff8590' : '#fff' }}; font-weight: 800; line-height: 1.4;">
            {{ $row?->feast_name ?: ($row?->saint_name ?: 'Spomen svetih za današnji dan') }}
          </h2>
        </div>

        {{-- PRAVILO POSTA I TRENUTNI TIPIK --}}
        <div style="padding: 20px 24px; border-radius: 20px; background: {{ $fastingInfo['bg'] }}; border: 1.5px solid {{ $fastingInfo['border'] }}; margin-bottom: 28px;">
          <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 8px;">
            <span style="font-size: 1.6rem;">{{ $fastingInfo['icon'] }}</span>
            <h3 style="margin: 0; font-size: 1.15rem; color: {{ $fastingInfo['color'] }}; font-weight: 800;">
              Pravilo posta prema Tipiku SPC: {{ $fastingInfo['label'] }}
            </h3>
          </div>
          <p style="margin: 0; font-size: 0.95rem; color: rgba(255, 255, 255, 0.92); line-height: 1.6; text-align: justify;">
            {{ $fastingInfo['desc'] }}
            @if($fastingInfo['type'] === 'mrs')
              Danas nema posta — vernicima je dozvoljena uobičajena hrana (mrs).
            @elseif($fastingInfo['type'] === 'voda')
              Po crkvenom pravilu, hrana se priprema isključivo na vodi, bez dodavanja biljnog ulja i bez ribe.
            @elseif($fastingInfo['type'] === 'ulje')
              Dozvoljena je hrana pripremljena na jestivom biljnom ulju, kao i umereno konzumiranje vina.
            @elseif($fastingInfo['type'] === 'riba')
              Dozvoljeno je konzumiranje ribe, biljnog ulja i vina (praznično razrešenje posta).
            @elseif($fastingInfo['type'] === 'beli_mrs')
              Tokom ove sedmice razrešava se upotreba sira, mleka i jaja, dok se meso ne upotrebljava.
            @endif
          </p>
        </div>

        @if(!empty($row?->note))
          <div style="padding: 16px 20px; border-radius: 18px; background: rgba(255, 255, 255, 0.04); border: 1px solid rgba(197, 162, 74, 0.25); margin-bottom: 28px;">
            <div style="font-size: 0.86rem; font-weight: 700; color: var(--cs-gold); margin-bottom: 4px;">🕯️ Crkvena napomena:</div>
            <p style="margin: 0; font-size: 0.92rem; color: rgba(255, 255, 255, 0.88); line-height: 1.5;">{{ $row->note }}</p>
          </div>
        @endif

        {{-- DUHOVNA POUKA --}}
        <div style="padding: 22px 26px; border-radius: 20px; background: rgba(18, 12, 10, 0.9); border: 1px solid rgba(197, 162, 74, 0.3); margin-bottom: 24px;">
          <h3 style="margin: 0 0 10px; font-size: 1.1rem; color: var(--cs-gold); font-weight: 700;">
            📖 Duhovna pouka Svetih Otaca
          </h3>
          <p style="margin: 0 0 10px; font-size: 0.95rem; color: rgba(255, 255, 255, 0.9); line-height: 1.7; font-style: italic; text-align: justify;">
            „Prava mera posta ne sastoji se samo u uzdržavanju od mrsne hrane, već u uzdržavanju jezika od praznoslovlja, 
            srca od zlobe i uma od rđavih misli. Post koji Bog prima jeste onaj koji je spojen sa milosrđem, praštanjem i iskrenom molitvom.”
          </p>
          <span style="font-size: 0.82rem; color: #c5a24a; font-weight: 600;">— Sveti Jovan Zlatousti</span>
        </div>

        {{-- ZVANIČNI IZVOR --}}
        <div style="padding-top: 16px; border-top: 1px solid rgba(197, 162, 74, 0.2); font-size: 0.84rem; color: rgba(255, 255, 255, 0.65);">
          🏛️ <strong>Izvor:</strong> Zvanični bogoslužbeni crkveni kalendar i Tipik Srpske Pravoslavne Crkve (SPC).
        </div>

      </article>

      {{-- DESNI PANEL (SEDMIČNI PREGLED) --}}
      <aside style="display: flex; flex-direction: column; gap: 20px;">
        
        <div class="cal-show-card" style="padding: 22px 24px;">
          <h3 style="margin: 0 0 14px; font-size: 1.1rem; color: var(--cs-gold); font-weight: 700;">
            Ove sedmice (7 dana)
          </h3>

          <div style="display: flex; flex-direction: column; gap: 8px;">
            @foreach($week as $w)
              @php
                  $wDate = Carbon::parse($w->date);
                  $wIsRed = (bool)$w->is_red_letter;
                  $wFast = OrthodoxCalendarHelper::formatFasting($w->fasting_type);
                  $isCurrent = $wDate->isSameDay($selected);
              @endphp
              <a 
                href="{{ route('pravoslavni.kalendar.show', ['date' => $wDate->toDateString()]) }}" 
                style="display: flex; align-items: center; gap: 12px; padding: 10px 12px; border-radius: 12px; background: {{ $isCurrent ? 'rgba(197, 162, 74, 0.22)' : 'rgba(255, 255, 255, 0.03)' }}; border: 1px solid {{ $isCurrent ? 'var(--cs-gold-bright)' : 'rgba(255, 255, 255, 0.07)' }}; text-decoration: none; transition: all 0.2s ease;"
              >
                <div style="font-size: 0.95rem; font-weight: 800; color: {{ $wIsRed ? '#ff6b77' : 'var(--cs-gold)' }}; min-width: 36px;">
                  {{ $wDate->translatedFormat('d.m') }}
                </div>
                <div style="flex: 1; min-width: 0;">
                  <div style="font-size: 0.85rem; font-weight: 700; color: {{ $wIsRed ? '#ff8590' : '#fff' }}; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                    {{ $w->feast_name ?: ($w->saint_name ?: '—') }}
                  </div>
                  <div style="font-size: 0.76rem; color: {{ $wFast['color'] }};">
                    {{ $wFast['icon'] }} {{ $wFast['label'] }} @if($wIsRed) • 🔴 Crveno @endif
                  </div>
                </div>
                <div style="color: rgba(255, 255, 255, 0.4); font-size: 0.9rem;">→</div>
              </a>
            @endforeach
          </div>
        </div>

        <div class="cal-show-card" style="padding: 22px 24px;">
          <h3 style="margin: 0 0 10px; font-size: 1.05rem; color: var(--cs-gold); font-weight: 700;">
            🕊️ Priprema za Pričešće
          </h3>
          <p style="margin: 0; font-size: 0.88rem; color: rgba(255, 255, 255, 0.8); line-height: 1.6; text-align: justify;">
            Telesni post je priprema za primanje Svetih Tajni, a suština leži u čistoti srca, iskrenom pokajanju i izmirenju sa svim bližnjima pre stupanja pred Čašu spasenja.
          </p>
        </div>

      </aside>

    </div>

  </div>
</section>
@endsection