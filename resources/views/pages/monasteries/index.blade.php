@extends('layouts.site')

@section('title', 'Manastiri — Pravoslavni Svetionik')
@section('nav_monasteries', 'active')

@section('content')
  <section class="section">
    <div class="container">
      <div class="sectionhead">
        <h2>Manastiri</h2>
        <span class="muted">Pretraga i filtriranje svetinja u bazi</span>
      </div>

      <form class="filters" method="GET" action="{{ route('monasteries.index') }}">
        <div class="filters__row">
          <div class="filters__field">
            <label class="sr-only" for="q2">Pretraga</label>
            <input id="q2" name="q" type="search" value="{{ $q }}" placeholder="Naziv, grad, region..." />
          </div>

        <select name="eparchy">
          <option value="">Sve eparhije</option>
          @foreach($eparchies as $e)
            <option value="{{ $e->slug }}" {{ request('eparchy')===$e->slug ? 'selected' : '' }}>
              {{ $e->name }}
            </option>
          @endforeach
        </select>


          <div class="filters__field">
            <label class="sr-only" for="region">Region</label>
            <select id="region" name="region">
              <option value="">Svi regioni</option>
              @foreach($regions as $r)
                <option value="{{ $r }}" @selected($region === $r)>{{ $r }}</option>
              @endforeach
            </select>
          </div>

          <div class="filters__field">
            <label class="sr-only" for="sort">Sortiranje</label>
            <select id="sort" name="sort">
              <option value="popular" @selected($sort==='popular')>Preporučeno</option>
              <option value="name" @selected($sort==='name')>Naziv (A–Z)</option>
              <option value="new" @selected($sort==='new')>Najnovije</option>
            </select>
          </div>

          <button class="btn2" type="submit">Primeni</button>
          <a class="btn" href="{{ route('monasteries.index') }}">Reset</a>
        </div>
      </form>

      <div class="monGridCards">
        @forelse($monasteries as $m)
          @php
            // ✅ Primarno: lokalna slika po slug-u (public/images/monasteries/{slug}.jpg)
            $localImg = asset('images/monasteries/' . $m->slug . '.jpg');

            // ✅ Fallback: placeholder ako lokalna ne postoji
            $fallbackImg = asset('images/monasteries/placeholder.jpg');
          @endphp

          <a class="monCard" href="{{ route('monasteries.show', $m->slug) }}">
            <div class="monCard__img"
                 style="background-image:url('{{ $localImg }}')"
                 data-fallback="{{ $fallbackImg }}">
            </div>

            <div class="monCard__overlay"></div>

            <div class="monCard__body">
              <div class="monCard__title">{{ $m->name }}</div>

              <div class="monCard__meta">
                <span>{{ $m->region ?: '—' }}</span>
                @if(!empty($m->city))
                  <span>•</span><span>{{ $m->city }}</span>
                @endif
              </div>

              @if(!empty($m->excerpt))
                <div class="monCard__excerpt">{{ $m->excerpt }}</div>
              @endif
            </div>
          </a>
        @empty
          <p class="muted">Nema rezultata za zadate filtere.</p>
        @endforelse
      </div>

      <div style="margin-top:16px;">
        {{ $monasteries->links() }}
      </div>

      {{-- ✅ JS fallback za background-image (kad slika ne postoji) --}}
      <script>
        (function () {
          const nodes = document.querySelectorAll('.monCard__img[data-fallback]');
          nodes.forEach(el => {
            const bg = getComputedStyle(el).backgroundImage || '';
            const match = bg.match(/url\(["']?(.*?)["']?\)/);
            const imgUrl = match ? match[1] : null;

            if (!imgUrl) return;

            const test = new Image();
            test.onload = () => {};
            test.onerror = () => {
              const fb = el.getAttribute('data-fallback');
              if (fb) el.style.backgroundImage = `url('${fb}')`;
            };
            test.src = imgUrl;
          });
        })();
      </script>

    </div>
  </section>
@endsection