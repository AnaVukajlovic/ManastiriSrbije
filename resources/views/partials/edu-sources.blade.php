@if(!empty($sources) && count($sources) > 0)
<div class="edu-sources-section" style="margin-top: 45px; width: 100%;">
    <div style="background: linear-gradient(180deg, rgba(28, 18, 17, 0.96), rgba(16, 11, 10, 0.98)); border: 1.5px solid rgba(197, 162, 74, 0.35); border-radius: 22px; padding: 26px 30px; box-shadow: 0 14px 36px rgba(0, 0, 0, 0.45);">
        
        <div style="display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px; margin-bottom: 20px; border-bottom: 1px solid rgba(197, 162, 74, 0.25); padding-bottom: 14px;">
            <div style="display: flex; align-items: center; gap: 12px;">
                <span style="font-size: 1.45rem; line-height: 1;">📚</span>
                <h3 style="margin: 0; font-size: 1.22rem; color: var(--gold, #c5a24a); font-weight: 800; letter-spacing: 0.3px;">
                    {{ $title ?? 'Извори и стручна литература' }}
                </h3>
            </div>
            <span style="font-size: 0.8rem; font-weight: 700; color: #e2c26a; background: rgba(197, 162, 74, 0.12); padding: 4px 12px; border-radius: 999px; border: 1px solid rgba(197, 162, 74, 0.28);">
                Историјска и стручна грађа
            </span>
        </div>

        <ul style="margin: 0; padding: 0; list-style: none; display: flex; flex-direction: column; gap: 12px;">
            @foreach($sources as $index => $source)
                <li style="display: flex; align-items: flex-start; gap: 12px; font-size: 0.93rem; line-height: 1.65; color: rgba(255, 255, 255, 0.88); background: rgba(255, 255, 255, 0.025); padding: 12px 16px; border-radius: 14px; border: 1px solid rgba(255, 255, 255, 0.05);">
                    <span style="flex-shrink: 0; width: 24px; height: 24px; border-radius: 50%; background: rgba(197, 162, 74, 0.15); border: 1px solid rgba(197, 162, 74, 0.35); color: #e2c26a; font-weight: 800; font-size: 0.78rem; display: inline-flex; align-items: center; justify-content: center; margin-top: 2px;">
                        {{ $index + 1 }}
                    </span>
                    <div style="flex: 1;">
                        @if(is_array($source))
                            @if(!empty($source['author']))
                                <strong style="color: #fff;">{{ $source['author'] }}</strong>: 
                            @endif
                            @if(!empty($source['work']))
                                <em style="color: #e2c26a;">{{ $source['work'] }}</em>
                            @endif
                            @if(!empty($source['details']))
                                <span style="color: rgba(255, 255, 255, 0.75);"> — {{ $source['details'] }}</span>
                            @endif
                            @if(!empty($source['url']))
                                <a href="{{ $source['url'] }}" target="_blank" rel="noopener noreferrer" style="color: var(--gold, #c5a24a); margin-left: 8px; text-decoration: underline; font-weight: 600;">
                                    [Линк ↗]
                                </a>
                            @endif
                        @else
                            <span>{!! $source !!}</span>
                        @endif
                    </div>
                </li>
            @endforeach
        </ul>

        @if(!empty($note))
            <div style="margin-top: 18px; padding-top: 14px; border-top: 1px solid rgba(255, 255, 255, 0.06); font-size: 0.84rem; color: rgba(255, 255, 255, 0.65); line-height: 1.6; font-style: italic;">
                💡 {{ $note }}
            </div>
        @endif

    </div>
</div>
@endif
