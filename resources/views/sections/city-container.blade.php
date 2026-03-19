{{--
    sections/city-container.blade.php
    ─────────────────────────────────
    Displays all cities as clickable cards.
    Reuses the exact same card structure as room-container-brief.
    Data: $cities  (Collection of City models with hotels_count)
    Flow: Click card → cities/{city}/hotels
--}}

<!-- Destination Start -->
<div class="container-xxl py-4">
    <div class="container">

        {{-- Section heading — same structure as room section heading --}}
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title text-center text-primary text-uppercase">
                Destinations
            </h6>
            <h1 class="mb-5">
Explorez Nos
<span class="text-primary text-uppercase">Villes</span>
            </h1>
        </div>

        {{-- City cards grid — same column classes as room cards --}}
        <div class="row g-4">

            @forelse ($cities as $city)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="0.1s">

                    {{--
                        Card wrapper — identical class structure to room cards.
                        Entire card is a link so the click target is large.
                    --}}
                    <a href="{{ route('cities.hotels', $city) }}"
                       class="room-item shadow rounded overflow-hidden d-block text-decoration-none">

                        {{-- City image — same ratio wrapper as room image --}}
                        <div class="position-relative">
                            <img class="img-fluid w-100" style="height: 250px; object-fit: cover;"
                                 src="{{ $city->image ? (Str::startsWith($city->image, 'http') ? $city->image : asset($city->image)) : asset('img/cities/default.jpg') }}"
                                 alt="{{ $city->name }}">

                            {{-- Hotel count badge — replaces the room price tag --}}
                            <small class="position-absolute start-0 bottom-0 bg-primary text-white rounded-end
                                          py-1 px-3 mb-3">
                                {{ $city->hotels_count }}
                                {{ Str::plural('Hotel', $city->hotels_count) }}
                            </small>
                        </div>

                        {{-- Card body — same padding and structure as room cards --}}
                        <div class="p-4 mt-2">
                            <div class="d-flex justify-content-between mb-3">

                                {{-- City name --}}
                                <h5 class="mb-0 text-dark">{{ $city->name }}</h5>

                                {{-- "Explore" CTA — replaces the room price --}}
                                <span class="text-primary fw-medium">
Explorer
<i class="fa fa-arrow-right ms-1"></i>
                                </span>

                            </div>

                            {{-- Divider line — kept identical to room cards --}}
                            <div class="d-flex mb-1">
                                <small class="border-end me-3 pe-3 text-muted">
                                    <i class="fa fa-hotel text-primary me-2"></i>
                                    {{ $city->hotels_count }}
                                    {{ Str::plural('Hotel', $city->hotels_count) }}
                                </small>
                                <small class="text-muted">
                                    <i class="fa fa-map-marker-alt text-primary me-2"></i>
                                    {{ $city->name }}, Morocco
                                </small>
                            </div>

                        </div>
                    </a>

                </div>
            @empty
                {{-- Empty state — reuses same grid structure --}}
                <div class="col-12 text-center py-5">
                    <i class="fa fa-city fa-3x text-primary mb-3"></i>
                    <h5 class="text-muted">No destinations available yet.</h5>
                    <p class="text-muted">Check back soon for exciting destinations.</p>
                </div>
            @endforelse

        </div>
        {{-- end row --}}

    </div>
</div>
