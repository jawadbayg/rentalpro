@extends('layouts.app')

@section('content')
<div class="container mt-4 profile_settings_container">
    <h2>Profile Settings</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form action="{{ route('profile.upload', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" id="email" name="email" class="form-control" value="{{ $user->email }}" readonly>
                </div>

                <div class="mb-3">
                    <label for="profile_picture" class="form-label">Profile Picture</label><br>
                    @if($user->profile && $user->profile->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile->profile_picture) }}" alt="Profile Picture" width="150" class="mb-3"><br>
                    @else
                        <p>No profile picture uploaded.</p>
                    @endif

                    <input type="file" name="profile_picture" id="profile_picture" class="form-control">
                </div>

                <div class="text-end">
                    <button type="submit" class="btn-blue">Save Changes</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="mb-3">Change Password</h5>
            <form action="{{ route('profile.password', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <x-password-field
                    id="new-password"
                    name="password"
                    label="New Password"
                    autocomplete="new-password"
                    input-class="form-control"
                />

                <x-password-field
                    id="new-password-confirm"
                    name="password_confirmation"
                    label="Confirm New Password"
                    autocomplete="new-password"
                    input-class="form-control"
                />

                <div class="text-end">
                    <button type="submit" class="btn-blue">Update Password</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
