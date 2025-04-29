    @extends('layouts.app')

    @section('content')
        <div class="container mt-5 bookings-container">
            <h2 class="mb-4">My Bookings</h2>

            <table id="bookingsTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Vehicle</th>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @if ($bookings->isEmpty())
                    <tr>
                        <td colspan="7" class="text-center">No Bookings Available</td>
                    </tr>
                @else
                        @foreach ($bookings as $booking)
                            <tr>
                                <td>{{ $booking->booking_no }}</td>
                                <td>{{ $booking->fleet->vehicle_name }}</td>
                                <td>{{ $booking->from_date }}</td>
                                <td>{{ $booking->to_date }}</td>
                                <td>{{ $booking->total_price }}</td>
                                <td>{{ $booking->status }}</td>
                                <td>{{ $booking->payment_status }}</td>
                                <td>
                                    <a href="{{ route('vehicle.show', $booking->fleet->id) }}" class="btn-black-sm">View</a>
                                    <a href="{{ route('vehicle.show', $booking->fleet->id) }}" class="btn-black-sm">Invoice</a>
                                    <a href="#" class="btn-black-sm" data-bs-toggle="modal" data-bs-target="#cancelModal" data-booking-id="{{ $booking->id }}">
                                        Cancel
                                    </a>

                                </td>
                            </tr>
                        @endforeach
                @endif  
                </tbody>
            </table>
        </div>


        <!-- Cancel Confirmation Modal -->
        <div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Confirm Cancellation</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to cancel this booking?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                <button type="button" class="btn-black-sm" id="confirmCancel">Yes, Cancel</button>
            </div>
            </div>
        </div>
        </div>

   
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script>
            $(document).ready(function() {
                $('#bookingsTable').DataTable();
            });
        </script>
        <script>
            let cancelBookingId = null;

            $('#cancelModal').on('show.bs.modal', function (event) {
                const button = $(event.relatedTarget);
                cancelBookingId = button.data('booking-id');
            });
            $('#confirmCancel').on('click', function () {
                if (cancelBookingId) {
                    $.ajax({
                        url: `/bookings/${cancelBookingId}/cancel`,
                        type: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function (response) {
                            location.reload(); 
                        },
                        error: function (err) {
                            alert('Something went wrong. Try again.');
                        }
                    });
                }
            });
        </script>

@endsection

