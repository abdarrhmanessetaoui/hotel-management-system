@extends('layouts.app')

@section('content')
    {{-- Admin Reservation Detail (Phase 11) --}}
    @include('components.show-success')

    {{-- Controller provides: $reservation --}}
    <div class="card">
        <div class="card-header">
            <h3>Détails de la Réservation</h3>
        </div>

        <div class="card-body">
            {{-- Reservation summary --}}
            <div class="mb-3">
                <div><strong>Chambre :</strong> {{ $reservation->room->room_number ?? '-' }}</div>
                <div><strong>Client :</strong> {{ $reservation->user->name ?? '-' }}</div>
                <div><strong>Arrivée :</strong> {{ optional($reservation->check_in)->format('Y-m-d') ?? $reservation->check_in }}</div>
                <div><strong>Départ :</strong> {{ optional($reservation->check_out)->format('Y-m-d') ?? $reservation->check_out }}</div>
                <div><strong>Clients :</strong> {{ $reservation->guests }}</div>
                <div><strong>Prix Total :</strong>
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
                          action="{{ route('Admin.reservations.update', ['reservation' => $reservation->id]) }}">
                        @csrf
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

            {{-- Back link -- }}
            <div class="mt-3">
                <a href="{{ route('Admin.reservations.index') }}" class="btn btn-secondary">
                    Back to Reservations
                </a>
            </div>
        </div>
    </div>
@endsection

