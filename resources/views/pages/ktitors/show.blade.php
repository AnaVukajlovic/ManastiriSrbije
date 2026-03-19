@extends('layouts.site')

@section('title', ($ktitor->name ?? 'Ktitor') . ' — Pravoslavni Svetionik')

@section('content')
<section class="section ktitor-show-page">
  <div class="container ktitor-show-container">



    @php
      use Illuminate\Support\Str;

      $slug = $ktitor->slug ?? 'ktitor';

      // GLAVNA SLIKA
      $mainImagePath = optional($ktitor->mainImage)->path
        ?? optional($ktitor->images->sortBy('sort')->first())->path
        ?? null;

      $mainImageUrl = $mainImagePath
        ? asset($mainImagePath)
        : asset('images/placeholders/ktitor.png');

      // BIO
      $rawBio = trim((string)($ktitor->bio ?? ''));

      $lead = 'Biografija uskoro…';
      if ($rawBio !== '' && preg_match('/Kratak opis:\s*(.+?)(?:\n[A-ZČĆŠĐŽa-zčćšđž ]+:\s*|\z)/us', $rawBio, $m)) {
        $lead = trim($m[1]);
      } elseif ($rawBio !== '') {
        $lead = Str::limit(strip_tags($rawBio), 260);
      }

      $sections = [];
      if ($rawBio !== '') {
        $chunks = preg_split("/\n\s*\n/u", $rawBio) ?: [];

        foreach ($chunks as $ch) {
          $ch = trim($ch);
          if ($ch === '') continue;

          if (preg_match('/^(.{2,80}):\s*(.*)$/us', $ch, $m)) {
            $title = trim($m[1]);
            $body  = trim($m[2]);

            if ($body === '' && str_contains($ch, "\n")) {
              $lines = preg_split("/\n/u", $ch);
              $first = array_shift($lines);
              $title = trim(rtrim($first, ':'));
              $body  = trim(implode("\n", $lines));
            }

            if (mb_strtolower($title) !== 'kratak opis') {
              $sections[] = ['title' => $title, 'body' => $body];
            }
          } else {
            $sections[] = ['title' => 'Biografija', 'body' => $ch];
          }
        }
      }

      // GODINE
      $years = ($ktitor->born_year || $ktitor->died_year)
        ? (($ktitor->born_year ?? '—') . ' – ' . ($ktitor->died_year ?? '—'))
        : null;

      // POVEZANI MANASTIRI
      $monNames = [];
      if (isset($monasteries) && $monasteries) {
        $monNames = $monasteries
          ->map(fn($m) => $m->name ?? 'Manastir')
          ->values()
          ->all();
      }

      // POSTOJEĆE SLIKE IZ BAZE
      $galleryFromDb = $ktitor->images
        ? $ktitor->images->sortBy('sort')->values()
        : collect();

      

      

      $context = [
        'name' => $ktitor->name ?? '',
        'born_year' => $ktitor->born_year,
        'died_year' => $ktitor->died_year,
        'bio' => $ktitor->bio ?? '',
        'monasteries' => $monNames,
      ];
    @endphp

    {{-- HERO --}}
   <div class="kt-hero-shell">
  <div class="kt-page-title">
    <h1>{{ $ktitor->name ?? 'Ktitor' }}</h1>
    <p>Vladari, vladarke i dobrotvori srpskih svetinja</p>
  </div>

  <div class="kt-hero">
    <div class="kt-hero__media">
      <img
        src="{{ $mainImageUrl }}"
        alt="{{ e($ktitor->name ?? 'Ktitor') }}"
        loading="lazy"
        onerror="this.onerror=null;this.src='{{ asset('images/placeholders/ktitor.png') }}';"
      />
    </div>

    <div class="kt-hero__content">
      <div class="kt-kicker">Srpski ktitor i istorijska ličnost</div>

      <h2 class="kt-hero__title">{{ $ktitor->name ?? 'Ktitor' }}</h2>

      @if($years)
        <div class="kt-hero__years">{{ $years }}</div>
      @endif

      <p class="kt-hero__lead">{{ $lead }}</p>

      <div class="kt-hero__divider"></div>

      <div class="kt-hero__mini">
        <div class="kt-hero__mini-item">
          <span class="kt-hero__mini-label">Uloga</span>
          <span class="kt-hero__mini-value">Ktitor i istorijska ličnost</span>
        </div>

        <div class="kt-hero__mini-item">
          <span class="kt-hero__mini-label">Sadržaj</span>
          <span class="kt-hero__mini-value">Biografija, značaj i veze sa svetinjama</span>
        </div>
      </div>

      <div class="kt-hero__actions">
        <a href="#biografija" class="btn">Biografija</a>
        <a href="#ai-blok" class="btn btn--ghost">Pitaj AI</a>
      </div>
    </div>
  </div>
