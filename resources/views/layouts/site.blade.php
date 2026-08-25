<!doctype html>
<html lang="sr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Pravoslavni Svetionik')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Inter:wght@400;500;600;700;800;900&family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">

<link rel="stylesheet" href="/css/site.css">
<link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""
    />

    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css"
    />
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css"
    />

    <style>
        html,
        body {
            min-height: 100%;
            overflow-x: hidden;
        }

        body {
            margin: 0;
        }

        .page {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            isolation: isolate;
        }

        .pageMain {
            flex: 1 0 auto;
            display: block;
            position: relative;
            z-index: 1;
            min-height: 0;
            padding-top: 76px !important;
            padding-bottom: 24px;
        }

        .topnav {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            width: 100% !important;
            z-index: 999999 !important;
            background: rgba(18, 12, 13, 0.98) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border-bottom: 1px solid rgba(197, 162, 74, 0.35) !important;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.65) !important;
        }

        #mobileMenu {
            position: fixed !important;
            top: 68px !important;
            left: 0 !important;
            right: 0 !important;
            z-index: 999998 !important;
        }

        .footer,
        footer.footer {
            position: relative !important;
            left: auto !important;
            right: auto !important;
            bottom: auto !important;
            top: auto !important;
            width: 100% !important;
            transform: none !important;
            clear: both;
            margin-top: auto !important;
            z-index: 10 !important;
            background: rgba(18, 12, 13, 0.98) !important;
            border-top: 1px solid rgba(197, 162, 74, 0.28) !important;
            box-shadow: 0 -8px 32px rgba(0, 0, 0, 0.55) !important;
            padding: 16px 0 !important;
        }

        .footer::before,
        .footer::after,
        footer.footer::before,
        footer.footer::after {
            content: none !important;
            display: none !important;
        }

        .footer__inner--simple {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            gap: 16px !important;
            flex-wrap: wrap !important;
        }

        .footer__inner--simple .footer__brand {
            font-weight: 800;
            color: #c5a24a;
            font-size: 0.92rem;
            white-space: nowrap;
        }

        .footer__inner--simple .footer__line {
            font-size: 0.84rem;
            color: rgba(255, 255, 255, 0.72);
            text-align: center;
        }

        .footer__inner--simple .footer__small {
            font-size: 0.76rem;
            color: rgba(255, 255, 255, 0.48);
        }

        @media (max-width: 900px) {
            .footer__inner--simple {
                flex-direction: column !important;
                text-align: center !important;
                gap: 6px !important;
                padding: 4px 0 !important;
            }
            .footer__inner--simple .footer__small {
                display: none !important;
            }
        }

        /* Global Justified Text & Typography */
        .monBookBlock__text,
        .kt-paragraph,
        .kt-hero__lead,
        .text-book-layout p,
        .text-justified,
        .pageMain article p {
            text-align: justify !important;
            text-justify: inter-word !important;
            hyphens: auto;
            -webkit-hyphens: auto;
        }

        /* Global Golden Ornament Separator */
        .monBookSeparator,
        .kt-separator,
        .ornament-separator {
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 35px 0;
            position: relative;
            width: 100%;
        }
        .monBookSeparator::before,
        .monBookSeparator::after,
        .kt-separator::before,
        .kt-separator::after,
        .ornament-separator::before,
        .ornament-separator::after {
            content: "";
            flex: 1;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(197, 162, 74, 0.3) 50%, rgba(197, 162, 74, 0.7) 100%);
        }
        .monBookSeparator::after,
        .kt-separator::after,
        .ornament-separator::after {
            background: linear-gradient(90deg, rgba(197, 162, 74, 0.7), rgba(197, 162, 74, 0.3) 50%, transparent 100%);
        }
        .monBookSeparator__ornament,
        .kt-separator__ornament,
        .ornament-separator__ornament {
            color: #c5a24a;
            font-size: 18px;
            padding: 0 15px;
            line-height: 1;
            opacity: 0.85;
            text-shadow: 0 0 8px rgba(197, 162, 74, 0.3);
        }

        /* Back to Top Button */
        .back-to-top {
            position: fixed;
            bottom: 30px;
            right: 30px;
            z-index: 998;
            width: 48px;
            height: 48px;
            border-radius: 50%;
            background: linear-gradient(135deg, rgba(32, 22, 20, 0.95), rgba(18, 12, 13, 0.98));
            border: 1.5px solid rgba(197, 162, 74, 0.55);
            color: #e2c26a;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 10px 28px rgba(0, 0, 0, 0.6), 0 0 16px rgba(197, 162, 74, 0.2);
            opacity: 0;
            visibility: hidden;
            transform: translateY(18px) scale(0.9);
            transition: opacity 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        visibility 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        transform 0.3s cubic-bezier(0.4, 0, 0.2, 1),
                        border-color 0.2s ease,
                        background 0.2s ease,
                        box-shadow 0.2s ease;
        }
        .back-to-top.is-visible {
            opacity: 1;
            visibility: visible;
            transform: translateY(0) scale(1);
        }
        .back-to-top:hover {
            background: linear-gradient(135deg, rgba(50, 34, 30, 0.98), rgba(28, 18, 19, 1));
            border-color: #f0d186;
            color: #fff;
            transform: translateY(-4px) scale(1.08);
            box-shadow: 0 14px 34px rgba(0, 0, 0, 0.75), 0 0 24px rgba(197, 162, 74, 0.45);
        }
        .back-to-top:active {
            transform: translateY(-1px) scale(0.96);
        }
        @media (max-width: 600px) {
            .back-to-top {
                bottom: 20px;
                right: 20px;
                width: 42px;
                height: 42px;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="{{ request()->routeIs('home') ? 'page-home' : '' }}">
    <a class="skip-link" href="#main">Preskoči na sadržaj</a>

    <div class="page">
        <header class="topnav" role="banner">
            <div class="topnav__frame">
                <div class="topnav__inner">
                    <a class="brand" href="{{ route('home') }}" aria-label="Početna - Pravoslavni Svetionik">
                        <span class="brand__mark" aria-hidden="true">☦</span>
                        <span class="brand__text">
                            <span class="brand__name">Pravoslavni Svetionik</span>
                            <span class="brand__tag">digitalni vodič kroz svetinje Srbije</span>
                        </span>
                    </a>

                    <nav class="navlinks" aria-label="Glavna navigacija">
                        <a class="@yield('nav_home')" href="{{ route('home') }}">Početna</a>
                        <a class="@yield('nav_monasteries')" href="{{ route('monasteries.index') }}">Manastiri</a>
                        <a class="@yield('nav_ktitors')" href="{{ route('ktitors.index') }}">Ktitori</a>

                        <a
                            class="{{ request()->routeIs('pravoslavni.*') || request()->routeIs('vaskrs.*') || request()->routeIs('curiosities.*') || request()->routeIs('zanimljivosti.*') ? 'active' : '' }}"
                            href="{{ route('pravoslavni.index') }}"
                        >
                            Pravoslavni sadržaj
                        </a>

                        <a class="@yield('nav_map')" href="{{ route('map.index') }}">Mapa</a>
                        <a class="{{ request()->routeIs('edukacija.*') ? 'active' : '' }}" href="{{ route('edukacija.index') }}">
                            Edukacija
                        </a>
                    </nav>

                    <div class="topnav__actions">
                        <form class="navsearch" action="{{ route('search') }}" method="GET" role="search">
                            <label class="sr-only" for="q">Pretraga</label>
                            <input id="q" name="q" type="search" placeholder="Pretraga...">
                            <button type="submit" aria-label="Traži">🔎</button>
                        </form>

                        <div class="script-switch" aria-label="Izbor pisma">
                            <button type="button" id="btnCyr" class="script-switch__btn">Ћир</button>
                            <button type="button" id="btnLat" class="script-switch__btn">Lat</button>
                        </div>

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

            <div id="mobileMenu" class="mobilemenu" aria-label="Mobilni meni">
                <div class="mobilemenu__frame">
                    <div class="mobilemenu__inner">
                        <div class="mobilemenu__links">
                            <a href="{{ route('home') }}">Početna</a>
                            <a href="{{ route('monasteries.index') }}">Manastiri</a>
                            <a href="{{ route('ktitors.index') }}">Ktitori</a>
                            <a href="{{ route('pravoslavni.index') }}">Pravoslavni sadržaj</a>
                            <a href="{{ route('map.index') }}">Mapa</a>
                            <a href="{{ route('edukacija.index') }}">Edukacija</a>
                        </div>

                        <form class="mobilemenu__search" action="{{ route('search') }}" method="GET" role="search">
                            <label class="sr-only" for="mq">Pretraga</label>
                            <input id="mq" name="q" type="search" placeholder="Pretraži sve sadržaje...">
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
    </script>

    <script>
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
        })();
    </script>

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

            function protectSpecialTokens(text) {
                const tokens = [];
                
                // 1. Zastita AI akronima (AI, Ai, A.I., AI-...)
                text = text.replace(/\b(?:[AaАа][IiИи]|[AaАа]\.[IiИи]\.)\b/g, (match) => {
                    const id = `\uE000${tokens.length}\uE001`;
                    tokens.push({ id, val: match });
                    return id;
                });

                // 2. Zastita raspona rimskih brojeva (npr. XII–XV, XII-XV, I–III, IV–V, IX–XI, XIV–XV)
                const romanRangePattern = /(?:^|(?<=[\s(\[\"\'«„\-\/]))([IVXLCDM]{1,8})\s*([–—\-\/])\s*([IVXLCDM]{1,8})(?=[.,;:!?)\]\"\'»“\s]|$)/g;
                text = text.replace(romanRangePattern, (match) => {
                    const id = `\uE000${tokens.length}\uE001`;
                    tokens.push({ id, val: match });
                    return id;
                });

                // 3. Viseslovni rimski brojevi: II, III, IV, VI, VII, VIII, IX, XI, XII, XIII, XIV, XV, XVI, XVII, XVIII, XIX, XX, XXI, XXII, itd.
                const multiRomanPattern = /(?:^|(?<=[\s(\[\"\'«„\-\/]))(II|III|IV|VI|VII|VIII|IX|XI|XII|XIII|XIV|XV|XVI|XVII|XVIII|XIX|XX|XXI|XXII|XXIII|XXIV|XXV|XXVI|XXVII|XXVIII|XXIX|XXX|XL|L|LX|LXX|LXXX|XC|C|CC|CCC|CD|D|DC|DCC|DCCC|CM|M)\.?(?=[.,;:!?)\]\"\'»“\s]|$)/g;
                text = text.replace(multiRomanPattern, (match) => {
                    const id = `\uE000${tokens.length}\uE001`;
                    tokens.push({ id, val: match });
                    return id;
                });

                // 4. Jednoslovni rimski brojevi 'V' i 'X' (samostalno veliko slovo)
                const vxPattern = /(?:^|(?<=[\s(\[\"\'«„\-\/]))([VX])\.?(?=[.,;:!?)\]\"\'»“\s]|$)/g;
                text = text.replace(vxPattern, (match) => {
                    const id = `\uE000${tokens.length}\uE001`;
                    tokens.push({ id, val: match });
                    return id;
                });

                // 5. Jednoslovni rimski broj 'I':
                // a) U zagradama ili navodnicima: (I), [I], "I"
                text = text.replace(/(?<=[\(\[\"\'«„])I(?=[\)\]\"\'»“\.])/g, (match) => {
                    const id = `\uE000${tokens.length}\uE001`;
                    tokens.push({ id, val: match });
                    return id;
                });

                // b) Sa tackom: "I." (npr. "I. razred", "faza I.")
                text = text.replace(/(?:^|(?<=[\s]))I\.(?=[.,;:!?)\]\"\'»“\s]|$)/g, (match) => {
                    const id = `\uE000${tokens.length}\uE001`;
                    tokens.push({ id, val: match });
                    return id;
                });

                // c) Iza imena vladara, titula, vekova ili strukturalnih pojmova
                const precededByRulerOrTerm = /(?<=\b(?:Stefan|Stefana|Stefanu|Uroš|Uroša|Urošu|Uros|Urosa|Urosu|Petar|Petra|Petru|Lazar|Lazara|Lazaru|Pavle|Pavla|Pavlu|Dušan|Dušana|Dušanu|Dusan|Dusana|Dusanu|Mihailo|Mihaila|Mihailu|Karlo|Karla|Karlu|Oto|Otona|Otonu|Jovan|Jovana|Jovanu|Nikola|Nikole|Nikoli|Nemanja|Nemanje|Nemanji|Aleksandar|Aleksandra|Aleksandru|Radoslav|Radoslava|Radoslavu|Vladislav|Vladislava|Vladislavu|Dragutin|Dragutina|Dragutinu|Milutin|Milutina|Milutinu|Dečanski|Decanski|Urošic|Urosic|Tvrtko|Tvrtka|Tvrtku|Luj|Luja|Luju|Vilijam|Vilijama|Vilijamu|Fridrih|Fridriha|Fridrihu|Henrik|Henrika|Henriku|Kralj|Kralja|Kralju|Car|Cara|Caru|Knez|Kneza|Knezu|Despot|Despota|Despotu|Papa|Pape|Papi|Patrijarh|Patrijarha|Patrijarhu|Episkop|Episkopa|Episkopu|deo|dela|delu|Deo|Dela|Delu|glava|glave|glavi|Glava|Glave|Glavi|tom|toma|tomu|Tom|Toma|Tomu|knjiga|knjige|knjizi|Knjiga|Knjige|Knjizi|član|člana|članu|Član|Člana|Članu|faza|faze|fazi|Faza|Faze|Fazi|nivo|nivoa|nivou|Nivo|Nivoa|Nivou|stepen|stepena|stepenu|Stepen|Stepena|Stepenu)\s+)I\b/g;
                text = text.replace(precededByRulerOrTerm, (match) => {
                    const id = `\uE000${tokens.length}\uE001`;
                    tokens.push({ id, val: 'I' });
                    return id;
                });

                // d) Ispred "vek", "veka", "veku", "stoleće"
                text = text.replace(/\bI(?=\s+(?:vek|veka|veku|stoleće|stoleća|stoleću|stolećem))/gi, (match) => {
                    const id = `\uE000${tokens.length}\uE001`;
                    tokens.push({ id, val: 'I' });
                    return id;
                });

                return { text, tokens };
            }

            function restoreSpecialTokens(text, tokens) {
                for (const t of tokens) {
                    text = text.replace(t.id, t.val);
                }
                return text;
            }

            function latToCyr(text) {
                if (!text) return text;

                let { text: protectedText, tokens } = protectSpecialTokens(String(text));

                let result = protectedText
                    .replace(/Dž/g, 'Џ')
                    .replace(/DŽ/g, 'Џ')
                    .replace(/dž/g, 'џ')
                    .replace(/Lj/g, 'Љ')
                    .replace(/LJ/g, 'Љ')
                    .replace(/lj/g, 'љ')
                    .replace(/Nj/g, 'Њ')
                    .replace(/NJ/g, 'Њ')
                    .replace(/nj/g, 'њ');

                result = result.replace(/[A-Za-zĐđŽžĆćČčŠš]/g, ch => LAT_TO_CYR[ch] || ch);

                return restoreSpecialTokens(result, tokens);
            }

            function cyrToLat(text) {
                if (!text) return text;
                let result = String(text);
                result = result
                    .replace(/Џ/g, 'Dž')
                    .replace(/џ/g, 'dž')
                    .replace(/Љ/g, 'Lj')
                    .replace(/љ/g, 'lj')
                    .replace(/Њ/g, 'Nj')
                    .replace(/њ/g, 'nj');
                return result.replace(/[\u0400-\u04FF]/g, ch => CYR_TO_LAT[ch] || ch);
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
                try {
                    window.dispatchEvent(new CustomEvent('sitescriptchange', { detail: { mode: mode } }));
                } catch (e) {}
            }

            // Expose globally for dynamic components and modals
            window.getSiteScript = function () {
                return localStorage.getItem(STORAGE_KEY) || 'lat';
            };
            window.latToCyr = latToCyr;
            window.cyrToLat = cyrToLat;
            window.convertSiteText = function (text, mode) {
                const activeMode = mode || window.getSiteScript();
                return convertText(text, activeMode);
            };
            window.applySiteScriptToNode = function (node, mode) {
                const targetNode = node || document.body;
                const activeMode = mode || window.getSiteScript();
                processNode(targetNode, activeMode);
            };

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
    
    {{-- DUGME ZA POVRATAK NA VRH STRANICE (BACK TO TOP) --}}
    <button type="button" id="backToTopBtn" class="back-to-top" aria-label="Nazad na vrh" title="Povratak na vrh">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 15l-6-6-6 6"/>
        </svg>
    </button>

    <script>
        (function () {
            const btn = document.getElementById('backToTopBtn');
            if (!btn) return;

            let ticking = false;
            window.addEventListener('scroll', function () {
                if (!ticking) {
                    window.requestAnimationFrame(function () {
                        if (window.scrollY > 280) {
                            btn.classList.add('is-visible');
                        } else {
                            btn.classList.remove('is-visible');
                        }
                        ticking = false;
                    });
                    ticking = true;
                }
            }, { passive: true });

            btn.addEventListener('click', function () {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        })();
    </script>
</body>
</html>