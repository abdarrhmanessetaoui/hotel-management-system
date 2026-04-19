@extends('layouts.app')

@section('content')
    @include('components.show-success')

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="mb-0">Gestion des Avis Clients</h3>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                    <thead class="thead-brand">
                        <tr>
                            <th class="ps-4">#</th>
                            <th class="text-nowrap">Auteur</th>
                            <th>Avis</th>
                            <th class="text-nowrap text-center">Note</th>
                            <th class="text-nowrap">Date</th>
                            <th class="text-nowrap">Statut</th>
                            <th class="text-center pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($reviews as $review)
                        <tr id="review-row-{{ $review->id }}">
                            <th class="ps-4 text-muted">{{ $loop->iteration }}</th>

                            {{-- Auteur --}}
                            <td class="fw-bold text-nowrap py-2">
                                {{ $review->author_name }}
                                <br><small class="text-muted fw-normal" style="font-size:0.72rem;">
                                    {{ $review->user ? $review->user->email : 'Anonyme' }}
                                </small>
                            </td>

                            {{-- Contenu --}}
                            <td style="max-width: 260px;">
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
                                    <span class="review-badge badge-accepted">
                                        <i class="bi bi-check-circle-fill"></i> Accepté
                                    </span>
                                @elseif($review->status === 'rejected')
                                    <span class="review-badge badge-rejected">
                                        <i class="bi bi-x-circle-fill"></i> Refusé
                                    </span>
                                @else
                                    <span class="review-badge badge-pending">
                                        <i class="bi bi-clock-fill"></i> En attente
                                    </span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="text-center pe-4">
                                <div class="d-flex justify-content-center align-items-center gap-2" id="actions-col-{{ $review->id }}">

                                    {{-- Accept --}}
                                    @if($review->status !== 'accepted')
                                    <button type="button"
                                        class="btn btn-success btn-sm py-1 px-2 fw-bold action-btn"
                                        data-id="{{ $review->id }}"
                                        data-action="approve"
                                        style="font-size:0.75rem;">
                                        ACCEPTER
                                    </button>
                                    @endif

                                    {{-- Reject --}}
                                    @if($review->status !== 'rejected')
                                    <button type="button"
                                        class="btn btn-warning btn-sm py-1 px-2 fw-bold action-btn"
                                        data-id="{{ $review->id }}"
                                        data-action="reject"
                                        style="font-size:0.75rem;">
                                        REFUSER
                                    </button>
                                    @endif

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
            </div>

            <div class="p-3">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.addEventListener('click', async function (e) {
            const btn = e.target.closest('.action-btn');
            if (!btn) return;

            e.preventDefault();

            const id     = btn.dataset.id;
            const action = btn.dataset.action;

            if (action === 'destroy') {
                if (!confirm('Supprimer définitivement cet avis ?')) return;
            }

            const urlMap = {
                approve: `/superadmin/reviews/${id}/approve`,
                reject:  `/superadmin/reviews/${id}/reject`,
                destroy: `/superadmin/reviews/${id}`
            };
            const methodMap = { approve: 'PUT', reject: 'PUT', destroy: 'DELETE' };

            const originalHTML = btn.innerHTML;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';
            btn.disabled = true;

            try {
                const response = await fetch(urlMap[action], {
                    method: methodMap[action],
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    }
                });

                const data = await response.json();
                if (!data.success) throw new Error('Échec');

                if (action === 'destroy') {
                    const row = document.getElementById(`review-row-${id}`);
                    if (row) {
                        row.style.transition = 'opacity 0.3s, transform 0.3s';
                        row.style.opacity = '0';
                        row.style.transform = 'translateX(20px)';
                        setTimeout(() => row.remove(), 300);
                    }
                    return;
                }

                // Update status badge
                const statusCol = document.getElementById(`status-col-${id}`);
                if (data.status === 'accepted') {
                    statusCol.innerHTML = '<span class="review-badge badge-accepted"><i class="bi bi-check-circle-fill"></i> Accepté</span>';
                } else if (data.status === 'rejected') {
                    statusCol.innerHTML = '<span class="review-badge badge-rejected"><i class="bi bi-x-circle-fill"></i> Refusé</span>';
                }

                // Update action buttons after AJAX
                const actionsCol = document.getElementById(`actions-col-${id}`);
                const baseStyle  = 'font-size:0.75rem;';
                let html = '';
                if (data.status === 'rejected') {
                    html += `<button type="button" class="btn btn-success btn-sm py-1 px-2 fw-bold action-btn" data-id="${id}" data-action="approve" style="${baseStyle}">ACCEPTER</button>`;
                }
                if (data.status === 'accepted') {
                    html += `<button type="button" class="btn btn-warning btn-sm py-1 px-2 fw-bold action-btn" data-id="${id}" data-action="reject" style="${baseStyle}">REFUSER</button>`;
                }
                actionsCol.innerHTML = html;

            } catch (err) {
                console.error(err);
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            }
        });
    });
</script>
@endpush
