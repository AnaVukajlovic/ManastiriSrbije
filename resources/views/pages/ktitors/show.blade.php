@extends('layouts.site')

@section('title', ($ktitor->name ?? 'Ktitor') . ' — Pravoslavni Svetionik')

@section('content')
<section class="section ktitor-show-page">
  <div class="container ktitor-show-container">

    <a class="btn2 ktPro__back" href="{{ route('ktitors.index') }}">← Nazad na listu</a>

    {{-- ZAGLAVLJE --}}
    <div class="ktHeaderCard">
      <div class="ktHeaderCard__inner">
        <div class="ktHeaderCard__content">
          <h1 class="ktHeaderCard__title">{{ $ktitor->name ?? 'Ktitor' }}</h1>
        </div>
        <div class="ktHeaderCard__actions">
          <a class="btn2 btn2--ghost" href="#biografija">Biografija</a>
        </div>
      </div>
    </div>

    @php
      use Illuminate\Support\Str;

      $mainImagePath = optional($ktitor->mainImage)->path
        ?? optional($ktitor->images->sortBy('sort')->first())->path
        ?? null;

      $mainImageUrl = $mainImagePath
        ? asset($mainImagePath)
        : asset('images/placeholders/ktitor.png');

      $rawBio = trim((string)($ktitor->bio ?? ''));
      $lead = 'Biografija uskoro…';
      
      // Čišćenje i izvlačenje Kratkog opisa
      if ($rawBio !== '' && preg_match('/Kratak opis:\s*(.+?)(?:\n[A-ZČĆŠĐŽa-zčćšđž ]+:\s*|\z)/us', $rawBio, $m)) {
        $lead = trim($m[1]);
      } elseif ($rawBio !== '') {
        $lead = Str::limit(strip_tags($rawBio), 260);
      }

      $sections = [];
      if ($rawBio !== '') {
        // Deljenje teksta na celine
        $chunks = preg_split("/\n\s*\n/u", $rawBio) ?: [];
        foreach ($chunks as $ch) {
          $ch = trim($ch);
          if ($ch === '') continue;
          
          if (preg_match('/^(.{2,80}):\s*(.*)$/us', $ch, $m)) {
            $title = trim($m[1]);
            $body = trim($m[2]);
            
            if ($body === '' && str_contains($ch, "\n")) {
              $lines = preg_split("/\n/u", $ch);
              $first = array_shift($lines);
              $title = trim(rtrim($first, ':'));
              $body = trim(implode("\n", $lines));
            }
            
            // Rešavamo ružne markere - pretvaramo ih u lep naslov (npr. ISTORIJA -> Istorija)
            $cleanTitle = Str::ucfirst(mb_strtolower(rtrim($title, ':')));
            
            if (mb_strtolower($cleanTitle) !== 'kratak opis' && $body !== '') {
              $sections[] = ['title' => $cleanTitle, 'body' => $body];
            }
          } else {
            $sections[] = ['title' => 'Biografija', 'body' => $ch];
          }
        }
      }

      $years = ($ktitor->born_year || $ktitor->died_year)
        ? (($ktitor->born_year ?? '—') . ' – ' . ($ktitor->died_year ?? '—'))
        : null;
    @endphp

    {{-- DVO-KOLONARNI LAYOUT --}}
    <div class="ktGrid" id="biografija">
      
      {{-- GLAVNI TEKST (SREĐEN I TIPOGRAFSKI UTIEGNUT) --}}
      <div class="ktMain text-book-layout">
        
        @if($lead && $lead !== 'Biografija uskoro…')
          <div class="kt-hero-lead-block">
            {{-- Prvi pasus dobija uvodni, elegantni stil --}}
            <p class="kt-hero__lead">{{ $lead }}</p>
            <div class="kt-separator"><span class="kt-separator__ornament">❧</span></div>
          </div>
        @endif

        @if(!empty($sections))
          @foreach($sections as $s)
            @php
              // Čistimo pasuse unutar same sekcije od zaostalih duplih enter-a i razmaka
              $paras = array_values(array_filter(array_map('trim', preg_split("/\n+/u", $s['body']) ?: [])));
            @endphp
            
            <article class="kt-book-section">
              <div class="kt-book-section__head">
                <h3>{{ $s['title'] }}</h3>
              </div>
              
              <div class="kt-book-section__body">
                @foreach($paras as $p)
                  {{-- Proveravamo da tekst pasusa slučajno ne sadrži ponovljen marker --}}
                  @php
                    $cleanParagraph = preg_replace('/^[A-ZČĆŠĐŽa-zčćšđž ]+:\s*/u', '', $p);
                  @endphp
                  <p class="kt-paragraph">{{ $cleanParagraph }}</p>
                @endforeach
              </div>

              <div class="kt-separator">
                <span class="kt-separator__ornament">❧</span>
              </div>
            </article>
          @endforeach
        @else
          <p class="kt-paragraph muted">Detaljna biografija za ovu istorijsku ličnost je trenutno u pripremi.</p>
        @endif

        {{-- MANASTIRI --}}
        @if(isset($monasteries) && $monasteries->count())
          <section class="kt-info-panel">
            <div class="kt-info-panel__title">Povezani manastiri</div>
            <div class="kt-tags-list">
              @foreach($monasteries as $m)
                <span class="kt-custom-tag">{{ $m->name ?? 'Manastir' }}</span>
              @endforeach
            </div>
          </section>
        @endif
      </div>

      {{-- DESNI PANEL --}}
      <aside class="ktSide">
        <div class="ktSideBannerPhoto">
          <img src="{{ $mainImageUrl }}" alt="Fotografija: {{ $ktitor->name ?? 'Ktitor' }}" loading="lazy" onerror="this.onerror=null;this.src='{{ asset('images/placeholders/ktitor.png') }}';" />
        </div>

        <div class="card ktSide__card">
          <h3 class="ktSide__title">Informacije</h3>
          <div class="ktKV">
            @if($years)
              <div class="ktKV__row">
                <div class="ktKV__k">Vreme života</div>
                <div class="ktKV__v nm-gold-highlight">{{ $years }}</div>
              </div>
            @endif

            @if(!empty($ktitor->title))
              <div class="ktKV__row">
                <div class="ktKV__k">Titula / Status</div>
                <div class="ktKV__v nm-gold-highlight">{{ $ktitor->title }}</div>
              </div>
            @endif

            @if(!empty($ktitor->dynasty))
              <div class="ktKV__row">
                <div class="ktKV__k">Dinastija</div>
                <div class="ktKV__v">{{ $ktitor->dynasty }}</div>
              </div>
            @endif

            @if(isset($ktitor->is_saint))
              <div class="ktKV__row">
                <div class="ktKV__k">Kanonizacija</div>
                <div class="ktKV__v">{{ $ktitor->is_saint ? 'Da (Svetitelj)' : 'Ne' }}</div>
              </div>
            @endif

            @if(!empty($ktitor->burial_place))
              <div class="ktKV__row">
                <div class="ktKV__k">Mesto sahrane</div>
                <div class="ktKV__v">{{ $ktitor->burial_place }}</div>
              </div>
            @endif
          </div>
        </div>
      </aside>

    </div>
  </div>
