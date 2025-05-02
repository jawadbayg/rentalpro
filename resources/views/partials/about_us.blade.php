@extends('layouts.app')

@section('content')
<style>
    .about-image-column {
        background-image: url('https://img.ge/i/W0CKG96.jpg');
        background-size: cover;
        background-position: center;
        height: 100vh;
    }

    .about-text-column {
        height: 100vh;
        display: flex;
        align-items: center;
        padding: 2rem;
    }
    .row{
        --bs-gutter-x : 0 !important;
    }
</style>

<div class="container-fluid px-0">
    <div class="row no-gutters">
        <!-- Text Column -->
        <div class="col-md-6 about-text-column">
            <div>
                <h1 class="mb-4">RentalPro</h1>
                <p class="lead">
                    At RentalPro, we believe renting a car should be simple, fast, and stress-free.
                    Whether you're planning a weekend getaway, a business trip, or need a reliable ride in the city,
                    we've got the perfect vehicle for you.
                </p>
                <p>
                    Founded with a passion for innovation and customer convenience, RentalPro is here to redefine the way you rent cars.
                    With an easy-to-use booking system, transparent pricing, and a wide variety of vehicles, we are your go-to car rental partner.
                </p>
                <p>
                    Our mission is to make mobility accessible, affordable, and enjoyable for everyone.
                    Drive with confidence – drive with RentalPro.
                </p>
            </div>
        </div>

        <div class="col-md-6 about-image-column"></div>
    </div>
</div>
@endsection
