@extends('layouts.app')
@section('title','Admin • Pregled uvoza')

@section('content')
<div class="card">
  <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; align-items:center;">
    <div>
      <h1 style="margin:0">Pregled uvoza (čekiranje)</h1>
      <p class="muted" style="margin:6px 0 0">
        Čekiraj više stavki pa klikni ✅ Odobri / ❌ Odbaci / 🔄 Pending / 🗑 Obriši.
      </p>
    </div>

    <div style="display:flex; gap:8px; flex-wrap:wrap;">
      <a class="btn2" href="{{ route('admin.import', ['status'=>'pending']) }}">Pending</a>
      <a class="btn2" href="{{ route('admin.import', ['status'=>'approved']) }}">Approved</a>
      <a class="btn2" href="{{ route('admin.import', ['status'=>'rejected']) }}">Rejected</a>
      <a class="btn2" href="{{ route('admin.import', ['status'=>'all']) }}">Svi</a>
    </div>
  </div>

  @if(session('ok'))
    <div class="widget" style="margin-top:12px">{{ session('ok') }}</div>
  @endif

  <form method="GET" action="{{ route('admin.import') }}" style="margin-top:12px; display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
    <input type="hidden" name="status" value="{{ $status }}">
    <input class="input" name="q" value="{{ $q }}" placeholder="Pretraga (naziv/grad/slug/QID)..." style="min-width:280px;">
    <select class="input" name="spc" style="min-width:200px;">
      <option value="">SPC guess (sve)</option>
      <option value="1" @selected(request('spc')==='1')>SPC guess = DA</option>
      <option value="0" @selected(request('spc')==='0')>SPC guess = NE</option>
    </select>
    <button class="btn2" type="submit">Filtriraj</button>
    <a class="btn2" href="{{ route('admin.import', ['status'=>$status]) }}">Reset</a>
  </form>

  <form id="bulkForm" method="POST" style="margin-top:12px;">
    @csrf

    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:12px;">
      <button class="btn2" type="button" onclick="submitBulk('{{ route('admin.import.approve') }}')">✅ Odobri</button>
      <button class="btn2" type="button" onclick="submitBulk('{{ route('admin.import.reject') }}')">❌ Odbaci</button>
      <button class="btn2" type="button" onclick="submitBulk('{{ route('admin.import.pending') }}')">🔄 Pending</button>
      <button class="btn2" type="button" onclick="if(confirm('Obrisati čekirane?')) submitBulk('{{ route('admin.import.delete') }}')">🗑 Obriši</button>

      <span style="margin-left:auto; display:flex; gap:8px; flex-wrap:wrap;">
        <button class="btn2" type="button" onclick="toggleAll(true)">Čekiraj sve</button>
        <button class="btn2" type="button" onclick="toggleAll(false)">Skini sve</button>
      </span>
    </div>

    <div class="cards__grid" style="grid-template-columns: repeat(3, minmax(0, 1fr));">
      @foreach($rows as $m)
        @php $img = optional($m->images->first())->url; @endphp
        <div class="tile">
          <label style="display:flex; gap:10px; align-items:flex-start;">
            <input type="checkbox" name="ids[]" value="{{ $m->id }}" class="pick" style="margin-top:4px;">
            <div style="flex:1">
              <img class="tile__img tile__img--sm" src="{{ $img ?: ($m->image_url ?: 'https://placehold.co/900x600?text=Manastiri+Srbije') }}" alt="">
              <div class="tile__title">{{ $m->name }}</div>
              <div class="tile__meta">
                <div><b>Status:</b> {{ $m->review_status }} • <b>approved:</b> {{ $m->is_approved ? 'DA' : 'NE' }}</div>
                <div><b>SPC guess:</b> {{ $m->is_spc_guess ? 'DA' : 'NE' }} • <b>religion:</b> {{ $m->religion_qid ?? '—' }}</div>
                <div style="opacity:.85"><b>Grad:</b> {{ $m->city ?? '—' }} • <b>Region:</b> {{ $m->region ?? '—' }}</div>
                <div style="opacity:.75"><b>Slug:</b> {{ $m->slug }}</div>
                @php
  $rq = $m->religion_qid;
  $badge = '❓ Nepoznato';
  if ($rq === 'Q188814') $badge = '☦ SPC';
  elseif ($rq === 'Q35032') $badge = '☦ Pravoslavno';
  elseif (in_array($rq, ['Q9592','Q748','Q43229','Q93191'], true)) $badge = '⛔ Nije SPC';
@endphp
              </div>
            </div>
          </label>
        </div>
      @endforeach
    </div>
  </form>

  <div style="margin-top:14px;">
    {{ $rows->links() }}
  </div>
</div>

<script>
function toggleAll(on){
  document.querySelectorAll('.pick').forEach(cb => cb.checked = on);
}
function submitBulk(url){
  const form = document.getElementById('bulkForm');
  form.action = url;
  form.submit();
}
</script>
@endsection
