@php
    $entitetIme = $entitet->ime ?? $entitet->name ?? ($tip === 'manastir' ? 'овом светом месту' : 'овом ктитору');
    if ($tip === 'manastir') {
        $pocetniPozdrav = "Мир са тобом. Добродошли у {$entitetIme}. Као Дигитални Летописац, овде сам да вам помогнем у истраживању историје, архитектуре и предања ове светиње. Слободно ме упитајте све што вас занима.";
    } else {
        $pocetniPozdrav = "Мир са тобом. Добродошли. Овде смо да заједно истражимо живот, задужбине и историјско наслеђе које је оставио {$entitetIme}. Као Дигитални Летописац, слободно ме упитајте било шта о његовој владавини, делима и светињама.";
    }
@endphp

<div class="kustos-chat-inner">
    {{-- Овде се исписују поруке --}}
    <div class="chat-messages" id="chatMessages">
        {{-- Почетни уводни поздрав који је увек приказан одмах --}}
        <div class="chat-message chat-message--ai">
            <div class="chat-message__author">📜 Дигитални Летописац</div>
            <div class="chat-message__body">{{ $pocetniPozdrav }}</div>
        </div>
    </div>
    
    {{-- Форма са скривеним пољима --}}
    <form id="chatForm" class="chat-form">
        <input type="hidden" id="entitetId" value="{{ $entitet->id }}">
        <input type="hidden" id="entitetTip" value="{{ $tip }}">
        
        <input type="text" id="chatInput" placeholder="Упитај Летописца..." autocomplete="off" required>
        <button type="submit" id="chatSubmitBtn" title="Пошаљи поруку">➤</button>
    </form>
</div>

<script>
(function() {
    const chatForm = document.getElementById('chatForm');
    if (!chatForm) return;

    chatForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const inputField = document.getElementById('chatInput');
        const entitetIdField = document.getElementById('entitetId');
        const entitetTipField = document.getElementById('entitetTip');

        if (!inputField || !entitetIdField || !entitetTipField) return;

        const text = inputField.value.trim();
        if (!text) return;

        appendKustosMessage(text, 'user');
        inputField.value = '';
        
        // Privremeni loading indikator
        const loadingId = 'loading-' + Date.now();
        appendKustosMessage("Летописац размишља...", 'ai', loadingId);

        fetch("{{ url('/kustos/chat') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({
                poruka: text,
                id: entitetIdField.value,
                tip: entitetTipField.value
            })
        })
        .then(response => response.json())
        .then(data => {
            const loadingEl = document.getElementById(loadingId);
            if (loadingEl) loadingEl.remove();

            const reply = data.odgovor || data.answer || data.reply || data.greeting || "Летописац тренутно није могао да пронађе одговор.";
            appendKustosMessage(reply, 'ai');
        })
        .catch(error => {
            console.error("Грешка при слању:", error);
            const loadingEl = document.getElementById(loadingId);
            if (loadingEl) loadingEl.remove();
            appendKustosMessage("Дошло је до грешке у комуникацији. Покушајте поново.", 'ai');
        });
    });

    function formatText(str) {
        if (!str) return '';
        let esc = str
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;");
        esc = esc.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        esc = esc.replace(/\n/g, '<br>');
        return esc;
    }

    function appendKustosMessage(text, sender, elementId = null) {
        const messagesBox = document.getElementById('chatMessages');
        if (!messagesBox) return;

        const div = document.createElement('div');
        if (elementId) div.id = elementId;
        div.className = 'chat-message ' + (sender === 'user' ? 'chat-message--user' : 'chat-message--ai');
        
        if (sender === 'user') {
            div.innerHTML = `<div class="chat-message__author">Ви</div><div class="chat-message__body">${formatText(text)}</div>`;
        } else {
            div.innerHTML = `<div class="chat-message__author">📜 Дигитални Летописац</div><div class="chat-message__body">${formatText(text)}</div>`;
        }
        
        messagesBox.appendChild(div);
        messagesBox.scrollTop = messagesBox.scrollHeight;
    }
})();
</script>

<style>
.kustos-chat-inner { 
    display: flex; 
    flex-direction: column; 
    height: 100%; 
    width: 100%; 
    min-height: 0;
}
.chat-messages { 
    flex: 1; 
    overflow-y: auto; 
    padding: 14px 8px; 
    display: flex; 
    flex-direction: column; 
    gap: 14px; 
    scroll-behavior: smooth;
    min-height: 220px;
    max-height: 480px;
}
.chat-message { 
    max-width: 90%; 
    padding: 12px 16px; 
    line-height: 1.55; 
    font-size: 0.95em; 
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}
.chat-message__author {
    font-size: 0.8rem;
    font-weight: 700;
    margin-bottom: 4px;
    letter-spacing: 0.5px;
}
.chat-message--user { 
    align-self: flex-end; 
    background: #2b211a; 
    color: #f1e6d4; 
    border-radius: 16px 16px 4px 16px; 
    border: 1px solid #4a382d; 
}
.chat-message--user .chat-message__author {
    color: #e2c26a;
    text-align: right;
}
.chat-message--ai { 
    align-self: flex-start; 
    background: #1e1814; 
    color: #e5d8c3; 
    border-left: 3px solid #c5a059; 
    border-radius: 4px 16px 16px 16px;
    border-top: 1px solid #332720;
    border-right: 1px solid #332720;
    border-bottom: 1px solid #332720;
}
.chat-message--ai .chat-message__author {
    color: #c5a059;
}
.chat-message__body {
    word-break: break-word;
}
.chat-message__body strong {
    color: #f3d790;
}
.chat-form { 
    display: flex; 
    gap: 10px; 
    padding-top: 15px; 
    margin-top: auto; 
    border-top: 1px solid #332720; 
}
#chatInput { 
    flex: 1; 
    background: #120e0c; 
    border: 1px solid #423229; 
    color: #f5ede0; 
    padding: 12px 18px; 
    border-radius: 24px; 
    outline: none; 
    font-size: 0.95rem;
    transition: border-color 0.2s;
}
#chatInput:focus {
    border-color: #c5a059;
}
#chatSubmitBtn { 
    background: #c5a059; 
    color: #1a1512; 
    border: none; 
    border-radius: 50%; 
    width: 46px; 
    height: 46px; 
    cursor: pointer; 
    font-size: 1.1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s, background-color 0.2s;
}
#chatSubmitBtn:hover {
    background: #deb76b;
    transform: scale(1.05);
}
</style>