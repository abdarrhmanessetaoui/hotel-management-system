@extends('layouts.app')

@section('content')
@php
    $roomsCount        = $stats['total_rooms']        ?? 0;
    $reservationsCount = $stats['total_reservations'] ?? 0;
    $pendingCount      = $stats['pending']             ?? 0;
    $confirmedCount    = $stats['confirmed']           ?? 0;
    $availableRooms    = $stats['available_rooms']     ?? 0;
    $bookedRooms       = $roomsCount - $availableRooms;
    $revenue           = $stats['revenue']             ?? 0;
@endphp

@include('components.show-success')

{{-- Dashboard Overview --}}
<div class="row h-100">
    <div class="col-12 h-100">
        <div class="card h-100 d-flex flex-column border-0 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom">
                <h3 class="mb-0 fw-bold">Aperçu du Système</h3>
            </div>
            <div class="card-body flex-grow-1 d-flex flex-column">
                <div class="dashboard-chart-container flex-grow-1 mb-4" style="width: 100%; min-height: 450px;">
                    <canvas id="mainDashboardChart"></canvas>
                </div>

                <div class="row mt-4 text-center">
                    <div class="col-md-3">
                        <h4 class="fw-bold" style="color: #FF7E21;">{{ $roomsCount }}</h4>
                        <span class="text-muted small text-uppercase">Total Chambres</span>
                    </div>
                    <div class="col-md-3">
                        <h4 class="fw-bold" style="color: #FF7E21;">{{ $reservationsCount }}</h4>
                        <span class="text-muted small text-uppercase">Réservations</span>
                    </div>
                    <div class="col-md-3">
                        <h4 class="fw-bold" style="color: #FF7E21;">{{ $availableRooms }}</h4>
                        <span class="text-muted small text-uppercase">Chambres Disponibles</span>
                    </div>
                    <div class="col-md-3">
                        <h4 class="fw-bold text-success">{{ number_format($revenue, 2) }} DH</h4>
                        <span class="text-muted small text-uppercase">Revenus (MAD)</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js logic matching Super Admin --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('mainDashboardChart').getContext('2d');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['En Attente', 'Confirmées', 'Chambres Réservées', 'Total Réservations'],
                datasets: [{
                    label: 'Quantité',
                    data: [
                        {{ $pendingCount }}, 
                        {{ $confirmedCount }}, 
                        {{ $bookedRooms }},
                        {{ $reservationsCount }}
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
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>

{{-- Removed Réservations Récentes table to match Super Admin layout structure --}}
@endsection
