@extends('layouts.app')

@section('content')
    @include('components.show-success')

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom py-3">
            <h3 class="mb-0 fw-bold">Toutes les Réservations</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                <thead class="thead-brand">
                <tr>
                    <th class="ps-3">#</th>
                    <th>Hôtel</th>
                    <th>Chambre</th>
                    <th>Client</th>
                    <th>Arrivée</th>
                    <th>Départ</th>
                    <th>Voyageurs</th>
                    <th>Statut</th>
                    <th class="pe-3 text-end">Action</th>
                </tr>
                </thead>
                <tbody>
                @forelse($reservations as $reservation)
                    <tr>
                        <th class="ps-3">{{ $loop->iteration }}</th>
                        <td>{{ $reservation->hotel->name ?? '-' }}</td>
                        <td class="fw-bold">
                            {{ $reservation->room->room_number ?? '-' }}
                        </td>
                        <td>{{ $reservation->user->name ?? '-' }}</td>
                        <td>{{ optional($reservation->check_in)->format('d/m/Y') }}</td>
                        <td>{{ optional($reservation->check_out)->format('d/m/Y') }}</td>
                        <td>{{ $reservation->guests }}</td>
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
                        <td colspan="9" class="text-center py-4 text-muted">
                            Aucune réservation trouvée.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            </div>

            <div class="p-3">
                {{ $reservations->links() }}
            </div>
        </div>
    </div>
@endsection