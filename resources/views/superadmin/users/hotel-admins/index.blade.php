@extends('layouts.app')

@section('content')
    @include('components.show-success')

    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Assigner les Admins aux Hôtels</h3>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Hôtel</th>
                    <th>Ville</th>
                    <th>Admin Actuel</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($hotels as $hotel)
                    <tr>
                        <th>{{ $loop->iteration }}</th>
                        <td>{{ $hotel->name }}</td>
                        <td>{{ $hotel->city->name ?? '-' }}</td>
                        <td>
                            @if($hotel->admin)
                                {{ $hotel->admin->name }}
                                <small class="text-muted d-block">{{ $hotel->admin->email }}</small>
                            @else
                                <span class="text-muted">Non assigné</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a class="btn btn-warning btn-sm"
                                   href="{{ route('superadmin.hotel-admins.create', $hotel->id) }}">
                                    Assigner / Changer
                                </a>
                                @if($hotel->admin)
                                    <form method="post"
                                          action="{{ route('superadmin.hotel-admins.destroy', ['hotel' => $hotel->id, 'user' => $hotel->admin->id]) }}"
                                          onsubmit="return confirm('Retirer cet admin ?')">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Retirer
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">
                            <p class="text-primary fw-bold mb-0">Aucun hôtel disponible.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection