@extends('layouts.app')

@section('title', 'Detalj manastira')

@section('content')
  <div id="wrap">
    <div class="skeleton">Učitavanje...</div>
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

    async function load(){
      const res = await fetch(`/api/monasteries/${encodeURIComponent(slug)}`, {
        headers: { 'Accept': 'application/json' }
      });

      if (!res.ok) {
        document.getElementById('wrap').innerHTML = `<p>Manastir nije pronađen.</p>`;
        return;
      }

      const json = await res.json();
      const m = json.data ?? json; // ako vratiš direktno, pokriveno

      const lat = m.latitude ?? '';
      const lng = m.longitude ?? '';

      document.getElementById('wrap').innerHTML = `
        <div class="detail-hero">
          <div class="detail-title">${escapeHtml(m.name)}</div>
          <div class="detail-meta">${escapeHtml(m.region ?? '')} • ${escapeHtml(m.city ?? '')}</div>
        </div>

        <div class="detail-grid">
          <section class="panel">
            <h3>Opis</h3>
            <p>${escapeHtml(m.description ?? '')}</p>
          </section>

          <section class="panel">
            <h3>Lokacija</h3>
            <p><b>Koordinate:</b> ${escapeHtml(lat)} , ${escapeHtml(lng)}</p>
            <div class="mapbox">
              <a class="btn2" target="_blank"
                 href="https://www.google.com/maps?q=${encodeURIComponent(lat)},${encodeURIComponent(lng)}">
                Otvori u Google Maps
              </a>
            </div>
          </section>
        </div>

        <div class="actions">
          <button class="btn2" disabled>⭐ Dodaj u omiljene (kasnije)</button>
          <button class="btn2" disabled>🖼 Galerija (kad dodamo slike)</button>
          <button class="btn2" disabled>🧭 360° tura (kad dodamo)</button>
        </div>
      `;
    }

    load();
  </script>
@endsection
