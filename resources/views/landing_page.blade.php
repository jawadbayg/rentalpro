@extends('layouts.app')

@section('content')
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/2.0.2/anime.min.js"></script>
    @if (Auth::check() && $userValidated == false)
    <div class="user_validation_msg">
        Please do your verification in order to access the Rental Pro Services! 
        <a href="{{ route('user.validation') }}" class="btn-outline-blank">Verify Now</a>
    </div>
    @endif

    @include('partials.section1', ['images' => [
        'https://img.ge/i/W0CKG96.jpg',
        'https://i.ibb.co/d4BqLhzJ/pexels-pixabay-164634.jpg',
        'https://i.ibb.co/gMmGPzQH/pexels-mikebirdy-112460.jpg',
    ]])

    <div class="container mt-5 main-page-container">
        <!-- <h2>Collection</h2> -->
        <div class="row">
            @foreach($fleets as $fleet)
                <div class="col-md-4 mb-4">
                    <a href="{{ route('vehicle.show', $fleet->id) }}" class="text-decoration-none text-dark">
                        <div class="card h-100 shadow-sm">
                            @if($fleet->images->count() > 0)
                                <img src="{{ asset('storage/' . $fleet->images->first()->image) }}" class="card-img-top" alt="Vehicle Image" style="height: 200px; object-fit: cover;">
                            @else
                                <img src="https://via.placeholder.com/400x200?text=No+Image" class="card-img-top" alt="No Image" style="height: 200px; object-fit: cover;">
                            @endif

                            <div class="card-body">
                                <h5 class="card-title">{{ $fleet->vehicle_name }}</h5>
                                <h6 class="card-subtitle mb-2 text-muted">{{ $fleet->vehicle_owner_name }}</h6>

                                <ul class="list-group list-group-flush mt-3">
                                    <li class="list-group-item"><strong>Type:</strong> {{ ucfirst($fleet->vehicle_type) }}</li>
                                </ul>
                            </div>
                            <div class="card-footer text-center">
                                <small class="text-muted">Registered: {{ \Carbon\Carbon::parse($fleet->registration_date)->format('d M, Y') }}</small>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
    <!-- Include the footer -->
@include('partials.footer')

    <script>
        window.addEventListener('scroll', function() {
            var validationMsg = document.querySelector('.user_validation_msg');
            var navbarHeight = document.querySelector('.navbar')?.offsetHeight || 70; 
            if (window.scrollY > navbarHeight) {
                validationMsg.classList.add('sticky');
            } else {
                validationMsg.classList.remove('sticky');
            }
        });
    </script>
<script>
    var ml4 = {};
ml4.opacityIn = [0,1];
ml4.scaleIn = [0.2, 1];
ml4.scaleOut = 3;
ml4.durationIn = 800;
ml4.durationOut = 600;
ml4.delay = 500;

anime.timeline({loop: true})
  .add({
    targets: '.ml4 .letters-1',
    opacity: ml4.opacityIn,
    scale: ml4.scaleIn,
    duration: ml4.durationIn
  }).add({
    targets: '.ml4 .letters-1',
    opacity: 0,
    scale: ml4.scaleOut,
    duration: ml4.durationOut,
    easing: "easeInExpo",
    delay: ml4.delay
  }).add({
    targets: '.ml4 .letters-2',
    opacity: ml4.opacityIn,
    scale: ml4.scaleIn,
    duration: ml4.durationIn
  }).add({
    targets: '.ml4 .letters-2',
    opacity: 0,
    scale: ml4.scaleOut,
    duration: ml4.durationOut,
    easing: "easeInExpo",
    delay: ml4.delay
  }).add({
    targets: '.ml4 .letters-3',
    opacity: ml4.opacityIn,
    scale: ml4.scaleIn,
    duration: ml4.durationIn
  }).add({
    targets: '.ml4 .letters-3',
    opacity: 0,
    scale: ml4.scaleOut,
    duration: ml4.durationOut,
    easing: "easeInExpo",
    delay: ml4.delay
  }).add({
    targets: '.ml4',
    opacity: 0,
    duration: 500,
    delay: 500
  });
</script>

@endsection