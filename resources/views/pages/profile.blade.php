@extends('layouts.app')

@section('header')
    @include('layouts.header')

    <div class="container-fluid page-header mb-5 p-0"
         style="background-image: url({{ asset('img/carousel-1.jpg') }});">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center pb-5">

                <h6 class="section-title text-white text-uppercase mb-3 animated slideInDown">
                    Hotelia — Mon Compte
                </h6>

                <h1 class="display-3 text-white mb-3 animated slideInDown">
                    Mon Profil
                </h1>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item text-white active" aria-current="page">
                            Mon Profil
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
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fa fa-check-circle me-2"></i>{{ session('message') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <div class="row g-5 justify-content-center">

            {{-- ── Left: Avatar + quick info ──────────────────────────── --}}
            <div class="col-lg-3 col-md-4 text-center">
                <div class="room-item shadow rounded p-4">

                    {{-- Avatar circle with initial --}}
                    <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3"
                         style="width: 90px; height: 90px;">
                        <span class="text-white fw-bold" style="font-size: 2rem;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </span>
                    </div>

                    <h5 class="fw-bold mb-1">{{ $user->name }} {{ $user->last_name }}</h5>
                    <small class="text-primary text-uppercase" style="letter-spacing: 1px;">
                        @if($user->isSuperAdmin()) Super Admin
                        @elseif($user->isAdmin()) Hôtelier
                        @else Client
                        @endif
                    </small>

                    <hr class="my-3">

                    <div class="text-start">
                        <p class="mb-2 small text-muted">
                            <i class="fa fa-envelope text-primary me-2"></i>
                            {{ $user->email }}
                        </p>
                        <p class="mb-2 small text-muted">
                            <i class="fa fa-phone text-primary me-2"></i>
                            {{ $user->phone ?? 'Non renseigné' }}
                        </p>
                        <p class="mb-0 small text-muted">
                            <i class="fa fa-calendar text-primary me-2"></i>
                            Membre depuis {{ $user->created_at->format('M Y') }}
                        </p>
                    </div>

                    <hr class="my-3">

                    <a href="{{ route('reservations.index') }}"
                       class="btn btn-sm btn-outline-primary w-100">
                        <i class="fa fa-calendar-check me-2"></i>Mes Réservations
                    </a>
                </div>
            </div>

            {{-- ── Right: Edit form ───────────────────────────────────── --}}
            <div class="col-lg-6 col-md-8">
                <div class="room-item shadow rounded p-5">

                    <h4 class="mb-1">Modifier mes informations</h4>
                    <p class="text-muted mb-4" style="font-size: 0.9rem;">
                        Mettez à jour vos informations personnelles ci-dessous.
                    </p>

                    <form class="row g-3" method="post" action="{{ route('profile.update') }}">
                        @csrf
                        @method('put')

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Prénom</label>
                            <input type="text" name="name"
                                   value="{{ old('name', $user->name) }}"
                                   class="form-control @error('name') is-invalid @enderror"
                                   placeholder="Votre prénom">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nom</label>
                            <input type="text" name="last_name"
                                   value="{{ old('last_name', $user->last_name) }}"
                                   class="form-control @error('last_name') is-invalid @enderror"
                                   placeholder="Votre nom">
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Téléphone</label>
                            <input type="text" name="phone"
                                   value="{{ old('phone', $user->phone) }}"
                                   class="form-control @error('phone') is-invalid @enderror"
                                   placeholder="+212 6XX XXX XXX">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Email</label>
                            <input type="email" readonly
                                   value="{{ $user->email }}"
                                   class="form-control bg-light text-muted">
                            <small class="text-muted">
                                <i class="fa fa-lock me-1"></i>L'email ne peut pas être modifié.
                            </small>
                        </div>

                        <div class="col-12 mt-2">
                            <button type="submit" class="btn btn-primary py-2 px-5">
                                <i class="fa fa-save me-2"></i>Enregistrer les modifications
                            </button>
                        </div>

                    </form>
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