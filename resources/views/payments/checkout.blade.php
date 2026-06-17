@extends('layouts.app')

@section('content')
<style>
    .card-payment-form {
        border: 1px solid rgba(1, 35, 46, 0.12);
        border-radius: 16px;
        padding: 1.5rem;
        background: #fff;
        box-shadow: 0 8px 28px rgba(1, 35, 46, 0.06);
    }

    .card-payment-form__header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
    }

    .card-payment-form__title {
        font-family: 'Outfit', sans-serif;
        font-weight: 700;
        color: #01232e;
        margin: 0;
    }

    .card-payment-form__icons {
        color: #6b7f88;
        font-size: 1.35rem;
        display: flex;
        gap: 0.5rem;
    }

    .card-input-wrap {
        position: relative;
    }

    .card-input-wrap .form-control {
        padding-right: 2.5rem;
    }

    .card-input-wrap__icon {
        position: absolute;
        right: 0.85rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7f88;
    }
</style>

<div class="d-flex justify-content-center align-items-center py-4">
    <div class="checkout-container col-12 col-md-8 col-lg-5">
        <div class="checkout-info mb-4">
            <h4 class="mb-3">Pay with card</h4>
            <ul class="list-unstyled mb-0">
                <li class="mb-2"><strong>Booking ID:</strong> {{ $booking->booking_no }}</li>
                <li class="mb-2"><strong>Vehicle:</strong> {{ $booking->fleet->vehicle_name ?? 'N/A' }}</li>
                <li class="mb-2"><strong>From:</strong> {{ $booking->from_date }} — <strong>To:</strong> {{ $booking->to_date }}</li>
                <li class="mb-0"><strong>Amount due:</strong> {{ format_pkr($booking->total_price, 2) }}</li>
            </ul>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card-payment-form">
            <div class="card-payment-form__header">
                <h5 class="card-payment-form__title">Card details</h5>
                <div class="card-payment-form__icons">
                    <i class="fab fa-cc-visa"></i>
                    <i class="fab fa-cc-mastercard"></i>
                </div>
            </div>

            <form method="POST" action="{{ route('checkout.process', $booking->id) }}" id="cardPaymentForm">
                @csrf

                <div class="mb-3">
                    <label for="card_holder_name" class="form-label">Name on card <span class="text-danger">*</span></label>
                    <input type="text" id="card_holder_name" name="card_holder_name"
                           value="{{ old('card_holder_name', Auth::user()->name) }}"
                           class="form-control @error('card_holder_name') is-invalid @enderror"
                           placeholder="John Doe" required>
                    @error('card_holder_name')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="card_number" class="form-label">Card number <span class="text-danger">*</span></label>
                    <div class="card-input-wrap">
                        <input type="text" id="card_number" name="card_number" inputmode="numeric"
                               value="{{ old('card_number') }}"
                               class="form-control @error('card_number') is-invalid @enderror"
                               placeholder="1234 5678 9012 3456" maxlength="19" required>
                        <i class="fas fa-credit-card card-input-wrap__icon"></i>
                    </div>
                    @error('card_number')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label for="card_expiry" class="form-label">Expiry <span class="text-danger">*</span></label>
                        <input type="text" id="card_expiry" name="card_expiry" inputmode="numeric"
                               value="{{ old('card_expiry') }}"
                               class="form-control @error('card_expiry') is-invalid @enderror"
                               placeholder="MM/YY" maxlength="5" required>
                        @error('card_expiry')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="card_cvv" class="form-label">CVV <span class="text-danger">*</span></label>
                        <div class="card-input-wrap">
                            <input type="password" id="card_cvv" name="card_cvv" inputmode="numeric"
                                   class="form-control @error('card_cvv') is-invalid @enderror"
                                   placeholder="123" maxlength="4" required>
                            <i class="fas fa-lock card-input-wrap__icon"></i>
                        </div>
                        @error('card_cvv')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn-blue btn-lg">Pay {{ format_pkr($booking->total_price, 2) }}</button>
                </div>
            </form>
        </div>

        <div class="text-center mt-3">
            <a href="{{ route('customer.bookings.index') }}" class="sign-up-btn">Cancel and go back</a>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cardNumber = document.getElementById('card_number');
        const cardExpiry = document.getElementById('card_expiry');

        if (cardNumber) {
            cardNumber.addEventListener('input', function () {
                let value = this.value.replace(/\D/g, '').slice(0, 16);
                this.value = value.replace(/(\d{4})(?=\d)/g, '$1 ').trim();
            });
        }

        if (cardExpiry) {
            cardExpiry.addEventListener('input', function () {
                let value = this.value.replace(/\D/g, '').slice(0, 4);
                if (value.length >= 3) {
                    value = value.slice(0, 2) + '/' + value.slice(2);
                }
                this.value = value;
            });
        }
    });
</script>
@endsection
