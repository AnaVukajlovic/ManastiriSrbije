@extends('layouts.site')

@section('title','AI — Edukacija')
@section('nav_edu','active')

@section('content')
<section class="section">
  <div class="container">

    {{-- Header --}}
    <div style="display:flex;justify-content:space-between;gap:14px;align-items:flex-end;flex-wrap:wrap;margin-bottom:16px;">
      <div>
        <h1 style="margin:0;font-size:40px;letter-spacing:.2px;">AI radionica</h1>
        <p class="muted" style="margin:8px 0 0;max-width:860px;line-height:1.75;">
          Ovde AI nije “samo pitanja”. Koristi ga kao alat: sažetak, objašnjenje, glosar, kviz — posebno korisno za učenje istorije,
          SPC i kulture. Model: <strong>{{ env('OLLAMA_MODEL','qwen2.5:7b') }}</strong>
        </p>
      </div>

      <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a class="btn btn--ghost" href="{{ route('edukacija.index') }}">← Edukacija</a>
        <a class="btn btn--ghost" href="{{ route('edukacija.show','ucenje-interakcija') }}">Učenje i interakcija</a>
      </div>
    </div>

    <div class="ai-grid">

      {{-- LEFT: Inputs --}}
      <div class="ps-card ai-card">
        <div class="ai-head">
          <div>
            <h3 style="margin:0;">Izaberi alat</h3>
            <div class="muted" style="margin-top:6px;">Nalepi tekst ili napiši temu — AI će obraditi po izabranom režimu.</div>
          </div>
          <span class="ai-pill">Radionica</span>
        </div>

        <div class="ai-modes" role="tablist" aria-label="AI alati">
          <button class="ai-mode active" type="button" data-mode="summarize" role="tab">Sažmi</button>
          <button class="ai-mode" type="button" data-mode="explain" role="tab">Objasni</button>
          <button class="ai-mode" type="button" data-mode="glossary" role="tab">Glosar</button>
          <button class="ai-mode" type="button" data-mode="quiz" role="tab">Kviz</button>
        </div>

        <div class="ai-row">
          <label class="muted" for="ai-level">Nivo objašnjenja</label>
          <select id="ai-level" class="ai-select">
            <option value="A2">A2 — vrlo jednostavno</option>
            <option value="B1" selected>B1 — školski nivo</option>
            <option value="B2">B2 — detaljnije</option>
          </select>

          <label class="muted" for="ai-len">Dužina</label>
          <select id="ai-len" class="ai-select">
            <option value="short">kratko</option>
            <option value="medium" selected>srednje</option>
            <option value="long">duže</option>
          </select>
        </div>

        <textarea id="ai-msg" rows="9" class="ai-input"
          placeholder="Nalepi pasus iz edukacije ili napiši temu (npr. 'Sveti Sava i autokefalnost 1219', 'Raška škola', 'SPC pod Turcima')..."></textarea>

        <div class="ai-actions">
          <button class="btn" id="ai-send" type="button">Pokreni alat</button>
          <button class="btn btn--ghost" id="ai-clear" type="button">Očisti</button>
          <span class="muted" id="ai-status" style="margin-left:auto;"></span>
        </div>

        <div class="ai-hint">
          <strong>Tip:</strong> Za najbolje rezultate nalepi 1–3 pasusa (do ~1500 reči). Za kviz, nalepi lekciju — AI napravi pitanja.
        </div>
      </div>

      {{-- RIGHT: Output --}}
      <div class="ps-card ai-card">
        <div class="ai-head">
          <div>
            <h3 style="margin:0;">Rezultat</h3>
            <div class="muted" style="margin-top:6px;">Možeš da kopiraš i ubaciš u beleške / pripremu za kviz.</div>
          </div>

          <div style="display:flex;gap:10px;flex-wrap:wrap;">
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
/* layout */
.ai-grid{
  display:grid;
  grid-template-columns: 1.05fr .95fr;
  gap:18px;
}
@media (max-width: 980px){
  .ai-grid{ grid-template-columns:1fr; }
}

/* card polish */
.ai-card{
  position:relative;
  overflow:hidden;
}
.ai-card::before{
  content:"";
  position:absolute;
  inset:-80px -80px auto auto;
  width:240px;height:240px;
  background:radial-gradient(circle at 30% 30%, rgba(197,162,74,.18), transparent 60%);
  pointer-events:none;
  filter:blur(0px);
}
.ai-head{
  display:flex;
  align-items:flex-start;
  justify-content:space-between;
  gap:12px;
  margin-bottom:12px;
}
.ai-pill{
  font-size:12px;
  font-weight:900;
  letter-spacing:.2px;
  padding:6px 10px;
  border-radius:999px;
  border:1px solid rgba(197,162,74,.45);
  background: rgba(197,162,74,.10);
  opacity:.95;
}

/* modes */
.ai-modes{
  display:flex;
  gap:10px;
  flex-wrap:wrap;
  margin:10px 0 12px;
}
.ai-mode{
  border:1px solid rgba(255,255,255,.14);
  background:rgba(0,0,0,.22);
  color:rgba(255,255,255,.92);
  padding:10px 12px;
  border-radius:999px;
  cursor:pointer;
  font-weight:950;
  transition: transform .08s ease, border-color .12s ease;
}
.ai-mode:hover{ transform: translateY(-1px); }
.ai-mode.active{
  border-color: rgba(197,162,74,.55);
  background: rgba(197,162,74,.12);
}

