@extends('layouts.site')
@section('title','Prijava')

@section('content')
<section class="section auth-page">
  <div class="container">

    <div class="auth-shell">
      <div class="auth-hero">
        <div class="auth-kicker">
          <span class="auth-kicker__dot"></span>
          <span>Pravoslavni Svetionik</span>
        </div>

        <h1 class="auth-hero__title">Dobrodošli nazad</h1>

        <p class="auth-hero__text">
          Prijavite se na svoj nalog kako biste sačuvali omiljene sadržaje, koristili dodatne
          mogućnosti aplikacije i nastavili istraživanje manastira, istorije i pravoslavnog sadržaja.
        </p>

        <div class="auth-hero__chips">
          <span>Omiljeni manastiri</span>
          <span>Edukacija</span>
          <span>AI alati</span>
          <span>Pravoslavni sadržaj</span>
        </div>
      </div>

      <div class="auth-card">
        <div class="auth-card__head">
          <h2>Prijava</h2>
          <p>Unesite email i lozinku za pristup nalogu.</p>
        </div>

        @if (session('status'))
          <div class="auth-alert">
            {{ session('status') }}
          </div>
        @endif
@if ($errors->any())
  <div class="auth-alert auth-alert--error">
    {{ $errors->first() }}
  </div>
@endif
        <form method="POST" action="{{ route('login') }}" class="auth-form">
          @csrf

          <label class="auth-field">
            <span>Email adresa</span>
            <input
              type="email"
              name="email"
              value="{{ old('email') }}"
              required
              autofocus
              autocomplete="username"
              placeholder="unesi@email.com"
            >
            @error('email')
              <small class="auth-err">{{ $message }}</small>
            @enderror
          </label>

          <label class="auth-field">
            <span>Lozinka</span>
            <input
              type="password"
              name="password"
              required
              autocomplete="current-password"
              placeholder="Unesite lozinku"
            >
            @error('password')
              <small class="auth-err">{{ $message }}</small>
            @enderror
          </label>

          <div class="auth-row">
            <label class="auth-check">
              <input type="checkbox" name="remember">
              <span>Zapamti me</span>
            </label>

            @if (Route::has('password.request'))
              <a class="auth-link" href="{{ route('password.request') }}">Zaboravljena lozinka?</a>
            @endif
          </div>

          <button class="auth-submit" type="submit">
            Prijavi se
          </button>

          <div class="auth-bottom">
            Nemaš nalog?
            <a class="auth-link" href="{{ route('register') }}">Registruj se</a>
          </div>
        </form>
      </div>
    </div>

  </div>
</section>

<style>
.auth-page{
  --au-ink: rgba(255,255,255,.94);
  --au-muted: rgba(255,255,255,.74);
  --au-line: rgba(255,255,255,.10);
  --au-soft: rgba(255,255,255,.04);
  --au-soft-2: rgba(255,255,255,.02);
  --au-gold: #c5a24a;
  --au-gold-2: #e2c26a;
  --au-gold-soft: rgba(197,162,74,.12);
  --au-gold-line: rgba(197,162,74,.24);
  --au-shadow: 0 24px 60px rgba(0,0,0,.30);
}

.auth-page .container{
  width:min(1380px, calc(100% - 34px));
  max-width:none;
}

.auth-shell{
  display:grid;
  grid-template-columns: 1.03fr .97fr;
  gap:22px;
  align-items:stretch;
}

.auth-hero,
.auth-card{
  position:relative;
  border:1px solid var(--au-line);
  border-radius:30px;
  overflow:hidden;
  background:
    radial-gradient(circle at top right, rgba(197,162,74,.09), transparent 24%),
    linear-gradient(180deg, var(--au-soft), var(--au-soft-2));
  box-shadow:var(--au-shadow);
}

.auth-hero{
  padding:34px 30px;
  min-height:640px;
  display:flex;
  flex-direction:column;
  justify-content:flex-end;
  background:
    radial-gradient(circle at left top, rgba(197,162,74,.18), transparent 34%),
    radial-gradient(circle at 82% 18%, rgba(226,194,106,.10), transparent 20%),
    linear-gradient(180deg, rgba(255,255,255,.045), rgba(255,255,255,.02));
}
.auth-alert--error{
  border:1px solid rgba(231,76,60,.28);
  background:rgba(231,76,60,.10);
  color:#ffd7d7;
}
.auth-hero::before{
  content:"";
  position:absolute;
  inset:0;
  background:
    linear-gradient(135deg, rgba(0,0,0,.06), rgba(0,0,0,.18)),
    radial-gradient(circle at 85% 10%, rgba(197,162,74,.08), transparent 20%);
  pointer-events:none;
}

.auth-hero > *{
  position:relative;
  z-index:1;
}

.auth-kicker{
  display:inline-flex;
  align-items:center;
  gap:10px;
  width:max-content;
  margin-bottom:20px;
  padding:9px 15px;
  border-radius:999px;
  border:1px solid rgba(197,162,74,.28);
  background:rgba(197,162,74,.09);
  color:#f0d892;
  font-size:.95rem;
  font-weight:800;
}