</div>
    {{-- BIO SEKCIJE --}}
    <div id="biografija" class="kt-sections-grid">
      @if(!empty($sections))
        @foreach($sections as $s)
          @php
            $title = $s['title'] ?? 'Biografija';
            $body  = trim((string)($s['body'] ?? ''));
            $paras = preg_split("/\n+/u", $body) ?: [];
            $paras = array_values(array_filter(array_map('trim', $paras)));
          @endphp

          <section class="card kt-section-card">
            <div class="kt-section-card__head">
              <h3>{{ $title }}</h3>
            </div>

            <div class="kt-section-card__body">
              @if(!empty($paras))
                @foreach($paras as $p)
                  <p>{{ $p }}</p>
                @endforeach
              @else
                <p class="muted">—</p>
              @endif
            </div>
          </section>
        @endforeach
      @else
        <section class="card kt-section-card">
          <div class="kt-section-card__head">
            <h3>Biografija</h3>
          </div>
          <div class="kt-section-card__body">
            <p class="muted">Nema opisa za ovog ktitora.</p>
          </div>
        </section>
      @endif
    </div>

    {{-- POVEZANI MANASTIRI --}}
    @if(isset($monasteries) && $monasteries->count())
      <section class="card kt-info-card">
        <div class="kt-block-title">Povezani manastiri</div>

        <div class="kt-tags">
          @foreach($monasteries as $m)
            <span class="kt-tag">{{ $m->name ?? 'Manastir' }}</span>
          @endforeach
        </div>
      </section>
    @endif

    

    

    {{-- AI --}}
    <section id="ai-blok" class="card kt-ai-card">
      <div class="kt-block-head">
        <div>
          <div class="kt-block-title">Pitaj AI o ovom ktitoru</div>
          <div class="muted">AI koristi podatke iz tvoje baze kao glavni izvor</div>
        </div>
      </div>

      <form id="aiForm" class="kt-ai-form">
        <input
          id="aiQuestion"
          type="text"
          name="question"
          placeholder="Npr. Ko je bio ovaj ktitor i po čemu je poznat?"
        />
        <button id="aiBtn" type="submit" class="btn">Pitaj AI</button>
      </form>

      <div id="aiStatus" class="muted" style="margin-top:10px; display:none;"></div>

      <div id="aiAnswerWrap" style="margin-top:14px; display:none;">
        <div class="muted" style="margin-bottom:8px;">Odgovor:</div>
        <div id="aiAnswer" class="kt-answer-box"></div>
      </div>
    </section>

  </div>
</section>

<style>
.ktitor-show-page{
  padding-top: 8px;
}

.ktitor-show-container{
  max-width: 1500px !important;
}
.ktitor-show-page{
  padding-top: 10px;
}

.ktitor-show-container{
  max-width: 1500px !important;
}

.kt-hero-shell{
  margin-top: 6px;
}

.kt-page-title{
  margin-bottom: 18px;
}

.kt-page-title h1{
  margin:0;
  font-size:58px;
  line-height:1.02;
  font-weight:900;
  color:#fff;
  letter-spacing:-0.03em;
}

.kt-page-title p{
  margin:10px 0 0 0;
  color:rgba(255,255,255,.72);
  font-size:18px;
  line-height:1.5;
}

.kt-hero{
  display:grid;
  grid-template-columns: 360px 1fr;
  gap:22px;
  align-items:stretch;
}

.kt-hero__media{
  position:relative;
  border-radius:28px;
  overflow:hidden;
  border:1px solid rgba(197,162,74,.24);
  background:rgba(255,255,255,.03);
  box-shadow:0 24px 56px rgba(0,0,0,.34);
  aspect-ratio: 4 / 5;
}

.kt-hero__media img{
  width:100%;
  height:100%;
  object-fit:cover;
  display:block;
  transition:transform .45s ease;
}

.kt-hero__media:hover img{
  transform:scale(1.03);
}

.kt-hero__media::after{
  content:'';
  position:absolute;
  inset:0;
  background:
    linear-gradient(to top, rgba(0,0,0,.22), rgba(0,0,0,.03));
  pointer-events:none;
}

