@extends('layouts.site')

@section('title', ($monastery->name ?? 'Manastir') . ' — Pravoslavni Svetionik')
@section('nav_monasteries', 'active')
@php
    $slug = $monastery->slug ?? 'placeholder';
    $img = asset('images/monasteries/' . $slug . '.jpg');
    $fallbackImg = asset('images/monasteries/placeholder.jpg');

    $p = $monastery->profile ?? null;

    $eparchyName = null;
    if (!empty($monastery->eparchy)) {
        $eparchyName = is_string($monastery->eparchy)
            ? $monastery->eparchy
            : ($monastery->eparchy->name ?? null);
    } elseif (!empty($monastery->eparchy_name)) {
        $eparchyName = $monastery->eparchy_name;
    }

    $hasDescription = !empty($monastery->description);

    $sections = [
        ['id' => 'opis', 'label' => 'Opis', 'show' => $hasDescription],
        ['id' => 'uvod', 'label' => 'Uvod', 'show' => !$hasDescription && !empty($p?->intro)],
        ['id' => 'istorija', 'label' => 'Istorija', 'show' => !$hasDescription && !empty($p?->history)],
        ['id' => 'arhitektura', 'label' => 'Arhitektura', 'show' => !$hasDescription && !empty($p?->architecture)],
        ['id' => 'ktitor', 'label' => 'Ktitor', 'show' => !$hasDescription && !empty($p?->ktitor_text)],
        ['id' => 'freske', 'label' => 'Umetnost i freske', 'show' => !$hasDescription && !empty($p?->art_frescoes)],
        ['id' => 'zanimljivosti', 'label' => 'Zanimljivosti', 'show' => !$hasDescription && !empty($p?->interesting_facts)],
    ];

    $regionLabel = (!empty($monastery->region) && strtolower($monastery->region) !== 'nepoznato') ? $monastery->region : null;
    $cityLabel = (!empty($monastery->city) && strtolower($monastery->city) !== 'nepoznato') ? $monastery->city : null;
    $godinaLabel = (!empty($monastery->godina_izgradnje) && strtolower($monastery->godina_izgradnje) !== 'nepoznato') ? $monastery->godina_izgradnje : null;

    $hasCoords = !empty($monastery->lat) && !empty($monastery->lng);
    $ktitorName = !empty($monastery->ktitor) ? trim($monastery->ktitor) : null;
@endphp

