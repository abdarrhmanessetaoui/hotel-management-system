@extends('layouts.app')

@section('header')
    @if(!isset($AdminView) || !$AdminView)
        @include('layouts.header')
        <div class="container-fluid page-header mb-5 p-0" style="background-image: url({{ asset('img/carousel-1.jpg') }});">
            <div class="container-fluid page-header-inner py-5">
                <div class="container text-center pb-5">
                    <h6 class="section-title text-white text-uppercase mb-3 animated slideInDown">Hotelia — Mon Compte</h6>
                    <h1 class="display-3 text-white mb-3 animated slideInDown">Mon Profil</h1>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('content')
    <div class="container py-5">
        @include('components.show-success')

        <div class="row justify-content-center">
            <div class="col-lg-7">
                @include('profile.form', ['action' => route('profile.update'), 'user' => Auth::user()])
            </div>
        </div>
    </div>
    
    @if(!isset($AdminView) || !$AdminView)
        @include('sections.newsletter')
    @endif
@endsection

@section('footer')
    @if(!isset($AdminView) || !$AdminView)
        @include('layouts.footer')
    @endif
@endsection

