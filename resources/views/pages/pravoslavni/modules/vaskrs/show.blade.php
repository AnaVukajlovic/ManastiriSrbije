@extends('layouts.site')

@section('title', 'Sve o Vaskrsu — Pravoslavni Svetionik')
@section('nav_pravoslavni', 'active')

@section('content')

<style>
/* 3. Osiguravamo da grid bude pravilno raspoređen */
  .vaskrs-layout {
    display: grid;
    grid-template-columns: 1fr 320px; /* Tekst uzima sve što ostane, sidebar fiksiran */
    gap: 40px;
    align-items: start;
    width: 100%;
  }

.vaskrs-article {
    max-width: 95%; /* Umesto 800px, dajemo mu slobodu */
    width: 100%;
    margin: 0 auto;
    padding: 0 10px; /* Mali padding da se ne lepi za ivice ekrana */
  }
/* 2. Sređivanje pasusa da budu ravnomerni */
  .vaskrs-section p {
    text-align: justify;
    text-justify: inter-word;
    line-height: 1.8;
    margin-bottom: 20px;
    color: rgba(255, 255, 255, 0.85);
    hyphens: auto;
    padding-right: 0px; /* Mali prostor da se ne lepi za sidebar */
    max-width: 100%;
  }

  /* Obostrano poravnanje i tipografija */
  .vaskrs-box p {
    text-align: justify;
    text-justify: inter-word;
    line-height: 1.8;
    margin-bottom: 24px;
    color: rgba(255, 255, 255, 0.85);
    hyphens: auto;
  }

  /* Naslovi sekcija */
  .vaskrs-h2 {
    color: var(--gold);
    margin: 40px 0 20px 0;
    font-size: 1.8rem;
    line-height: 1.2;
  }

  /* Izvori na dnu - puna širina */
  .vaskrs-sources {
    display: flex;
    flex-direction: column;
    gap: 12px;
    width: 100%;
  }
/* Sidebar karta */
  .vaskrs-sidecard {
    background: rgba(20, 12, 12, 0.7);
    border: 1px solid rgba(197, 162, 74, 0.15);
    border-radius: 20px;
    padding: 24px;
    margin-bottom: 24px;
  }

  .vaskrs-h3 {
    color: var(--gold);
    font-size: 0.9rem;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    margin-bottom: 18px;
    border-bottom: 1px solid rgba(197, 162, 74, 0.1);
    padding-bottom: 10px;
  }

  /* TOC Sadržaj - estetski unapređen */
  .vaskrs-toc {
    display: flex;
    flex-direction: column;
    gap: 8px;
  }

  .vaskrs-toc a {
    color: rgba(255, 255, 255, 0.7);
    text-decoration: none;
    font-size: 0.95rem;
    padding: 8px 12px;
    border-radius: 8px;
    transition: all 0.3s ease;
    border-left: 2px solid transparent;
  }

  .vaskrs-toc a:hover {
    color: #fff;
    background: rgba(197, 162, 74, 0.05);
    border-left: 2px solid var(--gold);
    padding-left: 18px; /* Efekat pomeranja pri hoveru */
  }

/* Stil za separator između sekcija */
.vaskrs-separator {
  display: flex;
  justify-content: center;
  align-items: center;
  margin: 10px auto;
  width: 100%;
  max-width: 600px;
  color: var(--gold);
}

.vaskrs-separator::before,
.vaskrs-separator::after {
  content: "";
  height: 1px;
  background: var(--gold);
  flex-grow: 1;
  opacity: 0.4;
}
.vaskrs-separator::before { margin-right: 20px; }
.vaskrs-separator::after { margin-left: 20px; }
/* Simbol u sredini */
.vaskrs-separator__icon {
  font-size: 1.2rem;
  line-height: 1;
}

  /* Akcije */
  .vaskrs-sideactions {
    margin-top: 20px;
    display: flex;
    gap: 10px;
  }
  /* Uklanjanje svih nasleđenih linija koje prave duplikat */
