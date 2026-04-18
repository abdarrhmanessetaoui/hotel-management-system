{{-- ─── Témoignages dynamiques ────────────────────────────────────────────── --}}
<div class="container-xxl testimonial my-5 py-5 bg-dark wow zoomIn" id="testimonial" data-wow-delay="0.1s">
    <div class="container">
        <div class="text-center mb-5">
            <h6 class="section-title text-center text-primary text-uppercase">Témoignages</h6>
            <h1 class="text-white">Ce que disent nos <span class="text-primary">clients</span></h1>
        </div>

        {{-- ── Carousel des avis acceptés ─────────────────────────────────── --}}
        @if($acceptedReviews->count() > 0)
            <div class="owl-carousel testimonial-carousel py-5" id="testimonial-carousel">
                @foreach($acceptedReviews as $review)
                    <div class="testimonial-item position-relative bg-white rounded overflow-hidden p-4">
                        {{-- Étoiles --}}
                        <div class="d-flex mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star {{ $i <= $review->rating ? 'text-warning' : 'text-muted opacity-25' }}"></i>
                            @endfor
                        </div>
                        <p class="text-body mb-3">
                            "{{ $review->content }}"
                        </p>
                        <div class="d-flex align-items-center">
                            {{-- Unified Avatar System --}}
                            <div class="flex-shrink-0">
                                @if($review->user)
                                    <img class="rounded shadow-sm" 
                                         src="{{ $review->user->avatar_url }}" 
                                         alt="{{ $review->author_name }}"
                                         style="width: 60px; height: 60px; object-fit: cover; border: 2px solid rgba(255,126,33,0.1);">
                                @else
                                    <div class="d-flex align-items-center justify-content-center rounded shadow-sm"
                                         style="width:60px;height:60px;background-color:#FF7E21;font-size:1.4rem;font-weight:700;color:#fff;">
                                        {{ strtoupper(substr($review->author_name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div class="ps-3">
                                <h6 class="fw-bold mb-1">{{ $review->author_name }}</h6>
                                <small class="text-muted">{{ $review->created_at->format('M Y') }}</small>
                            </div>
                        </div>
                        <i class="fa fa-quote-right fa-3x text-primary position-absolute end-0 bottom-0 me-4 mb-n1"></i>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Fallback si aucun avis accepté --}}
            <div class="text-center py-5">
                <i class="fa fa-comments fa-3x text-primary opacity-50 mb-3 d-block"></i>
                <p class="text-white-50">Soyez le premier à laisser un témoignage !</p>
            </div>
        @endif
    </div>
</div>

{{-- ── Formulaire d'ajout d'avis (Séparé du bg-dark) ─────────────────────────── --}}
<div class="container-xxl py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-7 col-md-9">
                <div class="text-center mb-4">
                    <h6 class="section-title text-center text-primary text-uppercase">Votre Avis Compte</h6>
                    <h1 class="mb-3">Partagez votre expérience</h1>
                    <p class="text-muted mb-0">Votre avis sera visible publiquement après validation par notre équipe.</p>
                </div>

                {{-- Conteneur d'alerte dynamique --}}
                <div id="review-alert-container">
                    @guest
                        <div class="alert alert-warning d-flex align-items-center gap-2 mb-4 shadow-sm" style="border:1px solid rgba(0,0,0,0.05);" role="alert">
                            <i class="bi bi-info-circle-fill fs-5 text-warning"></i>
                            <span class="text-dark">Veuillez vous <a href="{{ route('login') }}?redirect={{ urlencode(url()->current() . '#review-form-section') }}" class="alert-link fw-bold text-dark text-decoration-underline">connecter</a> pour laisser un avis.</span>
                        </div>
                    @endguest
                </div>

                {{-- Formulaire --}}
                <div class="bg-white rounded p-4 shadow-sm position-relative" style="border: 1px solid rgba(0,0,0,0.08);" id="review-form-section">


                    <form id="review-form" novalidate>
                        @csrf

                        {{-- Nom (Automatique via Auth) --}}
                        <div class="mb-3 d-flex align-items-center gap-2 text-muted bg-light p-2 rounded border" style="font-size:0.9rem;">
                            <i class="bi bi-person-circle fs-5"></i>
                            @auth
                                <span class="fw-bold text-dark">{{ Auth::user()->name }}</span>
                            @else
                                <span>Connectez-vous pour afficher votre nom</span>
                            @endauth
                        </div>

                        {{-- Note étoiles (interactive) --}}
                        <div class="mb-3">
                            <label class="form-label fw-semibold">
                                Votre note
                            </label>
                            <div class="d-flex gap-1 align-items-center" id="star-rating">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa fa-star fa-lg text-muted star-btn"
                                       data-value="{{ $i }}"
                                       style="cursor:pointer; transition: color .15s;"
                                       title="{{ $i }} étoile{{ $i > 1 ? 's' : '' }}"></i>
                                @endfor
                                <span class="ms-2 text-muted small" id="star-label">Sélectionnez une note</span>
                            </div>
                            <input type="hidden" name="rating" id="review_rating" value="5">
                            <div class="text-danger small mt-1 d-none" id="error-rating"></div>
                        </div>

                        {{-- Avis --}}
                        <div class="mb-4">
                            <label for="review_content" class="form-label fw-semibold">
                                Votre témoignage
                            </label>
                            <textarea id="review_content"
                                      name="content"
                                      rows="3"
                                      class="form-control"
                                      placeholder="Partagez votre expérience avec Hotelia..."
                                      maxlength="1000"
                                      required></textarea>
                            <div class="d-flex justify-content-between mt-1">
                                <div class="invalid-feedback" id="error-content"></div>
                                <small class="text-muted ms-auto" id="char-counter">0 / 1000</small>
                            </div>
                        </div>

                        {{-- Bouton --}}
                        <div class="d-grid mt-2">
                            <button type="submit" class="btn btn-primary py-2 fw-bold" id="review-submit-btn" @guest disabled @endguest>
                                Envoyer mon témoignage
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    // ── Owl Carousel (uniquement si des avis existent)
    if ($('#testimonial-carousel').length) {
        $('#testimonial-carousel').owlCarousel({
            loop: true,
            margin: 20,
            nav: true,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            responsive: {
                0:    { items: 1 },
                768:  { items: 2 },
                1024: { items: 3 }
            },
            navText: [
                '<i class="fa fa-chevron-left"></i>',
                '<i class="fa fa-chevron-right"></i>'
            ]
        });
    }

    // ── Star Rating interactif
    let currentRating = parseInt(document.getElementById('review_rating').value) || 5;
    const labels = ['', 'Très mauvais', 'Mauvais', 'Correct', 'Bien', 'Excellent'];
    const starBtns = document.querySelectorAll('.star-btn');
    const starLabel = document.getElementById('star-label');
    const ratingInput = document.getElementById('review_rating');

    function paintStars(n) {
        starBtns.forEach(btn => {
            const v = parseInt(btn.dataset.value);
            if (v <= n) {
                btn.classList.add('text-warning');
                btn.classList.remove('text-muted');
            } else {
                btn.classList.remove('text-warning');
                btn.classList.add('text-muted');
            }
        });
        starLabel.textContent = n > 0 ? labels[n] + ' (' + n + '/5)' : 'Sélectionnez une note';
    }

    paintStars(currentRating);

    starBtns.forEach(btn => {
        btn.addEventListener('mouseenter', () => paintStars(parseInt(btn.dataset.value)));
        btn.addEventListener('click', () => {
            currentRating = parseInt(btn.dataset.value);
            ratingInput.value = currentRating;
            paintStars(currentRating);
        });
    });

    document.getElementById('star-rating').addEventListener('mouseleave', () => paintStars(currentRating));

    // ── Compteur de caractères
    const textarea = document.getElementById('review_content');
    const counter = document.getElementById('char-counter');

    textarea.addEventListener('input', function() {
        const len = this.value.length;
        counter.textContent = len + ' / 1000';
        if(len > 950) {
            counter.classList.add('text-danger');
        } else {
            counter.classList.remove('text-danger');
        }
    });

    // ── Validation AJAX
    const form = document.getElementById('review-form');
    const submitBtn = document.getElementById('review-submit-btn');
    const alertContainer = document.getElementById('review-alert-container');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        // Reset errors
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback, .text-danger').forEach(el => {
            if(el.id && el.id.startsWith('error-')) {
                el.textContent = '';
                if(el.id === 'error-rating') el.classList.add('d-none');
                else el.style.display = 'none';
            }
        });
        alertContainer.innerHTML = '';

        // UI Loading
        const originalHtml = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Envoi en cours...';

        try {
            const formData = new FormData(form);
            const response = await fetch("{{ route('reviews.store') }}", {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest', // Important for Laravel validation formats
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
                // Success
                form.reset();
                paintStars(5);
                currentRating = 5;
                counter.textContent = '0 / 1000';
                
                alertContainer.innerHTML = `
                    <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                        <span>${data.message}</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                    </div>
                `;
            } else if (response.status === 422) {
                // Validation Errors
                const errors = data.errors;
                for (const field in errors) {
                    const input = document.getElementById('review_' + field);
                    const errorDiv = document.getElementById('error-' + field);
                    if (input) input.classList.add('is-invalid');
                    if (errorDiv) {
                        errorDiv.textContent = errors[field][0];
                        if (field === 'rating') errorDiv.classList.remove('d-none');
                        else errorDiv.style.display = 'block';
                    }
                }
            } else {
                throw new Error('Unexpected status');
            }
        } catch (error) {
            console.error(error);
            alertContainer.innerHTML = `
                <div class="alert alert-danger alert-dismissible fade show d-flex align-items-center gap-2" role="alert">
                    <i class="fa fa-exclamation-triangle fs-5"></i>
                    <span>Une erreur s'est produite lors de l'envoi. Veuillez réessayer.</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>
                </div>
            `;
        }

        // Reset global loader
        submitBtn.disabled = false;
        submitBtn.innerHTML = originalHtml;
    });

});
</script>
@endpush