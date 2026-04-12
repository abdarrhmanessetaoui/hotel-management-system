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

{{-- Stat cards --}}
<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4 mb-4">
    <div class="col">
        <div class="card text-white bg-primary h-100">
            <div class="card-header text-center fw-semibold text-white">Total Chambres</div>
            <div class="card-body py-4 text-center">
                <h3 class="mb-0 text-white">{{ $roomsCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card text-white bg-success h-100">
            <div class="card-header text-center fw-semibold text-white">Total Réservations</div>
            <div class="card-body py-4 text-center">
                <h3 class="mb-0 text-white">{{ $reservationsCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card text-white bg-warning h-100">
            <div class="card-header text-center fw-semibold text-white">En Attente</div>
            <div class="card-body py-4 text-center">
                <h3 class="mb-0 text-white">{{ $pendingCount }}</h3>
            </div>
        </div>
    </div>
    <div class="col">
        <div class="card text-white bg-dark h-100">
            <div class="card-header text-center fw-semibold text-white">Confirmées</div>
            <div class="card-body py-4 text-center">
                <h3 class="mb-0 text-white">{{ $confirmedCount }}</h3>
            </div>
        </div>
    </div>
</div>

{{-- Quick overview + Revenue --}}
<div class="row g-4 mb-4">
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header fw-semibold">Aperçu des Chambres</div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span>Disponibles</span>
                    <span class="badge bg-success px-3">{{ $availableRooms }}</span>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Réservées</span>
                    <span class="badge bg-danger px-3">{{ $bookedRooms }}</span>
                </div>
                @if($roomsCount > 0)
                    @php $occupancy = round(($bookedRooms / $roomsCount) * 100); @endphp
                    <div class="mt-3">
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Taux d'occupation</span>
                            <span>{{ $occupancy }}%</span>
                        </div>
                        <div class="progress" style="height:8px;">
                            <div class="progress-bar bg-primary" style="width:{{ $occupancy }}%"></div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card h-100">
            <div class="card-header fw-semibold">Revenus</div>
            <div class="card-body d-flex flex-column justify-content-center align-items-center">
                <small class="text-muted mb-1">Chiffre d'Affaires Total</small>
                <h2 class="text-success fw-bold mb-0">{{ number_format($revenue, 2) }} DH</h2>
            </div>
        </div>
    </div>
</div>

{{-- Recent reservations --}}
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center fw-semibold">
        <span>Réservations Récentes</span>
        <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped table-hover align-middle mb-0">
            <thead class="table-dark">
            <tr>
                <th class="ps-3">Client</th>
                <th>Chambre</th>
                <th>Arrivée</th>
                <th>Départ</th>
                <th>Statut</th>
                <th class="pe-3 text-end">Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse($recentReservations as $reservation)
                <tr>
                    <td class="ps-3">{{ $reservation->user->name ?? '-' }}</td>
                    <td>{{ $reservation->room->room_number ?? '-' }}</td>
                    <td>{{ optional($reservation->check_in)->format('d/m/Y') }}</td>
                    <td>{{ optional($reservation->check_out)->format('d/m/Y') }}</td>
                    <td>
                        @php
                            $badge = match($reservation->status) {
                                'confirmed' => 'bg-success',
                                'cancelled' => 'bg-danger',
                                'completed' => 'bg-secondary',
                                default     => 'bg-warning text-dark',
                            };
                            $labels = ['pending'=>'En Attente','confirmed'=>'Confirmé','cancelled'=>'Annulé','completed'=>'Terminé'];
                        @endphp
                        <span class="badge {{ $badge }}">
                            {{ $labels[$reservation->status] ?? ucfirst($reservation->status) }}
                        </span>
                    </td>
                    <td class="pe-3 text-end">
                        <a href="{{ route('admin.reservations.show', $reservation->id) }}"
                           class="btn btn-sm btn-outline-secondary">Voir</a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-4 text-muted">
                        Aucune réservation pour le moment.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>

@endsection