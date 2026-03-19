@extends('layouts.site')

@section('title', 'Osnovni koncepti — Pravoslavni Svetionik')
@section('nav_pravoslavni', 'active')

@section('content')

<!-- =======================
     OSNOVNI KONCEPTI — WOW (RESPONSIVE)
     ======================= -->

<header class="ps-hero">
  <div class="container ps-hero__inner">

    <div class="ps-hero__badge">
      <span class="ps-hero__dot"></span>
      Osnovni koncepti pravoslavlja
    </div>

    <h1 class="ps-hero__title">Vera koja se živi</h1>

    <p class="ps-hero__lead">
      Pravoslavlje nije samo znanje o Bogu, već život u Crkvi: liturgija, molitva, post, ljubav,
      pokajanje i nada Vaskrsenja — korak po korak, svakog dana.
    </p>

    <div class="ps-hero__quick">

      <div class="ps-quickgrid">
        <a class="ps-quick" href="#trosjstvo">
          <div class="ps-quick__k">3</div>
          <div class="ps-quick__v">Ličnosti — jedan Bog</div>
        </a>

        <a class="ps-quick" href="#zapovesti">
          <div class="ps-quick__k">10</div>
          <div class="ps-quick__v">Zapovesti — put savesti</div>
        </a>

        <a class="ps-quick" href="#liturgija">
          <div class="ps-quick__k">1</div>
          <div class="ps-quick__v">Srce svega — Liturgija</div>
        </a>
      </div>

      <div class="ps-hero__toc">
        <div class="ps-toc__label">Sadržaj</div>

        <div class="ps-toc">
          <a class="ps-chip" href="#sta-je-pravoslavlje">Šta je pravoslavlje</a>
          <a class="ps-chip" href="#ispovedanje-vere">Ispovedanje vere</a>
          <a class="ps-chip" href="#crkva-i-predanje">Crkva i Predanje</a>
          <a class="ps-chip" href="#liturgija">Liturgija</a>
          <a class="ps-chip" href="#molitva">Molitva</a>
          <a class="ps-chip" href="#zapovesti">10 zapovesti</a>
          <a class="ps-chip" href="#post">Post</a>
          <a class="ps-chip" href="#ispovest-pricesce">Ispovest &amp; Pričešće</a>
        </div>
      </div>

    </div><!-- /.ps-hero__quick -->

  </div><!-- /.container.ps-hero__inner -->
</header>