/* selects row */
.ai-row{
  display:grid;
  grid-template-columns: auto 1fr auto 1fr;
  gap:10px 12px;
  align-items:center;
  margin-bottom:10px;
}
@media (max-width: 520px){
  .ai-row{ grid-template-columns: 1fr; }
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
  border-color: rgba(197,162,74,.55);
  box-shadow: 0 0 0 4px rgba(197,162,74,.10);
}

/* textarea */
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
  min-height:170px;
}
.ai-input:focus{
  border-color: rgba(197,162,74,.55);
  box-shadow: 0 0 0 4px rgba(197,162,74,.10);
}

/* actions */
.ai-actions{
  display:flex;
  gap:10px;
  align-items:center;
  margin-top:10px;
  flex-wrap:wrap;
}
.ai-hint{
  margin-top:12px;
  border:1px solid rgba(255,255,255,.10);
  background:rgba(255,255,255,.03);
  border-radius:14px;
  padding:10px 12px;
  line-height:1.7;
  opacity:.9;
}

/* output */
.ai-output{
  border:1px solid rgba(255,255,255,.10);
  background:rgba(0,0,0,.16);
  border-radius:16px;
  padding:12px 12px;
  min-height:320px;
  white-space:pre-wrap;
  line-height:1.75;
  opacity:.94;
}
.ai-footer{
  margin-top:10px;
  line-height:1.6;
  opacity:.75;
}
</style>

<script>
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
  document.querySelectorAll('.ai-mode').forEach(b => b.classList.remove('active'));
  document.querySelector(`.ai-mode[data-mode="${newMode}"]`)?.classList.add('active');

  const placeholders = {
    summarize: "Nalepi pasus/lekciju koju želiš da sažmeš (npr. tekst o Raškoj školi ili SPC pod Turcima)...",
    explain:   "Upiši temu ili nalepi pasus koji želiš da AI objasni (npr. 'Autokefalnost 1219' ili 'Pasha')...",
    glossary:  "Nalepi tekst (ili napiši temu) — AI izvlači pojmove i objašnjava ih kao mali rečnik...",
    quiz:      "Nalepi lekciju — AI će napraviti kviz (pitanja + tačni odgovori + kratko objašnjenje)..."
  };
  msgEl.placeholder = placeholders[newMode] || msgEl.placeholder;
}

document.querySelectorAll('.ai-mode').forEach(btn => {
  btn.addEventListener('click', () => setMode(btn.dataset.mode));
});

function buildRequestPayload(rawText){
  const level = levelEl.value;
  const length = lenEl.value;

  // UI režimi -> instrukcije (da AI bude alat, ne Q&A)
  const instructions = {
    summarize:
      `Zadatak: SAŽMI dati tekst. Napiši ${length} sažetak na srpskom. ` +
      `Dodaj: 1) kratki sažetak, 2) 6-10 ključnih tačaka, 3) 3 pojma koje treba zapamtiti.`,
    explain:
      `Zadatak: OBJASNI temu/tekst. Piši nivo ${level}. Napiši ${length} objašnjenje. ` +
      `Dodaj: analogiju (ako može), zatim 5 ključnih tačaka i 3 pitanja za proveru razumevanja.`,
    glossary:
      `Zadatak: GLOSAR iz datog teksta. Izvuci 10-16 pojmova (zavisno od teksta) i objasni ih nivo ${level}. ` +
      `Format: Pojam — objašnjenje (1-3 rečenice). Na kraju dodaj 5 kratkih "poveži pojmove" asocijacija.`,
    quiz:
      `Zadatak: NAPRAVI KVIZ iz datog teksta. Napravi 8 pitanja: ` +
      `5 na zaokruživanje (A/B/C/D) + 3 kratka pitanja. ` +
      `Za svako pitanje daj tačan odgovor i 1-2 rečenice objašnjenja. ` +
      `Težina: ${level}.`
  };

  return {
    message: rawText,
    mode,
    level,
    length,
    instruction: instructions[mode] || instructions.summarize
  };
}

async function runTool(){
  const raw = (msgEl.value || '').trim();
  if(!raw){ stEl.textContent = 'Upiši ili nalepi tekst.'; return; }

  stEl.textContent = 'Radim...';
  sendBtn.disabled = true;
  outEl.textContent = '⏳ Obrada u toku...';

  try{
    const res = await fetch("{{ route('edukacija.ai.chat') }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
      },
      body: JSON.stringify(buildRequestPayload(raw))
    });

    const data = await res.json().catch(() => ({}));

    if(!res.ok || !data.ok){
      outEl.textContent = (data && (data.error || data.details))
        ? (data.error + (data.details ? "\n" + data.details : ""))
        : 'Greška.';
      stEl.textContent = 'Neuspešno.';
    } else {
      outEl.textContent = data.answer || '—';
      stEl.textContent = 'Gotovo.';
    }
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
  stEl.textContent = 'Ubačen primer — klikni “Pokreni alat”.';
});
</script>
@endsection