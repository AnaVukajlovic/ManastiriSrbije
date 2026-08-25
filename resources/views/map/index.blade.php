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
        grid-template-columns:100px minmax(0,1fr);
        gap:14px;
        align-items:stretch;
        padding:14px;
        border-radius:20px;
        border:1px solid rgba(197,162,74,.18);
        background:linear-gradient(135deg, rgba(255,255,255,.035), rgba(18,12,13,.78));
        box-shadow:0 10px 24px rgba(0,0,0,.28);
        transition:transform .22s ease, border-color .22s ease, box-shadow .22s ease;
      }

      .resultcard:hover{
        transform:translateY(-2px);
        border-color:rgba(197,162,74,.48);
        box-shadow:0 16px 36px rgba(0,0,0,.45), 0 0 18px rgba(197,162,74,.12);
      }

      .resultcard__media{
        width:100px;
        min-height:100px;
        height:100%;
        border-radius:15px;
        overflow:hidden;
        border:1px solid rgba(197,162,74,.25);
        background:rgba(0,0,0,.35);
        flex-shrink:0;
        position:relative;
      }

      .resultcard__media img{
        width:100%;
        height:100%;
        object-fit:cover;
        display:block;
        transition:transform .35s ease;
      }

      .resultcard:hover .resultcard__media img{
        transform:scale(1.08);
      }

      .resultcard__content{
        display:flex;
        flex-direction:column;
        justify-content:space-between;
      }

      .resultcard__title{
        margin:0 0 4px;
        font-size:1.12rem;
        font-weight:800;
        color:#fff;
        line-height:1.2;
      }

      .resultcard__badge-row{
        display:flex;
        align-items:center;
        gap:6px;
        flex-wrap:wrap;
        margin-bottom:6px;
      }

      .resultcard__dist{
        display:inline-flex;
        align-items:center;
        gap:4px;
        padding:2px 8px;
        border-radius:8px;
        background:rgba(197,162,74,.15);
        color:#f3dc9b;
        font-size:.82rem;
        font-weight:700;
        border:1px solid rgba(197,162,74,.30);
      }

      .resultcard__meta{
        font-size:.90rem;
        color:var(--map-muted);
        line-height:1.35;
      }

      .resultcard__actions{
        display:flex;
        flex-wrap:wrap;
        gap:8px;
        margin-top:10px;
        align-items:center;
      }

      .resultcard__actions .btn{
        border-radius:12px;
        font-size:.88rem;
        padding:6px 12px;
        font-weight:700;
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
    </style>

    <div class="sectionhead sectionhead--map">
      <div>
        <h2>Mapa svetinja</h2>
        <span class="muted">Pregled manastira na mapi, pretraga i brzi fokus na lokacije.</span>
      </div>

      <div class="sectionhead__actions map-actions-top">
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
    <div class="muted" id="mapCounterInfo">
        Prikazano: <strong>{{ $total ?? 0 }}</strong>
        <span class="dot">&bull;</span>
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
                  <div>
                    <h4 class="resultcard__title">{{ $name }}</h4>
                    <div class="resultcard__badge-row">
                      <span class="resultcard__meta">📍 {{ collect([$city, $reg])->filter()->join(' • ') ?: 'Nepoznato' }}</span>
                    </div>
                  </div>

                  <div class="resultcard__actions">
                    @php
                      $gmQuery = $hasGeo
                        ? ((float)$lat . ',' . (float)$lng)
                        : trim($name . ' ' . ($city ?? '') . ' Srbija');

                      $gmUrl = 'https://www.google.com/maps?q=' . urlencode($gmQuery);
                    @endphp

                    <a class="btn btn--soft btn--sm" href="{{ $gmUrl }}" target="_blank" rel="noopener">🗺️ Google Maps</a>

                    @if($hasGeo)
                      <button
                        type="button"
                        class="btn btn--soft btn--sm"
                        data-map-action="focus"
                        data-lat="{{ (float)$lat }}"
                        data-lng="{{ (float)$lng }}"
                        data-title="{{ $name }}"
                      >
                        📍 Prikaži na mapi
                      </button>
                    @else
                      <span class="muted" style="font-size:0.8em;">Nema koordinate</span>
                    @endif

                    @if($slug)
                      <a class="btn btn--ghost btn--sm" href="{{ route('monasteries.show', $slug) }}">Detalji ➔</a>
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
  if (form) {
    ['region', 'eparchy'].forEach(id => {
      const el = document.getElementById(id);
      if (el) el.addEventListener('change', () => setTimeout(() => form.submit(), 50));
    });
  }
});

window.MAP_PAGE = {
  points: @json($points ?? []),
  options: { cluster: true, onlyGeo: false }
};

