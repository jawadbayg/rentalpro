<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />
    
    <style>
        /* Sidebar styling */
        .sidebar {
            height: 100vh;
            background: #f8f9fa;
            padding: 20px;
            position: fixed;
            width: 250px;
            top: 70px; /* adjust if navbar height changes */
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
        }
        .sidebar a:hover {
            background: #343a40;
            color: #fff;
        }
        .content-area {
            margin-left: 270px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div id="app">
        <!-- Top Navbar -->
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    Rental Pro
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto"></ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
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

        <!-- Sidebar -->
        @if (Auth::user()->hasRole('Admin'))
        <div class="sidebar">
            <a href="{{ route('users.index') }}"><i class="fa fa-users"></i> Manage Users</a>
            <a href="{{ route('roles.index') }}"><i class="fa fa-user-shield"></i> Manage Roles</a>
            <a href="{{ route('products.index') }}"><i class="fa fa-box"></i> Manage Products</a>
        </div>
        @elseif (Auth::user()->hasRole('User'))
        <div class="sidebar">
            <a href="{{ route('users.index') }}"><i class="fa fa-users"></i> Manage Users</a>
            
        </div>
        @endif
        <!-- Main Content -->
        <main class="content-area">
            @yield('content')
        </main>
        
    </div>
</body>
</html>
