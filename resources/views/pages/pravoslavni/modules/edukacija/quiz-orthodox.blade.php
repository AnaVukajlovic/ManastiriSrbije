@extends('layouts.site')

@section('title','Kviz — Pravoslavlje')

@section('content')
<section class="section quiz-orthodox-page">
  <div class="container">

    <div class="quiz-hero">
      <div class="quiz-hero__content">
        <div class="quiz-kicker">
          <span class="quiz-kicker__dot"></span>
          <span>Interaktivna edukacija</span>
        </div>

        <h1 class="quiz-title">Kviz — Pravoslavlje</h1>
        <p class="quiz-sub">
          Proveri znanje o osnovnim pojmovima pravoslavne vere, bogosluženju, praznicima,
          simbolima i duhovnom životu. Nakon završetka kviza dobijaš rezultat i objašnjenja.
        </p>

        <div class="quiz-meta">
          <div class="quiz-chip">20 pitanja</div>
          <div class="quiz-chip">1 tačan odgovor</div>
          <div class="quiz-chip">Rezultat + objašnjenja</div>
        </div>
      </div>

      <div class="quiz-hero__side">
        <a class="quiz-back" href="{{ route('edukacija.show','ucenje-interakcija') }}">
          ← Nazad
        </a>
      </div>
    </div>

    @php
      $totalQuestions = count($questions);
      $answeredCount = count(array_filter($answers ?? [], fn($v) => $v !== null && $v !== ''));
      $progressPercent = $totalQuestions > 0 ? round(($answeredCount / $totalQuestions) * 100) : 0;
    @endphp

    <div class="quiz-toolbar">
      <div class="quiz-progress-card">
        <div class="quiz-progress-card__top">
          <span>Napredak</span>
          <strong>{{ $answeredCount }}/{{ $totalQuestions }}</strong>
        </div>
        <div class="quiz-progress">
          <span style="width: {{ $progressPercent }}%"></span>
        </div>
      </div>

      @if($result)
        <div class="quiz-result-card">
          <div class="quiz-result-card__left">
            <div class="quiz-score-ring">
              <div class="quiz-score-ring__inner">
                <strong>{{ $result['percent'] }}%</strong>
                <span>tačnost</span>
              </div>
            </div>
          </div>

          <div class="quiz-result-card__right">
            <h3>Rezultat kviza</h3>
            <p>
              Osvojeno: <strong>{{ $result['score'] }}/{{ $result['max'] }}</strong>
            </p>

            @php
              $percent = $result['percent'];
              if ($percent >= 90) $message = 'Odlično! Veoma dobro poznaješ osnovne pojmove pravoslavne vere.';
              elseif ($percent >= 75) $message = 'Vrlo dobro! Imaš lepo i stabilno znanje.';
              elseif ($percent >= 50) $message = 'Dobar rezultat. Još malo pažnje i biće odlično.';
              else $message = 'Nema veze — pročitaj objašnjenja i pokušaj ponovo.';
            @endphp

            <p class="quiz-result-msg">{{ $message }}</p>

            <div class="quiz-result-actions">
              <a class="btn btn--ghost" href="{{ route('edukacija.quiz-orthodox') }}">Pokušaj ponovo</a>
            </div>
          </div>
        </div>
      @endif
    </div>

    <form method="POST" action="{{ route('edukacija.quiz-orthodox.submit') }}">
      @csrf

      <div class="quiz-grid">
        @foreach($questions as $i => $q)
          @php
            $picked = $answers[$q['id']] ?? null;
          @endphp

          <article class="quiz-card {{ $result ? 'is-checked' : '' }}">
            <div class="quiz-card__head">
              <div class="quiz-card__number">{{ $i + 1 }}</div>
              <div class="quiz-card__title-wrap">
                <span class="quiz-card__label">Pitanje {{ $i + 1 }}</span>
                <h3 class="quiz-card__title">{{ $q['q'] }}</h3>
              </div>
            </div>

            <div class="quiz-options">
              @foreach($q['options'] as $idx => $opt)
                @php
                  $isCorrect = $result && ((string)$idx === (string)$q['correct']);
                  $isPicked  = $result && ((string)$idx === (string)$picked);
                  $stateClass = '';
                  if ($isCorrect) $stateClass = 'is-correct';
                  elseif ($isPicked && !$isCorrect) $stateClass = 'is-wrong';
                @endphp

                <label class="quiz-option {{ $stateClass }}">
                  <input
                    type="radio"
                    name="answers[{{ $q['id'] }}]"
                    value="{{ $idx }}"
                    {{ (string)$picked === (string)$idx ? 'checked' : '' }}
                  >
                  <span class="quiz-option__control"></span>
                  <span class="quiz-option__text">{{ $opt }}</span>
                </label>
              @endforeach
            </div>

            @if($result)
              <div class="quiz-explain">
                <div class="quiz-explain__icon">i</div>
                <div>
                  <strong>Objašnjenje:</strong>
                  <span>{{ $q['explain'] }}</span>
                </div>
              </div>
            @endif
          </article>
        @endforeach
      </div>

      <div class="quiz-actions">
        <button class="btn quiz-submit-btn" type="submit">Završi kviz</button>
        <a class="btn btn--ghost quiz-reset-btn" href="{{ route('edukacija.quiz-orthodox') }}">Reset</a>
      </div>

    </form>

  </div>
