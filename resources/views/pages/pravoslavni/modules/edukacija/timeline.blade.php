@extends('layouts.site')

@section('title','Timeline — Edukacija')
@section('nav_edu','active')

@section('content')
<section class="section">
  <div class="container">

    <div style="display:flex;justify-content:space-between;gap:14px;align-items:flex-end;flex-wrap:wrap;margin-bottom:16px;">
      <div>
        <h1 style="margin:0;font-size:40px;letter-spacing:.2px;">Timeline</h1>
        <p class="muted" style="margin:8px 0 0;max-width:820px;line-height:1.7;">
          Vremenske linije ključnih događaja — Nemanjići, istorija SPC i Srbija pod Turcima.
          Ovo je “pravi” timeline sa linijom, markerima i karticama.
        </p>
      </div>

      <div style="display:flex;gap:10px;flex-wrap:wrap;">
        <a class="btn btn--ghost" href="{{ route('edukacija.index') }}">← Edukacija</a>
        <a class="btn btn--ghost" href="{{ route('edukacija.show','ucenje-interakcija') }}">Učenje i interakcija</a>
      </div>
    </div>

    {{-- TABOVI --}}
    <div class="ps-card" style="margin-bottom:14px;">
      <h3 style="margin:0 0 10px;">Izaberi liniju</h3>
      <div class="tl-tabs">
        <button class="tl-tab active" type="button" data-tab="nemanjici">Nemanjići</button>
        <button class="tl-tab" type="button" data-tab="spc">Istorija SPC</button>
        <button class="tl-tab" type="button" data-tab="turci">Srbija pod Turcima</button>
      </div>
      <div class="muted" style="margin-top:10px;line-height:1.7;">
        Na desktopu skroluješ horizontalno (kao infografik). Na telefonu se automatski slaže vertikalno.
      </div>
    </div>

    {{-- TIMELINES --}}
    @foreach($timelines as $key => $items)
      <section class="tl-panel {{ $key === 'nemanjici' ? 'show' : '' }}" data-panel="{{ $key }}">
        <div class="tl-wrap">
          <div class="tl-line" aria-hidden="true"></div>

          @foreach($items as $i => $it)
            @php $pos = ($i % 2 === 0) ? 'top' : 'bottom'; @endphp

            <article class="tl-item tl-{{ $pos }}">
              <div class="tl-dot" aria-hidden="true"></div>

              <div class="tl-card">
                <div class="tl-year">{{ $it['year'] }}</div>
                <div class="tl-title">{{ $it['title'] }}</div>
                <div class="tl-text">{{ $it['text'] }}</div>

                {{-- kasnije: link ka članku --}}
                <div class="tl-actions">
                  <span class="tl-chip">ključni događaj</span>
                  <span class="tl-chip tl-chip--muted">u izradi: “pročitaj više”</span>
                </div>
              </div>
            </article>
          @endforeach

        </div>
      </section>
    @endforeach

  </div>
</section>

<style>
/* panel show/hide */
.tl-panel{ display:none; }
.tl-panel.show{ display:block; }

/* tabs */
.tl-tabs{ display:flex; gap:10px; flex-wrap:wrap; }
.tl-tab{
  border:1px solid rgba(255,255,255,.14);
  background:rgba(0,0,0,.22);
  color:rgba(255,255,255,.92);
  padding:10px 12px;
  border-radius:999px;
  cursor:pointer;
  font-weight:900;
}
.tl-tab.active{
  border-color: rgba(197,162,74,.55);
  background: rgba(197,162,74,.12);
}

/* timeline canvas */
.tl-wrap{
  position:relative;
  border:1px solid rgba(255,255,255,.10);
  border-radius:18px;
  background:rgba(255,255,255,.03);
  padding:26px 18px;
  overflow:auto;               /* horizontal scroll */
  white-space:nowrap;
}

/* central line */
.tl-line{
  position:absolute;
  left:18px;
  right:18px;
  top:50%;
  height:2px;
  background:linear-gradient(90deg,
    rgba(197,162,74,.0),
    rgba(197,162,74,.55),
    rgba(197,162,74,.0)
  );
  transform:translateY(-50%);
  pointer-events:none;
}

