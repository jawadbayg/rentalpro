@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row min-vh-100">
        <!-- Left Side Image -->
        <div class="col-md-6 d-none d-md-block p-0">
            <div class="h-100 w-100">
                <img src="https://img.ge/i/W0CKG96.jpg" 
                     alt="Forgot Password Illustration" 
                     class="img-cover h-100 w-100">
            </div>
        </div>

        <!-- Right Side Form -->
        <div class="col-md-6 position-relative form-container">
            <div class="position-absolute logo-container">
                <img src="{{ asset('logo-black.png') }}" style="width: 120px; height: auto;">
                <div class="login-p">
                    <p>Forgot Your Password?</p>
                </div>
                <div class="abc">
                    <p>No worries! Enter your email and we’ll send you a reset link.</p>
                </div>
            </div>

            <div class="d-flex justify-content-center h-100 login-form">
                <form method="POST" action="{{ route('password.email') }}" class="w-100" style="max-width: 570px;">
                    @csrf

                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <!-- Email Input -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            {{ __('Email Address') }} <span class="text-danger">*</span>
                        </label>
                        <input id="email" type="email" 
                               class="form-control form-control-lg custom-input @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" 
                               required autocomplete="email" autofocus>

                        @error('email')
                            <span class="invalid-feedback d-block mt-1" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn-blue btn btn-lg">
                            {{ __('Send Password Reset Link') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reuse your custom form styles -->
<style>
.img-cover {
    object-fit: cover;
    object-position: center;
}
.form-container {
    background-color: white;
}
.logo-container {
    padding-left: 85px;
    padding-top: 50px;
}
.login-p {
    padding-left: 16px;
    font-family: 'Poppins', sans-serif;
    font-size: 38px;
    line-height: 50px;
    font-weight: 500;
    margin-top: 15px;
}
.abc {
    font-family: 'Poppins', sans-serif;
    margin-top: -15px;
    padding-left: 16px;
    margin-bottom: 50px;
}
.login-form {
    padding-top: 320px;
}
.custom-input {
    width: 570px;
}

</style>
@endsection
