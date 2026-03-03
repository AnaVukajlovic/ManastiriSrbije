@extends('layouts.site')

@section('title','Kviz — Istorija')

@section('content')
<section class="section">
  <div class="container">

    <div class="ps-head">
      <div>
        <h1 class="ps-title">Kviz — Istorija</h1>
        <p class="ps-sub">Odgovori na pitanja i proveri znanje. Posle submit-a dobijaš rezultat i objašnjenje.</p>
      </div>
      <div class="ps-meta">
        <a class="btn btn--ghost" href="{{ route('edukacija.show','ucenje-interakcija') }}">← Nazad</a>
      </div>
    </div>

    @if($result)
      <div class="ps-card" style="margin-bottom:14px;">
        <h3 style="margin-top:0;">Rezultat</h3>
        <div style="display:flex;gap:12px;align-items:center;flex-wrap:wrap;">
          <div class="badge">{{ $result['score'] }}/{{ $result['max'] }}</div>
          <div class="muted">Tačnost: <strong>{{ $result['percent'] }}%</strong></div>
          <a class="btn btn--ghost" href="{{ route('edukacija.quiz.history') }}">Pokušaj ponovo</a>
        </div>
      </div>
    @endif

    <form method="POST" action="{{ route('edukacija.quiz.history.submit') }}">
      @csrf

      <div class="ps-topgrid">
        @foreach($questions as $i => $q)
          @php $picked = $answers[$q['id']] ?? null; @endphp

          <div class="ps-card">
            <h3 style="margin-top:0;">{{ $i+1 }}. {{ $q['q'] }}</h3>

            <div class="opts">
              @foreach($q['options'] as $idx => $opt)
                @php
                  $isCorrect = $result && ((string)$idx === (string)$q['correct']);
                  $isPicked  = $result && ((string)$idx === (string)$picked);
                @endphp

                <label class="opt {{ $isCorrect ? 'ok' : '' }} {{ $isPicked && !$isCorrect ? 'bad' : '' }}">
                  <input type="radio" name="answers[{{ $q['id'] }}]" value="{{ $idx }}" {{ (string)$picked === (string)$idx ? 'checked' : '' }}>
                  <span>{{ $opt }}</span>
                </label>
              @endforeach
            </div>

            @if($result)
              <div class="explain">
                <strong>Objašnjenje:</strong> {{ $q['explain'] }}
              </div>
            @endif
          </div>
        @endforeach
      </div>

      <div style="margin-top:14px;display:flex;gap:10px;flex-wrap:wrap;">
        <button class="btn" type="submit">Završi kviz</button>
        <a class="btn btn--ghost" href="{{ route('edukacija.quiz.history') }}">Reset</a>
      </div>

    </form>

  </div>
</section>

<style>
.opts{ display:flex; flex-direction:column; gap:10px; margin-top:12px; }
.opt{
  display:flex; gap:10px; align-items:flex-start;
  padding:10px 12px;
  border-radius:14px;
  border:1px solid rgba(255,255,255,.12);
  background:rgba(0,0,0,.18);
  cursor:pointer;
}
.opt input{ margin-top:3px; }
.opt.ok{ border-color: rgba(46, 204, 113,.35); background: rgba(46, 204, 113,.08); }
.opt.bad{ border-color: rgba(231, 76, 60,.35); background: rgba(231, 76, 60,.07); }

.explain{
  margin-top:12px;
  padding:10px 12px;
  border-radius:14px;
  border:1px solid rgba(255,255,255,.10);
  background: rgba(255,255,255,.03);
  line-height:1.7;
  opacity:.92;
}
.badge{
  padding:8px 12px;
  border-radius:999px;
  border:1px solid rgba(197,162,74,.55);
  background: rgba(197,162,74,.12);
  font-weight:900;
}
</style>
@endsection