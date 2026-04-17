{{-- Navbar Header (Fixed Top Overlay) --}}
<header id="main-header" class="fixed-top header-transparent transition-all">
    


    {{-- Main Navigation --}}
    <nav class="navbar navbar-expand-lg py-2 px-4 px-lg-5 transition-all" id="main-navbar">
        
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="navbar-brand d-flex align-items-center bg-white rounded-3 shadow-sm px-3 py-1 m-0">
            <img src="{{ asset('img/logo.png') }}" id="brand-logo" alt="Hotelia" style="height: 45px; width: auto; object-fit: contain; transition: all 0.3s ease;">
        </a>

        {{-- Mobile Toggler --}}
        <button type="button" class="navbar-toggler border-0 shadow-none px-2 text-white" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" id="nav-toggler">
            <i class="fa fa-bars fs-2"></i>
        </button>

        {{-- Nav Links & Actions --}}
        <div class="collapse navbar-collapse" id="navbarCollapse">
            
            {{-- Centered Menu --}}
            <div class="navbar-nav mx-auto py-3 py-lg-0" id="main-nav">
                <a class="nav-item nav-link fw-medium px-3 scroll-target" data-section="top" href="{{ route('home') }}">Accueil</a>
                <a class="nav-item nav-link fw-medium px-3 scroll-target" data-section="villes" href="{{ route('home') }}#villes">Destinations</a>
                <a class="nav-item nav-link fw-medium px-3 scroll-target" data-section="services" href="{{ route('home') }}#services">Services</a>
                <a class="nav-item nav-link fw-medium px-3 scroll-target" data-section="testimonial" href="{{ route('home') }}#testimonial">Avis</a>
                <a class="nav-item nav-link fw-medium px-3 scroll-target" data-section="newsletter" href="{{ route('home') }}#newsletter">Contact</a>
            </div>

            {{-- Right Actions --}}
            <div class="navbar-nav auth-nav align-items-lg-center gap-2 pb-3 pb-lg-0">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-outline-primary d-inline-flex align-items-center justify-content-center rounded-2 px-3 py-2 fw-semibold transition-all">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="btn btn-primary d-inline-flex align-items-center justify-content-center rounded-2 px-3 py-2 fw-semibold text-white transition-all">
                        S'inscrire
                    </a>
                @else
                    <div class="nav-item dropdown">
                        <button class="btn btn-primary dropdown-toggle d-inline-flex align-items-center justify-content-center border-0 px-3 py-2 rounded-2 fw-semibold text-white w-100" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user-circle me-2"></i>{{ explode(' ', Auth::user()->name)[0] }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end rounded-3 mt-2 border-0 shadow modern-dropdown">
                            <li><a href="{{ route('reservations.index') }}" class="dropdown-item py-2"><i class="fa fa-calendar-check me-2 text-primary"></i>Mes Réservations</a></li>
                            <li><a href="{{ route('profile') }}" class="dropdown-item py-2"><i class="fa fa-user me-2 text-primary"></i>Mon Profil</a></li>
                            <li><hr class="dropdown-divider my-1"></li>
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
                        <a href="{{ route('admin.index') }}" class="btn btn-outline-primary ms-lg-2 rounded-2 px-3 py-2 fw-semibold">
                            <i class="fa fa-cog me-1"></i> Admin
                        </a>
                    @elseif(Auth::user()->isSuperAdmin())
                        <a href="{{ route('superadmin.index') }}" class="btn btn-warning text-dark ms-lg-2 rounded-2 px-3 py-2 fw-semibold text-nowrap">
                            <i class="fa fa-crown me-1"></i> Super Admin
                        </a>
                    @endif
                @endguest
            </div>
        </div>
    </nav>
</header>

