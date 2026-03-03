<!doctype html>
<html lang="sr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css">
  <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css">

  <title>@yield('title', 'Pravoslavni Svetionik')</title>

  {{-- CSRF (global) --}}
  <meta name="csrf-token" content="{{ csrf_token() }}">

  {{-- Styles --}}
  <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
  <link rel="stylesheet" href="{{ asset('css/site.css') }}">

  @yield('head')
</head>
<body>
  <a class="skip-link" href="#main">Preskoči na sadržaj</a>

  <div class="page">
    <header class="topnav" role="banner">
      <div class="topnav__frame">
        <div class="topnav__inner">

          {{-- LEFT: Brand --}}
          <a class="brand" href="{{ route('home') }}" aria-label="Početna - Pravoslavni Svetionik">
            {{-- <img class="brand__logo" src="{{ asset('images/logo.svg') }}" alt="Pravoslavni Svetionik" /> --}}
            <span class="brand__mark" aria-hidden="true">☦</span>
            <span class="brand__text">
              <span class="brand__name">Pravoslavni Svetionik</span>
              <span class="brand__tag">digitalni vodič kroz svetinje Srbije</span>
            </span>
          </a>

          {{-- CENTER: Navigation (desktop) --}}
          <nav class="navlinks" aria-label="Glavna navigacija">
            <a class="@yield('nav_home')" href="{{ route('home') }}">Početna</a>
            <a class="@yield('nav_monasteries')" href="{{ route('monasteries.index') }}">Manastiri</a>
            <a class="@yield('nav_ktitors')" href="{{ route('ktitors.index') }}">Ktitori</a>
            <a class="@yield('nav_map')" href="{{ route('map.index') }}">Mapa</a>

            {{-- ✅ Usklađeno: pravoslavni.index --}}
            <a class="@yield('nav_orthodox')" href="{{ route('pravoslavni.index') }}">Pravoslavni sadržaj</a>

            <a class="@yield('nav_edu')" href="{{ route('edukacija.index') }}">Edukacija</a>
          </nav>

          {{-- RIGHT: Actions --}}
          <div class="topnav__actions">
            {{-- Search --}}
            <form class="navsearch navsearch--expand" action="{{ route('monasteries.index') }}" method="GET" role="search">
              <label class="sr-only" for="q">Pretraga</label>
              <input id="q" name="q" type="search" placeholder="Pretraga…" />
              <button type="submit" aria-label="Traži">🔎</button>
            </form>

            {{-- Nalog --}}
            <details class="acct">
              <summary class="pill" aria-label="Meni naloga">
                @auth
                  {{ auth()->user()->name ?? 'Nalog' }}
                @else
                  Nalog
                @endauth
              </summary>

              <div class="acct__menu" role="menu">
                @auth
                  <a role="menuitem" class="acct__item" href="{{ route('profile.edit') }}">Profil</a>

                  @if (Route::has('admin.monasteries.index'))
                    <a role="menuitem" class="acct__item" href="{{ route('admin.monasteries.index') }}">Admin</a>
                  @endif

                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="acct__item acct__btn">Odjavi se</button>
                  </form>
                @else
                  <a role="menuitem" class="acct__item" href="{{ route('login') }}">Prijava</a>

                  @if (Route::has('register'))
                    <a role="menuitem" class="acct__item" href="{{ route('register') }}">Registracija</a>
                  @endif
                @endauth
              </div>
            </details>

            {{-- Mobile trigger --}}
            <button
              id="burgerBtn"
              class="burger"
              type="button"
              aria-label="Otvori meni"
              aria-controls="mobileMenu"
              aria-expanded="false"
            >☰</button>
          </div>
        </div>
      </div>

      {{-- MOBILE MENU --}}
      <div id="mobileMenu" class="mobilemenu" aria-label="Mobilni meni">
        <div class="mobilemenu__frame">
          <div class="mobilemenu__inner">
            <div class="mobilemenu__links">
              <a href="{{ route('home') }}">Početna</a>
              <a href="{{ route('monasteries.index') }}">Manastiri</a>
              <a href="{{ route('ktitors.index') }}">Ktitori</a>
              <a href="{{ route('map.index') }}">Mapa</a>

              {{-- ✅ Usklađeno: pravoslavni.index --}}
              <a href="{{ route('pravoslavni.index') }}">Pravoslavni sadržaj</a>

              <a href="{{ route('edukacija.index') }}">Edukacija</a>

              <div class="mobilemenu__divider" aria-hidden="true"></div>

              @auth
                <div class="mobilemenu__sectionTitle">Nalog</div>
                <a href="{{ route('profile.edit') }}">Profil</a>

                @if (Route::has('admin.monasteries.index'))
                  <a href="{{ route('admin.monasteries.index') }}">Admin</a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="mobilemenu__logout">Odjavi se</button>
                </form>
              @else
                <div class="mobilemenu__sectionTitle">Nalog</div>
                <a href="{{ route('login') }}">Prijava</a>

                @if (Route::has('register'))
                  <a href="{{ route('register') }}">Registracija</a>
                @endif
              @endauth
            </div>

            <form class="mobilemenu__search" action="{{ route('monasteries.index') }}" method="GET" role="search">
              <label class="sr-only" for="mq">Pretraga manastira</label>
              <input id="mq" name="q" type="search" placeholder="Pretraga manastira…" />
              <button type="submit">Traži</button>
            </form>
          </div>
        </div>
      </div>
    </header>

    <main id="main" class="pageMain">
      @yield('content')
    </main>

    <footer class="footer" role="contentinfo">
      <div class="container footer__inner footer__inner--simple">
        <div class="footer__brand">☦ Pravoslavni Svetionik</div>

        <div class="footer__line">
          <i>„Ne branimo se od drugih, nego od zla u sebi.” — Patrijarh Pavle</i>
        </div>

        <div class="footer__line footer__small">
          © 2026 Pravoslavni Svetionik — Sva prava zadržana.
          Zabranjeno je neovlašćeno kopiranje, preuzimanje i distribucija sadržaja bez dozvole autora.
        </div>
      </div>
    </footer>
  </div>

