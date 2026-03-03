@extends('layouts.site')

@section('title', 'Početna — Pravoslavni Svetionik')
@section('nav_home', 'active')

@section('content')
@php
  /**
   * Podrška za oba slučaja:
   * 1) stari: $today = ['date','feast','fasting','saint','red','note']
   * 2) novi (CSV servis): $calendarToday = ['date','feast','fasting','saints'/'saint','red','note']
   */
  $day = $today ?? ($calendarToday ?? null);

  if (is_array($day)) {
    $day = array_merge([
      'date'    => '—',
      'feast'   => '—',
      'fasting' => '—',
      'saint'   => '—',
      'saints'  => null,
      'red'     => null,
      'note'    => null,
    ], $day);

    // ako iz CSV dođe "saints", prelij u "saint" za prikaz
    if (($day['saint'] ?? '—') === '—' && !empty($day['saints'])) {
      $day['saint'] = $day['saints'];
    }
  } else {
    $day = [
      'date'    => '—',
      'feast'   => '—',
      'fasting' => '—',
      'saint'   => '—',
      'red'     => null,
      'note'    => null,
    ];
  }

  // Fallback kartice (ako nema dovoljno featured)
  $fallback = [
    ['name' => 'Đurđevi Stupovi', 'slug' => null, 'img' => asset('images/sample/djurdjevi.jpg'), 'meta' => '—'],
    ['name' => 'Gračanica',       'slug' => null, 'img' => asset('images/sample/gracanica.jpg'), 'meta' => '—'],
    ['name' => 'Mileševa',        'slug' => null, 'img' => asset('images/sample/mileseva.jpg'), 'meta' => '—'],
    ['name' => 'Sopoćani',        'slug' => null, 'img' => asset('images/sample/sopocani.jpg'), 'meta' => '—'],
    ['name' => 'Studenica',       'slug' => null, 'img' => asset('images/sample/studenica.jpg'), 'meta' => '—'],
    ['name' => 'Žiča',            'slug' => null, 'img' => asset('images/sample/zica.jpg'), 'meta' => '—'],
  ];

  $cards = [];

  if (!empty($featured) && count($featured) > 0) {
    foreach ($featured as $m) {
      $img = !empty($m->image_url) ? $m->image_url : null;

      if (!$img) {
        $img = $fallback[count($cards) % count($fallback)]['img'];
      }

      $meta = trim(($m->region ?? '') . ($m->city ? ' • ' . $m->city : ''));
      if ($meta === '') $meta = '—';

      $cards[] = [
        'name' => $m->name ?? 'Manastir',
        'slug' => $m->slug ?? null,
        'img'  => $img,
        'meta' => $meta,
      ];

      if (count($cards) >= 4) break;
    }
  }

  while (count($cards) < 4) {
    $cards[] = $fallback[count($cards) % count($fallback)];
  }
@endphp

  {{-- HERO --}}
  <section class="hero" aria-label="Uvod">
    <div class="hero__bg" aria-hidden="true"
         style="background-image:url('{{ asset('images/hero/hero1.jpg') }}');"></div>
    <div class="hero__overlay" aria-hidden="true"></div>

    <div class="container hero__inner">
      <div class="hero__content">
        <p class="eyebrow">Digitalni vodič kroz svetinje Srbije</p>
        <h1>Pravoslavni Svetionik</h1>

        <div class="quote" aria-label="Citat dana">
          <div class="quote__title">Citat dana</div>

          <div class="quote__text">
            {{ $quote?->text ?? 'Još nema citata u bazi.' }}
          </div>

          <div class="quote__meta">
            — {{ $quote?->author ?? 'Pravoslavni Svetionik' }}
          </div>
        </div>

        <form class="hero__search" action="{{ route('monasteries.index') }}" method="GET" role="search">
          <label class="sr-only" for="hero-q">Pretraga manastira</label>
          <input id="hero-q" name="q" type="search" placeholder="Pretraga manastira (naziv, grad, region)..." />
          <button type="submit">Pretraži</button>
        </form>
      </div>

      <aside class="hero__aside" aria-label="Danas">
        <div class="asidecard">
          <div class="asidecard__head">
            <div class="asidecard__title">Danas</div>

            @if(!empty($day['red']))
              <div class="asidecard__badge" aria-label="Crveno slovo">Crveno slovo</div>
            @endif
          </div>

          <div class="asidecard__big">
            {{ $day['date'] ?? '—' }}
          </div>

          <div class="asidecard__row">
            <span>Praznik</span>
            <strong>{{ $day['feast'] ?? '—' }}</strong>
          </div>

          <div class="asidecard__row">
            <span>Post</span>
            <strong>{{ $day['fasting'] ?? '—' }}</strong>
          </div>

          <div class="asidecard__row">
            <span>Svetac</span>
            <strong>{{ $day['saint'] ?? '—' }}</strong>
          </div>

          @if(!empty($day['note']))
            <div class="asidecard__note muted">
              {{ $day['note'] }}
            </div>
          @endif
        </div>
      </aside>
    </div>
  </section>

  {{-- Predlog dana --}}
  <section class="section" aria-label="Predlog dana">
    <div class="container">
      <div class="sectionhead">
        <h2>Predlog dana</h2>
        <span class="muted">Izaberi jednu svetinju za posetu ili istraživanje</span>
      </div>

      <div class="cardgrid">
        @foreach($cards as $c)
          @php
            $href  = $c['slug'] ? route('monasteries.show', $c['slug']) : route('monasteries.index');
            $label = $c['slug'] ? "Otvori manastir: {$c['name']}" : "Otvori listu manastira";
          @endphp

          <a class="mcard" href="{{ $href }}" aria-label="{{ $label }}">
            <div class="mcard__img" aria-hidden="true" style="background-image:url('{{ $c['img'] }}');"></div>
            <div class="mcard__shade" aria-hidden="true"></div>

            <div class="mcard__body">
              <div class="mcard__title">{{ $c['name'] }}</div>
              <div class="mcard__meta">{{ $c['meta'] }}</div>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </section>

  {{-- Brzi pristup sekcijama --}}
  <section class="section" aria-label="Brzi pristup">
    <div class="container">
      <div class="sectionhead">
        <h2>Brzi pristup sekcijama</h2>
        <span class="muted">Sve na jednom mestu</span>
      </div>

      <div class="quickgrid">
        <a class="quick" href="{{ route('map.index') }}">
          <div class="quick__icon" aria-hidden="true">🗺️</div>
          <div class="quick__title">Mapa svetinja</div>
          <div class="quick__text">Istraži manastire po lokaciji.</div>
        </a>

        <a class="quick" href="{{ route('ktitors.index') }}">
          <div class="quick__icon" aria-hidden="true">👑</div>
          <div class="quick__title">Ktitori</div>
          <div class="quick__text">Ljudi koji su gradili i darivali.</div>
        </a>

        <a class="quick" href="{{ route('edukacija.index') }}">
          <div class="quick__icon" aria-hidden="true">🎓</div>
          <div class="quick__title">Edukacija</div>
          <div class="quick__text">Kratke lekcije i pojmovi.</div>
        </a>

        <a class="quick" href="{{ route('tours.index') }}">
          <div class="quick__icon" aria-hidden="true">🌐</div>
          <div class="quick__title">360° ture</div>
          <div class="quick__text">Virtuelne posete svetinjama.</div>
        </a>
      </div>
    </div>
  </section>
@endsection