@section('content')
<section class="section monPro">
    <div class="container">

        <a class="btn2 monPro__back" href="{{ route('monasteries.index') }}">← Nazad na listu</a>

        <div class="monHeaderCard">
            <div class="monHeaderCard__inner">
                <div class="monHeaderCard__content">
                    <h1 class="monHeaderCard__title">{{ $monastery->name ?? 'Manastir' }}</h1>
                </div>

                <div class="monHeaderCard__actions">
                    @if($hasCoords)
                        <a
                            class="btn2"
                            target="_blank"
                            rel="noopener"
                            href="http://maps.google.com/?q={{ $monastery->lat }},{{ $monastery->lng }}"
                        >
                            Otvori na mapi
                        </a>
                    @endif

                    <a class="btn2 btn2--ghost" href="#sadrzaj">Sadržaj</a>
                </div>
            </div>
        </div>

        <div class="monGrid" id="sadrzaj">

            {{-- GLAVNI DEO SA TEKSTOM - PRETVOREN U ELEGANTNI KNJIŠKI STIL BEZ KUTIJA --}}
            <div class="monMain text-book-layout">

                <div class="monTocMobile">
                    <div class="muted monTocMobile__label">Sadržaj</div>
                    <div class="monTocMobile__links">
                        @foreach($sections as $s)
                            @if($s['show'])
                                <a href="#{{ $s['id'] }}">{{ $s['label'] }}</a>
                            @endif
                        @endforeach
                    </div>
                </div>

                @if($hasDescription)
                    @php
                        $desc = (string) $monastery->description;

                        $markers = [
                            'OPŠTI PODACI:',
                            'ISTORIJA:',
                            'ARHITEKTURA:',
                            'DUHOVNI ŽIVOT:',
                            'ZNAČAJ:',
                            'UZ STRUČNI OPIS:',
                        ];

                        foreach ($markers as $marker) {
                            $desc = str_replace($marker, "\n\n" . $marker, $desc);
                        }

                        $desc = trim($desc);
                        $parts = preg_split('/\n{2,}/u', $desc, -1, PREG_SPLIT_NO_EMPTY);

                        $structured = [];
                        foreach ($parts as $part) {
                            $part = trim($part);
                            $isHeading = false;
                            $heading = null;
                            $body = $part;

                            foreach ($markers as $marker) {
                                if (str_starts_with($part, $marker)) {
                                    $isHeading = true;
                                    $heading = rtrim($marker, ':');
                                    $body = trim(mb_substr($part, mb_strlen($marker)));
                                    break;
                                }
                            }

                            $structured[] = [
                                'isHeading' => $isHeading,
                                'heading' => $heading,
                                'body' => $body,
                            ];
                        }

                        $firstBlock = null;
                        $restBlocks = [];

                        foreach ($structured as $item) {
                            if ($item['isHeading'] && $item['heading'] === 'OPŠTI PODACI' && $firstBlock === null) {
                                $firstBlock = $item;
                            } else {
                                $restBlocks[] = $item;
                            }
                        }
                    @endphp

                    <article class="monBookSection" id="opis">
                        <h2 class="monBookSection__main-title">Opis manastira</h2>

                        @if($firstBlock)
                            <section class="monBookBlock">
                                <h3 class="monBookBlock__title">{{ $firstBlock['heading'] }}</h3>
                                @if($firstBlock['body'] !== '')
                                    <p class="monBookBlock__text">{{ $firstBlock['body'] }}</p>
                                @endif
                            </section>
                            <div class="monBookSeparator"><span class="monBookSeparator__ornament">❧</span></div>
                        @endif

                        @foreach($restBlocks as $item)
                            @if($item['isHeading'])
                                <section class="monBookBlock">
                                    <h3 class="monBookBlock__title">{{ $item['heading'] }}</h3>
                                    @if($item['body'] !== '')
                                        <p class="monBookBlock__text">{{ $item['body'] }}</p>
                                    @endif
                                </section>
                                @if(!$loop->last)
                                    <div class="monBookSeparator"><span class="monBookSeparator__ornament">❧</span></div>
                                @endif
                            @else
                                <p class="monBookBlock__text">{{ $item['body'] }}</p>
                            @endif
                        @endforeach
                    </article>

                @elseif($p)
                    @if(!empty($p->intro))
                        <article class="monBookSection" id="uvod">
                            <h3 class="monBookBlock__title">Uvod</h3>
                            <p class="monBookBlock__text">{{ $p->intro }}</p>
                            <div class="monBookSeparator"><span class="monBookSeparator__ornament">❧</span></div>
                        </article>
                    @endif

                    @if(!empty($p->history))
                        <article class="monBookSection" id="istorija">
                            <h3 class="monBookBlock__title">Istorija</h3>
                            <p class="monBookBlock__text">{{ $p->history }}</p>
                            <div class="monBookSeparator"><span class="monBookSeparator__ornament">❧</span></div>
                        </article>
                    @endif

                    @if(!empty($p->architecture))
                        <article class="monBookSection" id="arhitektura">
                            <h3 class="monBookBlock__title">Arhitektura</h3>
                            <p class="monBookBlock__text">{{ $p->architecture }}</p>
                            <div class="monBookSeparator"><span class="monBookSeparator__ornament">❧</span></div>
                        </article>
                    @endif

                    @if(!empty($p->ktitor_text))
                        <article class="monBookSection" id="ktitor">
                            <h3 class="monBookBlock__title">Ktitor</h3>
                            <p class="monBookBlock__text">{{ $p->ktitor_text }}</p>
                            <div class="monBookSeparator"><span class="monBookSeparator__ornament">❧</span></div>
                        </article>
                    @endif

                    @if(!empty($p->art_frescoes))
                        <article class="monBookSection" id="freske">
                            <h3 class="monBookBlock__title">Umetnost i freske</h3>
                            <p class="monBookBlock__text">{{ $p->art_frescoes }}</p>
                            <div class="monBookSeparator"><span class="monBookSeparator__ornament">❧</span></div>
                        </article>
                    @endif

                    @if(!empty($p->interesting_facts))
                        <article class="monBookSection" id="zanimljivosti">
                            <h3 class="monBookBlock__title">Zanimljivosti</h3>
                            <p class="monBookBlock__text">{{ $p->interesting_facts }}</p>
                        </article>
                    @endif
                @else
                    <p class="muted">Detaljan tekst za ovu svetinju još nije dodat.</p>
                @endif

                @php
                    $sources = $p?->sources_json ?? null;

                    if (is_string($sources)) {
                        $decoded = json_decode($sources, true);
                        if (json_last_error() === JSON_ERROR_NONE) {
                            $sources = $decoded;
                        }
                    }
                @endphp

                @if(!empty($sources) && is_array($sources))
                    <div class="monBookSources">
                        <h3 class="monBookSources__title">Izvori</h3>
                        <ul class="monBookSources__list">
                            @foreach($sources as $src)
                                @php
                                    $title = $src['title'] ?? 'Izvor';
                                    $url = $src['url'] ?? null;
                                @endphp
                                <li>
                                    @if($url)
                                        <a href="{{ $url }}" target="_blank" rel="noopener">{{ $title }}</a>
                                    @else
                                        {{ $title }}
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

