@extends('layouts.app')

@section('header')
    @include('layouts.header')
    <div class="container-fluid page-header mb-5 p-0"
         style="background-image: url({{ asset('img/carousel-1.jpg') }});">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center pb-5">
                <h1 class="display-3 text-white mb-3 animated slideInDown">
                    {{ $hotel->name }}
                </h1>
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
                        <li class="breadcrumb-item text-primary active" aria-current="page">
                            {{ $hotel->name }}
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-5">

            {{-- ═══ LEFT COLUMN ═══ --}}
            <div class="col-lg-8">

                <div class="room-item shadow rounded overflow-hidden wow fadeInUp"
                     data-wow-delay="0.1s">

                    <div class="position-relative">
                        <img class="img-fluid w-100"
                             style="height: 400px; object-fit: cover;"
                             src="{{ $hotel->image
                                     ? (Str::startsWith($hotel->image, 'http') ? $hotel->image : asset($hotel->image))
                                     : asset('img/hotels/default.jpg') }}"
                             alt="{{ $hotel->name }}">
                        <small class="position-absolute start-0 top-0
                                      bg-primary text-white rounded
                                      py-1 px-3 ms-4">
                            <i class="fa fa-star me-1"></i>
                            {{ number_format($hotel->rating, 1) }} / 5.0
                        </small>
                    </div>

                    <div class="p-4 mt-2">
                        <div class="d-flex justify-content-between mb-3">
                            <h5 class="mb-0">{{ $hotel->name }}</h5>
                            <div class="ps-2">
                                @for ($i = 1; $i <= 5; $i++)
                                    <small class="fa fa-star {{ $i <= round($hotel->rating) ? 'text-primary' : 'text-muted' }}"></small>
                                @endfor
                            </div>
                        </div>

                        <div class="d-flex mb-3 flex-wrap gap-2">
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

                        @if ($hotel->description)
                            <p class="text-body mb-3">{{ $hotel->description }}</p>
                        @endif
                    </div>
                </div>

                {{-- Amenities --}}
                <div class="mt-5 wow fadeInUp" data-wow-delay="0.2s">
                    <h6 class="section-title text-primary text-uppercase">Ce qui est Inclus</h6>
                    <h4 class="mb-4">Équipements de l'Hôtel</h4>
                    <div class="row g-3">
                        @foreach ([
                            ['fa-wifi',          'WiFi Gratuit'],
                            ['fa-swimming-pool', 'Piscine'],
                            ['fa-utensils',      'Restaurant'],
                            ['fa-dumbbell',      'Salle de Sport'],
                            ['fa-concierge-bell','Service de Chambre'],
                            ['fa-parking',       'Parking Gratuit'],
                            ['fa-snowflake',     'Climatisation'],
                            ['fa-shield-alt',    'Sécurité 24h/24'],
                        ] as [$icon, $label])
                            <div class="col-6 col-md-3">
                                <div class="room-item shadow rounded text-center p-3">
                                    <i class="fa {{ $icon }} fa-2x text-primary mb-2 d-block"></i>
                                    <p class="mb-0 small">{{ $label }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
            {{-- end left col --}}

            {{-- ═══ RIGHT COLUMN ═══ --}}
            <div class="col-lg-4" id="available-rooms">

                <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
                    <h6 class="section-title text-center text-primary text-uppercase">Réservez Votre Séjour</h6>
                    <h4 class="mb-4">Chambres Disponibles</h4>
                </div>

                @forelse ($rooms as $room)
                    <div class="room-item shadow rounded overflow-hidden mb-4 wow fadeInUp"
                         data-wow-delay="{{ $loop->iteration / 10 }}s">

                        <div class="position-relative">
                            <img class="img-fluid w-100"
                                 style="height: 200px; object-fit: cover;"
                                 src="{{ $room->image
                                         ? (Str::startsWith($room->image, 'http') ? $room->image : asset($room->image))
                                         : asset('img/rooms/default.jpg') }}"
                                 alt="{{ $room->type }}">

                            {{-- ✅ DH --}}
                            <small class="position-absolute start-0 top-0
                                          bg-primary text-white rounded
                                          py-1 px-3 ms-4">
                                {{ number_format($room->price, 0) }} DH/Nuit
                            </small>
                        </div>

                        <div class="p-4">
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="mb-0">
                                    {{ ['single' => 'Simple', 'double' => 'Double', 'suite' => 'Suite', 'deluxe' => 'Luxe'][$room->type] ?? ucfirst($room->type) }}
                                </h5>
                                <div class="ps-2">
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                </div>
                            </div>

                            <div class="d-flex mb-3">
                                <small class="border-end me-3 pe-3">
                                    <i class="fa fa-bed text-primary me-2"></i>
                                    {{ ucfirst($room->type) }}
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

                            @if ($room->description)
                                <p class="text-body mb-3 small">
                                    {{ Str::limit($room->description, 80) }}
                                </p>
                            @endif

                            <div class="d-flex justify-content-between align-items-center">
                                {{-- ✅ DH --}}
                                <span class="fw-bold text-primary">
                                    {{ number_format($room->price, 2) }} DH
                                    <small class="text-muted fw-normal">/nuit</small>
                                </span>

                                @auth
                                    <a href="{{ route('reservations.create', $hotel) }}?room={{ $room->id }}"
                                       class="btn btn-sm btn-success rounded py-2 px-4">
                                        <i class="fa fa-calendar-check me-1"></i> Réserver
                                    </a>
                                @else
                                    <a href="{{ route('login') }}?redirect={{ urlencode(route('hotels.show', $hotel)) }}"
                                       class="btn btn-sm btn-success rounded py-2 px-4">
                                        <i class="fa fa-calendar-check me-1"></i> Réserver
                                    </a>
                                @endauth
                            </div>

                        </div>
                    </div>

                @empty
                    <div class="room-item shadow rounded p-4 text-center">
                        <i class="fa fa-bed fa-2x text-primary mb-3 d-block"></i>
                        <h6 class="text-muted">Aucune chambre disponible</h6>
                        <p class="small text-muted mb-0">
                            Toutes les chambres sont actuellement occupées.<br>
                            Veuillez revenir bientôt.
                        </p>
                    </div>
                @endforelse

            </div>
            {{-- end right col --}}

        </div>
    </div>
</div>

@include('sections.testimonial')
@include('sections.newsletter')

@endsection

@section('footer')
    @include('layouts.footer')
@endsection

