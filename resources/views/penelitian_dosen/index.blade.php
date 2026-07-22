@extends('layouts.app')

@section('title', 'Data Penelitian Dosen')

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
        overflow: hidden;
    }

    .glass-panel:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.08) !important;
    }

    /* Gradient Header */
    .gradient-header {
        background: linear-gradient(135deg, #1e293b, #0f172a) !important;
        color: #fff !important;
        border: none !important;
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

    /* Glass Table */
    .glass-table {
        background: rgba(255, 255, 255, 0.4) !important;
        border-collapse: separate !important;
        border-spacing: 0 !important;
        border-radius: 14px !important;
        overflow: hidden !important;
        border: 1px solid rgba(255, 255, 255, 0.4) !important;
    }

    .glass-table thead tr th {
        background: rgba(15, 23, 42, 0.9) !important;
        color: #fff !important;
        border: none !important;
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        padding: 14px 10px !important;
    }

    .glass-table tbody tr {
        transition: all 0.2s ease;
    }

    .glass-table tbody tr:hover {
        background: rgba(255, 255, 255, 0.75) !important;
    }

    .glass-table tbody tr td {
        border-bottom: 1px solid rgba(226, 232, 240, 0.6) !important;
        border-top: none !important;
        border-left: none !important;
        border-right: none !important;
    }

    /* Custom Form Inputs for Glassmorphism */
    .glass-input {
        background: rgba(255, 255, 255, 0.7) !important;
        border: 1px solid rgba(226, 232, 240, 0.8) !important;
        border-radius: 10px !important;
        transition: all 0.3s ease;
    }

    .glass-input:focus {
        background: #fff !important;
        border-color: #6366f1 !important;
        box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.15) !important;
    }

    /* Custom Scrollbar for stats lists */
    .custom-scroll::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scroll::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.03);
        border-radius: 10px;
    }
    .custom-scroll::-webkit-scrollbar-thumb {
        background: rgba(0, 0, 0, 0.15);
        border-radius: 10px;
    }
    .custom-scroll::-webkit-scrollbar-thumb:hover {
        background: rgba(0, 0, 0, 0.25);
    }

    /* Styling khusus cetak */
    @media print {
        @page {
            size: landscape;
            margin: 10mm;
        }
        body {
            font-size: 11px;
            color: #000;
            background: #fff;
        }
        .page-gradient-bg {
            background: none !important;
            padding: 0 !important;
            border: none !important;
        }
        .glass-panel {
            background: none !important;
            box-shadow: none !important;
            border: none !important;
        }
        .table {
            width: 100% !important;
            border-collapse: collapse !important;
            font-size: 10px;
        }
        .table th, .table td {
            border: 1px solid #000 !important;
            padding: 4px 5px !important;
            vertical-align: middle !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
        .table th {
            background-color: #212529 !important;
            color: #fff !important;
            text-align: center !important;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9 !important;
        }
        .table-striped tbody tr:nth-of-type(even) {
            background-color: #e9ecef !important;
        }
        .col-lg-9, .col-md-8 {
            width: 100% !important;
            flex: 0 0 100% !important;
            max-width: 100% !important;
        }
    }
</style>

<div class="d-none d-print-block mb-4 text-center">
    <h4 class="fw-bold">Penelitian Dosen Program Studi Sistem Informasi Akuntansi (D3)</h4>
    <h5 class="fw-bold">UBSI Kampus Kota Pontianak</h5>
    <hr>
</div>

<div class="page-gradient-bg">
    <div class="row">
        <!-- Kiri: Statistik (d-print-none) -->
        <div class="col-lg-3 col-md-4 d-print-none mb-4">
            <div class="card glass-panel border-0 mb-3">
                <div class="card-header gradient-header py-3">
                    <h5 class="card-title mb-0 fw-bold"><i class="bi bi-bar-chart-line-fill me-2"></i>Statistik Penelitian</h5>
                </div>
                <div class="card-body">
                    <!-- Total Penelitian -->
                    <div class="mb-4 text-center py-3 stat-card-total">
                        <span class="text-white-50 small d-block mb-1">Total Penelitian</span>
                        <h2 class="fw-bold mb-0 text-white">{{ $totalPenelitian }}</h2>
                    </div>

                    <!-- Berdasarkan Jenis Jurnal -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-primary text-white rounded p-1 px-2 me-2">
                                <i class="bi bi-journal-check"></i>
                            </div>
                            <span class="fw-bold text-dark">Jenis Jurnal</span>
                        </div>
                        <div class="ps-1">
                            @foreach ($jenisJurnalCounts as $jenis => $count)
                                <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                                    <span class="text-truncate text-dark" style="max-width: 160px;" title="{{ $jenis }}">{{ $jenis }}</span>
                                    <span class="badge bg-primary rounded-pill">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Berdasarkan Jenis Penelitian -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-success text-white rounded p-1 px-2 me-2">
                                <i class="bi bi-diagram-3-fill"></i>
                            </div>
                            <span class="fw-bold text-dark">Jenis Penelitian</span>
                        </div>
                        <div class="ps-1">
                            @foreach ($jenisPenelitianCounts as $jenis => $count)
                                <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                                    <span class="text-truncate text-dark" style="max-width: 160px;" title="{{ $jenis }}">{{ $jenis }}</span>
                                    <span class="badge bg-success rounded-pill">{{ $count }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Berdasarkan TS -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-warning text-dark rounded p-1 px-2 me-2">
                                <i class="bi bi-tags-fill"></i>
                            </div>
                            <span class="fw-bold text-dark">Berdasarkan TS</span>
                        </div>
                        <div class="ps-1 custom-scroll" style="max-height: 150px; overflow-y: auto;">
                            @forelse ($labelTsCounts as $labelTs => $count)
                                <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                                    <span class="text-dark">{{ $labelTs }}</span>
                                    <span class="badge bg-warning text-dark rounded-pill">{{ $count }}</span>
                                </div>
                            @empty
                                <span class="text-muted small">Tidak ada data TS</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- Berdasarkan TA -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-info text-dark rounded p-1 px-2 me-2">
                                <i class="bi bi-calendar-event-fill"></i>
                            </div>
                            <span class="fw-bold text-dark">Berdasarkan TA</span>
                        </div>
                        <div class="ps-1 custom-scroll" style="max-height: 150px; overflow-y: auto;">
                            @forelse ($tsCounts as $tsName => $count)
                                <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                                    <span class="text-dark">{{ $tsName }}</span>
                                    <span class="badge bg-info text-dark rounded-pill">{{ $count }}</span>
                                </div>
                            @empty
                                <span class="text-muted small">Tidak ada data</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- Berdasarkan Dosen -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-success text-white rounded p-1 px-2 me-2">
                                <i class="bi bi-person-badge-fill"></i>
                            </div>
                            <span class="fw-bold text-dark">Berdasarkan Dosen</span>
                        </div>
                        <div class="ps-1 custom-scroll" style="max-height: 180px; overflow-y: auto;">
                            @forelse ($dosenCounts as $dosenName => $count)
                                <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                                    <span class="text-truncate text-dark" style="max-width: 150px;" title="{{ $dosenName }}">{{ $dosenName }}</span>
                                    <span class="badge bg-success rounded-pill">{{ $count }}</span>
                                </div>
                            @empty
                                <span class="text-muted small">Tidak ada data</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- Berdasarkan Mahasiswa -->
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-2">
                            <div class="bg-warning text-dark rounded p-1 px-2 me-2">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <span class="fw-bold text-dark">Berdasarkan Mahasiswa</span>
                        </div>
                        <div class="ps-1 custom-scroll" style="max-height: 180px; overflow-y: auto;">
                            @forelse ($mhsCounts as $mhsName => $count)
                                <div class="d-flex justify-content-between text-muted small py-1 border-bottom border-light">
                                    <span class="text-truncate text-dark" style="max-width: 150px;" title="{{ $mhsName }}">{{ $mhsName }}</span>
                                    <span class="badge bg-warning text-dark rounded-pill">{{ $count }}</span>
                                </div>
                            @empty
                                <span class="text-muted small">Tidak ada data</span>
                            @endforelse
                        </div>
                    </div>

                    <!-- Grafik Pai Kolaborasi Dosen & Mahasiswa -->
                    <div class="mt-4 border-top pt-3">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-danger text-white rounded p-1 px-2 me-2" style="background: linear-gradient(135deg, #ec4899, #db2777) !important;">
                                <i class="bi bi-pie-chart-fill"></i>
                            </div>
                            <span class="fw-bold text-dark">Kolaborasi Penelitian</span>
                        </div>
                        <div style="position: relative; height: 180px; width: 100%;">
                            <canvas id="kolaborasiPieChart"></canvas>
                        </div>
                        <div class="text-center mt-2">
                            <span class="badge bg-light text-dark shadow-sm py-2 px-3" style="border-radius: 8px; font-weight: 700; font-size: 11px;">
                                @if($totalPenelitian > 0)
                                    Tingkat Kolaborasi: {{ number_format(($kolaborasiCount / $totalPenelitian) * 100, 1) }}%
                                @else
                                    Tingkat Kolaborasi: 0%
                                @endif
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kanan: Tabel Data -->
        <div class="col-lg-9 col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-3 d-print-none">
                <div>
                    <h1 class="mb-0 fw-bold text-dark" style="letter-spacing: -0.5px;">Data Penelitian Dosen</h1>
                    <p class="text-muted mb-0">Total: <strong>{{ $totalPenelitian }}</strong> penelitian terdaftar</p>
                </div>
            </div>

            <div class="card glass-panel mb-3 d-print-none border-0">
                <div class="card-body py-2">
                    <form method="GET" class="row g-2 align-items-center">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control glass-input" placeholder="Cari dosen, mahasiswa, jurnal, penelitian..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-2">
                            <select name="per_page" class="form-select glass-input" onchange="this.form.submit()" title="Tampilkan baris">
                                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10 Baris</option>
                                <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 Baris</option>
                                <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100 Baris</option>
                                <option value="200" {{ request('per_page') == 200 ? 'selected' : '' }}>200 Baris</option>
                            </select>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <button type="submit" class="btn btn-primary px-3 me-1" style="border-radius:10px;"><i class="bi bi-search"></i> Cari</button>
                            <a href="{{ route('penelitian-dosen.index', array_merge(request()->query(), ['print' => 'all'])) }}" target="_blank" class="btn btn-success px-3 me-1" style="border-radius:10px;"><i class="bi bi-printer"></i> Cetak</a>
                            <button type="button" class="btn btn-primary px-3 me-1" data-bs-toggle="modal" data-bs-target="#tambahPenelitianModal" style="border-radius:10px;">
                                <i class="bi bi-plus-circle"></i> Tambah Penelitian
                            </button>
                            @if (request('search') || request('per_page'))
                                <a href="{{ route('penelitian-dosen.index') }}" class="btn btn-secondary px-3" style="border-radius:10px;">Reset</a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <div class="table-responsive glass-panel border-0">
                <table class="table glass-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 5%;">No</th>
                            <th style="width: 15%;">Dosen Pelaksana</th>
                            <th style="width: 15%;">Anggota (Mahasiswa & Mitra)</th>
                            <th style="width: 20%;">Judul Penelitian</th>
                            <th style="width: 15%;">Jenis Jurnal & Penelitian</th>
                            <th style="width: 15%;">Nama Jurnal</th>
                            <th class="text-center" style="width: 5%;">TA</th>
                            <th class="text-center" style="width: 5%;">Link</th>
                            <th class="text-center d-print-none" style="width: 5%;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penelitian as $item)
                            <tr>
                                <td class="text-center fw-bold text-muted">{{ $penelitian instanceof \Illuminate\Pagination\LengthAwarePaginator ? ($penelitian->currentPage() - 1) * $penelitian->perPage() + $loop->iteration : $loop->iteration }}</td>
                                <td>
                                    @php
                                        $kodes = explode(', ', $item->kode_dosen);
                                        $namas = explode(', ', $item->nama_dosen);
                                    @endphp
                                    @foreach ($kodes as $idx => $kode)
                                        <div class="mb-2">
                                            <strong class="text-dark d-block" style="font-size: 0.95rem;">{{ $namas[$idx] ?? '' }}</strong>
                                            <span class="badge bg-dark-subtle text-dark small" style="font-size: 11px;">Kode: {{ $kode }}</span>
                                        </div>
                                    @endforeach
                                </td>
                                <td>
                                    <!-- Mahasiswa -->
                                    @if ($item->nim_mhs)
                                        <div class="mb-2">
                                            <span class="badge bg-secondary-subtle text-secondary mb-1 fw-bold" style="font-size: 10px;">Mahasiswa</span>
                                            @php
                                                $nims = explode(', ', $item->nim_mhs);
                                                $mhsNamas = explode(', ', $item->nama_mahasiswa);
                                            @endphp
                                            @foreach ($nims as $idx => $nim)
                                                <div class="small text-dark mb-1">
                                                    <i class="bi bi-person-fill text-muted me-1"></i>{{ $mhsNamas[$idx] ?? '' }} <span class="text-muted">({{ $nim }})</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <!-- Mitra -->
                                    @if ($item->anggota_mitra)
                                        <div>
                                            <span class="badge bg-dark-subtle text-dark mb-1 fw-bold" style="font-size: 10px;">Mitra</span>
                                            @php
                                                $mitras = explode(', ', $item->anggota_mitra);
                                            @endphp
                                            @foreach ($mitras as $mitra)
                                                <div class="small text-dark mb-1">
                                                    <i class="bi bi-building text-muted me-1"></i>{{ $mitra }}
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif

                                    @if (!$item->nim_mhs && !$item->anggota_mitra)
                                        <span class="text-muted small">-</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $item->judul_penelitian }}</div>
                                </td>
                                <td>
                                    <span class="badge bg-primary-subtle text-primary mb-1 custom-badge-pill" style="font-size: 10px;">{{ $item->jenis_jurnal }}</span>
                                    <div class="small text-muted mt-1"><i class="bi bi-gear-fill me-1"></i>{{ $item->jenis_penelitian }}</div>
                                </td>
                                <td class="fw-semibold text-dark">{{ $item->nama_jurnal }}</td>
                                <td class="text-center fw-bold text-dark">{{ $item->ts->tahun_sekarang ?? '-' }}</td>
                                <td>
                                    <div class="d-flex flex-column gap-1">
                                        @if ($item->berkas_sertifikat)
                                            <a href="{{ $item->berkas_sertifikat }}" target="_blank" class="badge bg-success-subtle text-success text-decoration-none py-1 px-2" title="Sertifikat" style="font-size:10px;"><i class="bi bi-link-45deg"></i> Sertifikat</a>
                                        @endif
                                        @if ($item->berkas_paper)
                                            <a href="{{ $item->berkas_paper }}" target="_blank" class="badge bg-primary-subtle text-primary text-decoration-none py-1 px-2" title="Paper" style="font-size:10px;"><i class="bi bi-link-45deg"></i> Paper</a>
                                        @endif
                                        @if ($item->proposal)
                                            <a href="{{ $item->proposal }}" target="_blank" class="badge bg-info-subtle text-info text-decoration-none py-1 px-2" title="Proposal" style="font-size:10px;"><i class="bi bi-link-45deg"></i> Proposal</a>
                                        @endif
                                        @if ($item->laporan)
                                            <a href="{{ $item->laporan }}" target="_blank" class="badge bg-warning-subtle text-warning text-decoration-none py-1 px-2" title="Laporan" style="font-size:10px;"><i class="bi bi-link-45deg"></i> Laporan</a>
                                        @endif
                                        @if ($item->lainnya)
                                            <a href="{{ $item->lainnya }}" target="_blank" class="badge bg-secondary-subtle text-secondary text-decoration-none py-1 px-2" title="Lainnya" style="font-size:10px;"><i class="bi bi-link-45deg"></i> Lainnya</a>
                                        @endif
                                        @if (!$item->berkas_sertifikat && !$item->berkas_paper && !$item->proposal && !$item->laporan && !$item->lainnya)
                                            <span class="text-muted small text-center">-</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center d-print-none">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('penelitian-dosen.show', $item) }}" class="btn btn-sm btn-info text-white" style="border-radius: 6px 0 0 6px;"><i class="bi bi-eye"></i></a>
                                        <a href="{{ route('penelitian-dosen.edit', $item) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                                        <form action="{{ route('penelitian-dosen.destroy', $item) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" style="border-radius: 0 6px 6px 0;"><i class="bi bi-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted py-4">Belum ada data penelitian dosen.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($penelitian instanceof \Illuminate\Pagination\LengthAwarePaginator)
                <div class="d-flex justify-content-center d-print-none mt-4">
                    {{ $penelitian->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Tambah Penelitian Dosen -->
<div class="modal fade" id="tambahPenelitianModal" tabindex="-1" aria-labelledby="tambahPenelitianModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content glass-panel border-0">
            <form action="{{ route('penelitian-dosen.store') }}" method="POST">
                @csrf
                <div class="modal-header gradient-header">
                    <h5 class="modal-title fw-bold" id="tambahPenelitianModalLabel"><i class="bi bi-plus-circle-fill me-2"></i>Tambah Penelitian Dosen</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <!-- Baris 1: Jurnal & Penelitian -->
                        <div class="col-md-4 mb-3">
                            <label for="modal_jenis_jurnal" class="form-label fw-semibold">Jenis Jurnal <span class="text-danger">*</span></label>
                            <select name="jenis_jurnal" id="modal_jenis_jurnal" class="form-select glass-input" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Jurnal Nasional">Jurnal Nasional</option>
                                <option value="Jurnal Nasional Terakreditasi (SINTA)">Jurnal Nasional Terakreditasi (SINTA)</option>
                                <option value="Jurnal Internasional">Jurnal Internasional</option>
                                <option value="Jurnal Internasional Bereputasi (Scopus/WoS)">Jurnal Internasional Bereputasi (Scopus/WoS)</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_jenis_penelitian" class="form-label fw-semibold">Jenis Penelitian <span class="text-danger">*</span></label>
                            <select name="jenis_penelitian" id="modal_jenis_penelitian" class="form-select glass-input" required>
                                <option value="">-- Pilih Jenis Penelitian --</option>
                                <option value="Penelitian Mandiri">Penelitian Mandiri</option>
                                <option value="Publikasi Karya Ilmiah hasil Penelitian">Publikasi Karya Ilmiah hasil Penelitian</option>
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="modal_ts_id" class="form-label fw-semibold">Pilihan TA <span class="text-danger">*</span></label>
                            <select name="ts_id" id="modal_ts_id" class="form-select glass-input" required>
                                <option value="">-- Pilih TA --</option>
                                @foreach ($tsList as $ts)
                                    <option value="{{ $ts->id }}">{{ $ts->tahun_sekarang }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Baris 2: Nama & Link Jurnal -->
                        <div class="col-md-6 mb-3">
                            <label for="modal_nama_jurnal" class="form-label fw-semibold">Nama Jurnal <span class="text-danger">*</span></label>
                            <input type="text" name="nama_jurnal" id="modal_nama_jurnal" class="form-control glass-input" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="modal_link_jurnal" class="form-label fw-semibold">Link Jurnal</label>
                            <input type="url" name="link_jurnal" id="modal_link_jurnal" class="form-control glass-input" placeholder="https://...">
                        </div>

                        <!-- Section: Dosen (Multiple) -->
                        <div class="col-12 mb-3 border-top pt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0 fw-bold text-primary"><i class="bi bi-person-badge me-1"></i>Dosen Pelaksana <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-sm btn-outline-primary" id="btn-add-dosen" style="border-radius:8px;"><i class="bi bi-plus-circle"></i> Tambah Dosen</button>
                            </div>
                            <div id="dosen-rows-container">
                                <div class="row g-2 mb-2 dosen-row align-items-center">
                                    <div class="col-md-5">
                                        <select name="kode_dosen[]" class="form-select select-kode-dosen glass-input" data-route="{{ route('penelitian-dosen.get-dosen', '') }}/" required>
                                            <option value="">-- Pilih Kode Dosen --</option>
                                            @foreach ($dosens as $dosen)
                                                <option value="{{ $dosen->kode_dosen }}">
                                                    {{ $dosen->kode_dosen }} - {{ $dosen->nama_dosen }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="nama_dosen[]" class="form-control input-nama-dosen glass-input" readonly required placeholder="Nama Dosen">
                                    </div>
                                    <div class="col-md-2">
                                        <!-- Baris pertama tidak bisa dihapus -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Mahasiswa (Multiple) -->
                        <div class="col-12 mb-3 border-top pt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0 fw-bold text-secondary"><i class="bi bi-people me-1"></i>Anggota Mahasiswa (Opsional)</label>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="btn-add-mahasiswa" style="border-radius:8px;"><i class="bi bi-plus-circle"></i> Tambah Mahasiswa</button>
                            </div>
                            <div id="mahasiswa-rows-container">
                                <div class="row g-2 mb-2 mahasiswa-row align-items-center">
                                    <div class="col-md-5">
                                        <input type="text" name="nim_mhs[]" class="form-control input-nim-mhs glass-input" data-route="{{ route('penelitian-dosen.get-mahasiswa', '') }}/" placeholder="Masukkan NIM...">
                                    </div>
                                    <div class="col-md-5">
                                        <input type="text" name="nama_mahasiswa[]" class="form-control input-nama-mahasiswa glass-input" readonly placeholder="Nama Mahasiswa">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger w-100 btn-remove-mahasiswa" style="border-radius:8px;"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Section: Mitra (Multiple) -->
                        <div class="col-12 mb-3 border-top pt-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <label class="form-label mb-0 fw-bold text-dark"><i class="bi bi-building me-1"></i>Anggota Mitra (Opsional)</label>
                                <button type="button" class="btn btn-sm btn-outline-dark" id="btn-add-mitra" style="border-radius:8px;"><i class="bi bi-plus-circle"></i> Tambah Mitra</button>
                            </div>
                            <div id="mitra-rows-container">
                                <div class="row g-2 mb-2 mitra-row align-items-center">
                                    <div class="col-md-10">
                                        <input type="text" name="anggota_mitra[]" class="form-control glass-input" placeholder="Masukkan Nama Mitra (Instansi/Perusahaan/Perorangan)...">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger w-100 btn-remove-mitra" style="border-radius:8px;"><i class="bi bi-trash"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Baris Dokumen Pendukung (Link) -->
                        <div class="col-12 border-top pt-3">
                            <label class="form-label fw-bold text-success mb-2"><i class="bi bi-link-45deg me-1"></i>Tautan Berkas Pendukung</label>
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="modal_berkas_sertifikat" class="form-label small">Link Sertifikat</label>
                                    <input type="url" name="berkas_sertifikat" id="modal_berkas_sertifikat" class="form-control glass-input" placeholder="https://...">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="modal_berkas_paper" class="form-label small">Link Paper</label>
                                    <input type="url" name="berkas_paper" id="modal_berkas_paper" class="form-control glass-input" placeholder="https://...">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="modal_proposal" class="form-label small">Link Proposal</label>
                                    <input type="url" name="proposal" id="modal_proposal" class="form-control glass-input" placeholder="https://...">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="modal_laporan" class="form-label small">Link Laporan</label>
                                    <input type="url" name="laporan" id="modal_laporan" class="form-control glass-input" placeholder="https://...">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="modal_lainnya" class="form-label small">Link Lainnya</label>
                                    <input type="url" name="lainnya" id="modal_lainnya" class="form-control glass-input" placeholder="https://...">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid rgba(0,0,0,0.05);">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal" style="border-radius:10px;">Batal</button>
                    <button type="submit" class="btn btn-primary px-4" style="border-radius:10px; background: linear-gradient(135deg, #4f46e5, #3b82f6); border:none;">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Inisialisasi Grafik Pai Kolaborasi Dosen & Mahasiswa
    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('kolaborasiPieChart').getContext('2d');
        var kolaborasiCount = {{ $kolaborasiCount }};
        var nonKolaborasiCount = {{ $nonKolaborasiCount }};
        
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Kolaborasi dengan Mahasiswa', 'Hanya Dosen (Tanpa Mahasiswa)'],
                datasets: [{
                    data: [kolaborasiCount, nonKolaborasiCount],
                    backgroundColor: ['#ec4899', '#6366f1'], // Pink untuk Kolaborasi, Indigo untuk Non-Kolaborasi
                    borderWidth: 2,
                    borderColor: 'rgba(255, 255, 255, 0.6)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(15, 23, 42, 0.9)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        padding: 10,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                var label = context.label || '';
                                var value = context.raw || 0;
                                var total = kolaborasiCount + nonKolaborasiCount;
                                var percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                return ' ' + label + ': ' + value + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
    });
    // Fungsi untuk memasang event listener autocomplete Dosen pada baris tertentu
    function attachDosenEvents(row) {
        var select = row.querySelector('.select-kode-dosen');
        var input = row.querySelector('.input-nama-dosen');
        
        select.addEventListener('change', function() {
            var kode = this.value;
            if (kode) {
                var route = this.getAttribute('data-route');
                fetch(route + kode)
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        input.value = data.nama_dosen;
                    })
                    .catch(function() {
                        input.value = '';
                    });
            } else {
                input.value = '';
            }
        });

        var removeBtn = row.querySelector('.btn-remove-dosen');
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                row.remove();
            });
        }
    }

    // Fungsi untuk memasang event listener autocomplete Mahasiswa pada baris tertentu
    function attachMahasiswaEvents(row) {
        var nimInput = row.querySelector('.input-nim-mhs');
        var namaInput = row.querySelector('.input-nama-mahasiswa');
        
        nimInput.addEventListener('input', function() {
            var nim = this.value.trim();
            if (nim.length >= 3) {
                var route = this.getAttribute('data-route');
                fetch(route + nim)
                    .then(function(res) { return res.json(); })
                    .then(function(data) {
                        if(data) {
                            namaInput.value = data.nama;
                        } else {
                            namaInput.value = '';
                        }
                    })
                    .catch(function() {
                        namaInput.value = '';
                    });
            } else {
                namaInput.value = '';
            }
        });

        var removeBtn = row.querySelector('.btn-remove-mahasiswa');
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                row.remove();
            });
        }
    }

    // Fungsi pasang event Hapus Mitra
    function attachMitraEvents(row) {
        var removeBtn = row.querySelector('.btn-remove-mitra');
        if (removeBtn) {
            removeBtn.addEventListener('click', function() {
                row.remove();
            });
        }
    }

    // Daftarkan event pada baris awal
    document.querySelectorAll('.dosen-row').forEach(function(row) {
        attachDosenEvents(row);
    });
    document.querySelectorAll('.mahasiswa-row').forEach(function(row) {
        attachMahasiswaEvents(row);
    });
    document.querySelectorAll('.mitra-row').forEach(function(row) {
        attachMitraEvents(row);
    });

    // Aksi Tambah Baris Dosen
    document.getElementById('btn-add-dosen').addEventListener('click', function() {
        var container = document.getElementById('dosen-rows-container');
        var newRow = document.createElement('div');
        newRow.className = 'row g-2 mb-2 dosen-row align-items-center';
        newRow.innerHTML = `
            <div class="col-md-5">
                <select name="kode_dosen[]" class="form-select select-kode-dosen glass-input" data-route="{{ route('penelitian-dosen.get-dosen', '') }}/" required>
                    <option value="">-- Pilih Kode Dosen --</option>
                    @foreach ($dosens as $dosen)
                        <option value="{{ $dosen->kode_dosen }}">
                            {{ $dosen->kode_dosen }} - {{ $dosen->nama_dosen }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-5">
                <input type="text" name="nama_dosen[]" class="form-control input-nama-dosen glass-input" readonly required placeholder="Nama Dosen">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 btn-remove-dosen" style="border-radius:8px;"><i class="bi bi-trash"></i></button>
            </div>
        `;
        container.appendChild(newRow);
        attachDosenEvents(newRow);
    });

    // Aksi Tambah Baris Mahasiswa
    document.getElementById('btn-add-mahasiswa').addEventListener('click', function() {
        var container = document.getElementById('mahasiswa-rows-container');
        var newRow = document.createElement('div');
        newRow.className = 'row g-2 mb-2 mahasiswa-row align-items-center';
        newRow.innerHTML = `
            <div class="col-md-5">
                <input type="text" name="nim_mhs[]" class="form-control input-nim-mhs glass-input" data-route="{{ route('penelitian-dosen.get-mahasiswa', '') }}/" placeholder="Masukkan NIM...">
            </div>
            <div class="col-md-5">
                <input type="text" name="nama_mahasiswa[]" class="form-control input-nama-mahasiswa glass-input" readonly placeholder="Nama Mahasiswa">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 btn-remove-mahasiswa" style="border-radius:8px;"><i class="bi bi-trash"></i></button>
            </div>
        `;
        container.appendChild(newRow);
        attachMahasiswaEvents(newRow);
    });

    // Aksi Tambah Baris Mitra
    document.getElementById('btn-add-mitra').addEventListener('click', function() {
        var container = document.getElementById('mitra-rows-container');
        var newRow = document.createElement('div');
        newRow.className = 'row g-2 mb-2 mitra-row align-items-center';
        newRow.innerHTML = `
            <div class="col-md-10">
                <input type="text" name="anggota_mitra[]" class="form-control glass-input" placeholder="Masukkan Nama Mitra...">
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 btn-remove-mitra" style="border-radius:8px;"><i class="bi bi-trash"></i></button>
            </div>
        `;
        container.appendChild(newRow);
        attachMitraEvents(newRow);
    });

    document.getElementById('modal_jenis_penelitian').addEventListener('change', function() {
        var val = this.value;
        var prop = document.getElementById('modal_proposal');
        var lap = document.getElementById('modal_laporan');
        
        if(val === 'Penelitian Mandiri') {
            prop.setAttribute('required', 'required');
            lap.setAttribute('required', 'required');
            document.querySelector('label[for="modal_proposal"]').innerHTML = 'Link Proposal <span class="text-danger">*</span>';
            document.querySelector('label[for="modal_laporan"]').innerHTML = 'Link Laporan <span class="text-danger">*</span>';
        } else {
            prop.removeAttribute('required');
            lap.removeAttribute('required');
            document.querySelector('label[for="modal_proposal"]').innerHTML = 'Link Proposal';
            document.querySelector('label[for="modal_laporan"]').innerHTML = 'Link Laporan';
        }
    });
</script>
@endpush
@endsection