{{-- DESNI ASIDE PANEL - STRUKTURA SA INTEGRISANIM KUSTOS KONTEKSTOM --}}
            <aside class="monSide">
                
                <div class="monSideBannerPhoto">
                    <img
                        src="{{ $img }}"
                        alt="Fotografija manastira {{ $monastery->name }}"
                        loading="lazy"
                        onerror="this.src='{{ $fallbackImg }}'"
                    >
                </div>

                <div class="card monSide__card">
                    <h3 class="monSide__title">Informacije</h3>

                    <div class="monKV">
                        @if($ktitorName)
                            <div class="monKV__row">
                                <div class="monKV__k">Ktitor / Zadužbinar</div>
                                <div class="monKV__v nm-gold-highlight">{{ $ktitorName }}</div>
                            </div>
                        @endif

                        @if($godinaLabel)
                            <div class="monKV__row">
                                <div class="monKV__k">Vreme osnivanja</div>
                                <div class="monKV__v nm-gold-highlight">{{ $godinaLabel }}</div>
                            </div>
                        @endif

                        <div class="monKV__row">
                            <div class="monKV__k">Region</div>
                            <div class="monKV__v">{{ $regionLabel ?: '—' }}</div>
                        </div>

                        <div class="monKV__row">
                            <div class="monKV__k">Grad / Mesto</div>
                            <div class="monKV__v">{{ $cityLabel ?: '—' }}</div>
                        </div>

                        @if(!empty($eparchyName))
                            <div class="monKV__row">
                                <div class="monKV__k">Eparhija</div>
                                <div class="monKV__v">{{ $eparchyName }}</div>
                            </div>
                        @endif

                        @if(!empty($monastery->address))
                            <div class="monKV__row">
                                <div class="monKV__k">Adresa</div>
                                <div class="monKV__v">{{ $monastery->address }}</div>
                            </div>
                        @endif
                    </div>

                    <div class="monSide__actions">
                        @if($hasCoords)
                            <a
                                class="btn2 btn2--wide"
                                target="_blank"
                                rel="noopener"
                                href="http://maps.google.com/?q={{ $monastery->lat }},{{ $monastery->lng }}"
                            >
                                Navigacija
                            </a>
                        @endif

                        @if(!empty($monastery->source_url))
                            <a
                                class="btn2 btn2--ghost btn2--wide"
                                target="_blank"
                                rel="noopener"
                                href="{{ $monastery->source_url }}"
                            >
                                Pročitaj više
                            </a>
                        @endif
                    </div>

                   {{-- DUGME KOJE POKREĆE ČET --}}
                    <div class="kustos-trigger-wrapper" style="margin: 20px 0;">
                        <button type="button" id="openKustosBtn" class="btn2 btn2--wide btn2--gold">
                            ✨ Pitaj AI Letopisca
                        </button>
                    </div>

                    {{-- MODALNI PROZOR ZA ČET --}}
                    <div id="kustosModal" class="kustos-modal">
                        <div class="kustos-modal__overlay" id="closeKustosOverlay"></div>
                        <div class="kustos-modal__content">
                            
                            {{-- Zaglavlje --}}
                            <div class="kustos-modal__header">
                                <div class="kustos-modal__title-box">
                                    <span class="kustos-modal__icon">📜</span>
                                    <h3 class="kustos-modal__title">Digitalni Letopisac</h3>
                                </div>
                                <button type="button" class="kustos-modal__close" id="closeKustosBtn">&times;</button>
                            </div>

                            {{-- Telo četa --}}
                            <div class="kustos-modal__body">
                                @include('kustos.chat', ['entitet' => $monastery, 'tip' => 'manastir'])
                            </div>

                        </div>
                    </div>

                    {{-- Skriveni parametri --}}
                    <div id="kustos-context" 
                         data-id="{{ $monastery->id }}" 
                         data-name="{{ $monastery->name }}" 
                         style="display: none;">
                    </div>

                    {{-- CSS STILOVI ZA PRELEP POP-UP --}}
                    <style>
                    .kustos-modal {
                        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
                        z-index: 9999; display: flex; align-items: center; justify-content: center;
                        opacity: 0; pointer-events: none; transition: opacity 0.3s ease;
                    }
                    .kustos-modal--open { opacity: 1; pointer-events: auto; }
                    .kustos-modal__overlay {
                        position: absolute; width: 100%; height: 100%;
                        background: rgba(10, 8, 5, 0.85); backdrop-filter: blur(5px);
                    }
                    .kustos-modal__content {
                        position: relative; width: 90%; max-width: 420px;
                        height: 600px; max-height: 85vh;
                        background: #1a1512; border: 1px solid #c5a059; border-radius: 16px;
                        box-shadow: 0 25px 50px -12px rgba(0,0,0,0.7);
                        display: flex; flex-direction: column;
                        transform: translateY(30px) scale(0.95);
                        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
                    }
                    .kustos-modal--open .kustos-modal__content { transform: translateY(0) scale(1); }
                    .kustos-modal__header {
                        padding: 16px 20px; background: #241d19;
                        border-bottom: 1px solid #332720; border-radius: 16px 16px 0 0;
                        display: flex; align-items: center; justify-content: space-between; flex-shrink: 0;
                    }
                    .kustos-modal__title-box { display: flex; align-items: center; gap: 12px; }
                    .kustos-modal__icon { font-size: 1.5em; }
                    .kustos-modal__title { margin: 0; font-size: 1.15em; color: #c5a059; font-weight: 600; }
                    .kustos-modal__close {
                        background: none; border: none; color: #a39288; font-size: 32px;
                        cursor: pointer; padding: 0; line-height: 1; transition: 0.2s;
                    }
                    .kustos-modal__close:hover { color: #c5a059; transform: scale(1.1); }
                    .kustos-modal__body {
                        flex: 1; padding: 16px; overflow-y: auto;
                        display: flex; flex-direction: column;
                    }
                    /* Obezbeđuje da čet uvek ima prostora */
                    .kustos-modal__body > div { flex: 1; display: flex; flex-direction: column; height: 100%; }
                    
                    .btn2--gold {
                        background: #c5a059 !important; color: #1a1512 !important;
                        font-weight: 700; font-size: 1.1em; padding: 14px 24px;
                        border-radius: 8px; transition: all 0.2s ease;
                        box-shadow: 0 4px 15px rgba(197, 160, 89, 0.2);
                    }
                    .btn2--gold:hover { background: #e0b86f !important; transform: translateY(-2px); }
                    </style>

                    {{-- JAVASCRIPT KOJI POKREĆE SVE --}}
                    <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const modal = document.getElementById('kustosModal');
                        const openBtn = document.getElementById('openKustosBtn');
                        const closeBtn = document.getElementById('closeKustosBtn');
                        const closeOverlay = document.getElementById('closeKustosOverlay');
                        const contextEl = document.getElementById('kustos-context');
                        
                        if (!modal || !openBtn || !contextEl) return;

                        const monasteryId = contextEl.dataset.id;
                        const monasteryName = contextEl.dataset.name;

                        // Otvaranje modala
                        openBtn.addEventListener('click', () => {
                            modal.classList.add('kustos-modal--open');
                            triggerContextGreeting(monasteryId, monasteryName);
                        });

                        // Zatvaranje modala
                        const closeModal = () => modal.classList.remove('kustos-modal--open');
                        closeBtn?.addEventListener('click', closeModal);
                        closeOverlay?.addEventListener('click', closeModal);
                    });

                    async function triggerContextGreeting(id, name) {
                        if (sessionStorage.getItem('kustos_greeted_' + id)) return;

                        try {
                            const response = await fetch("{{ route('kustos.context-greeting') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({ model_id: id, model_type: 'manastir' })
                            });

                            const data = await response.json();
                            
                            if (data.success && data.greeting) {
                                sessionStorage.setItem('kustos_greeted_' + id, 'true');

                                // Pronađi prostor za poruke iz tvog kustos.chat fajla
                                const chatMessages = document.querySelector('.chat-messages') || document.getElementById('chatMessages');
                                if (chatMessages) {
                                    const greetingDiv = document.createElement('div');
                                    greetingDiv.className = 'chat-message chat-message--ai'; 
                                    greetingDiv.innerHTML = `<p>${data.greeting}</p>`;
                                    chatMessages.appendChild(greetingDiv);
                                    chatMessages.scrollTop = chatMessages.scrollHeight;
                                }
                            }
                        } catch (error) {
                            console.error('Greška pri učitavanju AI konteksta:', error);
                        }
                    }
                    </script>

                    <script>
                    document.addEventListener('DOMContentLoaded', () => {
                        const contextEl = document.getElementById('kustos-context');
                        if (!contextEl) return;

                        const monasteryId = contextEl.dataset.id;
                        const monasteryName = contextEl.dataset.name;

                        // Pokretanje automatskog pozdrava na osnovu trenutnog manastira
                        triggerContextGreeting(monasteryId, monasteryName);
                    });

                    async function triggerContextGreeting(id, name) {
                        // Ako je Kustos već jednom pozdravio korisnika na ovoj stranici, ne ponavljaj poziv
                        if (sessionStorage.getItem('kustos_greeted_' + id)) return;

                        try {
                            const response = await fetch("{{ route('kustos.context-greeting') }}", {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                                },
                                body: JSON.stringify({ 
                                    model_id: id,
                                    model_type: 'manastir'
                                })
                            });

                            const data = await response.json();
                            
                            if (data.success && data.greeting) {
                                // Beležimo u sesiju pretraživača da je pozdrav uspešno izvršen
                                sessionStorage.setItem('kustos_greeted_' + id, 'true');

                                // Pronalaženje kontejnera gde se ispisuju poruke u tvom vidžetu
                                const chatMessages = document.querySelector('.chat-messages') || document.getElementById('chatMessages');
                                
                                if (chatMessages) {
                                    const greetingDiv = document.createElement('div');
                                    greetingDiv.className = 'chat-message chat-message--ai'; 
                                    greetingDiv.innerHTML = `<p>${data.greeting}</p>`;
                                    chatMessages.appendChild(greetingDiv);
                                    
                                    // Automatski skrol na dno četa
                                    chatMessages.scrollTop = chatMessages.scrollHeight;
                                }
                            }
                        } catch (error) {
                            console.error('Greška pri učitavanju AI konteksta:', error);
                        }
                    }
                    </script>

                    <div class="monTocDesktop">
                        <div class="muted monTocDesktop__label">Sadržaj</div>
                        <ul class="monTocDesktop__list">
                            @foreach($sections as $s)
                                @if($s['show'])
                                    <li><a href="#{{ $s['id'] }}">{{ $s['label'] }}</a></li>
                                 @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </aside>
        </div>

    </div>
