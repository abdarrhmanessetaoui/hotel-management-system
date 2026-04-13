@extends('layouts.app')

@section('content')
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
            <h5 class="mb-0 text-primary fw-bold">
                <i class="bi bi-robot me-2"></i>Assistant IA — Console d'Administration
            </h5>
            <a href="{{ route('superadmin.chatbot-suggestions.index') }}" class="btn btn-outline-secondary btn-sm">
                Gérer les Suggestions
            </a>
        </div>
        <div class="card-body bg-light p-0">
            <div class="row g-0">
                <!-- Chat Console -->
                <div class="col-lg-8 border-end d-flex flex-column" style="height: 65vh; min-height: 500px;">
                    <div id="admin-chat-box" class="flex-grow-1 overflow-auto p-4 d-flex flex-column gap-3 bg-white">
                        <!-- Messages dynamically appear here -->
                    </div>
                    
                    <div id="admin-typing" class="px-4 py-2 d-none bg-white">
                        <span class="text-muted small fst-italic">Analyse en cours...</span>
                    </div>

                    <div class="p-3 bg-white border-top">
                        <div class="d-flex flex-wrap gap-2 mb-3" id="admin-suggestions">
                            <!-- Suggestions populated by API -->
                        </div>
                        <div class="input-group">
                            <input type="text" id="admin-chat-input" class="form-control" placeholder="Entrez votre requête pour l'intelligence artificielle..." aria-label="Message IA">
                            <button class="btn btn-primary px-4 fw-bold" id="admin-chat-send" type="button">
                                Envoyer
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Chatbot Metrics -->
                <div class="col-lg-4 d-flex flex-column" style="height: 65vh; min-height: 500px; background-color: #f8f9fa;">
                    <div class="p-4 flex-grow-1 overflow-auto">
                        <h6 class="fw-bold mb-4 text-uppercase text-secondary" style="letter-spacing: 1px; font-size: 0.85rem;">Statistiques d'Utilisation</h6>
                        
                        <div class="row g-3 mb-4">
                            <div class="col-6">
                                <div class="card border-0 shadow-sm text-center p-3">
                                    <h4 class="fw-bold text-primary mb-1">{{ $totalSessions }}</h4>
                                    <small class="text-muted">Sessions Totales</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card border-0 shadow-sm text-center p-3">
                                    <h4 class="fw-bold text-success mb-1">{{ $conversionRate }}%</h4>
                                    <small class="text-muted">Résolution</small>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm p-3 mb-4">
                            <h6 class="fw-bold mb-3" style="font-size: 0.9rem;">Activité des 7 derniers jours</h6>
                            <canvas id="usageChart" style="max-height: 180px; width: 100%;"></canvas>
                        </div>

                        <h6 class="fw-bold mb-3 text-uppercase text-secondary" style="letter-spacing: 1px; font-size: 0.85rem;">Requêtes Fréquentes</h6>
                        <ul class="list-group list-group-flush rounded shadow-sm border-0">
                            @foreach($topMessages as $msg)
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-white border-bottom">
                                <span class="small text-truncate" style="max-width: 80%;">{{ $msg->message }}</span>
                                <span class="badge bg-light text-dark border">{{ $msg->count }}</span>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        #admin-chat-box::-webkit-scrollbar { width: 6px; }
        #admin-chat-box::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
        
        .msg { max-width: 85%; padding: 12px 16px; border-radius: 12px; font-size: 0.95rem; line-height: 1.5; word-wrap: break-word; }
        
        /* Bot Message Style */
        .msg-bot { align-self: flex-start; background-color: #f1f5f9; color: #1e293b; border: 1px solid #e2e8f0; border-bottom-left-radius: 2px; }
        
        /* User Message Style */
        .msg-user { align-self: flex-end; background-color: #0d6efd; color: #ffffff; border-bottom-right-radius: 2px; }
        
        .suggestion-pill { cursor: pointer; border: 1px solid #dee2e6; background-color: #ffffff; color: #495057; font-size: 0.85rem; padding: 6px 14px; border-radius: 20px; transition: all 0.2s; font-weight: 500; }
        .suggestion-pill:hover { background-color: #e9ecef; border-color: #ced4da; }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // -- Metrics Chart Logic --
            const ctx = document.getElementById('usageChart');
            if (ctx) {
                new Chart(ctx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($dailyLabels) !!},
                        datasets: [{
                            label: 'Interactions',
                            data: {!! json_encode($dailyMessages) !!},
                            borderColor: '#0d6efd',
                            backgroundColor: 'rgba(13, 110, 253, 0.1)',
                            tension: 0.3,
                            fill: true,
                            borderWidth: 2,
                            pointRadius: 3
                        }]
                    },
                    options: {
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { borderDash: [4, 4] } },
                            x: { grid: { display: false } }
                        },
                        maintainAspectRatio: false
                    }
                });
            }

            // -- Chat Console Logic --
            const chatBox = document.getElementById('admin-chat-box');
            const input = document.getElementById('admin-chat-input');
            const sendBtn = document.getElementById('admin-chat-send');
            const suggestionsTray = document.getElementById('admin-suggestions');
            const typingIndicator = document.getElementById('admin-typing');
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]') ? 
                              document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '';
            
            let sessionId = null;
            let isSending = false;

            async function initializeChat() {
                try {
                    const response = await fetch('{{ route('chatbot.init') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        }
                    });

                    if (!response.ok) throw new Error('Erreur de réseau');

                    const data = await response.json();
                    
                    if (data.status === 'success') {
                        sessionId = data.session_id;
                        renderMessage(data.welcome, 'bot');
                        renderSuggestions(data.suggestions);
                    } else {
                        throw new Error(data.message || 'Erreur inconnue');
                    }
                } catch (error) {
                    renderMessage("Erreur de connexion au serveur IA. Veuillez vérifier l'état du système.", 'bot');
                }
            }

            async function sendMessage(text = null) {
                if (isSending) return;
                
                const messageToSend = text || input.value.trim();
                if (!messageToSend || !sessionId) return;
                
                if (!text) input.value = '';
                
                renderMessage(messageToSend, 'user');
                typingIndicator.classList.remove('d-none');
                suggestionsTray.innerHTML = '';
                
                isSending = true;

                try {
                    const response = await fetch('{{ route('chatbot.send') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            session_id: sessionId,
                            message: messageToSend
                        })
                    });

                    if (!response.ok) throw new Error('Erreur réseau');

                    const data = await response.json();
                    
                    typingIndicator.classList.add('d-none');
                    
                    if (data.status === 'success') {
                        renderMessage(data.reply, 'bot');
                        renderSuggestions(data.suggestions);
                    } else {
                        renderMessage("Le traitement a échoué. " + (data.message || ""), 'bot');
                    }
                } catch (error) {
                    typingIndicator.classList.add('d-none');
                    renderMessage("Service temporairement indisponible. Vérifiez la connexion réseau.", 'bot');
                } finally {
                    isSending = false;
                }
            }

            function renderMessage(text, sender) {
                const messageWrapper = document.createElement('div');
                messageWrapper.className = `msg msg-${sender}`;
                
                messageWrapper.innerHTML = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
                                               .replace(/\n/g, '<br>');
                
                chatBox.appendChild(messageWrapper);
                chatBox.scrollTop = chatBox.scrollHeight;
            }

            function renderSuggestions(suggestionsArray) {
                suggestionsTray.innerHTML = '';
                if (!suggestionsArray || !Array.isArray(suggestionsArray)) return;

                suggestionsArray.forEach(suggestionText => {
                    const pillBtn = document.createElement('span');
                    pillBtn.className = 'suggestion-pill shadow-sm';
                    pillBtn.textContent = suggestionText;
                    pillBtn.onclick = () => sendMessage(suggestionText);
                    suggestionsTray.appendChild(pillBtn);
                });
            }

            // Event Listeners
            if (input) {
                input.addEventListener('keydown', (e) => {
                    if (e.key === 'Enter') sendMessage();
                });
            }
            if (sendBtn) {
                sendBtn.addEventListener('click', () => sendMessage());
            }

            // Start initialization
            initializeChat();
        });
    </script>
@endsection
