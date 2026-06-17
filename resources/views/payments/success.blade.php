@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="checkout-container mx-auto text-center" style="max-width: 560px;">
        <div class="mb-4">
            <i class="fas fa-circle-check text-success" style="font-size: 4rem;"></i>
        </div>
        <h2 class="mb-3">Payment Successful</h2>
        <p class="text-muted mb-4">
            Your payment for booking <strong>{{ $booking->booking_no }}</strong> has been received.
        </p>

        <div class="text-start bg-light rounded p-3 mb-4">
            <p class="mb-2"><strong>Vehicle:</strong> {{ $booking->fleet->vehicle_name ?? 'N/A' }}</p>
            <p class="mb-2"><strong>Amount paid:</strong> {{ format_pkr($booking->total_price, 2) }}</p>
            @if ($payment)
                <p class="mb-2"><strong>Cardholder:</strong> {{ $payment->payer_name }}</p>
                <p class="mb-2"><strong>Card:</strong> {{ $payment->reference_no }}</p>
                <p class="mb-0"><strong>Method:</strong> Card payment</p>
            @endif
        </div>

        <a href="{{ route('customer.bookings.index') }}" class="btn-blue">Back to My Bookings</a>
    </div>
</div>
@endsection
