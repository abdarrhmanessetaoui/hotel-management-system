<nav class="navbar navbar-expand-lg bg-dark navbar-dark">
    <div class="container">

        {{-- Brand --}}
        <a class="navbar-brand fw-bold text-primary text-uppercase" href="{{ route('home') }}">
            Hotelia
        </a>

        {{-- Toggler --}}
        <button class="navbar-toggler border-0" type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarMain"
                aria-controls="navbarMain"
                aria-expanded="false"
                aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMain">

            {{-- Left links --}}
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"
                       href="{{ route('home') }}">Accueil</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}#villes">Destinations</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}#services">Services</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('home') }}#testimonial">Avis</a>
                </li>
            </ul>

            {{-- Right: auth --}}
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center gap-lg-2">

                @guest
                    <li class="nav-item">
                        <a class="btn btn-outline-primary btn-sm px-3
                                  {{ request()->routeIs('login') ? 'active' : '' }}"
                           href="{{ route('login') }}">
                            <i class="fa fa-sign-in-alt me-1"></i>Connexion
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="btn btn-primary btn-sm px-3
                                  {{ request()->routeIs('register') ? 'active' : '' }}"
                           href="{{ route('register') }}">
                            <i class="fa fa-user-plus me-1"></i>S'inscrire
                        </a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2"
                           href="#" role="button"
                           data-bs-toggle="dropdown"
                           aria-expanded="false">
                            {{-- Avatar circle --}}
                            <span class="rounded-circle bg-primary d-inline-flex align-items-center
                                         justify-content-center text-dark fw-bold"
                                  style="width:28px;height:28px;font-size:.75rem;flex-shrink:0;">
                                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                            </span>
                            {{ explode(' ', Auth::user()->name)[0] }}
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow mt-1"
                            style="min-width:200px;">

                            {{-- Email header --}}
                            <li class="px-3 py-2 border-bottom">
                                <small class="text-muted d-block">
                                    <i class="fa fa-envelope me-1"></i>{{ Auth::user()->email }}
                                </small>
                            </li>

                            <li>
                                <a class="dropdown-item py-2" href="{{ route('reservations.index') }}">
                                    <i class="fa fa-calendar-check me-2 text-primary"></i>Mes Réservations
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item py-2" href="{{ route('profile') }}">
                                    <i class="fa fa-user me-2 text-primary"></i>Mon Profil
                                </a>
                            </li>

                            @if(Auth::user()->isAdmin())
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('admin.index') }}">
                                        <i class="fa fa-cog me-2 text-primary"></i>Panel Admin
                                    </a>
                                </li>
                            @elseif(Auth::user()->isSuperAdmin())
                                <li><hr class="dropdown-divider my-1"></li>
                                <li>
                                    <a class="dropdown-item py-2" href="{{ route('superadmin.index') }}">
                                        <i class="fa fa-crown me-2 text-warning"></i>Super Admin
                                    </a>
                                </li>
                            @endif

                            <li><hr class="dropdown-divider my-1"></li>
                            <li>
                                <form method="post" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item py-2 text-danger w-100 text-start">
                                        <i class="fa fa-sign-out-alt me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endguest

            </ul>
        </div>
    </div>
</nav>
