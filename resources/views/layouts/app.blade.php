<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Cleansetz Shoe Care') }}</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #f472b6;       /* Soft Pink */
            --primary-dark: #db2777;  /* Deep Pink */
            --secondary: #121212;     /* Almost Black */
            --secondary-light: #1e1e1e; /* Lighter Black */
            --text-light: #e0e0e0;
            --text-muted: #a0a0a0;
        }

        body {
            font-family: 'Jost', sans-serif;
            background-color: var(--secondary);
            color: var(--text-light);
            letter-spacing: 0.3px;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            color: #ffffff;
        }

        .navbar {
            background-color: rgba(18, 18, 18, 0.98);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.05);
            padding: 1rem 0;
            z-index: 1000;
        }

        .navbar-brand {
            font-family: 'Jost', sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: #ffffff !important;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .navbar-brand span {
            color: var(--primary);
        }

        .nav-link {
            font-weight: 400;
            font-size: 0.95rem;
            color: #ffffff !important;
            margin: 0 1rem;
            position: relative;
            text-transform: uppercase;
            letter-spacing: 1px;
            opacity: 0.8;
            transition: opacity 0.3s ease;
        }

        .nav-link:hover, .nav-link.active {
            opacity: 1;
        }

        .card {
            background-color: var(--secondary-light);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 0;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            color: var(--text-light);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            font-weight: 600;
            padding: 20px 25px;
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            color: #fff;
            text-align: center;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
            font-weight: 500;
            padding: 0.8rem 2rem;
            border-radius: 0;
            border: 1px solid var(--primary);
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 0.85rem;
            transition: all 0.4s ease;
        }

        .btn-primary:hover {
            background-color: transparent;
            color: var(--primary);
        }

        .form-control {
            border-radius: 0;
            padding: 12px 15px;
            border: 1px solid rgba(255,255,255,0.1);
            background-color: var(--secondary);
            color: var(--text-light);
            font-weight: 300;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            box-shadow: none;
            border-color: var(--primary);
            background-color: var(--secondary);
            color: #fff;
        }

        .form-control::placeholder {
            color: rgba(255,255,255,0.2);
        }

        .input-group-text {
            background-color: var(--secondary);
            border: 1px solid rgba(255,255,255,0.1);
            border-right: none;
            color: rgba(255,255,255,0.3);
            border-radius: 0;
        }

        .form-label {
            font-family: 'Jost', sans-serif;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--text-muted);
            font-weight: 500;
            margin-bottom: 0.5rem;
        }

        .text-primary {
            color: var(--primary) !important;
        }
        
        .dropdown-menu {
            background-color: var(--secondary-light);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        .dropdown-item {
            color: var(--text-light);
            font-weight: 300;
            transition: all 0.2s ease;
        }

        .dropdown-item:hover {
            background-color: rgba(244, 114, 182, 0.1);
            color: var(--primary);
        }

        .bg-light {
            background-color: rgba(255,255,255,0.02) !important;
        }

        .text-dark {
            color: #ffffff !important;
        }
        
        main {
            flex: 1;
            display: flex;
            align-items: center;
            padding: 100px 0;
        }
    </style>
</head>
<body>
    <div id="app" class="d-flex flex-column min-vh-100">
        <nav class="navbar navbar-expand-md fixed-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    Cleansetz<span>.</span>
                </a>
                <button class="navbar-toggler border-0 shadow-none text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <i class="fas fa-bars fs-3"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto align-items-center">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">{{ __('Masuk') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item ms-lg-3">
                                    <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}" href="{{ route('register') }}">{{ __('Daftar') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; font-size: 0.8rem;">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <span class="fw-semibold">{{ Auth::user()->name }}</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end mt-3" aria-labelledby="navbarDropdown">
                                    <div class="px-4 py-3 border-bottom border-secondary mb-2 bg-light">
                                        <p class="mb-0 text-muted small" style="text-transform: none; letter-spacing: 0;">Login sebagai</p>
                                        <span class="fw-bold text-primary text-uppercase" style="letter-spacing: 1px; font-size: 0.85rem;">{{ Auth::user()->role }}</span>
                                    </div>
                                    <a class="dropdown-item py-2" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt me-2" style="opacity: 0.5;"></i>{{ __('Keluar') }}
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

        <main>
            @yield('content')
        </main>
        
        <footer class="mt-auto py-4 text-center border-top" style="border-color: rgba(255,255,255,0.05) !important;">
            <p class="text-white     small mb-0">&copy; {{ date('Y') }} Cleansetz Premium Care.</p>
        </footer>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
