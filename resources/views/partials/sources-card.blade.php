@php
    $cardTitle = $title ?? 'Izvori i literatura';
    $cardIcon = $icon ?? '📚';
    $sourceList = $sources ?? [
        'Zavod za zaštitu spomenika kulture (ZSK)',
        'Eparhija žička i ostale eparhije SPC (zvanični sajtovi)',
        'Slobodan Mileusnić: <em>Sveti Srbi</em> i <em>Sveti manastiri</em>, Beograd',
        'Predrag Puzović: <em>Kratka istorija SPC</em>, Beograd, 2001.',
        'Dr Radovan Bigović: <em>Crkva u istoriji</em>, Hrišćanski kulturni centar, Beograd'
    ];

    $footerNote = $note ?? 'Svi podaci su provereni u zvaničnim i stručnim izvorima SPC radi obezbeđivanja tačnosti i pouzdanosti informacija.';
@endphp

<div style="margin-top: 30px; margin-bottom: 25px; color: #c5a24a; font-style: italic; font-size: 0.85rem; line-height: 1.6;">
    <strong style="display: block; margin-bottom: 6px; opacity: 0.9;">{{ $cardTitle }}:</strong>
    <ul style="margin: 0 0 10px 0; padding-left: 20px;">
        @foreach($sourceList as $src)
            <li style="margin-bottom: 4px;">{!! $src !!}</li>
        @endforeach
    </ul>
    @if(!empty($footerNote))
        <div style="font-size: 0.8rem; opacity: 0.75; margin-top: 8px;">
            ℹ  {{ $footerNote }}
        </div>
    @endif
</div>
