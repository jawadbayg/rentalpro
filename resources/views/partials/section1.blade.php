@php
    $heroImage = $heroImage ?? 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?auto=format&fit=crop&w=1920&q=80';
@endphp
<section class="landing-hero" style="--hero-bg-image: url('{{ $heroImage }}');">
    <div class="landing-hero__overlay"></div>
    <div class="container landing-hero__content">
        <div class="row justify-content-center">
            <div class="col-lg-9 col-xl-8 text-center">
                <p class="landing-hero__eyebrow">Premium car rental</p>
                <h1 class="landing-hero__title">Enjoy Your Ride!</h1>
                <p class="landing-hero__subtitle">Reserve your vehicle now and enjoy a smooth journey.</p>
                <div class="landing-hero__actions">
                    <a href="#featured-fleet" class="btn btn-landing-primary btn-landing-lg">Book Now</a>
                    <a href="#why-us" class="btn btn-landing-outline btn-landing-lg">Why Rental Pro</a>
                </div>
            </div>
        </div>
    </div>
</section>
