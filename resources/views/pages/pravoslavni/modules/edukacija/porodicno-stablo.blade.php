@extends('layouts.site')

@section('title', 'Loza Nemanjića — Pravoslavni Svetionik')
@section('nav_edukacija', 'active')

@section('content')
<section class="section nm-gene-page">
  <div class="container">
    <div class="nm-gene-hero">
      <div class="nm-hero-text-block">
        <span class="nm-gene-kicker">Dinastija • Edukacija</span>
        <h1>Genealogija Nemanjića</h1>
        <p class="nm-hero-lead-text">Povijesni pregled muške loze Nemanjića. Poveznice sa zlatnim i plavim okvirom vode na detaljan profil ktitora.</p>
      </div>

      <a href="{{ route('edukacija.ucenje-interakcija') }}" class="nm-gene-back">
        ← Nazad na učenje
      </a>
    </div>
  </div>

  <div class="nm-tree-full-width">
    <div class="nm-tree-wrapper">
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
                <a href="{{ route('ktitors.show', 'vukan-nemanjic') }}" class="nm-tree-card text-clickable">
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
                        <a href="{{ route('ktitors.show', 'stefan-dragutin') }}" class="nm-tree-card text-clickable">
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
                        <a href="{{ route('ktitors.show', 'stefan-milutin') }}" class="nm-tree-card style-ruler text-clickable">
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
                                    <a href="{{ route('ktitors.show', 'stefan-uros-v') }}" class="nm-tree-card style-ruler text-clickable">
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
/* ===== ČISTA CSS ARHITEKTURA STABLA BEZ SKROLBARA ===== */

.nm-tree-full-width {
  width: 100vw;
  position: relative;
  left: 50%;
  right: 50%;
  margin-left: -50vw;
  margin-right: -50vw;
  display: flex;
  justify-content: center;
  overflow: hidden;
}

.nm-tree-wrapper {
  padding: 30px 10px;
  background: rgba(18, 16, 13, 0.25);
  border: 1px solid rgba(197, 162, 74, 0.15);
  border-radius: 24px;
  display: inline-flex;
  justify-content: center;
  margin-top: 10px;
  margin-bottom: 40px;
}

.nm-tree ul {
  padding-top: 20px;
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
  padding: 20px 3px 0 3px;
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
  height: 20px;
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
  height: 20px;
  margin-left: -0.75px;
}

/* ===== DIZAJN KARTICA ===== */
.nm-tree-card {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  width: 138px !important;
  min-height: 95px !important;
  padding: 10px 6px;
  border-radius: 8px;
  text-align: center;
  text-decoration: none !important;
  box-shadow: 0 8px 18px rgba(0, 0, 0, 0.4);
  transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
  background: #1c1914;
  border: 1px solid rgba(255, 255, 255, 0.08);
  position: relative;
  z-index: 5;
}

.nm-tree-card.text-clickable { border-color: rgba(234, 182, 77, 0.5); cursor: pointer; }
.nm-tree-card.style-ruler { background: linear-gradient(145deg, rgba(38, 33, 25, 0.98), rgba(20, 17, 14, 0.98)); }
.nm-tree-card.style-church { border-color: rgba(104, 151, 204, 0.6); background: linear-gradient(145deg, rgba(20, 28, 38, 0.98), rgba(11, 16, 22, 0.98)); }
.nm-tree-card.style-gold { border: 1.5px solid #eab64d; background: linear-gradient(145deg, rgba(46, 37, 22, 0.98), rgba(22, 18, 11, 0.98)); box-shadow: 0 0 15px rgba(234, 182, 77, 0.2); }
.nm-tree-card.style-static { border-color: rgba(255, 255, 255, 0.08); background: rgba(24, 22, 19, 0.6); pointer-events: none; }

/* Tipografija */
.nm-vladar-name { color: #fbf9f3; font-weight: 700; font-size: 0.78rem; line-height: 1.15; }
.nm-tree-card.style-gold .nm-vladar-name { color: #eab64d; }
.nm-tree-card.style-static .nm-vladar-name { color: rgba(255, 255, 255, 0.5); }
.nm-vladar-title { color: rgba(216, 209, 195, 0.6); font-size: 0.6rem; margin-top: 3px; font-weight: 500; }
.nm-vladar-date { color: #eab64d; font-size: 0.6rem; font-weight: 700; margin-top: 3px; }

.nm-tree-card.text-clickable:hover { transform: translateY(-4px); border-color: #eab64d; box-shadow: 0 8px 20px rgba(234, 182, 77, 0.3); }

/* HERO PANEL */
.nm-gene-page .nm-gene-hero { display: flex; justify-content: space-between; align-items: center; gap: 30px; margin-bottom: 35px; width: 100%; }
.nm-hero-text-block { display: flex; flex-direction: column; align-items: flex-start; flex: 1; }
.nm-gene-page .nm-gene-kicker { display: inline-flex; padding: 5px 12px; border-radius: 999px; border: 1px solid rgba(197, 162, 74, 0.2); background: rgba(197, 162, 74, 0.05); color: #eab64d; font-size: 0.78rem; font-weight: 700; text-transform: uppercase; }
.nm-gene-page .nm-gene-hero h1 { margin: 10px 0 6px 0; font-size: clamp(1.8rem, 3.5vw, 2.3rem); font-weight: 800; color: #eab64d; line-height: 1.1; }
.nm-gene-page .nm-gene-hero .nm-hero-lead-text { color: rgba(255, 255, 255, 0.65); font-size: 0.95rem; margin: 0; line-height: 1.4; max-width: 850px; }
.nm-gene-back { display: inline-flex; align-items: center; padding: 10px 18px; border-radius: 10px; text-decoration: none; font-weight: 600; color: rgba(255, 255, 255, 0.85); border: 1px solid rgba(255, 255, 255, 0.08); background: rgba(255, 255, 255, 0.02); transition: all 0.2s ease; font-size: 0.88rem; white-space: nowrap; }

/* ===== AUTOMATSKI TIMELINE PRIKAZ ZA MOBITELE ===== */
@media (max-width: 1024px) {
  .nm-tree-full-width {
    margin-left: 0;
    margin-right: 0;
    left: 0;
    right: 0;
    width: 100%;
    overflow-x: hidden;
  }
  .nm-tree-wrapper { border: none; background: none; padding: 0; margin-bottom: 20px; justify-content: flex-start; }
  
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
    width: 220px !important;
    text-align: left;
    align-items: flex-start;
  }
}
</style>
@endsection