@extends('layouts.app')

@section('content')
    <div class="d-flex flex-column h-100">
        <div class="d-flex justify-content-between align-items-center mb-4 flex-shrink-0">
            <h3 class="mb-0">Suggestions du Chatbot</h3>
            <a href="{{ route('superadmin.chatbot-suggestions.create') }}" class="btn btn-primary px-4 py-2">
                + NOUVELLE SUGGESTION
            </a>
        </div>

        @include('components.show-success')

        <div class="card border-0 shadow-sm flex-grow-1 d-flex flex-column">
            <div class="card-body p-0 flex-grow-1 d-flex flex-column">
                <div class="table-responsive flex-grow-1">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="thead-brand">
                            <tr>
                                <th class="ps-4 py-3">Texte de la suggestion</th>
                                <th class="py-3">Rôle</th>
                                <th class="py-3">Statut</th>
                                <th class="text-end pe-4 py-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($suggestions as $suggestion)
                                <tr>
                                    <td class="ps-4 fw-bold text-dark">{{ $suggestion->text }}</td>
                                    <td>
                                        <span class="badge border text-dark bg-light px-3 py-2">
                                            {{ ucfirst($suggestion->role === 'all' ? 'tous' : $suggestion->role) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($suggestion->is_active)
                                            <span class="badge bg-success px-3 py-2">Actif</span>
                                        @else
                                            <span class="badge bg-danger px-3 py-2">Désactivé</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <a href="{{ route('superadmin.chatbot-suggestions.edit', $suggestion->id) }}" 
                                               class="btn btn-warning fw-bold text-dark border-0 px-3 py-2 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                                                Modifier
                                            </a>
                                            <form action="{{ route('superadmin.chatbot-suggestions.destroy', $suggestion->id) }}" method="POST" onsubmit="return confirm('Supprimer cette suggestion ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger fw-bold border-0 px-3 py-2 text-uppercase" style="font-size: 0.8rem; letter-spacing: 0.5px;">
                                                    Supprimer
                                                </button>
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
    </div>
@endsection

