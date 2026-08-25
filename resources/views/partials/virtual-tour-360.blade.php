@if(!empty($tourData))
@php
    $uniqueTourId = 'walk_' . preg_replace('/[^a-zA-Z0-9_]/', '_', $tourData['monastery_slug'] ?? 'tour_' . uniqid());
    $steps = $tourData['steps'] ?? [];
    $firstStep = reset($steps) ?: [];
    
    $initialLat = $firstStep['lat'] ?? $tourData['lat'] ?? 43.4865;
    $initialLng = $firstStep['lng'] ?? $tourData['lng'] ?? 20.5316;
    $initialHeading = $firstStep['heading'] ?? 0;
    $initialPitch = $firstStep['pitch'] ?? 5;
    
    $blagoUrl = $tourData['blago_url'] ?? null;
    $artsCultureUrl = $tourData['arts_culture_url'] ?? null;

    $initialEmbedUrl = $firstStep['embed_url'] ?? \App\Support\VirtualTour360::getStreetViewEmbedUrl($initialLat, $initialLng, $initialHeading, $initialPitch);
    $satelliteEmbedUrl = $tourData['satellite_url'] ?? \App\Support\VirtualTour360::getSatelliteEmbedUrl($initialLat, $initialLng);
    $directStreetViewUrl = $firstStep['direct_url'] ?? \App\Support\VirtualTour360::getDirectStreetViewUrl($initialLat, $initialLng, $initialHeading, $initialPitch);
@endphp

