@extends('layouts.site')

@section('title', 'Mapa — Pravoslavni Svetionik')
@section('nav_map', 'active')

@section('content')
<section class="section">
  <div class="container">

    <div class="sectionhead">
      <div>
        <h2>Mapa svetinja</h2>
        <span class="muted">Pregled manastira na mapi, pretraga i brzi fokus na lokacije.</span>
      </div>

      <div class="sectionhead__actions">
        <button type="button" class="btn btn--ghost" data-map-action="legend">Legenda</button>
        <button type="button" class="btn btn--ghost" data-map-action="locate">Moja lokacija</button>
        <a class="btn btn--ghost" href="{{ route('map.index') }}">Reset</a>
      </div>
    </div>

    {{-- DEBUG (po želji obriši kad završiš) --}}
    <!-- <div style="margin:10px 0; padding:10px; border:1px solid #ddd; border-radius:12px; font-size:14px">
      <strong>DEBUG</strong> —
      total: {{ $total ?? 'n/a' }},
      geo_total: {{ $geo_total ?? 'n/a' }},
      regions: {{ isset($regions) ? count($regions) : 'n/a' }},
      eparchies: {{ isset($eparchies) ? count($eparchies) : 'n/a' }},
      monasteries: {{ isset($monasteries) ? count($monasteries) : 'n/a' }}
      <br>
      q={{ request('q') }},
      region={{ request('region') }},
      eparchy={{ request('eparchy') }},
      only_geo={{ request('only_geo') }}
    </div> -->

    <form id="mapFilters" class="filters" method="GET" action="{{ route('map.index') }}">
      <div class="filters__row">
        <div class="filters__field">
          <label class="sr-only" for="q">Pretraga</label>
          <input id="q" name="q" type="search"
            value="{{ $q ?? request('q') }}"
            placeholder="Naziv, grad, region, eparhija..."
            autocomplete="off" />
        </div>

        <div class="filters__field">
          <label class="sr-only" for="region">Region</label>
          <select id="region" name="region">
            <option value="">Svi regioni</option>
            @foreach(($regions ?? []) as $r)
              <option value="{{ $r }}" @selected(($region ?? request('region')) === $r)>{{ $r }}</option>
            @endforeach
          </select>
        </div>

        <div class="filters__field">
          <label class="sr-only" for="eparchy">Eparhija</label>
          <select id="eparchy" name="eparchy">
            <option value="">Sve eparhije</option>
            @foreach(($eparchies ?? []) as $ep)
              <option value="{{ $ep->slug }}" @selected(($eparchy ?? request('eparchy')) === $ep->slug)>{{ $ep->name }}</option>
            @endforeach
          </select>
        </div>

        <div class="filters__actions">
          <button class="btn" type="submit">Primeni</button>
          <a class="btn btn--soft" href="{{ route('map.index') }}">Očisti</a>
        </div>
      </div>

      <div class="filters__row filters__row--meta">
        <div class="muted">
          Prikazano: <strong>{{ $total ?? 0 }}</strong> rezultata
          <span class="dot">•</span>
          Sa koordinatama: <strong>{{ $geo_total ?? 0 }}</strong>
          <span class="dot">•</span>
          Savet: klik na “Prikaži na mapi” fokusira marker.

          @if(($eparchy_name ?? null))
            <span class="dot">•</span>
            Eparhija: <strong>{{ $eparchy_name }}</strong>
          @endif
        </div>

        <div class="filters__toggles">
          <label class="toggle">
            <input id="only_geo" type="checkbox" name="only_geo" value="1" @checked((request('only_geo') ?? null) == 1) />
            <span>Sa koordinatama</span>
          </label>

          <label class="toggle">
            <input type="checkbox" name="cluster" value="1" @checked((request('cluster') ?? 1) == 1) />
            <span>Grupiši markere</span>
          </label>
        </div>
      </div>
    </form>

    <div class="maplayout">
      <div class="card mapcard">
        <div class="card__header">
          <div class="card__title">
            <h3>Interaktivna mapa</h3>
            <p class="muted">Pomeraj, zumiraj, klikni marker za detalje.</p>
          </div>
          <div class="card__tools">
            <button type="button" class="btn btn--soft btn--sm" data-map-action="zoom-in">+</button>
            <button type="button" class="btn btn--soft btn--sm" data-map-action="zoom-out">−</button>
            <button type="button" class="btn btn--soft btn--sm" data-map-action="fit">U kadar</button>
          </div>
        </div>

        <div class="card__body mapcard__body">
          <div id="map" class="mapcanvas" aria-label="Mapa manastira"></div>

          @if(($geo_total ?? 0) == 0)
            <div class="muted" style="margin-top:10px">
              Trenutno nema koordinata u bazi (latitude/longitude su prazni), zato nema markera na mapi.
              Lista i filteri rade, a “Google Maps” dugme otvara lokaciju kroz pretragu.
            </div>
          @endif

          <div class="maplegend" data-map-legend hidden>
            <div class="maplegend__inner">
              <div class="maplegend__head">
                <strong>Legenda</strong>
                <button type="button" class="btn btn--ghost btn--sm" data-map-action="legend-close">Zatvori</button>
              </div>
              <ul class="maplegend__list">
                <li><span class="badge badge--primary"></span> Manastir</li>
                <li><span class="badge badge--soft"></span> Više lokacija (cluster)</li>
                <li><span class="badge badge--accent"></span> Tvoja lokacija</li>
              </ul>
              <p class="muted maplegend__note">
                Ako nema markera, proveri filtere ili uključi “Sa koordinatama”.
              </p>
            </div>
          </div>

        </div>
      </div>

      <div class="card maplist">
        <div class="card__header">
          <div class="card__title">
            <h3>Rezultati</h3>
            <p class="muted">Klik na “Prikaži” fokusira tačku na mapi.</p>
          </div>

          <div class="card__tools">
            <div class="inputicon">
              <span class="inputicon__ico">⌕</span>
              <input type="search" class="inputicon__input" placeholder="Brza pretraga u listi..." data-map-list-search />
            </div>
          </div>
        </div>

        <div class="card__body maplist__body">
          <div class="maplist__items" data-map-list>
            @forelse(($monasteries ?? []) as $m)
              @php
                // backend treba da ti šalje lat/lng (alias od latitude/longitude)
                $lat  = $m->lat ?? null;
                $lng  = $m->lng ?? null;

                $name = $m->name ?? 'Manastir';
                $slug = $m->slug ?? null;
                $city = $m->city ?? null;
                $reg  = $m->region ?? null;
                $epName = $m->eparchy_name ?? null;

                $img  = $m->image_url ?? null;
                $imgSrc = $img ? (str_starts_with($img, 'http') ? $img : asset($img)) : null;

                $search = \Illuminate\Support\Str::lower($name.' '.($city ?? '').' '.($reg ?? '').' '.($epName ?? ''));
              @endphp

              <article class="resultcard"
                data-title="{{ $search }}"
                @if($lat && $lng) data-lat="{{ $lat }}" data-lng="{{ $lng }}" @endif
              >
                <div class="resultcard__media">
                  @if($imgSrc)
                    <img src="{{ $imgSrc }}" alt="{{ $name }}" loading="lazy">
                  @else
                    <div class="resultcard__placeholder" aria-hidden="true"></div>
                  @endif
                </div>

                <div class="resultcard__content">
                  <div class="resultcard__top">
                    <h4 class="resultcard__title">{{ $name }}</h4>
                    <div class="resultcard__meta muted">
                      @if($city) <span>{{ $city }}</span> @endif
                      @if($city && $reg) <span class="dot">•</span> @endif
                      @if($reg) <span>{{ $reg }}</span> @endif
                      @if(($city || $reg) && $epName) <span class="dot">•</span> @endif
                      @if($epName) <span>{{ $epName }}</span> @endif
                    </div>
                  </div>

                  <div class="resultcard__actions">
                    @php
                      // ako ima koordinate -> tačna lokacija, ako nema -> pretraga po nazivu
                      $gmQuery = ($lat && $lng)
                        ? ($lat . ',' . $lng)
                        : trim($name . ' ' . ($city ?? '') . ' Srbija');

                      $gmUrl  = 'https://www.google.com/maps?q=' . urlencode($gmQuery);

                      $dirUrl = null;
                      if ($lat && $lng) {
                        $dirUrl = 'https://www.google.com/maps/dir/?api=1&destination=' . urlencode($lat . ',' . $lng);
                      }
                    @endphp

                    <a class="btn btn--soft btn--sm" href="{{ $gmUrl }}" target="_blank" rel="noopener">
                      Google Maps
                    </a>

                    @if($dirUrl)
                      <a class="btn btn--ghost btn--sm" href="{{ $dirUrl }}" target="_blank" rel="noopener">
                        Navigacija
                      </a>
                    @endif

                    @if($lat && $lng)
                      <button type="button"
                        class="btn btn--soft btn--sm"
                        data-map-action="focus"
                        data-lat="{{ $lat }}"
                        data-lng="{{ $lng }}"
                        data-title="{{ $name }}"
                      >Prikaži na mapi</button>
                    @else
                      <span class="muted">Nema koordinate</span>
                    @endif

                    @if($slug)
                      <a class="btn btn--ghost btn--sm" href="{{ route('monasteries.show', $slug) }}">Detalji</a>
                    @endif
                  </div>

                </div>
              </article>
            @empty
              <div class="empty">
                <strong>Nema rezultata.</strong>
                <p class="muted">Promeni filtere ili klikni “Reset”.</p>
              </div>
            @endforelse
          </div>
        </div>

        <div class="card__footer maplist__footer">
          <div class="muted">Tip: drži listu otvorenu i klikaj “Prikaži na mapi”.</div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('mapFilters');
  if (!form) return;

  const region = document.getElementById('region');
  const eparchy = document.getElementById('eparchy');
  const onlyGeo = document.getElementById('only_geo');

  function submitSoon(){
    setTimeout(() => form.submit(), 50);
  }

  if (region) region.addEventListener('change', submitSoon);
  if (eparchy) eparchy.addEventListener('change', submitSoon);
  if (onlyGeo) onlyGeo.addEventListener('change', submitSoon);
});
</script>

@push('scripts')
<script>
  window.MAP_PAGE = {
    points: @json($points ?? []),
    options: {
      cluster: {{ (request('cluster') ?? 1) == 1 ? 'true' : 'false' }},
      onlyGeo: {{ (request('only_geo') ?? 0) == 1 ? 'true' : 'false' }}
    },
    detailBase: "{{ url('/manastiri/') }}/"
  };
</script>
<script src="{{ asset('js/map.js') }}"></script>
@endpush
@endsection