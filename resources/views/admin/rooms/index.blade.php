@extends('layouts.app')

@section('content')
    @include('components.show-success')

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header d-flex align-items-center justify-content-between bg-white border-bottom py-3">
            <h3 class="mb-0 fw-bold">Chambres — {{ $hotel->name ?? 'Hôtel' }}</h3>
            <a href="{{ route('admin.rooms.create') }}" class="btn btn-success btn-sm fw-bold px-3">
                + AJOUTER
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                    <thead class="thead-brand text-white">
                    <tr>
                        <th class="ps-4">#</th>
                        <th class="text-nowrap">Image</th>
                        <th class="text-nowrap">Numéro</th>
                        <th class="text-nowrap">Type</th>
                        <th class="text-nowrap">Prix</th>
                        <th class="text-nowrap text-center">Statut</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($rooms as $room)
                        <tr>
                            <td class="ps-4 text-muted fw-bold">#{{ $room->id }}</td>
                            <td class="py-2">
                                <img src="{{ $room->image ? (Str::startsWith($room->image,'http') ? $room->image : asset('storage/'.$room->image)) : asset('img/rooms/default.jpg') }}"
                                     width="50" height="40" alt="{{ $room->room_number }}" class="rounded shadow-sm" style="object-fit: cover;">
                            </td>
                            <td class="fw-bold text-dark">{{ $room->room_number }}</td>
                            <td class="text-muted">
                                {{ $room->roomType->name ?? 'Standard' }}
                            </td>
                            <td class="fw-bold text-primary">{{ number_format($room->price, 2) }} DH</td>
                            <td class="text-center">
                                @if($room->is_available ?? true)
                                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2">Disponible</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle px-2">Occupée</span>
                                @endif
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('admin.rooms.edit', $room->id) }}" class="btn btn-sm btn-primary text-white px-3 rounded fw-bold" style="font-size: 0.75rem;">
                                        MODIFIER
                                    </a>
                                    <form action="{{ route('admin.rooms.destroy', $room->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger text-white px-3 rounded fw-bold" style="font-size: 0.75rem;" onclick="return confirm('Supprimer cette chambre ?')">
                                            SUPPRIMER
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-4 text-center text-muted">Aucune chambre trouvée.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

