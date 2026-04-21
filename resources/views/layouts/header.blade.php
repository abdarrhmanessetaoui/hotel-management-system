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

        {{-- ═══════════════════════════════════════════
             DESKTOP NAV (unchanged, hidden on mobile)
             ═══════════════════════════════════════════ --}}
        <div class="collapse navbar-collapse d-none d-lg-flex" id="navbarDesktop">
            <div class="navbar-nav mx-lg-auto py-3 py-lg-0 align-items-lg-center" id="main-nav">
                <a class="nav-item nav-link fw-bold scroll-target" href="{{ route('home') }}">Accueil</a>
                <a class="nav-item nav-link fw-bold scroll-target" href="{{ route('home') }}#villes">Destinations</a>
                <a class="nav-item nav-link fw-bold scroll-target" href="{{ route('home') }}#services">Services</a>
                <a class="nav-item nav-link fw-bold scroll-target" href="{{ route('home') }}#testimonial">Avis</a>
                <a class="nav-item nav-link fw-bold scroll-target" href="{{ route('home') }}#newsletter">Contact</a>
            </div>

            <div class="navbar-nav align-items-lg-center gap-2">
                @guest
                    <a href="{{ route('login') }}" class="btn-custom-outline">Connexion</a>
                    <a href="{{ route('register') }}" class="btn-custom-solid">S'inscrire</a>
                @else
                    <div class="nav-item dropdown">
                        <button class="btn-custom-solid dropdown-toggle border-0" data-bs-toggle="dropdown">
                            <i class="fa fa-user-circle me-2"></i>{{ explode(' ', Auth::user()->name)[0] }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end mt-2 border-0 shadow modern-dropdown">
                            <li><a href="{{ route('reservations.index') }}" class="dropdown-item py-2"><i class="fa fa-calendar-check me-2 text-primary"></i>Mes Réservations</a></li>
                            <li><a href="{{ route('profile') }}" class="dropdown-item py-2"><i class="fa fa-user me-2 text-primary"></i>Mon Profil</a></li>
                            @if(Auth::user()->isAdmin())
                                <li><a href="{{ route('admin.index') }}" class="dropdown-item py-2"><i class="fa fa-cog me-2 text-primary"></i>Dashboard</a></li>
                            @elseif(Auth::user()->isSuperAdmin())
                                <li><a href="{{ route('superadmin.index') }}" class="dropdown-item py-2"><i class="fa fa-crown me-2 text-primary"></i>Dashboard</a></li>
                            @endif
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

        {{-- ═══════════════════════════════════════════
             MOBILE MENU — Ultra-minimal drawer
             Plain text. Left-aligned. No icons.
             ═══════════════════════════════════════════ --}}
        <div class="mobile-menu d-lg-none" id="mobileMenu">

            {{-- Header: logo + close --}}
            <div class="menu-header">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('img/logo.png') }}" alt="Hotelia" class="menu-logo">
                </a>
                <button class="menu-close" id="menuClose">✕</button>
            </div>

            <hr class="menu-divider">

            {{-- Main navigation --}}
            <nav class="menu-nav">
                <a href="{{ route('home') }}">Accueil</a>
                <a href="{{ route('home') }}#villes">Destinations</a>
                <a href="{{ route('home') }}#services">Services</a>
                <a href="{{ route('home') }}#testimonial">Avis</a>
                <a href="{{ route('home') }}#newsletter">Contact</a>
            </nav>

            @auth
                <hr class="menu-divider">

                {{-- User greeting --}}
                <div class="menu-user-name">Bonjour, {{ Auth::user()->name }}</div>

                <hr class="menu-divider">

                {{-- User links --}}
                <nav class="menu-user-links">
                    <a href="{{ route('reservations.index') }}">Mes Réservations</a>
                    <a href="{{ route('profile') }}">Mon Profil</a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.index') }}">Dashboard</a>
                    @elseif(Auth::user()->isSuperAdmin())
                        <a href="{{ route('superadmin.index') }}">Dashboard</a>
                    @endif
                    <form method="post" action="{{ route('logout') }}" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="menu-logout-btn">Déconnexion</button>
                    </form>
                </nav>
            @else
                <hr class="menu-divider">
                <nav class="menu-user-links">
                    <a href="{{ route('login') }}">Connexion</a>
                    <a href="{{ route('register') }}">S'inscrire</a>
                </nav>
            @endauth

        </div>
    </nav>
</header>