<div class="walk-tour-card" id="walk_card_{{ $uniqueTourId }}" style="margin: 35px 0 25px 0; background: linear-gradient(180deg, rgba(28, 18, 16, 0.98), rgba(16, 10, 10, 0.98)); border: 1.5px solid rgba(197, 162, 74, 0.35); border-radius: 26px; padding: 26px; box-shadow: 0 20px 50px rgba(0,0,0,0.6);">
    
    {{-- ZAGLAVLJE SA MODALIMA / TABOVIMA --}}
    <div class="walk-header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 14px; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid rgba(197, 162, 74, 0.25);">
        <div>
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 4px;">
                <span style="font-size: 1.6rem;">🏛️</span>
                <h3 style="margin: 0; font-size: 1.35rem; color: var(--gold, #c5a24a); font-weight: 700;">{{ $tourData['title'] ?? '360° Virtuelna Šetnja kroz Manastir' }}</h3>
            </div>
            @if(!empty($tourData['subtitle']))
                <p style="margin: 0; font-size: 0.92rem; color: rgba(255, 255, 255, 0.75);">{{ $tourData['subtitle'] }}</p>
            @endif
        </div>

        {{-- TABOVI ZA PRIKAZ --}}
        <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
            <button 
                type="button" 
                class="walk-tab-btn active" 
                onclick="switchWalkTourMode('{{ $uniqueTourId }}', 'walkthrough')"
                id="tab_walk_{{ $uniqueTourId }}"
                title="Prikaz interaktivne 360° šetnje kroz kompleks"
                style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.84rem; font-weight: 700; color: #fff; background: rgba(197, 162, 74, 0.25); border: 1px solid #c5a24a; padding: 7px 14px; border-radius: 999px; cursor: pointer; transition: all 0.2s ease;">
                <span>🚶</span> Šetnja & Dvorište
            </button>

            @if($artsCultureUrl)
                <a href="{{ $artsCultureUrl }}" target="_blank" rel="noopener" title="Otvori 3D priču na Google Arts & Culture" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.84rem; font-weight: 700; color: #e2c26a; background: rgba(197, 162, 74, 0.12); border: 1px solid rgba(197, 162, 74, 0.35); padding: 7px 14px; border-radius: 999px; text-decoration: none; transition: all 0.2s ease;">
                    <span>🎨</span> Google Arts 3D ↗
                </a>
            @endif

            @if($blagoUrl)
                <a href="{{ $blagoUrl }}" target="_blank" rel="noopener" title="Otvori digitalni arhiv freskopisa Fonda BLAGO" style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.84rem; font-weight: 700; color: #e2c26a; background: rgba(197, 162, 74, 0.12); border: 1px solid rgba(197, 162, 74, 0.35); padding: 7px 14px; border-radius: 999px; text-decoration: none; transition: all 0.2s ease;">
                    <span>✨</span> BLAGO 3D Riznica ↗
                </a>
            @endif

            <button 
                type="button" 
                class="walk-tab-btn" 
                onclick="switchWalkTourMode('{{ $uniqueTourId }}', 'satellite')"
                id="tab_sat_{{ $uniqueTourId }}"
                title="Prikaz 3D satelitskog snimka manastira i krovova"
                style="display: inline-flex; align-items: center; gap: 6px; font-size: 0.84rem; font-weight: 700; color: rgba(255, 255, 255, 0.8); background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.15); padding: 7px 14px; border-radius: 999px; cursor: pointer; transition: all 0.2s ease;">
                <span>🗺️</span> 3D Teren & Satelit
            </button>
        </div>
    </div>

    {{-- INTERAKTIVNA STAZA KORAČANJA (STEPPER / DUGMIĆI) --}}
    @if(count($steps) > 0)
        <div class="walk-stepper-container" style="margin-bottom: 16px;">
            <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 8px;">
                <span style="font-size: 0.86rem; font-weight: 700; color: var(--gold, #c5a24a); text-transform: uppercase; letter-spacing: 0.5px;">
                    🚩 Staza koračanja kroz manastirski kompleks:
                </span>
                <span style="font-size: 0.82rem; color: rgba(255, 255, 255, 0.65);" id="step_indicator_{{ $uniqueTourId }}">
                    Korak 1 od {{ count($steps) }}
                </span>
            </div>

            <div class="walk-steps-scroll" style="display: flex; gap: 8px; overflow-x: auto; padding-bottom: 6px; scrollbar-width: thin;">
                @foreach($steps as $sIdx => $step)
                    @php
                        $stepEmbed = $step['embed_url'] ?? \App\Support\VirtualTour360::getStreetViewEmbedUrl($step['lat'] ?? $initialLat, $step['lng'] ?? $initialLng, $step['heading'] ?? 0, $step['pitch'] ?? 5);
                        $stepDirect = $step['direct_url'] ?? \App\Support\VirtualTour360::getDirectStreetViewUrl($step['lat'] ?? $initialLat, $step['lng'] ?? $initialLng, $step['heading'] ?? 0, $step['pitch'] ?? 5);
                    @endphp
                    <button 
                        type="button" 
                        class="walk-step-pill {{ $sIdx === 0 ? 'active' : '' }}" 
                        data-step-index="{{ $sIdx }}"
                        data-step-title="{{ $step['title'] }}"
                        data-step-desc="{{ $step['desc'] ?? '' }}"
                        data-step-embed="{{ $stepEmbed }}"
                        data-step-direct="{{ $stepDirect }}"
                        onclick="goToWalkStep('{{ $uniqueTourId }}', {{ $sIdx }})"
                        style="white-space: nowrap; display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; border-radius: 12px; font-size: 0.85rem; font-weight: 600; cursor: pointer; transition: all 0.2s ease; border: 1px solid rgba(197, 162, 74, 0.3); background: rgba(24, 15, 14, 0.9); color: rgba(255, 255, 255, 0.85);">
                        {{ $step['title'] }}
                    </button>
                @endforeach
            </div>
        </div>
    @endif

    {{-- GLAVNA POZORNICA / EKRAN ZA ŠETNJU --}}
    <div id="walk_stage_{{ $uniqueTourId }}" class="walk-stage-wrapper" style="position: relative; width: 100%; aspect-ratio: 16 / 9; min-height: 460px; max-height: 640px; border-radius: 22px; overflow: hidden; border: 1.5px solid rgba(197, 162, 74, 0.4); box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7); background: #060404;">
        
        {{-- IFRAME ZA STREET VIEW / ŠETNJU --}}
        <iframe
            id="walk_iframe_{{ $uniqueTourId }}"
            src="{{ $initialEmbedUrl }}"
            width="100%"
            height="100%"
            style="border: 0; width: 100%; height: 100%; display: block;"
            allowfullscreen=""
            loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"
            title="360 Šetnja — {{ $tourData['title'] }}"
        ></iframe>

        {{-- PLUTAJUĆE KONTROLE ZA KORAČANJE PREKO EKRANA --}}
        <div class="walk-floating-controls" style="position: absolute; bottom: 14px; left: 14px; right: 14px; z-index: 10; display: flex; align-items: center; justify-content: space-between; pointer-events: none;">
            
            {{-- KORAČAJ PRETHODNI / SLEDEĆI --}}
            <div style="display: flex; gap: 8px; pointer-events: auto;">
                <button 
                    type="button" 
                    class="walk-nav-btn"
                    onclick="walkStepDelta('{{ $uniqueTourId }}', -1)"
                    title="Prethodni korak na stazi kroz manastir"
                    style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 16px; border-radius: 12px; background: rgba(18, 11, 10, 0.88); border: 1px solid rgba(197, 162, 74, 0.45); color: #e2c26a; cursor: pointer; font-size: 0.86rem; font-weight: 700; backdrop-filter: blur(8px); transition: all 0.2s ease;">
                    ⬅️ Koračaj nazad
                </button>

                <button 
                    type="button" 
                    class="walk-nav-btn"
                    onclick="walkStepDelta('{{ $uniqueTourId }}', 1)"
                    title="Sledeći korak na stazi kroz manastir"
                    style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 18px; border-radius: 12px; background: linear-gradient(135deg, #c5a24a, #e2c26a); border: 1px solid #fff; color: #160e0a; cursor: pointer; font-size: 0.86rem; font-weight: 800; box-shadow: 0 4px 15px rgba(197, 162, 74, 0.4); transition: all 0.2s ease;">
                    Koračaj napred ➡️
                </button>
            </div>

            {{-- FULLSCREEN & GOOGLE MAPS --}}
            <div style="display: flex; gap: 8px; pointer-events: auto;">
                <a 
                    id="walk_direct_link_{{ $uniqueTourId }}"
                    href="{{ $directStreetViewUrl }}" 
                    target="_blank" 
                    rel="noopener" 
                    class="walk-nav-btn"
                    title="Otvori u Google Street View aplikaciji za telefon ili VR"
                    style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 14px; border-radius: 12px; background: rgba(18, 11, 10, 0.88); border: 1px solid rgba(197, 162, 74, 0.45); color: #e2c26a; text-decoration: none; font-size: 0.84rem; font-weight: 700; backdrop-filter: blur(8px); transition: all 0.2s ease;">
                    <span>📱</span> VR & Mape ↗
                </a>

                <button 
                    type="button" 
                    id="fs_btn_{{ $uniqueTourId }}"
                    class="walk-nav-btn"
                    onclick="toggleWalkFullscreen('walk_stage_{{ $uniqueTourId }}', 'fs_btn_{{ $uniqueTourId }}')"
                    title="Prikaz preko celog ekrana (Fullscreen)"
                    style="display: inline-flex; align-items: center; gap: 6px; padding: 9px 14px; border-radius: 12px; background: rgba(18, 11, 10, 0.88); border: 1px solid rgba(197, 162, 74, 0.45); color: #e2c26a; cursor: pointer; font-size: 0.84rem; font-weight: 700; backdrop-filter: blur(8px); transition: all 0.2s ease;">
                    <span>⛶</span> <span class="fs-label">Ceo ekran</span>
                </button>
            </div>
        </div>

        {{-- OPIS TRENUTNOG KORAKA NA VRHU EKRANA --}}
        <div id="walk_overlay_info_{{ $uniqueTourId }}" style="position: absolute; top: 12px; left: 14px; right: 14px; z-index: 10; pointer-events: none; display: flex; justify-content: flex-start;">
            <div style="max-width: 650px; background: rgba(12, 8, 8, 0.88); backdrop-filter: blur(8px); border: 1px solid rgba(197, 162, 74, 0.4); border-radius: 16px; padding: 8px 16px; color: #fff; box-shadow: 0 10px 25px rgba(0,0,0,0.5);">
                <div id="walk_info_title_{{ $uniqueTourId }}" style="font-size: 0.88rem; font-weight: 700; color: #e2c26a; margin-bottom: 2px;">
                    📍 {{ $firstStep['title'] ?? 'Manastirski kompleks' }}
                </div>
                <div id="walk_info_desc_{{ $uniqueTourId }}" style="font-size: 0.8rem; color: rgba(255, 255, 255, 0.85); line-height: 1.4;">
                    {{ $firstStep['desc'] ?? 'Koristite miša ili prst za 360° rotaciju i istraživanje svetinje.' }}
                </div>
            </div>
        </div>

    </div>

</div>

<style>
.walk-tab-btn:hover {
    background: rgba(197, 162, 74, 0.3) !important;
    border-color: #c5a24a !important;
    color: #fff !important;
}
.walk-tab-btn.active {
    background: rgba(197, 162, 74, 0.35) !important;
    border-color: #e2c26a !important;
    color: #fff !important;
}
.walk-step-pill:hover, .walk-step-pill.active {
    background: rgba(197, 162, 74, 0.28) !important;
    border-color: #e2c26a !important;
    color: #fff !important;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(197, 162, 74, 0.25);
}
.walk-nav-btn:hover {
    transform: scale(1.05);
}
@media (max-width: 640px) {
    .walk-floating-controls {
        flex-direction: column;
        gap: 8px;
        align-items: stretch;
    }
    .walk-floating-controls > div {
        justify-content: center;
    }
    .walk-nav-btn {
        padding: 7px 11px !important;
        font-size: 0.78rem !important;
    }
}
</style>

<script>
window.walkTourStates = window.walkTourStates || {};
window.walkTourStates["{{ $uniqueTourId }}"] = {
    currentIndex: 0,
    steps: @json($steps),
    satelliteUrl: "{{ $satelliteEmbedUrl }}",
    currentMode: 'walkthrough'
};

function getStreetViewEmbedUrl(lat, lng, heading, pitch) {
    heading = Math.round(heading || 0);
    pitch = Math.round(pitch !== undefined ? pitch : 5);
    return 'https://maps.google.com/maps?layer=c&cbll=' + lat + ',' + lng + '&cbp=11,' + heading + ',0,0,' + pitch + '&output=svembed';
}

function getDirectStreetViewUrl(lat, lng, heading, pitch) {
    heading = Math.round(heading || 0);
    pitch = Math.round(pitch !== undefined ? pitch : 5);
    return 'https://www.google.com/maps/@?api=1&map_action=pano&viewpoint=' + lat + ',' + lng + '&heading=' + heading + '&pitch=' + pitch;
}

function goToWalkStep(tourId, index) {
    const state = window.walkTourStates[tourId];
    if (!state || !state.steps || !state.steps[index]) return;

    state.currentIndex = index;
    const step = state.steps[index];
    const card = document.getElementById('walk_card_' + tourId);
    if (!card) return;

    // Proračunaj URLs
    let embedUrl = step.embed_url || getStreetViewEmbedUrl(step.lat, step.lng, step.heading, step.pitch);
    let directUrl = step.direct_url || getDirectStreetViewUrl(step.lat, step.lng, step.heading, step.pitch);

    const iframe = document.getElementById('walk_iframe_' + tourId);
    const pills = card.querySelectorAll('.walk-step-pill');

    pills.forEach((p, idx) => {
        if (idx === index) {
            p.classList.add('active');
            p.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
        } else {
            p.classList.remove('active');
        }
    });

    if (iframe && embedUrl) {
        iframe.src = embedUrl;
    }

    // Ažuriraj opis i naslov
    const titleEl = document.getElementById('walk_info_title_' + tourId);
    const descEl = document.getElementById('walk_info_desc_' + tourId);
    const indicatorEl = document.getElementById('step_indicator_' + tourId);
    const directLinkEl = document.getElementById('walk_direct_link_' + tourId);

    if (titleEl) titleEl.innerText = '📍 ' + (step.title || 'Manastirska lokacija');
    if (descEl) descEl.innerText = step.desc || 'Koristite miša ili prst za 360° rotaciju.';
    if (indicatorEl) indicatorEl.innerText = 'Korak ' + (index + 1) + ' od ' + state.steps.length;
    if (directLinkEl && directUrl) directLinkEl.href = directUrl;

    // Osiguraj da je tab u walkthrough modu
    const tabWalk = document.getElementById('tab_walk_' + tourId);
    const tabSat = document.getElementById('tab_sat_' + tourId);
    if (tabWalk) tabWalk.classList.add('active');
    if (tabSat) tabSat.classList.remove('active');
    state.currentMode = 'walkthrough';
}

function walkStepDelta(tourId, delta) {
    const state = window.walkTourStates[tourId];
    if (!state || !state.steps || state.steps.length === 0) return;

    let nextIdx = state.currentIndex + delta;
    if (nextIdx >= state.steps.length) {
        nextIdx = 0; // Ciklično na početak
    } else if (nextIdx < 0) {
        nextIdx = state.steps.length - 1; // Ciklično na kraj
    }

    goToWalkStep(tourId, nextIdx);
}

function switchWalkTourMode(tourId, mode) {
    const state = window.walkTourStates[tourId];
    const iframe = document.getElementById('walk_iframe_' + tourId);
    const tabWalk = document.getElementById('tab_walk_' + tourId);
    const tabSat = document.getElementById('tab_sat_' + tourId);

    if (!state || !iframe) return;

    if (mode === 'satellite') {
        state.currentMode = 'satellite';
        iframe.src = state.satelliteUrl;
        if (tabSat) tabSat.classList.add('active');
        if (tabWalk) tabWalk.classList.remove('active');
    } else {
        state.currentMode = 'walkthrough';
        goToWalkStep(tourId, state.currentIndex);
        if (tabWalk) tabWalk.classList.add('active');
        if (tabSat) tabSat.classList.remove('active');
    }
}

function toggleWalkFullscreen(stageWrapperId, btnId) {
    const el = document.getElementById(stageWrapperId);
    if (!el) return;

    if (!document.fullscreenElement && !document.webkitFullscreenElement) {
        if (el.requestFullscreen) {
            el.requestFullscreen();
        } else if (el.webkitRequestFullscreen) {
            el.webkitRequestFullscreen();
        } else if (el.msRequestFullscreen) {
            el.msRequestFullscreen();
        }
    } else {
        if (document.exitFullscreen) {
            document.exitFullscreen();
        } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
        }
    }
}

document.addEventListener('fullscreenchange', handleFullscreenChange);
document.addEventListener('webkitfullscreenchange', handleFullscreenChange);

function handleFullscreenChange() {
    const isFs = !!(document.fullscreenElement || document.webkitFullscreenElement);
    document.querySelectorAll('.walk-stage-wrapper').forEach(stage => {
        const tourId = stage.id.replace('walk_stage_', '');
        const btn = document.getElementById('fs_btn_' + tourId);
        if (btn) {
            const label = btn.querySelector('.fs-label');
            if (label) {
                label.innerText = isFs ? 'Zatvori' : 'Ceo ekran';
            }
        }
    });
}
</script>
@endif
