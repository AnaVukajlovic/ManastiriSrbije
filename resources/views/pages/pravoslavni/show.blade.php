@extends('layouts.site')

@section('title', ($page['title'] ?? 'Pravoslavni sadržaj') . ' — Pravoslavni Svetionik')
@section('nav_pravoslavni', 'active')

@section('content')
<section class="section ps-show" id="top">
  <div class="container">

    @php
      $pageTitle = $page['title'] ?? 'Pravoslavni sadržaj';
      $pageSubtitle = $page['subtitle'] ?? null;
      $pageCategory = $page['category'] ?? 'Duhovni modul';
      $pageBadge = $page['badge'] ?? null;
      $sections = $page['sections'] ?? [];
      $todayTitle = $day?->saint_name ?: ($day?->feast_name ?: '—');
      $todayFast = $day?->fasting_type ?: 'Nema posta';

      $moduleLinks = [
        'osnovni-koncepti' => ['label' => 'Osnovni koncepti vere', 'icon' => '📘'],
        'kalendar' => ['label' => 'Pravoslavni kalendar', 'icon' => '🗓️'],
        'zanimljivosti' => ['label' => 'Zanimljivosti', 'icon' => '✨'],
        'datum-vaskrsa' => ['label' => 'Datum Vaskrsa', 'icon' => '✝️'],
        'posni-recepti' => ['label' => 'Posni recepti', 'icon' => '🥗'],
      ];
    @endphp

    <nav class="ps-bc" aria-label="Breadcrumb">
      <a class="ps-bc__link" href="{{ route('pravoslavni.index') }}">Pravoslavni sadržaj</a>
      <span class="ps-bc__sep">/</span>
      <span class="ps-bc__current">{{ $pageTitle }}</span>
    </nav>

    <header class="ps-showhero">
      <div class="ps-showhero__inner">

        <div class="ps-showhero__top">
          <div class="ps-showhead__eyebrow">
            <span class="ps-pill">{{ $pageCategory }}</span>
            @if($pageBadge)
              <span class="ps-pill ps-pill--gold">{{ $pageBadge }}</span>
            @endif
          </div>

          <div class="ps-miniwrap">
            <div class="ps-minihead">
              <span class="ps-ico">🕯️</span>
              <div>
                <div class="ps-minititle">Danas</div>
                <div class="ps-minisub">{{ $today->format('d.m.Y') }}</div>
              </div>
            </div>

            <div class="ps-minibody">
              <div class="ps-miniline">
                <span class="muted">Svetitelj:</span>
                <strong>{{ $todayTitle }}</strong>
              </div>

              <div class="ps-miniline">
                <span class="muted">Post:</span>
                <strong>{{ $todayFast }}</strong>
              </div>

              @if(!empty($day?->is_red_letter))
                <div class="ps-miniline">
                  <span class="ps-badge ps-badge--gold">Crveno slovo</span>
                </div>
              @endif
            </div>
          </div>
        </div>

        <h1 class="ps-showtitle">{{ $pageTitle }}</h1>

        @if($pageSubtitle)
          <p class="ps-showsub">{{ $pageSubtitle }}</p>
        @endif

        <div class="ps-herorow">
          <span class="ps-heropill">⛪ Duhovni modul</span>
          <span class="ps-heropill">📌 Jasno i pregledno</span>
          <span class="ps-heropill">🕊️ Korak po korak</span>
        </div>
      </div>
    </header>

    <div class="ps-showgrid">

      <article class="ps-article">

        @if(!empty($page['intro']))
          <div class="ps-intro">
            <div class="ps-intro__label">Ukratko</div>
            <div class="ps-intro__text">{!! nl2br(e($page['intro'])) !!}</div>
          </div>
        @endif

        @if(!empty($sections) && is_array($sections))
          @foreach($sections as $i => $sec)
            <section class="ps-asec" id="sec-{{ $i + 1 }}">
              <div class="ps-asec__head">
                <h2>{{ $sec['title'] ?? '' }}</h2>
                <span class="ps-asec__n">{{ $i + 1 }}</span>
              </div>

              @if(!empty($sec['text']))
                <div class="ps-ap">
                  {!! nl2br(e($sec['text'])) !!}
                </div>
              @endif

              @if(!empty($sec['bullets']) && is_array($sec['bullets']))
                <ul class="ps-ul">
                  @foreach($sec['bullets'] as $b)
                    <li>{{ $b }}</li>
                  @endforeach
                </ul>
              @endif

              @if(!empty($sec['note']))
                <div class="ps-callout ps-callout--gold">
                  <span class="ps-callout__ico">💡</span>
                  <div class="ps-callout__text">{!! nl2br(e($sec['note'])) !!}</div>
                </div>
              @endif
            </section>
          @endforeach
        @else
          <section class="ps-asec">
            <div class="ps-asec__head">
              <h2>U pripremi</h2>
              <span class="ps-asec__n">…</span>
            </div>
            <p class="ps-ap muted">
              Ovaj modul se trenutno dopunjava. Uskoro dodajemo potpun sadržaj.
            </p>
          </section>
        @endif

        @if(!empty($page['quote']))
          <blockquote class="ps-quote ps-quote--big">
            <span class="ps-quote__mark">„</span>
            <span class="ps-quote__text">{{ $page['quote'] }}</span>
            <span class="ps-quote__mark">“</span>
            @if(!empty($page['quote_by']))
              <div class="ps-quote__by">— {{ $page['quote_by'] }}</div>
            @endif
          </blockquote>
        @endif

      </article>

      <aside class="ps-side">

        <div class="ps-box">
          <div class="ps-box__title">Brzi pristup</div>

          <div class="ps-sidelinks">
            <a class="ps-sidelink" href="{{ route('pravoslavni.index') }}">
              <span class="ps-sidelink__ico">🏠</span>
              <span>Početna pravoslavnog sadržaja</span>
              <span class="ps-sidelink__arr">→</span>
            </a>

            @foreach($moduleLinks as $slug => $item)
              <a class="ps-sidelink" href="{{ route('pravoslavni.show', $slug) }}">
                <span class="ps-sidelink__ico">{{ $item['icon'] }}</span>
                <span>{{ $item['label'] }}</span>
                <span class="ps-sidelink__arr">→</span>
              </a>
            @endforeach
          </div>
        </div>

        <div class="ps-box">
          <div class="ps-box__title">Napomena</div>
          <div class="ps-sidep muted">
            Sadržaj je informativnog karaktera. Za lično duhovno rukovođenje najbolje je obratiti se svom parohijskom svešteniku.
          </div>
        </div>

        @if(!empty($sections) && is_array($sections))
          <div class="ps-box ps-box--toc">
            <div class="ps-box__title">Sadržaj</div>
            <div class="ps-tocmini">
              @foreach($sections as $i => $sec)
                <a class="ps-tocmini__a" href="#sec-{{ $i + 1 }}">
                  <span class="ps-tocmini__n">{{ $i + 1 }}</span>
                  <span class="ps-tocmini__t">{{ $sec['title'] ?? ('Sekcija ' . ($i + 1)) }}</span>
                </a>
              @endforeach
            </div>
          </div>
        @endif

        <div class="ps-box">
          <div class="ps-box__title">Povratak</div>
          <div class="ps-sidelinks">
            <a class="ps-sidelink" href="#top">
              <span class="ps-sidelink__ico">↑</span>
              <span>Na vrh stranice</span>
              <span class="ps-sidelink__arr">→</span>
            </a>
          </div>
        </div>

      </aside>
    </div>

  </div>
</section>
@endsection