@extends('layouts.app')

@section('content')
@php
    $vtLower = strtolower($fleet->vehicle_type ?? '');
    $transmission = str_contains($vtLower, 'manual') ? 'Manual' : 'Automatic';
    $heroUrl = $fleet->images->count() > 0
        ? asset('storage/' . $fleet->images->first()->image)
        : 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=1200&q=80';
    $priceRaw = $fleet->price_per_day;
    $priceDisplay = $priceRaw !== null && (float) $priceRaw > 0
        ? '£' . number_format((float) $priceRaw, 0)
        : 'Ask for quote';
    $providerAddress = $fleet->user->fpDetail?->address ?? '—';
@endphp

<div class="vehicle-show-page">
    <div class="container py-4 py-lg-5">
        <a href="{{ url('/') }}#featured-fleet" class="vehicle-show-back">
            <i class="fas fa-arrow-left-long me-2"></i>Back to fleet
        </a>

        <div class="row g-4 g-xl-5 align-items-start">
            <div class="col-lg-8">
                <div class="vehicle-show-gallery mb-4">
                    <div class="vehicle-show-hero-frame">
                        <img
                            src="{{ $heroUrl }}"
                            alt="{{ $fleet->vehicle_name }}"
                            class="vehicle-show-hero-img"
                            id="vehicle-show-main-image"
                        >
                    </div>
                    @if($fleet->images->count() > 1)
                        <div class="vehicle-show-thumbs" role="group" aria-label="Vehicle photos">
                            @foreach($fleet->images as $idx => $img)
                                @php $thumbSrc = asset('storage/' . $img->image); @endphp
                                <button type="button" class="vehicle-show-thumb{{ $idx === 0 ? ' is-active' : '' }}" data-src="{{ $thumbSrc }}">
                                    <img src="{{ $thumbSrc }}" alt="">
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="vehicle-show-heading">
                    <div class="d-flex flex-wrap align-items-start justify-content-between gap-3 mb-3">
                        <div>
                            <h1 class="vehicle-show-title">{{ $fleet->vehicle_name }}</h1>
                            <p class="vehicle-show-meta mb-0">
                                <span class="me-3"><i class="fas fa-id-card me-1 opacity-75"></i>{{ $fleet->license_plate }}</span>
                                <span><i class="fas fa-user-tie me-1 opacity-75"></i>{{ $fleet->vehicle_owner_name }}</span>
                            </p>
                        </div>
                        <div class="vehicle-show-price-badge">
                            <span class="vehicle-show-price-label">From</span>
                            <span class="vehicle-show-price-value">{{ $priceDisplay }}@if($priceRaw !== null && (float) $priceRaw > 0)<span class="vehicle-show-price-unit">/day</span>@endif</span>
                        </div>
                    </div>
                    <div class="fleet-card__features vehicle-show-chips">
                        <span class="fleet-chip"><i class="fas fa-snowflake"></i> A/C</span>
                        <span class="fleet-chip"><i class="fas fa-gears"></i> {{ $transmission }}</span>
                        @if($fleet->no_of_seats)
                            <span class="fleet-chip"><i class="fas fa-user-group"></i> {{ $fleet->no_of_seats }} seats</span>
                        @endif
                        @if($fleet->fuel_type)
                            <span class="fleet-chip"><i class="fas fa-gas-pump"></i> {{ $fleet->fuel_type }}</span>
                        @endif
                    </div>
                </div>

                <div class="vehicle-show-spec-panel">
                    <h2 class="vehicle-show-spec-title">Specifications</h2>
                    <div class="row g-3">
                        <div class="col-sm-6 col-md-4">
                            <div class="vehicle-spec-item">
                                <span class="vehicle-spec-item__icon"><i class="fas fa-car-side"></i></span>
                                <div>
                                    <span class="vehicle-spec-item__label">Type</span>
                                    <span class="vehicle-spec-item__value">{{ $fleet->vehicle_type }}</span>
                                </div>
                            </div>
                        </div>
                        @if($fleet->no_of_doors)
                        <div class="col-sm-6 col-md-4">
                            <div class="vehicle-spec-item">
                                <span class="vehicle-spec-item__icon"><i class="fas fa-door-open"></i></span>
                                <div>
                                    <span class="vehicle-spec-item__label">Doors</span>
                                    <span class="vehicle-spec-item__value">{{ $fleet->no_of_doors }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($fleet->no_of_bags)
                        <div class="col-sm-6 col-md-4">
                            <div class="vehicle-spec-item">
                                <span class="vehicle-spec-item__icon"><i class="fas fa-suitcase-rolling"></i></span>
                                <div>
                                    <span class="vehicle-spec-item__label">Bag space</span>
                                    <span class="vehicle-spec-item__value">{{ $fleet->no_of_bags }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($fleet->color)
                        <div class="col-sm-6 col-md-4">
                            <div class="vehicle-spec-item">
                                <span class="vehicle-spec-item__icon"><i class="fas fa-palette"></i></span>
                                <div>
                                    <span class="vehicle-spec-item__label">Color</span>
                                    <span class="vehicle-spec-item__value">{{ $fleet->color }}</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($fleet->mileage)
                        <div class="col-sm-6 col-md-4">
                            <div class="vehicle-spec-item">
                                <span class="vehicle-spec-item__icon"><i class="fas fa-gauge-high"></i></span>
                                <div>
                                    <span class="vehicle-spec-item__label">Mileage</span>
                                    <span class="vehicle-spec-item__value">{{ $fleet->mileage }} km/L</span>
                                </div>
                            </div>
                        </div>
                        @endif
                        <div class="col-sm-6 col-md-4">
                            <div class="vehicle-spec-item">
                                <span class="vehicle-spec-item__icon"><i class="fas fa-calendar-check"></i></span>
                                <div>
                                    <span class="vehicle-spec-item__label">Registered</span>
                                    <span class="vehicle-spec-item__value">{{ \Carbon\Carbon::parse($fleet->registration_date)->format('d M Y') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <aside class="vehicle-provider-card vehicle-provider-card--sticky">
                    <div class="vehicle-provider-card__price">
                        <span class="vehicle-provider-card__price-label">Daily rate</span>
                        <span class="vehicle-provider-card__price-num">{{ $priceDisplay }}</span>
                    </div>
                    <hr class="vehicle-provider-card__rule">
                    <h3 class="vehicle-provider-card__heading">Provider</h3>
                    <div class="text-center mb-3">
                        @if($fleet->user->profile && $fleet->user->profile->profile_picture)
                            <img src="{{ asset('storage/' . $fleet->user->profile->profile_picture) }}" alt="" class="vehicle-provider-card__avatar">
                        @else
                            <img src="{{ asset('default-user.png') }}" alt="" class="vehicle-provider-card__avatar">
                        @endif
                        <p class="vehicle-provider-card__name mb-1">{{ $fleet->user->name }}</p>
                        <p class="vehicle-provider-card__detail mb-1"><i class="fas fa-envelope me-2 opacity-75"></i>{{ $fleet->user->email }}</p>
                        <p class="vehicle-provider-card__detail mb-0"><i class="fas fa-location-dot me-2 opacity-75"></i>{{ $providerAddress }}</p>
                    </div>
                    @if ($already_booked == true)
                        <button type="button" class="btn vehicle-show-btn-disabled w-100" disabled aria-disabled="true">Already booked</button>
                    @else
                        <button type="button" class="btn btn-vehicle-book w-100" data-bs-toggle="modal" data-bs-target="#bookingModal">Book now</button>
                    @endif
                </aside>
            </div>
        </div>
    </div>

    @if($fleets->count() > 0)
    <section class="vehicle-explore-more">
        <div class="container">
            <div class="text-center mb-4">
                <h2 class="landing-section__title">Explore more</h2>
                <p class="landing-section__lead">You might also like these vehicles.</p>
            </div>
            <div class="row g-4">
                @foreach($fleets as $item)
                    @php
                        $itemImg = ($item->images && $item->images->first())
                            ? asset('storage/' . $item->images->first()->image)
                            : 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?auto=format&fit=crop&w=900&q=80';
                        $itemPrice = $item->price_per_day;
                        $itemPriceLabel = $itemPrice !== null && (float) $itemPrice > 0
                            ? '£' . number_format((float) $itemPrice, 0)
                            : 'Ask for quote';
                        $itemVt = strtolower($item->vehicle_type ?? '');
                        $itemTrans = str_contains($itemVt, 'manual') ? 'Manual' : 'Automatic';
                    @endphp
                    <div class="col-md-4">
                        <article class="fleet-card">
                            <a href="{{ route('vehicle.show', $item->id) }}" class="fleet-card__image-link">
                                <div class="fleet-card__image-wrap">
                                    <img src="{{ $itemImg }}" class="fleet-card__image" alt="{{ $item->vehicle_name }}">
                                </div>
                            </a>
                            <div class="fleet-card__body">
                                <h3 class="fleet-card__title">{{ $item->vehicle_name }}</h3>
                                <p class="fleet-card__owner"><i class="fas fa-user-tie me-1 opacity-75"></i>{{ $item->vehicle_owner_name }}</p>
                                <div class="fleet-card__features">
                                    <span class="fleet-chip"><i class="fas fa-snowflake"></i> A/C</span>
                                    <span class="fleet-chip"><i class="fas fa-gears"></i> {{ $itemTrans }}</span>
                                </div>
                                <div class="fleet-card__footer">
                                    <div>
                                        <span class="fleet-card__price-label">From</span>
                                        <span class="fleet-card__price">{{ $itemPriceLabel }}@if($itemPrice !== null && (float) $itemPrice > 0)<span class="fleet-card__price-unit">/day</span>@endif</span>
                                    </div>
                                    <a href="{{ route('vehicle.show', $item->id) }}" class="btn btn-fleet-book">View</a>
                                </div>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @include('partials.footer')
</div>

<!-- Booking Modal -->
<div class="modal fade vehicle-booking-modal" id="bookingModal" tabindex="-1" aria-labelledby="bookingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered vehicle-show-booking-dialog">
    <div class="modal-content vehicle-modal-content">

      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title vehicle-modal-title" id="bookingModalLabel">Book vehicle</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body pt-2">

      @if(Auth::check())

        @php
            $validation = \App\Models\UserValidation::where('user_id', Auth::id())->first();
        @endphp

        @if($validation && $validation->status === 'approved')
            <form action="{{ route('bookings.store') }}" method="POST">
                @csrf

                <input type="hidden" name="fp_id" value="{{ $fleet->user_id }}">
                <input type="hidden" name="fleet_id" value="{{ $fleet->id }}">
                <input type="hidden" name="customer_id" value="{{ Auth::id() }}">
                <input type="hidden" name="payment_status" value="pending">
                <input type="hidden" name="total_price" id="hidden_total_price">

                <div class="mb-3">
                  <label for="from_date" class="form-label">From date <span class="text-danger">*</span></label>
                  <input type="text" id="from_date" name="from_date" class="form-control datepicker vehicle-modal-input" required autocomplete="off">
                  <div id="from_date_error" class="text-danger small mt-1"></div>
                </div>


                <div class="mb-3">
                  <label for="to_date" class="form-label">To date <span class="text-danger">*</span></label>
                  <input type="text" id="to_date" name="to_date" class="form-control datepicker vehicle-modal-input" required autocomplete="off">
                  <div id="to_date_error" class="text-danger small mt-1"></div>
                </div>


                <div class="mb-3">
                  <label class="form-label">Booking summary</label>
                  <div class="d-flex align-items-center flex-wrap gap-2">
                    <div class="input-group" style="max-width: 150px;">
                      <span class="input-group-text">£</span>
                      <input type="text" id="charges_per_day" class="form-control" value="{{ $fleet->price_per_day }}" readonly>
                    </div>
                    <span class="fw-bold">×</span>
                    <div class="input-group" style="max-width: 110px;">
                      <input type="text" id="days" class="form-control" value="0" readonly>
                      <span class="input-group-text">Days</span>
                    </div>
                    <span class="fw-bold">=</span>
                    <div class="input-group" style="max-width: 180px;">
                      <span class="input-group-text">£</span>
                      <input type="text" id="total_cost" class="form-control" value="0" readonly>
                    </div>
                  </div>
                </div>


                <div class="text-end">
                  <button type="button" class="btn btn-next-step btn-vehicle-modal-next" onclick="openConfirmBookingModal()">Next step</button>
                </div>

            </form>

                @elseif($validation && $validation->status === 'pending')

                    <div class="text-center py-3">
                        <p class="mb-0">Please wait — your verification is in progress.</p>
                    </div>

                @else

                    <div class="text-center py-3">
                        <p class="mb-4">Your account is not verified. Complete verification to book a vehicle.</p>
                    </div>
                @endif

            @else

            <div class="text-center py-3">
                <p class="mb-4">Please log in to book this vehicle.</p>
                <a href="{{ route('login') }}" class="btn btn-vehicle-book">Login</a>
            </div>
        @endif
      </div>

    </div>
  </div>
</div>


<div class="modal fade vehicle-booking-modal" id="confirmBookingModal" tabindex="-1" aria-labelledby="confirmBookingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content vehicle-modal-content">

    <div class="modal-header border-0 pb-0">
      <div class="d-flex align-items-center">
        <button type="button" class="go-back-modal-btn me-2 btn btn-link p-0 text-decoration-none" onclick="goBackToBooking()" aria-label="Back">
          <i class="fa fa-arrow-left"></i>
        </button>
        <h5 class="modal-title vehicle-modal-title mb-0" id="confirmBookingModalLabel">Confirm booking</h5>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>


      <div class="modal-body text-center pt-2">
      <p class="mb-2">Your payment due date is <strong><span id="due-date-display">[Not Selected]</span></strong></p>

        <div class="d-flex justify-content-center gap-3">
          <button type="button" class="btn btn-vehicle-modal-next" onclick="openPayLaterModal()">Next step</button>
        </div>
      </div>

    </div>
  </div>
</div>


<div class="modal fade vehicle-booking-modal" id="payLaterModal" tabindex="-1" aria-labelledby="payLaterModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content vehicle-modal-content">

    <div class="modal-header border-0 pb-0">
      <div class="d-flex align-items-center">
        <button type="button" class="go-back-modal-btn me-2 btn btn-link p-0 text-decoration-none" onclick="goBackToSecondModal()" aria-label="Back"><i class="fa fa-arrow-left"></i></button>
        <h5 class="modal-title vehicle-modal-title mb-0" id="payLaterModalLabel">Confirm your booking</h5>
      </div>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

      <div class="modal-body pt-2">
        <h5 class="mb-4 vehicle-modal-subtitle">Booking details</h5>
        
        <table class="table vehicle-modal-table">
          <thead>
            <tr>
              <th>From date</th>
              <th>To date</th>
              <th>Price</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td id="pay_later_from_date"></td>
              <td id="pay_later_to_date"></td>
              <td id="pay_later_price"></td>
            </tr>
          </tbody>
        </table>

        <div class="text-center">
          <button type="button" class="btn btn-vehicle-modal-next" onclick="confirmPayLaterBooking()">Confirm</button>
        </div>
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
    document.querySelectorAll('.vehicle-show-thumb').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var src = this.getAttribute('data-src');
            var main = document.getElementById('vehicle-show-main-image');
            if (main && src) main.src = src;
            document.querySelectorAll('.vehicle-show-thumb').forEach(function(b) { b.classList.remove('is-active'); });
            this.classList.add('is-active');
        });
    });
