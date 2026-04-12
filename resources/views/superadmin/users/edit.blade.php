@extends('layouts.app')

@section('content')
@php $AdminView = true; @endphp

<div class="container-fluid py-4">
    <div class="mb-4">
        <a href="{{ route('superadmin.users.index') }}" class="text-decoration-none small d-flex align-items-center gap-1 text-muted">
            <i class="bi bi-arrow-left"></i>
            <span>Retour à la liste</span>
        </a>
        <h4 class="mt-3 fw-bold">Modifier l'Utilisateur</h4>
        <p class="text-muted small">Mettez à jour les informations du compte de {{ $user->name }}.</p>
    </div>

    <div class="row">
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm p-4">
                <form action="{{ route('superadmin.users.update', $user) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nom Complet</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Adresse Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Rôle du compte</label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                            <option value="client" {{ old('role', $user->role) == 'client' ? 'selected' : '' }}>Client</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                            <option value="superadmin" {{ old('role', $user->role) == 'superadmin' ? 'selected' : '' }}>Super Admin</option>
                        </select>
                        @error('role') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <hr class="my-4">

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Nouveau Mot de passe (Laisser vide pour ne pas changer)</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
                        @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Confirmer le nouveau mot de passe</label>
                        <input type="password" name="password_confirmation" class="form-control">
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('superadmin.users.index') }}" class="btn btn-light px-4 border">Annuler</a>
                        <button type="submit" class="btn btn-primary px-4">Enregistrer les modifications</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="col-xl-4 offset-xl-1">
            @if($user->isAdmin() && $user->hotel)
                <div class="card border-0 bg-light p-4 mb-4">
                    <h6 class="fw-bold mb-3">Établissement Géré</h6>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded bg-white border d-flex align-items-center justify-content-center text-primary" style="width:48px; height:48px;">
                            <i class="bi bi-building fs-5"></i>
                        </div>
                        <div>
                            <div class="fw-bold text-dark">{{ $user->hotel->name }}</div>
                            <div class="text-muted small">{{ $user->hotel->city->name }}</div>
                        </div>
                    </div>
                    <a href="{{ route('superadmin.hotels.edit', $user->hotel) }}" class="btn btn-sm btn-outline-primary mt-3 w-100">Gérer l'hôtel</a>
                </div>
            @endif

            <div class="card border-0 bg-dark text-white p-4">
                <small class="text-white-50 text-uppercase fw-bold" style="letter-spacing: 1px;">Détails du compte</small>
                <div class="mt-3">
                    <div class="small mb-1">Inscrit le : <strong>{{ $user->created_at->format('d/m/Y') }}</strong></div>
                    <div class="small mb-1">Dernière mise à jour : <strong>{{ $user->updated_at->format('d/m/Y') }}</strong></div>
                    <div class="small">Statut: <span class="badge bg-success">Compte Actif</span></div>
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
