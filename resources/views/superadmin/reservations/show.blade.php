@extends('layouts.app')

@section('content')
    @include('components.show-success')

    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Détails de la Réservation</h3>
        </div>
        <div class="card-body">

            <div class="mb-3">
                <div><strong>Hôtel :</strong> {{ $reservation->hotel->name ?? '-' }}</div>
                <div><strong>Ville :</strong> {{ $reservation->hotel->city->name ?? '-' }}</div>
                <div><strong>Chambre :</strong>
                    {{ $reservation->room->room_number ?? '-' }}
                    @if(isset($reservation->room->type))
                        ({{ ['single'=>'Simple','double'=>'Double','suite'=>'Suite','deluxe'=>'Luxe'][$reservation->room->type] ?? ucfirst($reservation->room->type) }})
                    @endif
                </div>
                <div><strong>Client :</strong> {{ $reservation->user->name ?? '-' }}</div>
                <div><strong>Arrivée :</strong> {{ optional($reservation->check_in)->format('Y-m-d') }}</div>
                <div><strong>Départ :</strong> {{ optional($reservation->check_out)->format('Y-m-d') }}</div>
                <div><strong>Voyageurs :</strong> {{ $reservation->guests }}</div>
                <div><strong>Prix Total :</strong>
                    {{ $reservation->total_price !== null ? number_format($reservation->total_price, 2).' DH' : '-' }}
                </div>
                <div><strong>Statut :</strong>
                    {{ ['pending'=>'En Attente','confirmed'=>'Confirmé','cancelled'=>'Annulé','completed'=>'Terminé'][$reservation->status] ?? ucfirst($reservation->status) }}
                </div>
                @if(!empty($reservation->notes))
                    <div><strong>Notes :</strong> {{ $reservation->notes }}</div>
                @endif
            </div>

            {{-- Status update --}}
            <div class="card mt-4">
                <div class="card-header"><h4 class="mb-0">Mettre à jour le Statut</h4></div>
                <div class="card-body">
                    <form method="post"
                          action="{{ route('superadmin.reservations.update', $reservation->id) }}">
                        @csrf
                        @method('patch')
                        <div class="mb-3">
                            <label class="form-label">Statut</label>
                            <select name="status"
                                    class="form-select @error('status') is-invalid @enderror">
                                @foreach(['pending','confirmed','cancelled','completed'] as $s)
                                    <option value="{{ $s }}" @selected(old('status', $reservation->status) === $s)>
                                        {{ ['pending'=>'En Attente','confirmed'=>'Confirmé','cancelled'=>'Annulé','completed'=>'Terminé'][$s] }}
                                    </option>
                                @endforeach
                            </select>
                            @error('status')
                                <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </form>
                </div>
            </div>

            <div class="mt-3 d-flex gap-2">
                <a href="{{ route('superadmin.reservations.index') }}" class="btn btn-secondary">
                    Retour
                </a>
                <form method="post"
                      action="{{ route('superadmin.reservations.destroy', $reservation->id) }}"
                      onsubmit="return confirm('Supprimer cette réservation définitivement ?')">
                    @csrf
                    @method('delete')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>

        </div>
    </div>
@endsection