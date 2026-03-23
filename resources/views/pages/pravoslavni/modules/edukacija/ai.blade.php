@extends('layouts.site')

@section('title','AI — Edukacija')
@section('nav_edu','active')

@section('content')
<section class="section ai-edu-page">
  <div class="container">

    {{-- Header --}}
    <div class="ai-page-head">
      <div class="ai-page-head__content">
        <h1 class="ai-page-title">AI radionica</h1>
        <p class="ai-page-sub">
          Ovde AI nije “samo pitanja”. Koristi ga kao alat:
          <span>sažetak</span>, <span>objašnjenje</span>, <span>rečnik</span>, <span>kviz</span> —
          posebno korisno za učenje istorije, SPC i kulture.
          Režim: <strong>{{ env('AI_USE_OLLAMA', false) ? 'Ollama + baza' : 'Pametni odgovor iz baze' }}</strong>
        </p>
      </div>

      <div class="ai-page-head__actions">
        <a class="btn btn--ghost" href="{{ route('edukacija.index') }}">← Edukacija</a>
        <a class="btn btn--ghost" href="{{ route('edukacija.show','ucenje-interakcija') }}">Učenje i interakcija</a>
      </div>
    </div>

    <div class="ai-grid">

      {{-- LEFT: Inputs --}}
      <div class="ps-card ai-card">
        <div class="ai-head">
          <div>
            <h3 class="ai-section-title">Izaberi alat</h3>
            <div class="muted ai-section-sub">Nalepi tekst ili napiši temu — AI će obraditi po izabranom režimu.</div>
          </div>
          <span class="ai-pill">Radionica</span>
        </div>

        <div class="ai-modes" role="tablist" aria-label="AI alati">
          <button class="ai-mode active" type="button" data-mode="summarize" role="tab" aria-selected="true">Sažmi</button>
          <button class="ai-mode" type="button" data-mode="explain" role="tab" aria-selected="false">Objasni</button>
          <button class="ai-mode" type="button" data-mode="glossary" role="tab" aria-selected="false">Rečnik</button>
          <button class="ai-mode" type="button" data-mode="quiz" role="tab" aria-selected="false">Kviz</button>
        </div>

        <div class="ai-row">
          <label class="muted ai-label" for="ai-level">Nivo objašnjenja</label>
          <select id="ai-level" class="ai-select">
            <option value="A2">A2 — vrlo jednostavno</option>
            <option value="B1" selected>B1 — školski nivo</option>
            <option value="B2">B2 — detaljnije</option>
          </select>

          <label class="muted ai-label" for="ai-len">Dužina</label>
          <select id="ai-len" class="ai-select">
            <option value="short">kratko</option>
            <option value="medium" selected>srednje</option>
            <option value="long">duže</option>
          </select>
        </div>

        <textarea
          id="ai-msg"
          rows="9"
          class="ai-input"
          placeholder="Nalepi pasus iz edukacije ili napiši temu (npr. 'Sveti Sava i autokefalnost 1219', 'Raška škola', 'SPC pod Turcima')..."
        ></textarea>

        <div class="ai-actions">
          <button class="btn" id="ai-send" type="button">Pokreni alat</button>
          <button class="btn btn--ghost" id="ai-clear" type="button">Očisti</button>
          <span class="muted" id="ai-status" style="margin-left:auto;"></span>
        </div>

        <div class="ai-hint">
          <strong>Tip:</strong> Za najbolje rezultate nalepi 1–3 pasusa (do ~1500 reči). Za kviz, nalepi lekciju — AI napravi pitanja.
          <br><span class="muted">Prečica: Ctrl+Enter (ili ⌘+Enter).</span>
        </div>
      </div>

      {{-- RIGHT: Output --}}
      <div class="ps-card ai-card">
        <div class="ai-head">
          <div>
            <h3 class="ai-section-title">Rezultat</h3>
            <div class="muted ai-section-sub">Možeš da kopiraš i ubaciš u beleške / pripremu za kviz.</div>
          </div>

          <div class="ai-top-actions">
            <button class="btn btn--ghost" id="ai-copy" type="button">Kopiraj</button>
            <button class="btn btn--ghost" id="ai-demo" type="button">Primer</button>
          </div>
        </div>

        <div id="ai-out" class="ai-output">—</div>

        <div class="ai-footer muted">
          Ako dobiješ previše “opšte” odgovore: nalepi više konteksta iz lekcije.
        </div>
      </div>

    </div>

  </div>
