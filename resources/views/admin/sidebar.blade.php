<div class="bg-white d-flex flex-column h-100" style="min-width:64px; z-index: 10; border-right: 1px solid rgba(0,0,0,0.08) !important; box-shadow: 4px 0 15px rgba(0,0,0,0.03);">

    {{-- Brand --}}
    <a href="{{ route('admin.index') }}"
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
    <ul class="nav nav-pills flex-column flex-grow-1 px-3 gap-2 mt-2" id="menu">

        <li class="nav-item">
            <a href="{{ route('admin.index') }}"
               class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-3
                      {{ request()->routeIs('admin.index') ? 'active text-white' : 'text-dark opacity-75' }}">
                <i class="bi bi-speedometer2 fs-5 flex-shrink-0"></i>
                <span class="d-none d-md-inline fw-medium text-nowrap">Tableau de Bord</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.reservations.index') }}"
               class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-3
                      {{ request()->routeIs('admin.reservations.*') ? 'active text-white' : 'text-dark opacity-75' }}">
                <i class="bi bi-calendar-check fs-5 flex-shrink-0"></i>
                <span class="d-none d-md-inline fw-medium text-nowrap">Réservations</span>
                @php 
                    $pendingReservations = \App\Models\Reservation::where('status', 'pending')
                        ->whereHas('room', function($q) { 
                            $q->where('hotel_id', auth()->user()->hotel_id); 
                        })->count();
                @endphp
                @if($pendingReservations > 0)
                    <span class="badge rounded-pill ms-auto d-none d-md-inline shadow-sm 
                                {{ request()->routeIs('admin.reservations.*') ? 'bg-white text-primary' : 'bg-primary text-white' }}"
                          style="font-size:0.7rem; font-weight: 800; min-width: 20px;">
                        {{ $pendingReservations }}
                    </span>
                @endif
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.roomtypes.index') }}"
               class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-3
                      {{ request()->routeIs('admin.roomtypes.*') ? 'active text-white' : 'text-dark opacity-75' }}">
                <i class="bi bi-tags fs-5 flex-shrink-0"></i>
                <span class="d-none d-md-inline fw-medium text-nowrap">Types de Chambres</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('admin.rooms.index') }}"
               class="nav-link d-flex align-items-center gap-3 px-3 py-2 rounded-3
                      {{ request()->routeIs('admin.rooms.*') ? 'active text-white' : 'text-dark opacity-75' }}">
                <i class="bi bi-door-open fs-5 flex-shrink-0"></i>
                <span class="d-none d-md-inline fw-medium text-nowrap">Chambres</span>
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

