@extends('layouts.app')

@section('content')
<div class="about-page">
    <section class="about-hero">
        <div class="about-hero__bg" aria-hidden="true"></div>
        <div class="container about-hero__inner">
            <div class="row align-items-center py-5 py-lg-0 about-hero__row">
                <div class="col-lg-7">
                    <p class="about-hero__eyebrow">Our story</p>
                    <h1 class="about-hero__title">Mobility made simple</h1>
                    <p class="about-hero__lead">
                        At Rental Pro, we believe renting a car should be simple, fast, and stress-free.
                        Whether you are planning a weekend trip, a business journey, or need a reliable ride in the city,
                        we are here to help you find the right vehicle.
                    </p>
                    <div class="about-hero__actions">
                        <a href="{{ url('/') }}#featured-fleet" class="btn btn-landing-primary btn-landing-lg">Browse fleet</a>
                        <a href="{{ route('login') }}" class="btn btn-landing-outline btn-landing-lg about-hero__btn-outline">Member login</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="about-split">
        <div class="container py-5 about-split__container">
            <div class="row g-4 g-xl-5 align-items-center">
                <div class="col-lg-6 order-lg-2">
                    <div class="about-split__image-wrap">
                        <img
                            src="https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&w=1200&q=80"
                            alt="Driving on the open road"
                            class="about-split__image"
                            loading="lazy"
                        >
                        <div class="about-split__badge">
                            <span class="about-split__badge-num">10+</span>
                            <span class="about-split__badge-text">Years focused on great rentals</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 order-lg-1">
                    <h2 class="about-section-title">Who we are</h2>
                    <p class="about-text">
                        Founded with a passion for innovation and customer convenience, Rental Pro exists to make booking
                        straightforward: clear steps, transparent pricing, and a curated range of vehicles so you can choose with confidence.
                    </p>
                    <p class="about-text mb-0">
                        Our mission is to make mobility accessible, affordable, and enjoyable. From compact city cars to spacious
                        family options, we work to keep every handover smooth and every journey a pleasure.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="about-values">
        <div class="container py-5">
            <div class="text-center mb-5">
                <h2 class="about-section-title about-section-title--center">What we stand for</h2>
                <p class="about-section-lead">Principles that guide how we serve drivers every day.</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="about-value-card">
                        <div class="about-value-card__icon"><i class="fas fa-handshake"></i></div>
                        <h3 class="about-value-card__title">Trust</h3>
                        <p class="about-value-card__text">Honest pricing and vehicles that are checked and ready—no surprises at pickup.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="about-value-card">
                        <div class="about-value-card__icon"><i class="fas fa-wand-magic-sparkles"></i></div>
                        <h3 class="about-value-card__title">Simplicity</h3>
                        <p class="about-value-card__text">A booking flow designed to be quick on any device, so you spend less time clicking and more time driving.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="about-value-card">
                        <div class="about-value-card__icon"><i class="fas fa-heart"></i></div>
                        <h3 class="about-value-card__title">Care</h3>
                        <p class="about-value-card__text">Support that treats every trip as important—because your plans depend on a car that works.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="about-cta">
        <div class="container py-5">
            <div class="about-cta__box text-center">
                <h2 class="about-cta__title">Ready to hit the road?</h2>
                <p class="about-cta__text mb-4">Explore available vehicles and book in a few steps.</p>
                <a href="{{ url('/') }}#featured-fleet" class="btn btn-landing-primary btn-landing-lg">View vehicles</a>
            </div>
        </div>
    </section>

    @include('partials.footer')
</div>
@endsection
