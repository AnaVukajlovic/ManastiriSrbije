@extends('layouts.site')

@section('title', ($monastery->name ?? 'Manastir') . ' — Pravoslavni Svetionik')
@section('nav_monasteries', 'active')

@php
  // ✅ Primarno: lokalna slika po slug-u
  $img = asset('images/monasteries/' . ($monastery->slug ?? 'placeholder') . '.jpg');

  // ✅ Fallback: placeholder
  $fallbackImg = asset('images/monasteries/placeholder.jpg');

  $p = $monastery->profile;

  // Sidra samo za sekcije koje postoje
  $sections = [
    ['id' => 'uvod', 'label' => 'Uvod', 'show' => !empty($p?->intro)],
    ['id' => 'istorija', 'label' => 'Istorija', 'show' => !empty($p?->history)],
    ['id' => 'arhitektura', 'label' => 'Arhitektura', 'show' => !empty($p?->architecture)],
    ['id' => 'ktitor', 'label' => 'Ktitor', 'show' => !empty($p?->ktitor_text)],
    ['id' => 'freske', 'label' => 'Umetnost i freske', 'show' => !empty($p?->art_frescoes)],
    ['id' => 'zanimljivosti', 'label' => 'Zanimljivosti', 'show' => !empty($p?->interesting_facts)],
  ];
@endphp

