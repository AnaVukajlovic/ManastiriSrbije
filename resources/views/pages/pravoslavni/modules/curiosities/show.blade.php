@extends('layouts.site')

@section('title', $item->title . ' — Pravoslavni Svetionik')
@section('nav_curiosities', 'active')

@section('content')
<style>
  .curios-show3{
    --cs-ink: rgba(255,255,255,.92);
    --cs-muted: rgba(255,255,255,.74);
    --cs-line: rgba(255,255,255,.08);
    --cs-gold-line: rgba(197,162,74,.22);
    --cs-shadow: 0 18px 45px rgba(0,0,0,.26);
  }

  .curios-show3 .container{
    width:min(1320px, calc(100% - 30px));
    max-width:none;
  }

  .back-btn{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    margin-bottom:20px;
    padding:10px 16px;
    border-radius:999px;
    text-decoration:none;
    color:var(--gold);
    background:rgba(197,162,74,.10);
    border:1px solid rgba(197,162,74,.22);
  }

  .curios-show3__head{
    margin-bottom:24px;
  }

  .curios-show3__badge{
    display:inline-flex;
    align-items:center;
    justify-content:center;
    padding:8px 14px;
    border-radius:999px;
    background:rgba(197,162,74,.10);
    border:1px solid rgba(197,162,74,.22);
    color:var(--gold);
    font-weight:700;
    font-size:.82rem;
    margin-bottom:14px;
  }

  .curios-show3__title{
    margin:0 0 10px;
    color:var(--gold);
    font-size:clamp(2rem, 4vw, 3rem);
    line-height:1.06;
    letter-spacing:-.02em;
  }

  .curios-show3__meta{
    display:flex;
    flex-wrap:wrap;
    gap:8px;
    align-items:center;
    color:var(--cs-muted);
    margin-bottom:14px;
  }

  .curios-show3__lead{
    margin:0;
    max-width:980px;
    color:var(--cs-ink);
    line-height:1.9;
    font-size:1.03rem;
    text-align:justify;
    text-justify:inter-word;
  }

  .curios-show3__layout{
    display:grid;
    grid-template-columns:minmax(0, 1fr) 320px;
    gap:24px;
    align-items:start;
  }

  .curios-show3__article,
  .curios-show3__box{
    border-radius:24px;
    border:1px solid var(--cs-line);
    background:
      radial-gradient(circle at top right, rgba(197,162,74,.10), transparent 26%),
      linear-gradient(180deg, rgba(255,255,255,.03), rgba(255,255,255,.015)),
      rgba(20,12,12,.90);
    box-shadow:var(--cs-shadow);
  }

  .curios-show3__article{
    overflow:hidden;
  }

  .curios-show3__imagewrap{
    width:100%;
    overflow:hidden;
    border-bottom:1px solid var(--cs-line);
  }

  .curios-show3__image{
    display:block;
    width:100%;
    max-height:420px;
    object-fit:cover;
  }

  .curios-show3__content{
    padding:28px 30px;
    color:var(--cs-ink);
    line-height:2;
    font-size:1.05rem;
    text-align:justify;
    text-justify:inter-word;
    white-space:normal;
  }

  .curios-show3__side{
    position:sticky;
    top:100px;
  }

  .curios-show3__box{
    padding:22px;
  }

  .curios-show3__box h3{
    margin:0 0 14px;
    color:var(--gold);
    font-size:1.15rem;
  }

  .curios-show3__more{
    display:flex;
    flex-direction:column;
    gap:12px;
  }

  .curios-show3__moreitem{
    display:block;
    text-decoration:none;
    padding:14px 14px;
    border-radius:16px;
    background:rgba(255,255,255,.03);
    border:1px solid rgba(255,255,255,.06);
  }

  .curios-show3__moretitle{
    display:block;
    color:var(--cs-ink);
    font-weight:700;
    margin-bottom:6px;
  }

  .curios-show3__moremeta{
    display:block;
    color:var(--cs-muted);
    font-size:.9rem;
  }

  .curios-show3__backlink{
    display:inline-flex;
    margin-top:16px;
    color:var(--gold);
    text-decoration:none;
    font-weight:700;
  }

  @media (max-width: 980px){
    .curios-show3__layout{
      grid-template-columns:1fr;
    }

    .curios-show3__side{
      position:static;
    }
  }

  @media (max-width: 760px){
    .curios-show3 .container{
      width:min(100%, calc(100% - 20px));
    }

    .curios-show3__content,
    .curios-show3__box{
      padding:20px;
    }

    .curios-show3__title{
      font-size:clamp(1.8rem, 8vw, 2.4rem);
    }

    .curios-show3__content{
      font-size:1rem;
      line-height:1.9;
    }
  }
</style>

<section class="section curios-show3">
  <div class="container">

    @php
      $img = $item->image ? asset($item->image) : asset('images/curiosities/default.jpg');
      $lead = $item->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($item->content ?? ''), 220);
      $reading = $item->reading_minutes ? ((int)$item->reading_minutes . ' min čitanja') : 'Duhovni vodič';
    @endphp

    <a class="back-btn" href="{{ route('curiosities.index') }}">← Nazad na zanimljivosti</a>

    <div class="curios-show3__head">
      @if(!empty($item->category))
        <span class="curios-show3__badge">{{ $item->category }}</span>
      @endif

      <h1 class="curios-show3__title">{{ $item->title }}</h1>

      <div class="curios-show3__meta">
        <span>{{ $reading }}</span>
        <span class="dot">•</span>
        <span>Pravoslavni Svetionik</span>
      </div>

      <p class="curios-show3__lead">{{ $lead }}</p>
    </div>

    <div class="curios-show3__layout">

      <article class="curios-show3__article">
        <div class="curios-show3__imagewrap">
          <img src="{{ $img }}" alt="{{ $item->title }}" class="curios-show3__image">
        </div>

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

          <a class="curios-show3__backlink" href="{{ route('curiosities.index') }}">Nazad na sve</a>
        </div>
      </aside>

    </div>

  </div>
</section>
@endsection