.vaskrs-section, 
.vaskrs-box {
  border: none !important;
  border-top: none !important;
  border-bottom: none !important;
}
  /* Responzivnost */
  @media (max-width: 992px) {
    .vaskrs-layout {
      grid-template-columns: 1fr;
      gap: 40px;
    }
    .vaskrs-aside {
      order: -1; /* Sidebar ide iznad teksta na mobilnom */
    }
  }
</style>

<section class="section vaskrs-show" id="top">
  <div class="container">

    <div class="crumbs vaskrs-crumbs">
      <a href="{{ route('vaskrs.index') }}">Vaskrs</a>
      <span class="muted">/</span>
      <span class="muted">Sve o Vaskrsu</span>
    </div>

    <header class="vaskrs-hero2">
      <div class="vaskrs-hero2__bg" style="background-image:url('{{ asset('images/hero/hero2.jpg') }}')"></div>
      <div class="vaskrs-hero2__overlay"></div>

      <div class="vaskrs-hero2__inner">
        <span class="vaskrs-pill2">Praznik nad praznicima</span>
        <h1 class="vaskrs-h1">Sve o Vaskrsu</h1>
        <p class="vaskrs-lead">
          Vaskrsenje Gospoda Isusa Hrista je središte hrišćanske vere: pobeda života nad smrću i izvor nade, radosti i mira.
        </p>
      </div>
    </header>

    <div class="vaskrs-layout">

      <article class="vaskrs-article">

        <section class="vaskrs-box" id="kalkulator">
          <div class="vaskrs-box__head">
            <h2 class="vaskrs-h2" style="margin-top:0">Izračunaj datum Vaskrsa</h2>
            <span class="vaskrs-chip2">Kalkulator</span>
          </div>

          <p class="muted" style="margin-top:6px">
            Unesi godinu i dobićeš datum Vaskrsa po pravoslavnom računanju (Julijanski pashalion → građanski kalendar).
          </p>

          <div class="vaskrs-form2">
            <div class="vaskrs-field2">
              <label for="ve-year2" class="muted">Godina</label>
              <input id="ve-year2" class="vaskrs-input2" type="number" min="1900" max="2200" value="{{ $year }}" inputmode="numeric" />
            </div>

            <button class="btn vaskrs-btn2" type="button" onclick="calcVaskrs2()">Izračunaj</button>

            <div class="vaskrs-result2" aria-live="polite">
              <span class="muted">Rezultat</span>
              <strong id="ve-date2" class="vaskrs-badge2">—</strong>
            </div>
          </div>
        </section>
        <section class="vaskrs-section" id="pasha">
          <h2 class="vaskrs-h2">Šta znači “Pasha”</h2>
          <p>
            Vaskrs se naziva i <strong>Pasha</strong>, što znači <em>prelaženje</em>:
            Crkva praznuje prelazak iz smrti u život, iz tame u svetlost, kao i povratak čoveka u prvobitno dostojanstvo života sa Bogom.
          </p>
          <p class="muted">
            Ovaj naglasak na “prelaženju” i oslobođenju čuva se u bogoslužbenim tekstovima i sinaksaru za Nedelju Pashe.
          </p>
        </section>

        <div class="vaskrs-separator"><span class="vaskrs-separator__icon">❧</span></div>

        <section class="vaskrs-section" id="praznik">
          <h2 class="vaskrs-h2">Zašto je Vaskrs “praznik nad praznicima”</h2>
          <p>
            U hrišćanskom iskustvu, Vaskrsenje nije samo uspomena na jedan događaj, već potvrda da smrt nema poslednju reč.
            Zato je Vaskrs kruna čitave crkvene godine: sve vodi ka njemu i sve iz njega dobija smisao.
          </p>
          <p>
            U prazničnom raspoloženju se često ponavlja psalamski uzvik:
            “Evo dana koji stvori Gospod, obradujmo se i uzveselimo se u njemu” — kao poziv da se radost ne zadrži samo u rečima,
            već da pređe u mirenje, dobrotu i novu snagu za život.
          </p>
        </section>

        <div class="vaskrs-separator"><span class="vaskrs-separator__icon">❧</span></div>

        <section class="vaskrs-section" id="vaskrsenje">
          <h2 class="vaskrs-h2">Kako Crkva opisuje događaj Vaskrsenja</h2>
          <p>
            U sinaksarskom pripovedanju, Vaskrsenje se objavljuje u noći, uz potres zemlje i anđelsku vest kod groba.
            Žene mironosice dolaze na grob, a radosna vest se prenosi učenicima.
            Ovaj tok događaja nije “detalj radi detalja”, nego pedagoški put: od straha ka veri, od tuge ka radosti.
          </p>
          <p>
            Posebno je naglašeno da se Vaskrsenje doživljava kao prekid neprijateljstva i ponovno sjedinjenje sa Bogom,
            što se vidi i u prazničnom pozdravu i celivanju među vernicima.
          </p>
        </section>

        <div class="vaskrs-separator"><span class="vaskrs-separator__icon">❧</span></div>

        <section class="vaskrs-section" id="pozdrav">
          <h2 class="vaskrs-h2">Praznični pozdrav</h2>
          <div class="vaskrs-quote2">
            <div class="vaskrs-quote2__main">
              <strong>Hristos vaskrse!</strong> <span class="muted">—</span> <strong>Vaistinu vaskrse!</strong>
            </div>
            <div class="vaskrs-quote2__sub muted">
              Kratko ispovedanje vere i deljenje radosti Vaskrsenja među ljudima.
            </div>
          </div>
        </section>

        <div class="vaskrs-separator"><span class="vaskrs-separator__icon">❧</span></div>

        <section class="vaskrs-section" id="jaja">
          <h2 class="vaskrs-h2">Farbanje jaja i simbolika crvene boje</h2>
          <p>
            Za Vaskrs je u našem narodu snažno vezan običaj farbanja i darivanja jaja.
            Jaje je simbol života i obnavljanja, a <strong>crveno vaskršnje jaje</strong> se posebno ističe kao znak radosti.
          </p>
          <p>
            Crvena boja se tumači kao podsećanje na nevino prolivenu krv Hristovu na Golgoti, ali istovremeno i kao boja Vaskrsenja —
            zato crveno jaje u sebi spaja i stradanje i pobedu života.
          </p>
        </section>

        <div class="vaskrs-separator"><span class="vaskrs-separator__icon">❧</span></div>

        <section class="vaskrs-section" id="zivot">
          <h2 class="vaskrs-h2">Kako da se Vaskrs živi</h2>
          <p>
            Vaskrsna radost nije samo svečanost “jednog dana”.
            Najlepše se vidi kada praznik donese promenu: da čovek lakše prašta, lakše se miri,
            da odustaje od sujete i vraća pažnju na ono što je stvarno važno.
          </p>
        </section>

      </article>

      <aside class="vaskrs-aside">
        <div class="vaskrs-sidecard">
          <h3 class="vaskrs-h3">Brzi sadržaj</h3>
          <nav class="vaskrs-toc">
            <a href="#kalkulator">Kalkulator datuma</a>
            <a href="#pasha">Šta znači “Pasha”</a>
            <a href="#praznik">Praznik nad praznicima</a>
            <a href="#vaskrsenje">Događaj Vaskrsenja</a>
            <a href="#pozdrav">Praznični pozdrav</a>
            <a href="#jaja">Farbanje jaja</a>
            <a href="#zivot">Kako se živi Vaskrs</a>
          </nav>
          <div class="vaskrs-sideactions">
            <a class="btn btn--ghost" href="#top">Nazad na vrh</a>
            <a class="btn btn--ghost" href="{{ route('vaskrs.index') }}">← Nazad</a>
          </div>
        </div>

        <div class="vaskrs-sidecard" style="padding: 22px; background: rgba(22, 14, 13, 0.95); border: 1px solid rgba(197, 162, 74, 0.3); border-radius: 18px;">
          <h3 class="vaskrs-h3" style="color: var(--gold); margin: 0 0 12px 0; font-size: 1.05rem;">📜 Црквени извори</h3>
          <p class="muted" style="margin:0 0 12px; font-size: 0.85rem; line-height: 1.5;">Текст је приређен на основу светоотачких и званичних црквених извора:</p>
          <div class="vaskrs-sources">
            <a class="vaskrs-source" href="https://svetosavlje.rs/vaskrs/" target="_blank" rel="noopener" style="display: block; padding: 10px 12px; background: rgba(255,255,255,0.03); border: 1px solid rgba(197,162,74,0.2); border-radius: 12px; margin-bottom: 8px; text-decoration: none;">
              <span class="vaskrs-source__title" style="display: block; font-weight: 700; color: #fff; font-size: 0.86rem;">Светосавље — „Васкрс”</span>
              <span class="vaskrs-source__meta muted" style="font-size: 0.75rem; color: #c5a24a;">svetosavlje.rs ↗</span>
            </a>
            <a class="vaskrs-source" href="https://www.tvhram.rs/vesti/praznici/4030/vaskrsenje-gospoda-naseg-isusa-hrista" target="_blank" rel="noopener" style="display: block; padding: 10px 12px; background: rgba(255,255,255,0.03); border: 1px solid rgba(197,162,74,0.2); border-radius: 12px; margin-bottom: 8px; text-decoration: none;">
              <span class="vaskrs-source__title" style="display: block; font-weight: 700; color: #fff; font-size: 0.86rem;">ТВ Храм — „Васкрсење Господа Исуса Христа”</span>
              <span class="vaskrs-source__meta muted" style="font-size: 0.75rem; color: #c5a24a;">tvhram.rs ↗</span>
            </a>
            <div style="padding: 8px 10px; font-size: 0.8rem; color: rgba(255,255,255,0.7); line-height: 1.4; border-top: 1px dashed rgba(197,162,74,0.2); margin-top: 8px;">
              ❧ Др Лазар Мирковић: <em>„Православна Литургика”</em>, Београд
            </div>
          </div>
        </div>
      </aside>

    </div>
  </div>
