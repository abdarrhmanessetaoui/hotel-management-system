@extends('layouts.app')

@section('content')
    {{-- Super Admin - Reservation Detail (Phase 12) --}}
    @include('components.show-success')

    {{-- Controller provides: $reservation --}}
    <div class="card">
        <div class="card-header">
            <h3>Détails de la Réservation</h3>
        </div>

        <div class="card-body">
            {{-- Reservation summary --}}
            <div class="mb-3">
                <div><strong>Hôtel :</strong> {{ $reservation->hotel->name ?? '-' }}</div>
                <div><strong>Ville :</strong> {{ $reservation->hotel->city->name ?? '-' }}</div>
                <div>
                    <strong>Chambre :</strong>
                    {{ $reservation->room->room_number ?? '-' }}
                    @if(isset($reservation->room->type))
                        ({{ ['single' => 'Simple', 'double' => 'Double', 'suite' => 'Suite', 'deluxe' => 'Luxe'][$reservation->room->type] ?? ucfirst($reservation->room->type) }})
                    @endif
                </div>
                <div><strong>Client :</strong> {{ $reservation->user->name ?? '-' }}</div>
                <div><strong>Arrivée :</strong> {{ optional($reservation->check_in)->format('Y-m-d') ?? $reservation->check_in }}</div>
                <div><strong>Départ :</strong> {{ optional($reservation->check_out)->format('Y-m-d') ?? $reservation->check_out }}</div>
                <div><strong>Clients :</strong> {{ $reservation->guests }}</div>
                <div>
                    <strong>Prix Total :</strong>
                    {{ $reservation->total_price !== null ? '$' . number_format($reservation->total_price, 2) : '-' }}
                </div>
                <div><strong>Statut :</strong> {{ ['pending' => 'En Attente', 'confirmed' => 'Confirmé', 'cancelled' => 'Annulé', 'available' => 'Disponible', 'booked' => 'Réservé'][$reservation->status] ?? ucfirst($reservation->status) }}</div>
                @if(!empty($reservation->notes))
                    <div><strong>Notes :</strong> {{ $reservation->notes }}</div>
                @endif
            </div>

            {{-- Status update form --}}
            <div class="card mt-4">
                <div class="card-header">
                    <h4>Mettre à jour le Statut</h4>
                </div>
                <div class="card-body">
                    <form method="post"
                          action="{{ route('superAdmin.reservations.update', ['reservation' => $reservation->id]) }}">
                        @csrf
                        {{-- Controller uses PATCH in resource; keep consistent --}}
                        @method('patch')

                        <div class="mb-3">
                            <label class="form-label">Statut</label>
                            <select name="status"
                                    class="form-select @error('status') is-invalid @enderror">
                                @foreach(['pending', 'confirmed', 'cancelled', 'completed'] as $status)
                                    <option value="{{ ['pending' => 'En Attente', 'confirmed' => 'Confirmé', 'cancelled' => 'Annulé', 'available' => 'Disponible', 'booked' => 'Réservé'][$status ] ?? $status  }}" @selected(old('status', $reservation->status) === $status)>
                                        {{ ['pending' => 'En Attente', 'confirmed' => 'Confirmé', 'cancelled' => 'Annulé', 'available' => 'Disponible', 'booked' => 'Réservé'][$status] ?? ucfirst($status) }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Enregistrer le Statut</button>
                    </form>
                </div>
            </div>

            {{-- Back / delete (optional) --}}
            <div class="mt-3">
                <a href="{{ route('superAdmin.reservations.index') }}" class="btn btn-secondary">
                    Back to Reservations
                </a>

                <form method="post"
                      action="{{ route('superAdmin.reservations.destroy', ['reservation' => $reservation->id]) }}"
                      class="mt-2"
                      onsubmit="return confirm('Delete this reservation permanently?');">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger">
Supprimer
</button>
                </form>
            </div>
        </div>
    </div>
@endsection

