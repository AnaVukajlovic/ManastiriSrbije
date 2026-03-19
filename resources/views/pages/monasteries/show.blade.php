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

    $regionLabel = (!empty($monastery->region) && $monastery->region !== 'Nepoznato') ? $monastery->region : null;
    $cityLabel = (!empty($monastery->city) && $monastery->city !== 'Nepoznato') ? $monastery->city : null;
@endphp

@section('content')
<section class="section monPro">
    <div class="container">

        <a class="btn2 monPro__back" href="{{ route('monasteries.index') }}">← Nazad na listu</a>

        <div class="monHeaderCard">
            <h1 class="monHeaderCard__title">{{ $monastery->name ?? 'Manastir' }}</h1>

            <div class="monHeaderCard__meta">
                @if($regionLabel)
                    <span class="tag">{{ $regionLabel }}</span>
                @endif

                @if($cityLabel)
                    <span class="tag">{{ $cityLabel }}</span>
                @endif

                @if(!empty($eparchyName))
                    <span class="tag">{{ $eparchyName }}</span>
                @endif
            </div>

            <div class="monHeaderCard__actions">
                @if(!empty($monastery->lat) && !empty($monastery->lng))
                    <a
                        class="btn2"
                        target="_blank"
                        rel="noopener"
                        href="https://www.google.com/maps?q={{ $monastery->lat }},{{ $monastery->lng }}"
                    >
                        Otvori na mapi
                    </a>
                @endif

                <a class="btn2 btn2--ghost" href="#sadrzaj">Sadržaj</a>
            </div>
        </div>

        <div class="monGrid" id="sadrzaj">

            <div class="monMain card">

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

                    <article class="monSec" id="opis">
                        <h2>Opis manastira</h2>

                        <div class="monIntroSplit">
                            <div class="monIntroSplit__text">
                                @if($firstBlock)
                                    <section class="monTextBlock monTextBlock--intro">
                                        <h3>{{ $firstBlock['heading'] }}</h3>
                                        @if($firstBlock['body'] !== '')
                                            <p>{{ $firstBlock['body'] }}</p>
                                        @endif
                                    </section>
                                @endif
                            </div>

                            <div class="monIntroSplit__media">
                                <div class="monInlinePhoto">
                                    <img
                                        src="{{ $img }}"
                                        alt="Fotografija manastira {{ $monastery->name }}"
                                        loading="lazy"
                                        onerror="this.src='{{ $fallbackImg }}'"
                                    >
                                </div>
                            </div>
                        </div>

                        @foreach($restBlocks as $item)
                            @if($item['isHeading'])
                                <section class="monTextBlock">
                                    <h3>{{ $item['heading'] }}</h3>
                                    @if($item['body'] !== '')
                                        <p>{{ $item['body'] }}</p>
                                    @endif
                                </section>
                            @else
                                <p>{{ $item['body'] }}</p>
                            @endif
                        @endforeach
                    </article>

                @elseif($p)
                    @if(!empty($p->intro))
                        <article class="monSec" id="uvod">
                            <h2>Uvod</h2>
                            <p>{{ $p->intro }}</p>
                        </article>
                    @endif

                    @if(!empty($p->history))
                        <article class="monSec" id="istorija">
                            <h2>Istorija</h2>
                            <p>{{ $p->history }}</p>
                        </article>
                    @endif

                    @if(!empty($p->architecture))
                        <article class="monSec" id="arhitektura">
                            <h2>Arhitektura</h2>
                            <p>{{ $p->architecture }}</p>
                        </article>
                    @endif

                    @if(!empty($p->ktitor_text))
                        <article class="monSec" id="ktitor">
                            <h2>Ktitor</h2>
                            <p>{{ $p->ktitor_text }}</p>
                        </article>
                    @endif

                    @if(!empty($p->art_frescoes))
                        <article class="monSec" id="freske">
                            <h2>Umetnost i freske</h2>
                            <p>{{ $p->art_frescoes }}</p>
                        </article>
                    @endif

                    @if(!empty($p->interesting_facts))
                        <article class="monSec" id="zanimljivosti">
                            <h2>Zanimljivosti</h2>
                            <p>{{ $p->interesting_facts }}</p>
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
                    <div class="monSources">
                        <h3>Izvori</h3>
                        <ul>
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

            <aside class="monSide">
                <div class="card monSide__card">
                    <h3 class="monSide__title">Informacije</h3>

                    <div class="monKV">
                        <div class="monKV__row">
                            <div class="monKV__k">Region</div>
                            <div class="monKV__v">{{ $regionLabel ?: '—' }}</div>
                        </div>

                        <div class="monKV__row">
                            <div class="monKV__k">Grad</div>
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
                        @if(!empty($monastery->lat) && !empty($monastery->lng))
                            <a
                                class="btn2 btn2--wide"
                                target="_blank"
                                rel="noopener"
                                href="https://www.google.com/maps?q={{ $monastery->lat }},{{ $monastery->lng }}"
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
@endsection