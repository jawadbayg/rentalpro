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
            <div class="logo-container d-flex flex-column align-items-center w-100">
            <div class="logo w-100 text-start" style="max-width: 560px; padding-top: 30px; margin-bottom: -5px;">
                <h1>Rental Pro</h1>
            </div>
            <div class="login-p w-100 text-start" style="max-width: 560px;">
                <h2>No worries! Enter your email and we’ll send you a reset link</h2>
            </div>
        </div>

            <div class="d-flex justify-content-center login-form">
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
    /* padding-left: 85px; */
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
.logo{
    font-family: 'Poppins',sans-serif;
    color: rgb(1, 35, 46);
    padding-left: 0px !important;
    margin-left: 0px !important;
}
.login-form {
    padding-top: 50px;
}
/* .custom-input {
    width: 570px;
} */

</style>
@endsection
