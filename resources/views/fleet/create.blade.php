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
    <!-- @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif -->

    <h2>Add New Vehicle</h2>
    <form action="{{ route('fleet.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="user_id" value="{{ Auth::id() }}">

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="vehicle_no" class="form-label">Vehicle Number <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('vehicle_no') is-invalid @enderror" 
                       id="vehicle_no" name="vehicle_no" value="{{ old('vehicle_no') }}">
                @error('vehicle_no')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="license_plate" class="form-label">License Plate <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('license_plate') is-invalid @enderror" 
                       id="license_plate" name="license_plate" value="{{ old('license_plate') }}">
                @error('license_plate')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="vehicle_name" class="form-label">Vehicle Name <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('vehicle_name') is-invalid @enderror" 
                       id="vehicle_name" name="vehicle_name" value="{{ old('vehicle_name') }}">
                @error('vehicle_name')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="vehicle_type" class="form-label">Vehicle Type <span class="text-danger">*</span></label>
                <input type="text" class="form-control @error('vehicle_type') is-invalid @enderror" 
                       id="vehicle_type" name="vehicle_type" value="{{ old('vehicle_type') }}">
                @error('vehicle_type')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="vehicle_owner_name" class="form-label">Vehicle Owner Name <span class="text-danger">*</span></label>
            <input type="text" class="form-control @error('vehicle_owner_name') is-invalid @enderror" 
                   id="vehicle_owner_name" name="vehicle_owner_name" value="{{ old('vehicle_owner_name') }}">
            @error('vehicle_owner_name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="registration_date" class="form-label">Registration Date <span class="text-danger">*</span></label>
            <input type="date" class="form-control @error('registration_date') is-invalid @enderror" 
                   id="registration_date" name="registration_date" value="{{ old('registration_date') }}">
            @error('registration_date')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="mileage" class="form-label">Mileage (optional)</label>
                <input type="number" class="form-control @error('mileage') is-invalid @enderror" 
                       id="mileage" name="mileage" value="{{ old('mileage') }}">
                @error('mileage')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="col-md-6">
                <label for="fuel_type" class="form-label">Fuel Type (optional)</label>
                <input type="text" class="form-control @error('fuel_type') is-invalid @enderror" 
                       id="fuel_type" name="fuel_type" value="{{ old('fuel_type') }}">
                @error('fuel_type')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
        </div>

        <div class="mb-3">
            <label for="manufacturing_year" class="form-label">Manufacturing Year <span class="text-danger">*</span></label>
            <input type="number" class="form-control @error('manufacturing_year') is-invalid @enderror" 
                   id="manufacturing_year" name="manufacturing_year" value="{{ old('manufacturing_year') }}" min="1900" max="{{ date('Y') }}">
            @error('manufacturing_year')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status" class="form-label">Vehicle Status <span class="text-danger">*</span></label>
            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                <option value="">Select Status</option>
                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                <option value="under_maintenance" {{ old('status') == 'under_maintenance' ? 'selected' : '' }}>Under Maintenance</option>
            </select>
            @error('status')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="charges_per_day" class="form-label">Charges per day <span class="text-danger">*</span></label>
            <input type="number" class="form-control @error('charges_per_day') is-invalid @enderror" 
                   id="charges_per_day" name="charges_per_day" value="{{ old('charges_per_day') }}">
            @error('charges_per_day')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="form-group mb-3">
            <label for="images">Vehicle Images (optional)</label>
            <input type="file" name="images[]" class="form-control @error('images.*') is-invalid @enderror" multiple>
            @error('images.*')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <button type="submit" class="btn-black">Add Vehicle</button>
    </form>
</div>


@endsection