</section>

<style>
.ai-edu-page{
  --ai-ink:rgba(255,255,255,.94);
  --ai-muted:rgba(255,255,255,.74);
  --ai-line:rgba(255,255,255,.10);
  --ai-soft:rgba(255,255,255,.04);
  --ai-soft-2:rgba(255,255,255,.025);
  --ai-gold:#c5a24a;
  --ai-gold-2:#e2c26a;
  --ai-gold-soft:rgba(197,162,74,.10);
  --ai-gold-line:rgba(197,162,74,.24);
  --ai-shadow:0 22px 55px rgba(0,0,0,.28);
}

.ai-edu-page .container{
  width:min(1520px, calc(100% - 36px));
  max-width:none;
}

.ai-page-head{
  display:flex;
  justify-content:space-between;
  gap:16px;
  align-items:flex-end;
  flex-wrap:wrap;
  margin-bottom:18px;
}

.ai-page-head__content{
  max-width:980px;
}

.ai-page-title{
  margin:0;
  font-size:clamp(2rem, 3.1vw, 3.25rem);
  line-height:1.06;
  letter-spacing:-.02em;
  color:var(--ai-gold);
}

.ai-page-sub{
  margin:10px 0 0;
  max-width:940px;
  color:rgba(255,255,255,.84);
  line-height:1.8;
  font-size:1.03rem;
}

.ai-page-sub span{
  color:#f0d892;
  font-weight:800;
}

.ai-page-sub strong{
  color:#f3d58e;
  font-weight:900;
}

.ai-page-head__actions{
  display:flex;
  gap:10px;
  flex-wrap:wrap;
}

.ai-grid{
  display:grid;
  grid-template-columns:1.05fr .95fr;
  gap:18px;
}

.ai-card{
  position:relative;
  overflow:hidden;
  border:1px solid var(--ai-line);
  border-radius:28px;
  background:
    radial-gradient(circle at top right, rgba(197,162,74,.10), transparent 22%),
    linear-gradient(180deg, var(--ai-soft), var(--ai-soft-2));
  box-shadow:var(--ai-shadow);
}

.ai-card::before{
  content:"";
  position:absolute;
  inset:-80px -80px auto auto;
  width:260px;
  height:260px;
  background:radial-gradient(circle at 30% 30%, rgba(197,162,74,.16), transparent 60%);
  pointer-events:none;
}

.ai-head{
  display:flex;
  align-items:flex-start;
  justify-content:space-between;
  gap:12px;
  margin-bottom:14px;
  position:relative;
  z-index:1;
}

.ai-section-title{
  margin:0;
  color:#f0d892;
  font-size:1.8rem;
  line-height:1.15;
}

.ai-section-sub{
  margin-top:6px;
  line-height:1.6;
}

.ai-pill{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  font-size:12px;
  font-weight:900;
  letter-spacing:.2px;
  padding:7px 11px;
  border-radius:999px;
  border:1px solid rgba(197,162,74,.42);
  background:rgba(197,162,74,.10);
  color:#f0d892;
  opacity:.98;
  white-space:nowrap;
}

.ai-modes{
  display:flex;
  gap:10px;
  flex-wrap:wrap;
  margin:12px 0 14px;
  position:relative;
  z-index:1;
}

.ai-mode{
  border:1px solid rgba(255,255,255,.14);
  background:rgba(0,0,0,.22);
  color:rgba(255,255,255,.92);
  padding:10px 14px;
  border-radius:999px;
  cursor:pointer;
  font-weight:950;
  transition:transform .08s ease, border-color .12s ease, background .12s ease;
}

.ai-mode:hover{
  transform:translateY(-1px);
}

