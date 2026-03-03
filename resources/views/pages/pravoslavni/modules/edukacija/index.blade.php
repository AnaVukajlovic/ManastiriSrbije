@extends('layouts.site')

@section('title','Edukacija — Pravoslavni Svetionik')

@section('content')
<section class="section">
<div class="container">

  <h1 class="ps-title">Edukacija</h1>
  <p class="ps-sub">
    Upoznaj istoriju, umetnost i duhovno nasleđe kroz pažljivo
    organizovane oblasti znanja.
  </p>

  <div class="ps-modgrid">

    <a class="ps-mod" href="{{ route('edukacija.show','istorija-kultura') }}">
      <div class="ps-mod__name">🏛️ Istorija i kultura</div>
      <div class="ps-mod__desc">
        Nemanjići, istorija SPC, Kosovo i Metohija, UNESCO manastiri.
      </div>
      <div class="ps-mod__cta">Otvori →</div>
    </a>

    <a class="ps-mod" href="{{ route('edukacija.show','arhitektura-umetnost') }}">
      <div class="ps-mod__name">🎨 Arhitektura i umetnost</div>
      <div class="ps-mod__desc">
        Raška i Moravska škola, freske, ikonopis i vizantijski uticaj.
      </div>
      <div class="ps-mod__cta">Otvori →</div>
    </a>

    <a class="ps-mod" href="{{ route('edukacija.show','ucenje-interakcija') }}">
      <div class="ps-mod__name">🧠 Učenje i interakcija</div>
      <div class="ps-mod__desc">
        Timeline istorije, porodično stablo Nemanjića, kviz i AI vodič.
      </div>
      <div class="ps-mod__cta">Otvori →</div>
    </a>

  </div>

</div>
</section>
@endsection