</section>

<style>
.ktitor-show-page { padding-top: 20px; padding-bottom: 60px; }
.ktitor-show-container { max-width: 1500px !important; }

.btn2.ktPro__back {
    display: inline-flex;
    align-items: center;
    padding: 10px 18px;
    border-radius: 14px;
    text-decoration: none;
    font-weight: 700;
    background: rgba(255,255,255,.05);
    color: rgba(255,255,255,.92);
    border: 1px solid rgba(255,255,255,.10);
    margin-bottom: 20px;
}

.ktHeaderCard {
    padding: 24px;
    border-radius: 28px;
    border: 1px solid rgba(255,255,255,.08);
    background: linear-gradient(135deg, rgba(28,18,17,.96), rgba(12,8,9,.96));
    box-shadow: 0 20px 48px rgba(0,0,0,.30);
    margin-bottom: 30px;
}
.ktHeaderCard__inner { display: flex; align-items: center; justify-content: space-between; gap: 20px; flex-wrap: wrap; }
.ktHeaderCard__title { margin: 0; font-size: 2.4rem; font-weight: 800; color: #c5a24a; text-shadow: 0 0 14px rgba(197,162,74,.15); }
.ktHeaderCard__actions { display: flex; gap: 12px; }

.ktGrid {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 50px;
    align-items: flex-start;
}

.text-book-layout {
    background: transparent !important;
    border: none !important;
    box-shadow: none !important;
    padding: 0 !important;
}

/* TIPOGRAFSKO SREĐIVANJE TEKSTA */
.kt-hero__lead {
    font-size: 17.5px;
    line-height: 1.9;
    color: rgba(255, 255, 255, 0.95);
    font-style: italic;
    text-align: justify;
    text-justify: inter-word;
    margin-bottom: 20px;
}

.kt-book-section {
    background: transparent !important;
    border: none !important;
    margin-bottom: 10px;
}

.kt-book-section__head h3 {
    font-size: 24px;
    color: #c5a24a; /* Prepoznatljiva žuto-zlatna */
    font-weight: 800;
    margin: 0 0 16px 0;
    letter-spacing: -0.01em;
}

/* Svaki pojedinačni pasus */
.kt-paragraph {
    font-size: 16px;
    line-height: 1.85;
    color: rgba(255, 255, 255, 0.86); /* Lepa prozirna bela za ugodno čitanje */
    text-align: justify;
    text-justify: inter-word; /* Savršeno ravnanje ivica */
    margin-bottom: 18px;
}

/* UKRASNA LINIJA (tanko -> debelo -> tanko) */
.kt-separator {
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 35px 0;
    position: relative;
    width: 100%;
}

.kt-separator::before,
.kt-separator::after {
    content: "";
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(197, 162, 74, 0.3) 50%, rgba(197, 162, 74, 0.7) 100%);
}

