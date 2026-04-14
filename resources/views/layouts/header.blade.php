<div class="container-fluid bg-dark px-0 sticky-top" style="z-index:1030;">
    <div class="row gx-0">

        {{-- Desktop logo column --}}
        <div class="col-lg-2 bg-dark d-none d-lg-block" style="overflow:visible;">
            <a href="{{ route('home') }}"
                class="navbar-brand w-100 h-100 m-0 p-0 d-flex align-items-center justify-content-center">
                <img src="{{ asset('img/logo.png') }}" alt="Hotelia" style="height:110px; width:auto; object-fit:contain;
                            transform:scale(1.35); transform-origin:center center;">
            </a>
        </div>

        <div class="col-lg-10">

            {{-- Desktop utility bar --}}
            <div class="row gx-0 bg-white d-none d-lg-flex">
                <div class="col-lg-7 px-5 text-start">
                    <div class="h-100 d-inline-flex align-items-center py-2 me-4">
                        <i class="fa fa-envelope text-primary me-2"></i>
                        <p class="mb-0">hotelia@gmail.com</p>
                    </div>
                    <div class="h-100 d-inline-flex align-items-center py-2">
                        <i class="fa fa-phone-alt text-primary me-2"></i>
                        <p class="mb-0">+212 599 887 766</p>
                    </div>
                </div>
                <div class="col-lg-5 px-5 text-end">
                    <div class="d-inline-flex align-items-center py-2">
                        <a class="me-3" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="me-3" href=""><i class="fab fa-twitter"></i></a>
                        <a class="me-3" href=""><i class="fab fa-linkedin-in"></i></a>
                        <a class="me-3" href=""><i class="fab fa-instagram"></i></a>
                        <a class="" href=""><i class="fab fa-youtube"></i></a>
                    </div>
                </div>
            </div>

            {{-- Navbar --}}
            <nav class="navbar navbar-expand-lg bg-dark navbar-dark p-lg-0" style="padding:4px 10px !important;">

                {{-- Mobile logo --}}
                <a href="{{ route('home') }}" class="navbar-brand d-block d-lg-none m-0" style="padding:0 !important;">
                    <img src="{{ asset('img/logo.png') }}" alt="Hotelia"
                        style="height:45px; width:auto; object-fit:contain;">
                </a>

                {{-- Toggler --}}
                <button type="button" class="navbar-toggler border-0 ms-auto"
                    style="padding:2px 4px; width:28px; height:45px;" data-bs-toggle="collapse"
                    data-bs-target="#navbarCollapse">
                    <span class="navbar-toggler-icon" style="width:16px; height:16px; background-size:100%;"></span>
                </button>

                <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">

                    {{-- Left: section nav links --}}
                    <div class="navbar-nav py-0" id="main-nav">
                        <a class="nav-item nav-link px-3" data-section="top" href="{{ route('home') }}">Accueil</a>
                        <a class="nav-item nav-link px-3" data-section="villes"
                            href="{{ route('home') }}#villes">Destinations</a>
                        <a class="nav-item nav-link px-3" data-section="services"
                            href="{{ route('home') }}#services">Services</a>
                        <a class="nav-item nav-link px-3" data-section="testimonial"
                            href="{{ route('home') }}#testimonial">Avis</a>
                        <a class="nav-item nav-link px-3" data-section="newsletter"
                            href="{{ route('home') }}#newsletter">Contact</a>
                    </div>

                    {{-- Right: auth --}}
                    <div class="navbar-nav py-0 auth-nav pe-1">

                        @guest
                            <a href="{{ route('login') }}"
                                class="nav-btn-outline {{ request()->routeIs('login') ? 'active' : '' }}">
                                Connexion
                            </a>
                            <a href="{{ route('register') }}"
                                class="nav-btn-solid {{ request()->routeIs('register') ? 'active' : '' }}">
                                S'inscrire
                            </a>
                        @else
                            <div class="nav-item dropdown">
                                <button class="nav-btn-solid dropdown-toggle border-0" data-bs-toggle="dropdown"
                                    aria-expanded="false">
                                    <i class="fa fa-user-circle me-1"></i>{{ explode(' ', Auth::user()->name)[0] }}
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end rounded-0 mt-1 border-0 shadow">
                                    <li>
                                        <a href="{{ route('reservations.index') }}" class="dropdown-item py-2">
                                            <i class="fa fa-calendar-check me-2 text-primary"></i>Mes Réservations
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('profile') }}" class="dropdown-item py-2">
                                            <i class="fa fa-user me-2 text-primary"></i>Mon Profil
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider my-1">
                                    </li>
                                    <li>
                                        <form method="post" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="dropdown-item py-2 text-danger">
                                                <i class="fa fa-sign-out-alt me-2"></i>Déconnexion
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            </div>

                            @if(Auth::user()->isAdmin())
                                <a href="{{ route('admin.index') }}" class="nav-btn-outline ms-1">
                                    <i class="fa fa-cog me-1"></i>Admin
                                </a>
                            @elseif(Auth::user()->isSuperAdmin())
                                <a href="{{ route('superadmin.index') }}" class="nav-btn-outline ms-1">
                                    <i class="fa fa-crown me-1"></i>Super Admin
                                </a>
                            @endif
                        @endguest

                    </div>

                </div>
            </nav>
        </div>
    </div>
