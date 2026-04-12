@extends('layouts.app')

@section('content')
@php $AdminView = true; @endphp

<div class="container-fluid py-4">
    <div class="row g-4">
        {{-- Section 1: Chat Console --}}
        <div class="col-xl-7 col-lg-6">
            <div class="card border-0 shadow-sm h-100 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-2">
                        <i class="bi bi-robot text-primary fs-4"></i>
                        <div>
                            <h6 class="mb-0 fw-bold">Console Stratégique IA</h6>
                            <small class="text-muted">Analyse de plate-forme en temps réel</small>
                        </div>
                    </div>
                </div>
                <div class="card-body p-0 d-flex flex-column bg-light" style="height: 600px;">
                    <div id="admin-chat-box" class="flex-grow-1 overflow-auto p-4 d-flex flex-column gap-3">
                        {{-- Messages populate here --}}
                    </div>
                    
                    <div id="admin-typing" class="px-4 py-2 d-none">
                        <div class="bg-white border rounded-pill px-3 py-1 shadow-sm d-inline-flex align-items-center gap-2">
                            <small class="text-muted">Calcul des données...</small>
                            <div class="dots-loader-small"><span></span><span></span><span></span></div>
                        </div>
                    </div>

                    <div class="bg-white border-top p-4">
                        <div class="suggestion-tray mb-3" id="admin-suggestions">
                            {{-- Suggestions populate here --}}
                        </div>
                        <div class="input-group dashboard-input p-1 border rounded-pill bg-light shadow-sm">
                            <input type="text" id="admin-chat-input" class="form-control border-0 bg-transparent px-3" placeholder="Tapez votre requête stratégique..." style="box-shadow:none;">
                            <button class="btn btn-primary rounded-circle" id="admin-chat-send" style="width:40px; height:40px; padding:0;">
                                <i class="bi bi-send-fill" style="margin-left: 2px;"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Section 2: AI Metrics --}}
        <div class="col-xl-5 col-lg-6">
            <div class="row g-4">
                <div class="col-6">
                    <div class="card border-0 shadow-sm p-4 h-100 text-center">
                        <i class="bi bi-chat-left-text text-primary fs-1 mb-3"></i>
                        <h3 class="fw-bold mb-0">{{ $totalSessions }}</h3>
                        <small class="text-muted text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 1px;">TOTAL SESSIONS</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card border-0 shadow-sm p-4 h-100 text-center">
                        <i class="bi bi-graph-up-arrow text-success fs-1 mb-3"></i>
                        <h3 class="fw-bold mb-0">{{ $conversionRate }}%</h3>
                        <small class="text-muted text-uppercase fw-bold" style="font-size: 10px; letter-spacing: 1px;">CONVERSION</small>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card border-0 shadow-sm p-4">
                        <h6 class="fw-bold mb-3">Volume d'Activité</h6>
                        <canvas id="usageChart" style="max-height: 220px;"></canvas>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card border-0 shadow-sm p-4">
                        <h6 class="fw-bold mb-3">Top Requêtes Utilisateurs</h6>
                        <div class="list-group list-group-flush">
                            @foreach($topMessages as $msg)
                            <div class="list-group-item px-0 border-0 d-flex justify-content-between align-items-center">
                                <span class="text-muted small"><i class="bi bi-search me-2"></i>{{ $msg->message }}</span>
                                <span class="badge bg-light text-dark border">{{ $msg->count }}</span>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
