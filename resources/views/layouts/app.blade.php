<!doctype html>
<html lang="sr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Manastiri Srbije')</title>
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>

<div class="app-shell">

  <aside class="sidebar">
    <div class="brand">
      <div class="brand-logo">☦</div>
      <div>
        <div class="brand-title">Manastiri Srbije</div>
        <div class="brand-sub">SPC • Mape • Ture • Edukacija</div>
      </div>
    </div>

    <nav class="nav">
      {{-- HOME --}}
      <a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">🏠 Početna</a>

      {{-- MANASTIRI --}}
      @if(\Illuminate\Support\Facades\Route::has('monasteries.index'))
        <a href="{{ route('monasteries.index') }}" class="{{ request()->routeIs('monasteries.*') ? 'active' : '' }}">⛪ Manastiri</a>
      @else
        <a href="#" title="Ruta monasteries.index nije definisana">⛪ Manastiri</a>
      @endif

      {{-- KTITORI --}}
      @if(\Illuminate\Support\Facades\Route::has('ktitors.index'))
        <a href="{{ route('ktitors.index') }}" class="{{ request()->routeIs('ktitors.*') ? 'active' : '' }}">👑 Ktitori</a>
      @else
        <a href="#" title="Ruta ktitors.index nije definisana">👑 Ktitori</a>
      @endif

      {{-- MAP --}}
      @if(\Illuminate\Support\Facades\Route::has('map'))
        <a href="{{ route('map') }}" class="{{ request()->routeIs('map') ? 'active' : '' }}">🗺 Mapa</a>
      @else
        <a href="#" title="Ruta map još nije definisana">🗺 Mapa</a>
      @endif

      {{-- ORTHODOX --}}
      @if(\Illuminate\Support\Facades\Route::has('orthodox.index'))
        <a href="{{ route('orthodox.index') }}" class="{{ request()->routeIs('orthodox.*') ? 'active' : '' }}">📜 Pravoslavni sadržaj</a>
      @else
        <a href="#" title="Ruta orthodox.index nije definisana">📜 Pravoslavni sadržaj</a>
      @endif

      {{-- EDUCATION --}}
      @if(\Illuminate\Support\Facades\Route::has('education.index'))
        <a href="{{ route('education.index') }}" class="{{ request()->routeIs('education.*') ? 'active' : '' }}">🎓 Edukacija</a>
      @else
        <a href="#" title="Ruta education.index nije definisana">🎓 Edukacija</a>
      @endif

      {{-- TOURS --}}
      @if(\Illuminate\Support\Facades\Route::has('tours.index'))
        <a href="{{ route('tours.index') }}" class="{{ request()->routeIs('tours.*') ? 'active' : '' }}">🎥 360° ture</a>
      @else
        <a href="#" title="Ruta tours.index nije definisana">🎥 360° ture</a>
      @endif

      {{-- AI --}}
      @if(\Illuminate\Support\Facades\Route::has('ai'))
        <a href="{{ route('ai') }}" class="{{ request()->routeIs('ai') ? 'active' : '' }}">🤖 Pitaj AI</a>
      @else
        <a href="#" title="Ruta ai nije definisana">🤖 Pitaj AI</a>
      @endif
    </nav>
  </aside>

  <main class="main">
    <div class="topbar container">
      <div class="p">@yield('subtitle', 'Dobrodošli')</div>

      <details class="account">
        <summary class="btn">Nalog</summary>

        <div class="menu card">
          @auth
            <div class="p" style="margin-bottom:10px;">
              Ulogovan/a: <strong>{{ auth()->user()->name ?? auth()->user()->email }}</strong>
            </div>

            <a href="{{ route('profile.edit') }}">👤 Profil</a>


            {{-- Omiljeni --}}
            @if(\Illuminate\Support\Facades\Route::has('favorites.index'))
              <a href="{{ route('favorites.index') }}">⭐ Omiljeni manastiri</a>
            @else
              <a href="#" title="Ruta favorites.index nije definisana">⭐ Omiljeni manastiri</a>
            @endif

            {{-- Moje ture --}}
            @if(\Illuminate\Support\Facades\Route::has('tours.my'))
              <a href="{{ route('tours.my') }}">🧭 Moje ture</a>
            @else
              <a href="#" title="Ruta tours.my nije definisana">🧭 Moje ture</a>
            @endif

            {{-- Logout (Breeze obično postoji) --}}
            @if(\Illuminate\Support\Facades\Route::has('logout'))
              <form method="POST" action="{{ route('logout') }}" style="margin-top:10px;">
                @csrf
                <button class="btn" style="width:100%;" type="submit">Odjava</button>
              </form>
            @else
              <div class="p" style="margin-top:10px;">Ruta logout nije definisana.</div>
            @endif

          @else
            @if(\Illuminate\Support\Facades\Route::has('login'))
              <a href="{{ route('login') }}">Prijava</a>
            @else
              <a href="#">Prijava</a>
            @endif

            @if(\Illuminate\Support\Facades\Route::has('register'))
              <a href="{{ route('register') }}">Registracija</a>
            @else
              <a href="#">Registracija</a>
            @endif
          @endauth
        </div>
      </details>
    </div>

    <div class="container">
      @yield('content')
    </div>
  </main>

</div>

</body>
</html>
