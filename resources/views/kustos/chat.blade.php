<div class="kustos-chat-inner">
    {{-- Овде се исписују поруке --}}
    <div class="chat-messages" id="chatMessages"></div>
    
    {{-- Форма са скривеним пољима која JavaScript тражи --}}
    <form id="chatForm" class="chat-form">
        {{-- Ово су она два ID-ја која су фалила у HTML-у --}}
        <input type="hidden" id="entitetId" value="{{ $entitet->id }}">
        <input type="hidden" id="entitetTip" value="{{ $tip }}">
        
        <input type="text" id="chatInput" placeholder="Упитај ме..." autocomplete="off" required>
        <button type="submit" id="chatSubmitBtn">➤</button>
    </form>
</div>

<script>
document.getElementById('chatForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const inputField = document.getElementById('chatInput');
    const entitetIdField = document.getElementById('entitetId');
    const entitetTipField = document.getElementById('entitetTip');

    // Провера постојања елемената
    if (!inputField || !entitetIdField || !entitetTipField) {
        console.error("Грешка: Неки елементи форме недостају у HTML-у!");
        return;
    }

    const text = inputField.value.trim();
    if (!text) return;

    appendMessage("Ти: " + text, 'user');
    inputField.value = '';

    fetch("{{ url('/kustos/chat') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            poruka: text,
            id: entitetIdField.value,
            tip: entitetTipField.value
        })
    })
    .then(response => response.json())
    .then(data => {
        appendMessage("Летописац: " + (data.odgovor || "Нема одговора"), 'ai');
    })
    .catch(error => {
        console.error("Грешка:", error);
        appendMessage("Системска грешка при слању.", 'ai');
    });
});

function appendMessage(text, sender) {
    const messagesBox = document.getElementById('chatMessages');
    if (messagesBox) {
        const div = document.createElement('div');
        // Додајемо класе за стилизовање
        div.className = 'chat-message ' + (sender === 'user' ? 'chat-message--user' : 'chat-message--ai');
        div.innerText = text;
        messagesBox.appendChild(div);
        messagesBox.scrollTop = messagesBox.scrollHeight;
    }
}
</script>

<style>
.kustos-chat-inner { display: flex; flex-direction: column; height: 100%; width: 100%; }
.chat-messages { flex: 1; overflow-y: auto; padding: 10px 5px; display: flex; flex-direction: column; gap: 12px; scroll-behavior: smooth; }
.chat-message { max-width: 88%; padding: 12px 16px; line-height: 1.4; font-size: 0.95em; }
.chat-message--user { align-self: flex-end; background: #2a221d; color: #e8dcc5; border-radius: 16px 16px 4px 16px; border: 1px solid #332720; }
.chat-message--ai { align-self: flex-start; background: transparent; color: #c5a059; border-left: 2px solid #c5a059; padding-left: 12px; }
.chat-form { display: flex; gap: 10px; padding-top: 15px; margin-top: auto; border-top: 1px solid #332720; }
#chatInput { flex: 1; background: #120e0c; border: 1px solid #332720; color: #e8dcc5; padding: 14px 18px; border-radius: 24px; outline: none; }
#chatSubmitBtn { background: #c5a059; color: #1a1512; border: none; border-radius: 50%; width: 48px; height: 48px; cursor: pointer; }
</style>