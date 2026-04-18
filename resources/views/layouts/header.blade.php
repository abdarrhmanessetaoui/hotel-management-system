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
            
            {{-- Centered Menu --}}
            <div class="navbar-nav ms-0 py-3 py-lg-0 align-items-lg-center text-start" id="main-nav">
                <a class="nav-item nav-link fw-medium scroll-target" data-section="top" href="{{ route('home') }}">Accueil</a>
                <a class="nav-item nav-link fw-medium scroll-target" data-section="villes" href="{{ route('home') }}#villes">Destinations</a>
                <a class="nav-item nav-link fw-medium scroll-target" data-section="services" href="{{ route('home') }}#services">Services</a>
                <a class="nav-item nav-link fw-medium scroll-target" data-section="testimonial" href="{{ route('home') }}#testimonial">Avis</a>
                <a class="nav-item nav-link fw-medium scroll-target" data-section="newsletter" href="{{ route('home') }}#newsletter">Contact</a>
            </div>

            {{-- Right Actions --}}
            <div class="navbar-nav auth-nav align-items-lg-center gap-2 pb-3 pb-lg-0">
                @guest
                    <a href="{{ route('login') }}" class="btn-custom-outline transition-all">
                        Connexion
                    </a>
                    <a href="{{ route('register') }}" class="btn-custom-solid transition-all">
                        S'inscrire
                    </a>
                @else
                    <div class="nav-item dropdown">
                        <button class="btn-custom-solid dropdown-toggle w-100" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user-circle me-2"></i>{{ explode(' ', Auth::user()->name)[0] }}
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end mt-2 border-0 shadow modern-dropdown">
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
                        <a href="{{ route('admin.index') }}" class="btn-custom-outline ms-lg-2">
                            <i class="fa fa-cog me-1"></i> Admin
                        </a>
                    @elseif(Auth::user()->isSuperAdmin())
                        <a href="{{ route('superadmin.index') }}" class="btn-custom-outline ms-lg-2">
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
    html, body {
        margin: 0 !important;
        padding: 0 !important;
    }

    #main-header {
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        margin-top: 0 !important;
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
        border: none;
    }

    .header-transparent #main-nav .nav-link {
        color: rgba(255, 255, 255, 0.9);
    }
    .header-transparent #main-nav .nav-link:hover,
    .header-transparent #main-nav .nav-link.scroll-active {
        color: var(--primary);
    }

    .border-light-subtle {
        border-color: rgba(255, 255, 255, 0.1) !important;
    }

    #main-nav .nav-link {
        padding: 0 15px !important;
        display: flex;
        align-items: center;
        height: 55px; /* Match logo height for absolute centering */
    }

    /* Modern indicator line for active state */
    @media (min-width: 992px) {
        #main-nav .nav-link::after {
            content: '';
            position: absolute;
            bottom: 10px; /* Position inside the 60px height */
            left: 50%;
            transform: translateX(-50%);
            width: 0;
            height: 2px;
            background-color: var(--primary);
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
        background: #ffffff !important;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08) !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
    }



    .header-scrolled #main-nav .nav-link {
        color: #333333 !important;
    }
    
    .header-scrolled #main-nav .nav-link:hover,
    .header-scrolled #main-nav .nav-link.scroll-active {
        color: var(--primary) !important;
    }

    .header-scrolled #nav-toggler {
        color: #333333 !important;
    }

    /* ════════════════════════════════════════════════
    SaaS SQUARE BUTTONS
    ════════════════════════════════════════════════ */
    .btn-custom-solid,
    .btn-custom-outline {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 0.95rem;
        padding: 0 20px;
        height: 42px;
        line-height: normal;
        border-radius: 0; /* Sharp corners like screenshot */
        transition: all 0.2s ease;
        text-decoration: none;
        cursor: pointer;
        box-sizing: border-box;
    }

    /* Solid Orange Button */
    .btn-custom-solid {
        background: var(--primary);
        color: #0f172b !important;
        border: 1px solid var(--primary);
    }
    .btn-custom-solid:hover {
        background: var(--primary-dark); /* darker hover */
        border-color: var(--primary-dark);
        color: #ffffff !important;
    }

    /* Outline Orange Button */
    .btn-custom-outline {
        background: transparent;
        border: 1px solid var(--primary);
        color: var(--primary) !important;
    }
    .btn-custom-outline:hover {
        background: rgba(255, 126, 33, 0.1);
    }

    /* Solid state adaptations */
    .header-scrolled .btn-custom-solid {
        box-shadow: 0 4px 10px rgba(255, 126, 33, 0.2);
    }
    
    .header-scrolled .btn-custom-outline {
        /* On white background, keep orange but maybe slightly darker */
    }

    /* Toggle specific */
    button.btn-custom-solid.dropdown-toggle::after,
    button.btn-custom-outline.dropdown-toggle::after {
        margin-left: 6px;
        vertical-align: middle;
    }

    /* ════════════════════════════════════════════════
    DROPDOWN & MOBILE STYLING
    ════════════════════════════════════════════════ */
    .modern-dropdown {
        background-color: #ffffff;
        border-radius: 0 !important;
        padding: 8px 0;
        box-shadow: 0 10px 40px rgba(0,0,0,0.12) !important;
        border: 1px solid rgba(0,0,0,0.05) !important;
        display: block !important;
        visibility: hidden;
    }

    @media (max-width: 991.98px) {
        #navbarCollapse {
            position: fixed !important;
            top: 0 !important;
            left: -100% !important; 
            width: 85% !important;
            height: 100vh !important;
            background: #ffffff !important; /* PURE SOLID WHITE */
            opacity: 1 !important;
            visibility: visible !important;
            z-index: 3000 !important;
            margin: 0 !important;
            padding: 80px 0 30px 20px !important; 
            transition: left 0.4s cubic-bezier(0.77,0,0.175,1) !important;
            display: flex !important;
            flex-direction: column;
            box-shadow: 10px 0 40px rgba(0,0,0,0.1) !important;
            border: none !important;
            border-radius: 0 !important;
        }

        #navbarCollapse.show {
            left: 0 !important;
        }

        body.sidebar-open #main-header {
            z-index: 3001 !important;
        }

        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(15, 23, 43, 0.4);
            backdrop-filter: blur(2px);
            z-index: 2500;
            display: none;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        body.sidebar-open .sidebar-overlay {
            display: block;
            opacity: 1;
        }
        
        body.sidebar-open {
            overflow: hidden !important;
        }

        .sidebar-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: none;
            border: none;
            font-size: 1.8rem;
            color: #333;
            cursor: pointer;
        }

        #main-nav, .auth-nav, .navbar-nav, .nav-item {
            padding-left: 0 !important;
            margin-left: 0 !important;
            width: 100% !important;
            display: block !important;
        }

        .nav-link, .dropdown-item {
            font-size: 1.15rem !important;
            font-weight: 700 !important;
            color: #1a1a1a !important;
            padding: 15px 0 !important; 
            margin: 0 !important;
            border-bottom: 1px solid rgba(0,0,0,0.04) !important;
            width: 100% !important;
            text-align: left !important;
            background: transparent !important;
            box-shadow: none !important;
            border-radius: 0 !important;
        }
        
        .nav-link i, .dropdown-item i {
            display: none !important; 
        }

        .auth-nav {
            margin-top: auto !important; 
            padding-top: 10px !important;
            border-top: 20px solid #f8f9fa !important;
        }

        .auth-nav .dropdown-menu {
            display: block !important;
            visibility: visible !important;
            opacity: 1 !important;
            transform: none !important;
            position: static !important;
            background: transparent !important; 
            box-shadow: none !important; 
            margin: 0 !important;
            padding: 0 !important;
            border: none !important;
            width: 100%;
        }

        .dropdown-item.text-danger {
            color: #dc3545 !important;
        }
        
        .btn-custom-solid, .btn-custom-outline {
            height: 50px !important;
            width: 100% !important;
            margin-bottom: 10px;
        }
    }