.kt-hero__content{
  padding:34px;
  border-radius:28px;
  background:
    radial-gradient(circle at top right, rgba(197,162,74,.14), transparent 26%),
    linear-gradient(180deg, rgba(255,255,255,.045), rgba(255,255,255,.02)),
    rgba(18,12,13,.92);
  border:1px solid rgba(197,162,74,.18);
  box-shadow:0 22px 52px rgba(0,0,0,.28);
  display:flex;
  flex-direction:column;
  justify-content:center;
  min-height:100%;
}

.kt-kicker{
  font-size:12px;
  letter-spacing:.13em;
  text-transform:uppercase;
  color:rgba(226,194,106,.90);
  margin-bottom:14px;
  font-weight:800;
}

.kt-hero__title{
  margin:0;
  font-size:54px;
  line-height:1.02;
  color:#fff;
  font-weight:900;
  letter-spacing:-0.03em;
}

.kt-hero__years{
  margin-top:14px;
  display:inline-flex;
  align-items:center;
  width:fit-content;
  padding:10px 16px;
  border-radius:999px;
  background:rgba(197,162,74,.10);
  border:1px solid rgba(197,162,74,.22);
  color:rgba(226,194,106,.98);
  font-size:15px;
  font-weight:800;
}

.kt-hero__lead{
  margin-top:18px;
  font-size:17px;
  line-height:1.9;
  color:rgba(255,255,255,.86);
  max-width:900px;
}

.kt-hero__divider{
  margin:22px 0 18px;
  height:1px;
  background:linear-gradient(to right, rgba(197,162,74,.24), rgba(255,255,255,.05));
}

.kt-hero__mini{
  display:grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap:14px;
}

.kt-hero__mini-item{
  padding:14px 16px;
  border-radius:18px;
  background:rgba(255,255,255,.025);
  border:1px solid rgba(255,255,255,.06);
}

.kt-hero__mini-label{
  display:block;
  font-size:12px;
  text-transform:uppercase;
  letter-spacing:.08em;
  color:rgba(226,194,106,.84);
  margin-bottom:6px;
  font-weight:700;
}

.kt-hero__mini-value{
  display:block;
  color:rgba(255,255,255,.88);
  line-height:1.6;
  font-size:14px;
}

.kt-hero__actions{
  margin-top:22px;
  display:flex;
  gap:10px;
  flex-wrap:wrap;
}

@media (max-width: 1200px){
  .kt-page-title h1{
    font-size:48px;
  }

  .kt-hero__title{
    font-size:44px;
  }
}

@media (max-width: 1100px){
  .kt-hero{
    grid-template-columns:1fr;
  }

  .kt-page-title h1{
    font-size:42px;
  }

  .kt-hero__title{
    font-size:40px;
  }

  .kt-hero__mini{
    grid-template-columns:1fr;
  }
}

@media (max-width: 720px){
  .kt-page-title h1{
    font-size:34px;
  }

  .kt-page-title p{
    font-size:15px;
  }

  .kt-hero__content{
    padding:24px;
  }

  .kt-hero__title{
    font-size:32px;
  }

  .kt-hero__lead{
    font-size:15px;
  }
}

/* BIO SEKCIJE */
.kt-sections-grid{
  margin-top:22px;
  display:grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap:18px;
}

.kt-section-card{
  padding:0;
  overflow:hidden;
  border-radius:24px;
  border:1px solid rgba(197,162,74,.14);
  background:
    linear-gradient(180deg, rgba(255,255,255,.045), rgba(255,255,255,.02)),
    rgba(18,12,13,.86);
  box-shadow:0 16px 34px rgba(0,0,0,.24);
}

.kt-section-card__head{
  padding:18px 20px 14px;
  border-bottom:1px solid rgba(255,255,255,.06);
  background:
    linear-gradient(180deg, rgba(197,162,74,.10), rgba(197,162,74,.03));
}

.kt-section-card__head h3{
  margin:0;
  font-size:21px;
  color:#fff;
  font-weight:900;
}

.kt-section-card__body{
  padding:20px;
}

.kt-section-card__body p{
  margin:0 0 14px 0;
  line-height:1.88;
  color:rgba(255,255,255,.87);
  text-align:justify;
}

/* INFO / BLOKOVI */
.kt-info-card,
.kt-ai-card{
  margin-top:20px;
  padding:22px;
  border-radius:24px;
  border:1px solid rgba(197,162,74,.14);
  background:
    linear-gradient(180deg, rgba(255,255,255,.045), rgba(255,255,255,.02)),
    rgba(18,12,13,.86);
  box-shadow:0 16px 34px rgba(0,0,0,.24);
}

