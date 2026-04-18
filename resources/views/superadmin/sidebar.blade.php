<div class="col-auto col-md-3 col-xl-2 px-0 bg-white d-flex flex-column" style="min-height:100vh; min-width:64px; z-index: 10; border-right: 1px solid rgba(0,0,0,0.08) !important; box-shadow: 4px 0 15px rgba(0,0,0,0.03);">

    {{-- Brand --}}
    <a href="{{ route('superadmin.index') }}"
       class="d-flex align-items-center justify-content-center text-decoration-none py-4 px-3"
       style="overflow:hidden;">
        <img src="{{ asset('img/logo.png') }}"
             alt="Hotelia"
             class="d-none d-sm-block"
             style="height:80px; width:auto; object-fit:contain;">
        <img src="{{ asset('img/logo.png') }}"
             alt="Hotelia"
             class="d-block d-sm-none"
             style="height:40px; width:auto; object-fit:contain;">
    </a>

    {{-- Nav links --}}
    <ul class="nav nav-pills flex-column flex-grow-1 px-3 gap-2 mt-2" id="superadmin-menu">

        <li class="nav-item">
            <a href="{{ route('superadmin.index') }}"
               class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-3
                      {{ request()->routeIs('superadmin.index') ? 'active text-white' : 'text-dark opacity-75' }}">
                <i class="bi bi-speedometer2 fs-5 flex-shrink-0"></i>
                <span class="d-none d-md-inline fw-medium text-nowrap">Tableau de Bord</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('superadmin.cities.index') }}"
               class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-3
                      {{ request()->routeIs('superadmin.cities.*') ? 'active text-white' : 'text-dark opacity-75' }}">
                <i class="bi bi-geo-alt fs-5 flex-shrink-0"></i>
                <span class="d-none d-md-inline fw-medium text-nowrap">Villes</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('superadmin.hotels.index') }}"
               class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-3
                      {{ request()->routeIs('superadmin.hotels.*') ? 'active text-white' : 'text-dark opacity-75' }}">
                <i class="bi bi-building fs-5 flex-shrink-0"></i>
                <span class="d-none d-md-inline fw-medium text-nowrap">Hôtels</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('superadmin.users.index') }}"
               class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-3
                      {{ request()->routeIs('superadmin.users.*') ? 'active text-white' : 'text-dark opacity-75' }}">
                <i class="bi bi-people fs-5 flex-shrink-0"></i>
                <span class="d-none d-md-inline fw-medium text-nowrap">Utilisateurs</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('superadmin.chatbot-suggestions.index') }}"
               class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-3
                      {{ request()->routeIs('superadmin.chatbot-suggestions.*') ? 'active text-white' : 'text-dark opacity-75' }}">
                <i class="bi bi-robot fs-5 flex-shrink-0"></i>
                <span class="d-none d-md-inline fw-medium text-nowrap">Assistant IA</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('superadmin.reviews.index') }}"
               class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-3
                      {{ request()->routeIs('superadmin.reviews.*') ? 'active text-white' : 'text-dark opacity-75' }}">
                <i class="bi bi-chat-quote fs-5 flex-shrink-0"></i>
                <span class="d-none d-md-inline fw-medium text-nowrap">Avis Clients</span>
                @php $pendingReviews = \App\Models\Review::where('status','pending')->count(); @endphp
                @if($pendingReviews > 0)
                    <span class="badge rounded-pill bg-primary text-white ms-auto d-none d-md-inline shadow-sm"
                          style="font-size:0.7rem; font-weight: 700;">{{ $pendingReviews }}</span>
                @endif
            </a>
        </li>

    </ul>

    {{-- User badge pinned to bottom --}}
    <div class="dropdown px-2 py-3 mt-auto border-top" style="border-color: rgba(0,0,0,0.05) !important;">
        <a href="#"
           class="d-flex align-items-center gap-2 text-dark text-decoration-none dropdown-toggle px-3 py-2 rounded"
           data-bs-toggle="dropdown"
           aria-expanded="false">
            <img src="{{ Auth::user()->avatar_url }}" 
                 alt="Avatar" 
                 class="rounded-circle shadow-sm flex-shrink-0"
                 style="width:32px;height:32px;object-fit:cover;">
            <div class="d-none d-sm-block overflow-hidden">
                <div class="small fw-semibold text-dark text-truncate lh-sm" style="max-width:110px;">
                    {{ Auth::user()->name }}
                </div>
                <div class="text-muted text-truncate lh-sm" style="font-size:.7rem;max-width:110px;">
                    {{ Auth::user()->email }}
                </div>
            </div>
        </a>

        <ul class="dropdown-menu shadow border border-light mb-1"
            style="min-width:200px; bottom:100%; top:auto; border-radius: 0 !important;">
            <li class="px-3 py-2 border-bottom">
                <small class="text-muted d-block text-truncate">
                    <i class="fa fa-envelope me-1"></i>{{ Auth::user()->email }}
                </small>
            </li>
            <li>
                <a class="dropdown-item py-2" href="{{ route('superadmin.profile') }}">
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