</script>
<script>
    const fromDateInput = document.getElementById('from_date');
    const toDateInput = document.getElementById('to_date');
    const nextStepButton = document.querySelector('.btn-next-step');

    if (fromDateInput) fromDateInput.addEventListener('change', function () {
        checkDateAvailability(this.value, 'from');
    });

    if (toDateInput) toDateInput.addEventListener('change', function () {
        checkDateAvailability(this.value, 'to');
    });

    function checkDateAvailability(date, type) {
    const urlParts = window.location.pathname.split('/');
    const vehicleId = urlParts[urlParts.length - 1]; 

    const payload = {
        id: vehicleId, 
        from_date: fromDateInput.value,
        to_date: toDateInput.value
    };

    fetch('/check-date', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify(payload)
    })
    .then(response => response.json())
    .then(data => {
        const errorDivId = type === 'from' ? 'from_date_error' : 'to_date_error';
        const errorDiv = document.getElementById(errorDivId);

        if (!data.available) {
            errorDiv.textContent = data.message;
        } else {
            errorDiv.textContent = '';
        }

        toggleNextStepButton();
    })
    .catch(error => {
        console.error('Error checking date availability:', error);
    });
}

    function toggleNextStepButton() {
        if (!nextStepButton) return;
        const fromError = document.getElementById('from_date_error').textContent.trim();
        const toError = document.getElementById('to_date_error').textContent.trim();
        if (fromError || toError) {
            nextStepButton.disabled = true;
            nextStepButton.classList.add('disabled');
        } else {
            nextStepButton.disabled = false;
            nextStepButton.classList.remove('disabled');
        }
    }
