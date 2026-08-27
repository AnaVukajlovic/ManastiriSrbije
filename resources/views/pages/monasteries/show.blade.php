@extends('layouts.site')

@section('title', ($monastery->name ?? 'Manastir') . ' — Pravoslavni Svetionik')
@section('nav_monasteries', 'active')
@php
    $slug = $monastery->slug ?? 'placeholder';
    $img = $monastery->image_src;
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

    // Galerija slika za desni panel i lightbox pregled
    $galleryImages = $monastery->images;
    $hasGallery = $galleryImages->isNotEmpty();

    $monasteryVideos = \App\Support\EducationalMedia::forMonastery($monastery->slug);

    $sections = [
        ['id' => 'opis', 'label' => 'Opis i istorija', 'show' => $hasDescription],
        ['id' => 'uvod', 'label' => 'Uvod', 'show' => !$hasDescription && !empty($p?->intro)],
        ['id' => 'istorija', 'label' => 'Istorija', 'show' => !$hasDescription && !empty($p?->history)],
        ['id' => 'arhitektura', 'label' => 'Arhitektura', 'show' => !$hasDescription && !empty($p?->architecture)],
        ['id' => 'ktitor', 'label' => 'Ktitor', 'show' => !$hasDescription && !empty($p?->ktitor_text)],
        ['id' => 'freske', 'label' => 'Umetnost i freske', 'show' => !$hasDescription && !empty($p?->art_frescoes)],
        ['id' => 'zanimljivosti', 'label' => 'Zanimljivosti', 'show' => !$hasDescription && !empty($p?->interesting_facts)],
        ['id' => 'video-dokumentarac', 'label' => 'Video dokumentarac', 'show' => !empty($monasteryVideos)],
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

            {{-- GLAVNI DEO SA TEKSTOM - ELEGANTNI KNJIŠKI STIL --}}
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

                        $knownHeadings = [
                            'OPŠTI PODACI',
                            'ISTORIJA',
                            'ISTORIJAT I NASTANAK',
                            'ISTORIJAT',
                            'ARHITEKTURA I UMETNOST',
                            'ARHITEKTURA I UNUTRAŠNJOST',
                            'ARHITEKTURA',
                            'DUHOVNI ŽIVOT I ZNAČAJ',
                            'DUHOVNI ŽIVOT I FRESKE',
                            'DUHOVNI ŽIVOT I MOŠTI',
                            'DUHOVNI ŽIVOT I ČUDOTVORENJA',
                            'DUHOVNI ŽIVOT I VINARSTVO',
                            'DUHOVNI ŽIVOT I MIROTOČIVE MOŠTI',
                            'DUHOVNI ŽIVOT',
                            'ZNAČAJ',
                            'PREDANJE',
                            'FRESKOPIS',
                            'ZANIMLJIVOSTI',
                            'UZ STRUČNI OPIS',
                        ];

                        usort($knownHeadings, fn($a, $b) => mb_strlen($b) - mb_strlen($a));
                        $pattern = '/(?:^|\n+)\s*(' . implode('|', array_map('preg_quote', $knownHeadings)) . ')\s*:\s*/iu';

                        $parts = preg_split($pattern, $desc, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

                        $structured = [];
                        if (count($parts) >= 2) {
                            for ($i = 0; $i < count($parts); $i += 2) {
                                $h = trim($parts[$i]);
                                $b = trim($parts[$i + 1] ?? '');
                                $structured[] = [
                                    'heading' => $h,
                                    'body' => $b,
                                ];
                            }
                        } else {
                            $structured[] = [
                                'heading' => 'Opis i istorijat',
                                'body' => $desc,
                            ];
                        }
                    @endphp

                    <article class="monBookSection" id="opis">
                        <h2 class="monBookSection__main-title">Predanje i istorija svetinje</h2>

                        @foreach($structured as $block)
                            <section class="monBookBlock">
                                <h3 class="monBookBlock__title">{{ $block['heading'] }}</h3>
                                @if(!empty($block['body']))
                                    @php
                                        $paragraphs = array_filter(array_map('trim', explode("\n", $block['body'])));
                                    @endphp
                                    @foreach($paragraphs as $para)
                                        <p class="monBookBlock__text">{{ $para }}</p>
                                    @endforeach
                                @endif
                            </section>
                            @if(!$loop->last)
                                <div class="monBookSeparator"><span class="monBookSeparator__ornament">❧</span></div>
                            @endif
                        @endforeach
                    </article>

                @elseif($p)
                    @php
                        $fallbackSections = [];
                        if (!empty($p->intro)) $fallbackSections[] = ['id' => 'uvod', 'title' => 'Uvod', 'text' => $p->intro];
                        if (!empty($p->history)) $fallbackSections[] = ['id' => 'istorija', 'title' => 'Istorija', 'text' => $p->history];
                        if (!empty($p->architecture)) $fallbackSections[] = ['id' => 'arhitektura', 'title' => 'Arhitektura', 'text' => $p->architecture];
                        if (!empty($p->ktitor_text)) $fallbackSections[] = ['id' => 'ktitor', 'title' => 'Ktitor', 'text' => $p->ktitor_text];
                        if (!empty($p->art_frescoes)) $fallbackSections[] = ['id' => 'freske', 'title' => 'Umetnost i freske', 'text' => $p->art_frescoes];
                        if (!empty($p->interesting_facts)) $fallbackSections[] = ['id' => 'zanimljivosti', 'title' => 'Zanimljivosti', 'text' => $p->interesting_facts];
                    @endphp

                    @if(!empty($fallbackSections))
                        @foreach($fallbackSections as $fs)
                            <article class="monBookSection" id="{{ $fs['id'] }}">
                                <h3 class="monBookBlock__title">{{ $fs['title'] }}</h3>
                                @php
                                    $paras = array_filter(array_map('trim', explode("\n", $fs['text'])));
                                @endphp
                                @foreach($paras as $para)
                                    <p class="monBookBlock__text">{{ $para }}</p>
                                @endforeach
                            </article>
                            @if(!$loop->last)
                                <div class="monBookSeparator"><span class="monBookSeparator__ornament">❧</span></div>
                            @endif
                        @endforeach
                    @else
                        <p class="muted">Detaljan tekst za ovu svetinju još nije dodat.</p>
                    @endif
                @else
                    <p class="muted">Detaljan tekst za ovu svetinju još nije dodat.</p>
                @endif

                @if(!empty($monasteryVideos) && count($monasteryVideos) > 0)
                    <div id="video-dokumentarac" style="margin-top: 35px;">
                        @include('partials.video-section', [
                            'videos' => $monasteryVideos,
                            'sectionTitle' => 'Video dokumentarac i istorijski osvrt (HistoryCast)'
                        ])
                    </div>
                @endif
            </div>

            {{-- DESNI ASIDE PANEL --}}
            <aside class="monSide">
                
                {{-- Centrirana i estetski uokvirena glavna fotografija sa mini-galerijom --}}
                <div class="monSideBannerCard">
                    <div class="monSideBannerPhoto" onclick="openMonLightbox(currentSideIndex)" title="Kliknite za pregled fotografije u punoj rezoluciji">
                        <img
                            id="sideMainBannerImg"
                            src="{{ $galleryImages->first()?->image_src ?? $img }}"
                            alt="Fotografija manastira {{ $monastery->name }}"
                            loading="lazy"
                            onerror="this.src='{{ $fallbackImg }}'"
                        >
                        <div class="monSideBannerPhoto__badge">
                            @if($hasGallery && $galleryImages->count() > 1)
                                📸 Galerija ({{ $galleryImages->count() }})
                            @else
                                🔍 Uvećaj fotografiju
                            @endif
                        </div>
                    </div>

                    {{-- Mini traka sa sličicama ukoliko ima više fotografija --}}
                    @if($hasGallery && $galleryImages->count() > 1)
                        <div class="monSideGalleryStrip">
                            @foreach($galleryImages as $thumbIdx => $thumb)
                                <button 
                                    type="button" 
                                    class="monSideThumbItem {{ $thumbIdx === 0 ? 'active' : '' }}" 
                                    onclick="selectMonSideThumb({{ $thumbIdx }}, '{{ $thumb->image_src }}')"
                                    title="{{ $thumb->caption ?? 'Slika ' . ($thumbIdx + 1) }}"
                                    aria-label="Prikaži sliku {{ $thumbIdx + 1 }}"
                                >
                                    <img 
                                        src="{{ $thumb->image_src }}" 
                                        alt="{{ $thumb->caption ?? 'Pregled' }}" 
                                        loading="lazy"
                                        onerror="this.src='{{ $fallbackImg }}'"
                                    >
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="card monSide__card">
                    <h3 class="monSide__title">Podaci o svetinji</h3>

                    <div class="monKV">
                        @if($ktitorName)
                            <div class="monKV__row">
                                <div class="monKV__k">Ktitor / Zadužbinar</div>
                                <div class="monKV__v nm-gold-highlight">{{ $ktitorName }}</div>
                            </div>
                        @endif

                        @if($godinaLabel)
                            <div class="monKV__row">
                                <div class="monKV__k">Vreme nastanka</div>
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
                                Navigacija do manastira
                            </a>
                        @endif

                    </div>

                    {{-- AI KUSTOS DUGME --}}
                    <div style="margin-top: 15px;">
                        <button type="button" class="btn2 btn2--gold btn2--wide" id="openKustosBtn">
                            Pitaj Digitalnog Letopisca
                        </button>
                    </div>

                    {{-- Pop-up modal --}}
                    <div class="kustos-modal" id="kustosModal">
                        <div class="kustos-modal__overlay" id="closeKustosOverlay"></div>
                        <div class="kustos-modal__content">
                            
                            {{-- Zaglavlje modala --}}
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

                    {{-- Skriveni parametri za kustosa --}}
                    <div id="kustos-context" 
                         data-id="{{ $monastery->id }}" 
                         data-name="{{ $monastery->name }}" 
                         style="display: none;">
                    </div>

                    <div class="monTocDesktop">
                        <div class="muted monTocDesktop__label">Sadržaj stranice</div>
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

{{-- MODAL LIGHTBOX ZA PREGLED I ZUMIRANJE SLIKA (LUPA) --}}
<div id="monLightbox" class="mon-lightbox" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="mon-lightbox__backdrop" onclick="closeMonLightbox()"></div>
    
    <div class="mon-lightbox__dialog">
        <div class="mon-lightbox__header">
            <div class="mon-lightbox__tools">
                <button type="button" class="mon-lightbox__tool-btn" onclick="zoomMonLightbox(0.3)" title="Uvećaj sliku (+)" aria-label="Uvećaj">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        <line x1="11" y1="8" x2="11" y2="14"></line>
                        <line x1="8" y1="11" x2="14" y2="11"></line>
                    </svg>
                    <span>Uvećaj</span>
                </button>
                <button type="button" class="mon-lightbox__tool-btn" onclick="zoomMonLightbox(-0.3)" title="Umanji sliku (-)" aria-label="Umanji">
                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                        <line x1="8" y1="11" x2="14" y2="11"></line>
                    </svg>
                    <span>Umanji</span>
                </button>
                <button type="button" class="mon-lightbox__tool-btn" onclick="resetMonLightboxZoom()" title="Resetuj zum (100%)" aria-label="Resetuj">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12a9 9 0 1 0 9-9 9.75 9.75 0 0 0-6.74 2.74L3 8"></path>
                        <path d="M3 3v5h5"></path>
                    </svg>
                    <span id="monZoomLevelText">100%</span>
                </button>
            </div>
            <button type="button" class="mon-lightbox__close" onclick="closeMonLightbox()" aria-label="Zatvori galeriju">&times;</button>
        </div>
        
        <button type="button" class="mon-lightbox__nav mon-lightbox__nav--prev" onclick="prevMonLightboxImage()" aria-label="Prethodna fotografija">&#10094;</button>
        <button type="button" class="mon-lightbox__nav mon-lightbox__nav--next" onclick="nextMonLightboxImage()" aria-label="Sledeća fotografija">&#10095;</button>

        <div class="mon-lightbox__stage" id="monLightboxStage">
            <img id="monLightboxImg" src="" alt="" draggable="false" onerror="this.src='{{ $fallbackImg }}'">
            <div class="mon-lightbox__hint" id="monLightboxHint">🔍 Dupli klik za uvećanje / skrol mišem za zum</div>
        </div>

        <div class="mon-lightbox__footer">
            <div class="mon-lightbox__footer-header">
                <span class="mon-lightbox__footer-badge">
                    <span>🖼️</span> Опис фотографије
                </span>
                <span id="monLightboxCounter" class="mon-lightbox__counter"></span>
            </div>
            <div id="monLightboxCaption" class="mon-lightbox__caption"></div>
        </div>
    </div>
</div>

<style>
/* CENTRIRANJE I ESTETIKA GLAVNE SLIKE I THUMBNAIL STRIP */
.monSideBannerCard {
    max-width: 440px;
    margin: 0 auto 16px auto;
}

.monSideBannerPhoto {
    position: relative;
    width: 100%;
    aspect-ratio: 16 / 11;
    border-radius: 20px;
    overflow: hidden;
    border: 1px solid rgba(197, 162, 74, 0.3);
    box-shadow: 0 14px 38px rgba(0, 0, 0, 0.5);
    cursor: pointer;
    background: #140d0d;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
}
.monSideBannerPhoto img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
    display: block;
    transition: transform 0.5s ease;
}
.monSideBannerPhoto:hover {
    border-color: rgba(197, 162, 74, 0.6);
    box-shadow: 0 18px 45px rgba(197, 162, 74, 0.18);
}
.monSideBannerPhoto:hover img {
    transform: scale(1.05);
}
.monSideBannerPhoto__badge {
    position: absolute;
    bottom: 12px;
    right: 12px;
    background: rgba(15, 10, 10, 0.92);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(197, 162, 74, 0.45);
    color: #e2c26a;
    font-size: 0.82rem;
    font-weight: 700;
    padding: 6px 14px;
    border-radius: 999px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.6);
}

