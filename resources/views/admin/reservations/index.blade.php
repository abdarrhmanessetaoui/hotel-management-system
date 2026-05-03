@extends('layouts.app')

@section('content')
    @include('components.show-success')

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header d-flex justify-content-between align-items-center bg-white border-bottom">
            <h3 class="mb-0 fw-bold">Réservations — {{ $hotel->name ?? 'Hôtel' }}</h3>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                    <thead class="thead-brand">
                    <tr>
                        <th class="ps-4">#</th>
                        <th class="text-nowrap">Chambre</th>
                        <th class="text-nowrap">Client</th>
                        <th class="text-nowrap">Arrivée</th>
                        <th class="text-nowrap">Départ</th>
                        <th class="text-nowrap">Clients</th>
                        <th class="text-nowrap">Prix Total</th>
                        <th class="text-nowrap">Statut</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($reservations as $reservation)
                        <tr>
                            <th class="ps-4 text-muted">{{ $loop->iteration }}</th>
                            <td class="fw-bold py-3 text-nowrap">
                                {{ $reservation->room->room_number ?? '-' }}
                            </td>
                            <td class="text-nowrap">{{ $reservation->user->name ?? '-' }}</td>
                            <td class="text-nowrap text-muted">{{ optional($reservation->check_in)->format('d/m/Y') ?? $reservation->check_in }}</td>
                            <td class="text-nowrap text-muted">{{ optional($reservation->check_out)->format('d/m/Y') ?? $reservation->check_out }}</td>
                            <td class="text-muted">{{ $reservation->guests }}</td>
                            <td class="fw-bold text-nowrap" style="color: #FF7E21;">{{ $reservation->total_price !== null ? number_format($reservation->total_price,2).' MAD' : '-' }}</td>
                            <td class="text-nowrap">
                                @php
                                    $badge = match($reservation->status) {
                                        'confirmed' => 'bg-success',
                                        'cancelled' => 'bg-danger',
                                        'completed' => 'bg-secondary',
                                        default     => 'bg-warning text-dark',
                                    };
                                @endphp
                                <span class="badge {{ $badge }}" style="font-size: 0.7rem;">
                                    {{ ['pending'=>'En Attente','confirmed'=>'Confirmé','cancelled'=>'Annulé','completed'=>'Terminé'][$reservation->status] ?? ucfirst($reservation->status) }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end align-items-center gap-2">
                                    <form method="post"
                                          action="{{ route('admin.reservations.update', $reservation->id) }}"
                                          class="d-flex align-items-center gap-1 m-0 p-0">
                                        @csrf
                                        @method('patch')
                                        <select name="status" class="form-select form-select-sm" style="font-size: 0.75rem; width: 110px;">
                                            @foreach(['pending','confirmed','cancelled','completed'] as $status)
                                                <option value="{{ $status }}" @selected($reservation->status === $status)>
                                                    {{ ['pending'=>'En Attente','confirmed'=>'Confirmé','cancelled'=>'Annulé','completed'=>'Terminé'][$status] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <button type="submit" class="btn btn-primary btn-sm py-1 px-2 fw-bold" style="font-size: 0.75rem;">
                                            VALIDER
                                        </button>
                                    </form>
                                    <a class="btn btn-secondary btn-sm py-1 px-2 fw-bold"
                                       href="{{ route('admin.reservations.show', $reservation->id) }}" style="font-size: 0.75rem;">
                                        Détails
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="py-4 text-center text-muted">Aucune réservation trouvée pour cet hôtel.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        </div>
    </div>
@endsection

