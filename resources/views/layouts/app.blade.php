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
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700&family=Outfit:wght@500;600;700;800&display=swap" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    <!-- In the <head> section -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- At the end of your <body> section, before closing </body> -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    
    <style>
        /* Sidebar & admin content: see app.css (.sidebar-app, .content-area) */
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

        .rental-navbar {
            background: linear-gradient(135deg, #01232e 0%, #0a3d4f 50%, #062a36 100%) !important;
            z-index: 1030;
            transition: box-shadow 0.25s ease;
        }
        .rental-navbar .nav-link {
            position: relative;
            padding: 0.5rem 0.85rem !important;
            transition: color 0.2s ease, transform 0.2s ease;
        }
        .rental-navbar .nav-link::after {
            content: '';
            position: absolute;
            left: 0.85rem;
            right: 0.85rem;
            bottom: 0.2rem;
            height: 2px;
            background: rgba(255, 255, 255, 0.85);
            transform: scaleX(0);
            transition: transform 0.25s ease;
        }
        .rental-navbar .nav-link:hover {
            color: #fff !important;
            transform: translateY(-1px);
        }
        .rental-navbar .nav-link:hover::after {
            transform: scaleX(1);
        }
        .rental-navbar .navbar-brand .logo-btn {
            font-family: 'Outfit', 'Poppins', sans-serif;
            font-weight: 700;
            letter-spacing: -0.02em;
        }
    </style>
</head>
<body>
    <div id="app">
        @if (!in_array(Route::currentRouteName(), ['login', 'register', 'password.request', 'password.reset']))
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-md navbar-dark rental-navbar sticky-top shadow-sm">

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
                                    <a class="nav-link" href="{{ route('about.us.index') }}">{{ __('About us') }}</a>
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
                        <div class="sidebar sidebar-app">
                            <div class="sidebar-app__brand">
                                <button type="button" onclick="window.location.href='{{ url('/home') }}'" class="logo-btn sidebar-app__logo">
                                    Rental Pro
                                </button>
                                <span class="sidebar-app__badge">Admin</span>
                            </div>
                    <nav class="sidebar-app__nav">
                    <a href="{{ route('users.index') }}"><i class="fas fa-users"></i> Manage Users</a>
                    <a href="{{ route('roles.index') }}"><i class="fas fa-user-shield"></i> Manage Roles</a>
                    <a href="{{ route('fleet.index') }}"><i class="fas fa-car"></i> Manage Fleet</a>
                    <a href="{{ route('customer.bookings.index') }}"><i class="fas fa-calendar-days"></i> Bookings</a>
                    <a href="{{ route('invoices.index') }}"><i class="fas fa-file-invoice-dollar"></i> Invoices</a>
                    <a href="{{ route('payments.index') }}"><i class="fas fa-credit-card"></i> Payments</a>
                    <a href="{{ route('verification_requests.index') }}"><i class="fas fa-clipboard-check"></i> Verification</a>
                    </nav>
                    </div>
                    @elseif (Auth::user()->hasRole('User'))

                    @elseif (Auth::user()->hasRole('FP'))
                    <div class="sidebar sidebar-app">
                        <div class="sidebar-app__brand">
                            <button type="button" onclick="window.location.href='{{ url('/home') }}'" class="logo-btn sidebar-app__logo">
                                Rental Pro
                            </button>
                            <span class="sidebar-app__badge">Provider</span>
                        </div>
                        <nav class="sidebar-app__nav">
                        <a href="{{ route('fleet.index') }}"><i class="fas fa-car"></i> Manage Fleet</a>
                        <a href="{{ route('customer.bookings.index') }}"><i class="fas fa-calendar-days"></i> Bookings</a>
                        <a href="{{ route('invoices.index') }}"><i class="fas fa-file-invoice-dollar"></i> Invoices</a>
                        <a href="{{ route('payments.index') }}"><i class="fas fa-credit-card"></i> Payments</a>
                        </nav>
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
