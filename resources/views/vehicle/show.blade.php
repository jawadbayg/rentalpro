@extends('layouts.app')

@section('content')

<style>
    .modal-content{
        margin-bottom: 35vh !important;
    }
    .btn-next-step.disabled {
    opacity: 0.5; /* Fades the button */
    pointer-events: none; /* Prevents clicks */
    cursor: not-allowed; /* Changes the cursor to indicate it's not clickable */
    background-color: #cccccc; /* You can adjust this to match your design */
    color: #666666; /* Change text color to show it's disabled */
    border-color: #999999; /* Adjust border color for disabled state */
  }

  .btn-next-step {
      transition: all 0.3s ease; /* Adds a smooth transition for changes */
  }

</style>
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
                    <p><strong>Address:</strong>{{ $fleet->user->fpDetail->address }}</p>
                    <button class="btn-outline"> Contact</button>
                    @if ($already_booked == true)
                    <button class="btn-disabled mt-3 disabled" aria-disabled="true" style="pointer-events: none;">
                        Already Booked
                    </button>
                    @else
                        <button class="btn-blue mt-3" data-bs-toggle="modal" data-bs-target="#bookingModal">Book Now</button>
                    @endif

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
                        <a href="{{ route('vehicle.show', $item->id) }}" class="btn-blue">View Details</a>
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
          <form action="{{ route('bookings.store') }}" method="POST">
            @csrf

            <!-- Hidden Inputs -->
            <input type="hidden" name="fp_id" value="{{ $fleet->user_id }}"> <!-- Assuming fp_id is the Fleet Provider (owner of car) -->
            <input type="hidden" name="fleet_id" value="{{ $fleet->id }}">
            <input type="hidden" name="customer_id" value="{{ Auth::id() }}">
            <input type="hidden" name="payment_status" value="pending"> <!-- Default payment status -->
            <input type="hidden" name="total_price" id="hidden_total_price">

            <!-- From Date -->
            <div class="mb-3">
              <label for="from_date" class="form-label">From Date <span class="text-danger">*</span></label>
              <input type="text" id="from_date" name="from_date" class="form-control datepicker" required autocomplete="off">
              <div id="from_date_error" class="text-danger small mt-1"></div>
            </div>

            <!-- To Date -->
            <div class="mb-3">
              <label for="to_date" class="form-label">To Date <span class="text-danger">*</span></label>
              <input type="text" id="to_date" name="to_date" class="form-control datepicker" required autocomplete="off">
              <div id="to_date_error" class="text-danger small mt-1"></div>
            </div>

            <!-- Booking Summary -->
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

            <!-- Replace the submit button with a trigger -->
            <div class="text-end">
              <button type="button" class="btn-black-sm btn-next-step" onclick="openConfirmBookingModal()">Next Step</button>
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

<!-- Confirm Booking Modal -->
<div class="modal fade" id="confirmBookingModal" tabindex="-1" aria-labelledby="confirmBookingModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="confirmBookingModalLabel">Confirm Booking</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="back-btn-container">
        <button type="button" class="btn-black-sm" onclick="goBackToBooking()">Back</button>
      </div>

      <div class="modal-body text-center">
        <p class="mb-4">Would you like to pay now or later?</p>

        <div class="d-flex justify-content-center gap-3">
          <button type="button" class="btn-black-sm" onclick="submitBookingForm('paid')">Pay Now</button>
          <button type="button" class="btn-black-sm" onclick="openPayLaterModal()">Pay Later</button>
        </div>
      </div>

    </div>
  </div>
</div>


<div class="modal fade" id="payLaterModal" tabindex="-1" aria-labelledby="payLaterModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="payLaterModalLabel">Confirm Your Booking</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="back-btn-container">
        <button type="button" class="btn-black-sm" onclick="goBackToSecondModal()">Back</button>
      </div>
      <div class="modal-body">
        <h5 class="mb-4">Booking Details</h5>
        
        <table class="table">
          <thead>
            <tr>
              <th>From Date</th>
              <th>To Date</th>
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
          <button type="button" class="btn-black-sm" onclick="confirmPayLaterBooking()">Confirm</button>
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
    const fromDateInput = document.getElementById('from_date');
    const toDateInput = document.getElementById('to_date');
    const nextStepButton = document.querySelector('.btn-next-step');

    fromDateInput.addEventListener('change', function () {
        checkDateAvailability(this.value, 'from');
    });

    toDateInput.addEventListener('change', function () {
        checkDateAvailability(this.value, 'to');
    });

    function checkDateAvailability(date, type) {
        const payload = {
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

        window.location.reload();

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
