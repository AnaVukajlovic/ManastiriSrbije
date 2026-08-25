@php
    $galleryItems = \App\Support\KtitorsGallery::all();
@endphp

<section class="kt-mini-gallery-section" style="margin-bottom: 45px; width: 100%;">
    {{-- ZAGLAVLJE MINI GALERIJE --}}
    <div class="kt-gal-header">
        <div class="kt-gal-header__text">
            <div class="kt-gal-badge">🎨 Портрети и фрескопис</div>
            <h3 class="kt-gal-title-main">Галерија ктиторских портрета и фресака</h3>
            <p class="kt-gal-subtitle">
                Историјски портрети српских владара, ктитора и владарки сачувани на зидовима српских средњовековних манастира (XII–XV век). 
                Свака слика садржи верификован извор и локацију оригиналне фреске.
            </p>
        </div>

        {{-- FILTER TABOVI --}}
        <div class="kt-gal-filters">
            <button type="button" class="kt-gal-tab active" data-filter="all">Све фреске ({{ count($galleryItems) }})</button>
            <button type="button" class="kt-gal-tab" data-filter="nemanjici">Немањићи</button>
            <button type="button" class="kt-gal-tab" data-filter="vladarke">Владарке и ктиторке</button>
            <button type="button" class="kt-gal-tab" data-filter="lazarevici">Лазаревићи</button>
        </div>
    </div>

    {{-- RASTER KARTICA FRESAKA --}}
    <div class="kt-gal-grid" id="ktGalGrid">
        @foreach($galleryItems as $slug => $item)
            <article class="kt-gal-card" data-category="{{ $item['category'] }}">
                {{-- FOTO BLOK SA ZOOM LIGHTBOX-OM --}}
                <div class="kt-gal-media" onclick="openKtGalLightbox('{{ asset($item['image_url']) }}', '{{ addslashes($item['name']) }}', '{{ addslashes($item['fresco_location']) }}', '{{ addslashes($item['source_title']) }}', '{{ addslashes($item['source_url']) }}', '{{ addslashes($item['description']) }}')" title="Кликните за преглед и зумирање фреске">
                    <img 
                        src="{{ asset($item['image_url']) }}" 
                        alt="Фреска: {{ $item['name'] }}" 
                        loading="lazy"
                        onerror="this.onerror=null;this.src='{{ asset('images/sample/studenica.jpg') }}';"
                    />
                    <div class="kt-gal-media__overlay"></div>
                    <div class="kt-gal-loc-badge">
                        <span>🏛️</span> {{ $item['fresco_location'] }}
                    </div>
                    <div class="kt-gal-zoom-btn">🔍 Увећај</div>
                </div>

                {{-- PODACI I OPIS --}}
                <div class="kt-gal-body">
                    <div class="kt-gal-cat-tag">{{ $item['category_label'] }}</div>
                    
                    <h4 class="kt-gal-name">
                        <a href="{{ route('ktitors.show', $item['slug']) }}">{{ $item['name'] }}</a>
                    </h4>

                    <div class="kt-gal-meta">
                        <div class="kt-gal-role">{{ $item['title'] }}</div>
                        <div class="kt-gal-years-tag">{{ $item['years'] }}</div>
                    </div>

                    <p class="kt-gal-desc">
                        {{ $item['description'] }}
                    </p>

                    {{-- IZVOR I AKCIJA --}}
                    <div class="kt-gal-footer">
                        <div class="kt-gal-source-box">
                            <span class="kt-gal-src-label">Извор фреске:</span>
                            <a href="{{ $item['source_url'] }}" target="_blank" rel="noopener noreferrer" class="kt-gal-src-link" title="Отвори изворне податке на Wikimedia Commons">
                                📜 {{ $item['source_title'] }} ↗
                            </a>
                        </div>

                        <a href="{{ route('ktitors.show', $item['slug']) }}" class="kt-gal-bio-btn">
                            Профил ктитора →
                        </a>
                    </div>
                </div>
            </article>
        @endforeach
    </div>
</section>

