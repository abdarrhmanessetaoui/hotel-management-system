@extends('layouts.app')

@section('header')
    @include('layouts.header')
    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 p-0" style="background-image: url('{{ $reservation->hotel->image ? (Str::startsWith($reservation->hotel->image, 'http') ? $reservation->hotel->image : asset($reservation->hotel->image)) : 'https://picsum.photos/1920/1080?random=3' }}');">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center pb-5">
                <h1 class="display-3 text-white mb-3 animated slideInDown">Détails de la Réservation</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('reservations.index') }}">Réservations</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Détails</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
    <!-- Page Header End -->
@endsection

@section('content')
<div class="container-xxl py-5">
    <div class="container">
        
        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        <div class="row g-5">
            <div class="col-lg-8">
                <div class="card shadow rounded border-0 mb-4">
                    <div class="card-header bg-primary text-white p-4 d-flex justify-content-between align-items-center">
                        <h3 class="mb-0 text-white"><i class="fa fa-receipt me-2"></i>Booking #{{ str_pad($reservation->id, 5, '0', STR_PAD_LEFT) }}</h3>
                        @if($reservation->status == 'pending' || $reservation->status == 'confirmed')
                            <span class="badge bg-success fs-6 rounded-pill px-3">{{ ['pending' => 'En Attente', 'confirmed' => 'Confirmé', 'cancelled' => 'Annulé', 'available' => 'Disponible', 'booked' => 'Réservé'][$reservation->status] ?? ucfirst($reservation->status) }}</span>
                        @elseif($reservation->status == 'cancelled')
                            <span class="badge bg-danger fs-6 rounded-pill px-3">{{ ['pending' => 'En Attente', 'confirmed' => 'Confirmé', 'cancelled' => 'Annulé', 'available' => 'Disponible', 'booked' => 'Réservé'][$reservation->status] ?? ucfirst($reservation->status) }}</span>
                        @else
                            <span class="badge bg-secondary fs-6 rounded-pill px-3">{{ ['pending' => 'En Attente', 'confirmed' => 'Confirmé', 'cancelled' => 'Annulé', 'available' => 'Disponible', 'booked' => 'Réservé'][$reservation->status] ?? ucfirst($reservation->status) }}</span>
                        @endif
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4 mb-4">
                            <div class="col-sm-6 border-end">
                                <h6 class="text-uppercase text-muted fw-bold mb-3">Check-In</h6>
                                <h4>{{ \Carbon\Carbon::parse($reservation->check_in)->format('F d, Y') }}</h4>
                                <p class="text-muted mb-0">From 14:00</p>
                            </div>
                            <div class="col-sm-6 ps-sm-4">
                                <h6 class="text-uppercase text-muted fw-bold mb-3">Check-Out</h6>
                                <h4>{{ \Carbon\Carbon::parse($reservation->check_out)->format('F d, Y') }}</h4>
                                <p class="text-muted mb-0">Until 12:00</p>
                            </div>
                        </div>

                        <hr>

                        <div class="row g-4 mt-2">
                            <div class="col-sm-4">
                                <h6 class="text-muted fw-bold">Room Type</h6>
                                <p class="mb-0">{{ ['single' => 'Simple', 'double' => 'Double', 'suite' => 'Suite', 'deluxe' => 'Luxe'][$reservation->room->type] ?? ucfirst($reservation->room->type) }} Room</p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-muted fw-bold">Clients</h6>
                                <p class="mb-0">{{ $reservation->guests }} {{ Str::plural('Guest', $reservation->guests) }}</p>
                            </div>
                            <div class="col-sm-4">
                                <h6 class="text-muted fw-bold">Length of Stay</h6>
                                <p class="mb-0">{{ $reservation->stayDays }} {{ Str::plural('Night', $reservation->stayDays) }}</p>
                            </div>
                        </div>

                        @if($reservation->notes)
                        <hr>
                        <div class="mt-4">
                            <h6 class="text-muted fw-bold">Special Requests / Notes</h6>
                            <p class="mb-0 p-3 bg-light rounded">{{ $reservation->notes }}</p>
                        </div>
                        @endif

                    </div>
                    
                    <div class="card-footer bg-light p-4 d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-dark">Total Price</h4>
                        <h3 class="mb-0 text-primary">${{ number_format($reservation->totalPrice, 2) }}</h3>
                    </div>
                </div>

                @if($reservation->status == 'pending' || $reservation->status == 'confirmed')
                    <div class="text-end">
                        <form action="{{ route('reservations.cancel', $reservation) }}" method="POST" onsubmit="return confirm('Are you sure you want to cancel this reservation?');">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-danger py-2 px-4"><i class="fa fa-times me-2"></i>Cancel Reservation</button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="col-lg-4">
                <div class="bg-light shadow rounded p-4 mb-4">
                    <h4 class="mb-4">Informations sur l'Hôtel</h4>
                    <img src="{{ $reservation->hotel->image ? (Str::startsWith($reservation->hotel->image, 'http') ? $reservation->hotel->image : asset($reservation->hotel->image)) : 'https://picsum.photos/400/300?random=4' }}" class="img-fluid rounded mb-3" alt="{{ $reservation->hotel->name }}">
                    <h5>{{ $reservation->hotel->name }}</h5>
                    <p class="mb-2"><i class="fa fa-map-marker-alt text-primary me-3"></i>{{ $reservation->hotel->location ?? $reservation->hotel->city->name }}</p>
                    <p class="mb-0"><i class="fa fa-star text-primary me-3"></i>{{ number_format($reservation->hotel->rating, 1) }} / 5.0 Rating</p>
                </div>
                
                <a href="{{ route('reservations.index') }}" class="btn btn-dark w-100 py-3"><i class="fa fa-arrow-left me-2"></i>Back to My Reservations</a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
