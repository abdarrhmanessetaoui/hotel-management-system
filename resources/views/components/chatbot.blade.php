<div class="chatbot-wrapper">
    {{-- ── Step 2: Chat Window ──── --}}
    {{-- Reusing '.card' and '.shadow-sm' from project styles --}}
    <div class="chat-window card shadow-sm p-0 mb-3 border-0" id="chatbot-window">
        
        <!-- Native Header Style -->
        <div class="chat-header d-flex justify-content-between align-items-center">
            <h6 class="mb-0 small fw-bold text-uppercase" style="letter-spacing: 1.5px; color: #FFFFFF; font-family: 'Montserrat', sans-serif;">Chat de Support</h6>

            <button class="btn btn-sm p-0 border-0" id="chatbot-close" style="color: #FFFFFF !important;">
                <i class="bi bi-x-lg" style="font-size: 1.1rem;"></i>
            </button>
        </div>


        
        <!-- Messages Area -->
        <div class="chat-messages p-3" id="chatbot-messages">
            <!-- Messages dynamically added here -->

        </div>

        <!-- Suggestions Area -->
        <div id="chatbot-suggestions" class="px-3 d-flex flex-wrap gap-2 mb-2">
            <!-- suggestions like 'Show me available hotels' -->
        </div>

        <!-- Input Area (Sticky Bottom) -->

        <div class="chat-input-area border-top p-3 bg-white">
            <div class="input-group">
                <input type="text" class="form-control form-control-sm border shadow-none" id="chatbot-input" placeholder="Votre message..." autocomplete="off" style="border-radius: 8px 0 0 8px !important;">

                <button class="btn btn-primary btn-sm px-3" id="chatbot-send" style="color: var(--chat-dark) !important; border-radius: 0 8px 8px 0 !important;">
                    <i class="bi bi-send-fill" style="font-size: 14px;"></i>
                </button>

            </div>
        </div>
    </div>

    {{-- ── Step 1: Floating Icon (Bottom-Left) ──── --}}
    <button class="chatbot-fab" id="chatbot-toggle">
        <i class="bi bi-chat-dots-fill"></i>
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const toggle = document.getElementById('chatbot-toggle');
    const close = document.getElementById('chatbot-close');
    const windowEl = document.getElementById('chatbot-window');
    const input = document.getElementById('chatbot-input');
    const send = document.getElementById('chatbot-send');
    const messages = document.getElementById('chatbot-messages');
    
    let sessionId = null;
    const csrfToken = '{{ csrf_token() }}';

    // Render role-based suggestions
    function renderSuggestions(list) {
        messages.scrollTop = messages.scrollHeight;
        const container = document.getElementById('chatbot-suggestions');
        container.innerHTML = '';
        if(!list || list.length === 0) return;

        list.forEach(text => {
            const btn = document.createElement('button');
            btn.className = 'btn btn-sm btn-outline-secondary py-1 px-2 border-0 shadow-none';
            btn.style.fontSize = '12px';
            btn.style.backgroundColor = '#f1f5f9';
            btn.style.color = 'var(--chat-dark)';
            btn.style.fontWeight = '500';
            btn.innerText = text;
            btn.onclick = () => {
                addMsg(text, 'user');
                sendMessageToBackend(text);
            };
            container.appendChild(btn);
        });
    }

    // Step 4: Initialize Chat Session safely
    async function initSession() {
        if(sessionId) return;
        try {
            const res = await fetch('{{ route('chatbot.init') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const data = await res.json();
            sessionId = data.session_id;
            
            // Show initial suggestions
            if(data.suggestions) renderSuggestions(data.suggestions);
            
        } catch (err) {
            console.error('Chatbot Init Error:', err);
        }
    }


    toggle.addEventListener('click', () => {
        windowEl.classList.toggle('active');
        if(windowEl.classList.contains('active')) {
            input.focus();
            initSession(); // Start session on first open
        }
    });

    close.addEventListener('click', () => {
        windowEl.classList.remove('active');
    });

    function addMsg(text, type) {
        if(!text.trim()) return;
        const div = document.createElement('div');
        div.className = `msg msg-${type} mb-2 shadow-sm border-0`;
        div.innerText = text;
        messages.appendChild(div);
        messages.scrollTop = messages.scrollHeight;
    }

    // Step 4: AI Backend Integration with Fallback
    async function sendMessageToBackend(msg) {
        if(!sessionId) await initSession();
        
        try {
            const res = await fetch('{{ route('chatbot.send') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ session_id: sessionId, message: msg })
            });

            if(!res.ok) throw new Error('Network error');

            const data = await res.json();
            addMsg(data.reply, 'bot');
            
            // Update suggestions based on new context
            if(data.suggestions) renderSuggestions(data.suggestions);
            else renderSuggestions([]);

        } catch (err) {
            console.error('Chatbot Error:', err);
            addMsg("Aucune donnée disponible actuellement.", 'bot');
        }

    }

    send.addEventListener('click', () => {
        const val = input.value;
        if(!val) return;
        
        addMsg(val, 'user');
        input.value = '';
        
        sendMessageToBackend(val);
    });

    input.addEventListener('keypress', (e) => {
        if(e.key === 'Enter') {
            e.preventDefault();
            send.click();
        }
    });

    if(window.innerWidth < 576) {
        windowEl.style.width = 'calc(100vw - 60px)';
    }
});
</script>
