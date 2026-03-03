document.addEventListener("DOMContentLoaded", function () {
  const mapEl = document.getElementById("map");
  if (!mapEl) return;

  const data = window.MAP_PAGE || {};
  const points = Array.isArray(data.points) ? data.points : [];
  const opts = data.options || {};
  const useCluster = !!opts.cluster;

  const map = L.map("map", { scrollWheelZoom: true }).setView([44.0165, 21.0059], 7);

  // Layers
  const osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
    maxZoom: 19,
    attribution: "&copy; OpenStreetMap"
  });

  const satellite = L.tileLayer(
    "https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}",
    { maxZoom: 19, attribution: "Tiles © Esri" }
  );

  const terrain = L.tileLayer("https://{s}.tile.opentopomap.org/{z}/{x}/{y}.png", {
    maxZoom: 17,
    attribution: "© OpenTopoMap"
  });

  osm.addTo(map);
  L.control.layers({ "Mapa": osm, "Satelit": satellite, "Reljef": terrain }).addTo(map);

  const esc = (s) => String(s ?? "").replace(/[&<>"']/g, (m) => ({
    "&": "&amp;", "<": "&lt;", ">": "&gt;", '"': "&quot;", "'": "&#039;"
  }[m]));

  const layer = useCluster ? L.markerClusterGroup() : L.layerGroup();
  const bounds = [];

  for (const p of points) {
    // ✅ BITNO: parseFloat jer često dolazi kao string iz JSON-a
    const lat = parseFloat(p.lat);
    const lng = parseFloat(p.lng);
    if (!isFinite(lat) || !isFinite(lng)) continue;

    const name = p.name || "Manastir";
    const marker = L.marker([lat, lng]);

    const meta = [p.city, p.region, p.eparchy].filter(Boolean).join(" • ");
    const gmUrl = `https://www.google.com/maps?q=${encodeURIComponent(lat + "," + lng)}`;

    // Popup
    marker.bindPopup(`
      <strong>${esc(name)}</strong><br>
      ${meta ? `<span class="muted">${esc(meta)}</span><br>` : ""}
      <a href="${gmUrl}" target="_blank" rel="noopener">Otvori u Google Maps</a>
    `);

    // Klik -> Google Maps (novi tab)
    marker.on("click", () => {
      window.open(gmUrl, "_blank", "noopener");
    });

    // ✅ NAZIV STALNO PORED MARKERA (permanent:true)
    marker.bindTooltip(name, {
      permanent: true,          // <- OVO je glavno
      direction: "right",       // desno od tačke (može "top")
      offset: [12, 0],          // malo odmaknuto
      opacity: 0.95,
      className: "map-label"
    });

    layer.addLayer(marker);
    bounds.push([lat, lng]);
  }

  layer.addTo(map);

  if (bounds.length) {
    map.fitBounds(bounds, { padding: [60, 60], maxZoom: 10 });
  }

  // Focus dugme iz liste
  document.addEventListener("click", (e) => {
    const btn = e.target.closest('[data-map-action="focus"]');
    if (!btn) return;

    const lat = parseFloat(btn.getAttribute("data-lat"));
    const lng = parseFloat(btn.getAttribute("data-lng"));
    const title = btn.getAttribute("data-title") || "Lokacija";

    if (!isFinite(lat) || !isFinite(lng)) return;

    map.setView([lat, lng], Math.max(map.getZoom(), 12), { animate: true });
    L.popup().setLatLng([lat, lng]).setContent(`<strong>${esc(title)}</strong>`).openOn(map);
  });

  // Toolbar (zoom/fit/legend/locate)
  document.addEventListener("click", (e) => {
    const btn = e.target.closest("[data-map-action]");
    if (!btn) return;

    const action = btn.getAttribute("data-map-action");

    if (action === "zoom-in") map.zoomIn();
    if (action === "zoom-out") map.zoomOut();
    if (action === "fit" && bounds.length) map.fitBounds(bounds, { padding: [60, 60], maxZoom: 10 });

    if (action === "legend") {
      const el = document.querySelector("[data-map-legend]");
      if (el) el.hidden = false;
    }
    if (action === "legend-close") {
      const el = document.querySelector("[data-map-legend]");
      if (el) el.hidden = true;
    }

    if (action === "locate") {
      map.locate({ setView: true, maxZoom: 12 });
    }
  });
});