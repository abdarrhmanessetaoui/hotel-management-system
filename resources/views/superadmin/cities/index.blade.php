@extends('layouts.app')

@section('content')
    @include('components.show-success')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Toutes les Villes</h3>
            <a href="{{ route('superadmin.cities.create') }}" class="btn btn-success btn-sm">
                <i class="fa-solid fa-plus me-1"></i>Ajouter
            </a>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Nom</th>
                    <th>Hôtels</th>
                    <th>Image</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($cities as $city)
                    <tr>
                        <th>{{ $loop->iteration }}</th>
                        <td>{{ $city->name }}</td>
                        <td>{{ $city->hotels_count ?? 0 }}</td>
                        <td>
                            @if(!empty($city->image))
                                <img src="{{ Str::startsWith($city->image,'http') ? $city->image : asset($city->image) }}"
                                     width="50" height="40" class="rounded" alt="{{ $city->name }}">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a class="btn btn-warning btn-sm"
                                   href="{{ route('superadmin.cities.edit', $city->id) }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                                <form method="post"
                                      action="{{ route('superadmin.cities.destroy', $city->id) }}"
                                      onsubmit="return confirm('Supprimer cette ville ?')">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <p class="text-primary fw-bold mb-0">Aucune ville trouvée.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection