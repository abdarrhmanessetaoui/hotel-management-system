@extends('layouts.app')

@section('content')
    {{-- Super Admin - Hotel Admin Assignments (Phase 12) --}}
    @include('components.show-success')

    {{-- Controller provides: $hotels --}}
    <div class="card">
        <div class="card-header">
            <h3>Assign Hotel Admins</h3>
        </div>

        <div class="card-body">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Hôtel</th>
                    <th scope="col">Ville</th>
                    <th scope="col">Current Admin</th>
                    <th scope="col">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($hotels as $hotel)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $hotel->name }}</td>
                        <td>{{ $hotel->city->name ?? '-' }}</td>
                        <td>
                            @if($hotel->Admin)
                                {{ $hotel->Admin->name }}
                            @else
                                <span class="text-muted">Not assigned</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                {{-- Go to assignment form --}}
                                <a class="btn btn-warning btn-sm"
                                   href="{{ route('superAdmin.hotel-Admins.create', ['hotel' => $hotel->id]) }}">
                                    Assign / Change
                                </a>

                                {{-- Remove Admin (only if assigned) --}}
                                @if($hotel->Admin)
                                    <form method="post"
                                          action="{{ route('superAdmin.hotel-Admins.destroy', ['hotel' => $hotel->id, 'user' => $hotel->Admin->id]) }}"
                                          onsubmit="return confirm('Remove this Admin from the hotel?');">
                                        @csrf
                                        @method('delete')
                                        <button type="submit" class="btn btn-danger btn-sm">
                                            Remove
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">
                            <p class="text-primary fw-bold mb-0">No hotels available.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

