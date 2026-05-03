@extends('layouts.app')

@section('content')
    @include('components.show-success')

    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Modifier la Ville</h3>
        </div>
        <div class="card-body">
            <form class="row g-3" method="post"
                  action="{{ route('superadmin.cities.update', $city->id) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('put')

                <div class="col-12">
                    <label class="form-label">Nom de la Ville</label>
                    <input type="text" name="name" value="{{ old('name', $city->name) }}"
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">Image</label>
                    <input type="file" name="image"
                           class="form-control @error('image') is-invalid @enderror">
                    @error('image')<div class="invalid-feedback" style="display:block">{{ $message }}</div>@enderror
                    @if(!empty($city->image))
                        <div class="mt-2">
                            <img src="{{ Str::startsWith($city->image,'http') ? $city->image : asset($city->image) }}"
                                 width="100" class="rounded" alt="{{ $city->name }}">
                        </div>
                    @endif
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    <a href="{{ route('superadmin.cities.index') }}" class="btn btn-secondary ms-2">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection

