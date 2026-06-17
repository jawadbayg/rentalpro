@extends('layouts.app')

@section('content')
<style>
    .verification-detail-section {
        margin-bottom: 1.25rem;
    }

    .verification-detail-section:last-child {
        margin-bottom: 0;
    }

    .verification-detail-section__title {
        font-family: 'Outfit', 'Poppins', sans-serif;
        font-size: 0.95rem;
        font-weight: 700;
        color: #01232e;
        margin-bottom: 0.75rem;
        padding-bottom: 0.35rem;
        border-bottom: 1px solid rgba(1, 35, 46, 0.1);
    }

    .user-detail-row {
        display: flex;
        gap: 0.75rem;
        margin-bottom: 0.65rem;
        align-items: flex-start;
    }

    .user-detail-label {
        font-weight: 600;
        color: #5a6f78;
        min-width: 140px;
        flex-shrink: 0;
    }

    .user-detail-value {
        flex: 1;
        color: #0b1f26;
        word-break: break-word;
    }

    .verification-status-badge {
        display: inline-flex;
        align-items: center;
        padding: 0.2rem 0.6rem;
        border-radius: 999px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: capitalize;
    }

    .verification-status-badge--pending {
        background: rgba(255, 193, 7, 0.18);
        color: #997404;
    }

    .verification-status-badge--approved {
        background: rgba(25, 135, 84, 0.12);
        color: #146c43;
    }

    .verification-profile-preview {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid rgba(1, 35, 46, 0.1);
        margin-bottom: 0.75rem;
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
                <th>Status</th>
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
                    <span class="verification-status-badge verification-status-badge--{{ $request->status === 'approved' ? 'approved' : 'pending' }}">
                        {{ $request->status }}
                    </span>
                </td>
                <td>
                    <a href="javascript:void(0)" class="datatable-btn view-btn"
                       data-name="{{ $request->user->name }}"
                       data-email="{{ $request->user->email }}"
                       data-role="{{ $request->user->getRoleNames()->implode(', ') }}"
                       data-registered="{{ $request->user->created_at?->format('d M Y, h:i A') }}"
                       data-profile="{{ $request->user->profile && $request->user->profile->profile_picture ? asset('storage/' . $request->user->profile->profile_picture) : asset('default-user.png') }}"
                       data-identity="{{ $request->identity_number }}"
                       data-license="{{ $request->license_number }}"
                       data-license-provider="{{ $request->license_provider }}"
                       data-age="{{ $request->age }}"
                       data-address="{{ $request->address }}"
                       data-status="{{ ucfirst($request->status) }}"
                       data-submitted="{{ $request->created_at?->format('d M Y, h:i A') }}"
                       data-updated="{{ $request->updated_at?->format('d M Y, h:i A') }}"
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
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewModalLabel">Verification Request Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="verification-detail-section">
                    <h6 class="verification-detail-section__title">User information</h6>
                    <img id="userProfile" src="" alt="Profile picture" class="verification-profile-preview d-none">
                    <div class="user-detail-row">
                        <span class="user-detail-label">Name</span>
                        <span id="userName" class="user-detail-value"></span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">Email</span>
                        <span id="userEmail" class="user-detail-value"></span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">Role</span>
                        <span id="userRole" class="user-detail-value"></span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">Registered on</span>
                        <span id="userRegistered" class="user-detail-value"></span>
                    </div>
                </div>

                <div class="verification-detail-section">
                    <h6 class="verification-detail-section__title">Verification details</h6>
                    <div class="user-detail-row">
                        <span class="user-detail-label">Status</span>
                        <span id="verificationStatus" class="user-detail-value"></span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">Identity number</span>
                        <span id="verificationIdentity" class="user-detail-value"></span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">License number</span>
                        <span id="verificationLicense" class="user-detail-value"></span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">License provider</span>
                        <span id="verificationLicenseProvider" class="user-detail-value"></span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">Age</span>
                        <span id="verificationAge" class="user-detail-value"></span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">Address</span>
                        <span id="verificationAddress" class="user-detail-value"></span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">Submitted on</span>
                        <span id="verificationSubmitted" class="user-detail-value"></span>
                    </div>
                    <div class="user-detail-row">
                        <span class="user-detail-label">Last updated</span>
                        <span id="verificationUpdated" class="user-detail-value"></span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-black-sm" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.view-btn').on('click', function() {
            const $btn = $(this);

            $('#userProfile').attr('src', $btn.data('profile')).removeClass('d-none');
            $('#userName').text($btn.data('name') || '—');
            $('#userEmail').text($btn.data('email') || '—');
            $('#userRole').text($btn.data('role') || '—');
            $('#userRegistered').text($btn.data('registered') || '—');

            $('#verificationStatus').text($btn.data('status') || '—');
            $('#verificationIdentity').text($btn.data('identity') || '—');
            $('#verificationLicense').text($btn.data('license') || '—');
            $('#verificationLicenseProvider').text($btn.data('licenseProvider') || '—');
            $('#verificationAge').text($btn.data('age') || '—');
            $('#verificationAddress').text($btn.data('address') || '—');
            $('#verificationSubmitted').text($btn.data('submitted') || '—');
            $('#verificationUpdated').text($btn.data('updated') || '—');

            const modal = bootstrap.Modal.getOrCreateInstance(document.getElementById('viewModal'));
            modal.show();
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#verificationTable').DataTable({
            responsive: true,
            paging: true,
            lengthChange: false,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false
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
