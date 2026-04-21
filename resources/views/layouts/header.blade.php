{{-- Navbar Header (Fixed Top Overlay) --}}
<header id="main-header" class="fixed-top header-transparent transition-all">
    <nav class="navbar navbar-expand-lg py-0 px-3 px-lg-5 transition-all" id="main-navbar">
        
        {{-- Logo --}}
        <a href="{{ route('home') }}" class="navbar-brand d-none d-md-flex align-items-center p-0" style="z-index: 10;">
            <img src="{{ asset('img/logo.png') }}" id="brand-logo" alt="Hotelia" style="height: 55px; width: auto;">
        </a>

        {{-- Mobile Toggler --}}
        <button type="button" class="navbar-toggler border-0 shadow-none px-2 text-white" id="nav-toggler">
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
            
            {{-- Main Menu --}}
            <div class="navbar-nav mx-lg-auto py-3 py-lg-0 align-items-lg-center" id="main-nav">
                <a class="nav-item nav-link fw-bold scroll-target" href="{{ route('home') }}">Accueil</a>
                <a class="nav-item nav-link fw-bold scroll-target" href="{{ route('home') }}#villes">Destinations</a>
                <a class="nav-item nav-link fw-bold scroll-target" href="{{ route('home') }}#services">Services</a>
                <a class="nav-item nav-link fw-bold scroll-target" href="{{ route('home') }}#testimonial">Avis</a>
                <a class="nav-item nav-link fw-bold scroll-target" href="{{ route('home') }}#newsletter">Contact</a>
            </div>

            {{-- Auth Section --}}
            <div class="navbar-nav auth-nav align-items-lg-center gap-2 pb-3 pb-lg-0">
                @guest
                    <a href="{{ route('login') }}" class="btn-custom-outline">Connexion</a>
                    <a href="{{ route('register') }}" class="btn-custom-solid">S'inscrire</a>
                @else
                    {{-- 📱 MOBILE VIEW (Hidden on Desktop) --}}
                    <div class="d-lg-none w-100">
                        {{-- SINGLE Greeting Block --}}
                        <div class="px-4 py-4 border-bottom mb-3 bg-light text-center">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-2 shadow-sm" style="width: 65px; height: 65px; font-size: 1.6rem; font-weight: 800;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </div>
                            <small class="text-muted d-block text-uppercase" style="font-size: 0.7rem; letter-spacing: 1px;">Bonjour,</small>
                            <span class="fw-bold text-dark fs-5">{{ Auth::user()->name }}</span>
                        </div>

                        {{-- Mobile Link List --}}
                        <div class="auth-mobile-links">
                            <a href="{{ route('reservations.index') }}" class="mobile-nav-item">
                                <i class="fa fa-calendar-check me-3 text-primary"></i>Mes Réservations
                            </a>
                            <a href="{{ route('profile') }}" class="mobile-nav-item">
                                <i class="fa fa-user me-3 text-primary"></i>Mon Profil
                            </a>
                            @if(Auth::user()->isAdmin() || Auth::user()->isSuperAdmin())
                                <a href="{{ Auth::user()->isSuperAdmin() ? route('superadmin.index') : route('admin.index') }}" class="mobile-nav-item text-primary fw-bold">
                                    <i class="fa {{ Auth::user()->isSuperAdmin() ? 'fa-crown' : 'fa-cog' }} me-3"></i>Dashboard
                                </a>
                            @endif
                            <form method="post" action="{{ route('logout') }}" class="w-100">
                                @csrf
                                <button type="submit" class="mobile-nav-item text-danger border-0 w-100 text-start bg-transparent">
                                    <i class="fa fa-sign-out-alt me-3"></i>Déconnexion
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- 💻 DESKTOP VIEW (Hidden on Mobile) --}}
                    <div class="nav-item dropdown d-none d-lg-block">
                        <button class="btn-custom-solid dropdown-toggle border-0" data-bs-toggle="dropdown">
                            <i class="fa fa-user-circle me-2"></i>{{ explode(' ', Auth::user()->name)[0] }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end mt-2 border-0 shadow modern-dropdown">
                            <li><a href="{{ route('reservations.index') }}" class="dropdown-item py-2"><i class="fa fa-calendar-check me-2 text-primary"></i>Mes Réservations</a></li>
                            <li><a href="{{ route('profile') }}" class="dropdown-item py-2"><i class="fa fa-user me-2 text-primary"></i>Mon Profil</a></li>
                            <li><hr class="dropdown-divider"></li>
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
    #main-header { z-index: 1050; width: 100%; transition: 0.3s; }
    .header-transparent { background: transparent; }
    .header-transparent .nav-link { color: #fff !important; }
    .header-scrolled { background: #fff !important; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .header-scrolled .nav-link { color: #333 !important; }
    .header-scrolled #nav-toggler { color: #333 !important; }

    @media (max-width: 991.98px) {
        #navbarCollapse {
            position: fixed !important; top: 0; left: -100% !important;
            width: 100% !important; height: 100vh !important;
            background: #fff !important; z-index: 9999 !important;
            transition: left 0.4s ease !important; display: flex !important; flex-direction: column; overflow-y: auto !important;
        }
        #navbarCollapse.show { left: 0 !important; }
        .sidebar-close { position: absolute; top: 20px; right: 20px; border: none; background: none; font-size: 1.5rem; color: #333; }

        /* ABSOLUTE FORCE: Hide desktop dropdown elements on mobile */
        .auth-nav .dropdown, .modern-dropdown, .btn-custom-solid, .btn-custom-outline { display: none !important; }
        
        /* Show only the mobile auth block if authenticated */
        .auth-nav .d-lg-none { display: block !important; }

        .mobile-nav-item {
            display: flex !important; align-items: center !important; padding: 18px 30px !important;
            font-size: 1.1rem !important; font-weight: 700 !important; color: #1a1a1a !important;
            border-bottom: 1px solid rgba(0,0,0,0.04) !important; text-decoration: none !important; background: #fff !important;
        }
        .nav-link { padding: 15px 30px !important; color: #1a1a1a !important; border-bottom: 1px solid rgba(0,0,0,0.04) !important; }
        .auth-nav { margin-top: auto !important; width: 100% !important; }
    }

    .btn-custom-solid, .btn-custom-outline { font-weight: 700; padding: 0 25px; height: 42px; border-radius: 8px; }
    .btn-custom-solid { background: var(--primary); color: #000 !important; }
    .btn-custom-outline { border: 1px solid var(--primary); color: var(--primary) !important; }
</style>

{{-- Backdrop --}}
<div id="sidebar-overlay" style="position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:9000;display:none;"></div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const header = document.getElementById('main-header');
        const navCollapse = document.getElementById('navbarCollapse');
        const overlay = document.getElementById('sidebar-overlay');
        const navToggler = document.getElementById('nav-toggler');
        
        function toggleSidebar() {
            // BUG 3 FIX: Close any active desktop dropdowns before opening sidebar
            document.querySelectorAll('.dropdown-menu.show').forEach(m => m.classList.remove('show'));

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

        // BUG 3 FIX: Clicking outside should close both
        document.addEventListener('click', function(e) {
            if (!header.contains(e.target) && !overlay.contains(e.target) && document.body.classList.contains('sidebar-open')) {
                toggleSidebar();
            }
        });
    });
</script>
