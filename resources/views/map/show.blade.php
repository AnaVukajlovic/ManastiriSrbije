@extends('layouts.site')

@section('title', ($m->name ?? 'Lokacija') . ' — Mapa — Pravoslavni Svetionik')
@section('nav_map', 'active')

@section('content')
<section class="section">
  <div class="container">

    @php
      $name = $m->name ?? 'Lokacija';

      $city = $m->city ?? null;
      $region = $m->region ?? null;

      $lat = $m->lat ?? $m->latitude ?? null;
      $lng = $m->lng ?? $m->longitude ?? null;

      if (is_string($lat)) $lat = str_replace(',', '.', trim($lat));
      if (is_string($lng)) $lng = str_replace(',', '.', trim($lng));

      $hasGeo = ($lat !== null && $lng !== null && $lat !== '' && $lng !== '' && is_numeric($lat) && is_numeric($lng));

      $slug = $m->slug ?? null;
      $type = $m->type ?? null;

      $eparchyValue = null;
      if (!empty($m->eparchy)) {
          $eparchyValue = is_string($m->eparchy) ? $m->eparchy : ($m->eparchy->name ?? null);
      } elseif (!empty($m->eparchy_name)) {
          $eparchyValue = $m->eparchy_name;
      }

      $foundedYear = $m->founded_year ?? $m->year_built ?? $m->godina_izgradnje ?? null;
      $shortDescription = $m->short_description ?? $m->excerpt ?? null;

      $img = $m->image_url ?? $m->image ?? null;
      if ($img) {
          $imgSrc = str_starts_with($img, 'http') ? $img : asset($img);
      } else {
          $imgSrc = null;
      }

      $googleMapsUrl = $hasGeo
          ? 'https://www.google.com/maps?q=' . urlencode(((float)$lat) . ',' . ((float)$lng))
          : 'https://www.google.com/maps?q=' . urlencode(trim($name . ' ' . ($city ?? '') . ' Srbija'));
    @endphp

    <div class="crumbs">
      <a class="crumbs__link" href="{{ route('map.index') }}">← Nazad na mapu</a>
      <span class="crumbs__sep">/</span>
      <span class="crumbs__current">{{ $name }}</span>
    </div>

    <div class="sectionhead">
      <div>
        <h2>{{ $name }}</h2>
        <span class="muted">
          {{ collect([$city, $region])->filter()->join(' • ') ?: 'Lokacija manastira' }}
        </span>
      </div>

      <div class="sectionhead__actions">
        @if($hasGeo)
          <button type="button" class="btn btn--ghost" data-map-action="locate">
            Moja lokacija
          </button>
          <button type="button" class="btn btn--ghost" data-map-action="fit">
            U kadar
          </button>
        @endif

        @if($slug)
          <a class="btn btn--ghost" href="{{ route('monasteries.show', $slug) }}">
            Otvori detalje
          </a>
        @endif
      </div>
    </div>

    <div class="mapshow">
      <div class="card mapcard mapcard--single">
        <div class="card__header">
          <div class="card__title">
            <h3>Lokacija na mapi</h3>
            <p class="muted">
              @if($hasGeo)
                Klikni marker za brze akcije i pregled lokacije.
              @else
                Koordinate trenutno nisu dostupne za ovu lokaciju.
              @endif
            </p>
          </div>

          @if($hasGeo)
            <div class="card__tools">
              <button type="button" class="btn btn--soft btn--sm" data-map-action="zoom-in">+</button>
              <button type="button" class="btn btn--soft btn--sm" data-map-action="zoom-out">−</button>
              <button type="button" class="btn btn--soft btn--sm" data-map-action="fit">U kadar</button>
            </div>
          @endif
        </div>

        <div class="card__body mapcard__body">
          <div id="map" class="mapcanvas mapcanvas--single" aria-label="Mapa lokacije"></div>

          @if(!$hasGeo)
            <div class="mapempty">
              <strong>Nema koordinata za prikaz.</strong>
              <span class="muted">Dopuni lat/lng u bazi kako bi se ova lokacija prikazala na mapi.</span>
              <a class="btn" href="{{ route('map.index') }}">Nazad na mapu</a>
            </div>
          @endif
        </div>

        @if($hasGeo)
          <div class="card__footer mapcard__footer">
            <div class="pillrow">
              <span class="pill">Lat: <strong>{{ $lat }}</strong></span>
              <span class="pill">Lng: <strong>{{ $lng }}</strong></span>
              <a class="pill pill--link" target="_blank" rel="noopener" href="{{ $googleMapsUrl }}">
                Otvori u Google Maps
              </a>
            </div>
          </div>
        @endif
      </div>

      <div class="stack">
        <div class="card">
          <div class="card__body infobox">
            <div class="infobox__media">
              @if($imgSrc)
                <img src="{{ $imgSrc }}" alt="{{ $name }}" loading="lazy">
              @else
                <div class="infobox__placeholder" aria-hidden="true"></div>
              @endif
            </div>

            <div class="infobox__content">
              <div class="infobox__top">
                <h3 class="infobox__title">{{ $name }}</h3>
                <div class="infobox__meta muted">
                  {{ collect([$city, $region])->filter()->join(' • ') ?: 'Nepoznata lokacija' }}
                </div>
              </div>

              <div class="badges">
                @if($type)
                  <span class="chip">{{ $type }}</span>
                @endif

                @if($eparchyValue)
                  <span class="chip chip--soft">{{ $eparchyValue }}</span>
                @endif

                @if($foundedYear)
                  <span class="chip chip--ghost">Osnovan: {{ $foundedYear }}</span>
                @endif
              </div>

              @if(!empty($shortDescription))
                <p class="muted">{{ $shortDescription }}</p>
              @elseif(!empty($m->description))
                <p class="muted">{{ \Illuminate\Support\Str::limit(strip_tags((string)$m->description), 260) }}</p>
              @else
                <p class="muted">
                  Kratak opis još nije dodat za ovu lokaciju.
                </p>
              @endif

              <div class="actions">
                @if($slug)
                  <a class="btn" href="{{ route('monasteries.show', $slug) }}">Detaljna stranica</a>
                @endif

                <a class="btn btn--soft" href="{{ route('map.index', ['q' => $name]) }}">
                  Pronađi slične
                </a>

                @if($hasGeo)
                  <button type="button" class="btn btn--ghost" data-map-action="nearby">
                    U okolini
                  </button>
                @endif
              </div>
            </div>
          </div>
        </div>

        <div class="card">
          <div class="card__header">
            <div class="card__title">
              <h3>Brze informacije</h3>
              <p class="muted">Sažet pregled osnovnih podataka.</p>
            </div>
          </div>

          <div class="card__body facts">
            <div class="facts__grid">
              <div class="fact">
                <span class="fact__label">Grad</span>
                <span class="fact__value">{{ $city ?: '—' }}</span>
              </div>

              <div class="fact">
                <span class="fact__label">Region</span>
                <span class="fact__value">{{ $region ?: '—' }}</span>
              </div>

              <div class="fact">
                <span class="fact__label">Tip</span>
                <span class="fact__value">{{ $type ?: '—' }}</span>
              </div>

              <div class="fact">
                <span class="fact__label">Eparhija</span>
                <span class="fact__value">{{ $eparchyValue ?: '—' }}</span>
              </div>
            </div>

            @if($hasGeo)
              <div class="facts__row muted">
                Koordinate: <strong>{{ $lat }}</strong>, <strong>{{ $lng }}</strong>
              </div>
            @endif
          </div>
        </div>

        <div class="card">
          <div class="card__header">
            <div class="card__title">
              <h3>Povezano</h3>
              <p class="muted">Brz pristup povezanim prikazima.</p>
            </div>
          </div>

          <div class="card__body linkgrid">
            <a class="linkcard" href="{{ route('monasteries.index', ['region' => $region ?: null]) }}">
              <div class="linkcard__title">Manastiri u istom regionu</div>
              <div class="linkcard__meta muted">Filter po regionu</div>
            </a>

            <a class="linkcard" href="{{ route('map.index', ['region' => $region ?: null]) }}">
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

@push('scripts')
<script>
  window.MAP_SHOW = {
    point: @json([
      'name' => $name,
      'slug' => $slug,
      'city' => $city,
      'region' => $region,
      'lat' => $hasGeo ? (float)$lat : null,
      'lng' => $hasGeo ? (float)$lng : null,
      'image' => $imgSrc,
      'url' => $slug ? route('monasteries.show', $slug) : null,
      'google_maps' => $googleMapsUrl,
    ])
  };
</script>
<script src="{{ asset('js/map-show.js') }}"></script>
@endpush
@endsection