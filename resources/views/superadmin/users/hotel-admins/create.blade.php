@extends('layouts.app')

@section('content')
    @include('components.show-success')

    <div class="card">
        <div class="card-header">
            <h3 class="mb-0">Assigner un Admin — {{ $hotel->name }}</h3>
        </div>
        <div class="card-body">
            <form method="post"
                  action="{{ route('superadmin.hotel-admins.store', $hotel->id) }}">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Admin Hôtel</label>
                    <select name="admin_id"
                            class="form-select @error('admin_id') is-invalid @enderror">
                        <option value="">Choisir...</option>
                        @foreach($availableAdmins as $admin)
                            <option value="{{ $admin->id }}"
                                    @selected(old('admin_id') == $admin->id)>
                                {{ $admin->name }} ({{ $admin->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('admin_id')
                        <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Assigner</button>
                <a href="{{ route('superadmin.hotel-admins.index') }}" class="btn btn-secondary ms-2">Annuler</a>

                @if($availableAdmins->isEmpty())
                    <div class="mt-3 text-muted">
                        Aucun compte admin disponible à assigner.
                    </div>
                @endif
            </form>
        </div>
    </div>
@endsection

