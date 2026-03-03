@extends('layouts.app')
@section('title', 'Admin • Review manastira')

@section('content')
<div class="card">
  <div style="display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; align-items:center;">
    <div>
      <h1 style="margin:0">Review manastira/crkava</h1>
      <p class="muted" style="margin:6px 0 0">
        Klikni ✅ (SPC) ili ❌ (nije SPC). Sajtu ćeš prikazivati samo <b>approved</b>.
      </p>
    </div>

    <div style="display:flex; gap:8px; flex-wrap:wrap; align-items:center;">
      <a class="btn2" href="{{ route('admin.monasteries', ['status'=>'pending'] ) }}">Pending</a>
      <a class="btn2" href="{{ route('admin.monasteries', ['status'=>'approved'] ) }}">Approved</a>
      <a class="btn2" href="{{ route('admin.monasteries', ['status'=>'rejected'] ) }}">Rejected</a>
      <a class="btn2" href="{{ route('admin.monasteries', ['status'=>'all'] ) }}">Svi</a>
    </div>
  </div>

  @if(session('ok'))
    <div class="widget" style="margin-top:12px">{{ session('ok') }}</div>
  @endif

  <form method="GET" action="{{ route('admin.monasteries') }}" style="margin-top:12px; display:flex; gap:10px; flex-wrap:wrap;">
    <input type="hidden" name="status" value="{{ $status }}">
    <input class="input" name="q" value="{{ $q }}" placeholder="Pretraga (naziv / slug / QID)..." style="min-width:280px;">
    <button class="btn2" type="submit">Traži</button>
    <a class="btn2" href="{{ route('admin.monasteries', ['status'=>$status]) }}">Reset</a>
  </form>

  <div style="margin-top:14px" class="cards__grid">
    @foreach($items as $m)
      @php
        $img = optional($m->images->first())->url;
      @endphp

      <div class="tile">
        <img class="tile__img" src="{{ $img ?: 'https://placehold.co/900x600?text=Manastiri+Srbije' }}" alt="">
        <div class="tile__title">{{ $m->name }}</div>
        <div class="tile__meta">
          <div><b>Status:</b> {{ $m->review_status }}</div>
          <div><b>SPC:</b> {{ is_null($m->is_spc) ? '—' : ($m->is_spc ? 'da' : 'ne') }}</div>
          <div style="opacity:.85"><b>QID:</b> {{ $m->wikidata_qid ?? '—' }}</div>
          <div style="opacity:.85"><b>Slug:</b> {{ $m->slug }}</div>
        </div>

        <div style="display:flex; gap:8px; flex-wrap:wrap; margin-top:10px;">
          <form method="POST" action="{{ route('admin.monasteries.approve', $m) }}">
            @csrf
            <button class="btn2" type="submit">✅ SPC</button>
          </form>

          <form method="POST" action="{{ route('admin.monasteries.reject', $m) }}">
            @csrf
            <button class="btn2" type="submit">❌ Nije SPC</button>
          </form>

          <form method="POST" action="{{ route('admin.monasteries.reset', $m) }}">
            @csrf
            <button class="btn2" type="submit">🔄 Pending</button>
          </form>
        </div>
      </div>
    @endforeach
  </div>

  <div style="margin-top:14px">
    {{ $items->links() }}
  </div>
</div>
@endsection
