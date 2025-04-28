@extends('layouts.app')

@section('content')
<div class="container cards_container">
    <div class="row">
        <div class="col-md-3">
            <div class="card mb-4 left_container">
                <div class="card-header text-center">
                    Provider Information
                </div>
                <div class="card-body text-center">
                @if($fleet->user->profile && $fleet->user->profile->profile_picture)
                    <img src="{{ asset('storage/' . $fleet->user->profile->profile_picture) }}" alt="User Image" class="img-fluid rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                @else
                    <img src="{{ asset('default-user.png') }}" alt="Default User" class="img-fluid rounded-circle mb-3" style="width: 100px; height: 100px; object-fit: cover;">
                @endif
                    <p><strong>{{ $fleet->user->name }}</strong></p>
                    <p><strong>Email:</strong> {{ $fleet->user->email }}</p>
                    <p><strong>Address:</strong>{{ $fleet->address }}</p>
                    <button class="btn-outline"> Contact</button>
                    <button class="btn-black mt-3" data-bs-toggle="modal" data-bs-target="#bookingModal">Book Now</button>

                </div>
            </div>
        </div>

        <!-- Right Side (col-9) -->
        <div class="col-md-9">
            <div class="card right_container">
                <div class="card-header">
                    Vehicle Information
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <p><strong>Vehicle Name:</strong> {{ $fleet->vehicle_name }}</p>
                            <p><strong>Type:</strong> {{ $fleet->vehicle_type }}</p>
                            <p><strong>License Plate:</strong> {{ $fleet->license_plate }}</p>
                            <p><strong>Charges Per Day:</strong> £{{ $fleet->price_per_day }}</p>
                            <!-- <p><strong>Year:</strong> {{ $fleet->year }}</p> -->
                        </div>
                        <div class="col-md-6 text-center ">
                            @if($fleet->images->count() > 0)
                            <img src="{{ asset('storage/' . $fleet->images->first()->image) }}" class="card-img-top right_container_image" alt="Vehicle Image" style="height: 200px; object-fit: cover;">
                            @else
                                <img src="{{ asset('default-vehicle.png') }}" alt="Default Vehicle" class="img-fluid rounded" style="max-width: 100%; height: auto;">
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  

<!-- Explore More Section -->
<div class="container mt-5 explore_more_section">
    <h2 class="mb-4 text-center">Explore More!</h2>
    <div class="row">
        @foreach($fleets as $item)
            <div class="col-md-4 mb-4">
                <div class="card explore_card">
                    @if($item->images && $item->images->first())
                        <img src="{{ asset('storage/' . $item->images->first()->image) }}" class="card-img-top" alt="Vehicle Image" style="height: 200px; object-fit: cover;">
                    @else
                        <img src="{{ asset('default-vehicle.png') }}" class="card-img-top" alt="Default Vehicle" style="height: 200px; object-fit: cover;">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{ $item->vehicle_name }}</h5>
                        <p class="card-text">{{ $item->vehicle_type }}</p>
                        <a href="{{ route('vehicle.show', $item->id) }}" class="btn btn-black">View Details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>





<!-- Booking Modal -->
<div class="modal fade" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="bookingModalLabel">Book Vehicle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">

        @if(Auth::check())
          <form action="" method="POST">
            @csrf

            <input type="hidden" name="fleet_id" value="{{ $fleet->id }}">

            <div class="mb-3">
                <label for="from_date" class="form-label">From Date</label>
                <input type="text" id="from_date" name="from_date" class="form-control datepicker" required>
            </div>

            <div class="mb-3">
                <label for="to_date" class="form-label">To Date</label>
                <input type="text" id="to_date" name="to_date" class="form-control datepicker" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Booking Summary</label>
                <div class="d-flex align-items-center">
                    <div class="input-group me-2" style="max-width: 150px;">
                        <span class="input-group-text">£</span>
                        <input type="text" id="charges_per_day" class="form-control" value="{{ $fleet->price_per_day }}" readonly>
                    </div>
                    <span class="mx-2 fw-bold">×</span>
                    <div class="input-group me-2" style="max-width: 100px;">
                        <input type="text" id="days" class="form-control" value="0" readonly>
                        <span class="input-group-text">Days</span>
                    </div>
                    <span class="mx-2 fw-bold">=</span>
                    <div class="input-group" style="max-width: 180px;">
                        <span class="input-group-text">£</span>
                        <input type="text" id="total_cost" class="form-control" value="0" readonly>
                    </div>
                </div>
            </div>

            <div class="text-end">
              <button type="submit" class="btn-black">Confirm Booking</button>
            </div>

          </form>

        @else

          <div class="text-center">
            <p class="mb-4">Please login to book this vehicle.</p>
            <a href="{{ route('login') }}" class="btn-black">Login</a>
          </div>

        @endif

      </div>

    </div>
  </div>
</div>


<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    flatpickr(".datepicker", {
        altInput: true,
        altFormat: "F j, Y",
        dateFormat: "Y-m-d",
        minDate: "today",
        onChange: function(selectedDates, dateStr, instance) {
            calculateTotal();
        }
    });
</script>

<script>
    function calculateTotal() {
        const fromDateInput = document.getElementById('from_date');
        const toDateInput = document.getElementById('to_date');
        const chargesPerDayInput = document.getElementById('charges_per_day');
        const totalCostInput = document.getElementById('total_cost');
        const daysInput = document.getElementById('days');

        const fromDate = new Date(fromDateInput.value);
        const toDate = new Date(toDateInput.value);
        const chargesPerDay = parseFloat(chargesPerDayInput.value);

        if (fromDateInput.value && toDateInput.value && toDate >= fromDate) {
            const timeDiff = toDate.getTime() - fromDate.getTime();
            const days = Math.ceil(timeDiff / (1000 * 3600 * 24));
            const total = days * chargesPerDay;

            daysInput.value = days;
            totalCostInput.value = total.toFixed(2);
        } else {
            daysInput.value = 0;
            totalCostInput.value = 0;
        }
    }
</script>


@endsection
