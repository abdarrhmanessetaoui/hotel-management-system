@extends('layouts.app')

@section('header')
    @include('layouts.header')

    {{-- =========================================================
         Page Header — identical structure to list-rooms
         Background image kept the same
         Only the title and breadcrumb text change dynamically
         ========================================================= --}}
    <div class="container-fluid page-header mb-5 p-0"
         style="background-image: url({{ asset('img/carousel-1.jpg') }});">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center pb-5">

                {{-- Dynamic city name instead of static "Rooms" --}}
                <h1 class="display-3 text-white mb-3 animated slideInDown">
                    Hotels in {{ $city->name }}
                </h1>

                {{-- Breadcrumb: Home → Cities → {City Name} --}}
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb justify-content-center text-uppercase">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Villes</a>
                        </li>
                        <li class="breadcrumb-item text-white active" aria-current="page">
                            {{ $city->name }}
                        </li>
                    </ol>
                </nav>

            </div>
        </div>
    </div>
    {{-- Page Header End --}}

    {{-- NOTE: booking-header removed — city hotel listing
         does not require a date search at this step.
         Date selection happens on the individual hotel page. --}}
@endsection

@section('content')

    {{-- Hotel cards grid — replaces room-container-details --}}
    @include('sections.hotel-list', [
        'hotels' => $hotels,
        'city'   => $city,
    ])

    {{-- These sections are kept exactly as-is --}}
    @include('sections.testimonial')
    @include('sections.newsletter')

@endsection

@section('footer')
    @include('layouts.footer')
@endsection