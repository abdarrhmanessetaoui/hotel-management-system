@extends('layouts.app')

@section('content')
    @include('components.show-success')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Gestion des Utilisateurs</h3>
            <a href="{{ route('superadmin.users.create') }}" class="btn btn-success btn-sm fw-bold">
                + AJOUTER
            </a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="thead-brand">
                <tr>
                    <th class="ps-4">#</th>
                    <th class="text-nowrap">Nom</th>
                    <th>Email</th>
                    <th class="text-nowrap">Rôle</th>
                    <th class="text-nowrap">Date</th>
                    <th class="text-nowrap">Statut</th>
                    <th class="text-end pe-4">Actions</th>
                </tr>
                </thead>
                <tbody>
                @forelse($users as $user)
                    <tr>
                        <th class="ps-4 text-muted" data-label="#">{{ $loop->iteration }}</th>
                        <td class="fw-bold text-nowrap py-2" data-label="Nom"><span>{{ $user->name }}</span></td>
                        <td class="text-muted" data-label="Email"><span>{{ $user->email }}</span></td>
                        <td class="text-nowrap" data-label="Rôle">
                            @if($user->isSuperAdmin())
                                <span class="badge bg-danger" style="font-size: 0.7rem;">Super Admin</span>
                            @elseif($user->isAdmin())
                                <span class="badge bg-primary" style="font-size: 0.7rem;">Admin Hôtel</span>
                            @else
                                <span class="badge bg-dark" style="font-size: 0.7rem;">Client</span>
                            @endif
                        </td>
                        <td class="text-nowrap text-muted" data-label="Date"><span>{{ $user->created_at->format('d/m/Y') }}</span></td>
                        <td class="text-nowrap" data-label="Statut">
                            @if($user->is_active)
                                <span class="badge bg-success" style="font-size: 0.7rem;">Actif</span>
                            @else
                                <span class="badge bg-secondary" style="font-size: 0.7rem;">Inactif</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-1">
                                <a class="btn btn-warning btn-sm py-1 px-2 fw-bold" href="{{ route('superadmin.users.edit', $user) }}" style="font-size: 0.75rem;">
                                    Modifier
                                </a>

                                <a class="btn btn-secondary btn-sm py-1 px-2 fw-bold" href="{{ route('superadmin.users.show', $user) }}" style="font-size: 0.75rem;">
                                    Détails
                                </a>
                            </div>

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8">
                            <p class="text-primary fw-bold mb-0">Aucun utilisateur trouvé.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            </div>
            
            <div class="p-3">
                {{ $users->links() }}
            </div>
        </div>
    </div>
@endsection
