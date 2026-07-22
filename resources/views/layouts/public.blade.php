<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Beranda') - Prodi Sistem Informasi Akuntansi UBSI Kampus Kota Pontianak</title>
    <meta name="description" content="@yield('meta_description', 'Portal Akademik resmi Program Studi Sistem Informasi Akuntansi (SIA) Universitas Bina Sarana Informatika (UBSI) Kampus Kota Pontianak. Dapatkan informasi pendaftaran, berita terbaru, dan kegiatan akademik.')">
    <meta name="keywords" content="UBSI Pontianak, Sistem Informasi Akuntansi, Prodi SIA, Kuliah Pontianak, Universitas Bina Sarana Informatika, Portal Akademik, Sistem Informasi">
    <meta name="author" content="Prodi Sistem Informasi Akuntansi UBSI Pontianak">
    <meta name="robots" content="index, follow">
    <meta name="google-site-verification" content="A42mM0DpJngiVvmKh9cnbVYufjYuUktpKMeFTn51lnE" />
    
    <!-- Open Graph / Social Media Meta -->
    <meta property="og:title" content="@yield('title', 'Beranda') - Prodi Sistem Informasi Akuntansi UBSI Pontianak">
    <meta property="og:description" content="@yield('meta_description', 'Portal Akademik resmi Program Studi Sistem Informasi Akuntansi (SIA) Universitas Bina Sarana Informatika (UBSI) Kampus Kota Pontianak.')">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:image" content="{{ asset('img/logo_ubsi.png') }}">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <style>
        :root {
            --primary: #1e3a8a; /* Deep royal blue for premium feel */
            --primary-dark: #172554;
            --secondary: #d97706; /* Rich gold/amber accent */
            --dark: #0f172a;
            --light: #f8fafc;
            --gray: #64748b;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            background-image: 
                radial-gradient(at 0% 0%, hsla(220, 100%, 80%, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 0%, hsla(35, 100%, 75%, 0.15) 0px, transparent 50%),
                radial-gradient(at 100% 100%, hsla(220, 100%, 80%, 0.15) 0px, transparent 50%),
                radial-gradient(at 0% 100%, hsla(35, 100%, 75%, 0.15) 0px, transparent 50%);
            background-attachment: fixed;
            color: var(--dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
            max-width: 100vw;
        }

        /* Glassmorphism Classes for Premium Look */
        .glass-card, .card, .stat-card, .prestasi-card, .dosen-card {
            background: rgba(255, 255, 255, 0.65) !important;
            backdrop-filter: blur(20px) !important;
            -webkit-backdrop-filter: blur(20px) !important;
            border: 1px solid rgba(255, 255, 255, 0.8) !important;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04) !important;
        }

        /* Navbar */
        .navbar {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(16px) !important;
            -webkit-backdrop-filter: blur(16px) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.6);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.08), 0 5px 15px rgba(0, 0, 0, 0.03);
            padding: 15px 0;
            transition: all 0.3s ease;
        }
        .navbar-brand {
            font-weight: 800;
            font-size: 1.4rem;
            color: var(--primary) !important;
        }
        .nav-link {
            font-weight: 600;
            color: var(--dark) !important;
            margin: 0 10px;
            transition: color 0.3s ease;
            white-space: nowrap;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--primary) !important;
        }
        .btn-login {
            background: var(--primary);
            color: white !important;
            border-radius: 12px;
            padding: 8px 24px;
            font-weight: 600;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3);
            transition: all 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
        }

        /* Hero */
        .hero {
            padding: 100px 0 80px;
            background: linear-gradient(135deg, rgba(79,70,229,0.05) 0%, rgba(16,185,129,0.05) 100%);
            position: relative;
            overflow: hidden;
        }
        .hero-shape {
            position: absolute;
            z-index: -1;
            filter: blur(80px);
            opacity: 0.5;
            border-radius: 50%;
        }
        .hero-shape-1 {
            width: 400px;
            height: 400px;
            background: var(--primary);
            top: -100px;
            right: -100px;
        }
        .hero-shape-2 {
            width: 300px;
            height: 300px;
            background: var(--secondary);
            bottom: -50px;
            left: -50px;
        }
        .hero h1 {
            font-weight: 800;
            font-size: 3.5rem;
            line-height: 1.2;
            margin-bottom: 24px;
            color: var(--dark);
            letter-spacing: -1px;
        }
        .hero h1 span {
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .hero p {
            font-size: 1.15rem;
            color: var(--gray);
            margin-bottom: 32px;
            line-height: 1.6;
        }

        /* Stats */
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 30px 20px;
            text-align: center;
            border: 1px solid rgba(0,0,0,0.03);
            box-shadow: 0 10px 40px rgba(0,0,0,0.03);
            transition: all 0.3s ease;
            height: 100%;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.06);
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            margin: 0 auto 20px;
        }
        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 5px;
            line-height: 1;
        }
        .stat-label {
            color: var(--gray);
            font-weight: 600;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Section Titles */
        .section-title {
            font-weight: 800;
            font-size: 2.2rem;
            margin-bottom: 10px;
            color: var(--dark);
        }
        .section-subtitle {
            color: var(--gray);
            font-size: 1.1rem;
            margin-bottom: 40px;
        }

        /* Prestasi Cards */
        .prestasi-card {
            background: white;
            border-radius: 20px;
            padding: 24px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03);
            border: 1px solid rgba(0,0,0,0.03);
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }
        .prestasi-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.08);
            border-color: rgba(79, 70, 229, 0.2);
        }
        .prestasi-badge {
            background: rgba(79, 70, 229, 0.1);
            color: var(--primary);
            padding: 6px 14px;
            border-radius: 30px;
            font-weight: 700;
            font-size: 0.8rem;
            display: inline-block;
            margin-bottom: 15px;
        }

        /* Dosen Directory */
        .dosen-card {
            background: white;
            border-radius: 24px;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 10px 30px rgba(0,0,0,0.02);
            border: 1px solid rgba(0,0,0,0.03);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            height: 100%;
        }
        .dosen-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.06);
        }
        .dosen-img {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 20px;
            border: 4px solid #f8fafc;
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }
        .dosen-img-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), #8b5cf6);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            font-weight: 800;
            margin: 0 auto 20px;
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
        }
        .dosen-name {
            font-weight: 800;
            font-size: 1.1rem;
            color: var(--dark);
            margin-bottom: 8px;
        }
        .dosen-role {
            color: var(--primary);
            font-weight: 600;
            font-size: 0.85rem;
            background: rgba(79, 70, 229, 0.08);
            padding: 4px 12px;
            border-radius: 20px;
            display: inline-block;
        }

        /* Footer */
        footer {
            background: var(--dark);
            color: white;
            padding: 40px 0;
            margin-top: auto;
        }
        
        /* Auto scrolling utility for dosen */
        .marquee {
            overflow: hidden;
            width: 100%;
            padding: 20px 0;
        }
        .marquee-content {
            display: inline-flex;
            width: max-content;
            animation: marquee 40s linear infinite;
            gap: 24px;
        }
        .marquee-content:hover {
            animation-play-state: paused;
        }
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(calc(-50% - 12px)); }
        }
        .marquee-item {
            width: 250px;
            flex-shrink: 0;
        }

    </style>
