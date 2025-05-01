    @extends('layouts.app')

    @section('content')
        <div class="container mt-5 bookings-container">
        @if(Auth::user()->hasRole('Admin') || (Auth::user()->hasRole('FP')))   
            <h2 class="mb-4">All Bookings</h2>
        @else
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>My Bookings</h2>
                @if($invoice_to_be_paid == true)
                    <a href="{{ route('invoices.index') }}" class="btn-black-sm ml-auto">Invoices to be paid</a>
                @endif
            </div>
        @endif


           
            <table id="bookingsTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Vehicle</th>
                        <th>From Date</th>
                        <th>To Date</th>
                        <th>Booking Amount</th>
                        @if(Auth::user()->hasRole('Admin') || (Auth::user()->hasRole('FP')))  
                            <th>Fee %</th>
                            <th>Final Amount</th>
                        @endif
                        @if(Auth::user()->hasRole('Admin'))
                            <th>Rental Pro Amount</th>
                        @endif
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
                                <td>£{{ $booking->total_price }}</td>
                                @if(Auth::user()->hasRole('Admin') || (Auth::user()->hasRole('FP')))  
                                    <td>20</td>
                                    <td>£{{ number_format($booking->total_price * 0.80, 2) }}</td>
                                @endif
                                @if(Auth::user()->hasRole('Admin'))
                                    <td>£{{ number_format($booking->total_price * 0.20, 2) }}</td>
                                @endif
                                <td class="{{ $booking->status == 'cancelled' ? 'text-danger' : '' }}">
                                    {{ $booking->status }}
                                </td>
                                <td>{{ $booking->payment_status }}</td>
                                <td>
                                    @if(Auth::user()->hasRole('Admin')) 
                                        <a href="#" class="btn-black-sm" data-bs-toggle="modal" data-bs-target="#adminViewModal" data-booking-id="{{ $booking->id }}">View</a>
                                    @elseif(Auth::user()->hasRole('FP')) 
                                    <a href="#" class="btn-black-sm" data-bs-toggle="modal" data-bs-target="#FPViewModal" data-booking-id="{{ $booking->id }}">View</a>
                                        @else
                                        <a href="{{ route('vehicle.show', $booking->fleet->id) }}" class="btn-black-sm">View</a>
                                    @endif
                                    
                                    <a href="{{ route('bookings.invoice', $booking->id) }}" target="_blank" class="btn-black-sm">Invoice</a>
                                    
                                    @if(Auth::user()->hasRole('Admin') || (Auth::user()->hasRole('FP')))
                                    @else
                                        <a href="#" class="btn-black-sm" data-bs-toggle="modal" data-bs-target="#cancelModal" data-booking-id="{{ $booking->id }}">
                                            Cancel
                                        </a>
                                    @endif
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

    @if(Auth::user()->hasRole('Admin')) 
                <div class="modal fade" id="adminViewModal" tabindex="-1" aria-labelledby="adminViewModalLabel" aria-hidden="true">
                <div class="modal-dialog  modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="adminViewModalLabel">Booking Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div> <h3>Fleet Provider Detail</h3></div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @if ($fleet)
                                    <td>{{ $fleet->user->name }}</td>
                                    <td>{{ $fleet->user->email }}</td>
                                    <td>{{ $fleet->user->address }}</td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                        <hr style="border: 0; border-top: 1px solid #ccc; margin: 1rem 0;">
                        <div><h3>Vehicle Details</h3></div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Vehicle No</th>
                                    <th>Vehicle Name</th>
                                    <th>Vehicle Type</th>
                                    <th>License Plate</th>
                                    <th>Vehicle Owner Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                @if ($fleet)
                                    <td>{{ $fleet->vehicle_no }}</td>
                                    <td>{{ $fleet->vehicle_name }}</td>
                                    <td>{{ $fleet->vehicle_type }}</td>
                                    <td>{{ $fleet->license_plate }}</td>
                                    <td>{{ $fleet->vehicle_owner_name }}</td>
                                @endif
                                </tr>
                            </tbody>
                        </table>
                        <hr style="border: 0; border-top: 1px solid #ccc; margin: 1rem 0;">
                        <div><h3>Booking Details</h3></div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Customer Email</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                @if ($fleet)
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $booking->from_date }}</td>
                                    <td>{{ $booking->to_date }}</td>
                                    <td>£{{ $booking->total_price }}</td>
                                @endif
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>

        <style>
            .modal-content{
                margin-top: -50px;
            }
        </style>
    @endif
    @if(Auth::user()->hasRole('FP')) 
                <div class="modal fade" id="FPViewModal" tabindex="-1" aria-labelledby="FPViewModalLabel" aria-hidden="true">
                <div class="modal-dialog  modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="FPViewModalLabel">Booking Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        
                        <div><h3>Vehicle Details</h3></div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Vehicle No</th>
                                    <th>Vehicle Name</th>
                                    <th>Vehicle Type</th>
                                    <th>License Plate</th>
                                    <th>Vehicle Owner Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @if ($fleet)
                                        <td>{{ $fleet->vehicle_no }}</td>
                                        <td>{{ $fleet->vehicle_name }}</td>
                                        <td>{{ $fleet->vehicle_type }}</td>
                                        <td>{{ $fleet->license_plate }}</td>
                                        <td>{{ $fleet->vehicle_owner_name }}</td>
                                    @endif
                                </tr>
                            </tbody>
                        </table>
                        <hr style="border: 0; border-top: 1px solid #ccc; margin: 1rem 0;">
                        <div><h3>Booking Details</h3></div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Customer Email</th>
                                    <th>From Date</th>
                                    <th>To Date</th>
                                    <th>Total Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                @if ($fleet)
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->email }}</td>
                                    <td>{{ $booking->from_date }}</td>
                                    <td>{{ $booking->to_date }}</td>
                                    <td>£{{ $booking->total_price }}</td>
                                @endif
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="modal-footer">
                    </div>
                </div>
            </div>
        </div>

        <style>
            .modal-content{
                margin-top: -50px;
            }
        </style>
    @endif

<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

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

