@if(!empty($videos) && count($videos) > 0)
@php
    $videoCount = count($videos);
@endphp

<div class="edu-video-section" style="margin: 40px 0 30px 0; width: 100%;">
    <div class="edu-video-section__header" style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 22px; border-bottom: 1.5px solid rgba(197, 162, 74, 0.25); padding-bottom: 14px;">
        <div style="display: flex; align-items: center; gap: 12px;">
            <span style="font-size: 1.45rem; line-height: 1;">🎬</span>
            <h3 style="margin: 0; font-size: 1.28rem; color: var(--gold, #c5a24a); font-weight: 700; letter-spacing: 0.2px;">
                {{ $sectionTitle ?? 'Video sadržaji i istorijski osvrt (HistoryCast & RTS)' }}
            </h3>
        </div>
        <span style="font-size: 0.82rem; font-weight: 700; color: #e2c26a; background: rgba(197, 162, 74, 0.12); padding: 5px 14px; border-radius: 999px; border: 1px solid rgba(197, 162, 74, 0.3);">
            {{ $videoCount }} {{ $videoCount === 1 ? 'emisija' : ($videoCount < 5 ? 'emisije' : 'emisija') }}
        </span>
    </div>

    <div class="edu-video-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 22px; width: 100%;">
        @foreach($videos as $vid)
            <div class="edu-video-card" style="background: linear-gradient(180deg, rgba(28, 20, 18, 0.96), rgba(16, 12, 12, 0.98)); border: 1.2px solid rgba(197, 162, 74, 0.28); border-radius: 16px; overflow: hidden; box-shadow: 0 10px 26px rgba(0, 0, 0, 0.45); display: flex; flex-direction: column; width: 100%; transition: transform 0.25s ease, border-color 0.25s ease, box-shadow 0.25s ease;">
                
                {{-- KOMPAKTNI 16:9 VIDEO EMBED --}}
                <div class="edu-video-embed" style="position: relative; width: 100%; aspect-ratio: 16 / 9; background: #080606; overflow: hidden; border-bottom: 1px solid rgba(197, 162, 74, 0.18);">
                    <iframe 
                        src="{{ $vid['embed_url'] }}" 
                        title="{{ $vid['title'] }}" 
                        frameborder="0" 
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                        allowfullscreen
                        loading="lazy"
                        style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: none;">
                    </iframe>
                </div>

                {{-- PRATEĆI TEKST I PODACI O EMISIJI --}}
                <div class="edu-video-info" style="padding: 14px 15px 15px 15px; display: flex; flex-direction: column; flex: 1;">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-bottom: 8px;">
                        <span style="display: inline-flex; align-items: center; gap: 4px; font-size: 0.74rem; font-weight: 700; color: #e2c26a; background: rgba(197, 162, 74, 0.12); border: 1px solid rgba(197, 162, 74, 0.25); padding: 3px 8px; border-radius: 999px;">
                            {{ $vid['badge'] ?? '🎬 Istorijski video' }}
                        </span>
                        <span style="font-size: 0.76rem; font-weight: 600; color: rgba(255, 255, 255, 0.65);">
                            {{ $vid['author'] }}
                        </span>
                    </div>

                    <h4 style="margin: 0 0 6px 0; font-size: 0.94rem; line-height: 1.35; color: #fff; font-weight: 700;">
                        {{ $vid['title'] }}
                    </h4>

                    @if(!empty($vid['description']))
                        <p style="margin: 0 0 10px 0; font-size: 0.82rem; line-height: 1.55; color: rgba(255, 255, 255, 0.75); text-align: justify; text-justify: inter-word;">
                            {{ $vid['description'] }}
                        </p>
                    @endif

                    <div style="margin-top: auto; padding-top: 8px; border-top: 1px solid rgba(255, 255, 255, 0.06); display: flex; justify-content: flex-end;">
                        <a href="{{ $vid['url'] }}" target="_blank" rel="noopener noreferrer" style="display: inline-flex; align-items: center; gap: 4px; font-size: 0.78rem; font-weight: 600; color: var(--gold, #c5a24a); text-decoration: none; transition: color 0.2s ease;">
                            Gledaj na YouTube ↗
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
.edu-video-card:hover {
    transform: translateY(-3px);
    border-color: rgba(197, 162, 74, 0.6) !important;
    box-shadow: 0 14px 32px rgba(197, 162, 74, 0.22) !important;
}
@media (max-width: 640px) {
    .edu-video-grid {
        grid-template-columns: 1fr !important;
    }
}
</style>
@endif
