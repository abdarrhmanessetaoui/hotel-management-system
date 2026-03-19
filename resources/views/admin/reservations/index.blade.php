@extends('layouts.app')

@section('content')
    {{-- Admin Reservations List (Phase 11) --}}
    @include('components.show-success')

    {{-- Controller provides: $hotel, $reservations --}}
    <div class="card">
        <div class="card-header">
            <h3>
                Réservations pour {{ $hotel->name ?? 'Hôtel' }}
            </h3>
        </div>

        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Chambre</th>
                    <th scope="col">Client</th>
                    <th scope="col">Arrivée</th>
                    <th scope="col">Départ</th>
                    <th scope="col">Clients</th>
                    <th scope="col">Prix Total</th>
                    <th scope="col">Statut</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($reservations as $reservation)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
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
                        <td>{{ $reservation->total_price !== null ? '$' . number_format($reservation->total_price, 2) : '-' }}</td>
                        <td>{{ ['pending' => 'En Attente', 'confirmed' => 'Confirmé', 'cancelled' => 'Annulé', 'available' => 'Disponible', 'booked' => 'Réservé'][$reservation->status] ?? ucfirst($reservation->status) }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                {{-- Quick status update (Admin update endpoint) --}}
                                <form
                                    method="post"
                                    action="{{ route('Admin.reservations.update', ['reservation' => $reservation->id]) }}"
                                    class="d-flex gap-2 align-items-center"
                                >
                                    @csrf
                                    @method('patch')

                                    <select name="status" class="form-select form-select-sm" aria-label="Mettre à jour le statut">
                                        @foreach(['pending', 'confirmed', 'cancelled', 'completed'] as $status)
                                            <option value="{{ ['pending' => 'En Attente', 'confirmed' => 'Confirmé', 'cancelled' => 'Annulé', 'available' => 'Disponible', 'booked' => 'Réservé'][$status] ?? $status }}" @selected(old('status', $reservation->status) === $status)>
                                                {{ ['pending' => 'En Attente', 'confirmed' => 'Confirmé', 'cancelled' => 'Annulé', 'available' => 'Disponible', 'booked' => 'Réservé'][$status] ?? ucfirst($status) }}
                                            </option>
                                        @endforeach
                                    </select>

                                    <button type="submit" class="btn btn-primary btn-sm">
Mettre à jour
</button>
                                </form>
                            </div>

                            {{-- Full detail link --}}
                            <div class="mt-2">
                                <a class="btn btn-outline-secondary btn-sm"
                                   href="{{ route('Admin.reservations.show', ['reservation' => $reservation->id]) }}">Voir</a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            <p class="text-primary fw-bold mb-0">Aucune réservation trouvée pour cet hôtel.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

