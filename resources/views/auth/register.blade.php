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
            <div class="logo-container d-flex flex-column align-items-center w-100">
                <div class="logo w-100 text-start" style="max-width: 560px; padding-top: 30px; margin-bottom: -5px;">
                    <h1>Rental Pro</h1>
                </div>
                <div class="login-p w-100 text-start" style="max-width: 560px;">
                    <h2>Login or Signup to access full service</h2>
                </div>
            </div>

            <div class="d-flex justify-content-center login-form">
                <form method="POST" action="{{ route('register') }}" class="w-100" style="max-width: 560px;">
                    @csrf

                    <!-- Name -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                        <input id="name" type="text" 
                               class="form-control form-control-lg custom-input @error('name') is-invalid @enderror" 
                               name="name" value="{{ old('name') }}" 
                                autocomplete="name" autofocus>
                        @error('name')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                        <input id="email" type="text" 
                               class="form-control form-control-lg custom-input @error('email') is-invalid @enderror" 
                               name="email" value="{{ old('email') }}" 
                                autocomplete="email">
                        @error('email')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                        <input id="password" type="password" 
                               class="form-control form-control-lg custom-input @error('password') is-invalid @enderror" 
                               name="password"  autocomplete="new-password">
                        @error('password')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-3">
                        <label for="password-confirm" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                        <input id="password-confirm" type="password" 
                                class="form-control form-control-lg custom-input @error('password') is-invalid @enderror" 
                               name="password_confirmation"  autocomplete="new-password">
                               @error('password')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                   
                    <!-- Address Field (Initially Hidden) -->
                    <div class="mb-3" id="address-field" style="display: none;">
                        <label for="address" class="form-label">Address <span class="text-danger">*</span></label>
                        <input id="address" type="text"
                            class="form-control form-control-lg custom-input @error('address') is-invalid @enderror"
                            name="address" value="{{ old('address') }}" autocomplete="address">
                        @error('address')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>


                    <!-- Role Selection -->
                    <div class="mb-3">
                        <label class="form-label">Register As <span class="text-danger">*</span></label>
                        <div class="row">
                            <div class="col-6">
                            <div class="form-check col">
                                <input type="radio" class="form-check-input" id="role_user" name="role" value="User"
                                    {{ old('role') == 'User' ? 'checked' : '' }}>
                                <label class="form-check-label" for="role_user">Customer</label>
                            </div>
                            </div>
                            <div class="col-6">
                            <div class="form-check col">
                                <input type="radio" class="form-check-input" id="role_fp" name="role" value="FP"
                                    {{ old('role') == 'FP' ? 'checked' : '' }}>
                                <label class="form-check-label" for="role_fp">Fleet Provider</label>
                            </div>
                            </div>
                        </div>
                        @error('role')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
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
    padding-top: 30px;
    background-color: white;
}
.logo-container {
    /* padding-left: 80px; */
    /* padding-top: 50px; */
}
.login-p {
    /* padding-left: 16px; */
    font-family: 'Poppins', sans-serif;
    font-size: 38px;
    line-height: 50px;
    font-weight: 500;
    margin-top: 15px;
}
.logo{
    font-family: 'Poppins',sans-serif;
    color: rgb(1, 35, 46);
    padding-left: 0px !important;
    margin-left: 0px !important;
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const roleFP = document.getElementById('role_fp');
        const roleUser = document.getElementById('role_user');
        const addressField = document.getElementById('address-field');

        function toggleAddressField() {
            if (roleFP.checked) {
                addressField.style.display = 'block';
            } else {
                addressField.style.display = 'none';
            }
        }

        roleFP.addEventListener('change', toggleAddressField);
        roleUser.addEventListener('change', toggleAddressField);
        toggleAddressField();
    });
</script>



@endsection
