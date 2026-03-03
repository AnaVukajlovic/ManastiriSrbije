@extends('layouts.app')
@section('title','Prijava')

@section('content')
<div class="card auth-card">
  <h1>Prijava</h1>
  <p class="muted">Dobrodošli nazad. Unesite podatke za nalog.</p>

  @if (session('status'))
    <div class="alert">{{ session('status') }}</div>
  @endif

  <form method="POST" action="{{ route('login') }}" class="auth-form">
    @csrf

    <label class="field">
      <span>Email</span>
      <input type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username">
      @error('email') <small class="err">{{ $message }}</small> @enderror
    </label>

    <label class="field">
      <span>Lozinka</span>
      <input type="password" name="password" required autocomplete="current-password">
      @error('password') <small class="err">{{ $message }}</small> @enderror
    </label>

    <label class="check">
      <input type="checkbox" name="remember">
      <span>Zapamti me</span>
    </label>

    <div class="auth-actions">
      <button class="btn2" type="submit">Prijavi se</button>

      @if (Route::has('password.request'))
        <a class="link" href="{{ route('password.request') }}">Zaboravljena lozinka?</a>
      @endif
    </div>

    <div class="muted" style="margin-top:10px;">
      Nemaš nalog? <a class="link" href="{{ route('register') }}">Registruj se</a>
    </div>
  </form>
</div>
@endsection