.monSideGalleryStrip {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 8px;
    margin-top: 10px;
}
.monSideThumbItem {
    position: relative;
    aspect-ratio: 4 / 3;
    border-radius: 10px;
    overflow: hidden;
    border: 2px solid transparent;
    padding: 0;
    background: #140d0d;
    cursor: pointer;
    transition: all 0.2s ease;
}
.monSideThumbItem img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
.monSideThumbItem:hover {
    transform: translateY(-2px);
    border-color: rgba(197, 162, 74, 0.6);
}
.monSideThumbItem.active {
    border-color: #c5a24a;
    box-shadow: 0 0 10px rgba(197, 162, 74, 0.45);
}

/* LIGHTBOX MODAL SA ZUMOM I LUPOM - POZICIONIRANO ISPOD NAVBARA */
.mon-lightbox {
    position: fixed !important;
    inset: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 2000000 !important; /* Znatno iznad fiksnog navbara */
    display: flex !important;
    align-items: flex-start !important; /* Poravnanje na vrh */
    justify-content: center !important;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
    padding: 100px 20px 40px !important; /* 100px gornji padding da prozor bude ispod navbara */
    box-sizing: border-box !important;
    overflow-y: auto !important; /* Skrolovanje ako dijalog premaši ekran */
}
.mon-lightbox.active {
    opacity: 1 !important;
    pointer-events: auto !important;
}
.mon-lightbox__backdrop {
    position: absolute !important;
    inset: 0 !important;
    background: rgba(4, 3, 3, 0.96) !important;
    backdrop-filter: blur(16px) !important;
    -webkit-backdrop-filter: blur(16px) !important;
}
.mon-lightbox__dialog {
    position: relative !important;
    z-index: 2 !important;
    width: min(1180px, 92vw) !important;
    max-height: calc(100vh - 140px) !important; /* Prilagođena visina da stane u preostali prostor */
    display: flex !important;
    flex-direction: column !important;
    align-items: center !important;
    background: linear-gradient(180deg, rgba(26, 17, 16, 0.98), rgba(16, 10, 10, 0.98)) !important;
    border: 1.5px solid rgba(197, 162, 74, 0.45) !important;
    border-radius: 24px !important;
    box-shadow: 0 30px 80px rgba(0,0,0,0.9), 0 0 50px rgba(197, 162, 74, 0.2) !important;
    padding: 18px 24px 20px 24px !important;
    box-sizing: border-box !important;
    margin: 0 auto !important; /* Nema auto gornje/donje margine */
}

