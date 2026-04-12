@extends('layouts.app')

@section('content')
    @include('components.show-success')

    <div class="card">
        <div class="card-header d-flex align-items-center justify-content-between">
            <h3 class="mb-0">Chambres — {{ $hotel->name ?? 'Hôtel' }}</h3>
            <a href="{{ route('admin.rooms.create') }}" class="btn btn-success btn-sm">
                <i class="fa-solid fa-plus me-1"></i>Ajouter
            </a>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Numéro</th>
                    <th>Type</th>
                    <th>Prix</th>
                    <th>Image</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($rooms as $room)
                    <tr>
                        <th>{{ $loop->iteration }}</th>
                        <td>{{ $room->room_number }}</td>
                        <td>{{ ['single'=>'Simple','double'=>'Double','suite'=>'Suite','deluxe'=>'Luxe'][$room->type] ?? ucfirst($room->type) }}</td>
                        <td>{{ number_format($room->price, 2) }} DH</td>
                        <td>
                            <img src="{{ $room->image ? (Str::startsWith($room->image,'http') ? $room->image : asset($room->image)) : asset('img/rooms/default.jpg') }}"
                                 width="50" height="40" alt="{{ $room->room_number }}" class="rounded">
                        </td>
                        <td>
                            @if($room->status === 'available')
                                <span class="badge bg-success">Disponible</span>
                            @else
                                <span class="badge bg-danger">Indisponible</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a class="btn btn-warning btn-sm"
                                   href="{{ route('admin.rooms.edit', $room->id) }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form method="post"
                                      action="{{ route('admin.rooms.destroy', $room->id) }}">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                            onclick="return confirm('Supprimer cette chambre ?')">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <p class="text-primary fw-bold mb-0">Aucune chambre trouvée.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection