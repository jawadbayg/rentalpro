@extends('layouts.app')

@section('content')
<div class="container users_index_container">
    <div class="row">
        <div class="col-lg-12 margin-tb d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h2 class="mb-0">Edit Fleet Provider</h2>
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

    <form method="POST" action="{{ route('fleet-providers.update', $fleetProvider->id) }}" class="mt-3">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
            <input type="text" id="name" name="name" value="{{ old('name', $fleetProvider->name) }}" class="form-control @error('name') is-invalid @enderror">
            @error('name')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
            <input type="email" id="email" name="email" value="{{ old('email', $fleetProvider->email) }}" class="form-control @error('email') is-invalid @enderror">
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
            <input type="text" id="address" name="address" value="{{ old('address', $fleetProvider->fpDetail->address ?? '') }}" class="form-control @error('address') is-invalid @enderror">
            @error('address')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <hr>
        <p class="text-muted mb-3">Leave password fields blank to keep the current password.</p>

        <x-password-field
            id="password"
            name="password"
            label="New Password"
            autocomplete="new-password"
            input-class="form-control"
            :required="false"
        />

        <x-password-field
            id="password-confirm"
            name="password_confirmation"
            label="Confirm New Password"
            autocomplete="new-password"
            input-class="form-control"
            :required="false"
        />

        <div class="text-end">
            <button type="submit" class="btn-black-sm"><i class="fa-solid fa-floppy-disk"></i> Update Fleet Provider</button>
        </div>
    </form>
</div>
@endsection
