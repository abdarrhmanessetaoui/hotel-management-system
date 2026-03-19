@extends('layouts.app')

@section('content')
    @include('components.show-success')
    <div class="card">
        <div class="card-header">
            <h3>Tous les Types de Chambres
                <a href="{{ route('Admin.roomtypes.create') }}" class="btn btn-success rounded-circle">
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
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($types as $type)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $type->name }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <form method="post"
                                      action="{{ route('Admin.roomtypes.destroy', ['roomtype' => $type->id]) }}">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                                <a class="btn btn-warning"
                                   href="{{ route('Admin.roomtypes.edit', ['roomtype' => $type->id]) }}">
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">
                            <p class="text-primary fw-bold mb-0">Vous n'avez pas encore créé de types de chambres.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
