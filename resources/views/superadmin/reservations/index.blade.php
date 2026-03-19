@extends('layouts.app')

@section('content')
    {{-- Super Admin - Reservations List (Phase 12) --}}
    @include('components.show-success')

    {{-- Controller provides: $reservations (paginated) --}}
    <div class="card">
        <div class="card-header">
            <h3>Toutes les Réservations</h3>
        </div>

        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Hôtel</th>
                    <th scope="col">Chambre</th>
                    <th scope="col">Client</th>
                    <th scope="col">Arrivée</th>
                    <th scope="col">Départ</th>
                    <th scope="col">Clients</th>
                    <th scope="col">Statut</th>
                    <th>Détails</th>
                </tr>
                </thead>
                <tbody>
                @forelse($reservations as $reservation)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $reservation->hotel->name ?? '-' }}</td>
                        <td>
                            {{ $reservation->room->room_number ?? '-' }}
                            @if(isset($reservation->room->type))
                                ({{ ['single' => 'Simple', 'double' => 'Double', 'suite' => 'Suite', 'deluxe' => 'Luxe'][$reservation->room->type] ?? ucfirst($reservation->room->type) }})
                            @endif
                        </td>
                        <td>{{ $reservation->user->name ?? '-' }}</td>
                        <td>{{ optional($reservation->check_in)->format('Y-m-d') ?? $reservation->check_in }}</td>
                        <td>{{ optional($reservation->check_out)->format('Y-m-d') ?? $reservation->check_out }}</td>
                        <td>{{ $reservation->guests }}</td>
                        <td>{{ ['pending' => 'En Attente', 'confirmed' => 'Confirmé', 'cancelled' => 'Annulé', 'available' => 'Disponible', 'booked' => 'Réservé'][$reservation->status] ?? ucfirst($reservation->status) }}</td>
                        <td>
                            <a class="btn btn-outline-secondary btn-sm"
                               href="{{ route('superAdmin.reservations.show', ['reservation' => $reservation->id]) }}">Voir</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            <p class="text-primary fw-bold mb-0">Aucune réservation trouvée.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            {{-- Pagination links --}}
            <div class="mt-3">
                {{ $reservations->links() }}
            </div>
        </div>
    </div>
@endsection

