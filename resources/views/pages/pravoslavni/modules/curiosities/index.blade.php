@extends('layouts.site')

@section('title', 'Zanimljivosti — Pravoslavni Svetionik')
@section('nav_curiosities', 'active')

@section('content')
<style>
  .curios-index{
    --ci-ink: rgba(255,255,255,.92);
    --ci-muted: rgba(255,255,255,.74);
    --ci-line: rgba(255,255,255,.08);
    --ci-gold-line: rgba(197,162,74,.22);
    --ci-shadow: 0 16px 38px rgba(0,0,0,.24);
  }

  .curios-index .container{
    width:min(1320px, calc(100% - 30px));
    max-width:none;
  }

  .ps-head{
    margin-bottom:22px;
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
    gap:18px;
    flex-wrap:wrap;
  }

  .ps-title{
    margin:0;
    color:var(--gold);
    font-size:clamp(2.2rem, 4vw, 3.6rem);
    line-height:1.02;
    letter-spacing:-.02em;
  }

  .ps-sub{
    margin:12px 0 0;
    color:var(--ci-ink);
    text-align:justify;
    text-justify:inter-word;
    max-width:900px;
    line-height:1.9;
    font-size:1.02rem;
  }

  .ps-meta{
    display:flex;
    align-items:center;
  }

  .ps-meta .muted{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:12px 18px;
    border-radius:999px;
    border:1px solid var(--ci-line);
    background:rgba(255,255,255,.03);
    white-space:nowrap;
  }

  .filters{
    margin-bottom:24px;
    padding:14px;
    border-radius:24px;
    border:1px solid var(--ci-line);
    background:
      radial-gradient(circle at top right, rgba(197,162,74,.10), transparent 24%),
      linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,.015)),
      rgba(20,12,12,.90);
    box-shadow:var(--ci-shadow);
  }

  .filters__row{
    display:grid;
    grid-template-columns: minmax(0, 1fr) auto;
    gap:14px;
    align-items:start;
  }

  .filters__main{
    display:grid;
    grid-template-columns:minmax(0, 1fr);
    gap:12px;
  }

  .filters__field{
    min-width:0;
  }

  .filters__field input{
    width:100%;
    height:48px;
    border-radius:14px;
    color:var(--ci-ink);
    font-size:14px;
    outline:none;
    border:1px solid var(--ci-line);
    background:rgba(255,255,255,.04);
    padding:0 14px;
  }

  .filters__field input::placeholder{
    color:rgba(255,255,255,.46);
  }

  .filters__field input:focus{
    border-color:rgba(197,162,74,.34);
    box-shadow:0 0 0 2px rgba(197,162,74,.10);
  }

  .filters__actions{
    display:flex;
    gap:10px;
    flex-wrap:wrap;
  }

  .cat-pills{
    display:flex;
    flex-wrap:wrap;
    gap:10px;
  }

  .cat-pill{
    appearance:none;
    border:none;
    cursor:pointer;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    min-height:42px;
    padding:0 16px;
    border-radius:999px;
    background:rgba(255,255,255,.04);
    border:1px solid rgba(255,255,255,.08);
    color:var(--ci-ink);
    font-size:14px;
    font-weight:600;
    transition:all .2s ease;
    text-decoration:none;
  }

  .cat-pill:hover{
    border-color:rgba(197,162,74,.26);
    color:#fff;
    transform:translateY(-1px);
  }

  .cat-pill.is-active{
    background:rgba(197,162,74,.12);
    border-color:rgba(197,162,74,.32);
    color:var(--gold);
    box-shadow:0 8px 20px rgba(0,0,0,.18);
  }

  .cards-grid{
    display:grid;
    grid-template-columns:repeat(3, minmax(0, 1fr));
    gap:22px;
  }

  .card{
    overflow:hidden;
    border-radius:22px;
    border:1px solid var(--ci-line);
    background:
      radial-gradient(circle at top right, rgba(197,162,74,.10), transparent 24%),
      linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,.015)),
      rgba(20,12,12,.90);
    box-shadow:var(--ci-shadow);
    transition:transform .22s ease, box-shadow .22s ease, border-color .22s ease;
  }

  .card:hover{
    transform:translateY(-4px);
    border-color:rgba(197,162,74,.24);
    box-shadow:0 22px 46px rgba(0,0,0,.28);
  }

  .card__media{
    position:relative;
    display:block;
    height:220px;
    overflow:hidden;
  }

  .card__img{
    position:absolute;
    inset:0;
    background-size:cover;
    background-position:center;
    transform:scale(1);
    transition:transform .35s ease;
  }

  .card:hover .card__img{
    transform:scale(1.05);
  }

  .card__shade{
    position:absolute;
    inset:0;
    background:linear-gradient(to top, rgba(0,0,0,.35), rgba(0,0,0,.05));
  }

  .chip{
    position:absolute;
    left:14px;
    top:14px;
    z-index:2;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:7px 12px;
    border-radius:999px;
    background:rgba(197,162,74,.12);
    border:1px solid rgba(197,162,74,.22);
    color:var(--gold);
    font-size:.78rem;
    font-weight:700;
  }

  .card__body{
    padding:18px;
  }

  .card__title{
    margin:0 0 10px;
    font-size:1.15rem;
    line-height:1.3;
  }

  .card__title a{
    color:var(--ci-ink);
    text-decoration:none;
  }

  .card__text{
    margin:0 0 14px;
    color:var(--ci-ink);
    line-height:1.82;
    font-size:.97rem;
    text-align:justify;
    text-justify:inter-word;
  }

  .card__meta{
    display:flex;
    align-items:center;
    justify-content:space-between;
    gap:12px;
  }

  .link{
    color:var(--gold);
    text-decoration:none;
    font-weight:700;
    white-space:nowrap;
  }

  .empty{
    padding:28px;
    border-radius:22px;
    border:1px solid var(--ci-line);
    background:rgba(255,255,255,.03);
  }

  .pagination-wrap{
    margin-top:24px;
  }

  @media (max-width: 1100px){
    .cards-grid{
      grid-template-columns:repeat(2, minmax(0, 1fr));
    }

    .filters__row{
      grid-template-columns:1fr;
    }
  }

  @media (max-width: 760px){
    .curios-index .container{
      width:min(100%, calc(100% - 20px));
    }

    .cards-grid{
      grid-template-columns:1fr;
      gap:18px;
    }

    .card__media{
      height:210px;
    }

    .ps-meta .muted{
      white-space:normal;
    }

    .filters__actions{
      width:100%;
    }

    .filters__actions .btn,
    .filters__actions .btn--ghost{
      flex:1;
      justify-content:center;
    }
  }
