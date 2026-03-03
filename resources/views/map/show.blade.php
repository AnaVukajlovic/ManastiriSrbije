@extends('layouts.site')

@section('title', ($m->name ?? 'Lokacija') . ' — Mapa — Pravoslavni Svetionik')
@section('nav_map', 'active')

@section('content')
  <section class="section">
    <div class="container">

      {{-- Breadcrumb / Back --}}
      <div class="crumbs">
        <a class="crumbs__link" href="{{ route('map.index') }}">← Nazad na mapu</a>
        <span class="crumbs__sep">/</span>
        <span class="crumbs__current">{{ $m->name ?? 'Lokacija' }}</span>
      </div>

      <div class="sectionhead">
        <div>
          <h2>{{ $m->name ?? 'Lokacija' }}</h2>
          <span class="muted">
            @php
              $city = $m->city ?? null;
              $reg  = $m->region ?? null;
            @endphp
            @if($city) {{ $city }} @endif
            @if($city && $reg) <span class="dot">•</span> @endif
            @if($reg) {{ $reg }} @endif
          </span>
        </div>

        <div class="sectionhead__actions">
          @if(!empty($m->lat) && !empty($m->lng))
            <button type="button" class="btn btn--ghost" data-map-action="locate">
              Moja lokacija
            </button>
            <button type="button" class="btn btn--ghost" data-map-action="fit">
              U kadar
            </button>
          @endif

          @if(!empty($m->slug))
            <a class="btn btn--ghost" href="{{ route('monasteries.show', $m->slug) }}">
              Otvori detalje
            </a>
          @endif
        </div>
      </div>

      <div class="mapshow">
        {{-- LEFT: MAP CARD --}}
        <div class="card mapcard mapcard--single">
          <div class="card__header">
            <div class="card__title">
              <h3>Lokacija na mapi</h3>
              <p class="muted">
                @if(!empty($m->lat) && !empty($m->lng))
                  Klikni marker za brze akcije.
                @else
                  Koordinate nisu dostupne za ovu lokaciju.
                @endif
              </p>
            </div>

            @if(!empty($m->lat) && !empty($m->lng))
              <div class="card__tools">
                <button type="button" class="btn btn--soft btn--sm" data-map-action="zoom-in">+</button>
                <button type="button" class="btn btn--soft btn--sm" data-map-action="zoom-out">−</button>
                <button type="button" class="btn btn--soft btn--sm" data-map-action="fit">U kadar</button>
              </div>
            @endif
          </div>

          <div class="card__body mapcard__body">
            <div id="map" class="mapcanvas mapcanvas--single" aria-label="Mapa lokacije"></div>

            @if(empty($m->lat) || empty($m->lng))
              <div class="mapempty">
                <strong>Nema koordinata za prikaz.</strong>
                <span class="muted">Možeš dopuniti koordinate u bazi ili isključiti filter “Sa koordinatama”.</span>
                <a class="btn" href="{{ route('map.index') }}">Nazad na mapu</a>
              </div>
            @endif
          </div>

          @if(!empty($m->lat) && !empty($m->lng))
            <div class="card__footer mapcard__footer">
              <div class="pillrow">
                <span class="pill">Lat: <strong>{{ $m->lat }}</strong></span>
                <span class="pill">Lng: <strong>{{ $m->lng }}</strong></span>

                {{-- Google Maps link (ne zavisi od JS) --}}
                <a class="pill pill--link" target="_blank" rel="noopener"
                   href="https://www.google.com/maps?q={{ $m->lat }},{{ $m->lng }}">
                  Otvori u Google Maps
                </a>
              </div>
            </div>
          @endif
        </div>

        {{-- RIGHT: INFO / ACTIONS --}}
        <div class="stack">
          {{-- HERO INFO CARD --}}
          <div class="card">
            <div class="card__body infobox">
              <div class="infobox__media">
                @php $img = $m->image ?? null; @endphp
                @if($img)
                  <img src="{{ asset($img) }}" alt="{{ $m->name ?? 'Manastir' }}" loading="lazy">
                @else
                  <div class="infobox__placeholder" aria-hidden="true"></div>
                @endif
              </div>

              <div class="infobox__content">
                <div class="infobox__top">
                  <h3 class="infobox__title">{{ $m->name ?? 'Lokacija' }}</h3>
                  <div class="infobox__meta muted">
                    @if(!empty($m->city)) <span>{{ $m->city }}</span> @endif
                    @if(!empty($m->city) && !empty($m->region)) <span class="dot">•</span> @endif
                    @if(!empty($m->region)) <span>{{ $m->region }}</span> @endif
                  </div>
                </div>

                <div class="badges">
                  @if(!empty($m->type))
                    <span class="chip">{{ $m->type }}</span>
                  @endif
                  @if(!empty($m->eparchy))
                    <span class="chip chip--soft">{{ $m->eparchy }}</span>
                  @endif
                  @if(!empty($m->founded_year))
                    <span class="chip chip--ghost">Osnovan: {{ $m->founded_year }}</span>
                  @endif
                </div>

                @if(!empty($m->short_description))
                  <p class="muted">
                    {{ $m->short_description }}
                  </p>
                @else
                  <p class="muted">
                    Kratak opis možeš dodati u bazi (prikaz bude lepši i “master” nivo).
                  </p>
                @endif

                <div class="actions">
                  @if(!empty($m->slug))
                    <a class="btn" href="{{ route('monasteries.show', $m->slug) }}">Detaljna stranica</a>
                  @endif
                  <a class="btn btn--soft" href="{{ route('map.index', ['q' => $m->name ?? null]) }}">
                    Pronađi slične
                  </a>
                  @if(!empty($m->lat) && !empty($m->lng))
                    <button type="button" class="btn btn--ghost" data-map-action="nearby">
                      U okolini
                    </button>
                  @endif
                </div>
              </div>
            </div>
          </div>

          {{-- QUICK FACTS --}}
          <div class="card">
            <div class="card__header">
              <div class="card__title">
                <h3>Brze informacije</h3>
                <p class="muted">Strukturisano, pregledno i “naučno” prezentovano.</p>
              </div>
            </div>

            <div class="card__body facts">
              <div class="facts__grid">
                <div class="fact">
                  <span class="fact__label">Grad</span>
                  <span class="fact__value">{{ $m->city ?? '—' }}</span>
                </div>
                <div class="fact">
                  <span class="fact__label">Region</span>
                  <span class="fact__value">{{ $m->region ?? '—' }}</span>
                </div>
                <div class="fact">
                  <span class="fact__label">Tip</span>
                  <span class="fact__value">{{ $m->type ?? '—' }}</span>
                </div>
                <div class="fact">
                  <span class="fact__label">Eparhija</span>
                  <span class="fact__value">{{ $m->eparchy ?? '—' }}</span>
                </div>
              </div>

              @if(!empty($m->lat) && !empty($m->lng))
                <div class="facts__row muted">
                  Koordinate: <strong>{{ $m->lat }}</strong>, <strong>{{ $m->lng }}</strong>
                </div>
              @endif
            </div>
          </div>

          {{-- CONTEXT LINKS --}}
          <div class="card">
            <div class="card__header">
              <div class="card__title">
                <h3>Povezano</h3>
                <p class="muted">Brz pristup bez gubljenja konteksta.</p>
              </div>
            </div>

            <div class="card__body linkgrid">
              <a class="linkcard" href="{{ route('monasteries.index', ['region' => $m->region ?? null]) }}">
                <div class="linkcard__title">Manastiri u istom regionu</div>
                <div class="linkcard__meta muted">Filter po regionu</div>
              </a>

              <a class="linkcard" href="{{ route('map.index', ['region' => $m->region ?? null]) }}">
                <div class="linkcard__title">Mapa za isti region</div>
                <div class="linkcard__meta muted">Vizuelni pregled</div>
              </a>

              <a class="linkcard" href="{{ route('ktitors.index') }}">
                <div class="linkcard__title">Ktitori</div>
                <div class="linkcard__meta muted">Istorijski kontekst</div>
              </a>
            </div>
          </div>
        </div>
      </div>

    </div>
  </section>

  {{-- DATA za JS --}}
  @push('scripts')
    <script>
      window.MAP_SHOW = {
        point: @json([
          'name' => $m->name ?? null,
          'slug' => $m->slug ?? null,
          'city' => $m->city ?? null,
          'region' => $m->region ?? null,
          'lat' => $m->lat ?? null,
          'lng' => $m->lng ?? null,
          'image' => $m->image ?? null,
        ])
      };
    </script>
    <script src="{{ asset('js/map-show.js') }}"></script>
  @endpush
@endsection