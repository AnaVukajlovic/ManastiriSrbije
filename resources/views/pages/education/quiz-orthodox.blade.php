@extends('layouts.site')
@section('title','Kviz pravoslavlje')

@section('content')
<section class="section"><div class="container">

<h2>Kviz — pravoslavlje</h2>

<div class="quiz-box">
<p>Šta znači autokefalnost?</p>

<button onclick="a(true)">Samostalnost crkve</button>
<button onclick="a(false)">Vrsta posta</button>

<p id="r"></p>
</div>

</div></section>

<script>
function a(ok){
 document.getElementById('r').innerText =
 ok ? "Tačno ✔" : "Netačno ❌";
}
</script>
@endsection