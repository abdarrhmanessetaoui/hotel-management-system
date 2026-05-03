@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom py-3">
                <h4 class="mb-0 fw-bold">Modifier le Type : {{ $roomtype->name }}</h4>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.roomtypes.update', $roomtype->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label for="name" class="form-label fw-bold">Nom du Type</label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $roomtype->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="form-label fw-bold">Description</label>
                        <textarea name="description" id="description" rows="4" 
                                  class="form-control @error('description') is-invalid @enderror">{{ old('description', $roomtype->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between">
                        <a href="{{ route('admin.roomtypes.index') }}" class="btn btn-light border fw-bold">
                            Annuler
                        </a>
                        <button type="submit" class="btn btn-primary fw-bold px-4">
                            METTRE À JOUR
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

