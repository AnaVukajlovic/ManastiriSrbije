@extends('layouts.site')

@section('title', 'Početna — Pravoslavni Svetionik')
@section('nav_home', 'active')
@section('content')

@php
    $day = $today ?? ($calendarToday ?? null);

    if (is_array($day)) {
        $day = array_merge([
            'date'    => '—',
            'feast'   => '—',
            'fasting' => '—',
            'saint'   => '—',
            'saints'  => null,
            'red'     => null,
            'note'    => null,
        ], $day);

        if (($day['saint'] ?? '—') === '—' && !empty($day['saints'])) {
            $day['saint'] = $day['saints'];
        }
    } else {
        $day = [
            'date'    => '—',
            'feast'   => '—',
            'fasting' => '—',
            'saint'   => '—',
            'red'     => null,
            'note'    => null,
        ];
    }

    $fallback = [
        ['name' => 'Đurđevi Stupovi', 'slug' => 'djurdjevi-stupovi', 'img' => asset('images/sample/djurdjevi.jpg')],
        ['name' => 'Gračanica', 'slug' => 'gracanica', 'img' => asset('images/sample/gracanica.jpg')],
        ['name' => 'Mileševa', 'slug' => 'mileseva', 'img' => asset('images/sample/mileseva.jpg')],
        ['name' => 'Sopoćani', 'slug' => 'sopocani', 'img' => asset('images/sample/sopocani.jpg')],
    ];

    $cards = [];

    if (!empty($featured) && count($featured) > 0) {
        foreach ($featured as $m) {
            $name = $m->name ?? 'Manastir';
            $slug = !empty($m->slug) ? $m->slug : null;

            $img = !empty($m->image_url) ? $m->image_url : null;

            if (!$img && $slug) {
                $img = asset('images/monasteries/' . $slug . '.jpg');
            }

            if (!$img) {
                $img = $fallback[count($cards) % count($fallback)]['img'];
            }

            if (!$slug) {
                $slug = $fallback[count($cards) % count($fallback)]['slug'];
            }

            $cards[] = [
                'name' => $name,
                'slug' => $slug,
                'img'  => $img,
            ];

            if (count($cards) >= 4) {
                break;
            }
        }
    }

    while (count($cards) < 4) {
        $cards[] = $fallback[count($cards) % count($fallback)];
    }
@endphp

