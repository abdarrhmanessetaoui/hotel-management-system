@extends('layouts.app')

@section('content')
    @include('components.show-success')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Gestion des Avis Clients</h3>
        </div>

        <div class="card-body p-0">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="table-dark">
                    <tr>
                        <th class="ps-4">#</th>
                        <th class="text-nowrap">Auteur</th>
                        <th>Avis</th>
                        <th class="text-nowrap text-center">Note</th>
                        <th class="text-nowrap">Date</th>
                        <th class="text-nowrap">Statut</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($reviews as $review)
                    <tr id="review-row-{{ $review->id }}">
                        <th class="ps-4 text-muted">{{ $loop->iteration }}</th>

                        {{-- Auteur --}}
                        <td class="fw-bold text-nowrap py-2">
                            {{ $review->author_name }}
                            @if($review->user)
                                <br><small class="text-muted fw-normal" style="font-size:0.72rem;">
                                    {{ $review->user->email }}
                                </small>
                            @else
                                <br><small class="text-muted fw-normal" style="font-size:0.72rem;">
                                    Anonyme
                                </small>
                            @endif
                        </td>

                        {{-- Contenu --}}
                        <td style="max-width: 300px;">
                            <span class="text-body" style="display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">
                                {{ $review->content }}
                            </span>
                        </td>

                        {{-- Note étoiles --}}
                        <td class="text-center text-nowrap">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted opacity-25' }}"
                                   style="font-size:0.75rem;"></i>
                            @endfor
                        </td>

                        {{-- Date --}}
                        <td class="text-nowrap text-muted">
                            {{ $review->created_at->format('d/m/Y') }}
                        </td>

                        {{-- Statut --}}
                        <td class="text-nowrap" id="status-col-{{ $review->id }}">
                            @if($review->status === 'accepted')
                                <span class="badge bg-success" style="font-size: 0.7rem;">Accepté</span>
                            @elseif($review->status === 'rejected')
                                <span class="badge bg-danger" style="font-size: 0.7rem;">Refusé</span>
                            @else
                                <span class="badge bg-warning text-dark" style="font-size: 0.7rem;">En attente</span>
                            @endif
                        </td>

                        {{-- Actions --}}
                        <td class="text-end pe-4">
                            <div class="d-flex justify-content-end gap-1 flex-nowrap" id="actions-col-{{ $review->id }}">
                                @if($review->status === 'pending')
                                    <button type="button" class="btn btn-success btn-sm py-1 px-2 fw-bold action-btn"
                                            data-id="{{ $review->id }}" data-action="approve" style="font-size: 0.75rem;">
                                        Accepter
                                    </button>
                                    <button type="button" class="btn btn-warning btn-sm py-1 px-2 fw-bold text-dark action-btn ms-1"
                                            data-id="{{ $review->id }}" data-action="reject" style="font-size: 0.75rem;">
                                        Refuser
                                    </button>
                                @endif

                                <button type="button" class="btn btn-danger btn-sm py-1 px-2 fw-bold action-btn ms-1"
                                        data-id="{{ $review->id }}" data-action="destroy" style="font-size:0.75rem;">
                                    Supprimer
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7">
                            <p class="text-primary fw-bold mb-0 p-3">Aucun avis trouvé.</p>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>

            <div class="p-3">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.addEventListener('click', async function(e) {
                const btn = e.target.closest('.action-btn');
                if (!btn) return;

                e.preventDefault();
                
                const id = btn.dataset.id;
                const action = btn.dataset.action;

                if (action === 'destroy') {
                    if (!confirm('Supprimer définitivement cet avis ?')) return;
                }

                const url = action === 'destroy' ? `/superadmin/reviews/${id}` : `/superadmin/reviews/${id}/${action}`;
                const method = action === 'destroy' ? 'DELETE' : 'PUT';
                const originalHtml = btn.innerHTML;
                
                // Loader state
                btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                btn.disabled = true;

                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        }
                    });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        if (action === 'destroy') {
                            const row = document.getElementById(`review-row-${id}`);
                            if (row) {
                                // Animation douce (optionnel)
                                row.style.transition = 'opacity 0.3s';
                                row.style.opacity = '0';
                                setTimeout(() => row.remove(), 300);
                            }
                            return;
                        }

                        const statusCol = document.getElementById(`status-col-${id}`);
                        const actionsCol = document.getElementById(`actions-col-${id}`);
                        
                        // Update Status Badge
                        if (data.status === 'accepted') {
                            statusCol.innerHTML = '<span class="badge bg-success" style="font-size: 0.7rem;">Accepté</span>';
                        } else if (data.status === 'rejected') {
                            statusCol.innerHTML = '<span class="badge bg-danger" style="font-size: 0.7rem;">Refusé</span>';
                        }

                        // Une fois accepté/refusé, on ne garde QUE le bouton supprimer
                        let buttonsHtml = `
                            <button type="button" class="btn btn-danger btn-sm py-1 px-2 fw-bold action-btn ms-1"
                                    data-id="${id}" data-action="destroy" style="font-size:0.75rem;">
                                Supprimer
                            </button>
                        `;
                        
                        actionsCol.innerHTML = buttonsHtml;
                    }
                } catch (error) {
                    console.error('Error processing action:', error);
                    btn.innerHTML = originalHtml;
                    btn.disabled = false;
                }
            });
        });
    </script>
    @endpush
@endsection
