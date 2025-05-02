<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- DataTables CSS -->
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- DataTables JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    
    <!-- sweet alert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <!-- In the <head> section -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- At the end of your <body> section, before closing </body> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        /* Sidebar styling */
        .sidebar {
            height: calc(1000vh - 50px); 
            background: rgb(1, 35, 46);
            padding: 20px;
            position: fixed;
            width: 250px;
            top: 0px; /* Adjust if navbar height changes */
            overflow-y: auto;
        }
        .sidebar a {
            display: block;
            color: #333;
            padding: 10px 15px;
            margin-bottom: 10px;
            text-decoration: none;
            font-weight: 600;
            border-radius: 5px;
            color: white;
        }
        .sidebar a:hover {
            background: rgb(20, 97, 174);
            color: #fff;
        }

        /* Main Content */
        .content-area {
            margin-left: 250px; /* Space for the sidebar */
            padding: 20px;
            width: calc(100% - 250px); /* This will ensure content takes the rest of the space */
        }

        /* Mobile View */
        @media (max-width: 768px) {
            .content-area {
                margin-left: 0;
                width: 100%;
            }

            .sidebar {
                position: absolute;
                top: 0;
                left: 0;
                z-index: 10;
                height: 100%; 
            }
        }
        .logo {
            margin-top: -10px;
            margin-bottom: 50px;
            margin-left: 20px;
            color: white;
            font-family: 'Times New Roman', Times, serif;
        }

        .logo-btn {
            font-family: 'Poppins', sans-serif;
            background: none;
            border: none;
            padding: 0;
            margin: 0;
            color: inherit;
            font-size: inherit;
            text-decoration: none;
            cursor: pointer;
        }

        .logo-btn:hover,
        .logo-btn:focus,
        .logo-btn:active {
            background: none;
            border: none;
            outline: none;
            color: inherit;
            text-decoration: none;
            box-shadow: none;
        }
  
        .content-area-guest {
            width: 100%; 
            margin-left: 0;
            padding: 0;
        }
        .navbar .nav-link {
            font-family: 'Poppins', sans-serif !important;
        }
    </style>
</head>
<body>
    <div id="app">
        @if (!in_array(Route::currentRouteName(), ['login', 'register', 'password.request', 'password.reset']))
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-md navbar-dark" style="background: rgb(1, 35, 46);">

            <div class="container">
                @if (Auth::guest() || !Auth::user()->hasRole('Admin') && !Auth::user()->hasRole('FP'))
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <button class="logo-btn">
                            Rental Pro
                        </button>
                    </a>
                @endif

                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto"></ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('About us') }}</a>
                                </li>
                            @endif
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <!-- <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li> -->
                            @endif
                        @else
                            @if (Auth::user()->hasRole('User'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('about.us.index') }}">{{ __('About us') }}</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('customer.bookings.index') }}">{{ __('My Bookings') }}</a>
                                </li>
                            @endif

                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('profile.settings', Auth::user()->id) }}">
                                        Profile Settings
                                    </a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
@endif
                @if (Auth::check())
                    @if (Auth::user()->hasRole('Admin'))
                        <div class="sidebar">
                            <div class="logo">
                                <h3>
                                    <button onclick="window.location.href='{{ url('/home') }}'" class="logo-btn">
                                        Rental Pro
                                    </button>
                                </h3>
                            </div>
    
                    <a href="{{ route('users.index') }}"><i class="fa fa-users"></i> Manage Users</a>
                    <a href="{{ route('roles.index') }}"><i class="fa fa-user-shield"></i> Manage Roles</a>
                    <a href="{{ route('fleet.index') }}"><i class="fa fa-box"></i> Manage Fleet</a>
                    <a href="{{ route('customer.bookings.index') }}"><i class="fa fa-box"></i> Bookings</a>
                    <a href="{{ route('invoices.index') }}"><i class="fa fa-box"></i> Invoices</a>
                    <a href="{{ route('payments.index') }}"><i class="fa fa-box"></i> Payments</a>
                    <a href="{{ route('verification_requests.index') }}"><i class="fa fa-file"></i> Verification Requests</a>
                    </div>
                    @elseif (Auth::user()->hasRole('User'))

                    @elseif (Auth::user()->hasRole('FP'))
                    <div class="sidebar">
                        <div class="logo">
                            <h3>
                                <button onclick="window.location.href='{{ url('/home') }}'" class="logo-btn">
                                    Rental Pro
                                </button>
                            </h3>
                        </div>
                        <a href="{{ route('fleet.index') }}"><i class="fa fa-box"></i> Manage Fleet</a>
                        <a href="{{ route('customer.bookings.index') }}"><i class="fa fa-box"></i> Bookings</a>
                        <a href="{{ route('invoices.index') }}"><i class="fa fa-box"></i> Invoices</a>
                        <a href="{{ route('payments.index') }}"><i class="fa fa-box"></i> Payments</a>
                    </div>
                    @endif
                @endif

                <main class="content-area @if(Auth::guest() || (Auth::check() && Auth::user()->hasRole('User'))) content-area-guest @endif">
                    @yield('content')
                </main>



            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    </div>
</body>
</html>
