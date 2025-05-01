@extends('layouts.app')
@section('content')
<div class="container-fluid">
    <div class="row min-vh-100">
        <div class="col-md-6 d-none d-md-block p-0">
            <div class="h-100 w-100">
                <img src="https://i.ibb.co/XkbPZwgh/ivana-cajina-WPVt-T0-MEM00-unsplash.jpg" 
                     alt="Login Illustration" 
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
            
                <form method="POST" action="{{ route('login') }}" class="w-100" style="max-width: 560px;">
                    @csrf
                    <!-- Email -->
                    <div class="mb-3">
                        <label for="email" class="form-label">
                            {{ __('Email Address') }} <span class="text-danger">*</span>
                        </label>
                        <input id="email" type="email" 
                        class="form-control form-control-lg custom-input @error('email') is-invalid @enderror" 
                            name="email" value="{{ old('email') }}" 
                            autocomplete="email" autofocus>
                        @error('email')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label">
                            {{ __('Password') }} <span class="text-danger">*</span>
                        </label>
                        <input id="password" type="password" 
                        class="form-control form-control-lg custom-input @error('password') is-invalid @enderror" 
                            name="password"  autocomplete="current-password">
                        @error('password')
                            <span class="text-danger" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="row align-items-center mb-3">
                        <div class="col-md-6">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" 
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                        </div>
                        <div class="col-md-6 text-end">
                            @if (Route::has('password.request'))
                                <a class="sign-up-btn p-0" href="{{ route('password.request') }}">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="d-grid mb-3">
                        <button type="submit" class="btn-blue btn btn-lg">
                            {{ __('Login') }}
                        </button>
                    </div>
                    <div class="register-msg text-center">
                        <p>Don't you have an Account? <a href="{{ route('register') }}" class="sign-up-btn">Sign up now</a></p>
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
.form-container{
    padding-top: 30px;
    background-color: white
}
.logo-container {
    top: 0;
    left: 0;
    right: 0;
    padding: 50px 15px; /* responsive vertical/horizontal padding */
    width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.logo-container > div {
    max-width: 560px;  /* match the form width */
    width: 100%;
    text-align: center;
}

.login-p {
    font-family: 'Poppins', sans-serif;
    font-size: 38px;
    line-height: 50px;
    font-weight: 500;
    margin-top: 15px;
}
.abc{
    font-family: 'Poppins', sans-serif;
    margin-top: -15px;
    margin-bottom: 50px;
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
/* .custom-input{
    width: 570px;
} */
.logo{
    font-family: 'Poppins',sans-serif;
    color: rgb(1, 35, 46);
    padding-left: 0px !important;
    margin-left: 0px !important;
}
</style>
@endsection
