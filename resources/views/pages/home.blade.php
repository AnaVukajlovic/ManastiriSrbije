@extends('layouts.app')
@section('title', 'Početna')

@section('content')
  <div class="home-wrap">

    <section class="hero card hero-card">
      <div class="hero-top">
        <span class="pill">☦ Mir, istorija i kultura</span>
        <h1 class="hero-title">Manastiri Srbije</h1>
        <p class="hero-text">
          Pretraži manastire, istraži na mapi, uči kroz edukaciju i pitaj AI — sve na jednom mestu.
        </p>

        <div class="hero-actions">
          <a class="btn2" href="{{ route('monasteries.index') }}">🏛 Pregled manastira</a>
          <a class="btn2" href="{{ route('map.index') }}">🗺 Otvori mapu</a>
          <a class="btn2" href="{{ route('ai.index') }}">🤖 Pitaj AI</a>
        </div>
      </div>

      <div class="hero-img"></div>
    </section>

    <section class="card quick-card">
      <h3 class="section-title">Brzi pristup</h3>
      <div class="quick-grid">
        <a class="quick" href="{{ route('monasteries.index') }}">
          <div class="quick-ico">🏛</div>
          <div>
            <div class="quick-title">Manastiri</div>
            <div class="quick-sub">Lista, filteri, detalji</div>
          </div>
        </a>

        <a class="quick" href="{{ route('ktitors.index') }}">
          <div class="quick-ico">👑</div>
          <div>
            <div class="quick-title">Ktitori</div>
            <div class="quick-sub">Zadužbinari i priče</div>
          </div>
        </a>

        <a class="quick" href="{{ route('map.index') }}">
          <div class="quick-ico">🗺</div>
          <div>
            <div class="quick-title">Mapa</div>
            <div class="quick-sub">Manastiri po lokaciji</div>
          </div>
        </a>

        <a class="quick" href="{{ route('education.index') }}">
          <div class="quick-ico">📚</div>
          <div>
            <div class="quick-title">Edukacija</div>
            <div class="quick-sub">Kvizovi, lekcije</div>
          </div>
        </a>

        <a class="quick" href="{{ route('orthodox.index') }}">
          <div class="quick-ico">✝️</div>
          <div>
            <div class="quick-title">Pravoslavni sadržaj</div>
            <div class="quick-sub">Kalendar, post, citati</div>
          </div>
        </a>

        <a class="quick" href="{{ route('tours.index') }}">
          <div class="quick-ico">🧭</div>
          <div>
            <div class="quick-title">360° ture</div>
            <div class="quick-sub">Virtuelne posete</div>
          </div>
        </a>
      </div>
    </section>

    <section class="card featured-card">
      <div class="featured-head">
        <h3 class="section-title" style="margin:0;">Izdvojeni manastiri</h3>
        <a class="link" href="{{ route('monasteries.index') }}">Vidi sve →</a>
      </div>

      <div id="featuredGrid" class="grid"></div>
      <div id="featuredHint" class="muted" style="margin-top:10px;">Učitavanje...</div>
    </section>

  </div>

  <script>
    async function loadFeatured(){
      const res = await fetch('/api/monasteries?per_page=6&sort=name&dir=asc', {
        headers: { 'Accept': 'application/json' }
      });
      const json = await res.json();

      const meta = json.data;
      const items = meta.data || [];

      const grid = document.getElementById('featuredGrid');
      const hint = document.getElementById('featuredHint');

      if (!items.length){
        hint.textContent = 'Nema podataka još. Dodaj manastire u bazu.';
        return;
      }

      hint.style.display = 'none';
      grid.innerHTML = items.map(m => `
        <a class="card" href="/manastiri/${m.slug}">
          <div class="thumb">
            <div class="thumb-badge">${m.region ?? ''}</div>
          </div>
          <div class="card-body">
            <div class="card-title">${m.name}</div>
            <div class="card-sub">${m.city ?? ''}</div>
            <div class="card-desc">${m.description ?? ''}</div>
          </div>
        </a>
      `).join('');
    }

    loadFeatured();
  </script>
@endsection
