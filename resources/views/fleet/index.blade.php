@extends('layouts.app')

@section('content')
   

        <a href="{{ route('fleet.create') }}" class="btn-black mb-3">
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
                                <form action="" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

<script>
    $(document).ready(function() {
        $('#fleetTable').DataTable(); 
    });
</script>

@endsection
