@extends('layouts.site')

@section('title', 'Loza Nemanjića — Pravoslavni Svetionik')
@section('nav_edukacija', 'active')

@section('content')
<section class="section nm-gene-page">
  <div class="container">

    <div class="nm-gene-hero">
      <div>
        <span class="nm-gene-kicker">Dinastija • Edukacija</span>
        <h1>Genealogija Nemanjića</h1>
        <p>
          Pregled porodičnih veza dinastije Nemanjić u klasičnom obliku genealogije.
          Klik na ime otvara profil u delu Ktitori, gde profil postoji.
        </p>
      </div>

      <a href="{{ route('edukacija.ucenje-interakcija') }}" class="nm-gene-back">
        ← Nazad na učenje i interakciju
      </a>
    </div>

    <div class="nm-gene-card">
      <svg class="nm-gene-svg" viewBox="0 0 1200 900" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Genealogija Nemanjića">
        <defs>
          <style>
            .g-title{font:700 26px system-ui, sans-serif; fill:#f7f2e7}
            .g-name{font:700 16px system-ui, sans-serif; fill:#f7f2e7}
            .g-small{font:500 12px system-ui, sans-serif; fill:#d8d1c3}
            .g-line{stroke:#c5a24a; stroke-width:2; fill:none; opacity:.95}
            .g-box{fill:rgba(255,255,255,.03); stroke:rgba(197,162,74,.45); stroke-width:1.7}
            .g-box2{fill:rgba(255,255,255,.02); stroke:rgba(255,255,255,.25); stroke-width:1.4}
            .g-link text{transition:opacity .2s ease}
            .g-link:hover text{opacity:.85}
            .g-link:hover rect{stroke:#e2c26a}
          </style>
        </defs>

        <!-- naslov -->
        <text x="600" y="42" text-anchor="middle" class="g-title">Genealogija Nemanjića</text>

        <!-- ZAVIDA -->
        <a href="{{ route('ktitors.show', 'zavida') }}" class="g-link">
          <text x="600" y="88" text-anchor="middle" class="g-name">Zavida</text>
        </a>

        <!-- linija od Zavide -->
        <line x1="600" y1="100" x2="600" y2="126" class="g-line"/>
        <line x1="250" y1="126" x2="950" y2="126" class="g-line"/>

        <!-- 1. generacija -->
        <line x1="250" y1="126" x2="250" y2="154" class="g-line"/>
        <line x1="450" y1="126" x2="450" y2="154" class="g-line"/>
        <line x1="700" y1="126" x2="700" y2="154" class="g-line"/>
        <line x1="950" y1="126" x2="950" y2="154" class="g-line"/>

        <a href="{{ route('ktitors.show', 'tihomir') }}" class="g-link">
          <text x="250" y="178" text-anchor="middle" class="g-name">Tihomir</text>
        </a>

        <a href="{{ route('ktitors.show', 'stracimir') }}" class="g-link">
          <text x="450" y="178" text-anchor="middle" class="g-name">Stracimir</text>
        </a>

        <a href="{{ route('ktitors.show', 'miroslav') }}" class="g-link">
          <text x="700" y="178" text-anchor="middle" class="g-name">Miroslav</text>
        </a>

        <a href="{{ route('ktitors.show', 'stefan-nemanja') }}" class="g-link">
          <text x="950" y="172" text-anchor="middle" class="g-name">Nemanja</text>
          <text x="950" y="192" text-anchor="middle" class="g-small">veliki župan</text>
          <text x="950" y="208" text-anchor="middle" class="g-small">vladao: 1170–1196.</text>
        </a>

        <!-- od Nemanje ka deci -->
        <line x1="950" y1="220" x2="950" y2="250" class="g-line"/>
        <line x1="180" y1="250" x2="1120" y2="250" class="g-line"/>

        <line x1="180" y1="250" x2="180" y2="278" class="g-line"/>
        <line x1="600" y1="250" x2="600" y2="278" class="g-line"/>
        <line x1="1120" y1="250" x2="1120" y2="278" class="g-line"/>

        <!-- 2. generacija -->
        <a href="{{ route('ktitors.show', 'vukan-nemanjic') }}" class="g-link">
          <text x="180" y="300" text-anchor="middle" class="g-name">Vukan</text>
          <text x="180" y="318" text-anchor="middle" class="g-small">zetski kralj</text>
          <text x="180" y="334" text-anchor="middle" class="g-small">vladao: 1195–1208.</text>
        </a>

        <a href="{{ route('ktitors.show', 'stefan-prvovencani') }}" class="g-link">
          <text x="600" y="300" text-anchor="middle" class="g-name">Stefan Prvovenčani</text>
          <text x="600" y="318" text-anchor="middle" class="g-small">Kralj Raške</text>
          <text x="600" y="334" text-anchor="middle" class="g-small">vladao: 1196–1227.</text>
        </a>

        <a href="{{ route('ktitors.show', 'sveti-sava') }}" class="g-link">
          <text x="1120" y="300" text-anchor="middle" class="g-name">Rastko (Sava)</text>
          <text x="1120" y="318" text-anchor="middle" class="g-small">prvi srpski</text>
          <text x="1120" y="334" text-anchor="middle" class="g-small">arhiepiskop</text>
        </a>

        <!-- Vukan -> Dmitar -->
        <line x1="180" y1="346" x2="180" y2="374" class="g-line"/>
        <a href="{{ route('ktitors.show', 'dmitar') }}" class="g-link">
          <text x="180" y="396" text-anchor="middle" class="g-name">Dmitar</text>
        </a>

        <!-- Stefan Prvovenčani -> deca -->
        <line x1="600" y1="346" x2="600" y2="374" class="g-line"/>
        <line x1="430" y1="374" x2="770" y2="374" class="g-line"/>
        <line x1="430" y1="374" x2="430" y2="402" class="g-line"/>
        <line x1="600" y1="374" x2="600" y2="402" class="g-line"/>
        <line x1="770" y1="374" x2="770" y2="402" class="g-line"/>

        <a href="{{ route('ktitors.show', 'stefan-radoslav') }}" class="g-link">
          <text x="430" y="424" text-anchor="middle" class="g-name">Radoslav</text>
          <text x="430" y="442" text-anchor="middle" class="g-small">kralj 1227–1234.</text>
        </a>

        <a href="{{ route('ktitors.show', 'stefan-vladislav') }}" class="g-link">
          <text x="600" y="424" text-anchor="middle" class="g-name">Vladislav</text>
          <text x="600" y="442" text-anchor="middle" class="g-small">kralj 1234–1243.</text>
        </a>

        <a href="{{ route('ktitors.show', 'stefan-uros-i') }}" class="g-link">
          <text x="770" y="424" text-anchor="middle" class="g-name">Uroš I</text>
          <text x="770" y="442" text-anchor="middle" class="g-small">kralj 1243–1276.</text>
        </a>

        <!-- Dmitar -> Vratislav -> Vratko -> Milica -->
        <line x1="180" y1="408" x2="180" y2="438" class="g-line"/>
        <a href="{{ route('ktitors.show', 'vratislav') }}" class="g-link">
          <text x="180" y="460" text-anchor="middle" class="g-name">Vratislav</text>
        </a>

        <line x1="180" y1="470" x2="180" y2="500" class="g-line"/>
        <a href="{{ route('ktitors.show', 'vratko') }}" class="g-link">
          <text x="180" y="522" text-anchor="middle" class="g-name">Vratko</text>
        </a>

        <line x1="180" y1="532" x2="180" y2="562" class="g-line"/>
        <a href="{{ route('ktitors.show', 'kneginja-milica') }}" class="g-link">
          <text x="180" y="584" text-anchor="middle" class="g-name">Milica</text>
          <text x="180" y="602" text-anchor="middle" class="g-small">žena Lazara</text>
          <text x="180" y="618" text-anchor="middle" class="g-small">Hrebeljanovića</text>
        </a>

        <!-- od Uroša I ka Dragutin / Milutin -->
        <line x1="770" y1="454" x2="770" y2="484" class="g-line"/>
        <line x1="530" y1="484" x2="1010" y2="484" class="g-line"/>
        <line x1="530" y1="484" x2="530" y2="512" class="g-line"/>
        <line x1="1010" y1="484" x2="1010" y2="512" class="g-line"/>

        <a href="{{ route('ktitors.show', 'stefan-dragutin') }}" class="g-link">
          <text x="530" y="534" text-anchor="middle" class="g-name">Dragutin</text>
          <text x="530" y="552" text-anchor="middle" class="g-small">kralj: 1276–1282.</text>
        </a>

        <a href="{{ route('ktitors.show', 'stefan-milutin') }}" class="g-link">
          <text x="1010" y="534" text-anchor="middle" class="g-name">Milutin</text>
          <text x="1010" y="552" text-anchor="middle" class="g-small">kralj 1282–1321.</text>
        </a>

        <!-- Dragutin children -->
        <line x1="530" y1="564" x2="530" y2="594" class="g-line"/>
        <line x1="430" y1="594" x2="630" y2="594" class="g-line"/>
        <line x1="430" y1="594" x2="430" y2="622" class="g-line"/>
        <line x1="630" y1="594" x2="630" y2="622" class="g-line"/>

        <a href="{{ route('ktitors.show', 'vladislav-ii') }}" class="g-link">
          <text x="430" y="644" text-anchor="middle" class="g-name">Vladislav</text>
        </a>

        <a href="{{ route('ktitors.show', 'konstantin') }}" class="g-link">
          <text x="630" y="644" text-anchor="middle" class="g-name">Konstantin</text>
        </a>

        <!-- Milutin children -->
        <line x1="1010" y1="564" x2="1010" y2="594" class="g-line"/>
        <line x1="900" y1="594" x2="1120" y2="594" class="g-line"/>
        <line x1="1120" y1="594" x2="1120" y2="622" class="g-line"/>

        <a href="{{ route('ktitors.show', 'stefan-decanski') }}" class="g-link">
          <text x="1120" y="644" text-anchor="middle" class="g-name">Stefan Dečanski</text>
          <text x="1120" y="662" text-anchor="middle" class="g-small">kralj: 1321–1331.</text>
        </a>

        <!-- Vladislav -> Simeon -->
        <line x1="430" y1="654" x2="430" y2="684" class="g-line"/>
        <a href="{{ route('ktitors.show', 'simeon') }}" class="g-link">
          <text x="430" y="706" text-anchor="middle" class="g-name">Simeon</text>
        </a>

        <!-- Decanski -> Dusan -->
        <line x1="1120" y1="674" x2="1120" y2="704" class="g-line"/>
        <a href="{{ route('ktitors.show', 'car-dusan') }}" class="g-link">
          <text x="1120" y="726" text-anchor="middle" class="g-name">Dušan</text>
          <text x="1120" y="744" text-anchor="middle" class="g-small">kralj: 1331–1345.</text>
          <text x="1120" y="760" text-anchor="middle" class="g-small">car: 1346–1355.</text>
        </a>

        <!-- Dusan -> Uros V -->
        <line x1="1120" y1="772" x2="1120" y2="802" class="g-line"/>
        <a href="{{ route('ktitors.show', 'stefan-uros-v') }}" class="g-link">
          <text x="1120" y="824" text-anchor="middle" class="g-name">Uroš II</text>
          <text x="1120" y="842" text-anchor="middle" class="g-small">car 1355–1371.</text>
        </a>
      </svg>
    </div>

  </div>
</section>



<style>
/* ===== GENEALOGIJA NEMANJIĆA — NASLOV I BOJE ===== */

.nm-gene-page .nm-gene-hero{
  align-items:flex-start !important;
}

.nm-gene-page .nm-gene-kicker{
  display:inline-flex !important;
  align-items:center !important;
  justify-content:center !important;
  padding:8px 12px !important;
  border-radius:999px !important;
  border:1px solid rgba(197,162,74,.22) !important;
  background:rgba(197,162,74,.08) !important;
  color:#e2c26a !important;
  font-size:.86rem !important;
  font-weight:700 !important;
  line-height:1 !important;
}

.nm-gene-page .nm-gene-hero h1{
  margin:12px 0 12px !important;
  font-size:clamp(1.9rem, 2.6vw, 2.45rem) !important;
  line-height:1.06 !important;
  letter-spacing:-.02em !important;
  font-weight:800 !important;
  color:#c5a24a !important;
  text-shadow:0 0 14px rgba(197,162,74,.16) !important;
}

.nm-gene-page .nm-gene-hero p{
  margin:0 !important;
  max-width:920px !important;
  color:rgba(255,255,255,.82) !important;
  line-height:1.8 !important;
  font-size:.98rem !important;
}

.nm-gene-page .nm-gene-back{
  display:inline-flex !important;
  align-items:center !important;
  justify-content:center !important;
  min-height:44px !important;
  padding:0 16px !important;
  border-radius:14px !important;
  text-decoration:none !important;
  font-weight:700 !important;
  color:#fff !important;
  border:1px solid rgba(255,255,255,.10) !important;
  background:rgba(255,255,255,.03) !important;
  transition:all .2s ease !important;
}

.nm-gene-page .nm-gene-back:hover{
  transform:translateY(-1px) !important;
  border-color:rgba(197,162,74,.35) !important;
  background:rgba(197,162,74,.10) !important;
  color:#f0d78f !important;
}

/* unutrašnji naslov u SVG kartici */
.nm-gene-page .nm-gene-svg .g-title{
  fill:#c5a24a !important;
}

.nm-gene-page .nm-gene-svg .g-name{
  fill:#f7f2e7 !important;
}

.nm-gene-page .nm-gene-svg .g-small{
  fill:#d8d1c3 !important;
}

@media (max-width: 640px){
  .nm-gene-page .nm-gene-hero h1{
    font-size:clamp(1.55rem, 7vw, 1.95rem) !important;
  }
}
</style>
@endsection