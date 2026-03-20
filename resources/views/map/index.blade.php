@extends('layouts.site')

@section('title', 'Mapa — Pravoslavni Svetionik')
@section('nav_map', 'active')

@section('content')
<section class="section map-page">
  <div class="container">

    <style>
      .map-page{
        --map-gold:#c5a24a;
        --map-gold-2:#e2c26a;
        --map-ink:rgba(255,255,255,.94);
        --map-muted:rgba(255,255,255,.72);
        --map-muted-2:rgba(255,255,255,.58);
        --map-line:rgba(255,255,255,.08);
        --map-line-soft:rgba(255,255,255,.06);
        --map-bg-soft:rgba(255,255,255,.03);
        --map-bg-soft-2:rgba(255,255,255,.02);
        --map-panel:
          radial-gradient(circle at top left, rgba(197,162,74,.08), transparent 28%),
          linear-gradient(180deg, rgba(255,255,255,.025), rgba(255,255,255,.012)),
          rgba(18,12,13,.72);
        --map-shadow:0 18px 46px rgba(0,0,0,.24);
      }

      .sectionhead--map{
        display:flex;
        align-items:flex-end;
        justify-content:space-between;
        gap:20px;
        margin-bottom:18px;
      }

      .sectionhead--map h2{
        margin:0 0 6px;
        font-size:clamp(1.7rem, 2.3vw, 2.2rem);
        line-height:1.08;
        letter-spacing:-.02em;
        font-weight:800;
        color:var(--map-gold);
        text-shadow:0 0 14px rgba(197,162,74,.14);
      }

      .sectionhead--map .muted{
        color:var(--map-muted);
      }

      .map-actions-top{
        display:flex;
        flex-wrap:wrap;
        gap:10px;
      }

      .map-actions-top .btn{
        border-radius:14px;
      }

      .filters--map{
        padding:14px;
        border-radius:24px;
        border:1px solid var(--map-line);
        background:var(--map-panel);
        box-shadow:var(--map-shadow);
      }

      .filters__row--map-main{
        display:grid;
        grid-template-columns:minmax(260px,1.45fr) minmax(180px,.95fr) minmax(180px,.95fr) auto;
        gap:12px;
        align-items:center;
      }

      .filters__row--map-meta{
        margin-top:14px;
        padding:12px 14px;
        border:1px solid var(--map-line-soft);
        border-radius:18px;
        background:rgba(255,255,255,.015);
      }

      .filters--map input,
      .filters--map select{
        width:100%;
        height:50px;
        padding:0 16px;
        border-radius:16px;
        border:1px solid var(--map-line);
        background:rgba(255,255,255,.035);
        color:#f5f1ea;
        box-shadow:inset 0 1px 0 rgba(255,255,255,.03);
        transition:.22s ease;
        font-size:.97rem;
      }

      .filters--map input::placeholder{
        color:rgba(255,255,255,.48);
      }

      .filters--map input:focus,
      .filters--map select:focus{
        outline:none;
        border-color:rgba(197,162,74,.72);
        box-shadow:0 0 0 3px rgba(197,162,74,.10);
        background:rgba(255,255,255,.05);
      }

      .filters--map select{
        appearance:none;
        -webkit-appearance:none;
        -moz-appearance:none;
        padding-right:42px;
        cursor:pointer;
      }

      .filters--map select option{
        background:#1a1211;
        color:#f3ede3;
      }

      .select-wrap{
        position:relative;
      }

      .select-wrap::after{
        content:"";
        position:absolute;
        right:16px;
        top:50%;
        width:10px;
        height:10px;
        border-right:2px solid rgba(255,255,255,.72);
        border-bottom:2px solid rgba(255,255,255,.72);
        transform:translateY(-65%) rotate(45deg);
        pointer-events:none;
      }

      .filters__actions--map{
        display:flex;
        gap:10px;
        flex-wrap:wrap;
      }

      .filters__actions--map .btn{
        height:50px;
        padding:0 18px;
        border-radius:16px;
        font-weight:800;
      }

      .filters__actions--map .btn[type="submit"],
      .filters__actions--map .btn:first-child{
        background:linear-gradient(135deg, var(--map-gold), var(--map-gold-2));
        color:#19120e;
        border:none;
        box-shadow:0 10px 22px rgba(197,162,74,.16);
      }

      .filters__actions--map .btn--soft{
        background:rgba(255,255,255,.05);
        border:1px solid rgba(255,255,255,.10);
        color:#fff;
      }

      .ai-city-box{
        margin-top:16px;
        padding:16px;
        border-radius:22px;
        border:1px solid rgba(197,162,74,.16);
        background:
          radial-gradient(circle at top left, rgba(197,162,74,.06), transparent 24%),
          rgba(255,255,255,.02);
      }

      .ai-city-head h3{
        margin:0 0 6px;
        font-size:1.15rem;
        font-weight:800;
        color:var(--map-gold);
        text-shadow:0 0 12px rgba(197,162,74,.12);
      }

      .ai-city-head p{
        margin:0;
        color:var(--map-muted);
        line-height:1.65;
      }

      .ai-city-form{
        display:grid;
        grid-template-columns:minmax(0,1fr) auto;
        gap:12px;
        margin-top:14px;
      }

      .ai-city-form input{
        height:50px;
        border-radius:16px;
        border:1px solid var(--map-line);
        background:rgba(255,255,255,.035);
        color:#fff;
        padding:0 16px;
      }

      .ai-city-form input:focus{
        outline:none;
        border-color:rgba(197,162,74,.72);
        box-shadow:0 0 0 3px rgba(197,162,74,.10);
      }

      .ai-city-form button{
        height:50px;
        padding:0 18px;
        border:none;
        border-radius:16px;
        font-weight:800;
        background:linear-gradient(135deg, var(--map-gold), var(--map-gold-2));
        color:#19120e;
        cursor:pointer;
        box-shadow:0 10px 22px rgba(197,162,74,.16);
      }

      .ai-city-loading,
      .ai-city-text,
      .ai-city-empty{
        color:var(--map-muted);
      }

      .ai-city-result{
        margin-top:14px;
      }

      .ai-city-items{
        display:flex;
        flex-direction:column;
        gap:10px;
        margin-top:12px;
      }

      .ai-city-item{
        display:flex;
        justify-content:space-between;
        gap:14px;
        flex-wrap:wrap;
        padding:14px;
        border-radius:16px;
        border:1px solid var(--map-line-soft);
        background:rgba(255,255,255,.02);
      }

      .ai-city-item-title{
        font-weight:800;
        color:#fff;
      }

      .ai-city-item-meta{
        margin-top:4px;
        color:var(--map-muted);
        font-size:.94rem;
      }

      .ai-city-item-actions{
        display:flex;
        gap:8px;
        align-items:center;
        flex-wrap:wrap;
      }

      .ai-link-open,
      .show-on-map-btn{
        height:38px;
        padding:0 14px;
        display:inline-flex;
        align-items:center;
        justify-content:center;
        border-radius:12px;
        text-decoration:none;
        font-weight:700;
        cursor:pointer;
      }

      .ai-link-open{
        background:rgba(255,255,255,.05);
        color:#fff;
        border:1px solid rgba(255,255,255,.10);
      }

      .show-on-map-btn{
        border:1px solid rgba(197,162,74,.22);
        background:rgba(197,162,74,.10);
        color:#f0d78f;
      }

      .maplayout{
        display:grid;
        grid-template-columns:minmax(0,1.35fr) minmax(360px,.95fr);
        gap:16px;
        margin-top:16px;
      }

      .mapcard,
      .maplist{
        border-radius:22px;
        overflow:hidden;
        border:1px solid var(--map-line);
        background:
          radial-gradient(circle at top left, rgba(197,162,74,.05), transparent 24%),
          linear-gradient(180deg, rgba(255,255,255,.02), rgba(255,255,255,.01)),
          rgba(18,12,13,.62);
        box-shadow:0 16px 40px rgba(0,0,0,.20);
      }

      .card__header{
        display:flex;
        align-items:flex-start;
        justify-content:space-between;
        gap:14px;
        padding:14px 16px;
        border-bottom:1px solid var(--map-line-soft);
      }

      .card__title h3{
        margin:0 0 4px;
        font-size:1.15rem;
        font-weight:800;
        color:var(--map-gold);
        text-shadow:0 0 12px rgba(197,162,74,.10);
      }

      .card__title .muted{
        color:var(--map-muted);
      }

      .card__body{
        padding:14px;
      }

      .mapcard__body{
        position:relative;
      }

      .mapcanvas{
        width:100%;
        min-height:520px;
        border-radius:18px;
        overflow:hidden;
        background:#16100f;
      }

      .mapcard__emptygeo{
        margin-top:10px;
      }

      .card__tools{
        display:flex;
        gap:8px;
        flex-wrap:wrap;
      }

      .card__tools .btn{
        border-radius:12px;
      }

      .maplegend{
        position:absolute;
        top:18px;
        right:18px;
        z-index:500;
      }

      .maplegend__inner{
        min-width:220px;
        padding:14px;
        border-radius:18px;
        border:1px solid var(--map-line);
        background:rgba(18,12,13,.96);
        box-shadow:0 18px 40px rgba(0,0,0,.28);
      }

      .maplegend__head{
        display:flex;
        align-items:center;
        justify-content:space-between;
        gap:10px;
        margin-bottom:10px;
      }

      .maplegend__head strong{
        color:var(--map-gold);
      }

      .maplegend__list{
        margin:0;
        padding:0;
        list-style:none;
        display:flex;
        flex-direction:column;
        gap:8px;
        color:#fff;
      }

      .badge{
        display:inline-block;
        width:12px;
        height:12px;
        border-radius:999px;
        margin-right:8px;
      }

      .badge--primary{ background:#c5a24a; }
      .badge--soft{ background:#8f7a43; }
      .badge--accent{ background:#6fa8ff; }

      .maplist__body{
        padding:12px;
      }

      .maplist__items{
        display:flex;
        flex-direction:column;
        gap:12px;
        max-height:720px;
        overflow:auto;
        padding-right:4px;
      }

      .resultcard{
        display:grid;
        grid-template-columns:72px minmax(0,1fr);
        gap:12px;
        align-items:center;
        padding:12px;
        border-radius:18px;
        border:1px solid var(--map-line-soft);
        background:rgba(255,255,255,.018);
      }

      .resultcard__media{
        width:72px;
        height:72px;
        border-radius:16px;
        overflow:hidden;
        background:rgba(255,255,255,.04);
        flex-shrink:0;
      }

      .resultcard__media img{
        width:100%;
        height:100%;
        object-fit:cover;
        display:block;
      }

      .resultcard__placeholder{
        width:100%;
        height:100%;
        background:
          radial-gradient(circle at top left, rgba(197,162,74,.10), transparent 32%),
          rgba(255,255,255,.03);
      }

      .resultcard__title{
        margin:0 0 4px;
        font-size:1.05rem;
        font-weight:800;
        color:#fff;
      }

      .resultcard__meta{
        font-size:.92rem;
      }

      .resultcard__actions{
        display:flex;
        flex-wrap:wrap;
        gap:8px;
        margin-top:10px;
      }

      .resultcard__actions .btn{
        border-radius:12px;
      }

      .empty{
        padding:18px;
        border-radius:18px;
        border:1px solid var(--map-line-soft);
        background:rgba(255,255,255,.02);
      }

      @media (max-width: 1180px){
        .filters__row--map-main{
          grid-template-columns:1fr 1fr;
        }

        .filters__actions--map{
          grid-column:1 / -1;
        }

        .maplayout{
          grid-template-columns:1fr;
        }
      }

      @media (max-width: 760px){
        .sectionhead--map{
          flex-direction:column;
          align-items:stretch;
        }

        .map-actions-top{
          width:100%;
        }

        .map-actions-top .btn{
          flex:1 1 auto;
          justify-content:center;
        }

        .filters__row--map-main{
          grid-template-columns:1fr;
        }

        .ai-city-form{
          grid-template-columns:1fr;
        }

        .mapcanvas{
          min-height:380px;
        }

        .resultcard{
          grid-template-columns:1fr;
          align-items:flex-start;
        }

        .resultcard__media{
          width:100%;
          height:180px;
        }
      }

      #map,
      .leaflet-container{
        width:100%;
        height:100%;
      }

      .leaflet-container{
        background:#16100f;
        border-radius:18px;
      }

      .leaflet-control-zoom a{
        background:rgba(24,16,15,.94) !important;
        color:#f4e7c3 !important;
        border-bottom:1px solid rgba(255,255,255,.08) !important;
      }

      .leaflet-popup-content-wrapper{
        background:rgba(24,16,15,.98);
        color:#f4eee7;
        border:1px solid rgba(197,162,74,.14);
        border-radius:16px;
      }

      .leaflet-popup-tip{
        background:rgba(24,16,15,.98);
      }

      .leaflet-popup-content{
        margin:12px 14px;
        line-height:1.6;
      }

      .map-popup__title{
        font-weight:800;
        color:#f2d16b;
        margin-bottom:4px;
      }

      .map-popup__meta{
        color:rgba(255,255,255,.74);
        font-size:.92rem;
        margin-bottom:8px;
      }

      .map-popup__link{
        color:#f4e5bc;
        font-weight:700;
        text-decoration:none;
      }

      .map-popup__link:hover{
        color:#fff1c8;
      }
      #map,
      .leaflet-container{
        width:100%;
        height:100%;
      }

      .leaflet-container{
        background:#16100f;
        border-radius:18px;
      }

      .leaflet-popup-content-wrapper{
        background:rgba(24,16,15,.98);
        color:#f4eee7;
        border:1px solid rgba(197,162,74,.14);
        border-radius:16px;
      }

      .leaflet-popup-tip{
        background:rgba(24,16,15,.98);
      }

      .leaflet-popup-content{
        margin:12px 14px;
        line-height:1.6;
      }

      .map-popup__title{
        font-weight:800;
        color:#f2d16b;
        margin-bottom:4px;
      }

      .map-popup__meta{
        color:rgba(255,255,255,.74);
        font-size:.92rem;
        margin-bottom:8px;
      }

      .map-popup__actions{
        display:flex;
        flex-wrap:wrap;
        gap:8px;
      }

      .map-popup__link{
        color:#f4e5bc;
        font-weight:700;
        text-decoration:none;
      }

      .map-popup__link:hover{
        color:#fff1c8;
      }

    </style>

    <div class="sectionhead sectionhead--map">
      <div>
        <h2>Mapa svetinja</h2>
        <span class="muted">Pregled manastira na mapi, pretraga i brzi fokus na lokacije.</span>
      </div>

      <div class="sectionhead__actions map-actions-top">
        <button type="button" class="btn btn--ghost" data-map-action="legend">Legenda</button>
        <button type="button" class="btn btn--ghost" data-map-action="locate">Moja lokacija</button>
        <a class="btn btn--ghost" href="{{ route('map.index') }}">Reset</a>
      </div>
    </div>

    <form id="mapFilters" class="filters filters--map" method="GET" action="{{ route('map.index') }}">
      <div class="filters__row filters__row--map-main">
        <div class="filters__field filters__field--search">
          <label class="sr-only" for="q">Pretraga</label>
          <input
            id="q"
            name="q"
            type="search"
            value="{{ $q ?? request('q') }}"
            placeholder="Naziv, grad, region, eparhija..."
            autocomplete="off"
          />
        </div>

        <div class="filters__field filters__field--select">
          <label class="sr-only" for="region">Region</label>
          <div class="select-wrap">
            <select id="region" name="region">
              <option value="">Svi regioni</option>
              @foreach(($regions ?? []) as $r)
                <option value="{{ $r }}" @selected(($region ?? request('region')) === $r)>{{ $r }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="filters__field filters__field--select">
          <label class="sr-only" for="eparchy">Eparhija</label>
          <div class="select-wrap">
            <select id="eparchy" name="eparchy">
              <option value="">Sve eparhije</option>
              @foreach(($eparchies ?? []) as $ep)
                <option value="{{ $ep->slug }}" @selected(($eparchy ?? request('eparchy')) === $ep->slug)>{{ $ep->name }}</option>
              @endforeach
            </select>
          </div>
        </div>

        <div class="filters__actions filters__actions--map">
          <button class="btn" type="submit">Primeni</button>
          <a class="btn btn--soft" href="{{ route('map.index') }}">Očisti</a>
        </div>
      </div>

      <div class="ai-city-box">
        <div class="ai-city-head">
          <h3>AI predlog manastira</h3>
          <p>Unesi grad i dobićeš predlog manastira iz tog grada ili njegove okoline koje možeš da posetiš.</p>
        </div>

        <div class="ai-city-form">
          <input
            type="text"
            id="aiCityInput"
            placeholder="Unesi grad, npr. Čačak"
            autocomplete="off"
          >
          <button type="button" id="aiCityBtn">Predloži manastire</button>
        </div>

        <div id="aiCityLoading" class="ai-city-loading" style="display:none;">
          Učitavanje preporuke...
        </div>

        <div id="aiCityResult" class="ai-city-result" style="display:none;">
          <p id="aiCityText" class="ai-city-text"></p>
          <div id="aiCityItems" class="ai-city-items"></div>
        </div>
      </div>

      <div class="filters__row filters__row--meta filters__row--map-meta">
        <div class="muted">
          Prikazano: <strong>{{ $total ?? 0 }}</strong>
          <span class="dot">•</span>
          Sa koordinatama: <strong>{{ $geo_total ?? 0 }}</strong>
        </div>
      </div>
    </form>

    <div class="maplayout">
      <div class="card mapcard">
        <div class="card__header">
          <div class="card__title">
            <h3>Interaktivna mapa</h3>
            <p class="muted">Pomeraj, zumiraj i klikni marker za detalje.</p>
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
            <div class="muted mapcard__emptygeo">
              Nema validnih koordinata za prikaz.
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
                <li><span class="badge badge--soft"></span> Više lokacija</li>
                <li><span class="badge badge--accent"></span> Tvoja lokacija</li>
              </ul>
            </div>
          </div>
        </div>
      </div>

      <div class="card maplist">
        <div class="card__header">
          <div class="card__title">
            <h3>Rezultati</h3>
            <p class="muted">Klik na „Prikaži na mapi“ fokusira lokaciju.</p>
          </div>
        </div>

        <div class="card__body maplist__body">
          <div class="maplist__items" data-map-list>
            @forelse(($monasteries ?? []) as $m)
              @php
                $lat = $m->latitude ?? $m->lat ?? null;
                $lng = $m->longitude ?? $m->lng ?? null;

                if (is_string($lat)) $lat = str_replace(',', '.', trim($lat));
                if (is_string($lng)) $lng = str_replace(',', '.', trim($lng));

                $hasGeo = ($lat !== null && $lng !== null && $lat !== '' && $lng !== '' && is_numeric($lat) && is_numeric($lng));

                $name = $m->name ?? 'Manastir';
                $slug = $m->slug ?? null;
                $city = $m->city ?? null;
                $reg  = $m->region ?? null;

                $rawImg = $m->image_url ?? $m->image ?? null;
                $localImg = $slug ? asset('images/monasteries/' . $slug . '.jpg') : null;
                $placeholderImg = asset('images/monasteries/placeholder.jpg');

                if (!empty($rawImg)) {
                    $imgSrc = str_starts_with($rawImg, 'http') ? $rawImg : asset($rawImg);
                } elseif ($localImg) {
                    $imgSrc = $localImg;
                } else {
                    $imgSrc = $placeholderImg;
                }
              @endphp

              <article class="resultcard">
                <div class="resultcard__media">
                  <img
                    src="{{ $imgSrc }}"
                    alt="{{ $name }}"
                    loading="lazy"
                    onerror="this.onerror=null; this.src='{{ $placeholderImg }}';"
                  >
                </div>

                <div class="resultcard__content">
                  <h4 class="resultcard__title">{{ $name }}</h4>

                  <div class="resultcard__meta muted">
                    {{ collect([$city, $reg])->filter()->join(' • ') ?: 'Nepoznato' }}
                  </div>

                  <div class="resultcard__actions">
                    @php
                      $gmQuery = $hasGeo
                        ? ((float)$lat . ',' . (float)$lng)
                        : trim($name . ' ' . ($city ?? '') . ' Srbija');

                      $gmUrl = 'https://www.google.com/maps?q=' . urlencode($gmQuery);
                    @endphp

                    <a class="btn btn--soft btn--sm" href="{{ $gmUrl }}" target="_blank" rel="noopener">Google Maps</a>

                    @if($hasGeo)
                      <button
                        type="button"
                        class="btn btn--soft btn--sm"
                        data-map-action="focus"
                        data-lat="{{ (float)$lat }}"
                        data-lng="{{ (float)$lng }}"
                        data-title="{{ $name }}"
                      >
                        Prikaži na mapi
                      </button>
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
                <p class="muted">Promeni filtere ili klikni „Reset“.</p>
              </div>
            @endforelse
          </div>
        </div>
      </div>
    </div>

  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('mapFilters');
  if (!form) return;

  ['region', 'eparchy'].forEach(id => {
    const el = document.getElementById(id);
    if (el) {
      el.addEventListener('change', () => setTimeout(() => form.submit(), 50));
    }
  });
});

window.MAP_PAGE = {
  points: @json($points ?? []),
  options: {
    cluster: true,
    onlyGeo: false
  }
};

document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('aiCityInput');
  const btn = document.getElementById('aiCityBtn');
  const loading = document.getElementById('aiCityLoading');
  const result = document.getElementById('aiCityResult');
  const textEl = document.getElementById('aiCityText');
  const itemsEl = document.getElementById('aiCityItems');

  async function fetchRecommendation() {
    const city = input.value.trim();

    if (!city) {
      alert('Unesi grad.');
      return;
    }

    loading.style.display = 'block';
    result.style.display = 'none';
    textEl.textContent = '';
    itemsEl.innerHTML = '';

    try {
      const response = await fetch("{{ route('map.ai.recommendByCity') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: JSON.stringify({ city })
      });

      const data = await response.json();

      loading.style.display = 'none';
      result.style.display = 'block';
      textEl.textContent = data.ai_text || '';

      if (Array.isArray(data.items) && data.items.length) {
        data.items.forEach(item => {
          const card = document.createElement('div');
          card.className = 'ai-city-item';

          card.innerHTML = `
            <div class="ai-city-item-main">
              <div class="ai-city-item-title">${item.name}</div>
              <div class="ai-city-item-meta">
                ${item.city ?? ''}${item.region ? ' • ' + item.region : ''}
              </div>
            </div>

            <div class="ai-city-item-actions">
              <a href="${item.url}" target="_blank" class="ai-link-open">Otvori</a>
              <button
                type="button"
                class="show-on-map-btn"
                data-lat="${item.lat ?? ''}"
                data-lng="${item.lng ?? ''}"
                data-name="${item.name}"
              >
                Prikaži na mapi
              </button>
            </div>
          `;

          itemsEl.appendChild(card);
        });
      } else {
        itemsEl.innerHTML = `<div class="ai-city-empty">Nema rezultata.</div>`;
      }
    } catch (error) {
      loading.style.display = 'none';
      result.style.display = 'block';
      textEl.textContent = 'Došlo je do greške pri generisanju preporuke.';
    }
  }

  btn?.addEventListener('click', fetchRecommendation);

  input?.addEventListener('keydown', (e) => {
    if (e.key === 'Enter') {
      e.preventDefault();
      fetchRecommendation();
    }
  });

  document.addEventListener('click', (e) => {
    const mapBtn = e.target.closest('.show-on-map-btn');
    if (!mapBtn) return;

    const lat = parseFloat(mapBtn.dataset.lat);
    const lng = parseFloat(mapBtn.dataset.lng);
    const name = mapBtn.dataset.name || 'Manastir';

    if (!Number.isFinite(lat) || !Number.isFinite(lng)) {
      alert('Za ovaj manastir nema koordinata.');
      return;
    }

    if (window.map) {
      window.map.setView([lat, lng], 11);

      if (window.L) {
        L.popup()
          .setLatLng([lat, lng])
          .setContent(`<strong>${name}</strong>`)
          .openOn(window.map);
      }
    } else {
      alert('Mapa nije dostupna.');
    }
  });
});
</script>

<script src="{{ asset('js/map.js') }}"></script>
@endsection