@section('content')
<section class="section monPro">
  <div class="container">

    <a class="btn2 monPro__back" href="{{ route('monasteries.index') }}">← Nazad na listu</a>

    {{-- HERO --}}
    <div class="monHero">
      <div
        class="monHero__media"
        style="background-image:url('{{ $img }}');"
        role="img"
        aria-label="Fotografija manastira {{ $monastery->name }}"
        data-fallback="{{ $fallbackImg }}"
      ></div>

      <div class="monHero__overlay">
        <div class="monHero__titleWrap">
          <h1 class="monHero__title">{{ $monastery->name ?? 'Manastir' }}</h1>

          <div class="monHero__meta">
            <span class="tag">{{ $monastery->region ?: '—' }}</span>
            @if(!empty($monastery->city))
              <span class="tag">{{ $monastery->city }}</span>
            @endif
            @if(!empty($monastery->eparchy?->name))
              <span class="tag">{{ $monastery->eparchy->name }}</span>
            @endif
          </div>

          {{-- Kratak pregled --}}
          <p class="monHero__lead">
            @if(!empty($monastery->excerpt))
              {{ $monastery->excerpt }}
            @elseif(!empty($p?->intro))
              {{ $p->intro }}
            @else
              Opis još nije dodat.
            @endif
          </p>

          {{-- CTA dugmad --}}
          <div class="monHero__cta">
            @if(!empty($monastery->lat) && !empty($monastery->lng))
              <a class="btn2" target="_blank" rel="noopener"
                 href="https://www.google.com/maps?q={{ $monastery->lat }},{{ $monastery->lng }}">
                Otvori na mapi
              </a>
            @endif

            <a class="btn2 btn2--ghost" href="#sadrzaj">Čitaj dalje</a>
          </div>
        </div>
      </div>
    </div>

    {{-- GRID: content + sidebar --}}
    <div class="monGrid" id="sadrzaj">

      {{-- CONTENT --}}
      <div class="monMain card">
        {{-- mini TOC za mobilni --}}
        <div class="monTocMobile">
          <div class="muted monTocMobile__label">Sadržaj</div>
          <div class="monTocMobile__links">
            @foreach($sections as $s)
              @if($s['show'])
                <a href="#{{ $s['id'] }}">{{ $s['label'] }}</a>
              @endif
            @endforeach
          </div>
        </div>

        @if(!$p)
          <p class="muted">Detaljan tekst za ovu svetinju još nije dodat.</p>
        @else

          @if(!empty($p->intro))
            <article class="monSec" id="uvod">
              <h2>Uvod</h2>
              <p>{{ $p->intro }}</p>
            </article>
          @endif

          @if(!empty($p->history))
            <article class="monSec" id="istorija">
              <h2>Istorija</h2>
              <p>{{ $p->history }}</p>
            </article>
          @endif

          @if(!empty($p->architecture))
            <article class="monSec" id="arhitektura">
              <h2>Arhitektura</h2>
              <p>{{ $p->architecture }}</p>
            </article>
          @endif

          @if(!empty($p->ktitor_text))
            <article class="monSec" id="ktitor">
              <h2>Ktitor</h2>
              <p>{{ $p->ktitor_text }}</p>
            </article>
          @endif

          @if(!empty($p->art_frescoes))
            <article class="monSec" id="freske">
              <h2>Umetnost i freske</h2>
              <p>{{ $p->art_frescoes }}</p>
            </article>
          @endif

          @if(!empty($p->interesting_facts))
            <article class="monSec" id="zanimljivosti">
              <h2>Zanimljivosti</h2>
              <p>{{ $p->interesting_facts }}</p>
            </article>
          @endif

        @endif

        {{-- izvori (ako ti sources_json bude array cast) --}}
        @php
          $sources = $p?->sources_json;
          if (is_string($sources)) {
            $decoded = json_decode($sources, true);
            if (json_last_error() === JSON_ERROR_NONE) $sources = $decoded;
          }
        @endphp

        @if(!empty($sources) && is_array($sources))
          <div class="monSources">
            <h3>Izvori</h3>
            <ul>
              @foreach($sources as $src)
                @php
                  $title = $src['title'] ?? 'Izvor';
                  $url = $src['url'] ?? null;
                @endphp
                <li>
                  @if($url)
                    <a href="{{ $url }}" target="_blank" rel="noopener">{{ $title }}</a>
                  @else
                    {{ $title }}
                  @endif
                </li>
              @endforeach
            </ul>
          </div>
        @endif
      </div>

      {{-- SIDEBAR --}}
      <aside class="monSide">
        <div class="card monSide__card">
          <h3 class="monSide__title">Informacije</h3>

          <div class="monKV">
            <div class="monKV__row">
              <div class="monKV__k">Region</div>
              <div class="monKV__v">{{ $monastery->region ?: '—' }}</div>
            </div>

            <div class="monKV__row">
              <div class="monKV__k">Grad</div>
              <div class="monKV__v">{{ $monastery->city ?: '—' }}</div>
            </div>

            @if(!empty($monastery->eparchy?->name))
              <div class="monKV__row">
                <div class="monKV__k">Eparhija</div>
                <div class="monKV__v">{{ $monastery->eparchy->name }}</div>
              </div>
            @endif

            @if(!empty($monastery->address))
              <div class="monKV__row">
                <div class="monKV__k">Adresa</div>
                <div class="monKV__v">{{ $monastery->address }}</div>
              </div>
            @endif
          </div>

          <div class="monSide__actions">
            @if(!empty($monastery->lat) && !empty($monastery->lng))
              <a class="btn2 btn2--wide" target="_blank" rel="noopener"
                 href="https://www.google.com/maps?q={{ $monastery->lat }},{{ $monastery->lng }}">
                Navigacija
              </a>
            @endif

            @if(!empty($monastery->source_url))
              <a class="btn2 btn2--ghost btn2--wide" target="_blank" rel="noopener"
                 href="{{ $monastery->source_url }}">
                Pročitaj više
              </a>
            @endif
          </div>

          {{-- Desktop sadržaj (sticky) --}}
          <div class="monTocDesktop">
            <div class="muted monTocDesktop__label">Sadržaj</div>
            <ul class="monTocDesktop__list">
              @foreach($sections as $s)
                @if($s['show'])
                  <li><a href="#{{ $s['id'] }}">{{ $s['label'] }}</a></li>
                @endif
              @endforeach
            </ul>
          </div>
        </div>
      </aside>

    </div>

    {{-- ✅ JS fallback za HERO background-image --}}
    <script>
      (function () {
        const el = document.querySelector('.monHero__media[data-fallback]');
        if (!el) return;

        const bg = getComputedStyle(el).backgroundImage || '';
        const match = bg.match(/url\(["']?(.*?)["']?\)/);
        const imgUrl = match ? match[1] : null;
        if (!imgUrl) return;

        const test = new Image();
        test.onload = () => {};
        test.onerror = () => {
          const fb = el.getAttribute('data-fallback');
          if (fb) el.style.backgroundImage = `url('${fb}')`;
        };
        test.src = imgUrl;
      })();
    </script>

  </div>
</section>
@endsection