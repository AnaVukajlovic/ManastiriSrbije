@extends('layouts.site')

@section('title','Država Nemanjića')

@section('content')
<style>
  .lesson-page{
    max-width: 1100px;
    margin: 0 auto;
  }

  .lesson-card{
    background: rgba(255,255,255,.03);
    border: 1px solid rgba(255,255,255,.08);
    border-radius: 18px;
    padding: 32px;
    box-shadow: 0 10px 30px rgba(0,0,0,.18);
  }

  .lesson-page h1{
    font-size: 36px;
    margin-bottom: 18px;
    color: var(--gold);
  }

  .lesson-page .lead{
    font-size: 18px;
    line-height: 1.85;
    text-align: justify;
    color: rgba(255,255,255,.92);
    margin-bottom: 24px;
  }

  .lesson-page h2{
    margin-top: 34px;
    margin-bottom: 12px;
    font-size: 24px;
    color: var(--gold);
  }

  .lesson-page p{
    text-align: justify;
    line-height: 1.9;
    font-size: 17px;
    margin-bottom: 16px;
    color: rgba(255,255,255,.92);
  }

  @media (max-width: 768px){
    .lesson-card{
      padding: 22px;
      border-radius: 14px;
    }

    .lesson-page h1{
      font-size: 29px;
    }

    .lesson-page h2{
      font-size: 22px;
    }

    .lesson-page p,
    .lesson-page .lead{
      font-size: 16px;
      line-height: 1.8;
    }
  }
</style>

<section class="section">
  <div class="container lesson-page">
    <div class="lesson-card">

      <h1>Država Nemanjića</h1>

      <p class="lead">
        Period vladavine dinastije Nemanjića predstavlja jedno od najznačajnijih i
        najblistavijih razdoblja u istoriji srednjovekovne Srbije. Od kraja 12. do
        sredine 14. veka Srbija je doživela veliki politički, kulturni i duhovni uspon.
        U tom periodu formirani su temelji srpske državnosti, razvijene su institucije,
        izgrađeni brojni manastiri i stvorena snažna duhovna tradicija koja je oblikovala
        identitet srpskog naroda kroz naredne vekove.
      </p>

      <h2>Stefan Nemanja i učvršćivanje države</h2>

      <p>
        Osnivač dinastije bio je veliki župan Stefan Nemanja, koji je uspeo da ujedini
        srpske zemlje i učvrsti političku vlast. Njegova vladavina označila je početak
        snažne države i organizovanog društva. Nemanja nije bio samo vladar u političkom
        smislu, već i važan zadužbinar i zaštitnik crkvenog života.
      </p>

      <p>
        Njegova najpoznatija zadužbina jeste manastir Studenica, jedan od najznačajnijih
        spomenika srpske srednjovekovne arhitekture i duhovnosti. Kroz zadužbinarstvo,
        vladari iz ove dinastije pokazivali su da je njihova vlast tesno povezana sa
        verom, kulturom i očuvanjem narodnog identiteta.
      </p>

      <h2>Sveti Sava i autokefalnost Srpske crkve</h2>

      <p>
        Posebno mesto u istoriji ovog perioda zauzima Sveti Sava, najmlađi sin Stefana
        Nemanje. On je 1219. godine u Nikeji dobio autokefalnost Srpske pravoslavne
        crkve, čime je srpski narod dobio samostalnu crkvenu organizaciju. Ovaj događaj
        bio je izuzetno značajan jer je omogućio jačanje duhovnog i kulturnog identiteta
        srpskog naroda.
      </p>

      <p>
        Sveti Sava nije bio samo crkveni poglavar, već i prosvetitelj, diplomata i
        zakonodavac. Organizovao je crkvenu strukturu, osnovao episkopije i napisao
        Zakonopravilo, prvi srpski pravni zbornik. Zahvaljujući njegovom radu manastiri
        su postali centri obrazovanja, pisanja i prepisivanja knjiga, očuvanja kulture i
        razvoja duhovnog života.
      </p>

      <h2>Uspon Srbije u vreme cara Dušana</h2>

      <p>
        Najveći teritorijalni i politički uspon Srbija je dostigla u vreme cara Stefana
        Dušana u 14. veku. Njegova država prostirala se na velikom delu Balkanskog
        poluostrva i bila je jedna od najmoćnijih država tog vremena. Dušan je 1346.
        godine proglašen za cara, a iste godine srpska arhiepiskopija uzdignuta je na
        rang patrijaršije.
      </p>

      <p>
        Jedan od najvažnijih pravnih dokumenata tog vremena bio je Dušanov zakonik,
        donet 1349. godine, a dopunjen 1354. godine. Ovaj zakonik predstavljao je
        napredan pravni sistem koji je uređivao društvene odnose, pravosuđe i državnu
        upravu, što pokazuje visok stepen organizovanosti tadašnje Srbije.
      </p>

      <h2>Kulturno i duhovno nasleđe</h2>

      <p>
        U vreme Nemanjića izgrađeni su mnogi manastiri koji danas predstavljaju
        najznačajnije spomenike srpske kulture i duhovnosti. Među njima se posebno
        izdvajaju Studenica, Žiča, Mileševa, Sopoćani, Gračanica, Visoki Dečani i
        Pećka patrijaršija. Freske iz ovih manastira ubrajaju se među najvrednija dela
        srednjovekovne evropske umetnosti.
      </p>

      <p>
        Manastiri su u tom periodu bili mnogo više od verskih objekata. Oni su
        predstavljali kulturne centre u kojima su nastajale knjige, hronike i pravni
        spisi. Zahvaljujući njima sačuvan je veliki deo istorijskog i duhovnog nasleđa
        srpskog naroda, pa se vreme Nemanjića s pravom smatra jednim od temelja srpske
        istorije i kulture.
      </p>

    </div>
  </div>
</section>
@endsection