{{-- MODAL LIGHTBOX ZA PREGLED FRESKE --}}
<div id="ktGalLightbox" class="kt-gal-lightbox" role="dialog" aria-modal="true" aria-hidden="true">
    <div class="kt-gal-lightbox__backdrop" onclick="closeKtGalLightbox()"></div>
    
    <div class="kt-gal-lightbox__dialog">
        <div class="kt-gal-lightbox__header">
            <div class="kt-gal-lightbox__meta-box">
                <h3 id="ktGalModalTitle" class="kt-gal-lightbox__title">Назив фреске</h3>
                <div id="ktGalModalLoc" class="kt-gal-lightbox__loc">Локација</div>
            </div>
            <button type="button" class="kt-gal-lightbox__close" onclick="closeKtGalLightbox()" aria-label="Затвори">&times;</button>
        </div>

        <div class="kt-gal-lightbox__stage">
            <img id="ktGalModalImg" src="" alt="Фреска ктитора" draggable="false" />
        </div>

        <div class="kt-gal-lightbox__footer">
            <div id="ktGalModalDesc" class="kt-gal-lightbox__desc"></div>
            <div class="kt-gal-lightbox__footer-bottom">
                <div id="ktGalModalSource" class="kt-gal-lightbox__source"></div>
                <button type="button" class="kt-gal-lightbox__done-btn" onclick="closeKtGalLightbox()">Затвори преглед</button>
            </div>
        </div>
    </div>
</div>

<style>
/* MINI GALERIJA GLAVNI STILOVI */
.kt-mini-gallery-section {
    background: linear-gradient(180deg, rgba(28, 18, 17, 0.98), rgba(16, 10, 10, 0.98));
    border: 1.5px solid rgba(197, 162, 74, 0.35);
    border-radius: 28px;
    padding: 34px 36px 38px;
    box-shadow: 0 18px 45px rgba(0, 0, 0, 0.55);
}

.kt-gal-header {
    display: flex;
    flex-direction: column;
    gap: 18px;
    margin-bottom: 30px;
    border-bottom: 1px solid rgba(197, 162, 74, 0.22);
    padding-bottom: 22px;
}

.kt-gal-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 6px 16px;
    border-radius: 999px;
    background: rgba(197, 162, 74, 0.12);
    border: 1px solid rgba(197, 162, 74, 0.35);
    color: #e2c26a;
    font-size: 0.82rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    width: max-content;
    margin-bottom: 8px;
}

.kt-gal-title-main {
    margin: 0 0 10px 0;
    font-size: clamp(1.6rem, 2.6vw, 2.2rem);
    color: var(--gold, #c5a24a);
    font-weight: 800;
    letter-spacing: -0.01em;
}

.kt-gal-subtitle {
    margin: 0;
    font-size: 0.96rem;
    line-height: 1.8;
    color: rgba(255, 255, 255, 0.86);
    text-align: justify;
    text-justify: inter-word;
    width: 100%;
}

/* FILTER TABOVI */
.kt-gal-filters {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 6px;
}

.kt-gal-tab {
    padding: 8px 18px;
    border-radius: 999px;
    border: 1px solid rgba(197, 162, 74, 0.3);
    background: rgba(255, 255, 255, 0.03);
    color: rgba(255, 255, 255, 0.88);
    font-size: 0.86rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.kt-gal-tab:hover {
    background: rgba(197, 162, 74, 0.15);
    border-color: #c5a24a;
    color: #fff;
}

.kt-gal-tab.active {
    background: linear-gradient(135deg, #e2c26a, #c5a24a);
    color: #1a100b;
    border-color: #e2c26a;
    font-weight: 700;
    box-shadow: 0 4px 14px rgba(197, 162, 74, 0.3);
}

/* GRID KARTICA */
.kt-gal-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 24px;
    width: 100%;
}

.kt-gal-card {
    background: linear-gradient(180deg, rgba(22, 14, 13, 0.95), rgba(14, 9, 8, 0.98));
    border: 1.5px solid rgba(197, 162, 74, 0.28);
    border-radius: 22px;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: 0 12px 30px rgba(0, 0, 0, 0.45);
    transition: transform 0.28s ease, border-color 0.28s ease, box-shadow 0.28s ease;
}

.kt-gal-card:hover {
    transform: translateY(-5px);
    border-color: rgba(197, 162, 74, 0.6);
    box-shadow: 0 18px 45px rgba(197, 162, 74, 0.2);
}

/* FOTO MEDIJ */
.kt-gal-media {
    position: relative;
    width: 100%;
    aspect-ratio: 1 / 1.16;
    background: #0c0808;
    overflow: hidden;
    cursor: zoom-in;
}

.kt-gal-media img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center top;
    display: block;
    transition: transform 0.35s ease;
}

.kt-gal-card:hover .kt-gal-media img {
    transform: scale(1.05);
}

.kt-gal-media__overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.1) 40%, rgba(0,0,0,0.85) 100%);
    pointer-events: none;
}

