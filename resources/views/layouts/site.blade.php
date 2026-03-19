<!doctype html>
<html lang="sr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- GLAVNI CSS -->
    <link rel="stylesheet" href="{{ asset('css/site.css') }}">
</head>
<body>
  @php
    $hidePrimaryNav = auth()->guest() && (
      request()->routeIs('home') ||
      request()->routeIs('login') ||
      request()->routeIs('register') ||
      request()->routeIs('password.request') ||
      request()->routeIs('password.reset') ||
      request()->routeIs('password.email')
    );
  @endphp

  <a class="skip-link" href="#main">Preskoči na sadržaj</a>

  <div class="page">
    <header class="topnav" role="banner">
      <div class="topnav__frame">
        <div class="topnav__inner">

          {{-- LEFT: Brand --}}
          <a class="brand" href="{{ route('home') }}" aria-label="Početna - Pravoslavni Svetionik">
            <span class="brand__mark" aria-hidden="true">☦</span>
            <span class="brand__text">
              <span class="brand__name">Pravoslavni Svetionik</span>
              <span class="brand__tag">digitalni vodič kroz svetinje Srbije</span>
            </span>
          </a>

          {{-- CENTER: Desktop navigation --}}
          @unless($hidePrimaryNav)
            <nav class="navlinks" aria-label="Glavna navigacija">
              <a class="@yield('nav_home')" href="{{ route('home') }}">Početna</a>

              <a class="@yield('nav_monasteries')" href="{{ route('monasteries.index') }}">
                Manastiri
              </a>

              <a class="@yield('nav_ktitors')" href="{{ route('ktitors.index') }}">
                Ktitori
              </a>

              <a
                class="{{ request()->routeIs('pravoslavni.*')
                    || request()->routeIs('vaskrs.*')
                    || request()->routeIs('curiosities.*')
                    || request()->routeIs('zanimljivosti.*')
                    ? 'active' : '' }}"
                href="{{ route('pravoslavni.index') }}"
              >
                Pravoslavni sadržaj
              </a>

              @auth
                <a class="@yield('nav_map')" href="{{ route('map.index') }}">Mapa</a>

                <a class="{{ request()->routeIs('edukacija.*') ? 'active' : '' }}"
                   href="{{ route('edukacija.index') }}">
                  Edukacija
                </a>
              @endauth
            </nav>
          @endunless

          {{-- RIGHT: Actions --}}
          <div class="topnav__actions">

            {{-- Search --}}
            @unless($hidePrimaryNav)
              <form class="navsearch navsearch--expand"
                    action="{{ route('monasteries.index') }}"
                    method="GET"
                    role="search">
                <label class="sr-only" for="q">Pretraga</label>
                <input id="q" name="q" type="search" placeholder="Pretraga…" />
                <button type="submit" aria-label="Traži">🔎</button>
              </form>
            @endunless

            {{-- Account --}}
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

                  @if(auth()->user() && auth()->user()->role === 'admin')
                    <a role="menuitem" class="acct__item" href="{{ route('admin.dashboard') }}">Admin</a>
                  @endif

                  <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="acct__item acct__btn">Odjavi se</button>
                  </form>
                @else
                  <a role="menuitem" class="acct__item" href="{{ route('login') }}">Prijava</a>

                  @if(Route::has('register'))
                    <a role="menuitem" class="acct__item" href="{{ route('register') }}">Registracija</a>
                  @endif
                @endauth
              </div>
            </details>

            <div class="script-switch" aria-label="Izbor pisma">
              <button type="button" id="btnCyr" class="script-switch__btn">Ћир</button>
              <button type="button" id="btnLat" class="script-switch__btn">Lat</button>
            </div>

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
              @unless($hidePrimaryNav)
                <a href="{{ route('home') }}">Početna</a>
                <a href="{{ route('monasteries.index') }}">Manastiri</a>
                <a href="{{ route('ktitors.index') }}">Ktitori</a>
                <a href="{{ route('pravoslavni.index') }}">Pravoslavni sadržaj</a>

                @auth
                  <a href="{{ route('map.index') }}">Mapa</a>
                  <a href="{{ route('edukacija.index') }}">Edukacija</a>
                @endauth

                <div class="mobilemenu__divider" aria-hidden="true"></div>
              @endunless

              @auth
                <div class="mobilemenu__sectionTitle">Nalog</div>
                <a href="{{ route('profile.edit') }}">Profil</a>

                @if(auth()->user() && auth()->user()->role === 'admin')
                  <a href="{{ route('admin.dashboard') }}">Admin</a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="mobilemenu__logout">Odjavi se</button>
                </form>
              @else
                <div class="mobilemenu__sectionTitle">Nalog</div>
                <a href="{{ route('login') }}">Prijava</a>

                @if(Route::has('register'))
                  <a href="{{ route('register') }}">Registracija</a>
                @endif
              @endauth
            </div>

            @unless($hidePrimaryNav)
              <form class="mobilemenu__search"
                    action="{{ route('monasteries.index') }}"
                    method="GET"
                    role="search">
                <label class="sr-only" for="mq">Pretraga manastira</label>
                <input id="mq" name="q" type="search" placeholder="Pretraga manastira…" />
                <button type="submit">Traži</button>
              </form>
            @endunless

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
    (function () {
      const meta = document.querySelector('meta[name="csrf-token"]');
      window.__csrf = meta ? meta.getAttribute('content') : '';

      window.apiFetch = async function (url, options = {}) {
        const headers = Object.assign({
          'Accept': 'application/json',
          'X-CSRF-TOKEN': window.__csrf || ''
        }, options.headers || {});

        return fetch(url, Object.assign({}, options, { headers }));
      };
    })();

    (function () {
      const html = document.documentElement;
      const btn = document.getElementById('burgerBtn');
      const menu = document.getElementById('mobileMenu');

      if (!btn || !menu) return;

      const MQ = window.matchMedia('(max-width: 680px)');

      function isOpen() {
        return html.classList.contains('menu-open');
      }

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

      function toggleMenu() {
        isOpen() ? closeMenu() : openMenu();
      }

      btn.addEventListener('click', function (e) {
        e.preventDefault();
        toggleMenu();
      });

      document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && isOpen()) {
          closeMenu();
        }
      });

      menu.addEventListener('click', function (e) {
        const a = e.target.closest('a');
        if (a) closeMenu();
      });

      document.addEventListener('click', function (e) {
        if (!isOpen()) return;
        const insideHeader = e.target.closest('.topnav');
        if (!insideHeader) closeMenu();
      });

      function handleBreakpointChange() {
        if (!MQ.matches && isOpen()) closeMenu();
      }

      handleBreakpointChange();

      if (MQ.addEventListener) {
        MQ.addEventListener('change', handleBreakpointChange);
      } else {
        MQ.addListener(handleBreakpointChange);
      }

      document.addEventListener('click', function (e) {
        const opened = document.querySelector('.acct[open]');
        if (!opened) return;
        if (!e.target.closest('.acct')) {
          opened.removeAttribute('open');
        }
      });
    })();
  </script>

  {{-- Leaflet JS --}}
  <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
  <script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

  @stack('scripts')

  <script>
  (function () {
    const STORAGE_KEY = 'site_script';

    const LAT_TO_CYR = {
      'A':'А','B':'Б','V':'В','G':'Г','D':'Д','Đ':'Ђ','E':'Е','Ž':'Ж','Z':'З','I':'И',
      'J':'Ј','K':'К','L':'Л','M':'М','N':'Н','O':'О','P':'П','R':'Р','S':'С','T':'Т',
      'Ć':'Ћ','U':'У','F':'Ф','H':'Х','C':'Ц','Č':'Ч','Š':'Ш',
      'a':'а','b':'б','v':'в','g':'г','d':'д','đ':'ђ','e':'е','ž':'ж','z':'з','i':'и',
      'j':'ј','k':'к','l':'л','m':'м','n':'н','o':'о','p':'п','r':'р','s':'с','t':'т',
      'ć':'ћ','u':'у','f':'ф','h':'х','c':'ц','č':'ч','š':'ш'
    };

    const CYR_TO_LAT = {
      'А':'A','Б':'B','В':'V','Г':'G','Д':'D','Ђ':'Đ','Е':'E','Ж':'Ž','З':'Z','И':'I',
      'Ј':'J','К':'K','Л':'L','Љ':'Lj','М':'M','Н':'N','Њ':'Nj','О':'O','П':'P','Р':'R',
      'С':'S','Т':'T','Ћ':'Ć','У':'U','Ф':'F','Х':'H','Ц':'C','Ч':'Č','Џ':'Dž','Ш':'Š',
      'а':'a','б':'b','в':'v','г':'g','д':'d','ђ':'đ','е':'e','ж':'ž','з':'z','и':'i',
      'ј':'j','к':'k','л':'l','љ':'lj','м':'m','н':'n','њ':'nj','о':'o','п':'p','р':'r',
      'с':'s','т':'t','ћ':'ć','у':'u','ф':'f','х':'h','ц':'c','ч':'č','џ':'dž','ш':'š'
    };

    const ATTRS = ['placeholder', 'title', 'aria-label'];

    function latToCyr(text) {
      if (!text) return text;

      let result = String(text);

      result = result
        .replace(/Dž/g, 'Џ')
        .replace(/DŽ/g, 'Џ')
        .replace(/dž/g, 'џ')
        .replace(/Lj/g, 'Љ')
        .replace(/LJ/g, 'Љ')
        .replace(/lj/g, 'љ')
        .replace(/Nj/g, 'Њ')
        .replace(/NJ/g, 'Њ')
        .replace(/nj/g, 'њ');

      return result.replace(/[A-Za-zĐđŽžĆćČčŠš]/g, ch => LAT_TO_CYR[ch] || ch);
    }

    function cyrToLat(text) {
      if (!text) return text;
      return String(text).replace(/[\u0400-\u04FF]/g, ch => CYR_TO_LAT[ch] || ch);
    }

    function convertText(text, mode) {
      return mode === 'cyr' ? latToCyr(text) : cyrToLat(text);
    }

    function skipElement(el) {
      if (!el) return true;
      const tag = el.tagName;
      return ['SCRIPT', 'STYLE', 'TEXTAREA', 'CODE', 'PRE'].includes(tag);
    }

    function processNode(node, mode) {
      if (node.nodeType === Node.TEXT_NODE) {
        const parent = node.parentElement;
        if (!parent || skipElement(parent)) return;
        if (!node.nodeValue.trim()) return;
        node.nodeValue = convertText(node.nodeValue, mode);
        return;
      }

      if (node.nodeType === Node.ELEMENT_NODE) {
        if (skipElement(node)) return;

        ATTRS.forEach(attr => {
          if (node.hasAttribute(attr)) {
            node.setAttribute(attr, convertText(node.getAttribute(attr), mode));
          }
        });

        node.childNodes.forEach(child => processNode(child, mode));
      }
    }

    function updateButtons(mode) {
      const btnCyr = document.getElementById('btnCyr');
      const btnLat = document.getElementById('btnLat');
      if (btnCyr) btnCyr.classList.toggle('is-active', mode === 'cyr');
      if (btnLat) btnLat.classList.toggle('is-active', mode === 'lat');
    }

    function applyMode(mode) {
      processNode(document.body, mode);
      localStorage.setItem(STORAGE_KEY, mode);
      updateButtons(mode);
    }

    document.addEventListener('DOMContentLoaded', function () {
      const btnCyr = document.getElementById('btnCyr');
      const btnLat = document.getElementById('btnLat');

      if (btnCyr) {
        btnCyr.addEventListener('click', function () {
          applyMode('cyr');
        });
      }

      if (btnLat) {
        btnLat.addEventListener('click', function () {
          applyMode('lat');
        });
      }

      const saved = localStorage.getItem(STORAGE_KEY);
      if (saved === 'cyr' || saved === 'lat') {
        applyMode(saved);
      } else {
        updateButtons('lat');
      }
    });
  })();
  </script>
</body>
</html>