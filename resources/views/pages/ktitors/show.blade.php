@extends('layouts.site')

@section('title', ($ktitor->name ?? 'Ktitor') . ' — Pravoslavni Svetionik')

@section('content')
<section class="section">
  <div class="container">

    <div class="sectionhead">
      <h2>{{ $ktitor->name ?? 'Ktitor' }}</h2>
      <span class="muted">Detalji o ktitoru</span>
    </div>

    @php
      use Illuminate\Support\Str;

      // === Glavna slika (iz ktitor_images) ===
      $imgPath = optional($ktitor->mainImage)->path
        ?? optional($ktitor->images->sortBy('sort')->first())->path
        ?? null;

      $imgUrl = $imgPath ? asset($imgPath) : asset('images/placeholders/ktitor.png');

      // === BIO formatiranje: očekujemo format "Naslov:\nTekst\n\nNaslov:\nTekst..."
      $rawBio = trim((string)($ktitor->bio ?? $ktitor->description ?? ''));

      $sections = [];
      if ($rawBio !== '') {
        $chunks = preg_split("/\n\s*\n/u", $rawBio) ?: [];

        foreach ($chunks as $ch) {
          $ch = trim($ch);
          if ($ch === '') continue;

          if (preg_match('/^(.{2,60}):\s*(.*)$/us', $ch, $m)) {
            $title = trim($m[1]);
            $body  = trim($m[2]);

            if ($body === '' && str_contains($ch, "\n")) {
              $lines = preg_split("/\n/u", $ch);
              $first = array_shift($lines);
              $title = trim(rtrim($first, ':'));
              $body  = trim(implode("\n", $lines));
            }

            $sections[] = ['title' => $title, 'body' => $body];
          } else {
            $sections[] = ['title' => 'Biografija', 'body' => $ch];
          }
        }
      }

      // === Povezani manastiri za JS context ===
      $monNames = [];
      if (isset($monasteries) && $monasteries) {
        $monNames = $monasteries
          ->map(function ($m) { return $m->name ?? 'Manastir'; })
          ->values()
          ->all();
      }

      // === Godine ===
      $years = ($ktitor->born_year || $ktitor->died_year)
        ? (($ktitor->born_year ?? '—') . ' – ' . ($ktitor->died_year ?? '—'))
        : null;

      // === Context za AI (samo iz baze) ===
      $context = [
        'name' => $ktitor->name ?? '',
        'born_year' => $ktitor->born_year,
        'died_year' => $ktitor->died_year,
        'bio'  => $ktitor->bio ?? ($ktitor->description ?? ''),
        'monasteries' => $monNames,
      ];
    @endphp

    {{-- HEADER CARD (slika + osnovno) --}}
    <div class="kt-show">
      <div class="kt-show__media">
        <img
          src="{{ $imgUrl }}"
          alt="{{ e($ktitor->name ?? 'Ktitor') }}"
          loading="lazy"
          onerror="this.onerror=null;this.src='{{ asset('images/placeholders/ktitor.png') }}';"
        />
      </div>

      <div class="kt-show__meta card">
        <div class="kt-show__name">{{ $ktitor->name ?? 'Ktitor' }}</div>

        @if($years)
          <div class="kt-show__years muted">{{ $years }}</div>
        @endif

        <div class="kt-show__lead muted" style="margin-top:10px;">
          {{ Str::limit($rawBio !== '' ? $rawBio : 'Biografija uskoro…', 180) }}
        </div>
      </div>
    </div>

    {{-- BIO / SEKCIJE --}}
    <div class="kt-sections" style="margin-top:14px;">
      @if(!empty($sections))
        @foreach($sections as $s)
          @php
            $title = $s['title'] ?? 'Biografija';
            $body  = trim((string)($s['body'] ?? ''));

            $paras = preg_split("/\n+/u", $body) ?: [];
            $paras = array_values(array_filter(array_map('trim', $paras)));

            $isSlugs = mb_strtolower($title) === mb_strtolower('Manastiri (slugovi)');
            $slugs = [];
            if ($isSlugs) {
              $slugs = preg_split('/[,\s]+/u', $body) ?: [];
              $slugs = array_values(array_filter(array_map('trim', $slugs)));
            }
          @endphp

          <div class="card" style="padding:16px; margin-bottom:12px;">
            <h3 style="margin:0 0 8px 0;">{{ $title }}</h3>

            @if($isSlugs)
              @if(!empty($slugs))
                <div style="display:flex; flex-wrap:wrap; gap:8px;">
                  @foreach($slugs as $slug)
                    <span class="badge" style="display:inline-block; padding:6px 10px; border-radius:999px; border:1px solid rgba(255,255,255,.12);">
                      {{ $slug }}
                    </span>
                  @endforeach
                </div>
              @else
                <p class="muted" style="margin:0;">—</p>
              @endif
            @else
              @if(!empty($paras))
                @foreach($paras as $p)
                  <p style="margin:0 0 10px 0; line-height:1.75;">{{ $p }}</p>
                @endforeach
              @else
                <p class="muted" style="margin:0;">—</p>
              @endif
            @endif
          </div>
        @endforeach
      @else
        <div class="card" style="padding:16px; margin-top:12px;">
          <p class="muted" style="margin:0;">Nema opisa za ovog ktitora.</p>
        </div>
      @endif
    </div>

    {{-- POVEZANI MANASTIRI --}}
    @if(isset($monasteries) && $monasteries->count())
      <div class="card" style="padding:16px; margin: 6px 0 16px;">
        <h3 style="margin:0 0 10px 0;">Povezani manastiri</h3>
        <ul style="margin:0; padding-left:18px; line-height:1.7;">
          @foreach($monasteries as $m)
            <li>{{ $m->name ?? 'Manastir' }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    {{-- PITAJ AI --}}
    <div class="card" style="padding:16px;">
      <h3 style="margin:0 0 10px 0;">Pitaj AI o ovom ktitoru</h3>

      <form id="aiForm" style="display:flex; gap:10px; align-items:center; flex-wrap:wrap;">
        <input
          id="aiQuestion"
          type="text"
          name="question"
          placeholder="Npr. Ko je bio ovaj ktitor i po čemu je poznat?"
          style="flex:1; min-width:260px;"
        />
        <button id="aiBtn" type="submit" class="btn">Pitaj AI</button>
      </form>

      <div id="aiStatus" class="muted" style="margin-top:10px; display:none;"></div>

      <div id="aiAnswerWrap" style="margin-top:12px; display:none;">
        <div class="muted" style="margin-bottom:6px;">Odgovor:</div>
        <div id="aiAnswer" class="card" style="padding:12px; line-height:1.7;"></div>
      </div>
    </div>

  </div>
</section>

{{-- Minimal CSS za show (možeš kasnije prebaciti u site.css) --}}
<style>
.kt-show{
  display:grid;
  grid-template-columns: 320px 1fr;
  gap:14px;
  align-items:stretch;
  margin-top:12px;
}
.kt-show__media{
  border-radius: calc(var(--r) + 6px);
  overflow:hidden;
  border:1px solid rgba(197,162,74,.20);
  box-shadow: 0 14px 40px rgba(0,0,0,.34);
  background: rgba(255,255,255,.04);
}
.kt-show__media img{ width:100%; height:100%; min-height: 240px; object-fit:cover; display:block; }
.kt-show__meta{ padding:16px; }
.kt-show__name{ font-weight:900; font-size:22px; color:#fff; line-height:1.2; }
.kt-show__years{ margin-top:6px; }

@media (max-width: 820px){
  .kt-show{ grid-template-columns: 1fr; }
  .kt-show__media img{ min-height: 220px; }
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

  // Context iz baze (Blade -> JS)
  const context = @json($context);

  // Sanitizuj bio (ako je slučajno HTML) + limit da prompt ne bude ogroman
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

  // Ovo šaljemo kao "jedini izvor istine"
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