@extends('layouts.site')

@section('title','Dobrodošli — Pravoslavni Svetionik')

@section('content')
<section class="welcome-hero">

<div class="welcome-inner">

<div class="welcome-badge">
✦ Pravoslavni Svetionik
</div>

<h1 class="welcome-title">
Digitalni vodič kroz<br>
<span>manastire Srbije</span>
</h1>

<p class="welcome-sub">
Dobrodošli u aplikaciju posvećenu duhovnoj i kulturnoj baštini
Srpske pravoslavne crkve. Istražite manastire, upoznajte ktitore,
učite kroz interaktivne module i otkrijte pravoslavnu tradiciju
na moderan način.
</p>

<div class="welcome-buttons">

<a href="{{ route('login') }}" class="btn btn-gold">
Prijava
</a>

<a href="{{ route('register') }}" class="btn btn-outline">
Registracija
</a>

<a href="{{ route('pravoslavni.index') }}" class="btn btn-ghost">
Nastavi kao gost
</a>

</div>

<div class="welcome-quote">

<p>
„Ne branimo se od drugih, nego od zla u sebi.“
</p>

<span>
— Patrijarh Pavle
</span>

</div>

</div>

</section>



<style>

.welcome-hero{

min-height:calc(100vh - 120px);

display:flex;
align-items:center;
justify-content:center;

padding:40px;

background:
radial-gradient(circle at 20% 10%, rgba(197,162,74,.18), transparent 40%),
radial-gradient(circle at 80% 0%, rgba(226,194,106,.10), transparent 40%),
linear-gradient(180deg,#0d0606,#160a0a);

position:relative;

overflow:hidden;

}

.welcome-hero::before{

content:"";

position:absolute;

inset:0;

background:
linear-gradient(120deg, rgba(0,0,0,.2), rgba(0,0,0,.6));

pointer-events:none;

}

.welcome-inner{

position:relative;
z-index:2;

max-width:900px;

text-align:center;

}

.welcome-badge{

display:inline-block;

padding:10px 16px;

border-radius:999px;

border:1px solid rgba(197,162,74,.35);

background:rgba(197,162,74,.10);

color:#f0d892;

font-weight:700;

margin-bottom:22px;

letter-spacing:.5px;

}

.welcome-title{

font-size:clamp(2.6rem,5vw,4.5rem);

font-weight:800;

line-height:1.1;

color:#fff;

margin-bottom:20px;

}

.welcome-title span{

color:#c5a24a;

}

.welcome-sub{

font-size:1.05rem;

line-height:1.9;

color:rgba(255,255,255,.80);

margin-bottom:34px;

max-width:720px;

margin-left:auto;
margin-right:auto;

}

.welcome-buttons{

display:flex;

gap:12px;

justify-content:center;

flex-wrap:wrap;

margin-bottom:40px;

}

.btn{

padding:14px 22px;

border-radius:12px;

font-weight:700;

text-decoration:none;

transition:.25s;

}

.btn-gold{

background:linear-gradient(180deg,#e2c26a,#c5a24a);

color:#1b1208;

}

.btn-gold:hover{

transform:translateY(-2px);

}

.btn-outline{

border:1px solid rgba(197,162,74,.45);

color:#e6d19a;

}

.btn-outline:hover{

background:rgba(197,162,74,.08);

}

.btn-ghost{

border:1px solid rgba(255,255,255,.12);

color:#fff;

}

.btn-ghost:hover{

background:rgba(255,255,255,.05);

}

.welcome-quote{

margin-top:10px;

padding-top:20px;

border-top:1px solid rgba(255,255,255,.08);

}

.welcome-quote p{

font-style:italic;

color:#e9dcb2;

font-size:1.1rem;

margin-bottom:6px;

}

.welcome-quote span{

color:rgba(255,255,255,.65);

font-size:.95rem;

}

</style>

@endsection