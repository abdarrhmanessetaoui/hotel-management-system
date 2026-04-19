@extends('layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Modifier la Chambre</h3>
        </div>
        <div class="card-body">
            <form class="row g-3" method="post"
                  action="{{ route('admin.rooms.update', $room->id) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('put')

                <div class="col-md-6 col-12">
                    <label class="form-label">Numéro de Chambre</label>
                    <input type="text" name="room_number" value="{{ old('room_number', $room->room_number) }}"
                           class="form-control @error('room_number') is-invalid @enderror">
                    @error('room_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 col-12">
                    <label class="form-label">Type de Chambre</label>
                    <select name="room_type_id" class="form-select @error('room_type_id') is-invalid @enderror">
                        <option value="">Choisir...</option>
                        @foreach($roomTypes as $type)
                            <option value="{{ $type->id }}" @selected(old('room_type_id', $room->room_type_id) == $type->id)>{{ $type->name }}</option>
                        @endforeach
                    </select>
                    @error('room_type_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 col-12">
                    <label class="form-label">Prix / Nuit (DH)</label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $room->price) }}"
                           class="form-control @error('price') is-invalid @enderror">
                    @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6 col-12">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select @error('status') is-invalid @enderror">
                        <option value="available" @selected(old('status', $room->status) === 'available')>Disponible</option>
                        <option value="unavailable" @selected(old('status', $room->status) === 'unavailable')>Indisponible</option>
                    </select>
                    @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror"
                              name="description" rows="3">{{ old('description', $room->description) }}</textarea>
                    @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Image</label>
                    @if($room->image)
                        <div class="mb-2">
                            <img src="{{ Str::startsWith($room->image,'http') ? $room->image : asset($room->image) }}"
                                 width="80" height="60" class="rounded">
                            <small class="text-muted ms-2">Image actuelle — télécharger pour remplacer.</small>
                        </div>
                    @endif
                    <input type="file" name="image" class="form-control @error('image') is-invalid @enderror">
                    @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    <a href="{{ route('admin.rooms.index') }}" class="btn btn-secondary ms-2">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
