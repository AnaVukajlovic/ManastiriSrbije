@extends('layouts.site')

@section('title', 'Loza Nemanjića — Pravoslavni Svetionik')
@section('nav_edukacija', 'active')

@section('content')
<section class="section nm-gene-page">
  <div class="container">
    <div class="nm-gene-hero">
      <div class="nm-hero-header-row">
        <div class="nm-hero-titles">
          <span class="nm-gene-kicker">Dinastija • Edukacija</span>
          <h1>Genealogija Nemanjića</h1>
        </div>
        <a href="{{ route('edukacija.ucenje-interakcija') }}" class="nm-gene-back">
          ← Nazad na učenje
        </a>
      </div>

      <p class="nm-hero-lead-text">
        U ovoj obrazovnoj celini detaljno je prikazana genealogija dinastije Nemanjić, jer su upravo njeni vladari i članovi svete loze ostali upamćeni kao najveći zadužbinari u celokupnoj srpskoj istoriji. Tokom više od dva veka vladavine (1166—1371), Nemanjići su podigli najznačajnije srpske manastire — od Studenice, Hilandara i Žiče, preko Mileševe i Sopoćana, do Gračanice i Visokih Dečana. Njihovim zadužbinarskim radom utemeljena je autokefalna Srpska crkva (1219), stvorena vrhunska srednjovekovna umetnost i arhitektura, te postavljen trajni duhovni, pravni i kulturni temelj našeg naroda. Klikom na uokvirene kartice vladara i ktitora možete otvoriti detaljne istorijske profile sa podacima o njihovom životu, vladavini i zadužbinama.
      </p>
    </div>

    {{-- TOOLBAR: LEGENDA I ZOOM KONTROLE --}}
    <div class="nm-tree-toolbar">
      <div class="nm-tree-legend">
        <span class="nm-legend-item"><span class="nm-legend-dot dot-ruler"></span> Vladari i knezovi</span>
        <span class="nm-legend-item"><span class="nm-legend-dot dot-gold"></span> Srpski car</span>
        <span class="nm-legend-item"><span class="nm-legend-dot dot-church"></span> Arhiepiskop</span>
        <span class="nm-legend-item"><span class="nm-legend-dot dot-static"></span> Dinastička loza</span>
      </div>

      <div class="nm-tree-zoom-box">
        <span class="nm-zoom-title">🔍 Zoom:</span>
        <button type="button" class="nm-zoom-btn" id="nmZoomOut" title="Umanji stablo">−</button>
        <button type="button" class="nm-zoom-btn nm-zoom-val" id="nmZoomReset" title="Resetuj na 100%">100%</button>
        <button type="button" class="nm-zoom-btn" id="nmZoomIn" title="Uvećaj stablo">+</button>
      </div>
    </div>
  </div>

  <div class="nm-tree-full-width">
    <div class="nm-tree-wrapper" id="nmTreeWrapper">
      <div class="nm-tree">
        <ul>
          <li>
            <a href="{{ route('ktitors.show', 'stefan-nemanja') }}" class="nm-tree-card style-ruler text-clickable">
              <div class="nm-vladar-name">Stefan Nemanja</div>
              <div class="nm-vladar-title">Veliki Župan Raške</div>
              <div class="nm-vladar-date">1166—1196.</div>
            </a>
            
            <ul>
              <li>
                <a href="{{ route('ktitors.show', 'vukan-nemanjic') }}" class="nm-tree-card style-ruler text-clickable">
                  <div class="nm-vladar-name">Vukan Nemanjić</div>
                  <div class="nm-vladar-title">Kralj Duklje i Zete</div>
                  <div class="nm-vladar-date">1195—1208.</div>
                </a>
                <ul>
                  <li>
                    <a href="#" class="nm-tree-card style-static">
                      <div class="nm-vladar-name">Dmitar Nemanjić</div>
                      <div class="nm-vladar-title">Župan (Monah David)</div>
                    </a>
                    <ul>
                      <li>
                        <a href="#" class="nm-tree-card style-static">
                          <div class="nm-vladar-name">Vratislav Nemanjić</div>
                          <div class="nm-vladar-title">Knez Raške</div>
                        </a>
                        <ul>
                          <li>
                            <a href="#" class="nm-tree-card style-static">
                              <div class="nm-vladar-name">Vratko Nemanjić</div>
                              <div class="nm-vladar-title">Vojvoda (Jug Bogdan)</div>
                            </a>
                            <ul>
                              <li>
                                <a href="{{ route('ktitors.show', 'kneginja-milica') }}" class="nm-tree-card text-clickable">
                                  <div class="nm-vladar-name">Kneginja Milica</div>
                                  <div class="nm-vladar-title">Vladarka Srbije</div>
                                  <div class="nm-vladar-date">1389—1405.</div>
                                </a>
                              </li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                </ul>
              </li>

              <li>
                <a href="{{ route('ktitors.show', 'stefan-prvovencani') }}" class="nm-tree-card style-ruler text-clickable">
                  <div class="nm-vladar-name">Stefan Prvovenčani</div>
                  <div class="nm-vladar-title">Kralj Raške (Sveti Simon)</div>
                  <div class="nm-vladar-date">1196—1228.</div>
                </a>
                
                <ul>
                  <li>
                    <a href="{{ route('ktitors.show', 'stefan-radoslav') }}" class="nm-tree-card text-clickable">
                      <div class="nm-vladar-name">Stefan Radoslav</div>
                      <div class="nm-vladar-title">Kralj Raške</div>
                      <div class="nm-vladar-date">1228—1233.</div>
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('ktitors.show', 'stefan-vladislav') }}" class="nm-tree-card text-clickable">
                      <div class="nm-vladar-name">Stefan Vladislav</div>
                      <div class="nm-vladar-title">Kralj Raške</div>
                      <div class="nm-vladar-date">1233—1243.</div>
                    </a>
                  </li>
                  <li>
                    <a href="{{ route('ktitors.show', 'stefan-uros-i') }}" class="nm-tree-card style-ruler text-clickable">
                      <div class="nm-vladar-name">Stefan Uroš I</div>
                      <div class="nm-vladar-title">Kralj Raške</div>
                      <div class="nm-vladar-date">1243—1276.</div>
                    </a>

                    <ul>
                      <li>
                        <a href="{{ route('ktitors.show', 'kralj-dragutin') }}" class="nm-tree-card text-clickable">
                          <div class="nm-vladar-name">Stefan Dragutin</div>
                          <div class="nm-vladar-title">Kralj Raške i Srema</div>
                          <div class="nm-vladar-date">1276—1282.</div>
                        </a>
                        <ul>
                          <li>
                            <a href="#" class="nm-tree-card style-static">
                              <div class="nm-vladar-name">Stefan Vladislav II</div>
                              <div class="nm-vladar-title">Kralj Srema</div>
                              <div class="nm-vladar-date">1316—1325.</div>
                            </a>
                          </li>
                        </ul>
                      </li>

                      <li>
                        <a href="{{ route('ktitors.show', 'kralj-milutin') }}" class="nm-tree-card style-ruler text-clickable">
                          <div class="nm-vladar-name">Stefan Uroš II Milutin</div>
                          <div class="nm-vladar-title">Kralj Raške</div>
                          <div class="nm-vladar-date">1282—1321.</div>
                        </a>
                        <ul>
                          <li>
                            <a href="#" class="nm-tree-card style-static">
                              <div class="nm-vladar-name">Konstantin Nemanjić</div>
                              <div class="nm-vladar-title">Protivkralj / Knez</div>
                            </a>
                          </li>
                          <li>
                            <a href="{{ route('ktitors.show', 'stefan-decanski') }}" class="nm-tree-card style-ruler text-clickable">
                              <div class="nm-vladar-name">Stefan Uroš III Dečanski</div>
                              <div class="nm-vladar-title">Kralj Srbije</div>
                              <div class="nm-vladar-date">1321—1331.</div>
                            </a>
                            
                            <ul>
                              <li>
                                <a href="{{ route('ktitors.show', 'car-dusan') }}" class="nm-tree-card style-gold text-clickable">
                                  <div class="nm-vladar-name">Stefan Uroš IV Dušan</div>
                                  <div class="nm-vladar-title">Kralj • Prvi Srpski Car</div>
                                  <div class="nm-vladar-date">1346—1355.</div>
                                </a>
                                <ul>
                                  <li>
                                    <a href="{{ route('ktitors.show', 'uros-nejaki') }}" class="nm-tree-card style-ruler text-clickable">
                                      <div class="nm-vladar-name">Stefan Uroš V Nejaki</div>
                                      <div class="nm-vladar-title">Car Srbije</div>
                                      <div class="nm-vladar-date">1355—1371.</div>
                                    </a>
                                  </li>
                                </ul>
                              </li>
                              <li>
                                <a href="#" class="nm-tree-card style-static">
                                  <div class="nm-vladar-name">Simeon (Siniša)</div>
                                  <div class="nm-vladar-title">Car Tesalije i Epira</div>
                                  <div class="nm-vladar-date">1359—1372.</div>
                                </a>
                              </li>
                            </ul>
                          </li>
                        </ul>
                      </li>
                    </ul>
                  </li>
                </ul>
              </li>

              <li>
                <a href="{{ route('ktitors.show', 'sveti-sava') }}" class="nm-tree-card style-church text-clickable">
                  <div class="nm-vladar-name">Rastko (Sveti Sava)</div>
                  <div class="nm-vladar-title">Prvi Srpski Arhiepiskop</div>
                  <div class="nm-vladar-date">1219—1236.</div>
                </a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </div>

</section>

<style>
/* ===== TOOLBAR ZA LEGENDA I ZOOM ===== */
.nm-tree-toolbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  flex-wrap: wrap;
  gap: 16px;
  margin-bottom: 20px;
  padding: 14px 20px;
  background: rgba(24, 18, 16, 0.7);
  border: 1px solid rgba(197, 162, 74, 0.22);
  border-radius: 16px;
  backdrop-filter: blur(8px);
}

.nm-tree-legend {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 16px;
}

.nm-legend-item {
  display: inline-flex;
  align-items: center;
  gap: 7px;
  font-size: 0.82rem;
  color: rgba(255, 255, 255, 0.78);
}

.nm-legend-dot {
  width: 12px;
  height: 12px;
  border-radius: 4px;
  display: inline-block;
}

.dot-ruler { background: #262119; border: 1.5px solid rgba(234, 182, 77, 0.7); }
.dot-gold { background: #2e2516; border: 1.5px solid #eab64d; box-shadow: 0 0 6px rgba(234, 182, 77, 0.4); }
.dot-church { background: #141c26; border: 1.5px solid rgba(104, 151, 204, 0.8); }
.dot-static { background: #181613; border: 1.5px solid rgba(255, 255, 255, 0.2); }

.nm-tree-zoom-box {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background: rgba(0, 0, 0, 0.35);
  padding: 4px 8px;
  border-radius: 12px;
  border: 1px solid rgba(197, 162, 74, 0.2);
}

.nm-zoom-title {
  font-size: 0.8rem;
  font-weight: 700;
  color: #e2c26a;
  margin-right: 4px;
}

.nm-zoom-btn {
  background: rgba(197, 162, 74, 0.12);
  border: 1px solid rgba(197, 162, 74, 0.3);
  color: #f0d78f;
  font-weight: 700;
  font-size: 0.88rem;
  width: 32px;
  height: 30px;
  border-radius: 8px;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: all 0.2s ease;
}

.nm-zoom-btn:hover {
  background: rgba(197, 162, 74, 0.25);
  border-color: #eab64d;
  color: #fff;
  transform: translateY(-1px);
}

.nm-zoom-btn.nm-zoom-val {
  width: auto;
  min-width: 54px;
  padding: 0 8px;
  font-size: 0.78rem;
}

/* ===== STRUKTURA I POZICIONIRANJE STABLA ===== */
.nm-tree-full-width {
  width: 100vw;
  position: relative;
  left: 50%;
  right: 50%;
  margin-left: -50vw;
  margin-right: -50vw;
  display: flex;
  justify-content: center;
  overflow-x: auto;
  overflow-y: hidden;
  padding: 10px 20px 30px 20px;
  -webkit-overflow-scrolling: touch;
  scrollbar-width: thin;
  scrollbar-color: rgba(197, 162, 74, 0.4) rgba(0, 0, 0, 0.2);
}

.nm-tree-full-width::-webkit-scrollbar {
  height: 8px;
}

.nm-tree-full-width::-webkit-scrollbar-track {
  background: rgba(18, 14, 12, 0.6);
  border-radius: 4px;
}

.nm-tree-full-width::-webkit-scrollbar-thumb {
  background: rgba(197, 162, 74, 0.45);
  border-radius: 4px;
}

.nm-tree-full-width::-webkit-scrollbar-thumb:hover {
  background: rgba(197, 162, 74, 0.7);
}

.nm-tree-wrapper {
  padding: 32px 20px;
  background: rgba(18, 16, 13, 0.35);
  border: 1px solid rgba(197, 162, 74, 0.2);
  border-radius: 24px;
  display: inline-flex;
  justify-content: center;
  margin-top: 10px;
  margin-bottom: 25px;
  box-shadow: 0 12px 35px rgba(0, 0, 0, 0.4);
  transform-origin: top center;
  transition: transform 0.2s cubic-bezier(0.2, 0, 0, 1);
}

.nm-tree ul {
  padding-top: 22px;
  position: relative;
  display: flex;
  justify-content: center;
  padding-left: 0;
  margin: 0;
}

.nm-tree li {
  text-align: center;
  list-style-type: none;
  position: relative;
  padding: 22px 3px 0 3px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

/* === LINIJE KOJE SPAJAJU KARTICE === */
.nm-tree li::before, .nm-tree li::after {
  content: '';
  position: absolute;
  top: 0;
  right: 50%;
  border-top: 1.5px solid #eab64d;
  width: 50%;
  height: 22px;
  z-index: 1;
}

.nm-tree li::after {
  right: auto;
  left: 50%;
  border-left: 1.5px solid #eab64d;
}

.nm-tree li:first-child::before, .nm-tree li:last-child::after {
  border: 0 none;
}

.nm-tree li:last-child::before {
  border-right: 1.5px solid #eab64d;
  border-radius: 0 4px 0 0;
}
.nm-tree li:first-child::after {
  border-radius: 4px 0 0 0;
}

.nm-tree li:only-child::after, .nm-tree li:only-child::before {
  display: none;
}
.nm-tree li:only-child {
  padding-top: 0;
}

.nm-tree ul ul::before {
  content: '';
  position: absolute;
  top: 0;
  left: 50%;
  border-left: 1.5px solid #eab64d;
  width: 0;
  height: 22px;
  margin-left: -0.75px;
}

/* ===== DIZAJN KARTICA (PROŠIRENE I JASNIJE) ===== */
.nm-tree-card {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  width: 154px !important;
  min-height: 102px !important;
  padding: 12px 8px;
  border-radius: 10px;
  text-align: center;
  text-decoration: none !important;
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.45);
  transition: all 0.25s cubic-bezier(0.25, 0.8, 0.25, 1);
  background: #1c1914;
  border: 1.2px solid rgba(255, 255, 255, 0.08);
  position: relative;
  z-index: 5;
  box-sizing: border-box;
}

.nm-tree-card.text-clickable { border-color: rgba(234, 182, 77, 0.55); cursor: pointer; }
.nm-tree-card.style-ruler { background: linear-gradient(145deg, rgba(38, 33, 25, 0.98), rgba(20, 17, 14, 0.98)); }
.nm-tree-card.style-church { border-color: rgba(104, 151, 204, 0.7); background: linear-gradient(145deg, rgba(20, 28, 38, 0.98), rgba(11, 16, 22, 0.98)); }
.nm-tree-card.style-gold { border: 1.8px solid #eab64d; background: linear-gradient(145deg, rgba(48, 38, 22, 0.98), rgba(24, 19, 12, 0.98)); box-shadow: 0 0 16px rgba(234, 182, 77, 0.25); }
.nm-tree-card.style-static { border-color: rgba(255, 255, 255, 0.08); background: rgba(24, 22, 19, 0.65); pointer-events: none; }

/* Tipografija kartica */
.nm-vladar-name { color: #fbf9f3; font-weight: 700; font-size: 0.86rem; line-height: 1.2; }
.nm-tree-card.style-gold .nm-vladar-name { color: #eab64d; }
.nm-tree-card.style-static .nm-vladar-name { color: rgba(255, 255, 255, 0.55); }
.nm-vladar-title { color: rgba(216, 209, 195, 0.7); font-size: 0.68rem; margin-top: 4px; font-weight: 500; line-height: 1.25; }
.nm-vladar-date { color: #eab64d; font-size: 0.68rem; font-weight: 700; margin-top: 3px; }

.nm-tree-card.text-clickable:hover { transform: translateY(-4px); border-color: #eab64d; box-shadow: 0 10px 24px rgba(234, 182, 77, 0.35); }

/* HERO PANEL */
.nm-gene-page .nm-gene-hero {
  display: flex;
  flex-direction: column;
  gap: 14px;
  margin-bottom: 24px;
  width: 100%;
}
.nm-hero-header-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 20px;
  width: 100%;
}
.nm-hero-titles {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
}
.nm-gene-page .nm-gene-kicker {
  display: inline-flex;
  padding: 5px 12px;
  border-radius: 999px;
  border: 1px solid rgba(197, 162, 74, 0.25);
  background: rgba(197, 162, 74, 0.08);
  color: #eab64d;
  font-size: 0.78rem;
  font-weight: 700;
  text-transform: uppercase;
}
.nm-gene-page .nm-gene-hero h1 {
  margin: 8px 0 0 0;
  font-size: clamp(1.8rem, 3.5vw, 2.3rem);
  font-weight: 800;
  color: #eab64d;
  line-height: 1.1;
}
.nm-gene-page .nm-gene-hero .nm-hero-lead-text {
  color: rgba(255, 255, 255, 0.88);
  font-size: 1rem;
  margin: 0;
  line-height: 1.85;
  width: 100%;
  max-width: 100%;
  text-align: justify;
  text-justify: inter-word;
}
.nm-gene-back {
  display: inline-flex;
  align-items: center;
  padding: 10px 18px;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 600;
  color: rgba(255, 255, 255, 0.85);
  border: 1px solid rgba(255, 255, 255, 0.1);
  background: rgba(255, 255, 255, 0.03);
  transition: all 0.2s ease;
  font-size: 0.88rem;
  white-space: nowrap;
}
.nm-gene-back:hover {
  border-color: rgba(197, 162, 74, 0.4);
  background: rgba(197, 162, 74, 0.1);
  color: #f0d78f;
}

@media (max-width: 768px) {
  .nm-hero-header-row {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }
  .nm-gene-back {
    width: 100%;
    justify-content: center;
  }
}

/* ===== MOBILNI PRIKAZ ===== */
@media (max-width: 1024px) {
  .nm-tree-toolbar {
    flex-direction: column;
    align-items: flex-start;
    gap: 12px;
  }
  .nm-tree-zoom-box {
    display: none;
  }
  .nm-tree-full-width {
    margin-left: 0;
    margin-right: 0;
    left: 0;
    right: 0;
    width: 100%;
    overflow-x: hidden;
    padding: 0;
  }
  .nm-tree-wrapper { border: none; background: none; padding: 0; margin-bottom: 20px; justify-content: flex-start; transform: none !important; }
  
  .nm-tree > ul { padding-left: 0; }
  .nm-tree > ul > li { border-left: none; padding-left: 0; }
  
  .nm-tree ul {
    flex-direction: column;
    padding-top: 0;
    padding-left: 20px;
    align-items: flex-start;
  }
  
  .nm-tree li {
    padding: 12px 0 0 15px;
    align-items: flex-start;
    border-left: 1.5px solid #eab64d;
  }
  
  .nm-tree li::before, .nm-tree li::after, .nm-tree ul ul::before {
    display: none !important;
  }
  
  .nm-tree li:only-child { padding-top: 12px; }
  
  .nm-tree-card {
    width: 240px !important;
    text-align: left;
    align-items: flex-start;
    min-height: auto !important;
    padding: 12px 14px;
  }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
  let currentScale = 1.0;
  const step = 0.12;
  const minScale = 0.76;
  const maxScale = 1.48;
  const treeWrapper = document.getElementById('nmTreeWrapper');
  const zoomValBtn = document.getElementById('nmZoomReset');
  const zoomInBtn = document.getElementById('nmZoomIn');
  const zoomOutBtn = document.getElementById('nmZoomOut');

  function updateZoom(val) {
    currentScale = Math.min(Math.max(val, minScale), maxScale);
    if (treeWrapper) {
      treeWrapper.style.transform = `scale(${currentScale})`;
    }
    if (zoomValBtn) {
      zoomValBtn.textContent = Math.round(currentScale * 100) + '%';
    }
  }

  if (zoomInBtn) {
    zoomInBtn.addEventListener('click', function() {
      updateZoom(currentScale + step);
    });
  }
  if (zoomOutBtn) {
    zoomOutBtn.addEventListener('click', function() {
      updateZoom(currentScale - step);
    });
  }
  if (zoomValBtn) {
    zoomValBtn.addEventListener('click', function() {
      updateZoom(1.0);
    });
  }
});
</script>
@endsection