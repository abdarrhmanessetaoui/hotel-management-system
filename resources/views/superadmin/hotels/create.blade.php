@extends('layouts.app')

@section('content')
    {{-- Super Admin - Create Hotel (Phase 12) --}}
    @include('components.show-success')

    {{-- Controller provides: $cities --}}
    <div class="card">
        <div class="card-header">
            <h3>Create Hotel</h3>
        </div>

        <div class="card-body">
            <form class="row g-3"
                  method="post"
                  action="{{ route('superAdmin.hotels.store') }}"
                  enctype="multipart/form-data">
                @csrf

                {{-- City --}}
                <div class="col-12">
                    <label class="form-label">Ville</label>
                    <select name="city_id" class="form-select @error('city_id') is-invalid @enderror">
                        <option value="">Choose...</option>
                        @foreach($cities as $city)
                            <option value="{{ $city->id }}"
                                    @selected(old('city_id') == $city->id)>
                                {{ $city->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('city_id')
                        <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Name --}}
                <div class="col-12">
                    <label class="form-label">Nom de l'Hôtel</label>
                    <input type="text"
                           name="name"
                           value="{{ old('name') }}"
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name')
                        <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Description --}}
                <div class="col-12">
                    <label class="form-label">Description</label>
                    <textarea name="description"
                              class="form-control @error('description') is-invalid @enderror"
                              rows="3">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Location --}}
                <div class="col-12">
                    <label class="form-label">Emplacement</label>
                    <input type="text"
                           name="location"
                           value="{{ old('location') }}"
                           class="form-control @error('location') is-invalid @enderror">
                    @error('location')
                        <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Rating --}}
                <div class="col-12">
                    <label class="form-label">Note</label>
                    <input type="number"
                           name="rating"
                           step="0.1"
                           value="{{ old('rating') }}"
                           class="form-control @error('rating') is-invalid @enderror">
                    @error('rating')
                        <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Image --}}
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
                    <button type="submit" class="btn btn-primary">Create Hotel</button>
                </div>
            </form>
        </div>
    </div>
@endsection

