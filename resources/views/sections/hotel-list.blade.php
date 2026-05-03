{{--
    sections/hotel-list.blade.php
    ─────────────────────────────
    Displays hotels for a given city.
    Variables: $hotels (Collection), $city (City model)
    Flow: Click card → /hotels/{hotel} → HotelController@show
--}}

<!-- Hotel List Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title text-center text-primary text-uppercase">Nos Hôtels</h6>
            <h1 class="mb-5">
                Hôtels à <span class="text-primary text-uppercase">{{ $city->name }}</span>
            </h1>
        </div>
        <div class="row g-4">
            @forelse($hotels as $hotel)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="{{ $loop->iteration/10 }}s">
                    <a href="{{ route('hotels.show', $hotel) }}"
                       class="room-item shadow rounded overflow-hidden d-block text-decoration-none">

                        <div class="position-relative">
                            <img class="img-fluid w-100"
                                 style="height: 250px; object-fit: cover;"
                                 src="{{ $hotel->image ? (Str::startsWith($hotel->image, 'http') ? $hotel->image : asset($hotel->image)) : asset('img/hotels/default.jpg') }}"
                                 alt="{{ $hotel->name }}">
                            <small class="position-absolute start-0 top-0 bg-primary text-white rounded py-1 px-3 m-2">
                                <i class="fa fa-star text-white me-1"></i>{{ number_format($hotel->rating, 1) }}
                            </small>
                        </div>

                        <div class="p-4 mt-2">
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="mb-0 text-dark">{{ $hotel->name }}</h5>
                            </div>
                            <div class="d-flex mb-3">
                                <small class="border-end me-3 pe-3 text-muted">
                                    <i class="fa fa-map-marker-alt text-primary me-2"></i>
                                    {{ $hotel->location ?? 'Centre-ville' }}
                                </small>
                                <small class="text-muted">
                                    <i class="fa fa-bed text-primary me-2"></i>
                                    {{ $hotel->rooms_count ?? 0 }} {{ Str::plural('Chambre', $hotel->rooms_count ?? 0) }}
                                </small>
                            </div>
                            <p class="text-body mb-3">{{ Str::limit($hotel->description, 100) }}</p>
                            <div class="d-flex justify-content-between">
                                <span class="btn btn-sm btn-primary rounded py-2 px-4">
                                    Voir l'Hôtel
                                </span>
                            </div>
                        </div>

                    </a>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fa fa-hotel fa-3x text-primary mb-3"></i>
                    <h5 class="text-muted">Aucun hôtel disponible dans cette ville pour le moment.</h5>
                    <p class="text-muted">Explorez nos autres destinations.</p>
                    <a href="{{ route('home') }}#villes" class="btn btn-primary mt-2">
                        <i class="fa fa-arrow-left me-2"></i>Voir toutes les villes
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</div>
<!-- Hotel List End -->

