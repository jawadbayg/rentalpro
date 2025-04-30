@extends('layouts.app')

@section('content')
<div class="container user_validation_page_container">
    <h2>Verification Form</h2>
    <p>
        <ul>
            <li>Fill in all the fields with correct and valid details.</li>
            <li>Make sure your Identity Number and License Number are correct.</li>
            <li>Provide the exact name of your License Provider.</li>
            <li>Your age must be 18 years or older to be eligible.</li>
            <li>Enter your complete residential address.</li>
            <li>After submitting, our admin team will review your information within 24 hours.</li>
            <li>Once your verification is approved, you will be able to access Rental Pro services.</li>
        </ul>
    </p>

    <!-- @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif -->

    <form action="{{ route('user_validation.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="identity_number" class="form-label">Identity Number<span class="text-danger">*</span></label>
            <input type="number" 
                class="form-control @error('identity_number') is-invalid @enderror" 
                id="identity_number" name="identity_number" value="{{ old('identity_number') }}" placeholder="Enter you legal identification number (eg. 123456789)" >
            @error('identity_number')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="license_number" class="form-label">License Number<span class="text-danger">*</span></label>
            <input type="text" 
                class="form-control @error('license_number') is-invalid @enderror" 
                id="license_number" name="license_number" value="{{ old('license_number') }}" placeholder="Enter you Vehicle License number (eg. RP1234)">
            @error('license_number')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="license_provider" class="form-label">License Provider<span class="text-danger">*</span></label>
            <input type="text" 
                class="form-control @error('license_provider') is-invalid @enderror" 
                id="license_provider" name="license_provider" value="{{ old('license_provider') }}" placeholder="Enter Country (eg. England)" >
            @error('license_provider')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="age" class="form-label">Age<span class="text-danger">*</span></label>
            <input type="number" 
                class="form-control @error('age') is-invalid @enderror" 
                id="age" name="age"  value="{{ old('age') }}" placeholder="Enter your age - min 18">
        @error('age')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <div class="mb-3">
        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
        <textarea 
            class="form-control @error('address') is-invalid @enderror" 
            id="address" 
            name="address" 
            rows="3" 
            placeholder="Enter your full address including street, city, and zip code">{{ old('address') }}</textarea>
        @error('address')
            <span class="text-danger">{{ $message }}</span>
        @enderror
    </div>


    <button type="submit" class="btn-black mt-2">Submit Verification</button>
</form>

</div>

<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Verification Submitted!',
            text: 'Your verification is submitted. Our administrator will review your application within 24 hours.',
            confirmButtonText: 'Okay'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = "{{ url('/') }}"; 
            }
        });
    @endif
</script>

@endsection
