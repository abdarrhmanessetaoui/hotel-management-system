@extends('layouts.app')

@section('content')
    {{-- Super Admin - Assign Admin to Hotel (Phase 12) --}}
    @include('components.show-success')

    {{-- Controller provides: $hotel, $availableAdmins --}}
    <div class="card">
        <div class="card-header">
            <h3>Assign Admin for {{ $hotel->name }}</h3>
        </div>

        <div class="card-body">
            <form method="post"
                  action="{{ route('superAdmin.hotel-Admins.store', ['hotel' => $hotel->id]) }}">
                @csrf

                {{-- Admin selection --}}
                <div class="mb-3">
                    <label class="form-label">Hotel Admin</label>
                    <select name="Admin_id" class="form-select @error('Admin_id') is-invalid @enderror">
                        <option value="">Choose...</option>
                        @foreach($availableAdmins as $Admin)
                            <option value="{{ $Admin->id }}" @selected(old('Admin_id') == $Admin->id)>
                                {{ $Admin->name }} ({{ $Admin->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('Admin_id')
                        <div class="invalid-feedback" style="display:block">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">
                    Assign Admin
                </button>
            </form>

            @if($availableAdmins->isEmpty())
                <div class="mt-3 text-muted">
                    There are no available Admin accounts to assign.
                </div>
            @endif
        </div>
    </div>
@endsection

