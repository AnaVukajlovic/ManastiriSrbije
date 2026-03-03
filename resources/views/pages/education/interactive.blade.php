@extends('layouts.site')
@section('title','Učenje i interakcija')

@section('content')
<section class="section"><div class="container">

<h2>Učenje i interakcija</h2>

<div class="edu-grid">

<a href="{{ route('education.timeline') }}" class="card edu-card">
<h3>📈 Timeline Nemanjića</h3>
<p>Svi ključni datumi dinastije.</p>
</a>

<a href="{{ route('education.tree') }}" class="card edu-card">
<h3>🌳 Porodično stablo</h3>
<p>Interaktivno stablo Nemanjića.</p>
</a>

<a href="{{ route('education.quiz.history') }}" class="card edu-card">
<h3>🧠 Kviz istorija Srbije</h3>
</a>

<a href="{{ route('education.quiz.orthodox') }}" class="card edu-card">
<h3>✝️ Kviz pravoslavlje</h3>
</a>

</div>

</div>
<a href="javascript:history.back()" class="back-btn">
  ← Nazad
</a>
</section>
@endsection