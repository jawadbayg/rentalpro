@extends('layouts.app')

@section('content')
    
<div class="container edit-fleet ">
    <h1>Edit Vehicle</h1>

    <form action="{{ route('fleet.update', $fleet->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="user_id" value="{{ $fleet->user_id }}">

        <div class="form-group">
            <label for="license_plate">License Plate</label>
            <input type="text" class="form-control fleet-license-plate" id="license_plate" name="license_plate"
                   value="{{ old('license_plate', $fleet->license_plate) }}" required
                   autocomplete="off" autocapitalize="characters" spellcheck="false">
        </div>

        <div class="form-group">
            <label for="vehicle_name">Vehicle Name</label>
            <input type="text" class="form-control" id="vehicle_name" name="vehicle_name" value="{{ old('vehicle_name', $fleet->vehicle_name) }}" required>
        </div>

        <div class="form-group">
            <label for="vehicle_owner_name">Owner Name</label>
            <input type="text" class="form-control" id="vehicle_owner_name" name="vehicle_owner_name" value="{{ old('vehicle_owner_name', $fleet->vehicle_owner_name) }}" required>
        </div>

        <div class="form-group">
            <label for="registration_date">Registration Date</label>
            <input type="date" class="form-control" id="registration_date" name="registration_date" value="{{ old('registration_date', $fleet->registration_date) }}" required>
        </div>

        <div class="form-group">
            @php
                $vehicleTypes = ['Sedan', 'SUV', 'Jeep', 'Other'];
                $fuelTypes = ['Petrol', 'Diesel', 'EV'];
                $selectedVehicleType = old('vehicle_type', $fleet->vehicle_type);
                $selectedFuelType = old('fuel_type', $fleet->fuel_type);
            @endphp
            <label for="vehicle_type">Vehicle Type</label>
            <select class="form-control" id="vehicle_type" name="vehicle_type" required>
                <option value="">Select Vehicle type</option>
                @foreach ($vehicleTypes as $type)
                    <option value="{{ $type }}" {{ $selectedVehicleType === $type ? 'selected' : '' }}>{{ $type }}</option>
                @endforeach
                @if ($selectedVehicleType && ! in_array($selectedVehicleType, $vehicleTypes, true))
                    <option value="{{ $selectedVehicleType }}" selected>{{ $selectedVehicleType }}</option>
                @endif
            </select>
        </div>

        <div class="form-group">
            <label for="fuel_type">Fuel Type</label>
            <select class="form-control" id="fuel_type" name="fuel_type">
                <option value="">Select fuel type</option>
                @foreach ($fuelTypes as $fuel)
                    <option value="{{ $fuel }}" {{ $selectedFuelType === $fuel ? 'selected' : '' }}>{{ $fuel }}</option>
                @endforeach
                @if ($selectedFuelType && ! in_array($selectedFuelType, $fuelTypes, true))
                    <option value="{{ $selectedFuelType }}" selected>{{ $selectedFuelType }}</option>
                @endif
            </select>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="active" {{ $fleet->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $fleet->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="under_maintenance" {{ $fleet->status == 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
            </select>
        </div>

        <div class="form-group">
            <label for="mileage">Mileage</label>
            <input type="number" class="form-control" id="mileage" name="mileage" value="{{ old('mileage', $fleet->mileage) }}">
        </div>

        <div class="form-group">
            <label for="images">Images</label>
            <input type="file" class="form-control" id="images" name="images[]" multiple>
            @foreach($fleet->images as $image)
                <img src="{{ asset('storage/' . $image->image) }}" alt="Fleet Image" class="table-image" width="100">
            @endforeach
        </div>

        <button type="submit" class="btn-black mt-4">Update Vehicle</button>
    </form>
</div>

<script>
    document.querySelectorAll('.fleet-license-plate').forEach(function (input) {
        input.addEventListener('input', function () {
            this.value = this.value.replace(/\s+/g, '').toUpperCase();
        });
    });
</script>

@endsection
