<footer class="container-fluid site-footer wow fadeIn" data-wow-delay="0.1s">
    <div class="container">
        <div class="row g-5">
            
            {{-- 1. CONTACT --}}
            <div class="col-12 col-md-4 footer-col">
                <h6 class="footer-title">Contact</h6>
                <div class="footer-contact-item mb-4">
                    <a href="mailto:hotelia@gmail.com" class="text-decoration-none" style="color: #666; font-size: 15px;">
                        <i class="fa fa-envelope text-primary me-2"></i>hotelia@gmail.com
                    </a>
                </div>
                <div class="footer-social-box">
                    <a href="#" class="social-btn"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-youtube"></i></a>
                    <a href="#" class="social-btn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            {{-- 2. PLATEFORME --}}
            <div class="col-12 col-md-4 footer-col">
                <h6 class="footer-title">Plateforme</h6>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}">Accueil</a></li>
                    <li><a href="{{ route('home') }}#villes">Nos Villes</a></li>
                    <li><a href="{{ route('login') }}">Connexion</a></li>
                    <li><a href="{{ route('register') }}">S'inscrire</a></li>
                    @auth
                        <li><a href="{{ route('reservations.index') }}">Mes Réservations</a></li>
                    @endauth
                </ul>
            </div>

            {{-- 3. DESTINATIONS --}}
            <div class="col-12 col-md-4 footer-col">
                <h6 class="footer-title">Destinations</h6>
                <ul class="footer-links">
                    <li><a href="{{ route('home') }}#villes">Marrakech</a></li>
                    <li><a href="{{ route('home') }}#villes">Casablanca</a></li>
                    <li><a href="{{ route('home') }}#villes">Agadir</a></li>
                    <li><a href="{{ route('home') }}#villes">Tanger</a></li>
                </ul>
            </div>

        </div>

        {{-- Bottom Copyright Bar --}}
        <div class="footer-bottom">
            <p class="mb-0">© {{ date('Y') }} Hotelia. Tous droits réservés.</p>
        </div>
    </div>
</footer>

