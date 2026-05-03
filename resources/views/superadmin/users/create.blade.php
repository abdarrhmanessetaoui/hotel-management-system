@extends('layouts.app')

@section('content')
    @include('components.show-success')

    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Nouvel Utilisateur</h3>
        </div>
        <div class="card-body">
            <form class="row g-3" method="post" action="{{ route('superadmin.users.store') }}">
                @csrf

                <div class="col-md-6">
                    <label class="form-label">Nom Complet</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Adresse Email</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="form-control @error('email') is-invalid @enderror" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror" required>
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-control" required>
                </div>

                <div class="col-12">
                    <label class="form-label">Rôle du compte</label>
                    <select name="role" class="form-select @error('role') is-invalid @enderror" required>
                        <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Client</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur Hôtel</option>
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary">Créer le compte</button>
                    <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary ms-2">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection

