<div class="container-xxl testimonial my-5 py-5 bg-dark wow zoomIn" id="testimonial" data-wow-delay="0.1s">
    <div class="container">
        <div class="text-center mb-5">
            <h6 class="section-title text-center text-primary text-uppercase">Témoignages</h6>
            <h1 class="text-white">Ce que disent nos <span class="text-primary">clients</span></h1>
        </div>
        <div class="owl-carousel testimonial-carousel py-5">

            {{-- 1 --}}
            <div class="testimonial-item position-relative bg-white rounded overflow-hidden p-4">
                <div class="d-flex mb-3">
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                </div>
                <p class="text-body mb-3">
                    "J'ai trouvé un hôtel à Marrakech en moins de 2 minutes.
                    La réservation était simple, rapide et sans surprise.
                    Je recommande cette plateforme à tous les voyageurs."
                </p>
                <div class="d-flex align-items-center">
                    <img class="img-fluid flex-shrink-0 rounded"
                         src="https://randomuser.me/api/portraits/men/32.jpg"
                         style="width: 60px; height: 60px; object-fit: cover;" alt="Karim Alaoui">
                    <div class="ps-3">
                        <h6 class="fw-bold mb-1">Karim Alaoui</h6>
                        <small class="text-muted">Casablanca, Maroc</small>
                    </div>
                </div>
                <i class="fa fa-quote-right fa-3x text-primary position-absolute end-0 bottom-0 me-4 mb-n1"></i>
            </div>

            {{-- 2 --}}
            <div class="testimonial-item position-relative bg-white rounded overflow-hidden p-4">
                <div class="d-flex mb-3">
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                </div>
                <p class="text-body mb-3">
                    "Grâce à Hotelia, j'ai pu comparer plusieurs hôtels à Agadir
                    et choisir celui qui correspondait parfaitement à mon budget.
                    Expérience parfaite du début à la fin."
                </p>
                <div class="d-flex align-items-center">
                    <img class="img-fluid flex-shrink-0 rounded"
                         src="https://randomuser.me/api/portraits/women/44.jpg"
                         style="width: 60px; height: 60px; object-fit: cover;" alt="Sophie Renaud">
                    <div class="ps-3">
                        <h6 class="fw-bold mb-1">Sophie Renaud</h6>
                        <small class="text-muted">Lyon, France</small>
                    </div>
                </div>
                <i class="fa fa-quote-right fa-3x text-primary position-absolute end-0 bottom-0 me-4 mb-n1"></i>
            </div>

            {{-- 3 --}}
            <div class="testimonial-item position-relative bg-white rounded overflow-hidden p-4">
                <div class="d-flex mb-3">
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                </div>
                <p class="text-body mb-3">
                    "La plateforme est très claire et bien organisée par ville.
                    J'ai réservé à Tanger et mon hôtel était exactement
                    comme sur les photos. Très fiable."
                </p>
                <div class="d-flex align-items-center">
                    <img class="img-fluid flex-shrink-0 rounded"
                         src="https://randomuser.me/api/portraits/men/75.jpg"
                         style="width: 60px; height: 60px; object-fit: cover;" alt="Youssef Benkirane">
                    <div class="ps-3">
                        <h6 class="fw-bold mb-1">Youssef Benkirane</h6>
                        <small class="text-muted">Rabat, Maroc</small>
                    </div>
                </div>
                <i class="fa fa-quote-right fa-3x text-primary position-absolute end-0 bottom-0 me-4 mb-n1"></i>
            </div>

            {{-- 4 --}}
            <div class="testimonial-item position-relative bg-white rounded overflow-hidden p-4">
                <div class="d-flex mb-3">
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star text-warning"></i>
                    <i class="fa fa-star-half-alt text-warning"></i>
                </div>
                <p class="text-body mb-3">
                    "Hotelia m'a permis de gérer facilement les réservations
                    de mon hôtel à Fès. Le panel admin est intuitif et
                    mes clients adorent la simplicité de la réservation en ligne."
                </p>
                <div class="d-flex align-items-center">
                    <img class="img-fluid flex-shrink-0 rounded"
                         src="https://randomuser.me/api/portraits/men/58.jpg"
                         style="width: 60px; height: 60px; object-fit: cover;" alt="Hassan Filali">
                    <div class="ps-3">
                        <h6 class="fw-bold mb-1">Hassan Filali</h6>
                        <small class="text-muted">Fès, Maroc — Hôtelier</small>
                    </div>
                </div>
                <i class="fa fa-quote-right fa-3x text-primary position-absolute end-0 bottom-0 me-4 mb-n1"></i>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        $('.testimonial-carousel').owlCarousel({
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
    });
</script>
@endpush