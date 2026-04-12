@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Chatbot Suggestions</h3>
        <a href="{{ route('superadmin.chatbot-suggestions.create') }}" class="btn btn-primary">
            + New Suggestion
        </a>
    </div>

    @include('components.show-success')

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-dark text-white">
            <h6 class="mb-0 py-1">Manage Quick Questions</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Suggestion Text</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suggestions as $suggestion)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">{{ $suggestion->text }}</td>
                                <td>
                                    <span class="badge bg-outline-{{ $suggestion->role == 'all' ? 'secondary' : 'info' }} text-dark border">
                                        {{ ucfirst($suggestion->role) }}
                                    </span>
                                </td>
                                <td>
                                    @if($suggestion->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Disabled</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('superadmin.chatbot-suggestions.edit', $suggestion->id) }}" class="btn btn-sm btn-outline-primary">
                                            Edit
                                        </a>
                                        <form action="{{ route('superadmin.chatbot-suggestions.destroy', $suggestion->id) }}" method="POST" onsubmit="return confirm('Delete this suggestion?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger ms-1">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">No suggestions found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
