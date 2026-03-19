<!-- Hotel List Start -->
<div class="container-xxl py-5">
    <div class="container">
        <div class="text-center wow fadeInUp" data-wow-delay="0.1s">
            <h6 class="section-title text-center text-primary text-uppercase">Our Hotels</h6>
            <h1 class="mb-5">Hotels in <span class="text-primary text-uppercase">{{ $city->name }}</span></h1>
        </div>
        <div class="row g-4">
            @forelse($hotels as $hotel)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="{{ $loop->iteration/10 }}s">
                    <a href="{{ route('hotels.show', $hotel) }}" class="room-item shadow rounded overflow-hidden d-block text-decoration-none">
                        <div class="position-relative">
                            <img class="img-fluid w-100" style="height: 250px; object-fit: cover;" src="{{ $hotel->image ? (Str::startsWith($hotel->image, 'http') ? $hotel->image : asset($hotel->image)) : asset('img/hotels/default.jpg') }}" alt="{{ $hotel->name }}">
                            <small class="position-absolute start-0 top-0 bg-primary text-white rounded py-1 px-3 ms-4"><i class="fa fa-star text-white me-1"></i>{{ number_format($hotel->rating, 1) }}</small>
                        </div>
                        <div class="p-4 mt-2">
                            <div class="d-flex justify-content-between mb-3">
                                <h5 class="mb-0 text-dark">{{ $hotel->name }}</h5>
                            </div>
                            <div class="d-flex mb-3">
                                <small class="border-end me-3 pe-3 text-muted"><i class="fa fa-map-marker-alt text-primary me-2"></i>{{ $hotel->location ?? 'Central Area' }}</small>
                                <small class="text-muted"><i class="fa fa-bed text-primary me-2"></i>{{ $hotel->rooms_count ?? 0 }} {{ Str::plural('Room', $hotel->rooms_count ?? 0) }}</small>
                            </div>
                            <p class="text-body mb-3">{{ Str::limit($hotel->description, 100) }}</p>
                            <div class="d-flex justify-content-between">
                                <span class="btn btn-sm btn-primary rounded py-2 px-4">View Hotel</span>
                            </div>
                        </div>
                    </a>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="fa fa-hotel fa-3x text-primary mb-3"></i>
                    <h5 class="text-muted">No hotels available in this city yet.</h5>
                </div>
            @endforelse
        </div>
    </div>
</div>
<!-- Hotel List End -->
