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
            <label for="identity_number" class="form-label">Identity Number</label>
            <input type="number" class="form-control" id="identity_number" name="identity_number" required>
        </div>

        <div class="mb-3">
            <label for="license_number" class="form-label">License Number</label>
            <input type="text" class="form-control" id="license_number" name="license_number" required>
        </div>

        <div class="mb-3">
            <label for="license_provider" class="form-label">License Provider</label>
            <input type="text" class="form-control" id="license_provider" name="license_provider" required>
        </div>

        <div class="mb-3">
            <label for="age" class="form-label">Age</label>
            <input type="number" class="form-control" id="age" name="age" required min="18">
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
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
