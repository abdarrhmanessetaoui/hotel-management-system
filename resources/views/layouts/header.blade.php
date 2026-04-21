{{-- Navbar Header (Fixed Top Overlay) --}}
<header id="main-header" class="fixed-top header-transparent transition-all">
    {{-- Main Navigation --}}
    <nav class="navbar navbar-expand-lg py-0 px-3 px-lg-5 transition-all" id="main-navbar">
        
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="navbar-brand d-none d-md-flex align-items-center p-0" style="line-height: 0; z-index: 10; margin: 5px 0; margin-right: 15px;">
            <img src="{{ asset('img/logo.png') }}" id="brand-logo" alt="Hotelia" class="transition-all" style="height: 55px; width: auto; object-fit: contain; transform: scale(1.1); transform-origin: left center;">
        </a>

        {{-- Mobile Toggler --}}
        <button type="button" class="navbar-toggler border-0 shadow-none px-2 text-white" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" id="nav-toggler">
            <i class="fa fa-bars fs-2"></i>
        </button>

        {{-- Nav Links & Actions --}}
        <div class="collapse navbar-collapse" id="navbarCollapse">
            {{-- Mobile Logo Header --}}
            <div class="d-lg-none text-center py-4 mb-3 border-bottom">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="Hotelia" style="height: 40px; width: auto;">
                </a>
            </div>
            
            {{-- Centered Menu --}}
            <div class="navbar-nav mx-lg-auto py-3 py-lg-0 align-items-lg-center text-start" id="main-nav">
                <a class="nav-item nav-link fw-medium scroll-target" data-section="top" href="{{ route('home') }}">Accueil</a>
                <a class="nav-item nav-link fw-medium scroll-target" data-section="villes" href="{{ route('home') }}#villes">Destinations</a>
                <a class="nav-item nav-link fw-medium scroll-target" data-section="services" href="{{ route('home') }}#services">Services</a>
                <a class="nav-item nav-link fw-medium scroll-target" data-section="testimonial" href="{{ route('home') }}#testimonial">Avis</a>
                <a class="nav-item nav-link fw-medium scroll-target" data-section="newsletter" href="{{ route('home') }}#newsletter">Contact</a>
            </div>

            {{-- Right Actions --}}
            <div class="navbar-nav auth-nav align-items-lg-center gap-2 pb-3 pb-lg-0">
                @guest
                    <a href="{{ route('login') }}" class="btn-custom-outline transition-all">Connexion</a>
                    <a href="{{ route('register') }}" class="btn-custom-solid transition-all">S'inscrire</a>
                @else
                    {{-- [MOBILE ONLY] Personalized User Header --}}
                    <div class="d-lg-none px-4 py-4 border-bottom mb-3 w-100 bg-light">
                        <div class="d-flex align-items-center justify-content-center flex-column">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mb-2 shadow-sm" style="width: 60px; height: 60px; font-size: 1.5rem; font-weight: 800;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <div class="text-center">
                                <small class="text-muted d-block text-uppercase letter-spacing-1" style="font-size: 0.7rem;">Bonjour,</small>
                                <span class="fw-bold text-dark fs-5">{{ Auth::user()->name }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- [MOBILE ONLY] Authenticated Navigation Links --}}
                    <div class="d-lg-none w-100 auth-mobile-links">
                        <a href="{{ route('reservations.index') }}" class="mobile-nav-item">
                            <i class="fa fa-calendar-check me-3 text-primary"></i>Mes Réservations
                        </a>
                        <a href="{{ route('profile') }}" class="mobile-nav-item">
                            <i class="fa fa-user me-3 text-primary"></i>Mon Profil
                        </a>
                        
                        @if(Auth::user()->isAdmin())
                            <a href="{{ route('admin.index') }}" class="mobile-nav-item text-primary fw-bold">
                                <i class="fa fa-cog me-3"></i>Tableau de bord Admin
                            </a>
                        @elseif(Auth::user()->isSuperAdmin())
                            <a href="{{ route('superadmin.index') }}" class="mobile-nav-item text-primary fw-bold">
                                <i class="fa fa-crown me-3"></i>Tableau de bord Super Admin
                            </a>
                        @endif

                        <form method="post" action="{{ route('logout') }}" class="w-100">
                            @csrf
                            <button type="submit" class="mobile-nav-item text-danger border-0 w-100 text-start bg-transparent">
                                <i class="fa fa-sign-out-alt me-3"></i>Déconnexion
                            </button>
                        </form>
                    </div>

                    {{-- [DESKTOP ONLY] Dropdown Menu --}}
                    <div class="nav-item dropdown d-none d-lg-block">
                        <button class="btn-custom-solid dropdown-toggle border-0" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user-circle me-2"></i>{{ explode(' ', Auth::user()->name)[0] }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end mt-2 border-0 shadow modern-dropdown">
                            <li><a href="{{ route('reservations.index') }}" class="dropdown-item py-2"><i class="fa fa-calendar-check me-2 text-primary"></i>Mes Réservations</a></li>
                            <li><a href="{{ route('profile') }}" class="dropdown-item py-2"><i class="fa fa-user me-2 text-primary"></i>Mon Profil</a></li>
                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form method="post" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger py-2"><i class="fa fa-sign-out-alt me-2"></i>Déconnexion</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                @endguest
            </div>
        </div>
    </nav>