</div>

<style>
    /* ════════════════════════════════════════════════
   AUTH BUTTONS
════════════════════════════════════════════════ */
    .nav-btn-solid,
    .nav-btn-outline {
        display: inline-block;
        padding: 7px 16px;
        font-size: .82rem;
        font-weight: 600;
        text-decoration: none !important;
        white-space: nowrap;
        border: 2px solid var(--bs-primary);
        transition: opacity .15s;
        cursor: pointer;
        background: none;
        line-height: 1.4;
    }

    .nav-btn-solid {
        background: var(--bs-primary);
        color: #0F172B !important;
    }

    .nav-btn-outline {
        background: transparent;
        color: var(--bs-primary) !important;
    }

    .nav-btn-solid:hover,
    .nav-btn-outline:hover {
        opacity: .82;
    }

    /* ════════════════════════════════════════════════
   DESKTOP (≥ 992px)
════════════════════════════════════════════════ */
    @media (min-width: 992px) {

        /* Nav link sizing + vertical padding */
        #main-nav .nav-link {
            font-size: .82rem !important;
            letter-spacing: .05px;
            padding-top: 22px !important;
            padding-bottom: 22px !important;
        }

        /* Auth buttons row */
        .auth-nav {
            display: flex !important;
            flex-direction: row;
            align-items: center;
            gap: 8px;
        }

        /* Dropdown floats — right-aligned so it never overflows */
        .auth-nav .dropdown-menu {
            position: absolute !important;
            right: 0 !important;
            left: auto !important;
            top: 100% !important;
            min-width: 195px;
            z-index: 1051;
        }
    }

    /* ════════════════════════════════════════════════
   MOBILE (< 992px)
════════════════════════════════════════════════ */
    @media (max-width: 991.98px) {

        /* Nav links */
        .navbar-nav .nav-link {
            font-size: .9rem !important;
            padding: 9px 14px !important;
        }

        /* Auth section: column, full-width */
        .auth-nav {
            display: flex !important;
            flex-direction: column;
            align-items: stretch;
            padding: 8px 12px 12px;
            gap: 6px;
        }

        .auth-nav .nav-btn-solid,
        .auth-nav .nav-btn-outline {
            display: block;
            width: 100%;
            text-align: center;
            padding: 10px 16px;
            font-size: .9rem;
        }

        /* Dropdown: STATIC on mobile — flows inline, not floating */
        .auth-nav .dropdown,
        .auth-nav .nav-item.dropdown {
            width: 100%;
        }

        .auth-nav .dropdown-menu {
            position: static !important;
            float: none !important;
            width: 100% !important;
            border: none !important;
            border-top: 1px solid rgba(255, 255, 255, .1) !important;
            border-radius: 0 !important;
            background: rgba(255, 255, 255, .06) !important;
            box-shadow: none !important;
            padding: 4px 0 !important;
            margin: 0 !important;
        }

        .auth-nav .dropdown-menu .dropdown-item {
            color: rgba(255, 255, 255, .85) !important;
            font-size: .88rem;
            padding: 9px 18px !important;
        }

        .auth-nav .dropdown-menu .dropdown-item:hover {
            background: rgba(255, 255, 255, .1) !important;
            color: #fff !important;
        }

        .auth-nav .dropdown-menu .dropdown-item.text-danger {
            color: #f87171 !important;
        }

        .auth-nav .dropdown-menu hr.dropdown-divider {
            border-color: rgba(255, 255, 255, .15);
        }

        /* Dropdown toggle stretches full-width on mobile */
        .auth-nav .dropdown-toggle {
            width: 100%;
            text-align: center;
        }

        /* Prevent double-chevron on toggler icon */
        .auth-nav .nav-btn-solid.dropdown-toggle::after {
            margin-left: 6px;
        }
    }

    /* ════════════════════════════════════════════════
   SHARED UTILS
════════════════════════════════════════════════ */
    /* Parent must not clip the floating dropdown */
    .navbar-collapse,
    .navbar-nav {
        overflow: visible !important;
    }

    /* Scrollspy highlight */
    #main-nav .nav-link.scroll-active {
        color: var(--bs-primary) !important;
    }

    /* Kill section-title underline pseudo-elements on nav links */
    .navbar-dark .navbar-nav .nav-link::after,
    .navbar-dark .navbar-nav .nav-link::before,
    #main-nav .nav-link::after,
    #main-nav .nav-link::before {
        display: none !important;
        content: none !important;
    }
</style>

{{-- Scrollspy --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const links = document.querySelectorAll('#main-nav .nav-link[data-section]');
        const sections = [];
        links.forEach(link => {
            const sid = link.getAttribute('data-section');
            if (sid && sid !== 'top') {
                const el = document.getElementById(sid);
                if (el) sections.push({ link, el });
            }
        });
        function setActive() {
            if (sections.length === 0) return;
            const scrollY = window.scrollY + 120;
            let current = links[0];
            sections.forEach(({ link, el }) => {
                if (el.offsetTop <= scrollY) current = link;
            });
            links.forEach(l => l.classList.remove('scroll-active'));
            if (current) current.classList.add('scroll-active');
        }
        if (sections.length > 0) {
            window.addEventListener('scroll', setActive, { passive: true });
            setActive();
        }
    });
</script>