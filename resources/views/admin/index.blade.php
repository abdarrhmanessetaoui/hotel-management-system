@extends('layouts.app')

@section('content')
    {{-- Admin Dashboard (Phase 11) --}}
    {{-- Controller provides: $hotel, $stats, $recentReservations --}}
    @php
        $rooms = $hotel?->rooms;
        $reservations = $hotel?->reservations;

        $roomsCount = $stats['total_rooms'] ?? ($rooms?->count() ?? 0);
        $reservationsCount = $stats['total_reservations'] ?? ($reservations?->count() ?? 0);
        $pendingCount = $stats['pending'] ?? 0;
        $confirmedCount = $stats['confirmed'] ?? 0;
    @endphp
    <div class="row row-cols-1 row-cols-md-3 g-4">
        <div class="col">
            <div class="card text-white bg-primary">
                <div class="card-header h4 text-center">Total Chambres</div>
                <div class="card-body py-5">
                    {{-- Total rooms for this Admin's hotel --}}
                    <h5 class="text-center">{{ $roomsCount }}</h5>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card text-white bg-success">
                <div class="card-header h4 text-center">Total Réservations</div>
                <div class="card-body py-5">
                    {{-- Total reservations for this Admin's hotel --}}
                    <h5 class="text-center">{{ $reservationsCount }}</h5>
                    {{-- Extra breakdown (still within the same card UI) --}}
                    <div class="text-center mt-3">
                        <div class="small">
                            En attente: {{ $pendingCount }} | Confirmée: {{ $confirmedCount }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
