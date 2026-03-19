@extends('layouts.site')

@section('title', 'Manastiri — Pravoslavni Svetionik')
@section('nav_monasteries', 'active')

@section('content')
<section class="section">
  <div class="container">

    <style>
      .mon-index{
        --panel-bg: linear-gradient(135deg, rgba(28,18,17,.96), rgba(12,8,9,.96));
        --panel-glow: radial-gradient(circle at top left, rgba(197,162,74,.11), transparent 30%);
        --stroke: rgba(255,255,255,.08);
        --stroke-soft: rgba(255,255,255,.06);
        --text-soft: rgba(255,255,255,.72);
        --text-muted: rgba(255,255,255,.56);
        --gold: #c5a24a;
        --gold2: #e2c26a;
      }

      .monIndexHero{
        margin-bottom: 26px;
      }

      .monIndexHero__top{
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 14px;
      }
.monIndexHero__title{
  margin:0;
  font-size:clamp(1.7rem, 2.2vw, 2.2rem);
  line-height:1.08;
  letter-spacing:-.02em;
  font-weight:800;
  color:var(--gold);
  text-shadow:0 0 14px rgba(197,162,74,.15);
}

      .monIndexHero__subtitle{
        margin: 8px 0 0;
        font-size: .98rem;
        color: var(--text-soft);
      }

.monIndexHero__quote{
  margin:12px 0 0;
  font-size:.97rem;
  font-style:italic;
  color:rgba(255,255,255,.78);
}

.monIndexHero__quote span{
  color:var(--gold);
  font-weight:600;
}

.monFiltersCard{
  padding:18px;
  border-radius:28px;
  border:1px solid var(--stroke);
  background:
    radial-gradient(circle at top left, rgba(197,162,74,.09), transparent 30%),
    linear-gradient(180deg, rgba(255,255,255,.02), rgba(255,255,255,.01)),
    linear-gradient(135deg, rgba(28,18,17,.96), rgba(12,8,9,.96));
  box-shadow:
    0 20px 48px rgba(0,0,0,.30),
    inset 0 1px 0 rgba(255,255,255,.03);
}

      .filters__row{
        display: grid;
        grid-template-columns: minmax(220px, 1.35fr) minmax(170px, .95fr) minmax(170px, .95fr) minmax(170px, .75fr) auto auto;
        gap: 12px;
        align-items: center;
      }

      .filters__field{
        min-width: 0;
      }

      .mon-field,
      .mon-select-wrap{
        position: relative;
      }

.filters input,
.filters select{
  width:100%;
  height:50px;
  padding:0 16px;
  border-radius:16px;
  border:1px solid var(--stroke);
  background:rgba(255,255,255,.04);
  color:#f5f1ea;
  font-size:.97rem;
  box-shadow:inset 0 1px 0 rgba(255,255,255,.03);
  transition:.25s ease;
}

.filters input:focus,
.filters select:focus{
  outline:none;
  border-color:rgba(197,162,74,.6);
  box-shadow:0 0 0 3px rgba(197,162,74,.10);
  background:rgba(255,255,255,.06);
}

      .filters input::placeholder{
        color: rgba(255,255,255,.50);
      }



      .filters select{
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        padding-right: 42px;
        cursor: pointer;
        color: #f3ede3;
      }

      .filters select option{
        background: #1a1211;
        color: #f3ede3;
      }

      .mon-select-wrap::after{
        content: "";
        position: absolute;
        right: 16px;
        top: 50%;
        width: 10px;
        height: 10px;
        border-right: 2px solid rgba(255,255,255,.72);
        border-bottom: 2px solid rgba(255,255,255,.72);
        transform: translateY(-65%) rotate(45deg);
        pointer-events: none;
      }

      .filters .btn2,
      .filters .btn{
        height: 48px;
        padding: 0 18px;
        border-radius: 16px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        white-space: nowrap;
        font-weight: 700;
      }

      .filters .btn2{
        border: 0;
        background: linear-gradient(135deg, var(--gold), var(--gold2));
        color: #19120e;
        box-shadow: 0 10px 22px rgba(197,162,74,.16);
        transition: transform .2s ease, box-shadow .2s ease;
      }

      .filters .btn2:hover{
        transform: translateY(-1px);
        box-shadow: 0 14px 26px rgba(197,162,74,.20);
      }

      .filters .btn{
        background: rgba(255,255,255,.05);
        color: rgba(255,255,255,.92);
        border: 1px solid rgba(255,255,255,.10);
      }

      .filters .btn:hover{
        background: rgba(255,255,255,.08);
      }

      .monGridCards{
        display: grid;
        grid-template-columns: repeat(4, minmax(0, 1fr));
        gap: 20px;
        margin-top: 20px;
      }