/* items: inline-block along x axis */
.tl-item{
  position:relative;
  display:inline-block;
  width:320px;                 /* card width */
  min-height:260px;
  margin-right:22px;
  vertical-align:top;
}

/* dot anchored on the center line */
.tl-dot{
  position:absolute;
  left:50%;
  top:50%;
  width:14px;
  height:14px;
  border-radius:999px;
  transform:translate(-50%,-50%);
  background:rgba(197,162,74,.95);
  box-shadow:
    0 0 0 6px rgba(197,162,74,.12),
    0 0 0 1px rgba(255,255,255,.10);
}

/* connector */
.tl-item::before{
  content:"";
  position:absolute;
  left:50%;
  width:2px;
  background:rgba(197,162,74,.35);
  transform:translateX(-50%);
}
.tl-top::before{ top:22px; bottom:50%; }
.tl-bottom::before{ top:50%; bottom:22px; }

/* card positioning top/bottom */
.tl-card{
  width:100%;
  max-width:320px;
  padding:14px 14px 12px;
  border-radius:18px;
  border:1px solid rgba(255,255,255,.12);
  background:
    radial-gradient(1200px 260px at 0% 0%, rgba(197,162,74,.10), transparent 60%),
    rgba(0,0,0,.18);
  box-shadow: 0 18px 45px rgba(0,0,0,.35);
  white-space:normal; /* allow text wrap inside */
}

.tl-top .tl-card{ margin-top:0; }
.tl-bottom .tl-card{ margin-top:150px; } /* pushes card below center line */
.tl-top .tl-card{ margin-top:0; }
.tl-top{ padding-top:0; }
.tl-bottom{ padding-top:0; }

/* content */
.tl-year{
  font-weight:950;
  letter-spacing:.3px;
  color: rgba(197,162,74,.95);
  margin-bottom:6px;
}
.tl-title{ font-weight:950; margin-bottom:8px; }
.tl-text{ opacity:.90; line-height:1.75; font-size:14px; }

.tl-actions{ display:flex; gap:8px; flex-wrap:wrap; margin-top:10px; }
.tl-chip{
  font-size:12px;
  padding:6px 10px;
  border-radius:999px;
  border:1px solid rgba(197,162,74,.45);
  background: rgba(197,162,74,.10);
  opacity:.95;
}
.tl-chip--muted{
  border-color: rgba(255,255,255,.12);
  background: rgba(255,255,255,.04);
  opacity:.78;
}

/* nicer scrollbar (optional) */
.tl-wrap::-webkit-scrollbar{ height:10px; }
.tl-wrap::-webkit-scrollbar-thumb{
  background: rgba(255,255,255,.12);
  border-radius:999px;
}
.tl-wrap::-webkit-scrollbar-track{
  background: rgba(0,0,0,.15);
  border-radius:999px;
}

/* MOBILE: switch to vertical timeline */
@media (max-width: 780px){
  .tl-wrap{
    white-space:normal;
    overflow:visible;
    padding:18px 14px;
  }
  .tl-line{
    left:22px; right:auto;
    top:18px; bottom:18px;
    width:2px; height:auto;
    transform:none;
    background:linear-gradient(180deg,
      rgba(197,162,74,.0),
      rgba(197,162,74,.55),
      rgba(197,162,74,.0)
    );
  }
  .tl-item{
    display:block;
    width:auto;
    min-height:auto;
    margin:0 0 14px 0;
    padding-left:36px;
  }
  .tl-dot{
    left:22px;
    top:22px;
    transform:translate(-50%,-50%);
  }
  .tl-item::before{ display:none; }
  .tl-card{ max-width:none; }
  .tl-bottom .tl-card{ margin-top:0; }
}
</style>

<script>
document.querySelectorAll('.tl-tab').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.tl-tab').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    const tab = btn.getAttribute('data-tab');
    document.querySelectorAll('.tl-panel').forEach(p => p.classList.remove('show'));
    const panel = document.querySelector(`.tl-panel[data-panel="${tab}"]`);
    if(panel) panel.classList.add('show');

    // reset scroll when switching
    const wrap = panel?.querySelector('.tl-wrap');
    if (wrap) wrap.scrollLeft = 0;
  });
});
</script>
@endsection