@extends('layouts.app')
@section('title','Edukacija')

@section('content')
<div class="card">
  <h1>Edukacija</h1>
  <p class="muted">Učenje o istoriji, arhitekturi, freskama i kulturi. Izaberi kategoriju.</p>

  <div id="grid" class="grid" style="margin-top:12px"></div>
  <div id="empty" class="muted" style="margin-top:12px; display:none">Još nema sadržaja.</div>
</div>

<script>
(async function(){
  const grid = document.getElementById('grid');
  const empty = document.getElementById('empty');

  grid.innerHTML = `<div class="card"><div class="card-body"><div class="card-title">Učitavam…</div></div></div>`;

  const res = await fetch('/api/content/categories?module=education', { headers:{'Accept':'application/json'}});
  const json = await res.json();
  const cats = json.data || [];

  if (!cats.length) { grid.innerHTML=''; empty.style.display='block'; return; }

  grid.innerHTML = cats.map(c => `
    <a class="card" href="/edukacija/${encodeURIComponent(c.slug)}">
      <div class="card-body">
        <div class="card-title">${esc(c.title)}</div>
        <div class="card-desc">${esc(c.description ?? '')}</div>
      </div>
    </a>
  `).join('');

  function esc(s){
    return String(s ?? '')
      .replaceAll('&','&amp;').replaceAll('<','&lt;').replaceAll('>','&gt;')
      .replaceAll('"','&quot;').replaceAll("'","&#039;");
  }
})().catch(err => {
  document.getElementById('grid').innerHTML =
    `<div class="card"><div class="card-body"><div class="card-title">Greška</div><div class="card-desc">${String(err)}</div></div></div>`;
});
</script>
@endsection