<style>
    /* ════════════════════════════════════════════════
       DESKTOP HEADER (untouched)
       ════════════════════════════════════════════════ */
    #main-header { z-index: 1050; width: 100%; position: fixed !important; }
    .transition-all { transition: all 0.3s ease; }

    #main-nav .nav-link {
        padding: 0 15px !important;
        display: flex; align-items: center; height: 55px;
    }

    .header-transparent { background: transparent; }
    .header-transparent .nav-link { color: #fff !important; }
    .header-scrolled { background: #fff !important; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
    .header-scrolled .nav-link { color: #333 !important; }
    .header-scrolled #nav-toggler { color: #333 !important; }

    .btn-custom-solid, .btn-custom-outline {
        display: inline-flex; align-items: center; justify-content: center;
        font-weight: 700; padding: 0 25px; height: 42px; border-radius: 8px;
        text-decoration: none; cursor: pointer; transition: 0.2s;
    }
    .btn-custom-solid { background: var(--primary); color: #000 !important; border: 1px solid var(--primary); }
    .btn-custom-solid:hover { background: var(--primary-dark); color: #fff !important; }
    .btn-custom-outline { border: 1px solid var(--primary); color: var(--primary) !important; background: transparent; }
    .btn-custom-outline:hover { background: rgba(255,126,33,0.1); }

    button.btn-custom-solid.dropdown-toggle::after { margin-left: 6px; vertical-align: middle; }

    .modern-dropdown {
        background: #fff; border-radius: 8px !important; padding: 8px 0;
        box-shadow: 0 10px 40px rgba(0,0,0,0.12) !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }

    /* ════════════════════════════════════════════════
       MOBILE MENU — Ultra-minimal, plain text
       Only active below 992px
       ════════════════════════════════════════════════ */
    .mobile-menu {
        position: fixed;
        top: 0; left: 0;
        width: 100vw;
        height: 100vh;
        background: #ffffff;
        z-index: 9999;
        overflow-y: auto;
        padding: 0 24px 40px 24px;
        transform: translateX(-100%);
        transition: transform 0.35s ease;
    }

    .mobile-menu.open {
        transform: translateX(0);
    }

    .menu-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px 0;
    }

    .menu-logo { height: 36px; width: auto; }

    .menu-close {
        background: none; border: none;
        font-size: 22px; color: #333;
        cursor: pointer; padding: 4px 10px;
        line-height: 1;
    }

    .menu-divider {
        border: none;
        border-top: 1px solid #eee;
        margin: 0;
    }

    .menu-nav a,
    .menu-user-links a {
        display: block;
        padding: 16px 0;
        font-size: 16px;
        color: #222222 !important;
        text-decoration: none !important;
        border-bottom: 1px solid #f5f5f5;
        text-align: left;
        background: none !important;
        font-weight: 400;
    }

    .menu-nav a:last-child,
    .menu-user-links a:last-child {
        border-bottom: none;
    }

    .menu-nav a:hover,
    .menu-user-links a:hover {
        color: var(--primary) !important;
    }

    .menu-user-name {
        padding: 16px 0;
        font-size: 14px;
        color: #888;
        text-align: left;
    }

    .menu-logout-btn {
        display: block; width: 100%;
        padding: 16px 0; font-size: 16px;
        color: #e74c3c; text-align: left;
        background: none; border: none;
        cursor: pointer; font-weight: 400;
    }

    .menu-logout-btn:hover { color: #c0392b; }

    /* Backdrop behind menu */
    .menu-backdrop {
        position: fixed; top: 0; left: 0;
        width: 100%; height: 100%;
        background: rgba(0,0,0,0.4);
        z-index: 9998;
        display: none;
    }
    .menu-backdrop.active { display: block; }

    /* Hide mobile menu elements on desktop */
    @media (min-width: 992px) {
        .mobile-menu, .menu-backdrop { display: none !important; }
    }

    /* Hide hamburger on desktop */
    @media (min-width: 992px) {
        #nav-toggler { display: none !important; }
    }
</style>

{{-- Backdrop --}}
<div class="menu-backdrop" id="menuBackdrop"></div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const header   = document.getElementById('main-header');
    const menu     = document.getElementById('mobileMenu');
    const backdrop = document.getElementById('menuBackdrop');
    const toggler  = document.getElementById('nav-toggler');
    const closeBtn = document.getElementById('menuClose');

    // Open
    toggler.addEventListener('click', function (e) {
        e.preventDefault();
        menu.classList.add('open');
        backdrop.classList.add('active');
        document.body.style.overflow = 'hidden';
    });

    // Close
    function closeMenu() {
        menu.classList.remove('open');
        backdrop.classList.remove('active');
        document.body.style.overflow = '';
    }

    closeBtn.addEventListener('click', closeMenu);
    backdrop.addEventListener('click', closeMenu);

    // Close on any link click inside menu
    menu.querySelectorAll('a').forEach(function (link) {
        link.addEventListener('click', closeMenu);
    });

    // Scroll: transparent → solid header
    function updateHeader() {
        var hasHero = !!document.getElementById('header-carousel');
        if (window.scrollY > 50 || !hasHero) {
            header.classList.add('header-scrolled');
            header.classList.remove('header-transparent');
        } else {
            header.classList.remove('header-scrolled');
            header.classList.add('header-transparent');
        }
    }
    updateHeader();
    window.addEventListener('scroll', updateHeader, { passive: true });
});
</script>
