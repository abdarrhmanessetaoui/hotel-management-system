@php
    use Illuminate\Support\Str;
@endphp

<!-- Destination Start -->
{{-- ✅ id="villes" added — carousel buttons scroll here --}}
<div class="container-xxl py-4" id="villes">
    <div class="container">

        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title text-center text-primary text-uppercase">
                Destinations
            </h6>
            <h1 class="mb-5">
                Explorez Nos
                <span class="text-primary text-uppercase">Villes</span>
            </h1>
        </div>

        <div class="row g-4">

            @forelse ($cities as $city)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="{{ $loop->iteration * 0.1 }}s">
                    <a href="{{ route('cities.hotels', $city) }}"
                       class="room-item shadow rounded overflow-hidden d-block text-decoration-none"
                       style="transition: transform 0.3s ease, box-shadow 0.3s ease;">

                        <div class="position-relative">
                            <img class="img-fluid w-100" style="height: 250px; object-fit: cover;"
                                 src="{{ $city->image ? (Str::startsWith($city->image, 'http') ? $city->image : asset($city->image)) : asset('img/cities/default.jpg') }}"
                                 alt="{{ $city->name }}" loading="lazy">
                            <small class="position-absolute start-0 bottom-0 bg-primary text-white rounded-end
                                          py-1 px-3 mb-3">
                                {{ $city->hotels_count ?? 0 }}
                                {{ Str::plural('Hôtel', $city->hotels_count ?? 0) }}
                            </small>
                        </div>

                        <div class="p-4 mt-2">
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="mb-0 text-dark">{{ $city->name }}</h5>
                                <span class="text-primary fw-medium">
                                    Explorer
                                    <i class="fa fa-arrow-right ms-1"></i>
                                </span>
                            </div>
                            <div class="d-flex mb-1">
                                <small class="border-end me-3 pe-3 text-muted">
                                    <i class="fa fa-hotel text-primary me-2"></i>
                                    {{ $city->hotels_count ?? 0 }}
                                    {{ Str::plural('Hôtel', $city->hotels_count ?? 0) }}
                                </small>
                                <small class="text-muted">
                                    <i class="fa fa-map-marker-alt text-primary me-2"></i>
                                    {{ $city->name }}, Maroc
                                </small>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fa fa-city fa-3x text-primary mb-3"></i>
                    <h5 class="text-muted">Aucune destination disponible pour le moment.</h5>
                    <p class="text-muted">Revenez bientôt pour découvrir de nouvelles destinations.</p>
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