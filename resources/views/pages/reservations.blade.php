@extends('layouts.app')

@section('header')
    @include('layouts.header')
    <!-- Page Header Start -->
    <div class="container-fluid page-header mb-5 p-0" style="background-image: url('https://picsum.photos/1920/1080?random=2');">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center pb-5">
                <h1 class="display-3 text-white mb-3 animated slideInDown">My Reservations</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('profile') }}">Profile</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Réservations</li>
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

        <div class="card shadow rounded border-0">
            <div class="card-header bg-primary text-white p-4">
                <h2 class="mb-0 text-white">My Reservations</h2>
            </div>
            <div class="card-body p-0">
                
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="p-4" scope="col">Hôtel</th>
                                <th scope="col">Chambre</th>
                                <th scope="col">Dates</th>
                                <th scope="col">Clients</th>
                                <th scope="col">Statut</th>
                                <th scope="col" class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reservations as $res)
                                <tr>
                                    <td class="p-4 fw-bold text-dark">{{ $res->hotel->name }}</td>
                                    <td>{{ ['single' => 'Simple', 'double' => 'Double', 'suite' => 'Suite', 'deluxe' => 'Luxe'][$res->room->type] ?? ucfirst($res->room->type) }}</td>
                                    <td>
                                        <small class="d-block"><i class="fa fa-calendar-check text-primary me-1"></i> {{ \Carbon\Carbon::parse($res->check_in)->format('M d, Y') }}</small>
                                        <small class="d-block"><i class="fa fa-calendar-times text-primary me-1"></i> {{ \Carbon\Carbon::parse($res->check_out)->format('M d, Y') }}</small>
                                    </td>
                                    <td>{{ $res->guests }} {{ Str::plural('Guest', $res->guests) }}</td>
                                    <td>
                                        @if($res->status == 'pending' || $res->status == 'confirmed')
                                            <span class="badge bg-success rounded-pill px-3">{{ ['pending' => 'En Attente', 'confirmed' => 'Confirmé', 'cancelled' => 'Annulé', 'available' => 'Disponible', 'booked' => 'Réservé'][$res->status] ?? ucfirst($res->status) }}</span>
                                        @elseif($res->status == 'cancelled')
                                            <span class="badge bg-danger rounded-pill px-3">{{ ['pending' => 'En Attente', 'confirmed' => 'Confirmé', 'cancelled' => 'Annulé', 'available' => 'Disponible', 'booked' => 'Réservé'][$res->status] ?? ucfirst($res->status) }}</span>
                                        @else
                                            <span class="badge bg-secondary rounded-pill px-3">{{ ['pending' => 'En Attente', 'confirmed' => 'Confirmé', 'cancelled' => 'Annulé', 'available' => 'Disponible', 'booked' => 'Réservé'][$res->status] ?? ucfirst($res->status) }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4">
                                        <a href="{{ route('reservations.show', $res) }}" class="btn btn-sm btn-outline-primary">Détails</a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center p-5">
                                        <i class="fa fa-calendar fa-3x text-muted mb-3 d-block"></i>
                                        <h5 class="text-muted">You don't have any reservations yet.</h5>
                                        <a href="{{ route('home') }}" class="btn btn-primary mt-3">Browse Hotels</a>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>
        </div>
        
    </div>
</div>
<!-- Newsletter -->
@include('sections.newsletter')
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
