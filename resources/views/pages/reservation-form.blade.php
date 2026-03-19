@extends('layouts.app')

@section('header')
    @include('layouts.header')
    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 p-0" style="background-image: url('{{ $hotel->image ? (Str::startsWith($hotel->image, 'http') ? $hotel->image : asset($hotel->image)) : 'https://picsum.photos/1920/1080?random=1' }}');">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center pb-5">
                <h1 class="display-3 text-white mb-3 animated slideInDown">Complétez votre réservation</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('hotels.show', $hotel) }}">{{ $hotel->name }}</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Booking</li>
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
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title text-center text-primary text-uppercase">Réservation</h6>
            <h1 class="mb-5">Book Your <span class="text-primary text-uppercase">Stay</span></h1>
        </div>

        <div class="row g-5">
            <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.2s">
                <div class="bg-white shadow rounded p-5">
                    
                    @if(session('message'))
                        <div class="alert alert-success">{{ session('message') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('reservations.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">

                        <div class="row g-3">
                            <div class="col-md-12">
                                <label for="room_id" class="form-label fw-bold">Select Room</label>
                                <select class="form-select" id="room_id" name="room_id" required>
                                    <option value="" disabled {{ old('room_id', $selectedRoomId) ? '' : 'selected' }}>Choose a room...</option>
                                    @foreach($rooms as $room)
                                        <option value="{{ $room->id }}" {{ old('room_id', $selectedRoomId) == $room->id ? 'selected' : '' }}>
                                            {{ ['single' => 'Simple', 'double' => 'Double', 'suite' => 'Suite', 'deluxe' => 'Luxe'][$room->type] ?? ucfirst($room->type) }} - ${{ number_format($room->price, 2) }} / Night
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label for="check_in" class="form-label fw-bold">Check-In</label>
                                <input type="date" class="form-control" id="check_in" name="check_in" value="{{ old('check_in') }}" required min="{{ date('Y-m-d') }}">
                            </div>

                            <div class="col-md-6">
                                <label for="check_out" class="form-label fw-bold">Check-Out</label>
                                <input type="date" class="form-control" id="check_out" name="check_out" value="{{ old('check_out') }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                            </div>

                            <div class="col-md-12">
                                <label for="guests" class="form-label fw-bold">Number of Guests</label>
                                <input type="number" class="form-control" id="guests" name="guests" min="1" value="{{ old('guests', 1) }}" required>
                            </div>

                            <div class="col-12">
                                <label for="notes" class="form-label fw-bold">Special Requests</label>
                                <textarea class="form-control" id="notes" name="notes" rows="4" placeholder="Any special requests?">{{ old('notes') }}</textarea>
                            </div>

                            <div class="col-12 mt-4">
                                <button class="btn btn-primary w-100 py-3" type="submit">Confirm Reservation</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.4s">
                <div class="bg-light shadow rounded p-4 mb-4">
                    <h4 class="mb-4">Informations sur l'Hôtel</h4>
                    <p><i class="fa fa-hotel text-primary me-3"></i>{{ $hotel->name }}</p>
                    <p><i class="fa fa-map-marker-alt text-primary me-3"></i>{{ $hotel->location ?? $hotel->city->name }}</p>
                    <p><i class="fa fa-star text-primary me-3"></i>{{ number_format($hotel->rating, 1) }} / 5.0 Rating</p>
                </div>
                
                <div class="bg-light shadow rounded p-4">
                    <h4 class="mb-4">Need Help?</h4>
                    <p class="mb-2">If you have any questions about your booking, please don't hesitate to contact us.</p>
                    <p class="mb-0"><i class="fa fa-phone-alt text-primary me-3"></i>+1514 345 6789</p>
                    <p class="mb-0 mt-2"><i class="fa fa-envelope text-primary me-3"></i>info@example.com</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
