@extends('layouts.app')

@section('content')
    @include('components.show-success')

    <div class="card">
        <div class="card-header">
            <h3>Réservations — {{ $hotel->name ?? 'Hôtel' }}</h3>
        </div>

        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Chambre</th>
                    <th>Client</th>
                    <th>Arrivée</th>
                    <th>Départ</th>
                    <th>Clients</th>
                    <th>Prix Total</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($reservations as $reservation)
                    <tr>
                        <th>{{ $loop->iteration }}</th>
                        <td>
                            {{ $reservation->room->room_number ?? '-' }}
                            @if(isset($reservation->room->type))
                                ({{ ['single'=>'Simple','double'=>'Double','suite'=>'Suite','deluxe'=>'Luxe'][$reservation->room->type] ?? ucfirst($reservation->room->type) }})
                            @endif
                        </td>
                        <td>{{ $reservation->user->name ?? '-' }}</td>
                        <td>{{ optional($reservation->check_in)->format('Y-m-d') ?? $reservation->check_in }}</td>
                        <td>{{ optional($reservation->check_out)->format('Y-m-d') ?? $reservation->check_out }}</td>
                        <td>{{ $reservation->guests }}</td>
                        <td>{{ $reservation->total_price !== null ? number_format($reservation->total_price,2).' DH' : '-' }}</td>
                        <td>{{ ['pending'=>'En Attente','confirmed'=>'Confirmé','cancelled'=>'Annulé','completed'=>'Terminé'][$reservation->status] ?? ucfirst($reservation->status) }}</td>
                        <td>
                            {{-- Quick status update --}}
                            <form method="post"
                                  action="{{ route('admin.reservations.update', $reservation->id) }}"
                                  class="d-flex gap-2 align-items-center">
                                @csrf
                                @method('patch')
                                <select name="status" class="form-select form-select-sm">
                                    @foreach(['pending','confirmed','cancelled','completed'] as $status)
                                        <option value="{{ $status }}"
                                                @selected($reservation->status === $status)>
                                            {{ ['pending'=>'En Attente','confirmed'=>'Confirmé','cancelled'=>'Annulé','completed'=>'Terminé'][$status] }}
                                        </option>
                                    @endforeach
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm text-nowrap">
                                    Mettre à jour
                                </button>
                            </form>

                            <div class="mt-2">
                                <a class="btn btn-outline-secondary btn-sm"
                                   href="{{ route('admin.reservations.show', $reservation->id) }}">
                                    Voir
                                </a>
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