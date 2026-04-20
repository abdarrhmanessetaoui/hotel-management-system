@extends('layouts.app')

@section('header')
    @include('layouts.header')

    {{-- Page Header — dynamic city background --}}
    <div class="container-fluid page-header mb-0 p-0"
         style="background-image: url('{{ $city->image ? (Str::startsWith($city->image, 'http') ? $city->image : asset($city->image)) : asset('img/carousel-1.jpg') }}');">
        <div class="container-fluid page-header-inner py-5">
            <div class="container text-center py-5">

                <h6 class="section-title text-white text-uppercase mb-3 animated slideInDown">
                    Hotelia — Destinations
                </h6>

                <h1 class="display-3 text-white mb-3 animated slideInDown">
                    Hôtels à {{ $city->name }}
                </h1>

                <p class="text-white mb-4 animated fadeInUp">
                    {{ $hotels->count() }} {{ Str::plural('hôtel', $hotels->count()) }}
                    disponible{{ $hotels->count() > 1 ? 's' : '' }} dans cette ville
                </p>

                {{-- Breadcrumb --}}
                <nav aria-label="breadcrumb" class="mt-4">
                    <ol class="breadcrumb justify-content-center text-uppercase mb-0">
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}">Accueil</a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('home') }}#villes">Villes</a>
                        </li>
                        <li class="breadcrumb-item text-white active" aria-current="page">
                            {{ $city->name }}
                        </li>
                    </ol>
                </nav>

            </div>
        </div>
    </div>
@endsection

@section('content')

    @include('sections.hotel-list', [
        'hotels' => $hotels,
        'city'   => $city,
    ])

    @include('sections.testimonial')
    @include('sections.newsletter')

@endsection

@section('footer')
    @include('layouts.footer')
@endsection