<script>
  // Global CSRF helper + fetch wrapper (da svuda radi isto)
  (function(){
    const meta = document.querySelector('meta[name="csrf-token"]');
    window.__csrf = meta ? meta.getAttribute('content') : '';

    window.apiFetch = async function(url, options = {}) {
      const headers = Object.assign({
        'Accept': 'application/json',
        'X-CSRF-TOKEN': window.__csrf || ''
      }, options.headers || {});

      return fetch(url, Object.assign({}, options, { headers }));
    };
  })();

  // Mobile menu + acct close
  (function () {
    const html = document.documentElement;
    const btn  = document.getElementById('burgerBtn');
    const menu = document.getElementById('mobileMenu');

    if (!btn || !menu) return;

    const MQ = window.matchMedia('(max-width: 680px)');

    function isOpen() { return html.classList.contains('menu-open'); }
    function openMenu() {
      html.classList.add('menu-open');
      btn.setAttribute('aria-expanded', 'true');
      btn.textContent = '✕';
    }
    function closeMenu() {
      html.classList.remove('menu-open');
      btn.setAttribute('aria-expanded', 'false');
      btn.textContent = '☰';
    }
    function toggleMenu() { isOpen() ? closeMenu() : openMenu(); }

    btn.addEventListener('click', (e) => { e.preventDefault(); toggleMenu(); });
    document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && isOpen()) closeMenu(); });

    menu.addEventListener('click', (e) => {
      const a = e.target.closest('a');
      if (a) closeMenu();
    });

    document.addEventListener('click', (e) => {
      if (!isOpen()) return;
      const insideHeader = e.target.closest('.topnav');
      if (!insideHeader) closeMenu();
    });

    function handleBreakpointChange() {
      if (!MQ.matches && isOpen()) closeMenu();
    }
    handleBreakpointChange();
    if (MQ.addEventListener) MQ.addEventListener('change', handleBreakpointChange);
    else MQ.addListener(handleBreakpointChange);

    document.addEventListener('click', (e) => {
      const opened = document.querySelector('.acct[open]');
      if (!opened) return;
      if (!e.target.closest('.acct')) opened.removeAttribute('open');
    });
  })();
</script>

  {{-- Leaflet JS --}}
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

@stack('scripts')
</body>
</html>