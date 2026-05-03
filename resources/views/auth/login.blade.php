@extends('layouts.app')

@section('header')
    @include('layouts.header')
@endsection

@section('content')
    <div class="container-fluid min-vh-100 d-flex align-items-center justify-content-center" style="padding-top: 110px; padding-bottom: 50px;">
        <div class="row w-100 justify-content-center">
            <div class="col-12 col-sm-8 col-md-6 col-lg-5 col-xl-4 animate__animated animate__fadeIn">
                @error('login_err')
                @include('components.alert-error')
                @enderror
                <div class="card shadow-lg">
                    <div class="card-body">
                        <h4 class="card-title my-4 text-center">Connectez-vous à votre compte</h4>
                        <form novalidate class="row g-3" method="post" action="{{ route('login') }}">
                            @csrf
                            <div class="col-12">
                                <div class="input-group has-validation">
                                    <span class="input-group-text"> <i class="fa-solid fa-envelope"></i> </span>
                                    <input type="email" placeholder="Adresse email" name="email"
                                           value="{{ old('email') }}"
                                           class="form-control @error('email') is-invalid @enderror">
                                    @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="input-group has-validation">
                                    <span class="input-group-text"> <i class="fa fa-lock"></i> </span>
                                    <input type="password" placeholder="Mot de passe" name="password"
                                           class="form-control @error('password') is-invalid @enderror">
                                    @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-primary w-100" type="submit">Se connecter</button>
                            </div>
                            <p class="text-center">Pas encore de compte ? <a href="{{ route('register') }}">Créer un compte</a></p>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

