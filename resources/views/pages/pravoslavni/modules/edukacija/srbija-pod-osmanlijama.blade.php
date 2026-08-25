@extends('layouts.site')

@section('title','Srbija pod Osmanlijama')

@section('content')

<style>
.lesson-page{
  max-width: 1240px;
  margin: 0 auto;
  width: 100%;
}
.lesson-page .lesson-card{
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
  .lesson-page .lesson-card{
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

      <h1>Srbija pod Osmanlijama</h1>

      <p class="lead">
        Period osmanske vlasti predstavlja jedno od najtežih, ali i najvažnijih
        razdoblja u istoriji srpskog naroda. Posle slabljenja srednjovekovne države
        i konačnog pada pod osmansku vlast, Srbija je izgubila političku samostalnost,
        ali nije izgubila duhovni i kulturni identitet. Upravo u tom vremenu vera,
        crkva i narodna tradicija postali su ključni čuvari istorijskog pamćenja.
      </p>

      <h2>Pad srednjovekovne države</h2>

      <p>
        Slom srpske srednjovekovne države nije došao odjednom, već postepeno. Posle
        Kosovske bitke 1389. godine srpske zemlje su se suočile sa sve većim pritiskom
        Osmanskog carstva. Konačan pad Smedereva 1459. godine označio je kraj
        samostalne srednjovekovne srpske države i početak dugog perioda osmanske uprave.
      </p>

      <p>
        Iako je politička vlast nestala, svest o ranijoj državnosti, o vladarima,
        svetinjama i duhovnom nasleđu nije iščezla. Ona je sačuvana kroz crkvu,
        manastire, predanje i narodno sećanje.
      </p>

      <h2>Život pod osmanskom vlašću</h2>

      <p>
        Život pod Osmanlijama bio je obeležen brojnim teškoćama. Stanovništvo je bilo
        izloženo porezima, različitim obavezama i ograničenjima. Ipak, uprkos takvim
        uslovima, srpski narod je uspeo da sačuva jezik, veru i običaje. To očuvanje
        identiteta bilo je presudno za kasnije periode obnove i oslobođenja.
      </p>

      <p>
        U takvim okolnostima narod je često upravo u crkvi i manastirima nalazio
        duhovnu snagu, utehu i osećaj pripadnosti. Zato je religijski život imao mnogo
        širi značaj od isključivo verskog.
      </p>

      <h2>Uloga Srpske pravoslavne crkve</h2>

      <p>
        Srpska pravoslavna crkva imala je ključnu ulogu u očuvanju narodne svesti.
        Ona je bila oslonac zajednice u vremenu kada nije postojala sopstvena država.
        Kroz bogosluženje, pismenost, predanje i negovanje uspomene na srednjovekovnu
        Srbiju, crkva je čuvala kontinuitet identiteta.
      </p>

      <p>
        Poseban značaj imala je obnova Pećke patrijaršije 1557. godine. Time je
        crkveni život ponovo snažnije organizovan, a duhovna povezanost srpskog naroda
        dodatno učvršćena. Ovaj događaj bio je važan ne samo za veru, već i za očuvanje
        zajedničke istorijske svesti.
      </p>

      <h2>Manastiri kao mesta opstanka</h2>

      <p>
        Tokom osmanske vlasti manastiri su ostali čuvari knjiga, rukopisa, fresaka i
        istorijskih zapisa. U njima su se prepisivale knjige, negovala pismenost i
        prenosila svest o prošlosti. Zbog toga su manastiri imali ogroman značaj u
        očuvanju kulturnog kontinuiteta.
      </p>

      <p>
        Mnoge svetinje su kroz vekove rušene, obnavljane i ponovo oživljavane. Upravo
        ta istrajnost govori o tome koliko su manastiri bili važni narodu. Oni nisu bili
        samo verski objekti, već i simbol opstanka, duhovne snage i nade.
      </p>

      <h2>Istorijsko pamćenje i obnova</h2>

      <p>
        Sećanje na Nemanjiće, na stare srpske vladare i na nekadašnju državnost nije
        nestalo tokom osmanskog perioda. Naprotiv, ono je sačuvano kroz crkvenu tradiciju,
        narodnu epiku, predanja i istorijske zapise. Ta svest o prošlosti pomogla je da
        se i u kasnijim vekovima održi težnja ka obnovi slobode i državnosti.
      </p>

      <p>
        Zato se period Srbije pod Osmanlijama ne može posmatrati samo kao vreme gubitka
        i stradanja. To je istovremeno i period duhovnog opstanka, čuvanja identiteta i
        istorijskog pamćenja, zahvaljujući kojem je narod uspeo da sačuva svoje
        najvažnije vrednosti.
      </p>

      {{-- DOKUMENTARNI VIDEO SADRŽAJI --}}
      @php
          $moduleVideos = \App\Support\EducationalMedia::forEduModule('srbija-pod-osmanlijama');
      @endphp
      @if(!empty($moduleVideos) && count($moduleVideos) > 0)
          @include('partials.video-section', [
              'videos' => $moduleVideos,
              'sectionTitle' => '🎬 Историјске емисије и видео документарци (HistoryCast)'
          ])
      @endif

      {{-- IZVORI I STRUČNA LITERATURA --}}
      @include('partials.edu-sources', [
          'title' => 'Извори и стручна литература за тему „Србија под Османлијама“',
          'sources' => [
              [
                  'author' => 'Радован Самарџић',
                  'work' => 'Историја српског народа у доба турске владавине (XVI–XVIII век)',
                  'details' => 'СКЗ, Београд (детаљна студија о друштвеном положају, цркви, улози манастира и очувању писмености).'
              ],
              [
                  'author' => 'Владимир Ћоровић',
                  'work' => 'Историја Срба',
                  'details' => 'Томови о паду Деспотовине 1459, обнови Пећке патријаршије 1557. и Великим сеобама под Арсенијем III Чарнојевићем.'
              ],
              [
                  'author' => 'Српска академија наука и уметности (САНУ)',
                  'work' => 'Зборник о патријарху Макарију Соколовићу и обнови српских светиња',
                  'details' => 'Историјски документи о очувању светиња и живописању манастира у XVI и XVII веку.'
              ],
              [
                  'author' => 'Матица српска',
                  'work' => 'Српска поствизантијска уметност и преписивачка делатност (Фрушка Гора и јужни крајеви)',
                  'details' => 'Нови Сад (студије о фрушкогорским манастирима као чуварима немањићке традиције).'
              ],
              [
                  'author' => 'Историјски институт у Београду',
                  'work' => 'Зборници докумената о српском средњем веку и раном новом веку',
                  'details' => 'Анализа народног памћења, епске поезије и манастирских летописа као чувара идентитета.'
              ]
          ],
          'note' => 'Подаци о обнови Пећке патријаршије 1557. године, Сеобама Срба 1690. и 1739. године и улози манастира усклађени су са званичном историјском науком.'
      ])
  </div>
</section>
@endsection