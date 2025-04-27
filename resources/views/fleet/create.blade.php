@extends('layouts.app')

@section('content')
<style>
    .content-area-fp {
        margin-left: 270px;
        margin-right: 270px; /* This will add padding to both sides of the content */
        padding: 20px;
        max-width: calc(100% - 540px); /* Leaves space for the sidebar on both sides */
    }
</style>


<div class="container create-fleet ">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h2>Add New Vehicle</h2>
    <form action="{{ route('fleet.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- User ID (Assumed the current logged-in user is the owner) -->
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">

        <!-- Vehicle Number and License Plate (in a row) -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="vehicle_no" class="form-label">Vehicle Number</label>
                <input type="text" class="form-control" id="vehicle_no" name="vehicle_no" required>
            </div>
            <div class="col-md-6">
                <label for="license_plate" class="form-label">License Plate</label>
                <input type="text" class="form-control" id="license_plate" name="license_plate" required>
            </div>
        </div>

        <!-- Vehicle Name and Vehicle Type (in a row) -->
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="vehicle_name" class="form-label">Vehicle Name</label>
                <input type="text" class="form-control" id="vehicle_name" name="vehicle_name" required>
            </div>
            <div class="col-md-6">
                <label for="vehicle_type" class="form-label">Vehicle Type</label>
                <input type="text" class="form-control" id="vehicle_type" name="vehicle_type" required>
            </div>
        </div>

        <!-- Vehicle Owner Name -->
        <div class="mb-3">
            <label for="vehicle_owner_name" class="form-label">Vehicle Owner Name</label>
            <input type="text" class="form-control" id="vehicle_owner_name" name="vehicle_owner_name" required>
        </div>

        <!-- Registration Date -->
        <div class="mb-3">
            <label for="registration_date" class="form-label">Registration Date</label>
            <input type="date" class="form-control" id="registration_date" name="registration_date" required>
        </div>

        <div class="row mb-3">
        <!-- Mileage -->
        <div class="col-md-6">
            <label for="mileage" class="form-label">Mileage (optional)</label>
            <input type="number" class="form-control" id="mileage" name="mileage">
        </div>

        <!-- Fuel Type -->
        <div class="col-md-6">
            <label for="fuel_type" class="form-label">Fuel Type (optional)</label>
            <input type="text" class="form-control" id="fuel_type" name="fuel_type">
        </div>
        </div>
        <!-- Manufacturing Year -->
        <div class="mb-3">
            <label for="manufacturing_year" class="form-label">Manufacturing Year</label>
            <input type="number" class="form-control" id="manufacturing_year" name="manufacturing_year" min="1900" max="{{ date('Y') }}" required>
        </div>

        <!-- Status -->
        <div class="mb-3">
            <label for="status" class="form-label">Vehicle Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="under_maintenance">Under Maintenance</option>
            </select>
        </div>

        <!-- Vehicle Images -->
        <div class="form-group mb-3">
            <label for="images">Vehicle Images</label>
            <input type="file" name="images[]" class="form-control" multiple>
        </div>

        <!-- Submit Button -->
        <button type="submit" class="btn btn-primary">Add Vehicle</button>
    </form>
</div>


@endsection