@push('styles')
<style>
  body.page-home .home-ref,
  body.page-home .home-ref *{
    box-sizing:border-box;
  }

  body.page-home .home-ref{
    --gold:#c5a24a;
    --gold-soft:#e1c56f;
    --ink:rgba(255,255,255,.94);
    --muted:rgba(255,255,255,.74);
    --panel:linear-gradient(180deg, rgba(11,9,10,.66), rgba(11,9,10,.42));
    --panel-2:linear-gradient(180deg, rgba(29,16,15,.96), rgba(13,8,9,.97));
    --shadow:0 18px 44px rgba(0,0,0,.26);
    --serif:"Cormorant Garamond", Georgia, serif;
    --sans:"Inter", system-ui, -apple-system, "Segoe UI", Arial, sans-serif;
    color:var(--ink);
    position:relative;
    z-index:1;
  }

  body.page-home .home-ref a{
    color:inherit;
    text-decoration:none;
  }

  body.page-home .home-ref__wrap{
    width:min(1520px, calc(100% - 72px)) !important;
    margin-inline:auto !important;
  }

  body.page-home .home-ref__hero{
    position:relative !important;
    min-height:760px !important;
    overflow:hidden !important;
    border-bottom:1px solid rgba(255,255,255,.04) !important;
  }

  body.page-home .home-ref__bg{
    position:absolute !important;
    inset:0 !important;
    background-size:cover !important;
    background-position:center center !important;
    background-repeat:no-repeat !important;
    filter:brightness(.52) contrast(1.03) saturate(1.02) !important;
    transform:scale(1.01) !important;
  }

  body.page-home .home-ref__overlay{
    position:absolute !important;
    inset:0 !important;
    background:
      linear-gradient(90deg, rgba(8,6,7,.76) 0%, rgba(8,6,7,.48) 34%, rgba(8,6,7,.18) 68%, rgba(8,6,7,.10) 100%),
      linear-gradient(180deg, rgba(10,8,9,.02) 0%, rgba(10,8,9,.10) 44%, rgba(10,8,9,.56) 100%) !important;
  }

  body.page-home .home-ref__grid{
    position:relative !important;
    z-index:2 !important;
    min-height:760px !important;
    display:grid !important;
    grid-template-columns:minmax(0, 760px) minmax(330px, 390px) !important;
    justify-content:space-between !important;
    align-items:center !important;
    gap:110px !important;
    padding:86px 0 74px !important;
  }

  body.page-home .home-ref__panel{
    position:relative !important;
    overflow:hidden !important;
    width:100% !important;
    min-width:0 !important;
    padding:26px 26px 22px !important;
    border-radius:30px !important;
    border:1px solid rgba(255,255,255,.08) !important;
    background:var(--panel) !important;
    backdrop-filter:blur(10px) !important;
    -webkit-backdrop-filter:blur(10px) !important;
    box-shadow:0 18px 44px rgba(0,0,0,.28) !important;
  }

  body.page-home .home-ref__kicker{
    margin:0 0 10px !important;
    font-family:var(--sans) !important;
    font-size:14px !important;
    font-weight:800 !important;
    letter-spacing:.14em !important;
    text-transform:uppercase !important;
    color:var(--gold-soft) !important;
  }

  body.page-home .home-ref__title{
    margin:0 0 18px !important;
    font-family:var(--serif) !important;
    font-size:52px !important;
    line-height:.82 !important;
    letter-spacing:-.060em !important;
    font-weight:700 !important;
    color:var(--gold) !important;
    text-shadow:0 0 14px rgba(197,162,74,.08) !important;
  }

  body.page-home .home-ref__lead{
    margin:0 0 18px !important;
    max-width:44ch !important;
    font-family:var(--sans) !important;
    font-size:20px !important;
    line-height:1.72 !important;
    font-weight:500 !important;
    color:rgba(255,255,255,.90) !important;
  }

  body.page-home .home-ref__quote{
    margin:18px 0 18px !important;
    padding:18px 18px 16px !important;
    border-radius:22px !important;
    border:1px solid rgba(255,255,255,.08) !important;
    background:rgba(255,255,255,.03) !important;
    box-shadow:inset 0 1px 0 rgba(255,255,255,.02) !important;
  }

  body.page-home .home-ref__quote-label{
    margin-bottom:10px !important;
    font-family:var(--sans) !important;
    font-size:13px !important;
    font-weight:800 !important;
    letter-spacing:.14em !important;
    text-transform:uppercase !important;
    color:var(--gold-soft) !important;
  }

  body.page-home .home-ref__quote-text{
    font-family:var(--sans) !important;
    font-size:18px !important;
    line-height:1.45 !important;
    font-weight:700 !important;
    color:#fff !important;
  }

  body.page-home .home-ref__quote-meta{
    margin-top:10px !important;
    font-family:var(--sans) !important;
    font-size:14px !important;
    color:var(--muted) !important;
  }

  body.page-home .home-ref__search{
    display:grid !important;
    grid-template-columns:minmax(0, 1fr) 120px !important;
    gap:10px !important;
    align-items:center !important;
    padding:8px !important;
    border-radius:18px !important;
    border:1px solid rgba(255,255,255,.10) !important;
    background:rgba(255,255,255,.03) !important;
    box-shadow:inset 0 1px 0 rgba(0, 0, 0, 0.02) !important;
  }


