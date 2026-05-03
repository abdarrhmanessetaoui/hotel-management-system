@extends('layouts.app')

@section('header')
    @include('layouts.header')
    <div class="container-fluid page-header mb-5 p-0"
         style="background-image: url('{{ $reservation->hotel->image ? (Str::startsWith($reservation->hotel->image, 'http') ? $reservation->hotel->image : asset($reservation->hotel->image)) : asset('img/carousel-1.jpg') }}');">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center pb-5">
                <h6 class="section-title text-white text-uppercase mb-3 animated slideInDown">
                    Hotelia — Mon Compte
                </h6>
                <h1 class="display-3 text-white mb-3 animated slideInDown">
                    Détails de la Réservation
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('reservations.index') }}">Mes Réservations</a>
                        </li>
                        <li class="breadcrumb-item text-white active" aria-current="page">
                            Détails
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

        @if(session('message'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fa fa-check-circle me-2"></i>{{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-5">

            {{-- ── Left: Reservation card ──────────────────────────────── --}}
            <div class="col-lg-8">
                <div class="room-item shadow rounded overflow-hidden mb-4">

                    {{-- Header --}}
                    <div class="bg-primary p-4 d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-white">
                            <i class="fa fa-receipt me-2"></i>
                            Réservation #{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}
                        </h4>
                        @if($reservation->status == 'pending')
                            <span class="badge bg-warning text-dark fs-6 rounded-pill px-3">En Attente</span>
                        @elseif($reservation->status == 'confirmed')
                            <span class="badge bg-success fs-6 rounded-pill px-3">Confirmé</span>
                        @elseif($reservation->status == 'cancelled')
                            <span class="badge bg-danger fs-6 rounded-pill px-3">Annulé</span>
                        @else
                            <span class="badge bg-secondary fs-6 rounded-pill px-3">
                                {{ ucfirst($reservation->status) }}
                            </span>
                        @endif
                    </div>

                    {{-- Dates --}}
                    <div class="p-4">
                        <div class="row g-4 mb-4">
                            <div class="col-sm-6 border-end">
                                <h6 class="text-uppercase text-muted fw-bold mb-2">
                                    <i class="fa fa-calendar-check text-primary me-2"></i>Arrivée
                                </h6>
                                <h4 class="mb-1">
                                    {{ \Carbon\Carbon::parse($reservation->check_in)->format('d M Y') }}
                                </h4>
                                <p class="text-muted mb-0 small">À partir de 14h00</p>
                            </div>
                            <div class="col-sm-6 ps-sm-4">
                                <h6 class="text-uppercase text-muted fw-bold mb-2">
                                    <i class="fa fa-calendar-times text-primary me-2"></i>Départ
                                </h6>
                                <h4 class="mb-1">
                                    {{ \Carbon\Carbon::parse($reservation->check_out)->format('d M Y') }}
                                </h4>
                                <p class="text-muted mb-0 small">Avant 12h00</p>
                            </div>
                        </div>

                        <hr>

                        {{-- Details --}}
                        <div class="row g-4 mt-2">
                            <div class="col-sm-4">
                                <h6 class="text-muted fw-bold mb-1">Type de Chambre</h6>
                                <p class="mb-0">
                                    {{ ['single' => 'Simple', 'double' => 'Double', 'suite' => 'Suite', 'deluxe' => 'Luxe'][$reservation->room->type] ?? ucfirst($reservation->room->type) }}
                                </p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-muted fw-bold mb-1">Personnes</h6>
                                <p class="mb-0">
                                    {{ $reservation->guests }}
                                    {{ $reservation->guests > 1 ? 'Personnes' : 'Personne' }}
                                </p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-muted fw-bold mb-1">Durée du Séjour</h6>
                                <p class="mb-0">
                                    {{ $reservation->stay_days }}
                                    {{ $reservation->stay_days > 1 ? 'Nuits' : 'Nuit' }}
                                </p>
                            </div>
                        </div>

                        {{-- Notes --}}
                        @if($reservation->notes)
                            <hr>
                            <div class="mt-3">
                                <h6 class="text-muted fw-bold mb-2">
                                    <i class="fa fa-comment text-primary me-2"></i>
                                    Demandes Spéciales
                                </h6>
                                <p class="mb-0 p-3 bg-light rounded">{{ $reservation->notes }}</p>
                            </div>
                        @endif
                    </div>

                    {{-- Footer: Total price --}}
                    <div class="p-4 border-top d-flex justify-content-between align-items-center bg-light">
                        <div>
                            <h6 class="text-muted mb-0">Prix Total</h6>
                            <small class="text-muted">
                                {{ $reservation->stay_days }} nuit(s) ×
                                {{ number_format($reservation->room->price, 0) }} DH
                            </small>
                        </div>
                        {{-- ✅ DH --}}
                        <h3 class="mb-0 text-primary fw-bold">
                            {{ number_format($reservation->total_price, 2) }} DH
                        </h3>
                    </div>

                </div>

                {{-- Cancel button --}}
                @if($reservation->status == 'pending' || $reservation->status == 'confirmed')
                    <div class="text-end">
                        <form action="{{ route('reservations.cancel', $reservation) }}"
                              method="POST"
                              onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette réservation ?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-danger py-2 px-5">
                                <i class="fa fa-times me-2"></i>Annuler la Réservation
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            {{-- ── Right: Hotel info + back button ────────────────────── --}}
            <div class="col-lg-4">

                <div class="room-item shadow rounded p-4 mb-4">
                    <h5 class="mb-4">
                        <i class="fa fa-hotel text-primary me-2"></i>
                        Informations sur l'Hôtel
                    </h5>
                    <img src="{{ $reservation->hotel->image ? (Str::startsWith($reservation->hotel->image, 'http') ? $reservation->hotel->image : asset($reservation->hotel->image)) : asset('img/hotels/default.jpg') }}"
                         class="img-fluid rounded mb-3 w-100"
                         style="height: 160px; object-fit: cover;"
                         alt="{{ $reservation->hotel->name }}">
                    <h5 class="mb-3">{{ $reservation->hotel->name }}</h5>
                    <p class="mb-2">
                        <i class="fa fa-map-marker-alt text-primary me-2"></i>
                        {{ $reservation->hotel->location ?? $reservation->hotel->city->name }}
                    </p>
                    <p class="mb-2">
                        <i class="fa fa-city text-primary me-2"></i>
                        {{ $reservation->hotel->city->name }}, Maroc
                    </p>
                    <p class="mb-0">
                        <i class="fa fa-star text-primary me-2"></i>
                        {{ number_format($reservation->hotel->rating, 1) }} / 5.0
                        @for($i = 1; $i <= 5; $i++)
                            <small class="fa fa-star {{ $i <= round($reservation->hotel->rating) ? 'text-primary' : 'text-muted' }}"></small>
                        @endfor
                    </p>
                </div>

                <a href="{{ route('reservations.index') }}"
                   class="btn btn-primary w-100 py-3 mb-3">
                    <i class="fa fa-arrow-left me-2"></i>Mes Réservations
                </a>

                <a href="{{ route('hotels.show', $reservation->hotel) }}"
                   class="btn btn-outline-primary w-100 py-3">
                    <i class="fa fa-hotel me-2"></i>Voir l'Hôtel
                </a>

            </div>

        </div>
    </div>
</div>

@include('sections.newsletter')
@endsection

@section('footer')
    @include('layouts.footer')
@endsection

