@extends('layouts.app')

@section('content')
    @include('components.show-success')

    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Créer une Suggestion</h3>
        </div>
        <div class="card-body">
            <form class="row g-3" action="{{ route('superadmin.chatbot-suggestions.store') }}" method="POST">
                @csrf
                
                <div class="col-12">
                    <label class="form-label">Texte de la Suggestion</label>
                    <input type="text" name="text" value="{{ old('text') }}" class="form-control @error('text') is-invalid @enderror" placeholder="ex. Comment réserver une chambre ?" required>
                    @error('text') <div class="invalid-feedback" style="display:block">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Rôle Cible</label>
                    <select name="role" class="form-control @error('role') is-invalid @enderror">
                        <option value="client" {{ old('role') == 'client' ? 'selected' : '' }}>Client</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin Hôtel</option>
                        <option value="all" {{ old('role', 'all') == 'all' ? 'selected' : '' }}>Tous les Rôles</option>
                    </select>
                    @error('role') <div class="invalid-feedback" style="display:block">{{ $message }}</div> @enderror
                </div>
                
                <div class="col-12">
                    <label class="form-label">Statut</label>
                    <select name="is_active" class="form-control @error('is_active') is-invalid @enderror">
                        <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Actif</option>
                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Désactivé</option>
                    </select>
                    @error('is_active') <div class="invalid-feedback" style="display:block">{{ $message }}</div> @enderror
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Créer la Suggestion</button>
                    <a href="{{ route('superadmin.chatbot-suggestions.index') }}" class="btn btn-secondary ms-2">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection

