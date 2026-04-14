@extends('layouts.app')

@section('content')
    @include('components.show-success')

    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Modifier l'Utilisateur</h3>
        </div>
        <div class="card-body">
            <form class="row g-3" method="post" action="{{ route('superadmin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div class="col-md-6">
                    <label class="form-label">Nom Complet</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="form-control @error('name') is-invalid @enderror" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Adresse Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="form-control @error('email') is-invalid @enderror" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Rôle du compte</label>
                    <select name="role" id="roleSelect" class="form-select @error('role') is-invalid @enderror" required {{ $user->isSuperAdmin() ? 'disabled' : '' }}>
                        @if($user->isSuperAdmin())
                            <option value="superadmin" selected>Super Admin</option>
                        @else
                            <option value="client" {{ old('role', $user->role) == 'client' ? 'selected' : '' }}>Client</option>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrateur Hôtel</option>
                        @endif
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12" id="hotelAssignmentSection" style="display: {{ old('role', $user->role) == 'admin' ? 'block' : 'none' }};">
                    <label class="form-label fw-bold text-primary">Assignation de l'Hôtel (Admins uniquement)</label>
                    <select name="hotel_id" class="form-select">
                        <option value="">-- Aucun hôtel assigné --</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" {{ ($user->hotel && $user->hotel->id == $hotel->id) ? 'selected' : '' }}>
                                {{ $hotel->name }} ({{ $hotel->city->name ?? 'Sans ville' }})
                            </option>
                        @endforeach
                    </select>
                    <small class="text-muted">L'établissement sélectionné sera géré par cet utilisateur.</small>
                </div>

                <script>
                    document.getElementById('roleSelect').addEventListener('change', function() {
                        const section = document.getElementById('hotelAssignmentSection');
                        if (this.value === 'admin') {
                            section.style.display = 'block';
                        } else {
                            section.style.display = 'none';
                        }
                    });
                </script>

                <div class="col-12">
                    <hr class="my-3">
                    <h5 class="text-muted">Changer le mot de passe (optionnel)</h5>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nouveau mot de passe</label>
                    <input type="password" name="password"
                           class="form-control @error('password') is-invalid @enderror">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Confirmer le mot de passe</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    <a href="{{ route('superadmin.users.index') }}" class="btn btn-secondary ms-2">Annuler</a>
                </div>
            </form>

            {{-- Status Toggle Form (Moved outside to avoid nested forms) --}}
            @if($user->id !== auth()->id() && !$user->isSuperAdmin())
                <hr class="mt-5 mb-4">
                <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded">
                    <div>
                        <h6 class="mb-1">Statut du compte</h6>
                        <small class="text-muted">
                            @if($user->is_active)
                                Ce compte est actuellement <strong>actif</strong>.
                            @else
                                Ce compte est actuellement <strong>désactivé</strong>.
                            @endif
                        </small>
                    </div>
                    <form method="post" action="{{ route('superadmin.users.toggle-status', $user) }}">
                        @csrf
                        @method('patch')
                        <button type="submit" class="btn {{ $user->is_active ? 'btn-danger' : 'btn-success' }}"
                                onclick="return confirm('{{ $user->is_active ? 'Désactiver ce compte ?' : 'Activer ce compte ?' }}')">
                            {{ $user->is_active ? 'Désactiver le compte' : 'Activer le compte' }}
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection
