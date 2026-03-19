@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Créer une Chambre</h3>
        </div>
        <div class="card-body">
            <form class="row g-3" method="post" action="{{ route('Admin.rooms.store') }}"
                  enctype="multipart/form-data">
                @csrf
                {{-- Room Number --}}
                <div class="col-6">
                    <label class="form-label">Numéro de Chambre</label>
                    <input type="text" name="room_number" value="{{ old('room_number') }}"
                           class="form-control @error('room_number') is-invalid @enderror">
                    @error('room_number')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Room Type --}}
                <div class="col-6">
                    <label class="form-label">Type de Chambre</label>
                    <select name="type" class="form-select @error('type') is-invalid @enderror">
                        <option value="">Choose...</option>
                        @foreach(['single', 'double', 'suite', 'deluxe'] as $t)
                            <option value="{{ $t }}" @selected(old('type') === $t)>
                                {{ ucfirst($t) }}
                            </option>
                        @endforeach
                    </select>
                    @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Price --}}
                <div class="col-6">
                    <label class="form-label">Price per Night ($)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price') }}"
                           class="form-control @error('price') is-invalid @enderror">
                    @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Status --}}
                <div class="col-6">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="available" @selected(old('status') === 'available')>Disponible</option>
                        <option value="unavailable" @selected(old('status') === 'unavailable')>Indisponible</option>
                    </select>
                    @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              name="description">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Image --}}
                <div class="col-12">
                    <label class="form-label">Image</label>
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                    @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Add Room</button>
                    <a href="{{ route('Admin.rooms.index') }}" class="btn btn-secondary ms-2">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
