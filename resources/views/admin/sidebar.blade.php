<div class="col-auto col-md-3 col-xl-2 px-0 bg-dark d-flex flex-column" style="min-height:100vh; min-width:64px;">

    {{-- Brand --}}
    <a href="{{ route('admin.index') }}"
       class="d-flex align-items-center justify-content-center text-decoration-none py-4"
       style="overflow:hidden; min-height:100px;">
        <img src="{{ asset('img/logo.png') }}"
             alt="Hotelia"
             class="d-none d-sm-block"
             style="height:70px; width:auto; object-fit:contain;
                    transform:scale(2); transform-origin:center center;">
        <img src="{{ asset('img/logo.png') }}"
             alt="Hotelia"
             class="d-block d-sm-none"
             style="height:36px; width:auto; object-fit:contain;">
    </a>

    {{-- Nav links --}}
    <ul class="nav nav-pills flex-column flex-grow-1 px-2 gap-1 mt-2" id="menu">

        <li class="nav-item">
            <a href="{{ route('admin.index') }}"
               class="nav-link d-flex align-items-center gap-2 px-3 py-2
                      {{ request()->routeIs('admin.index') ? 'active' : 'text-white-50' }}">
                <i class="bi bi-speedometer2 fs-5 flex-shrink-0"></i>
                <span class="d-none d-md-inline">Tableau de Bord</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.reservations.index') }}"
               class="nav-link d-flex align-items-center gap-2 px-3 py-2
                      {{ request()->routeIs('admin.reservations.*') ? 'active' : 'text-white-50' }}">
                <i class="bi bi-calendar-check fs-5 flex-shrink-0"></i>
                <span class="d-none d-md-inline">Réservations</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.rooms.index') }}"
               class="nav-link d-flex align-items-center gap-2 px-3 py-2
                      {{ request()->routeIs('admin.rooms.*') ? 'active' : 'text-white-50' }}">
                <i class="bi bi-door-open fs-5 flex-shrink-0"></i>
                <span class="d-none d-md-inline">Chambres</span>
            </a>
        </li>

    </ul>

    {{-- User badge pinned to bottom --}}
    <div class="dropdown px-2 py-3 mt-auto">
        <a href="#"
           class="d-flex align-items-center gap-2 text-white text-decoration-none dropdown-toggle px-3 py-2 rounded"
           data-bs-toggle="dropdown"
           aria-expanded="false">
            @if(Auth::user()->profile_image)
                <img src="{{ asset('storage/' . Auth::user()->profile_image) }}" 
                     alt="Avatar" 
                     class="rounded-circle shadow-sm flex-shrink-0"
                     style="width:32px;height:32px;object-fit:cover;">
            @else
                <span class="rounded-circle bg-primary d-inline-flex align-items-center justify-content-center
                             fw-bold text-dark flex-shrink-0"
                      style="width:32px;height:32px;font-size:.8rem;">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </span>
            @endif
            <div class="d-none d-sm-block overflow-hidden">
                <div class="small fw-semibold text-white text-truncate lh-sm" style="max-width:110px;">
                    {{ Auth::user()->name }}
                </div>
                <div class="text-white-50 text-truncate lh-sm" style="font-size:.7rem;max-width:110px;">
                    {{ Auth::user()->email }}
                </div>
            </div>
        </a>

        <ul class="dropdown-menu dropdown-menu-dark shadow border-0 mb-1"
            style="min-width:200px; bottom:100%; top:auto;">
            <li class="px-3 py-2 border-bottom border-secondary">
                <small class="text-white-50 d-block text-truncate">
                    <i class="fa fa-envelope me-1"></i>{{ Auth::user()->email }}
                </small>
            </li>
            <li>
                <a class="dropdown-item py-2" href="{{ route('admin.profile') }}">
                    <i class="fa fa-user me-2 text-primary"></i>Mon Profil
                </a>
            </li>
            <li>
                <a class="dropdown-item py-2" href="{{ route('home') }}">
                    <i class="fa fa-home me-2 text-primary"></i>Accueil
                </a>
            </li>
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
    </div>

</div>