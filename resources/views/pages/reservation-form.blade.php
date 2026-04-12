@extends('layouts.app')

@section('header')
    @include('layouts.header')
    <div class="container-fluid page-header mb-5 p-0"
         style="background-image: url('{{ $hotel->image ? (Str::startsWith($hotel->image, 'http') ? $hotel->image : asset($hotel->image)) : asset('img/carousel-1.jpg') }}');">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center pb-5">
                <h6 class="section-title text-white text-uppercase mb-3 animated slideInDown">
                    Hotelia — Réservation
                </h6>
                <h1 class="display-3 text-white mb-3 animated slideInDown">
                    Complétez votre Réservation
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('hotels.show', $hotel) }}">{{ $hotel->name }}</a>
                        </li>
                        <li class="breadcrumb-item text-white active" aria-current="page">
                            Réservation
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

        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title text-center text-primary text-uppercase">Réservation</h6>
            <h1 class="mb-5">
                Réservez Votre
                <span class="text-primary text-uppercase">Séjour</span>
            </h1>
        </div>

        <div class="row g-4 g-lg-5">

            {{-- ── Left: Form ─────────────────────────────────── --}}
            {{-- ✅ on mobile col-12 (full), desktop col-lg-8 --}}
            <div class="col-12 col-lg-8 wow fadeInUp" data-wow-delay="0.2s">
                <div class="room-item shadow rounded p-3 p-md-5">

                    @if(session('message'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fa fa-check-circle me-2"></i>{{ session('message') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fa fa-exclamation-circle me-2"></i>
                            <strong>Veuillez corriger les erreurs suivantes :</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('reservations.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">

                        <div class="row g-3">

                            {{-- Room --}}
                            <div class="col-12">
                                <label for="room_id" class="form-label fw-bold">
                                    <i class="fa fa-bed text-primary me-2"></i>Choisir une Chambre
                                </label>
                                <select class="form-select" id="room_id" name="room_id" required>
                                    <option value="" disabled {{ old('room_id', $selectedRoomId) ? '' : 'selected' }}>
                                        Sélectionnez une chambre...
                                    </option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}"
                                                {{ old('room_id', $selectedRoomId) == $room->id ? 'selected' : '' }}>
                                            {{ ['single'=>'Simple','double'=>'Double','suite'=>'Suite','deluxe'=>'Luxe'][$room->type] ?? ucfirst($room->type) }}
                                            — {{ number_format($room->price, 0) }} DH / Nuit
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- ✅ Dates: full width on mobile, side by side md+ --}}
                            <div class="col-12 col-md-6">
                                <label for="check_in" class="form-label fw-bold">
                                    <i class="fa fa-calendar-check text-primary me-2"></i>Date d'Arrivée
                                </label>
                                <input type="date" class="form-control" id="check_in"
                                       name="check_in" value="{{ old('check_in') }}"
                                       required min="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-12 col-md-6">
                                <label for="check_out" class="form-label fw-bold">
                                    <i class="fa fa-calendar-times text-primary me-2"></i>Date de Départ
                                </label>
                                <input type="date" class="form-control" id="check_out"
                                       name="check_out" value="{{ old('check_out') }}"
                                       required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            </div>

                            {{-- Guests --}}
                            <div class="col-12">
                                <label for="guests" class="form-label fw-bold">
                                    <i class="fa fa-users text-primary me-2"></i>Nombre de Personnes
                                </label>
                                <input type="number" class="form-control" id="guests"
                                       name="guests" min="1" max="10"
                                       value="{{ old('guests', 1) }}" required>
                            </div>

                            {{-- Notes --}}
                            <div class="col-12">
                                <label for="notes" class="form-label fw-bold">
                                    <i class="fa fa-comment text-primary me-2"></i>Demandes Spéciales
                                    <small class="text-muted fw-normal">(facultatif)</small>
                                </label>
                                <textarea class="form-control" id="notes" name="notes"
                                          rows="4"
                                          placeholder="Ex: chambre non-fumeur, lit bébé, arrivée tardive...">{{ old('notes') }}</textarea>
                            </div>

                            {{-- Submit --}}
                            <div class="col-12">
                                <button class="btn btn-primary w-100 py-3" type="submit">
                                    <i class="fa fa-calendar-check me-2"></i>
                                    Confirmer la Réservation
                                </button>
                            </div>

                        </div>
                    </form>
                </div>
            </div>

            {{-- ── Right: Hotel info + Help ─────────────────── --}}
            {{-- ✅ on mobile shows ABOVE form via order classes --}}
            <div class="col-12 col-lg-4 wow fadeInUp" data-wow-delay="0.4s">

                {{-- Hotel card --}}
                <div class="room-item shadow rounded p-3 p-md-4 mb-4">
                    <h5 class="mb-4">
                        <i class="fa fa-hotel text-primary me-2"></i>
                        Informations sur l'Hôtel
                    </h5>

                    @if($hotel->image)
                        <img src="{{ Str::startsWith($hotel->image,'http') ? $hotel->image : asset($hotel->image) }}"
                             class="img-fluid rounded mb-3 w-100"
                             style="height:160px;object-fit:cover;"
                             alt="{{ $hotel->name }}">
                    @endif

                    <p class="mb-2">
                        <i class="fa fa-hotel text-primary me-2"></i>
                        <strong>{{ $hotel->name }}</strong>
                    </p>
                    <p class="mb-2">
                        <i class="fa fa-map-marker-alt text-primary me-2"></i>
                        {{ $hotel->location ?? $hotel->city->name }}
                    </p>
                    <p class="mb-2">
                        <i class="fa fa-city text-primary me-2"></i>
                        {{ $hotel->city->name }}, Maroc
                    </p>
                    <p class="mb-0">
                        <i class="fa fa-star text-primary me-2"></i>
                        {{ number_format($hotel->rating, 1) }} / 5.0
                        @for($i = 1; $i <= 5; $i++)
                            <small class="fa fa-star {{ $i <= round($hotel->rating) ? 'text-primary' : 'text-muted' }}"></small>
                        @endfor
                    </p>
                </div>

                {{-- Help card --}}
                <div class="room-item shadow rounded p-3 p-md-4">
                    <h5 class="mb-4">
                        <i class="fa fa-headset text-primary me-2"></i>
                        Besoin d'aide ?
                    </h5>
                    <p class="text-muted mb-3" style="font-size:.9rem;">
                        Notre équipe est disponible 24h/24 pour répondre
                        à toutes vos questions.
                    </p>
                    <p class="mb-2">
                        <i class="fa fa-phone-alt text-primary me-2"></i>
                        +212 599 887 766
                    </p>
                    <p class="mb-0">
                        <i class="fa fa-envelope text-primary me-2"></i>
                        hotelia@gmail.com
                    </p>
                </div>

            </div>

        </div>
    </div>
</div>

@include('sections.newsletter')
@endsection

@section('footer')
    @include('layouts.footer')
@endsection