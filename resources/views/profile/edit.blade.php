@extends('layouts.site')

@section('title', 'Moj nalog — Profil')

@section('content')
  <section class="section">
    <div class="container">

      <div class="sectionhead">
        <div>
          <h1 style="margin:0">Moj profil</h1>
          <div class="muted" style="margin-top:6px">
            Ovde možeš da izmeniš osnovne podatke, lozinku i podešavanja naloga.
          </div>
        </div>

        <a class="btn2" href="{{ route('home') }}">← Nazad na početnu</a>
      </div>

      {{-- STATUS poruke iz Breeze --}}
      @if (session('status') === 'profile-updated')
        <div class="card" style="padding:14px; border-left:4px solid rgba(40,140,70,.55)">
          <div style="font-weight:900">Sačuvano</div>
          <div class="muted" style="margin-top:4px">Podaci profila su uspešno ažurirani.</div>
        </div>
        <div style="height:12px"></div>
      @elseif (session('status') === 'password-updated')
        <div class="card" style="padding:14px; border-left:4px solid rgba(40,140,70,.55)">
          <div style="font-weight:900">Sačuvano</div>
          <div class="muted" style="margin-top:4px">Lozinka je uspešno promenjena.</div>
        </div>
        <div style="height:12px"></div>
      @endif

      <div style="display:grid; grid-template-columns: 1.2fr .8fr; gap:14px; align-items:start">
        {{-- LEVO: glavne forme --}}
        <div style="display:grid; gap:14px">
          <div class="card" style="padding:18px">
            <h2 style="margin:0 0 10px">Osnovni podaci</h2>
            <div class="muted" style="margin:-4px 0 14px; line-height:1.6">
              Izmeni ime i email adresu.
            </div>

            @include('profile.partials.update-profile-information-form')
          </div>

          <div class="card" style="padding:18px">
            <h2 style="margin:0 0 10px">Promena lozinke</h2>
            <div class="muted" style="margin:-4px 0 14px; line-height:1.6">
              Preporuka: koristi jaku lozinku i ne deli je.
            </div>

            @include('profile.partials.update-password-form')
          </div>

          <div class="card" style="padding:18px; border:1px solid rgba(130,0,0,.18)">
            <h2 style="margin:0 0 10px">Brisanje naloga</h2>
            <div class="muted" style="margin:-4px 0 14px; line-height:1.6">
              Ovo je nepovratna radnja. Ako obrišeš nalog, brišu se i podaci vezani za nalog.
            </div>

            @include('profile.partials.delete-user-form')
          </div>
        </div>

        {{-- DESNO: info panel (profi izgled) --}}
        <aside class="card" style="padding:18px">
          <h3 style="margin:0 0 10px">Brzi pregled</h3>

          <div class="muted" style="line-height:1.7">
            <div style="display:flex; justify-content:space-between; gap:12px; padding:8px 0; border-top:1px solid rgba(0,0,0,.06)">
              <span>Ime</span>
              <strong style="text-align:right">{{ auth()->user()->name }}</strong>
            </div>

            <div style="display:flex; justify-content:space-between; gap:12px; padding:8px 0; border-top:1px solid rgba(0,0,0,.06)">
              <span>Email</span>
              <strong style="text-align:right; word-break:break-word">{{ auth()->user()->email }}</strong>
            </div>

            <div style="display:flex; justify-content:space-between; gap:12px; padding:8px 0; border-top:1px solid rgba(0,0,0,.06)">
              <span>Nalog kreiran</span>
              <strong style="text-align:right">
                {{ optional(auth()->user()->created_at)->format('d.m.Y') }}
              </strong>
            </div>
          </div>

          <div style="height:12px"></div>

          <div class="card" style="background: var(--paper2); padding:12px; border-radius:14px">
            <div style="font-weight:900; margin-bottom:6px">Sledeće (uskoro)</div>
            <div class="muted" style="line-height:1.6">
              Ovde ćemo dodati: omiljene manastire, beleške, moje rute i istoriju poseta.
            </div>
          </div>
        </aside>
      </div>

      <style>
        @media (max-width: 1024px){
          .section .container > div[style*="grid-template-columns"]{
            grid-template-columns: 1fr !important;
          }
        }
      </style>
    </div>
  </section>
@endsection