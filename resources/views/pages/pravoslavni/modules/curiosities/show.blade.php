@extends('layouts.site')

@section('title', $item->title . ' — Pravoslavni Svetionik')
@section('nav_curiosities', 'active')

@section('content')
<style>
  .curios-show3 { padding-top: 10px !important; }
  .curios-show3 .container { width: min(1200px, calc(100% - 30px)); margin: 0 auto; }

  /* Head - svedeno i kompaktno */
  .curios-show3__head { margin-bottom: 30px; max-width: 800px; }
  .back-btn { display: inline-flex; margin-bottom: 15px; text-decoration: none; color: var(--gold); background: rgba(197,162,74,.08); padding: 6px 14px; border-radius: 999px; font-size: 0.85rem; }
  
  .curios-show3__title { 
    margin: 0 0 15px 0; color: #fff; 
    font-size: clamp(1.6rem, 3.5vw, 2.4rem); 
    line-height: 1.1; font-weight: 800;
    white-space: normal; word-break: break-word; 
    text-align: left; /* Osigurava da uvek krene od ivice */
    margin-bottom: 20px;
    padding-right: 20px; /* Daje malo disanja naslovu da ne dodiruje sidebar */
  }
  /* 1. Glavni grid - dajemo mu imena za redove */
  .curios-show3__layout { 
    display: grid; 
    grid-template-columns: 1fr 340px; 
    gap: 40px; 
    align-items: start; 
  }

  /* Naslov zauzima prvu kolonu */
  .curios-show3__head { grid-column: 1; margin-bottom: 30px; }
  
  /* Artikal zauzima prvu kolonu ispod naslova */
  .curios-show3__article { grid-column: 1; }

  /* Sidebar zauzima drugu kolonu i proteže se od vrha naslova do dna članka */
.curios-show3__side { 
    grid-column: 2; 
    grid-row: 1 / 3; 
    position: sticky; 
    top: 20px; 
    /* OVO SPUŠTA DIV DOLE - povecaj broj ako zelis jos nize */
    margin-top: 60px; 
  }
  .curios-show3__meta { color: rgba(255,255,255,0.6); margin-bottom: 20px; font-size: 0.9rem; }
  .curios-show3__lead { margin: 0; color: rgba(255,255,255,0.9); line-height: 1.6; font-size: 1.05rem; border-left: 3px solid var(--gold); padding-left: 15px; }

  /* Layout - Grid */
.curios-show3__layout { 
    display: grid; 
    grid-template-columns: 1fr 340px; 
    gap: 40px; 
    align-items: start; 
  }  
  /* Article */
  .curios-show3__article { border-radius: 24px; border: 1px solid rgba(255,255,255,.05); background: rgba(20,12,12,.6); overflow: hidden; }
  .curios-show3__content { padding: 30px; line-height: 1.8; color: #eee; text-align: justify; }

  /* Sidebar (Još zanimljivosti) */
.curios-show3__side { 
    grid-column: 2; 
    grid-row: 1 / 3; 
    position: sticky; 
    top: 20px; 
    margin-top: 0; /* Vrati na 0 */
  }
.curios-show3__box { 
    padding: 25px; 
    margin-top: 0px; /* OVO ĆE SPUSTITI SAM BOX DOLE */
    border-radius: 24px; 
    border: 1px solid rgba(255,255,255,.05); 
    background: rgba(20,12,12,.6); 
  }
    .curios-show3__box h3 { margin: 0 0 20px; color: var(--gold); font-size: 1.1rem; text-transform: uppercase; letter-spacing: 1px; }
  .curios-show3__moreitem { display: block; text-decoration: none; padding: 14px; border-radius: 16px; background: rgba(255,255,255,.03); border: 1px solid rgba(255,255,255,.05); margin-bottom: 12px; }
  .curios-show3__moreitem:hover { background: rgba(197,162,74,.05); }
  .curios-show3__moretitle { display: block; color: var(--cs-ink); font-weight: 700; margin-bottom: 4px; }
  .curios-show3__moremeta { color: rgba(255,255,255,0.5); font-size: 0.8rem; }
/* RESPONSIVNOST - KLJUČNA IZMENA */
  @media (max-width: 980px) {
    /* 1. Grid se pretvara u jednu kolonu */
    .curios-show3__layout { 
      grid-template-columns: 1fr !important; 
      gap: 30px !important; 
    }

    /* 2. Sidebar se spušta ispod članka */
    .curios-show3__side { 
      grid-column: 1 !important; 
      grid-row: auto !important; 
      position: static !important; /* Ukida "sticky" efekat na mobilnom */
      margin-top: 0 !important; 
    }

    /* 3. Smanjivanje margina na mobilnom */
    .curios-show3__box { margin-top: 0 !important; }
    
    /* 4. Naslov prilagođen malom ekranu */
    .curios-show3__title { font-size: 1.8rem !important; }
  }

  /* Dodatno za tablet */
  @media (max-width: 768px) {
    .curios-show3__content { padding: 20px !important; }
  }
</style>

<section class="section curios-show3">
  <div class="container">
    <a class="back-btn" href="{{ route('curiosities.index') }}">← Nazad na zanimljivosti</a>

    <div class="curios-show3__layout">
      <div class="curios-show3__head">
        @if(!empty($item->category))
          <span class="curios-show3__badge">{{ $item->category }}</span>
        @endif
        <h1 class="curios-show3__title">{{ $item->title }}</h1>
        <div class="curios-show3__meta">
          {{ $item->reading_minutes ? ((int)$item->reading_minutes . ' min čitanja') : 'Duhovni vodič' }} • Pravoslavni Svetionik
        </div>
        <p class="curios-show3__lead">{{ $item->excerpt }}</p>
      </div>

      <article class="curios-show3__article">
        <img src="{{ $item->image ? asset($item->image) : asset('images/curiosities/default.jpg') }}" alt="{{ $item->title }}" class="curios-show3__image">
        <div class="curios-show3__content">
          {!! nl2br(e($item->content)) !!}
        </div>
      </article>

      <aside class="curios-show3__side">
        <div class="curios-show3__box">
          <h3>Još zanimljivosti</h3>
          <div class="curios-show3__more">
            @foreach($more as $m)
              <a class="curios-show3__moreitem" href="{{ route('curiosities.show', $m->slug) }}">
                <span class="curios-show3__moretitle">{{ $m->title }}</span>
                @if(!empty($m->category))
                  <span class="curios-show3__moremeta">{{ $m->category }}</span>
                @endif
              </a>
            @endforeach
          </div>
        </div>
      </aside>
    </div>
  </div>
</section>
@endsection