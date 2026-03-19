@extends('layouts.app')

@section('content')
    {{-- Super Admin Dashboard (Phase 12) --}}
    @include('components.show-success')

    {{-- Controller provides: $stats, $recentReservations, $topHotels --}}
    @php
        $cities = $stats['total_cities'] ?? 0;
        $hotels = $stats['total_hotels'] ?? 0;
        $reservations = $stats['total_reservations'] ?? 0;
        $Admins = \App\Models\User::where('role', \App\Models\User::ROLE_ADMIN)->count();
        $pendingCount = $stats['pending'] ?? 0;
        $confirmedCount = $stats['confirmed'] ?? 0;
    @endphp

    {{-- Summary cards --}}
    <div class="row row-cols-1 row-cols-md-4 g-4">
        <div class="col">
            <div class="card text-white bg-primary">
                <div class="card-header h5 text-center">Villes</div>
                <div class="card-body py-4">
                    <h5 class="text-center mb-0">{{ $cities }}</h5>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card text-white bg-success">
                <div class="card-header h5 text-center">Hôtels</div>
                <div class="card-body py-4">
                    <h5 class="text-center mb-0">{{ $hotels }}</h5>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card text-white bg-dark">
                <div class="card-header h5 text-center">Admins</div>
                <div class="card-body py-4">
                    <h5 class="text-center mb-0">{{ $Admins }}</h5>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card text-white bg-warning">
                <div class="card-header h5 text-center">Réservations</div>
                <div class="card-body py-4">
                    <h5 class="text-center mb-0">{{ $reservations }}</h5>
                    <div class="text-center small mt-2">
                        Pending: {{ $pendingCount }} | Confirmed: {{ $confirmedCount }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent reservations --}}
    <div class="card mt-4">
        <div class="card-header">
            <h3>Réservations Récentes</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Hôtel</th>
                    <th scope="col">Chambre</th>
                    <th scope="col">Client</th>
                    <th scope="col">Dates</th>
                    <th scope="col">Clients</th>
                    <th scope="col">Statut</th>
                    <th>Voir</th>
                </tr>
                </thead>
                <tbody>
                @forelse($recentReservations as $reservation)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $reservation->hotel->name ?? '-' }}</td>
                        <td>
                            {{ $reservation->room->room_number ?? '-' }}
                            @if(isset($reservation->room->type)) ({{ ['single' => 'Simple', 'double' => 'Double', 'suite' => 'Suite', 'deluxe' => 'Luxe'][$reservation->room->type] ?? ucfirst($reservation->room->type) }}) @endif
                        </td>
                        <td>{{ $reservation->user->name ?? '-' }}</td>
                        <td>
                            {{ optional($reservation->check_in)->format('Y-m-d') ?? $reservation->check_in }}
                            &rarr;
                            {{ optional($reservation->check_out)->format('Y-m-d') ?? $reservation->check_out }}
                        </td>
                        <td>{{ $reservation->guests }}</td>
                        <td>{{ ['pending' => 'En Attente', 'confirmed' => 'Confirmé', 'cancelled' => 'Annulé', 'available' => 'Disponible', 'booked' => 'Réservé'][$reservation->status] ?? ucfirst($reservation->status) }}</td>
                        <td>
                            <a class="btn btn-outline-secondary btn-sm"
                               href="{{ route('superAdmin.reservations.show', ['reservation' => $reservation->id]) }}">Détails</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <p class="text-primary fw-bold mb-0">No reservations yet.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Top hotels --}}
    <div class="card mt-4">
        <div class="card-header">
            <h3>Top Hotels (by Reservations)</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Hôtel</th>
                    <th scope="col">Réservations</th>
                </tr>
                </thead>
                <tbody>
                @forelse($topHotels as $hotel)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $hotel->name }}</td>
                        <td>{{ $hotel->reservations_count ?? 0 }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            <p class="text-primary fw-bold mb-0">No hotels found.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