.mon-lightbox__header {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding-bottom: 12px;
    margin-bottom: 10px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.08);
}
.mon-lightbox__tools {
    display: flex;
    align-items: center;
    gap: 8px;
}
.mon-lightbox__tool-btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(35, 24, 22, 0.85);
    border: 1px solid rgba(197, 162, 74, 0.35);
    color: #e2c26a;
    font-size: 13px;
    font-weight: 600;
    padding: 6px 12px;
    border-radius: 10px;
    cursor: pointer;
    transition: all 0.2s ease;
}
.mon-lightbox__tool-btn:hover {
    background: rgba(197, 162, 74, 0.2);
    border-color: #c5a24a;
    color: #fff;
    transform: translateY(-1px);
}
.mon-lightbox__tool-btn:active {
    transform: scale(0.96);
}

.mon-lightbox__close {
    background: rgba(0,0,0,0.5);
    border: 1px solid rgba(255,255,255,0.15);
    color: #fff;
    font-size: 26px;
    line-height: 1;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: 0.2s;
}
.mon-lightbox__close:hover {
    color: var(--gold);
    border-color: var(--gold);
    transform: scale(1.1);
}

.mon-lightbox__stage {
    position: relative;
    width: 100%;
    height: 58vh;
    max-height: 580px;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
    border-radius: 14px;
    background: #080404;
    cursor: zoom-in;
    user-select: none;
    touch-action: none;
}
.mon-lightbox__stage img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
    border-radius: 8px;
    box-shadow: 0 12px 35px rgba(0,0,0,0.6);
    transform-origin: center center;
    transition: transform 0.15s ease-out;
    will-change: transform;
}
.mon-lightbox__hint {
    position: absolute;
    top: 10px;
    left: 50%;
    transform: translateX(-50%);
    background: rgba(10, 6, 6, 0.75);
    border: 1px solid rgba(197, 162, 74, 0.25);
    color: rgba(255, 255, 255, 0.75);
    font-size: 12px;
    padding: 4px 12px;
    border-radius: 20px;
    pointer-events: none;
    opacity: 0.85;
    transition: opacity 0.3s;
}

