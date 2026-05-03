@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h4 class="mb-0 fw-bold">Ajouter un Type de Chambre</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.roomtypes.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nom du Type (ex: Suite Junior, Deluxe Vue Mer)</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name') }}" placeholder="Entrez le nom du type" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea name="description" id="description" rows="4" 
                                  class="form-control @error('description') is-invalid @enderror" 
                                  placeholder="Décrivez les caractéristiques de ce type de chambre">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.roomtypes.index') }}" class="btn btn-light border fw-bold">
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary fw-bold px-4">
                            ENREGISTRER
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

