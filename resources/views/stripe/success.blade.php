@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Payment Successful</h2>
        <p>Booking #{{ $booking->booking_no }} has been marked as <strong>paid</strong>.</p>
        <a href="{{ route('home') }}" class="btn btn-primary">Go to Dashboard</a>
    </div>
@endsection
