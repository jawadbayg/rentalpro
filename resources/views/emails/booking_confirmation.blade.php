<h2>Your Booking is Confirmed</h2>

<p><strong>Booking ID:</strong> {{ $booking->booking_no }}</p>
<p><strong>Vehicle:</strong> {{ $booking->fleet->vehicle_name ?? 'N/A' }}</p>
<p><strong>From:</strong> {{ \Carbon\Carbon::parse($booking->from_date)->format('Y-m-d') }}</p>
<p><strong>To:</strong> {{ \Carbon\Carbon::parse($booking->to_date)->format('Y-m-d') }}</p>
<p><strong>Total Price:</strong> ${{ number_format($booking->total_price, 2) }}</p>

<p>Thank you for booking with us!</p>
<p>Rental Pro</p>