.mon-lightbox__nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(18, 12, 12, 0.8);
    border: 1px solid rgba(197, 162, 74, 0.35);
    color: #e2c26a;
    font-size: 24px;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    z-index: 5;
}
.mon-lightbox__nav:hover {
    background: var(--gold);
    color: #120b0b;
    transform: translateY(-50%) scale(1.12);
}
.mon-lightbox__nav--prev { left: 24px; }
.mon-lightbox__nav--next { right: 24px; }

.mon-lightbox__footer {
    width: 100%;
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 14px;
    padding: 12px 18px;
    background: rgba(14, 9, 8, 0.88);
    border: 1px solid rgba(197, 162, 74, 0.3);
    border-radius: 16px;
    box-sizing: border-box;
}
.mon-lightbox__footer-header {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    padding-bottom: 6px;
    border-bottom: 1px solid rgba(197, 162, 74, 0.18);
}
.mon-lightbox__footer-badge {
    color: var(--gold, #c5a24a);
    font-size: 0.82rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.6px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}
.mon-lightbox__caption {
    font-size: 1.05rem;
    font-weight: 500;
    color: #f5f1ea;
    text-align: left;
    width: 100%;
    max-width: 100%;
    box-sizing: border-box;
}
.mon-lightbox__caption-desc {
    color: #ffffff;
    font-size: 1.04rem;
    font-weight: 600;
    line-height: 1.55;
    text-align: justify;
    text-justify: inter-word;
    margin: 0 0 6px 0;
    width: 100%;
}
.mon-lightbox__caption-source {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    color: #facc15 !important; /* Žuta slova */
    font-style: italic !important; /* Kurziv */
    font-size: 0.88rem !important;
    font-weight: 600;
    line-height: 1.35;
    padding: 3px 10px;
    background: rgba(250, 204, 21, 0.12);
    border: 1px solid rgba(250, 204, 21, 0.30);
    border-radius: 8px;
    margin-top: 4px;
    width: fit-content;
}
.mon-lightbox__counter {
    font-size: 0.86rem;
    color: #fce7aa;
    font-weight: 800;
    padding: 3px 12px;
    background: rgba(197, 162, 74, 0.18);
    border: 1px solid rgba(197, 162, 74, 0.35);
    border-radius: 999px;
    white-space: nowrap;
}

@media (max-width: 768px) {
    .mon-lightbox__dialog {
        padding: 14px;
        width: 96vw;
    }
    .mon-lightbox__nav {
        width: 38px;
        height: 38px;
        font-size: 18px;
    }
    .mon-lightbox__nav--prev { left: 8px; }
    .mon-lightbox__nav--next { right: 8px; }
}

/* KUSTOS MODAL I POSTOJEĆI STILOVI - CENTRIRANO NA SREDINI EKRANA */
.kustos-modal {
    position: fixed !important;
    inset: 0 !important;
    width: 100vw !important;
    height: 100vh !important;
    z-index: 1000000 !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
    padding: 20px !important;
    box-sizing: border-box !important;
}
.kustos-modal--open {
    opacity: 1 !important;
    pointer-events: auto !important;
}
.kustos-modal__overlay {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
    background: rgba(8, 5, 5, 0.88);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
}
.kustos-modal__content {
    position: relative;
    width: min(540px, 94vw);
    height: min(680px, 82vh);
    max-height: 85vh;
    background: linear-gradient(180deg, #201714, #140d0c);
    border: 1.5px solid rgba(197, 162, 74, 0.45);
    border-radius: 24px;
    box-shadow: 0 30px 80px rgba(0,0,0,0.85), 0 0 40px rgba(197,162,74,0.18);
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transform: translateY(20px) scale(0.96);
    transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
.kustos-modal--open .kustos-modal__content {
    transform: translateY(0) scale(1);
}
.kustos-modal__header {
    padding: 16px 22px;
    background: #281d18;
    border-bottom: 1px solid rgba(197, 162, 74, 0.22);
    border-radius: 24px 24px 0 0;
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-shrink: 0;
}
.kustos-modal__title-box { display: flex; align-items: center; gap: 12px; }
.kustos-modal__icon { font-size: 1.5em; }
.kustos-modal__title { margin: 0; font-size: 1.15em; color: #c5a059; font-weight: 700; }
.kustos-modal__close {
    background: none; border: none; color: #a39288; font-size: 32px;
    cursor: pointer; padding: 0; line-height: 1; transition: 0.2s;
}
.kustos-modal__close:hover { color: #c5a059; transform: scale(1.1); }
.kustos-modal__body {
    flex: 1; padding: 16px; overflow-y: auto;
    display: flex; flex-direction: column;
    min-height: 0;
}
.kustos-modal__body > div { flex: 1; display: flex; flex-direction: column; height: 100%; min-height: 0; }

.btn2--gold {
    background: #c5a059 !important; color: #1a1512 !important;
    font-weight: 700; font-size: 1rem; padding: 10px 20px;
    border-radius: 8px; transition: all 0.2s ease;
    box-shadow: 0 4px 15px rgba(197, 160, 89, 0.25);
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}
.btn2--gold:hover { background: #e0b86f !important; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(197, 160, 89, 0.35); }

.monKV__v.nm-gold-highlight {
    color: #e2c26a !important;
    font-weight: 700;
}

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

.monBookBlock__title {
    font-size: 24px;
    color: #c5a24a;
    font-weight: 700;
    margin: 0 0 14px 0;
    letter-spacing: -0.01em;
}

.monBookBlock__text {
    font-size: 16.5px;
    line-height: 1.85;
    color: rgba(255, 255, 255, 0.88);
    text-align: justify;
    margin-bottom: 16px;
}

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

.monBookSeparator__ornament {
    color: #c5a24a;
    font-size: 18px;
    padding: 0 15px;
    line-height: 1;
    opacity: 0.8;
    text-shadow: 0 0 6px rgba(197, 162, 74, 0.2);
}

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
    // PODACI ZA GALERIJU I LIGHTBOX
    @php
        $jsGalleryList = $hasGallery 
            ? $galleryImages->map(fn($item) => ['url' => $item->image_src, 'caption' => $item->caption ?? ($monastery->name ?? 'Manastir')])
            : collect([['url' => $img, 'caption' => 'Fotografija manastira ' . ($monastery->name ?? '')]]);
    @endphp

    const monGalleryList = @json($jsGalleryList);
    let curLightboxIdx = 0;
    let currentSideIndex = 0;
    let monZoom = 1.0;
    let monPanX = 0;
    let monPanY = 0;
    let monIsDragging = false;
    let monStartX = 0;
    let monStartY = 0;

    function formatLightboxCaption(caption) {
        if (!caption) return '';
        
        let desc = caption;
        let source = '';

        // Extract HTML formatted source tag
        const htmlMatch = caption.match(/^(.*?)(?:<br\s*\/?>)?\s*<small[^>]*>(?:<em>)?(?:\*|\()?([^*()<>\n]+(?:\.rs|[a-zA-Z0-9\s\.\-_]+))(?:\*|\))?(?:<\/em>)?<\/small>$/i);
        if (htmlMatch) {
            desc = htmlMatch[1].trim();
            source = htmlMatch[2].trim();
        } else {
            // Extract plaintext / markdown source
            const textMatch = caption.match(/^(.*?)(?:\s*[-–—]|\s*<br\s*\/?>|\s*\n)?\s*(?:\*|\()?\s*(Izvor:\s*[^)*\n]+)(?:\*|\))?$/i);
            if (textMatch) {
                desc = textMatch[1].trim();
                source = textMatch[2].trim();
            }
        }

        // Clean any leftover brackets, asterisks or trailing punctuation
        source = source.replace(/^[(*\s]+/, '').replace(/[)*\s]+$/, '').trim();
        if (source && !source.toLowerCase().startsWith('izvor:') && !source.toLowerCase().startsWith('извор:')) {
            source = 'Izvor: ' + source;
        }

        const mode = (typeof window.getSiteScript === 'function') 
            ? window.getSiteScript() 
            : (localStorage.getItem('site_script') || 'lat');

        if (typeof window.convertSiteText === 'function') {
            desc = window.convertSiteText(desc, mode);
            source = window.convertSiteText(source, mode);
        } else if (mode === 'cyr' && typeof window.latToCyr === 'function') {
            desc = window.latToCyr(desc);
            source = window.latToCyr(source);
        }

        if (desc.length > 0 && source.length > 0) {
            return `<div class="mon-lightbox__caption-desc">${desc}</div><div class="mon-lightbox__caption-source" style="color: #eab308; font-style: italic; margin-top: 4px;">*${source}*</div>`;
        } else if (source.length > 0) {
            return `<div class="mon-lightbox__caption-source" style="color: #eab308; font-style: italic;">*${source}*</div>`;
        }
        return `<div class="mon-lightbox__caption-desc">${desc}</div>`;
    }

    function openMonLightbox(index) {
        if (!monGalleryList || monGalleryList.length === 0) return;
        curLightboxIdx = (typeof index === 'number' && index >= 0 && index < monGalleryList.length) ? index : 0;
        resetMonLightboxZoom();
        updateMonLightboxUI();
        const lb = document.getElementById('monLightbox');
        if (lb) {
            lb.classList.add('active');
            lb.setAttribute('aria-hidden', 'false');
            document.body.style.overflow = 'hidden';
            if (typeof window.applySiteScriptToNode === 'function') {
                window.applySiteScriptToNode(lb);
            }
        }
    }

    function closeMonLightbox() {
        const lb = document.getElementById('monLightbox');
        if (lb) {
            lb.classList.remove('active');
            lb.setAttribute('aria-hidden', 'true');
            document.body.style.overflow = '';
            resetMonLightboxZoom();
        }
    }

    function updateMonZoomTransform(smooth = true) {
        const imgEl = document.getElementById('monLightboxImg');
        const stage = document.getElementById('monLightboxStage');
        const zoomText = document.getElementById('monZoomLevelText');
        if (!imgEl) return;

        imgEl.style.transition = smooth ? 'transform 0.15s ease-out' : 'none';
        imgEl.style.transform = `translate(${monPanX}px, ${monPanY}px) scale(${monZoom})`;
        if (zoomText) zoomText.textContent = `${Math.round(monZoom * 100)}%`;

        if (stage) {
            if (monZoom > 1.05) {
                stage.style.cursor = monIsDragging ? 'grabbing' : 'grab';
            } else {
                stage.style.cursor = 'zoom-in';
            }
        }
    }

    function zoomMonLightbox(delta) {
        let newZoom = Math.min(Math.max(monZoom + delta, 0.75), 4.0);
        newZoom = Math.round(newZoom * 100) / 100;
        if (newZoom === monZoom) return;
        monZoom = newZoom;
        if (monZoom <= 1.05) {
            monPanX = 0;
            monPanY = 0;
        }
        updateMonZoomTransform(true);
    }

    function resetMonLightboxZoom() {
        monZoom = 1.0;
        monPanX = 0;
        monPanY = 0;
        updateMonZoomTransform(true);
    }

    function toggleMonLightboxZoom() {
        if (monZoom > 1.1) {
            resetMonLightboxZoom();
        } else {
            monZoom = 2.2;
            monPanX = 0;
            monPanY = 0;
            updateMonZoomTransform(true);
        }
    }

    function updateMonLightboxUI() {
        const item = monGalleryList[curLightboxIdx];
        if (!item) return;
        
        const imgEl = document.getElementById('monLightboxImg');
        const capEl = document.getElementById('monLightboxCaption');
        const cntEl = document.getElementById('monLightboxCounter');

        const mode = (typeof window.getSiteScript === 'function') 
            ? window.getSiteScript() 
            : (localStorage.getItem('site_script') || 'lat');

        if (imgEl) {
            imgEl.src = item.url;
            imgEl.alt = (typeof window.convertSiteText === 'function') 
                ? window.convertSiteText(item.caption || '', mode) 
                : (item.caption || '');
        }
        if (capEl) {
            capEl.innerHTML = formatLightboxCaption(item.caption || '');
        }
        if (cntEl) {
            cntEl.textContent = `${curLightboxIdx + 1} / ${monGalleryList.length}`;
        }

        // Apply script to any other elements inside lightbox
        const lb = document.getElementById('monLightbox');
        if (lb && typeof window.applySiteScriptToNode === 'function') {
            window.applySiteScriptToNode(lb, mode);
        }

        // Sakrij navigaciju ako ima samo 1 slika
        const prevBtn = document.querySelector('.mon-lightbox__nav--prev');
        const nextBtn = document.querySelector('.mon-lightbox__nav--next');
        if (monGalleryList.length <= 1) {
            if (prevBtn) prevBtn.style.display = 'none';
            if (nextBtn) nextBtn.style.display = 'none';
        } else {
            if (prevBtn) prevBtn.style.display = 'flex';
            if (nextBtn) nextBtn.style.display = 'flex';
        }
    }

    function nextMonLightboxImage() {
        if (!monGalleryList || monGalleryList.length <= 1) return;
        curLightboxIdx = (curLightboxIdx + 1) % monGalleryList.length;
        resetMonLightboxZoom();
        updateMonLightboxUI();
    }

    function prevMonLightboxImage() {
        if (!monGalleryList || monGalleryList.length <= 1) return;
        curLightboxIdx = (curLightboxIdx - 1 + monGalleryList.length) % monGalleryList.length;
        resetMonLightboxZoom();
        updateMonLightboxUI();
    }

    function selectMonSideThumb(idx, url) {
        currentSideIndex = idx;
        const mainImg = document.getElementById('sideMainBannerImg');
        if (mainImg) mainImg.src = url;

        document.querySelectorAll('.monSideThumbItem').forEach((btn, i) => {
            if (i === idx) btn.classList.add('active');
            else btn.classList.remove('active');
        });
    }

    window.addEventListener('sitescriptchange', function () {
        updateMonLightboxUI();
        const sideBanner = document.querySelector('.monSideBannerCard');
        if (sideBanner && typeof window.applySiteScriptToNode === 'function') {
            window.applySiteScriptToNode(sideBanner);
        }
    });

    // Lightbox interakcije za zum i prevlačenje (drag/pan)
    document.addEventListener('DOMContentLoaded', () => {
        const stage = document.getElementById('monLightboxStage');
        if (!stage) return;

        stage.addEventListener('wheel', (e) => {
            e.preventDefault();
            const delta = e.deltaY < 0 ? 0.25 : -0.25;
            zoomMonLightbox(delta);
        }, { passive: false });

        stage.addEventListener('dblclick', toggleMonLightboxZoom);

        stage.addEventListener('mousedown', (e) => {
            if (monZoom <= 1.05) return;
            monIsDragging = true;
            monStartX = e.clientX - monPanX;
            monStartY = e.clientY - monPanY;
            stage.style.cursor = 'grabbing';
        });

        window.addEventListener('mousemove', (e) => {
            if (!monIsDragging) return;
            monPanX = e.clientX - monStartX;
            monPanY = e.clientY - monStartY;
            updateMonZoomTransform(false);
        });

        window.addEventListener('mouseup', () => {
            if (monIsDragging) {
                monIsDragging = false;
                updateMonZoomTransform(true);
            }
        });

        // Touch podrška za mobilne uređaje
        stage.addEventListener('touchstart', (e) => {
            if (e.touches.length === 1 && monZoom > 1.05) {
                monIsDragging = true;
                monStartX = e.touches[0].clientX - monPanX;
                monStartY = e.touches[0].clientY - monPanY;
            }
        }, { passive: true });

        stage.addEventListener('touchmove', (e) => {
            if (monIsDragging && e.touches.length === 1) {
                monPanX = e.touches[0].clientX - monStartX;
                monPanY = e.touches[0].clientY - monStartY;
                updateMonZoomTransform(false);
            }
        }, { passive: true });

        stage.addEventListener('touchend', () => {
            if (monIsDragging) {
                monIsDragging = false;
                updateMonZoomTransform(true);
            }
        });
    });

    // Keyboard navigacija za galeriju i zum (+, -, 0, strelice, ESC)
    document.addEventListener('keydown', (e) => {
        const lb = document.getElementById('monLightbox');
        if (!lb || !lb.classList.contains('active')) return;

        if (e.key === 'Escape') closeMonLightbox();
        else if (e.key === 'ArrowRight') nextMonLightboxImage();
        else if (e.key === 'ArrowLeft') prevMonLightboxImage();
        else if (e.key === '+' || e.key === '=') zoomMonLightbox(0.25);
        else if (e.key === '-' || e.key === '_') zoomMonLightbox(-0.25);
        else if (e.key === '0') resetMonLightboxZoom();
    });

    // KUSTOS INICIJALIZACIJA
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('kustosModal');
        const openBtn = document.getElementById('openKustosBtn');
        const closeBtn = document.getElementById('closeKustosBtn');
        const closeOverlay = document.getElementById('closeKustosOverlay');
        
        if (!modal || !openBtn) return;

        openBtn.addEventListener('click', () => {
            modal.classList.add('kustos-modal--open');
            const inputField = document.getElementById('chatInput');
            if (inputField) setTimeout(() => inputField.focus(), 300);
        });

        const closeModal = () => modal.classList.remove('kustos-modal--open');
        closeBtn?.addEventListener('click', closeModal);
        closeOverlay?.addEventListener('click', closeModal);
    });

    // MAPA
    window.MAP_SHOW = {
        point: {
            name: "{!! addslashes($monastery->name) !!}",
            slug: "{{ $monastery->slug }}",
            lat: {{ $monastery->lat ?? 'null' }},
            lng: {{ $monastery->lng ?? 'null' }},
            city: "{!! addslashes($monastery->city ?? '') !!}",
            region: "{!! addslashes($monastery->region ?? '') !!}"
        }
    };
</script>
<script src="{{ asset('js/map-show.js') }}"></script>
@endsection