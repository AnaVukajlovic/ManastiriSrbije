@extends('layouts.app')
@section('title','Registracija')

@section('content')
<div class="card auth-card">
  <h1>Registracija</h1>
  <p class="muted">Kreiraj nalog da bi čuvala omiljene manastire i pitanja.</p>

  <form method="POST" action="{{ route('register') }}" class="auth-form">
    @csrf

    <label class="field">
      <span>Ime</span>
      <input type="text" name="name" value="{{ old('name') }}" required autocomplete="name">
      @error('name') <small class="err">{{ $message }}</small> @enderror
    </label>

    <label class="field">
      <span>Email</span>
      <input type="email" name="email" value="{{ old('email') }}" required autocomplete="username">
      @error('email') <small class="err">{{ $message }}</small> @enderror
    </label>

    <label class="field">
      <span>Lozinka</span>
      <input type="password" name="password" required autocomplete="new-password">
      @error('password') <small class="err">{{ $message }}</small> @enderror
    </label>

    <label class="field">
      <span>Potvrdi lozinku</span>
      <input type="password" name="password_confirmation" required autocomplete="new-password">
    </label>

    <div class="auth-actions">
      <button class="btn2" type="submit">Napravi nalog</button>
      <a class="link" href="{{ route('login') }}">Već imaš nalog? Prijava</a>
    </div>
  </form>
</div>
@endsection
