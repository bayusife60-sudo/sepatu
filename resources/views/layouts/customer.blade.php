<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard') | Cleansetz</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #f472b6;
            --primary-dark: #db2777;
            --bg: #0f0f0f;
            --bg-card: #1a1a1a;
            --bg-card2: #222222;
            --text: #e0e0e0;
            --muted: #888;
        }

        * { box-sizing: border-box; }

        body {
            font-family: 'Jost', sans-serif;
            background-color: var(--bg);
            color: var(--text);
            margin: 0;
            min-height: 100vh;
        }

        /* Navbar */
        .top-nav {
            background-color: rgba(26,26,26,0.95);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255,255,255,0.06);
            padding: 0.9rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-brand {
            font-family: 'Jost', sans-serif;
            font-size: 1.4rem;
            font-weight: 700;
            color: #ffffff;
            letter-spacing: 2px;
            text-transform: uppercase;
            text-decoration: none;
        }

        .nav-brand span { color: var(--primary); }

        .nav-user {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .avatar-sm {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.85rem;
            color: #fff;
            flex-shrink: 0;
        }

        .user-name {
            font-size: 0.9rem;
            font-weight: 500;
            color: #e0e0e0;
        }

        .btn-logout {
            background: transparent;
            border: 1px solid rgba(255,255,255,0.15);
            color: #a0a0a0;
            font-size: 0.78rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            padding: 0.4rem 1rem;
            border-radius: 4px;
            transition: all 0.3s;
            text-decoration: none;
        }

        .btn-logout:hover {
            border-color: var(--primary);
            color: var(--primary);
        }

        /* Main layout */
        .main-content {
            padding: 2.5rem 0;
        }

        /* Cards */
        .card {
            background-color: var(--bg-card);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 12px;
        }

        .card-header {
            background-color: transparent;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            padding: 1.1rem 1.5rem;
        }

        .stat-card {
            background: var(--bg-card);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 12px;
            padding: 1.5rem;
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            border-color: rgba(244,114,182,0.3);
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            color: #fff;
            line-height: 1;
            font-family: 'Playfair Display', serif;
        }

        .stat-label {
            font-size: 0.78rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--muted);
            margin-top: 0.4rem;
        }

        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
        }

        /* Table */
        .table { color: var(--text); }
        .table thead th {
            font-size: 0.72rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: var(--muted);
            border-bottom: 1px solid rgba(255,255,255,0.08);
            padding: 0.9rem 1rem;
            font-weight: 500;
        }
        .table tbody td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.04);
            vertical-align: middle;
        }
        .table tbody tr:last-child td { border-bottom: none; }
        .table-hover tbody tr:hover { background-color: rgba(255,255,255,0.02); }

        /* Badges */
        .badge-status {
            font-size: 0.7rem;
            padding: 0.35em 0.8em;
            border-radius: 20px;
            font-weight: 500;
            letter-spacing: 0.5px;
        }

        /* Pagination */
        .pagination .page-link {
            background-color: var(--bg-card);
            border-color: rgba(255,255,255,0.08);
            color: #a0a0a0;
            font-size: 0.85rem;
        }
        .pagination .page-item.active .page-link {
            background-color: var(--primary);
            border-color: var(--primary);
            color: #fff;
        }
        .pagination .page-link:hover {
            background-color: rgba(244,114,182,0.1);
            color: var(--primary);
        }

        /* No order state */
        .empty-state {
            padding: 4rem 2rem;
            text-align: center;
        }

        .empty-icon {
            font-size: 3rem;
            color: rgba(255,255,255,0.1);
            margin-bottom: 1rem;
        }

        /* Progress bar custom */
        .progress {
            background-color: rgba(255,255,255,0.07);
            height: 6px;
            border-radius: 3px;
        }
        .progress-bar {
            background: linear-gradient(90deg, var(--primary), var(--primary-dark));
        }
    </style>

    @yield('styles')
</head>
<body>

    <!-- Navbar -->
    <nav class="top-nav">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <a class="nav-brand" href="{{ url('/') }}">Cleansetz<span>.</span></a>

                <div class="nav-user">
                    <div class="avatar-sm">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                    <span class="user-name d-none d-md-block">{{ auth()->user()->name ?? 'Pelanggan' }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline mb-0">
                        @csrf
                        <button type="submit" class="btn-logout">
                            <i class="fas fa-sign-out-alt me-1"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <div class="container">
            @yield('content')
        </div>
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>
</html>