</section>

<style>
/* TVOJA POSTAVKA ZA DESNU SLIKU DA SE NE SEČE I DA BUDE PROPORCIONALNA */
.monSideBannerPhoto {
    width: 100%;
    border-radius: 18px;
    overflow: hidden;
    margin-bottom: 16px;
    border: 1px solid rgba(255, 255, 255, 0.06);
    box-shadow: 0 8px 24px rgba(0,0,0,0.3);
}
.monSideBannerPhoto img {
    width: 100%;
    height: auto;
    display: block;
    transition: transform 0.4s ease;
}
.monSideBannerPhoto:hover img {
    transform: scale(1.04);
}
.monKV__v.nm-gold-highlight {
    color: #e2c26a !important;
    font-weight: 700;
}

/* NOVI ELEGANTNI KNJIŠKI STIL ZA TEKSTUALNE BLOKOVE UMESTO "DOSADNIH KUTIJA" */
.text-book-layout {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    padding: 0 !important;
}

.monBookSection__main-title {
    font-size: 32px;
    color: #c5a24a;
    font-weight: 800;
    margin: 0 0 25px 0;
    letter-spacing: -0.02em;
}

.monBookBlock {
    margin-bottom: 15px;
}

/* Žuti/zlatni naslovi pasusa */
.monBookBlock__title {
    font-size: 24px;
    color: #c5a24a;
    font-weight: 700;
    margin: 0 0 14px 0;
    letter-spacing: -0.01em;
}

