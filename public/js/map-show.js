/* public/js/map-show.js
   Requires Leaflet (L global). Graceful fallback if not present.
*/
(function () {
  function qs(sel, root = document) { return root.querySelector(sel); }
  function qsa(sel, root = document) { return Array.from(root.querySelectorAll(sel)); }

  function buildPopupHTML(p) {
    const safe = (x) => (x ?? "").toString().replace(/</g, "&lt;").replace(/>/g, "&gt;");
    const name = safe(p.name || "Lokacija");
    const city = safe(p.city || "");
    const region = safe(p.region || "");
    const meta = [city, region].filter(Boolean).join(" • ");

    const detailUrl = p.slug ? `/manastiri/${encodeURIComponent(p.slug)}` : null;
    const gmapsUrl = (p.lat && p.lng) ? `https://www.google.com/maps?q=${p.lat},${p.lng}` : null;

    return `
      <div class="mappopup">
        <div class="mappopup__title">${name}</div>
        ${meta ? `<div class="mappopup__meta">${meta}</div>` : ``}
        <div class="mappopup__actions">
          ${detailUrl ? `<a class="mappopup__btn" href="${detailUrl}">Detalji</a>` : ``}
          ${gmapsUrl ? `<a class="mappopup__btn mappopup__btn--ghost" target="_blank" rel="noopener" href="${gmapsUrl}">Google Maps</a>` : ``}
        </div>
      </div>
    `;
  }

  function addUserMarker(L, map, latlng) {
    // Marker + krug za "tvoja lokacija"
    const marker = L.circleMarker(latlng, {
      radius: 7,
      weight: 2,
      opacity: 1,
      fillOpacity: 1,
    }).addTo(map);

    const circle = L.circle(latlng, {
      radius: 250,
      weight: 1,
      opacity: 0.35,
      fillOpacity: 0.08,
    }).addTo(map);

    return { marker, circle };
  }

  function init() {
    const data = window.MAP_SHOW && window.MAP_SHOW.point ? window.MAP_SHOW.point : null;
    const mapEl = qs("#map");

    if (!mapEl) return;

    // Ako nema koordinata – Blade već prikazuje empty overlay, ovde samo izlazimo.
    if (!data || !data.lat || !data.lng) return;

    const lat = Number(data.lat);
    const lng = Number(data.lng);

    if (!Number.isFinite(lat) || !Number.isFinite(lng)) return;

    // Leaflet check
    if (typeof window.L === "undefined") {
      console.warn("Leaflet (L) nije učitan. Dodaj Leaflet CDN u layout.");
      mapEl.innerHTML = `
        <div class="mapfallback">
          <strong>Mapa nije dostupna</strong>
          <div class="muted">Leaflet biblioteka nije učitana.</div>
        </div>
      `;
      return;
    }

    const L = window.L;

    // Kreiranje mape
    const map = L.map(mapEl, {
      zoomControl: false, // imamo custom dugmad u headeru
      attributionControl: true,
    });

    // Tile layer (OSM)
    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      maxZoom: 19,
      attribution: '&copy; OpenStreetMap',
    }).addTo(map);

    const defaultZoom = 13;
    map.setView([lat, lng], defaultZoom);

    // Marker
    const marker = L.marker([lat, lng]).addTo(map);
    marker.bindPopup(buildPopupHTML(data), { closeButton: true }).openPopup();

    // Helpers
    let user = null;

    function fitPoint() {
      map.setView([lat, lng], defaultZoom, { animate: true });
      marker.openPopup();
    }

    function zoomIn() { map.zoomIn(); }
    function zoomOut() { map.zoomOut(); }

    function locateUser() {
      if (!navigator.geolocation) {
        alert("Tvoj browser ne podržava geolokaciju.");
        return;
      }

      navigator.geolocation.getCurrentPosition(
        (pos) => {
          const uLat = pos.coords.latitude;
          const uLng = pos.coords.longitude;

          if (user) {
            try {
              map.removeLayer(user.marker);
              map.removeLayer(user.circle);
            } catch {}
          }
          user = addUserMarker(L, map, [uLat, uLng]);

          const group = L.featureGroup([marker, user.marker, user.circle]);
          map.fitBounds(group.getBounds().pad(0.2), { animate: true });
        },
        (err) => {
          console.warn(err);
          alert("Ne mogu da dobijem lokaciju (dozvola ili signal).");
        },
        { enableHighAccuracy: true, timeout: 10000 }
      );
    }

    function goNearby() {
      // Šaljemo na map index sa parametrima oko lat/lng (ti kasnije u kontroleru dodaš filtriranje)
      const url = new URL(window.location.origin + "/mapa"); // prilagodi ako ti je ruta drugačija
      url.searchParams.set("around_lat", String(lat));
      url.searchParams.set("around_lng", String(lng));
      url.searchParams.set("r_km", "20");
      window.location.href = url.toString();
    }

    // Bind dugmad iz Blade-a
    qsa("[data-map-action]").forEach((btn) => {
      const action = btn.getAttribute("data-map-action");

      btn.addEventListener("click", (e) => {
        e.preventDefault();
        switch (action) {
          case "fit": return fitPoint();
          case "zoom-in": return zoomIn();
          case "zoom-out": return zoomOut();
          case "locate": return locateUser();
          case "nearby": return goNearby();
          default: return;
        }
      });
    });

    // UX: klik na mapu zatvara popup, ponovni fit vraća
    map.on("click", () => marker.closePopup());
  }

  document.addEventListener("DOMContentLoaded", init);
})();