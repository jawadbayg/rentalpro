@extends('layouts.app')

@section('content')
   

        <a href="{{ route('fleet.create') }}" class="btn-blue mb-3">
           + Add Vehicle
        </a>
        <div class="table-card">
            <table id="fleetTable" class="table table-bordered">
                <thead>
                    <tr>
                        @if($isAdmin) <!-- Add the Fleet Provider column if the user is an Admin -->
                            <th>Fleet Provider</th>
                        @endif
                        <th>Vehicle</th>
                        <th>Vehicle Name</th>
                        <th>Owner Name</th>
                        <th>Vehicle Type</th>
                        <th>Manufacturing Year</th>
                        <th>Status</th>
                        <th>Rental Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fleets as $fleet)
                        <tr>
                            @if($isAdmin)
                                <td>{{ $fleet->user->name }}</td>
                            @endif
                            <td>
                                @if($fleet->images->count() > 0)
                                    <img src="{{ asset('storage/' . $fleet->images->first()->image) }}" alt="Vehicle Image" class="table-image">
                                @else
                                    No Image
                                @endif
                            </td>
                            <td>{{ $fleet->vehicle_name }}</td>
                            <td>{{ $fleet->vehicle_owner_name }}</td>
                            <td>{{ $fleet->vehicle_type }}</td>
                            <td>{{ $fleet->manufacturing_year }}</td>
                            <td style="color: 
                                @if($fleet->status == 'active') 
                                    green;
                                @elseif($fleet->status == 'inactive') 
                                    red;
                                @elseif($fleet->status == 'under_maintenance') 
                                    orange;
                                @else 
                                    black; 
                                @endif">
                                @if($fleet->status == 'active')
                                    Active
                                @elseif($fleet->status == 'inactive')
                                    Inactive
                                @elseif($fleet->status == 'under_maintenance')
                                    Under Maintenance
                                @else
                                    {{ ucfirst($fleet->status) }}
                                @endif
                            </td>
                            <td>{{ $fleet->rental_status }}</td>
                            <td>
                            <a href="{{ route('fleet.edit', $fleet->id) }}" class="btn btn-warning btn-sm">Edit</a>     
                            <!-- <button 
                                class="btn btn-danger btn-sm delete-fleet-btn" 
                                data-id="{{ $fleet->id }}" 
                                data-token="{{ csrf_token() }}">
                                Delete
                            </button> -->
                           



                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>

<script>
    $(document).ready(function() {
        $('#fleetTable').DataTable(); 
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.delete-fleet-btn').forEach(button => {
        button.addEventListener('click', function () {
            const fleetId = this.dataset.id;
            const token = this.dataset.token;

            Swal.fire({
                title: 'Are you sure?',
                text: "This fleet will be permanently deleted.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/fleet/delete/${fleetId}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            Swal.fire('Deleted!', data.message, 'success').then(() => {
                                location.reload(); 
                            });
                        } else {
                            Swal.fire('Error!', 'Something went wrong.', 'error');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire('Error!', 'Request failed.', 'error');
                    });
                }
            });
        });
    });
});
</script>



@endsection
