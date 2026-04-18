@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Aperçu du Système</h3>
                </div>
                <div class="card-body">
                    <div style="height: 500px; width: 100%;">
                        <canvas id="mainDashboardChart"></canvas>
                    </div>

                    <div class="row mt-4 text-center">
                        <div class="col-md-3">
                            <h4 class="fw-bold" style="color: #FF7E21;">{{ $stats['total_cities'] }}</h4>
                            <span class="text-muted small text-uppercase">Total Villes</span>
                        </div>
                        <div class="col-md-3">
                            <h4 class="fw-bold" style="color: #FF7E21;">{{ $stats['total_hotels'] }}</h4>
                            <span class="text-muted small text-uppercase">Total Hôtels</span>
                        </div>
                        <div class="col-md-3">
                            <h4 class="fw-bold" style="color: #FF7E21;">{{ $stats['total_users'] }}</h4>
                            <span class="text-muted small text-uppercase">Total Utilisateurs</span>
                        </div>
                        <div class="col-md-3">
                            <h4 class="fw-bold" style="color: #FF7E21;">{{ $stats['total_reviews'] }}</h4>
                            <span class="text-muted small text-uppercase">Total Avis </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('mainDashboardChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: ['Villes', 'Hôtels', 'Utilisateurs'],
                    datasets: [{
                        label: 'Quantité',
                        data: [
                            {{ $stats['total_cities'] }}, 
                            {{ $stats['total_hotels'] }}, 
                            {{ $stats['total_users'] }}
                        ],
                        borderColor: '#FF7E21',
                        backgroundColor: 'rgba(254, 161, 22, 0.1)',
                        fill: true,
                        tension: 0.1,
                        borderWidth: 2,
                        pointBackgroundColor: '#FF7E21',
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
@endsection