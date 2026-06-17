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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    
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
    @php
        $authRoutes = ['login', 'register', 'password.request', 'password.reset'];
        $hideNavbar = in_array(Route::currentRouteName(), $authRoutes);
        $isAdminShell = Auth::check() && (Auth::user()->hasRole('Admin') || Auth::user()->hasRole('FP'));
    @endphp

    <div id="app" @class(['admin-shell' => $isAdminShell])>
        @if (!$hideNavbar && !$isAdminShell)
            <nav class="navbar navbar-expand-md navbar-dark rental-navbar sticky-top shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <button class="logo-btn">Rental Pro</button>
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                        <ul class="navbar-nav me-auto"></ul>
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
                                        <a class="dropdown-item" href="{{ route('profile.settings', Auth::user()->id) }}">Profile Settings</a>
                                        <a class="dropdown-item" href="{{ route('logout') }}"
                                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
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

        @if ($isAdminShell)
            <aside class="sidebar sidebar-app" id="adminSidebar">
                <div class="sidebar-app__brand">
                    <button type="button" onclick="window.location.href='{{ Auth::user()->hasRole('Admin') ? route('admin.dashboard') : route('home') }}'" class="logo-btn sidebar-app__logo">
                        Rental Pro
                    </button>
                </div>
                <nav class="sidebar-app__nav">
                    @if (Auth::user()->hasRole('Admin'))
                        <a href="{{ route('admin.dashboard') }}"><i class="fas fa-gauge-high"></i> Dashboard</a>
                        <a href="{{ route('users.index') }}"><i class="fas fa-users"></i> Manage Users</a>
                        <a href="{{ route('fleet-providers.index') }}"><i class="fas fa-handshake"></i> Fleet Providers</a>
                        <a href="{{ route('roles.index') }}"><i class="fas fa-user-shield"></i> Manage Roles</a>
                        <a href="{{ route('fleet.index') }}"><i class="fas fa-car"></i> Manage Fleet</a>
                        <a href="{{ route('customer.bookings.index') }}"><i class="fas fa-calendar-days"></i> Bookings</a>
                        <a href="{{ route('invoices.index') }}"><i class="fas fa-file-invoice-dollar"></i> Invoices</a>
                        <a href="{{ route('payments.index') }}"><i class="fas fa-credit-card"></i> Payments</a>
                        <a href="{{ route('verification_requests.index') }}"><i class="fas fa-clipboard-check"></i> Verification</a>
                    @else
                        <a href="{{ route('home') }}"><i class="fas fa-gauge-high"></i> Dashboard</a>
                        <a href="{{ route('fleet.index') }}"><i class="fas fa-car"></i> Manage Fleet</a>
                        <a href="{{ route('customer.bookings.index') }}"><i class="fas fa-calendar-days"></i> Bookings</a>
                        <a href="{{ route('invoices.index') }}"><i class="fas fa-file-invoice-dollar"></i> Invoices</a>
                        <a href="{{ route('payments.index') }}"><i class="fas fa-credit-card"></i> Payments</a>
                    @endif
                </nav>
            </aside>

            <div class="admin-shell__backdrop" id="adminSidebarBackdrop" hidden></div>

            <div class="admin-shell__main">
                <header class="admin-topbar shadow-sm">
                    <div class="admin-topbar__inner">
                        <button class="admin-topbar__toggle" type="button" id="adminSidebarToggle" aria-label="Toggle sidebar" aria-controls="adminSidebar" aria-expanded="false">
                            <i class="fas fa-bars"></i>
                        </button>
                        <p class="admin-topbar__title mb-0">
                            {{ Auth::user()->hasRole('Admin') ? 'Admin Dashboard' : 'Provider Dashboard' }}
                        </p>
                        <div class="admin-topbar__actions">
                            <div class="dropdown">
                                <a href="#" class="admin-topbar__user dropdown-toggle" id="adminUserMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="fas fa-user-circle"></i>
                                    <span>{{ Auth::user()->name }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="adminUserMenu">
                                    <a class="dropdown-item" href="{{ route('profile.settings', Auth::user()->id) }}">Profile Settings</a>
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>
                                </div>
                            </div>
                            <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </header>

                <main class="content-area">
                    @yield('content')
                </main>
            </div>
        @else
            <main class="content-area @if(Auth::guest() || (Auth::check() && Auth::user()->hasRole('User'))) content-area-guest @endif">
                @yield('content')
            </main>
        @endif

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

        @if ($isAdminShell)
            <script>
                (function () {
                    var userMenu = document.getElementById('adminUserMenu');
                    if (userMenu && window.bootstrap) {
                        bootstrap.Dropdown.getOrCreateInstance(userMenu);
                    }

                    var sidebar = document.getElementById('adminSidebar');
                    var toggle = document.getElementById('adminSidebarToggle');
                    var backdrop = document.getElementById('adminSidebarBackdrop');

                    if (!sidebar || !toggle || !backdrop) {
                        return;
                    }

                    function closeSidebar() {
                        sidebar.classList.remove('is-open');
                        backdrop.hidden = true;
                        toggle.setAttribute('aria-expanded', 'false');
                        document.body.classList.remove('admin-sidebar-open');
                    }

                    function openSidebar() {
                        sidebar.classList.add('is-open');
                        backdrop.hidden = false;
                        toggle.setAttribute('aria-expanded', 'true');
                        document.body.classList.add('admin-sidebar-open');
                    }

                    toggle.addEventListener('click', function () {
                        if (sidebar.classList.contains('is-open')) {
                            closeSidebar();
                        } else {
                            openSidebar();
                        }
                    });

                    backdrop.addEventListener('click', closeSidebar);
                    window.addEventListener('resize', function () {
                        if (window.innerWidth >= 992) {
                            closeSidebar();
                        }
                    });
                })();
            </script>
        @endif

        <script>
            document.addEventListener('click', function (event) {
                var toggle = event.target.closest('.password-field__toggle');
                if (!toggle) {
                    return;
                }

                var input = document.getElementById(toggle.getAttribute('data-target'));
                if (!input) {
                    return;
                }

                var isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
                toggle.classList.toggle('is-visible', isPassword);
                toggle.setAttribute('aria-label', isPassword ? 'Hide password' : 'Show password');
            });
        </script>
    </div>
</body>
</html>