</style>

{{-- Backdrop for Sidebar --}}
<div class="sidebar-overlay" id="sidebar-overlay"></div>

{{-- Vanilla JS for Header Interactions --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const header = document.getElementById('main-header');
        const navCollapse = document.getElementById('navbarCollapse');
        const overlay = document.getElementById('sidebar-overlay');
        const navToggler = document.getElementById('nav-toggler');
        
        // 1. Sidebar Logic
        function toggleSidebar() {
            document.body.classList.toggle('sidebar-open');
            if (document.body.classList.contains('sidebar-open')) {
                navCollapse.classList.add('show');
            } else {
                navCollapse.classList.remove('show');
                // Small delay to ensure smooth transition before backdrop hides
            }
        }

        navToggler.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            toggleSidebar();
        });

        overlay.addEventListener('click', toggleSidebar);

        // Close on link click
        document.querySelectorAll('.scroll-target').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 992) toggleSidebar();
            });
        });

        // Add Close Button to Navbar Collapse if missing
        if (window.innerWidth < 992 && !document.querySelector('.sidebar-close')) {
            const closeBtn = document.createElement('button');
            closeBtn.className = 'sidebar-close';
            closeBtn.innerHTML = '<i class="fa fa-times"></i>';
            closeBtn.onclick = toggleSidebar;
            navCollapse.prepend(closeBtn);
        }

        // 2. Scroll Transparency Toggle
        function toggleHeaderBackground() {
            const hasHero = !!document.getElementById('header-carousel');
            if (window.scrollY > 50 || !hasHero) {
                header.classList.add('header-scrolled');
                header.classList.remove('header-transparent');
            } else {
                header.classList.remove('header-scrolled');
                header.classList.add('header-transparent');
            }
        }

        toggleHeaderBackground();
        window.addEventListener('scroll', toggleHeaderBackground, { passive: true });
    });
</script>