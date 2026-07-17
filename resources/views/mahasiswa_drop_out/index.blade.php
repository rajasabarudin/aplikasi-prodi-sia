@extends('layouts.app')

@section('title', 'Tabel Kohort Lulusan & Drop Out')

@section('content')
<style>
    /* Gradient Background for the main page area */
    .page-gradient-bg {
        background: radial-gradient(circle at 10% 20%, rgba(240, 244, 255, 0.8) 0%, rgba(249, 245, 255, 0.8) 90.1%);
        padding: 24px;
        border-radius: 24px;
        border: 1px solid rgba(255, 255, 255, 0.5);
    }

    /* Glassmorphism Card Style */
    .glass-panel {
        background: rgba(255, 255, 255, 0.6) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        border: 1px solid rgba(255, 255, 255, 0.5) !important;
        border-radius: 16px !important;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.04) !important;
        transition: all 0.3s ease;
    }

    /* Gradient Header */
    .gradient-header {
        background: linear-gradient(135deg, #1e293b, #0f172a) !important;
        color: #fff !important;
        border: none !important;
    }

    /* Premium Stats Card */
    .stat-card-total {
        background: linear-gradient(135deg, #6366f1, #4f46e5) !important;
        color: #fff !important;
        border-radius: 14px !important;
        border: none !important;
        box-shadow: 0 8px 24px rgba(99, 102, 241, 0.2) !important;
    }
    
    .stat-card-aktif {
        background: linear-gradient(135deg, #10b981, #059669) !important;
        color: #fff !important;
        border-radius: 14px !important;
        border: none !important;
        box-shadow: 0 8px 24px rgba(16, 185, 129, 0.2) !important;
    }

    .stat-card-do {
        background: linear-gradient(135deg, #f43f5e, #e11d48) !important;
        color: #fff !important;
        border-radius: 14px !important;
        border: none !important;
        box-shadow: 0 8px 24px rgba(244, 63, 94, 0.2) !important;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(99, 102, 241, 0.05) !important;
        transform: scale(1.002);
        transition: all 0.2s;
    }
    
    .table th {
        background-color: #f8fafc;
        color: #334155;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
        padding: 12px 16px;
    }
</style>

<div class="page-gradient-bg mb-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;"><i class="bi bi-people-fill text-primary me-2"></i>Tabel Kohort (Retensi Mahasiswa)</h2>
            <p class="text-muted mb-0">Kelulusan dan Putus Studi (Drop Out) Mahasiswa Reguler (Kriteria C3)</p>
        </div>
        <div class="col-md-4 text-md-end">
            <a href="{{ route('kohort.cetak') }}" target="_blank" class="btn btn-outline-danger rounded-pill px-4 shadow-sm mb-2 mb-md-0">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> Cetak Laporan PDF
            </a>
            <a href="{{ route('obe.index') }}" class="btn btn-outline-dark rounded-pill px-4 shadow-sm">
                <i class="bi bi-arrow-left-circle me-1"></i> Kembali ke OBE
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-4" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    
    @php
        $totalMasuk = collect($kohorts)->sum('jumlah_masuk');
        $totalLulus = collect($kohorts)->sum('jumlah_lulus');
        $totalDO = collect($kohorts)->sum('jumlah_do');
        $totalAktif = collect($kohorts)->sum('jumlah_aktif');
    @endphp

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card stat-card-total h-100">
                <div class="card-body">
                    <h6 class="text-white-50 text-uppercase fw-bold mb-2" style="letter-spacing: 1px; font-size: 0.75rem;">Total Mahasiswa Baru</h6>
                    <h2 class="mb-0 fw-bold">{{ number_format($totalMasuk) }} <small class="fs-6 fw-normal">Orang</small></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-dark text-white rounded-4 border-0 shadow h-100">
                <div class="card-body">
                    <h6 class="text-white-50 text-uppercase fw-bold mb-2" style="letter-spacing: 1px; font-size: 0.75rem;">Total Lulusan</h6>
                    <h2 class="mb-0 fw-bold">{{ number_format($totalLulus) }} <small class="fs-6 fw-normal">Orang</small></h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card stat-card-do h-100">
                <div class="card-body">
                    <h6 class="text-white-50 text-uppercase fw-bold mb-2" style="letter-spacing: 1px; font-size: 0.75rem;">Total Drop Out</h6>
                    <h2 class="mb-0 fw-bold">{{ number_format($totalDO) }} <small class="fs-6 fw-normal">Orang</small></h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Table Card -->
    <div class="row">
        <div class="col-12">
            <div class="card glass-panel">
                <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0 fw-bold text-dark"><i class="bi bi-table text-primary me-2"></i>Matriks Retensi Mahasiswa</h5>
                    <div>
                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill"><i class="bi bi-info-circle me-1"></i>Data Masuk & Lulus otomatis dari sistem</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="text-center" style="width: 20%">Tahun Masuk (TS-i)</th>
                                    <th class="text-center" style="width: 20%">Jumlah Mahasiswa Masuk</th>
                                    <th class="text-center" style="width: 20%">Jumlah Lulus</th>
                                    <th class="text-center" style="width: 20%">Jumlah Drop Out (DO)</th>
                                    <th class="text-center" style="width: 20%">Aksi (Update DO)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($kohorts as $row)
                                <tr>
                                    <td class="text-center fw-bold text-dark fs-5">{{ $row['tahun_masuk'] }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-primary fs-6">{{ $row['jumlah_masuk'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-dark fs-6">{{ $row['jumlah_lulus'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-danger fs-6">{{ $row['jumlah_do'] }}</span>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('kohort.store') }}" method="POST" class="d-flex justify-content-center gap-2">
                                            @csrf
                                            <input type="hidden" name="tahun_masuk" value="{{ $row['tahun_masuk'] }}">
                                            <input type="number" name="jumlah_do" class="form-control form-control-sm text-center" style="width: 80px;" value="{{ $row['jumlah_do'] }}" min="0" required>
                                            <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3"><i class="bi bi-save me-1"></i>Simpan</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="bi bi-folder-x fs-1 d-block mb-2"></i>
                                        Belum ada data PMB untuk diolah menjadi Kohort.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            <div class="alert alert-info mt-4 rounded-4 shadow-sm border-0 d-flex align-items-center">
                <i class="bi bi-lightbulb-fill fs-3 text-info me-3"></i>
                <div>
                    <h6 class="fw-bold mb-1">Catatan Sistem:</h6>
                    <p class="mb-0 small text-muted">Sistem ini bekerja secara otomatis. <strong>Jumlah Masuk</strong> ditarik dari modul <em>Penerimaan Mahasiswa Baru</em>, sedangkan <strong>Jumlah Lulus</strong> ditarik langsung dari modul <em>Tracer Study / Alumni</em> berdasarkan kecocokan Tahun Masuk. Anda hanya perlu menginput angka <strong>Drop Out (DO)</strong>, dan sistem akan langsung menghitung total <strong>Mahasiswa Aktif</strong> pada angkatan tersebut.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
