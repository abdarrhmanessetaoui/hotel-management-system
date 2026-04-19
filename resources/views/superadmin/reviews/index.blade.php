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
                                <div class="review-actions" id="actions-col-{{ $review->id }}">

                                    {{-- Accept --}}
                                    @if($review->status !== 'accepted')
                                    <button type="button"
                                        class="review-action-btn btn-accept action-btn"
                                        data-id="{{ $review->id }}"
                                        data-action="approve"
                                        title="Accepter">
                                        <i class="bi bi-check-lg"></i>
                                        <span>Accepter</span>
                                    </button>
                                    @endif

                                    {{-- Reject --}}
                                    @if($review->status !== 'rejected')
                                    <button type="button"
                                        class="review-action-btn btn-reject action-btn"
                                        data-id="{{ $review->id }}"
                                        data-action="reject"
                                        title="Refuser">
                                        <i class="bi bi-slash-circle"></i>
                                        <span>Refuser</span>
                                    </button>
                                    @endif

                                    {{-- Delete --}}
                                    <button type="button"
                                        class="review-action-btn btn-delete action-btn"
                                        data-id="{{ $review->id }}"
                                        data-action="destroy"
                                        title="Supprimer">
                                        <i class="bi bi-trash3"></i>
                                        <span>Supprimer</span>
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
            </div>

            <div class="p-3">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<style>
    /* ─── Status Badges ─────────────────────────────── */
    .review-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 0.3px;
    }
    .badge-accepted  { background: rgba(25,135,84,.12);  color: #157347; }
    .badge-rejected  { background: rgba(220,53,69,.1);   color: #b02a37; }
    .badge-pending   { background: rgba(255,126,33,.12); color: #c46000; }

    /* ─── Action Button Group ───────────────────────── */
    .review-actions {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        flex-wrap: wrap;
    }

    .review-action-btn {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        border: none;
        border-radius: 7px;
        padding: 5px 11px;
        font-size: 0.75rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.18s ease;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        white-space: nowrap;
    }

    .review-action-btn i { font-size: 0.9rem; }

    .btn-accept {
        background: rgba(25,135,84,.1);
        color: #157347;
        border: 1px solid rgba(25,135,84,.25);
    }
    .btn-accept:hover {
        background: #157347;
        color: #fff;
        box-shadow: 0 3px 10px rgba(25,135,84,.35);
        transform: translateY(-1px);
    }

    .btn-reject {
        background: rgba(255,126,33,.1);
        color: #c46000;
        border: 1px solid rgba(255,126,33,.3);
    }
    .btn-reject:hover {
        background: var(--primary);
        color: #0f172b;
        box-shadow: 0 3px 10px rgba(255,126,33,.35);
        transform: translateY(-1px);
    }

    .btn-delete {
        background: rgba(220,53,69,.08);
        color: #b02a37;
        border: 1px solid rgba(220,53,69,.2);
    }
    .btn-delete:hover {
        background: #dc3545;
        color: #fff;
        box-shadow: 0 3px 10px rgba(220,53,69,.35);
        transform: translateY(-1px);
    }

    .review-action-btn:disabled {
        opacity: 0.55;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    /* Compact on very small screens */
    @media (max-width: 575px) {
        .review-action-btn span { display: none; }
        .review-action-btn { padding: 6px 8px; border-radius: 6px; }
        .review-action-btn i { font-size: 1rem; }
    }
</style>

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

                if (!data.success) throw new Error('Échec de l\'opération');

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

                // Update action buttons
                const actionsCol = document.getElementById(`actions-col-${id}`);
                const oppAction  = data.status === 'accepted' ? 'reject' : 'approve';
                const oppLabel   = data.status === 'accepted' ? '<i class="bi bi-slash-circle"></i><span>Refuser</span>' : '<i class="bi bi-check-lg"></i><span>Accepter</span>';
                const oppClass   = data.status === 'accepted' ? 'btn-reject' : 'btn-accept';

                actionsCol.innerHTML = `
                    <button type="button" class="review-action-btn ${oppClass} action-btn"
                            data-id="${id}" data-action="${oppAction}" title="${data.status === 'accepted' ? 'Refuser' : 'Accepter'}">
                        ${oppLabel}
                    </button>
                    <button type="button" class="review-action-btn btn-delete action-btn"
                            data-id="${id}" data-action="destroy" title="Supprimer">
                        <i class="bi bi-trash3"></i><span>Supprimer</span>
                    </button>
                `;

            } catch (err) {
                console.error(err);
                btn.innerHTML = originalHTML;
                btn.disabled = false;
            }
        });
    });
</script>
@endpush
