@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <a href="{{ route('superadmin.chatbot-suggestions.index') }}" class="text-secondary text-decoration-none small">
            &larr; Back to Suggestions
        </a>
        <h3 class="mt-2">Create New Suggestion</h3>
    </div>

    <div class="card border-0 shadow-sm col-md-8">
        <div class="card-body p-4">
            <form action="{{ route('superadmin.chatbot-suggestions.store') }}" method="POST">
                @csrf
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Suggestion Text</label>
                    <input type="text" name="text" class="form-control shadow-none @error('text') is-invalid @enderror" placeholder="e.g. How to book a room?" required>
                    @error('text') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Target Role</label>
                        <select name="role" class="form-select shadow-none">
                            <option value="client">Client (Guest)</option>
                            <option value="admin">Hotel Admin</option>
                            <option value="all">All Roles</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="is_active" class="form-select shadow-none">
                            <option value="1">Enabled</option>
                            <option value="0">Disabled</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn btn-primary px-4">Create Suggestion</button>
                </div>
            </form>
        </div>
    </div>
@endsection
