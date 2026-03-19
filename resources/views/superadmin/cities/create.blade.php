@extends('layouts.app')

@section('content')
    {{-- Super Admin - Create City (Phase 12) --}}
    @include('components.show-success')

    <div class="card">
        <div class="card-header">
            <h3>Create City</h3>
        </div>

        <div class="card-body">
            <form class="row g-3"
                  method="post"
                  action="{{ route('superAdmin.cities.store') }}"
                  enctype="multipart/form-data">
                @csrf

                {{-- City name --}}
                <div class="col-12">
                    <label class="form-label">Nom de la Ville</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- City image --}}
                <div class="col-12">
                    <label class="form-label">Image</label>
                    <input type="file"
                           name="image"
                           class="form-control @error('image') is-invalid @enderror">
                    @error('image')
                        <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Create City</button>
                </div>
            </form>
        </div>
    </div>
@endsection

