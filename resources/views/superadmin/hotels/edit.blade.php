@extends('layouts.app')

@section('content')
    @include('components.show-success')

    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Modifier l'Hôtel</h3>
        </div>
        <div class="card-body">
            <form class="row g-3" method="post"
                  action="{{ route('superadmin.hotels.update', $hotel->id) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('put')

                <div class="col-12">
                    <label class="form-label">Ville</label>
                    <select name="city_id" class="form-select @error('city_id') is-invalid @enderror">
                        <option value="">Choisir...</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}"
                                    @selected(old('city_id', $hotel->city_id) == $city->id)>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('city_id')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Nom de l'Hôtel</label>
                    <input type="text" name="name" value="{{ old('name', $hotel->name) }}"
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $hotel->description) }}</textarea>
                    @error('description')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Emplacement</label>
                    <input type="text" name="location" value="{{ old('location', $hotel->location) }}"
                           class="form-control @error('location') is-invalid @enderror">
                    @error('location')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Note (0–5)</label>
                    <input type="number" name="rating" step="0.1" min="0" max="5"
                           value="{{ old('rating', $hotel->rating) }}"
                           class="form-control @error('rating') is-invalid @enderror">
                    @error('rating')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Image</label>
                    <input type="file" name="image"
                           class="form-control @error('image') is-invalid @enderror">
                    @error('image')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                    @if(!empty($hotel->image))
                        <div class="mt-2">
                            <img src="{{ Str::startsWith($hotel->image,'http') ? $hotel->image : asset($hotel->image) }}"
                                 width="120" class="rounded" alt="{{ $hotel->name }}">
                        </div>
                    @endif
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    <a href="{{ route('superadmin.hotels.index') }}" class="btn btn-secondary ms-2">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