</script>

<script>
    function calculateTotal() {
        const fromDateInput = document.getElementById('from_date');
        const toDateInput = document.getElementById('to_date');
        const chargesPerDayInput = document.getElementById('charges_per_day');
        const totalCostInput = document.getElementById('total_cost');
        const daysInput = document.getElementById('days');
        const hiddenTotalInput = document.getElementById('hidden_total_price');

        if (!fromDateInput || !toDateInput || !chargesPerDayInput) return;

        const fromDate = new Date(fromDateInput.value);
        const toDate = new Date(toDateInput.value);
        const chargesPerDay = parseFloat(chargesPerDayInput.value);

        if (fromDateInput.value && toDateInput.value && toDate >= fromDate) {
            const timeDiff = toDate.getTime() - fromDate.getTime();
            const days = Math.ceil(timeDiff / (1000 * 3600 * 24));
            const total = days * chargesPerDay;

            daysInput.value = days;
            totalCostInput.value = total.toFixed(2);
            hiddenTotalInput.value = total.toFixed(2);
        } else {
            daysInput.value = 0;
            totalCostInput.value = 0;
            hiddenTotalInput.value = 0;
        }
    }

</script>
<script>
  function openConfirmBookingModal() {
    if (!validateBookingForm()) {
        return;
    }
    const toDate = document.getElementById('to_date').value;
    const formattedDueDate = new Date(toDate).toLocaleDateString(undefined, {
      year: 'numeric',
      month: 'long',
      day: 'numeric'
    });
    document.getElementById('due-date-display').textContent = formattedDueDate;
  
    let bookingModalEl = document.getElementById('bookingModal');
    let bookingModal = bootstrap.Modal.getInstance(bookingModalEl);

    if (bookingModal) {
      bookingModal.hide();
    }

    setTimeout(() => {
      document.querySelectorAll('.modal-backdrop').forEach(el => el.remove()); 

      let confirmModal = new bootstrap.Modal(document.getElementById('confirmBookingModal'));
      confirmModal.show();
    }, 300);
  }

  function goBackToBooking() {
    let confirmModalEl = document.getElementById('confirmBookingModal');
    let confirmModal = bootstrap.Modal.getInstance(confirmModalEl);

    if (confirmModal) {
      confirmModal.hide(); 
    }

    setTimeout(() => {
      document.querySelectorAll('.modal-backdrop').forEach(el => el.remove()); 

      let bookingModal = new bootstrap.Modal(document.getElementById('bookingModal'));
      bookingModal.show();
    }, 300);
  }

  function goBackToSecondModal() {
    let payLaterModalEl = document.getElementById('payLaterModal');
    let payLaterModal = bootstrap.Modal.getInstance(payLaterModalEl);
    if (payLaterModal) {
      payLaterModal.hide(); 
    }
    setTimeout(() => {
      document.querySelectorAll('.modal-backdrop').forEach(el => el.remove()); 

      let confirmModal = new bootstrap.Modal(document.getElementById('confirmBookingModal'));
      confirmModal.show();
    }, 300);
  }

  function openPayLaterModal() {
    const fromDate = document.getElementById('from_date').value;
    const toDate = document.getElementById('to_date').value;
    const pricePerDay = document.getElementById('charges_per_day').value;
    const days = document.getElementById('days').value;
    const totalPrice = document.getElementById('total_cost').value;

    document.getElementById('pay_later_from_date').textContent = fromDate;
    document.getElementById('pay_later_to_date').textContent = toDate;
    document.getElementById('pay_later_price').textContent = `£${totalPrice}`;

    let confirmModalEl = document.getElementById('confirmBookingModal');
    let confirmModal = bootstrap.Modal.getInstance(confirmModalEl);

    if (confirmModal) {
      confirmModal.hide();
    }

    setTimeout(() => {
      document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
      let payLaterModal = new bootstrap.Modal(document.getElementById('payLaterModal'));
      payLaterModal.show(); 
    }, 300);
  }

  async function confirmPayLaterBooking() {
    const paymentStatusInput = document.querySelector('input[name="payment_status"]');
    if (paymentStatusInput) {
        paymentStatusInput.value = 'pending';
    }

    let payLaterModalEl = document.getElementById('payLaterModal');
    let payLaterModal = bootstrap.Modal.getInstance(payLaterModalEl);
    if (payLaterModal) {
        payLaterModal.hide();
    }
    Swal.fire({
        title: 'Processing Booking...',
        text: 'Please wait while we confirm your booking.',
        allowOutsideClick: false,
        didOpen: () => {
        Swal.showLoading(); 
        }
    });

    const bookingForm = document.querySelector('#bookingModal form');
    if (bookingForm) {
        const formData = new FormData(bookingForm);
        const actionUrl = bookingForm.getAttribute('action');
        try {
        const response = await fetch(actionUrl, {
            method: 'POST',
            body: formData,
            headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        const data = await response.json();
        await Swal.fire({
            icon: 'success',
            title: 'Booking Confirmed!',
            text: 'Your booking has been successfully confirmed.',
            confirmButtonText: 'OK'
        });

        window.location.href = '/my-bookings';

        } catch (error) {
        Swal.fire({
            icon: 'error',
            title: 'Something went wrong',
            text: 'Your booking could not be processed. Please try again later.',
        });
        console.error('Booking error:', error);
        } finally {
        Swal.close();
        }
    }
}



  function validateBookingForm() {
    const fromDate = document.getElementById('from_date').value;
    const toDate = document.getElementById('to_date').value;

    document.getElementById('from_date_error').textContent = '';
    document.getElementById('to_date_error').textContent = '';

    let hasError = false;

    if (!fromDate) {
      document.getElementById('from_date_error').textContent = 'Please select the "From Date".';
      hasError = true;
    }

    if (!toDate) {
      document.getElementById('to_date_error').textContent = 'Please select the "To Date".';
      hasError = true;
    }

    if (fromDate && toDate) {
      const fromDateObj = new Date(fromDate);
      const toDateObj = new Date(toDate);

      if (fromDateObj >= toDateObj) {
        document.getElementById('to_date_error').textContent = 'The "To Date" must be after the "From Date".';
        hasError = true;
      } else {
        const timeDiff = toDateObj - fromDateObj;
        const days = timeDiff / (1000 * 3600 * 24);
        document.getElementById('days').value = days;

        const pricePerDay = parseFloat(document.getElementById('charges_per_day').value);
        const totalCost = days * pricePerDay;
        document.getElementById('total_cost').value = totalCost.toFixed(2);
        document.getElementById('hidden_total_price').value = totalCost.toFixed(2);

        if (totalCost <= 0) {
          document.getElementById('to_date_error').textContent = 'Please select valid dates to calculate cost.';
          hasError = true;
        }
      }
    }

    return !hasError;
  }
</script>

@endsection