.kt-gal-loc-badge {
    position: absolute;
    bottom: 12px;
    left: 12px;
    right: 12px;
    font-size: 0.76rem;
    font-weight: 700;
    color: #f7eedb;
    background: rgba(16, 10, 9, 0.88);
    backdrop-filter: blur(6px);
    border: 1px solid rgba(197, 162, 74, 0.35);
    padding: 4px 10px;
    border-radius: 8px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    display: flex;
    align-items: center;
    gap: 5px;
}

.kt-gal-zoom-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 0.72rem;
    font-weight: 700;
    color: #e2c26a;
    background: rgba(12, 8, 8, 0.9);
    border: 1px solid rgba(197, 162, 74, 0.35);
    padding: 3px 8px;
    border-radius: 999px;
    backdrop-filter: blur(4px);
    pointer-events: none;
}

/* TELO KARTICE */
.kt-gal-body {
    padding: 16px 18px 18px 18px;
    display: flex;
    flex-direction: column;
    flex: 1;
}

.kt-gal-cat-tag {
    font-size: 0.72rem;
    font-weight: 700;
    color: #e2c26a;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 4px;
}

.kt-gal-name {
    margin: 0 0 6px 0;
    font-size: 1.12rem;
    font-weight: 800;
    line-height: 1.3;
}

.kt-gal-name a {
    color: #fff;
    text-decoration: none;
    transition: color 0.2s ease;
}

.kt-gal-name a:hover {
    color: #e2c26a;
}

.kt-gal-meta {
    display: flex;
    flex-direction: column;
    gap: 2px;
    margin-bottom: 10px;
}

.kt-gal-role {
    font-size: 0.82rem;
    color: rgba(255, 255, 255, 0.7);
    line-height: 1.35;
}

.kt-gal-years-tag {
    font-size: 0.78rem;
    font-weight: 700;
    color: #c5a24a;
}

.kt-gal-desc {
    margin: 0 0 14px 0;
    font-size: 0.85rem;
    line-height: 1.65;
    color: rgba(255, 255, 255, 0.82);
    text-align: justify;
    text-justify: inter-word;
}

.kt-gal-footer {
    margin-top: auto;
    padding-top: 12px;
    border-top: 1px solid rgba(255, 255, 255, 0.08);
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.kt-gal-source-box {
    display: flex;
    flex-direction: column;
    gap: 3px;
}

.kt-gal-src-label {
    font-size: 0.7rem;
    color: rgba(255, 255, 255, 0.5);
    text-transform: uppercase;
    font-weight: 600;
}

.kt-gal-src-link {
    font-size: 0.76rem;
    color: #e2c26a;
    text-decoration: none;
    line-height: 1.4;
    word-break: break-word;
    transition: color 0.2s ease;
}

.kt-gal-src-link:hover {
    color: #fff;
    text-decoration: underline;
}

.kt-gal-bio-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 8px 14px;
    border-radius: 12px;
    background: rgba(197, 162, 74, 0.12);
    border: 1px solid rgba(197, 162, 74, 0.35);
    color: #fff;
    font-size: 0.82rem;
    font-weight: 700;
    text-decoration: none;
    transition: all 0.2s ease;
}

