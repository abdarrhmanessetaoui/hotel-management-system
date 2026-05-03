
<div class="card mt-4">
    <div class="card-header">
        <h3>Statistiques</h3>
    </div>
    <div class="card-body">
        @php
            $totalRooms = $stats['total_rooms'] ?? ($hotel?->rooms?->count() ?? 0);
            $totalReservations = $stats['total_reservations'] ?? ($hotel?->reservations?->count() ?? 0);
            $pending = $stats['pending'] ?? 0;
            $confirmed = $stats['confirmed'] ?? 0;
        @endphp

        {{-- Quick counts --}}
        <div class="mb-3">
            <div><strong>Total Chambres:</strong> {{ $totalRooms }}</div>
            <div><strong>Total Réservations:</strong> {{ $totalReservations }}</div>
            <div><strong>Pending:</strong> {{ $pending }}</div>
            <div><strong>Confirmed:</strong> {{ $confirmed }}</div>
        </div>

        {{-- Revenue (if available) --}}
        @if(isset($stats['revenue']))
            <div>
                <strong>Revenue:</strong> ${{ number_format((float) $stats['revenue'], 2) }}
            </div>
        @endif
    </div>
</div>


