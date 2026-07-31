<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'IoT Digital Twin Dashboard')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @stack('styles')
    
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        /* Modern Dark Navbar */
        .navbar-custom {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
        .navbar-custom .navbar-brand {
            color: #ffffff;
            font-weight: 700;
            letter-spacing: 0.5px;
        }
        .navbar-custom .nav-link {
            color: rgba(255, 255, 255, 0.7);
            font-weight: 500;
            transition: all 0.3s ease;
            margin: 0 4px;
            border-radius: 8px;
            padding: 8px 16px;
        }
        .navbar-custom .nav-link:hover, .navbar-custom .nav-link.active {
            color: #ffffff;
            background: rgba(255, 255, 255, 0.15);
            transform: translateY(-1px);
        }
        
        .main-content {
            padding: 24px 0;
            min-height: calc(100vh - 76px);
        }
        
        /* Custom Button */
        .btn-back {
            background: rgba(255,255,255,0.1);
            color: #fff;
            border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.3s ease;
        }
        .btn-back:hover {
            background: rgba(255,255,255,0.2);
            color: #fff;
        }
    </style>
</head>
<body>
    
    <!-- Top Navigation -->
    <nav class="navbar navbar-expand-xl navbar-custom py-3">
        <div class="container-fluid px-4">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('digital-twin.index') }}">
                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px; box-shadow: 0 0 15px rgba(25, 135, 84, 0.5);">
                    <i class="fas fa-satellite-dish text-white"></i>
                </div>
                <span>Digital Twin <span class="text-success">IoT</span></span>
            </a>
            <button class="navbar-toggler text-white border-0" type="button" data-bs-toggle="collapse" data-bs-target="#digitalTwinNav" aria-controls="digitalTwinNav" aria-expanded="false" aria-label="Toggle navigation">
                <i class="fas fa-bars fs-4"></i>
            </button>
            
            <div class="collapse navbar-collapse" id="digitalTwinNav">
                <ul class="navbar-nav ms-4 me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('digital-twin.index') ? 'active' : '' }}" href="{{ route('digital-twin.index') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('digital-twin.zonasi') ? 'active' : '' }}" href="{{ route('digital-twin.zonasi') }}">
                            <i class="fas fa-map-marked-alt me-2"></i>Peta Zonasi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="alert('Fitur Simulasi Fuzzy Logic sedang dikembangkan.'); return false;">
                            <i class="fas fa-brain me-2"></i>Simulasi Fuzzy
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="alert('Fitur Riwayat Infeksi (Pathogen Persistence) sedang dikembangkan.'); return false;">
                            <i class="fas fa-history me-2"></i>Riwayat Infeksi
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#" onclick="alert('Fitur Data Akuisisi (IoT & Drone) sedang dikembangkan.'); return false;">
                            <i class="fas fa-database me-2"></i>Data Akuisisi
                        </a>
                    </li>
                </ul>
                <div class="d-flex align-items-center">
                    <a href="{{ route('dashboard') }}" class="btn btn-back btn-sm rounded-pill px-4 py-2">
                        <i class="fas fa-arrow-left me-2"></i>Kembali ke SIA
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        @yield('content')
    </main>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