<section class="ps-wrap ps-wow">
  <div class="container">

    <!-- 3 ključne poruke -->
    <section class="ps-grid ps-grid--3">
      <article class="ps-card">
        <div class="ps-card__icon">✦</div>
        <h3 class="ps-card__title">Vera je put</h3>
        <p class="ps-card__text">
          Pravoslavni život nije “jedan korak i gotovo”. To je trajno učenje:
          kako da srce omekša, kako da se misli pročiste i kako da ljubav postane navika.
        </p>
      </article>

      <article class="ps-card">
        <div class="ps-card__icon">☦</div>
        <h3 class="ps-card__title">Crkva je zajednica</h3>
        <p class="ps-card__text">
          Vera se ne živi samo privatno. Liturgija, molitva i praznici oblikuju ritam života
          i podsećaju da smo pozvani da budemo jedno telo u Hristu.
        </p>
      </article>

      <article class="ps-card">
        <div class="ps-card__icon">♡</div>
        <h3 class="ps-card__title">Cilj je zajednica sa Bogom</h3>
        <p class="ps-card__text">
          Spasenje nije “spisak ispunjenih pravila”, već odnos: poverenje, pokajanje,
          zahvalnost i rast u blagodatnom životu.
        </p>
      </article>
    </section>

    <!-- ŠTA JE PRAVOSLAVLJE -->
    <section id="pravoslavlje" class="ps-asec ps-asec--soft">
      <div class="ps-asec__head">
        <h2>Šta je pravoslavlje</h2>
        <p class="ps-sub">
          Pravoslavlje je život u Crkvi — vera koja se prepoznaje po plodovima:
          miru, smirenju, istrajnosti i ljubavi.
        </p>
      </div>

      <div class="ps-split">
        <div>
          <p class="ps-ap">
            Pravoslavlje nije samo “učenje o Bogu”, nego način na koji čovek uči da živi pred Bogom.
            Zbog toga je pravoslavna vera istovremeno i duhovna i praktična: uči nas kako da budemo
            istinitiji, strpljiviji, blagiji, sposobniji da praštamo i da se radujemo dobru.
          </p>
          <p class="ps-ap">
            U središtu pravoslavlja je Hristos: Bog koji je postao čovek da bi čovek mogao da se
            približi Bogu. Njegovo stradanje i Vaskrsenje nisu samo istorijski događaji, već izvor nade:
            smrt nije kraj, zlo nema poslednju reč, a čovek je pozvan na novi početak.
          </p>

          <div class="ps-callout ps-callout--note">
            <strong>Za početnike:</strong> ne pokušavaj da “uhvatiš sve odjednom”.
            Kreni od malog: nedeljna Liturgija, kratka molitva, mali napor da ne osuđuješ i da praštaš.
          </div>
        </div>

        <aside class="ps-panel">
          <h3 class="ps-panel__title">U praksi, pravoslavlje se živi kroz</h3>
          <ul class="ps-check">
            <li>bogosluženje i Liturgiju</li>
            <li>molitvu (ličnu i zajedničku)</li>
            <li>post (telo + duša)</li>
            <li>pokajanje i ispovest</li>
            <li>milosrđe i dela ljubavi</li>
            <li>praznike, postove i crkveni ritam</li>
          </ul>
        </aside>
      </div>
    </section>

    <!-- ISPOVEDANJE VERE -->
    <section id="vera" class="ps-asec">
      <div class="ps-asec__head">
        <h2>Ispovedanje vere</h2>
        <p class="ps-sub">
          Simvol vere sažima srce hrišćanskog učenja: ko je Bog, ko je Hristos,
          šta je Crkva i u šta se nadamo.
        </p>
      </div>

      <div class="ps-grid ps-grid--2">
        <article class="ps-card ps-card--flat">
          <h3 class="ps-card__title">Sveta Trojica</h3>
          <p class="ps-card__text">
            Pravoslavlje ispoveda jednog Boga u tri Ličnosti: Oca, Sina i Svetoga Duha.
            To nije matematička zagonetka, već tajna ljubavi: Bog nije usamljenost,
            nego zajednica. Zato i čovek najdublje postaje čovek kada uči da voli, služi i gradi mir.
          </p>
          <div class="ps-mini">
            <span class="ps-pill">Otac — izvor</span>
            <span class="ps-pill">Sin — spasenje</span>
            <span class="ps-pill">Duh — osvećenje</span>
          </div>
        </article>

        <article class="ps-card ps-card--flat">
          <h3 class="ps-card__title">Hristos — centar svega</h3>
          <p class="ps-card__text">
            Isus Hristos je istiniti Bog i istiniti čovek. Njegov život otkriva Boga kao Oca,
            Njegovo stradanje pokazuje dubinu ljubavi, a Vaskrsenje otvara perspektivu večnosti.
            Pravoslavna vera nije samo “da verujem da Bog postoji”, već da se učim da živim po Hristu.
          </p>
          <div class="ps-callout ps-callout--gold">
            <strong>Ključna misao:</strong> hrišćanstvo se ne svodi na moral bez Boga —
            nego na život koji izvire iz susreta sa Hristom.
          </div>
        </article>
      </div>
    </section>

    <!-- CRKVA I PREDANJE-->
    <section id="crkva" class="ps-asec ps-asec--soft">
      <div class="ps-asec__head">
        <h2>Crkva i Sveto Predanje</h2>
        <p class="ps-sub">
          Vera se čuva i prenosi kroz Sveto Pismo i Sveto Predanje — kao živo iskustvo Crkve.
        </p>
      </div>

      <p class="ps-ap">
        Sveto Pismo je zapisano svedočanstvo o Bogu i istoriji spasenja. Ali Crkva Pismo ne čita
        kao izdvojeni tekst, već u svetlu bogoslužbenog života i iskustva svetih.
        Predanje obuhvata način na koji Crkva vekovima razume Jevanđelje: kroz molitvu,
        liturgiju, dela Svetih Otaca, odluke sabora, ikonopis i celokupan crkveni ritam.
      </p>
      <p class="ps-ap">
        Predanje nije “dodatak” veri, niti običaj radi običaja. Ono je pamćenje Crkve,
        provera da ne skrenemo u samovolju i privatna tumačenja. U Predanju se vidi kako Jevanđelje
        postaje život: kako se moli, kako se posti, kako se prašta, kako se voli.
      </p>

      <div class="ps-grid ps-grid--3">
        <div class="ps-note">
          <div class="ps-note__t">Sveto Pismo</div>
          <div class="ps-note__p">Reč Božija koja oblikuje veru, misli i savest.</div>
        </div>
        <div class="ps-note">
          <div class="ps-note__t">Sveto Predanje</div>
          <div class="ps-note__p">Živo iskustvo Crkve: bogosluženje, Oci, sabori, ikonopis.</div>
        </div>
        <div class="ps-note">
          <div class="ps-note__t">Saborni duh</div>
          <div class="ps-note__p">Vera se živi u zajednici — uz pastirsko vođstvo i smirenje.</div>
        </div>
      </div>
    </section>

    <!-- LITURGIJA -->
    <section id="liturgija" class="ps-asec">
      <div class="ps-asec__head">
        <h2>Božanska Liturgija</h2>
        <p class="ps-sub">Srce crkvenog života — mesto gde vera postaje susret.</p>
      </div>

      <div class="ps-split">
        <div>
          <p class="ps-ap">
            Liturgija je centralno bogosluženje pravoslavne Crkve. Na Liturgiji nismo publika,
            nego učesnici: slušamo Jevanđelje, odgovaramo molitvama, donosimo svoje brige i radosti,
            i učimo da stojimo pred Bogom sabrano.
          </p>
          <p class="ps-ap">
            Nedelja se doživljava kao “mali Vaskrs”. To ne znači da su ostali dani nevažni,
            već da se ritam nedeljne Liturgije postavlja kao osnova duhovne stabilnosti:
            šta god da se dešava — postoji mesto gde se srce vraća miru.
          </p>

          <div class="ps-callout ps-callout--blue">
            <strong>Mali praktični vodič:</strong> dođi ranije, ugasi telefon, prati prozbe,
            slušaj Jevanđelje, i samo budi prisutna. “Malo po malo” donosi najveću promenu.
          </div>
        </div>

        <aside class="ps-panel">
          <h3 class="ps-panel__title">Liturgija u nekoliko tačaka</h3>
          <ul class="ps-ul ps-ul--tight">
            <li><strong>Okupljanje</strong> — postajemo zajednica.</li>
            <li><strong>Jevanđelje</strong> — reč koja preobražava.</li>
            <li><strong>Prinošenje</strong> — darovi i molitve.</li>
            <li><strong>Pričešće</strong> — sjedinjenje sa Hristom.</li>
            <li><strong>Otpuštanje</strong> — vera se nosi u svakodnevicu.</li>
          </ul>
        </aside>
      </div>
    </section>

    <!-- MOLITVA -->
    <section id="molitva" class="ps-asec ps-asec--soft">
      <div class="ps-asec__head">
        <h2>Molitva</h2>
        <p class="ps-sub">Razgovor srca sa Bogom — jednostavno, istrajno i iskreno.</p>
      </div>

      <p class="ps-ap">
        Molitva može biti kratka kao uzdah, ili duža i čitana iz molitvenika.
        U pravoslavnom iskustvu naglašava se: važnija je pažnja nego “mnogo reči”.
        Bolje je reći malo, ali sabrano, nego mnogo — rasuto.
      </p>
      <p class="ps-ap">
        Molitva uči srce da se smiri, da se ne rasipa, da se ne hrani gnevom i brigom.
        Vremenom, čovek počinje da primećuje da se menja pogled na svet: manje panike,
        više poverenja, više zahvalnosti.
      </p>

      <div class="ps-grid ps-grid--2">
        <div class="ps-card ps-card--flat">
          <h3 class="ps-card__title">Jutro i veče</h3>
          <p class="ps-card__text">
            Kratak molitveni početak dana postavlja ton: “Nisam sam/sama.”
            Večernja molitva vraća mir i uči nas da oprostimo, sabere nas posle svega što se desilo.
          </p>
          <div class="ps-mini">
            <span class="ps-pill">5–10 min</span>
            <span class="ps-pill">redovno</span>
            <span class="ps-pill">s pažnjom</span>
          </div>
        </div>

        <div class="ps-card ps-card--flat">
          <h3 class="ps-card__title">Isusova molitva</h3>
          <p class="ps-card__text">
            “Gospode Isuse Hriste, pomiluj me.” Kratka molitva koja sabira misli.
            Ne radi se o “tehnici”, već o poverenju i skromnosti: da srce prestane da glumi snagu.
          </p>
          <div class="ps-callout ps-callout--note">
            <strong>Savet:</strong> izgovaraj tiho, bez žurbe. Ako misli odlutaju — vrati se bez nervoze.
          </div>
        </div>
      </div>
    </section>

    <!-- 10 ZAPOVESTI (ACCORDION) -->
    <section id="zapovesti" class="ps-asec">
      <div class="ps-asec__head">
        <h2>Deset Božijih zapovesti</h2>
        <p class="ps-sub">
          Zapovesti nisu “spisak zabrana”, već putokazi koji čuvaju čoveka od unutrašnjeg rasula
          i odnose od povređivanja.
        </p>
      </div>

      <div class="ps-callout ps-callout--gold">
        <strong>Kako da ih čitaš?</strong> Ne kao sud, nego kao ogledalo. One pokazuju gde srce klizi,
        i gde je potrebno izlečenje — ne “perfekcija”.
      </div>

      <div class="ps-acc">
        <details class="ps-acc__item">
          <summary class="ps-acc__sum">
            <span class="ps-acc__n">1</span>
            Ja sam Gospod Bog tvoj — nemoj imati drugih bogova osim Mene.
          </summary>
          <div class="ps-acc__body">
            <p class="ps-ap">
              Ova zapovest govori o prvenstvu Boga. “Drugi bogovi” nisu samo idoli od kamena,
              nego sve ono što zauzme mesto smisla: slava, novac, kontrola, strah, tuđe mišljenje.
              Kada Bog nije na prvom mestu, čovek postaje rob onoga što prolazi.
            </p>
          </div>
        </details>

        <details class="ps-acc__item">
          <summary class="ps-acc__sum">
            <span class="ps-acc__n">2</span>
            Ne pravi sebi idola.
          </summary>
          <div class="ps-acc__body">
            <p class="ps-ap">
              Idol je lažna slika koja obećava sigurnost, a donosi prazninu. Čovek lako pravi idole
              od svojih planova, uspeha ili ideala o sebi. Vera nas vraća realnosti: Bog je živ,
              ne može se “ukalupiti” u naše predstave, niti se svede na magiju.
            </p>
          </div>
        </details>

        <details class="ps-acc__item">
          <summary class="ps-acc__sum">
            <span class="ps-acc__n">3</span>
            Ne uzimaj ime Gospodnje uzalud.
          </summary>
          <div class="ps-acc__body">
            <p class="ps-ap">
              Ime Božije se ne izgovara kao poštapalica, pretnja ili “za svaki slučaj”.
              Poštovanje prema Bogu oblikuje i naš jezik: manje grubosti, manje kletve,
              manje olakog obećavanja “pred Bogom”.
            </p>
          </div>
        </details>

        <details class="ps-acc__item">
          <summary class="ps-acc__sum">
            <span class="ps-acc__n">4</span>
            Sećaj se dana Gospodnjeg i svetkuj ga.
          </summary>
          <div class="ps-acc__body">
            <p class="ps-ap">
              Svetkovanje nije puko “ne-raditi”, već posvetiti vreme Bogu i porodici,
              vratiti srce smislu. Nedeljna Liturgija postaje sidro: ono što drži čoveka
              da se ne raspadne od brzine života.
            </p>
          </div>
        </details>

        <details class="ps-acc__item">
          <summary class="ps-acc__sum">
            <span class="ps-acc__n">5</span>
            Poštuj oca i majku.
          </summary>
          <div class="ps-acc__body">
            <p class="ps-ap">
              Poštovanje roditelja ne znači idealizaciju, nego prepoznavanje korena.
              Čak i kad su odnosi teški, poziv je na zahvalnost za život i na trud da se
              prekine lanac povređivanja. Poštovanje gradi stabilnost porodice i društva.
            </p>
          </div>
        </details>

        <details class="ps-acc__item">
          <summary class="ps-acc__sum">
            <span class="ps-acc__n">6</span>
            Ne ubij.
          </summary>
          <div class="ps-acc__body">
            <p class="ps-ap">
              Ovo se tiče ne samo fizičkog nasilja, nego i svega što “ubija” čoveka iznutra:
              mržnja, ponižavanje, okrutne reči, osuda. Hrišćanin je pozvan da štiti život
              i dostojanstvo drugog, čak i kad se ne slaže sa njim.
            </p>
          </div>
        </details>

        <details class="ps-acc__item">
          <summary class="ps-acc__sum">
            <span class="ps-acc__n">7</span>
            Ne čini preljube.
          </summary>
          <div class="ps-acc__body">
            <p class="ps-ap">
              Vernost je zaštita ljubavi. Ona čuva brak i odnose od egoizma i prolaznih strasti.
              Pravoslavni pogled na čistotu ne svodi se na “zabrane”, već na poštovanje osobe:
              da se drugi ne koristi kao predmet, nego da se voli kao ličnost.
            </p>
          </div>
        </details>

        <details class="ps-acc__item">
          <summary class="ps-acc__sum">
            <span class="ps-acc__n">8</span>
            Ne kradi.
          </summary>
          <div class="ps-acc__body">
            <p class="ps-ap">
              Krađa nije samo uzimanje stvari. To može biti i krađa vremena, poverenja,
              tuđe reputacije ili zasluga. Zapovest poziva na poštenje i skromnost,
              i na trud da se živi od svog rada bez prevare.
            </p>
          </div>
        </details>

        <details class="ps-acc__item">
          <summary class="ps-acc__sum">
            <span class="ps-acc__n">9</span>
            Ne svedoči lažno.
          </summary>
          <div class="ps-acc__body">
            <p class="ps-ap">
              Laž razara odnose. Ponekad je laž gruba, ponekad suptilna — prećutkivanje,
              izvrtanje, trač. Zapovest uči odgovornosti: reč ima težinu. Istina se govori
              s ljubavlju, ne kao oružje, nego kao lek.
            </p>
          </div>
        </details>

        <details class="ps-acc__item">
          <summary class="ps-acc__sum">
            <span class="ps-acc__n">10</span>
            Ne poželi ono što je tuđe.
          </summary>
          <div class="ps-acc__body">
            <p class="ps-ap">
              Zavist truje srce. Ona čini da čovek ne vidi svoje darove, nego stalno meri
              sebe tuđim životom. Zapovest poziva na zahvalnost i mir: da se radujemo dobru
              drugog, a da svoje potrebe iznosimo Bogu bez ogorčenja.
            </p>
          </div>
        </details>
      </div>

      <div class="ps-footerline">
        <div class="ps-footerline__left">
          <strong>Sažetak:</strong> prve zapovesti govore o odnosu prema Bogu, a druge o odnosu prema bližnjem.
        </div>
        <div class="ps-footerline__right">
          <span class="ps-pill ps-pill--soft">Ljubav prema Bogu</span>
          <span class="ps-pill ps-pill--soft">Ljubav prema čoveku</span>
        </div>
      </div>
    </section>

    <!-- POST -->
    <section id="post" class="ps-asec ps-asec--soft">
      <div class="ps-asec__head">
        <h2>Post</h2>
        <p class="ps-sub">Post je podvig koji uključuje i telo i dušu — put budnosti i smirenja. Tokom posta 
          nije najvažnije koju hranu ćemo jesti, već da ne jedemo jedni druge.</p>
      </div>

      <p class="ps-ap">
        Post ima telesnu stranu (uzdržanje u hrani) i duhovnu stranu (uzdržanje od zla, sujete,
        osuđivanja, svađe). Smisao posta nije “da izdržim”, nego da naučim meru i da srce postane
        slobodnije: manje nervoze, manje ropstva navikama, više prostora za molitvu i milosrđe.
      </p>

      <div class="ps-grid ps-grid--3">
        <div class="ps-note">
          <div class="ps-note__t">Umerenost</div>
          <div class="ps-note__p">Post uči da ja nisam rob svojih želja.</div>
        </div>
        <div class="ps-note">
          <div class="ps-note__t">Milosrđe</div>
          <div class="ps-note__p">Bez dobrote i praštanja, post gubi smisao.</div>
        </div>
        <div class="ps-note">
          <div class="ps-note__t">Razboritost</div>
          <div class="ps-note__p">Post se usklađuje uz blagoslov i realne okolnosti.</div>
        </div>
      </div>
    </section>

    <!-- ISPOVEST & PRICESCE -->
    <section id="ispovest" class="ps-asec">
      <div class="ps-asec__head">
        <h2>Ispovest i Pričešće</h2>
        <p class="ps-sub">Pokajanje je početak isceljenja, a Pričešće vrhunac liturgijskog života.</p>
      </div>

      <div class="ps-grid ps-grid--2">
        <article class="ps-card ps-card--flat">
          <h3 class="ps-card__title">Ispovest</h3>
          <p class="ps-card__text">
            Ispovest je susret sa Bogom u istini: priznanje grehova bez opravdavanja,
            ali i bez očaja. Ona vraća trezvenost: vidiš gde si pao/pala, i dobijaš snagu da ustaneš.
            Ispovest nije poniženje — nego oslobađanje od tereta.
          </p>
          <ul class="ps-ul ps-ul--tight">
            <li><strong>Priprema:</strong> kratko preispitivanje savesti, molitva, iskrenost.</li>
            <li><strong>Cilj:</strong> lečenje i novi početak, a ne “kazna”.</li>
          </ul>
        </article>

        <article class="ps-card ps-card--flat">
          <h3 class="ps-card__title">Pričešće</h3>
          <p class="ps-card__text">
            Sveto Pričešće je sjedinjenje sa Hristom — najveći dar Crkve. Pravoslavni život
            bez Liturgije i Pričešća postaje samo moralizam. Priprema se gradi kroz molitvu,
            post koliko je moguće, i mir sa ljudima: trud da ne nosimo zlopamćenje.
          </p>
          <div class="ps-callout ps-callout--blue">
            <strong>Najvažnije:</strong> pristupiti smireno, bez osuđivanja drugih — sa željom da se menja sopstveni život.
          </div>
        </article>
      </div>
    </section>

    <blockquote class="ps-quote ps-quote--big">
      <span class="ps-quote__mark">„</span>
      <span class="ps-quote__text">
        Vera nije samo znanje — to je život koji se svakog dana iznova uči."
      </span>
    </blockquote>

  </div>
</section>
<style>
/* ===== OSNOVNI KONCEPTI — FORCE STYLE ===== */

.ps-hero .ps-hero__title{
  margin:0 0 12px !important;
  font-size:clamp(1.9rem, 2.7vw, 2.55rem) !important;
  line-height:1.06 !important;
  letter-spacing:-.025em !important;
  font-weight:800 !important;
  color:#c5a24a !important;
  text-shadow:0 0 14px rgba(197,162,74,.14) !important;
}

.ps-hero .ps-hero__lead{
  color:rgba(255,255,255,.80) !important;
  line-height:1.85 !important;
  font-size:1rem !important;
  max-width:920px !important;
}

.ps-hero .ps-hero__badge{
  display:inline-flex !important;
  align-items:center !important;
  gap:8px !important;
  padding:8px 12px !important;
  border-radius:999px !important;
  border:1px solid rgba(197,162,74,.18) !important;
  background:rgba(197,162,74,.08) !important;
  color:#e2c26a !important;
  font-size:.88rem !important;
  font-weight:700 !important;
}

.ps-hero .ps-hero__dot{
  width:10px !important;
  height:10px !important;
  border-radius:999px !important;
  background:#c5a24a !important;
  box-shadow:0 0 0 4px rgba(197,162,74,.10) !important;
}

