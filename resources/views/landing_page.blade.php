@extends('layouts.app')

@section('content')

    @include('partials.section1', ['images' => [
        'https://img.ge/i/W0CKG96.jpg',
        'https://i.ibb.co/d4BqLhzJ/pexels-pixabay-164634.jpg',
        'https://i.ibb.co/gMmGPzQH/pexels-mikebirdy-112460.jpg',
    ]])

    <section class="text-center py-16 bg-gray-100 dark:bg-gray-900">
    <div class="container mt-5">
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
    </section>
@endsection