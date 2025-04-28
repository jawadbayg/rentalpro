@extends('layouts.app')

@section('content')
<style>
   
    .user-detail-row {
    display: flex;
    margin-bottom: 10px;
    }

    .user-detail-label {
        font-weight: bold;
        margin-right: 10px;
        width: 120px; /* Adjust as needed */
    }

    .user-detail-value {
        flex: 1;
    }

</style>
<div class="container verification_request_container">
    <h2>Verification Requests</h2>

    <table id="verificationTable" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Identity Number</th>
                <th>License Number</th>
                <th>License Provider</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $request)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $request->user->name }}</td>
                <td>{{ $request->identity_number }}</td>
                <td>{{ $request->license_number }}</td>
                <td>{{ $request->license_provider }}</td>
                <td>
                    <a href="javascript:void(0)" class="datatable-btn view-btn" 
                       data-name="{{ $request->user->name }}"
                       data-email="{{ $request->user->email }}"
                       data-age="{{ $request->age }}"
                       data-address="{{ $request->address }}"
                    >View</a>
                    @if($request->status == 'pending')
                        <a href="javascript:void(0)" class="datatable-btn approve-btn" data-id="{{ $request->id }}">Approve</a>
                    @endif
                </td> 
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">User Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="user-detail-row">
                    <span class="user-detail-label"><strong>Name:</strong></span>
                    <span id="userName" class="user-detail-value"></span>
                </div>
                <div class="user-detail-row">
                    <span class="user-detail-label"><strong>Email:</strong></span>
                    <span id="userEmail" class="user-detail-value"></span>
                </div>
                <div class="user-detail-row">
                    <span class="user-detail-label"><strong>Age:</strong></span>
                    <span id="userAge" class="user-detail-value"></span>
                </div>
                <div class="user-detail-row">
                    <span class="user-detail-label"><strong>Address:</strong></span>
                    <span id="userAddress" class="user-detail-value"></span>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        $('.view-btn').on('click', function() {
            var userName = $(this).data('name');
            var userEmail = $(this).data('email');
            var userAge = $(this).data('age');
            var userAddress = $(this).data('address');
            $('#userName').text(userName);
            $('#userEmail').text(userEmail);
            $('#userAge').text(userAge);
            $('#userAddress').text(userAddress);
            $('#viewModal').modal('show');
        });
    });
</script>


<link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#verificationTable').DataTable({
            "responsive": true,
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false
        });
    });
</script>
<script>
    $(document).ready(function() {
        $('.approve-btn').on('click', function() {
            var requestId = $(this).data('id'); 
            Swal.fire({
                title: 'Are you sure?',
                text: "You are about to approve this user. This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, approve it!',
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire({
                        title: 'Approving...',
                        text: 'Please wait while we approve this user.',
                        showCancelButton: false,
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading(); 
                        }
                    });

                    $.ajax({
                        url: '{{ route('user_validation.approve') }}', 
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: requestId,
                        },
                        success: function(response) {
                            if (response.status === 'success') {
                                Swal.fire(
                                    'Approved!',
                                    'The user has been approved.',
                                    'success'
                                ).then((result) => {
                                    if (result.isConfirmed) {
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    'There was an error approving the user.',
                                    'error'
                                );
                            }
                        },
                        error: function() {
                            Swal.fire(
                                'Error!',
                                'Something went wrong. Please try again.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
</script>



@endsection
