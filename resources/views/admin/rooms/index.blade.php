@extends('layouts.app')

@section('content')
    @include('components.show-success')

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header d-flex align-items-center justify-content-between bg-white border-bottom">
            <h3 class="mb-0 fw-bold">Chambres — {{ $hotel->name ?? 'Hôtel' }}</h3>
            <a href="{{ route('admin.rooms.create') }}" class="btn btn-success btn-sm fw-bold px-3">
                + AJOUTER
            </a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="table-dark">
                <tr>
                    <th class="ps-4">#</th>
                    <th class="text-nowrap">Image</th>
                    <th class="text-nowrap">Numéro</th>
                    <th class="text-nowrap">Type</th>
                    <th class="text-nowrap">Prix</th>
                    <th class="text-nowrap">Statut</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($rooms as $room)
                    <tr>
                        <th class="ps-4 text-muted">{{ $loop->iteration }}</th>
                        <td class="py-2">
                            <img src="{{ $room->image ? (Str::startsWith($room->image,'http') ? $room->image : asset($room->image)) : asset('img/rooms/default.jpg') }}"
                                 width="50" height="40" alt="{{ $room->room_number }}" class="rounded shadow-sm" style="object-fit: cover;">
                        </td>
                        <td class="fw-bold text-nowrap">{{ $room->room_number }}</td>
                        <td class="text-muted text-nowrap">{{ ['single'=>'Simple','double'=>'Double','suite'=>'Suite','deluxe'=>'Luxe'][$room->type] ?? ucfirst($room->type) }}</td>
                        <td class="fw-bold text-nowrap" style="color: #FEA116;">{{ number_format($room->price, 2) }} MAD</td>
                        <td class="text-nowrap">
                            @if($room->status === 'available')
                                <span class="badge bg-success" style="font-size: 0.7rem;">Disponible</span>
                            @else
                                <span class="badge bg-danger" style="font-size: 0.7rem;">Indisponible</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-1">
                                <a class="btn btn-warning btn-sm py-1 px-2 fw-bold text-dark"
                                   href="{{ route('admin.rooms.edit', $room->id) }}" style="font-size: 0.75rem;">
                                    Modifier
                                </a>
                                <form method="post"
                                      action="{{ route('admin.rooms.destroy', $room->id) }}"
                                      class="m-0 p-0 d-inline-block">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm py-1 px-2 fw-bold"
                                            onclick="return confirm('Souhaitez-vous vraiment supprimer cette chambre ?')"
                                            style="font-size: 0.75rem;">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <p class="text-primary fw-bold mb-0 ps-4 py-3">Aucune chambre trouvée.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection