<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cleansetz Shoe Care | Perawatan Sepatu Premium</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;1,600&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CDN -->
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
            color: var(--text-light);
            background-color: var(--secondary);
            overflow-x: hidden;
            letter-spacing: 0.3px;
        }

        h1, h2, h3, h4, h5, h6 {
            font-family: 'Playfair Display', serif;
            color: #ffffff;
        }

        /* Transparent Navbar */
        .navbar {
            background-color: transparent;
            padding: 1.5rem 0;
            transition: all 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
            z-index: 1000;
        }
        
        .navbar.scrolled {
            background-color: rgba(18, 18, 18, 0.95);
            backdrop-filter: blur(10px);
            padding: 1rem 0;
            border-bottom: 1px solid rgba(255,255,255,0.05);
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

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 1px;
            background-color: var(--primary);
            transition: width 0.3s ease;
        }

        .nav-link:hover::after, .nav-link.active::after {
            width: 100%;
        }

        .btn-outline-custom {
            border: 1px solid rgba(255,255,255,0.3);
            color: #ffffff;
            font-weight: 400;
            padding: 0.6rem 1.8rem;
            border-radius: 0;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 0.85rem;
            transition: all 0.4s ease;
        }

        .btn-outline-custom:hover {
            border-color: var(--primary);
            background-color: transparent;
            color: var(--primary);
        }

        .btn-solid-custom {
            background-color: var(--primary);
            color: #ffffff;
            font-weight: 500;
            padding: 0.6rem 2rem;
            border-radius: 0;
            border: 1px solid var(--primary);
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 0.85rem;
            transition: all 0.4s ease;
        }

        .btn-solid-custom:hover {
            background-color: transparent;
            color: var(--primary);
        }

        /* Mobile Navbar Styling */
        @media (max-width: 991px) {
            .navbar {
                padding: 1rem 0;
                background-color: rgba(18, 18, 18, 0.98) !important;
                backdrop-filter: blur(15px);
            }
            .navbar-collapse {
                border: none !important;
                margin-top: 0 !important;
                padding: 2rem 0 !important;
                text-align: center;
                height: 100vh;
                display: flex;
                flex-direction: column;
                justify-content: center;
            }
            .nav-link {
                font-size: 1.5rem !important;
                margin: 1.5rem 0 !important;
                display: inline-block;
            }
            .nav-link::after { bottom: -10px; }
            .navbar-nav { margin-bottom: 2rem !important; }
            .mobile-auth-btns {
                flex-direction: column;
                width: 100%;
                padding: 0 2rem;
            }
            .mobile-auth-btns .btn, .mobile-auth-btns .nav-link {
                width: 100%;
                margin: 0.5rem 0 !important;
            }
        }

        /* Hero Parallax */
        .hero {
            position: relative;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
        }

        .hero-bg {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 120%; /* Extra height for parallax */
            background-image: url('https://images.unsplash.com/photo-1549298916-b41d501d3772?q=80&w=2012&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -2;
            will-change: transform;
        }

        .hero-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(18,18,18,0.9) 0%, rgba(18,18,18,0.4) 100%);
            z-index: -1;
        }

        .hero-content {
            text-align: center;
            max-width: 800px;
            padding: 0 20px;
            margin-top: 5rem;
        }

        .hero-subtitle {
            text-transform: uppercase;
            letter-spacing: 4px;
            color: var(--primary);
            font-size: 0.9rem;
            font-weight: 500;
            margin-bottom: 1.5rem;
            display: block;
        }

        .hero-title {
            font-size: 5.5rem;
            line-height: 1.1;
            margin-bottom: 2rem;
            font-style: italic;
        }

        @media (max-width: 768px) {
            .hero-title {
                font-size: 3.2rem;
            }
            .hero-content {
                margin-top: 2rem;
            }
            .hero-desc {
                font-size: 1rem;
                margin-bottom: 2rem;
            }
        }

        .hero-desc {
            font-size: 1.1rem;
            color: var(--text-light);
            margin-bottom: 3rem;
            font-weight: 300;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            opacity: 0.9;
            line-height: 1.8;
        }

        /* Sections General */
        section {
            padding: 120px 0;
        }

        .section-header {
            text-align: center;
            margin-bottom: 5rem;
        }

        .section-subtitle {
            color: var(--primary);
            text-transform: uppercase;
            letter-spacing: 3px;
            font-size: 0.85rem;
            font-weight: 500;
            margin-bottom: 1rem;
            display: block;
        }

        .section-title {
            font-size: 3.5rem;
            margin-bottom: 1.5rem;
        }

        /* Services Minimalist */
        .service-item {
            border-bottom: 1px solid rgba(255,255,255,0.05);
            padding: 3rem 0;
            transition: all 0.4s ease;
        }

        .service-item:first-child {
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .service-item:hover {
            padding-left: 20px;
            border-bottom-color: var(--primary);
        }

        .service-number {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: rgba(255,255,255,0.1);
            font-style: italic;
            transition: color 0.4s ease;
        }

        .service-item:hover .service-number {
            color: var(--primary);
        }

        .service-title {
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            font-family: 'Jost', sans-serif;
            font-weight: 400;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .service-desc {
            color: var(--text-muted);
            font-weight: 300;
            margin: 0;
            line-height: 1.7;
        }

        /* Image Mask / Split Section */
        .split-section {
            padding: 0;
            background-color: var(--secondary-light);
            display: flex;
            flex-wrap: wrap;
        }

        .split-image {
            background-image: url('https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?q=80&w=1974&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            min-height: 500px;
        }

        .split-content {
            padding: 8rem 5rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        /* Large Typography Background */
        .bg-text {
            position: absolute;
            font-size: 15rem;
            font-weight: 900;
            color: rgba(255,255,255,0.02);
            font-family: 'Jost', sans-serif;
            text-transform: uppercase;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            white-space: nowrap;
            z-index: -1;
            user-select: none;
        }

        /* Process Simple List */
        .process-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .process-item {
            display: flex;
            margin-bottom: 3rem;
            position: relative;
        }

        .process-item::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 50px;
            bottom: -3rem;
            width: 1px;
            background-color: rgba(255,255,255,0.1);
        }
        
        .process-item:last-child::before {
            display: none;
        }

        .process-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--secondary);
            border: 1px solid var(--primary);
            color: var(--primary);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-right: 2rem;
            z-index: 1;
        }

        /* Footer */
        footer {
            background-color: var(--secondary-light);
            padding: 5rem 0 2rem;
            border-top: 1px solid rgba(255,255,255,0.05);
        }

        .footer-logo {
            font-size: 2rem;
            font-weight: 700;
            color: #fff;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 1.5rem;
            display: block;
            text-decoration: none;
        }

        .footer-logo span {
            color: var(--primary);
        }

        .social-links a {
            color: var(--text-muted);
            font-size: 1.2rem;
            margin-right: 1.5rem;
            transition: color 0.3s ease;
        }

        .social-links a:hover {
            color: var(--primary);
        }

        @media (max-width: 991px) {
            .hero-title { font-size: 3.5rem; }
            .split-content { padding: 4rem 2rem; }
            .section-title { font-size: 2.5rem; }
            .bg-text { font-size: 6rem; }
        }

        @media (max-width: 576px) {
            .hero-btns {
                flex-direction: column;
                gap: 1rem !important;
            }
            .hero-btns .btn {
                width: 100%;
            }
            .hero-title {
                font-size: 2.8rem;
            }
        }
    </style>
</head>
<body>

    <!-- Transparent Fixed Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                Cleansetz<span>.</span>
            </a>
            
            <button class="navbar-toggler border-0 shadow-none text-white" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
                <i class="fas fa-bars fs-3"></i>
            </button>

            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="#home" onclick="closeMenu()">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services" onclick="closeMenu()">Layanan</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#process" onclick="closeMenu()">Proses</a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center gap-3 mobile-auth-btns">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/home') }}" class="btn btn-solid-custom">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="nav-link d-inline-block ps-0">Masuk</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-outline-custom">Daftar</a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Parallax Hero -->
    <section id="home" class="hero">
        <div class="hero-bg" id="parallax-bg"></div>
        <div class="hero-overlay"></div>
        
        <div class="container position-relative z-index-2">
            <div class="hero-content mx-auto">
                <span class="hero-subtitle">Daily Shoe Care Service</span>
                <h1 class="hero-title">Estetika Dalam Setiap Langkah.</h1>
                <p class="hero-desc">
                    Kami mengembalikan kemewahan alas kaki Anda melalui metodologi pembersihan perfeksionis dan restorasi detail tingkat tinggi. Tanpa kompromi.
                </p>
                <div class="d-flex gap-4 justify-content-center mt-5 hero-btns">
                    <a href="#services" class="btn btn-solid-custom">Eksplorasi Layanan</a>
                    <a href="{{ route('register') }}" class="btn btn-outline-custom">Mulai Reservasi</a>
                </div>
            </div>
        </div>
        
        <div class="position-absolute bottom-0 start-50 translate-middle-x mb-5" style="z-index: 2;">
            <a href="#services" class="text-white text-decoration-none" style="opacity: 0.5;">
                <div class="d-flex flex-column align-items-center">
                    <span class="small text-uppercase tracking-widest mb-2" style="letter-spacing: 2px;">Scroll</span>
                    <i class="fas fa-chevron-down mt-1 animation-bounce"></i>
                </div>
            </a>
            <style>
                @keyframes bounce { 0%, 20%, 50%, 80%, 100% {transform: translateY(0);} 40% {transform: translateY(-10px);} 60% {transform: translateY(-5px);} }
                .animation-bounce { animation: bounce 2s infinite; }
            </style>
        </div>
    </section>

    <!-- Minimalist Services List -->
    <section id="services" class="position-relative">
        <div class="bg-text">LAYANAN</div>
        <div class="container">
            <div class="section-header">
                <span class="section-subtitle">Keahlian Kami</span>
                <h2 class="section-title">Solusi Restorasi Komprehensif</h2>
            </div>
            
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="service-item row align-items-center">
                        <div class="col-md-2 mb-3 mb-md-0">
                            <span class="service-number">01</span>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <h3 class="service-title">Deep Cleaning</h3>
                        </div>
                        <div class="col-md-6">
                            <p class="service-desc">
                                Pembersihan mendetail menyeluruh pada material luar, dalam, dan sol menggunakan formulasi kimia khusus yang aman bagi material sensitif seperti suede dan nubuck.
                            </p>
                        </div>
                    </div>
                    
                    <div class="service-item row align-items-center">
                        <div class="col-md-2 mb-3 mb-md-0">
                            <span class="service-number">02</span>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <h3 class="service-title">Repaint & Dye</h3>
                        </div>
                        <div class="col-md-6">
                            <p class="service-desc">
                                Restorasi pigmen warna yang pudar atau kustomisasi warna total menggunakan cat akrilik premium khusus kulit dan kanvas yang tahan lama dan fleksibel.
                            </p>
                        </div>
                    </div>
                    
                    <div class="service-item row align-items-center">
                        <div class="col-md-2 mb-3 mb-md-0">
                            <span class="service-number">03</span>
                        </div>
                        <div class="col-md-4 mb-3 mb-md-0">
                            <h3 class="service-title">Unyellowing</h3>
                        </div>
                        <div class="col-md-6">
                            <p class="service-desc">
                                Proses oksidasi terbalik menggunakan sinar UV dan krim khusus untuk mengembalikan warna putih murni pada sol karet atau silikon yang menguning akibat usia.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Split Section (Image / Content) -->
    <section class="split-section" id="process">
        <div class="col-lg-6 split-image"></div>
        <div class="col-lg-6 split-content">
            <span class="section-subtitle">Alur Kerja</span>
            <h2 class="section-title mb-5">Minimalis.<br>Efisien.<br>Transparan.</h2>
            
            <ul class="process-list">
                <li class="process-item">
                    <div class="process-icon"><i class="fas fa-calendar-check text-white"></i></div>
                    <div>
                        <h4 class="mb-2" style="font-family: 'Jost', sans-serif; text-transform:uppercase; font-size:1.1rem; letter-spacing:1px;">Reservasi Digital</h4>
                        <p class="text-muted fw-light mb-0">Tentukan layanan dan atur jadwal penjemputan via dashboard.</p>
                    </div>
                </li>
                <li class="process-item">
                    <div class="process-icon"><i class="fas fa-box text-white"></i></div>
                    <div>
                        <h4 class="mb-2" style="font-family: 'Jost', sans-serif; text-transform:uppercase; font-size:1.1rem; letter-spacing:1px;">Pengambilan Kurir</h4>
                        <p class="text-muted fw-light mb-0">Tim logistik kami mengamankan sepatu Anda dari lokasi yang ditentukan.</p>
                    </div>
                </li>
                <li class="process-item">
                    <div class="process-icon"><i class="fas fa-spray-can text-white"></i></div>
                    <div>
                        <h4 class="mb-2" style="font-family: 'Jost', sans-serif; text-transform:uppercase; font-size:1.1rem; letter-spacing:1px;">Eksekusi Artisanal</h4>
                        <p class="text-muted fw-light mb-0">Teknisi kami melakukan restorasi detail sesuai standar kualitas.</p>
                    </div>
                </li>
                <li class="process-item">
                    <div class="process-icon"><i class="fas fa-check-double text-white"></i></div>
                    <div>
                        <h4 class="mb-2" style="font-family: 'Jost', sans-serif; text-transform:uppercase; font-size:1.1rem; letter-spacing:1px;">Pengembalian Prima</h4>
                        <p class="text-muted fw-light mb-0">Sepatu dikembalikan dalam kondisi optimal dan pengemasan premium.</p>
                    </div>
                </li>
            </ul>
        </div>
    </section>

    <!-- Minimal CTA -->
    <section style="background: linear-gradient(135deg, var(--secondary-light) 0%, rgba(244, 114, 182, 0.1) 100%); padding: 150px 0; border-top: 1px solid rgba(255,255,255,0.05); border-bottom: 1px solid rgba(255,255,255,0.05);">
        <div class="container text-center">
            <h2 class="section-title" style="font-size: 4rem;">Tingkatkan Standar Anda.</h2>
            <p class="text-light mb-5 mx-auto" style="max-width: 500px; font-weight: 400; font-size: 1.1rem; opacity: 0.95;">Bergabunglah dengan pelanggan eksklusif yang mempercayakan koleksi berharga mereka kepada spesialis kami.</p>
            <a href="{{ route('register') }}" class="btn btn-solid-custom px-5 py-3" style="font-size: 1rem;">Buat Akun Sekarang</a>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="row mb-5">
                <div class="col-lg-4 mb-4 mb-lg-0">
                    <a href="#" class="footer-logo">Cleansetz<span>.</span></a>
                    <p class="text-light" style="max-width: 300px; font-weight: 300; opacity: 0.9;">
                        Mendefinisikan ulang standar perawatan alas kaki melalui dedikasi pada detail dan produk restorasi top-tier.
                    </p>
                </div>
                <div class="col-lg-2 offset-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-4" style="font-family: 'Jost', sans-serif; font-size:1rem; letter-spacing:2px; text-transform:uppercase;">Navigasi</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#home" class="text-light text-decoration-none" style="opacity: 0.85;">Beranda</a></li>
                        <li class="mb-2"><a href="#services" class="text-light text-decoration-none" style="opacity: 0.85;">Layanan</a></li>
                        <li class="mb-2"><a href="#process" class="text-light text-decoration-none" style="opacity: 0.85;">Proses</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4 mb-4 mb-md-0">
                    <h5 class="mb-4" style="font-family: 'Jost', sans-serif; font-size:1rem; letter-spacing:2px; text-transform:uppercase;">Legalitas</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none" style="opacity: 0.85;">Privasi</a></li>
                        <li class="mb-2"><a href="#" class="text-light text-decoration-none" style="opacity: 0.85;">Syarat & Ketentuan</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-4">
                    <h5 class="mb-4" style="font-family: 'Jost', sans-serif; font-size:1rem; letter-spacing:2px; text-transform:uppercase;">Sosial</h5>
                    <div class="social-links">
                        <a href="#"><i class="fab fa-instagram"></i></a>
                        <a href="#"><i class="fab fa-twitter"></i></a>
                        <a href="#"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-top border-secondary pt-4 text-center">
                <p class="text-muted small mb-0">&copy; {{ date('Y') }} Cleansetz Premium Care. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom Scripts -->
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Simple Parallax Effect
        window.addEventListener('scroll', function() {
            const parallaxBg = document.getElementById('parallax-bg');
            let scrollPosition = window.pageYOffset;
            
            // Apply slight move up for parallax effect
            if (scrollPosition < window.innerHeight) {
                parallaxBg.style.transform = `translateY(${scrollPosition * 0.4}px)`;
            }
        });

        // Close Mobile Menu on Click
        function closeMenu() {
            const navMenu = document.getElementById('navMenu');
            if (window.innerWidth < 992) {
                new bootstrap.Collapse(navMenu).hide();
            }
        }
    </script>
</body>
</html>