.monCard{
  position:relative;
  display:block;
  min-height:330px;
  border-radius:26px;
  overflow:hidden;
  text-decoration:none;
  border:1px solid rgba(255,255,255,.08);
  background:linear-gradient(180deg, rgba(28,18,17,.96), rgba(12,8,9,.96));
  box-shadow:0 18px 44px rgba(0,0,0,.28);
  transition:transform .28s ease, box-shadow .28s ease, border-color .28s ease;
}
.monCard::before{
  content:"";
  position:absolute;
  inset:0 0 auto 0;
  height:1px;
  background:linear-gradient(90deg, transparent, rgba(197,162,74,.45), transparent);
  opacity:.75;
  z-index:3;
  pointer-events:none;
}
.monCard:hover{
  transform:translateY(-6px);
  box-shadow:
    0 26px 60px rgba(0,0,0,.34),
    0 0 24px rgba(197,162,74,.08);
  border-color:rgba(197,162,74,.22);
}

      .monCard__img{
        position: absolute;
        inset: 0;
        background-size: cover;
        background-position: center center;
        background-repeat: no-repeat;
        transform: scale(1.02);
        transition: transform .35s ease;
      }

.monCard:hover .monCard__img{
  transform:scale(1.06);
  filter:brightness(1.03) saturate(1.05);
}

      .monCard__overlay{
        position: absolute;
        inset: 0;
        background:
          linear-gradient(180deg, rgba(0,0,0,.04) 0%, rgba(0,0,0,.10) 30%, rgba(0,0,0,.68) 100%);
      }

      .monCard__body{
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        z-index: 2;
        padding: 18px 18px 16px;
      }

.monCard__title{
  margin-bottom:8px;
  font-size:1.28rem;
  line-height:1.16;
  font-weight:900;
  color:#fff;
  text-shadow:0 2px 10px rgba(0,0,0,.25);
}

