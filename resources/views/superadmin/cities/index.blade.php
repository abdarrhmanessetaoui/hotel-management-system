@extends('layouts.app')

@section('content')
    {{-- Super Admin - Cities List (Phase 12) --}}
    @include('components.show-success')

    {{-- Controller provides: $cities --}}
    <div class="card">
        <div class="card-header">
            <h3>Toutes les Villes<a href="{{ route('superAdmin.cities.create') }}" class="btn btn-success rounded-circle ms-2">
                    <i class="fa-solid fa-plus"></i>
                </a>
            </h3>
        </div>

        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Hôtels</th>
                    <th scope="col">Image</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($cities as $city)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $city->name }}</td>
                        <td>{{ $city->hotels_count ?? 0 }}</td>
                        <td>
                            @if(!empty($city->image))
                                <img src="{{ Str::startsWith($city->image, 'http') ? $city->image : asset($city->image) }}" width="50" height="40" alt="{{ $city->name }}">
                            @else
                                <span class="text-muted">-</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a class="btn btn-warning"
                                   href="{{ route('superAdmin.cities.edit', ['city' => $city->id]) }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>

                                <form method="post"
                                      action="{{ route('superAdmin.cities.destroy', ['city' => $city->id]) }}">
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

