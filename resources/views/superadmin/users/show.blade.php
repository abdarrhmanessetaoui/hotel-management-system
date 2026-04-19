@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">
                <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary btn-sm me-3">
                    <i class="fa fa-arrow-left"></i>
                </a>
                Détails de l'Utilisateur : {{ $user->name }}
            </h3>
            <a href="{{ route('superadmin.users.edit', $user) }}" class="btn btn-primary btn-sm">Modifier</a>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nom :</strong> {{ $user->name }}</p>
                    <p><strong>Email :</strong> {{ $user->email }}</p>
                    <p><strong>Rôle :</strong> 
                        @if($user->isSuperAdmin())
                            <span class="badge bg-danger">Super Admin</span>
                        @elseif($user->isAdmin())
                            <span class="badge bg-primary">Admin Hôtel</span>
                        @else
                            <span class="badge bg-dark">Client</span>
                        @endif
                    </p>
                </div>
                <div class="col-md-6">
                    <p><strong>Créé le :</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Dernière mise à jour :</strong> {{ $user->updated_at->format('d/m/Y H:i') }}</p>
                    <p><strong>Statut :</strong> 
                        @if($user->is_active)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-secondary">Inactif</span>
                        @endif
                    </p>
                </div>
            </div>

            @if($user->isAdmin())
                <hr>
                <h5>Établissement Géré</h5>
                @if($user->hotel)
                    <div class="alert alert-info border-0 mb-0">
                        <p class="mb-0"><strong>Hôtel :</strong> {{ $user->hotel->name }}</p>
                        <p class="mb-0"><strong>Ville :</strong> {{ $user->hotel->city->name ?? 'Non spécifiée' }}</p>
                    </div>
                @else
                    <p class="text-muted small">Aucun établissement assigné à cet administrateur.</p>
                @endif
            @endif
        </div>
    </div>
@endsection
