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
                    Mes Réservations
                </h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('profile') }}">Mon Profil</a>
                        </li>
                        <li class="breadcrumb-item text-white active" aria-current="page">
                            Mes Réservations
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

        {{-- Section heading --}}
        <div class="text-center wow fadeInUp mb-5" data-wow-delay="0.1s">
            <h6 class="section-title text-center text-primary text-uppercase">Mon Compte</h6>
            <h1>Mes <span class="text-primary text-uppercase">Réservations</span></h1>
        </div>

        <div class="room-item shadow rounded overflow-hidden wow fadeInUp" data-wow-delay="0.2s">

            {{-- Table header --}}
            <div class="bg-primary p-4 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white">
                    <i class="fa fa-calendar-check me-2"></i>
                    Mes Réservations
                </h5>
                <span class="badge bg-white text-primary px-3 py-2">
                    {{ $reservations->count() }}
                    {{ $reservations->count() > 1 ? 'réservations' : 'réservation' }}
                </span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="p-4" scope="col">Hôtel</th>
                            <th scope="col">Chambre</th>
                            <th scope="col">Dates</th>
                            <th scope="col">Personnes</th>
                            <th scope="col">Statut</th>
                            <th scope="col" class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reservations as $res)
                            <tr>
                                {{-- Hotel --}}
                                <td class="p-4">
                                    <div class="fw-bold text-dark">{{ $res->hotel->name }}</div>
                                    <small class="text-muted">
                                        <i class="fa fa-map-marker-alt text-primary me-1"></i>
                                        {{ $res->hotel->city->name ?? '' }}
                                    </small>
                                </td>

                                {{-- Room type --}}
                                <td>
                                    {{ ['single' => 'Simple', 'double' => 'Double', 'suite' => 'Suite', 'deluxe' => 'Luxe'][$res->room->type] ?? ucfirst($res->room->type) }}
                                </td>

                                {{-- Dates --}}
                                <td>
                                    <small class="d-block">
                                        <i class="fa fa-calendar-check text-primary me-1"></i>
                                        {{ \Carbon\Carbon::parse($res->check_in)->format('d M Y') }}
                                    </small>
                                    <small class="d-block">
                                        <i class="fa fa-calendar-times text-primary me-1"></i>
                                        {{ \Carbon\Carbon::parse($res->check_out)->format('d M Y') }}
                                    </small>
                                </td>

                                {{-- Guests --}}
                                <td>
                                    {{ $res->guests }}
                                    {{ $res->guests > 1 ? 'Personnes' : 'Personne' }}
                                </td>

                                {{-- Status --}}
                                <td>
                                    @if($res->status == 'pending')
                                        <span class="badge bg-warning text-dark rounded-pill px-3">En Attente</span>
                                    @elseif($res->status == 'confirmed')
                                        <span class="badge bg-success rounded-pill px-3">Confirmé</span>
                                    @elseif($res->status == 'cancelled')
                                        <span class="badge bg-danger rounded-pill px-3">Annulé</span>
                                    @else
                                        <span class="badge bg-secondary rounded-pill px-3">
                                            {{ ucfirst($res->status) }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="text-end pe-4">
                                    <a href="{{ route('reservations.show', $res) }}"
                                       class="btn btn-sm btn-outline-primary rounded">
                                        <i class="fa fa-eye me-1"></i>Détails
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center p-5">
                                    <i class="fa fa-calendar fa-3x text-primary mb-3 d-block"></i>
                                    <h5 class="text-muted">Vous n'avez aucune réservation pour le moment.</h5>
                                    <p class="text-muted">Explorez nos hôtels et réservez votre séjour idéal.</p>
                                    <a href="{{ route('home') }}#villes"
                                       class="btn btn-primary mt-2">
                                        <i class="fa fa-city me-2"></i>Explorer les Villes
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>

@include('sections.newsletter')
@endsection

@section('footer')
    @include('layouts.footer')
@endsection