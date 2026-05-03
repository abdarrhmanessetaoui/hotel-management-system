<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title text-center text-primary text-uppercase">
                Créateur de la Plateforme
            </h6>
            <h1 class="mb-5">
                Derrière
                <span class="text-primary text-uppercase">Hotelia</span>
            </h1>
        </div>
        <div class="row g-4 justify-content-center">

            <div class="col-lg-3 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <div class="rounded shadow overflow-hidden">
                    <div class="position-relative">
                        <img class="img-fluid w-100" src="{{ asset('img/team-1.jpg') }}"
                             alt="Abdarrhmane Setaoui" style="height: 300px; object-fit: cover;">
                        <div class="position-absolute start-50 top-100 translate-middle d-flex align-items-center"
                             style="width: 100%; justify-content: center;">
                            <a class="btn btn-square btn-primary mx-1" href="#"><i class="fab fa-github"></i></a>
                            <a class="btn btn-square btn-primary mx-1" href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a class="btn btn-square btn-primary mx-1" href="#"><i class="fab fa-twitter"></i></a>
                        </div>
                    </div>
                    <div class="text-center p-4 mt-3" style="min-height: 100px;">
                        <h5 class="fw-bold mb-1">Abdarrhmane Setaoui</h5>
                        <small class="text-primary">Full-Stack Developer & Fondateur</small>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .rounded {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .rounded:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }
    .rounded .btn-square {
        opacity: 0;
        transform: translateY(-10px);
        transition: all 0.3s ease;
    }
    .rounded:hover .btn-square {
        opacity: 1;
        transform: translateY(-25px);
    }
</style>