.ps-wrap .ps-asec__head h2{
  margin:0 0 8px !important;
  font-size:clamp(1.45rem, 2vw, 1.9rem) !important;
  line-height:1.08 !important;
  letter-spacing:-.02em !important;
  font-weight:800 !important;
  color:#c5a24a !important;
  text-shadow:0 0 12px rgba(197,162,74,.12) !important;
}

.ps-wrap .ps-sub{
  margin:0 !important;
  color:rgba(255,255,255,.74) !important;
  line-height:1.8 !important;
  font-size:.98rem !important;
  text-align:justify !important;
  text-justify:inter-word !important;
  max-width:none !important;
}

.ps-wrap .ps-card__title{
  color:#ffffff !important;
  font-size:1.12rem !important;
  line-height:1.18 !important;
  font-weight:800 !important;
  margin:0 0 10px !important;
}

.ps-wrap .ps-card__text,
.ps-wrap .ps-ap,
.ps-wrap .ps-note__p,
.ps-wrap .ps-ul,
.ps-wrap .ps-check{
  color:rgba(255,255,255,.82) !important;
  line-height:1.8 !important;
}

.ps-wrap .ps-panel__title{
  margin:0 0 12px !important;
  font-size:1.02rem !important;
  font-weight:800 !important;
  color:#c5a24a !important;
  text-shadow:0 0 10px rgba(197,162,74,.10) !important;
}

