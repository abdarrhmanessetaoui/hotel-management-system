@extends('layouts.app')

@section('header')
    @include('layouts.header')
    <div class="container-fluid page-header mb-5 p-0"
         style="background-image: url({{ asset('img/carousel-1.jpg') }});">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center pb-5">
                <h6 class="section-title text-white text-uppercase mb-3 animated slideInDown">
                    Hotelia — Développement
                </h6>
                <h1 class="display-3 text-white mb-3 animated slideInDown">
                    Liste de Vérification
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item text-white active" aria-current="page">
                            Tests
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-xxl py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-9">

                {{-- Header --}}
                <div class="text-center wow fadeInUp mb-5" data-wow-delay="0.1s">
                    <h6 class="section-title text-center text-primary text-uppercase">
                        Contrôle Qualité
                    </h6>
                    <h1>Liste de <span class="text-primary text-uppercase">Vérification</span></h1>
                    <p class="text-muted">
                        Cochez chaque point pour valider le bon fonctionnement de la plateforme Hotelia.
                    </p>
                </div>

                {{-- 1. Navigation Villes --}}
                <div class="room-item shadow rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.1s">
                    <h5 class="mb-3 text-primary">
                        <i class="fa fa-city me-2"></i>1. Navigation par Villes
                    </h5>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="t_city_1">
                        <label class="form-check-label" for="t_city_1">
                            La page d'accueil affiche la liste des villes correctement.
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="t_city_2">
                        <label class="form-check-label" for="t_city_2">
                            Le bouton "Explorer les Villes" du carousel défile jusqu'à la section villes.
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="t_city_3">
                        <label class="form-check-label" for="t_city_3">
                            Cliquer sur une ville affiche uniquement les hôtels de cette ville.
                        </label>
                    </div>
                </div>

                {{-- 2. Page Hôtel --}}
                <div class="room-item shadow rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.2s">
                    <h5 class="mb-3 text-primary">
                        <i class="fa fa-hotel me-2"></i>2. Page Hôtel
                    </h5>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="t_hotel_1">
                        <label class="form-check-label" for="t_hotel_1">
                            La page détail de l'hôtel affiche uniquement les chambres disponibles.
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="t_hotel_2">
                        <label class="form-check-label" for="t_hotel_2">
                            L'image, la description, la note et les équipements s'affichent correctement.
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="t_hotel_3">
                        <label class="form-check-label" for="t_hotel_3">
                            Le fil d'ariane indique : Accueil → Ville → Nom de l'hôtel.
                        </label>
                    </div>
                </div>

                {{-- 3. Réservation --}}
                <div class="room-item shadow rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.3s">
                    <h5 class="mb-3 text-primary">
                        <i class="fa fa-calendar-check me-2"></i>3. Processus de Réservation
                    </h5>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="t_res_1">
                        <label class="form-check-label" for="t_res_1">
                            Le bouton Réserver ouvre le formulaire avec la chambre pré-sélectionnée (?room=ID).
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="t_res_2">
                        <label class="form-check-label" for="t_res_2">
                            Les erreurs de validation (dates, clients) s'affichent correctement.
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="t_res_3">
                        <label class="form-check-label" for="t_res_3">
                            La double réservation est bloquée : "Cette chambre n'est pas disponible."
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="t_res_4">
                        <label class="form-check-label" for="t_res_4">
                            La réservation apparaît dans "Mes Réservations" avec le statut "En Attente".
                        </label>
                    </div>
                </div>

                {{-- 4. Authentification --}}
                <div class="room-item shadow rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.4s">
                    <h5 class="mb-3 text-primary">
                        <i class="fa fa-lock me-2"></i>4. Authentification
                    </h5>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="t_login_1">
                        <label class="form-check-label" for="t_login_1">
                            Un visiteur non connecté est redirigé vers la page de connexion.
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="t_login_2">
                        <label class="form-check-label" for="t_login_2">
                            Après connexion, le client est renvoyé vers le formulaire de réservation.
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="t_login_3">
                        <label class="form-check-label" for="t_login_3">
                            L'inscription crée un compte avec le rôle "client" par défaut.
                        </label>
                    </div>
                </div>

                {{-- 5. Panel Admin --}}
                <div class="room-item shadow rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.5s">
                    <h5 class="mb-3 text-primary">
                        <i class="fa fa-cog me-2"></i>5. Panel Administrateur Hôtel
                    </h5>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="t_admin_1">
                        <label class="form-check-label" for="t_admin_1">
                            L'admin voit uniquement les statistiques de son hôtel.
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="t_admin_2">
                        <label class="form-check-label" for="t_admin_2">
                            L'admin peut créer, modifier et supprimer ses chambres.
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="t_admin_3">
                        <label class="form-check-label" for="t_admin_3">
                            L'admin peut voir et gérer uniquement les réservations de son hôtel.
                        </label>
                    </div>
                </div>

                {{-- 6. Super Admin --}}
                <div class="room-item shadow rounded p-4 mb-4 wow fadeInUp" data-wow-delay="0.6s">
                    <h5 class="mb-3 text-primary">
                        <i class="fa fa-crown me-2"></i>6. Super Administrateur
                    </h5>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="t_super_1">
                        <label class="form-check-label" for="t_super_1">
                            Le Super Admin voit les statistiques globales : villes, hôtels, réservations.
                        </label>
                    </div>
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="checkbox" id="t_super_2">
                        <label class="form-check-label" for="t_super_2">
                            Le Super Admin peut créer, modifier et supprimer des villes et des hôtels.
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="t_super_3">
                        <label class="form-check-label" for="t_super_3">
                            Le Super Admin peut assigner et retirer les admins des hôtels.
                        </label>
                    </div>
                </div>

                {{-- Progress indicator --}}
                <div class="room-item shadow rounded p-4 wow fadeInUp" data-wow-delay="0.7s">
                    <h6 class="text-primary mb-3">
                        <i class="fa fa-tasks me-2"></i>Progression
                    </h6>
                    <div class="progress" style="height: 12px; border-radius: 6px;">
                        <div class="progress-bar bg-primary" role="progressbar"
                             id="progress-bar" style="width: 0%">
                        </div>
                    </div>
                    <p class="text-muted mt-2 mb-0 small text-end">
                        <span id="checked-count">0</span> / <span id="total-count">0</span> points validés
                    </p>
                </div>

            </div>
        </div>
    </div>
</div>

@include('sections.newsletter')
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

@push('scripts')
<script>
    // Live progress bar as user ticks checkboxes
    const checkboxes = document.querySelectorAll('.form-check-input[type="checkbox"]');
    const total      = checkboxes.length;
    const bar        = document.getElementById('progress-bar');
    const countEl    = document.getElementById('checked-count');
    const totalEl    = document.getElementById('total-count');

    totalEl.textContent = total;

    function updateProgress() {
        const checked = document.querySelectorAll('.form-check-input:checked').length;
        const pct     = Math.round((checked / total) * 100);
        bar.style.width = pct + '%';
        bar.textContent = pct > 10 ? pct + '%' : '';
        countEl.textContent = checked;
    }

    checkboxes.forEach(cb => cb.addEventListener('change', updateProgress));
    updateProgress();
</script>
@endpush