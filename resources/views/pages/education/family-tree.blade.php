@extends('layouts.site')
@section('title','Porodično stablo Nemanjića')

@section('content')
<section class="section"><div class="container">

<h2>Porodično stablo Nemanjića</h2>

<div class="tree">
<ul>
<li>
Stefan Nemanja
<ul>
<li>Sveti Sava</li>
<li>Stefan Prvovenčani
  <ul>
    <li>Radoslav</li>
    <li>Vladislav</li>
  </ul>
</li>
</ul>
</li>
</ul>
</div>

</div>
<a href="javascript:history.back()" class="back-btn">
  ← Nazad
</a></section>
@endsection