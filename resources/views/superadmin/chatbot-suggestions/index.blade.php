@extends('layouts.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Suggestions du Chatbot</h3>
        <a href="{{ route('superadmin.chatbot-suggestions.create') }}" class="btn btn-primary">
            + Nouvelle Suggestion
        </a>
    </div>

    @include('components.show-success')

    <div class="card border-0 shadow-sm">

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th class="ps-4">Texte de la suggestion</th>
                            <th>Rôle</th>
                            <th>Statut</th>
                            <th class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($suggestions as $suggestion)
                            <tr>
                                <td class="ps-4 fw-bold text-dark">{{ $suggestion->text }}</td>
                                <td>
                                    <span class="badge bg-outline-{{ $suggestion->role == 'all' ? 'secondary' : 'info' }} text-dark border">
                                        {{ ucfirst($suggestion->role === 'all' ? 'tous' : $suggestion->role) }}
                                    </span>
                                </td>
                                <td>
                                    @if($suggestion->is_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-danger">Désactivé</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <a href="{{ route('superadmin.chatbot-suggestions.edit', $suggestion->id) }}" class="btn btn-sm btn-warning">
                                            Modifier
                                        </a>
                                        <form action="{{ route('superadmin.chatbot-suggestions.destroy', $suggestion->id) }}" method="POST" onsubmit="return confirm('Supprimer cette suggestion ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger ms-1">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">Aucune suggestion trouvée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
