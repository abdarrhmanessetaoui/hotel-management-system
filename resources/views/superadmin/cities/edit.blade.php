@extends('layouts.app')

@section('content')
    {{-- Super Admin - Edit City (Phase 12) --}}
    @include('components.show-success')

    {{-- Controller provides: $city --}}
    <div class="card">
        <div class="card-header">
            <h3>Modifier la Ville</h3>
        </div>

        <div class="card-body">
            <form class="row g-3"
                  method="post"
                  action="{{ route('superAdmin.cities.update', ['city' => $city->id]) }}"
                  enctype="multipart/form-data">
                @csrf
                @method('put')

                {{-- City name --}}
                <div class="col-12">
                    <label class="form-label">Nom de la Ville</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name', $city->name) }}"
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- City image (optional) --}}
                <div class="col-12">
                    <label class="form-label">Image</label>
                    <input type="file"
                           name="image"
                           class="form-control @error('image') is-invalid @enderror">
                    @error('image')
                        <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                    @enderror

                    @if(!empty($city->image))
                        <div class="mt-2">
                            <img src="{{ Str::startsWith($city->image, 'http') ? $city->image : asset($city->image) }}" width="100" alt="{{ $city->name }}">
                        </div>
                    @endif
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Update City</button>
                </div>
            </form>
        </div>
    </div>
@endsection

