@extends('layouts.site')

@section('title','Pravoslavni sadržaj')
@section('nav_orthodox','active')

@section('content')

<section class="section">
<div class="container">

<div class="sectionhead">
  <h2>Pravoslavni sadržaj</h2>
  <span class="muted">
    Danas je {{ now()->format('d.m.Y') }} — crkveni kalendar, pouke i duhovni sadržaj.
  </span>
</div>

{{-- TOP INFO --}}
<div class="orthodox-main-grid">

  <article class="card orthodox-feature">
    <h3>🕯️ Danas u kalendaru</h3>
    <p>Sveti Teodor Tiron</p>
    <span class="badge">Post na vodi</span>
  </article>

  <article class="card orthodox-feature">
    <h3>📜 Pouka dana</h3>
    <p class="quote">
      „Smirenje je odeća božanstva.“
    </p>
  </article>

  <article class="card orthodox-feature">
    <h3>📅 Predstojeći praznici</h3>
    <ul>
      <li>Cveti — 28. april</li>
      <li>Vaskrs — 5. maj</li>
      <li>Đurđevdan — 6. maj</li>
    </ul>
  </article>

</div>

<div class="sectionhead sectionhead--sm">
  <h3>Duhovni moduli</h3>
</div>

{{-- MODULE CARDS --}}
<div class="orthodox-modules">

<a href="{{ route('orthodox.concepts') }}" class="card module-card">
  <h4>📖 Osnovni koncepti vere</h4>
  <p>Liturgija, molitva, post, pričest i osnove pravoslavlja.</p>
</a>

<a href="{{ route('orthodox.calendar') }}" class="card module-card">
  <h4>📅 Pravoslavni kalendar</h4>
  <p>Pregled crkvenih praznika.</p>
</a>

<a href="{{ route('orthodox.facts') }}" class="card module-card">
  <h4>✨ Zanimljivosti</h4>
  <p>Zanimljive činjenice o pravoslavlju.</p>
</a>

<a href="{{ route('orthodox.easter') }}" class="card module-card">
  <h4>🕊 Datum Vaskrsa</h4>
  <p>Algoritam za izračunavanje datuma Vaskrsa.</p>
</a>

<a href="{{ route('orthodox.recipes') }}" class="card module-card">
  <h4>🥗 Posni recepti</h4>
  <p>Biraj posna jela prema postu.</p>
</a>

</div>

</div>
</section>

@endsection