.auth-kicker__dot{
  width:10px;
  height:10px;
  border-radius:999px;
  background:linear-gradient(180deg, var(--au-gold-2), var(--au-gold));
  box-shadow:0 0 0 5px rgba(197,162,74,.10);
}

.auth-hero__title{
  margin:0 0 16px;
  color:#fff;
  font-size:clamp(2.2rem, 4vw, 4rem);
  line-height:1.04;
  letter-spacing:-.03em;
}

.auth-hero__text{
  margin:0;
  max-width:560px;
  color:rgba(255,255,255,.84);
  font-size:1.04rem;
  line-height:1.85;
}

.auth-hero__chips{
  display:flex;
  flex-wrap:wrap;
  gap:10px;
  margin-top:24px;
}

.auth-hero__chips span{
  display:inline-flex;
  align-items:center;
  padding:10px 14px;
  border-radius:999px;
  border:1px solid rgba(255,255,255,.10);
  background:rgba(255,255,255,.04);
  color:#fff;
  font-size:.93rem;
  font-weight:700;
}

.auth-card{
  padding:34px 28px;
  display:flex;
  flex-direction:column;
  justify-content:center;
}

.auth-card::before{
  content:"";
  position:absolute;
  inset:-90px -90px auto auto;
  width:240px;
  height:240px;
  background:radial-gradient(circle at 30% 30%, rgba(197,162,74,.14), transparent 60%);
  pointer-events:none;
}

.auth-card__head,
.auth-form{
  position:relative;
  z-index:1;
}

.auth-card__head{
  margin-bottom:22px;
}

.auth-card__head h2{
  margin:0 0 8px;
  color:#f0d892;
  font-size:2rem;
  line-height:1.08;
}

.auth-card__head p{
  margin:0;
  color:var(--au-muted);
  line-height:1.7;
}

.auth-alert{
  margin-bottom:16px;
  padding:12px 14px;
  border-radius:16px;
  border:1px solid rgba(197,162,74,.22);
  background:rgba(197,162,74,.09);
  color:#fff;
}

.auth-form{
  display:flex;
  flex-direction:column;
  gap:16px;
}

.auth-field{
  display:flex;
  flex-direction:column;
  gap:8px;
}

.auth-field span{
  color:#ecd08a;
  font-size:.96rem;
  font-weight:800;
}

.auth-field input{
  width:100%;
  min-height:54px;
  border-radius:16px;
  border:1px solid rgba(255,255,255,.12);
  background:rgba(0,0,0,.20);
  color:#fff;
  padding:0 14px;
  outline:none;
  transition:border-color .2s ease, box-shadow .2s ease, background .2s ease;
}

.auth-field input::placeholder{
  color:rgba(255,255,255,.42);
}

.auth-field input:focus{
  border-color:rgba(197,162,74,.50);
  box-shadow:0 0 0 4px rgba(197,162,74,.10);
  background:rgba(0,0,0,.24);
}

.auth-row{
  display:flex;
  justify-content:space-between;
  align-items:center;
  gap:14px;
  flex-wrap:wrap;
}

.auth-check{
  display:inline-flex;
  align-items:center;
  gap:10px;
  color:rgba(255,255,255,.80);
  cursor:pointer;
}

.auth-check input{
  accent-color:#c5a24a;
}

.auth-link{
  color:#f0d892;
  text-decoration:none;
  font-weight:700;
}

.auth-link:hover{
  color:#fff2c7;
}

.auth-submit{
  width:100%;
  min-height:56px;
  border:none;
  border-radius:16px;
  background:linear-gradient(180deg, #d8b864, #b78f34);
  color:#1a1310;
  font-size:1rem;
  font-weight:900;
  cursor:pointer;
  box-shadow:0 16px 34px rgba(197,162,74,.18);
  transition:transform .18s ease, filter .18s ease, box-shadow .18s ease;
}

.auth-submit:hover{
  transform:translateY(-1px);
  filter:brightness(1.03);
  box-shadow:0 20px 38px rgba(197,162,74,.24);
}

.auth-bottom{
  margin-top:4px;
  color:rgba(255,255,255,.74);
  text-align:center;
}

.auth-err{
  color:#ff9a9a;
  font-size:.88rem;
}

@media (max-width: 1100px){
  .auth-shell{
    grid-template-columns:1fr;
  }

  .auth-hero{
    min-height:340px;
  }
}

@media (max-width: 700px){
  .auth-page .container{
    width:min(100%, calc(100% - 18px));
  }

  .auth-hero,
  .auth-card{
    border-radius:22px;
  }

  .auth-hero,
  .auth-card{
    padding:22px 18px;
  }

  .auth-hero__title{
    font-size:2rem;
  }

  .auth-card__head h2{
    font-size:1.7rem;
  }
}
</style>
@endsection