@php
    use Illuminate\Support\Str;
@endphp

<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title text-center text-primary text-uppercase">Nos Chambres</h6>
            <h1 class="mb-5">
                Explorez Nos
                <span class="text-primary text-uppercase">Chambres</span>
            </h1>
        </div>
        <div class="row g-4">
            @forelse($rooms as $room)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="{{ $loop->iteration * 0.1 }}s">
                    <div class="room-item shadow rounded overflow-hidden">

                        <div class="position-relative">
                            <img src="{{ $room->image ? (Str::startsWith($room->image, 'http') ? $room->image : asset($room->image)) : asset('img/rooms/default.jpg') }}"
                                 alt="{{ $room->type }}"
                                 class="img-fluid w-100"
                                 style="height: 250px; object-fit: cover;"
                                 loading="lazy">
                            {{-- ✅ DH --}}
                            <small class="position-absolute start-0 top-0 bg-primary text-white rounded py-1 px-3 ms-4">
                                {{ number_format($room->price ?? 0, 0) }} DH/Nuit
                            </small>
                        </div>

                        <div class="p-4 mt-2">
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="mb-0">
                                    {{ ['single' => 'Simple', 'double' => 'Double', 'suite' => 'Suite', 'deluxe' => 'Luxe'][$room->type] ?? ucfirst($room->type) }}
                                </h5>
                                <div class="ps-2">
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                    <small class="fa fa-star text-primary"></small>
                                </div>
                            </div>

                            <div class="d-flex mb-3">
                                <small class="border-end me-3 pe-3 text-muted">
                                    <i class="fa fa-bed text-primary me-2"></i>
                                    {{ ucfirst($room->type) }}
                                </small>
                                <small class="border-end me-3 pe-3 text-muted">
                                    <i class="fa fa-bath text-primary me-2"></i>
                                    Bain
                                </small>
                                <small class="text-muted">
                                    <i class="fa fa-wifi text-primary me-2"></i>
                                    Wifi
                                </small>
                            </div>

                            <p class="text-body mb-3">
                                {{ Str::limit($room->description ?? 'Description non disponible', 80) }}
                            </p>

                            <div class="d-flex justify-content-between">
                                <a class="btn btn-sm btn-primary rounded py-2 px-4"
                                   href="{{ $room->hotel ? route('hotels.show', $room->hotel) : route('home') }}">
                                    Voir les Détails
                                </a>
                                <a class="btn btn-sm btn-dark rounded py-2 px-4"
                                   href="{{ $room->hotel ? route('reservations.create', $room->hotel) : route('home') }}">
                                    Réserver
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fa fa-bed fa-3x text-primary mb-3"></i>
                    <h5 class="text-muted">Aucune chambre disponible pour le moment.</h5>
                    <p class="text-muted">Explorez nos hôtels pour trouver votre chambre idéale.</p>
                    <a href="{{ route('home') }}#villes" class="btn btn-primary mt-2">
                        <i class="fa fa-city me-2"></i>Voir nos destinations
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>

<style>
    .room-item:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15) !important;
    }
</style>
