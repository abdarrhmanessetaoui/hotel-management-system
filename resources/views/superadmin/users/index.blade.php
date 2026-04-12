@extends('layouts.app')

@section('content')
<div class="container-fluid p-0">
    {{-- Original Header Style --}}
    <div class="bg-white p-4 mb-4 border-bottom d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-0">Gestion des Utilisateurs</h2>
            <p class="text-muted small">Contrôlez les comptes clients et administrateurs.</p>
        </div>
        <a href="{{ route('superadmin.users.create') }}" class="btn btn-primary">
            + NOUVEL UTILISATEUR
        </a>
    </div>

    <div class="px-4">
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4">{{ session('success') }}</div>
        @endif

        <div class="card border-0">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th class="ps-4 py-3">Réf.</th>
                                <th class="py-3">Utilisateur</th>
                                <th class="py-3">Rôle / Accès</th>
                                <th class="py-3">Établissement</th>
                                <th class="py-3 text-center pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            @foreach($users as $user)
                            <tr>
                                <td class="ps-4 fw-bold">#{{ $user->id }}</td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $user->name }}</div>
                                    <div class="text-muted small">{{ $user->email }}</div>
                                </td>
                                <td>
                                    @if($user->isSuperAdmin())
                                        <span class="badge bg-danger px-3 py-2 rounded-0">SUPER ADMIN</span>
                                    @elseif($user->isAdmin())
                                        <span class="badge bg-primary px-3 py-2 rounded-0">HOTEL ADMIN</span>
                                    @else
                                        <span class="badge bg-dark px-3 py-2 rounded-0">CLIENT</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->hotel)
                                        <span class="small fw-semibold text-dark">{{ $user->hotel->name }}</span>
                                    @elseif($user->isAdmin())
                                        <span class="text-muted small">Aucun hôtel</span>
                                    @else
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <div class="d-flex justify-content-center gap-1">
                                        <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-sm btn-outline-dark">EDITER</a>
                                        
                                        @if($user->id !== auth()->id())
                                            <form action="{{ route('superadmin.users.destroy', $user) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">SUPPRIMER</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if($users->hasPages())
            <div class="card-footer bg-white py-3 border-top">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
