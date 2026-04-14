<div id="newsletter" class="container newsletter mt-5 wow fadeIn" data-wow-delay="0.1s">
    <div class="row justify-content-center">
        <div class="col-lg-10 border rounded p-1">
            <div class="border rounded text-center p-1">
                <div class="bg-white rounded text-center p-4 p-md-5">

                    <i class="fa fa-envelope-open-text fa-2x text-primary mb-3"></i>

                    <h4 class="mb-2">
                        Restez informé avec
                        <span class="text-primary text-uppercase">Hotelia</span>
                    </h4>

                    <p class="text-muted mb-4 px-2 px-md-4">
                        Recevez en avant-première nos meilleures offres d'hôtels,
                        nos nouvelles destinations et nos promotions exclusives au Maroc.
                    </p>

                    {{-- Feedback message (hidden by default) --}}
                    <div id="newsletter-feedback" class="mx-auto mb-3 d-none" style="max-width:460px;"></div>

                    <div class="mx-auto" style="max-width:460px;">
                        <form id="newsletter-form" method="POST" action="{{ route('newsletter.store') }}">
                            @csrf
                            <div class="d-flex flex-column flex-sm-row gap-2">
                                <input id="newsletter-email"
                                       class="form-control py-3 ps-4"
                                       type="email"
                                       name="email"
                                       placeholder="Votre adresse email"
                                       required
                                       style="border-radius:8px;">
                                <button id="newsletter-btn"
                                        type="submit"
                                        class="btn btn-primary py-3 px-4 flex-shrink-0"
                                        style="border-radius:8px;white-space:nowrap;">
                                    <i class="fa fa-paper-plane me-2"></i>S'abonner
                                </button>
                            </div>
                            <div id="newsletter-error" class="text-danger text-start mt-2 small d-none"></div>
                        </form>
                    </div>

                    <div class="d-flex flex-wrap justify-content-center gap-3 mt-4">
                        <small class="text-muted">
                            <i class="fa fa-shield-alt text-primary me-1"></i>Aucun spam
                        </small>
                        <small class="text-muted">
                            <i class="fa fa-times-circle text-primary me-1"></i>Désabonnement facile
                        </small>
                        <small class="text-muted">
                            <i class="fa fa-tag text-primary me-1"></i>Offres exclusives
                        </small>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('newsletter-form').addEventListener('submit', async function (e) {
    e.preventDefault();

    const form      = this;
    const btn       = document.getElementById('newsletter-btn');
    const feedback  = document.getElementById('newsletter-feedback');
    const errorBox  = document.getElementById('newsletter-error');
    const emailInput = document.getElementById('newsletter-email');

    // Reset state
    feedback.className = 'mx-auto mb-3 d-none';
    feedback.innerHTML = '';
    errorBox.className = 'text-danger text-start mt-2 small d-none';
    errorBox.innerHTML = '';

    // Loading state
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Envoi...';

    try {
        const response = await fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            },
            body: new FormData(form),
        });

        const data = await response.json();

        if (response.ok) {
            // ✅ Success
            feedback.innerHTML = `<div class="alert alert-success d-flex align-items-center gap-2 mb-0">
                <i class="fa fa-check-circle"></i>${data.message}
            </div>`;
            feedback.className = 'mx-auto mb-3';
            emailInput.value = '';
            form.style.display = 'none';
        } else {
            // ❌ Validation error
            const msg = data.errors?.email?.[0] ?? 'Une erreur est survenue.';
            errorBox.textContent = msg;
            errorBox.className = 'text-danger text-start mt-2 small';
            // Reset button
            btn.disabled = false;
            btn.innerHTML = '<i class="fa fa-paper-plane me-2"></i>S\'abonner';
        }

    } catch (err) {
        errorBox.textContent = 'Erreur réseau. Veuillez réessayer.';
        errorBox.className = 'text-danger text-start mt-2 small';
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-paper-plane me-2"></i>S\'abonner';
    }
});
</script>