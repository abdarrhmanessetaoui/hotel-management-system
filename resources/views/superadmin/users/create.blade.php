@extends('layouts.app')

@section('content')
@php $AdminView = true; @endphp

<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="{{ route('superadmin.users.index') }}" class="text-decoration-none small d-flex align-items-center gap-1 text-muted">
            <i class="bi bi-arrow-left"></i>
            <span>Retour à la liste</span>
        </a>
        <h4 class="mt-3 fw-bold">Nouvel Utilisateur</h4>
        <p class="text-muted small">Créez un nouveau compte client ou administrateur.</p>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm p-4">
                <form action="{{ route('superadmin.users.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nom Complet</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="Ex: Jean Dupont" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Adresse Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" placeholder="jean@example.com" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Mot de passe</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required>
                            @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label small fw-bold text-muted">Confirmer le mot de passe</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Rôle du compte</label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Client (Accès standard)</option>
                            <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur (Gestion d'un hôtel)</option>
                            <option value="superadmin" {{ old('role') == 'superadmin' ? 'selected' : '' }}>Super Admin (Accès total)</option>
                        </select>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('superadmin.users.index') }}" class="btn btn-light px-4 border">Annuler</a>
                        <button type="submit" class="btn btn-primary px-4">Créer le compte</button>
                    </div>
                </form>
            </div>
        </div>
        
        <div class="col-xl-4 offset-xl-1">
            <div class="card border-0 bg-light p-4">
                <h6 class="fw-bold mb-3">Informations sur les Rôles</h6>
                <div class="mb-3">
                    <strong class="text-primary small d-block">Client</strong>
                    <p class="small text-muted mb-0">Peut parcourir les hôtels et faire des réservations. Le rôle standard par défaut.</p>
                </div>
                <div class="mb-3">
                    <strong class="text-primary small d-block">Admin</strong>
                    <p class="small text-muted mb-0">Peut gérer un hôtel spécifique, ses chambres et ses réservations. Ne peut pas accéder aux paramètres globaux.</p>
                </div>
                <div>
                    <strong class="text-danger small d-block">Super Admin</strong>
                    <p class="small text-muted mb-0">Contrôle total de la plateforme : villes, hôtels, utilisateurs et statistiques globales.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.form-control, .form-select { padding: 0.6rem 0.75rem; border-color: #e2e8f0; border-radius: 8px; }
.form-control:focus, .form-select:focus { box-shadow: 0 0 0 3px rgba(254, 161, 22, 0.1); border-color: #FEA116; }
</style>
@endsection
