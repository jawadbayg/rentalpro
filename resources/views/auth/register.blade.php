@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row min-vh-100">
        <div class="col-md-6 d-none d-md-block p-0">
            <div class="h-100 w-100">
                <img src="https://i.ibb.co/XkbPZwgh/ivana-cajina-WPVt-T0-MEM00-unsplash.jpg" 
                     alt="Register Illustration" 
                     class="img-cover h-100 w-100">
            </div>
        </div>

        <div class="col-md-6 position-relative form-container">
            <div class="position-absolute logo-container">
                <img src="{{ asset('logo-black.png') }}" style="width: 120px; height: auto;">
                <div class="login-p">
                    <p> Register to access full service</p>
                </div>
                <div class="abc">
                    <p>Fill in your details to create an account.</p>
                </div>
            </div>

            <div class="d-flex justify-content-center h-100 login-form">
                <form method="POST" action="{{ route('register') }}" class="w-100" style="max-width: 560px;">
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input id="name" type="text" 
                               class="form-control form-control-lg custom-input @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" 
                               required autocomplete="name" autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input id="email" type="email" 
                               class="form-control form-control-lg custom-input @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" 
                               required autocomplete="email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
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
                        <label for="password-confirm" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input id="password-confirm" type="password" 
                               class="form-control form-control-lg custom-input" 
                               name="password_confirmation" required autocomplete="new-password">
                    </div>

                    <!-- Role Selection -->
                    <div class="mb-3">
                        <label class="form-label">Register As <span class="text-danger">*</span></label>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="role_fp" name="role" value="FP" checked>
                            <label class="form-check-label" for="role_fp">Fleet Provider</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" class="form-check-input" id="role_user" name="role" value="User">
                            <label class="form-check-label" for="role_user">Customer</label>
                        </div>
                    </div>

                    <!-- Register Button -->
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn-blue btn btn-lg">
                            {{ __('Register') }}
                        </button>
                    </div>

                    <div class="register-msg text-center">
                        <p>Already have an account? <a href="{{ route('login') }}" class="sign-up-btn">Login now</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.img-cover {
    object-fit: cover;
    object-position: center;
}
.form-container {
    background-color: white;
}
.logo-container {
    padding-left: 80px;
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
    padding-top: 280px;
}
.register-msg p {
    font-family: 'Poppins', sans-serif;
}
.sign-up-btn {
    color: rgb(1, 35, 46);
    font-weight: 600;
    text-decoration: none;
}
.sign-up-btn:hover {
    text-decoration: underline;
}
.custom-input {
    width: 570px;
}
</style>
@endsection
