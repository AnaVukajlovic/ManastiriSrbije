@extends('layouts.site')
@section('title','Kviz istorija')

@section('content')
<section class="section"><div class="container">

<h2>Kviz — srednjovekovna Srbija</h2>

<div class="quiz-box">
<p>Ko je osnovao dinastiju Nemanjića?</p>
<button onclick="answer(true)">Stefan Nemanja</button>
<button onclick="answer(false)">Car Dušan</button>

<p id="quizResult"></p>
</div>

</div></section>

<script>
function answer(ok){
 document.getElementById('quizResult').innerText =
 ok ? "Tačno ✔" : "Netačno ❌";
}
</script>
@endsection