.ai-mode.active{
  border-color:rgba(197,162,74,.55);
  background:rgba(197,162,74,.12);
  color:#f2d58a;
}

.ai-row{
  display:grid;
  grid-template-columns:auto 1fr auto 1fr;
  gap:10px 12px;
  align-items:center;
  margin-bottom:12px;
  position:relative;
  z-index:1;
}

.ai-label{
  color:#ecd08a !important;
  font-weight:700;
}

.ai-select{
  width:100%;
  border-radius:14px;
  border:1px solid rgba(255,255,255,.14);
  background:rgba(0,0,0,.20);
  color:rgba(255,255,255,.92);
  padding:10px 12px;
  outline:none;
}

.ai-select:focus{
  border-color:rgba(197,162,74,.55);
  box-shadow:0 0 0 4px rgba(197,162,74,.10);
}

.ai-input{
  width:100%;
  border-radius:16px;
  border:1px solid rgba(255,255,255,.14);
  background:rgba(0,0,0,.20);
  color:rgba(255,255,255,.92);
  padding:12px 12px;
  outline:none;
  line-height:1.7;
  resize:vertical;
  min-height:220px;
  position:relative;
  z-index:1;
}

.ai-input::placeholder{
  color:rgba(255,255,255,.48);
}

.ai-input:focus{
  border-color:rgba(197,162,74,.55);
  box-shadow:0 0 0 4px rgba(197,162,74,.10);
}

.ai-actions{
  display:flex;
  gap:10px;
  align-items:center;
  margin-top:10px;
  flex-wrap:wrap;
  position:relative;
  z-index:1;
}

.ai-hint{
  margin-top:12px;
  border:1px solid rgba(197,162,74,.16);
  background:rgba(255,255,255,.03);
  border-radius:16px;
  padding:12px 14px;
  line-height:1.75;
  opacity:.92;
  position:relative;
  z-index:1;
}

.ai-hint strong{
  color:#f0d892;
}

.ai-top-actions{
  display:flex;
  gap:10px;
  flex-wrap:wrap;
}

.ai-output{
  border:1px solid rgba(255,255,255,.10);
  background:rgba(0,0,0,.16);
  border-radius:18px;
  padding:14px 14px;
  min-height:360px;
  white-space:pre-wrap;
  line-height:1.8;
  opacity:.96;
  position:relative;
  z-index:1;
}

.ai-footer{
  margin-top:12px;
  line-height:1.65;
  opacity:.8;
  position:relative;
  z-index:1;
}

@media (max-width: 980px){
  .ai-grid{
    grid-template-columns:1fr;
  }
}

@media (max-width: 700px){
  .ai-edu-page .container{
    width:min(100%, calc(100% - 18px));
  }

  .ai-page-title{
    font-size:2rem;
  }

  .ai-page-sub{
    font-size:1rem;
    line-height:1.72;
  }

  .ai-card{
    border-radius:22px;
  }

  .ai-section-title{
    font-size:1.45rem;
  }

  .ai-row{
    grid-template-columns:1fr;
  }

  .ai-output{
    min-height:280px;
  }
}



/* ===== AI radionica – estetski patch ===== */

.ai-edu-page{
  padding-top: 10px;
}

.ai-grid{
  align-items: stretch;
}

.ai-card{
  padding: 22px 22px 20px;
  min-width: 0;
}

.ai-head{
  margin-bottom: 18px;
}

.ai-section-title{
  font-size: clamp(1.7rem, 2vw, 2.1rem);
  line-height: 1.15;
  margin: 0 0 4px;
  word-break: break-word;
}

.ai-section-sub{
  margin-top: 4px;
}

.ai-modes{
  margin: 14px 0 18px;
}

.ai-row{
  margin-bottom: 14px;
}

.ai-input{
  min-height: 230px;
}

.ai-output{
  min-height: 430px;
}

.ai-footer{
  margin-top: 14px;
}

.ai-page-head{
  margin-bottom: 22px;
}

.ai-page-head__actions{
  align-items: center;
}