.kt-gal-bio-btn:hover {
    background: linear-gradient(135deg, #e2c26a, #c5a24a);
    color: #1a100b;
    border-color: #e2c26a;
}

/* LIGHTBOX MODAL */
.kt-gal-lightbox {
    position: fixed;
    inset: 0;
    z-index: 9999999;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.3s ease;
}

.kt-gal-lightbox.active {
    opacity: 1;
    pointer-events: auto;
}

.kt-gal-lightbox__backdrop {
    position: absolute;
    inset: 0;
    background: rgba(6, 4, 4, 0.95);
    backdrop-filter: blur(12px);
}

.kt-gal-lightbox__dialog {
    position: relative;
    z-index: 2;
    width: min(840px, 94vw);
    max-height: 90vh;
    background: linear-gradient(180deg, #201412, #120b0a);
    border: 1.5px solid rgba(197, 162, 74, 0.45);
    border-radius: 24px;
    box-shadow: 0 24px 60px rgba(0, 0, 0, 0.8);
    display: flex;
    flex-direction: column;
    overflow: hidden;
}

.kt-gal-lightbox__header {
    padding: 16px 20px;
    background: rgba(14, 8, 8, 0.9);
    border-bottom: 1px solid rgba(197, 162, 74, 0.25);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.kt-gal-lightbox__title {
    margin: 0;
    font-size: 1.25rem;
    color: #e2c26a;
    font-weight: 800;
}

.kt-gal-lightbox__loc {
    font-size: 0.82rem;
    color: rgba(255, 255, 255, 0.75);
    margin-top: 3px;
}

.kt-gal-lightbox__close {
    background: rgba(255, 255, 255, 0.08);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #fff;
    font-size: 24px;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s ease;
    line-height: 1;
}

.kt-gal-lightbox__close:hover {
    background: #c5a24a;
    color: #1a100b;
}

.kt-gal-lightbox__stage {
    padding: 16px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #080404;
    overflow: auto;
    max-height: 60vh;
}

.kt-gal-lightbox__stage img {
    max-width: 100%;
    max-height: 56vh;
    object-fit: contain;
    border-radius: 12px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.6);
}

.kt-gal-lightbox__footer {
    padding: 16px 22px;
    background: rgba(14, 8, 8, 0.95);
    border-top: 1px solid rgba(197, 162, 74, 0.25);
    display: flex;
    flex-direction: column;
    gap: 10px;
    width: 100%;
    box-sizing: border-box;
}

.kt-gal-lightbox__desc {
    width: 100%;
    font-size: 0.98rem;
    line-height: 1.6;
    color: #ffffff;
    text-align: justify;
    text-justify: inter-word;
    margin-bottom: 4px;
    padding-bottom: 8px;
    border-bottom: 1px solid rgba(197, 162, 74, 0.15);
}

.kt-gal-lightbox__footer-bottom {
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    flex-wrap: wrap;
}

.kt-gal-lightbox__source {
    font-size: 0.84rem;
    color: #facc15;
    font-style: italic;
}

.kt-gal-lightbox__source a {
    color: #facc15;
    text-decoration: underline;
    font-weight: 600;
}

.kt-gal-lightbox__done-btn {
    padding: 6px 18px;
    border-radius: 999px;
    border: 1px solid rgba(197, 162, 74, 0.35);
    background: rgba(197, 162, 74, 0.18);
    color: #fce7aa;
    font-size: 0.82rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s ease;
}

.kt-gal-lightbox__done-btn:hover {
    background: linear-gradient(135deg, #e2c26a, #c5a24a);
    color: #1a100b;
}

@media (max-width: 768px) {
    .kt-mini-gallery-section { padding: 22px 18px 26px; border-radius: 20px; }
    .kt-gal-grid { grid-template-columns: 1fr; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Filteri
    const tabs = document.querySelectorAll('.kt-gal-tab');
    const cards = document.querySelectorAll('.kt-gal-card');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            
            const filter = tab.dataset.filter;
            cards.forEach(card => {
                if (filter === 'all' || card.dataset.category === filter) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    });
});

function openKtGalLightbox(imgUrl, title, location, srcTitle, srcUrl, desc) {
    const modal = document.getElementById('ktGalLightbox');
    document.getElementById('ktGalModalImg').src = imgUrl;
    document.getElementById('ktGalModalTitle').textContent = (typeof window.convertSiteText === 'function') ? window.convertSiteText(title) : title;
    document.getElementById('ktGalModalLoc').textContent = (typeof window.convertSiteText === 'function') ? window.convertSiteText(location) : location;
    
    const descEl = document.getElementById('ktGalModalDesc');
    if (descEl) {
        descEl.textContent = (typeof window.convertSiteText === 'function') ? window.convertSiteText(desc || '') : (desc || '');
        descEl.style.display = desc ? 'block' : 'none';
    }

    const prefix = (typeof window.convertSiteText === 'function') ? window.convertSiteText('Verifikovan izvor:') : 'Verifikovan izvor:';
    const sTitle = (typeof window.convertSiteText === 'function') ? window.convertSiteText(srcTitle) : srcTitle;
    document.getElementById('ktGalModalSource').innerHTML = `${prefix} <a href="${srcUrl}" target="_blank" rel="noopener noreferrer">${sTitle} ↗</a>`;
    
    if (typeof window.applySiteScriptToNode === 'function') {
        window.applySiteScriptToNode(modal);
    }
    
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeKtGalLightbox() {
    const modal = document.getElementById('ktGalLightbox');
    modal.classList.remove('active');
    document.body.style.overflow = '';
}

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeKtGalLightbox();
    }
});
</script>
