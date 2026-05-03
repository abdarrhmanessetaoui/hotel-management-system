@extends('layouts.app')

@section('content')
    {{-- Fallback page when the Admin user has no hotel assigned --}}
    <div class="card">
        <div class="card-header">
            <h3>Aucun Hôtel Assigné</h3>
        </div>
        <div class="card-body">
            <p class="mb-0">
                You do not have a hotel assigned to your account.
            </p>
        </div>
    </div>
@endsection