</section>

<style>
.quiz-orthodox-page{
  --qo-bg1:#120c0d;
  --qo-bg2:#181112;
  --qo-panel:rgba(255,255,255,.035);
  --qo-panel-2:rgba(255,255,255,.02);
  --qo-ink:rgba(255,255,255,.96);
  --qo-muted:rgba(255,255,255,.76);
  --qo-soft:rgba(255,255,255,.05);
  --qo-line:rgba(255,255,255,.09);
  --qo-gold:#c5a24a;
  --qo-gold-2:#e2c26a;
  --qo-gold-soft:rgba(197,162,74,.12);
  --qo-gold-line:rgba(197,162,74,.24);
  --qo-shadow:0 24px 60px rgba(0,0,0,.30);
  --qo-ok:rgba(46,204,113,.95);
  --qo-bad:rgba(231,76,60,.95);
}

.quiz-orthodox-page .container{
  width:min(1520px, calc(100% - 36px));
  max-width:none;
}

.quiz-hero{
  display:grid;
  grid-template-columns:1.35fr .38fr;
  gap:22px;
  align-items:stretch;
  margin-bottom:24px;
  padding:26px 24px;
  border:1px solid rgba(197,162,74,.18);
  border-radius:28px;
  background:
    radial-gradient(circle at left top, rgba(197,162,74,.16), transparent 34%),
    radial-gradient(circle at 84% 20%, rgba(226,194,106,.08), transparent 22%),
    linear-gradient(135deg, rgba(255,255,255,.04), rgba(255,255,255,.015));
  box-shadow:var(--qo-shadow);
  position:relative;
  overflow:hidden;
}

.quiz-hero::before{
  content:"";
  position:absolute;
  inset:0;
  background:
    linear-gradient(90deg, rgba(197,162,74,.06), transparent 26%, transparent 74%, rgba(197,162,74,.05)),
    radial-gradient(circle at 15% 80%, rgba(197,162,74,.08), transparent 25%);
  pointer-events:none;
}

.quiz-hero__content,
.quiz-hero__side{
  position:relative;
  z-index:1;
}

.quiz-kicker{
  display:inline-flex;
  align-items:center;
  gap:10px;
  margin-bottom:16px;
  padding:8px 14px;
  border-radius:999px;
  border:1px solid rgba(197,162,74,.24);
  background:rgba(197,162,74,.08);
  color:#f0d892;
  font-size:.94rem;
  font-weight:800;
}

.quiz-kicker__dot{
  width:10px;
  height:10px;
  border-radius:999px;
  background:linear-gradient(180deg, var(--qo-gold-2), var(--qo-gold));
  box-shadow:0 0 0 6px rgba(197,162,74,.10);
}

.quiz-title{
  margin:0 0 12px;
  font-size:clamp(1.9rem, 3vw, 2.95rem);
  line-height:1.08;
  letter-spacing:-.02em;
  color:var(--qo-gold);
  max-width:1100px;
}
.quiz-sub{
  margin:0;
  max-width:980px;
  color:rgba(255,255,255,.82);
  font-size:1rem;
  line-height:1.75;
  white-space:normal;
}

.quiz-meta{
  display:flex;
  flex-wrap:wrap;
  gap:10px;
  margin-top:18px;
}

.quiz-chip{
  display:inline-flex;
  align-items:center;
  padding:10px 14px;
  border-radius:999px;
  border:1px solid rgba(197,162,74,.18);
  background:rgba(255,255,255,.03);
  color:#f2d58a;
  font-size:.95rem;
  font-weight:800;
}

