@extends('layouts.app')

@section('content')
    {{-- Rooms List (Phase 11) --}}
    {{-- Controller provides: $hotel, $rooms --}}
    @include('components.show-success')
    <div class="card">
        <div class="card-header">
            <h3>Toutes les Chambres<a href="{{ route('Admin.rooms.create') }}" class="btn btn-success rounded-circle">
                    <i class="fa-solid fa-plus"></i>
                </a>
            </h3>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Numéro de Chambre</th>
                    <th scope="col">Type</th>
                    <th scope="col">Prix</th>
                    <th scope="col">Image</th>
                    <th scope="col">Statut</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($rooms as $room)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $room->room_number }}</td>
                        <td>{{ ['single' => 'Simple', 'double' => 'Double', 'suite' => 'Suite', 'deluxe' => 'Luxe'][$room->type] ?? ucfirst($room->type) }}</td>
                        <td>{{ $room->price }}</td>
                        <td>
                            <img
                                src="{{ $room->image ? (Str::startsWith($room->image, 'http') ? $room->image : asset($room->image)) : asset('img/rooms/default.jpg') }}"
                                width="50"
                                height="40"
                                alt="{{ $room->room_number }}"
                            >
                        </td>
                        @if($room->status === 'available')
                            <td class="text-success">Disponible</td>
                        @else
                            <td class="text-danger">Indisponible</td>
                        @endif
                        <td>
                            <div class="btn-group" role="group">
                                <form method="post"
                                      action="{{ route('Admin.rooms.destroy', ['room' => $room->id]) }}">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                                <a class="btn btn-warning"
                                   href="{{ route('Admin.rooms.edit', ['room' => $room->id]) }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <p class="text-primary fw-bold">Vous n'avez pas encore créé de chambres.</p>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
