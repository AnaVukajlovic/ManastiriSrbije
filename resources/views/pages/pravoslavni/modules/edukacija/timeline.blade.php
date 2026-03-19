@extends('layouts.site')

@section('title', 'Timeline — Edukacija')
@section('nav_edukacija', 'active')

@section('content')
<style>
/* ===== TIMELINE HERO FIX ===== */

.tl-page .container{
  width:min(1560px, calc(100% - 34px)) !important;
  max-width:none !important;
}

.tl-page .tl-hero{
  display:grid !important;
  grid-template-columns:1fr auto !important;
  grid-template-areas:
    "kicker actions"
    "title  actions"
    "text   text"
    "chips  chips" !important;
  gap:14px 24px !important;
  align-items:start !important;

  margin-bottom:24px !important;
  padding:26px !important;
  border-radius:26px !important;
  border:1px solid rgba(197,162,74,.18) !important;
  background:
    radial-gradient(circle at top left, rgba(197,162,74,.08), transparent 28%),
    linear-gradient(180deg, rgba(255,255,255,.025), rgba(255,255,255,.012)),
    rgba(20,12,12,.78) !important;
  box-shadow:0 18px 42px rgba(0,0,0,.24) !important;
}

.tl-page .tl-hero__left{
  display:contents !important;
}

.tl-page .tl-kicker{
  grid-area:kicker !important;
  display:inline-flex !important;
  align-items:center !important;
  justify-content:center !important;
  width:max-content !important;
  padding:8px 12px !important;
  margin:0 !important;
  border-radius:999px !important;
  border:1px solid rgba(197,162,74,.22) !important;
  background:rgba(197,162,74,.08) !important;
  color:#e2c26a !important;
  font-size:.86rem !important;
  font-weight:700 !important;
  line-height:1 !important;
}

.tl-page .tl-hero h1{
  grid-area:title !important;
  margin:0 !important;
  font-size:clamp(1.75rem, 2.35vw, 2.35rem) !important;
  line-height:1.06 !important;
  letter-spacing:-.02em !important;
  font-weight:800 !important;
  color:#c5a24a !important;
  text-shadow:0 0 14px rgba(197,162,74,.18) !important;
}

.tl-page .tl-hero__left > p{
  grid-area:text !important;
  display:block !important;
  width:100% !important;
  max-width:none !important;
  margin:0 !important;
  color:rgba(255,255,255,.86) !important;
  font-size:1rem !important;
  line-height:1.9 !important;
  text-align:justify !important;
  text-justify:inter-word !important;
}

.tl-page .tl-hero__chips{
  grid-area:chips !important;
  display:flex !important;
  flex-wrap:wrap !important;
  gap:10px !important;
  margin:0 !important;
}

.tl-page .tl-hero__chips span{
  display:inline-flex !important;
  align-items:center !important;
  justify-content:center !important;
  padding:8px 12px !important;
  border-radius:999px !important;
  border:1px solid rgba(255,255,255,.08) !important;
  background:rgba(255,255,255,.03) !important;
  color:#f0d78f !important;
  font-size:.84rem !important;
  font-weight:700 !important;
  line-height:1 !important;
}

.tl-page .tl-hero__right{
  grid-area:actions !important;
  display:flex !important;
  gap:10px !important;
  flex-wrap:wrap !important;
  justify-content:flex-end !important;
  align-self:start !important;
}

.tl-page .tl-back{
  display:inline-flex !important;
  align-items:center !important;
  justify-content:center !important;
  min-height:44px !important;
  padding:0 16px !important;
  border-radius:14px !important;
  text-decoration:none !important;
  font-weight:700 !important;
  color:#fff !important;
  border:1px solid rgba(255,255,255,.10) !important;
  background:rgba(255,255,255,.03) !important;
  transition:all .2s ease !important;
}

.tl-page .tl-back:hover{
  transform:translateY(-1px) !important;
  border-color:rgba(197,162,74,.35) !important;
  background:rgba(197,162,74,.10) !important;
  color:#f0d78f !important;
}

.tl-page .tl-switcher__note{
  width:100% !important;
  max-width:none !important;
  text-align:justify !important;
  text-justify:inter-word !important;
  line-height:1.9 !important;
  color:rgba(255,255,255,.84) !important;
}

@media (max-width: 900px){
  .tl-page .tl-hero{
    grid-template-columns:1fr !important;
    grid-template-areas:
      "kicker"
      "title"
      "actions"
      "text"
      "chips" !important;
  }

  .tl-page .tl-hero__right{
    justify-content:flex-start !important;
  }
}

@media (max-width: 640px){
  .tl-page .container{
    width:min(100%, calc(100% - 22px)) !important;
  }

  .tl-page .tl-hero{
    padding:18px !important;
    border-radius:22px !important;
  }

  .tl-page .tl-hero h1{
    font-size:clamp(1.55rem, 7vw, 1.95rem) !important;
  }

  .tl-page .tl-back{
    width:100% !important;
  }
}
</style>

<section class="section tl-page">
  <div class="container">

    <div class="tl-hero">
      <div class="tl-hero__left">
        <span class="tl-kicker">Istorijski pregled</span>
        <h1>Vremenska linija</h1>
        <p>
          Pregled najvažnijih događaja kroz tri velike celine: dinastija Nemanjić, istorija Srpske pravoslavne crkve
          i period Srbije pod turskom vlašću. Posebno mesto zauzimaju Nemanjići, jer njihova vladavina predstavlja
          jedno od najznačajnijih razdoblja u srpskoj srednjovekovnoj istoriji, obeleženo jačanjem države, razvojem
          duhovnosti, zadužbinarstvom i oblikovanjem trajnog kulturnog i verskog identiteta srpskog naroda.
        </p>

        <div class="tl-hero__chips">
          <span>Nemanjići</span>
          <span>istorija SPC</span>
          <span>osmanski period</span>
          <span>dodatna objašnjenja</span>
        </div>
      </div>

      <div class="tl-hero__right">
        <a href="{{ route('edukacija.index') }}" class="tl-back">← Edukacija</a>
        <a href="{{ route('edukacija.ucenje-interakcija') }}" class="tl-back">Učenje i interakcija</a>
      </div>
    </div>

    <div class="tl-switcher">
      <h3>Izaberi oblast</h3>

      <div class="tl-tabs">
        <button class="tl-tab active" type="button" data-target="nemanjici">Nemanjići</button>
        <button class="tl-tab" type="button" data-target="spc">Istorija SPC</button>
        <button class="tl-tab" type="button" data-target="turci">Srbija pod Turcima</button>
      </div>

      <p class="tl-switcher__note">
        Na vremenskoj liniji prikazani su najvažniji događaji sa kratkim objašnjenjem.
        Klikom na dugme <strong>„Objasni”</strong> dobijaš dodatni, sažeti prikaz događaja i njegovog značaja.
      </p>
    </div>

    @foreach($timelines as $key => $items)
      <div class="tl-panel {{ $loop->first ? 'active' : '' }}" id="tl-{{ $key }}">
        <div class="tl-vertical">
          @foreach($items as $index => $item)
            <article class="tl-item">
              <div class="tl-item__year">{{ $item['year'] }}</div>

              <div class="tl-item__dot"></div>

              <div class="tl-item__card">
                <div class="tl-item__tag">{{ $item['tag'] ?? 'događaj' }}</div>
                <h3>{{ $item['title'] }}</h3>

                <p class="tl-item__summary">{{ $item['text'] }}</p>

                <div class="tl-item__actions">
                  <button
                    type="button"
                    class="tl-ai-btn"
                    data-year="{{ $item['year'] }}"
                    data-title="{{ $item['title'] }}"
                    data-text="{{ $item['text'] }}"
                    data-context="{{ $item['context'] ?? '' }}"
                    data-area="{{ $key }}"
                    data-target="tl-ai-{{ $key }}-{{ $index }}"
                  >
                    Objasni
                  </button>
                </div>

                <div class="tl-ai-box" id="tl-ai-{{ $key }}-{{ $index }}" hidden>
                  <div class="tl-ai-box__label">Detaljnije objašnjenje</div>
                  <div class="tl-ai-box__content"></div>
                </div>
              </div>
            </article>
          @endforeach
        </div>
      </div>
    @endforeach

  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const tabs = document.querySelectorAll('.tl-tab');
  const panels = document.querySelectorAll('.tl-panel');

  tabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const target = tab.dataset.target;

      tabs.forEach(t => t.classList.remove('active'));
      panels.forEach(p => p.classList.remove('active'));

      tab.classList.add('active');

      const panel = document.getElementById('tl-' + target);
      if (panel) panel.classList.add('active');
    });
  });

  const aiButtons = document.querySelectorAll('.tl-ai-btn');

  aiButtons.forEach(btn => {
    btn.addEventListener('click', async () => {
      if (btn.disabled) return;

      const year = (btn.dataset.year || '').trim();
      const title = (btn.dataset.title || '').trim();
      const text = (btn.dataset.text || '').trim();
      const area = (btn.dataset.area || '').trim();
      const targetId = btn.dataset.target;
      const extraContext = (btn.dataset.context || '').trim();

      const box = document.getElementById(targetId);
      if (!box) return;

      const content = box.querySelector('.tl-ai-box__content');
      if (!content) return;

      box.hidden = false;
      content.textContent = 'Priprema se dodatno objašnjenje...';

      btn.disabled = true;
      btn.classList.add('is-loading');

      let oblastNaziv = 'istorija';
      if (area === 'nemanjici') oblastNaziv = 'dinastija Nemanjić';
      if (area === 'spc') oblastNaziv = 'istorija Srpske pravoslavne crkve';
      if (area === 'turci') oblastNaziv = 'Srbija pod osmanskom vlašću';

      try {
        const instruction = 'Objasni događaj sažeto i jasno na osnovu dostavljenog konteksta.';
        const context =
          'OBLAST: ' + oblastNaziv + '\n' +
          'GODINA: ' + year + '\n' +
          'DOGAĐAJ: ' + title + '\n' +
          'KRATAK OPIS: ' + text + '\n' +
          (extraContext ? ('DODATNI KONTEKST: ' + extraContext) : '');

        const response = await fetch('{{ route('edukacija.ai.chat') }}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
          },
          body: JSON.stringify({
            question: instruction,
            instruction: instruction,
            context: context,
            max_tokens: 160,
            mode: 'timeline_explain'
          })
        });

        const data = await response.json().catch(() => ({}));

        const aiText =
          (data.answer || data.reply || data.message || data.response || '').trim();

        content.innerHTML = aiText
          ? aiText.replace(/\n/g, '<br>')
          : 'Dodatno objašnjenje trenutno nije dostupno.';
      } catch (error) {
        content.textContent = 'Došlo je do greške pri komunikaciji sa modulom za objašnjenje.';
      } finally {
        btn.disabled = false;
        btn.classList.remove('is-loading');
      }
    });
  });
});
</script>
@endsection