</head>
<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('welcome') }}">
                <img src="{{ asset('img/logo_ubsi.png') }}" alt="Logo UBSI" style="width: 45px; height: auto;">
                <div class="d-flex flex-column lh-1">
                    <span class="fs-6 fw-bold">Prodi Sistem Informasi Akuntansi</span>
                    <span class="fs-6 fw-bold">UBSI Kampus Kota Pontianak</span>
                </div>
            </a>
            <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <i class="bi bi-list fs-1 text-dark"></i>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('welcome') ? 'active' : '' }}" href="{{ route('welcome') }}">Beranda</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('profil-prodi.public') ? 'active' : '' }}" href="{{ route('profil-prodi.public') }}">Profil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('direktori-hki') ? 'active' : '' }}" href="{{ route('direktori-hki') }}">Direktori HKI</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('portal.kegiatan*') ? 'active' : '' }}" href="{{ route('portal.kegiatan') }}">Kegiatan & Event</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold {{ request()->routeIs('portal.beasiswa*') || request()->routeIs('direktori-alumni') ? 'active' : '' }}" href="#" id="navbarMahasiswa" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-mortarboard-fill me-1 text-warning"></i>Mahasiswa & Alumni
                        </a>
                        <ul class="dropdown-menu border-0 shadow-sm rounded-4 mt-2" aria-labelledby="navbarMahasiswa">
                            {{-- <li><a class="dropdown-item fw-semibold py-2" href="{{ route('portal.beasiswa') }}"><i class="bi bi-star-fill me-2 text-warning"></i>Beasiswa</a></li> --}}
                            <li><a class="dropdown-item fw-semibold py-2" href="{{ route('direktori-alumni') }}"><i class="bi bi-people-fill me-2 text-primary"></i>Direktori Alumni</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle fw-bold {{ request()->routeIs('portal.penelitian*') || request()->routeIs('portal.pkm*') ? 'active' : '' }}" href="#" id="navbarTridharma" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-briefcase-fill me-1 text-secondary"></i>Tridharma Dosen
                        </a>
                        <ul class="dropdown-menu border-0 shadow-sm rounded-4 mt-2" aria-labelledby="navbarTridharma">
                            <li><a class="dropdown-item fw-semibold py-2" href="{{ route('portal.penelitian') }}"><i class="bi bi-journal-text me-2 text-primary"></i>Penelitian Dosen</a></li>
                            <li><a class="dropdown-item fw-semibold py-2" href="{{ route('portal.pkm') }}"><i class="bi bi-people-fill me-2 text-secondary"></i>Pengabdian (PkM)</a></li>
                        </ul>
                    </li>
                </ul>
                <div class="d-flex align-items-center gap-3">
                    @auth
                        <div class="dropdown">
                            <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark" data-bs-toggle="dropdown" aria-expanded="false">
                                @if (Auth::user()->foto)
                                    <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="" class="rounded-circle border me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                @else
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                        <i class="bi bi-person-fill fs-5"></i>
                                    </div>
                                @endif
                                <div class="text-start d-none d-sm-block">
                                    <div class="fw-bold fs-6 lh-1">{{ Auth::user()->username }}</div>
                                    <small class="text-muted text-capitalize" style="font-size: 0.75rem;">{{ Auth::user()->level }}</small>
                                </div>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-sm mt-3" style="border-radius: 12px;">
                                <li><a class="dropdown-item py-2 fw-semibold" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2 text-primary"></i>Panel Admin</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item py-2 fw-semibold text-danger" href="{{ route('logout') }}"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-login">
                            Masuk Portal <i class="bi bi-arrow-right ms-1"></i>
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>


    @yield('content')

    <!-- Footer -->
    <footer>
        <div class="container text-center">
            <h4 class="fw-bold mb-3">Prodi Sistem Informasi Akuntansi</h4>
            <h5 class="fw-semibold text-white-50 mb-3">Universitas Bina Sarana Informatika (UBSI) Kampus Kota Pontianak</h5>
            <p class="text-white-50 mb-4" style="max-width: 600px; margin: 0 auto;">Pusat informasi akademik, berita, dan manajemen kegiatan mahasiswa terpadu untuk tata kelola program studi yang modern dan unggul.</p>
            <div class="d-flex justify-content-center gap-3 mb-4">
                <a href="#" class="text-white text-decoration-none"><i class="bi bi-instagram fs-4"></i></a>
                <a href="#" class="text-white text-decoration-none"><i class="bi bi-globe fs-4"></i></a>
                <a href="#" class="text-white text-decoration-none"><i class="bi bi-envelope fs-4"></i></a>
            </div>
            <p class="mb-0 small text-white-50">&copy; {{ date('Y') }} Prodi SIA UBSI Kampus Kota Pontianak. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
            duration: 800,
            easing: 'ease-out-cubic',
            once: true,
            offset: 50
        });
    </script>
    @stack('scripts')
</body>
</html>
