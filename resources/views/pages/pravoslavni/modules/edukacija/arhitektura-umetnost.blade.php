@extends('layouts.site')

@section('title','Arhitektura i umetnost')

@section('content')
<style>
  .lesson-page{
    max-width: 1240px;
    margin: 0 auto;
    width: 100%;
  }

  .lesson-card{
    background: linear-gradient(180deg, rgba(28, 18, 16, 0.96), rgba(16, 10, 10, 0.96));
    border: 1.5px solid rgba(197, 162, 74, 0.28);
    border-radius: 24px;
    padding: 38px 42px;
    box-shadow: 0 16px 40px rgba(0,0,0,.45);
    width: 100%;
  }

  .lesson-page h1{
    font-size: clamp(2rem, 3.2vw, 2.7rem);
    margin-bottom: 20px;
    color: var(--gold, #c5a24a);
    font-weight: 800;
  }

  .lesson-page .lead{
    font-size: 1.12rem;
    line-height: 1.95;
    text-align: justify;
    text-justify: inter-word;
    color: rgba(255,255,255,.96);
    margin-bottom: 24px;
    font-style: italic;
  }

  .lesson-page h2{
    margin-top: 36px;
    margin-bottom: 14px;
    font-size: 1.5rem;
    color: var(--gold, #c5a24a);
    font-weight: 700;
  }

  .lesson-page p{
    text-align: justify;
    text-justify: inter-word;
    line-height: 1.95;
    font-size: 1.05rem;
    margin-bottom: 18px;
    color: rgba(255,255,255,.90);
    width: 100%;
  }

  @media (max-width: 768px){
    .lesson-card{
      padding: 22px 18px;
      border-radius: 16px;
    }

    .lesson-page h1{
      font-size: 1.75rem;
    }

    .lesson-page h2{
      font-size: 1.3rem;
    }

    .lesson-page p,
    .lesson-page .lead{
      font-size: 0.98rem;
      line-height: 1.8;
    }
  }
</style>

<section class="section">
  <div class="container lesson-page">
    <div class="lesson-card">

      <h1>Arhitektura i umetnost</h1>

      <p class="lead">
        Srpska srednjovekovna arhitektura i umetnost predstavljaju jedno od
        najdragocenijih nasleđa srpske kulture. Manastiri nisu građeni samo kao
        mesta molitve, već i kao duhovni, kulturni i umetnički centri. Kroz njihove
        zidove, freske, ikone i ukrase može se pratiti razvoj vere, estetike i
        istorijskog identiteta srpskog naroda.
      </p>

      <h2>Vizantijski uticaj i lokalni izraz</h2>

      <p>
        Razvoj srpske srednjovekovne arhitekture odvijao se pod snažnim uticajem
        Vizantije, koja je u to vreme predstavljala jedan od najvažnijih kulturnih
        i duhovnih centara pravoslavnog sveta. Ipak, srpski graditelji nisu samo
        preuzimali postojeće obrasce, već su ih prilagođavali sopstvenim potrebama
        i prostoru, stvarajući prepoznatljiv lokalni izraz.
      </p>

      <p>
        Zato srpski manastiri ne predstavljaju puku kopiju vizantijskog graditeljstva,
        već originalan spoj različitih uticaja. U njima se prepoznaju i istočni i
        zapadni elementi, ali su objedinjeni u jedinstvenu celinu koja je postala
        karakteristična za srpsku srednjovekovnu umetnost.
      </p>

      <h2>Raška škola</h2>

      <p>
        Raška škola predstavlja jedan od najvažnijih pravaca rane srpske
        srednjovekovne arhitekture. Razvijala se u vreme Nemanjića i odlikovala se
        skladnim proporcijama, jednostavnijim spoljnim izgledom i jasnom prostornom
        organizacijom. U ovom stilu uočava se spoj romaničkih i vizantijskih elemenata,
        što ga čini posebno zanimljivim.
      </p>

      <p>
        Jedan od najpoznatijih primera raške škole jeste manastir Studenica.
        Njegova arhitektura pokazuje težnju ka ravnoteži, miru i monumentalnosti.
        Ovaj stil ostavio je snažan trag u razvoju srpske crkvene arhitekture i
        postavio temelje za kasnija umetnička dostignuća.
      </p>

      <h2>Moravska škola</h2>

      <p>
        Kasniji razvoj srednjovekovne arhitekture doveo je do oblikovanja moravske
        škole, koja se razlikuje od ranijih rešenja po bogatijoj dekoraciji i
        složenijem izrazu. Fasade postaju ukrašenije, pojavljuju se ornamenti,
        rozete i pažljivo obrađeni detalji koji crkvama daju svečaniji i raskošniji izgled.
      </p>

      <p>
        Manastiri kao što su Ravanica i Manasija predstavljaju vrhunac ovog pravca.
        U njima se vidi visoki nivo graditeljskog umeća, ali i potreba da arhitektura
        ne bude samo funkcionalna, već i snažno izražajna. Moravska škola zato
        predstavlja jedan od najlepših perioda srpske srednjovekovne umetnosti.
      </p>

      <h2>Freskopis kao duhovni jezik</h2>

      <p>
        Freske zauzimaju centralno mesto u pravoslavnoj umetnosti. One nisu služile
        samo kao ukras zidova, već su imale duboko duhovno i poučno značenje.
        Kroz prikaze svetitelja, biblijskih scena i praznika, vernicima su prenošene
        poruke vere i osnovne istine hrišćanskog učenja.
      </p>

      <p>
        Srpski srednjovekovni freskopis poznat je po izuzetnoj umetničkoj vrednosti.
        Freske u Mileševi, Sopoćanima, Gračanici, Dečanima i drugim manastirima
        ubrajaju se među najznačajnija dela evropske srednjovekovne umetnosti.
        Njihova lepota nije samo u tehnici i kompoziciji, već i u izraženoj duhovnosti
        koja prožima čitav prostor hrama.
      </p>

      <h2>Ikone i simbolika</h2>

      <p>
        Ikonopis je imao posebno mesto u crkvenom životu. Ikona u pravoslavlju nije
        samo slika, već sveti prikaz sa dubokim simboličkim značenjem. Svaka boja,
        pokret ruke, pogled i raspored figura imaju svoju poruku. Zato ikonopis
        prati određena pravila i kanone koji su se razvijali vekovima.
      </p>

      <p>
        Posmatranje ikona i fresaka nije bilo samo estetsko iskustvo, već i način
        duhovnog približavanja veri. U tome se ogleda posebnost pravoslavne umetnosti:
        ona ne teži samo lepoti, već i izražavanju duhovne stvarnosti.
      </p>

      <h2>Unutrašnjost hrama i umetnički detalji</h2>

      <p>
        Pored fresaka i ikona, unutrašnjost manastira obogaćivana je reljefima,
        kamenim ukrasima, ikonostasima i drugim umetničkim elementima. Sve to
        doprinosilo je stvaranju jedinstvenog svetog prostora u kojem su arhitektura,
        svetlost i slikarstvo činili jednu celinu.
      </p>

      <p>
        U takvom prostoru vernik nije samo prisustvovao bogosluženju, već je celokupnim
        doživljajem ulazio u svet simbolike i duhovnog smisla. Zato razumevanje
        srednjovekovne umetnosti podrazumeva i razumevanje načina na koji je tadašnji
        čovek doživljavao veru, prostor i svetlost.
      </p>

      <h2>Trajni značaj umetničkog nasleđa</h2>

      <p>
        Srpski manastiri danas nisu samo verski objekti, već i dragoceni spomenici
        istorije, arhitekture i umetnosti. Oni svedoče o stvaralačkoj snazi epoha
        u kojima su nastali i o dubokoj povezanosti vere i kulture. Kroz njih se
        može pratiti razvoj srpskog identiteta i odnos prema duhovnim vrednostima.
      </p>

      <p>
        Zbog toga proučavanje arhitekture i umetnosti manastira nije važno samo za
        istoriju umetnosti, već i za razumevanje celokupne kulturne istorije srpskog
        naroda. Ove svetinje ostaju živi svedoci prošlosti i trajni izvori nadahnuća.
      </p>

      {{-- IZVORI I STRUČNA LITERATURA --}}
      @include('partials.edu-sources', [
          'title' => 'Извори и стручна литература за тему „Архитектура и уметност“',
          'sources' => [
              [
                  'author' => 'Др Војислав Ј. Ђурић',
                  'work' => 'Византијске фреске у Југославији / Српско средњовековно сликарство',
                  'details' => 'Београд (темељна научна студија о стилским епохама, монументалном фрескопису и сопоћанском златном добу).'
              ],
              [
                  'author' => 'Проф. др Слободан Ненадовић',
                  'work' => 'Историја српске архитектуре',
                  'details' => 'Београд (детаљна архитектонска анализа Рашке, Вардарске и Моравске градитељске школе).'
              ],
              [
                  'author' => 'Светозар Радојчић',
                  'work' => 'Старо српско сликарство',
                  'details' => 'Истраживања иконографије, портрета владара и стилских особености српских манастира од XII до XV века.'
              ],
              [
                  'author' => 'Галерија фресака Народног музеја у Београду',
                  'work' => 'Студије и водичи кроз копије фресака и камену пластику средњовековне Србије',
                  'details' => 'Каталошка документација о розетским украсима, порталима и живопису.'
              ],
              [
                  'author' => 'УНЕСКО (UNESCO World Heritage Centre)',
                  'work' => 'Документација светске баштине за српске споменике',
                  'details' => 'Евалуација изузетне универзалне вредности Студенице, Сопоћана, Дечана и Грачанице.'
              ]
          ],
          'note' => 'Подела на градитељске школе и стилске фазе фрескописа заснована је на званичним уџбеницима и монографијама Филозофског и Архитектонског факултета Универзитета у Београду.'
      ])
  </div>
</section>
@endsection