</section>

<script>
(function () {
  function orthodoxEasterDate(year) {
    const a = year % 4;
    const b = year % 7;
    const c = year % 19;
    const d = (19 * c + 15) % 30;
    const e = (2 * a + 4 * b - d + 34) % 7;
    const month = Math.floor((d + e + 114) / 31);
    const day = ((d + e + 114) % 31) + 1;
    const shift = (year >= 2100) ? 14 : 13;
    const dt = new Date(Date.UTC(year, month - 1, day));
    dt.setUTCDate(dt.getUTCDate() + shift);
    return dt;
  }
  function fmtSR(dt) {
    const dd = String(dt.getUTCDate()).padStart(2, '0');
    const mm = String(dt.getUTCMonth() + 1).padStart(2, '0');
    const yyyy = dt.getUTCFullYear();
    return `${dd}.${mm}.${yyyy}.`;
  }
  function setText(id, text) {
    const el = document.getElementById(id);
    if (el) el.textContent = text;
  }
  function calcVaskrs2() {
    const input = document.getElementById('ve-year2');
    const y = parseInt((input && input.value) ? input.value : '0', 10);
    if (!y || y < 1900 || y > 2200) {
      setText('ve-date2', 'Unesi godinu 1900–2200.');
      return;
    }
    const dt = orthodoxEasterDate(y);
    setText('ve-date2', fmtSR(dt));
  }
  window.calcVaskrs2 = calcVaskrs2;
  const yearInput = document.getElementById('ve-year2');
  if (yearInput) {
    yearInput.addEventListener('keydown', function (e) {
      if (e.key === 'Enter') { e.preventDefault(); calcVaskrs2(); }
    });
  }
  calcVaskrs2();
})();
</script>
@endsection