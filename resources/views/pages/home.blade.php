@extends('layouts.app')

@section('header')
    @include('layouts.header')

    {{-- 1. HOOK — grab attention immediately --}}
    @include('sections.carousel')
@endsection

@section('content')

    {{-- 2. ACTION — main goal: browse cities & book --}}
    @include('sections.city-container', ['cities' => $cities])

    {{-- 3. WHY US — build confidence after they've seen the offer --}}
    @include('sections.service')

    {{-- 4. SOCIAL PROOF — validate the decision --}}
    @include('sections.testimonial')
    
    {{-- 6. HUMAN — creator / team, personalize the brand --}}
    <div id="team">
        @include('sections.team')
    </div>
    
    {{-- 5. CAPTURE — newsletter before they leave --}}
    @include('sections.newsletter')


@endsection

@section('footer')
    @include('layouts.footer')
@endsection