@extends('layouts.site')

@section('title','Učenje i interakcija')

@section('content')
<section class="section">
<div class="container">

<h1>Učenje i interakcija</h1>

<p>
Ova oblast namenjena je aktivnom učenju kroz interaktivne sadržaje.
Ovde će korisnici moći da istražuju istorijske događaje,
porodične veze dinastije Nemanjić i ključne periode razvoja
Srpske pravoslavne crkve.
</p>

<p>
Jedan od centralnih elemenata biće interaktivno porodično
stablo Nemanjića. Klikom na članove porodice otvaraće se
detaljne stranice o njihovom životu, ulozi u istoriji
i zadužbinama.
</p>

<p>
Timeline sekcija prikazaće najvažnije događaje kroz vreme:
period Nemanjića, istoriju SPC i razdoblje osmanske vlasti.
Na taj način korisnik će vizuelno razumeti tok istorije.
</p>

<p>
Poseban deo činiće kvizovi znanja. Kroz kratka pitanja korisnik
će proveravati poznavanje istorije i pravoslavne tradicije.
Ovakav način učenja omogućava bolje pamćenje informacija.
</p>

<p>
U budućnosti ova oblast će sadržati i AI duhovnog vodiča,
baziranog na lokalnom LLM modelu (Ollama). Korisnik će moći
da postavlja pitanja, traži jednostavnija objašnjenja istorijskih
tema ili da dobije sažetke dužih tekstova.
</p>

<p>
Cilj ovog modula nije samo prikaz informacija, već stvaranje
interaktivnog prostora u kome korisnik aktivno učestvuje u učenju.
</p>

</div>


<hr style="margin:40px 0; opacity:.2;">

<h2 style="margin-bottom:20px;">Interaktivni moduli</h2>

<div class="ps-modgrid">

  <a class="ps-mod" href="{{ route('edukacija.show','porodicno-stablo') }}">
    <div class="ps-mod__name">👑 Porodično stablo Nemanjića</div>
    <div class="ps-mod__desc">
      Vizuelno porodično stablo sa granama i vezama između članova dinastije.
    </div>
    <div class="ps-mod__cta">Otvori →</div>
  </a>

  <a class="ps-mod" href="{{ route('edukacija.timeline') }}">
    <div class="ps-mod__name">🕒 Timeline istorije</div>
    <div class="ps-mod__desc">
      Vremenska linija Nemanjića, istorije SPC i perioda pod Turcima.
    </div>
    <div class="ps-mod__cta">Uskoro</div>
  </a>

  <a class="ps-mod" href="{{ route('edukacija.quiz.history') }}">
    <div class="ps-mod__name">🧩 Kviz znanja</div>
    <div class="ps-mod__desc">
      Proveri svoje znanje iz istorije.
    </div>
    <div class="ps-mod__cta">Uskoro</div>
  </a>

<a class="ps-mod" href="{{ route('edukacija.quiz.orthodox') }}">
    <div class="ps-mod__name">🧩 Kviz znanja</div>
    <div class="ps-mod__desc">
      Proveri svoje znanje iz pravoslavlja.
    </div>
    <div class="ps-mod__cta">Uskoro</div>
  </a>

<a class="ps-mod" href="{{ route('edukacija.ai') }}">
    <div class="ps-mod__name">🤖 AI vodič</div>
    <div class="ps-mod__desc">
      Postavi pitanje i dobiješ objašnjenje uz pomoć lokalnog AI modela.
    </div>
    <div class="ps-mod__cta">Uskoro</div>
  </a>

</div>


</section>
@endsection