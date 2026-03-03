@extends('layouts.site')

@section('title', 'Omiljeni — Pravoslavni Svetionik')

@section('content')
  <section class="section">
    <div class="container">
      <div class="sectionhead">
        <div>
          <h1 style="margin:0">Omiljeni manastiri</h1>
          <div class="muted" style="margin-top:6px">
            Ovde su manastiri koje si označila kao omiljene.
          </div>
        </div>

        <a class="btn2" href="{{ route('monasteries.index') }}">+ Dodaj još</a>
      </div>

      @if ($favorites->count() === 0)
        <div class="card" style="padding:18px">
          <h2 style="margin:0 0 6px">Još nema omiljenih</h2>
          <p class="muted" style="margin:0 0 12px; line-height:1.6">
            Otvori listu manastira i klikni na ⭐ da dodaš omiljene.
          </p>
          <a class="btn2" href="{{ route('monasteries.index') }}">Otvori manastire →</a>
        </div>
      @else
        <div class="favgrid">
          @foreach ($favorites as $m)
            @php
              $img = trim((string) ($m->image_url ?? ''));
              $bg = $img
                ? "background-image:url('{$img}');"
                : "background-image:
                    radial-gradient(800px 500px at 20% 20%, rgba(201,161,74,.35), transparent 55%),
                    radial-gradient(700px 480px at 85% 35%, rgba(255,255,255,.12), transparent 60%),
                    linear-gradient(120deg, rgba(30,18,18,1), rgba(60,30,30,1));";
            @endphp

            <a class="card favcard" href="{{ route('monasteries.show', $m->slug) }}">
              <div class="favcard__img" style="{{ $bg }}"></div>

              <div class="favcard__body">
                <div class="favcard__top">
                  <div class="favcard__main">
                    <div class="favcard__title">{{ $m->name }}</div>

                    <div class="muted favcard__meta">
                      {{ $m->region ?? '—' }}@if(!empty($m->city)) • {{ $m->city }}@endif
                    </div>

                    @if(!empty($m->eparchy?->name))
                      <div class="favcard__badge">
                        <span class="badge">{{ $m->eparchy->name }}</span>
                      </div>
                    @endif
                  </div>

                  <span class="badge" title="Omiljeno">⭐</span>
                </div>

                @if(!empty($m->description))
                  <div class="muted favcard__desc">
                    {{ \Illuminate\Support\Str::limit($m->description, 120) }}
                  </div>
                @endif

                <div class="favcard__actions">
                  <span class="btn2" style="pointer-events:none; opacity:.75">Detalji →</span>
                </div>
              </div>
            </a>
          @endforeach
        </div>

        <div class="favpager">
          {{ $favorites->links() }}
        </div>
      @endif
    </div>
  </section>

  {{-- Minimalan CSS samo za ovu stranicu (da ne diraš globalni fajl). --}}
  <style>
    .favgrid{
      display:grid;
      grid-template-columns:repeat(3, minmax(0, 1fr));
      gap:14px;
    }

    .favcard{
      overflow:hidden;
      transform: translateZ(0);
    }
    .favcard:hover{
      transform: translateY(-2px);
      transition: .18s ease;
    }

    .favcard__img{
      height:180px;
      background-size:cover;
      background-position:center;
      background-color:#2b1a1a;
    }

    .favcard__body{ padding:14px; }

    .favcard__top{
      display:flex;
      gap:10px;
      align-items:flex-start;
      justify-content:space-between;
    }

    .favcard__main{ min-width:0; }

    .favcard__title{
      font-weight:950;
      font-size:16px;
      line-height:1.2;
      word-break:break-word;
    }

    .favcard__meta{
      margin-top:6px;
      font-size:13px;
    }

    .favcard__badge{ margin-top:10px; }

    .favcard__desc{
      margin-top:10px;
      font-size:13px;
      line-height:1.55;
    }

    .favcard__actions{
      margin-top:12px;
      display:flex;
      gap:10px;
      flex-wrap:wrap;
    }

    .favpager{ margin-top:18px; }

    @media (max-width: 1024px){
      .favgrid{ grid-template-columns: repeat(2, minmax(0, 1fr)); }
    }
    @media (max-width: 680px){
      .favgrid{ grid-template-columns: 1fr; }
    }
  </style>
@endsection