.ps-wrap .ps-chip,
.ps-wrap .ps-pill{
  display:inline-flex !important;
  align-items:center !important;
  justify-content:center !important;
  padding:8px 12px !important;
  border-radius:999px !important;
  border:1px solid rgba(197,162,74,.18) !important;
  background:rgba(197,162,74,.08) !important;
  color:#e2c26a !important;
  font-size:.84rem !important;
  font-weight:700 !important;
}

.ps-wrap .ps-pill--soft{
  border-color:rgba(255,255,255,.08) !important;
  background:rgba(255,255,255,.03) !important;
  color:rgba(255,255,255,.78) !important;
}

.ps-wrap .ps-callout--gold{
  border:1px solid rgba(197,162,74,.20) !important;
  background:rgba(197,162,74,.08) !important;
  color:rgba(255,255,255,.88) !important;
}

.ps-wrap .ps-callout--gold strong{
  color:#e2c26a !important;
}

.ps-wrap .ps-note__t{
  color:#c5a24a !important;
  font-weight:800 !important;
}

.ps-wrap .ps-acc__sum{
  color:#ffffff !important;
  font-weight:700 !important;
}

.ps-wrap .ps-acc__n{
  color:#e2c26a !important;
  font-weight:800 !important;
}

.ps-wrap .ps-footerline strong{
  color:#e2c26a !important;
}

.ps-wrap .ps-quote--big{
  margin-top:34px !important;
  padding:22px 24px !important;
  border-radius:24px !important;
  border:1px solid rgba(197,162,74,.16) !important;
  background:
    radial-gradient(circle at top left, rgba(197,162,74,.07), transparent 24%),
    rgba(255,255,255,.02) !important;
}

.ps-wrap .ps-quote--big .ps-quote__mark{
  color:#c5a24a !important;
  font-size:2.4rem !important;
  line-height:1 !important;
}

.ps-wrap .ps-quote--big .ps-quote__text{
  color:rgba(255,255,255,.90) !important;
  font-size:1.02rem !important;
  line-height:1.85 !important;
  font-style:italic !important;
}

@media (max-width: 768px){
  .ps-hero .ps-hero__title{
    font-size:clamp(1.65rem, 7vw, 2rem) !important;
  }

  .ps-wrap .ps-asec__head h2{
    font-size:clamp(1.28rem, 5vw, 1.55rem) !important;
  }
}
</style>
@endsection