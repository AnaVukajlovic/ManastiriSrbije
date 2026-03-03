@extends('layouts.app')
@section('title','Kategorija')

@section('content')
<div class="card">
  <a class="link" href="{{ route('education.index') }}">← Nazad</a>
  <h1 id="title" style="margin-top:10px">Kategorija</h1>
  <p id="desc" class="muted"></p>

  <div id="grid" class="grid" style="margin-top:12px"></div>
  <div id="empty" class="muted" style="margin-top:12px; display:none">Nema stavki u ovoj kategoriji.</div>
</div>

<script>
(async function(){
  const slug = @json(request()->route('slug'));

  const title = document.getElementById('title');
  const desc  = document.getElementById('desc');
  const grid  = document.getElementById('grid');
  const empty = document.getElementById('empty');

  grid.innerHTML = `<div class="card"><div class="card-body"><div class="card-title">Učitavam…</div></div></div>`;

  const res = await fetch(`/api/content/${encodeURIComponent(slug)}/items`, { headers:{'Accept':'application/json'}});
  const json = await res.json();

  const cat = json.category;
  const items = json.data || [];

  title.textContent = cat?.title ?? 'Kategorija';
  desc.textContent  = cat?.description ?? '';

  if (!items.length) { grid.innerHTML=''; empty.style.display='block'; return; }

  grid.innerHTML = items.map(it => `
    <a class="card" href="/edukacija/${encodeURIComponent(slug)}/${encodeURIComponent(it.slug)}">
      <div class="card-body">
        <div class="card-title">${esc(it.title)}</div>
        <div class="card-desc">${esc(it.excerpt ?? '')}</div>
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
