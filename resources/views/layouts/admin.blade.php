<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Cleansetz Shoe Care') }} - Admin Dashboard</title>

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
            --sidebar-width: 260px;
        }

        body {
            font-family: 'Jost', sans-serif;
            background-color: var(--secondary);
            color: var(--text-light);
            letter-spacing: 0.3px;
            overflow-x: hidden;
            display: flex;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            color: #ffffff;
        }

        /* Sidebar Sidebar Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background-color: var(--secondary-light);
            border-right: 1px solid rgba(255,255,255,0.05);
            min-height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            padding-top: 2rem;
            z-index: 1000;
            transition: all 0.3s ease;
        }

        .sidebar-brand {
            font-family: 'Jost', sans-serif;
            font-size: 1.6rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-decoration: none;
            display: block;
            text-align: center;
            margin-bottom: 2.5rem;
        }
        
        .sidebar-brand span {
            color: var(--primary);
        }

        .nav-item {
            margin-bottom: 0.5rem;
        }

        .nav-link {
            color: var(--text-muted);
            padding: 0.8rem 1.5rem;
            display: flex;
            align-items: center;
            font-weight: 400;
            font-size: 0.95rem;
            letter-spacing: 0.5px;
            transition: all 0.3s ease;
            text-decoration: none;
            border-left: 3px solid transparent;
        }

        .nav-link i {
            width: 24px;
            text-align: center;
            margin-right: 12px;
            font-size: 1.1rem;
            opacity: 0.7;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary);
            background-color: rgba(244, 114, 182, 0.05);
            border-left-color: var(--primary);
        }

        .nav-link:hover i, .nav-link.active i {
            opacity: 1;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            margin-left: var(--sidebar-width);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Topbar */
        .topbar {
            background-color: rgba(18, 18, 18, 0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.05);
            padding: 1rem 2rem;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 999;
        }

        /* Card Customization */
        .card {
            background-color: var(--secondary-light);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            color: var(--text-light);
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(255,255,255,0.05);
            padding: 1.2rem 1.5rem;
        }

        .btn-primary {
            background-color: var(--primary);
            color: #ffffff;
            font-weight: 500;
            border-radius: 4px;
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

        .btn-outline-custom {
            border: 1px solid rgba(255,255,255,0.3);
            color: #ffffff;
            font-weight: 400;
            border-radius: 4px;
            font-size: 0.85rem;
            transition: all 0.4s ease;
        }

        .btn-outline-custom:hover {
            border-color: var(--primary);
            background-color: transparent;
            color: var(--primary);
        }
        
        /* Dropdown profile */
        .dropdown-menu {
            background-color: var(--secondary-light);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 8px;
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

        .table {
            color: var(--text-light);
        }
        .table th, .table td {
            border-color: rgba(255,255,255,0.05);
            background-color: transparent !important;
        }
        .table-hover tbody tr:hover {
            background-color: rgba(255,255,255,0.02) !important;
            color: #fff;
        }

        @media (max-width: 991px) {
            .sidebar {
                transform: translateX(-100%);
            }
            .sidebar.show {
                transform: translateX(0);
            }
            .main-content {
                margin-left: 0;
            }
            .mobile-toggle {
                display: block !important;
            }
        }
        .mobile-toggle {
            display: none;
            background: none;
            border: none;
            color: #fff;
            font-size: 1.5rem;
            margin-right: auto;
        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
        <a href="{{ url('/') }}" class="sidebar-brand">Cleansetz<span>.</span></a>
        
        <div class="px-4 mb-3">
            <p class="text-uppercase text-muted small fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.7rem;">Menu Utama</p>
        </div>

        <nav class="nav flex-column mb-4">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-home"></i> Dashboard
            </a>
            <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" href="{{ route('admin.orders.index') }}">
                <i class="fas fa-box-open"></i> Kelola Order
            </a>
            <a class="nav-link {{ request()->routeIs('admin.treatments.*') ? 'active' : '' }}" href="{{ route('admin.treatments.index') }}">
                <i class="fas fa-spray-can"></i> Layanan Treatment
            </a>
        </nav>

        <div class="px-4 mb-3 mt-4">
            <p class="text-uppercase text-muted small fw-bold mb-1" style="letter-spacing: 1px; font-size: 0.7rem;">Operasional</p>
        </div>
        <nav class="nav flex-column">
            <a class="nav-link {{ request()->routeIs('admin.expenses.*') ? 'active' : '' }}" href="{{ route('admin.expenses.index') }}">
                <i class="fas fa-wallet"></i> Pengeluaran
            </a>
            <a class="nav-link {{ request()->routeIs('admin.price-requests.*') ? 'active' : '' }}" href="{{ route('admin.price-requests.index') }}">
                <i class="fas fa-tags"></i> Request Harga
            </a>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <header class="topbar">
            <button class="mobile-toggle" onclick="document.getElementById('sidebar').classList.toggle('show')">
                <i class="fas fa-bars"></i>
            </button>
            
            <div class="dropdown">
                <a class="d-flex align-items-center text-decoration-none dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 35px; height: 35px;">
                        <i class="fas fa-user-tie"></i>
                    </div>
                    <div>
                        <span class="d-block text-white fw-medium" style="font-size: 0.9rem;">{{ Auth::user()->name }}</span>
                        <span class="d-block text-muted" style="font-size: 0.75rem; text-transform: uppercase; letter-spacing: 1px;">{{ Auth::user()->role }}</span>
                    </div>
                </a>
                <ul class="dropdown-menu dropdown-menu-end mt-2 border-0 shadow-lg">
                    <li><a class="dropdown-item py-2" href="#"><i class="fas fa-user-circle me-2 opacity-50"></i> Profil Saya</a></li>
                    <li><hr class="dropdown-divider border-secondary"></li>
                    <li>
                        <a class="dropdown-item py-2 text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fas fa-sign-out-alt me-2 opacity-50"></i> Keluar
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        <!-- Page Content -->
        <main class="flex-grow-1 p-4 p-md-5">
            @yield('content')
        </main>
        
        <!-- Footer -->
        <footer class="py-4 px-5 border-top" style="border-color: rgba(255,255,255,0.05) !important;">
            <p class="text-muted small mb-0 text-md-end">&copy; {{ date('Y') }} Cleansetz Premium Care.</p>
        </footer>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
