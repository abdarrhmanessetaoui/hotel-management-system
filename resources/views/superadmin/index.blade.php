@extends('layouts.app')

@section('content')
    @include('components.show-success')

    @php
        $cities       = $stats['total_cities']       ?? 0;
        $hotels       = $stats['total_hotels']       ?? 0;
        $reservations = $stats['total_reservations'] ?? 0;
        $admins       = \App\Models\User::where('role', \App\Models\User::ROLE_ADMIN)->count();
        $pendingCount = $stats['pending']             ?? 0;
        $confirmedCount = $stats['confirmed']         ?? 0;
    @endphp

    {{-- Stat cards --}}
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4 mb-4">
        <div class="col">
            <div class="card text-white bg-primary h-100">
                <div class="card-header text-center fw-semibold text-white">Villes</div>
                <div class="card-body py-4 text-center">
                    <h3 class="mb-0 text-white">{{ $cities }}</h3>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card text-white bg-success h-100">
                <div class="card-header text-center fw-semibold text-white">Hôtels</div>
                <div class="card-body py-4 text-center">
                    <h3 class="mb-0 text-white">{{ $hotels }}</h3>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card text-white bg-dark h-100">
                <div class="card-header text-center fw-semibold text-white">Admins</div>
                <div class="card-body py-4 text-center">
                    <h3 class="mb-0 text-white">{{ $admins }}</h3>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card text-white bg-warning h-100">
                <div class="card-header text-center fw-semibold text-white">Réservations</div>
                <div class="card-body py-4 text-center">
                    <h3 class="mb-0 text-white">{{ $reservations }}</h3>
                    <small class="text-white">En attente: {{ $pendingCount }} | Confirmées: {{ $confirmedCount }}</small>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent reservations --}}
    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center fw-semibold">
            <span>Réservations Récentes</span>
            <a href="{{ route('superadmin.reservations.index') }}" class="btn btn-sm btn-outline-primary">Voir tout</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover align-middle mb-0">
                <thead class="table-dark">
                <tr>
                    <th class="ps-3">#</th>
                    <th>Hôtel</th>
                    <th>Chambre</th>
                    <th>Client</th>
                    <th>Arrivée → Départ</th>
                    <th>Statut</th>
                    <th class="pe-3 text-end">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($recentReservations as $reservation)
                    <tr>
                        <th class="ps-3">{{ $loop->iteration }}</th>
                        <td>{{ $reservation->hotel->name ?? '-' }}</td>
                        <td>
                            {{ $reservation->room->room_number ?? '-' }}
                            @if(isset($reservation->room->type))
                                <small class="text-muted">({{ ['single'=>'Simple','double'=>'Double','suite'=>'Suite','deluxe'=>'Luxe'][$reservation->room->type] ?? $reservation->room->type }})</small>
                            @endif
                        </td>
                        <td>{{ $reservation->user->name ?? '-' }}</td>
                        <td>
                            {{ optional($reservation->check_in)->format('d/m/Y') }}
                            →
                            {{ optional($reservation->check_out)->format('d/m/Y') }}
                        </td>
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
                            <a class="btn btn-outline-secondary btn-sm"
                               href="{{ route('superadmin.reservations.show', $reservation->id) }}">
                                Voir
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4 text-muted">
                            Aucune réservation pour le moment.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Top hotels --}}
    <div class="card">
        <div class="card-header fw-semibold">Top Hôtels (par Réservations)</div>
        <div class="card-body p-0">
            <table class="table table-striped align-middle mb-0">
                <thead class="table-dark">
                <tr>
                    <th class="ps-3">#</th>
                    <th>Hôtel</th>
                    <th class="pe-3 text-end">Réservations</th>
                </tr>
                </thead>
                <tbody>
                @forelse($topHotels as $hotel)
                    <tr>
                        <th class="ps-3">{{ $loop->iteration }}</th>
                        <td>{{ $hotel->name }}</td>
                        <td class="pe-3 text-end">
                            <span class="badge bg-primary px-3">{{ $hotel->reservations_count ?? 0 }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center py-4 text-muted">Aucun hôtel trouvé.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection