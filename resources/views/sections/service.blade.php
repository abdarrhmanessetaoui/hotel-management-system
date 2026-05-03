{{-- ✅ id="services" added — navbar Services link scrolls here --}}
<div class="container-xxl py-5" id="services">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title text-center text-primary text-uppercase">
                Pourquoi Nous Choisir
            </h6>
            <h1 class="mb-5">
                La Plateforme Hôtelière
                <span class="text-primary text-uppercase">N°1 au Maroc</span>
            </h1>
        </div>
        <div class="row g-4">

            {{-- 1 --}}
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">
                <a class="service-item rounded" href="{{ route('home') }}#villes" style="text-decoration: none;">
                    <div class="service-icon bg-transparent border rounded p-1">
                        <div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                            <i class="fa fa-city fa-2x text-primary"></i>
                        </div>
                    </div>
                    <h5 class="mb-3">Plusieurs Villes</h5>
                    <p class="text-body mb-0">
                        Explorez des hôtels dans les plus belles villes du Maroc —
                        Marrakech, Casablanca, Agadir, Tanger et bien plus encore.
                    </p>
                </a>
            </div>

            {{-- 2 --}}
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.2s">
                <a class="service-item rounded" href="{{ route('home') }}#villes" style="text-decoration: none;">
                    <div class="service-icon bg-transparent border rounded p-1">
                        <div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                            <i class="fa fa-hotel fa-2x text-primary"></i>
                        </div>
                    </div>
                    <h5 class="mb-3">Hôtels Sélectionnés</h5>
                    <p class="text-body mb-0">
                        Des établissements soigneusement sélectionnés avec des
                        chambres vérifiées, des photos réelles et des avis authentiques.
                    </p>
                </a>
            </div>

            {{-- 3 --}}
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.3s">
                <a class="service-item rounded" href="{{ route('home') }}#villes" style="text-decoration: none;">
                    <div class="service-icon bg-transparent border rounded p-1">
                        <div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                            <i class="fa fa-calendar-check fa-2x text-primary"></i>
                        </div>
                    </div>
                    <h5 class="mb-3">Réservation Instantanée</h5>
                    <p class="text-body mb-0">
                        Réservez votre chambre en quelques clics. Confirmation
                        immédiate, sans frais cachés ni surprises.
                    </p>
                </a>
            </div>

            {{-- 4 --}}
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.4s">
                <a class="service-item rounded" href="{{ route('home') }}#villes" style="text-decoration: none;">
                    <div class="service-icon bg-transparent border rounded p-1">
                        <div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                            <i class="fa fa-bed fa-2x text-primary"></i>
                        </div>
                    </div>
                    <h5 class="mb-3">Chambres Variées</h5>
                    <p class="text-body mb-0">
                        Simple, Double, Suite ou Deluxe — trouvez la chambre qui
                        correspond à vos besoins et à votre budget.
                    </p>
                </a>
            </div>

            {{-- 5 --}}
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.5s">
                <a class="service-item rounded" href="{{ route('home') }}#villes" style="text-decoration: none;">
                    <div class="service-icon bg-transparent border rounded p-1">
                        <div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                            <i class="fa fa-user-shield fa-2x text-primary"></i>
                        </div>
                    </div>
                    <h5 class="mb-3">Gestion Simplifiée</h5>
                    <p class="text-body mb-0">
                        Chaque hôtel dispose de son propre espace admin pour gérer
                        les chambres, les disponibilités et les réservations.
                    </p>
                </a>
            </div>

            {{-- 6 --}}
            <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.6s">
                <a class="service-item rounded" href="{{ route('home') }}#villes" style="text-decoration: none;">
                    <div class="service-icon bg-transparent border rounded p-1">
                        <div class="w-100 h-100 border rounded d-flex align-items-center justify-content-center">
                            <i class="fa fa-headset fa-2x text-primary"></i>
                        </div>
                    </div>
                    <h5 class="mb-3">Support 24h/24</h5>
                    <p class="text-body mb-0">
                        Notre équipe est disponible à tout moment pour vous aider
                        avant, pendant et après votre séjour.
                    </p>
                </a>
            </div>

        </div>
    </div>
</div>