<style>
    /* ════════════════════════════════════════════════
    GLOBAL HEADER STYLES & TRANSITIONS
    ════════════════════════════════════════════════ */
    #main-header {
        z-index: 1050;
        width: 100%;
        /* Hardware acceleration for smooth transitions */
        transform: translateZ(0); 
    }
    
    .transition-all {
        transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .hover-primary:hover { color: var(--bs-primary) !important; }

    /* ════════════════════════════════════════════════
    STATE 1: TRANSPARENT (Top of page)
    ════════════════════════════════════════════════ */
    .header-transparent {
        background: transparent;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .header-transparent #main-nav .nav-link {
        color: rgba(255, 255, 255, 0.9);
    }
    .header-transparent #main-nav .nav-link:hover,
    .header-transparent #main-nav .nav-link.scroll-active {
        color: var(--bs-primary);
    }

    .border-light-subtle {
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    /* Modern indicator line for active state */
    @media (min-width: 992px) {
        #main-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background-color: var(--bs-primary);
            transition: width 0.3s ease;
            border-radius: 2px;
        }
        
        #main-nav .nav-link.scroll-active::after,
        #main-nav .nav-link:hover::after {
            width: 80%;
        }
        
        #main-nav .nav-link {
            position: relative;
        }
    }

    /* ════════════════════════════════════════════════
    STATE 2: SOLID WHITE (On Scroll)
    ════════════════════════════════════════════════ */
    .header-scrolled {
        background: rgba(255, 255, 255, 0.98) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05) !important;
        border-bottom: none !important;
    }



    .header-scrolled #main-nav .nav-link {
        color: #333333 !important;
    }
    
    .header-scrolled #main-nav .nav-link:hover,
    .header-scrolled #main-nav .nav-link.scroll-active {
        color: var(--bs-primary) !important;
    }

    .header-scrolled #nav-toggler {
        color: #333333 !important;
    }

    .header-scrolled #brand-logo {
        height: 48px !important; /* Shrink logo slightly on scroll */
    }

    /* ════════════════════════════════════════════════
    DROPDOWN & MOBILE STYLING
    ════════════════════════════════════════════════ */
    .modern-dropdown {
        background-color: #ffffff;
        border-radius: 8px !important;
        padding: 8px 0;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }
    .modern-dropdown .dropdown-item {
        font-size: 0.9rem;
        font-weight: 500;
        color: #444;
        transition: all 0.2s;
    }
    .modern-dropdown .dropdown-item:hover {
        background-color: rgba(var(--bs-primary-rgb), 0.08);
        color: var(--bs-primary);
    }

    @media (max-width: 991.98px) {
        #navbarCollapse {
            background: #ffffff;
            border-radius: 8px;
            padding: 15px;
            margin-top: 10px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            border: 1px solid rgba(0,0,0,0.05);
        }
        
        #main-nav .nav-link {
            color: #333333 !important;
            padding: 12px 15px !important;
            border-bottom: 1px solid rgba(0,0,0,0.05);
        }

        .auth-nav {
            flex-direction: column;
            padding-top: 15px;
            border-top: 1px solid rgba(0,0,0,0.05);
            margin-top: 10px;
        }

        .auth-nav .btn {
            width: 100%;
            margin-left: 0 !important;
            margin-top: 8px;
        }

        .dropdown-menu {
            position: static !important;
            box-shadow: none !important;
            border: none !important;
            background: rgba(0,0,0,0.03) !important;
            margin-top: 10px;
        }
    }
</style>

{{-- Vanilla JS for Header Interactions --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const header = document.getElementById('main-header');
        const links = document.querySelectorAll('#main-nav .scroll-target');
        
        // 1. Scroll Transparency Toggle
        function toggleHeaderBackground() {
            if (window.scrollY > 50) {
                header.classList.add('header-scrolled');
                header.classList.remove('header-transparent');
            } else {
                header.classList.remove('header-scrolled');
                header.classList.add('header-transparent');
            }
        }

        // Initialize state
        toggleHeaderBackground();
        window.addEventListener('scroll', toggleHeaderBackground, { passive: true });

        // 2. Active State Scrollspy
        const sections = [];
        links.forEach(link => {
            const sid = link.getAttribute('data-section');
            if (sid && sid !== 'top') {
                const el = document.getElementById(sid);
                if (el) sections.push({ link, el });
            }
        });

        function setActiveScrollspy() {
            if (sections.length === 0) return;
            const scrollY = window.scrollY + 120;
            let current = links[0]; // default 'Accueil'
            
            sections.forEach(({ link, el }) => {
                if (el.offsetTop <= scrollY) current = link;
            });
            links.forEach(l => l.classList.remove('scroll-active'));
            if (current) current.classList.add('scroll-active');
        }
        if (sections.length > 0) {
            window.addEventListener('scroll', setActiveScrollspy, { passive: true });
            setActiveScrollspy();
        }
    });
</script>