.monCard__meta{
  display:flex;
  flex-wrap:wrap;
  gap:6px;
  margin-bottom:8px;
  font-size:.92rem;
  color:rgba(255,255,255,.76);
}

      .monCard__excerpt{
        display: -webkit-box;
        -webkit-line-clamp: 3;
        line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-size: .95rem;
        line-height: 1.58;
        color: rgba(255,255,255,.84);
      }

      .monIndexPagination{
        margin-top: 20px;
      }

      @media (max-width: 1280px){
        .filters__row{
          grid-template-columns: 1fr 1fr 1fr;
        }

        .filters .btn2,
        .filters .btn{
          width: 100%;
        }

        .monGridCards{
          grid-template-columns: repeat(3, minmax(0, 1fr));
        }
      }

      @media (max-width: 920px){
        .filters__row{
          grid-template-columns: 1fr 1fr;
        }

        .monGridCards{
          grid-template-columns: repeat(2, minmax(0, 1fr));
        }
      }

      @media (max-width: 640px){
        .filters__row{
          grid-template-columns: 1fr;
        }

        .monGridCards{
          grid-template-columns: 1fr;
        }

        .monCard{
          min-height: 300px;
        }

        .monFiltersCard{
          padding: 14px;
          border-radius: 22px;
        }

        .monIndexHero__title{
          font-size: 2rem;
        }
      }
    </style>

    <div class="mon-index">
      <div class="monIndexHero">
        <div class="monIndexHero__top">
          <div>
            <h1 class="monIndexHero__title">Manastiri</h1>
            <p class="monIndexHero__subtitle">Pretraga i filtriranje svetinja u bazi</p>
            <p class="monIndexHero__quote">„Svaka svetinja čuva trag molitve, vremena i narodne duše.” <span>— Pravoslavni Svetionik</span></p>
          </div>
        </div>

        <form class="filters monFiltersCard" method="GET" action="{{ route('monasteries.index') }}">
          <div class="filters__row">
            <div class="filters__field mon-field">
              <label class="sr-only" for="q2">Pretraga</label>
              <input
                id="q2"
                name="q"
                type="search"
                value="{{ $q }}"
                placeholder="Naziv, grad, region..."
              />
            </div>

            <div class="filters__field mon-select-wrap">
              <label class="sr-only" for="eparchy">Eparhija</label>
              <select id="eparchy" name="eparchy">
                <option value="">Sve eparhije</option>
                @foreach($eparchies as $e)
                  <option value="{{ $e->slug }}" {{ request('eparchy') === $e->slug ? 'selected' : '' }}>
                    {{ $e->name }}
                  </option>
                @endforeach
              </select>
            </div>

            <div class="filters__field mon-select-wrap">
              <label class="sr-only" for="region">Region</label>
              <select id="region" name="region">
                <option value="">Svi regioni</option>
                @foreach($regions as $r)
                  <option value="{{ $r }}" @selected($region === $r)>{{ $r }}</option>
                @endforeach
              </select>
            </div>

            <div class="filters__field mon-select-wrap">
              <label class="sr-only" for="sort">Sortiranje</label>
              <select id="sort" name="sort">
                <option value="popular" @selected($sort === 'popular')>Preporučeno</option>
                <option value="name" @selected($sort === 'name')>Naziv (A–Z)</option>
                <option value="new" @selected($sort === 'new')>Najnovije</option>
              </select>
            </div>

            <button class="btn2" type="submit">Primeni</button>
            <a class="btn" href="{{ route('monasteries.index') }}">Reset</a>
          </div>
        </form>
      </div>

      <div class="monGridCards">
        @forelse($monasteries as $m)
          @php
            $localImg = asset('images/monasteries/' . $m->slug . '.jpg');
            $fallbackImg = asset('images/monasteries/placeholder.jpg');

            $regionLabel = (!empty($m->region) && $m->region !== 'Nepoznato') ? $m->region : null;
            $cityLabel = (!empty($m->city) && $m->city !== 'Nepoznato') ? $m->city : null;
          @endphp

          <a class="monCard" href="{{ route('monasteries.show', $m->slug) }}">
            <div
              class="monCard__img"
              style="background-image:url('{{ $localImg }}')"
              data-fallback="{{ $fallbackImg }}">
            </div>

            <div class="monCard__overlay"></div>

            <div class="monCard__body">
              <div class="monCard__title">{{ $m->name }}</div>

              @if($regionLabel || $cityLabel)
                <div class="monCard__meta">
                  @if($regionLabel)
                    <span>{{ $regionLabel }}</span>
                  @endif

                  @if($regionLabel && $cityLabel)
                    <span>•</span>
                  @endif

                  @if($cityLabel)
                    <span>{{ $cityLabel }}</span>
                  @endif
                </div>
              @endif

              @if(!empty($m->excerpt))
                <div class="monCard__excerpt">{{ $m->excerpt }}</div>
              @endif
            </div>
          </a>
        @empty
          <p class="muted">Nema rezultata za zadate filtere.</p>
        @endforelse
      </div>

      <div class="monIndexPagination">
        {{ $monasteries->links() }}
      </div>
    </div>

    <script>
      (function () {
        const nodes = document.querySelectorAll('.monCard__img[data-fallback]');
        nodes.forEach(el => {
          const bg = getComputedStyle(el).backgroundImage || '';
          const match = bg.match(/url\(["']?(.*?)["']?\)/);
          const imgUrl = match ? match[1] : null;

          if (!imgUrl) return;

          const test = new Image();
          test.onerror = () => {
            const fb = el.getAttribute('data-fallback');
            if (fb) el.style.backgroundImage = `url('${fb}')`;
          };
          test.src = imgUrl;
        });
      })();
    </script>

  </div>
</section>
@endsection