#admin-chat-box::-webkit-scrollbar { width: 5px; }
#admin-chat-box::-webkit-scrollbar-thumb { background: #ddd; border-radius: 10px; }
.msg { max-width: 80%; padding: 14px 18px; border-radius: 16px; font-size: 14px; position: relative; line-height: 1.6; }
.msg-bot { align-self: flex-start; background: #fff; border: 1px solid #e9ecef; color: #333; box-shadow: 0 2px 5px rgba(0,0,0,0.02); }
.msg-user { align-self: flex-end; background: #FEA116; color: #fff; text-align: right; }
.suggestion-pill-db { display: inline-flex; align-items: center; gap: 8px; padding: 7px 15px; background: #fff; border: 1px solid #ddd; border-radius: 20px; font-size: 12px; cursor: pointer; margin-right: 8px; margin-bottom: 8px; transition: 0.2s; font-weight: 500; }
.suggestion-pill-db:hover { border-color: #FEA116; color: #FEA116; }
.dots-loader-small { display: inline-flex; gap: 3px; }
.dots-loader-small span { width: 4px; height: 4px; background: #999; border-radius: 50%; animation: bounce 1s infinite alternate; }
.dots-loader-small span:nth-child(2) { animation-delay: 0.2s; }
.dots-loader-small span:nth-child(3) { animation-delay: 0.4s; }
@keyframes bounce { to { transform: translateY(-3px); } }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Charts
    const ctx = document.getElementById('usageChart').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($dailyLabels) !!},
            datasets: [{
                label: 'Messages',
                data: {!! json_encode($dailyMessages) !!},
                borderColor: '#FEA116',
                backgroundColor: 'rgba(254, 161, 22, 0.05)',
                tension: 0.4,
                fill: true,
                pointRadius: 3
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, grid: { borderDash:[5,5] } }, x: { grid: { display:false } } }
        }
    });

    // Console Logic
    const chatBox = document.getElementById('admin-chat-box');
    const input = document.getElementById('admin-chat-input');
    const sendBtn = document.getElementById('admin-chat-send');
    const suggestionsTray = document.getElementById('admin-suggestions');
    const typing = document.getElementById('admin-typing');
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    let sessionId = null;
    let sending = false;

    async function init() {
        try {
            const res = await fetch('{{ route('chatbot.session') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken }
            });
            const data = await res.json();
            sessionId = data.session_id;
            render(data.welcome, 'bot');
            renderSuggestions(data.suggestions);
        } catch(e) { render("Erreur d'initialisation.", 'bot'); }
    }

    async function send(text = null) {
        if(sending) return;
        const msg = text || input.value.trim();
        if(!msg || !sessionId) return;
        if(!text) input.value = '';
        
        render(msg, 'user');
        typing.classList.remove('d-none');
        suggestionsTray.innerHTML = '';
        sending = true;

        try {
            const res = await fetch('{{ route('chatbot.message') }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                body: JSON.stringify({ session_id: sessionId, message: msg })
            });
            const data = await res.json();
            typing.classList.add('d-none');
            render(data.reply, 'bot');
            renderSuggestions(data.suggestions);
        } catch(e) {
            typing.classList.add('d-none');
            render("Service temporairement indisponible.", 'bot');
        } finally { sending = false; }
    }

    function render(text, sender) {
        const div = document.createElement('div');
        div.className = `msg msg-${sender}`;
        div.innerHTML = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        chatBox.appendChild(div);
        chatBox.scrollTop = chatBox.scrollHeight;
    }

    function renderSuggestions(sugs) {
        suggestionsTray.innerHTML = '';
        const icons = { 'Performance globale': 'bi-bar-chart-line', 'Hôtels inactifs': 'bi-building-slash', 'Analyser par ville': 'bi-geo-alt', 'Problèmes détectés': 'bi-exclamation-triangle' };
        sugs.forEach(s => {
            const btn = document.createElement('div');
            btn.className = 'suggestion-pill-db';
            btn.innerHTML = `<i class="bi ${icons[s] || 'bi-info-circle'}"></i> ${s}`;
            btn.onclick = () => send(s);
            suggestionsTray.appendChild(btn);
        });
    }

    input.onkeydown = (e) => { if(e.key === 'Enter') send(); };
    sendBtn.onclick = () => send();

    init();
});
</script>
@endsection
