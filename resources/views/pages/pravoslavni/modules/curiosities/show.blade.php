@extends('layouts.site')

@section('title', ($item->title ?? 'Zanimljivost') . ' — Pravoslavni Svetionik')
@section('nav_curiosities', 'active')

@section('content')
<section class="section">
  <div class="container">

    <div class="crumbs">
      <a href="{{ route('curiosities.index') }}">Zanimljivosti</a>
      <span class="muted">/</span>
      <span class="muted">{{ $item->title }}</span>
    </div>

    @php
      $img = $item->image ? asset($item->image) : asset('images/hero/hero1.jpg');
    @endphp

    <header class="article-hero">
      <div class="article-hero__bg" style="background-image:url('{{ $img }}')"></div>
      <div class="article-hero__overlay"></div>

      <div class="article-hero__inner">
        @if(!empty($item->category))
          <div class="chip chip--light">{{ $item->category }}</div>
        @endif

        <h1 class="article-title">{{ $item->title }}</h1>

        <div class="article-meta">
          @if(!empty($item->reading_minutes))
            <span>{{ (int)$item->reading_minutes }} min čitanja</span>
            <span class="dot">•</span>
          @endif
          <span class="muted">Pravoslavni Svetionik</span>
        </div>

        @if(!empty($item->excerpt))
          <p class="article-excerpt">{{ $item->excerpt }}</p>
        @endif
      </div>
    </header>

    <div class="article-layout">
      <article class="article">
        <div class="article-content">
          {!! nl2br(e($item->content ?? '')) !!}
        </div>
      </article>

      <aside class="aside">
        <div class="aside-card">
          <h3>Još zanimljivosti</h3>

          @if($more->count() === 0)
            <p class="muted">Trenutno nema dodatnih tekstova.</p>
          @else
            <div class="aside-links">
              @foreach($more as $m)
                <a class="aside-link" href="{{ route('curiosities.show', $m->slug) }}">
                  <span class="aside-link__title">{{ $m->title }}</span>
                  @if(!empty($m->category))
                    <span class="aside-link__meta muted">{{ $m->category }}</span>
                  @endif
                </a>
              @endforeach
            </div>
          @endif

          <a class="btn btn--ghost" href="{{ route('curiosities.index') }}">Nazad na sve</a>
        </div>
      </aside>
    </div>

  </div>
</section>
@endsection