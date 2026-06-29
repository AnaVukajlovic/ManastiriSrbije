@extends('layouts.site')
@section('title','Kviz — Pravoslavlje')

@section('content')
<section class="section quiz-orthodox-page">
    <div class="container">
        <header class="quiz-header">
            <div class="quiz-badge">Interaktivna edukacija</div>
            <h1 class="quiz-title">Kviz — Osnovi pravoslavne vere</h1>
            <p class="quiz-desc">Proveri svoje znanje o bogosluženju, praznicima, simbolima i duhovnom životu.</p>
        </header>

        @if($result)
        <div class="quiz-result-banner">
            <div class="result-content">
                <h3>Rezultati istraživanja</h3>
                <p class="score-main">Tvoj rezultat: <strong>{{ $result['percent'] ?? 0 }}%</strong></p>
                <p>Tačnih odgovora: {{ $result['score'] ?? 0 }} od {{ $result['max'] ?? 0 }}</p>
                <button type="button" class="btn-restart" onclick="restartOrthodoxQuiz()">⟳ Generiši novi kviz</button>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('edukacija.quiz-orthodox.submit') }}">
            @csrf
            <div class="quiz-grid">
                @foreach($questions as $i => $q)
                    <article class="quiz-card">
                        <div class="quiz-card__head">
                            <span class="quiz-number">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <h3 class="quiz-card__title">{{ $q['q'] ?? 'Pitanje nedostaje' }}</h3>
                        </div>

                        <div class="ai-module">
                            <button type="button" class="ai-btn" onclick="askAi(this, '{{ addslashes($q['q'] ?? '') }}')">
                                ✦ Pitaj AI savetnika
                            </button>
                            <div class="ai-output" style="display: none;"></div>
                        </div>

                        <div class="quiz-options">
                            @foreach(($q['options'] ?? []) as $idx => $opt)
                                <label class="quiz-option {{ ($result && (string)$idx === (string)($q['correct'] ?? -1)) ? 'is-correct' : '' }}">
                                    <input type="radio" name="answers[{{ $q['id'] ?? $i }}]" value="{{ $idx }}" {{ $result ? 'disabled' : '' }}>
                                    <span class="quiz-option__text">{{ $opt }}</span>
                                </label>
                            @endforeach
                        </div>
                    </article>
                @endforeach
            </div>
            @if(!$result)
                <button class="btn-submit" type="submit">Završi kviz</button>
            @endif
        </form>
    </div>
</section>

<style>
    /* Stilovi su identični onim od istorijskog kviza, nema potrebe da ih dupliraš ako su u glavnom CSS-u */
    .quiz-header { text-align: center; margin-bottom: 50px; }
    .quiz-badge { background: rgba(197, 162, 74, 0.1); color: #c5a24a; padding: 6px 16px; border-radius: 999px; display: inline-block; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 2px; }
    .quiz-result-banner { background: linear-gradient(135deg, #1a1a1a 0%, #000 100%); border: 1px solid #c5a24a; padding: 40px; border-radius: 24px; text-align: center; margin-bottom: 50px; }
    .score-main { font-size: 2.5rem; color: #fff; margin: 10px 0; }
    .quiz-grid { display: grid; gap: 25px; max-width: 800px; margin: 0 auto; }
    .quiz-card { background: #1a1a1a; padding: 30px; border-radius: 20px; border: 1px solid #333; }
    .quiz-number { color: #c5a24a; font-size: 2rem; font-weight: 800; display: block; margin-bottom: 10px; }
    .quiz-card__title { color: #fff; font-size: 1.3rem; margin-bottom: 20px; }
    .quiz-option { display: block; background: #252525; padding: 15px; border-radius: 10px; margin-bottom: 10px; cursor: pointer; border: 1px solid transparent; color: #fff; }
    .is-correct { border-color: #2ecc71 !important; background: rgba(46, 204, 113, 0.1) !important; }
    .ai-btn { background: transparent; border: 1px solid #c5a24a; color: #c5a24a; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-size: 0.8rem; margin-bottom: 10px; }
    .ai-output { padding: 15px; background: #111; border-left: 3px solid #c5a24a; color: #ccc; font-style: italic; font-size: 0.9rem; margin-top: 10px; }
    .btn-submit, .btn-restart { background: #c5a24a; color: #000; padding: 15px 40px; border-radius: 10px; border: none; font-weight: 800; cursor: pointer; margin-top: 20px; }
</style>

<script>
async function askAi(btn, text) {
    const box = btn.nextElementSibling;
    box.style.display = 'block';
    box.innerHTML = "<em>Savetnik analizira teološke zapise...</em>";
    const res = await fetch('{{ route('edukacija.ai.chat') }}', {
        method: 'POST',
        headers: {'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}'},
        body: JSON.stringify({ question: text, mode: 'socratic_hint' })
    });
    const data = await res.json();
    box.innerHTML = "<strong>Savetnik:</strong> " + data.answer;
}

async function restartOrthodoxQuiz() {
    const btn = document.querySelector('.btn-restart');
    btn.innerHTML = "Generisanje novih podataka...";
    btn.disabled = true;

    await fetch('{{ route('edukacija.ai.regenerate') }}', { 
        method: 'POST', 
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ type: 'orthodox' }) // <--- OVO JE KLJUČNO
    });
    window.location.href = "{{ route('edukacija.quiz-orthodox') }}";
}
</script>
@endsection