</style>

<section class="section curios-index">
  <div class="container">

    <div class="ps-head">
      <div>
        <h1 class="ps-title">Zanimljivosti</h1>
        <p class="ps-sub">
          Priče, simboli i objašnjenja iz pravoslavne tradicije — napisano razumljivo i informativno.
        </p>
      </div>

      <div class="ps-meta">
        <span class="muted">Freske • Običaji • Post • Ikone • Manastirski život</span>
      </div>
    </div>

    <form class="filters" method="GET" action="{{ route('curiosities.index') }}">
      <div class="filters__row">
        <div class="filters__main">
          <div class="filters__field">
            <label class="sr-only" for="q">Pretraga</label>
            <input
              id="q"
              name="q"
              type="search"
              value="{{ $q }}"
              placeholder="Pretraži: sveće, tamjan, freske, post..."
            />
          </div>

          <div class="cat-pills">
            <button type="submit" name="category" value="" class="cat-pill {{ $category === null || $category === '' ? 'is-active' : '' }}">
              Sve kategorije
            </button>

            @foreach($categories as $c)
              <button
                type="submit"
                name="category"
                value="{{ $c }}"
                class="cat-pill {{ $category === $c ? 'is-active' : '' }}"
              >
                {{ $c }}
              </button>
            @endforeach
          </div>
        </div>

        <div class="filters__actions">
          <button class="btn" type="submit">Primeni</button>
          <a class="btn btn--ghost" href="{{ route('curiosities.index') }}">Reset</a>
        </div>
      </div>
    </form>

    @if($items->count() === 0)
      <div class="empty">
        <h3>Nema rezultata</h3>
        <p class="muted">Pokušaj sa drugim pojmom ili obriši filtere.</p>
      </div>
    @else
      <div class="grid cards-grid">
        @foreach($items as $it)
          @php
            $img = $it->image ? asset($it->image) : asset('images/hero/hero1.jpg');
          @endphp

          <article class="card">
            <a class="card__media" href="{{ route('curiosities.show', $it->slug) }}">
              <div class="card__img" style="background-image:url('{{ $img }}')"></div>
              <div class="card__shade"></div>

              @if(!empty($it->category))
                <span class="chip">{{ $it->category }}</span>
              @endif
            </a>

            <div class="card__body">
              <h3 class="card__title">
                <a href="{{ route('curiosities.show', $it->slug) }}">{{ $it->title }}</a>
              </h3>

              <p class="card__text">
                {{ $it->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($it->content ?? ''), 180) }}
              </p>

              <div class="card__meta">
                <span class="muted">
                  {{ $it->reading_minutes ? ((int)$it->reading_minutes . ' min čitanja') : 'Duhovni vodič' }}
                </span>

                <a class="link" href="{{ route('curiosities.show', $it->slug) }}">
                  Pročitaj više →
                </a>
              </div>
            </div>
          </article>
        @endforeach
      </div>

@if($items->hasPages())
  <div class="pagination-wrap">
    <div class="ps-pagination-wrap">
      <div class="ps-pagination">

        @if($items->onFirstPage())
          <span class="ps-page ps-disabled">← Prethodna</span>
        @else
          <a class="ps-page" href="{{ $items->previousPageUrl() }}">← Prethodna</a>
        @endif

        @foreach($items->getUrlRange(1, $items->lastPage()) as $page => $url)
          @if($page == $items->currentPage())
            <span class="ps-page is-active">{{ $page }}</span>
          @else
            <a class="ps-page" href="{{ $url }}">{{ $page }}</a>
          @endif
        @endforeach

        @if($items->hasMorePages())
          <a class="ps-page" href="{{ $items->nextPageUrl() }}">Sledeća →</a>
        @else
          <span class="ps-page ps-disabled">Sledeća →</span>
        @endif

      </div>
    </div>
  </div>
@endif
    @endif

  </div>
</section>
@endsection