.ai-page-sub{
  max-width: 980px;
}

.ai-page-sub strong{
  white-space: nowrap;
}

@media (max-width: 1100px){
  .ai-grid{
    grid-template-columns: 1fr;
  }

  .ai-output{
    min-height: 300px;
  }
}

@media (max-width: 700px){
  .ai-card{
    padding: 18px 16px 16px;
  }

  .ai-section-title{
    font-size: 1.45rem;
  }

  .ai-input{
    min-height: 190px;
  }

  .ai-output{
    min-height: 240px;
  }
}
</style>

<script>
(() => {
  const sendBtn = document.getElementById('ai-send');
  const clearBtn = document.getElementById('ai-clear');
  const copyBtn = document.getElementById('ai-copy');
  const demoBtn = document.getElementById('ai-demo');

  const msgEl = document.getElementById('ai-msg');
  const outEl = document.getElementById('ai-out');
  const stEl  = document.getElementById('ai-status');

  const levelEl = document.getElementById('ai-level');
  const lenEl   = document.getElementById('ai-len');

  let mode = 'summarize';

  function setMode(newMode){
    mode = newMode;
    document.querySelectorAll('.ai-mode').forEach(b => {
      b.classList.toggle('active', b.dataset.mode === newMode);
      b.setAttribute('aria-selected', b.dataset.mode === newMode ? 'true' : 'false');
    });

    const placeholders = {
      summarize: "Nalepi pasus ili lekciju koju želiš da sažmeš...",
      explain:   "Upiši temu ili nalepi pasus koji želiš da AI objasni...",
      glossary:  "Nalepi tekst ili napiši temu — AI pravi mali rečnik pojmova iz tog sadržaja...",
      quiz:      "Nalepi lekciju — AI će napraviti kratak kviz iz tog sadržaja..."
    };

    msgEl.placeholder = placeholders[newMode] || msgEl.placeholder;
  }

  document.querySelectorAll('.ai-mode').forEach(btn => {
    btn.addEventListener('click', () => setMode(btn.dataset.mode));
  });

  function buildRequestPayload(rawText){
    const level = levelEl.value;
    const length = lenEl.value;

    const maxTokensMap = {
      summarize: { short: 120, medium: 170, long: 220 },
      explain:   { short: 140, medium: 190, long: 240 },
      glossary:  { short: 160, medium: 220, long: 280 },
      quiz:      { short: 170, medium: 240, long: 320 }
    };

    const maxTokens =
      maxTokensMap[mode]?.[length]
      ?? 180;

    const commonRules = [
      'Odgovaraj isključivo na standardnom srpskom jeziku.',
      'Koristi ekavicu i latinicu.',
      'Ne mešaj jezike.',
      'Ne koristi hrvatske oblike kao što su: povijest, tisuća, svećenik, oduvijek.',
      'Ako u tekstu nema dovoljno podataka, jasno reci da nema dovoljno podataka.',
      'Ne izmišljaj činjenice.',
      'Budi jasan, prirodan i pregledan.'
    ].join(' ');

    const instructions = {
      summarize:
        `Zadatak: Sažmi dati tekst. ${commonRules} ` +
        `Nivo objašnjenja: ${level}. Dužina: ${length}. ` +
        `Format odgovora: ` +
        `1) naslov od najviše 8 reči, ` +
        `2) jedan sažet pasus od 3 do 5 rečenica, ` +
        `3) najviše 3 ključne stavke u novim redovima, bez dugih objašnjenja.`,

      explain:
        `Zadatak: Objasni dati pojam ili tekst tako da bude razumljiv učeniku. ${commonRules} ` +
        `Nivo objašnjenja: ${level}. Dužina: ${length}. ` +
        `Format odgovora: ` +
        `1) kratko objašnjenje u jednom pasusu, ` +
        `2) ako je korisno, dodaj još jedan kratak pasus sa značajem ili primerom. ` +
        `Ne dodaj pitanja na kraju.`,

      glossary:
        `Zadatak: Napravi rečnik iz datog teksta. ${commonRules} ` +
        `Nivo objašnjenja: ${level}. ` +
        `Izvuci od 5 do 8 najvažnijih pojmova samo iz datog sadržaja. ` +
        `Za svaki pojam napiši tačno jedan red u formatu: Pojam — kratko objašnjenje. ` +
        `Ne piši uvod. Ne ponavljaj isti pojam. Ne objašnjavaj reči koje nisu važne za temu. ` +
        `Ne prevodi pojmove na drugi jezik i ne navodi sinonime na hrvatskom.`,

      quiz:
        `Zadatak: Napravi kratak kviz iz datog teksta. ${commonRules} ` +
        `Težina: ${level}. Dužina: ${length}. ` +
        `Format odgovora: ` +
        `napiši 5 pitanja ukupno: ` +
        `3 pitanja sa ponuđenim odgovorima A), B), C), ` +
        `i 2 kratka pitanja. ` +
        `Na kraju napiši odvojeno: Tačni odgovori. ` +
        `Ne dodaj preduga objašnjenja.`
    };

    const instruction = instructions[mode] || instructions.summarize;

    return {
      question: instruction,
      instruction: instruction,
      context: rawText,
      max_tokens: maxTokens,
      mode,
      level,
      length
    };
  }

  async function runTool(){
    const raw = (msgEl.value ?? '').toString().trim();

    if(!raw){
      stEl.textContent = 'Upiši ili nalepi tekst.';
      outEl.textContent = 'Tekst ili tema je prazna.';
      return;
    }

    const payload = buildRequestPayload(raw);

    stEl.textContent = 'Radim...';
    sendBtn.disabled = true;
    outEl.textContent = '⏳ Obrada u toku...';

    try{
      const res = await fetch("{{ route('api.ai.chat') }}", {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'Accept': 'application/json',
          'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        body: JSON.stringify(payload),
        credentials: 'same-origin'
      });

      const data = await res.json().catch(() => ({}));

      if(!res.ok || !data.ok){
        const msg = (data && (data.error || data.message || data.details))
          ? (data.error || data.message || data.details)
          : ('Greška. HTTP ' + res.status);

        outEl.textContent = msg + (data && data.details ? "\n\n" + data.details : "");
        stEl.textContent = 'Neuspešno.';
        return;
      }

      outEl.textContent = (data.answer || data.reply || '').trim() || '—';
      stEl.textContent = 'Gotovo.';
    }catch(e){
      outEl.textContent = 'Ne mogu da kontaktiram server.\n' + (e?.message || '');
      stEl.textContent = 'Greška.';
    }finally{
      sendBtn.disabled = false;
    }
  }

  sendBtn.addEventListener('click', runTool);

  msgEl.addEventListener('keydown', (e) => {
    if((e.ctrlKey || e.metaKey) && e.key === 'Enter') runTool();
  });

  clearBtn.addEventListener('click', () => {
    msgEl.value = '';
    outEl.textContent = '—';
    stEl.textContent = '';
    msgEl.focus();
  });

  copyBtn.addEventListener('click', async () => {
    const text = (outEl.textContent || '').trim();
    if(!text || text === '—') return;

    try{
      await navigator.clipboard.writeText(text);
      stEl.textContent = 'Kopirano.';
      setTimeout(() => stEl.textContent = '', 1200);
    }catch{
      stEl.textContent = 'Ne mogu da kopiram.';
    }
  });

  demoBtn.addEventListener('click', () => {
    setMode('summarize');
    msgEl.value =
`Sveti Sava (Rastko Nemanjić) imao je ključnu ulogu u oblikovanju duhovnog i državnog identiteta srednjovekovne Srbije. Njegovim delovanjem organizovana je samostalna crkvena uprava i ojačane veze između vere, obrazovanja i državnosti. Pored crkvenog rada, važna je i njegova diplomatska uloga, kao i osnivanje manastirskih centara koji su postali žarišta pismenosti i kulture.`;
    stEl.textContent = 'Ubačen primer — klikni „Pokreni alat”.';
  });
})();
</script>
@endsection