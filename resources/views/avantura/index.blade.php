@extends('layouts.site')

@section('title', 'Put vladara')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/avantura.css') }}">
@endpush

@section('content')
<section class="avantura-page">
    <div class="avantura-hero">
        <div class="avantura-hero__overlay"></div>
        <div class="avantura-container avantura-hero__content">
            <span class="avantura-badge">Interaktivna avantura</span>
            <h1>Put vladara</h1>
            <p>
                U ovoj interaktivnoj igri ulazi� u ulogu vladara i donosi� odluke koje oblikuju
                ekonomiju, zadovoljstvo naroda, moc, bezbednost i mudrost dr�ave.
            </p>

            <div class="avantura-hero__actions">
                <a href="{{ route('avantura.play') }}" class="avantura-btn avantura-btn--primary">
                    Zapocni avanturu
                </a>
            </div>
        </div>
    </div>

    <div class="avantura-container avantura-intro-grid">
        <div class="avantura-card">
            <h2>Kako funkcioni�e</h2>
            <p>
                Na svakom koraku dobija� realnu situaciju i bira� jednu od ponudenih odluka.
                Svaka odluka menja stanje tvoje dr�ave.
            </p>
        </div>

        <div class="avantura-card">
            <h2>�ta prati�</h2>
            <ul class="avantura-list">
                <li>Ekonomija</li>
                <li>Zadovoljstvo naroda</li>
                <li>Moc</li>
                <li>Bezbednost</li>
                <li>Mudrost</li>
            </ul>
        </div>

        <div class="avantura-card">
            <h2>Cilj</h2>
            <p>
                Odr�i stabilnost dr�ave, donosi mudre odluke i stigni do kraja prve faze igre.
            </p>
        </div>
    </div>
</section>
@endsection