document.addEventListener('DOMContentLoaded', () => {
  const input = document.getElementById('aiCityInput');
  const btn = document.getElementById('aiCityBtn');
  const loading = document.getElementById('aiCityLoading');
  
  // Hvatamo elemente desnog panela
  const rightPanelList = document.querySelector('.maplist__items');
  const rightPanelTitle = document.querySelector('.maplist .card__title h3');
  const rightPanelDesc = document.querySelector('.maplist .card__title p');

  async function fetchRecommendation() {
    const city = input.value.trim();
    if (!city) { alert('Unesi grad.'); return; }

    loading.style.display = 'block';
    
    // Čistimo desni panel i stavljamo "Učitavanje"
    rightPanelList.innerHTML = '<div class="empty">Tražim manastire...</div>';

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

     if (Array.isArray(data.items) && data.items.length) {
        
        if (rightPanelTitle) rightPanelTitle.textContent = 'AI Rezultati';
        if (rightPanelDesc) rightPanelDesc.textContent = data.ai_text || `Predlog za: ${city}`;

        rightPanelList.innerHTML = '';

        // NOVO: Pripremamo niz za pomeranje mape i brojanje
        const bounds = [];
        let validCoordsCount = 0;

        data.items.forEach(item => {
          const rawLat = String(item.latitude ?? item.lat ?? 0).replace(',', '.').trim();
          const rawLng = String(item.longitude ?? item.lng ?? 0).replace(',', '.').trim();
          const lat = parseFloat(rawLat);
          const lng = parseFloat(rawLng);
          
          const hasCoord = !isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0;

          // NOVO: Ako imamo koordinate, beježimo ih za kameru i brojimo
          if (hasCoord) {
            bounds.push([lat, lng]);
            validCoordsCount++;
          }

          const placeholderImg = "{{ asset('images/monasteries/placeholder.jpg') }}";
          let imgSrc = placeholderImg;
          if (item.image && typeof item.image === 'string' && item.image.trim() !== '') {
            imgSrc = item.image.startsWith('http') || item.image.startsWith('/') ? item.image : `/${item.image}`;
          }
          
          const gmQuery = hasCoord ? `${lat},${lng}` : encodeURIComponent(item.name + ' ' + (item.city ?? 'Srbija'));
          const gmUrl = `https://www.google.com/maps/search/?api=1&query=${gmQuery}`;

          const distBadge = (item.distance_km !== null && item.distance_km !== undefined)
            ? `<span class="resultcard__dist">🚗 ~${item.distance_km} km</span>` 
            : '';

          const card = document.createElement('article');
          card.className = 'resultcard';
          
          card.innerHTML = `
            <div class="resultcard__media">
              <img src="${imgSrc}" alt="${item.name}" loading="lazy" onerror="this.onerror=null; this.src='${placeholderImg}';">
            </div>
            <div class="resultcard__content">
              <div>
                <h4 class="resultcard__title">${item.name}</h4>
                <div class="resultcard__badge-row">
                  ${distBadge}
                  <span class="resultcard__meta">📍 ${item.city ?? ''}${item.region ? ' • ' + item.region : ''}</span>
                </div>
              </div>
              <div class="resultcard__actions">
                <a class="btn btn--soft btn--sm" href="${gmUrl}" target="_blank" rel="noopener">🗺️ Google Maps</a>
                
                ${hasCoord ? `
                  <button type="button" class="btn btn--soft btn--sm" data-map-action="focus" data-lat="${lat}" data-lng="${lng}" data-title="${item.name}">
                    📍 Prikaži na mapi
                  </button>
                ` : `<span class="muted" style="font-size:0.8em;">Nema koordinate</span>`}
                
                <a class="btn btn--ghost btn--sm" href="${item.url}">Detalji ➔</a>
              </div>
            </div>
          `;
          rightPanelList.appendChild(card);
        });

        // --- NOVO: AUTOMATSKO ZUMIRANJE MAPE ---
        // Kad AI završi, mapa automatski "leti" da obuhvati sve nađene manastire
        if (bounds.length > 0 && window.map) {
          window.map.fitBounds(bounds, { padding: [50, 50], maxZoom: 12, animate: true });
        }

        // --- NOVO: AŽURIRANJE BROJAČA ---
        const counterEl = document.getElementById('mapCounterInfo');
        if (counterEl) {
          // Ažuriramo HTML direktno koristeći ID
          counterEl.innerHTML = `Prikazano: <strong>${data.items.length}</strong> &bull; Sa koordinatama: <strong>${validCoordsCount}</strong>`;
        }

      } else {
        rightPanelList.innerHTML = `
          <div class="empty">
            <strong>Nema rezultata.</strong>
            <p class="muted">AI nije pronašao manastire za ovaj upit.</p>
          </div>
        `;
      }
    } catch (error) {
      loading.style.display = 'none';
      rightPanelList.innerHTML = `<div class="empty">Došlo je do greške u komunikaciji sa serverom.</div>`;
    }
  }

  btn?.addEventListener('click', fetchRecommendation);
  input?.addEventListener('keydown', (e) => { if (e.key === 'Enter') { e.preventDefault(); fetchRecommendation(); }});
});
</script>

<script src="{{ asset('js/map.js') }}"></script>
@endsection