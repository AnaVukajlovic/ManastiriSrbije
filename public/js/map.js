document.addEventListener("DOMContentLoaded", function () {
  const mapEl = document.getElementById("map");
  if (!mapEl || typeof L === "undefined") return;

  const data = window.MAP_PAGE || {};
  const points = Array.isArray(data.points) ? data.points : [];
  const opts = data.options || {};
  const useCluster = !!opts.cluster;

  const DEFAULT_CENTER = [44.0165, 21.0059];
  const DEFAULT_ZOOM = 7;

  const map = L.map("map", {
    scrollWheelZoom: true,
    zoomControl: false
  }).setView(DEFAULT_CENTER, DEFAULT_ZOOM);

  window.map = map;

  const osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 19,
    attribution: "&copy; OpenStreetMap"
  });

  const satellite = L.tileLayer(
    "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
    {
      maxZoom: 19,
      attribution: "Tiles © Esri"
    }
  );

  const terrain = L.tileLayer("https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png", {
    maxZoom: 17,
    attribution: "© OpenTopoMap"
  });

  osm.addTo(map);

  L.control.layers(
    {
      "Mapa": osm,
      "Satelit": satellite,
      "Reljef": terrain
    },
    {},
    {
      position: "topright"
    }
  ).addTo(map);

  const esc = (s) =>
    String(s ?? "").replace(/[&<>"']/g, (m) => ({
      "&": "&amp;",
      "<": "&lt;",
      ">": "&gt;",
      '"': "&quot;",
      "'": "&#039;"
    }[m]));

  const markerLayer = useCluster && typeof L.markerClusterGroup === "function"
    ? L.markerClusterGroup()
    : L.layerGroup();

  const bounds = [];
  let userLocationMarker = null;
  let userLocationCircle = null;

  function parseCoord(value) {
    if (value === null || value === undefined) return NaN;
    return parseFloat(String(value).replace(",", ".").trim());
  }

  function buildPopupHtml(p, lat, lng) {
    const name = p.name || "Manastir";
    const meta = [p.city, p.region, p.eparchy].filter(Boolean).join(" • ");
    const slug = p.slug || null;
    const detailsUrl = slug ? `/manastiri/${slug}` : null;
    const gmUrl = `https://www.google.com/maps?q=${encodeURIComponent(lat + "," + lng)}`;

    return `
      <div class="mappopup">
        <div class="mappopup__title">${esc(name)}</div>
        ${meta ? `<div class="mappopup__meta">${esc(meta)}</div>` : ""}
        <div class="mappopup__actions">
          <a class="mappopup__btn" href="${gmUrl}" target="_blank" rel="noopener">Google Maps</a>
          ${detailsUrl ? `<a class="mappopup__btn mappopup__btn--ghost" href="${detailsUrl}">Detalji</a>` : ""}
        </div>
      </div>
    `;
  }

  for (const p of points) {
    const lat = parseCoord(p.latitude ?? p.lat);
    const lng = parseCoord(p.longitude ?? p.lng);

    if (!isFinite(lat) || !isFinite(lng)) continue;

    const name = p.name || "Manastir";
    const marker = L.marker([lat, lng]);

    marker.bindPopup(buildPopupHtml(p, lat, lng));

    marker.bindTooltip(esc(name), {
      permanent: false,
      direction: "top",
      offset: [0, -10],
      opacity: 0.95,
      className: "map-label"
    });

    markerLayer.addLayer(marker);
    bounds.push([lat, lng]);
  }

  markerLayer.addTo(map);

  function fitMapToBounds() {
    if (bounds.length) {
      map.fitBounds(bounds, {
        padding: [50, 50],
        maxZoom: 10
      });
    } else {
      map.setView(DEFAULT_CENTER, DEFAULT_ZOOM);
    }
  }

  fitMapToBounds();

  function toggleLegend(forceState = null) {
    const legend = document.querySelector("[data-map-legend]");
    if (!legend) return;

    if (forceState === true) {
      legend.hidden = false;
      return;
    }

    if (forceState === false) {
      legend.hidden = true;
      return;
    }

    legend.hidden = !legend.hidden;
  }

  function clearUserLocation() {
    if (userLocationMarker) {
      map.removeLayer(userLocationMarker);
      userLocationMarker = null;
    }

    if (userLocationCircle) {
      map.removeLayer(userLocationCircle);
      userLocationCircle = null;
    }
  }

  function showUserLocation(lat, lng, accuracy = null) {
    clearUserLocation();

    userLocationMarker = L.marker([lat, lng]).addTo(map);
    userLocationMarker.bindPopup("<strong>Tvoja lokacija</strong>").openPopup();

    if (accuracy && isFinite(accuracy)) {
      userLocationCircle = L.circle([lat, lng], {
        radius: accuracy
      }).addTo(map);
    }

    map.setView([lat, lng], 12, { animate: true });
  }

  map.on("locationfound", function (e) {
    showUserLocation(e.latlng.lat, e.latlng.lng, e.accuracy);
  });

  map.on("locationerror", function () {
    alert("Lokacija nije dostupna ili pristup lokaciji nije dozvoljen.");
  });

  document.addEventListener("click", function (e) {
    const btn = e.target.closest('[data-map-action="focus"]');
    if (!btn) return;

    const lat = parseCoord(btn.getAttribute("data-lat"));
    const lng = parseCoord(btn.getAttribute("data-lng"));
    const title = btn.getAttribute("data-title") || "Lokacija";

    if (!isFinite(lat) || !isFinite(lng)) return;

    map.setView([lat, lng], Math.max(map.getZoom(), 12), { animate: true });
    L.popup()
      .setLatLng([lat, lng])
      .setContent(`<strong>${esc(title)}</strong>`)
      .openOn(map);
  });

  document.addEventListener("click", function (e) {
    const btn = e.target.closest("[data-map-action]");
    if (!btn) return;

    const action = btn.getAttribute("data-map-action");

    switch (action) {
      case "zoom-in":
        map.zoomIn();
        break;

      case "zoom-out":
        map.zoomOut();
        break;

      case "fit":
        fitMapToBounds();
        break;

      case "legend":
        toggleLegend();
        break;

      case "legend-close":
        toggleLegend(false);
        break;

      case "locate":
        map.locate({
          setView: false,
          maxZoom: 12,
          enableHighAccuracy: true
        });
        break;

      default:
        break;
    }
  });

  document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
      toggleLegend(false);
    }
  });
});