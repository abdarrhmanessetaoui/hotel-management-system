@extends('layouts.app')

@section('header')
    @include('layouts.header')
    {{-- Carousel stays exactly as-is --}}
    @include('sections.carousel')
@endsection

@section('content')
    {{-- Service section stays exactly as-is --}}
    @include('sections.service')

    {{-- CHANGED: was sections.room-container-brief
         Now displays city cards using the same card design --}}
    @include('sections.city-container', ['cities' => $cities])

    {{-- All remaining sections stay exactly as-is --}}
    @include('sections.testimonial')
    @include('sections.team')
    @include('sections.newsletter')
@endsection

@section('footer')
    @include('layouts.footer')
@endsection