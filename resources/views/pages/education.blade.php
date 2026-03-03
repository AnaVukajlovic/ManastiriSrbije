@extends('layouts.site')

@section('title','Edukacija')
@section('nav_edu','active')

@section('content')

<section class="section">
<div class="container">

<div class="sectionhead">
  <h2>Edukacija</h2>
  <span class="muted">Interaktivno učenje istorije, kulture i pravoslavlja Srbije.</span>
</div>

<div class="edu-grid">

<a href="{{ route('education.history') }}" class="card edu-card">
<h3>📜 Istorija i kultura</h3>
<p>SPC, Nemanjići, KiM, Turci, UNESCO i Sveti Sava.</p>
</a>

<a href="{{ route('education.architecture') }}" class="card edu-card">
<h3>⛪ Arhitektura i umetnost</h3>
<p>Raška škola, Moravska škola, freske i vizantijski uticaj.</p>
</a>

<a href="{{ route('education.interactive') }}" class="card edu-card">
<h3>🧠 Učenje i interakcija</h3>
<p>Timeline, porodično stablo i kvizovi.</p>
</a>

</div>

</div>
</section>

@endsection