@extends('layouts.app')

@section('header')
    @include('layouts.header')

    {{-- =========================================================
         Page Header — identical structure to list-rooms page header
         Dynamic hotel name replaces static "Rooms"
         ========================================================= --}}
    <div class="container-fluid page-header mb-5 p-0"
         style="background-image: url({{ asset('img/carousel-1.jpg') }});">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center pb-5">

                <h1 class="display-3 text-white mb-3 animated slideInDown">
                    {{ $hotel->name }}
                </h1>

                {{-- Breadcrumb: Home → {City} → Hotel Name --}}
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('cities.hotels', $hotel->city) }}">
                                {{ $hotel->city->name }}
                            </a>
                        </li>
                        <li class="breadcrumb-item text-white active" aria-current="page">
                            {{ $hotel->name }}
                        </li>
                    </ol>
                </nav>

            </div>
        </div>
    </div>
    {{-- Page Header End --}}
@endsection

@section('content')

    <!-- Hotel Detail Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5">

                {{-- ═══════════════════════════════════════════════════════════
                     LEFT COLUMN — Hotel info, gallery, description, amenities
                     col-lg-8 mirrors the room card proportions
                     ═══════════════════════════════════════════════════════════ --}}
                <div class="col-lg-8">

                    {{-- ── Main hotel card ─────────────────────────────────────
                         Uses EXACT same wrapper as room cards:
                         room-item shadow rounded overflow-hidden
                    --}}
                    <div class="room-item shadow rounded overflow-hidden wow fadeInUp"
                         data-wow-delay="0.1s">

                        {{-- Hotel main image — exact same wrapper class --}}
                        <div class="position-relative image-container">
                            <img class="img-fluid w-100"
                                 src="{{ $hotel->image
                                         ? (Str::startsWith($hotel->image, 'http') ? $hotel->image : asset($hotel->image))
                                         : asset('img/hotels/default.jpg') }}"
                                 alt="{{ $hotel->name }}">

                            {{-- Rating badge — exact same position/style as price badge --}}
                            <small class="position-absolute start-0 top-0
                                          bg-primary text-white rounded
                                          py-1 px-3 ms-4">
                                <i class="fa fa-star me-1"></i>
                                {{ number_format($hotel->rating, 1) }} / 5.0
                            </small>
                        </div>

                        {{-- Card body — exact same padding --}}
                        <div class="p-4 mt-2">

                            {{-- Hotel name + star row — exact same structure --}}
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="mb-0">{{ $hotel->name }}</h5>
                                {{-- Star icons — copied exactly from room cards --}}
                                <div class="ps-2">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <small class="fa fa-star
                                            {{ $i <= round($hotel->rating)
                                               ? 'text-primary'
                                               : 'text-muted' }}">
                                        </small>
                                    @endfor
                                </div>
                            </div>

                            {{-- Amenities row — exact same border-end / icon pattern --}}
                            <div class="d-flex mb-3">
                                <small class="border-end me-3 pe-3">
                                    <i class="fa fa-map-marker-alt text-primary me-2"></i>
                                    {{ $hotel->location ?? $hotel->city->name }}
                                </small>
                                <small class="border-end me-3 pe-3">
                                    <i class="fa fa-bed text-primary me-2"></i>
                                    {{ $rooms->count() }} {{ $rooms->count() > 1 ? 'Chambres' : 'Chambre' }}
                                </small>
                                <small class="border-end me-3 pe-3">
                                    <i class="fa fa-wifi text-primary me-2"></i>
                                    Wifi Gratuit
                                </small>
                                <small>
                                    <i class="fa fa-parking text-primary me-2"></i>
                                    Parking
                                </small>
                            </div>

                            {{-- Description — exact same .text-body.mb-3 as room desc --}}
                            @if ($hotel->description)
                                <p class="text-body mb-3">
                                    {{ $hotel->description }}
                                </p>
                            @endif

                            {{-- UX Fix: Primary CTA to scroll to available rooms --}}
                            <div class="mt-4">
                                <a href="#available-rooms" class="btn btn-primary rounded py-3 px-5 animated slideInLeft">
                                    Réserver Maintenant
                                </a>
                            </div>

                        </div>
                        {{-- end card body --}}
                    </div>
                    {{-- end main hotel card --}}

                    {{-- ── Hotel Features / Extended Amenities ─────────────────
                         Reuses the same section heading structure
                    --}}
                    <div class="mt-5 wow fadeInUp" data-wow-delay="0.2s">
                        <h6 class="section-title text-primary text-uppercase">
                            Ce qui est Inclus
                        </h6>
                        <h4 class="mb-4">Équipements de l'Hôtel</h4>

                        {{-- Amenity icon grid — same icon style as room amenity rows --}}
                        <div class="row g-3">
                            @foreach ([
                                ['fa-wifi',         'WiFi Gratuit'],
                                ['fa-swimming-pool','Piscine'],
                                ['fa-utensils',     'Restaurant'],
                                ['fa-dumbbell',     'Salle de Sport'],
                                ['fa-concierge-bell','Service de Chambre'],
                                ['fa-parking',      'Parking Gratuit'],
                                ['fa-snowflake',    'Climatisation'],
                                ['fa-shield-alt',   'Sécurité 24h/24'],
                            ] as [$icon, $label])
                                <div class="col-6 col-md-3">
                                    {{-- Same shadow/rounded treatment as room cards --}}
                                    <div class="room-item shadow rounded text-center p-3">
                                        <i class="fa {{ $icon }} fa-2x text-primary mb-2"></i>
                                        <p class="mb-0 small">{{ $label }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    {{-- end amenities --}}

                </div>
                {{-- end left col --}}

                {{-- ═══════════════════════════════════════════════════════════
                     RIGHT COLUMN — Available rooms + Reserve buttons
                     Sticky sidebar — each room is a mini room-item card
                     ═══════════════════════════════════════════════════════════ --}}
                <div class="col-lg-4" id="available-rooms">
                    <div class="sticky-top" style="top: 100px;">

                        {{-- Sidebar heading --}}
                        <div class="wow fadeInUp" data-wow-delay="0.1s">
                            <h6 class="section-title text-primary text-uppercase">
                                Réservez Votre Séjour
                            </h6>
                            <h4 class="mb-4">Chambres Disponibles</h4>
                        </div>

                        @forelse ($rooms as $room)

                            {{-- ── Room mini-card ─────────────────────────────────
                                 EXACT same classes as room-container-details cards
                                 room-item shadow rounded overflow-hidden
                            --}}
                            <div class="room-item shadow rounded overflow-hidden mb-4
                                        wow fadeInUp"
                                 data-wow-delay="{{ $loop->iteration / 10 }}s">

                                {{-- Room image — exact same image-container wrapper --}}
                                <div class="position-relative image-container">
                                    <img class="img-fluid w-100"
                                         src="{{ $room->image
                                                 ? (Str::startsWith($room->image, 'http') ? $room->image : asset($room->image))
                                                 : asset('img/rooms/default.jpg') }}"
                                         alt="{{ ['single' => 'Simple', 'double' => 'Double', 'suite' => 'Suite', 'deluxe' => 'Luxe'][$room->type] ?? $room->type }}">

                                    {{-- Price badge — EXACT same class as original -- }}
                                    <small class="position-absolute start-0 top-0
                                                  bg-primary text-white rounded
                                                  py-1 px-3 ms-4">
                                        ${{ number_format($room->price, 0) }}/Night
                                    </small>
                                </div>

                                {{-- Card body — EXACT same p-4 mt-2 --}}
                                <div class="p-4 mt-2">

                                    {{-- Room type + stars — exact same header row -- }}
                                    <div class="d-flex justify-content-between mb-3">
                                        <h5 class="mb-0">{{ ['single' => 'Simple', 'double' => 'Double', 'suite' => 'Suite', 'deluxe' => 'Luxe'][$room->type] ?? ucfirst($room->type) }}</h5>
                                        <div class="ps-2">
                                            <small class="fa fa-star text-primary"></small>
                                            <small class="fa fa-star text-primary"></small>
                                            <small class="fa fa-star text-primary"></small>
                                            <small class="fa fa-star text-primary"></small>
                                            <small class="fa fa-star text-primary"></small>
                                        </div>
                                    </div>

                                    {{-- Room amenities — EXACT same border-end pattern --}}
                                    <div class="d-flex mb-3">
                                        <small class="border-end me-3 pe-3">
                                            <i class="fa fa-bed text-primary me-2"></i>
                                            {{ $room->no_beds }} Lit(s)
                                        </small>
                                        <small class="border-end me-3 pe-3">
                                            <i class="fa fa-bath text-primary me-2"></i>
                                            Bain
                                        </small>
                                        <small>
                                            <i class="fa fa-wifi text-primary me-2"></i>
                                            Wifi
                                        </small>
                                    </div>

                                    {{-- Short room description --}}
                                    @if ($room->description)
                                        <p class="text-body mb-3 small">
                                            {{ Str::limit($room->description, 80) }}
                                        </p>
                                    @endif

                                    {{-- ── Reserve button ────────────────────────────
                                         Exact same btn classes as original Reserve btn.

                                         If NOT logged in  → goes to login
                                         If logged in      → goes to reservation form
                                         with hotel + room pre-selected
                                    --}}
                                    <div class="d-flex">
                                        @auth
                                            {{-- Logged in: link to reservation form
                                                 with room pre-selected via query string --}}
                                            <a href="{{ route('reservations.create', $hotel) }}?room={{ $room->id }}"
                                               class="btn btn-sm btn-success rounded py-2 px-4">
                                                Réserver
                                            </a>
                                        @else
                                            {{-- Guest: redirect to login,
                                                 then return here after login --}}
                                            <a href="{{ route('login') }}?redirect={{ urlencode(route('hotels.show', $hotel)) }}"
                                               class="btn btn-sm btn-success rounded py-2 px-4">
                                                Réserver
                                            </a>
                                        @endauth
                                    </div>

                                </div>
                                {{-- end card body --}}

                            </div>
                            {{-- end room mini-card --}}

                        @empty

                            {{-- Empty state — same centered structure --}}
                            <div class="room-item shadow rounded p-4 text-center wow fadeInUp"
                                 data-wow-delay="0.1s">
                                <i class="fa fa-bed fa-2x text-primary mb-3 d-block"></i>
                                <h6 class="text-muted">Aucune chambre disponible</h6>
                                <p class="small text-muted mb-0">
                                    Toutes les chambres sont actuellement occupées.<br>
                                    Veuillez revenir bientôt.
                                </p>
                            </div>

                        @endforelse

                    </div>
                    {{-- end sticky sidebar --}}

                </div>
                {{-- end right col --}}

            </div>
            {{-- end row --}}
        </div>
    </div>
    <!-- Hotel Detail End -->

    {{-- Kept exactly as-is --}}
    @include('sections.testimonial')
    @include('sections.newsletter')

@endsection

@section('footer')
    @include('layouts.footer')
@endsection