.kt-block-head{
  display:flex;
  justify-content:space-between;
  align-items:flex-start;
  gap:12px;
  margin-bottom:16px;
}

.kt-block-title{
  font-size:24px;
  font-weight:900;
  color:#fff;
  margin-bottom:5px;
}

.kt-tags{
  display:flex;
  flex-wrap:wrap;
  gap:10px;
}

.kt-tag{
  display:inline-flex;
  align-items:center;
  padding:10px 15px;
  border-radius:999px;
  border:1px solid rgba(197,162,74,.24);
  background:rgba(255,255,255,.03);
  color:rgba(255,255,255,.88);
  font-size:14px;
  box-shadow:0 6px 16px rgba(0,0,0,.14);
}



.kt-placeholder-icon{
  width:56px;
  height:56px;
  border-radius:50%;
  display:flex;
  align-items:center;
  justify-content:center;
  font-size:24px;
  font-weight:900;
  color:rgba(226,194,106,.95);
  border:1px solid rgba(197,162,74,.25);
  margin-bottom:12px;
}

.kt-placeholder-title{
  color:#fff;
  font-weight:900;
  font-size:18px;
  margin-bottom:8px;
}

.kt-placeholder-text{
  color:rgba(255,255,255,.72);
  line-height:1.7;
  font-size:13px;
}

/* AI */
.kt-ai-form{
  display:flex;
  gap:12px;
  align-items:center;
  flex-wrap:wrap;
}

.kt-ai-form input{
  flex:1;
  min-width:260px;
}

.kt-answer-box{
  padding:16px;
  border-radius:18px;
  background:rgba(255,255,255,.04);
  border:1px solid rgba(255,255,255,.08);
  line-height:1.82;
  color:rgba(255,255,255,.88);
}

/* RESPONSIVE */
@media (max-width: 1100px){
  .kt-hero{
    grid-template-columns: 1fr;
  }

  .kt-sections-grid{
    grid-template-columns: 1fr;
  }

}

@media (max-width: 720px){
  .kt-hero__title{
    font-size:30px;
  }
}
</style>

<script>
(function () {
  const form = document.getElementById('aiForm');
  const q = document.getElementById('aiQuestion');
  const btn = document.getElementById('aiBtn');
  const status = document.getElementById('aiStatus');
  const wrap = document.getElementById('aiAnswerWrap');
  const answerEl = document.getElementById('aiAnswer');

  if (!form) return;

  function setStatus(text) {
    status.style.display = 'block';
    status.textContent = text;
  }

  const context = @json($context);

  const safeBio = (context.bio || '')
    .replace(/<[^>]*>/g, '')
    .replace(/\r\n/g, '\n')
    .replace(/\n{3,}/g, '\n\n')
    .trim()
    .slice(0, 4500);

  const years =
    (context.born_year || context.died_year)
      ? `${context.born_year ?? '—'} – ${context.died_year ?? '—'}`
      : '—';

  const contextText =
    `KTITOR (podatak iz baze; jedini izvor istine):\n` +
    `Ime: ${context.name || '—'}\n` +
    `Godine: ${years}\n\n` +
    `Biografija:\n${safeBio || 'Nema opisa u bazi.'}\n\n` +
    `Povezani manastiri:\n` +
    (context.monasteries && context.monasteries.length
      ? context.monasteries.map(n => `- ${n}`).join('\n')
      : '- Nema povezanih manastira u bazi.');

  form.addEventListener('submit', async (e) => {
    e.preventDefault();

    const question = (q.value || '').trim();
    if (!question) {
      setStatus('Unesi pitanje.');
      wrap.style.display = 'none';
      return;
    }

    btn.disabled = true;
    setStatus('Šaljem pitanje AI...');

    try {
      const res = await fetch('/api/ai/chat', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json'
        },
        body: JSON.stringify({
          message: question,
          context: contextText
        })
      });

      const data = await res.json().catch(() => ({}));

      if (!res.ok || data.ok === false) {
        setStatus(data.error || 'Greška pri AI upitu.');
        wrap.style.display = 'none';
        btn.disabled = false;
        return;
      }

      const text = (data.answer || data.reply || '').trim();
      setStatus(text ? 'Gotovo.' : 'AI nije vratio odgovor.');
      answerEl.textContent = text || '—';
      wrap.style.display = 'block';
    } catch (err) {
      setStatus('Ne mogu da kontaktiram backend ili Ollamu.');
      wrap.style.display = 'none';
    } finally {
      btn.disabled = false;
    }
  });
})();
</script>
@endsection