@extends('layouts.app')
@section('title','Sadržaj')

@section('content')
<div class="card">
  <a class="link" href="javascript:history.back()">← Nazad</a>

  <h1 id="title" style="margin-top:10px">Sadržaj</h1>
  <p id="excerpt" class="muted"></p>

  <div id="body" style="margin-top:12px; line-height:1.7"></div>

  <div id="sourceWrap" style="margin-top:14px; display:none">
    <a id="source" class="link" target="_blank" rel="noopener">Izvor</a>
  </div>
</div>

<script>
(async function(){
  const cat = @json(request()->route('cat'));
  const item = @json(request()->route('item'));

  const res = await fetch(`/api/content/${encodeURIComponent(cat)}/items/${encodeURIComponent(item)}`, {
    headers:{'Accept':'application/json'}
  });
  const json = await res.json();
  const d = json.data;

  document.getElementById('title').textContent = d?.title ?? 'Sadržaj';
  document.getElementById('excerpt').textContent = d?.excerpt ?? '';

  const body = document.getElementById('body');
  body.textContent = d?.body ?? 'Tekst će biti dodat uskoro.';

  if (d?.source_url) {
    document.getElementById('sourceWrap').style.display = 'block';
    document.getElementById('source').href = d.source_url;
  }
})().catch(err => {
  document.getElementById('body').innerHTML =
    `<div class="card-desc">Greška: ${String(err)}</div>`;
});
</script>
@endsection
