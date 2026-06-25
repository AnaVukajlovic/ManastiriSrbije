<!DOCTYPE html>
<html lang="sr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Светосавски Скиптар: Сенке Бездна</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@600;800&family=Spectral:ital,wght@0,500;0,750;1,500&display=swap" rel="stylesheet">
    
    <style>
        body {
            background-color: #0d0602;
            font-family: 'Spectral', serif;
        }
        .serif-title { font-family: 'Cinzel', serif; }
        .pergament {
            background-color: #f4ebd0;
            background-image: url("https://www.transparenttextures.com/patterns/parchment.png");
            box-shadow: inset 0 0 30px #d4b285, 0 15px 30px rgba(0,0,0,0.7);
        }
        /* Stil za platno igre */
        #gameCanvas {
            cursor: none; /* Sakrivamo običan miš, jer je miš naša baklja */
            box-shadow: 0 20px 50px rgba(0,0,0,0.9);
        }
        .shake { animation: shake 0.5s; }
        @keyframes shake {
            0%, 100% { transform: translate(0, 0); }
            20% { transform: translate(-4px, 2px); }
            40% { transform: translate(4px, -2px); }
            60% { transform: translate(-2px, -2px); }
            80% { transform: translate(2px, 2px); }
        }
    </style>
</head>
<body class="h-screen flex flex-col justify-between text-stone-200 overflow-hidden select-none">

    <div class="w-full bg-stone-950 p-4 flex justify-between items-center border-b-2 border-amber-500/40 shadow-2xl z-10">
        <div class="w-1/4">
            <div class="flex justify-between text-xs text-amber-500 font-bold tracking-widest serif-title">
                <span>☦️ ИСКУШЕНИК</span>
                <span id="player-hp-txt">100 HP</span>
            </div>
            <div class="w-full bg-stone-900 h-3 rounded-full mt-1 border border-amber-500/25 p-0.5 overflow-hidden">
                <div id="player-hp" class="bg-gradient-to-r from-amber-600 to-yellow-500 h-full w-full transition-all duration-500 rounded-full"></div>
            </div>
        </div>
        
        <div class="text-center">
            <div class="text-[11px] text-amber-500/60 font-bold tracking-[0.2em] serif-title mb-0.5">-{ СВЕТОСАВСКИ ЛЕТОПИС }-</div>
            <h1 id="level-title" class="text-amber-400 font-extrabold tracking-widest text-lg uppercase serif-title">НИВО 1: МАНАСТИР КОЈИ ЈЕ НЕСТАО</h1>
            <div class="flex justify-center items-center gap-1 text-xs text-stone-400 font-medium mt-1 tracking-wider">
                <span>✨ Фрагменти Скиптра:</span>
                <span id="fragments" class="text-yellow-400 font-bold bg-amber-950/60 px-2 py-0.5 rounded border border-amber-500/20">0 / 20 👑</span>
            </div>
        </div>

        <div class="w-1/4 text-right">
            <div class="flex justify-between text-xs text-red-600 font-bold tracking-widest serif-title">
                <span id="boss-hp-txt">100 HP</span>
                <span>БЕЗДАН (ТАМА) 👹</span>
            </div>
            <div class="w-full bg-stone-900 h-3 rounded-full mt-1 border border-red-900/30 p-0.5 overflow-hidden">
                <div id="boss-hp" class="bg-gradient-to-r from-red-800 to-rose-600 h-full w-full transition-all duration-500 rounded-full"></div>
            </div>
        </div>
    </div>

    <div class="flex-1 flex gap-6 items-center justify-center px-8 relative bg-stone-900/40">
        
        <div class="relative rounded-lg overflow-hidden border-2 border-amber-600/30">
            <canvas id="gameCanvas" width="700" height="450" class="bg-stone-950"></canvas>
            <div class="absolute bottom-2 left-2 bg-black/70 px-2 py-1 rounded text-[11px] text-amber-400 border border-amber-500/20 pointer-events-none serif-title">
                🔥 Померај миш преко зида да осветлиш тајне симболе
            </div>
        </div>

        <div id="narrator-box" class="w-4/12 src-box pergament border-2 border-amber-700 rounded-lg p-6 shadow-2xl relative min-h-[450px] flex flex-col justify-between text-stone-900 border-double border-4 transition-all">
            <div class="text-center text-amber-900 text-lg font-bold tracking-widest serif-title">☦ ☦ ☦</div>
            
            <div class="my-auto">
                <p id="riddle-text" class="text-center font-serif text-base font-semibold italic leading-relaxed px-2">
                    "Осветли бакљом три скривена крста на зидинама како би ти се приказала клетва Беzdana..."
                </p>
            </div>
            
            <div id="hint-status" class="text-center text-amber-800/80 text-xs font-bold serif-title bg-amber-900/10 py-1 rounded border border-amber-900/20">
                Пронађено симбола: <span id="symbol-count">0</span> / 3
            </div>
        </div>
    </div>

    <div class="w-full bg-stone-950 p-5 border-t-2 border-amber-500/40 shadow-2xl z-10">
        <div class="max-w-3xl mx-auto flex gap-4">
            <input type="text" id="player-input" disabled placeholder="Прво пронађи сва 3 скривена симбола на фрескама..." 
                   class="flex-1 bg-stone-900/50 border border-amber-600/20 rounded-md px-5 py-4 text-sm text-amber-100 placeholder-stone-700 focus:outline-none focus:border-amber-500 font-serif tracking-wide transition-all cursor-not-allowed">
            <button id="action-btn" onclick="posaljiPotez()" disabled class="bg-stone-800 text-stone-500 font-extrabold px-10 py-4 rounded-md text-xs tracking-widest uppercase serif-title border border-stone-700 cursor-not-allowed transition-all">
                ✨ ДЕЈСТВУЈ
            </button>
        </div>
    </div>

    <script>
        const canvas = document.getElementById('gameCanvas');
        const ctx = canvas.getContext('2d');

        let pHP = 100; let bHP = 100; let level = 1; let fragments = 0;
        let mouseX = 0; let mouseY = 0;

        // 2D Logika skrivenih simbola na freskama
        let symbolsFound = 0;
        const secretSymbols = [
            { x: 150, y: 120, r: 20, found: false },
            { x: 550, y: 300, r: 20, found: false },
            { x: 350, y: 220, r: 25, found: false }
        ];

        // Učitavanje autentične pozadine za 2D platno
        const bgImage = new Image();
        bgImage.src = 'https://images.unsplash.com/photo-1599733589046-10c005739ef9?q=80&w=800';

        bgImage.onload = function() {
            drawGame();
        };

        // Praćenje kretanja miša (baklje)
        canvas.addEventListener('mousemove', (e) => {
            const rect = canvas.getBoundingClientRect();
            mouseX = e.clientX - rect.left;
            mouseY = e.clientY - rect.top;
            
            proveriSimbole();
            drawGame();
        });

        function drawGame() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);

            // 1. Crtamo pozadinu (unutrašnjost manastira)
            ctx.drawImage(bgImage, 0, 0, canvas.width, canvas.height);

            // 2. Crtamo simbole ako su osvetljeni ili već pronađeni
            secretSymbols.forEach(sym => {
                let dist = Math.hypot(mouseX - sym.x, mouseY - sym.y);
                if (dist < 80 || sym.found) {
                    ctx.save();
                    ctx.font = `${sym.r * 1.5}px Arial`;
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'middle';
                    ctx.fillStyle = sym.found ? 'rgba(245, 158, 11, 0.9)' : 'rgba(212, 175, 55, 0.4)';
                    // Zlatni sjaj za simbole
                    ctx.shadowBlur = 15;
                    ctx.shadowColor = '#f59e0b';
                    ctx.fillText('☦', sym.x, sym.y);
                    ctx.restore();
                }
            });

            // 3. Efekat mraka (Fog of War) i Svetlosni krug baklje
            ctx.save();
            ctx.fillStyle = 'rgba(10, 5, 2, 0.94)'; // Intenzivan srednjovekovni mrak
            
            ctx.beginPath();
            ctx.rect(0, 0, canvas.width, canvas.height);
            // Izrezujemo krug svetlosti oko baklje
            ctx.arc(mouseX, mouseY, 75, 0, Math.PI * 2, true);
            ctx.fill();
            ctx.restore();

            // 4. Crtamo vizuelni efekat plamena baklje na poziciji miša
            ctx.save();
            let gradient = ctx.createRadialGradient(mouseX, mouseY, 5, mouseX, mouseY, 75);
            gradient.addColorStop(0, 'rgba(253, 224, 71, 0.4)');
            gradient.addColorStop(0.4, 'rgba(249, 115, 22, 0.15)');
            gradient.addColorStop(1, 'rgba(0, 0, 0, 0)');
            ctx.fillStyle = gradient;
            ctx.beginPath();
            ctx.arc(mouseX, mouseY, 75, 0, Math.PI * 2);
            ctx.fill();
            ctx.restore();
        }

        function proveriSimbole() {
            secretSymbols.forEach(sym => {
                if (!sym.found) {
                    let dist = Math.hypot(mouseX - sym.x, mouseY - sym.y);
                    if (dist < 30) { // Ako baklja direktno pređe preko centra
                        sym.found = true;
                        symbolsFound++;
                        document.getElementById('symbol-count').innerText = symbolsFound;
                        
                        if (symbolsFound === 3) {
                            aktivirajZagonetkuNivoa();
                        }
                    }
                }
            });
        }

        function aktivirajZagonetkuNivoa() {
            document.getElementById('riddle-text').innerText = "Учитавам древне списе и клетву Бездана са зидина...";
            
            // Otključavamo polje za unos i dugme
            const input = document.getElementById('player-input');
            const btn = document.getElementById('action-btn');
            
            input.disabled = false;
            input.placeholder = "Упиши име манастира из клетве...";
            input.classList.remove('bg-stone-900/50', 'cursor-not-allowed', 'placeholder-stone-700');
            input.classList.add('bg-stone-900', 'placeholder-stone-600');
            
            btn.disabled = false;
            btn.classList.remove('bg-stone-800', 'text-stone-500', 'cursor-not-allowed', 'border-stone-700');
            btn.classList.add('bg-yellow-500', 'text-black', 'border-amber-500/40', 'hover:from-amber-600');

            // Pozivamo Laravel backend da Llama preko Groq-a generiše tekst zagonetke
            fetch(`/api/game/riddle?level=1`)
                .then(res => res.json())
                .then(data => {
                    document.getElementById('riddle-text').innerText = data.riddle;
                });
        }

        function posaljiPotez() {
            const inputField = document.getElementById('player-input');
            const unos = inputField.value;
            if(!unos) return;

            fetch('/api/game/verify', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify({ level: level, unos: unos })
            })
            .then(res => res.json())
            .then(data => {
                inputField.value = '';
                document.getElementById('riddle-text').innerText = data.message;

                if(data.success) {
                    bHP = 0;
                    fragments = 1;
                    document.getElementById('boss-hp').style.width = '0%';
                    document.getElementById('boss-hp-txt').innerText = '0 HP';
                    document.getElementById('fragments').innerText = `${fragments} / 20 👑`;
                    document.getElementById('narrator-box').classList.add('shake');
                    
                    setTimeout(() => {
                        alert("Успешно сте решили први ниво! Прелазите на Ниво 2.");
                        // Ovde se kasnije dodaje preusmeravanje ili promena nivoa
                    }, 3000);
                } else {
                    pHP -= 25;
                    document.getElementById('player-hp').style.width = pHP + '%';
                    document.getElementById('player-hp-txt').innerText = pHP + ' HP';
                    document.getElementById('narrator-box').classList.add('shake');
                    setTimeout(() => document.getElementById('narrator-box').classList.remove('shake'), 500);
                    
                    if(pHP <= 0) {
                        alert("Тама је прекрила ваше сећање. Игра се рестартује.");
                        location.reload();
                    }
                }
            });
        }
    </script>
</body>
</html>