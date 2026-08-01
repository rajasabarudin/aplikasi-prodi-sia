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
    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    @stack('styles')
    
    <style>
        body {
            background-color: #f8fafc;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            overflow-x: hidden;
        }
        
        .wrapper {
            display: flex;
            width: 100%;
            align-items: stretch;
        }
        
        #sidebar {
            min-width: 260px;
            max-width: 260px;
            min-height: 100vh;
            background: #1e293b;
            color: #fff;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
        }

        #sidebar.active {
            margin-left: -260px;
        }

        #sidebar .sidebar-header {
            padding: 25px 20px;
            background: #0f172a;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        #sidebar ul.components {
            padding: 20px 0;
            flex-grow: 1;
        }

        #sidebar ul li a {
            padding: 15px 25px;
            font-size: 1.05em;
            display: block;
            color: rgba(255,255,255,0.7);
            text-decoration: none;
            transition: 0.2s;
            border-left: 4px solid transparent;
        }

        #sidebar ul li a:hover {
            color: #fff;
            background: rgba(255,255,255,0.05);
        }

        #sidebar ul li a.active {
            color: #fff;
            background: rgba(16, 185, 129, 0.15);
            border-left: 4px solid #10b981;
            font-weight: 600;
        }

        #content {
            width: 100%;
            min-height: 100vh;
            transition: all 0.3s;
            display: flex;
            flex-direction: column;
        }
        
        .top-navbar {
            background: #fff;
            box-shadow: 0 2px 10px rgba(0,0,0,0.03);
            padding: 15px 25px;
            display: flex;
            align-items: center;
        }
        
        .main-content {
            padding: 30px;
            flex-grow: 1;
        }

        @media (max-width: 768px) {
            #sidebar {
                margin-left: -260px;
                position: absolute;
                z-index: 999;
            }
            #sidebar.active {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar">
            <div class="sidebar-header d-flex align-items-center">
                <div class="bg-success rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 45px; height: 45px; box-shadow: 0 0 15px rgba(25, 135, 84, 0.4);">
                    <i class="fas fa-satellite-dish text-white fs-5"></i>
                </div>
                <h5 class="mb-0 fw-bold letter-spacing">Digital Twin</h5>
            </div>

            <ul class="list-unstyled components">
                <li>
                    <a href="{{ route('digital-twin.index') }}" class="{{ request()->routeIs('digital-twin.index') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt me-3 text-center" style="width: 20px;"></i> Dashboard IoT
                    </a>
                </li>
                <li>
                    <a href="{{ route('digital-twin.zonasi') }}" class="{{ request()->routeIs('digital-twin.zonasi') ? 'active' : '' }}">
                        <i class="fas fa-map-marked-alt me-3 text-center" style="width: 20px;"></i> Peta Zonasi
                    </a>
                </li>
                <li>
                    <a href="#" onclick="alert('Fitur Simulasi Fuzzy Logic sedang dikembangkan.'); return false;">
                        <i class="fas fa-brain me-3 text-center" style="width: 20px;"></i> Simulasi Fuzzy
                    </a>
                </li>
                <li>
                    <a href="#" onclick="alert('Fitur Riwayat Infeksi sedang dikembangkan.'); return false;">
                        <i class="fas fa-history me-3 text-center" style="width: 20px;"></i> Riwayat Infeksi
                    </a>
                </li>
            </ul>
            
            <div class="p-4 mt-auto border-top" style="border-color: rgba(255,255,255,0.05) !important;">
                <a href="{{ route('dashboard') }}" class="btn btn-outline-light w-100 rounded-pill py-2">
                    <i class="fas fa-arrow-left me-2"></i> Ke Prodi SIA
                </a>
            </div>
        </nav>

        <!-- Page Content -->
        <div id="content">
            <!-- Topbar (Just for Toggle and Title) -->
            <div class="top-navbar">
                <button type="button" id="sidebarCollapse" class="btn btn-light border shadow-sm">
                    <i class="fas fa-bars text-secondary"></i>
                </button>
                <div class="ms-3 fw-bold text-dark fs-5">
                    @yield('title', 'IoT Digital Twin Dashboard')
                </div>
            </div>
            
            <main class="main-content">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('sidebarCollapse').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('active');
        });
    </script>
    @stack('scripts')
</body>
</html>
