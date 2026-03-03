@extends('layouts.app')

@section('title', 'Manastir')

@section('content')
  <div id="detail">
    <div class="card detail-card">
      <div class="detail-head">
        <div>
          <div class="detail-kicker">Učitavanje...</div>
          <h1 class="detail-title">...</h1>
          <div class="detail-meta">...</div>
        </div>

        <div class="detail-actions">
          <button class="btn2" disabled>⭐ Omiljeni (uskoro)</button>
          <a class="btn2" id="gmaps" href="#" target="_blank" style="display:none">📍 Google Maps</a>
        </div>
      </div>

      <div class="detail-body">
        <section class="panel">
          <h3>Opis</h3>
          <p id="desc" class="muted">Učitavanje opisa...</p>
        </section>

        <section class="panel">
          <h3>Lokacija</h3>
          <div class="loc-row">
            <div><b>Region:</b> <span id="region">-</span></div>
            <div><b>Grad:</b> <span id="city">-</span></div>
          </div>
          <div class="loc-row">
            <div><b>Koordinate:</b> <span id="coords">-</span></div>
          </div>

          <div class="mapbox">
            <iframe id="map"
              title="Mapa"
              loading="lazy"
              referrerpolicy="no-referrer-when-downgrade"
              style="width:100%; height:260px; border:0; border-radius:14px; display:none;">
            </iframe>
            <div id="mapFallback" class="muted">Mapa će se prikazati kad postoje koordinate.</div>
          </div>
        </section>
      </div>
    </div>

    <div class="card" style="margin-top:14px;">
      <h3 style="margin:0 0 10px;">Još opcija (dodajemo na kraju)</h3>
      <div class="chips">
        <span class="chip">🖼 Galerija</span>
        <span class="chip">🧭 360° tura</span>
        <span class="chip">📌 Istorija / Vek</span>
        <span class="chip">✍️ Recenzije</span>
        <span class="chip">🤖 Pitaj AI o ovom manastiru</span>
      </div>
    </div>
  </div>

  <script>
    const slug = @json(request()->route('slug'));

    function escapeHtml(s){
      return String(s ?? '')
        .replaceAll('&','&amp;')
        .replaceAll('<','&lt;')
        .replaceAll('>','&gt;')
        .replaceAll('"','&quot;')
        .replaceAll("'","&#039;");
    }

    async function loadDetail(){
      const res = await fetch(`/api/monasteries/${encodeURIComponent(slug)}`, {
        headers: { 'Accept': 'application/json' }
      });

      if (!res.ok){
        document.getElementById('detail').innerHTML =
          `<div class="card"><h2>Manastir nije pronađen.</h2><p class="muted">Proveri slug u URL-u.</p></div>`;
        return;
      }

      const json = await res.json();
      const m = json.data;

      // Head
      document.querySelector('.detail-kicker').textContent = `${m.region ?? ''}`.trim() || 'Manastir';
      document.querySelector('.detail-title').textContent = m.name ?? 'Manastir';
      document.querySelector('.detail-meta').textContent = `${m.city ?? ''}`.trim();

      // Body
      document.getElementById('desc').textContent = m.description ?? '';
      document.getElementById('region').textContent = m.region ?? '-';
      document.getElementById('city').textContent = m.city ?? '-';

      const lat = m.latitude;
      const lng = m.longitude;

      if (typeof lat === 'number' && typeof lng === 'number'){
        document.getElementById('coords').textContent = `${lat}, ${lng}`;

        // Google Maps link
        const gmaps = document.getElementById('gmaps');
        gmaps.href = `https://www.google.com/maps?q=${encodeURIComponent(lat)},${encodeURIComponent(lng)}`;
        gmaps.style.display = 'inline-block';

        // Embed mapa (Google maps embed bez API ključa)
        const iframe = document.getElementById('map');
        iframe.src = `https://www.google.com/maps?q=${encodeURIComponent(lat)},${encodeURIComponent(lng)}&z=14&output=embed`;
        iframe.style.display = 'block';
        document.getElementById('mapFallback').style.display = 'none';
      }
    }

    loadDetail();
  </script>
@endsection
