@extends('layouts.site')

@section('title', 'Zanimljivosti — Pravoslavni Svetionik')
@section('nav_curiosities', 'active')

@section('content')
<section class="section">
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
        <div class="filters__field">
          <label class="sr-only" for="q">Pretraga</label>
          <input id="q" name="q" type="search" value="{{ $q }}" placeholder="Pretraži: sveće, tamjan, freske, post..." />
        </div>

        <div class="filters__field">
          <label class="sr-only" for="category">Kategorija</label>
          <select id="category" name="category">
            <option value="">Sve kategorije</option>
            @foreach($categories as $c)
              <option value="{{ $c }}" @selected($category === $c)>{{ $c }}</option>
            @endforeach
          </select>
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
                  {{ $it->reading_minutes ? ((int)$it->reading_minutes.' min čitanja') : 'Duhovni vodič' }}
                </span>
                <a class="link" href="{{ route('curiosities.show', $it->slug) }}">Pročitaj više →</a>
              </div>
            </div>
          </article>
        @endforeach
      </div>

      <div class="pagination-wrap">
        {{ $items->links() }}
      </div>
    @endif

  </div>
</section>
@endsection