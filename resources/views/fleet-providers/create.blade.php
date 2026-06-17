@extends('layouts.app')

@section('content')
<div class="container user-create-container">
    <div class="row">
        <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h2 class="mb-0">Create Fleet Provider</h2>
            <a class="btn-black-sm" href="{{ route('fleet-providers.index') }}"><i class="fa fa-arrow-left"></i> Back</a>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger mt-3">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('fleet-providers.store') }}" class="mt-3">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" placeholder="Full name">
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" class="form-control @error('email') is-invalid @enderror" placeholder="Email address">
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <x-password-field
            id="password"
            name="password"
            label="Password"
            autocomplete="new-password"
            input-class="form-control"
        />

        <x-password-field
            id="password-confirm"
            name="password_confirmation"
            label="Confirm Password"
            autocomplete="new-password"
            input-class="form-control"
        />

        <div class="mb-3">
            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
            <input type="text" id="address" name="address" value="{{ old('address') }}" class="form-control @error('address') is-invalid @enderror" placeholder="Business address">
            @error('address')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="text-end">
            <button type="submit" class="btn-black-sm"><i class="fa-solid fa-floppy-disk"></i> Create Fleet Provider</button>
        </div>
    </form>
</div>
@endsection