</header>

<style>
    /* ════════════════════════════════════════════════
    GLOBAL HEADER & MOBILE FIXES
    ════════════════════════════════════════════════ */
    #main-header { z-index: 1050; width: 100%; transition: all 0.3s ease; }
    .transition-all { transition: all 0.3s ease; }
    
    #main-nav .nav-link { 
        padding: 0 15px !important; 
        display: flex; align-items: center; height: 55px; 
    }

    .header-transparent { background: transparent; }
    .header-transparent .nav-link { color: #fff !important; }
    .header-scrolled { background: #fff !important; box-shadow: 0 4px 20px rgba(0,0,0,0.1); }
    .header-scrolled .nav-link { color: #333 !important; }
    .header-scrolled #nav-toggler { color: #333 !important; }

    @media (max-width: 991.98px) {
        #navbarCollapse {
            position: fixed !important; top: 0; left: -100% !important;
            width: 100vw !important; height: 100vh !important;
            background: #fff !important; z-index: 9999 !important;
            padding: 1rem 0 !important; transition: left 0.4s ease !important;
            display: flex !important; flex-direction: column; overflow-y: auto !important;
        }
        #navbarCollapse.show { left: 0 !important; }
        .sidebar-close { position: absolute; top: 20px; right: 20px; border: none; background: none; font-size: 1.5rem; }

        /* AUTH FIX: Hide absolute desktop buttons on mobile */
        .auth-nav .dropdown { display: none !important; } 
        .auth-nav .btn-custom-solid, .auth-nav .btn-custom-outline { display: none !important; }
        
        .mobile-nav-item {
            display: flex !important;
            align-items: center !important;
            padding: 15px 30px !important;
            font-size: 1.1rem !important;
            font-weight: 700 !important;
            color: #1a1a1a !important;
            border-bottom: 1px solid rgba(0,0,0,0.04) !important;
            text-decoration: none !important;
            background: #fff !important;
        }

        .auth-nav { margin-top: auto !important; width: 100% !important; }
    }

    /* SaaS Styles for Buttons */
    .btn-custom-solid, .btn-custom-outline {
        font-weight: 700; padding: 0 25px; height: 42px; border-radius: 8px; transition: 0.3s;
    }
    .btn-custom-solid { background: var(--primary); color: #000 !important; }
    .btn-custom-outline { border: 1px solid var(--primary); color: var(--primary) !important; }
</style>

{{-- Backdrop --}}
<div class="sidebar-overlay" id="sidebar-overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:9000;display:none;"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const header = document.getElementById('main-header');
        const navCollapse = document.getElementById('navbarCollapse');
        const overlay = document.getElementById('sidebar-overlay');
        const navToggler = document.getElementById('nav-toggler');
        
        function toggleSidebar() {
            const isOpen = document.body.classList.toggle('sidebar-open');
            navCollapse.classList.toggle('show', isOpen);
            overlay.style.display = isOpen ? 'block' : 'none';
        }

        navToggler.onclick = toggleSidebar;
        overlay.onclick = toggleSidebar;

        // Close on link click
        document.querySelectorAll('.scroll-target, .mobile-nav-item').forEach(link => {
            link.onclick = () => { if (window.innerWidth < 992) toggleSidebar(); };
        });

        if (window.innerWidth < 992 && !document.querySelector('.sidebar-close')) {
            const btn = document.createElement('button');
            btn.className = 'sidebar-close'; btn.innerHTML = '✕'; btn.onclick = toggleSidebar;
            navCollapse.prepend(btn);
        }

        window.onscroll = () => {
            if (window.scrollY > 50) { header.classList.add('header-scrolled'); header.classList.remove('header-transparent'); }
            else { header.classList.remove('header-scrolled'); header.classList.add('header-transparent'); }
        };
    });
</script>
