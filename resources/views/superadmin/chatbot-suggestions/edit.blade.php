@extends('layouts.app')

@section('content')
    <div class="mb-4">
        <a href="{{ route('superadmin.chatbot-suggestions.index') }}" class="text-secondary text-decoration-none small">
            &larr; Back to Suggestions
        </a>
        <h3 class="mt-2">Edit Suggestion</h3>
    </div>

    <div class="card border-0 shadow-sm col-md-8">
        <div class="card-body p-4">
            <form action="{{ route('superadmin.chatbot-suggestions.update', $chatbotSuggestion->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label class="form-label fw-bold">Suggestion Text</label>
                    <input type="text" name="text" class="form-control shadow-none @error('text') is-invalid @enderror" value="{{ $chatbotSuggestion->text }}" required>
                    @error('text') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Target Role</label>
                        <select name="role" class="form-select shadow-none">
                            <option value="client" {{ $chatbotSuggestion->role == 'client' ? 'selected' : '' }}>Client (Guest)</option>
                            <option value="admin" {{ $chatbotSuggestion->role == 'admin' ? 'selected' : '' }}>Hotel Admin</option>
                            <option value="all" {{ $chatbotSuggestion->role == 'all' ? 'selected' : '' }}>All Roles</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="is_active" class="form-select shadow-none">
                            <option value="1" {{ $chatbotSuggestion->is_active ? 'selected' : '' }}>Enabled</option>
                            <option value="0" {{ !$chatbotSuggestion->is_active ? 'selected' : '' }}>Disabled</option>
                        </select>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-flex justify-content-end gap-2">
                    <a href="{{ route('superadmin.chatbot-suggestions.index') }}" class="btn btn-light">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">Update Suggestion</button>
                </div>
            </form>
        </div>
    </div>
@endsection