body.page-home .home-ref__search{
  background:linear-gradient(180deg, rgba(30, 30, 30, 0.65), rgba(0,0,0,0.45)) !important;
  border:1px solid rgba(146, 144, 140, 0.25) !important;
  box-shadow:0 8px 20px rgba(0,0,0,0.4) !important;
}

  body.page-home .home-ref__search input{
    width:100% !important;
    min-width:0 !important;
    height:52px !important;
    border-radius:14px !important;
    border:1px solid rgba(255,255,255,.08) !important;
    background:rgba(0,0,0,.18) !important;
    color:#fff !important;
    padding:0 16px !important;
    font-family:var(--sans) !important;
    font-size:15px !important;
    font-weight:500 !important;
    outline:none !important;
    box-shadow:none !important;
  }

  body.page-home .home-ref__search input::placeholder{
    color:rgba(255,255,255,.52) !important;
  }

  body.page-home .home-ref__search button{
    width:100% !important;
    height:52px !important;
    border:0 !important;
    border-radius:14px !important;
    background:linear-gradient(135deg, var(--gold), #e1c466) !important;
    color:#19120e !important;
    font-family:var(--sans) !important;
    font-size:15px !important;
    font-weight:800 !important;
    cursor:pointer !important;
    box-shadow:0 10px 20px rgba(197,162,74,.18) !important;
  }

  body.page-home .home-ref__aside{
    width:100% !important;
    max-width:380px !important;
    min-width:0 !important;
    margin-left:auto !important;
    margin-top:64px !important;
    padding:26px 24px 22px !important;
    border-radius:28px !important;
    border:1px solid rgba(255,255,255,.08) !important;
    background:
      radial-gradient(circle at top left, rgba(197,162,74,.10), transparent 34%),
      var(--panel-2) !important;
    box-shadow:0 20px 42px rgba(0,0,0,.24) !important;
  }

  body.page-home .home-ref__aside-title{
    margin-bottom:16px !important;
    font-family:var(--sans) !important;
    font-size:15px !important;
    font-weight:800 !important;
    color:var(--gold-soft) !important;
  }

  body.page-home .home-ref__aside-date{
    margin-bottom:16px !important;
    font-family:var(--serif) !important;
    font-size:30px !important;
    line-height:.95 !important;
    font-weight:700 !important;
    color:#fff !important;
    letter-spacing:-.03em !important;
  }

  body.page-home .home-ref__aside-row{
    display:grid !important;
    grid-template-columns:72px 1fr !important;
    gap:14px !important;
    padding:12px 0 !important;
    border-top:1px solid rgba(255,255,255,.06) !important;
  }

  body.page-home .home-ref__aside-row span{
    font-family:var(--sans) !important;
    font-size:14px !important;
    font-weight:500 !important;
    color:var(--muted) !important;
  }

  body.page-home .home-ref__aside-row strong{
    font-family:var(--sans) !important;
    font-size:14px !important;
    line-height:1.45 !important;
    font-weight:700 !important;
    color:#fff !important;
  }

  body.page-home .home-ref__section{
    padding:38px 0 14px !important;
  }

  body.page-home .home-ref__head{
    display:flex !important;
    flex-direction:column !important;
    gap:6px !important;
    margin-bottom:22px !important;
  }

  body.page-home .home-ref__head h2{
    margin:0 !important;
    font-family:var(--serif) !important;
    font-size:42px !important;
    line-height:.92 !important;
    letter-spacing:-.04em !important;
    font-weight:700 !important;
    color:var(--gold) !important;
  }

  body.page-home .home-ref__head p{
    margin:0 !important;
    font-family:var(--sans) !important;
    font-size:18px !important;
    font-weight:500 !important;
    color:var(--muted) !important;
  }

  body.page-home .home-ref__cards{
    display:grid !important;
    grid-template-columns:repeat(4, minmax(0, 1fr)) !important;
    gap:18px !important;
  }

  body.page-home .home-ref__card{
    position:relative !important;
    display:block !important;
    height:292px !important;
    min-height:292px !important;
    border-radius:22px !important;
    overflow:hidden !important;
    border:1px solid rgba(255,255,255,.08) !important;
    background:#140e0f !important;
    box-shadow:0 16px 34px rgba(0,0,0,.20) !important;
  }

  body.page-home .home-ref__card-bg{
    position:absolute !important;
    inset:0 !important;
    background-size:cover !important;
    background-position:center !important;
    background-repeat:no-repeat !important;
    transition:transform .35s ease !important;
  }

  body.page-home .home-ref__card:hover .home-ref__card-bg{
    transform:scale(1.04) !important;
  }

  body.page-home .home-ref__card-shade{
    position:absolute !important;
    inset:0 !important;
    background:linear-gradient(180deg, rgba(0,0,0,.04) 0%, rgba(0,0,0,.10) 34%, rgba(0,0,0,.72) 100%) !important;
  }

  body.page-home .home-ref__card-body{
    position:absolute !important;
    left:0 !important;
    right:0 !important;
    bottom:0 !important;
    z-index:2 !important;
    padding:16px 16px 14px !important;
  }

  body.page-home .home-ref__card-title{
    margin:0 !important;
    font-family:var(--sans) !important;
    font-size:18px !important;
    line-height:1.25 !important;
    font-weight:800 !important;
    color:#fff !important;
    text-shadow:0 2px 10px rgba(0,0,0,.45) !important;
  }

  body.page-home .home-ref__quick{
    display:grid !important;
    grid-template-columns:repeat(3, minmax(0, 1fr)) !important;
    gap:18px !important;
  }

  body.page-home .home-ref__quick-item{
    display:flex !important;
    flex-direction:column !important;
    min-height:190px !important;
    padding:22px 22px 18px !important;
    border-radius:22px !important;
    border:1px solid rgba(255,255,255,.08) !important;
    background:
      radial-gradient(circle at top left, rgba(197,162,74,.08), transparent 26%),
      linear-gradient(135deg, rgba(26,17,16,.96), rgba(11,8,8,.96)) !important;
    box-shadow:0 16px 34px rgba(0,0,0,.20) !important;
  }

  body.page-home .home-ref__quick-title{
    margin:0 0 10px !important;
    font-family:var(--serif) !important;
    font-size:28px !important;
    line-height:1 !important;
    font-weight:700 !important;
    color:var(--gold) !important;
  }

  body.page-home .home-ref__quick-text{
    margin:0 !important;
    font-family:var(--sans) !important;
    font-size:14px !important;
    line-height:1.7 !important;
    color:rgba(255,255,255,.78) !important;
  }

  @media (max-width: 1280px){
    body.page-home .home-ref__wrap{
      width:min(100%, calc(100% - 40px)) !important;
    }

    body.page-home .home-ref__grid{
      grid-template-columns:1fr !important;
      min-height:auto !important;
      gap:26px !important;
      padding:42px 0 50px !important;
    }

    body.page-home .home-ref__aside{
      max-width:none !important;
      margin-left:0 !important;
      margin-top:0 !important;
    }

    body.page-home .home-ref__cards{
      grid-template-columns:repeat(2, minmax(0, 1fr)) !important;
    }

    body.page-home .home-ref__quick{
      grid-template-columns:repeat(2, minmax(0, 1fr)) !important;
    }
  }

  @media (max-width: 768px){
    body.page-home .home-ref__wrap{
      width:min(100%, calc(100% - 24px)) !important;
    }

    body.page-home .home-ref__hero{
      min-height:auto !important;
    }

    body.page-home .home-ref__grid{
      min-height:auto !important;
      padding:20px 0 24px !important;
      grid-template-columns:1fr !important;
      gap:16px !important;
    }

    body.page-home .home-ref__panel,
    body.page-home .home-ref__aside{
      padding:16px !important;
      border-radius:18px !important;
    }

    body.page-home .home-ref__title{
      font-size:52px !important;
      line-height:.88 !important;
    }

    body.page-home .home-ref__lead{
      font-size:16px !important;
    }

    body.page-home .home-ref__quote-text{
      font-size:16px !important;
    }

    body.page-home .home-ref__search{
      grid-template-columns:1fr !important;
    }

    body.page-home .home-ref__aside-date{
      font-size:40px !important;
    }

    body.page-home .home-ref__aside-row{
      grid-template-columns:1fr !important;
      gap:4px !important;
    }

    body.page-home .home-ref__cards{
      grid-template-columns:1fr !important;
      gap:14px !important;
    }

    body.page-home .home-ref__card{
      height:230px !important;
      min-height:230px !important;
    }

    body.page-home .home-ref__quick{
      grid-template-columns:1fr !important;
      gap:14px !important;
    }

    body.page-home .home-ref__head h2{
      font-size:44px !important;
    }

    body.page-home .home-ref__head p{
      font-size:15px !important;
    }
  }


/* =========================================
   FINAL FONT FIX (PRAVI FONT)
   ========================================= */

/* GLOBALNO */
body.page-home .home-ref,
body.page-home .home-ref *{
  font-family:"Inter", system-ui, -apple-system, "Segoe UI", Arial, sans-serif !important;
}

/* NASLOVI – PRAVI FONT */
body.page-home .home-ref__title,
body.page-home .home-ref__head h2,
body.page-home .home-ref__quick-title,
body.page-home .home-ref__aside-date{
font-family:"Inter", system-ui, -apple-system, "Segoe UI", Arial, sans-serif !important;
  font-weight:700 !important;
  letter-spacing:-0.02em !important;
}



  /* =========================================
   FORCE FONT FIX (OVO REŠAVA PROBLEM)
   ========================================= */

/* HERO NASLOV – GLAVNI PROBLEM */
body.page-home .home-ref__title{
font-family:"Inter", system-ui, -apple-system, "Segoe UI", Arial, sans-serif !important;
  font-weight:700 !important;
  letter-spacing:-0.05em !important;
}

/* NASLOVI SEKCIJA (Predlog dana, Brzi pristup) */
body.page-home .home-ref__head h2{
font-family:"Inter", system-ui, -apple-system, "Segoe UI", Arial, sans-serif !important;
  font-weight:700 !important;
}

/* QUICK TITLES */
body.page-home .home-ref__quick-title{
font-family:"Inter", system-ui, -apple-system, "Segoe UI", Arial, sans-serif !important;
}

/* DATUM U KALENDARU */
body.page-home .home-ref__aside-date{
font-family:"Inter", system-ui, -apple-system, "Segoe UI", Arial, sans-serif !important;
  font-weight:700 !important;
}

/* SVE OSTALO OSTAVLJAMO INTER */
body.page-home .home-ref,
body.page-home .home-ref *{
  font-family:"Inter", system-ui, -apple-system, "Segoe UI", Arial, sans-serif;
}

/* ALI NE DIRAMO SERIF ELEMENTE */
body.page-home .home-ref__title,
body.page-home .home-ref__head h2,
body.page-home .home-ref__quick-title,
body.page-home .home-ref__aside-date{
font-family:"Inter", system-ui, -apple-system, "Segoe UI", Arial, sans-serif !important;
}




/* ICON + TITLE U ISTOM REDU */
body.page-home .home-ref__quick-item{
  display:flex !important;
  flex-direction:column !important;
}

/* gornji deo (ikonica + naslov) */
body.page-home .home-ref__quick-item{
  gap:10px !important;
}

/* WRAP za icon + title (pravimo ih u liniji) */
body.page-home .home-ref__quick-item > .home-ref__quick-icon{
  font-size:28px !important;
  margin-bottom:0 !important;
}

/* ovo pravi da title ide pored ikone */
body.page-home .home-ref__quick-item{
  align-items:flex-start !important;
}

body.page-home .home-ref__quick-item{
  position:relative;
}

/* ključni deo */
body.page-home .home-ref__quick-icon,
body.page-home .home-ref__quick-title{
  display:inline-flex !important;
  align-items:center !important;
}

/* razmak između ikone i teksta */
body.page-home .home-ref__quick-title{
  margin-left:8px !important;
  font-size:26px !important;
}

/* ICON + TITLE u istom redu */
body.page-home .home-ref__quick-item{
  display:flex !important;
  flex-direction:column !important;
}

body.page-home .home-ref__quick-icon{
  display:inline-block !important;
  font-size:28px !important;
  vertical-align:middle !important;
}

body.page-home .home-ref__quick-title{
  display:inline-block !important;
  margin-left:8px !important;
  vertical-align:middle !important;
}



body.page-home .home-ref__quick-item{
  display:flex !important;
  flex-direction:column !important;
}

body.page-home .home-ref__quick-icon,
body.page-home .home-ref__quick-title{
  display:inline-flex !important;
  align-items:center !important;
}

body.page-home .home-ref__quick-title{
  margin-left:8px !important;
}

body.page-home .home-ref__quick-icon{
  font-size:28px !important;
}


/* FORCE da budu u istom redu */
body.page-home .home-ref__quick-top{
  display:flex !important;
  flex-direction:row !important;
  align-items:center !important;
  gap:10px !important;
}

/* ikonica */
body.page-home .home-ref__quick-icon{
  font-size:28px !important;
  display:flex !important;
}

/* title */
body.page-home .home-ref__quick-title{
  display:flex !important;
  align-items:center !important;
  margin:0 !important;
}


/* Usklađivanje icon + title u quick karticama */
body.page-home .home-ref__quick-top{
  display:flex !important;
  align-items:center !important;
  gap:10px !important;
  min-height:34px !important;
}

body.page-home .home-ref__quick-icon{
  display:inline-flex !important;
  align-items:center !important;
  justify-content:center !important;
  flex:0 0 30px !important;
  width:30px !important;
  height:30px !important;
  font-size:22px !important;
  line-height:1 !important;
  margin:0 !important;
}

body.page-home .home-ref__quick-title{
  display:inline-flex !important;
  align-items:center !important;
  min-height:30px !important;
  margin:0 !important;
  font-family:"Playfair Display", Georgia, serif !important;
  font-size:26px !important;
  line-height:1 !important;
  font-weight:700 !important;
}
body.page-home .home-ref__quick-icon{
  transform:translateY(-1px) !important;
}


body.page-home .home-ref .home-ref__quick-item .home-ref__quick-title{
    font-family:var(--sans) !important;
}



body.page-home .home-ref__title{
  font-family: "Inter", sans-serif !important;
  font-weight:900 !important; /* NAJBITNIJE */
  letter-spacing:-0.025em !important;
  line-height:0.9 !important;
}

body.page-home .home-ref__title{
  text-shadow:0 2px 12px rgba(197,162,74,0.25) !important;
}




</style>
@endpush

<div class="home-ref">
  <section class="home-ref__hero" aria-label="Uvod">
    <div class="home-ref__bg" aria-hidden="true" style="background-image:url('{{ asset('images/hero/hero1.jpg') }}');"></div>
    <div class="home-ref__overlay" aria-hidden="true"></div>

    <div class="home-ref__wrap home-ref__grid">
      <div class="home-ref__panel">
        <p class="home-ref__kicker">DIGITALNI VODIČ KROZ SVETINJE SRBIJE</p>

        <h1 class="home-ref__title">
          Pravoslavni Svetionik
        </h1>

        <p class="home-ref__lead">
          Istraži manastire Srbije, pronađi svetinje na mapi, upoznaj njihovu istoriju i duhovni značaj, i na jednom mestu otkrij sadržaje koji povezuju veru, kulturu i nasleđe.
        </p>

        <div class="home-ref__quote" aria-label="Citat dana">
          <div class="home-ref__quote-label">CITAT DANA</div>
          <div class="home-ref__quote-text">{{ $quote?->text ?? 'Budimo ljudi.' }}</div>
          <div class="home-ref__quote-meta">— {{ $quote?->author ?? 'Patrijarh Pavle' }}</div>
        </div>

        <form class="home-ref__search" action="{{ route('monasteries.index') }}" method="GET" role="search">
          <input id="hero-q" name="q" type="search" placeholder="Pretraga manastira, grada ili regiona...">
          <button type="submit">Pretraži</button>
        </form>
      </div>

      <aside class="home-ref__aside" aria-label="Danas u kalendaru">
        <div class="home-ref__aside-title">Danas u kalendaru</div>
        <div class="home-ref__aside-date">{{ $day['date'] ?? '—' }}</div>

        <div class="home-ref__aside-row">
          <span>Praznik</span>
          <strong>{{ $day['feast'] ?? '—' }}</strong>
        </div>

        <div class="home-ref__aside-row">
          <span>Post</span>
          <strong>{{ $day['fasting'] ?? '—' }}</strong>
        </div>

        <div class="home-ref__aside-row">
          <span>Svetac</span>
          <strong>{{ $day['saint'] ?? '—' }}</strong>
        </div>
      </aside>
    </div>
  </section>

  <section class="home-ref__section" aria-label="Predlog dana">
    <div class="home-ref__wrap">
      <div class="home-ref__head">
        <h2>Predlog dana</h2>
        <p>Izaberi jednu svetinju za posetu ili istraživanje</p>
      </div>

      <div class="home-ref__cards">
        @foreach($cards as $c)
          <a
            class="home-ref__card"
            href="{{ route('monasteries.show', $c['slug']) }}"
            aria-label="Otvori manastir: {{ $c['name'] }}"
          >
            <div class="home-ref__card-bg" aria-hidden="true" style="background-image:url('{{ $c['img'] }}');"></div>
            <div class="home-ref__card-shade" aria-hidden="true"></div>
            <div class="home-ref__card-body">
              <div class="home-ref__card-title">{{ $c['name'] }}</div>
            </div>
          </a>
        @endforeach
      </div>
    </div>
  </section>

<section class="home-ref__section" aria-label="Brzi pristup sekcijama">
  <div class="home-ref__wrap">
    <div class="home-ref__head">
      <h2>Brzi pristup sekcijama</h2>
      <p>Sve na jednom mestu</p>
    </div>

    <div class="home-ref__quick">

      <a class="home-ref__quick-item" href="{{ route('map.index') }}">
        <div class="home-ref__quick-top">
          <span class="home-ref__quick-icon">🗺️</span>
          <span class="home-ref__quick-title">Mapa svetinja</span>
        </div>
        <div class="home-ref__quick-text">
          Istraži manastire Srbije po lokaciji i upoznaj svetinje koje svedoče o duhovnom, kulturnom i istorijskom nasleđu našeg naroda.
        </div>
      </a>

      <a class="home-ref__quick-item" href="{{ route('ktitors.index') }}">
        <div class="home-ref__quick-top">
          <span class="home-ref__quick-icon">👑</span>
          <span class="home-ref__quick-title">Ktitori</span>
        </div>
        <div class="home-ref__quick-text">
          Upoznaj najveće srpske zadužbinare, sa posebnim osvrtom na Nemanjiće, čije su zadužbine obeležile zlatno doba srednjovekovne Srbije.
        </div>
      </a>

      <a class="home-ref__quick-item" href="{{ route('edukacija.index') }}">
        <div class="home-ref__quick-top">
          <span class="home-ref__quick-icon">🎓</span>
          <span class="home-ref__quick-title">Edukacija</span>
        </div>
        <div class="home-ref__quick-text">
          Kroz pažljivo odabrane sadržaje upoznaj period od početka do kraja vladavine Nemanjića i značaj koji je to imalo za srpsku duhovnost, državu i kulturu.
        </div>
      </a>

    </div>
  </div>
</section>
</div>
@endsection