@extends('layouts.app')

@section('content')
<div class="container mt-4 payment-history-container">
    <h2 class="mb-4">Payment History</h2>

    <table id= "paymentsTable" class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Booking ID</th>
                <!-- <th>Invoice ID</th> -->
                <th>Customer</th>
                @if (Auth::user()->hasRole('Admin'))
                <th>Fleet Provider</th>
                @endif
                <th>Total Amount Paid</th>
                <th>Payment Time</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($payments as $payment)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $payment->booking->booking_no }}</td>
                    <!-- <td>{{ $payment->invoice_id }}</td> -->
                    <td>{{ $payment->customer->name }}</td>
                    @if (Auth::user()->hasRole('Admin'))
                    <td>{{ $payment->fleetProvider->name}}</td>
                    @endif
                    <td>£{{ number_format($payment->total_price ) }}</td>
                    <td>{{ $payment->created_at->format('d M Y, h:i A') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No payments found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

        <script>
            $(document).ready(function() {
                $('#paymentsTable').DataTable();
            });
        </script>
@endsection