.kt-separator::after {
    background: linear-gradient(90deg, rgba(197, 162, 74, 0.7), rgba(197, 162, 74, 0.3) 50%, transparent 100%);
}

.kt-separator__ornament {
    color: #c5a24a;
    font-size: 18px;
    padding: 0 15px;
    line-height: 1;
    opacity: 0.8;
    text-shadow: 0 0 6px rgba(197, 162, 74, 0.2);
}

.kt-book-section:last-child .kt-separator {
    display: none;
}

/* Manastiri panel */
.kt-info-panel {
    margin-top: 40px;
    padding-top: 24px;
    border-top: 1px dashed rgba(197, 162, 74, 0.2);
}
.kt-info-panel__title {
    font-size: 22px;
    color: #c5a24a;
    font-weight: 800;
    margin-bottom: 15px;
}
.kt-tags-list { display: flex; flex-wrap: wrap; gap: 10px; }
.kt-custom-tag {
    display: inline-flex;
    align-items: center;
    padding: 8px 16px;
    border-radius: 12px;
    border: 1px solid rgba(197, 162, 74, 0.3);
    background: rgba(197, 162, 74, 0.05);
    color: #fff;
    font-size: 14px;
    font-weight: 600;
    transition: 0.2s ease;
}
.kt-custom-tag:hover {
    border-color: #c5a24a;
    background: rgba(197, 162, 74, 0.15);
    transform: translateY(-1px);
}

/* SIDEBAR STILOVI */
.ktSideBannerPhoto {
    width: 100%;
    border-radius: 24px;
    overflow: hidden;
    margin-bottom: 16px;
    border: 1px solid rgba(255, 255, 255, 0.06);
    box-shadow: 0 12px 32px rgba(0,0,0,0.4);
}
.ktSideBannerPhoto img {
    width: 100%;
    height: auto;
    display: block;
}

.ktSide__card {
    padding: 24px;
    border-radius: 24px;
    border: 1px solid rgba(255, 255, 255, 0.06);
    background: linear-gradient(180deg, rgba(28,18,17,.96), rgba(12,8,9,.96));
    box-shadow: 0 14px 36px rgba(0,0,0,.3);
}
.ktSide__title { font-size: 20px; color: #fff; font-weight: 800; margin-bottom: 16px; }

.ktKV { display: flex; flex-direction: column; gap: 12px; }
.ktKV__row { display: flex; justify-content: space-between; align-items: flex-start; gap: 14px; padding-bottom: 10px; border-bottom: 1px solid rgba(255,255,255,.04); }
.ktKV__row:last-child { border-bottom: 0; padding-bottom: 0; }
.ktKV__k { font-size: 13.5px; color: rgba(255,255,255,.55); font-weight: 500; }
.ktKV__v { font-size: 14px; color: rgba(255,255,255,.9); text-align: right; font-weight: 600; }
.ktKV__v.nm-gold-highlight { color: #e2c26a !important; font-weight: 700; }

@media (max-width: 1100px) {
    .ktGrid { grid-template-columns: 1fr; gap: 30px; }
    .ktSide { max-width: 460px; margin: 0 auto; width: 100%; }
    .ktHeaderCard__title { font-size: 2rem; }
}
</style>
@endsection