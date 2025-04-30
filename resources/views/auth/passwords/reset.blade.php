@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row min-vh-100">
        <!-- Left Side Image -->
        <div class="col-md-6 d-none d-md-block p-0">
            <div class="h-100 w-100">
                <img src="https://img.ge/i/W0CKG96.jpg" 
                     alt="Reset Illustration" 
                     class="img-cover h-100 w-100">
            </div>
        </div>
       

        <!-- Right Side Form -->
        <div class="col-md-6 position-relative form-container">
            <div class="logo-container d-flex flex-column align-items-center w-100">
                <div class="logo w-100 text-start" style="max-width: 560px; padding-top: 30px; margin-bottom: -5px;">
                    <h1>Rental Pro</h1>
                </div>
                <div class="login-p w-100 text-start" style="max-width: 560px;">
                    <h2>Enter your new password below to reset your account</h2>
                </div>
            </div>

            <div class="d-flex justify-content-center  login-form">
                <form method="POST" action="{{ route('password.update') }}" class="w-100" style="max-width: 560px;">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            {{ __('Email Address') }} <span class="text-danger">*</span>
                        </label>
                        <input id="email" type="email" 
                            class="form-control form-control-lg custom-input @error('email') is-invalid @enderror" 
                            name="email" value="{{ $email ?? old('email') }}" 
                            required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            {{ __('Password') }} <span class="text-danger">*</span>
                        </label>
                        <input id="password" type="password" 
                            class="form-control form-control-lg custom-input @error('password') is-invalid @enderror" 
                            name="password" required autocomplete="new-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label for="password-confirm" class="form-label">
                            {{ __('Confirm Password') }} <span class="text-danger">*</span>
                        </label>
                        <input id="password-confirm" type="password" 
                            class="form-control form-control-lg custom-input" 
                            name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <!-- Submit Button -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn-blue btn btn-lg">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Reuse your existing login page styles -->
<style>
.img-cover {
    object-fit: cover;
    object-position: center;
}
.form-container {
    background-color: white;
}
.logo-container {
    padding-top: 50px;
}
.login-p {
    /* padding-left: 16px; */
    font-family: 'Poppins', sans-serif;
    font-size: 38px;
    line-height: 50px;
    font-weight: 500;
    margin-top: 15px;
}
.abc {
    font-family: 'Poppins', sans-serif;
    margin-top: -15px;
    /* padding-left: 16px; */
    margin-bottom: 50px;
}
.login-form {
    padding-top: 50px;
}
.logo{
    font-family: 'Poppins',sans-serif;
    color: rgb(1, 35, 46);
    padding-left: 0px !important;
    margin-left: 0px !important;
}
</style>
@endsection
