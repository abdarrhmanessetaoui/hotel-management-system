@extends('layouts.app')

@section('content')
    {{-- Super Admin - Hotels List (Phase 12) --}}
    @include('components.show-success')

    {{-- Controller provides: $hotels --}}
    <div class="card">
        <div class="card-header">
            <h3>Tous les Hôtels<a href="{{ route('superAdmin.hotels.create') }}" class="btn btn-success rounded-circle ms-2">
                    <i class="fa-solid fa-plus"></i>
                </a>
            </h3>
        </div>

        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Hôtel</th>
                    <th scope="col">Ville</th>
                    <th scope="col">Chambres</th>
                    <th scope="col">Réservations</th>
                    <th scope="col">Note</th>
                    <th scope="col">Image</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($hotels as $hotel)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $hotel->name }}</td>
                        <td>{{ $hotel->city->name ?? '-' }}</td>
                        <td>{{ $hotel->rooms_count ?? 0 }}</td>
                        <td>{{ $hotel->reservations_count ?? 0 }}</td>
                        <td>{{ $hotel->rating ?? '-' }}</td>
                        <td>
                            @if(!empty($hotel->image))
                                <img src="{{ Str::startsWith($hotel->image, 'http') ? $hotel->image : asset($hotel->image) }}" width="50" height="40" alt="{{ $hotel->name }}">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a class="btn btn-warning"
                                   href="{{ route('superAdmin.hotels.edit', ['hotel' => $hotel->id]) }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form method="post"
                                      action="{{ route('superAdmin.hotels.destroy', ['hotel' => $hotel->id]) }}">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <p class="text-primary fw-bold mb-0">Aucun hôtel trouvé.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

