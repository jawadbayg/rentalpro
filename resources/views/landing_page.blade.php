@extends('layouts.app')

@section('content')
    @if (Auth::check() && $userValidated == false)
    <div class="user_validation_msg">
        Please do your verification in order to access the Rental Pro Services!
        <a href="{{ route('user.validation') }}" class="btn-outline-blank">Verify Now</a>
    </div>
    @endif

    @include('partials.section1')

    <section class="landing-section landing-why" id="why-us">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="landing-section__title">Why Choose Us</h2>
                <p class="landing-section__lead">Everything you need for a stress-free rental experience.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-6 col-lg-3">
                    <div class="landing-benefit-card">
                        <div class="landing-benefit-card__icon"><i class="fas fa-shield-halved"></i></div>
                        <h3 class="landing-benefit-card__title">Verified fleet</h3>
                        <p class="landing-benefit-card__text">Every vehicle is inspected and maintained to high standards before it hits the road.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="landing-benefit-card">
                        <div class="landing-benefit-card__icon"><i class="fas fa-bolt"></i></div>
                        <h3 class="landing-benefit-card__title">Quick booking</h3>
                        <p class="landing-benefit-card__text">Reserve online in minutes. Clear pricing with no hidden surprises at pickup.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="landing-benefit-card">
                        <div class="landing-benefit-card__icon"><i class="fas fa-headset"></i></div>
                        <h3 class="landing-benefit-card__title">24/7 support</h3>
                        <p class="landing-benefit-card__text">Our team is here when you need help—before, during, and after your trip.</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="landing-benefit-card">
                        <div class="landing-benefit-card__icon"><i class="fas fa-tags"></i></div>
                        <h3 class="landing-benefit-card__title">Fair daily rates</h3>
                        <p class="landing-benefit-card__text">Competitive per-day pricing so you can plan your budget with confidence.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="landing-section landing-fleet" id="featured-fleet">
        <div class="container main-page-container landing-main">
            <div class="text-center mb-5">
                <h2 class="landing-section__title">Featured Cars</h2>
                <p class="landing-section__lead">Pick a ride that fits your plans—compact, spacious, or something in between.</p>
            </div>

            <div class="row g-4">
                @forelse($fleets as $fleet)
                    @php
                        $imgUrl = $fleet->images->count() > 0
                            ? asset('storage/' . $fleet->images->first()->image)
                            : 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=900&q=80';
                        $price = $fleet->price_per_day;
                        $priceLabel = $price !== null && (float) $price > 0
                            ? format_pkr($price, 0)
                            : 'Ask for quote';
                        $seats = $fleet->no_of_seats;
                        $fuel = $fleet->fuel_type ?: '—';
                        $vtLower = strtolower($fleet->vehicle_type ?? '');
                        $transmission = str_contains($vtLower, 'manual') ? 'Manual' : 'Automatic';
                    @endphp
                    <div class="col-md-6 col-xl-4">
                        <article class="fleet-card">
                            <a href="{{ route('vehicle.show', $fleet->id) }}" class="fleet-card__image-link">
                                <div class="fleet-card__image-wrap">
                                    <img src="{{ $imgUrl }}" class="fleet-card__image" alt="{{ $fleet->vehicle_name }}">
                                </div>
                            </a>
                            <div class="fleet-card__body">
                                <h3 class="fleet-card__title">{{ $fleet->vehicle_name }}</h3>
                                <p class="fleet-card__owner"><i class="fas fa-user-tie me-1 opacity-75"></i>{{ $fleet->vehicle_owner_name }}</p>
                                <div class="fleet-card__features">
                                    <span class="fleet-chip" title="Climate control"><i class="fas fa-snowflake"></i> A/C</span>
                                    <span class="fleet-chip" title="Transmission"><i class="fas fa-gears"></i> {{ $transmission }}</span>
                                    <span class="fleet-chip" title="Seating"><i class="fas fa-user-group"></i> {{ $seats ? $seats . ' seats' : '—' }}</span>
                                    <span class="fleet-chip" title="Fuel"><i class="fas fa-gas-pump"></i> {{ $fuel }}</span>
                                </div>
                                <div class="fleet-card__footer">
                                    <div>
                                        <span class="fleet-card__price-label">From</span>
                                        <span class="fleet-card__price">{{ $priceLabel }}<span class="fleet-card__price-unit">/day</span></span>
                                    </div>
                                    <a href="{{ route('vehicle.show', $fleet->id) }}" class="btn btn-fleet-book">Book</a>
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="landing-empty-fleet text-center py-5">
                            <i class="fas fa-car-rear fa-3x mb-3 opacity-50"></i>
                            <p class="mb-0 fs-5">No vehicles available right now. Please check back soon.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="landing-section landing-testimonials" id="testimonials">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="landing-section__title">What Our Customers Say</h2>
                <p class="landing-section__lead">Real feedback from drivers who chose Rental Pro.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <blockquote class="testimonial-card">
                        <div class="testimonial-card__stars" aria-hidden="true">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-card__quote">“Booking took two minutes and the car was spotless. Best rental experience I’ve had this year.”</p>
                        <footer class="testimonial-card__author">
                            <strong>Sarah M.</strong>
                            <span>Weekend trip</span>
                        </footer>
                    </blockquote>
                </div>
                <div class="col-md-4">
                    <blockquote class="testimonial-card">
                        <div class="testimonial-card__stars" aria-hidden="true">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star-half-stroke"></i>
                        </div>
                        <p class="testimonial-card__quote">“Clear pricing and helpful support when I needed to extend my booking. Highly recommend.”</p>
                        <footer class="testimonial-card__author">
                            <strong>James T.</strong>
                            <span>Business travel</span>
                        </footer>
                    </blockquote>
                </div>
                <div class="col-md-4">
                    <blockquote class="testimonial-card">
                        <div class="testimonial-card__stars" aria-hidden="true">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="testimonial-card__quote">“Great selection of vehicles and a smooth handover. I’ll use Rental Pro again for sure.”</p>
                        <footer class="testimonial-card__author">
                            <strong>Elena R.</strong>
                            <span>Family holiday</span>
                        </footer>
                    </blockquote>
                </div>
            </div>
        </div>
    </section>

    @include('partials.footer')

    <script>
        window.addEventListener('scroll', function() {
            var validationMsg = document.querySelector('.user_validation_msg');
            if (!validationMsg) return;
            var navbarHeight = document.querySelector('.navbar')?.offsetHeight || 70;
            if (window.scrollY > navbarHeight) {
                validationMsg.classList.add('sticky');
            } else {
                validationMsg.classList.remove('sticky');
            }
        });
    </script>
@endsection
