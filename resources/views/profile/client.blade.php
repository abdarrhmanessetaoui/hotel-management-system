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
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
                        <li class="breadcrumb-item text-white active" aria-current="page">Mon Profil</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection

@section('content')
<div class="container-xxl py-5">
    <div class="container">
        @include('components.show-success')

        <div class="row g-5 justify-content-center">
            {{-- Unified Profile Form --}}
            <div class="col-lg-7">
                @include('profile.form', ['action' => route('profile.update'), 'user' => $user])
            </div>
        </div>
    </div>
</div>
@include('sections.newsletter')
@endsection

@section('footer')
    @include('layouts.footer')
@endsection
