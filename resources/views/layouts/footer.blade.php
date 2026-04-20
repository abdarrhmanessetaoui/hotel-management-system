<div class="container-fluid footer wow fadeIn" data-wow-delay="0.1s" style="background: #ffffff; color: #444444; border-top: 1px solid rgba(0,0,0,0.05);">
    <div class="container pb-5">
        <div class="row g-5 justify-content-lg-center">

            {{-- ── Contact ─────────────────────────────────────────────── --}}
            <div class="col-md-6 col-lg-3">
                <h6 class="section-title text-start text-primary text-uppercase mb-4">Contact</h6>
                <p class="mb-2"><i class="fa fa-map-marker-alt me-3"></i>123 Street, Casablanca, Maroc</p>
                <p class="mb-2"><i class="fa fa-phone-alt me-3"></i>+212 599 887 766</p>
                <p class="mb-2"><i class="fa fa-envelope me-3"></i>hotelia@gmail.com</p>
                <div class="d-flex pt-2">
                    <a class="btn btn-social" href=""><i class="fab fa-twitter"></i></a>
                    <a class="btn btn-social" href=""><i class="fab fa-facebook-f"></i></a>
                    <a class="btn btn-social" href=""><i class="fab fa-youtube"></i></a>
                    <a class="btn btn-social" href=""><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            {{-- Plateforme --}}
            <div class="col-md-6 col-lg-3">
                <h6 class="section-title text-start text-primary text-uppercase mb-4">
                    Plateforme
                </h6>
                <a class="btn btn-link" href="{{ route('home') }}">Accueil</a>
                <a class="btn btn-link" href="{{ route('home') }}#villes">Nos Villes</a>
                <a class="btn btn-link" href="{{ route('login') }}">Connexion</a>
                <a class="btn btn-link" href="{{ route('register') }}">S'inscrire</a>
                @auth
                    <a class="btn btn-link" href="{{ route('reservations.index') }}">
                        Mes Réservations
                    </a>
                @endauth
            </div>

            {{-- Villes --}}
            <div class="col-md-6 col-lg-3">
                <h6 class="section-title text-start text-primary text-uppercase mb-4">
                    Destinations
                </h6>
                <a class="btn btn-link" href="{{ route('home') }}#villes">Marrakech</a>
                <a class="btn btn-link" href="{{ route('home') }}#villes">Casablanca</a>
                <a class="btn btn-link" href="{{ route('home') }}#villes">Agadir</a>
                <a class="btn btn-link" href="{{ route('home') }}#villes">Tanger</a>
                <a class="btn btn-link" href="{{ route('home') }}#villes">Voir toutes</a>
            </div>

        </div>
    </div>

    {{-- ── Copyright ───────────────────────────────────────────────────── --}}
    <div class="container">
        <div class="copyright">
            <div class="row">
                <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                    &copy; {{ date('Y') }}
                    <a href="{{ route('home') }}" class="text-primary fw-bold text-decoration-none">
                        Hotelia
                    </a>. Tous droits réservés.
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <small class="text-white-50">
                        Plateforme hôtelière N°1 au Maroc
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Footer End -->

<!-- Back to Top -->
<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top">
    <i class="bi bi-arrow-up"></i>
</a>