.quiz-hero__side{
  display:flex;
  justify-content:flex-end;
  align-items:flex-start;
}

.quiz-back{
  display:inline-flex;
  align-items:center;
  justify-content:center;
  min-height:48px;
  padding:0 18px;
  border-radius:999px;
  border:1px solid rgba(255,255,255,.12);
  background:rgba(255,255,255,.04);
  color:#fff;
  text-decoration:none;
  font-weight:800;
  transition:.22s ease;
}

.quiz-back:hover{
  transform:translateY(-1px);
  border-color:rgba(197,162,74,.40);
  background:rgba(197,162,74,.10);
  color:#fff;
}

.quiz-toolbar{
  display:grid;
  grid-template-columns:.95fr 1.05fr;
  gap:18px;
  margin-bottom:22px;
}

.quiz-progress-card,
.quiz-result-card{
  border:1px solid var(--qo-line);
  border-radius:24px;
  background:linear-gradient(180deg, var(--qo-panel), var(--qo-panel-2));
  box-shadow:var(--qo-shadow);
}

.quiz-progress-card{
  padding:18px;
}

.quiz-progress-card__top{
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:12px;
  margin-bottom:12px;
  color:#fff;
  font-weight:800;
}

.quiz-progress{
  height:14px;
  border-radius:999px;
  background:rgba(255,255,255,.06);
  overflow:hidden;
  border:1px solid rgba(255,255,255,.06);
}

.quiz-progress span{
  display:block;
  height:100%;
  border-radius:999px;
  background:linear-gradient(90deg, var(--qo-gold), var(--qo-gold-2));
  box-shadow:0 0 18px rgba(197,162,74,.28);
}

.quiz-result-card{
  display:flex;
  gap:18px;
  align-items:center;
  padding:18px;
}

.quiz-score-ring{
  width:110px;
  height:110px;
  border-radius:999px;
  padding:7px;
  background:conic-gradient(from 0deg, var(--qo-gold), var(--qo-gold-2), var(--qo-gold));
  box-shadow:0 14px 30px rgba(197,162,74,.15);
}

.quiz-score-ring__inner{
  width:100%;
  height:100%;
  border-radius:999px;
  display:flex;
  flex-direction:column;
  align-items:center;
  justify-content:center;
  background:#1a1011;
  border:1px solid rgba(255,255,255,.06);
  text-align:center;
}

.quiz-score-ring__inner strong{
  font-size:1.2rem;
  color:#fff;
  line-height:1;
}

.quiz-score-ring__inner span{
  margin-top:6px;
  font-size:.82rem;
  color:var(--qo-muted);
}

.quiz-result-card__right h3{
  margin:0 0 8px;
  color:#fff;
  font-size:1.2rem;
}

.quiz-result-card__right p{
  margin:0 0 8px;
  color:var(--qo-muted);
  line-height:1.7;
}

.quiz-result-msg{
  color:rgba(255,255,255,.92) !important;
}

.quiz-grid{
  display:grid;
  grid-template-columns:repeat(2, minmax(0, 1fr));
  gap:18px;
}

.quiz-card{
  position:relative;
  padding:20px;
  border-radius:24px;
  border:1px solid rgba(255,255,255,.10);
  background:
    radial-gradient(circle at top right, rgba(197,162,74,.10), transparent 22%),
    linear-gradient(180deg, rgba(255,255,255,.04), rgba(255,255,255,.02));
  box-shadow:var(--qo-shadow);
  transition:transform .22s ease, border-color .22s ease, box-shadow .22s ease;
}

.quiz-card:hover{
  transform:translateY(-3px);
  border-color:rgba(197,162,74,.26);
  box-shadow:0 26px 70px rgba(0,0,0,.34);
}

.quiz-card__head{
  display:flex;
  gap:14px;
  align-items:flex-start;
  margin-bottom:16px;
}

.quiz-card__number{
  flex:0 0 48px;
  width:48px;
  height:48px;
  border-radius:16px;
  display:flex;
  align-items:center;
  justify-content:center;
  background:linear-gradient(180deg, rgba(197,162,74,.22), rgba(197,162,74,.08));
  border:1px solid rgba(197,162,74,.30);
  color:#fff;
  font-weight:900;
  font-size:1.05rem;
  box-shadow:inset 0 1px 0 rgba(255,255,255,.08);
}