/* Mekana, čitljiva slova za pasuse */
.monBookBlock__text {
    font-size: 16.5px;
    line-height: 1.85;
    color: rgba(255, 255, 255, 0.88);
    text-align: justify;
    margin-bottom: 16px;
}

/* TA FILIGRANSKA LINIJA IZMEĐU PASUSA: tanko -> debelo -> tanko */
.monBookSeparator {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 35px 0;
    position: relative;
    width: 100%;
}

.monBookSeparator::before,
.monBookSeparator::after {
    content: "";
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(197, 162, 74, 0.3) 50%, rgba(197, 162, 74, 0.7) 100%);
}

.monBookSeparator::after {
    background: linear-gradient(90deg, rgba(197, 162, 74, 0.7), rgba(197, 162, 74, 0.3) 50%, transparent 100%);
}

/* Ukrasni cvet/ornament na sredini linije */
.monBookSeparator__ornament {
    color: #c5a24a;
    font-size: 18px;
    padding: 0 15px;
    line-height: 1;
    opacity: 0.8;
    text-shadow: 0 0 6px rgba(197, 162, 74, 0.2);
}

/* Izvori u istom stilu knjige */
.monBookSources {
    margin-top: 40px;
    padding-top: 20px;
    border-top: 1px dashed rgba(197, 162, 74, 0.2);
}

.monBookSources__title {
    font-size: 22px;
    color: #c5a24a;
    font-weight: 700;
    margin-bottom: 12px;
}

.monBookSources__list {
    padding-left: 20px;
    color: rgba(255, 255, 255, 0.8);
}

.monBookSources__list li {
    margin-bottom: 8px;
    line-height: 1.6;
}

.monBookSources__list a {
    color: #e2c26a;
    text-decoration: none;
}

.monBookSources__list a:hover {
    text-decoration: underline;
}
</style>

<script>
    window.MAP_SHOW = {
        point: {
            name: "{!! addslashes($monastery->name) !!}",
            slug: "{{ $monastery->slug }}",
            lat: {{ $monastery->lat }},
            lng: {{ $monastery->lng }},
            city: "{!! addslashes($monastery->city ?? '') !!}",
            region: "{!! addslashes($monastery->region ?? '') !!}"
        }
    };
</script>
<script src="{{ asset('js/map-show.js') }}"></script>
@endsection