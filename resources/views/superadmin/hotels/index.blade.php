@extends('layouts.app')

@section('content')
    @include('components.show-success')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Tous les Hôtels</h3>
            <a href="{{ route('superadmin.hotels.create') }}" class="btn btn-success btn-sm fw-bold">
                + AJOUTER
            </a>
        </div>
        <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead class="thead-brand">
                <tr>
                    <th>#</th>
                    <th>Hôtel</th>
                    <th>Ville</th>
                    <th>Chambres</th>
                    <th>Réservations</th>
                    <th>Note</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($hotels as $hotel)
                    <tr>
                        <th>{{ $loop->iteration }}</th>
                        <td>{{ $hotel->name }}</td>
                        <td>{{ $hotel->city->name ?? '-' }}</td>
                        <td>{{ $hotel->rooms_count ?? 0 }}</td>
                        <td>{{ $hotel->reservations_count ?? 0 }}</td>
                        <td>{{ $hotel->rating ?? '-' }}</td>
                        <td>
                            @if(!empty($hotel->image))
                                <img src="{{ Str::startsWith($hotel->image,'http') ? $hotel->image : asset($hotel->image) }}"
                                     width="50" height="40" class="rounded" alt="{{ $hotel->name }}">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a class="btn btn-primary btn-sm"
                                   href="{{ route('superadmin.hotels.edit', $hotel->id) }}">
                                    Modifier
                                </a>
                                <form method="post"
                                      action="{{ route('superadmin.hotels.destroy', $hotel->id) }}"
                                      onsubmit="return confirm('Supprimer cet hôtel ?')">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm ms-1">
                                        Supprimer
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
    </div>
@endsection