.quiz-card__label{
  display:inline-block;
  margin-bottom:6px;
  font-size:.82rem;
  letter-spacing:.08em;
  text-transform:uppercase;
  color:#d9b35b;
  font-weight:900;
}

.quiz-card__title{
  margin:0;
  color:#fff;
  font-size:1.28rem;
  line-height:1.45;
}

.quiz-options{
  display:flex;
  flex-direction:column;
  gap:12px;
}

.quiz-option{
  position:relative;
  display:flex;
  align-items:flex-start;
  gap:12px;
  padding:14px 15px;
  border-radius:18px;
  border:1px solid rgba(255,255,255,.10);
  background:rgba(0,0,0,.18);
  cursor:pointer;
  transition:.2s ease;
}

.quiz-option:hover{
  border-color:rgba(197,162,74,.28);
  background:rgba(255,255,255,.04);
  transform:translateY(-1px);
}

.quiz-option input{
  position:absolute;
  opacity:0;
  pointer-events:none;
}

.quiz-option__control{
  position:relative;
  flex:0 0 22px;
  width:22px;
  height:22px;
  margin-top:2px;
  border-radius:999px;
  border:2px solid rgba(255,255,255,.42);
  background:transparent;
  transition:.2s ease;
}

.quiz-option__control::after{
  content:"";
  position:absolute;
  inset:4px;
  border-radius:999px;
  background:linear-gradient(180deg, var(--qo-gold-2), var(--qo-gold));
  transform:scale(0);
  transition:.18s ease;
}

.quiz-option input:checked + .quiz-option__control{
  border-color:var(--qo-gold);
  box-shadow:0 0 0 5px rgba(197,162,74,.10);
}

.quiz-option input:checked + .quiz-option__control::after{
  transform:scale(1);
}

.quiz-option__text{
  color:rgba(255,255,255,.92);
  line-height:1.65;
  font-size:1rem;
}

.quiz-option.is-correct{
  border-color:rgba(46,204,113,.34);
  background:rgba(46,204,113,.08);
}

.quiz-option.is-correct .quiz-option__control{
  border-color:var(--qo-ok);
}

.quiz-option.is-wrong{
  border-color:rgba(231,76,60,.34);
  background:rgba(231,76,60,.08);
}

.quiz-option.is-wrong .quiz-option__control{
  border-color:var(--qo-bad);
}

.quiz-explain{
  display:flex;
  gap:12px;
  margin-top:16px;
  padding:14px 15px;
  border-radius:18px;
  border:1px solid rgba(197,162,74,.18);
  background:rgba(197,162,74,.07);
  color:rgba(255,255,255,.90);
  line-height:1.75;
}

.quiz-explain__icon{
  flex:0 0 28px;
  width:28px;
  height:28px;
  border-radius:999px;
  display:flex;
  align-items:center;
  justify-content:center;
  background:rgba(197,162,74,.16);
  border:1px solid rgba(197,162,74,.26);
  color:#fff;
  font-weight:900;
  font-style:italic;
}

.quiz-actions{
  display:flex;
  gap:12px;
  flex-wrap:wrap;
  margin-top:22px;
}

.quiz-submit-btn,
.quiz-reset-btn{
  min-height:50px;
  padding-inline:20px;
  border-radius:16px;
  font-weight:900;
}



@media (max-width: 1200px){
  .quiz-grid{
    grid-template-columns:1fr;
  }

  .quiz-toolbar{
    grid-template-columns:1fr;
  }

  .quiz-hero{
    grid-template-columns:1fr;
  }

  .quiz-hero__side{
    justify-content:flex-start;
  }

  .quiz-sub{
    white-space:normal;
  }
}

@media (max-width: 700px){
  .quiz-orthodox-page .container{
    width:min(100%, calc(100% - 18px));
  }

  .quiz-hero,
  .quiz-progress-card,
  .quiz-result-card,
  .quiz-card{
    border-radius:20px;
  }

  .quiz-title{
    font-size:2rem;
  }

  .quiz-sub{
    font-size:1rem;
    line-height:1.7;
    white-space:normal;
  }

  .quiz-card__head{
    gap:12px;
  }

  .quiz-card__number{
    width:42px;
    height:42px;
    flex-basis:42px;
    border-radius:14px;
  }

  .quiz-card__title{
    font-size:1.1rem;
  }

  .quiz-result-card{
    flex-direction:column;
    align-items:flex-start;
  }
}
</style>
@endsection