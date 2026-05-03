<style>
/* ── Carousel height ── */
#header-carousel .carousel-item {
    height: 100vh;         /* full screen on desktop */
}
#header-carousel .carousel-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
}
}

.carousel-content-box {
    width: 100%;
    max-width: 100%;
}

@media (min-width: 992px) {
    .carousel-content-box {
        max-width: 750px;
    }
}
</style>

<div class="container-fluid p-0" id="header-carousel-container">
    <div id="header-carousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="5000">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#header-carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#header-carousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#header-carousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">

            {{-- Slide 1 --}}
            <div class="carousel-item active" data-bs-interval="5000">
                <img src="https://images.unsplash.com/photo-1597212618440-806262de4f6b?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"
                     alt="Marrakech" loading="eager">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3 carousel-content-box">
                        <h6 class="section-title text-white text-uppercase mb-3 animated slideInDown">
                            Bienvenue sur Hotelia
                        </h6>
                        <h1 class="display-3 text-white mb-4 animated slideInDown">
                            Les Meilleurs Hôtels<br>du Maroc
                        </h1>
                        <p class="text-white mb-4 animated fadeInUp" style="font-size:1.1rem;">
                            Explorez des centaines d'hôtels dans les plus belles
                            villes du Maroc. Réservez en quelques clics.
                        </p>
                        <a class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft"
                           href="#villes">
                            <i class="fa fa-city me-2"></i>Explorer les Villes
                        </a>
                    </div>
                </div>
            </div>

            {{-- Slide 2 --}}
            <div class="carousel-item" data-bs-interval="5000">
                <img src="https://images.unsplash.com/photo-1540541338287-41700207dee6?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"
                     alt="Hotel de luxe" loading="lazy">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3 carousel-content-box">
                        <h6 class="section-title text-white text-uppercase mb-3 animated slideInDown">
                            Réservation Instantanée
                        </h6>
                        <h1 class="display-3 text-white mb-4 animated slideInDown">
                            Trouvez Votre Chambre<br>Idéale
                        </h1>
                        <p class="text-white mb-4 animated fadeInUp" style="font-size:1.1rem;">
                            Simple, Double, Suite ou Deluxe — comparez et réservez
                            sans frais cachés ni intermédiaires.
                        </p>
                        <a class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft"
                           href="#villes">
                            <i class="fa fa-hotel me-2"></i>Voir les Hôtels
                        </a>
                    </div>
                </div>
            </div>

            {{-- Slide 3 --}}
            <div class="carousel-item" data-bs-interval="5000">
                <img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80"
                     alt="Hôtel en bord de mer" loading="lazy">
                <div class="carousel-caption d-flex flex-column align-items-center justify-content-center">
                    <div class="p-3 carousel-content-box">
                        <h6 class="section-title text-white text-uppercase mb-3 animated slideInDown">
                            Plusieurs Villes
                        </h6>
                        <h1 class="display-3 text-white mb-4 animated slideInDown">
                            Marrakech, Agadir,<br>Tanger &amp; Plus
                        </h1>
                        <p class="text-white mb-4 animated fadeInUp" style="font-size:1.1rem;">
                            Une plateforme unique pour découvrir et réserver
                            les meilleurs hôtels à travers tout le Maroc.
                        </p>
                        <a class="btn btn-primary py-md-3 px-md-5 me-3 animated slideInLeft"
                           href="#villes">
                            <i class="fa fa-map-marker-alt me-2"></i>Découvrir
                        </a>
                    </div>
                </div>
            </div>

        </div>

        <button class="carousel-control-prev" type="button"
                data-bs-target="#header-carousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon bg-primary rounded-circle"
                  aria-hidden="true" style="width:50px;height:50px;padding:10px;"></span>
            <span class="visually-hidden">Précédent</span>
        </button>
        <button class="carousel-control-next" type="button"
                data-bs-target="#header-carousel" data-bs-slide="next">
            <span class="carousel-control-next-icon bg-primary rounded-circle"
                  aria-hidden="true" style="width:50px;height:50px;padding:10px;"></span>
            <span class="visually-hidden">Suivant</span>
        </button>
    </div>
</div>

