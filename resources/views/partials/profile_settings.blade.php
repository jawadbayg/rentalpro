@extends('layouts.app')

@section('content')
<div class="container mt-2 profile_settings_container">
    <h2>Profile Settings</h2>

    <div class="card">
        <div class="card-body">
        <form action="{{ route('profile.upload', $user->id) }}" method="POST" enctype="multipart/form-data">

                @csrf

                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ $user->name }}" readonly>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" id="email" name="email" class="form-control" value="{{ $user->email }}" readonly>
                </div>

                <!-- Profile Picture -->
                <!-- Profile Picture -->
                <div class="mb-3">
                    <label for="profile_picture" class="form-label">Profile Picture</label><br>
                    @if($user->profile && $user->profile->profile_picture)
                        <img src="{{ asset('storage/' . $user->profile->profile_picture) }}" alt="Profile Picture" width="150" class="mb-3"><br>
                    @else
                        <p>No profile picture uploaded.</p>
                    @endif

                    <input type="file" name="profile_picture" id="profile_picture" class="form-control">
                </div>


                <!-- Save Button -->
                <div class="text-end">
                    <button type="submit" class="btn-black">Save Changes</button>
                </div>

            </form>
        </div>
    </div>
</div>
@endsection
