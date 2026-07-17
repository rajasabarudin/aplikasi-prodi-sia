<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SIA Prodi')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @stack('styles')
    <style>
        body {
            background: radial-gradient(circle at 10% 20%, rgba(240, 244, 255, 1) 0%, rgba(249, 245, 255, 1) 90.1%) !important;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: #1e293b;
            min-height: 100vh;
        }

        /* Collapsible Sidebar Styles */
        .transition-sidebar {
            transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            overflow-x: hidden;
            white-space: nowrap;
        }
        .sidebar-collapsed {
            width: 70px !important;
        }
        .sidebar-collapsed .sidebar-text,
        .sidebar-collapsed .bi-chevron-down,
        .sidebar-collapsed .float-end {
            display: none !important;
        }
        .sidebar-collapsed .nav-link {
            justify-content: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
        }
        .sidebar-collapsed .nav-link i {
            margin-right: 0 !important;
            font-size: 1.25rem;
        }
        .sidebar-collapsed .collapse {
            display: none !important;
        }
        .sidebar-collapsed .ms-3 {
            margin-left: 0 !important;
        }

        /* Sidebar Styling */
        .bg-dark {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%) !important;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .nav-link {
            transition: all 0.3s ease;
            border-radius: 8px !important;
        }
        
        .nav-link:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(3px);
        }
        
        .nav-link.active {
            background: linear-gradient(135deg, #4f46e5, #3b82f6) !important;
            box-shadow: 0 4px 15px rgba(79, 70, 229, 0.3) !important;
        }

        /* Navbar Styling */
        .navbar {
            background: rgba(255, 255, 255, 0.65) !important;
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border-bottom: 1px solid rgba(255, 255, 255, 0.4) !important;
        }

        /* Card / Glass Panel Styling */
        .card {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(12px) !important;
            -webkit-backdrop-filter: blur(12px) !important;
            border: 1px solid rgba(255, 255, 255, 0.5) !important;
            border-radius: 16px !important;
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.04) !important;
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.08) !important;
        }

        /* Premium Total Card */
        .stat-card-total {
            background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
            color: #fff !important;
            border-radius: 14px !important;
            border: none !important;
            box-shadow: 0 8px 24px rgba(99, 102, 241, 0.2) !important;
            position: relative;
            overflow: hidden;
        }

        .stat-card-total::before {
            content: '';
            position: absolute;
            top: -30%;
            right: -20%;
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.12);
            border-radius: 50%;
        }

        /* Card Header */
        .card-header {
            background: rgba(15, 23, 42, 0.03);
            border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
        }

        /* Modal Content Styling */
        .modal-content {
            background: rgba(255, 255, 255, 0.75) !important;
            backdrop-filter: blur(15px) !important;
            -webkit-backdrop-filter: blur(15px) !important;
            border: 1px solid rgba(255, 255, 255, 0.5) !important;
            border-radius: 18px !important;
            box-shadow: 0 12px 50px rgba(0, 0, 0, 0.15) !important;
        }

        /* Modal Header */
        .modal-header {
            background: linear-gradient(135deg, #1e293b, #0f172a) !important;
            color: #fff !important;
            border-bottom: none !important;
        }

        /* Input Styling */
        .form-control, .form-select {
            background: rgba(255, 255, 255, 0.7) !important;
            border: 1px solid rgba(226, 232, 240, 0.8) !important;
            border-radius: 10px !important;
            transition: all 0.3s ease !important;
        }

        .form-control:focus, .form-select:focus {
            background: #fff !important;
            border-color: #6366f1 !important;
            box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.15) !important;
        }

        /* Button Styling */
        .btn {
            border-radius: 10px !important;
            transition: all 0.2s ease !important;
        }

        .btn-primary {
            background: linear-gradient(135deg, #4f46e5, #3b82f6) !important;
            border: none !important;
            color: #fff !important;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2) !important;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            color: #fff !important;
            box-shadow: 0 6px 15px rgba(79, 70, 229, 0.3) !important;
        }

        .btn-success {
            background: linear-gradient(135deg, #10b981, #059669) !important;
            border: none !important;
            color: #fff !important;
            box-shadow: 0 4px 10px rgba(16, 185, 129, 0.2) !important;
        }

        .btn-success:hover {
            transform: translateY(-1px);
            color: #fff !important;
            box-shadow: 0 6px 15px rgba(16, 185, 129, 0.3) !important;
        }

        /* Table Styling */
        .table {
            background: rgba(255, 255, 255, 0.4) !important;
            border-collapse: separate !important;
            border-spacing: 0 !important;
            border-radius: 14px !important;
            overflow: hidden !important;
            border: 1px solid rgba(255, 255, 255, 0.4) !important;
        }

        .table thead tr th {
            background: rgba(15, 23, 42, 0.9) !important;
            color: #fff !important;
            border: none !important;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 0.5px;
            padding: 14px 10px !important;
        }

        .table tbody tr {
            transition: all 0.2s ease;
        }

        .table tbody tr:hover {
            background: rgba(255, 255, 255, 0.75) !important;
        }

        .table tbody tr td {
            border-bottom: 1px solid rgba(226, 232, 240, 0.6) !important;
            border-top: none !important;
            border-left: none !important;
            border-right: none !important;
        }

        /* Modal Backdrop Blur */
        .modal-backdrop {
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
            background-color: rgba(0, 0, 0, 0.5) !important;
        }
    </style>
</head>
<body>
    <div class="d-flex" style="min-height: 100vh;">
        <div id="sidebar" class="bg-dark text-white d-print-none transition-sidebar" style="width: 250px; flex-shrink: 0;">
            <div class="p-3 border-bottom border-secondary d-flex align-items-center justify-content-between" id="sidebar-header" style="cursor: pointer;" title="Toggle Sidebar">
                <span class="d-flex align-items-center">
                    <i class="bi bi-mortarboard-fill me-2 fs-4 text-warning"></i>
                    <h5 class="mb-0 sidebar-text fw-bold text-wrap" style="font-size: 0.95rem;">Manajemen Prd SIA</h5>
                </span>
                <i class="bi bi-chevron-bar-left sidebar-text ms-2" id="sidebar-collapse-icon"></i>
            </div>
            <ul class="nav nav-pills flex-column mt-2 px-2">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->routeIs('dashboard') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                        <i class="bi bi-speedometer2 me-2 text-info fs-5"></i>
                        <span class="sidebar-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item mt-2">
                    <a class="nav-link text-white d-flex align-items-center justify-content-between" data-bs-toggle="collapse" href="#laporanMenu" role="button" aria-expanded="{{ request()->routeIs('obe.*') || request()->routeIs('tracer-study.*') || request()->routeIs('kohort.*') || request()->routeIs('keuangan-prodi.*') || request()->routeIs('survei-kepuasan.*') ? 'true' : 'false' }}" aria-controls="laporanMenu">
                        <span>
                            <i class="bi bi-bar-chart-fill me-2 text-warning fs-5"></i>
                            <span class="sidebar-text">Evaluasi & Laporan</span>
                        </span>
                        <i class="bi bi-chevron-down float-end"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('obe.*') || request()->routeIs('tracer-study.*') || request()->routeIs('kohort.*') || request()->routeIs('keuangan-prodi.*') || request()->routeIs('survei-kepuasan.*') ? 'show' : '' }}" id="laporanMenu">
                        <ul class="nav nav-pills flex-column ms-3 mt-1">
                            <li class="nav-item mt-1">
                                <a href="{{ route('obe.index') }}" class="nav-link text-white {{ request()->routeIs('obe.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-shield-check me-2 text-warning"></i>
                                    <span class="sidebar-text">Portal Akreditasi (OBE)</span>
                                </a>
                            </li>
                            <li class="nav-item mt-1">
                                <a href="{{ route('tracer-study.index') }}" class="nav-link text-white {{ request()->routeIs('tracer-study.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-briefcase me-2 text-info"></i>
                                    <span class="sidebar-text">Tracer Study Alumni</span>
                                </a>
                            </li>
                            <li class="nav-item mt-1">
                                <a href="{{ route('kohort.index') }}" class="nav-link text-white {{ request()->routeIs('kohort.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-table me-2 text-warning"></i>
                                    <span class="sidebar-text">Matriks Kohort C3</span>
                                </a>
                            </li>
                            <li class="nav-item mt-1">
                                <a href="{{ route('keuangan-prodi.index') }}" class="nav-link text-white {{ request()->routeIs('keuangan-prodi.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-wallet2 me-2 text-success"></i>
                                    <span class="sidebar-text">Keuangan & Dana C5</span>
                                </a>
                            </li>
                            <li class="nav-item mt-1">
                                <a href="{{ route('survei-kepuasan.index') }}" class="nav-link text-white {{ request()->routeIs('survei-kepuasan.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-ui-checks me-2 text-primary"></i>
                                    <span class="sidebar-text">Survei Kepuasan (C1-C9)</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- Separator: Evaluasi & Asesmen -->
                @if(Auth::user()->level === 'king')
                <li class="nav-item mt-2">
                    <a class="nav-link text-white d-flex align-items-center justify-content-between" data-bs-toggle="collapse" href="#portalMenu" role="button" aria-expanded="{{ request()->routeIs('profil-prodi.*') || request()->routeIs('berita.*') ? 'true' : 'false' }}" aria-controls="portalMenu">
                        <span>
                            <i class="bi bi-globe me-2 text-primary fs-5"></i>
                            <span class="sidebar-text">Portal Website</span>
                        </span>
                        <i class="bi bi-chevron-down float-end"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('profil-prodi.*') || request()->routeIs('berita.*') ? 'show' : '' }}" id="portalMenu">
                        <ul class="nav nav-pills flex-column ms-3 mt-1">
                            <li class="nav-item">
                                <a href="{{ route('profil-prodi.index') }}" class="nav-link text-white {{ request()->routeIs('profil-prodi.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-building me-2 text-warning"></i>
                                    <span class="sidebar-text">Profil Prodi</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('berita.index') }}" class="nav-link text-white {{ request()->routeIs('berita.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-newspaper me-2 text-info"></i>
                                    <span class="sidebar-text">Berita & Pengumuman</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endif
                
                @if(Auth::user()->hasPermission('kelas') || Auth::user()->hasPermission('pmb') || Auth::user()->hasPermission('akun') || Auth::user()->hasPermission('kerjasama') || Auth::user()->hasPermission('pks-ia') || Auth::user()->hasPermission('praktisi'))
                <li class="nav-item mt-2">
                    <a class="nav-link text-white d-flex align-items-center justify-content-between" data-bs-toggle="collapse" href="#masterDataMenu" role="button" aria-expanded="{{ request()->routeIs('kelas.*') || request()->routeIs('pmb.*') || request()->routeIs('akun.*') || request()->routeIs('kerjasama.*') || request()->routeIs('pks-ia.*') || request()->routeIs('praktisi.*') ? 'true' : 'false' }}" aria-controls="masterDataMenu">
                        <span>
                            <i class="bi bi-database me-2 text-success fs-5"></i>
                            <span class="sidebar-text">Master Data</span>
                        </span>
                        <i class="bi bi-chevron-down float-end"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('kelas.*') || request()->routeIs('pmb.*') || request()->routeIs('akun.*') || request()->routeIs('kerjasama.*') || request()->routeIs('pks-ia.*') || request()->routeIs('praktisi.*') ? 'show' : '' }}" id="masterDataMenu">
                        <ul class="nav nav-pills flex-column ms-3 mt-1">
                            @if(Auth::user()->hasPermission('kelas'))
                            <li class="nav-item">
                                <a href="{{ route('kelas.index') }}" class="nav-link text-white {{ request()->routeIs('kelas.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-layers me-2 text-primary"></i>
                                    <span class="sidebar-text">Data Kelas</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('pmb'))
                            <li class="nav-item">
                                <a href="{{ route('pmb.index') }}" class="nav-link text-white {{ request()->routeIs('pmb.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-graph-up-arrow me-2 text-danger"></i>
                                    <span class="sidebar-text">Data PMB</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('kerjasama'))
                            <li class="nav-item">
                                <a href="{{ route('kerjasama.index') }}" class="nav-link text-white {{ request()->routeIs('kerjasama.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-briefcase me-2 text-info"></i>
                                    <span class="sidebar-text">Data Kerjasama</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('pks-ia'))
                            <li class="nav-item">
                                <a href="{{ route('pks-ia.index') }}" class="nav-link text-white {{ request()->routeIs('pks-ia.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-file-earmark-text me-2 text-success"></i>
                                    <span class="sidebar-text">Data PKS & IA</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('praktisi'))
                            <li class="nav-item">
                                <a href="{{ route('praktisi.index') }}" class="nav-link text-white {{ request()->routeIs('praktisi.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-person-workspace me-2 text-warning"></i>
                                    <span class="sidebar-text">Data Praktisi</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('akun'))
                            <li class="nav-item">
                                <a href="{{ route('akun.index') }}" class="nav-link text-white {{ request()->routeIs('akun.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-person-gear me-2 text-light"></i>
                                    <span class="sidebar-text">Data Akun</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if(Auth::user()->hasPermission('matakuliah') || Auth::user()->hasPermission('rps-cpl') || Auth::user()->hasPermission('rps-cpmk') || Auth::user()->hasPermission('rps-bahan-kajian') || Auth::user()->hasPermission('rps-referensi') || Auth::user()->hasPermission('penyusunan-rps'))
                <li class="nav-item mt-2">
                    <a class="nav-link text-white d-flex align-items-center justify-content-between" data-bs-toggle="collapse" href="#rpsMenu" role="button" aria-expanded="{{ request()->routeIs('matakuliah.*') || request()->routeIs('rps-cpl.*') || request()->routeIs('rps-cpmk.*') || request()->routeIs('rps-bahan-kajian.*') || request()->routeIs('rps-referensi.*') || request()->routeIs('penyusunan-rps.*') || request()->routeIs('penyusunan-rtm.*') || request()->routeIs('penyusunan-silabus.*') ? 'true' : 'false' }}" aria-controls="rpsMenu">
                        <span>
                            <i class="bi bi-book-fill me-2 fs-5" style="color:#a78bfa"></i>
                            <span class="sidebar-text">Master Data RPS</span>
                        </span>
                        <i class="bi bi-chevron-down float-end"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('matakuliah.*') || request()->routeIs('rps-cpl.*') || request()->routeIs('rps-cpmk.*') || request()->routeIs('rps-bahan-kajian.*') || request()->routeIs('rps-referensi.*') || request()->routeIs('penyusunan-rps.*') || request()->routeIs('penyusunan-rtm.*') || request()->routeIs('penyusunan-silabus.*') ? 'show' : '' }}" id="rpsMenu">
                        <ul class="nav nav-pills flex-column ms-3 mt-1">
                            @if(Auth::user()->hasPermission('matakuliah'))
                            <li class="nav-item">
                                <a href="{{ route('matakuliah.index') }}" class="nav-link text-white {{ request()->routeIs('matakuliah.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-journal-text me-2 text-warning"></i>
                                    <span class="sidebar-text">Data Matakuliah</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('rps-cpl'))
                            <li class="nav-item">
                                <a href="{{ route('rps-cpl.index') }}" class="nav-link text-white {{ request()->routeIs('rps-cpl.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-circle-fill me-2 text-danger" style="font-size:0.5rem"></i>
                                    <span class="sidebar-text">Data CPL</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('rps-cpmk'))
                            <li class="nav-item">
                                <a href="{{ route('rps-cpmk.index') }}" class="nav-link text-white {{ request()->routeIs('rps-cpmk.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-diagram-3 me-2 text-warning"></i>
                                    <span class="sidebar-text">Data CPMK</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('rps-bahan-kajian'))
                            <li class="nav-item">
                                <a href="{{ route('rps-bahan-kajian.index') }}" class="nav-link text-white {{ request()->routeIs('rps-bahan-kajian.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-list-ul me-2 text-info"></i>
                                    <span class="sidebar-text">Bahan Kajian MK</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('rps-referensi'))
                            <li class="nav-item">
                                <a href="{{ route('rps-referensi.index') }}" class="nav-link text-white {{ request()->routeIs('rps-referensi.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-bookmark-fill me-2 text-success"></i>
                                    <span class="sidebar-text">Referensi MK</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('penyusunan-rps'))
                            <li class="nav-item">
                                <a href="{{ route('penyusunan-rps.index') }}" class="nav-link text-white {{ request()->routeIs('penyusunan-rps.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-file-earmark-pdf-fill text-danger me-2"></i>
                                    <span class="sidebar-text">Data Penyusunan RPS</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('penyusunan-rps'))
                            <li class="nav-item">
                                <a href="{{ route('penyusunan-rtm.index') }}" class="nav-link text-white {{ request()->routeIs('penyusunan-rtm.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <div style="width: 20px" class="text-center me-2">
                                        <i class="bi bi-file-earmark-check"></i>
                                    </div>
                                    <span class="sidebar-text">Data Master RTM</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('penyusunan-silabus.index') }}" class="nav-link text-white {{ request()->routeIs('penyusunan-silabus.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <div style="width: 20px" class="text-center me-2">
                                        <i class="bi bi-file-earmark-medical"></i>
                                    </div>
                                    <span class="sidebar-text">Data Master Silabus</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if(Auth::user()->hasPermission('mahasiswa') || Auth::user()->hasPermission('sertifikasi-mahasiswa') || Auth::user()->hasPermission('ipk'))
                <li class="nav-item mt-2">
                    <a class="nav-link text-white d-flex align-items-center justify-content-between" data-bs-toggle="collapse" href="#mahasiswaDataMenu" role="button" aria-expanded="{{ request()->routeIs('mahasiswa.*') || request()->routeIs('sertifikasi-mahasiswa.*') || request()->routeIs('ipk.*') ? 'true' : 'false' }}" aria-controls="mahasiswaDataMenu">
                        <span>
                            <i class="bi bi-people-fill me-2 text-warning fs-5"></i>
                            <span class="sidebar-text">Data Mahasiswa</span>
                        </span>
                        <i class="bi bi-chevron-down float-end"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('mahasiswa.*') || request()->routeIs('sertifikasi-mahasiswa.*') || request()->routeIs('ipk.*') ? 'show' : '' }}" id="mahasiswaDataMenu">
                        <ul class="nav nav-pills flex-column ms-3 mt-1">
                            @if(Auth::user()->hasPermission('mahasiswa'))
                            <li class="nav-item">
                                <a href="{{ route('mahasiswa.index') }}" class="nav-link text-white {{ request()->routeIs('mahasiswa.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-people me-2 text-info"></i>
                                    <span class="sidebar-text">Data Mahasiswa</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('sertifikasi-mahasiswa'))
                            <li class="nav-item">
                                <a href="{{ route('sertifikasi-mahasiswa.index') }}" class="nav-link text-white {{ request()->routeIs('sertifikasi-mahasiswa.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-patch-check me-2 text-success"></i>
                                    <span class="sidebar-text">Sertifikasi Mahasiswa</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('ipk'))
                            <li class="nav-item">
                                <a href="{{ route('ipk.index') }}" class="nav-link text-white {{ request()->routeIs('ipk.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-award me-2 text-warning"></i>
                                    <span class="sidebar-text">IPK Mahasiswa</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                @if(Auth::user()->hasPermission('dosen') || Auth::user()->hasPermission('ts') || Auth::user()->hasPermission('rekognisi-dosen') || Auth::user()->hasPermission('prestasi-dosen') || Auth::user()->hasPermission('kegiatan-dosen') || Auth::user()->hasPermission('penelitian-dosen') || Auth::user()->hasPermission('pkm-dosen') || Auth::user()->hasPermission('hibah-penelitian'))
                <li class="nav-item mt-2">
                    <a class="nav-link text-white d-flex align-items-center justify-content-between" data-bs-toggle="collapse" href="#manajemenDataMenu" role="button" aria-expanded="{{ request()->routeIs('dosen.*') || request()->routeIs('tendik.*') || request()->routeIs('ts.*') || request()->routeIs('rekognisi-dosen.*') || request()->routeIs('prestasi-dosen.*') || request()->routeIs('kegiatan-dosen.*') || request()->routeIs('kegiatan-tendik.*') || request()->routeIs('penelitian-dosen.*') || request()->routeIs('pkm-dosen.*') || request()->routeIs('hibah-penelitian.*') ? 'true' : 'false' }}" aria-controls="manajemenDataMenu">
                        <span>
                            <i class="bi bi-gear-fill me-2 text-danger fs-5"></i>
                            <span class="sidebar-text">Data Dosen & Tendik</span>
                        </span>
                        <i class="bi bi-chevron-down float-end"></i>
                    </a>
                    <div class="collapse {{ request()->routeIs('dosen.*') || request()->routeIs('tendik.*') || request()->routeIs('ts.*') || request()->routeIs('rekognisi-dosen.*') || request()->routeIs('prestasi-dosen.*') || request()->routeIs('kegiatan-dosen.*') || request()->routeIs('kegiatan-tendik.*') || request()->routeIs('penelitian-dosen.*') || request()->routeIs('pkm-dosen.*') || request()->routeIs('hibah-penelitian.*') ? 'show' : '' }}" id="manajemenDataMenu">
                        <ul class="nav nav-pills flex-column ms-3 mt-1">
                            @if(Auth::user()->hasPermission('dosen'))
                            <li class="nav-item">
                                <a href="{{ route('dosen.index') }}" class="nav-link text-white {{ request()->routeIs('dosen.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-person-badge me-2 text-info"></i>
                                    <span class="sidebar-text">Data Dosen</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('tendik.index') }}" class="nav-link text-white {{ request()->routeIs('tendik.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-person-workspace me-2 text-primary"></i>
                                    <span class="sidebar-text">Data Tendik</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('ts'))
                            <li class="nav-item">
                                <a href="{{ route('ts.index') }}" class="nav-link text-white {{ request()->routeIs('ts.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-calendar me-2 text-success"></i>
                                    <span class="sidebar-text">Data Akademik</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('rekognisi-dosen'))
                            <li class="nav-item">
                                <a href="{{ route('rekognisi-dosen.index') }}" class="nav-link text-white {{ request()->routeIs('rekognisi-dosen.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-award me-2 text-warning"></i>
                                    <span class="sidebar-text">Data Rekognisi Dosen</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('prestasi-dosen'))
                            <li class="nav-item">
                                <a href="{{ route('prestasi-dosen.index') }}" class="nav-link text-white {{ request()->routeIs('prestasi-dosen.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-trophy me-2 text-danger"></i>
                                    <span class="sidebar-text">Data Prestasi Dosen</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('dosen') || Auth::user()->hasPermission('kegiatan-dosen'))
                            <li class="nav-item">
                                <a href="{{ route('kegiatan-dosen.index') }}" class="nav-link text-white {{ request()->routeIs('kegiatan-dosen.*') || request()->routeIs('kegiatan-tendik.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-activity me-2 text-light"></i>
                                    <span class="sidebar-text">Data Kegiatan Dosen</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('tendik') || Auth::user()->hasPermission('kegiatan-tendik'))
                            <li class="nav-item">
                                <a href="{{ route('kegiatan-tendik.index') }}" class="nav-link text-white {{ request()->routeIs('kegiatan-tendik.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-activity me-2 text-info"></i>
                                    <span class="sidebar-text">Data Kegiatan Tendik</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('penelitian-dosen'))
                            <li class="nav-item">
                                <a href="{{ route('penelitian-dosen.index') }}" class="nav-link text-white {{ request()->routeIs('penelitian-dosen.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-journal-text me-2 text-primary"></i>
                                    <span class="sidebar-text">Data Penelitian Dosen</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('pkm-dosen'))
                            <li class="nav-item">
                                <a href="{{ route('pkm-dosen.index') }}" class="nav-link text-white {{ request()->routeIs('pkm-dosen.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-people me-2 text-info"></i>
                                    <span class="sidebar-text">Data PKM Dosen</span>
                                </a>
                            </li>
                            @endif
                            @if(Auth::user()->hasPermission('hibah-penelitian'))
                            <li class="nav-item">
                                <a href="{{ route('hibah-penelitian.index') }}" class="nav-link text-white {{ request()->routeIs('hibah-penelitian.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                                    <i class="bi bi-cash-coin me-2 text-success"></i>
                                    <span class="sidebar-text">Data Hibah Penelitian</span>
                                </a>
                            </li>
                            @endif
                        </ul>
                    </div>
                </li>
                @endif

                <li class="nav-item mt-2">
                    <a href="{{ route('kegiatan.index') }}" class="nav-link text-white {{ request()->routeIs('kegiatan.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                        <i class="bi bi-calendar-event-fill me-2 text-info fs-5"></i>
                        <span class="sidebar-text">Manajemen Kegiatan</span>
                    </a>
                </li>

                @if(Auth::user()->level === 'king')
                <li class="nav-item mt-2">
                    <a href="{{ route('hak-akses.index') }}" class="nav-link text-white {{ request()->routeIs('hak-akses.*') ? 'active' : '' }} d-flex align-items-center justify-content-start">
                        <i class="bi bi-shield-lock-fill me-2 text-primary fs-5"></i>
                        <span class="sidebar-text">Hak Akses</span>
                    </a>
                </li>
                @endif
            </ul>
        </div>

        <div class="flex-grow-1 d-flex flex-column">
            <nav class="navbar navbar-light bg-light border-bottom px-4 d-print-none d-flex justify-content-between">
                <span class="navbar-brand mb-0 h6">@yield('title', 'Dashboard')</span>
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('welcome') }}" class="btn btn-outline-secondary btn-sm" style="border-radius: 8px;">
                        <i class="bi bi-globe me-1"></i> Lihat Beranda
                    </a>
                    @auth
                        @if (Auth::user()->foto)
                            <img src="{{ asset('storage/' . Auth::user()->foto) }}" class="rounded-circle border" style="width: 38px; height: 38px; object-fit: cover;" alt="">
                        @else
                            <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center border" style="width: 38px; height: 38px;">
                                <i class="bi bi-person-fill fs-5"></i>
                            </div>
                        @endif
                        <div class="text-start me-2">
                            <span class="d-block fw-bold text-dark mb-0" style="font-size: 0.85rem;">{{ Auth::user()->username }}</span>
                            <span class="badge bg-danger text-uppercase" style="font-size: 9px; font-weight: 600; border-radius: 4px;">{{ Auth::user()->level }}</span>
                        </div>
                        <a href="{{ route('logout') }}" class="btn btn-danger btn-sm" style="border-radius: 8px;">
                            <i class="bi bi-box-arrow-right me-1"></i> Keluar
                        </a>
                    @endauth
                </div>
            </nav>

            <main class="p-4 flex-grow-1">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var sidebar = document.getElementById('sidebar');
        var sidebarHeader = document.getElementById('sidebar-header');
        var sidebarCollapseIcon = document.getElementById('sidebar-collapse-icon');

        // Restore state from local storage
        if (localStorage.getItem('sidebar-state') === 'collapsed') {
            sidebar.classList.add('sidebar-collapsed');
            if (sidebarCollapseIcon) {
                sidebarCollapseIcon.classList.remove('bi-chevron-bar-left');
                sidebarCollapseIcon.classList.add('bi-chevron-bar-right');
            }
        }

        if (sidebarHeader) {
            sidebarHeader.addEventListener('click', function() {
                sidebar.classList.toggle('sidebar-collapsed');
                if (sidebar.classList.contains('sidebar-collapsed')) {
                    localStorage.setItem('sidebar-state', 'collapsed');
                    if (sidebarCollapseIcon) {
                        sidebarCollapseIcon.classList.remove('bi-chevron-bar-left');
                        sidebarCollapseIcon.classList.add('bi-chevron-bar-right');
                    }
                } else {
                    localStorage.setItem('sidebar-state', 'expanded');
                    if (sidebarCollapseIcon) {
                        sidebarCollapseIcon.classList.remove('bi-chevron-bar-right');
                        sidebarCollapseIcon.classList.add('bi-chevron-bar-left');
                    }
                }
            });
        }
    });
    </script>
    @stack('scripts')
</body>
</html>
